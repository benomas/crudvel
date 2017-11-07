<?php

namespace App\Models;

use Carbon\Carbon;
use Cartalyst\Sentinel\Checkpoints\{NotActivatedException,ThrottlingException};
use Cartalyst\Sentinel\Laravel\Facades\{Activation,Sentinel};
use Frontface\Models\BaseModel;
use Illuminate\Support\Facades\{View,App,Session,Validator,Response};
use Illuminate\Support\Str;
use App\Models\{ActivityType, Document, Gender, Infogram, Level, Review, Sublevel, SublevelType, Video};
use HTML2PDF;

class User extends BaseModel{

    protected $fillable = [
        'username','first_name', 'last_name', 'email', 'password', 'deadline','photo',"tree"
    ]; 

    protected $dates = [
        'created_at',
        'updated_at',
        'last_login'
    ];

    public function __construct($attributes = array())  {
        parent::__construct($attributes);
    }
//Relationships

    public function roles(){
        return $this->belongsToMany("App\Models\Role", "role_users");
    }
//End Relationships

//Non standar Relationships

    public function rolesPermissions()
    {
        return $this->manyToManyToMany("roles","permissions","App\Models\Permission");
    }

    public function activityUser(){
        return $this->hasMany("App\Models\ActivityUser");
    }

    public function sublevelUsers(){
        return $this->hasMany("App\Models\SublevelUser");
    }

//End Non standar Relationships
// Scopes

    public function scopeHidden($query){
        $query->whereHas('roles',function($query){
            $query->whereNotIn("roles.slug",["root"]);
        })->orWhere(function($query){
            $query->doesntHave('roles');
        });
    }

    public function scopeResourceActionPermission($query,$permission){
        $query->whereHas("roles",function($query) use($permission){
            $query->whereHas("permissions",function($query) use($permission){
                $query->where("permissions.slug",$permission);
            });
        });
    }

    public function scopeWithRole($query,$role){
        $query->whereHas("roles",function($query) use($role){
            $query->where("roles.slug",$role);
        });
    }

    public function scopeWithRoles($query,$roles){
        $query->whereHas("roles",function($query) use($roles){
            $query->whereIn("roles.slug",$roles);
        });
    }

    public function scopeWithRoleIds($query,$roles){
        $query->whereHas("roles",function($query) use($roles){
            $query->whereIn("roles.id",$roles);
        });
    }

// End Scopes

    public static function getLogginPropertys(){
        return self::staticloginNames;
    }

    public static function loginCredentials($request){
        $loginNames = self::$staticloginNames;
        $loginTest = ["password"=>$request->input("password")];
        foreach ($loginNames as $loginName) {
            if($request->input($loginName)){
                $loginTest["login"]=$request->input($loginName);
                return $loginTest;
            }
        }
        return $loginTest;
    }

    public function payment() {
        return Payment::where('user_id', $this->id)->orderBy('id', 'desc')->first();
    }

    public static function addNormal($data){
        $validation = Validator::make($data, [
            "username" => "required_without:email|unique:users,email|unique:users,username",
            "email"    => "required_without:username|email|unique:users,username|unique:users,email",
            "active"   => "boolean",
        ]);

        if($validation->fails()){
            $errors = $validation->errors()->getMessages();

            return $errors;
        }

        if(!isset($data["deadline"])){
            $date = Carbon::now();
            $date = $date->addMonths(3);

            $data["deadline"] = $date->toDateString();
        }

        try{
            $user = Sentinel::register($data);

            $activation = Activation::create($user);

            Activation::complete($user, $activation->code);

            $user = User::find($user->id);
            $user->is_manager = 0;
            $user->is_facebook = 0;
            $user->deadline = $data["deadline"];
            $user->save();

            return $user;
        }catch (\Exception $e){
            return false;
        }
    }

    public static function addFacebook($data){
        if(!isset($data["password"]) || !$data["password"]){
            $data["password"] = Str::random(12);
        }

        $validation = Validator::make($data, User::facebook_rules());

        if($validation->fails())
            return $validation->errors()->getMessages();

        if(!isset($data["deadline"])){
            $date = Carbon::now();
            $date = $date->addMonths(3);

            $data["deadline"] = $date->toDateString();
        }

        try{
            $user = Sentinel::register($data);

            $activation = Activation::create($user);

            Activation::complete($user, $activation->code);

            $user = User::find($user->id);
            $user->is_manager = 0;
            $user->is_facebook = 1;
            $user->facebook_id = $data["facebook_id"];
            $user->deadline = $data["deadline"];

            $now = Carbon::now();
            $last_login = $now->subMinute(5);

            $user->last_login = $last_login;
            $user->save();

            return $user;
        }catch (\Exception $e){
            return false;
        }
    }

    public static function normalLogin($email, $password){
        $credentials = [
            'email'    => $email,
            'password' => $password,
        ];

        try{
            $authenticate = Sentinel::authenticate($credentials);

            if($authenticate){
                $user = User::find($authenticate->id);

                $user->token = Str::random(16);
                $user->save();

                Session::put("user", $user);

                return $user;
            }

            return false;
        }catch (NotActivatedException $e){
            return false;
        }catch (ThrottlingException $e){
            return false;
        }
    }

    public static function betterwareLogin($user, $password){

        $data_login = [
           'cmd'      => 'login',
           'usuario'  => strtolower($user),  // USUARIO
           'password' => $password            // PASSWORD DEL USUARIO
        ];
        $client = curl_init(config("project.betterwareUrlApi"));
        curl_setopt($client,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($client,CURLOPT_POST,true);
        curl_setopt($client, CURLOPT_POSTFIELDS,$data_login);

        $response = curl_exec($client);
        $json     = json_decode($response);
        return empty($json->data->token)?null:$json->data->token;
    }

    public static function facebookLogin($facebook_id){
        $user = User::where("facebook_id", $facebook_id)->first();

        if(!$user){
            return false;
        }

        $sentinel_user = Sentinel::findById($user->id);

        Sentinel::login($sentinel_user);

        $user->token = Str::random(16);
        $user->save();

        Session::put("user", $user);

        return $user;
    }

    public function progress(){
        return 0;
        $nd = Document::where("id", ">", 0)->count();
        $ni = Infogram::where("id", ">", 0)->count();
        $nv = Video::where("id", ">", 0)->count();
        $nr = Review::where("id", ">", 0)->count();

        $np = Progress::where("user_id", $this->id)->count();

        $this->advance = round($np * 100 / ($nd + $ni + $nv + $nr));
        $this->save();

        return round($np * 100 / ($nd + $ni + $nv + $nr));
    }

    public function createPDF($sublevelId,$mode="file"){

        if(!($sublevel = Sublevel::id($sublevelId)->generateConstancy()->actives())->count())
            return $this->webNotFound();

        $sublevelsUsers=[];
        $user    = $this;
        $role_id = $user->roles()->first()->id;
        $sublevel=$sublevel->first();
        $sublevelTree= $sublevel->getChildrenTree($user,$role_id,$sublevelsUsers);
        
        if(!$sublevelTree || !count($sublevelTree))
            return $this->webNotFound();

        $sublevelTree=$sublevelTree[$sublevel->sublevel_slug];
        
        if($sublevelTree["cAct"]<$sublevelTree["tAct"])
            return $this->webFailResponse(["status"=>"No completado aun","statusMessage"=>"no se ha completado aun el ".$sublevel->level->singular_name]);
        
        if(file_exists(($fullPath=($path=public_path()."/diplomas/".$user->roles()->first()->slug."/$user->id/contancy/".$sublevelTree["level"]["level_slug"])."/".$sublevelTree["sublevel_slug"].".pdf")))
            return $mode==="file"?Response::download($fullPath):$fullPath;
        
        $html = View::make("public.diploma",[
            "user"            =>$user,
            "sublevel"        =>$sublevel,
            "sublevelTree"    =>$sublevelTree,
            "imgBackground"   =>asset("/img/constancy/".$user->roles()->first()->slug."-".$sublevelTree["sublevel_slug"].".jpg"),
        ]);

        $html2pdf = new HTML2PDF('L','A4','fr', true, 'UTF-8',  array(0, 0, 0, 0));

        $html2pdf->setDefaultFont('Helvetica');
        if(!file_exists($path))
            mkdir($path, 0775, true);
        $html2pdf->WriteHTML($html);
        $html2pdf->Output($fullPath,'F');
        return $mode==="file"?Response::download($fullPath):$fullPath;
    }

// validations
    public static function facebook_rules(){
        return [
            "email"       => "required|email",
            "first_name"  => "required",
            "facebook_id" => "required"
        ];
    }
//End validations
// Others

    public function isRoot(){
        return $this->roles()->withRoot()->count();
    }

    public function isAdmin(){
        return $this->roles()->withAdmin()->count();
    }

    public function isManager(){
        return $this->roles()->withManager()->count();
    }

    public function isDistribuitor(){
        return $this->roles()->withDistribuitor()->count();
    }

    public function inRoles(...$roles){
        return $this->roles()->inRoles($roles)->count();
    }
// End others
}

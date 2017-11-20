<?php

namespace Crudvel\Models;

use Carbon\Carbon;
use Cartalyst\Sentinel\Checkpoints\NotActivatedException;
use Cartalyst\Sentinel\Checkpoints\ThrottlingException;
use Cartalyst\Sentinel\Laravel\Facades\Activation;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Crudvel\Models\BaseModel;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;
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
        return $this->belongsToMany("Crudvel\Models\Role", "role_users");
    }
//End Relationships

//Non standar Relationships

    public function rolesPermissions()
    {
        return $this->manyToManyToMany("roles","permissions","Crudvel\Models\Permission");
    }

    public function activityUser(){
        return $this->hasMany("Crudvel\Models\ActivityUser");
    }

    public function sublevelUsers(){
        return $this->hasMany("Crudvel\Models\SublevelUser");
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

// Others

    public function isRoot(){
        return $this->roles()->withRoot()->count();
    }

    public function isAdmin(){
        return $this->roles()->withAdmin()->count();
    }

    public function inRoles(...$roles){
        return $this->roles()->inRoles($roles)->count();
    }
// End others
}

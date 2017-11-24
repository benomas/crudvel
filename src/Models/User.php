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
        'username','first_name', 'last_name', 'email', 'password'
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

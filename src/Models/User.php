<?php namespace Crudvel\Models;

use Carbon\Carbon;
use Crudvel\Models\BaseModel;

class User extends BaseModel{

  protected $fillable = [
    "active",
    "email",
    "first_name",
    "last_name",
    "password",
    "username",
  ];

  protected $dates = [
    "created_at",
    "updated_at",
    "last_login"
  ];

  public function __construct($attributes = array())  {
    parent::__construct($attributes);
  }
//Relationships

  public function roles(){
    return $this->belongsToMany("App\Models\Role", "role_user");
  }
//End Relationships

//Non standar Relationships

  public function rolesPermissions()
  {
    return $this->manyToManyToMany("roles","permissions","App\Models\Permission");
  }

  public function rolesroles()
  {
    return $this->manyToManyToMany("roles","roles","App\Models\Role");
  }

  public function sectionPermissions()
  {
    return $this->rolesPermissions()->secctionPermissions();
  }

  public function resourcePermissions()
  {
    return $this->rolesPermissions()->resourcePermissions();
  }

  public function actionePermissions()
  {
    return $this->rolesPermissions()->actionePermissions();
  }

  public function fieldPermissions()
  {
    return $this->rolesPermissions()->fieldPermissions();
  }

  public function specialPermissions()
  {
    return $this->rolesPermissions()->specialPermissions();
  }

//End Non standar Relationships
// Scopes

  public function scopeHidden($query){
    $query->whereHas("roles",function($query){
      $query->whereNotIn("roles.slug",["root"]);
    })->orWhere(function($query){
      $query->doesntHave("roles");
    });
  }

  public function scopeSectionPermission($query,$section){
    $query->whereHas("roles",function($query) use($section){
      $query->whereHas("permissions",function($query) use($section){
        $query->section($section);
      });
    });
  }

  public function scopeResourcePermission($query,$resource){
    $query->whereHas("roles",function($query) use($resource){
      $query->whereHas("permissions",function($query) use($resource){
        $query->resource($resource);
      });
    });
  }

  public function scopeActionPermission($query,$action){
    $query->whereHas("roles",function($query) use($action){
      $query->whereHas("permissions",function($query) use($action){
        $query->action($action);
      });
    });
  }

  public function scopeFieldPermission($query,$field){
    $query->whereHas("roles",function($query) use($field){
      $query->whereHas("permissions",function($query) use($field){
        $query->field($field);
      });
    });
  }

  public function scopeSpecialPermission($query,$special){
    $query->whereHas("roles",function($query) use($special){
      $query->whereHas("permissions",function($query) use($special){
        $query->special($special);
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

  public function scopeWithUserName($query,$username){
    $query->where($this->getTable().".username",$username);
  }

  public function scopeWithEmail($query,$email){
    $query->where($this->getTable().".email",$email);
  }

  public function scopeGeneralOwner($query,$userId){
    $this->scopeHidden($query);
  }

  public function scopeParticularOwner($query,$userId){
    $query->where($this->getTable().".id", $userId);
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

  public function __call($method, $parameters)
  {
    $checkForRole = explode("-",kebab_case($method));
    if(count($checkForRole)>2 && head($checkForRole)==="has" && last($checkForRole)==="role"){
      array_pull($checkForRole, (count($checkForRole)-1));
      array_pull($checkForRole, 0);
      $roleToFind =  implode("_",$checkForRole);
      return $this->inRoles($roleToFind);
    }
    return is_callable(["parent", "__call"]) ? parent::__call($method, $parameters) : null;
  }
// End others
}

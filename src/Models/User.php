<?php namespace Crudvel\Models;

use Carbon\Carbon;

class User extends \Customs\Crudvel\Models\BaseModel{

  use \Staudenmeir\EloquentHasManyDeep\HasRelationships;

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

// [Relationships]
  public function roles(){
    return $this->belongsToMany("App\Models\Role");
  }

  public function permissions(){
    return $this->hasManyDeep('App\Models\Permission', ['role_user', 'App\Models\Role', 'permission_role']);
  }

  public function rolesroles()
  {/*
    return $this->hasManyDeep(
      'App\Models\Role',
      ['role_user as aaa', 'App\Models\Role as bbb', 'role_role as ccc']
    );*/
    return $this->manyToManyToMany('roles','roles',"App\Models\Role");
  }
// [End Relationships]

//Non standar Relationships
  //To be deprecated
  public function rolesPermissions(){
    return $this->manyToManyToMany('roles',"permissions","App\Models\Permission");
  }

  public function sectionPermissions()
  {
    return $this->permissions()->secctionPermissions();
  }

  public function resourcePermissions()
  {
    return $this->permissions()->resourcePermissions();
  }

  public function actionePermissions()
  {
    return $this->permissions()->actionePermissions();
  }

  public function fieldPermissions()
  {
    return $this->permissions()->fieldPermissions();
  }

  public function specialPermissions()
  {
    return $this->permissions()->specialPermissions();
  }
//End Non standar Relationships

// [Scopes]
  public function scopeHidden($query){
    return $query->where(function($query){
      $query->whereHas('roles',function($query){
        $query->hidden();
      });
    })->orWhere(function($query){
      $query->doesntHave('roles');
    });
  }

  public function scopeSectionPermission($query,$section){
    $query->whereHas('roles',function($query) use($section){
      $query->whereHas("permissions",function($query) use($section){
        $query->section($section);
      });
    });
  }

  public function scopeResourcePermission($query,$resource){
    $query->whereHas('roles',function($query) use($resource){
      $query->whereHas("permissions",function($query) use($resource){
        $query->resource($resource);
      });
    });
  }

  public function scopeActionPermission($query,$action){
    $query->whereHas('roles',function($query) use($action){
      $query->whereHas("permissions",function($query) use($action){
        $query->action($action);
      });
    });
  }

  public function scopeFieldPermission($query,$field){
    $query->whereHas('roles',function($query) use($field){
      $query->whereHas("permissions",function($query) use($field){
        $query->field($field);
      });
    });
  }

  public function scopeSpecialPermission($query,$special){
    $query->whereHas('roles',function($query) use($special){
      $query->whereHas("permissions",function($query) use($special){
        $query->special($special);
      });
    });
  }

  public function scopeWithRole($query,$role){
    $query->whereHas('roles',function($query) use($role){
      $query->where("roles.slug",$role);
    });
  }

  public function scopeWithRoles($query,$roles){
    $query->whereHas('roles',function($query) use($roles){
      $query->whereIn("roles.slug",$roles);
    });
  }

  public function scopeWithRoleIds($query,$roles){
    $query->whereHas('roles',function($query) use($roles){
      $query->whereIn("roles.id",$roles);
    });
  }

  public function scopeWithUserName($query,$username){
    $query->where($this->preFixed('username'),$username);
  }

  public function scopeWithEmail($query,$email){
    $query->where($this->preFixed('email'),$email);
  }

  public function scopeSelectCvSearch($query, $alias = null){
    $alias = $this->alias($alias);
    return $query->selectRaw("CONCAT($alias.first_name, ' ',$alias.last_name)");
  }

  public function scopeGeneralOwner($query,$userId=null){
    if(!($user = $this->fixUser($userId)))
      return $query->nullFilter();

    return $query->hidden()->whereHas('roles',function($query) use($user){
      $query->keys($user->rolesroles()->get()->pluck("id")->toArray());
    });
  }

  public function scopeParticularOwner($query,$userId=null){
    $query->key($userId);
  }

  public function scopeHasInternalRole($query){
    $query->whereHas('roles',function($query){
      $query->internal();
    });
  }

  public function scopeHasExternalRole($query){
    $query->whereHas('roles',function($query){
      $query->external();
    });
  }
// [End Scopes]

// [Others]
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
  public function isRoot(){
    return $this->roles()->withRoot()->count();
  }

  public function isAdmin(){
    return $this->roles()->withAdmin()->count();
  }

  public function inRoles(...$roles){
    return $this->roles()->inRoles($roles)->count();
  }

  public function hasInternalRole(){
    return $this->roles()->internal()->count() > 0;
  }

  public function hasExternalRole(){
    return $this->roles()->external()->count() > 0;
  }
// [End Others]
}

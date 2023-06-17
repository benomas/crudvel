<?php namespace Crudvel\Models;

use Carbon\Carbon;

class User extends \Crudvel\Models\BaseModel{

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
    return $this->belongsToMany("App\Models\Role")->disableRestricction();
  }

  public function permissions(){
    return $this->hasManyDeepFromRelations($this->roles(),(new \App\Models\Role)->permissions())->disableRestricction();
  }

  public function domineeringRoles () {
    return $this->hasManyDeepFromRelations($this->roles(),(new \App\Models\Role)->setAlias('r2')->domineeringRoles())->disableRestricction();
  }

  public function dominedRoles(){
    return $this->hasManyDeepFromRelations($this->roles(),(new \App\Models\Role)->setAlias('r2')->dominedRoles())->disableRestricction();
  }
// [End Relationships]

//Non standar Relationships
  public function sectionPermissions()
  {
    return $this->permissions()->secctionPermissions();
  }

  public function resourcePermissions()
  {
    return $this->permissions()->resourcePermissions();
  }

  public function actionPermissions()
  {
    return $this->permissions()->actionPermissions();
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
    $query->whereHas("permissions",function($query) use($section){
      $query->section($section);
    });
  }

  public function scopeResourcePermission($query,$resource){
    $query->whereHas("permissions",function($query) use($resource){
      $query->resource($resource);
    });
  }

  public function scopeActionPermission($query,$action){
    $query->whereHas("permissions",function($query) use($action){
      $query->action($action);
    });
  }

  public function scopeFieldPermission($query,$field){
    $query->whereHas("permissions",function($query) use($field){
      $query->field($field);
    });
  }

  public function scopeSpecialPermission($query,$special){
    $query->whereHas("permissions",function($query) use($special){
      $query->special($special);
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

    $defaultCvSearch = "CONCAT(
      {$this->safeField($alias,'first_name')},
      {$this->safeField($alias,'last_name')}
    )";

    $fallBackCvSearch = "{$this->safeField($alias,'email')}";

    $query->selectRaw(static::sSafeIf("{$defaultCvSearch} = ''",$fallBackCvSearch,$defaultCvSearch));
  }

  public function scopeGeneralOwner($query,$user=null){
    if(!$user)
      return $query->noFilters();

    return $query->hidden()->whereHas('roles',function($query) use($user){
      $query->keys($user->dominedRoles->pluck("id")->toArray());
    });
  }

  public function scopeParticularOwner($query,$user=null){
    if(!$user)
      return $query->noFilters();

    $query->key($user->id);
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

  public function scopeAddUserFullNamed($query){
    return $this->selfPreBuilder('u')->selectRaw("CONCAT(u.first_name, ' ',u.last_name) as user_full_name");
  }

  public function scopeCurrentUser($query){
    if(\CvResource::getUserModelCollectionInstance())
      return $query->key(\CvResource::getUserModelCollectionInstance()->id);

      return $query;
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
    return $this->roles()->disableRestricction()->withRoot()->count();
  }

  public function isAdmin(){
    return $this->roles()->disableRestricction()->withAdmin()->count();
  }

  public function inRoles(...$roles){
    return $this->roles()->disableRestricction()->inRoles($roles)->count();
  }

  public function hasInternalRole(){
    return $this->roles()->disableRestricction()->internal()->count() > 0;
  }

  public function hasExternalRole(){
    return $this->roles()->disableRestricction()->external()->count() > 0;
  }

  public function disabledUser(){
    if(!$this->active)
      return true;

    return $this->roles()->actives()->count() === 0;
  }
// [End Others]
}

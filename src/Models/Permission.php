<?php namespace Crudvel\Models;

class Permission extends  \Customs\Crudvel\Models\BaseModel{

  public static $enablePermissionCheck = true;

  protected $fillable = [
    "slug",
    "name",
    "description",
    "cat_permission_type_id",
    "active",
  ];

  public function __construct($attributes = array())  {
    parent::__construct($attributes);
  }

// [Relationships]
  public function roles(){
    return $this->belongsToMany("Crudvel\Models\Role");
  }

  public function catPermissionType(){
    return $this->belongsTo("Crudvel\Models\CatPermissionType");
  }

  public function users(){
    return $this->hasManyDeep('App\Models\User', ['permission_role', 'App\Models\Role', 'role_user']);
  }

// [End Relationships]

// [Transformers]
// [End Transformers]

// [Scopes]
  //general
  public function scopePermissionsOfType($query,$type){
    $query->whereHas('catPermissionType',function($query) use($type){
      $query->name($type);
    });
  }

  //specific
  public function scopeSecctionPermissions($query){
    $query->permissionsOfType("Sección");
  }

  //specific
  public function scopeResourcePermissions($query){
    $query->permissionsOfType("Recurso");
  }

  //specific
  public function scopeActionPermissions($query){
    $query->permissionsOfType("Acción");
  }

  //specific
  public function scopeFieldPermissions($query){
    $query->permissionsOfType("Campo");
  }

  //specific
  public function scopeSpecialPermissions($query){
    $query->permissionsOfType("Especial");
  }

  //specific
  public function scopeSection($query,$slug){
    $query->slug($slug)->secctionPermissions();
  }

  //specific
  public function scopeResource($query,$slug){
    $query->slug($slug)->resourcePermissions();
  }

  //specific
  public function scopeAction($query,$slug){
    $query->slug($slug)->actionPermissions();
  }

  //specific
  public function scopeField($query,$slug){
    $query->slug($slug)->fieldPermissions();
  }

  //specific
  public function scopeSpecial($query,$slug){
    $query->slug($slug)->specialPermissions();
  }

  public function scopeInPermissions($query,$permissions){
    $query->whereIn($this->getTable().".id",$permissions);
  }

  public function scopeInNamePermissions($query,$namePermissions){
    $query->whereIn($this->getTable().".name",$namePermissions);
  }

  public function scopeSelectCvSearch($query, $alias = null){
    $alias = $this->alias($alias);
    return $query->join('cat_permission_types', 'cat_permission_types.id', '=', "$alias.cat_permission_type_id")
      ->selectRaw("CONCAT($alias.name, ' [',cat_permission_types.name,']')");
  }

  public function scopeGeneralOwner($query,$userId=null){
    $this->scopeParticularOwner($query,$userId=null);
  }

  public function scopeParticularOwner($query,$userId=null){/*
    $query->whereHas('roles',function($query) use($userId) {
      $query->whereHas('users',function($query) use($userId) {
        $query->particularOwner($userId);
      });
    });*/
  }

  public function scopeRelatedToRole ($query,$roleKey) {
    $query->whereHas("roles",function($query) use ($roleKey) {
      $query->key($roleKey);
    });
  }
// [End Scopes]

// [Others]
// [End Others]
}

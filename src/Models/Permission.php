<?php namespace Crudvel\Models;

class Permission extends  \Customs\Crudvel\Models\BaseModel{

  public static $enablePermissionCheck    = true;
  protected $permissionTypeIdValue        = null;
  protected $slugValue                    = null;

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
  public function setSlugAttribute($value)
  {
    $this->slugValue = $this->attributes['slug'] = $value;
    $this->fixResource();
  }

  public function setCatPermissionTypeIdAttribute($value)
  {
    $this->permissionTypeIdValue = $this->attributes['cat_permission_type_id'] = $value;
    $this->fixResource();
  }
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
    return $query->noFilters();
  }

  public function scopeParticularOwner($query,$userId=null){
    if(!($user = $this->fixUser($userId)))
      return $query->noFilters();

    $query->whereHas('roles',function($query) use($user) {
      $query->whereHas('users',function($query) use($user) {
        $query->key($user->id);
      });
    });
  }

  public function scopeRelatedToRole ($query,$roleKey) {
    $query->whereHas("roles",function($query) use ($roleKey) {
      $query->key($roleKey);
    });
  }
// [End Scopes]

// [Others]
  public function fixResource(){
    $this->attributes['resource'] = null;

    if($this->permissionTypeIdValue === null || $this->slugValue === null)
      return ;

    if(!$catFileInstance = \App\Models\CatPermissionType::withoutGlobalScope(\Crudvel\Scopes\PermissionsScope::class)->key($this->permissionTypeIdValue)->solveSearches()->first())
      return ;

    if($catFileInstance->slug==='resource'){
      $this->attributes['resource'] = $this->slugValue;
      return ;
    }

    if($catFileInstance->slug==='action'){
      $this->attributes['resource'] = explode('.', $this->slugValue)[0]??null;
      return ;
    }
  }
// [End Others]
}

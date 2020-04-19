<?php namespace Crudvel\Models;

class Role extends \Customs\Crudvel\Models\BaseModel{

  protected $fillable = [
    'slug',
    'name',
    'internal',
    'external',
    'description',
    'active',
  ];

  public function __construct($attributes = array())  {
    parent::__construct($attributes);
  }
// [Relationships]
  public function users(){
    return $this->belongsToMany("App\Models\User", "role_user");
  }

  public function permissions(){
    return $this->belongsToMany("App\Models\Permission", "permission_role");
  }

  public function dominedBy(){
    return $this->belongsToMany("App\Models\Role", 'role_role', 'domined_role_id', 'domineering_role_id');
  }

  public function roles(){
    return $this->belongsToMany("App\Models\Role", 'role_role', 'domineering_role_id', 'domined_role_id');
  }
// [End Relationships]

//Non standar Relationships
  public function sectionPermissions(){
    return $this->belongsToMany("App\Models\Permission", "permission_role")->secctionPermissions();
  }

  public function resourcePermissions(){
    return $this->belsongsToMany("App\Models\Permission", "permission_role")->resourcePermissions();
  }

  public function actionePermissions(){
    return $this->belongsToMany("App\Models\Permission", "permission_role")->actionPermissions();
  }

  public function fieldPermissions(){
    return $this->belongsToMany("App\Models\Permission", "permission_role")->fieldPermissions();
  }

  public function specialPermissions(){
    return $this->belongsToMany("App\Models\Permission", "permission_role")->specialPermissions();
  }
//End Non standar Relationships

// [Transformers]
// [End Transformers]

// [Scopes]
  public function scopeInRoles($query,$rolesSlug){
    return $query->whereIn($this->preFixed('slug'),$rolesSlug);
  }

  public function scopeNotInRoles($query,$rolesSlug){
    return $query->whereNotIn($this->preFixed('slug'),$rolesSlug);
  }

  public function scopeWithRole($query,$roleSlug){
    return $query->inRoles([$roleSlug]);
  }

  public function scopeWithoutRole($query,$roleSlug){
    return $query->notInRoles([$roleSlug]);
  }

  public function scopeWithRoot($query){
    return $query->withRole('root');
  }

  public function scopeWithAdmin($query){
    return $query->withRole('adminsitrator');
  }

  public function scopeWithoutRoot($query){
    $query->where($this->preFixed('slug'),"<>","root");
  }

  public function scopeWithoutAdmin($query){
    return $query->withoutRole('adminsitrator');
  }

  public function scopeWithManager($query){
    return $query->withRole('manager');
  }

  public function scopeWithDistribuitor($query){
    return $query->withRole('distributor');
  }

  public function scopeHidden($query){
    $query->where($this->preFixed('slug'),"<>","root");
  }

  public function scopeSelectCvSearch($query,$alias=null){
    $alias = $this->alias($alias);

    return $query->selectRaw("CONCAT($alias.name)");
  }

  public function scopeGeneralOwner($query,$user=null){
    return $query->hidden();
  }

  public function scopeParticularOwner($query,$user=null){
    if(!$user)
      return $query->noFilters();

    return $query->ids($user->rolesroles()->get()->pluck("id")->toArray());
  }

  public function scopeRelatedToUser ($query,$userKey) {
    return $query->whereHas("users",function($query) use ($userKey) {
      $query->key($userKey);
    });
  }

  public function scopeRelatedToRole ($query,$roleKey) {
    return $query->whereHas("roles",function($query) use ($roleKey) {
      $query->key($roleKey);
    });
  }

  public function scopeInternal($query){
    return $query->where($this->preFixed('internal'),1);
  }

  public function scopeExternal($query){
    return $query->where($this->preFixed('external'),1);
  }
// [End Scopes]

// [Others]
// [End Others]
}

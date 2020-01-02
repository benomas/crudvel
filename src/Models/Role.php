<?php namespace Crudvel\Models;

class Role extends \Customs\Crudvel\Models\BaseModel{

  protected $fillable = [
    "slug",
    "name",
    "description",
    "active",
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
    return $this->belongsToMany("App\Models\Role", 'role_role', 'id', 'domineering_role_id');
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
    $query->whereIn($this->getTable().".slug",$rolesSlug);
  }

  public function scopeNotInRoles($query,$rolesSlug){
    $query->whereNotIn($this->getTable().".slug",$rolesSlug);
  }

  public function scopeWithRole($query,$roleSlug){
    $this->scopeInRoles($query,[$roleSlug]);
  }

  public function scopeWithoutRole($query,$roleSlug){
    $this->scopeNotInRoles($query,[$roleSlug]);
  }

  public function scopeWithRoot($query){
    $this->scopeWithRole($query,"root");
  }

  public function scopeWithAdmin($query){
    $this->scopeWithRole($query,"adminsitrator");
  }

  public function scopeWithoutRoot($query){
    $this->scopeWithoutRole($query,"root");
  }

  public function scopeWithoutAdmin($query){
    $this->scopeWithoutRole($query,"adminsitrator");
  }
  public function scopeWithManager($query){
    $this->scopeWithRole($query,"manager");
  }

  public function scopeWithDistribuitor($query){
    $this->scopeWithRole($query,"distributor");
  }

  public function scopeHidden($query){
    $query->where($this->getTable().".slug","<>","root");
  }

  public function scopeGeneralOwner($query,$userId){
    $this->scopeHidden($query);
  }

  public function scopeParticularOwner($query,$userId){
    $user = \App\Models\User::id($userId)->first();
    if(!$user)
      $query->nullFilter();
    else
      $query->ids($user->rolesroles()->get()->pluck("id")->toArray());
  }
// [End Scopes]

// [Others]
// [End Others]
}

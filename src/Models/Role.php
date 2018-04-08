<?php 
namespace Crudvel\Models;
use Crudvel\Models\BaseModel;

class Role extends BaseModel{

    protected $fillable = [
        "slug",
        "name",
        "description",
        "active",
    ];

    public function __construct($attributes = array())  {
        parent::__construct($attributes);
    }

//Relationships

    public function users(){
        return $this->belongsToMany("Crudvel\Models\User", "role_user");
    }

    public function permissions(){
        return $this->belongsToMany("Crudvel\Models\Permission", "permission_role");
    }

    public function dominedBy(){
        return $this->belongsToMany("Crudvel\Models\Role", 'role_role', 'id', 'domineering_role_id');
    }

    public function dominetTo(){
        return $this->belongsToMany("Crudvel\Models\Role", 'role_role', 'id', 'domined_role_id');
    }

//End Relationships

//Non standar Relationships

    public function sectionPermissions(){
        return $this->belongsToMany("Crudvel\Models\Permission", "permission_role")->secctionPermissions();
    }

    public function resourcePermissions(){
        return $this->belsongsToMany("Crudvel\Models\Permission", "permission_role")->resourcePermissions();
    }

    public function actionePermissions(){
        return $this->belongsToMany("Crudvel\Models\Permission", "permission_role")->actionPermissions();
    }

    public function fieldPermissions(){
        return $this->belongsToMany("Crudvel\Models\Permission", "permission_role")->fieldPermissions();
    }

    public function specialPermissions(){
        return $this->belongsToMany("Crudvel\Models\Permission", "permission_role")->specialPermissions();
    }

//End Non standar Relationships

// Scopes
    public function scopeInRoles($query,$rolesSlug){
        $query->whereIn($this->getTable().".slug",$rolesSlug);
    }

    public function scopeWithRole($query,$roleSlug){
        $this->scopeInRoles($query,[$roleSlug]);
    }

    public function scopeWithRoot($query){
        $this->scopeWithRole($query,"root");
    }

    public function scopeWithAdmin($query){
        $this->scopeWithRole($query,"adminsitrator");
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

// End Scopes
}

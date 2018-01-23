<?php 
namespace Crudvel\Models;
use Crudvel\Models\BaseModel;

class Role extends BaseModel{

    protected $fillable = [
        "slug", "name", "description","active","created_at","updated_at"
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

//End Relationships

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
        $this->scopeWithRole($query,"administrador");
    }

    public function scopeWithManager($query){
        $this->scopeWithRole($query,"gerente");
    }

    public function scopeWithDistribuitor($query){
        $this->scopeWithRole($query,"distribuidor");
    }

    public function scopeHidden($query){
        $query->where($this->getTable().".slug","<>","root");
    }

// End Scopes
}

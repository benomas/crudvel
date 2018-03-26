<?php 
namespace Crudvel\Models;

use Crudvel\Models\BaseModel;
class Permission extends BaseModel{

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

    public function roles(){
        return $this->belongsToMany("Crudvel\Models\Role", "permission_role");
    }

    public function catPermissionType(){
        return $this->belongsTo("Crudvel\Models\CatPermissionType");
    }

//End Relationships

// Scopes

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
    public function scopeSection($query,$section){
        $query->slug($section)->secctionPermissions();
    }

    //specific
    public function scopeResource($query,$resource){
        $query->slug($resource)->resourcePermissions();
    }

    //specific
    public function scopeAction($query,$action){
        $query->slug($action)->actionPermissions();
    }

    //specific
    public function scopeField($query,$field){
        $query->slug($field)->fieldPermissions();
    }

    //specific
    public function scopeSpecial($query,$special){
        $query->slug($special)->fieldPermissions();
    }

    public function scopeInPermissions($query,$permissions){
        $query->whereIn($this->getTable().".id",$permissions);
    }

    public function scopeInNamePermissions($query,$namePermissions){
        $query->whereIn($this->getTable().".name",$namePermissions);
    }



// End Scopes
}

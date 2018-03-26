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

    public function scopeActionResource($query,$actionResource){
        $query->where($this->getTable().".slug",$actionResource);
    }

    public function scopeInPermissions($query,$permissions){
        $query->whereIn($this->getTable().".id",$permissions);
    }

    public function scopeInNamePermissions($query,$namePermissions){
        $query->whereIn($this->getTable().".name",$namePermissions);
    }


// End Scopes
}

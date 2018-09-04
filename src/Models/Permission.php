<?php 
namespace Crudvel\Models;

use Crudvel\Models\BaseModel;
class Permission extends BaseModel{
    
    public static $enablePermissionCheck = true;

    protected $fillable = [
        'slug', 'name', 'active','created_at','updated_at'
    ];

    public function __construct($attributes = array())  {
        parent::__construct($attributes);
    }
//Relationships

    public function roles(){
        return $this->belongsToMany("Crudvel\Models\Role", "permission_role");
    }

//End Relationships

// Scopes

    public function scopeActionResource($query,$actionResource){
        $query->where($this->getTable().'.slug',$actionResource);
    }

    public function scopeInPermissions($query,$permissions){
        $query->whereIn($this->getTable().'.id',$permissions);
    }

    public function scopeInNamePermissions($query,$namePermissions){
        $query->whereIn($this->getTable().'.name',$namePermissions);
    }


// End Scopes
}

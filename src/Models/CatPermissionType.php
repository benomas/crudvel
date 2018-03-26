<?php 
namespace Crudvel\Models;

use Crudvel\Models\BaseModel;
class CatPermissionType extends BaseModel{

    protected $fillable = [
        "name",
        "description",
        "active"
    ];

    public function __construct($attributes = array())  {
        parent::__construct($attributes);
    }
//Relationships

    public function permissions(){
        return $this->hasMany("Crudvel\Models\Permission");
    }

//End Relationships

// Scopes


// End Scopes
}

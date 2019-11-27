<?php namespace Crudvel\Models;

class CatPermissionType extends \Crudvel\Customs\Models\BaseModel{

  protected $fillable = [
    "name",
    "slug",
    "description",
    "active"
  ];

  public function __construct($attributes = array())  {
    parent::__construct($attributes);
  }
// [Relationships]
  public function permissions(){
    return $this->hasMany("Crudvel\Models\Permission");
  }
// [End Relationships]

// [Transformers]
// [End Transformers]

// [Scopes]
// [End Scopes]

// [Others]
// [End Others]
}

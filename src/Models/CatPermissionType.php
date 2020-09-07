<?php namespace Crudvel\Models;

class CatPermissionType extends \Crudvel\Models\BaseModel{

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
    return $this->hasMany('App\Models\Permission');
  }
// [End Relationships]

// [Transformers]
  public function setSlugAttribute($value){
    $this->attributes['slug'] = $this->cvSlugCase($value);
  }
// [End Transformers]

// [Scopes]
  public function scopeSeccionType($query){
    return $query->slug('section');
  }

  public function scopeResourceType($query){
    return $query->slug('resource');
  }

  public function scopeActionType($query){
    return $query->slug('action');
  }

  public function scopeFieldType($query){
    return $query->slug('field');
  }

  public function scopeSpecialType($query){
    return $query->slug('special');
  }
// [End Scopes]

// [Others]
// [End Others]
}

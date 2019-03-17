<?php namespace Crudvel\Models;

class CatFile extends \Crudvel\Customs\Models\BaseModel{

  protected $fillable = [
    'name',
    'slug',
    'description',
    'required',
    'group',
    'max_size',
    'min_size',
    'types',
    'multiple',
    'resource',
  ];

  protected $appends=[
    'camel_resource'
  ];

//Relationships


//End Relationships

// Scopes

  public function scopeResource($query,$resource){
    $query->where($this->getTable().'.resource',$resource);
  }

// End Scopes

  public function getCamelResourceAttribute(){
    return empty($this->attributes['resource'])?null:camel_case($this->attributes['resource'],'-');
  }
}

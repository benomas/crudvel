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

  public function file(){
    return $this->hasMany("\App\Models\File");
  }

//End Relationships

// Scopes

  public function scopeResource($query,$resource){
    $query->where($this->getTable().'.resource',$resource);
  }

  public function scopeHasFile($query){
    $query->whereHas('file');
  }

// End Scopes

  public function getCamelResourceAttribute(){
    return empty($this->attributes['resource'])?null:camel_case($this->attributes['resource'],'-');
  }

  public function modelClassInstance(){
    $targetModel = camel_case(str_singular($this->attributes['resource']));
    if (method_exists($this,$targetModel.'ModelClassInstance'))
      return $this->$targetModel.'ModelClassInstance'();
    $testModel  = '\App\Models\\'.studly_case(str_singular($this->attributes['resource']));
    if(class_exists($testModel))
      return new $testModel;
    return null;
  }
}

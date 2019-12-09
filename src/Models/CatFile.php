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
// [Relationships]
  public function file(){
    return $this->hasMany("\App\Models\File");
  }
// [End Relationships]

// [Transformers]
  public function getCamelResourceAttribute(){
    return empty($this->attributes['resource'])?null:\Str::camel($this->attributes['resource'],'-');
  }
// [End Transformers]

// [Scopes]
  public function scopeResource($query,$resource){
    $query->where($this->getTable().'.resource',$resource);
  }

  public function scopeHasFile($query){
    $query->whereHas('file');
  }
// [End Scopes]

// [Others]
  public function modelClassInstance(){
    $targetModel = \Str::camel(Str::singular($this->attributes['resource']));
    if (method_exists($this,$targetModel.'ModelClassInstance'))
      return $this->$targetModel.'ModelClassInstance'();
    $testModel  = '\App\Models\\'.\Str::studly(\Str::singular($this->attributes['resource']));
    if(class_exists($testModel))
      return new $testModel;
    return null;
  }
// [End Others]
}

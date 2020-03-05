<?php namespace Crudvel\Models;

class CatFile extends \Customs\Crudvel\Models\BaseModel{

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
    'public_path',
    'resource',
    'active',
    'created_at',
    'created_by',
    'updated_at',
    'updated_by',
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

  public function scopeSelectCvSearch($query,$alias=null){
    $alias = $alias ?? $this->getTable();
    return $query->select("$alias.name");
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

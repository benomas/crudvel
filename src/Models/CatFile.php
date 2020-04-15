<?php namespace Crudvel\Models;

use Illuminate\Database\Eloquent\Builder;
use DB;

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

  //this model has a dinamic column resource_label, it is solved with resource lang

  protected $appends=[
    'camel_resource',
  ];

// [Relationships]
  public function files(){
    return $this->hasMany("\App\Models\File");
  }
// [End Relationships]

// [Transformers]
  public function getCamelResourceAttribute(){
    return empty($this->attributes['resource'])?null:\Str::camel($this->attributes['resource'],'-');
  }

  public function setSlugAttribute($value)
  {
    $this->attributes['slug'] = cvSlugCase($value);
  }

  public function setResourceAttribute($value)
  {
    $this->attributes['resource']       = cvCaseFixer('slug|plural',$value);
    $this->attributes['resource_label'] = __("crudvel/".$this->attributes['resource'].".row_label") ?? $this->attributes['resource'];
  }
// [End Transformers]

// [Scopes]
  public function scopeResource($query,$resource){
    $query->where($this->getTable().'.resource',$resource);
  }

  public function scopeHasFiles($query){
    $query->whereHas('files');
  }

  public function scopeParticularOwner($query, $userId=null)
  {
    if(!($user = $this->fixUser($userId)))
      return $query->nullFilter();
  }

  public function scopeSelectCvSearch($query,$alias=null){
    $alias = $this->alias($alias);
    return $query->selectRaw("CONCAT($alias.name,' - ',$alias.resource_label)");
  }
// [End Scopes]

// [Others]
// [End Others]
}

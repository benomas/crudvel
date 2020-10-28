<?php namespace Crudvel\Models;

use Illuminate\Database\Eloquent\Builder;
use DB;

class CatFile extends \Crudvel\Models\BaseModel{

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
    'cv_has_code_hook',
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
  public function scopeInResources($query,$resources){
    return $query->whereIn($this->preFixed('resource'),$resources);
  }

  public function scopeResource($query,$resource){
    return $query->where($this->preFixed('resource'),$resource);
  }

  public function scopeHasFiles($query){
    return $query->whereHas('files');
  }

  public function scopeParticularOwner($query, $user=null)
  {
    if(!$user)
      return $query->noFilters();

    $validResources = [];
    foreach(self::groupBy('resource')->get() as $resource){
      if($user->permissions()->whereHas('catPermissionType',function($query){
        $query->specialType();
      })->slugs(["$resource->resource.index-files","$resource->resource.show-files","$resource->resource.update-files"])->count()){
        $validResources[] = $resource->resource;
      }
    }

    return $query->inResources($validResources);
  }

  public function scopeSelectCvSearch($query,$alias=null){
    $alias = $this->alias($alias);

    return $query->selectRaw("CONCAT($alias.name,' - ',$alias.resource_label)");
  }

  public function scopeGroup($query,$group){
    return $query->where($this->preFixed('group'),$group);
  }
// [End Scopes]

// [Others]

  public static function safeCollection ($catFilesCollection){
    if(($catFilesCollection = $catFilesCollection??null)){
      $catFilesCollection = $catFilesCollection->makeHidden([
        'id',
        'camel_resource',
        'cv_has_code_hook',
        'created_at',
        'updated_at',
        'created_by',
        'updated_by',
        'required',
        'max_size',
        'min_size',
        'types',
        'multiple',
        'public_path',
        'resource',
        'resource_label',
        'active',
      ]);
    }

    return $catFilesCollection;
  }
// [End Others]
}

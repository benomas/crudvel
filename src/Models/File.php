<?php namespace Crudvel\Models;

use Customs\Crudvel\Models\BaseModel;

class File extends \Customs\Crudvel\Models\BaseModel{
  use \Crudvel\Traits\Related;
  protected $catFileIdValue  = null;
  protected $resourceIdValue = null;

  protected $fillable = [
    'disk',
    'path',
    'absolute_path',
    'cat_file_id',
    'resource_id',
    'created_at',
    'created_by',
    'updated_at',
    'updated_by',
  ];

  protected $cvSearches = [
    'cat_file_cv_search' => 'App\Models\CatFile',
  ];

// [Relationships]
  public function catFile(){
    return $this->belongsTo("\App\Models\CatFile");
  }

  //this relation cant work as preload
  public function resource(){
    return $this->belongsTo(
      "\App\Models\\".\Str::studly(\Str::singular($this->catFile()->first()->resource)),
      'resource_id',
      'id'
    );
  }
// [End Relationships]

// [Transformers]
  public function setCatFileIdAttribute($value)
  {
    $this->catFileIdValue             = $value;
    $this->attributes['cat_file_id']  = $value;
    $this->fixMixedCvSearch();
  }

  public function setResourceIdAttribute($value)
  {
    $this->resourceIdValue            = $value;
    $this->attributes['resource_id']  = $value;
    $this->fixMixedCvSearch();
  }
// [End Transformers]

// [End Scopes]
  public function scopeCatFileId($query,$catFileId){
    $query->where($this->getTable().'.cat_file_id',$catFileId);
  }

  public function scopeCatFileSlug($query,$catFileSlug){
    $query->whereHas('catFile',function($query) use($catFileSlug) {
      $query->where('cat_files.slug',$catFileSlug);
    });
  }

  public function scopeResourceId($query,$resourceId){
    $query->where($this->getTable().'.resource_id',$resourceId);
  }

  public function scopeParticularOwner($query, $userId=null){
    if(!($user = $this->fixUser($userId)))
      return $query->nullFilter();
  }

  public function scopeFromResource($query,$resource){
    $query->whereHas('catFile',function($query) use($resource){
      $query->where('resource',$resource);
    });
  }

  public function scopeSelectCvSearch($query,$alias=null){
    $alias = $this->alias($alias);
    return $query->select("$alias.mixed_cv_search");
  }
// [End Scopes]

// [Others]
  public function fixMixedCvSearch(){
    $this->attributes['mixed_cv_search']  = '';

    if($this->catFileIdValue === null || $this->resourceIdValue === null)
      return ;

    if(!$catFileInstance = \App\Models\CatFile::key($this->catFileIdValue)->solveSearches()->first())
      return ;

    $resourceModel = '\App\Models\\'.cvCaseFixer('studly|singular',$catFileInstance->resource);

    if(!class_exists($resourceModel))
      return ;

    if(!$resourceModelInstance = $resourceModel::key($this->resourceIdValue)->solveSearches()->first())
      return ;

    $this->attributes['mixed_cv_search']  = $catFileInstance->cv_search . ' - '.$resourceModelInstance->cv_search;
  }
// [End Others]
}

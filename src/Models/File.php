<?php namespace Crudvel\Models;

use Customs\Crudvel\Models\BaseModel;

class File extends \Customs\Crudvel\Models\BaseModel{
  use \Crudvel\Traits\Related;

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

  public function scopeParticularOwner($query,$userId){
    /* TODO this logic needs to be redefined, based on the user,
      user has access to resourses, on those resources user maybe has records,
      so particual owner, only can have access to files asociated to those records from the
      resources that are related to files
     */

    /*
    $query->whereHas('resource',function($query) use($userId) {
      $query->particularOwner($userId);
    });*/
  }

  public function scopeFromResource($query,$resource){
    $query->whereHas('catFile',function($query) use($resource){
      $query->where('resource',$resource);
    });
  }

  public function scopeSelectCvSearch($query,$alias=null){
    $alias = $alias ?? $this->getTable();
    return $query->select("$alias.disk");
  }
// [End Scopes]

// [Others]
// [End Others]
}

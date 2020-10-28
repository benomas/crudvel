<?php namespace Crudvel\Models;

use Customs\Crudvel\Models\BaseModel;

class File extends \Crudvel\Models\BaseModel{
  use \Crudvel\Traits\Related;
  protected $hidden = ['resource'];

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

  public function resourcer(){
    return $this->morphTo(null,'resource','resource_id','id');
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
    return $query->where($this->preFixed('cat_file_id'),$catFileId);
  }

  public function scopeCatFileSlug($query,$catFileSlug){
    return $query->whereHas('catFile',function($query) use($catFileSlug) {
      $query->where('cat_files.slug',$catFileSlug);
    });
  }

  public function scopeResourceId($query,$resourceId){
    return $query->where($this->preFixed('resource_id'),$resourceId);
  }

  public function scopeResourceKey($query,$resourceKey){
    return $query->where($this->preFixed('resource_id'),$resourceKey);
  }

  public function scopeParticularOwner($query, $user=null){
    if(!$user)
      return $query->noFilters();

    return $query->morphedDefParticularOwner($user,'resourcer');
  }

  public function scopeAditionalParticularOwner($query, $userId=null){
    if(!($user = $this->fixUser($userId)))
      return $query->noFilters();

    return $query->whereHasMorph('resourcer','*');
  }

  public function scopeFromResource($query,$resource){
    return $query->whereHas('catFile',function($query) use($resource){
      $query->where('resource',$resource);
    });
  }

  public function scopeSelectCvSearch($query,$alias=null){
    $alias = $this->alias($alias);

    return $query->select("$alias.mixed_cv_search");
  }

  public function scopeGroup($query,$group){
    return $query->whereHas('catFile',function($query) use($group){
      $query->group($group);
    });
  }
// [End Scopes]

// [Others]
  public function fixMixedCvSearch(){
    $this->attributes['mixed_cv_search']    = '';
    $this->attributes['resource_cv_search'] = '';
    $this->attributes['resource']           = '';

    if($this->catFileIdValue === null || $this->resourceIdValue === null)
      return ;

    if(!$catFileInstance = \App\Models\CatFile::withoutGlobalScope(\Crudvel\Scopes\PermissionsScope::class)->key($this->catFileIdValue)->solveSearches()->first())
      return ;

    $resourceModel = 'App\Models\\'.cvCaseFixer('studly|singular',$catFileInstance->resource);

    if(!class_exists($resourceModel))
      return ;

    if(!$resourceModelInstance = $resourceModel::key($this->resourceIdValue)->solveSearches()->first())
      return ;

    $this->attributes['resource_cv_search'] = $resourceModelInstance->cv_search;
    $this->attributes['mixed_cv_search']    = $catFileInstance->cv_search . ' - '.$resourceModelInstance->cv_search;
    $this->attributes['resource']           = $resourceModel;
  }

  public static function safeCollection ($filesCollection){
    if(($filesCollection = $filesCollection??null)){
      $filesCollection = $filesCollection->map(function($row){
        \App\Models\CatFile::safeCollection($row->catFile);

        return $row;
      })->makeHidden([
        'id',
        'camel_resource',
        'cv_has_code_hook',
        'cv_has_files',
        'cat_file_id',
        'resource_id',
        'created_at',
        'updated_at',
        'created_by',
        'updated_by',
      ]);
    }

    return $filesCollection;
  }
// [End Others]
}

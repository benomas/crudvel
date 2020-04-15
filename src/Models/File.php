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

  public function scopeParticularOwner($query, $userId=null){
    if(!($user = $this->fixUser($userId)))
      return $query->nullFilter();
  }

  public function scopeFromResource($query,$resource){
    $query->whereHas('catFile',function($query) use($resource){
      $query->where('resource',$resource);
    });
  }

  //cv_search was disabled because this model rewrite builder instance with unions
  public function scopeCvSearch($query,$alias=null){
    return ;
  }

  public function scopeSelectCvSearch($query,$alias=null){
    $alias = $this->alias($alias);
    return $query->select("$alias.disk");
  }
// [End Scopes]

// [Others]
  public function selfDinamic() {
    $catFiles = \App\Models\CatFile::hasFiles()->get();
    $fileUnions        = [];
    foreach($catFiles as $catFile){
      $resourceModel = 'App\Models\\'.cvCaseFixer('studly|singular',$catFile->resource);
      if(class_exists($resourceModel)){
        $resource_label  = __("crudvel/$catFile->resource.row_label") ?? $catFile->resource;
        $fileUnions[] = $this->autoBuilder()
          ->where('cat_file_id',$catFile->id)
          ->addSelect(['cv_search' => $resourceModel::cvSearch()->whereColumn("id", "files.resource_id")->limit(1)->selectRaw("id")]);
          //->addSelect(['cv_search' => $this->autoBuilder('din_cv_search')->whereColumn("din_cv_search.id", "files.id")->limit(1)->selectRaw("CONCAT(files.id,', recurso: [','$resource_label]')")]);
      }
    }

    foreach($fileUnions as $unionBuilder){
      if(empty($fileBuilder))
        $fileBuilder = $unionBuilder;
      else
        $fileBuilder->union($unionBuilder);
    }

    return $fileBuilder;
  }

  public function autoBuilder($tableAlias=null){
    $builder = null;
    if(method_exists($this,'getModelBuilderInstance'))
      $builder =  kageBunshinNoJutsu($this->getModelBuilderInstance()->getQuery());
    else
      $builder = DB::table(self::cvIam()->getTable());

    if($tableAlias){
      $table = $this->cvIam()->getTable();
      $builder->from("$table as $tableAlias");
    }

    return $builder;
  }

  public function dinamicResourceFiles(){
    $catFiles   = \App\Models\CatFile::cvOwner()->hasFiles()->get();
    $filesUnion = [];
    foreach($catFiles as $catFile){
      $resourceModel    = 'App\Models\\'.cvCaseFixer('studly|singular',$catFile->resource);
      $resource_label   = __("crudvel/$catFile->resource.row_label") ?? $catFile->resource;
      if(class_exists($resourceModel)){
        $filesUnion[] =  \App\Models\File::cvOwner()->joinsub($resourceModel::cvOwner()->cvSearch(),'resource', function ($join) {
          $join->on('resource.id', '=', 'files.resource_id');
        })->joinsub(\App\Models\CatFile::cvOwner(),'cat_files', function ($join) {
          $join->on('cat_files.id', '=', 'files.cat_file_id');
        })
        ->selectRaw('
          files.*,
          resource.cv_search as r_cv_search,
          cat_files.resource as cf_resource,
          CONCAT(cat_files.name,", recurso: [","'.$resource_label.'","]") as cf_resource_cv_search'
        );
      }
    }
    foreach($filesUnion as $unionBuilder){
      if(empty($fileBuilder))
        $fileBuilder = $unionBuilder;
      else
        $fileBuilder->union($unionBuilder);
    }
    return $fileBuilder;
  }
// [End Others]
}

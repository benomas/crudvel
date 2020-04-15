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

  public function setResourceLabelAttribute($value)
  {
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
/*
  public function scopeCvSearch($query,$alias=null){
    $alias      = $this->alias($alias);
    $table      = $this->cvIam()->getTable();
    $modelClass = get_class($this->cvIam());

    $resources            = self::groupBy('resource')->get()->pluck('resource');
    $catFileUnions        = [];
    foreach($resources as $resource){
      $resourceModel = 'App\Models\\'.cvCaseFixer('studly|singular',$resource);
      if(class_exists($resourceModel)){
        $resource_label  = __("crudvel/$resource.row_label") ?? $resource;
        $catFileUnions[] = kageBunshinNoJutsu($query)
          ->where('resource',$resource)
          ->addSelect(['resource_label' => kageBunshinNoJutsu($query)->limit(1)->selectRaw("'$resource_label'")])
          ->addSelect(['cv_search' => $this->autoBuilder('din_cv_search')->whereColumn("din_cv_search.id", "cat_files.id")->limit(1)->selectRaw("CONCAT(cat_files.name,', recurso: [','$resource_label]')")]);
      }
    }

    foreach($catFileUnions as $unionBuilder){
      if(empty($catFileBuilder))
        $catFileBuilder = $unionBuilder;
      else
        $catFileBuilder->union($unionBuilder);
    }

    $query = $catFileBuilder;

    $query->addSelect(['cv_search' => $modelClass::from("$table as $alias")
      ->selectCvSearch($alias)
      ->whereColumn("$alias.id", "$table.id")
      ->limit(1)]);

    return $query;
  }

  public function scopeSelectCvSearch($query,$alias=null){
    //return $query->select("{$this->alias($alias)}.name");
  }*/
// [End Scopes]

// [Others]
  public function selfDinamic() {
    $resources            = DB::table('cat_files')->groupBy('resource')->get()->pluck('resource');
    $catFileUnions        = [];
    foreach($resources as $resource){
      $resourceModel = 'App\Models\\'.cvCaseFixer('studly|singular',$resource);
      if(class_exists($resourceModel)){
        $resource_label  = __("crudvel/$resource.row_label") ?? $resource;
        $catFileUnions[] = $this->autoBuilder()
          ->where('resource',$resource)
          ->addSelect(['resource_label' => $this->autoBuilder()->limit(1)->selectRaw("'$resource_label'")])
          ->addSelect(['cv_search' => $this->autoBuilder('din_cv_search')->whereColumn("din_cv_search.id", "cat_files.id")->limit(1)->selectRaw("CONCAT(cat_files.name,', recurso: [','$resource_label]')")]);
      }
    }

    foreach($catFileUnions as $unionBuilder){
      if(empty($catFileBuilder))
        $catFileBuilder = $unionBuilder;
      else
        $catFileBuilder->union($unionBuilder);
    }

    return $catFileBuilder;
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
// [End Others]
}

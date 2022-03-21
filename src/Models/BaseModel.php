<?php

namespace Crudvel\Models;

use Crudvel\Interfaces\CvCrudInterface;
use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class BaseModel extends Model implements CvCrudInterface
{
  use \Crudvel\Traits\CrudTrait;
  use \Crudvel\Traits\CacheTrait;
  use \Crudvel\Traits\CvPatronTrait;
  use \Crudvel\Libraries\Helpers\CasesTrait;
  use \Crudvel\Models\Traits\CvBaseScopeTrait;
  use \Crudvel\Models\Traits\CvBaseMethodsTrait;
  use \Staudenmeir\EloquentHasManyDeep\HasRelationships;
  use \Illuminate\Database\Eloquent\Factories\HasFactory;

  protected $slugSingularName;
  protected $cvResourceInstance;
  protected $schema;
  protected $hasPropertyActive = true;
  protected $cacheBoots        = [];
  protected $modelMetaData     = null;
  protected $cvSearches        = [];
  protected $appends           = ['cv_has_files','cv_has_code_hook'];
  protected $codeHook          = false;
  public    $cvStamps          = true;
  //protected $hidden            = ['pivot'];

  public function __construct($attributes = array())
  {
    parent::__construct($attributes);
    $this->setCacheBoots();
    $this->injectCvResource();
  }

// [Relationships]
  public function relatedFiles(){
    return $this->morphMany('App\Models\File', 'resourcer', 'resource', 'resource_id');
  }
// [End Relationships]

// [Transformers]
  public function getCvHasFilesAttribute(){
    $resource = cvCaseFixer('slug|plural',class_basename($this));

    return \App\Models\CatFile::resource($resource)->count();
  }

  public function getCvHasCodeHookAttribute(){
    return $this->codeHook;
  }

  public function setSlugAttribute($value){
    $value = $value ?? null;

    if(empty($value)){
      $value = $this->name??null;

      if(empty($value))
        $value = (string) \Illuminate\Support\Str::uuid();
    }

    $this->attributes['slug'] = str_replace(' ', '-', strtolower(preg_replace('/\.|,/', '', trim($value))));
  }

  public function setCodeHookAttribute($value){
    $value = $value ?? null;

    if(empty($value)){
      $value = $this->name??null;

      if(empty($value))
        $value = (string) \Illuminate\Support\Str::uuid();
    }

    $this->attributes['code_hook'] = str_replace(' ', '-', strtolower(preg_replace('/\.|,/', '', trim($value))));
  }
// [End Transformers]

// [Scopes]
  protected static function boot(){
    parent::boot();

    self::creating(function($model){
      if(!$model->cvStamps)
        return;

      $rightNow         = \Carbon\Carbon::now()->toDateTimeString();
      $model->created_at = $rightNow??null;

      if(class_exists(\CvResource::class)){
        $user = \CvResource::assignUser()->getUserModelCollectionInstance();

        if($user)
          $model->created_by = $user->id??null;
      }
    });

    self::updating(function($model){
      if(!$model->cvStamps)
        return;

      $rightNow         = \Carbon\Carbon::now()->toDateTimeString();
      $model->created_at = $rightNow??null;

      if(class_exists(\CvResource::class)){
        $user = \CvResource::assignUser()->getUserModelCollectionInstance();

        if($user)
          $model->updated_by = $user->id??null;
      }
    });
  }
// [End Scopes]

// [Others]
  public function fileZipOptPath($resource){
    $DS   = DIRECTORY_SEPARATOR;
    $time = time();

    return storage_path("app{$DS}public{$DS}{$resource}-{$this->getKeyValue()}-$time.zip");
  }

  public function fileZipOptDeleteFileAfterSend(){
    return true;
  }

  public function newCollection(array $models = []){
    return new \Crudvel\Collection\BaseCollection($models);
  }
// [End Others]
}

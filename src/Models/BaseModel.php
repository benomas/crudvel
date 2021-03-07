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
  //protected $hidden            = ['pivot'];
  protected $cacheBoots        = [];
  protected $modelMetaData     = null;
  protected $cvSearches        = [];
  protected $appends           = ['cv_has_files','cv_has_code_hook'];
  protected $codeHook          = false;

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
// [End Transformers]

// [Scopes]
// [End Scopes]

// [Others]
// [End Others]
}

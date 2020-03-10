<?php

namespace Crudvel\Libraries\CvScaffSupport;
use Illuminate\Support\Str;

class CvBaseScaff
{
  use \Crudvel\Traits\CvPatronTrait;
  use \Crudvel\Traits\CvClosureCommandTrait;
  use \Crudvel\Libraries\Helpers\CasesTrait;
  private $consoleInstance;
  private $resource;
  private $scaffParams;
  private $force;
  protected $fileExtension;
  protected $leftRegexGlobalRequiriment;
  protected $rightRegexGlobalRequiriment;

  public function __construct(){
  }

//[Getters]
  public function getResource(){
    return $this->resource??'';
  }

  public function getScaffParams(){
    return $this->scaffParams??[];
  }

  public function getForce(){
    return $this->force??null;
  }

  public function proyectRelatedRootPath(){
    return '""';
  }

  public function getFileExtension(){
    return $this->fileExtension??null;
  }
//[End Getters]

//[Setters]

  public function setResource($resource=null){
    $this->resource = $resource??null;
    return $this;
  }

  public function setScaffParams($scaffParams=null){
    $this->scaffParams = $scaffParams??[];
    return $this;
  }

  public function setForce($force=null){
    $this->force = $force??null;
    return $this;
  }

  public function setFileExtension($fileExtension=null){
    $this->fileExtension = $fileExtension??null;
    return $this;
  }
//[End Setters]

//[Stablishers]

  public function stablishResource($resource=null){
    return $this->setResource($resource??null);
  }

//[End Stablishers]

  public function force(){
    $this->propertyDefiner('force',true);
    return $this->setForce(true);
  }

  public function isForced(){
    return $this->getForce();
  }

  public function caseFixer($case=null,$value=null){
    $caseCallBacks = [
      'final'    => function($value){return $value;},
      'singular' => function($value){return $this->cvSingularCase($value);},
      'plural'   => function($value){return $this->cvPluralCase($value);},
      'camel'    => function($value){return $this->cvCamelCase($value);},
      'snake'    => function($value){return $this->cvSnakeCase($value);},
      'slug'     => function($value){return $this->cvSlugCase($value);},
      'studly'   => function($value){return $this->cvStudlyCase($value);},
      'human'    => function($value){return Str::slug($this->cvSlugCase($value),' ');},
      'title'    => function($value){return Str::title($value);},
      'title'    => function($value){return Str::title($value);},
      'lower'    => function($value){return strtolower($value);},
      'upper'    => function($value){return strtoupper($value);},
      'ucfirst'  => function($value){return ucfirst($value);},
    ];
    $currentCase = $caseCallBacks[$case]??null;
    if(!$currentCase){
      cvConsoler(cvWarning("case $case has no function to fix value $value")."\n");
      return $value;
    }
    return $currentCase($value);
  }
}

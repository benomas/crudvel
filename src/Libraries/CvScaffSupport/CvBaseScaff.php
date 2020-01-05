<?php

namespace Crudvel\Libraries\CvScaffSupport;
use Illuminate\Support\Str;

class CvBaseScaff
{
  use \Crudvel\Traits\CvPatronTrait;
  use \Crudvel\Traits\CvClosureCommandTrait;
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
      'singular' => function($value){return Str::singular($value);},
      'plural'   => function($value){return Str::plural($value);},
      'camel'    => function($value){return Str::camel($value);},
      'snake'    => function($value){return fixedSnake($value);},
      'slug'     => function($value){return fixedSlug($value);},
      'studly'   => function($value){return Str::studly($value);},
      'title'    => function($value){return Str::title($value);},
      'lower'    => function($value){return strtolower($value);},
      'upper'    => function($value){return strtoupper($value);},
    ];
    $currentCase = $caseCallBacks[$case]??null;
    if(!$currentCase){
      cvConsoler(cvWarning("case $case has no function to fix value $value")."\n");
      return $value;
    }
    return $currentCase($value);
  }
}

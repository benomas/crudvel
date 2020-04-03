<?php namespace Crudvel\Requests;

use Crudvel\Requests\ExternalRequestBuilder;

class ChildRequestBuilder{
  protected $parentResouce;
  protected $resource     = '';
  protected $class        = '';
  protected $path         = '';
  protected $rules        = [];
  protected $method       = null;
  protected $params       = [];

  public function __construct(ExternalRequestBuilder $parentResouce, $resource = null){
    if(!$parentResouce)
      cvConsoler('ExternalRequestBuilder inyection is required');

    $this->setParentResouce($parentResouce)->loadResource($resource);
  }

  public function loadResource($resource=null){
    if(!$resource)
      return $this;

    $this->setMethod($this->getParentResouce()->getMethod());

    if(is_array($resource))
      return $this->childFromArray($resource);

    return $this->childFromString($resource);
  }

  public function getResource(){
    return $this->resource??null;
  }

  public function setResource($resource=null){
    $this->resource = $resource??null;

    return $this;
  }

  public function getPath(): string{
    return $this->path??'';
  }

  public function setPath(string $path=''){
    $this->path = $path??'';

    return $this;
  }

  public function getRules(): array{
    return $this->rules??[];
  }

  public function setRules(array $rules=[]){
    $this->rules = $rules??[];

    return $this;
  }

  public function getPrimaryValue(){
    return $this->primaryValue??null;
  }

  public function setPrimaryValue($primaryValue=null){
    $this->primaryValue = $primaryValue??null;

    return $this;
  }

  public function getParentResouce(){
    return $this->parentResouce??null;
  }

  public function setParentResouce($parentResouce=null){
    $this->parentResouce = $parentResouce??null;

    return $this;
  }

  protected function childFromString(string $child = ''){
    if(class_exists($child))
      return $this->setClass($child)->setPath($this->pathCase(str_replace('Request', '', class_basename($child))).'.');

    if(!class_exists(($calculedClass = 'App\Http\Requests\\'.cvCaseFixer('singular|studly',$child).'Request')))
      return $this;

    return $this->setClass($calculedClass)->setPath($this->pathCase(str_replace('Request', '', class_basename($child))).'.');
  }

  protected function childFromArray(array $child = []){
    return $this;
  }

  protected function pathCase($path=''){
    return cvSnakeCase($path);
  }

  public function getClass(){
    return $this->class??null;
  }

  public function setClass($class=null){
    $this->class = $class??null;

    return $this;
  }

  public function getMethod(){
    return $this->method??null;
  }

  public function setMethod($method=null){
    $this->method = $method??null;

    return $this;
  }

  public function push(){
    return $this->getParentResouce()->pushChildRequest($this);
  }

  public function fixRules(){
    return $this->getClass()::staFixDepth($this->getClass()::{$this->getMethod()}()->fixRules(),$this->getPath());
  }

  public function getParams(){
    return $this->params??null;
  }

  public function setParams($params=null){
    $this->params = $params??null;

    return $this;
  }

  public function setParam($param=null,$value=null) {
    if(!$param)
      return $this;

    $this->params[$param] = $value;

    return $this;
  }
}

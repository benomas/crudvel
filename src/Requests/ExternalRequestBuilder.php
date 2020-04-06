<?php namespace Crudvel\Requests;


class ExternalRequestBuilder extends \Crudvel\Requests\ChildRequestBuilder{
  protected $selfRules       = [];
  protected $ExtraRules      = [];
  protected $childrenRequest = [];

  public function __construct($rules = []){
    $this->setSelfRules($rules)->setMethod('externalPostStoreRules');
  }

  public function getSelfRules():array{
    return $this->selfRules??[];
  }

  public function setSelfRules(array $selfRules=[]){
    $this->selfRules = $selfRules??[];

    return $this;
  }

  public function getExtraRules():array{
    return $this->ExtraRules??[];
  }

  public function setExtraRules(array $ExtraRules=[]){
    $this->ExtraRules = $ExtraRules??[];

    return $this;
  }

  public function getChildrenRequest(){
    return $this->childrenRequest??null;
  }

  public function setChildrenRequest($childrenRequest=null){
    $this->childrenRequest = $childrenRequest??null;
    return $this;
  }

  public function pushChildRequest($childRequest=null){
    $this->childrenRequest[] = $childRequest;

    return $this;
  }

  public function buildChild($resource=null){
    return new \Crudvel\Requests\ChildRequestBuilder($this,$resource);
  }

  public function buildAndPushChild($resource=null){
    return (new \Crudvel\Requests\ChildRequestBuilder($this,$resource))->push();
  }

  public function withChildren(...$resources){
    foreach($resources as $resource)
      (new \Crudvel\Requests\ChildRequestBuilder($this,$resource))->push();

    return $this;
  }

  public function fixWithChildren(...$resources){
    foreach($resources as $resource)
      (new \Crudvel\Requests\ChildRequestBuilder($this,$resource))->push();

    return $this->fixRules();
  }

  public function fixRules(){
    $rules = [$this->getExtraRules()];
    foreach($this->getChildrenRequest() as $child){
      $rules[]= $child->fixRules();
    }

    $rules = array_merge($this->getSelfRules(),...$rules);

    foreach($rules as $key=>$rule)
      foreach($this->getParams() as $param=>$value)
        $rules[$key] = str_replace("@$param@",$value,$rules[$key]);

    return $this->setRules($rules)->getRules();
  }
}

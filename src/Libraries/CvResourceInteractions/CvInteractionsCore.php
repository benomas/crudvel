<?php

namespace Crudvel\Libraries\CvResourceInteractions;

class CvInteractionsCore
{
  protected $modelCollectionInstance;
  protected $userModelCollectionInstance;
  protected $resource;
  protected $relatedResource;
  protected $relatedResourceRelation;
  protected $fields;
  protected $Key;
  protected $KeyValue;

  public function __construct(){

  }
// [Specific Logic]
  public function fixResource($resource){
    return $this->setResource(cvCaseFixer('snake|plural',$resource));
  }

  public function fixRelatedResourceRelation(){
    if (!$this->getModelCollectionInstance())
      return $this;

    $relacion = $this->getModelCollectionInstance()->{cvCaseFixer('camel|plural',$this->getRelatedResource())}();

    return $this->setRelatedResourceRelation($relacion);
  }
// [End Specific Logic]
// [Getters]
  public function getModelCollectionInstance(){
    return $this->modelCollectionInstance??null;
  }

  public function getResource(){
    return $this->resource??null;
  }

  public function getRelatedResource(){
    return $this->relatedResource??null;
  }

  public function getRelatedResourceRelation(){
    return $this->relatedResourceRelation??null;
  }

  public function getFields(){
    return $this->fields??null;
  }

  public function getKey(){
    return $this->Key??null;
  }

  public function getKeyValue(){
    return $this->KeyValue??null;
  }

  public function getUserModelCollectionInstance(){
    return $this->userModelCollectionInstance??null;
  }
// [End Getters]
// [Setters]
  public function setModelCollectionInstance($modelCollectionInstance=null){
    $this->modelCollectionInstance = $modelCollectionInstance??null;

    return $this;
  }

  public function setResource($resource=null){
    $this->resource = $resource??null;

    return $this;
  }

  public function setRelatedResource($relatedResource=null){
    $this->relatedResource = $relatedResource??null;

    return $this;
  }

  public function setRelatedResourceRelation($relatedResourceRelation=null){
    $this->relatedResourceRelation = $relatedResourceRelation??null;

    return $this;
  }

  public function setFields($fields=null){
    $this->fields = $fields??null;

    return $this;
  }

  public function setKey($Key=null){
    $this->Key = $Key??null;

    return $this;
  }

  public function setKeyValue($KeyValue=null){
    $this->KeyValue = $KeyValue??null;

    return $this;
  }

  public function setUserModelCollectionInstance($userModelCollectionInstance=null){
    $this->userModelCollectionInstance = $userModelCollectionInstance??null;

    return $this;
  }
// [End Setters]
}

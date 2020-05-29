<?php

namespace Crudvel\Libraries\CvAssociations;

class CvInteractionsCore
{
  protected $modelCollectionInstance;
  protected $resource;
  protected $fields;
  protected $Key;
  protected $KeyValue;

  public function __construct(){

  }

  public function getModelCollectionInstance(){
    return $this->modelCollectionInstance??null;
  }

  public function setModelCollectionInstance($modelCollectionInstance=null){
    $this->modelCollectionInstance = $modelCollectionInstance??null;

    return $this;
  }

  public function getResource(){
    return $this->resource??null;
  }

  public function setResource($resource=null){
    $this->resource = $resource??null;

    return $this;
  }

  public function getFields(){
    return $this->fields??null;
  }

  public function setFields($fields=null){
    $this->fields = $fields??null;

    return $this;
  }

  public function getKey(){
    return $this->Key??null;
  }

  public function setKey($Key=null){
    $this->Key = $Key??null;

    return $this;
  }

  public function getKeyValue(){
    return $this->KeyValue??null;
  }

  public function setKeyValue($KeyValue=null){
    $this->KeyValue = $KeyValue??null;

    return $this;
  }
}

<?php

namespace Crudvel\Libraries\CvResourceInteractions;

class CvSimpleRelationator extends \Crudvel\Libraries\CvResourceInteractions\CvInteractionsCore
{
  protected $fields;
  protected $toSync;
  protected $pivotColumns;
  protected $stamps = [
    'created_at',
    'updated_at',
    'created_by',
    'updated_by',
  ];

  public function __construct(){
    parent::__construct();
  }

  public function build(){
    if(!$this->getResource()){
      if(!$this->getModelCollectionInstance())
        return null;

      $this->fixResource(class_basename(get_class($this->getModelCollectionInstance())));
    }

    if(!$this->getRelatedResource())
      return null;

    if(!$this->getToSync())
      $this->fixToSync();

    if(!$this->getRelatedResourceRelation())
      $this->fixRelatedResourceRelation();

    return $this;
  }

  public function getData(){
    return $this->getFields()[$this->getSyncField()]??[];
  }

  public function cvSyncRelationateResource(){
    return $this->cvSync();
  }

  public function cvSync(){
    $this->getRelatedResourceRelation()->sync($this->getToSync());

    return $this;
  }

  public function getToSync(){
      return $this->toSync??null;
  }

  public function setToSync($toSync=null){
    $this->toSync = $toSync??null;

    return $this;
  }

  public function fixToSync(){
    //$toSync                  = cvGetSomeKeysAsList($this->getData());
    $toSync                  = cvGetSomeKeys($this->getData(),'id',...array_keys($this->getPivotColumns()));
    pdd($toSync);
    $toSyncWithPivoteColumns = [];

    if($this->getPivotColumns() && count($toSync)){
      $toSyncWithPivoteColumns = $this->fixPivotColumns($toSync);

      return $this->setToSync($toSyncWithPivoteColumns);
    }

    return $this->setToSync($toSync);
  }

  public function getFields(){
    return $this->fields??null;
  }

  public function setFields($fields=null){
    $this->fields = $fields??null;

    return $this;
  }

  public function getSyncField(){
    return cvCaseFixer('plural|snake',$this->getRelatedResource());
  }

  public function getPivotColumns(){
    return $this->pivotColumns??null;
  }

  public function setPivotColumns($pivotColumns=null){
    $this->pivotColumns = $pivotColumns??null;

    return $this;
  }

  public function getStamps(){
    return $this->stamps??null;
  }

  public function setStamps($stamps=null){
    $this->stamps = $stamps??null;

    return $this;
  }

  public function getFixedStamps(){
    if(!$this->getPivotColumns())
      return $this;

    $stamps = [];
    foreach($this->getStamps() AS $stampColumn){
      $hasStamp = $this->getPivotColumns()[$stampColumn] ?? null;

      if($hasStamp)
        $stamps[$stampColumn] = $this->getFields()[$stampColumn] ?? null;

    }

    return $stamps;
  }

  public function fixPivotColumns($toSync){
    pdd($toSync);
    foreach($this->getPivotColumns() as $pivotColumn){

    }

    return array_merge($this->getFixedStamps());
  }
}

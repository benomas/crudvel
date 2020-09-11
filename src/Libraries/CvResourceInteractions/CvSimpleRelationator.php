<?php

namespace Crudvel\Libraries\CvResourceInteractions;
use Carbon\Carbon;

class CvSimpleRelationator extends \Crudvel\Libraries\CvResourceInteractions\CvInteractionsCore
{
  protected $fields;
  protected $toSync;
  protected $pivotColumns;
  protected $syncField;

  protected $stamps = [
    'created_at'=>null,
    'updated_at'=>null,
    'created_by'=>null,
    'updated_by'=>null,
  ];

  public function __construct(){
    parent::__construct();
  }
// [Specific Logic]
  public function build(){
    if(!$this->getResource()){
      if(!$this->getModelCollectionInstance())
        return null;

      $this->fixResource(class_basename(get_class($this->getModelCollectionInstance())));
    }

    if(!$this->getRelatedResource())
      return null;

    if(!$this->getSyncField())
      $this->fixSyncField();

    if(!$this->getToSync())
      $this->fixToSync();

    if(!$this->getRelatedResourceRelation())
      $this->fixRelatedResourceRelation();

    return $this;
  }
  public function cvDetacher(){
    $this->getRelatedResourceRelation()->detach(array_keys($this->getToSync()));

    return $this;
  }

  public function cvAttacher(){
    $this->getRelatedResourceRelation()->detach(array_keys($this->getToSync()));
    $this->getRelatedResourceRelation()->attach(array_keys($this->getToSync()));

    return $this;
  }

  public function cvSyncRelationateResource(){
    return $this->CvDetacher()->cvSync();
  }

  public function cvSync(){
    $this->getRelatedResourceRelation()->sync($this->getToSync());

    return $this;
  }

  public function fixToSync(){
    if($this->getPivotColumns()){
      $toSync = cvGetSomeKeys($this->getData(),'id',...array_diff(array_keys($this->getPivotColumns()),$this->getStamps()));

      if(!$toSync)
        return $this->setToSync($toSync);


      $toSync = $this->fixPivotColumns($toSync);

      return $this->setToSync($toSync);
    }
    $toSync = cvGetSomeKeysAsList($this->getData());

    return $this->setToSync($toSync);
  }

  public function fixPivotColumns($toSync){
    foreach($this->loadStamps()->getStamps() as $stamp=>$value){
      if(!in_array($stamp,array_keys($this->getPivotColumns())))
        continue;

      foreach($toSync AS $position=>$rowValue)
        $toSync[$position][$stamp] = $value;
    }

    return $toSync;
  }

  public function fixSyncField(){
    return $this->setSyncField(cvCaseFixer('plural|snake',$this->getRelatedResource()));
  }
// [End Specific Logic]
// [Getters]
  public function getData(){
    return $this->getFields()[$this->getSyncField()]??[];
  }

  public function getToSync(){
    return $this->toSync??null;
  }

  public function getFields(){
    return $this->fields??null;
  }

  public function getPivotColumns(){
    return $this->pivotColumns??null;
  }

  public function getStamps(){
    return $this->stamps??null;
  }

  public function loadStamps(){
    $now = (new Carbon())->now();
    return $this->setStamps([
      'created_at'=>$now,
      'updated_at'=>$now,
      'created_by'=>$this->getUserModelCollectionInstance()->id??null,
      'updated_by'=>$this->getUserModelCollectionInstance()->id??null,
    ]);
  }

  public function getSyncField(){
    return $this->syncField??null;
  }
// [End Getters]
// [Setters]
  public function setToSync($toSync=null){
    $this->toSync = $toSync??null;

    return $this;
  }

  public function setFields($fields=null){
    $this->fields = $fields??null;

    return $this;
  }

  public function setPivotColumns($pivotColumns=null){
    $this->pivotColumns = $pivotColumns??null;

    return $this;
  }

  public function setStamps($stamps=null){
    $this->stamps = $stamps??null;

    return $this;
  }

  public function setSyncField($syncField=null){
    $this->syncField = $syncField??null;

    return $this;
  }

  public function setFixedStamps($fixedStamps=null){
    $this->fixedStamps = $fixedStamps??null;

    return $this;
  }
// [End Setters]
}

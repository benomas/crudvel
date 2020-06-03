<?php

namespace Crudvel\Libraries\CvResourceInteractions;

class CvAssociator extends \Crudvel\Libraries\CvResourceInteractions\CvInteractionsCore{
  protected $relatedResourceModelBuilderInstance;
  protected $associatedColumns   = 'id';
  protected $associatedData      = [];
  protected $associatedFixedData = [];
  protected $foreingColumn;

  public function __construct(){
    parent::__construct();
  }

  public function build(){
    if(!$this->getResource()){
      if(!$this->getModelCollectionInstance())
        return null;

      $this->fixResource(class_basename(get_class($this->getModelCollectionInstance())));
    }

    if(!$this->getForeingColumn())
      $this->fixForeingColumn($this->getResource());

    if(!$this->getAssociatedColumns())
      $this->fixAssociatedColumns();

    if(!$this->getRelatedResource())
      return null;

    $this->fixAssociatedData();

    if(!$this->getRelatedResourceRelation())
      $this->fixRelatedResourceRelation();

    if(!$this->makeDoRelatedResourceModelBuilderInstance())
      return null;

    return $this;
  }

  public function cvDissociateResource(){
    if($this->getRelatedResourceRelation())
      foreach($this->getRelatedResourceRelation()->get() as $oldRelatedResource){
        $oldRelatedResource->{$this->getForeingColumn()} = null;

        if(!$oldRelatedResource->save())
          return false;
      }

    return true;
  }

  public function cvAssociateResource(){
    if(!$this->cvDissociateResource())
      return false;

    foreach($this->getRelatedResourceModelBuilderInstance()->get() as $currentResource){
      $currentResource->{$this->getForeingColumn()} = $this->getModelCollectionInstance()->id;

      if(!$currentResource->save())
        return false;
    }

    return true;
  }

  public function getRelatedResourceModelBuilderInstance(){
    return $this->relatedResourceModelBuilderInstance??null;
  }

  public function setRelatedResourceModelBuilderInstance($relatedResourceModelBuilderInstance=null){
    $this->relatedResourceModelBuilderInstance = $relatedResourceModelBuilderInstance??null;

    return $this;
  }

  public function makeDoRelatedResourceModelBuilderInstance(){
    if($this->getRelatedResourceModelBuilderInstance())
      return $this;

    $relatedResourceModel = '\App\Models\\'.cvCaseFixer('singular|studly',$this->getRelatedResource());

    return $this->setRelatedResourceModelBuilderInstance($relatedResourceModel::keys($this->getAssociatedFixedData()));
  }

  public function getAssociatedColumns(){
    return $this->associatedColumns??null;
  }

  public function setAssociatedColumns($associatedColumns=null){
    $this->associatedColumns = $associatedColumns??null;

    return $this;
  }

  public function fixAssociatedColumns(){
    return $this->setAssociatedColumns('id');
  }

  public function getAssociatedData(){
    return $this->associatedData??[];
  }

  public function setAssociatedData($associatedData=[]){
    $this->associatedData = $associatedData??[];

    return $this;
  }

  public function getAssociatedFixedData(){
    return $this->associatedFixedData??null;
  }

  public function setAssociatedFixedData($associatedFixedData=null){
    $this->associatedFixedData = $associatedFixedData??null;

    return $this;
  }

  public function fixAssociatedData(){
    return $this->setAssociatedFixedData(cvGetSomeKeysAsList($this->getAssociatedData(),$this->getAssociatedColumns()));
  }

  public function getForeingColumn(){
    return $this->foreingColumn??null;
  }

  public function setForeingColumn($foreingColumn=null){
    $this->foreingColumn = $foreingColumn??null;

    return $this;
  }

  public function fixForeingColumn($resource=null){
    return $this->setForeingColumn(cvCaseFixer('snake|singular',$resource).'_id');
  }
}

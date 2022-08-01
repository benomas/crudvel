<?php

namespace Crudvel\Libraries\CvResourceInteractions;

class CvRelationator extends \Crudvel\Libraries\CvResourceInteractions\CvInteractionsCore
{
  protected $fields;
  protected $toDetach;
  protected $toAttach;
  protected $toSync;

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

    if(!$this->getToDetach())
      $this->fixToDetach();

    if(!$this->getToAttach())
      $this->fixToAttach();

    if(!$this->getToSync())
      $this->fixToSync();

    if(!$this->getRelatedResourceRelation())
      $this->fixRelatedResourceRelation();

    return $this;
  }

  public function cvRelationateResource(){
    return $this->cvDetacher()->cvAttacher();
  }

  public function cvSyncRelationateResource(){
    return $this->cvSync();
  }

  public function cvDetacher(){
    $this->getRelatedResourceRelation()->detach($this->getToDetach());

    return $this;
  }

  public function cvAttacher(){
    $toDetach = $this->getToAttach();

    foreach($this->getToAttach() as $pos=>$val){
      if(is_array($val)){
        $toDetach = array_keys($this->getToAttach());
        break;
      }
    }

    $this->getRelatedResourceRelation()->detach($toDetach);
    $this->getRelatedResourceRelation()->attach($this->getToAttach());

    return $this;
  }

  public function cvSync(){
    $this->getRelatedResourceRelation()->sync($this->getToSync());

    return $this;
  }

  public function getToDetach(){
    return $this->toDetach??[];
  }

  public function setToDetach($toDetach=null){
    $this->toDetach = $toDetach??[];

    return $this;
  }

  public function fixToDetach(){
    $detachAttachField = $this->getFields()[$this->getDetachAttachField()]??[];
    $toDetach = cvGetSomeKeysAsList($detachAttachField['detach']??[]);

    return $this->setToDetach($toDetach);
  }

  public function getToAttach(){
    return $this->toAttach??[];
  }

  public function setToAttach($toAttach=null){
    $this->toAttach = $toAttach??[];

    return $this;
  }

  public function fixToAttach(){
    $detachAttachField = $this->getFields()[$this->getDetachAttachField()]??[];
    $toAttach = cvGetSomeKeysAsList($detachAttachField['attach']??[]);

    return $this->setToAttach($toAttach);
  }

  public function getToSync(){
      return $this->toSync??null;
  }

  public function setToSync($toSync=null){
    $this->toSync = $toSync??null;

    return $this;
  }

  public function fixToSync(){
    $toSync = cvGetSomeKeysAsList(($this->getFields()[$this->getSyncField()]??[]));
    return $this->setToSync($toSync);
  }

  public function getFields(){
    return $this->fields??null;
  }

  public function setFields($fields=null){
    $this->fields = $fields??null;

    return $this;
  }

  public function getDetachAttachField(){
    return cvCaseFixer('plural|snake',$this->getRelatedResource()).'_detach_attach';
  }

  public function getAttachField(){
    return cvCaseFixer('plural|snake',$this->getRelatedResource()).'_attach';
  }

  public function getSyncField(){
    return cvCaseFixer('plural|snake',$this->getRelatedResource()).'_sync';
  }
}

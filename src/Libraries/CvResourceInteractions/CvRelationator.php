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
    return $this->CvDetacher()->CvAttacher();
  }

  public function cvSyncRelationateResource(){
    return $this->cvSync();
  }

  public function CvDetacher(){
    $this->getRelatedResourceRelation()->detach($this->getToDetach());

    return $this;
  }

  public function CvAttacher(){
    $this->getRelatedResourceRelation()->detach($this->getToAttach());
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
    $toDetach = cvGetSomeKeysAsList(($this->getFields()[$this->getDetachField()]??[]));

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
    $toAttach = cvGetSomeKeysAsList(($this->getFields()[$this->getAttachField()]??[]));

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

  public function getDetachField(){
    return cvCaseFixer('plural|snake',$this->getRelatedResource()).'_detach';
  }

  public function getAttachField(){
    return cvCaseFixer('plural|snake',$this->getRelatedResource()).'_attach';
  }

  public function getSyncField(){
    return cvCaseFixer('plural|snake',$this->getRelatedResource()).'_sync';
  }
}

<?php

namespace Crudvel\Libraries\CvResourceInteractions;

class CvRelationator extends \Crudvel\Libraries\CvResourceInteractions\CvInteractionsCore
{
  protected $fields;
  protected $toDetach;
  protected $toAttach;

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

    if(!$this->getRelatedResourceRelation())
      $this->fixRelatedResourceRelation();

    return $this;
  }

  public function cvRelationateResource(){
    return $this->CvDetacher()->CvAttacher();
  }

  public function cvSyncRelationateResource(){
    return $this->CvSync();
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

  public function CvSync(){
    $this->getRelatedResourceRelation()->sync($this->getToAttach());

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
    $toDetach = cvGetSomeKeysAsList(($this->getFields()[cvCaseFixer('plural|snake',$this->getRelatedResource()).'_detach']??[]));
    $this->setToDetach($toDetach);

    return $this;
  }

  public function getToAttach(){
    return $this->toAttach??[];
  }

  public function setToAttach($toAttach=null){
    $this->toAttach = $toAttach??[];

    return $this;
  }

  public function fixToAttach(){
    $toAttach = cvGetSomeKeysAsList(($this->getFields()[cvCaseFixer('plural|snake',$this->getRelatedResource()).'_attach']??[]));
    $this->setToAttach($toAttach);

    return $this;
  }

  public function getFields(){
    return $this->fields??null;
  }

  public function setFields($fields=null){
    $this->fields = $fields??null;

    return $this;
  }
}

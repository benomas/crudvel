<?php

namespace Crudvel\Libraries\CvResourceInteractions;

class CvRelationInteractions extends \Crudvel\Libraries\CvResourceInteractions\CvInteractionsCore
{
  public function CvAttacher(){
    $toAttach = cvGetSomeKeysAsList($this->getFields()[cvCaseFixer('plural|snake',$this->getResource()).'_attach']??[]);
    $this->getModelCollectionInstance()->{cvCaseFixer('plural|camel',$this->getResource())}()->detach($toAttach);
    $this->getModelCollectionInstance()->{cvCaseFixer('plural|camel',$this->getResource())}()->attach($toAttach);

    return $this->getModelCollectionInstance();
  }
}

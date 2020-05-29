<?php

namespace Crudvel\Libraries\CvAssociations;

class CvRelationInteractions extends \Crudvel\Libraries\CvAssociations\CvInteractionsCore
{
  public function CvAttacher(){
    $toAttach = cvGetSomeKeysAsList($this->getFields()[cvCaseFixer('plural|snake',$this->getResource()).'_attach']??[]);
    $this->getModelCollectionInstance()->{cvCaseFixer('plural|camel',$this->getResource())}()->detach($toAttach);
    $this->getModelCollectionInstance()->{cvCaseFixer('plural|camel',$this->getResource())}()->attach($toAttach);

    return $this->getModelCollectionInstance();
  }
}

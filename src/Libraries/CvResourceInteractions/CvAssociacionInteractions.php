<?php

namespace Crudvel\Libraries\CvAssociations;

class CvAssociacionInteractions extends \Crudvel\Libraries\CvAssociations\CvInteractionsCore{
  protected $relatedResource;
  protected $relatedResourceModelBuilderInstance;
  protected $dataToBeFilled;
  
  public function __construct(){
    parent::__construct();
  }

  public function build(){
    if(!$this->setRelatedResource())
      return null;

    if(!$this->makeRelatedResourceModelBuilderInstance())
      return null;
    
    return $this;
  }

  public function CvAssociateResource(){
    $relatedResourceModel = '\App\Models\\'.cvCaseFixer('singular|studly',$relatedResource);

    foreach($relatedResourceModel::keys($relatedResourceKeys)->get() as $currentResource)
      if(!$currentResource->fill($keyValueResource)->save())
        return false;

    return true;
  }

  public function getRelatedResource(){
    return $this->relatedResource??null;
  }

  public function setRelatedResource($relatedResource=null){
    $this->relatedResource = $relatedResource??null;

    return $this;
  }

  public function getRelatedResourceModelBuilderInstance(){
    return $this->relatedResourceModelBuilderInstance??null;
  }

  public function setRelatedResourceModelBuilderInstance($relatedResourceModelBuilderInstance=null){
    $this->relatedResourceModelBuilderInstance = $relatedResourceModelBuilderInstance??null;

    return $this;
  }

  public function makeRelatedResourceModelBuilderInstance(){
    if($this->getRelatedResourceModelBuilderInstance())
      return $this;

    $this->relatedResourceModelBuilderInstance = '\App\Models\\'.cvCaseFixer('singular|studly',$relatedResource);

    return $this;
  }
}

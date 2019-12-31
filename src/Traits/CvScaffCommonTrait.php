<?php

namespace Crudvel\Traits;

trait CvScaffCommonTrait
{
//[Getters]
  public function getRelatedFilePath(){
    return $this->relatedFilePath;
  }

  public function getAbsolutFilePath(){
    return $this->absolutFilePath??null;
  }

  public function getFile(){
    return $this->file??null;
  }
//[End Getters]

//[Setters]
  public function setRelatedFilePath($relatedFilePath){
    $this->relatedFilePath = $relatedFilePath;
    return $this;
  }

  public function setAbsolutFilePath($absolutFilePath=null){
    $this->absolutFilePath = $absolutFilePath??null;
    return $this;
  }
  public function setFile($file=null){
    $this->file = $file??null;
    return $this;
  }
//[End Setters]

//[Stablishers]
  protected function stablishAbsolutFilePath(){
    return $this->setAbsolutFilePath(base_path($this->getRelatedFilePath()));
  }
//[End Stablishers]

  public function processPaths(){
    return $this->stablishAbsolutFilePath();
  }
}

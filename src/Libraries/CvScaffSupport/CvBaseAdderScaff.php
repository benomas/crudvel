<?php

namespace Crudvel\Libraries\CvScaffSupport;
use Illuminate\Support\Str;
use \Crudvel\Interfaces\CvScaffInterface;

abstract class CvBaseAdderScaff extends \Crudvel\Libraries\CvScaffSupport\CvBaseScaff implements CvScaffInterface
{
  use \Crudvel\Traits\CvScaffCommonTrait;
  protected $relatedFilePath;
  protected $absolutFilePath;
  protected $file;
  public function __construct(...$propertyRewriter){
    parent::__construct(...$propertyRewriter);
  }

//[Getters]
//[End Getters]

//[Setters]
//[End Setters]

//[Stablishers]
//[End Stablishers]
  protected function loadFile(){
    $absolutFilePath = $this->getAbsolutFilePath();
    if(!file_exists($absolutFilePath))
      throw new \Exception("file $absolutFilePath doesnt exist");

    try{
      $file = file_get_contents($absolutFilePath);
    }catch(\Exception $e){
      throw new \Exception("file from $absolutFilePath, cant be loaded");
    }
    return $this->setFile($file);
  }

  abstract protected function fixFile();

  protected function inyectFixedFile(){
    try{
      file_put_contents($this->getAbsolutFilePath(), $this->getFile());
      cvConsoler(cvGreenTC('file was updated')."\n");
    }catch(\Exception $e){
      throw new \Exception('Error '.$this->getAbsolutFilePath().' cant be created');
    }
    return $this;
  }

  public function scaff() {
    return $this->processPaths()
      ->loadFile()
      ->fixFile()
      ->inyectFixedFile();
  }
}

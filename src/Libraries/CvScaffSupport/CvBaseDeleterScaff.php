<?php

namespace Crudvel\Libraries\CvScaffSupport;
use Illuminate\Support\Str;
use \Crudvel\Interfaces\CvScaffInterface;

class CvBaseDeleterScaff extends \Crudvel\Libraries\CvScaffSupport\CvBaseScaff implements CvScaffInterface
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
  protected function stablishAbsolutFilePath(){
    return $this->setAbsolutFilePath(base_path(
        $this->getRelatedFilePath()).
        Str::studly(Str::singular($this->getResource()).
        'Controller.php'
      )
    );
  }
//[End Stablishers]

  protected function deleteFile(){
    if(!file_exists($this->getAbsolutFilePath())){
      cvConsoler(cvBrownTC($this->getAbsolutFilePath().' file doest exist')."\n");
      return $this;
    }
    try{
      unlink($this->getAbsolutFilePath());
    }catch(\Exception $e){
      throw new \Exception('Error '.$this->getAbsolutFilePath().' cant be deleted');
    }
    return $this;
  }

  public function scaff() {
    return $this->processPaths()->deleteFile();
  }

}

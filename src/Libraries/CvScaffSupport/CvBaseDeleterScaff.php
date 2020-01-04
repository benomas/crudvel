<?php

namespace Crudvel\Libraries\CvScaffSupport;
use Illuminate\Support\Str;
use \Crudvel\Interfaces\CvScaffInterface;

abstract class CvBaseDeleterScaff extends \Crudvel\Libraries\CvScaffSupport\CvBaseScaff implements CvScaffInterface
{
  use \Crudvel\Traits\CvScaffCommonTrait;
  use \Crudvel\Traits\CvScaffBaseTrait;
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
    return $this->setAbsolutFilePath(
      base_path($this->getRelatedFilePath()).$this->selfRepresentation().$this->getFileExtension()
    );
  }
//[End Stablishers]

  protected function deleteFile(){
    if(!file_exists($this->getAbsolutFilePath())){
      cvConsoler(
        cvWarning('File ').
        cvNegative($this->getAbsolutFilePath()).
        cvWarning('  doest exist at ').
        cvNegative(get_class($this)).
        "\n"
      );
      return $this;
    }
    try{
      unlink($this->getAbsolutFilePath());
    }catch(\Exception $e){
      throw new \Exception('Error '.$this->getAbsolutFilePath().' cant be deleted');
    }
    cvConsoler(
      cvcvPositive('File ').
      cvInfo($this->getAbsolutFilePath()).
      cvcvPositive('  was deleted by ').
      cvInfo(get_class($this)).
      "\n"
    );
    return $this;
  }

  public function scaff() {
    return $this->processPaths()->deleteFile();
  }

}

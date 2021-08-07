<?php

namespace Crudvel\Libraries\CvScaffSupport\Back\Migration;

use \Crudvel\Interfaces\CvScaffInterface;
use Illuminate\Support\Str;
use Carbon\Carbon;

class CvDeleterMigrationScaff extends \Crudvel\Libraries\CvScaffSupport\Back\CvBaseDeleterScaff implements CvScaffInterface
{
  protected $relatedFilePath   = 'database/migrations/';
  public function __construct(){
    parent::__construct();
  }
//[Getters]
//[End Getters]

//[Setters]
//[End Setters]

//[Stablishers]
  protected function stablishAbsolutFilePath(){
    $fileName=null;
    foreach((array) assetsMap(base_path($this->getRelatedFilePath())) as $migration){
      $pos=strpos($migration,'_create_'.$this->selfRepresentation().'_table.php');
      if($pos !== null && $pos !== false){
        $fileName = $migration;
        break;
      }
    }
    if(!$fileName)
      return $this->setAbsolutFilePath(null);
    return $this->setAbsolutFilePath(base_path($this->getRelatedFilePath()).$fileName);
  }
//[End Stablishers]
  protected function selfRepresentation(){
    return cvSnakeCase(Str::plural($this->getResource()));
  }
}
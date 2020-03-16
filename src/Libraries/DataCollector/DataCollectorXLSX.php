<?php
namespace Crudvel\Libraries\DataCollector;
use \Maatwebsite\Excel\Facades\Excel;
use Crudvel\Interfaces\DataCollector\{DataCollectorInterface};

Class DataCollectorXLSX implements DataCollectorInterface {

  public $count          = 0;
  public $sourceLocation = '';

  public function __construct(){
    $this->init();
  }

  //Open source and count total data
  public function init($sourceLocation){

  }

  public function getTotalRecordsAttr(){

  }

  public function getCollectionFromSource(){

  }

  public function getChunkedCollection($chuckSize, $pageNumber){

  }

  public function getCurrentPageNumber(){

  }


}

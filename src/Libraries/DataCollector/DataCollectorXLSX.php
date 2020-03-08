<?php
namespace Crudvel\Libraries\DataCollector;
use \Maatwebsite\Excel\Facades\Excel;

Class DataCollectorXLSX implements \Crudvel\Interfaces\DataCollectorInterface {

  public $totalRecords = 0;
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

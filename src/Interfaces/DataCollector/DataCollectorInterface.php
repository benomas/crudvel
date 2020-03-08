<?php
namespace Crudvel\Interfaces;

interface DataCollectorInterface{
  // init import , open file, open source and set totalRecords
  public function init();
  public function getTotalRecordsAttr();
  public function getCollectionFromSource();
  public function getChunkedCollection($chuckSize, $pageNumber);
  public function getCurrentPageNumber();
}

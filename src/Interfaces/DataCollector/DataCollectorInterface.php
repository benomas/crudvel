<?php
namespace Crudvel\Interfaces\DataCollector;
interface DataCollectorInterface{
  public function getChunkedCollection():array;
  public function getNextChunk():array;
  public function getPage();
  public function getChuckSize();
  public function getOffSet();
  public function getCount();
  public function setPage($page=1);
  public function setCount($count=0);
  public function setChuckSize($chuckSize=10);
  public function loadContextData($contextData=null);
  public function counter();
  public function incresePage();
  public function init();
}

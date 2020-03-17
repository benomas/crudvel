<?php
namespace Crudvel\Interfaces\DataCollector;
interface DataCollectorInterface{
  public function getChunkedCollection():array;
  public function getNextChunk():array;
  public function getPage();
  public function getChuckSize();
  public function getOffSet();
  public function getCount();
  public function setPage($page);
  public function setCount($count);
  public function counter();
  public function setChuckSize($chuckSize);
  public function incresePage();
  public function init();
}

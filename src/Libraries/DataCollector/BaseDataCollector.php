<?php
namespace Crudvel\Libraries\DataCollector;

Class BaseDataCollector{
  protected $page              = 0;
  protected $chuckSize         = 100;
  protected $count             = 0;
  protected $dataCallerInstace = null;

  protected function responseAndAdvace(Array $arraySegment):Array{
    $this->incresePage();
    return $arraySegment;
  }

  public function getPage(){
    return $this->page??0;
  }

  public function getChuckSize(){
    return $this->chuckSize??100;
  }

  public function getOffSet(){
    return $this->getPage() * $this->getChuckSize();
  }

  public function getCount(){
    return $this->count??0;
  }

  public function setPage($page=0){
    $this->page = $page??0;

    return $this;
  }

  public function setCount($count=0){
    $this->count = $count??0;

    return $this;
  }

  public function setChuckSize($chuckSize=100){
    $this->chuckSize = $chuckSize??100;

    return $this;
  }

  public function incresePage(){
    $this->page ++;

    return $this;
  }

  public function nextSegment(){
    $nextSegment  =  $this->getOffSet() + $this->getChuckSize();

    if($nextSegment > $this->getCount())
      return $this->getCount() - $this->getOffSet();

    return $this->getChuckSize();
  }
}

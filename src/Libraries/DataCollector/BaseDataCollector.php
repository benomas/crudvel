<?php
namespace Crudvel\Libraries\DataCollector;

Class BaseDataCollector{
  protected int $page      = 0;
  protected int $chuckSize         = 100;
  protected int $count             = 0;
  protected     $dataCallerInstance = null;

// [Specific Logic]
  protected function responseAndAdvance(Array $arraySegment): array {
    $this->incresePage();
    return $arraySegment;
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
// [End Specific Logic]

// [Getters]
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
// [End Getters]

// [Setters]
  public function setPage($page=0): static {
    $this->page = $page??0;

    return $this;
  }

  public function setCount($count=0): static {
    $this->count = $count??0;

    return $this;
  }

  public function setChuckSize($chuckSize=100): static {
    $this->chuckSize = $chuckSize??100;

    return $this;
  }
// [End Setters]
}

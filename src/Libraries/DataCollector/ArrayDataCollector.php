<?php
namespace Crudvel\Libraries\DataCollector;

use Crudvel\Interfaces\DataCollector\{DataCollectorInterface,ArrayDataCollectorInterface};
use Crudvel\Interfaces\DataCaller\ArrayDataCallerInterface;

Class ArrayDataCollector extends BaseDataCollector implements DataCollectorInterface,ArrayDataCollectorInterface {
  protected $arrayData = [];

  public function __construct(ArrayDataCallerInterface $dataCallerInstace){
    $this->setDataCallerInstace($dataCallerInstace);
  }

// [Specific Logic]
  //Open source and count total data
  public function init(){
    $this->getDataCallerInstace()->loadArrayData();
    $this->setCount($this->counter());
  }

  public function counter(){
    return count($this->getArrayData());
  }
  
  public function loadContextData($contextData=null){
    return $this->setArrayData($contextData);
  }
// [End Specific Logic]

// [Getters]
  public function getChunkedCollection($chuckSize = 100, $pageNumber = 0):Array {
    $offset = $pageNumber * $chuckSize;

    if ($offset >= $this->getCount())
      return [];

    return array_slice($this->getArrayData(), $offset, $this->nextSegment());
  }

  public function getNextChunk($next=null):Array {
    if ($this->getOffSet() >= $this->getCount())
      return [];

    $arraySegment = array_slice($this->getArrayData(), $this->getOffSet(), $this->nextSegment());

    if(is_callable($next))
      if(!$next($arraySegment))
        throw new \Exception('next callback fail');

    return $this->responseAndAdvace($this->getDataCallerInstace()->dataTransform($arraySegment));
  }

  public function getArrayData(){
    return $this->arrayData??[];
  }

  public function getDataCallerInstace():ArrayDataCallerInterface{
    return $this->dataCallerInstace??null;
  }

// [End Getters]

// [Setters]

  public function setArrayData($arrayData=[]){
    $this->arrayData = $arrayData??[];

    return $this;
  }

  public function setDataCallerInstace(ArrayDataCallerInterface $dataCallerInstace){
    $this->dataCallerInstace = $dataCallerInstace??null;

    return $this;
  }
// [End Setters]
}
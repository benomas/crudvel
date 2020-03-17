<?php
namespace Crudvel\Libraries\DataCollector;

use Crudvel\Interfaces\DataCollector\{DataCollectorInterface,ArrayDataCollectorInterface};
use Crudvel\Interfaces\DataCaller\ArrayDataCallerInterface;

Class ArrayDataCollector extends BaseDataCollector implements DataCollectorInterface,ArrayDataCollectorInterface {
  protected $arrayData = [];

  public function __construct(ArrayDataCallerInterface $dataCallerInstace){
    $this->setDataCallerInstace($dataCallerInstace);
  }

  //Open source and count total data
  public function init(){
    $this->getDataCallerInstace()->loadArrayData();
    $this->setCount($this->counter());
  }

  public function getChunkedCollection($chuckSize = 100, $pageNumber = 0):Array {
    $offset = $pageNumber * $chuckSize;

    if ($offset >= $this->getCount())
      return [];

    return array_slice($this->getArrayData(), $offset, $offset+$chuckSize);
  }

  public function getNextChunk($next=null):Array {
    if ($this->getOffSet() >= $this->getCount())
      return [];

    $arraySegment = array_slice($this->getArrayData(), $this->getOffSet(), $this->getOffSet() + $this->getChuckSize());

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

  public function setArrayData($arrayData=[]){
    $this->arrayData = $arrayData??[];

    return $this;
  }

  public function setDataCallerInstace(ArrayDataCallerInterface $dataCallerInstace){
    $this->dataCallerInstace = $dataCallerInstace??null;

    return $this;
  }

  public function counter(){
    return count($this->getArrayData());
  }
}

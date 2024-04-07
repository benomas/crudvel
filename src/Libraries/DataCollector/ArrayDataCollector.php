<?php
namespace Crudvel\Libraries\DataCollector;

use Crudvel\Interfaces\DataCollector\{DataCollectorInterface,ArrayDataCollectorInterface};
use Crudvel\Interfaces\DataCaller\ArrayDataCallerInterface;

Class ArrayDataCollector extends BaseDataCollector implements DataCollectorInterface,ArrayDataCollectorInterface {
  protected array $arrayData = [];

  public function __construct(ArrayDataCallerInterface $dataCallerInstance){
    $this->setDataCallerInstance($dataCallerInstance);
  }

// [Specific Logic]
  //Open source and count total data
  public function init(): void {
    $this->getDataCallerInstance()->loadArrayData();
    $this->setCount($this->counter());
  }

  public function counter(): int {
    return count($this->getArrayData());
  }

  public function loadContextData($contextData=null): ArrayDataCollector|static {
    return $this->setArrayData($contextData);
  }
// [End Specific Logic]

// [Getters]
  public function getChunkedCollection($chuckSize = 100, $pageNumber = 0): array {
    $offset = $pageNumber * $chuckSize;

    if ($offset >= $this->getCount())
      return [];

    return array_slice($this->getArrayData(), $offset, $this->nextSegment());
  }

  public function getNextChunk($next=null): array {
    if ($this->getOffSet() >= $this->getCount())
      return [];

    $arraySegment = array_slice($this->getArrayData(), $this->getOffSet(), $this->nextSegment());

    if(is_callable($next))
      if(!$next($arraySegment))
        throw new \Exception('next callback fail');

    return $this->responseAndAdvance($this->getDataCallerInstance()->dataTransform($arraySegment));
  }

  public function getArrayData(){
    return $this->arrayData??[];
  }

  public function getDataCallerInstance():ArrayDataCallerInterface{
    return $this->dataCallerInstance;
  }

// [End Getters]

// [Setters]

  public function setArrayData($arrayData=[]): static {
    $this->arrayData = $arrayData??[];

    return $this;
  }

  public function setDataCallerInstance(ArrayDataCallerInterface $dataCallerInstance): static {
    $this->dataCallerInstance = $dataCallerInstance??null;

    return $this;
  }
// [End Setters]
}

<?php
namespace Crudvel\Libraries\DataCollector;

use Crudvel\Interfaces\DataCollector\{DataCollectorInterface,ModelDataCollectorInterface};
use Crudvel\Interfaces\DataCaller\ModelDataCallerInterface;

Class ModelDataCollector extends BaseDataCollector implements DataCollectorInterface,ModelDataCollectorInterface {
  protected $modelBuilderInstance = [];

  public function __construct(ModelDataCallerInterface $dataCallerInstance){
    $this->setDataCallerInstance($dataCallerInstance);
  }

// [Specific Logic]
  //Open source and count total data
  public function init(): void {
    $this->getDataCallerInstance()->loadModelSrc();
    $this->setCount($this->counter());
  }

  public function counter(){
    $modelBuilderInstance = $this->getModelBuilderInstance();

    return $modelBuilderInstance ? $modelBuilderInstance->count(): 0;
  }

  public function loadContextData($contextData=null): ModelDataCollector|static {
    if($contextData && class_exists($contextData))
      return $this->setModelBuilderInstance($contextData::noFilters());

    return $this->setModelBuilderInstance(null);
  }
// [End Specific Logic]

// [Getters]
  // TODO : complete this implementation
  public function getChunkedCollection($chuckSize = 100, $pageNumber = 0): array {
    $offset = $pageNumber * $chuckSize;

    if ($offset >= $this->getCount())
      return [];

    return kageBunshinNoJutsu($this->getModelBuilderInstance())->offset($offset)->limit($chuckSize)->get()->toArray();
  }

  public function getNextChunk($next=null): array {
    if ($this->getOffSet() >= $this->getCount())
      return [];

    $arraySegment = kageBunshinNoJutsu($this->getModelBuilderInstance())->offset($this->getOffSet())->limit($this->nextSegment())->get()->toArray();

    if(is_callable($next))
      if(!$next($arraySegment))
        throw new \Exception('next callback fail');

    return $this->responseAndAdvance($this->getDataCallerInstance()->dataTransform($arraySegment));
  }

  public function getDataCallerInstance():ModelDataCallerInterface{
    return $this->dataCallerInstance;
  }

  public function getModelBuilderInstance(): ?array {
    return $this->modelBuilderInstance??null;
  }
// [End Getters]

// [Setters]
  public function setDataCallerInstance(ModelDataCallerInterface $dataCallerInstance): static {
    $this->dataCallerInstance = $dataCallerInstance;

    return $this;
  }

  public function setModelBuilderInstance($modelBuilderInstance): static {
    $this->modelBuilderInstance = $modelBuilderInstance;

    return $this;
  }
// [End Setters]
}

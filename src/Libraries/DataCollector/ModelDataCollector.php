<?php
namespace Crudvel\Libraries\DataCollector;

use Crudvel\Interfaces\DataCollector\{DataCollectorInterface,ModelDataCollectorInterface};
use Crudvel\Interfaces\DataCaller\ModelDataCallerInterface;

Class ModelDataCollector extends BaseDataCollector implements DataCollectorInterface,ModelDataCollectorInterface {
  protected $modelBuilderInstance = [];

  public function __construct(ModelDataCallerInterface $dataCallerInstace){
    $this->setDataCallerInstace($dataCallerInstace);
  }

// [Specific Logic]
  //Open source and count total data
  public function init(){
    $this->getDataCallerInstace()->loadModelSrc();
    $this->setCount($this->counter());
  }

  public function counter(){
    $modelBuilderInstance = $this->getModelBuilderInstance();

    return $modelBuilderInstance ? $modelBuilderInstance->count(): 0;
  }
  
  public function loadContextData($contextData=null){
    if($contextData && class_exists($contextData))
      return $this->setModelBuilderInstance($contextData::noFilters());

    return $this->setModelBuilderInstance(null);
  }
// [End Specific Logic]

// [Getters]
  // TODO : complete this implementation
  public function getChunkedCollection($chuckSize = 100, $pageNumber = 0):Array {
    $offset = $pageNumber * $chuckSize;

    if ($offset >= $this->getCount())
      return [];

    return kageBunshinNoJutsu($this->getModelBuilderInstance())->offset($offset)->limit($chuckSize)->get()->toArray();
  }

  public function getNextChunk($next=null):Array {
    if ($this->getOffSet() >= $this->getCount())
      return [];

    $arraySegment = kageBunshinNoJutsu($this->getModelBuilderInstance())->offset($this->getOffSet())->limit($this->nextSegment())->get()->toArray();

    if(is_callable($next))
      if(!$next($arraySegment))
        throw new \Exception('next callback fail');

    return $this->responseAndAdvace($this->getDataCallerInstace()->dataTransform($arraySegment));
  }

  public function getDataCallerInstace():ModelDataCallerInterface{
    return $this->dataCallerInstace??null;
  }

  public function getModelBuilderInstance(){
    return $this->modelBuilderInstance??null;
  }
// [End Getters]

// [Setters]
  public function setDataCallerInstace(ModelDataCallerInterface $dataCallerInstace){
    $this->dataCallerInstace = $dataCallerInstace??null;

    return $this;
  }

  public function setModelBuilderInstance($modelBuilderInstance=null){
    $this->modelBuilderInstance = $modelBuilderInstance??null;

    return $this;
  }
// [End Setters]
}

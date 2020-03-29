<?php
namespace Crudvel\Libraries\DataCollector;

use Crudvel\Interfaces\DataCollector\{DataCollectorInterface,ModelDataCollectorInterface};
use Crudvel\Interfaces\DataCaller\ModelDataCallerInterface;

Class ModelDataCollector extends BaseDataCollector implements DataCollectorInterface,ModelDataCollectorInterface {
  protected $modelData = [];

  public function __construct(ModelDataCallerInterface $dataCallerInstace){
    $this->setDataCallerInstace($dataCallerInstace);
  }

  //Open source and count total data
  public function init(){
    $this->getDataCallerInstace()->loadModelData();
    $this->setCount($this->counter());
  }

  // TODO : complete this implementation
  public function getChunkedCollection($chuckSize = 100, $pageNumber = 0):Array {
    $offset = $pageNumber * $chuckSize;

    if ($offset >= $this->getCount())
      return [];
    return array_slice($this->getModelData(), $offset, $offset+$chuckSize);
  }

  public function getNextChunk($next=null):Array {
    if ($this->getOffSet() >= $this->getCount()){
      customLog("entre");
      return [];
    }

    $arraySegment = array_slice($this->getModelData(), $this->getOffSet(), $this->nextSegment());
    customLog($arraySegment);
    if(is_callable($next))
      if(!$next($arraySegment))
        throw new \Exception('next callback fail');
    return $this->responseAndAdvace($this->getDataCallerInstace()->dataTransform($arraySegment));
  }

  public function getModelData(){
    return $this->modelData??[];
  }

  public function getDataCallerInstace():ModelDataCallerInterface{
    return $this->dataCallerInstace??null;
  }

  public function setModelData($modelName=null){
    if(!$modelName){
      $this->modelData = [];
      return $this;
    }

    $model = new $modelName;
    $this->modelData = $model::all()->toArray();
    return $this;
  }

  public function setDataCallerInstace(ModelDataCallerInterface $dataCallerInstace){
    $this->dataCallerInstace = $dataCallerInstace??null;
    return $this;
  }

  public function counter(){
    return count($this->modelData);
  }
}

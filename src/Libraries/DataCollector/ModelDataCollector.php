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

  public function getChunkedCollection($chuckSize = 100, $pageNumber = 0):Array {
    $offset = $pageNumber * $chuckSize;

    if ($offset >= $this->getCount())
      return [];

    // return $this->getModelData()->slice($offset, $offset+$chuckSize);
    return array_slice($this->getModelData()->toArray(), $offset, $offset+$chuckSize);
  }

  public function getNextChunk($next=null):Array {
    if ($this->getOffSet() >= $this->getCount())
      return [];

    // $arraySegment= $this->getModelData()->slice($this->getOffSet(), $this->getOffSet() + $this->getChuckSize())->toArray();
    $arraySegment = array_slice($this->getModelData()->toArray(), $this->getOffSet(), $this->getOffSet() + $this->getChuckSize());
    // pdd($arraySegment);
    if(is_callable($next))
      if(!$next($arraySegment))
        throw new \Exception('next callback fail');
    return $this->responseAndAdvace($this->getDataCallerInstace()->dataTransform($arraySegment));
  }

  public function getModelData(){
    return $this->modelData??null;
  }

  public function getDataCallerInstace():ModelDataCallerInterface{
    return $this->dataCallerInstace??null;
  }

  public function setModelData($modelName=null){
    $model = new $modelName;
    $this->modelData = $model::all();
    return $this;
  }

  public function setDataCallerInstace(ModelDataCallerInterface $dataCallerInstace){
    $this->dataCallerInstace = $dataCallerInstace??null;
    return $this;
  }

  public function counter(){
    return $this->modelData->count();
  }
}

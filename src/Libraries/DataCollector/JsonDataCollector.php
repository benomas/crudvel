<?php
namespace Crudvel\Libraries\DataCollector;

use Crudvel\Interfaces\DataCollector\{DataCollectorInterface,JsonDataCollectorInterface};
use Crudvel\Interfaces\DataCaller\JsonDataCallerInterface;

Class JsonDataCollector extends BaseDataCollector implements DataCollectorInterface,JsonDataCollectorInterface {
  protected string $jsonPath            = '';
  protected array $jsonFilePaths       = [];
  protected int   $currentJsonPosition = 0;
  protected array $currentJsonContent  = [];

  public function __construct(JsonDataCallerInterface $dataCallerInstance){
    $this->setDataCallerInstance($dataCallerInstance);
  }

  public function loadContextData($contextData=null): JsonDataCollectorInterface {
    return $this->setJsonPath($contextData);
  }
// [Specific Logic]
  public function init(): void {
    $this->getDataCallerInstance()->loadJsonPath();
    $this->loadJsonFiles()->setCount($this->counter());
  }

  public function loadJsonFiles(): JsonDataCollector|static {
    if(!file_exists($this->getJsonPath()))
      return $this->setJsonFilePaths([]);

    $files     = assetsMap($this->getJsonPath());
    $jsonFiles = [];

    if ($files){
      foreach($files as $file){
        $pathInfo = pathinfo($file);

        if($pathInfo['extension'] === 'json')
          $jsonFiles[]=$this->getJsonPath().$file;
      }
    }

    return $this->setJsonFilePaths($jsonFiles);
  }

  public function counter(){
    $count = 0;

    foreach($this->getJsonFilePaths() as $jsonPath){
      try{
        $count = $count + count($this->loadJsonContent($jsonPath));
      }catch(\Exception $e){
        pdd($jsonPath);
        cvConsoleException($e,$jsonPath);
      }
    }

    return $count;
  }

  protected function increseCurrentJsonPosition(): static {
    $this->currentJsonPosition++;

    return $this;
  }

  protected function loadJsonContent($file){
    return json_decode(file_get_contents($file), true);
  }

  protected function nextFile(){
    if(count($this->getJsonFilePaths()) <= $this->getCurrentJsonPosition())
      return null;

    $nextFile = $this->getJsonFilePaths()[$this->getCurrentJsonPosition()];
    $this->increseCurrentJsonPosition();

    return $nextFile;
  }

  protected function requestJsonPortion(): array {
    $newJsonPortion   = [];
    $jsonPortionCount = 0;

    $sync = function () use(&$newJsonPortion,&$jsonPortionCount){
      $startPosition = $jsonPortionCount;
      foreach($this->getCurrentJsonContent() as $key=>$item){
        if($jsonPortionCount>=$this->getChuckSize()){
          $this->setCurrentJsonContent(array_slice($this->getCurrentJsonContent(),$jsonPortionCount-$startPosition));
          return $newJsonPortion;
        }

        $newJsonPortion[] = $item;
        $jsonPortionCount++;
      }
    };

    do{
      if($this->getCurrentJsonContent())
        if($sync())
          return $newJsonPortion;

      if($this->getCurrentJsonPosition() >= $this->getJsonsCount())
        return $newJsonPortion;

      $this->setCurrentJsonContent($this->loadJsonContent($this->nextFile()));
    }while($this->getCurrentJsonPosition() <= $this->getJsonsCount());

    return $newJsonPortion;
  }
// [End Specific Logic]

// [Getters]
  public function getChunkedCollection($chuckSize = 100, $pageNumber = 0):array {
    $offset = $pageNumber * $chuckSize;

    if ($offset >= $this->getCount())
      return [];

    return [];
  }

  public function getNextChunk($next=null):array {
    if ($this->getOffSet() >= $this->getCount())
      return [];

    $arraySegment = $this->requestJsonPortion();

    if(is_callable($next))
      if(!$next($arraySegment))
        throw new \Exception('next callback fail');

    return $this->responseAndAdvance($this->getDataCallerInstance()->dataTransform($arraySegment));
  }

  public function getDataCallerInstance():JsonDataCallerInterface{
    return $this->dataCallerInstance;
  }

  public function getJsonPath(){
    return $this->jsonPath??null;
  }

  public function getJsonFilePaths(){
    return $this->jsonFilePaths??null;
  }

  public function getJsonsCount(): int {
    return count($this->jsonFilePaths);
  }

  public function getCurrentJsonPosition(){
    return $this->currentJsonPosition??0;
  }

  public function getCurrentJsonContent(){
    return $this->currentJsonContent??null;
  }
// [End Getters]

// [Setters]
  public function setDataCallerInstance(JsonDataCallerInterface $dataCallerInstance): static {
    $this->dataCallerInstance = $dataCallerInstance??null;
    return $this;
  }

  public function setJsonPath($jsonPath=null):JsonDataCollectorInterface{
    $this->jsonPath = $jsonPath??null;

    return $this;
  }

  public function setJsonFilePaths($jsonFilePaths=null): static {
    $this->jsonFilePaths = $jsonFilePaths??null;

    return $this;
  }

  public function setCurrentJsonPosition($currentJsonPosition=0): static {
    $this->currentJsonPosition = $currentJsonPosition??0;

    return $this;
  }

  public function setCurrentJsonContent($currentJsonContent=null): static {
    $this->currentJsonContent = $currentJsonContent??null;

    return $this;
  }
// [End Setters]
}

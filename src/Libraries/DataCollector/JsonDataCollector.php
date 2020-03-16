<?php
namespace Crudvel\Libraries\DataCollector;
use \Maatwebsite\Excel\Facades\Excel;
use Crudvel\Interfaces\DataCollector\{DataCollectorInterface,JsonDataCollectorInterface};
use Crudvel\Interfaces\DataCaller\JsonDataCallerInterface;

Class JsonDataCollector extends BaseDataCollector implements DataCollectorInterface,JsonDataCollectorInterface {

  protected $jsonPath               = '';
  protected $jsonFilePaths          = [];
  protected $currentJsonPosition    = 0;
  protected $currentJsonContent     = [];
  protected $currentJsonContentLeft = null;

  public function __construct(JsonDataCallerInterface $dataCallerInstace){
    $this->setDataCallerInstace($dataCallerInstace);
  }

  //Open source and count total data
  public function init(){
    $this->getDataCallerInstace()->loadJsonPath();
    $this->loadJsonFiles()->setCount($this->counter());
  }

  public function getChunkedCollection($chuckSize = 100, $pageNumber = 0){
    $offset = $pageNumber * $chuckSize;
    if ($offset >= $this->getCount())
      return null;
    //return array_slice($this->getArrayData(), $offset, $offset+$chuckSize);
  }

  public function getNextChunk($next=null){
    if ($this->getOffSet() >= $this->getCount())
      return null;

    if(!$this->getCurrentJsonContent() || !$this->getCurrentJsonContentLeft()){
      $this->setCurrentJsonContent($this->loadJsonContent($this->nextFile()));
      $this->setCurrentJsonContentLeft(count($this->getCurrentJsonContent()));
    }

    $arraySegment = array_slice($this->getCurrentJsonContent(), $this->getOffSet(), $this->getOffSet() + $this->getChuckSize());
    if(is_callable($next))
      if(!$next($arraySegment))
        throw new \Exception('next callback fail');
    return $this->responseAndAdvace($arraySegment);
  }

  public function getDataCallerInstace():JsonDataCallerInterface{
    return $this->dataCallerInstace??null;
  }

  public function setDataCallerInstace(JsonDataCallerInterface $dataCallerInstace){
    $this->dataCallerInstace = $dataCallerInstace??null;
    return $this;
  }

  public function getJsonPath(){
    return $this->jsonPath??null;
  }

  public function getJsonFilePaths(){
    return $this->jsonFilePaths??null;
  }

  public function getJsonsCount(){
    return count($this->jsonFilePaths);
  }

  public function getCurrentJsonPosition(){
    return $this->currentJsonPosition??null;
  }

  public function getCurrentJsonContent(){
    return $this->currentJsonContent??null;
  }

  protected function getCurrentJsonContentLeft(){
    return $this->currentJsonContentLeft??null;
  }

  public function setJsonPath($jsonPath=null){
    $this->jsonPath = $jsonPath??null;
    return $this;
  }

  public function setJsonFilePaths($jsonFilePaths=null){
    $this->jsonFilePaths = $jsonFilePaths??null;
    return $this;
  }

  public function setCurrentJsonPosition($currentJsonPosition=null){
    $this->currentJsonPosition = $currentJsonPosition??null;
    return $this;
  }

  public function setCurrentJsonContent($currentJsonContent=null){
    $this->currentJsonContent = $currentJsonContent??null;
    return $this;
  }

  public function loadJsonFiles(){
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
    foreach($this->getJsonFilePaths() as $jsonPath)
      $count = $count + count($this->loadJsonContent($jsonPath));
    return $count;
  }

  protected function increseCurrentJsonPosition(){
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

  protected function setCurrentJsonContentLeft($currentJsonContentLeft=null){
    $this->currentJsonContentLeft = $currentJsonContentLeft??null;
    return $this;
  }
}

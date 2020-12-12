<?php
namespace Crudvel\Libraries\DataCollector;

use \Maatwebsite\Excel\Facades\Excel;
use Crudvel\Interfaces\DataCollector\{DataCollectorInterface,XlsxDataCollectorInterface};
use Crudvel\Interfaces\DataCaller\XlsxDataCallerInterface;

Class DataCollectorXLSX extends BaseDataCollector implements DataCollectorInterface,XlsxDataCollectorInterface {
  protected $xlsxPath               = '';
  protected $xlsxFilePaths          = [];
  protected $currentXlsxPosition    = 0;
  protected $currentXlsxContent     = [];

  public function __construct(XlsxDataCallerInterface $dataCallerInstace){
    $this->setDataCallerInstace($dataCallerInstace);
  }
  
  public function loadContextData($contextData=null){
    return $this->setXlsxPath($contextData);
  }
// [Specific Logic]
  public function init(){
    $this->getDataCallerInstace()->loadXlsxPath();
    $this->loadXlsxFiles()->setCount($this->counter());
  }

  public function loadXlsxFiles(){
    if(!file_exists($this->getXlsxPath()))
      return $this->setXlsxFilePaths([]);

    $files     = assetsMap($this->getXlsxPath());
    $xlsxFiles = [];

    if ($files){
      foreach($files as $file){
        $pathInfo = pathinfo($file);

        if($pathInfo['extension'] === 'xlsx')
          $xlsxFiles[]=$this->getXlsxPath().$file;
      }
    }

    return $this->setXlsxFilePaths($xlsxFiles);
  }

  public function counter(){
    $count = 0;

    foreach($this->getXlsxFilePaths() as $xlsxPath){
      try{
        $count = $count + count($this->loadXlsxContent($xlsxPath));
      }catch(\Exception $e){
        pdd($xlsxPath);
        cvConsoleException($e,$xlsxPath);
      }
    }

    return $count;
  }

  protected function increseCurrentXlsxPosition(){
    $this->currentXlsxPosition++;
    
    return $this;
  }

  protected function loadXlsxContent($file){
    return xlsx_decode(file_get_contents($file), true);
  }

  protected function nextFile(){
    if(count($this->getXlsxFilePaths()) <= $this->getCurrentXlsxPosition())
      return null;

    $nextFile = $this->getXlsxFilePaths()[$this->getCurrentXlsxPosition()];
    $this->increseCurrentXlsxPosition();

    return $nextFile;
  }

  protected function requestXlsxPortion(){
    $newXlsxPortion   = [];
    $xlsxPortionCount = 0;

    $sync = function () use(&$newXlsxPortion,&$xlsxPortionCount){
      $startPosition = $xlsxPortionCount;
      foreach($this->getCurrentXlsxContent() as $key=>$item){
        if($xlsxPortionCount>=$this->getChuckSize()){
          $this->setCurrentXlsxContent(array_slice($this->getCurrentXlsxContent(),$xlsxPortionCount-$startPosition));
          return $newXlsxPortion;
        }

        $newXlsxPortion[] = $item;
        $xlsxPortionCount++;
      }
    };

    do{
      if($this->getCurrentXlsxContent())
        if($sync())
          return $newXlsxPortion;

      if($this->getCurrentXlsxPosition() >= $this->getXlsxsCount())
        return $newXlsxPortion;

      $this->setCurrentXlsxContent($this->loadXlsxContent($this->nextFile()));
    }while($this->getCurrentXlsxPosition() <= $this->getXlsxsCount());

    return $newXlsxPortion;
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

    $arraySegment = $this->requestXlsxPortion();

    if(is_callable($next))
      if(!$next($arraySegment))
        throw new \Exception('next callback fail');

    return $this->responseAndAdvace($this->getDataCallerInstace()->dataTransform($arraySegment));
  }

  public function getDataCallerInstace():XlsxDataCallerInterface{
    return $this->dataCallerInstace??null;
  }

  public function getXlsxPath(){
    return $this->xlsxPath??null;
  }

  public function getXlsxFilePaths(){
    return $this->xlsxFilePaths??null;
  }

  public function getXlsxsCount(){
    return count($this->xlsxFilePaths);
  }

  public function getCurrentXlsxPosition(){
    return $this->currentXlsxPosition??0;
  }

  public function getCurrentXlsxContent(){
    return $this->currentXlsxContent??null;
  }
// [End Getters]

// [Setters]
  public function setDataCallerInstace(XlsxDataCallerInterface $dataCallerInstace){
    $this->dataCallerInstace = $dataCallerInstace??null;
    return $this;
  }

  public function setXlsxPath($xlsxPath=null):XlsxDataCollectorInterface{
    $this->xlsxPath = $xlsxPath??null;

    return $this;
  }

  public function setXlsxFilePaths($xlsxFilePaths=null){
    $this->xlsxFilePaths = $xlsxFilePaths??null;

    return $this;
  }

  public function setCurrentXlsxPosition($currentXlsxPosition=0){
    $this->currentXlsxPosition = $currentXlsxPosition??0;

    return $this;
  }

  public function setCurrentXlsxContent($currentXlsxContent=null){
    $this->currentXlsxContent = $currentXlsxContent??null;

    return $this;
  }
// [End Setters]
}

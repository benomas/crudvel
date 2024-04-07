<?php
namespace Crudvel\Libraries\DataCollector;

use \Maatwebsite\Excel\Facades\Excel;
use Crudvel\Interfaces\DataCollector\{DataCollectorInterface,XlsxDataCollectorInterface};
use Crudvel\Interfaces\DataCaller\XlsxDataCallerInterface;
use Crudvel\Imports\ModelImporterInterceptor;
Class DataCollectorXLSX extends BaseDataCollector implements DataCollectorInterface,XlsxDataCollectorInterface {
  protected string $xlsxPath     = '';
  protected mixed  $xlsxFilePath = null;
  protected        $importerInterceptor;
  /**
   * @var mixed|null
   */
  private mixed $currentXlsxContent;
  /**
   * @var int|mixed
   */
  private mixed $currentXlsxPosition;

  public function __construct(XlsxDataCallerInterface $dataCallerInstance){
    $this->setDataCallerInstance($dataCallerInstance);
  }

  public function loadContextData($contextData=null){
    return $this->setXlsxPath($contextData)
      ->setImporterInterceptor(new ModelImporterInterceptor('App\Models\CatInegiState'));
  }
// [Specific Logic]
  public function init(): void {
    $this->getDataCallerInstance()->loadXlsxPath();
    $this->loadXlsxFile()->setCount($this->counter());
  }

  public function loadXlsxFile(): DataCollectorXLSX|static {
    return $this->setXlsxFilePath("{$this->getXlsxPath()}data.xlsx");
  }

  public function counter(): int {
    $count = 0;

    if(!file_exists($this->getXlsxFilePath()))
      return 0;

    Excel::import($this->getImporterInterceptor(), $this->getXlsxFilePath());

    $excel = Excel::getFacadeRoot();
    $test=[];
    customLog('Load 1');
    $test[]=$excel->import($this->getImporterInterceptor(), $this->getXlsxFilePath());
    customLog('Load 2');
    pdd($test);
    return $count;
  }

  protected function increseCurrentXlsxPosition(): static {
    $this->currentXlsxPosition++;

    return $this;
  }

  protected function loadXlsxContent($file){
    return xlsx_decode(file_get_contents($file), true);
  }

  protected function nextFile(){
    if(count($this->getXlsxFilePath()) <= $this->getCurrentXlsxPosition())
      return null;

    $nextFile = $this->getXlsxFilePath()[$this->getCurrentXlsxPosition()];
    $this->increseCurrentXlsxPosition();

    return $nextFile;
  }

  protected function requestXlsxPortion(): array {
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

  /**
   * @throws \Exception
   */
  public function getNextChunk($next=null):array {
    if ($this->getOffSet() >= $this->getCount())
      return [];

    $arraySegment = $this->requestXlsxPortion();

    if(is_callable($next))
      if(!$next($arraySegment))
        throw new \Exception('next callback fail');

    return $this->responseAndAdvance($this->getDataCallerInstance()->dataTransform($arraySegment));
  }

  public function getDataCallerInstance():XlsxDataCallerInterface{
    return $this->dataCallerInstance;
  }

  public function getXlsxPath(){
    return $this->xlsxPath??null;
  }

  public function getXlsxFilePath(){
    return $this->xlsxFilePath??null;
  }

  public function getXlsxsCount(): int {
    return count($this->xlsxFilePath);
  }

  public function getCurrentXlsxPosition(){
    return $this->currentXlsxPosition??0;
  }

  public function getCurrentXlsxContent(){
    return $this->currentXlsxContent??null;
  }

  public function getImporterInterceptor(){
    return $this->importerInterceptor??null;
  }
// [End Getters]

// [Setters]
  public function setDataCallerInstance(XlsxDataCallerInterface $dataCallerInstance): static {
    $this->dataCallerInstance = $dataCallerInstance??null;
    return $this;
  }

  public function setXlsxPath($xlsxPath=null):XlsxDataCollectorInterface{
    $this->xlsxPath = $xlsxPath??null;

    return $this;
  }

  public function setXlsxFilePath($xlsxFilePath=null): static {
    $this->xlsxFilePath = $xlsxFilePath??null;

    return $this;
  }

  public function setCurrentXlsxPosition($currentXlsxPosition=0): static {
    $this->currentXlsxPosition = $currentXlsxPosition??0;

    return $this;
  }

  public function setCurrentXlsxContent($currentXlsxContent=null): static {
    $this->currentXlsxContent = $currentXlsxContent??null;

    return $this;
  }

  public function setImporterInterceptor($importerInterceptor=null): static {
    $this->importerInterceptor = $importerInterceptor??null;

    return $this;
  }
// [End Setters]
}

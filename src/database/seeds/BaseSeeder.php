<?php namespace Crudvel\Database\Seeds;

use Crudvel\Libraries\DataCollector\{ArrayDataCollector,JsonDataCollector,ModelDataCollector,DataCollectorXLSX};
use Crudvel\Traits\{CrudTrait,CvPatronTrait};
use Illuminate\Database\Seeder;
use DB;
use Illuminate\Support\Facades\Schema;
use Crudvel\Interfaces\DataCaller\{DataCallerInterface,ArrayDataCallerInterface,JsonDataCallerInterface,ModelDataCallerInterface,XlsxDataCallerInterface};
use Crudvel\Interfaces\DataCollector\DataCollectorInterface;
class BaseSeeder extends Seeder implements DataCallerInterface,ArrayDataCallerInterface,JsonDataCallerInterface,ModelDataCallerInterface,XlsxDataCallerInterface
{
  protected string $baseClass;
  protected string $modelClass;
  protected string $src;
  protected string $modelSrc;
  protected string $classType   = "TableSeeder";
  protected int    $chunkedSize = 999;
  protected bool   $runChunked  = false;
  protected bool $enableTransaction  = true;
  protected bool $deleteBeforeInsert = true;
  protected   DataCollectorInterface|null  $currentCollectorInstance = null;
  protected int $seedsToInsert            = 100;
  protected       $injectedSeedControl = null;
  protected array $collectors          = [
    'arrayCollector'  => ArrayDataCollector::class,
    'jsonCollector'   => JsonDataCollector::class,
    'modelCollector'  => ModelDataCollector::class,
    //'xlsxCollector'  => DataCollectorXLSX::class,//not completed yet
  ];
  use CrudTrait;
  use CvPatronTrait;

// [Specific Logic]
  public function chunkSize(): int {
    return $this->chunkedSize;
  }

  public function defaultImplementation(): static {
    foreach ($this->data as $key => $value)
      $this->modelInstantiator(true)->fill($value)->save();

    return $this;
  }

  protected function collectorIterator(): static {
    foreach($this->getCollectors() as $collectorClass){
      $this->setCurrentCollectorInstance(new $collectorClass($this))->getCurrentCollectorInstance()->init();
      while($slicedData = $this->getCurrentCollectorInstance()->getNextChunk()){
        foreach($slicedData as $item){
          $this->modelInstantiator(true)->fill($item)->save();
        }
      }
    }

    return $this;
  }

  public function run(): bool {
    try {
      if ($this->enableTransaction) {
        DB::transaction(function () {
          $this->prepareSeeder()->collectorIterator()->finishSeeder();
        });
      } else {
        $this->prepareSeeder()->collectorIterator()->finishSeeder();
      }
    } catch (\Exception $e) {
      cvConsoleException($e);
    }
    return true;
  }

  public function loadArrayData(): static {
    $this->getCurrentCollectorInstance()->loadContextData($this->data??[]);

    return $this;
  }

  public function loadModelSrc(): static {
    $this->getCurrentCollectorInstance()->loadContextData($this->getModelSrc());

    return $this;
  }

  public function loadJsonPath(): static {
    $src = cvCaseFixer('plural|slug',$this->getSrc());
    $this->getCurrentCollectorInstance()->loadContextData(database_path("data/$src/"));

    return $this;
  }

  public function loadXlsxPath(): static {
    $src = cvCaseFixer('plural|slug',$this->getSrc());
    $this->getCurrentCollectorInstance()->loadContextData(database_path("data/$src/"));

    return $this;
  }

  public function dataTransform(Array $arraySegment): array {
    return $arraySegment;
  }

  public function explodeClass(): static {
    if(!$this->getBaseClass())
      $this->setBaseClass(class_basename(get_class($this)));

    if(!$this->getSrc())
      $this->setSrc(cvCaseFixer('singular|studly',str_replace($this->getClassType(),"",$this->getBaseClass())));

    if(!$this->getModelClass())
      $this->setModelClass('App\Models\\'.$this->getSrc());

    return $this;
  }

  protected function prepareSeeder(): static {
    $this->explodeClass();

    Schema::disableForeignKeyConstraints();

    if($this->deleteBeforeInsert)
      $this->modelInstantiator()->delete();

    $modelClass = $this->getModelClass();
    $modelClass::reguard();

    return $this;
  }

  protected function finishSeeder(): static {
    Schema::enableForeignKeyConstraints();

    return $this;
  }

  /*
  Example implementation of mass insert
  protected function collectorIterator(){
    foreach($this->getCollectors() as $collectorClass){
      $this->setCurrentCollectorInstance(new $collectorClass($this))->getCurrentCollectorInstance()->init();
      do{
        $model = $this->modelSource;
        $slicedData = $this->getCurrentCollectorInstance()->getNextChunk(function($slicedData) use($model){
          -- the idea of next chunck callback is the posibility to react to the sqlserver max parameters error,
          -- when the exceptions occur, chunksize needs to be decresed, without advance to the next page
          ...mass insert script code here
          return $slicedData;
        })
      }while($slicedData)
    }
  }*/

  public function modelInstantiator($new=false){
    $modelClass = $this->getModelClass();

    if(!class_exists($modelClass))
      return null;

    return $new ? (new $modelClass()) : $modelClass::noFilters();
  }
// [End Specific Logic]

// [Getters]
  public function getSrc(): ?string {
    return $this->src ?? null;
  }

  public function getModelSrc(): ?string {
    return $this->modelSrc ?? null;
  }

  public function getData(){
    return $this->data;
  }

  public function getCollectors(): ?array {
    return $this->collectors??null;
  }

  public function getCurrentCollectorInstance():DataCollectorInterface{
    return $this->currentCollectorInstance;
  }

  public function getModelClass(): ?string {
    return $this->modelClass??null;
  }

  public function getBaseClass(): ?string {
    return $this->baseClass??null;
  }

  public function getClassType(): ?string {
    return $this->classType??null;
  }

  public function getSeedsToInsert(): ?int {
    return $this->seedsToInsert??null;
  }

  public function getInjectedSeedControl(){
    return $this->injectedSeedControl??null;
  }
// [End Getters]

// [Setters]
  public function setCollectors($collectors=null): static {
    $this->collectors = $collectors??null;

    return $this;
  }

  public function setCurrentCollectorInstance(DataCollectorInterface $currentCollectorInstance=null): static {
    $this->currentCollectorInstance = $currentCollectorInstance??null;

    return $this;
  }

  public function setModelClass($modelClass=null): static {
    $this->modelClass = $modelClass??null;

    return $this;
  }

  public function setBaseClass($baseClass=null): static {
      $this->baseClass = $baseClass??null;
      return $this;
  }

  public function setClassType($classType=null): static {
    $this->classType = $classType??null;

    return $this;
  }

  public function setModelSrc($modelSrc=null): static {
    $this->modelSrc = $modelSrc??null;

    return $this;
  }

  public function setSrc($src=null): static {
    $this->src = $src??null;

    return $this;
  }

  public function setSeedsToInsert($seedsToInsert=null): static {
    $this->seedsToInsert = $seedsToInsert??null;

    return $this;
  }

  public function setInjectedSeedControl(\Crudvel\Interfaces\injectedSeedControl $injectedSeedControl):\Illuminate\Database\Seeder{
    $this->injectedSeedControl = $injectedSeedControl;

    return $this;
  }
// [End Setters]
}

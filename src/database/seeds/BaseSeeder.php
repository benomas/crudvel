<?php namespace Crudvel\Database\Seeds;

use Illuminate\Database\Seeder;
use DB;
use Crudvel\Traits\CrudTrait;
use Illuminate\Support\Facades\Schema;
use Crudvel\Interfaces\DataCaller\{DataCallerInterface,ArrayDataCallerInterface,JsonDataCallerInterface,ModelDataCallerInterface,XlsxDataCallerInterface};
use Crudvel\Interfaces\DataCollector\DataCollectorInterface;
class BaseSeeder extends Seeder implements DataCallerInterface,ArrayDataCallerInterface,JsonDataCallerInterface,ModelDataCallerInterface,XlsxDataCallerInterface
{
  protected $baseClass;
  protected $modelClass;
  protected $src;
  protected $modelSrc;
  protected $classType                = "TableSeeder";
  protected $chunckedSize             = 999;
  protected $runChunked               = false;
  protected $enableTransaction        = true;
  protected $deleteBeforeInsert       = true;
  protected $currentCollectorInstance = null;
  protected $collectors = [
    'arrayCollector' => \Crudvel\Libraries\DataCollector\ArrayDataCollector::class,
    'jsonCollector'  => \Crudvel\Libraries\DataCollector\JsonDataCollector::class,
    'modelCollector' => \Crudvel\Libraries\DataCollector\ModelDataCollector::class,
    //'xlsxCollector'  => \Crudvel\Libraries\DataCollector\DataCollectorXLSX::class,//not completed yet
  ];
  use CrudTrait;

// [Specific Logic]
  public function chunkSize(){
    return $this->chunckedSize;
  }

  public function defaultImplementation(){
    foreach ($this->data as $key => $value)
      $this->modelInstanciator(true)->fill($value)->save();
  }

  protected function collectorIterator(){
    foreach($this->getCollectors() as $collectorClass){
      $this->setCurrentCollectorInstance(new $collectorClass($this))->getCurrentCollectorInstance()->init();
      while($slicedData = $this->getCurrentCollectorInstance()->getNextChunk()){
        foreach($slicedData as $item){
          $this->modelInstanciator(true)->fill($item)->save();
        }
      }
    }
    return $this;
  }

  public function run() {
    try{
      if($this->enableTransaction){
        DB::transaction(function(){
          $this->prepareSeeder()->collectorIterator()->finishSeeder();
        });
      }else{
        $this->prepareSeeder()->collectorIterator()->finishSeeder();
      }
    }catch(\Exception $e){
      cvConsoleException($e);
    }
    return true;
  }

  public function loadArrayData(){
    $this->getCurrentCollectorInstance()->loadContextData($this->data??[]);
    //$this->getCurrentCollectorInstance()->setArrayData($this->data??[]);

    return $this;
  }

  public function loadModelSrc(){
    $this->getCurrentCollectorInstance()->loadContextData($this->getModelSrc());

    return $this;
  }

  public function loadJsonPath(){
    $src = cvCaseFixer('plural|slug',$this->getSrc());
    $this->getCurrentCollectorInstance()->loadContextData(database_path("data/$src/"));

    return $this;
  }

  public function loadXlsxPath(){
    $src = cvCaseFixer('plural|slug',$this->getSrc());
    $this->getCurrentCollectorInstance()->loadContextData(database_path("data/$src/"));

    return $this;
  }

  public function dataTransform(Array $arraySegment):Array{
    return $arraySegment;
  }

  public function explodeClass(){
    if(!$this->getBaseClass())
      $this->setBaseClass(class_basename(get_class($this)));

    if(!$this->getSrc())
      $this->setSrc(cvCaseFixer('singular|studly',str_replace($this->getClassType(),"",$this->getBaseClass())));

    if(!$this->getModelClass())
      $this->setModelClass('App\Models\\'.$this->getSrc());
      
    return $this;
  }

  protected function prepareSeeder(){
    $this->explodeClass();

    Schema::disableForeignKeyConstraints();

    if($this->deleteBeforeInsert)
      $this->modelInstanciator()->delete();

    $modelClass = $this->getModelClass();
    $modelClass::reguard();

    return $this;
  }

  protected function finishSeeder(){
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

  public function modelInstanciator($new=false){
    $modelClass = $this->getModelClass();

    if(!class_exists($modelClass))
      return null;
      
    return $new ? (new $modelClass()) : $modelClass::noFilters();
  }
// [End Specific Logic]

// [Getters]
  public function getSrc(){
    return $this->src ?? null;
  }

  public function getModelSrc(){
    return $this->modelSrc ?? null;
  }

  public function getData(){
    return $this->data;
  }

  public function getCollectors(){
    return $this->collectors??null;
  }

  public function getCurrentCollectorInstance():DataCollectorInterface{
    return $this->currentCollectorInstance??null;
  }

  public function getModelClass(){
    return $this->modelClass??null;
  }

  public function getBaseClass(){
    return $this->baseClass??null;
  }

  public function getClassType(){
    return $this->classType??null;
  }
// [End Getters]

// [Setters]
  public function setCollectors($collectors=null){
    $this->collectors = $collectors??null;

    return $this;
  }

  public function setCurrentCollectorInstance(DataCollectorInterface $currentCollectorInstance=null){
    $this->currentCollectorInstance = $currentCollectorInstance??null;

    return $this;
  }

  public function setModelClass($modelClass=null){
    $this->modelClass = $modelClass??null;

    return $this;
  }

  public function setBaseClass($baseClass=null){
      $this->baseClass = $baseClass??null;
      return $this;
  }

  public function setClassType($classType=null){
    $this->classType = $classType??null;

    return $this;
  }

  public function setModelSrc($modelSrc=null){
    $this->modelSrc = $modelSrc??null;

    return $this;
  }

  public function setSrc($src=null){
    $this->src = $src??null;
    
    return $this;
  }
// [End Setters]
}

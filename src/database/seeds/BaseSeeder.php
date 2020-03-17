<?php namespace Crudvel\Database\Seeds;

use Illuminate\Database\Seeder;
use DB;
use Crudvel\Traits\CrudTrait;
use Illuminate\Support\Facades\Schema;
use Crudvel\Interfaces\DataCaller\{DataCallerInterface,ArrayDataCallerInterface,JsonDataCallerInterface};
use Crudvel\Interfaces\DataCollector\DataCollectorInterface;

class BaseSeeder extends Seeder implements DataCallerInterface,ArrayDataCallerInterface,JsonDataCallerInterface
{
  protected $baseClass;
  protected $classType = "TableSeeder";
  protected $resource;
  protected $modelSource;
  protected $model;
  protected $chunckedSize       = 999;
  protected $runChunked         = false;
  protected $enableTransaction  = true;
  protected $deleteBeforeInsert = true;
  protected $collectors = [
    'arrayCollector' => \Crudvel\Libraries\DataCollector\ArrayDataCollector::class,
    'jsonCollector'  => \Crudvel\Libraries\DataCollector\JsonDataCollector::class
  ];
  protected $currentCollectorInstance = null;
  use CrudTrait;

  /**
   * Run the database seeds.
   *
   * @return void
   */
  /* deprecated
  public function run()
  {
    if(empty($this->data))
      return false;

    $this->data = collect($this->data);
    $this->explodeClass();
    Schema::disableForeignKeyConstraints();

    if($this->deleteBeforeInsert)
      $this->modelInstanciator()->delete();

    if($this->enableTransaction){
      DB::transaction(function(){
        $this->defaultImplementation();
      });
    }else{
      $this->defaultImplementation();
    }

    Schema::enableForeignKeyConstraints();
  }
  */

  public function chunkSize(){
    return $this->chunckedSize;
  }

  public function defaultImplementation(){
    foreach ($this->data as $key => $value)
      $this->modelInstanciator(true)->fill($value)->save();
  }

  public function getResource(){
    if(empty($this->resource))
      $this->explodeClass();

    return $this->resource;
  }

  public function modelInstanciator($new=false){
    if(!$this->modelSource)
      $this->modelSource = 'App\Models\\'.$this->getResource();

    if(!class_exists($this->modelSource))
      return null;

    $model = $this->modelSource;

    if($new)
      return new $model;

    return $model::noFilters();
  }

  public function explodeClass(){
    if(empty($this->baseClass))
      $this->baseClass=class_basename(get_class($this));

    if(empty($this->resource))
      $this->resource = cvCaseFixer('singular|studly',str_replace($this->classType,"",$this->baseClass));
  }

  protected function prepareSeeder(){
    $this->explodeClass();
    Schema::disableForeignKeyConstraints();

    if($this->deleteBeforeInsert)
      $this->modelInstanciator()->delete();

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

  protected function collectorIterator(){
    foreach($this->getCollectors() as $collectorClass){
      $this->setCurrentCollectorInstance(new $collectorClass($this))->getCurrentCollectorInstance()->init();
      while($slicedData = $this->getCurrentCollectorInstance()->getNextChunk()){
        foreach($slicedData as $item)
          $this->modelInstanciator(true)->fill($item)->save();
      }
    }

    return $this;
  }

  public function run() {
    $this->prepareSeeder()->collectorIterator()->finishSeeder();
  }

  public function getCollectors(){
    return $this->collectors??null;
  }

  public function getCurrentCollectorInstance():DataCollectorInterface{
    return $this->currentCollectorInstance??null;
  }

  public function setCollectors($collectors=null){
    $this->collectors = $collectors??null;

    return $this;
  }

  public function setCurrentCollectorInstance(DataCollectorInterface $currentCollectorInstance=null){
    $this->currentCollectorInstance = $currentCollectorInstance??null;

    return $this;
  }

  public function loadArrayData(){
    $this->getCurrentCollectorInstance()->setArrayData($this->data??[]);

    return $this;
  }

  public function loadJsonPath(){
    $resource = cvCaseFixer('plural|slug',$this->resource);
    $this->getCurrentCollectorInstance()->setJsonPath(database_path("data/$resource/"));

    return $this;
  }

  public function dataTransform(Array $arraySegment):Array{
    return $arraySegment;
  }
}

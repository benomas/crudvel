<?php namespace Crudvel\Database\Seeds;

use Illuminate\Database\Seeder;
use DB;
use Crudvel\Traits\CrudTrait;
use Illuminate\Support\Facades\Schema;

class BaseSeeder extends Seeder
{
  protected $baseClass;
  protected $classType = "TableSeeder";
  protected $crudObjectName;
  protected $modelSource;
  protected $model;
  protected $chunckedSize      = 999;
  protected $runChunked        = false;
  protected $enableTransaction = true;
  protected $deleteBeforeInsert= false;
  use CrudTrait;
  /**+
   * Run the database seeds.
   *
   * @return void
   */
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

  public function chunkSize(){
    return $this->chunckedSize;
  }

  public function defaultImplementation(){
    foreach ($this->data as $key => $value)
      $this->modelInstanciator(true)->fill($value)->save();
  }

  public function chunckedImplementation($modelClass){
    foreach($this->data->chunk($this->chunkSize()) as $subData){
      try{
        $modelClass::insert($subData->toArray());
      }catch(\Exception $e){
        customLog('Seeder transaction fail with',$subData,json_encode($e));
        cvConsoler(cvNegative("\n".'Exception when running seeder '.json_encode($e)));
      }
    }
  }

  public function getCrudObjectName(){
    if(empty($this->crudObjectName))
      $this->explodeClass();
    return $this->crudObjectName;
  }

  public function modelInstanciator($new=false){
    $model = $this->modelSource = $this->modelSource?
      $this->modelSource:
      "App\Models\\".$this->getCrudObjectName();
    if(!class_exists($model))
      return null;
    if($new)
      return new $model();
    return $model::noFilters();
  }

  public function explodeClass(){
    if(empty($this->baseClass))
      $this->baseClass=class_basename(get_class($this));

    if(empty($this->classType)){
      foreach (["Controller","Request"] as $classType)
        if($this->testClassType($classType))
          $this->classType = $classType;
      if(empty($this->classType))
        $this->classType = "Model";
    }

    if(empty($this->crudObjectName))
      $this->crudObjectName = str_replace($this->classType,"",$this->baseClass);
  }

  public function runWithCollector() {
    $this->explodeClass();
    Schema::disableForeignKeyConstraints();
    $modelClass = get_class($this->modelInstanciator(true));
    if($this->deleteBeforeInsert)
      $this->modelInstanciator()->delete();
    if($this->enableTransaction){
      DB::transaction(function() use($modelClass){
        if($this->runChunked)
          $this->chunckedImplementation($modelClass);
        else
          $this->defaultImplementation();
      });
    }
    else{
      if($this->runChunked)
        $this->chunckedImplementation($modelClass);
      else
        $this->defaultImplementation();
    }
    Schema::enableForeignKeyConstraints();
  }
}

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
    disableForeignKeyConstraints();
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
    enableForeignKeyConstraints();
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
        cvConsoler(cvRedCoTC("\n".'Exception when running seeder '.json_encode($e)));
      }
    }
  }
}

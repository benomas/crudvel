<?php namespace Crudvel\Database\Seeds;

use Illuminate\Database\Seeder;
use DB;
use Crudvel\Traits\CrudTrait;

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
    $chunkCount = 0;
    foreach($this->data->chunk($this->chunkSize()) as $subData){
      $chunkCount++;
      try{
        $modelClass::insert($subData->toArray());
        cvConsoler("\n".cvBlueTC(class_basename($modelClass))." chunk number ".cvBrownTC($chunkCount)." with ".cvCyanTC(count($subData)).' rows, '.cvGreenTC('completed'));
      }catch(\Exception $e){
        cvConsoler("\n chunk number ".cvBrownTC($chunkCount)." of ".cvBlueTC(class_basename($modelClass))." with ".cvCyanTC(count($subData)).' rows, '.cvRedTC('fail').' message error '.cvBrownTC($e->getMessage()));
        cvConsoler("\n now trying row by row");
        foreach($subData AS $pos=>$row){
          try{
            $modelClass::insert([$row]);
            cvConsoler(
              "\n".cvBlueTC(class_basename($modelClass)).
              " chunk number ".cvBrownTC($chunkCount).
              " with ".cvCyanTC(count($subData)).' rows '.
              " with row number $pos, ".cvGreenTC('completed')
            );
          }catch(\Exception $e2){
            customLog('Seeder transaction fail with',json_encode($row),$e2->getMessage());
            cvConsoler(
              "\n chunk number ".cvBrownTC($chunkCount).
              " of ".cvBlueTC(class_basename($modelClass)).
              " with ".cvCyanTC(count($subData)).' rows '.
              " with row number $pos, ".cvRedTC('fail')
            );
            cvConsoler("\n\n\t error message: ".cvRedTC($e2->getMessage()));
            cvConsoler("\n\n\t failed row: ".cvRedTC(json_encode($row)));
            cvConsolerLn("");
          }
        }
      }
    }
  }
}

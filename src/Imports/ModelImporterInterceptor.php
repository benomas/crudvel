<?php namespace Crudvel\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\{ToModel,WithChunkReading,WithHeadingRow,WithBatchInserts};
use Crudvel\Imports\Exceptions\NoModelException;

class ModelImporterInterceptor implements ToModel, WithChunkReading,WithBatchInserts,WithHeadingRow
{
  protected $modelClass;

  public function __construct($modelClass = null){
    $this->setModelClass($modelClass);
  }

// [Specific Logic]
  public function model(array $row){
    pdd($row);
    return $this->newModel($row);
  }
  
  public function chunkSize(): int{
    return 100;
  }
    
  public function batchSize(): int{
    return 100;
  }

  public function newModel($data = []) {
    if(!class_exists($this->getModelClass()))
      throw new NoModelException();

    $modelClass = $this->getModelClass();

    return new $modelClass($data);
  }
    
  public function headingRow(): int{
    return 4;
  }

  public function limit(): int{
    return 10;
  }
// [End Specific Logic]

// [Getters]
  public function getModelClass(){
    return $this->modelClass??null;
  }
// [End Getters]

// [Setters]
  public function setModelClass($modelClass=null){
    $this->modelClass = $modelClass??null;

    return $this;
  }
// [End Setters]
}

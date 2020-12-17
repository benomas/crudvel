<?php namespace Crudvel\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\{ToCollection,WithStartRow,WithLimit,WithChunkReading,WithHeadingRow};

class CollectionImporterInterceptor implements ToCollection,WithStartRow,WithLimit,WithChunkReading,WithHeadingRow {

  protected $skip = 5;

// [Specific Logic]
  public function collection(Collection $rows){
    pdd($rows);
    foreach ($rows as $row) {
    }
  }
  public function limit(): int{
    return 5;
  }

  public function startRow(): int{
    return $this->getSkip();
  }
    
  public function headingRow(): int{
    return 4;
  }
  
  public function chunkSize(): int{
    return 100;
  }
// [End Specific Logic]

// [Getters]
  public function getSkip(){
    return $this->skip??null;
  }
// [End Getters]

// [Setters]
  public function setSkip($skip=null){
    $this->skip = $skip??null;

    return $this;
  }
// [End Setters]
}

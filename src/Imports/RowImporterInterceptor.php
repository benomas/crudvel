<?php namespace Crudvel\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Row;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Crudvel\Imports\Exceptions\NoModelException;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class RowImporterInterceptor implements OnEachRow, WithChunkReading
{
  public function __construct(){
  }

// [Specific Logic]
  public function onRow(Row $row){
    $rowIndex = $row->getIndex();
    $row      = $row->toArray();
  }
  public function chunkSize(): int{
    return 100;
  }
    
  public function headingRow(): int{
    return 3;
  }
// [End Specific Logic]

// [Getters]
// [End Getters]

// [Setters]
// [End Setters]
}

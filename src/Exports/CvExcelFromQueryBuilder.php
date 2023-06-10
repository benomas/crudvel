<?php
namespace Crudvel\Exports;

use Maatwebsite\Excel\Concerns\{Exportable,FromQuery,WithHeadings,ShouldAutoSize,WithMapping};
use Crudvel\Interfaces\ExcelFromQueryInterface;

class CvExcelFromQueryBuilder implements FromQuery, WithHeadings, ShouldAutoSize,WithMapping
{
  use Exportable;
  protected $queryProvider;
  protected $exportHeaders;
  protected $exportColumns;

  public function setQueryProvider(ExcelFromQueryInterface $queryProvider){
    $this->queryProvider = $queryProvider;

    return $this;
  }

  public function setExportHeaders(array $exportHeaders){
    $this->exportHeaders = $exportHeaders;

    return $this;
  }

  public function defineExportColumns(){
    $this->exportColumns = array_keys($this->exportHeaders);

    return $this;
  }

  public function query()
  {
    return $this->queryProvider->getModelBuilderInstance();
  }

  public function headings(): array
  {
    return $this->exportHeaders;
  }

  public function map($row): array
  {
    $rowArray = $row->toArray();
    $mappedRow = [];

    foreach($this->exportColumns as $exportColumn)
      $mappedRow[$exportColumn] = $rowArray[$exportColumn]??null;

    return $mappedRow;
  }
}

<?php
namespace Crudvel\Exports;

use Maatwebsite\Excel\Concerns\{Exportable,FromQuery,WithHeadings,ShouldAutoSize};

class CvQueryBuilderInterceptor implements FromQuery, WithHeadings, ShouldAutoSize
{
  use Exportable;

  public function __construct($query)
  {
      $this->query= $query;
  }

  public function query()
  {
      return $this->query;
  }

  public function headings(): array
  {
      return $this->query->exportHeaders;
  }
}

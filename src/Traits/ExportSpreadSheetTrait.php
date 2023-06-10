<?php

namespace Crudvel\Traits;

use \Maatwebsite\Excel\Facades\Excel;

/* This Trait is used to do a generic spreadhseet export, it means export data from a query builder from any resouce */

trait ExportSpreadSheetTrait
{
  private function filterExportingColumns($defaultColumns)
  {
    $fixedRowColumns = $defaultColumns;

    if (count($this->whiteListExportingColumns()))
      $fixedRowColumns = $this->whiteListExportingColumns();


    if (count($this->blackListExportingColumns()))
      $fixedRowColumns = array_diff($fixedRowColumns, $this->blackListExportingColumns());

    return $fixedRowColumns;
  }

  /* Function to filter pagination cols */
  public function fixPagination($paginate)
  {
    $paginate['limit'] = null;

    return $paginate;
  }

  /* Function to get Pagination */
  public function exportingsBeforeFlowControl()
  {
    $paginate = $this->getFields()['paginate'] ?? [];

    $this->addField('paginate', $this->fixPagination($paginate));
  }

  public function exportings()
  {
    $this->processPaginatedResponse();

    $prefilteredHeaders = $this->filterExportingColumns(
      array_keys(kageBunshinNoJutsu($this->getModelBuilderInstance())->first()->getAttributes())
    );

    $resourceFieldList  = __("crudvel/{$this->getSlugPluralName()}.fields");
    $exportHeaders      = [];

    foreach ($prefilteredHeaders as $value){
      $exportHeaders[$value] = $resourceFieldList[$value] ?? "no registrado.{$value}";
    }

    return (new \Crudvel\Exports\CvExcelFromQueryBuilder)
      ->setQueryProvider($this)
      ->setExportHeaders($exportHeaders)
      ->defineExportColumns()
      ->download($this->getSlugPluralName() . '.xlsx');
  }

  public function processPaginatedResponse()
  {
    $this->getModelBuilderInstance()->solveSearches();

    if($this->getPaginated())
      $this->getPaginatorInstance()->processPaginatedResponse();

    return $this;
  }
}

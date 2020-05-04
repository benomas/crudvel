<?php
namespace Crudvel\Traits;
use \Maatwebsite\Excel\Facades\Excel;

/* This Trait is used to do a generic spreadhseet export, it means export data from a query builder from any resouce */
trait ExportSpreadSheetTrait
{

  public function exportingsBeforeFlowControl(){
    $paginate = $this->getFields()['paginate'] ?? [];
    $paginate['limit'] = null;
    $this->addField('paginate', $paginate);
  }

  public function exportsSpreadSheet()
  {
    $this->processPaginatedResponse();
    $ExporterInterceptor = new \Crudvel\Exports\CvQueryBuilderInterceptor($this->query);
    // Excel::store($ExporterInterceptor, $this->getSlugPluralName().'.xlsx');
    return (new \Crudvel\Exports\CvQueryBuilderInterceptor($this->query))->download($this->getSlugPluralName().'.xlsx');
    // return Excel::download($ExporterInterceptor, $this->getSlugPluralName().'.xlsx');
  }

  public function exportings(){
    $query = $this->getModelBuilderInstance();

    $resourceFieldList = __("crudvel/{$this->getSlugPluralName()}.fields");
    $this->headers = [];
    foreach (kageBunshinNoJutsu($query)->first()->getAttributes() as $key => $value)
      $this->headers[] = $resourceFieldList[$key] ?? $key;
    $this->query = $query;
    $this->query->exportHeaders = $this->headers;
    return $this->exportsSpreadSheet();
  }

  public function processPaginatedResponse(){
    $this->getModelBuilderInstance()->solveSearches();
    $this->getPaginated();
    $this->getPaginatorInstance()->extractPaginate();
    $this->getPaginatorInstance()->processPaginated();
    $this->getModelBuilderInstance()->select($this->getPaginatorInstance()->getSelectQuery());
  }
}

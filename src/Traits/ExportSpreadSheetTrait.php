<?php
namespace Crudvel\Traits;
use \Maatwebsite\Excel\Facades\Excel;

/* This Trait is used to do a generic spreadhseet export, it means export data from a query builder from any resouce */
trait ExportSpreadSheetTrait
{

  //TODO: change by beforeFlowControl
  public function exportBeforePaginate(){
    $paginate = $this->getFields()['paginate'] ?? [];
    $paginate['limit'] = null;
    $this->addField('paginate', $paginate);
  }

  public function exportSpreadSheet()
  {
    $ExporterInterceptor = new \Crudvel\Exports\CvQueryBuilderInterceptor($this->query);
    Excel::store($ExporterInterceptor, $this->getSlugPluralName().'.xlsx');
  }

  public function export(){
    $this->processPaginatedResponse();
    $query = $this->getModelBuilderInstance();
    $resourceFieldList = __("crudvel/{$this->getSlugPluralName()}.fields");
    $this->headers = [];
    // pdd(kageBunshinNoJutsu($query)->first()->getAttributes());
    foreach (kageBunshinNoJutsu($query)->first()->getAttributes() as $key => $value)
      $this->headers[] = $resourceFieldList[$key] ?? $key;
    $this->query = $query;
    $this->query->exportHeaders = $this->headers;
    $this->exportSpreadSheet();
  }

  public function processPaginatedResponse(){
    $this->getModelBuilderInstance()->solveSearches();
    $this->getPaginated();
    $this->getPaginatorInstance()->processPaginatedResponse();
    $this->getPaginatorInstance()->loadBasicPropertys();
    $this->getModelBuilderInstance()->select($this->getPaginatorInstance()->getSelectQuery());
  }
}

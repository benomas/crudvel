<?php
namespace Crudvel\Traits;
use \Maatwebsite\Excel\Facades\Excel;

/* This Trait is used to do a generic spreadhseet export, it means export data from a query builder from any resouce */
trait ExportSpreadSheetTrait
{

  /* Function to filter pagination cols */
  public function filterExportingColumns($paginate){
    $paginate['limit'] = null;
    $blackList = $this->blackListExportingColumns();
    $whiteList = $this->whiteListExportingColumns();

    if(count($whiteList) > 0  && count($blackList))
      pdd("You cant define simultaneously white and black list for exporting cols");

    // check if it has a black list
    if(count($blackList) > 0){
      foreach($blackList as $col){
        $find = array_search($col, $paginate['selectQuery']);
        if($find != false){
          unset($paginate['selectQuery'][$find]);
        }
      }
    }

    // check if it has a white list
    if(count($whiteList) > 0){
      $paginate['selectQuery'] = [];
      foreach($whiteList as $col){
        $paginate['selectQuery'][] = $col;
      }
    }
    return $paginate;
  }

  /* Function to get Pagination */
  public function exportingsBeforeFlowControl(){
    $paginate = $this->getFields()['paginate'] ?? [];
    $paginate = $this->filterExportingColumns($paginate);
    $this->addField('paginate', $paginate);
  }

  public function exportsSpreadSheet(){
    return (new \Crudvel\Exports\CvQueryBuilderInterceptor($this->query))->download($this->getSlugPluralName().'.xlsx');
  }

  public function exportings(){
    $resourceFieldList = __("crudvel/{$this->getSlugPluralName()}.fields");
    $this->headers     = [];
    $this->processPaginatedResponse();

    foreach (kageBunshinNoJutsu($this->getModelBuilderInstance())->first()->getAttributes() as $key => $value)
      $this->headers[] = $resourceFieldList[$key] ?? $key;

    $this->query = $this->getModelBuilderInstance();
    $this->query->exportHeaders = $this->headers;

    return $this->exportsSpreadSheet();
  }

  public function processPaginatedResponse(){
    $this->getModelBuilderInstance()->solveSearches();
    $this->getPaginated();
    $this->getPaginatorInstance()->extractPaginate();
    $this->getPaginatorInstance()->processPaginated();
    $this->getModelBuilderInstance()->select($this->getPaginatorInstance()->getSelectQuery());
    return $this;
  }
}

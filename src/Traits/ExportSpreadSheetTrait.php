<?php

namespace Crudvel\Traits;

use \Maatwebsite\Excel\Facades\Excel;

/* This Trait is used to do a generic spreadhseet export, it means export data from a query builder from any resouce */

trait ExportSpreadSheetTrait
{

  /* Function to filter pagination cols */
  public function filterExportingColumns($paginate)
  {
    $paginate['limit'] = null;
    $blackList = $this->blackListExportingColumns();
    $whiteList = $this->whiteListExportingColumns();
    $blackListCount = count($blackList);
    $whiteListCount = count($whiteList);
    $paginate['selectQuery'] = $this->selectables;

    // check for programmer error defining white and blacklist in the same controller
    if ($whiteListCount > 0  && $blackListCount > 0)
      pdd("You cant define simultaneously white and black list for exporting cols");

    // check to export all cols bu default without white and black list
    if ($whiteListCount <= 0  && $blackListCount <= 0) {
      // $paginate['selectQuery'] = null;
      $paginate['selectQuery'] = $this->selectables;
      return $paginate;
    }

    // check if it has a black list
    if ($blackListCount > 0) {
      foreach ($blackList as $col) {
        $find = array_search($col, $paginate['selectQuery']);
        if ($find) {
          unset($paginate['selectQuery'][$find]);
        }
      }
    }

    // check if it has a white list
    if ($whiteListCount > 0) {
      $paginate['selectQuery'] = [];
      foreach ($whiteList as $col) {
        $paginate['selectQuery'][] = $col;
      }
    }
    return $paginate;
  }

  /* Function to get Pagination */
  public function exportingsBeforeFlowControl()
  {
    $paginate = $this->getFields()['paginate'] ?? [];
    $paginate = $this->filterExportingColumns($paginate);
    $this->addField('paginate', $paginate);
  }

  public function exportsSpreadSheet()
  {
    return (new \Crudvel\Exports\CvQueryBuilderInterceptor($this->query))->download($this->getSlugPluralName() . '.xlsx');
  }

  public function exportings()
  {
    $resourceFieldList = __("crudvel/{$this->getSlugPluralName()}.fields");
    $this->headers     = [];
    $this->processPaginatedResponse();
    foreach (kageBunshinNoJutsu($this->getModelBuilderInstance())->first()->getAttributes() as $key => $value)
      $this->headers[] = $resourceFieldList[$key] ?? 'no registrado.' . $key;
    $this->query = $this->getModelBuilderInstance();
    // $this->query = $this->query->getQuery();
    $this->query = $this->query->toBase();
    $this->query->exportHeaders = $this->headers;

    return $this->exportsSpreadSheet();
  }

  public function processPaginatedResponse()
  {
    $this->getModelBuilderInstance()->solveSearches();
    $this->getPaginated();
    $this->getPaginatorInstance()->extractPaginate();
    $this->getPaginatorInstance()->processPaginated();
    $this->getModelBuilderInstance()->select($this->getPaginatorInstance()->getSelectQuery());
    return $this;
  }
}

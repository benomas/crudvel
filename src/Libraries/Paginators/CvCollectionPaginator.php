<?php

namespace Crudvel\Libraries\Paginators;
use Crudvel\Libraries\Paginators\CvPaginate;

class CvCollectionPaginator extends CvBasePaginator implements CvPaginate
{
  protected $searchMode = 'cv-collection-paginator';
  protected $collectionData;

  public function filter() {
    $this->setCollectionData($this->getCollectionData()->filter(function($item){
      foreach($this->getFilterQuery() as $filterKey=>$filter){
        if($filter !==null || !isset($item[$filterKey]) || ((string) $this->getSearchObject()) === '')
          return true;

        if(stripos($item[$filterKey], (string) $this->getSearchObject()) !== false)
          return true;
      }

      return false;
    }));

    return $this;
  }

  public function processPaginatedResponse() {
    $this->filter();

    $this->setPaginateCount($this->getCollectionData()->count());

    if($this->getLimit())
      $this->setCollectionData($this->getCollectionData()
        ->chunk($this->getLimit())[$this->getPage()-1]??collect([])
      );


    if ($this->getOrderBy()){
      if($this->getAscending()==1)
        $this->setCollectionData($this->getCollectionData()->sortBy($this->getOrderBy()));
      else
        $this->setCollectionData($this->getCollectionData()->sortByDesc($this->getOrderBy()));
    }

    return $this;
  }

  public function paginateResponder(){
    $collectionData = [];

    foreach($this->getCollectionData() as $row)
      $collectionData[]=$row;

    $this->setPaginateData(collect($collectionData));

    return $this->getRootInstance()->apiSuccessResponse([
      "data"    => $this->getPaginateData(),
      "count"   => $this->getPaginateCount(),
      "message" => trans("crudvel.api.success")
    ]);
  }

  public function getCollectionData(){
    return $this->collectionData??collect([]);
  }

  public function setCollectionData($collectionData=null){
    $this->collectionData = $collectionData??collect([]);

    return $this;
  }
}

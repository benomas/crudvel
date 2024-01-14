<?php

namespace Crudvel\Libraries\Paginators;
use Crudvel\Libraries\Paginators\CvPaginate;

class CvSimplePaginator extends CvBasePaginator implements CvPaginate
{
  protected $comparator  = "like";
  public function filter() {
    $this->setComparator($this->getDbEngineContainer()->getLikeCommand());
    if(!$this->getSearchObject())
      $this->setSearchObject('');

    foreach ($this->getFilterQuery() as $field=>$filter){
      if(is_array($filter)){
        $filters = [];

        if(isAssociativeArray($filter))
          $filters[]=$filter;
        else
          $filters = $filter;

        foreach($filters as $fieldFilter)
          $this->applyCustomFilter($field,$fieldFilter);

        unset($this->filterQuery[$field]);
      }
    }
    //default filter
    if(!empty($this->getFilterQuery()))
      $this->getModelBuilderInstance()->where(function($query){
        foreach ($this->getFilterQuery() as $field=>$filter){
          if ($this->getSearchObject() === '')
              continue;
          $method=!isset($method)?"where":"orWhere";
          $query->{$method}($field,$this->getComparator(),"%{$this->getSearchObject()}%");
        }
      });
  }
}

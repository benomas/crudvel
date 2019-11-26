<?php

namespace Crudvel\Libraries\Paginators;
use Crudvel\Libraries\Paginators\CvPaginate;

class CvSimplePaginator extends CvBasePaginator implements CvPaginate
{
  protected $comparator  = "like";
  public function filter() {
    if(!$this->getSearchObject())
      $this->setSearchObject('');
    foreach ($this->getFilterQuery() as $field=>$filter){
      if(is_array($filter)){
        $this->applyCustomFilter($field,$filter);
        unset($this->filterQuery[$field]);
      }
    }
    //default filter
    if(!empty($this->getFilterQuery()))
      $this->getModelBuilderInstance()->where(function($query){
        foreach ($this->getFilterQuery() as $field=>$filter){
          $method=!isset($method)?"where":"orWhere";
          $query->{$method}($field,$this->getComparator(),"%{$this->getSearchObject()}%");
        }
      });
  }
}

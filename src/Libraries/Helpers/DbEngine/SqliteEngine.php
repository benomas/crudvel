<?php

namespace Crudvel\Libraries\DbEngine;

use Crudvel\Libraries\DbEngine\EngineInterface;

class SqliteEngine extends BaseDbEngine implements EngineInterface
{
  public function setFilterQueryString($filterQuery=[]){
    parent::setFilterQueryString($filterQuery);
    if($this->filterQueryString !== null)
      return $this;
    $this->filterQueryString = 'CONCAT(';
    foreach($filterQuery AS $filter=>$value)
      $this->filterQueryString.=$filter.',';

    $this->filterQueryString = rtrim($this->filterQueryString, ',').')';
    return $this;
  }

  public function getFilterQueryString(){
    return parent::getFilterQueryString();
  }

  public function getLikeCommand(){
    return 'LIKE';
  }

  public function getNotLikeCommand(){
    return 'NOT LIKE';
  }
}

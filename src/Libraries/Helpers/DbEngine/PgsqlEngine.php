<?php

namespace Crudvel\Libraries\DbEngine;

use Crudvel\Libraries\DbEngine\EngineInterface;

class PgsqlEngine extends BaseDbEngine implements EngineInterface
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

  public function getLikeCommand(){
    return 'ILIKE';
  }

  public function getNotLikeCommand(){
    return 'NOT ILIKE';
  }
}

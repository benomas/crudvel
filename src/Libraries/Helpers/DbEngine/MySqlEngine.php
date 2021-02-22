<?php

namespace Crudvel\Libraries\DbEngine;

use Crudvel\Libraries\DbEngine\EngineInterface;

class MySqlEngine extends BaseDbEngine implements EngineInterface
{
  public function setFilterQueryString($filterQuery=[]){
    parent::setFilterQueryString($filterQuery);
    if($this->filterQueryString !== null)
      return $this;
    $this->filterQueryString = 'CONCAT(';
    foreach($filterQuery AS $filter=>$value)
      $this->filterQueryString.="COALESCE($filter,''),";

    $this->filterQueryString = rtrim($this->filterQueryString, ',').')';
    
    return $this;
  }

  public function getFilterQueryString(){
    return parent::getFilterQueryString();
  }
}

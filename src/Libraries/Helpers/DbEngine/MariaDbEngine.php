<?php

namespace Crudvel\Libraries\DbEngine;

use Crudvel\Libraries\DbEngine\EngineInterface;

class MariaDbEngine extends MySqlDbEngine implements EngineInterface
{
  public function setFilterQueryString($filterQuery=[]){
    $this->$filterQueryString = parent::setFilterQueryString($filterQuery);
    return $this;
  }

  public function getFilterQueryString(){
    return parent::getFilterQueryString();
  }
}

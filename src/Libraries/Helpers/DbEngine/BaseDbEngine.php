<?php

namespace Crudvel\Libraries\DbEngine;

class BaseDbEngine
{
  protected $filterQueryString = null;
  protected $connection        = 'mysql';

  public function __construct(){
  }

  public function getConnection(){
    return $this->connection;
  }

  public function setFilterQueryString($filterQuery){
    $filterQuery = $filterQuery ?? [];
    if(count($filterQuery)===0){
      $this->filterQueryString = false;
      return $this;
    }
    if(count($filterQuery)===1){
      $this->filterQueryString = $filterQuery[0];
      return $this;
    }
    return $this;
  }

  public function getFilterQueryString(){
    return $this->filterQueryString;
  }
}

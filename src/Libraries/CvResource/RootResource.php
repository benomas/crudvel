<?php

namespace Crudvel\Libraries\CvResource;

class RootResource
{
  protected $resourceName;
  protected $model;
  protected $modelInstance;
  protected $requestInstance;
  protected $app;
  public function __construct($app = null){
    $this->app = $app;
  }

  public function test(){
    pdd('testa');
  }
}

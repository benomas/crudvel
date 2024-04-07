<?php
namespace Crudvel\Interfaces\DataCollector;
use Crudvel\Interfaces\DataCaller\ModelDataCallerInterface;

interface ModelDataCollectorInterface{
  // init import , open file, open source and set totalRecords
  public function __construct(ModelDataCallerInterface $dataCallerInstance);
  public function setModelBuilderInstance($modelBuilderInstance);
  public function getModelBuilderInstance();
  public function getDataCallerInstance():ModelDataCallerInterface;
  public function setDataCallerInstance(ModelDataCallerInterface $dataCallerInstance);
  public function loadContextData($contextData);
}

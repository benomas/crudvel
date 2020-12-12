<?php
namespace Crudvel\Interfaces\DataCollector;
use Crudvel\Interfaces\DataCaller\ModelDataCallerInterface;

interface ModelDataCollectorInterface{
  // init import , open file, open source and set totalRecords
  public function __construct(ModelDataCallerInterface $dataCallerInstace);
  public function setModelBuilderInstance($modelBuilderInstance=null);
  public function getModelBuilderInstance();
  public function getDataCallerInstace():ModelDataCallerInterface;
  public function setDataCallerInstace(ModelDataCallerInterface $dataCallerInstace);
  public function loadContextData($contextData=null);
}

<?php
namespace Crudvel\Interfaces\DataCollector;
use Crudvel\Interfaces\DataCaller\ModelDataCallerInterface;

interface ModelDataCollectorInterface{
  // init import , open file, open source and set totalRecords
  public function setModelData($modelName);
  public function getModelData();
  public function __construct(ModelDataCallerInterface $dataCallerInstace);
  public function getDataCallerInstace():ModelDataCallerInterface;
  public function setDataCallerInstace(ModelDataCallerInterface $dataCallerInstace);
}

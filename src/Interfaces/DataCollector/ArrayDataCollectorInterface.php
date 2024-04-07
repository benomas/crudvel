<?php
namespace Crudvel\Interfaces\DataCollector;
use Crudvel\Interfaces\DataCaller\ArrayDataCallerInterface;

interface ArrayDataCollectorInterface{
  // init import , open file, open source and set totalRecords
  public function __construct(ArrayDataCallerInterface $dataCallerInstance);
  public function setArrayData($arrayData=[]);
  public function getArrayData();
  public function getDataCallerInstance():ArrayDataCallerInterface;
  public function setDataCallerInstance(ArrayDataCallerInterface $dataCallerInstance);
  public function loadContextData($contextData=null);
}

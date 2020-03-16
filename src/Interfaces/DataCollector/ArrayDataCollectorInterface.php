<?php
namespace Crudvel\Interfaces\DataCollector;
use Crudvel\Interfaces\DataCaller\ArrayDataCallerInterface;

interface ArrayDataCollectorInterface{
  // init import , open file, open source and set totalRecords
  public function setArrayData($arrayData);
  public function getArrayData();
  public function __construct(ArrayDataCallerInterface $dataCallerInstace);
  public function getDataCallerInstace():ArrayDataCallerInterface;
  public function setDataCallerInstace(ArrayDataCallerInterface $dataCallerInstace);
}

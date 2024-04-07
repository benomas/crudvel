<?php
namespace Crudvel\Interfaces\DataCollector;
use Crudvel\Interfaces\DataCaller\JsonDataCallerInterface;

interface JsonDataCollectorInterface{
  public function __construct(JsonDataCallerInterface $dataCallerInstance);
  public function setJsonPath($jsonPath=null):JsonDataCollectorInterface;
  public function getDataCallerInstance():JsonDataCallerInterface;
  public function setDataCallerInstance(JsonDataCallerInterface $dataCallerInstance);
  public function loadContextData($contextData=null);
}

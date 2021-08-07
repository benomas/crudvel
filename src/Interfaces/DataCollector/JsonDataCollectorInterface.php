<?php
namespace Crudvel\Interfaces\DataCollector;
use Crudvel\Interfaces\DataCaller\JsonDataCallerInterface;

interface JsonDataCollectorInterface{
  public function __construct(JsonDataCallerInterface $dataCallerInstace);
  public function setJsonPath($jsonPath=null):JsonDataCollectorInterface;
  public function getDataCallerInstace():JsonDataCallerInterface;
  public function setDataCallerInstace(JsonDataCallerInterface $dataCallerInstace);
  public function loadContextData($contextData=null);
}
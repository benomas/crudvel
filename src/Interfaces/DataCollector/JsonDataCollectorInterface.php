<?php
namespace Crudvel\Interfaces\DataCollector;
use Crudvel\Interfaces\DataCaller\JsonDataCallerInterface;

interface JsonDataCollectorInterface{
  public function setJsonPath($jsonPath);
  public function __construct(JsonDataCallerInterface $dataCallerInstace);
  public function getDataCallerInstace():JsonDataCallerInterface;
  public function setDataCallerInstace(JsonDataCallerInterface $dataCallerInstace);
}

<?php
namespace Crudvel\Interfaces\DataCollector;
use Crudvel\Interfaces\DataCaller\XlsxDataCallerInterface;

interface XlsxDataCollectorInterface{
  public function __construct(XlsxDataCallerInterface $dataCallerInstace);
  public function setXlsxPath($xlsxPath=null):JsonDataCollectorInterface;
  public function getDataCallerInstace():XlsxDataCallerInterface;
  public function setDataCallerInstace(XlsxDataCallerInterface $dataCallerInstace);
  public function loadContextData($contextData=null);
}

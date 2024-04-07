<?php
namespace Crudvel\Interfaces\DataCollector;
use Crudvel\Interfaces\DataCaller\XlsxDataCallerInterface;

interface XlsxDataCollectorInterface{
  public function __construct(XlsxDataCallerInterface $dataCallerInstance);
  public function setXlsxPath($xlsxPath=null):XlsxDataCollectorInterface;
  public function getDataCallerInstance():XlsxDataCallerInterface;
  public function setDataCallerInstance(XlsxDataCallerInterface $dataCallerInstance);
  public function loadContextData($contextData=null);
}

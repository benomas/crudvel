<?php
namespace Crudvel\Interfaces\DataCaller;

use Crudvel\Interfaces\DataCollector\DataCollectorInterface;

interface DataCallerInterface{
  public function getCurrentCollectorInstance():DataCollectorInterface;
  public function setCurrentCollectorInstance(DataCollectorInterface $currentCollectorInstance=null);
  public function dataTransform(array $arraySegment):array;
}

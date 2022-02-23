<?php namespace Crudvel\Exceptions;

use Exception;
use Illuminate\Support\Facades\Cache;
use \Crudvel\Interfaces\LogExceptionInterface;

abstract class LogException extends Exception implements LogExceptionInterface{
  protected $exceptionData = [];

// [Specific Logic]
  public function __construct($message = null, $code = 0, \Exception $previous = null){
    parent::__construct($message, $code, $previous);
    $this->message = "Error on line {$this->getLine()} in {$this->getFile()}: <b>{$this->getMessage()}</b>";
    customLog($this->message);
  }
// [End Specific Logic]
// [Getters]
// [End Getters]
// [Setters]
// [End Setters]
}

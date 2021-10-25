<?php namespace Crudvel\Exceptions\CvHttpClientExceptions;

use Exception;

class UnableToProcessRequest extends Exception{

  public function __construct($message = null, $code = 0, \Exception $previous = null){
    parent::__construct($message, $code, $previous);
    $this->message = "Error on line {$this->getLine()} in {$this->getFile()}: <b>{$this->getMessage()}</b> unable to process the request";
  }

  public function report(){
  }
}

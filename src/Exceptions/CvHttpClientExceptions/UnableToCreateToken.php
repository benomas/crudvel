<?php namespace Crudvel\Exceptions\CvHttpClientExceptions;

use Exception;

class UnableToCreateToken extends Exception{

  public function __construct($message = null, $code = 0, \Exception $previous = null){
    parent::__construct($message, $code, $previous);
    $this->message = "Error on line {$this->getLine()} in {$this->getFile()}: <b>{$this->getMessage()}</b> beared token cant be requested";
  }

  public function report(){
  }
}

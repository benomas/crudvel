<?php

namespace Crudvel\Libraries\CvSecurity\Exceptions;

class NoRegisterException extends \Exception{
  public function __construct($message = null, $code = 0, \Exception $previous = null){
    parent::__construct($message, $code, $previous);
    $this->message = "Error on line {$this->getLine()} in {$this->getFile()}: <b>{$this->getMessage()}</b> No register class available";
  }
}
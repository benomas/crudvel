<?php

namespace Crudvel\Traits;

trait CvClosureCommandTrait
{
  public function getConsoleInstance(){
    return $this->consoleInstance;
  }

  public function setConsoleInstance($consoleInstance=null){
    $this->consoleInstance = $consoleInstance??null;
    return $this;
  }

  public function select($message,$options,$default){
    return $this->getConsoleInstance()->choice($message,$options,$default);
  }

  public function confirm($message='',$options=['yes','no'],$default='no'){
    return $this->getConsoleInstance()->choice($message,$options,$default)==='yes';
  }
}
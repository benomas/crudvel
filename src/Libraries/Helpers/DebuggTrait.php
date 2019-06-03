<?php
namespace Crudvel\Libraries\Helpers;

trait DebuggTrait
{

  public function pdd(...$doDebugg){
    $sourcePosition        = ($expresion = $this->debuggExpresion)=== null?3:0;
    $this->debuggExpresion = null;
    $backtrace             = debug_backtrace();
    array_unshift(
      $doDebugg,
      $this->debuggMessage($sourcePosition)
    );
    if($expresion !== null && !$expresion)
      \Illuminate\Support\Facades\Log::warning("pdd is called at ".$this->debuggMessage($sourcePosition));
    else
      dd($doDebugg);
  }

  public function jdd(...$doDebugg)
  {
    $sourcePosition        = ($expresion = $this->debuggExpresion)=== null?3:0;
    $this->debuggExpresion = null;
    $backtrace             = debug_backtrace();
    array_unshift(
      $doDebugg,
      $this->debuggMessage($sourcePosition)
    );
    if($expresion !== null && !$expresion)
      \Illuminate\Support\Facades\Log::warning("jdd is called at ".$this->debuggMessage($sourcePosition));
    else{
      echo json_encode($doDebugg);
      die();
    }
  }

  public function customLog(...$params){
    $rightNow                 = microtime(true);
    $this->debuggScriptTime   = $this->debuggScriptStamp=== null?0:$rightNow-$this->debuggScriptStamp;
    if(!$this->debuggScriptStamp)
      $this->debuggScriptStamp  = $rightNow;
    $params                   = json_encode($params);
    $backtrace                = debug_backtrace();
    $sourcePosition           = 3;
    \Illuminate\Support\Facades\Log::info(
      $this->debuggMessage($sourcePosition)." with message: ".$params.'. Script ejecution time until here is '.$this->debuggScriptTime. ' Seconds'
    );
  }

  private function debuggMessage($sourcePosition=0){
    $backtrace = debug_backtrace();
    return "Log from ".$backtrace[$sourcePosition]['file']." - ".$backtrace[$sourcePosition+1]['function']." in the line: ".$backtrace[$sourcePosition]['line'];
  }

  public function cvtest($expresion=null){
    $this->debuggExpresion=(boolean) $expresion;
    return $this;
  }

  public function getCheckPoint(){
    $rightNow = microtime(true);
    return $this->debuggScriptTime = $this->debuggScriptStamp=== null?0:$rightNow-$this->debuggScriptStamp;
  }
}

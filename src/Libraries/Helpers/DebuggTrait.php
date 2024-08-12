<?php
namespace Crudvel\Libraries\Helpers;

use Illuminate\Support\Facades\Log;

trait DebuggTrait
{
  public static array|null $loginChannles = null;
  public function getLoginChannles() {
    if (static::$loginChannles)
      return static::$loginChannles;

    return static::$loginChannles=config('logging.channels');
  }

  public function pdd(...$doDebugg): void {
    $sourcePosition        = ($expression = $this->debuggExpresion)=== null?3:0;
    $this->debuggExpresion = null;
    array_unshift(
      $doDebugg,
      $this->debuggMessage($sourcePosition)
    );
    if($expression !== null && !$expression)
      Log::warning("pdd is called at ".$this->debuggMessage($sourcePosition));
    else
      dd($doDebugg);
  }

  public function jdd(...$doDebugg): void {
    $sourcePosition        = ($expression = $this->debuggExpresion)=== null?3:0;
    $this->debuggExpresion = null;
    array_unshift(
      $doDebugg,
      $this->debuggMessage($sourcePosition)
    );
    if($expression !== null && !$expression)
      Log::warning("jdd is called at ".$this->debuggMessage($sourcePosition));
    else{
      echo json_encode($doDebugg);
      die();
    }
  }

  public function customLog(...$params): void {
    $rightNow                 = microtime(true);
    $this->debuggScriptTime   = $this->debuggScriptStamp=== null?0:$rightNow-$this->debuggScriptStamp;
    if(!$this->debuggScriptStamp)
      $this->debuggScriptStamp  = $rightNow;
    $params                   = json_encode($params);
    $sourcePosition           = 3;
    Log::info(
      $this->debuggMessage($sourcePosition)." with message: ".$params.'. Script ejecution time until here is '.$this->debuggScriptTime. ' Seconds'
    );
  }

  private function debuggMessage($sourcePosition=0): string {
    $backtrace = debug_backtrace();
    return "Log from ".$backtrace[$sourcePosition]['file']." - ".$backtrace[$sourcePosition+1]['function']." in the line: ".$backtrace[$sourcePosition]['line'];
  }

  public function cvtest($expresion=null): static {
    $this->debuggExpresion=(boolean) $expresion;

    return $this;
  }

  public function getCheckPoint(){
    $rightNow = microtime(true);
    return $this->debuggScriptTime = $this->debuggScriptStamp=== null?0:$rightNow-$this->debuggScriptStamp;
  }

  public function customTargetLog($target = null,...$params): void {
    $rightNow                 = microtime(true);
    $this->debuggScriptTime   = $this->debuggScriptStamp=== null?0:$rightNow-$this->debuggScriptStamp;
    if(!$this->debuggScriptStamp)
      $this->debuggScriptStamp  = $rightNow;
    $params                   = json_encode($params);
    $sourcePosition           = 4;

    if (!$target || !isset(static::getLoginChannles()["daily_{$target}"])){
      Log::info(
        $this->debuggMessage($sourcePosition)." with message: ".$params.'. Script ejecution time until here is '.$this->debuggScriptTime. ' Seconds'
      );

      return ;
    }

    Log::channel("daily_{$target}")->{$target}(
      $this->debuggMessage($sourcePosition)." with message: ".$params.'. Script ejecution time until here is '.$this->debuggScriptTime. ' Seconds'
    );
  }

  public function customEmergencyLog(...$params): void {
    $this->customTargetLog('emergency',...$params);
  }

  public function customAlertLog(...$params): void {
    $this->customTargetLog('alert',...$params);
  }

  public function customCriticalLog(...$params): void {
    $this->customTargetLog('critical',...$params);
  }

  public function customErrorLog(...$params): void {
    $this->customTargetLog('error',...$params);
  }

  public function customWarningLog(...$params): void {
    $this->customTargetLog('warning',...$params);
  }

  public function customNoticeLog(...$params): void {
    $this->customTargetLog('notice',...$params);
  }

  public function customInfoLog(...$params): void {
    $this->customTargetLog('info',...$params);
  }

  public function customDebugLog(...$params): void {
    $this->customTargetLog('debug',...$params);
  }
}

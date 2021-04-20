<?php
namespace Crudvel\Libraries\Helpers;

trait TerminalTrait
{
  public function cvConsoler($message){
    if(strpos(php_sapi_name(), 'cli') !== false)
      echo $message;
    else
      customLog(preg_replace('/(\e\[\d\dm)/i', '', $message));
  }

  public function blackColorCode(){
    return '30';
  }

  public function redColorCode(){
    return '31';
  }

  public function greenColorCode(){
    return '32';
  }

  public function brownColorCode(){
    return '33';
  }

  public function blueColorCode(){
    return '34';
  }

  public function purpleColorCode(){
    return '35';
  }

  public function cyanColorCode(){
    return '36';
  }

  public function whiteColorCode(){
    return '37';
  }

  public function defaultColorCode(){
    return '39';
  }

  public function colorScape($colorCode='39'){
    return "\e[".$colorCode."m";
  }

  public function blackTC($message){
    return $this->fixMessage($this->colorScape($this->blackColorCode()).$message);
  }

  public function redTC($message){
    return $this->fixMessage($this->colorScape($this->redColorCode()).$message);
  }

  public function greenTC($message){
    return $this->fixMessage($this->colorScape($this->greenColorCode()).$message);
  }

  public function brownTC($message){
    return $this->fixMessage($this->colorScape($this->brownColorCode()).$message);
  }

  public function blueTC($message){
    return $this->fixMessage($this->colorScape($this->blueColorCode()).$message);
  }

  public function purpleTC($message){
    return $this->fixMessage($this->colorScape($this->purpleColorCode()).$message);
  }

  public function cyanTC($message){
    return $this->fixMessage($this->colorScape($this->cyanColorCode()).$message);
  }

  public function whiteTC($message){
    return $this->fixMessage($this->colorScape($this->whiteColorCode()).$message);
  }

  public function defauTC($message){
    return $this->fixMessage($this->colorScape($this->defauColorCode()).$message);
  }

  public function resetTag(){
    return $this->colorScape($this->defaultColorCode());
  }

  public function fixMessage($coloredMessage){
    $defaultColorTag = $this->resetTag();
    return "$coloredMessage$defaultColorTag";
  }

  public function composerDump(){
    cvConsoler(cvGreenTC('scaff script completed, now composer dump-autoload will be launched...')."\n");
    $scriptResponse = customExec('composer dump-autoload');
    cvConsoler(cvGreenTC('composer dump-autoload completed')."\n");
    return $scriptResponse;
  }

  public function cvConsoleException(\Exception $e, $extraParam = null){
    $this->cvConsoler(
      "\n"
      .cvNegative($e->getMessage())
      ."\n"
      .cvInfo(' at file ')
      .cvWarning($e->getFile())
      ."\n"
      .cvInfo(' at line ')
      .cvWarning($e->getLine())
    );
    if($extraParam)
      $this->cvConsoler(
        "\n"
        .cvNegative('with data')
        ."\n"
        .cvInfo(json_encode($extraParam))
      );
  }
}

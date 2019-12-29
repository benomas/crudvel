<?php

namespace Crudvel\Libraries\CvScaffSupport;
use Illuminate\Support\Str;
abstract class CvBaseScaff
{
  use \Crudvel\Traits\CvPatronTrait;
  protected $modes = [
    'creator',
    'updator',
    'deletor',
  ];
  private $templateCache;
  private $extraParams;
  private   $force   = false;
  protected $mode    = 'create';
  protected $context = 'back';

  abstract protected function getTemplatePath();
  abstract protected function getTemplateReceptorPath();

  public function __construct(){
  }

  public function getConsoleInstance(){
    return $this->consoleInstance;
  }

  public function getResource(){
    return $this->resource??'';
  }

  public function getForce(){
    return $this->force??null;
  }

  public function getMode(){
    return $this->mode??null;
  }

  public function getExtraParams(){
    return $this->extraParams??null;
  }

  public function getModes(){
    return $this->modes??[];
  }

  protected function getTemplateCache(){
    return $this->templateCache??[];
  }

  public function getContext(){
    return $this->context??null;
  }
  public function getTemplateFileName(){
    return fileBaseName(pathinfo($this->getTemplatePath(), PATHINFO_FILENAME));
  }

  public function setConsoleInstance($consoleInstance=null){
    $this->consoleInstance = $consoleInstance??null;
    return $this;
  }

  public function setForce($force=null){
    $this->force = $force??null;
    return $this;
  }

  public function setResource($resource=null){
    $this->resource = $resource??null;
    return $this;
  }

  public function setMode($mode=null){
    $this->mode = $mode??null;
    return $this;
  }

  public function setExtraParams($extraParams=null){
    $this->extraParams = $extraParams??null;
    return $this;
  }

  protected function setTemplateCache($templateCache=null){
    $this->templateCache = $templateCache??null;
    return $this;
  }

  public function force($force=null){
    return $this->setForce($force);
  }

  public function isForced(){
    $this->getForce();
  }

  public function stablishConsoleInstace($consoleInstace=null){
    return $this->setConsoleInstance($consoleInstace);
  }

  public function stablishResource($resource=null){
    return $this->setResource($resource);
  }
  public function stablishMode($mode=null){
    $mode=$mode??$this->getMode()??'create';
    if(!in_array($mode,$this->getModes()))
      throw new \Exception('Invalid mode');
    return $this->setMode($mode);
  }

  protected function templateExist(){
    return file_exists($this->getTemplatePath());
  }

  protected function templateReceptorExists(){
    return file_exists($this->getPath());
  }

  protected function fixCase($quantity='singular',$case='camel',$fixer=null){
    $extraParams = $this->getExtraParams();
    $template = $this->getTemplateCache();
    foreach($extraParams as $param=>$value){
      if($fixer)
        $resolvedTag=$fixer($value);
      else
        $resolvedTag= Str::$case(Str::$quantity($value));
      $quantityTag=$quantity!==''?
        "_$quantity":
        '';
      $caseTag=$case!==''?
        "_$case":
        '';
      $template = str_replace("<cv{$quantityTag}{$caseTag}_{$param}_cv>",$resolvedTag,$template);
    }
    $this->setTemplateCache($template);
    return $this;
  }

  protected function fixFinalTag(){
    return $this->fixCase('','final',function($val){return $val;});
  }

  protected function fixSingularCamelTag(){
    return $this->fixCase('singular','camel');
  }

  protected function fixPluraCamelTag(){
    return $this->fixCase('plural','camel');
  }

  protected function fixSingularSnakeTag(){
    return $this->fixCase('singular','snake',function($val){return Str::singular(fixedSnake($val));});
  }

  protected function fixPluraSnakeTag(){
    return $this->fixCase('plural','snake',function($val){return Str::plural(fixedSnake($val));});
  }

  protected function fixSingularSlugTag(){
    return $this->fixCase('singular','slug',function($val){return Str::singular(fixedSlug($val));});
  }

  protected function fixPluraSlugTag(){
    return $this->fixCase('plural','slug',function($val){return Str::plural(fixedSlug($val));});
  }

  protected function fixSingularStudlyTag(){
    return $this->fixCase('singular','studly');
  }

  protected function fixPluraStudlyTag(){
    return $this->fixCase('plural','studly');
  }

  protected function fixSingularTitleTag(){
    return $this->fixCase('singular','title');
  }

  protected function fixPluraTitleTag(){
    return $this->fixCase('plural','title');
  }

  protected function fixSingularLowerTag(){
    return $this->fixCase('singular','lower',function($val){return Str::singular(strtolower($val));});
  }

  protected function fixPluraLowerTag(){
    return $this->fixCase('plural','lower',function($val){return Str::plural(strtolower($val));});
  }

  protected function fixSingularUpperTag(){
    return $this->fixCase('singular','upper',function($val){return Str::singular(strtoupper($val));});
  }

  protected function fixPluraUpperTag(){
    return $this->fixCase('plural','upper',function($val){return Str::plural(strtoupper($val));});
  }
//[LoadTemplate Modes]
  protected function creatorLoadTemplate(){
    if(!$this->templateExist()){
      if($this->confirm('template file doesnt exist, do you want to create it?'))
        $this->creatorTemplateFile();
      else
        throw new \Exception('Template doesnt exist');
    }
    return file_get_contents($this->getTemplatePath());
  }
  protected function updatorLoadTemplate(){
    return null;
  }
  protected function deletorLoadTemplate(){
    return null;
  }
//[End LoadTemplate Modes]

//[CalculateParams Modes]
  protected function creatorCalculateParams($template=null){
    $patern = '/<cv_singular_camel_(.+?)_cv>|<cv_plural_camel_(.+?)_cv>|<cv_singular_snake_(.+?)_cv>|<cv_plural_snake_(.+?)_cv>|<cv_singular_slug_(.+?)_cv>|<cv_plural_slug_(.+?)_cv>|<cv_singular_studly_(.+?)_cv>|<cv_plural_studly_(.+?)_cv>|<cv_singular_lower_(.+?)_cv>|<cv_plural_lower_(.+?)_cv>|<cv_singular_upper_(.+?)_cv>|<cv_plural_upper_(.+?)_cv>|<cv_final_(.+?)_cv>/';
    preg_match_all($patern,$template,$matches);
    if(isset($matches[0]))
      unset($matches[0]);
    $extraParams=[];
    foreach($matches as $collection)
      foreach($collection as $param)
        if($param && $param !== '')
          $extraParams[$param]=null;
    $extraParams['resource'] = $this->getResource();
    $this->setExtraParams($extraParams);
    return $this;
  }
  protected function updatorCalculateParams($template=null){
    $this->setExtraParams(['resource' => $this->getResource()]);
    return $this;
  }
  protected function deletorCalculateParams($template=null){
    $this->setExtraParams(['resource' => $this->getResource()]);
    return $this;
  }
//[End CalculateParams Modes]

//[FixTemplate Modes]
  protected function creatorFixTemplate($template=null){
    return $this->fixFinalTag()
    ->fixSingularCamelTag()
    ->fixPluraCamelTag()
    ->fixSingularSnakeTag()
    ->fixPluraSnakeTag()
    ->fixSingularSlugTag()
    ->fixPluraSlugTag()
    ->fixSingularStudlyTag()
    ->fixPluraStudlyTag()
    ->fixSingularTitleTag()
    ->fixPluraTitleTag()
    ->fixSingularLowerTag()
    ->fixPluraLowerTag()
    ->fixSingularUpperTag()
    ->fixPluraUpperTag()
    ->getTemplateCache();
  }
  protected function updatorFixTemplate($template=null){
    return null;
  }
  protected function deletorFixTemplate($template=null){
    return null;
  }
//[End FixTemplate Modes]

//[InyectFixedTemplate Modes]
  protected function creatorInyectFixedTemplate($template=null){
    if($this->templateReceptorExists()){
      if(!$this->isForced() && !$this->confirm('file already defined rewrite it?'))
        throw new \Exception('Error '.$this->getPath().' cant be created');
      try{
        unlink($this->getPath());
        cvConsoler(cvGreenTC('Old file was deleted')."\n");
      }catch(\Exception $e){
        throw new \Exception('Error '.$this->getPath().' cant be deleted');
      }
    }
    try{
      file_put_contents($this->getPath(), $template);
      cvConsoler(cvGreenTC('New file was created')."\n");
    }catch(\Exception $e){
      throw new \Exception('Error '.$this->getPath().' cant be created');
    }
    return $this;
  }
  protected function updatorInyectFixedTemplate($template=null){
    return $this;
  }
  protected function deletorInyectFixedTemplate($template=null){
    if(!$this->templateReceptorExists()){
      cvConsoler(cvBrownTC($this->getPath().' file doest exist')."\n");
      return $this;
    }
    try{
      unlink($this->getPath());
    }catch(\Exception $e){
      throw new \Exception('Error '.$this->getPath().' cant be deleted');
    }
    return $this;
  }
//[End InyectFixedTemplate Modes]
  private function creatorTemplateFile(){
    try{
      file_put_contents($this->getTemplatePath(),'');
    }catch(\Exception $e){
      throw new \Exception('Error '.$this->getTemplatePath().' cant be created');
    }
    return $this;
  }

  private function fixModeStep($step='loadTemplate'){
    return Str::camel($this->getMode().'_'.$step);
  }
  public function loadTemplate(){
    $callBack = $this->fixModeStep(__FUNCTION__);
    if(method_exists($this,$callBack))
      return $this->$callBack();
    return null;
  }
  private function calculateParams($template=null){
    $callBack = $this->fixModeStep(__FUNCTION__);
    if(method_exists($this,$callBack))
      return $this->$callBack($template);
    return null;
  }
  public function askAditionalParams($template=null){
    $this->calculateParams($template);
    $extraParams=$this->getExtraParams();
    foreach($extraParams as $param=>$value){
      if(!$value || $value==='')
        $extraParams[$param] = $this->getConsoleInstance()->ask("What is the value for $param param")??'';
    }
    $this->setExtraParams($extraParams);
    return $this;
  }
  public function fixTemplate($template=null){
    $this->setTemplateCache($template);
    $callBack = $this->fixModeStep(__FUNCTION__);
    if(method_exists($this,$callBack))
      return $this->$callBack();
    return null;
  }
  public function inyectFixedTemplate($template=null){
    $callBack = $this->fixModeStep(__FUNCTION__);
    if(method_exists($this,$callBack))
      return $this->$callBack($template);
    return $this;
  }
  public function confirm($message='',$options=['yes','no'],$default='no'){
    return $this->getConsoleInstance()->choice($message,$options,$default)==='yes';
  }
}

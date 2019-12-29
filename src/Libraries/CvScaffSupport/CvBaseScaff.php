<?php

namespace Crudvel\Libraries\CvScaffSupport;
use Illuminate\Support\Str;
abstract class CvBaseScaff
{
  protected $modes = [
    'create-template-receptor',
    'force-create-template-receptor',
    'update-template-receptor',
    'force-update-template-receptor',
    'delete-template-receptor',
    'force-delete-template-receptor',
  ];
  protected $mode = 'create-template-receptor';
  private $templateCache;
  private $extraParams;

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

  public function getMode(){
    return $this->mode??null;
  }

  public function getExtraParams($extraParams=null){
    return $this->extraParams??null;
  }

  public function getModes(){
    return $this->modes??[];
  }

  protected function getTemplateCache(){
    return $this->templateCache??[];
  }

  public function setConsoleInstance($consoleInstance=null){
    $this->consoleInstance = $consoleInstance??null;
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

  public function stablishConsoleInstace($consoleInstace=null){
    return $this->setConsoleInstance($consoleInstace);
  }

  public function stablishResource($resource=null){
    return $this->setResource($resource);
  }
  public function stablishMode($mode=null){
    $mode=$mode??$this->getMode()??'create-template-receptor';
    if(!in_array($mode,$this->getModes()))
      throw new \Exception('Invalid mode');
    return $this->setMode($mode);
  }

  protected function templateExist(){
    return file_exists($this->getTemplatePath());
  }

  protected function templateReceptorExists(){
    return file_exists($this->getTemplateReceptorPath());
  }

  protected function fixCase($quantity='singular',$case='camel',$fixer=null){
    $extraParams = $this->getExtraParams();
    $template = $this->getTemplateCache();
    foreach($extraParams as $param=>$value){
      if($fixer)
        $resolvedTag=$fixer(Str::$quantity($value));
      else
        $resolvedTag= Str::$case(Str::$quantity($value));
      $template = str_replace("<cv_{$quantity}_{$case}_{$param}_cv>",$resolvedTag,$template);
    }
    $this->setTemplateCache($template);
    return $this;
  }

  protected function fixSingularCamelTag(){
    return $this->fixCase('singular','camel');
  }

  protected function fixPluraCamelTag(){
    return $this->fixCase('plural','camel');
  }

  protected function fixSingularSnakeTag(){
    return $this->fixCase('singular','snake',function($val){return fixedSnake($val);});
  }

  protected function fixPluraSnakeTag(){
    return $this->fixCase('plural','snake',function($val){return fixedSnake($val);});
  }

  protected function fixSingularSlugTag(){
    return $this->fixCase('singular','slug',function($val){return fixedSlug($val);});
  }

  protected function fixPluraSlugTag(){
    return $this->fixCase('plural','slug',function($val){return fixedSlug($val);});
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
    return $this->fixCase('singular','lower',function($val){return strtolower($val);});
  }

  protected function fixPluraLowerTag(){
    return $this->fixCase('plural','lower',function($val){return strtolower($val);});
  }

  protected function fixSingularUpperTag(){
    return $this->fixCase('singular','upper',function($val){return strtoupper($val);});
  }

  protected function fixPluraUpperTag(){
    return $this->fixCase('plural','upper',function($val){return strtoupper($val);});
  }
//[LoadTemplate Modes]
  protected function createTemplateReceptorLoadTemplate(){
    if(!$this->templateExist())
      throw new \Exception('Template doesnt exist');
    return file_get_contents($this->getTemplatePath());
  }
  protected function forceCreateTemplateReceptorLoadTemplate(){
    if(!$this->templateExist())
      throw new \Exception('Template doesnt exist');
    return file_get_contents($this->getTemplatePath());
  }
  protected function updateTemplateReceptorLoadTemplate(){
    return null;
  }
  protected function forceUpdateTemplateReceptorLoadTemplate(){
    return null;
  }
  protected function deleteTemplateReceptorLoadTemplate(){
    return null;
  }
  protected function forceDeleteTemplateReceptorLoadTemplate(){
    return null;
  }
//[End LoadTemplate Modes]

//[CalculateParams Modes]
  protected function createTemplateReceptorCalculateParams($template=null){
    $patern = '/<cv_singular_camel_(.+?)_cv>|<cv_plural_camel_(.+?)_cv>|<cv_singular_snake_(.+?)_cv>|<cv_plural_snake_(.+?)_cv>|<cv_singular_slug_(.+?)_cv>|<cv_plural_slug_(.+?)_cv>|<cv_singular_studly_(.+?)_cv>|<cv_plural_studly_(.+?)_cv>|<cv_singular_lower_(.+?)_cv>|<cv_plural_lower_(.+?)_cv>|<cv_singular_upper_(.+?)_cv>|<cv_plural_upper_(.+?)_cv>/';
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
  protected function forceCreateTemplateReceptorCalculateParams($template=null){
    return $this->createTemplateReceptorCalculateParams($template);
  }
  protected function updateTemplateReceptorCalculateParams($template=null){
    $this->setExtraParams(['resource' => $this->getResource()]);
    return $this;
  }
  protected function forceUpdateTemplateReceptorCalculateParams($template=null){
    $this->setExtraParams(['resource' => $this->getResource()]);
    return $this;
  }
  protected function deleteTemplateReceptorCalculateParams($template=null){
    $this->setExtraParams(['resource' => $this->getResource()]);
    return $this;
  }
  protected function forceDeleteTemplateReceptorCalculateParams($template=null){
    $this->setExtraParams(['resource' => $this->getResource()]);
    return $this;
  }
//[End CalculateParams Modes]

//[FixTemplate Modes]
  protected function createTemplateReceptorFixTemplate($template=null){
    return $this->fixSingularCamelTag()
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
  protected function forceCreateTemplateReceptorFixTemplate($template=null){
    return $this->createTemplateReceptorFixTemplate($template);
  }
  protected function updateTemplateReceptorFixTemplate($template=null){
    return null;
  }
  protected function forceUpdateTemplateReceptorFixTemplate($template=null){
    return null;
  }
  protected function deleteTemplateReceptorFixTemplate($template=null){
    return null;
  }
  protected function forceDeleteTemplateReceptorFixTemplate($template=null){
    return null;
  }
//[End FixTemplate Modes]

//[InyectFixedTemplate Modes]
  protected function createTemplateReceptorInyectFixedTemplate($template=null){
    if($this->templateReceptorExists())
      throw new \Exception('Warging Template already defined');
    try{
      file_put_contents($this->getTemplateReceptorPath(), $template);
    }catch(\Exception $e){
      throw new \Exception('Error '.$this->getTemplateReceptorPath().' cant be created with '.$template);
    }
    return $this;
  }
  protected function forceCreateTemplateReceptorInyectFixedTemplate($template=null){
    if($this->templateReceptorExists()){
      try{
        unlink($this->getTemplateReceptorPath());
      }catch(\Exception $e){
        throw new \Exception('Error '.$this->getTemplateReceptorPath().' cant be deleted');
      }
    }
    try{
      file_put_contents($this->getTemplateReceptorPath(), $template);
    }catch(\Exception $e){
      throw new \Exception('Error '.$this->getTemplateReceptorPath().' cant be created');
    }
    return $this;
  }
  protected function updateTemplateReceptorInyectFixedTemplate($template=null){
    return $this;
  }
  protected function forceUpdateTemplateReceptorInyectFixedTemplate($template=null){
    return $this;
  }
  protected function deleteTemplateReceptorInyectFixedTemplate($template=null){
    if(!$this->templateReceptorExists()){
      cvConsoler(cvBrownTC($this->getTemplateReceptorPath().' file doest exist')."\n");
      return $this;
    }
    try{
      unlink($this->getTemplateReceptorPath());
    }catch(\Exception $e){
      throw new \Exception('Error '.$this->getTemplateReceptorPath().' cant be deleted');
    }
    return $this;
  }
  protected function forceDeleteTemplateReceptorInyectFixedTemplate($template=null){
    return $this->deleteTemplateReceptorInyectFixedTemplate($template);
  }
//[End InyectFixedTemplate Modes]
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
}

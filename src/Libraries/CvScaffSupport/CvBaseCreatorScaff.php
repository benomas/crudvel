<?php

namespace Crudvel\Libraries\CvScaffSupport;
use Illuminate\Support\Str;
use \Crudvel\Interfaces\CvScaffInterface;

class CvBaseCreatorScaff extends \Crudvel\Libraries\CvScaffSupport\CvBaseScaff implements CvScaffInterface
{
  protected $relatedTargetPath;
  protected $relatedTemplatePath;
  protected $absolutTargetPath;
  protected $absolutTemplatePath;
  protected $template;
  protected $extraParams;

  public function __construct(...$propertyRewriter){
    parent::__construct(...$propertyRewriter);
  }

//[Getters]
  public function getRelatedTargetPath(){
    return $this->relatedTargetPath??null;
  }

  public function getRelatedTemplatePath(){
    return $this->relatedTemplatePath??null;
  }

  public function getAbsolutTargetPath(){
    return $this->absolutTargetPath??null;
  }

  public function getAbsolutTemplatePath(){
    return $this->absolutTemplatePath??null;
  }

  public function getTemplate(){
    return $this->template??null;
  }

  public function getExtraParams(){
    return $this->extraParams??null;
  }
//[End Getters]

//[Setters]
  public function setRelatedTargetPath($relatedTargetPath=null){
    $this->relatedTargetPath = $relatedTargetPath??null;
    return $this;
  }

  public function setRelatedTemplatePath($relatedTemplatePath=null){
    $this->relatedTemplatePath = $relatedTemplatePath??null;
    return $this;
  }

  public function setAbsolutTargetPath($absolutTargetPath=null){
    $this->absolutTargetPath = $absolutTargetPath??null;
    return $this;
  }

  public function setAbsolutTemplatePath($absolutTemplatePath=null){
    $this->absolutTemplatePath = $absolutTemplatePath??null;
    return $this;
  }

  public function setTemplate($template=null){
    $this->template=$template??null;
    return $this;
  }

  public function setExtraParams($extraParams=null){
    $this->extraParams = $extraParams??null;
    return $this;
  }
//[End Setters]

//[Stablishers]
  protected function stablishRelatedTargetPath(){
    $relatedTargetPath = $this->getScaffParams()['related-target-path']??null;
    if(!$relatedTargetPath || $relatedTargetPath==='')
      throw new \Exception("Error, related-target-path is not defined in scaff tree");
    return $this->setRelatedTargetPath($relatedTargetPath??null);
  }

  protected function stablishRelatedTemplatePath(){
    $relatedTemplatePath = $this->getScaffParams()['related-template-path']??null;
    if(!$relatedTemplatePath || $relatedTemplatePath==='')
      throw new \Exception("Error, related-template-path is not defined in scaff tree");
    return $this->setRelatedTemplatePath($relatedTemplatePath??null);
  }

  protected function stablishAbsolutTargetPath(){
    return $this->setAbsolutTargetPath(base_path($this->getRelatedTargetPath()));
  }

  protected function stablishAbsolutTemplatePath(){
    return $this->setAbsolutTemplatePath(base_path($this->getRelatedTemplatePath()));
  }
//[End Stablishers]
  private function createTemplateFile(){
    try{
      file_put_contents($this->getAbsolutTemplatePath(),'');
      cvConsoler(cvBrownTC('Template file was created, ')."\n");
    }catch(\Exception $e){
      throw new \Exception('Error '.$this->getAbsolutTemplatePath().' cant be created');
    }
    throw new \Exception('Warning you need to fill template file ['.$this->getAbsolutTemplatePath().'] and run this command again');
  }

  protected function loadTemplate(){
    $absolutTemplatePath = $this->getAbsolutTemplatePath();
    if(!file_exists($absolutTemplatePath)){
      if($this->confirm('template file doesnt exist, do you want to create it?'))
        $this->createTemplateFile();
      else
        throw new \Exception('Template doesnt exist');
    }
    try{
      $template = file_get_contents($absolutTemplatePath);
    }catch(\Exception $e){
      throw new \Exception("template from $absolutTemplatePath, cant be loaded");
    }
    return $this->setTemplate($template);
  }

  private function processPaths(){
    return $this->stablishRelatedTargetPath()
      ->stablishRelatedTemplatePath()
      ->stablishAbsolutTargetPath()
      ->stablishAbsolutTemplatePath();
  }

  protected function calculateParams(){
    $template = $this->getTemplate();
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
  public function askAditionalParams(){
    $template = $this->getTemplate();
    $extraParams=$this->getExtraParams();
    foreach($extraParams as $param=>$value){
      if(!$value || $value==='')
        $extraParams[$param] = $this->getConsoleInstance()->ask("What is the value for $param param")??'';
    }
    $this->setExtraParams($extraParams);
    return $this;
  }

  protected function fixCase($quantity='singular',$case='camel',$fixer=null){
    $extraParams = $this->getExtraParams();
    $template = $this->getTemplate();
    foreach($extraParams as $param=>$value){
      if($fixer)
        $resolvedTag=$fixer($value);
      else
        $resolvedTag= Str::$case(Str::$quantity($value));
      $quantityTag=$quantity!==''?"_$quantity":'';
      $caseTag=$case!==''?"_$case":'';
      $template = str_replace("<cv{$quantityTag}{$caseTag}_{$param}_cv>",$resolvedTag,$template);
    }
    $this->setTemplate($template);
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

  protected function fixTemplate(){
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
        ->getTemplate();
  }

  public function scaff() {
    return $this->processPaths()
      ->calculateParams()
      ->askAditionalParams()
      ->fixTemplate()
      ->inyectFixedTemplate();
  }
}

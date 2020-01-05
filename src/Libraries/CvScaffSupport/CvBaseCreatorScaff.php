<?php

namespace Crudvel\Libraries\CvScaffSupport;
use Illuminate\Support\Str;
use \Crudvel\Interfaces\CvScaffInterface;

abstract class CvBaseCreatorScaff extends \Crudvel\Libraries\CvScaffSupport\CvBaseScaff implements CvScaffInterface
{
  use \Crudvel\Traits\CvScaffBaseTrait;
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
    return $this->extraParams??[];
  }

  protected function getParam($param=null){
    return $this->getExtraParams()[$param]??null;
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
      cvConsoler(cvWarning('Template file was created, ')."\n");
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

  protected function calculateTargetFileName(){
    return $this->getAbsolutTargetPath().$this->selfRepresentation().$this->getFileExtension();
  }

  protected function targetFileAlreadyExist(){
    return file_exists($this->calculateTargetFileName());
  }

  protected function processPaths(){
    return $this->stablishAbsolutTargetPath()->stablishAbsolutTemplatePath();
  }

  protected function calculateParams(){
    $extraParams = [];
    $patern      = '/<##\[(.+?)\]\((.+?)\)##>/';
    preg_match_all($patern,$this->getTemplate(),$matches);
    $paramMatches=$matches[2]??[];
    foreach($paramMatches as $param)
      $extraParams[$param]=null;
    $extraParams['resource']=$this->getResource();
    $this->setExtraParams($extraParams);
    return $this;
  }

  public function askAditionalParams(){
    $extraParams=$this->getExtraParams();
    foreach($extraParams as $param=>$value)
      if(!$value || $value==='')
        $extraParams[$param] = $this->getConsoleInstance()->ask("What is the value for $param param")??'';

    $this->setExtraParams($extraParams);
    return $this;
  }

  protected function fixTemplate(){
    $template = $this->getTemplate();
    preg_match_all('/<##\[(.+?)\]\((.+?)\)##>/',$template,$matches);
    $caseCollections=$matches[1]??[];
    if(!count($caseCollections))
      return $this;
    $params=$matches[2]??[];
    if(!count($params))
      return $this;
    $extraParams = $this->getExtraParams();
    foreach($caseCollections as $position=>$caseCollection){
      $cases        = array_reverse(explode('|',$caseCollection));
      $currentParam = $params[$position]??'';
      $fixedValue   = $extraParams[$currentParam]??'';
      foreach($cases as $case)
        $fixedValue = $this->caseFixer($case,$fixedValue);
      $template = str_replace("<##[$caseCollection]($currentParam)##>",$fixedValue,$template);
    }
    return $this->setTemplate($template);
  }

  protected function inyectFixedTemplate(){
    if($this->targetFileAlreadyExist()){
      if(!$this->isForced()){
        if(!$this->confirm('file already defined rewrite it?'))
          throw new \Exception("Error {$this->calculateTargetFileName()} cant be created");
        $this->force();
      }
      try{
        unlink($this->calculateTargetFileName());
        cvConsoler(
          cvPositive('Old file ').
          cvInfo($this->calculateTargetFileName()).
          cvPositive('  was deleted by ').
          cvInfo(get_class($this)).
          "\n"
        );
      }catch(\Exception $e){
        throw new \Exception("Error {$this->calculateTargetFileName()} cant be deleted");
      }
    }
    try{
      $pathInfo = pathinfo($this->calculateTargetFileName());
      if(!file_exists($pathInfo['dirname']))
        mkdir($pathInfo['dirname']);
      file_put_contents($this->calculateTargetFileName(), $this->getTemplate());
      cvConsoler(
        cvPositive('New file ').
        cvInfo($this->calculateTargetFileName()).
        cvPositive('  was created by ').
        cvInfo(get_class($this)).
        "\n"
      );
    }catch(\Exception $e){
      throw new \Exception("Error {$this->calculateTargetFileName()} cant be created");
    }
    return $this;
  }

  public function scaff() {
    return $this->processPaths()
      ->loadTemplate()
      ->calculateParams()
      ->askAditionalParams()
      ->fixTemplate()
      ->inyectFixedTemplate();
  }
}

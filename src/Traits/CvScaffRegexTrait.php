<?php

namespace Crudvel\Traits;

trait CvScaffRegexTrait
{
//[Getters]
  public function getLeftRegexGlobalRequiriment(){
    return $this->leftRegexGlobalRequiriment??'';
  }

  public function getRightRegexGlobalRequiriment(){
    return $this->rightRegexGlobalRequiriment??'';
  }
//[End Getters]

//[Setters]
  public function setLeftRegexGlobalRequiriment($leftRegexGlobalRequiriment=null){
    $this->leftRegexGlobalRequiriment = $leftRegexGlobalRequiriment??null;
    return $this;
  }

  public function setRightRegexGlobalRequiriment($rightRegexGlobalRequiriment=null){
    $this->rightRegexGlobalRequiriment = $rightRegexGlobalRequiriment??null;
    return $this;
  }
//[End Setters]

//[Regex]
  protected function getLastRegexMatch($sourceText,$regex){
    preg_match_all($regex,$sourceText,$matches);
    $matches = $matches??null;
    if(!$matches){
      cvConsoler(cvNegative('no matches for regex'.$regex)."\n");
      return '';
    }
    return end($matches[0]);
  }

  protected function regexMaker($basePatern,...$replacers){
    $newRegex='';
    foreach(explode('<slot>', $basePatern) as $key=>$value)
      $newRegex.=$value.($replacers[$key]??'');
    return $newRegex;
  }

  protected function scapedRegexMaker($basePatern,...$replacers){
    $newRegex='';
    foreach(explode('<slot>', $basePatern) as $key=>$value)
      $newRegex.=$value.preg_quote($replacers[$key]??'', '/');
    return $newRegex;
  }

  protected function regexElementAdder($sourceText,$lastReference,$replace){
    $pattern='/((?>\s|\S)*?'.$this->getLeftRegexGlobalRequiriment().'(?>\s|\S)*?)(\s+)(,?)(\s*)'.
      '('.preg_quote($lastReference, '/').')'.
      '(\s*)(,?)((?>\s|\S)*'.$this->getRightRegexGlobalRequiriment().'(?>\s|\S)*)/';
    return preg_replace($pattern,'$1$2$3$4$5$2$3$4'.$replace.'$6$7$8',$sourceText);
  }

  protected function globalFileRegexAdder($elementPatern,$newElementPatern,$replace){
    $fileContent    = $this->getFile();
    $lastReference = $this->getLastRegexMatch($fileContent,$elementPatern);
    if(!$lastReference)
      throw new \Exception("Error element patern selector fail [$elementPatern]");

    if($this->getLastRegexMatch($fileContent,$newElementPatern)){
      cvConsoler(cvInfo('no changes required')."\n");
      return $this;
    }
    return $this->setFile(
      $this->regexElementAdder(
        $fileContent,
        $lastReference,
        $replace
      )
    );
  }
//[End Regex]
}

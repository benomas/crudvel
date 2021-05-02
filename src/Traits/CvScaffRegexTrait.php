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

  public function getLeftRegexElementAdder(){
    return $this->leftRegexElementAdder??null;
  }

  public function getMiddleRegexElementAdder(){
    return $this->middleRegexElementAdder??null;
  }

  public function getRightRegexElementAdder(){
    return $this->rightRegexElementAdder??null;
  }

  public function getLeftRegexElementRemover(){
    return $this->leftRegexElementRemover??null;
  }

  public function getMiddleRegexElementRemover(){
    return $this->middleRegexElementRemover??null;
  }

  public function getRightRegexElementRemover(){
    return $this->rightRegexElementRemover??null;
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

  public function setLeftRegexElementAdder($leftRegexElementAdder=null){
    $this->leftRegexElementAdder = $leftRegexElementAdder??null;

    return $this;
  }

  public function setMiddleRegexElementAdder($middleRegexElementAdder = null){
    $this->middleRegexElementAdder = $middleRegexElementAdder??null;

    return $this;
  }


  public function setRightRegexElementAdder($rightRegexElementAdder=null){
    $this->rightRegexElementAdder = $rightRegexElementAdder??null;

    return $this;
  }

  public function setLeftRegexElementRemover($leftRegexElementRemover=null){
    $this->leftRegexElementRemover = $leftRegexElementRemover??null;

    return $this;
  }

  public function setMiddleRegexElementRemover($middleRegexElementRemover=null){
    $this->middleRegexElementRemover = $middleRegexElementRemover??null;

    return $this;
  }

  public function setRightRegexElementRemover($rightRegexElementRemover=null){
    $this->rightRegexElementRemover = $rightRegexElementRemover??null;

    return $this;
  }
//[End Setters]

//[Regex]
  protected function getLastRegexMatch($sourceText,$regex){
    preg_match_all("/$regex/",$sourceText,$matches);
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
    $leftRegexElementAdder   = $this->getLeftRegexElementAdder()??'((?>\s|\S)*?'.$this->getLeftRegexGlobalRequiriment().'(?>\s|\S)*?)(\s+)(,?)(\s*)';
    $middleRegexElementAdder = $this->getMiddleRegexElementAdder()??'('.preg_quote($lastReference, '/').')';
    $rightRegexElementAdder  = $this->getRightRegexElementAdder()??'(\s*)(,?)((?>\s|\S)*'.$this->getRightRegexGlobalRequiriment().'(?>\s|\S)*)';

    $pattern="/{$leftRegexElementAdder}{$middleRegexElementAdder}{$rightRegexElementAdder}/";

    return preg_replace($pattern,'$1$2$3$4$5$2$3$4'.$replace.'$6$7$8',$sourceText);
  }

  protected function regexElementRemover($sourceText,$newElementPatern){
    $leftRegexElementRemover   = $this->getLeftRegexElementRemover()??'((?>\s|\S)*?'.$this->getLeftRegexGlobalRequiriment().'(?>\s|\S)*?)(\s+)(,?)(\s*)';
    $middleRegexElementRemover = $this->getMiddleRegexElementRemover()??'('.$newElementPatern.')';
    $rightRegexElementRemover  = $this->getRightRegexElementRemover()??'(\s*)(,?)((?>\s|\S)*'.$this->getRightRegexGlobalRequiriment().'(?>\s|\S)*)';

    $pattern="/{$leftRegexElementRemover}{$middleRegexElementRemover}{$rightRegexElementRemover}/";

    return preg_replace_callback(
      $pattern,
      function($matches){
        if(($matches[7]??null))
          return ($matches[1]??'').($matches[2]??'').($matches[3]??'').($matches[4]??'').($matches[8]??'');
        return ($matches[1]??'').($matches[6]??'').($matches[7]??'').($matches[8]??'');
      },
      $sourceText
    );
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

  protected function globalFileRegexRemover($newElementPatern){
    $fileContent    = $this->getFile();
    if(!$this->getLastRegexMatch($fileContent,$newElementPatern)){
      cvConsoler(cvInfo('no changes required')."\n");
      return $this;
    }
    return $this->setFile($this->regexElementRemover($fileContent,$newElementPatern));
  }
//[End Regex]
}

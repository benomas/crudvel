<?php

namespace Crudvel\Libraries\CvScaffSupport\Front\Lang;

use \Crudvel\Interfaces\CvScaffInterface;
use Illuminate\Support\Str;

class CvAdderCrudvuelEnLangScaff extends \Crudvel\Libraries\CvScaffSupport\Front\CvBaseAdderScaff implements CvScaffInterface
{
  protected $relatedFilePath   = 'src/i18n/en/crudvuel.js';
  public function __construct(){
    parent::__construct();
  }
  //[Getters]
  //[End Getters]

  //[Setters]
  //[End Setters]

  //[Stablishers]
  //[End Stablishers]
  protected function fixFile(){
    return $this->setFile($this->fixCrudvuelLangsSection($this->fixImportSection($this->getFile())));
  }
  protected function selfRepresentation(){
    return 'crudvuel';
  }
  private function fixImportSection($fileContent=''){
    $basePatern       = '/import\s+<slot>\s+from\s+\'\.\/crudvuel\/<slot>\'/';
    $slugResource     = fixedSlug(Str::plural($this->getResource()));
    $camelResource    = Str::camel(Str::plural($this->getResource()));
    return $this->globalRegexAdder(
      $fileContent,
      $this->regexMaker($basePatern,'\S+','\S+'),
      $this->scapedRegexMaker($basePatern,$camelResource,$slugResource),
      'import '.$camelResource.' from '.'\'./crudvuel/'.$slugResource.'\''
    );
  }
  private function fixCrudvuelLangsSection($fileContent=''){
    $basePatern    = '/\'<slot>\'\s*:\s*resourceMixer\(<slot>\)/';
    $slugResource     = fixedSlug(Str::plural($this->getResource()));
    $camelResource    = Str::camel(Str::plural($this->getResource()));
    return $this->globalRegexAdder(
      $fileContent,
      $this->regexMaker($basePatern,'\S+','\S+'),
      $this->scapedRegexMaker($basePatern,$camelResource,$slugResource),
      '\''.$slugResource.'\' : resourceMixer('.$camelResource.')'
    );
  }

  protected function getLastRegexMatch($sourceText,$regex){
    preg_match_all($regex,$sourceText,$matches);
    $matches = $matches??null;
    if(!$matches){
      cvConsoler(cvRedTC('no matches for regex'.$regex)."\n");
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
    $pattern='/((?>\s|\S)*?)(\s+)(,?)(\s*)('.preg_quote($lastReference, '/').')(\s*)(,?)((?>\s|\S)*)/';
    return preg_replace($pattern,'$1$2$3$4$5$2$3$4'.$replace.'$6$7$8',$sourceText);
  }

  protected function globalRegexAdder($sourceText,$elementPatern,$newElementPatern,$replace){
    $lastReference = $this->getLastRegexMatch($sourceText,$elementPatern);
    if(!$lastReference)
      throw new \Exception("Error element patern selector fail [$elementPatern]");

    if($this->getLastRegexMatch($sourceText,$newElementPatern)){
      cvConsoler(cvBlueTC('no changes required')."\n");
      return $sourceText;
    }

    return $this->regexElementAdder(
      $sourceText,
      $lastReference,
      $replace
    );
  }
}

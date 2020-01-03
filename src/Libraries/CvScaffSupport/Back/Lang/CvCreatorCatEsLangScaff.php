<?php

namespace Crudvel\Libraries\CvScaffSupport\Back\Lang;

use \Crudvel\Interfaces\CvScaffInterface;
use Illuminate\Support\Str;
use Stichoza\GoogleTranslate\GoogleTranslate;

class CvCreatorCatEsLangScaff extends \Crudvel\Libraries\CvScaffSupport\Back\CvBaseCreatorScaff implements CvScaffInterface
{
  protected $relatedTargetPath   = 'resources/lang/es/crudvel/';
  protected $relatedTemplatePath = 'vendor/benomas/crudvel/src/templates/back/cv_scaff_cat_es_lang.txt';
  protected $trans;
  public function __construct(){
    parent::__construct();
    $this->trans = new GoogleTranslate('es');
  }
//[Getters]
//[End Getters]

//[Setters]
//[End Setters]

//[Stablishers]
  public function stablishResource($resource=null){
    return $this->setResource($resource?ltrim(fixedSlug($resource),'cat-'):$resource);
  }
//[End Stablishers]

  protected function fixCase($quantity='singular',$case='camel',$fixer=null){
    $extraParams = $this->getExtraParams();
    $template    = $this->getTemplate();
    foreach($extraParams as $param=>$value){
      try{
        $value = $this->trans->translate($value);
      }catch(\Exception $e){

      }
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

  protected function selfRepresentation(){
    return 'cat-'.fixedSlug(Str::plural($this->getResource()));
  }
}

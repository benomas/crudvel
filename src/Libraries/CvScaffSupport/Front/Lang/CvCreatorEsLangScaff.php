<?php

namespace Crudvel\Libraries\CvScaffSupport\Front\Lang;

use \Crudvel\Interfaces\CvScaffInterface;
use Illuminate\Support\Str;
use Stichoza\GoogleTranslate\GoogleTranslate;

class CvCreatorEsLangScaff extends \Crudvel\Libraries\CvScaffSupport\Front\CvBaseCreatorScaff implements CvScaffInterface
{
  protected $relatedTargetPath   = 'src/i18n/es/crudvuel/';
  protected $relatedTemplatePath = 'vendor/benomas/crudvel/src/templates/front/cv_scaff_es_lang.txt';
  public function __construct(){
    parent::__construct();
    $this->trans = new GoogleTranslate('es');
  }
//[Getters]
//[End Getters]

//[Setters]
//[End Setters]

//[Stablishers]
//[End Stablishers]
  public function caseFixer($case=null,$value=null){
    try{
      $value = $this->trans->translate($value);
    }catch(\Exception $e){

    }
    return parent::caseFixer($case,$value);
  }
  protected function selfRepresentation(){
    return cvSlugCase(Str::plural($this->getResource()));
  }
}

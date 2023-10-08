<?php

namespace Crudvel\Libraries\CvScaffSupport\Front\Lang;

use \Crudvel\Interfaces\CvScaffInterface;
use Illuminate\Support\Str;
use Stichoza\GoogleTranslate\GoogleTranslate;

class CvCreatorPtLangScaff extends \Crudvel\Libraries\CvScaffSupport\Front\CvBaseCreatorScaff implements CvScaffInterface
{
  protected $relatedTargetPath   = 'src/i18n/pt/crudvuel/';
  protected $relatedTemplatePath = 'vendor/benomas/crudvel/src/templates/front/cv_scaff_pt_lang.txt';
  public function __construct(){
    parent::__construct();
    $this->trans = new GoogleTranslate('pt');
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

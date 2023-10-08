<?php

namespace Crudvel\Libraries\CvScaffSupport\Back\Lang;

use \Crudvel\Interfaces\CvScaffInterface;
use Illuminate\Support\Str;
use Stichoza\GoogleTranslate\GoogleTranslate;

class CvCreatorPtLangScaff extends \Crudvel\Libraries\CvScaffSupport\Back\CvBaseCreatorScaff implements CvScaffInterface
{
  protected $relatedTargetPath   = 'resources/lang/pt/crudvel/';
  protected $relatedTemplatePath = 'vendor/benomas/crudvel/src/templates/back/cv_scaff_pt_lang.txt';
  protected $trans;
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

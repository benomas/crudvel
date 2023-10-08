<?php

namespace Crudvel\Libraries\CvScaffSupport\Front\Lang;

use \Crudvel\Interfaces\CvScaffInterface;
use Illuminate\Support\Str;
use Stichoza\GoogleTranslate\GoogleTranslate;

class CvCreatorCatPtLangScaff extends \Crudvel\Libraries\CvScaffSupport\Front\CvBaseCreatorScaff implements CvScaffInterface
{
  use \Crudvel\Traits\CvScaffCatTrait;
  protected $relatedTargetPath   = 'src/i18n/pt/crudvuel/';
  protected $relatedTemplatePath = 'vendor/benomas/crudvel/src/templates/front/cv_scaff_cat_pt_lang.txt';
  public function __construct(){
    parent::__construct();
    $this->trans = new GoogleTranslate('pt');
  }
//[Getters]
//[End Getters]

//[Setters]
//[End Setters]

//[Stablishers]
  public function caseFixer($case=null,$value=null){
    try{
      $value = $this->trans->translate($value);
    }catch(\Exception $e){

    }
    return parent::caseFixer($case,$value);
  }
  public function stablishResource($resource=null){
    return $this->setResource($this->unCat($resource));
  }
//[End Stablishers]
  protected function selfRepresentation(){
    return cvSlugCase(Str::plural($this->reCat($this->getResource())));
  }
}

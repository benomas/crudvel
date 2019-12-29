<?php

namespace Crudvel\Libraries\CvScaffSupport;

class CvScaffHelper
{
  use \Crudvel\Traits\CvPatronTrait;
  protected $finalScaffers;
  protected $extraFinalScaffers;
  public function __construct(){;
    $this->processFinalScaffers();
  }

  public function getFinalScaffers(){
    return $this->finalScaffers??null;
  }

  public function getExtraFinalScaffers(){
    return $this->extraFinalScaffers??null;
  }

  private function getContextFinalScaffers($context='back'){
    $contextFinalScaffers = [];
    foreach((array) $this->getFinalScaffers() as $finalScaffer){
      if(!class_exists($finalScaffer))
        continue;
      if($finalScaffer::cvIam()->getContext()===$context)
        $contextFinalScaffers[$finalScaffer::cvIam()->getTemplateFileName()]=$finalScaffer;
    }
    foreach((array) $this->getExtraFinalScaffers() as $extraFinalScaffer){
      if(!class_exists($extraFinalScaffer))
        continue;
      if($extraFinalScaffer::cvIam()->getContext()===$context)
        $contextFinalScaffers[$extraFinalScaffer::cvIam()->getTemplateFileName()]=$extraFinalScaffer;
    }
    return $contextFinalScaffers;
  }

  public function getBackFinalScaffers(){
    return $this->getContextFinalScaffers();
  }

  public function getFrontFinalScaffers(){
    return $this->getContextFinalScaffers('front');
  }

  public function getModeAliases(){
    return [
      '1'=>'create-template-receptor',
      '2'=>'force-create-template-receptor',
      '3'=>'update-template-receptor',
      '4'=>'force-update-template-receptor',
      '5'=>'delete-template-receptor',
      '6'=>'force-delete-template-receptor',
    ];
  }

  public function setFinalScaffers($finalScaffers=null){
    $this->finalScaffers = $finalScaffers??null;
    return $this;
  }

  public function setExtraFinalScaffers($extraFinalScaffers=null){
    $this->extraFinalScaffers = $extraFinalScaffers??null;
    return $this;
  }

  private function processFinalScaffers($finalScaffers=null){
    $scafferClassFiles = assetsMap(base_path('vendor/benomas/crudvel/src/Libraries/CvScaffSupport/Finals/'));
    $finalScaffers=[];
    foreach($scafferClassFiles as $scafferClassFile){
      $finalScaffers[] = getClassFromFile(base_path('vendor/benomas/crudvel/src/Libraries/CvScaffSupport/Finals/'.$scafferClassFile));
    }
    $this->setFinalScaffers($finalScaffers);
    return $this;
  }
}

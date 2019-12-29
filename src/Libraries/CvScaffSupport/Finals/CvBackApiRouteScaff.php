<?php

namespace Crudvel\Libraries\CvScaffSupport;

use \Crudvel\Interfaces\CvScaffInterface;
use Illuminate\Support\Str;

class CvBackApiRouteScaff extends \Crudvel\Libraries\CvScaffSupport\CvBaseScaff implements CvScaffInterface
{
  protected $context='back';
  public function __construct(){
    parent::__construct();
  }

  protected function getTemplatePath(){
    $fileName = 'api.php';
    $path     = 'routes/';
    return base_path("$path$fileName");
  }

  protected function getTemplateReceptorPath(){
    $fileName = 'api.php';
    $destPath = 'routes/';
    return base_path($destPath.'/'.$fileName);
  }

  //[CalculateParams Modes]
    protected function creatorCalculateParams($template=null){
      $extraParams['resource'] = $this->getResource();
      $this->setExtraParams($extraParams);
      return $this;
    }
    protected function updaterCalculateParams($template=null){
      $this->setExtraParams(['resource' => $this->getResource()]);
      return $this;
    }
    protected function deleterCalculateParams($template=null){
      $this->setExtraParams(['resource' => $this->getResource()]);
      return $this;
    }
  //[End CalculateParams Modes]

  //[FixTemplate Modes]
    protected function creatorFixTemplate($template=null){
      return $this->fixFinalTag()
      ->fixSingularCamelTag()
      ->fixPluraCamelTag()
      ->fixSingularSnakeTag()
      ->fixPluraSnakeTag()
      ->fixSingularSlugTag()
      ->fixPluraSlugTag()
      ->fixSingularStudlyTag()
      ->fixPluraStudlyTag()
      ->fixSingularTitleTag()
      ->fixPluraTitleTag()
      ->fixSingularLowerTag()
      ->fixPluraLowerTag()
      ->fixSingularUpperTag()
      ->fixPluraUpperTag()
      ->getTemplateCache();
    }
    protected function updaterFixTemplate($template=null){
      return null;
    }
    protected function deleterFixTemplate($template=null){
      return null;
    }
  //[End FixTemplate Modes]

  //[InyectFixedTemplate Modes]
    protected function creatorInyectFixedTemplate($template=null){
      if($this->templateReceptorExists()){
        if(!$this->isForced() && !$this->confirm('file already defined rewrite it?'))
          throw new \Exception('Error '.$this->getTemplateReceptorPath().' cant be created');
        try{
          unlink($this->getTemplateReceptorPath());
          cvConsoler(cvGreenTC('Old file was deleted')."\n");
        }catch(\Exception $e){
          throw new \Exception('Error '.$this->getTemplateReceptorPath().' cant be deleted');
        }
      }
      try{
        file_put_contents($this->getTemplateReceptorPath(), $template);
        cvConsoler(cvGreenTC('New file was created')."\n");
      }catch(\Exception $e){
        throw new \Exception('Error '.$this->getTemplateReceptorPath().' cant be created');
      }
      return $this;
    }
    protected function updaterInyectFixedTemplate($template=null){
      return $this;
    }
    protected function deleterInyectFixedTemplate($template=null){
      if(!$this->templateReceptorExists()){
        cvConsoler(cvBrownTC($this->getTemplateReceptorPath().' file doest exist')."\n");
        return $this;
      }
      try{
        unlink($this->getTemplateReceptorPath());
      }catch(\Exception $e){
        throw new \Exception('Error '.$this->getTemplateReceptorPath().' cant be deleted');
      }
      return $this;
    }
  //[End InyectFixedTemplate Modes]
}

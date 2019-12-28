<?php

namespace Crudvel\Libraries\CvScaffSupport;

use \Crudvel\Interfaces\CvScaffInterface;
use Illuminate\Support\Str;

class CvModelScaff extends \Crudvel\Libraries\CvScaffSupport\CvBaseScaff implements CvScaffInterface
{
  public function __construct(){
    parent::__construct();
  }

  protected function getTemplatePath(){
    return base_path("vendor/benomas/crudvel/src/templates/model.txt");
  }

  protected function getTemplateReceptorPath(){
    $modelName = Str::studly(Str::singular($this->getResource())).'.php';
    return app_path('Models/'.$modelName);
  }

  public function loadTemplate(){
    switch($this->getMode()){
      case 'create-template-receptor':
      case 'force-create-template-receptor':
        if(!$this->templateExist())
          throw new \Exception('Template doesnt exist');
        return file_get_contents($this->getTemplatePath());
      case 'update-template-receptor':
        break;
      case 'force-update-template-receptor':
        break;
      case 'delete-template-receptor':
        break;
      case 'force-delete-template-receptor':
        break;
      default:
        throw new \Exception('Invalid mode');
    }
  }

  public function fixTemplate($template=null){
    $this->setTemplateCache($template);
    switch($this->getMode()){
      case 'create-template-receptor':
      case 'force-create-template-receptor':
        return $this->fixSingularCamelTag()
        ->fixPluraCamelTag()
        ->fixSingularSnakeTag()
        ->fixPluraSnakeTag()
        ->fixSingularSlugTag()
        ->fixPluraSlugTag()
        ->fixSingularStudlyTag()
        ->fixPluraStudlyTag()->getTemplateCache();
      case 'update-template-receptor':
        break;
      case 'force-update-template-receptor':
        break;
      case 'delete-template-receptor':
        break;
      case 'force-delete-template-receptor':
        break;
      default:
        throw new \Exception('Invalid mode');
    }
  }

  public function inyectFixedTemplate($template=null){
    switch($this->getMode()){
      case 'create-template-receptor':
        if($this->templateReceptorExists())
          throw new \Exception('Warging Template already defined');
        try{
          file_put_contents($this->getTemplateReceptorPath(), $template);
        }catch(\Exception $e){
          throw new \Exception('Error '.$this->getTemplateReceptorPath().' cant be created');
        }
        return $this;
      case 'force-create-template-receptor':
        if($this->templateReceptorExists()){
          try{
            unlink($this->getTemplateReceptorPath());
          }catch(\Exception $e){
            throw new \Exception('Error '.$this->getTemplateReceptorPath().' cant be deleted');
          }
        }
        try{
          file_put_contents($this->getTemplateReceptorPath(), $template);
        }catch(\Exception $e){
          throw new \Exception('Error '.$this->getTemplateReceptorPath().' cant be created');
        }
        return $this;
      case 'update-template-receptor':
        return $this;
      case 'force-update-template-receptor':
        return $this;
      case 'delete-template-receptor':
        return $this;
      case 'force-delete-template-receptor':
        return $this;
      default:
        throw new \Exception('Invalid mode');
    }
    return $this;
  }

}

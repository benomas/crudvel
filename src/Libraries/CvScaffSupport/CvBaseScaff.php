<?php

namespace Crudvel\Libraries\CvScaffSupport;
use Illuminate\Support\Str;
abstract class CvBaseScaff
{
  protected $modes = [
    'create-template-receptor',
    'force-create-template-receptor',
    'update-template-receptor',
    'force-update-template-receptor',
    'delete-template-receptor',
    'force-delete-template-receptor',
  ];
  protected $mode = 'create-template-receptor';
  private $templateCache;

  abstract protected function getTemplatePath();
  abstract protected function getTemplateReceptorPath();

  public function __construct(){
  }

  public function getResource(){
    return $this->resource??'';
  }

  public function getMode(){
    return $this->mode??null;
  }

  public function getModes(){
    return $this->modes??[];
  }

  protected function getTemplateCache(){
    return $this->templateCache??[];
  }

  public function setResource($resource=null){
    $this->resource = $resource??null;
    return $this;
  }

  public function setMode($mode=null){
    $this->mode = $mode??null;
    return $this;
  }

  protected function setTemplateCache($templateCache=null){
    $this->templateCache = $templateCache??null;
    return $this;
  }

  public function stablishResource($resource=null){
    return $this->setResource($resource);
  }
  public function stablishMode($mode=null){
    $mode=$mode??$this->getMode()??'create-template-receptor';
    if(!in_array($mode,$this->getModes()))
      throw new \Exception('Invalid mode');
    return $this->setMode($mode);
  }

  protected function templateExist(){
    return file_exists($this->getTemplatePath());
  }

  protected function templateReceptorExists(){
    return file_exists($this->getTemplateReceptorPath());
  }

  private function fixCase($quantity='singular',$case='camel',$fixer=null){
    $template = preg_replace_callback(
      '/(<cv_'.$quantity.'_'.$case.'_)(.+)(_cv>)/',
      function($matches) use($quantity,$case,$fixer){
        $replacement = $matches[2]??null;
        if(!$replacement)
          return $matches[0]??'';
        $tagResolver = Str::camel('get'.$replacement);
        if(method_exists($this,$tagResolver)){
          if($fixer)
            return $fixer(Str::$quantity($this->$tagResolver()));
          return Str::$case(Str::$quantity($this->$tagResolver()));
        }

        return $matches[0]??'';
      },
      $this->getTemplateCache()
    );
    $this->setTemplateCache($template);
    return $this;
  }

  protected function fixSingularCamelTag(){
    return $this->fixCase('singular','camel');
  }

  protected function fixPluraCamelTag(){
    return $this->fixCase('plural','camel');
  }

  protected function fixSingularSnakeTag(){
    return $this->fixCase('singular','snake',function($val){return fixedSnake($val);});
  }

  protected function fixPluraSnakeTag(){
    return $this->fixCase('plural','snake',function($val){return fixedSnake($val);});
  }

  protected function fixSingularSlugTag(){
    return $this->fixCase('singular','slug',function($val){return fixedSlug($val);});
  }

  protected function fixPluraSlugTag(){
    return $this->fixCase('plural','slug',function($val){return fixedSlug($val);});
  }

  protected function fixSingularStudlyTag(){
    return $this->fixCase('singular','studly');
  }

  protected function fixPluraStudlyTag(){
    return $this->fixCase('plural','studly');
  }
}

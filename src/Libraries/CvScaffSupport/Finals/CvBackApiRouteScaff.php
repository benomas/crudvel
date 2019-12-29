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
    return base_path("$destPath$fileName");
  }

  public function getTemplateFileName(){
    return 'cv-back-api';
  }

//[CalculateParams Modes]
    protected function creatorCalculateParams($template=null){
      $this->setExtraParams(['resource' => $this->getResource()]);
    }
    protected function updaterCalculateParams($template=null){
      $this->setExtraParams(['resource' => $this->getResource()]);
      return $this;
    }
    protected function deleterCalculateParams($template=null){
      $this->setExtraParams(['resource' => $this->getResource()]);
    }
//[End CalculateParams Modes]

//[FixTemplate Modes]
    protected function creatorFixTemplate(){
      $extraParams = $this->getExtraParams();
      $template    = $this->getTemplateCache();
      $pattern = '/((?>\s|\S)*apiCrudvelResources\(\[)((?>\s|\S)*\[(.+)\](\s*))(\]\);(?>\s|\S)*)/';
      preg_match($pattern,$template,$matches);
      $apiCrudvelResources = $matches[2] ?? null;
      if(!$apiCrudvelResources)
        throw new \Exception('Error, api crudvel resources section is not defined');
      $slugResource = fixedSlug(Str::plural($this->getResource()));
      preg_match('/\[\W*'.$slugResource.'\W*\]/',$apiCrudvelResources,$matches2);
      if(count($matches2)){
        cvConsoler(cvBlueTC('no changes required')."\n");
        return $this->getTemplateCache();
      }
      return str_replace($apiCrudvelResources,"$apiCrudvelResources  ,[\"$slugResource\"]{$matches[4]}",$template);
    }
    protected function updaterFixTemplate(){
      return null;
    }
    protected function deleterFixTemplate(){
      $extraParams = $this->getExtraParams();
      $template    = $this->getTemplateCache();
      $pattern = '/((?>\s|\S)*apiCrudvelResources\(\[)((?>\s|\S)*\[(.+)\](\s*))(\]\);(?>\s|\S)*)/';
      preg_match($pattern,$template,$matches);
      $apiCrudvelResources = $matches[2] ?? null;
      if(!$apiCrudvelResources)
        throw new \Exception('Error, api crudvel resources section is not defined');
      $slugResource = fixedSlug(Str::plural($this->getResource()));
      preg_match('/(\])((?>\s|,)*)(\[\W*'.$slugResource.'\W*\])((?>\s|,)*)/',$apiCrudvelResources,$matches2);
      $toRemove = $matches2[2]??'';
      $toRemove .= $matches2[3]??'';

      if($toRemove===''){
        cvConsoler(cvBlueTC('no changes required')."\n");
        return $this->getTemplateCache();
      }
      $toRemove = str_replace($toRemove,"",$apiCrudvelResources);
      return str_replace($apiCrudvelResources,$toRemove,$template);
    }
//[End FixTemplate Modes]

//[InyectFixedTemplate Modes]
  protected function creatorInyectFixedTemplate($template=null){
    try{
      file_put_contents($this->getTemplateReceptorPath(), $template);
      cvConsoler(cvGreenTC('file was updated')."\n");
    }catch(\Exception $e){
      throw new \Exception('Error '.$this->getTemplateReceptorPath().' cant be created');
    }
    return $this;
  }
  protected function updaterInyectFixedTemplate($template=null){
    try{
      file_put_contents($this->getTemplateReceptorPath(), $template);
      cvConsoler(cvGreenTC('file was updated')."\n");
    }catch(\Exception $e){
      throw new \Exception('Error '.$this->getTemplateReceptorPath().' cant be created');
    }
    return $this;
  }
  protected function deleterInyectFixedTemplate($template=null){
    try{
      file_put_contents($this->getTemplateReceptorPath(), $template);
      cvConsoler(cvGreenTC('file was updated')."\n");
    }catch(\Exception $e){
      throw new \Exception('Error '.$this->getTemplateReceptorPath().' cant be created');
    }
    return $this;
  }
//[End InyectFixedTemplate Modes]
}

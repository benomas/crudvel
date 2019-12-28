<?php

namespace Crudvel\Libraries\CvScaffSupport;

use \Crudvel\Interfaces\CvScaffInterface;
use Illuminate\Support\Str;
use Stichoza\GoogleTranslate\GoogleTranslate;

class CvEsLangScaff  extends \Crudvel\Libraries\CvScaffSupport\CvBaseScaff implements CvScaffInterface
{
  protected $trans;
  public function __construct(){
    parent::__construct();
    $this->trans = new GoogleTranslate('es');
  }

  protected function fixCase($quantity='singular',$case='camel',$fixer=null){
    $template = preg_replace_callback(
      '/(<cv_'.$quantity.'_'.$case.'_)(.+)(_cv>)/',
      function($matches) use($quantity,$case,$fixer){
        $replacement = $matches[2]??null;
        if(!$replacement)
          return $matches[0]??'';
        $tagResolver = Str::camel('get'.$replacement);
        if(method_exists($this,$tagResolver)){
          if($fixer)
            return $fixer(Str::$quantity($this->trans->translate($this->$tagResolver())));
          return Str::$case(Str::$quantity($this->trans->translate($this->$tagResolver())));
        }

        return $matches[0]??'';
      },
      $this->getTemplateCache()
    );
    $this->setTemplateCache($template);
    return $this;
  }

  protected function getTemplatePath(){
    return base_path("vendor/benomas/crudvel/src/templates/es_lang.txt");
  }

  protected function getTemplateReceptorPath(){
    $langName = fixedSlug(Str::plural($this->getResource())).'.php';
    return resource_path('lang/es/crudvel/'.$langName);
  }

}

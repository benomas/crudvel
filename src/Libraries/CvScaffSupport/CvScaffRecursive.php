<?php

namespace Crudvel\Libraries\CvScaffSupport;

use \Crudvel\Interfaces\CvScaffInterface;
use Illuminate\Support\Str;

class CvScaffRecursive extends \Crudvel\Libraries\CvScaffSupport\CvBaseScaff implements CvScaffInterface
{
  public function __construct(){
    parent::__construct();
  }

  protected function getTemplatePath(){
    return base_path("vendor/benomas/crudvel/src/templates/cv_scaff.txt");
  }

  protected function getTemplateReceptorPath(){
    $scaffName = 'Cv'.Str::studly(Str::singular($this->getExtraParams()['prefix']??'')).Str::studly(Str::singular($this->getResource())).'Scaff.php';
    return base_path('vendor/benomas/crudvel/src/Libraries/CvScaffSupport/'.$scaffName);
  }

  public function getPrefix(){

  }
}

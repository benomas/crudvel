<?php

namespace Crudvel\Libraries\CvScaffSupport\Finals;

use \Crudvel\Interfaces\CvScaffInterface;
use Illuminate\Support\Str;

class CvBackCatRequestScaff extends \Crudvel\Libraries\CvScaffSupport\CvBaseScaff implements CvScaffInterface
{
  public function __construct(){
    parent::__construct();
  }

  protected function getTemplatePath(){
    return base_path("vendor/benomas/crudvel/src/templates/cv_back_cat_request.txt");
  }

  protected function getTemplateReceptorPath(){
    $requestName = Str::studly(Str::singular($this->getResource())).'Request.php';
    return app_path('Http/Requests/'.$requestName);
  }
  //[LoadTemplate Modes]
  //[End LoadTemplate Modes]

  //[CalculateParams Modes]
  //[End CalculateParams Modes]

  //[FixTemplate Modes]
  //[End FixTemplate Modes]

  //[InyectFixedTemplate Modes]
  //[End InyectFixedTemplate Modes]
}

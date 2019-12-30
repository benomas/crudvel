<?php

namespace Crudvel\Libraries\CvScaffSupport\Back\ApiController;

use \Crudvel\Interfaces\CvScaffInterface;
use Illuminate\Support\Str;

class CvCreatorApiControllerScaff extends \Crudvel\Libraries\CvScaffSupport\Back\CvBaseCreatorScaff implements CvScaffInterface
{
  public function __construct(){
    parent::__construct();
  }

  protected function getTemplatePath(){
    return base_path("vendor/benomas/crudvel/src/templates/cv_back_api_controller.txt");
  }

  protected function getTemplateReceptorPath(){
    $controllerName = Str::studly(Str::singular($this->getResource())).'Controller.php';
    return app_path('Http/Controllers/Api/'.$controllerName);
  }

  //[Getters]
  //[End Getters]

  //[Setters]
  //[End Setters]

  //[Stablishers]
  //[End Stablishers]
}

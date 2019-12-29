<?php

namespace Crudvel\Libraries\CvScaffSupport;

use \Crudvel\Interfaces\CvScaffInterface;
use Illuminate\Support\Str;

class CvBackCatTestSeederScaff extends \Crudvel\Libraries\CvScaffSupport\CvBaseScaff implements CvScaffInterface
{
  protected $context='back';
  public function __construct(){
    parent::__construct();
  }

  protected function getTemplatePath(){
    $fileName = 'cv_back_seeder.txt';
    $path='vendor/benomas/crudvel/src/templates/';
    return base_path("$path$fileName");
  }

  protected function getTemplateReceptorPath(){
    $fileName = Str::studly(Str::singular($this->getResource())).'TableSeeder.php';
    $destPath = 'database/seeds/';
    return base_path("$destPath$fileName");
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

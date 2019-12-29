<?php

namespace Crudvel\Libraries\CvScaffSupport;

use \Crudvel\Interfaces\CvScaffInterface;
use Illuminate\Support\Str;

class CvMigrationScaff extends \Crudvel\Libraries\CvScaffSupport\CvBaseScaff implements CvScaffInterface
{
  protected $context='<cv_lower_define_context_back_or_front_cv>';
  public function __construct(){
    parent::__construct();
  }

  protected function getTemplatePath(){
    $fileName = '';
    if($fileName==='')
      $fileName='migration.txt';
    $path = '';
    if($path==='')
      $path='vendor/benomas/crudvel/src/templates/';
    return base_path("$path$fileName");
  }

  protected function getTemplateReceptorPath(){
    $fileName = '';
    if($fileName==='')
      $fileName=Str::studly(Str::singular($this->getResource())).'Migration.php';
    $destPath = 'database/migrations';
    if($destPath==='')
      $destPath = rtrim('app/Http/Migration/','/');
    return base_path($destPath.'/'.$fileName);
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

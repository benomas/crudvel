<?php

namespace Crudvel\Libraries\CvScaffSupport;

use \Crudvel\Interfaces\CvScaffInterface;
use Illuminate\Support\Str;
use Carbon\Carbon;

class CvMigrationScaff extends \Crudvel\Libraries\CvScaffSupport\CvBaseScaff implements CvScaffInterface
{
  protected $context='back';
  public function __construct(){
    parent::__construct();
  }

  protected function getTemplatePath(){
    $fileName = 'migration.txt';
    if($fileName==='')
      $fileName='migration.txt';
    $path = '';
    if($path==='')
      $path='vendor/benomas/crudvel/src/templates/';
    return base_path("$path$fileName");
  }

  protected function getTemplateReceptorPath(){
    $fileName = Carbon::now()->format('Y_m_d_u').'_create_'.fixedSlug(Str::plural($this->getResource())).'_table.php';
    $destPath = 'database/migrations/';
    if(in_array($this->getMode(),[
      'delete-template-receptor',
      'force-delete-template-receptor',
      ]))
    {
      $migrations = assetsMap(base_path($destPath));
      foreach((array)$migrations as $migration){
        $pos=strpos($migration,'_create_'.fixedSlug(Str::plural($this->getResource())).'_table.php');
        if($pos !== null && $pos !== false){
          $fileName = $migration;
          break;
        }
      }
    }
    return base_path($destPath.$fileName);
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
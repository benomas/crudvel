<?php

namespace Crudvel\Libraries;
use \Illuminate\Support\Facades\Storage;
use \Maatwebsite\Excel\Facades\Excel;

/**
 * @author Energy1011 energy1011@gmail.com
 * @date   2020-03-03
 */
class PermissionSpreadSheet
{
  private $filename = '';
  private $format = '.xlsx';
  private $permissionsFolderName = 'permissions';
  private $disk = 'private';

  public function __construct($filename)
  {
    $this->filename = $filename.$this->format;
  }

  /** Get resource list from api controllers folder */
  function getResourceListFromApiControllers()
  {
    $controllers  = scandir(app_path('Http/Controllers/Api'));
    $temp = [];
    foreach ($controllers as $ctrl) {
      $ctrl = Str::kebab(preg_replace("/Controller.php$/", "", $ctrl), '-');
      if (strlen($ctrl) > 2)
        array_push($temp, $ctrl);
    }
    return $temp;
  }

  /** Get resource list from lang */
  function getResourceListFromLang($lang = 'en')
  {
    $controllers  = scandir(app_path('../resources/lang/' . $lang . '/crudvel'));
    $temp = [];
    foreach ($controllers as $ctrl) {
      $ctrl = preg_replace("/.php$/", "", $ctrl);
      if (strlen($ctrl) > 2)
        array_push($temp, $ctrl);
    }
    return $temp;
  }

  function exportPermissionSpreadSheet()
  {
    // get actions from translation
    $defaultActions = trans('crudvel.actions');
    $temp = [];
    $temp[] = 'Recursos/Acciones';
    // Set headers
    foreach ($defaultActions as $action => $value)
      array_push($temp, $action);
    $a[0] = $temp;

    // get resources from
    $resources = $this->getResourceListFromLang();
    foreach ($resources as $resource) {
      $a[] = [$resource];
    }
    $ExporterInterceptor = new \App\Exports\ExporterInterceptor(collect($a));
    return Excel::download($ExporterInterceptor, $this->filename);
  }

  function getStorageFilePath(){
    $ds = DIRECTORY_SEPARATOR;
    return  $this->permissionsFolderName.$ds.$this->filename;
  }

  function getFilePath(){
    $ds = DIRECTORY_SEPARATOR;
    return storage_path('app'.$ds.$this->disk).$ds.$this->getStorageFilePath();
  }

  function importPermissionSpreadSheet()
  {
    // $this->getFilePath();
    ## Import permissions spreadsheet
    if(! Storage::disk($this->disk)->exists($this->getStorageFilePath())){
      pdd("Archivo no encontrado");
      return ;
    }

   Excel::import(($ExcelCollection = new \App\Imports\ImporterInterceptor()), '/'.$this->getFilePath());
   pdd($ExcelCollection->getRows()->toArray());

    pdd("archivo encontrado");
  }
}

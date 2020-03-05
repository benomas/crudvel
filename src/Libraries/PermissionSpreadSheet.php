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
  use \Crudvel\Libraries\Helpers\SysInstancesTrait;
  private $filename = '';
  private $format = '.xlsx';
  private $permissionsFolderName = 'permissions';
  private $disk = 'private';
  private $options =['actions'=>PermissionSpreadSheetResourceAction::class, 'sections'=>PermissionSpreadSheetResourceSection::class];

  public function __construct($filename, $mode='actions')
  {
    if (! array_key_exists ($mode, $this->options)){
      pdd("El modo de operacion no existe, es necesario darlo de alta en $this->options");
    }

    $this->filename = $filename.$this->format;
    $this->currentConstructor = new $this->options[$mode];
  }


  public function exportPermissionSpreadSheet()
  {
    $ExporterInterceptor = new \App\Exports\ExporterInterceptor(collect($this->currentConstructor->constructSpreadSheet()));
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

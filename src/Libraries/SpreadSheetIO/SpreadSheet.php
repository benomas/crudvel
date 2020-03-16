<?php

namespace Crudvel\Libraries\SpreadSheetIO;
use \Illuminate\Support\Facades\File;
use \Maatwebsite\Excel\Facades\Excel;

class SpreadSheet
{
  use \Crudvel\Traits\SysInstancesTrait;
  protected $constructorInstance = null;

  public function __construct($CvSpreadSheetConstructor)
  {
    $this->constructorInstance = $CvSpreadSheetConstructor;
  }

  function importSpreadSheet()
  {
    if(!File::exists($this->constructorInstance->getFullFilePath())){
      pdd("File not found:", $this->constructorInstance->getFullFilePath() );
    }else{
      Excel::import(($ExcelCollection = new \App\Imports\ImporterInterceptor()), $this->constructorInstance->getfullFilePath());
      return $ExcelCollection->getRows()->toArray();
    }
  }

  public function exportSpreadSheet()
  {
    $ExporterInterceptor = new \App\Exports\ExporterInterceptor(collect($this->constructorInstance->build()));
    return Excel::download($ExporterInterceptor, $this->constructorInstance->getFileNameAttr());
  }

  public function storeSpreadSheet()
  {
    $ExporterInterceptor = new \App\Exports\ExporterInterceptor(collect($this->constructorInstance->build()));
    if(file_exists($this->constructorInstance->getFullFilePath()))
      \Excel::store($ExporterInterceptor, $this->constructorInstance->getRelatedPath().'layout_original'.DIRECTORY_SEPARATOR.$this->constructorInstance->getFileNameAttr(), 'seed');
    else
      \Excel::store($ExporterInterceptor, $this->constructorInstance->getRelatedPath().DIRECTORY_SEPARATOR.$this->constructorInstance->getFileNameAttr(), 'seed');
  }
}


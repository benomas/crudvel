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
      return null;
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
}

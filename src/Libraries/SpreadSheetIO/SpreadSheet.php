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

  function importSpreadSheet($ignoreIfNotExists = false)
  {
    if(!File::exists($this->constructorInstance->getFullFilePath())){
      if($ignoreIfNotExists) return [];
      pdd("File not found:", $this->constructorInstance->getFullFilePath() );
    }else{
      Excel::import(($ExcelCollection = new \App\Imports\ImporterInterceptor()), $this->constructorInstance->getfullFilePath());
      return $ExcelCollection->getRows();
    }
  }

  public function exportSpreadSheet()
  {
    $ExporterInterceptor = new \App\Exports\ExporterInterceptor(collect($this->constructorInstance->build()));
    return Excel::download($ExporterInterceptor, $this->constructorInstance->getFileNameAttr());
  }

  public function storeSpreadSheet($fromData=false)
  {
    if($fromData){
      $data = $this->constructorInstance->getData();
    }else{
      $data = $this->constructorInstance->build();
    }
    // Crudvel\Exports\CvResourcePermissionInterceptor
    // use specific export interceptor with background color in cells
    $ExporterInterceptor = new \Crudvel\Exports\CvResourcePermissionInterceptor(collect($data), $this->constructorInstance->bgRanges??[]);
    Excel::store($ExporterInterceptor, $this->constructorInstance->getRelatedPath().DIRECTORY_SEPARATOR.$this->constructorInstance->getFileNameAttr(), 'seed');
  }

  public function synchronize(){
    // import existing data xlsx file
    $importData = $this->importSpreadSheet(true);
    if(empty($importData)) {
      return $this->storeSpreadSheet();
    }
    // set keyby 0, to this I get key by 'name resource', then, map to convert keys of each resource for actions names
    $importData = $importData->keyby(0)->map(function($row) use ($importData){
      $newRow=[];
      // add resource-names keys
      foreach ($row as $k=> $v) {
        $newRow[$importData->first()[$k]] = $v;
      }
        return $newRow;
    });
    $this->constructorInstance->data =$importData;
    $this->constructorInstance->synchronize();
    $this->storeSpreadSheet(true);
  }
}


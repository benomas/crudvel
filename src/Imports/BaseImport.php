<?php namespace Crudvel\Imports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Crudvel\Imports\Exceptions\NoSheetImportException;

class BaseImport implements WithMultipleSheets{
  use \Crudvel\Traits\CrudTrait;
  use \Crudvel\Traits\CacheTrait;
  use \Crudvel\Traits\CvPatronTrait;
  use \Crudvel\Libraries\Helpers\CasesTrait;

  public function __construct(){
    $this->injectCvResource();
  }

  public function sheets(): array{
    $fixedFirstSheetClass = $this->fixedFirstSheetClass();

    return [
      new $fixedFirstSheetClass()
    ];
  }

  public function fixedFirstSheetClass () {
    $sheetClass = "App\Http\Imports\FirstSheet{$this->getStudlySingularName()}Import";

    if(!class_exists($sheetClass))
      throw new NoSheetImportException($sheetClass);

    return $sheetClass;
  }
}

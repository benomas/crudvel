<?php namespace Crudvel\Imports;

use App\Models\{Store,CatStoreCategory};
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\{WithValidation,WithStartRow};
use Maatwebsite\Excel\Imports\HeadingRowFormatter;
use Maatwebsite\Excel\HeadingRowImport;

class BaseSheetImport implements WithValidation,WithStartRow{
  protected $excelFieldsPositions;
  protected $excelFieldsLangs;
  protected $headings       = [];
  protected $excelFields    = [ ];
  protected $rules          = [];
  protected $attributeNames = [];

  use \Crudvel\Traits\CrudTrait;
  use \Crudvel\Traits\CacheTrait;
  use \Crudvel\Traits\CvPatronTrait;
  use \Crudvel\Libraries\Helpers\CasesTrait;

  public function __construct(){
    $this->injectCvResource();
    $this->setHeadings((new HeadingRowImport)->toArray($this->getRequestInstance()->toImport())[0][0]??[])
      ->setExcelFieldsPositions(array_flip($this->getExcelFields()))
      ->fixRules()
      ->fixAttributeNames();

    HeadingRowFormatter::default('none');
  }

  protected function fixRules () {
    $rules = [];

    $fixedRules = [];
    foreach($rules as $key=>$value){
      $ruleProp = $this->getExcelFieldsPositions()[$key] ?? null;

      if($ruleProp !== null)
        $fixedRules[$ruleProp] = $value;
    }

    return $this->setRules($fixedRules);
  }

  protected function fixAttributeNames() {
    $fixedAttributes = [];
    foreach($this->getRules() as $key=>$value){
      $attributeName = $this->getHeadings()[$key] ?? null;

      if($attributeName !== null)
        $fixedAttributes[$key] = $attributeName;
    }

    return $this->setAttributeNames($fixedAttributes);
  }

  public function rules(): array {;
    return $this->getRules();
  }

  public function startRow(): int{
    return 2;
  }

  public function customValidationAttributes(){
    return $this->getAttributeNames();
  }

  public function getFixedFieldValue($row = [],$field = null){
    $fixedPosition = $this->getExcelFieldsPositions()[$field];

    return $row[$fixedPosition] ?? null;
  }
// [Specific Logic]
// [End Specific Logic]

// [Getters]
  public function getExcelFields(){
    return $this->excelFields??null;
  }

  public function getHeadings(){
    return $this->headings??null;
  }

  public function getExcelFieldsLangs(){
    return $this->excelFieldsLangs??null;
  }

  public function getExcelFieldsPositions(){
    return $this->excelFieldsPositions??null;
  }
  public function getRules(){
    return $this->rules??null;
  }

  public function getAttributeNames(){
    return $this->attributeNames??null;
  }
// [End Getters]

// [Setters]
  public function setExcelFields($excelFields=null){
    $this->excelFields = $excelFields??null;

    return $this;
  }

  public function setHeadings($headings=null){
    $this->headings = $headings??null;

    return $this;
  }

  public function setExcelFieldsPositions($excelFieldsPositions=null){
    $this->excelFieldsPositions = $excelFieldsPositions??null;
    return $this;
  }

  public function setExcelFieldsLangs($excelFieldsLangs=null){
    $this->excelFieldsLangs = $excelFieldsLangs??null;
    return $this;
  }

  public function setRules($rules=null){
    $this->rules = $rules??null;

    return $this;
  }

  public function setAttributeNames($attributeNames=null){
    $this->attributeNames = $attributeNames??null;

    return $this;
  }
// [End Setters]
}

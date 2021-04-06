<?php namespace Crudvel\Imports;

use App\Models\{Store,CatStoreCategory};
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\{ToCollection,WithValidation,WithStartRow};
use Maatwebsite\Excel\Imports\HeadingRowFormatter;
use Maatwebsite\Excel\HeadingRowImport;

class FirstSheetStoreImport implements ToCollection,WithValidation,WithStartRow{
  protected $headings = [];
  protected $excelFieldsPositions;
  protected $excelFieldsLangs;
  protected $excelFields = [
    'title',
    'description',
    'street',
    'city',
    'state',
    'postal_code',
    'country',
    'lat',
    'lng',
    'phone',
    'fax',
    'email',
    'website',
    'is_disabled',
    'logo_name',
    'categories',
    'marker_id',
    'logo_image',
    'description_2',
    'open_hours',
    'update_id',
    'order',
  ];
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
    $rules = [
      "title"       => "required|max:100",
      "street"      => "required|max:254",
      "city"        => "required|max:254",
      "state"       => "required|max:254",
      "postal_code" => "required|max:99999",
      "country"     => "required|max:254",
      "lat"         => "required|numeric",
      "lng"         => "required|numeric",
      'logo_name'   => 'required|max:254',
      'categories'  => 'required|max:254',
    ];

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

  public function collection(Collection $rows){
    CatStoreCategory::noFilters()->delete();
    $this->getModelBuilderInstance()->delete();

    foreach($rows as $row){
      $catStoreCategoryRow = [
        'name'      => $this->getFixedFieldValue($row,'categories'),
        'logo_name' => $this->getFixedFieldValue($row,'logo_name'),
      ];
      $catStoreCategory = CatStoreCategory::name($catStoreCategoryRow['name'])->firstOrNew();
      $catStoreCategory->fill($catStoreCategoryRow)->save();

      $storeRow = [
        'title'                 => $this->getFixedFieldValue($row,'title'),
        'description'           => $this->getFixedFieldValue($row,'description'),
        'street'                => $this->getFixedFieldValue($row,'street'),
        'city'                  => $this->getFixedFieldValue($row,'city'),
        'state'                 => $this->getFixedFieldValue($row,'state'),
        'postal_code'           => $this->getFixedFieldValue($row,'postal_code'),
        'country'               => $this->getFixedFieldValue($row,'country'),
        'lat'                   => $this->getFixedFieldValue($row,'lat'),
        'lng'                   => $this->getFixedFieldValue($row,'lng'),
        'phone'                 => $this->getFixedFieldValue($row,'phone'),
        'fax'                   => $this->getFixedFieldValue($row,'fax'),
        'email'                 => $this->getFixedFieldValue($row,'email'),
        'website'               => $this->getFixedFieldValue($row,'website'),
        'is_disabled'           => $this->getFixedFieldValue($row,'is_disabled'),
        'cat_store_category_id' => $catStoreCategory->id,
        'marker_id'             => $this->getFixedFieldValue($row,'marker_id'),
        'logo_image'            => $this->getFixedFieldValue($row,'logo_image'),
        'description_2'         => $this->getFixedFieldValue($row,'description_2'),
        'open_hours'            => $this->getFixedFieldValue($row,'open_hours'),
        'update_id'             => $this->getFixedFieldValue($row,'update_id'),
        'order'                 => $this->getFixedFieldValue($row,'order'),
      ];

      $storeModel = $this->modelInstanciator(true);

      $storeModel->fill($storeRow)->save();
    }
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

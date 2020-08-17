<?php namespace Crudvel\Validations\Rules;


class UniqueCombination extends \Crudvel\Validations\Rules\BaseRule implements \Crudvel\Validations\CvRuleInterface
{
  protected $modelClass;
  protected $fields;
  protected $mainField;
  protected $extraFields;
  protected $exceptionKeyValue;
// [Specific Logic]
  /**
   * Create a new rule instance.
    *
    * @return void
    */
  public function __construct(){
    //
  }

  /**
   * Determine if the validation rule passes.
    *
    * @param  string  $attribute
    * @param  mixed  $value
    * @return bool
    */
  public function passes(){
    $modelBuilderInstance = $this->getModelClass()::noFilters();

    if($this->getExceptionKeyValue() !== null)
      $modelBuilderInstance->noKey($this->getExceptionKeyValue());

    return $modelBuilderInstance->columnCombination($this->getFields())->count() === 0;
  }

  /**
   * Get the validation error message.
    *
    * @return string
    */
  public function message(){
    $fieldsText   = '';
    $fields       = $this->getFields();
    $preventFirst = true;
    $this->modelFixer();

    foreach($fields as $field=>$value){
      if($preventFirst){
        $preventFirst = false;
        continue;
      }

      $fieldLang = $this->getModelClass()::cvIam()->getModelLang()['fields'][$field]??$field;
      $fieldsText.="$fieldLang,";
    }

    $fieldsText = rtrim($fieldsText, ',');

    return str_replace(':fields', $fieldsText, $this->getMessage());
  }

  public function fixParameters(){
    $secondParams = $this->secondParams();
    $thirdParams  = $this->thirdParams();

    if(!$secondParams || count($secondParams) < 2)
      throw new \Exception("Validation {$this->getRule()} needs to have at least 3 parameters, modelClass, mainField and secondaryField");

    $this->modelFixer()->setMainField($secondParams[0]);

    $fields = $this->tuplaFix(array_shift($secondParams));

    foreach($secondParams as $param)
      $fields = array_merge($fields,$this->tuplaFix($param));

    return $this->setFields($fields)->setExtraFields($secondParams)->setExceptionKeyValue($thirdParams[0]??null);
  }

  public function modelFixer(){
    $modelClass = $this->firstParams()[0]??null;

    if(!class_exists($modelClass))
      $modelClass = "\App\Models\\$modelClass";

    if(!class_exists($modelClass))
      throw new \Exception("Validation {$this->getRule()} needs a valid modelClass as first parameter");

    return $this->setModelClass($modelClass);
  }
// [End Specific Logic]
// [Getters]
  public function getModelClass(){
    return $this->modelClass??null;
  }

  public function getFields(){
    return $this->fields??null;
  }

  public function getMainField(){
    return $this->mainField??null;
  }

  public function getExtraFields(){
    return $this->extraFields??null;
  }

  public function getExceptionKeyValue(){
    return $this->exceptionKeyValue??null;
  }
// [End Getters]
// [Setters]
  public function setModelClass($modelClass=null){
    $this->modelClass = $modelClass??null;

    return $this;
  }

  public function setFields($fields=null){
    $this->fields = $fields??null;

    return $this;
  }

  public function setMainField($mainField=null){
    $this->mainField = $mainField??null;

    return $this;
  }

  public function setExtraFields($extraFields=null){
    $this->extraFields = $extraFields??null;

    return $this;
  }

  public function setExceptionKeyValue($exceptionKeyValue=null){
    $this->exceptionKeyValue = $exceptionKeyValue??null;

    return $this;
  }
// [End Setters]
}

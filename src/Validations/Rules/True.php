<?php namespace Crudvel\Validations\Rules;

class True extends \Crudvel\Validations\Rules\BaseRule implements \Crudvel\Validations\CvRuleInterface{
// [Specific Logic]
  /**
   * Determine if the validation rule passes.
    *
    * @param  string  $attribute
    * @param  mixed  $value
    * @return bool
    */
  public function passes(){
    return in_array($this->getValue(),[1,'1',true]);
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
// [End Getters]
// [Setters]
// [End Setters]
}

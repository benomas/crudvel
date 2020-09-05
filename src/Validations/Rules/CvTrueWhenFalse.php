<?php namespace Crudvel\Validations\Rules;

class CvTrueWhenFalse extends \Crudvel\Validations\Rules\BaseRule implements \Crudvel\Validations\CvRuleInterface{
// [Specific Logic]
  protected $resource   = '';
  protected $otherField = '';
  /**
   * Determine if the validation rule passes.
    *
    * @param  string  $attribute
    * @param  mixed  $value
    * @return bool
    */
  public function passes(){
    if($this->booleanValue($this->otherValue()))
      return true;

    return $this->booleanValue($this->getValue());
  }

  /**
   * Get the validation error message.
    *
    * @return string
    */
  public function message(){
    return str_replace(':other', $this->cvFieldResourceLang($this->getResource(),$this->getOtherField()), $this->getMessage());
  }

  public function fixParameters(){
    $resource   = $this->firstParams()[0]??null;
    $otherField = $this->firstParams()[1]??null;

    if(!$resource || !$otherField)
      throw new \Exception("Validation {$this->getRule()} needs to have 2 parameters, resource and other field");

    return $this->setResource($resource)->setOtherField($otherField);
  }

  public function otherValue(){
    return $this->getValidator()->getData()[$this->getOtherField()] ?? null;
  }
// [End Specific Logic]
// [Getters]
  public function getResource(){
    return $this->resource??null;
  }

  public function getOtherField(){
    return $this->otherField??null;
  }
// [End Getters]
// [Setters]
  public function setResource($resource=null){
    $this->resource = $resource??null;

    return $this;
  }

  public function setOtherField($otherField=null){
    $this->otherField = $otherField??null;

    return $this;
  }
// [End Setters]
}

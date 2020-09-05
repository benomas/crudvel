<?php namespace Crudvel\Validations\Rules;

class CvFalseWhenGreaterThan extends \Crudvel\Validations\Rules\BaseRule implements \Crudvel\Validations\CvRuleInterface{
// [Specific Logic]
  protected $resource   = '';
  protected $otherField = '';
  protected $limit      = 0;
  /**
   * Determine if the validation rule passes.
    *
    * @param  string  $attribute
    * @param  mixed  $value
    * @return bool
    */
  public function passes(){
    if(!$this->otherValue() || !is_numeric($this->otherValue()))
      return true;

    return $this->otherValue() > $this->getLimit() && $this->booleanValue($this->getValue());
  }

  /**
   * Get the validation error message.
    *
    * @return string
    */
  public function message(){
    return str_replace([':other',':limit'], [$this->cvFieldResourceLang($this->getResource(),$this->getOtherField()),$this->getLimit()], $this->getMessage());
  }

  public function fixParameters(){
    $resource   = $this->firstParams()[0]??null;
    $otherField = $this->firstParams()[1]??null;
    $limit      = $this->firstParams()[2]??null;

    if(!$resource || !$otherField || $limit === null)
      throw new \Exception("Validation {$this->getRule()} needs to have 3 parameters, resource and other field");

    return $this->setResource($resource)->setOtherField($otherField)->setLimit($limit);
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

  public function getLimit(){
    return $this->limit??null;
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

  public function setLimit($limit=null){
    $this->limit = $limit??null;

    return $this;
  }
// [End Setters]
}

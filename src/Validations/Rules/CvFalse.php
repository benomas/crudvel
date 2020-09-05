<?php namespace Crudvel\Validations\Rules;

class CvFalse extends \Crudvel\Validations\Rules\BaseRule implements \Crudvel\Validations\CvRuleInterface{
// [Specific Logic]
  /**
   * Determine if the validation rule passes.
    *
    * @param  string  $attribute
    * @param  mixed  $value
    * @return bool
    */
  public function passes(){
    return $this->booleanValue($this->getValue()) === false;
  }

  /**
   * Get the validation error message.
    *
    * @return string
    */
  public function message(){
    return $this->getMessage();
  }
// [End Specific Logic]
// [Getters]
// [End Getters]
// [Setters]
// [End Setters]
}

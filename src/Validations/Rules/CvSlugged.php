<?php namespace Crudvel\Validations\Rules;

class CvSlugged extends \Crudvel\Validations\Rules\BaseRule implements \Crudvel\Validations\CvRuleInterface{
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
    return cvSlugCase($this->getValue()) === $this->getValue();
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

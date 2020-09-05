<?php namespace Crudvel\Validations\Rules;

class CvWildCard extends \Crudvel\Validations\Rules\BaseRule implements \Crudvel\Validations\CvRuleInterface{
// [Specific Logic]
  protected $wildCardMessage   = '';
  /**
   * Determine if the validation rule passes.
    *
    * @param  string  $attribute
    * @param  mixed  $value
    * @return bool
    */
  public function passes(){
    return false;
  }

  /**
   * Get the validation error message.
    *
    * @return string
    */
  public function message(){
    return $this->getWildCardMessage();
  }

  public function fixParameters(){
    $wildCardMessage   = $this->firstParams()[0]??null;

    if(!$wildCardMessage)
      throw new \Exception("Validation {$this->getRule()} needs to have 1 parameter, wildcard message");

    return $this->setWildCardMessage($wildCardMessage);
  }
// [End Specific Logic]
// [Getters]
  public function getWildCardMessage(){
    return $this->wildCardMessage??null;
  }
// [End Getters]
// [Setters]
  public function setWildCardMessage($wildCardMessage=null){
    $this->wildCardMessage = $wildCardMessage??null;

    return $this;
  }
// [End Setters]
}

<?php namespace Crudvel\Validations;

/**
* Custom Rule interface, discart laravel interface, because some limitations
*
*/

interface CvRuleInterface{

  /**
   * Determine if the validation rule passes.
   *
   * @param  string  $attribute
   * @param  mixed  $value
   * @return bool
   */
  public function passes();

  /**
   * Get the validation error message.
   *
   * @return string
   */
  public function message();

  /**
  * Prepare parameters for current validation, this method will determine how multiple paramters will be handler
  *
  * @return void
  */
  public function fixParameters();
  public function prepare();
  public function parameterSplitter();

}

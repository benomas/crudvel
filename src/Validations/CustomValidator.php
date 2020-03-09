<?php namespace Crudvel\Validations;

use Illuminate\Validation\Validator;

use DB;

class CustomValidator extends Validator {

/**
 * Validate that an attribute contains only alpha-numeric characters and spaces.
 *
 * @param  string  $attribute
 * @param  mixed   $value
 * @param  array   $parameters
 *
 * @return bool
 */
  public function validateAlpha($attribute, $value) {
      return preg_match('/^[\pL]+$/u', $value);
  }

/**
 * Validate that an attribute contains only alpha-numeric characters and spaces.
 *
 * @param  string  $attribute
 * @param  mixed   $value
 * @param  array   $parameters
 *
 * @return bool
 */
  public function validateAlphaSpaces($attribute, $value, $parameters) {
      return preg_match('/^[\pL\s]+$/u', $value);
  }

/**
 * Validate the uniqueness of attribute values on a given database table.
 * Usage: unique_with: table, column1, column2, ...
 *
 * @param  string $attribute
 * @param  mixed  $value
 * @param  array  $parameters
 *
 * @return boolean
 */
public function validateUniqueWith($attribute, $value, $parameters)
{
	$parameters = array_map('trim', $parameters);
	$table = array_shift($parameters);
	$column = $attribute;
	$extra = array();
	list($ignore_id, $ignore_column) = $this->getIgnore($parameters);
	foreach ($parameters as $parameter)
	{
		$parameter = array_map('trim', explode('=', $parameter, 2));
		$field_name = $parameter[0];
		if (count($parameter) > 1)
		{
			$column_name = $parameter[1];
		}
		else
		{
			$column_name = $field_name;
		}
		if ($field_name == $column)
		{
			$column = $column_name;
		}
		else
		{
			$extra[$column_name] = array_get($this->data, $field_name);
		}
	}
	$verifier = $this->getPresenceVerifier();
	return $verifier->getCount(
		$table,
		$column,
		$value,
		$ignore_id,
		$ignore_column,
		$extra
		) == 0;
}

/**
 * Replace all place-holders for the unique_with rule.
 *
 * @param  string  $message
 * @param  string  $attribute
 * @param  string  $rule
 * @param  array   $parameters
 *
 * @return string
 */
  public function replaceUniqueWith($message, $attribute, $rule, $parameters)
  {
    // remove trailing ID param if present
    $this->getIgnore($parameters);
    // merge primary field with conditional fields
    $fields = array($attribute) + $parameters;
    // get full language support due to mapping to validator getAttribute
    // function
    $fields = array_map(array($this, 'getAttribute'), $fields);
    // fields to string
    $fields = implode(', ', $fields);
    return str_replace(':fields', $fields, $message);
  }

/**
 * Returns an array with value and column name for an optional ignore.
 * Shaves of the ignore_id from the end of the array, if there is one.
 *
 * @param  array $parameters
 * @return array [$ignoreId, $ignoreColumn]
 */
private function getIgnore(&$parameters)
{
	$lastParam = end($parameters);
	$lastParam = array_map('trim', explode('=', $lastParam));
	if (!preg_match('/^[1-9][0-9]*$/', $lastParam[0]))
	{
		return array(null, null);
	}
	$ignoreId = $lastParam[0];
	$ignoreColumn = (sizeof($lastParam) > 1) ? end($lastParam) : null;
	array_pop($parameters);
	return array($ignoreId, $ignoreColumn);
}

/**
 * Validate that a given url is youtube valid
 *
 * @param  string  $attribute
 * @param  mixed   $value
 * @param  array   $parameters
 *
 * @return bool
 */
  public function validateYoutube($attribute, $value, $parameters) {
    $videoId = get_youtube_id($value);

    $youtubeVideo = Youtube::getVideoInfo($videoId);

    return (bool) $youtubeVideo;
  }

  /**
   * Checks if value is valid hexadecimal color code.
   *
   * @param  mixed  $value
   *
   * @return boolean
   */
  public static function validateHexColor($attribute, $value, $parameters)
  {
    return (bool) preg_match('/^#[a-fA-F0-9]{3,6}$/', $value);
  }

/**
 * Validate that an attribute doesn't contains only digits
 *
 * @param  string  $attribute
 * @param  mixed   $value
 * @param  array   $parameters
 *
 * @return bool
 */
  public function validateNotOnlyDigits($attribute, $value, $parameters) {
    return preg_match('/(?!^\pN+$)^.+$/u', $value);
  }

/**
 * Validate that an attribute doesn't contains special characters
 * Only unicode and numbers
 *
 * @param  string  $attribute
 * @param  mixed   $value
 * @param  array   $parameters
 *
 * @return bool
 */
  public function validateNotSpecial($attribute, $value, $parameters) {
    return preg_match('/^[\pL\pN\s]+$/u', $value);
  }

/**
 * Validate that a unique combination of keys
 *
 * @author	Beni (beni@intagono.com) 2016-10-08
 *
 * @param	type	string	name of the attribute
 *
 * @param	type	string	value of the attribute
 *
 * @param	type	string	array of aditional parameters
 *
 * @return  boolean
 */
  function validateCompositives($attribute, $value, $parameters)
  {
    if(!is_array($parameters) || count($parameters)===0)
      return true;

    $table = $parameters[0];
    unset($parameters[0]);

    $columnName=null;
    $columnValue=null;
    $pairData[$attribute]=$value;

    foreach ($parameters as $key) {

      if(	$columnName && $columnValue){
        $pairData[$columnName]=$columnValue;
        $columnName=null;
        $columnValue=null;
      }
      else{
        if($columnName)
          $columnValue=$key;
        else
          $columnName=$key;
      }
    }

    if(	$columnName && $columnValue)
      $pairData[$columnName]=$columnValue;
    else{
      if($columnName)
        unset($pairData[$columnName]);
    }

    $result=DB::table($table)->where(function ($query) use ($pairData) {
      foreach ($pairData as $key=>$value)
      {
          $query->where($key,$value);
      }
    });

    return $result->count()===0;
  }

  /**
   * Validate that all the elements on the list, correspond with his catalog
   *
   * @author	Beni (beni@intagono.com) 2016-12-05
   *
   * @return  boolean
   */
  function validateListExist($attribute, $value, $parameters)
  {
    if(empty($value))
      return true;
    if(!isset($parameters[0]) || !is_array($value) || count($value)===0)
      return false;
    $key = $parameters[1];
    $itemList = cvGetSomeKeys($value,null,$key);
    $query = DB::table($parameters[0])->where($parameters[0].'.'.$key,'=',$value[0]);
    foreach($itemList AS $identifier){
      if(!is_int(($identifier[$key]??null) + 0))
        return false;
      $query->orWhere($parameters[0].'.'.$key,'=',$identifier[$key]);
    }

    return $query->count() === count($value);
  }

  /**
   * Validate that a unique combination of keys
   *
   * @author	Beni (beni@intagono.com) 2016-12-05
   *
   * @return  boolean
   */
  function validateNoDuplicateValues($attribute, $value, $parameters)
  {
    if(!isset($value) || !is_array($value) || count($value)<2)
      return true;
    $uniques = array_unique($value);

    if(count($value) !== count($uniques))
      return false;

    return true;

  }

  function validateKeyExist($attribute, $value, $parameters){

    if(!isset($parameters[0]))
      return false;

    if(!isset($parameters[1]))
      $parameters[1]='id';

      $hasExceptions = 0;
      $exceptions    = [];
    try{
      $test = DB::table($parameters[0])->where($parameters[0].'.'.$parameters[1],$value);
      foreach ($parameters as $key => $property) {
        if($key<2)
          continue;

        if($hasExceptions){
          if($key===$hasExceptions+1)
            $exceptionTable = $property;
          if($key===$hasExceptions+2)
            $exceptionTableColumn= $property;
          if($key===$hasExceptions+3)
            $exceptionTableValue= $property;
        }
        else{
          if($property==="exception"){
            $hasExceptions = $key;
            continue;
          }

          if($key%2===0)
            $temp = $property;
          else
            $test->where($parameters[0].'.'.$temp,$property);
        }
      }
      if($test->count()>0)
        return true;
      if($hasExceptions && DB::table($parameters[0])->where($parameters[0].'.'.$parameters[1],'=',$value)->count()){
        $exceptionQuery = DB::table($exceptionTable)->where($exceptionTable.".".$attribute,$value);
        if(!$exceptionQuery->count())
          return false;
        if(!empty($exceptionTableColumn) && !empty($exceptionTableValue))
          return $exceptionQuery->
            where($exceptionTable.".".$exceptionTableColumn,$exceptionTableValue)->
            count();
        return true;
      }

      return false;
    }
    catch(\Exception $e){
      customLog($e);
    }
    return false;
  }

  /**
   * Validate that a unique combination of keys
   *
   * @author	Beni (beni@intagono.com) 2016-12-05
   *
   * @return  boolean
   */
  function validateUserName($attribute, $value, $parameters)
  {
    return preg_match('/^[a-zA-Z0-9_\-\.@]+?$/',$value);
  }

  /**
   * Validate that a unique combination of keys
   *
   * @author	Beni (beni@intagono.com) 2016-12-05
   *
   * @return  boolean
   */
  function validateRfc($attribute, $value, $parameters)
  {
    return preg_match('/^\w{3,4}\d{6,6}\w{2,3}$/',$value);
  }

  /**
   * Validate that de value of the attribute correspond to with some of the items in the atributes list
   *
   * @author  Beni (beni@intagono.com) 2017-12-27
   *
   * @return  boolean
   */
  function validateInList($attribute, $value, $parameters)
  {
      if(empty($parameters))
          return true;
      return in_array($value,$parameters);
  }

  //TODO validate that internally, for now it will be evaluated in request, and if is called will be triggered always as false
  /**
  * Detect if file already exist
  *
  * @author benomas benomas@gmail.com
  * @date   2019-07-15
  * @return void
  *
  */
  function validateFileAlreadyExist($attribute, $value, $parameters)
  {
    return null;
  }

  //TODO validate that internally, for now it will be evaluated in request, and if is called will be triggered always as false
  /**
  * Validate that the user has permissions to upload files to the associated resource
  *
  * @author benomas benomas@gmail.com
  * @date   2019-07-15
  * @return void
  */
  function validateFileResource($attribute, $value, $parameters)
  {
    return null;
  }

  function validateCvKeyExist($attribute, $value, $parameters){

    if(!isset($parameters[0]))
      return false;

    if(!isset($parameters[1]))
      $parameters[1]='id';

      $hasExceptions = 0;
      $exceptions    = [];
    try{
      $test = DB::table($parameters[0])->where($parameters[0].'.'.$parameters[1],$value);
      foreach ($parameters as $key => $property) {
        if($key<2)
          continue;

        if($hasExceptions){
          if($key===$hasExceptions+1)
            $exceptionTable = $property;
          if($key===$hasExceptions+2)
            $exceptionTableColumn= $property;
          if($key===$hasExceptions+3)
            $exceptionTableValue= $property;
        }
        else{
          if($property==="exception"){
            $hasExceptions = $key;
            continue;
          }

          if($key%2===0)
            $temp = $property;
          else
            $test->where($parameters[0].'.'.$temp,$property);
        }
      }
      if($test->count()>0)
        return true;
      if($hasExceptions && DB::table($parameters[0])->where($parameters[0].'.'.$parameters[1],'=',$value)->count()){
        $exceptionQuery = DB::table($exceptionTable)->where($exceptionTable.".".$attribute,$value);
        if(!$exceptionQuery->count())
          return false;
        if(!empty($exceptionTableColumn) && !empty($exceptionTableValue))
          return $exceptionQuery->
            where($exceptionTable.".".$exceptionTableColumn,$exceptionTableValue)->
            count();
        return true;
      }

      return false;
    }
    catch(\Exception $e){
      customLog($e);
    }
    return false;
  }
}

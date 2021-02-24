<?php namespace Crudvel\Validations\Rules;

class KeyExistV2 extends \Crudvel\Validations\Rules\BaseRule implements \Crudvel\Validations\CvRuleInterface{
// [Specific Logic]
  protected $resource        = '';
  protected $otherField      = '';
  protected $table           = null;
  protected $tableKey        = null;
  protected $exceptions      = [];
  protected $extraConditions = [];
  /**
   * Determine if the validation rule passes.
    *
    * @param  string  $attribute
    * @param  mixed  $value
    * @return bool
    */
  public function passes(){
    try{
      $tableBuilder = \Illuminate\Support\Facades\DB::table($this->getTable())->where("{$this->getTable()}.{$this->getTableKey()}",$this->getValue());
        
      $property=null;

      foreach($this->getExtraConditions()??[] as $propertyOrValue){
        if(!$property){
          $property = $propertyOrValue;
          continue;
        }

        $tableBuilder->where("{$this->getTable()}.{$property}",$propertyOrValue);
        $property = null;
      }
      
      if($tableBuilder->count())
        return true;

      if(!$this->getExceptions() || count($this->getExceptions())<3)
        return false;

      $exceptions     = $this->getExceptions();
      $tableException = $exceptions[0];
      unset($exceptions[0]);

      $tableExceptionBuilder = \Illuminate\Support\Facades\DB::table($tableException);
      

      $property=null;
      foreach($exceptions??[] as $propertyOrValue){
        if(!$property){
          $property = $propertyOrValue;
          continue;
        }

        $tableExceptionBuilder->where("{$tableException}.{$property}",$propertyOrValue);
        $property = null;
      }

      return $tableExceptionBuilder->count();
    }
    catch(\Exception $e){
      customLog($e);
    }

    return false;
  }


  /**
   * Get the validation error message.
    *
    * @return string
    */
  public function message(){
    return str_replace(':resource', $this->cvResourceLang($this->getTable())['row_label']??cvSingularCase($this->getTable()), $this->getMessage());
  }

  public function fixParameters(){
    $this->setTable($this->firstParams()[0]??null)
      ->setTableKey($this->firstParams()[1]??null)
      ->setExtraConditions($this->secondParams())
      ->setExceptions($this->thirdParams());
      
    if(!$this->getTable() || !$this->getTableKey())
      throw new \Exception("Validation {$this->getRule()} needs to have 2 parameters, table and other tableKey");

    return $this;
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

  public function getTable(){
    return $this->table??null;
  }

  public function getTableKey(){
    return $this->tableKey??null;
  }

  public function getExtraConditions(){
    return $this->extraConditions??null;
  }

  public function getExceptions(){
    return $this->exceptions??null;
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

  public function setTable($table=null){
    $this->table = $table??null;
    
    return $this;
  }

  public function setTableKey($tableKey=null){
    $this->tableKey = $tableKey??null;

    return $this;
  }

  public function setExtraConditions($extraConditions=null){
    $this->extraConditions = $extraConditions??null;
    
    return $this;
  }

  public function setExceptions($exceptions=null){
    $this->exceptions = $exceptions??null;
    
    return $this;
  }
// [End Setters]
}

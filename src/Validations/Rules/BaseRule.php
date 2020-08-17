<?php namespace Crudvel\Validations\Rules;


class BaseRule{
  protected $message;
  protected $attribute;
  protected $rule;
  protected $parameters;
  protected $value;
  protected $parameterSeparator = ';';
  protected $tuplaSeparator     = '=>';
  protected $splittedParameters = [];
  /**
   * Create a new rule instance.
   *
   * @return void
   */
  public function __construct(){
    //
  }

// [Specific Logic]
  public function fixParameters(){
    return $this;
  }

  public function prepare(){
    return $this->parameterSplitter()->fixParameters();
  }

  public function parameterSplitter(){
    $splittedParameters = [[]];
    $parts = 0;
    foreach($this->getParameters() as $parameter){
      if($this->isParameterSeparator($parameter)){
        $splittedParameters[++$parts] = [];
        continue;
      }

      $splittedParameters[$parts][]=$parameter;
    }

    return $this->setSplittedParameters($splittedParameters);
  }

  public function isParameterSeparator($parameter){
    return  $parameter === $this->getParameterSeparator();
  }

  public function tuplaFix($stringField){
    $exploded = explode($this->getTuplaSeparator(), $stringField);
    if(count($exploded)< 2)
      return $stringField;

    if(count($exploded)> 2)
      throw new \Exception("Validation {$this->getRule()} error in tupla definition [$stringField]");

    return  [
      $exploded[0]=>$exploded[1]
    ];
  }

  public function firstParams(){
    return $this->getSplittedParameters()[0]??null;
  }

  public function secondParams() {
    return $this->getSplittedParameters()[1]??null;
  }

  public function thirdParams() {
    return $this->getSplittedParameters()[2]??null;
  }
// [End Specific Logic]
// [Getters]
  public function getMessage(){
    return $this->message??null;
  }

  public function getAttribute(){
    return $this->attribute??null;
  }

  public function getValue(){
    return $this->value??null;
  }

  public function getRule(){
    return $this->rule??null;
  }

  public function getParameters():iterable{
    return $this->parameters??[];
  }

  public function getParameterSeparator(){
    return $this->parameterSeparator??null;
  }

  public function getSplittedParameters(){
    return $this->splittedParameters??null;
  }

  public function getTuplaSeparator(){
    return $this->tuplaSeparator??'=>';
  }
// [End Getters]
// [Setters]

  public function setMessage($message=null){
    $this->message = $message??null;

    return $this;
  }

  public function setAttribute($attribute=null){
    $this->attribute = $attribute??null;

    return $this;
  }

  public function setValue($value=null){
    $this->value = $value??null;

    return $this;
  }

  public function setRule($rule=null){
    $this->rule = $rule??null;

    return $this;
  }

  public function setParameters($parameters=null){
    $this->parameters = $parameters??null;

    return $this;
  }

  public function setParameterSeparator($parameterSeparator=null){
    $this->parameterSeparator = $parameterSeparator??null;

    return $this;
  }

  public function setSplittedParameters($splittedParameters=null){
    $this->splittedParameters = $splittedParameters??null;

    return $this;
  }

  public function setTuplaSeparator($tuplaSeparator=null){
    $this->tuplaSeparator = $tuplaSeparator??'=>';

    return $this;
  }
// [End Setters]
}

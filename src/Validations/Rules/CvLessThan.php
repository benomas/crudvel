<?php namespace Crudvel\Validations\Rules;

class CvLessThan extends \Crudvel\Validations\Rules\BaseRule implements \Crudvel\Validations\CvRuleInterface{
  // [Specific Logic]
    protected $resource   = '';
    protected $limit      = 0;
    /**
     * Determine if the validation rule passes.
      *
      * @param  string  $attribute
      * @param  mixed  $value
      * @return bool
      */
    public function passes(){
      return $this->getValue() < $this->getLimit();
    }

    /**
     * Get the validation error message.
      *
      * @return string
      */
    public function message(){
      return str_replace([':limit'], [$this->getLimit()], $this->getMessage());
    }

    public function fixParameters(){
      $resource   = $this->firstParams()[0]??null;
      $limit = $this->firstParams()[1]??null;

      if(!$resource || $limit === null)
        throw new \Exception("Validation {$this->getRule()} needs to have 2 parameters, resource and other field");

      return $this->setResource($resource)->setLimit($limit);
    }
  // [End Specific Logic]
  // [Getters]
    public function getResource(){
      return $this->resource??null;
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

    public function setLimit($limit=null){
      $this->limit = $limit??null;

      return $this;
    }
  // [End Setters]
  }

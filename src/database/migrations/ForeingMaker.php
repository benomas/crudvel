<?php namespace Crudvel\Database\Migrations;

use Illuminate\Support\Facades\Schema;
use Crudvel\Database\Migrations\BaseMigration;

class ForeingMaker{
  protected $migration  = null;
  protected $table      = null;
  protected $to         = null;
  protected $foreign    = null;
  protected $references = null;
  protected $on         = null;
  protected $onUpdate   = null;
  protected $onDelete   = null;

  public function __construct($table=null){
    $this->setTable($table);
  }

// [Specific Logic]
  public function build($to=null){
    $this->setTo($to);
    
    try{
      Schema::table($this->getTable(), function($table){
        $table->foreign($this->getForeign()??cvCaseFixer('singular|snake',$this->getTo()).'_id')
          ->references($this->getReferences()??'id')
          ->on($this->getOn()??cvCaseFixer('plural|snake',$this->getTo()))
          ->onUpdate($this->getOnUpdate()??'cascade')
          ->onDelete($this->getOnDelete()??'cascade');
      });
    }
    catch(\Exception $e){
      cvConsoler(cvNegative("error when try to alter {$this->getTable()} table").cvWarning(' '.$e->getMessage())."\n");
    }

    return $this;
  }
// [End Specific Logic]
// [Getters]
  public function getMigration(){
    return $this->migration;
  }

  public function getTable(){
    return $this->table;
  }

  public function getTo(){
    return $this->to;
  }

  public function getForeign(){
    return $this->foreign??null;
  }

  public function getReferences(){
    return $this->references??null;
  }

  public function getOn(){
    return $this->on??null;
  }

  public function getOnUpdate(){
    return $this->onUpdate??null;
  }

  public function getOnDelete(){
    return $this->onDelete??null;
  }
// [End Getters]
// [Setters]
  public function setMigration(BaseMigration $migration=null){
    $this->migration = $migration;

    return $this;
  }

  public function setTable($table=null){
    $this->table = $table??null;

    return $this;
  }

  public function setTo($to=null){
    $this->to = $to??null;

    return $this;
  }

  public function setForeign($foreign = null){
    $this->foreign = $foreign??null;
    
    return $this;
  }

  public function setReferences($references = null){
    $this->references = $references??null;
    
    return $this;
  }

  public function setOn($on = null){
    $this->on = $on??null;
    
    return $this;
  }

  public function setOnUpdate($onUpdate = null){
    $this->onUpdate = $onUpdate??null;
    
    return $this;
  }

  public function setOnDelete($onDelete = null){
    $this->onDelete = $onDelete??null;
    
    return $this;
  }
// [End Setters]
}

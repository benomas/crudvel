<?php namespace Crudvel\Models;

class Migration extends \Crudvel\Customs\Models\BaseModel{

  protected $fillable = [
    "id",
    "migration",
    "batch",
  ];

  public function __construct($attributes = array())  {
    parent::__construct($attributes);
  }
//Relationships

//End Relationships

// Scopes
  public function scopeLikeTable($query,$table){
    $query->where('migration','like','%create_'.str_slug($table,'_').'_table%');
  }
// End Scopes
}

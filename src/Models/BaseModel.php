<?php namespace Crudvel\Models;

use Illuminate\Database\Eloquent\Model;
use Crudvel\Traits\CrudTrait;

class BaseModel extends Model {
  use CrudTrait;
  protected $schema;
  protected $hasPropertyActive = true;
  protected $hidden            = ['pivot'];

  public function __construct($attributes = array())  {
    parent::__construct($attributes);
  }

// Scopes

  public function scopeInStatus($query, $status){
    if(is_array($status))
      $query->whereIn($this->getTable().".status",$status);
    else
      $query->whereIn($this->getTable().".status",[$status]);
  }

  public function scopeNotInStatus($query, $status){
    if(is_array($status))
      $query->whereNotIn($this->getTable().".status",$status);
    else
      $query->whereNotIn($this->getTable().".status",[$status]);
  }

  public function scopeStatus($query, $status){
    $query->where($this->getTable().".status",$status);
  }

  public function scopeActives($query){
    if($query->getModel()->hasPropertyActive)
      $query->where($this->getTable().".active",1);
  }

  public function scopeNoFilters($query){
    $query->whereRaw("1 = 1");
  }

  public function scopeNullFilter($query){
    $query->whereNull($this->getTable().".id");
  }

  public function scopeId($query,$id){
    $query->where($this->getTable().".id",$id);
  }

  public function scopeIds($query,$ids){
    $query->whereIn($this->getTable().".id",$ids);
  }

  public function scopeNoIds($query,$ids){
    $query->whereNotIn($this->getTable().".id",$ids);
  }

  public function scopeUnActives($query){
    $query->where($this->getTable().".status",0);
  }

  public function scopeName($query,$name){
    $query->where($this->getTable().".name", $name);
  }

  public function scopeValue($query,$value){
    $query->where($this->getTable().".value", $value);
  }

  public function scopeSlugs($query,$slug){
    $query->whereIn($this->getTable().".slug", $slug);
  }

  public function scopeSlug($query,$slug){
    $query->where($this->getTable().".slug", $slug);
  }

  public function scopeOfLevel($query,$level_id){
    $query->where($this->getTable().".level_id", $level_id);
  }

  public function scopeOfParent($query,$parent_id){
    $query->where($this->getTable().".parent_id", $parent_id);
  }

  public function scopeOfSublevel($query,$sublevel_id){
    $query->where($this->getTable().".sublevel_id", $sublevel_id);
  }

  public function scopeGeneralOwner($query,$userId){
  }

  public function scopeParticularOwner($query,$userId){
    $query->where($this->getTable().".user_id", $userId);
  }

  public function scopeUpdatedBefore($query,$date){
    $query->where($this->getTable().".updated_at",'>',$date);
  }

  public function scopeUpdatedAfter($query,$date){
    $query->where($this->getTable().".updated_at",'<',$date);
  }

  public function scopeUpdatedBetween($query,$date){
    $query->updatedBefore($date)->updatedAfter($date);
  }

// End Scopes

// Others

  public function getTable(){
    //TODO, fix schema inclusion
    return parent::getTable();
  }

  public function manyToManyToMany($firstLevelRelation,$secondLevelRelation,$secondLevelModel){
    if(!is_callable(array($secondLevelModel,"nullFilter")))
      return null;

    if(!method_exists($this,$firstLevelRelation))
      return null;

    $firstLevelRelationInstace = $this->{$firstLevelRelation};
    if(!$firstLevelRelationInstace)
      return $secondLevelModel::nullFilter();

    $secondLevelRelationArray=[];
    foreach ($firstLevelRelationInstace as $firstLevelRelationItem){
      if(method_exists($firstLevelRelationItem,$secondLevelRelation) && $firstLevelRelationItem->{$secondLevelRelation}()->count())
          $secondLevelRelationArray = array_unique(array_merge($secondLevelRelationArray,$firstLevelRelationItem->{$secondLevelRelation}()->get()->pluck("id")->toArray()));
    }

    return $secondLevelModel::ids($secondLevelRelationArray);
  }

  public function shadow(){
    $clonedInstacse = new \Illuminate\Database\Eloquent\Builder(clone $this->getQuery());
    $clonedInstace->setModel($this->getModel());
    return $clonedInstace;
  }

  public function getConnectionName(){
    return $this->connection;
  }

  public static function accesor(){
    return self::first();
  }
// Others
}

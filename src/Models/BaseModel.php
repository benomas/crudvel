<?php namespace Crudvel\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Crudvel\Traits\CrudTrait;
use Crudvel\Traits\CacheTrait;

class BaseModel extends Model {
  use CrudTrait;
  use CacheTrait;
  protected $schema;
  protected $hasPropertyActive = true;
  protected $hidden            = ['pivot'];
  protected $cacheBoots        = [];

  public function __construct($attributes = array())  {
    parent::__construct($attributes);
    $this->setCacheBoots();
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
    $query->whereNull($this->getTable().".".$this->getKeyName());
  }

  public function scopeNotNull($query,$columnt){
    $query->whereNotNull($this->getTable().".".$columnt);
  }

  public function scopeId($query,$key){
    $query->where($this->getTable().".".$this->getKeyName(),$key);
  }

  public function scopeIds($query,$keys){
    $query->whereIn($this->getTable().".".$this->getKeyName(),$keys);
  }

  public function scopeNoIds($query,$keys){
    $query->whereNotIn($this->getTable().".".$this->getKeyName(),$keys);
  }

  public function scopeUuid($query,$uuid){
    $query->where($this->getTable().".uuid",$uuid);
  }

  public function scopeUuids($query,$uuids){
    $query->whereIn($this->getTable().".uuid",$uuids);
  }

  public function scopeNoUuids($query,$uuids){
    $query->whereNotIn($this->getTable().".uuid",$uuids);
  }

  public function scopeUnActives($query){
    $query->where($this->getTable().".status",0);
  }

  public function scopeName($query,$name){
    $query->where($this->getTable().".name", $name);
  }

  public function scopeNombre($query,$nombre){
    $query->where($this->getTable().".nombre", $nombre);
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

  public function scopeDistinctCount($query,$column){
    if(!in_array($column,$this->getTableColumns()))
      return 0;
    return $query->count(DB::raw("DISTINCT ".$this->fixColumnName($column)));
  }

  /**
   * Weir situation with sql server, where i cant access to other columns that are not definend
   * into de group by, so this scope add group by his self table key
   *
   *
   * */
  public function scopeGroupByKey($query){
    $query->groupBy($this->getTable().'.'.$this->getKeyName());
  }

  public function scopeOrderByKey($query){
    $query->orderBy($this->getTable().'.'.$this->getKeyName());
  }

  public function scopeSelectKey($query){
    $query->select($this->getTable().'.'.$this->getKeyName());
  }

  public function scopeSelectMinKey($query){
    $query->min($this->getTable().'.'.$this->getKeyName());
  }

  public function scopeDuplicity($query){
    $query->name($this->name);
  }
// End Scopes

// Others

  public function getTable(){
    //TODO, fix schema inclusion
    return parent::getTable();
  }

  public function getSimpleTable(){
    return empty($schema = $this->schema)?$this->getTable():str_replace($schema.'.','',$this->getTable());
  }

  public function getSchema(){
    return $this->schema;
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

  //TODO sanatize this method
  //! important this method need to some be fixed to prevent inyection
  public function fixColumnName($column){
    return $this->getTable().'.'.$column;
  }

  public function getTableColumns() {
    return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getSimpleTable());
  }
// Others
}

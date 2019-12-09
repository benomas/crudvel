<?php namespace Crudvel\Models;

use Crudvel\Interfaces\CvCrudInterface;
use DB;
use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model implements CvCrudInterface{
  use \Crudvel\Traits\CrudTrait;
  use \Crudvel\Traits\CacheTrait;
  use \Crudvel\Traits\CvPatronTrait;

  protected $slugSingularName;
  protected $cvResourceInstance;
  protected $schema;
  protected $hasPropertyActive = true;
  protected $hidden            = ['pivot'];
  protected $cacheBoots        = [];

  public function __construct($attributes = array())  {
    parent::__construct($attributes);
    $this->setCacheBoots();
    $this->injectCvResource();
  }

// [Relationships]
// [End Relationships]

// [Transformers]
// [End Transformers]

// [Scopes]
  public function scopeInStatus($query, $status, $preFixed = true){
    if(is_array($status))
      $query->whereIn($this->preFixed('status',$preFixed),$status);
    else
      $query->whereIn($this->preFixed('status',$preFixed),[$status]);
  }

  public function scopeNotInStatus($query, $status, $preFixed = true){
    if(is_array($status))
      $query->whereNotIn($this->preFixed('status',$preFixed),$status);
    else
      $query->whereNotIn($this->preFixed('status',$preFixed),[$status]);
  }

  public function scopeStatus($query, $status, $preFixed = true){
    $query->where($this->preFixed('status',$preFixed),$status);
  }

  public function scopeActives($query, $preFixed = true){
    if($query->getModel()->hasPropertyActive)
      $query->where($this->preFixed('active',$preFixed),1);
  }

  public function scopeNoFilters($query){
    $query->whereRaw("1 = 1");
  }

  public function scopeNullFilter($query, $preFixed = true){
    $query->whereNull($this->preFixed($this->getKeyName(),$preFixed));
  }

  public function scopeNotNull($query,$column, $preFixed = true){
    $query->whereNotNull($this->preFixed($column,$preFixed));
  }

  public function scopeId($query,$key, $preFixed = true){
    $query->where($this->preFixed($this->getKeyName(),$preFixed),$key);
  }

  public function scopeIds($query,$keys, $preFixed = true){
    $query->whereIn($this->preFixed($this->getKeyName(),$preFixed),$keys);
  }

  public function scopeNoIds($query,$keys, $preFixed = true){
    $query->whereNotIn($this->preFixed($this->getKeyName(),$preFixed),$keys);
  }

  public function scopeKey($query,$key, $preFixed = true){
    $query->where($this->preFixed($this->getKeyName(),$preFixed),$key);
  }

  public function scopeKeys($query,$keys, $preFixed = true){
    $query->whereIn($this->preFixed($this->getKeyName(),$preFixed),$keys);
  }

  public function scopeNoKeys($query,$keys, $preFixed = true){
    $query->whereNotIn($this->preFixed($this->getKeyName(),$preFixed),$keys);
  }

  public function scopeUuid($query,$uuid, $preFixed = true){
    $query->where($this->preFixed('uuid',$preFixed),$uuid);
  }

  public function scopeUuids($query,$uuids, $preFixed = true){
    $query->whereIn($this->preFixed('uuid',$preFixed),$uuids);
  }

  public function scopeNoUuids($query,$uuids, $preFixed = true){
    $query->whereNotIn($this->preFixed('uuid',$preFixed),$uuids);
  }

  public function scopeUnActives($query, $preFixed = true){
    $query->where($this->preFixed('status',$preFixed),0);
  }

  public function scopeName($query,$name, $preFixed = true){
    $query->where($this->preFixed('name',$preFixed), $name);
  }

  public function scopeNombre($query,$nombre, $preFixed = true){
    $query->where($this->preFixed('nombre',$preFixed), $nombre);
  }

  public function scopeValue($query,$value, $preFixed = true){
    $query->where($this->preFixed('value',$preFixed), $value);
  }

  public function scopeSlugs($query,$slug, $preFixed = true){
    $query->whereIn($this->preFixed('slug',$preFixed), $slug);
  }

  public function scopeSlug($query,$slug, $preFixed = true){
    $query->where($this->preFixed('slug',$preFixed), $slug);
  }

  public function scopeOfLevel($query,$level_id, $preFixed = true){
    $query->where($this->preFixed('level_id',$preFixed), $level_id);
  }

  public function scopeOfParent($query,$parent_id, $preFixed = true){
    $query->where($this->preFixed('parent_id',$preFixed), $parent_id);
  }

  public function scopeOfSublevel($query,$sublevel_id, $preFixed = true){
    $query->where($this->preFixed('sublevel_id',$preFixed), $sublevel_id);
  }

  public function scopeGeneralOwner($query,$userId){
  }

  public function scopeParticularOwner($query,$userId){
    $query->where($this->preFixed('user_id',true), $userId);
  }

  public function scopeUpdatedBefore($query,$date, $preFixed = true){
    $query->where($this->preFixed('updated_at',$preFixed),'>',$date);
  }

  public function scopeUpdatedAfter($query,$date, $preFixed = true){
    $query->where($this->preFixed('updated_at',$preFixed),'<',$date);
  }

  public function scopeUpdatedBetween($query,$date, $preFixed = true){
    $query->updatedBefore($date)->updatedAfter($date);
  }

  public function scopeDistinctCount($query,$column,$preFixed=true){
    if(!in_array($column,$this->getTableColumns()))
      return 0;
    return kageBunshinNoJutsu($query)->count(DB::raw("DISTINCT ".$this->preFixed($column,$preFixed)));
  }

  /**
   * Weir situation with sql server, where i cant access to other columns that are not definend
   * into de group by, so this scope add group by his self table key
   *
   *
   * */
  public function scopeGroupByKey($query, $preFixed = true){
    $query->groupBy($this->preFixed($this->getKeyName(),$preFixed));
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

  public function scopeInvokePosfix($query,$related,$posfix){
    $foreintColumn = \Str::snake(\Str::singular(($table=$this->getTable()))).'_id';
    $query->select("$table.$posfix as $table".'_'.$posfix)
    ->whereColumn($related::cvIam()->getTable().".$foreintColumn", "$table.id")
    ->limit(1);
  }

  public function scopeInvokeSearch($query,$related){
    $foreintColumn = \Str::snake(\Str::singular(($table=$this->getTable()))).'_id';
    $query->select("$table.name as cv_search")
    ->whereColumn($related::cvIam()->getTable().".$foreintColumn", "$table.id")
    ->limit(1);
  }
// [End Scopes]

// [Others]
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
    $clonedInstace = new \Illuminate\Database\Eloquent\Builder(clone $this->getQuery());
    $clonedInstace->setModel($this->getModel());
    return $clonedInstace;
  }

  public function getConnectionName(){
    if(!is_null($this->connection))
      return $this->connection;
    return config('database.default');
  }

  public static function accesor(){
    return self::first();
  }

  //TODO sanatize this method
  public function fixColumnName($column){
    if(!in_array($column,$this->getTableColumns()))
      return null;
    return $this->getTable().'.'.$column;
  }

  public function getTableColumns() {
    return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getSimpleTable());
  }

  public function getSearchFieldColumn(){
    return 'search_field';
  }

  public function preFixed($column,$fixed=true){
    if(!$fixed)
      return $column;
    return $this->getTable().'.'.$column;
  }

  public function getKeyValue(){
    return $this->attributes[$this->getKeyName()]??null;
  }
// [End Others]
}

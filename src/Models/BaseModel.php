<?php

namespace Crudvel\Models;

use Crudvel\Interfaces\CvCrudInterface;
use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class BaseModel extends Model implements CvCrudInterface
{
  use \Crudvel\Traits\CrudTrait;
  use \Crudvel\Traits\CacheTrait;
  use \Crudvel\Traits\CvPatronTrait;
  use \Crudvel\Libraries\Helpers\CasesTrait;

  protected $slugSingularName;
  protected $cvResourceInstance;
  protected $schema;
  protected $hasPropertyActive = true;
  protected $hidden            = ['pivot'];
  protected $cacheBoots        = [];
  protected $modelMetaData     = null;
  protected $cvSearches        = [];

  public function __construct($attributes = array())
  {
    parent::__construct($attributes);
    $this->setCacheBoots();
    $this->injectCvResource();
  }

  // [Relationships]
  // [End Relationships]

  // [Transformers]
  // [End Transformers]

  // [Scopes]
  public function scopeInStatus($query, $status, $preFixed = true)
  {
    if (is_array($status))
      $query->whereIn($this->preFixed('status', $preFixed), $status);
    else
      $query->whereIn($this->preFixed('status', $preFixed), [$status]);

    return $query;
  }

  public function scopeNotInStatus($query, $status, $preFixed = true)
  {
    if (is_array($status))
      $query->whereNotIn($this->preFixed('status', $preFixed), $status);
    else
      $query->whereNotIn($this->preFixed('status', $preFixed), [$status]);

    return $query;
  }

  public function scopeStatus($query, $status, $preFixed = true)
  {
    return $query->where($this->preFixed('status', $preFixed), $status);
  }

  public function scopeActives($query, $preFixed = true)
  {
    if ($query->getModel()->hasPropertyActive)
      $query->where($this->preFixed('active', $preFixed), 1);

    return $query;
  }

  public function scopeNoFilters($query)
  {
    return $query->whereRaw("1 = 1");
  }

  public function scopeNullFilter($query, $preFixed = true)
  {
    return $query->whereNull($this->preFixed($this->getKeyName(), $preFixed));
  }

  public function scopeNotNull($query, $column, $preFixed = true)
  {
    return $query->whereNotNull($this->preFixed($column, $preFixed));
  }

  public function scopeId($query, $key, $preFixed = true)
  {
    return $query->where($this->preFixed($this->getKeyName(), $preFixed), $key);
  }

  public function scopeIds($query, $keys, $preFixed = true)
  {
    return $query->whereIn($this->preFixed($this->getKeyName(), $preFixed), $keys);
  }

  public function scopeNoIds($query, $keys, $preFixed = true)
  {
    return $query->whereNotIn($this->preFixed($this->getKeyName(), $preFixed), $keys);
  }

  public function scopeKey($query, $key, $preFixed = true)
  {
    return $query->where($this->preFixed($this->getKeyName(), $preFixed), $key);
  }

  public function scopeKeyLessThan($query, $key, $preFixed = true)
  {
    return $query->where($this->preFixed($this->getKeyName(), $preFixed), '<', $key);
  }

  public function scopeKeys($query, $keys, $preFixed = true)
  {
    return $query->whereIn($this->preFixed($this->getKeyName(), $preFixed), $keys);
  }

  public function scopeNoKeys($query, $keys, $preFixed = true)
  {
    return $query->whereNotIn($this->preFixed($this->getKeyName(), $preFixed), $keys);
  }

  public function scopeUuid($query, $uuid, $preFixed = true)
  {
    return $query->where($this->preFixed('uuid', $preFixed), $uuid);
  }

  public function scopeUuids($query, $uuids, $preFixed = true)
  {
    return $query->whereIn($this->preFixed('uuid', $preFixed), $uuids);
  }

  public function scopeNoUuids($query, $uuids, $preFixed = true)
  {
    return $query->whereNotIn($this->preFixed('uuid', $preFixed), $uuids);
  }

  public function scopeUnActives($query, $preFixed = true)
  {
    return $query->where($this->preFixed('status', $preFixed), 0);
  }

  public function scopeName($query, $name, $preFixed = true)
  {
    return $query->where($this->preFixed('name', $preFixed), $name);
  }

  public function scopeNombre($query, $nombre, $preFixed = true)
  {
    return $query->where($this->preFixed('nombre', $preFixed), $nombre);
  }

  public function scopeValue($query, $value, $preFixed = true)
  {
    return $query->where($this->preFixed('value', $preFixed), $value);
  }

  public function scopeSlugs($query, $slug, $preFixed = true)
  {
    return $query->whereIn($this->preFixed('slug', $preFixed), $slug);
  }

  public function scopeSlug($query, $slug, $preFixed = true)
  {
    return $query->where($this->preFixed('slug', $preFixed), $slug);
  }

  public function scopeOfLevel($query, $level_id, $preFixed = true)
  {
    return $query->where($this->preFixed('level_id', $preFixed), $level_id);
  }

  public function scopeOfParent($query, $parent_id, $preFixed = true)
  {
    return $query->where($this->preFixed('parent_id', $preFixed), $parent_id);
  }

  public function scopeOfSublevel($query, $sublevel_id, $preFixed = true)
  {
    return $query->where($this->preFixed('sublevel_id', $preFixed), $sublevel_id);
  }

  public function scopeGeneralOwner($query, $userId)
  {
    return $query;
  }

  public function scopeParticularOwner($query, $userId)
  {
    return $query->where($this->preFixed('user_id', true), $userId);
  }

  public function scopeUpdatedBefore($query, $date, $preFixed = true)
  {
    return $query->where($this->preFixed('updated_at', $preFixed), '>', $date);
  }

  public function scopeUpdatedAfter($query, $date, $preFixed = true)
  {
    return $query->where($this->preFixed('updated_at', $preFixed), '<', $date);
  }

  public function scopeUpdatedBetween($query, $date, $preFixed = true)
  {
    return $query->updatedBefore($date)->updatedAfter($date);
  }

  public function scopeDistinctCount($query, $column, $preFixed = true)
  {
    if (!in_array($column, $this->getTableColumns()))
      return 0;

    return kageBunshinNoJutsu($query)->count(DB::raw("DISTINCT " . $this->preFixed($column, $preFixed)));
  }

  /**
   * Weir situation with sql server, where i cant access to other columns that are not definend
   * into de group by, so this scope add group by his self table key
   *
   *
   * */
  public function scopeGroupByKey($query, $preFixed = true)
  {
    return $query->groupBy($this->preFixed($this->getKeyName(), $preFixed));
  }

  public function scopeOrderByKey($query)
  {
    return $query->orderBy($this->getTable() . '.' . $this->getKeyName());
  }

  public function scopeSelectKey($query)
  {
    return $query->select($this->getTable() . '.' . $this->getKeyName());
  }

  public function scopeSelectMinKey($query)
  {
    return $query->min($this->getTable() . '.' . $this->getKeyName());
  }

  public function scopeDuplicity($query)
  {
    return $query->name($this->name);
  }

  public function scopeInvoker($query, $related)
  {
    $foreintColumn = \Str::snake(\Str::singular(($table = $this->getTable()))) . '_id';
    return $query->whereColumn($related::cvIam()->getTable() . ".$foreintColumn", "$table.id")
      ->limit(1);
  }

  public function scopeInvokePosfix($query, $related, $posfix)
  {
    $table = $this->getTable();
    return $query->invoker($related)->select("$table.$posfix as $table" . '_' . $posfix);
  }

  public function scopeInvokeSearch($query, $related)
  {
    return $query->invoker($related)->select("{$this->getTable()}.name as cv_search");
  }

  public function scopeExternalCvSearch($query, $related, $searchColumn,$alias=null)
  {
    $foreintColumn = str_replace('cv_search',$this->getKeyName(),$searchColumn);
    $alias         = $this->alias($alias);
    return $query
      ->from("{$this->getTable()} as $alias")
      ->whereColumn($related::cvIam()->getTable() . ".$foreintColumn", "$alias.id")
      ->limit(1)->selectCvSearch($alias);
  }

  public function scopeSelectCvSearch($query,$alias=null){
    $alias = $this->alias($alias);
    return $query->selectRaw(
      "CONCAT('scopeSelectCvSearch needs to be customized at ".get_class($this)." scopeSelectCvSearch ',$alias.id)");
  }

  public function scopeSolveSearches($query){
    $modelClass = get_class($this->cvIam());
    foreach($this->getCvSearches() as $searchColumn=>$relatedModel){
      $query->addSelect([$searchColumn => $relatedModel::externalCvSearch($modelClass,$searchColumn)]);
    }
    return $query->cvSearch();
  }

  public function scopeCvSearch($query,$alias=null){
    $alias      = $this->alias($alias);
    $table      = $this->cvIam()->getTable();
    $modelClass = get_class($this->cvIam());
    return $query->addSelect(['cv_search' => $modelClass::from("$table as $alias")
      ->selectCvSearch($alias)
      ->whereColumn("$alias.id", "$table.id")
      ->limit(1)]);
  }

  public function scopeCvOwner($query){
    $user = \CvResource::assignUser()->getUserModelCollectionInstance();

    if(!$user || $user->isRoot())
      return;

    $resource = cvCaseFixer('slug|plural',class_basename($this));
    $ownerPermissions = $user->permissions()->specialPermissions();

    if(kageBunshinNoJutsu($ownerPermissions)->slug("$resource.general-owner")->count())
      return $query->generalOwner($user->id);

    if($ownerPermissions->slug("$resource.particular-owner")->count())
      return $query->particularOwner($user->id);

    return $query->nullFilter();
  }

  public function scopeRelatedTo ($query,$relatedResource,$relatedKey) {
    $query->whereHas(cvCaseFixer('plural|camel',$relatedResource),function($query) use ($relatedKey) {
      $query->key($relatedKey);
    });
  }

  public function scopeByResource($query,$resource,$resourceKey){
    return $query->whereHas(cvCaseFixer('singluar|camel',$resource),function($query) use($resourceKey) {
      $query->key($resourceKey);
    });
  }

  public function scopeWithUser($query, $userId){
    return $query->where($this->preFixed('user_id'), $userId);
  }

  // [End Scopes]

  // [Others]
  public function getTable()
  {
    //TODO, fix schema inclusion
    return parent::getTable();
  }

  public function getSimpleTable()
  {
    return empty($schema = $this->schema) ? $this->getTable() : str_replace($schema . '.', '', $this->getTable());
  }

  public function getSchema()
  {
    return $this->schema;
  }

  //to be deprecated
  public function manyToManyToMany($firstLevelRelation, $secondLevelRelation, $secondLevelModel)
  {
    if (!is_callable(array($secondLevelModel, "nullFilter")))
      return null;

    if (!method_exists($this, $firstLevelRelation))
      return null;
    $firstLevelRelationInstace = $this->{$firstLevelRelation};
    if (!$firstLevelRelationInstace)
      return $secondLevelModel::nullFilter();

    $secondLevelRelationArray = [];
    foreach ($firstLevelRelationInstace as $firstLevelRelationItem) {
      if (method_exists($firstLevelRelationItem, $secondLevelRelation) && $firstLevelRelationItem->{$secondLevelRelation}()->count())
        $secondLevelRelationArray = array_unique(array_merge($secondLevelRelationArray, $firstLevelRelationItem->{$secondLevelRelation}()->get()->pluck("id")->toArray()));
    }

    return $secondLevelModel::ids($secondLevelRelationArray);
  }

  public function shadow()
  {
    $clonedInstace = new \Illuminate\Database\Eloquent\Builder(clone $this->getQuery());
    $clonedInstace->setModel($this->getModel());
    return $clonedInstace;
  }

  public function getConnectionName()
  {
    if (!is_null($this->connection))
      return $this->connection;
    return config('database.default');
  }

  public static function accesor()
  {
    return self::first();
  }

  //TODO sanatize this method
  public function fixColumnName($column)
  {
    if (!in_array($column, $this->getTableColumns()))
      return null;
    return $this->getTable() . '.' . $column;
  }

  public function getTableColumns()
  {
    return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getSimpleTable());
  }

  public function getSearchFieldColumn()
  {
    return 'search_field';
  }

  public function preFixed($column, $fixed = true)
  {
    if (!$fixed)
      return $column;

    return $this->getTable() . '.' . $column;
  }

  public function getKeyValue()
  {
    return $this->attributes[$this->getKeyName()] ?? null;
  }

  public function getConnectionTables()
  {
    return $this->getConnection()->getDoctrineSchemaManager()->listTableNames();
  }

  public function getModelMetaData()
  {
    return $this->modelMetaData;
  }

  public function setModelMetaData($modelMetaData = null)
  {
    $this->modelMetaData = $modelMetaData ?? null;
    return $this;
  }

  public function autoFixModelMetaData($mode = 0)
  {
    $tables                   = $this->getConnectionTables();
    $columns                  = $this->getTableColumns();
    $modelContent             = file_get_contents(cvClassFile($this));
    $defined                  = null;
    $declaredAndDefined       = null;
    $declared                 = null;
    $declaredAndDefinedPatern = '/(class\s*\w+\s*extends\s*.+{(?>\s|\S)*?protected\s+)(\$modelMetaData\s*=\s*)(\[(?>\s|\S)*?\]);((?>\s|\S)*)/';
    $declaredPatern           = '/(class\s*\w+\s*extends\s*.+{(?>\s|\S)*?protected\s+)(\$modelMetaData\s*;);((?>\s|\S)*)/';
    $undeclaredPatern         = '/class\s*\w+\s*extends\s*.+{\n/';
    preg_match($declaredAndDefinedPatern, $modelContent, $matches);
    $declaredAndDefined = $matches[3] ?? null;
    if (!$declaredAndDefined) {
      preg_match($declaredPatern, $modelContent, $matches);
      $declared = $matches[2] ?? null;
    }
    if ($declaredAndDefined)
      $defined = $this->modelMetaData;
    else
      $defined = [];
    $foreings = $this->autoFixModelForeings($tables, $columns);
    if (isset($defined['foreings'])) {
      foreach ($foreings as $key => $value) {
        if ($force)
          $defined['foreings'][$key] = $value;
        else{
          if (empty($defined['foreings'][$key]))
            $defined['foreings'][$key] = $value;
        }
      }
    } else
      $defined['foreings'] = $foreings;
    if ($declaredAndDefined) {
      $modelContent = preg_replace(
        $declaredAndDefinedPatern,
        '$1'."\$modelMetaData = ".varexport($defined, true).';$4',
        $modelContent
      );
    } else {
      if ($declared) {
        $modelContent = preg_replace(
          $declaredPatern,
          '$1'."\$modelMetaData = ".varexport($defined, true).';$3',
          $modelContent
        );
      } else {
        $modelContent = preg_replace(
          $undeclaredPatern,
          '$0'."\tprotected \$modelMetaData = ".varexport($defined, true).";\n",
          $modelContent
        );
      }
    }
    file_put_contents(cvClassFile($this), $modelContent);
    //file_put_contents($pathFile, str_replace($matches[1],$idColName, $fileToMod));
    //pdd($tables,$columns,cvClassFile($this));
  }
  public function autoFixModelForeings($tables, $columns)
  {
    $foreings = [];
    foreach ($tables as $table) {
      foreach ($columns as $column) {
        $singularTable = Str::singular($table);
        preg_match('/^(' . $singularTable . ')_(.+)/', $column, $matches);
        if (count($matches)) {
          $testModel     = 'App\Models\\' . Str::studly($singularTable);
          $relatedColumn = $matches[2] ?? null;
          if (!class_exists($testModel) || !in_array($relatedColumn, $testModel::cvIam()->getTableColumns()))
            continue;
          $foreings[$column] = [
            'relatedModel'  => $testModel,
            'relatedColumn' => $relatedColumn
          ];
        }
      }
    }
    return $foreings;
  }
  public function autoFixModelRelations($force = false)
  {
  }
  // [End Others]

  public function getCvSearches(){
    return $this->cvSearches??[];
  }

  public function setCvSearches($cvSearches=null){
    $this->cvSearches = $cvSearches??null;
    return $this;
  }

  public function alias($alias = null){
    if ($alias)
      return $alias;
    $randomWord = function ($length=10){
      return substr(str_shuffle("abcdefghijklmnopqrstuvwxyz"),0,$length);
    };
    return $randomWord();
  }

  public function fixUser($userKey = null){
    if(!$userKey){
      if(!$this->getUserModelCollectionInstance())
        return null;

      return $this->getUserModelCollectionInstance();
    }

    if(!$this->getUserModelCollectionInstance() || $this->getUserModelCollectionInstance()->getKeyValue() !== $userKey) {
      if(!($user = \App\Models\User::key($userKey)->first()))
        return null;

      return $user;
    }

    return $this->getUserModelCollectionInstance();
  }
}

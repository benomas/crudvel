<?php

namespace Crudvel\Models\Traits;

use stdClass;

trait CvBaseScopeTrait
{
// [Scopes]
  public function scopeInStatus($query, $status, $preFixed = true){
    if (is_array($status))
      $query->whereIn($this->preFixed('status', $preFixed), $status);
    else
      $query->whereIn($this->preFixed('status', $preFixed), [$status]);

    return $query;
  }

  public function scopeNotInStatus($query, $status, $preFixed = true){
    if (is_array($status))
      $query->whereNotIn($this->preFixed('status', $preFixed), $status);
    else
      $query->whereNotIn($this->preFixed('status', $preFixed), [$status]);

    return $query;
  }

  public function scopeStatus($query, $status, $preFixed = true){
    return $query->where($this->preFixed('status', $preFixed), $status);
  }

  public function scopeActives($query, $preFixed = true){
    if ($query->getModel()->hasPropertyActive)
      $query->where($this->preFixed('active', $preFixed), 1);

    return $query;
  }

  public function scopeNoFilters($query){
    return $query->whereRaw("1 = 1");
  }

  public function scopeNullFilter($query, $preFixed = true){
    return $query->whereNull($this->preFixed($this->getKeyName(), $preFixed));
  }

  public function scopeNotNull($query, $column, $preFixed = true){
    return $query->whereNotNull($this->preFixed($column, $preFixed));
  }

  public function scopeId($query, $key, $preFixed = true){
    return $query->where($this->preFixed($this->getKeyName(), $preFixed), $key);
  }

  public function scopeIds($query, $keys, $preFixed = true){
    return $query->whereIn($this->preFixed($this->getKeyName(), $preFixed), $keys);
  }

  public function scopeNoIds($query, $keys, $preFixed = true){
    return $query->whereNotIn($this->preFixed($this->getKeyName(), $preFixed), $keys);
  }

  public function scopeKey($query, $key, $preFixed = true){
    return $query->where($this->preFixed($this->getKeyName(), $preFixed), $key);
  }

  public function scopeNoKey($query, $key, $preFixed = true){
    return $query->where($this->preFixed($this->getKeyName(), $preFixed),'<>', $key);
  }

  public function scopeKeyLessThan($query, $key, $preFixed = true){
    return $query->where($this->preFixed($this->getKeyName(), $preFixed), '<', $key);
  }

  public function scopeKeys($query, $keys, $preFixed = true){
    return $query->whereIn($this->preFixed($this->getKeyName(), $preFixed), $keys);
  }

  public function scopeNoKeys($query, $keys, $preFixed = true){
    return $query->whereNotIn($this->preFixed($this->getKeyName(), $preFixed), $keys);
  }

  public function scopeUuid($query, $uuid, $preFixed = true){
    return $query->where($this->preFixed('uuid', $preFixed), $uuid);
  }

  public function scopeUuids($query, $uuids, $preFixed = true){
    return $query->whereIn($this->preFixed('uuid', $preFixed), $uuids);
  }

  public function scopeNoUuids($query, $uuids, $preFixed = true){
    return $query->whereNotIn($this->preFixed('uuid', $preFixed), $uuids);
  }

  public function scopeUnActives($query, $preFixed = true){
    return $query->where($this->preFixed('status', $preFixed), 0);
  }

  public function scopeName($query, $name, $preFixed = true){
    return $query->where($this->preFixed('name', $preFixed), $name);
  }

  public function scopeNombre($query, $nombre, $preFixed = true){
    return $query->where($this->preFixed('nombre', $preFixed), $nombre);
  }

  public function scopeValue($query, $value, $preFixed = true){
    return $query->where($this->preFixed('value', $preFixed), $value);
  }

  public function scopeSlugs($query, $slugs, $preFixed = true){
    return $query->whereIn($this->preFixed('slug', $preFixed), $slugs);
  }

  public function scopeSlug($query, $slug, $preFixed = true){
    return $query->where($this->preFixed('slug', $preFixed), $slug);
  }

  public function scopeCodeHooks($query, $codeHooks, $preFixed = true){
    return $query->whereIn($this->preFixed('code_hook', $preFixed), $codeHooks);
  }

  public function scopeCodeHook($query, $codeHook, $preFixed = true){
    return $query->where($this->preFixed('code_hook', $preFixed), $codeHook);
  }

  public function scopeNoCodeHook($query, $codeHook, $preFixed = true){
    return $query->where($this->preFixed('code_hook', $preFixed),'<>', $codeHook);
  }

  public function scopeOfLevel($query, $level_id, $preFixed = true){
    return $query->where($this->preFixed('level_id', $preFixed), $level_id);
  }

  public function scopeOfParent($query, $parent_id, $preFixed = true){
    return $query->where($this->preFixed('parent_id', $preFixed), $parent_id);
  }

  public function scopeOfSublevel($query, $sublevel_id, $preFixed = true){
    return $query->where($this->preFixed('sublevel_id', $preFixed), $sublevel_id);
  }

  public function scopeColumnStampBefore($query, $column, $date, $preFixed = true){
    return $query->where($this->preFixed($column, $preFixed), '>', $date);
  }

  public function scopeColumnStampAfter($query, $column, $date, $preFixed = true){
    return $query->where($this->preFixed($column, $preFixed), '<', $date);
  }

  public function scopeColumnStampBetween($query, $column, $date1, $date2, $preFixed = true){
    return $query->columnStampBefore($column,$date1)->columnStampAfter($column,$date2);
  }

  public function scopeUpdatedBefore($query, $date, $preFixed = true){
    return $query->columnStampBefore('updated_at',$date);
  }

  public function scopeUpdatedAfter($query, $date, $preFixed = true){
    return $query->columnStampAfter('updated_at',$date);
  }

  public function scopeUpdatedBetween($query, $date1, $date2, $preFixed = true){
    return $query->updatedBefore($date1)->updatedAfter($date2);
  }

  public function scopeCreatedBefore($query, $date, $preFixed = true){
    return $query->columnStampBefore('created_at',$date);
  }

  public function scopeCreatedAfter($query, $date, $preFixed = true){
    return $query->columnStampAfter('created_at',$date);
  }

  public function scopeCreatedBetween($query, $date1, $date2, $preFixed = true){
    return $query->createdBefore($date1)->createdAfter($date2);
  }

  public function scopeDistinctCount($query, $column, $preFixed = true){
    if (!in_array($column, $this->getTableColumns()))
      return 0;

    return kageBunshinNoJutsu($query)->count(\DB::raw("DISTINCT " . $this->preFixed($column, $preFixed)));
  }

  /**
   * Weir situation with sql server, where i cant access to other columns that are not definend
   * into de group by, so this scope add group by his self table key
   *
   *
   * */
  public function scopeGroupByKey($query, $preFixed = true){
    return $query->groupBy($this->preFixed($this->getKeyName(), $preFixed));
  }

  public function scopeOrderByKey($query){
    return $query->orderBy($this->getTable() . '.' . $this->getKeyName());
  }

  public function scopeOrderByKeyDesc($query){
    return $query->orderBy($this->getTable() . '.' . $this->getKeyName(),'DESC');
  }

  public function scopeOrderByKeyAsc($query){
    return $query->orderBy($this->getTable() . '.' . $this->getKeyName(),'ASC');
  }


  public function scopeSelectKey($query){
    return $query->select($this->getTable() . '.' . $this->getKeyName());
  }

  public function scopeSelectMinKey($query){
    return $query->min($this->getTable() . '.' . $this->getKeyName());
  }

  public function scopeDuplicity($query){
    return $query->name($this->name);
  }

  public function scopeInvoker($query, $related){
    $foreintColumn = \Str::snake(\Str::singular(($table = $this->getTable()))) . '_id';

    return $query->whereColumn($related::cvIam()->getTable().".$foreintColumn","$table.id")->limit(1);
  }

  public function scopeInvokePosfix($query, $related, $posfix){
    $table = $this->getTable();

    return $query->invoker($related)->select("$table.$posfix as $table" . '_' . $posfix);
  }

  public function scopeInvokeSearch($query, $related){
    return $query->invoker($related)->select("{$this->getTable()}.name as cv_search");
  }

  public function scopeExternalCvSearch($query, $searchColumn,$alias=null,$localPrefix = null){
    $localPrefix   = $localPrefix??'';
    $foreintColumn = str_replace('cv_search',$this->getKeyName(),$searchColumn);
    $alias         = $this->alias($alias);

    return $query
      ->from("{$this->getTable()} as $alias")
      ->whereColumn("{$localPrefix}{$foreintColumn}", "$alias.{$this->getKeyName()}")
      ->limit(1)->selectCvSearch($alias);
  }

  public function scopeSelectCvSearch($query,$alias=null){
    $alias = $this->alias($alias);
    $keyName = static::cvIam()->getKeyName();

    return $query->selectRaw("CONCAT('scopeSelectCvSearch needs to be customized at ".get_class($this)." scopeSelectCvSearch ',{$alias}.{$keyName})");
  }

  public function scopeSolveSearches($query){
    //$modelClass = get_class($this->cvIam());
    foreach($this->getCvSearches() as $searchColumn=>$relatedModel){
      $query->addSelect([$searchColumn => $relatedModel::withoutGlobalScopes()->externalCvSearch($searchColumn)]);
    }

    return $query->cvSearch();
  }

  public function dinamicFrom (){
    return static::cvIam()->getTable();
  }

  public function selfAddSelectPrebuilder($alias=null){
    $table       = static::cvIam()->getTable();
    $dinamicFrom = static::cvIam()->dinamicFrom();
    $modelClass  = get_class($this->cvIam());

    $selfBuilder = new stdClass;
    $selfBuilder->alias = $this->alias($alias);

    $selfBuilder->queryBuilder = $modelClass::withoutGlobalScopes()->fromRaw("$dinamicFrom as {$selfBuilder->alias}")
      ->whereColumn("{$selfBuilder->alias}.id", "$table.id")
      ->limit(1);

    return $selfBuilder;
  }

  public function scopeCvSearch($query,$alias=null){
    $selfAddSelectPrebuilder = $this->selfAddSelectPrebuilder($alias);

    return $query->addSelect([
      'cv_search' => $selfAddSelectPrebuilder->queryBuilder->selectCvSearch($selfAddSelectPrebuilder->alias)
    ]);
  }

  public function scopeDefParticularOwner($query,$user,$relation){
    try{
      return $query->whereHas($relation,function($query) use($user){
        $query->cvOwner($user);
      });
    }catch(\Exception $e){
      return $query->whereHasMorph($relation,'*',function($query) use($user){
        $query->cvOwner($user);
      });
    }
  }

  public function scopeMorphedDefParticularOwner($query,$user,$relation){
    try{
      return $query->whereHasMorph($relation,'*',function($query) use($user){
        $query->cvOwner($user);
        /*
        $generalOnwerSlug = cvCaseFixer('slug|plural',$query->getModel()->getTable()).'.general-owner';

        if($user->specialPermissions()->slug($generalOnwerSlug)->count())
          $query->generalOwner($user);
        else
          $query->particularOwner($user);
        */
      });
    }catch(\Exception $e){
      return $query->whereHas($relation,function($query) use($user){
        $query->cvOwner($user);
        /*
        $generalOnwerSlug = cvCaseFixer('slug|plural',$query->getModel()->getTable()).'.general-owner';

        if($user->specialPermissions()->slug($generalOnwerSlug)->count())
          $query->generalOwner($user);
        else
          $query->particularOwner($user);
        */
      });
    }
  }

  public function scopeGeneralOwner($query, $user=null){
    return $query;
  }

  public function scopeParticularOwner($query, $user=null){
    return $query->key($user->id);
  }

  public function scopeCvOwner($query, $user=null){
    if(!($user = $this->fixUser($user))){
      return $query->noFilters();
    }

    if($user->isRoot()){
      return $query;
    }

    $resource = cvCaseFixer('slug|plural',class_basename($this));

    $ownerPermissions = $user->permissions()->disableRestricction()->specialPermissions();

    if(kageBunshinNoJutsu($ownerPermissions)->slug("$resource.general-owner")->count()){
      return $query->generalOwner($user);
    }

    if($ownerPermissions->slug("$resource.particular-owner")->count()){
      return $query->particularOwner($user);
    }

    return $query->nullFilter();
  }

  public function scopeRelatedTo ($query,$relatedResource,$relatedKey) {
    $related           = cvCaseFixer('plural|camel',$relatedResource);
    $selftTable        = $this->getTable();
    $relatedKeyName    = cvCaseFixer('singular|snake',$related).'_id';
    $selftTableKeyName = cvCaseFixer('singular|snake',$selftTable).'_id';
    $reference = kageBunshinNoJutsu($query)->first();

    if(!$reference)
      return $query;

    $intermediateTable =$reference->{$related}()->getTable();

    return $query->whereHas($related,function($query) use ($relatedKey) {
      $query->key($relatedKey);
    })->addSelect([
      'related_order'=>\DB::table("{$intermediateTable} as ro")
        ->whereColumn("ro.{$selftTableKeyName}","{$selftTable}.id")
        ->where("ro.{$relatedKeyName}",$relatedKey)
        ->selectRaw("(
          SELECT
            COUNT(ro2.id) + 1
          FROM
              {$intermediateTable} as ro2
          WHERE
            ro2.{$relatedKeyName} = {$relatedKey} AND ro2.id < ro.id
          ) as relative_order")
        ->limit(1)
    ]);
  }

  public function scopeOwnedBy ($query,$ownerResource,$ownerKey) {
    $ownedRelated      = cvCaseFixer('plural|camel',"cvOwner {$ownerResource}");
    $ownedTable        = $this->getTable();
    $ownerKeyName      = 'owner_id';
    $ownedTableKeyName = 'owned_id';

    $intermediateTable = cvCaseFixer('singular|snake',$ownedTable)
      ."_owned_by_"
      .cvCaseFixer('singular|snake',$ownerResource);

    return $query->whereHas($ownedRelated,function($query) use ($ownerKey) {
      $query->key($ownerKey);
    })->addSelect([
      'related_order'=>\DB::table("$intermediateTable")
        ->whereColumn("{$intermediateTable}.{$ownedTableKeyName}","{$ownedTable}.id")
        ->where("{$intermediateTable}.{$ownerKeyName}",$ownerKey)
        ->selectRaw("(
          SELECT
            COUNT({$intermediateTable}2.id) + 1
          FROM
            {$intermediateTable} as {$intermediateTable}2
          WHERE
            {$intermediateTable}2.{$ownerKeyName} = {$ownerKey} AND {$intermediateTable}2.id < {$intermediateTable}.id
          ) as relative_order")
        ->limit(1)
    ]);
  }

  public function scopeOwnerOf ($query,$ownedResource,$owneddKey) {
    $ownedRelated      = cvCaseFixer('plural|camel',"cvOwned {$ownedResource}");
    $ownerTable        = $this->getTable();
    $ownedKeyName      = 'owned_id';
    $ownerTableKeyName = 'owner_id';

    $intermediateTable = cvCaseFixer('singular|snake',$ownedResource)
      ."_owned_by_"
      .cvCaseFixer('singular|snake',$ownerTable);

    return $query->whereHas($ownedRelated,function($query) use ($owneddKey) {
      $query->key($owneddKey);
    })->addSelect([
      'related_order'=>\DB::table("$intermediateTable")
        ->whereColumn("{$intermediateTable}.{$ownerTableKeyName}","{$ownerTable}.id")
        ->where("{$intermediateTable}.{$ownedKeyName}",$owneddKey)
        ->selectRaw("(
          SELECT
            COUNT({$intermediateTable}2.id) + 1
          FROM
            {$intermediateTable} as ro2
          WHERE
            {$intermediateTable}2.{$ownedKeyName} = {$owneddKey} AND {$intermediateTable}2.id < {$intermediateTable}.id
          ) as relative_order")
        ->limit(1)
    ]);
  }

  public function scopeByResource($query,$resource,$resourceKey){
    return $query->whereHas(cvCaseFixer('singluar|camel',$resource),function($query) use($resourceKey) {
      $query->key($resourceKey);
    });
  }

  public function scopeWithUser($query, $userId){
    return $query->where($this->preFixed('user_id'), $userId);
  }

  public function scopeColumnCombination($query, $columns){
    foreach($columns as $column=>$value){
      if($value)
        $query->where($this->preFixed($column), $value);
    }

    return $query;
  }

  public function scopeCvResourceFilter($query, $routeParam, $preFixed = true){
    return $query->key($routeParam,$preFixed);
  }

  public function scopeDisableRestricction($query){
    return $query->withoutGlobalScope(\Crudvel\Scopes\PermissionsScope::class);
  }

  public function scopeCreatedUntilYear($query,$year){
    return $query->whereYear('created_at','<=',$year);
  }

  public function scopeCreatedUntilMonth($query,$month){
    return $query->whereMonth('created_at','<=',$month);
  }
// [End Scopes]
}

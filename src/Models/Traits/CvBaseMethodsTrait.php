<?php

namespace Crudvel\Models\Traits;

trait CvBaseMethodsTrait
{
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

    $firstLevelRelationInstace = $this->{$firstLevelRelation}()->get();

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

  public function getPrefixedTableColumns()
  {
    $tableName = $this->getTable();

    return array_map(function($column) use($tableName){
      return "$tableName.$column";
    },$this->getTableColumns());
  }

  public function recoverDefaultBinding()
  {
    return array_map(function($column) {
      return "$column AS $column";
    },$this->getPrefixedTableColumns());
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
    $userInstace = null;
    $userInstace = \CvResource::assignUser()->getUserModelCollectionInstance();

    if(!$userKey){
      if(!$userInstace)
        return null;

      return $userInstace;
    }

    if(!$userInstace || $userInstace->getKeyValue() !== $userKey)
      if(!($userInstace = \App\Models\User::withoutGlobalScope(\Crudvel\Scopes\PermissionsScope::class)->key($userKey)->first()))
        return null;

    return $userInstace;
  }

  public function resourceCatalogs($userId =  null){
    if(!($user = $this->fixUser($userId)))
      return cvResourcesCatalog();

    $resourcesCatalog = [];

    foreach(cvResourcesCatalog() AS $resource){
      if(!$this->actionAccess($resource['value'].'.update') || !$this->actionAccess($resource['value'].'.create') || !$this->actionAccess($resource['value'].'.index'))
        continue;

      $resourcesCatalog[] = $resource;
    }

    return $resourcesCatalog;
  }

  protected static function boot(){
    parent::boot();

    if(!($GLOBALS['disablePermissionsScope']??false))
      static::addGlobalScope(new \Crudvel\Scopes\PermissionsScope);

    self::updated(function($model){
      $GLOBALS['disablePermissionsScope'] = true;

      if(!($relatedFiles = $model->relatedFiles())){
        $GLOBALS['disablePermissionsScope'] = false;
        return;
      }

      $relatedFiles = $relatedFiles->solveSearches()->get();

      foreach($relatedFiles as $relatedFile){
        $catFile = $relatedFile->catFile;
        $catFile->resource = $relatedFile->catFile->resource;
        $catFile->save();
        $relatedFile->cat_file_id = $relatedFile->catFile->id;
        $relatedFile->resource_id = $model->id;
        $relatedFile->save();
      }

      $GLOBALS['disablePermissionsScope'] = false;
    });
  }

  public function getSingularLang(){
    return $this->getModelLang()['row_label'] ?? cvCaseFixer('singular|slug',class_basename(get_class($this)));
  }

  public function getPluralLang(){
    return $this->getModelLang()['rows_label'] ?? cvCaseFixer('plural|slug',class_basename(get_class($this)));
  }

  public function safeField($alias,$field,$leftSeparator='',$rightSeparator=''){
    return "IF($alias.$field IS NOT NULL,CONCAT('$leftSeparator',$alias.$field,'$rightSeparator'),'')";
  }

  public function textIdentifierConcat($alias){
    return $this->safeField($alias,'text_identifier','(',')');
    //return "IF($alias.text_identifier IS NOT NULL,CONCAT('(',$alias.text_identifier,')'),'')";
  }
// [End Others]
}

<?php namespace Crudvel\Database\Migrations;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class BaseMigration extends Migration
{
  protected $schema;
  protected $mainTable;
  protected $autoForeingModels;
  public $blueprintTable=null;
  public function __construct(){
    if(empty($this->mainTable)){
      preg_match('/(?:reate|lter)(.+?)Table/',get_class($this),$matches);
      $this->mainTable = strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $matches[1]));
    }
  }

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    if (!Schema::hasTable($this->getTable())) {
      Schema::disableForeignKeyConstraints();
      Schema::create($this->getSchemaTable(), function (Blueprint $blueprintTable) {
        $this->setBlueprintTable($blueprintTable);
        $this->catalog($blueprintTable);
      });
      Schema::enableForeignKeyConstraints();
    }

    return $this;
  }

  public function makeStylesColumns($blueprintTable){
    $blueprintTable = $this->getSetBlueprintTable($blueprintTable);
    $blueprintTable->string('icon')->nullable()->default("");
    /*
    $blueprintTable->string('default_style')->nullable()->default("");
    $blueprintTable->string('list_default_style')->nullable()->default("");
    $blueprintTable->string('list_completed_style')->nullable()->default("");
    $blueprintTable->string('list_hover_style')->nullable()->default("");
    */
    return $this;
  }

  public function userStamps($blueprintTable){
    $blueprintTable = $this->getSetBlueprintTable($blueprintTable);
    $blueprintTable->bigInteger('created_by')->unsigned()->nullable();
    $blueprintTable->bigInteger('updated_by')->unsigned()->nullable();
    $blueprintTable->index("created_by");
    $blueprintTable->index("updated_by");

    return $this;
  }

  public function userStampsDown($blueprintTable){
    $blueprintTable = $this->getSetBlueprintTable($blueprintTable);
    $blueprintTable->dropIndex('created_by');
    $blueprintTable->dropIndex('updated_by');
    $blueprintTable->dropColumn('created_by');
    $blueprintTable->dropColumn('updated_by');

    return $this;
  }

  public function catalog($blueprintTable){
    $blueprintTable = $this->getSetBlueprintTable($blueprintTable);
    $blueprintTable->bigIncrements('id');
    $blueprintTable->string('name');
    $blueprintTable->text('description')->nullable();
    $blueprintTable->string('code_hook',100)->nullable();
    $this->defaultColumns($blueprintTable);
    $blueprintTable->index("name");

    return $this;
  }

  public function defaultColumns($blueprintTable){
    $blueprintTable = $this->getSetBlueprintTable($blueprintTable);
    $blueprintTable->boolean('active')->default(true);
    $blueprintTable->timestamps();
    $this->userStamps($blueprintTable);
    $blueprintTable->index("active");

    return $this;
  }

  public function defaultRelationColumns($blueprintTable){
    $blueprintTable = $this->getSetBlueprintTable($blueprintTable);
    $blueprintTable->timestamp('created_at')->nullable();
    $blueprintTable->bigInteger('created_by')->unsigned()->nullable();
    $blueprintTable->index("created_by");

    return $this;
  }


  public function down()
  {
    if(Schema::hasTable($this->getTable())){
      Schema::disableForeignKeyConstraints();
      Schema::drop($this->getSchemaTable());
      Schema::enableForeignKeyConstraints();
    }

    return $this;
  }

  public function change($callBack){
    if(!is_callable($callBack))
      return false;

    Schema::table($this->getSchemaTable(), function($blueprintTable) use($callBack){
      Schema::disableForeignKeyConstraints();
      $callBack($blueprintTable);
      Schema::enableForeignKeyConstraints();
    });

    return $this;
  }

  public function getTable(){
    return $this->mainTable;
  }

  public function getSchema(){
    return $this->schema;
  }

  public function getSchemaTable(){
    if(empty($this->schema))
      return $this->mainTable;

    return $this->schema.'.'.$this->mainTable;
  }

  public function sqlStatementUp($driver=null){
    if(!$driver){
      $default = config('database.default');
      $driver  = config("database.connections.$default.driver");
    }

    if(!$driver)
      return customLog('driver connection undefined');

    try{
      $this->{$driver.'StatementUp'}();
    }
    catch(\Exception $e){
      customLog("error when try to create ".$this->mainTable." view");
    }

    return $this;
  }

  public function sqlStatementDown($driver=null){
    if(!$driver){
      $default = config('database.default');
      $driver  = config("database.connections.$default.driver");
    }

    if(!$driver)
      return customLog('driver connection undefined');

    try{
      $this->{$driver.'StatementDown'}();
    }
    catch(\Exception $e){
      customLog("error when try to drop ".$this->mainTable." view");
    }

    return $this;
  }

  public function makeForeing($prefix,$foreingTable,$columnKey='id',$blueprintTable=null){
    $blueprintTable = $this->getSetBlueprintTable($blueprintTable);

    if(!config('cv.enable_foreings'))
      return $this;

    $blueprintTable->foreign($prefix.'_'.$columnKey)->references($columnKey)->on($foreingTable)->onUpdate('cascade')->onDelete('cascade');

    return $this;
  }

  public function makeSafeForeing($prefix,$foreingTable,$columnKey='id',$blueprintTable=null){
    $blueprintTable = $this->getSetBlueprintTable($blueprintTable);

    if(!config('cv.enable_foreings'))
      return $this;

    $blueprintTable->foreign($prefix.'_'.$columnKey)->references($columnKey)->on($foreingTable);

    return $this;
  }

  public function setBlueprintTable($blueprintTable=null){
    $this->blueprintTable=$blueprintTable;
  }

  public function getBlueprintTable(){
    return $this->blueprintTable;
  }

  public function getSetBlueprintTable($blueprintTable=null){
    if($blueprintTable)
      $this->blueprintTable = $blueprintTable;

    return $this->blueprintTable;
  }

  public function automaticStandarForeings(){
    $this->autoForeingModels = $this->autoForeingModels ?? [];
    Schema::disableForeignKeyConstraints();

    foreach($this->autoForeingModels as $autoForeingModel=>$foreingsConfig){
      $table = $autoForeingModel::cvIam()->getTable();
      foreach($foreingsConfig AS $foreingColumn=>$configForeing){
        try{
          Schema::table($table, function($table) use($foreingColumn,$configForeing){
            $foreingModel  = $configForeing['model']??null;
            if(!class_exists($foreingModel))
              return true;
            $referencedColumn = $configForeing['referenced']??$configForeing['model']::cvIam()->getKeyName();
            $referencedTable = $configForeing['model']::cvIam()->getTable();
            $table->foreign($foreingColumn)
            ->references($referencedColumn)
            ->on($referencedTable)
            ->onUpdate('cascade')
            ->onDelete('cascade');
          });
        }
        catch(\Exception $e){
          customLog("error when try to alter $table table");
        }
      }
    }
    Schema::enableForeignKeyConstraints();

    return $this;
  }

  public function autoForeing($table, $foreingName=null){
    return (new \Crudvel\Database\Migrations\ForeingMaker($table,$foreingName))->setMigration($this);
  }
}

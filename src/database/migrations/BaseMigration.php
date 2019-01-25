<?php namespace Crudvel\Database\Migrations;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class BaseMigration extends Migration
{
  protected $schema;
  protected $enableForeings=true;
  protected $mainTable;
  protected $blueprintTable=null;
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
      disableForeignKeyConstraints();
      Schema::create($this->getSchemaTable(), function (Blueprint $table) {
        $this->catalog($table);
      });
      enableForeignKeyConstraints();
    }
  }

  public function makeStylesColumns($blueprintTable=null){
    $blueprintTable = $this->getSetBlueprintTable($blueprintTable);
    $blueprintTable->string('icon')->nullable()->default("");
    /*
    $blueprintTable->string('default_style')->nullable()->default("");
    $blueprintTable->string('list_default_style')->nullable()->default("");
    $blueprintTable->string('list_completed_style')->nullable()->default("");
    $blueprintTable->string('list_hover_style')->nullable()->default("");
    */
  }

  public function userStamps($blueprintTable=null){
    $blueprintTable = $this->getSetBlueprintTable($blueprintTable);
    $blueprintTable->bigInteger('created_by')->unsigned()->nullable();
    $blueprintTable->bigInteger('updated_by')->unsigned()->nullable();
    $blueprintTable->index("created_by");
    $blueprintTable->index("updated_by");
  }

  public function userStampsDown($blueprintTable=null){
    $blueprintTable = $this->getSetBlueprintTable($blueprintTable);
    $blueprintTable->dropIndex('created_by');
    $blueprintTable->dropIndex('updated_by');
    $blueprintTable->dropColumn('created_by');
    $blueprintTable->dropColumn('updated_by');
  }

  public function catalog($blueprintTable=null){
    $blueprintTable = $this->getSetBlueprintTable($blueprintTable);
    $blueprintTable->engine = 'InnoDB';
    $blueprintTable->increments('id');
    $blueprintTable->string('name');
    $blueprintTable->text('description')->nullable();
    $this->defaultColumns($blueprintTable);
    $blueprintTable->index("name");
  }

  public function defaultColumns($blueprintTable=null){
    $blueprintTable = $this->getSetBlueprintTable($blueprintTable);
    $blueprintTable->boolean('active')->default(true);
    $blueprintTable->timestamps();
    $this->userStamps($blueprintTable);
    $blueprintTable->index("active");
  }

  public function down()
  {
    if(Schema::hasTable($this->getTable())){
      disableForeignKeyConstraints();
      Schema::drop($this->getSchemaTable());
      enableForeignKeyConstraints();
    }
  }

  public function change($callBack){
    if(!is_callable($callBack))
      return false;
    Schema::table($this->getSchemaTable(), function($blueprintTable) use($callBack){
      disableForeignKeyConstraints();
      $callBack($blueprintTable);
      enableForeignKeyConstraints();
    });
  }

  public function getTable(){
    return $this->mainTable;
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
  }

  public function makeForeing($prefix,$foreingTable,$columnKey='id',$blueprintTable=null){
    $blueprintTable = $this->getSetBlueprintTable($blueprintTable);
    if(!$this->enableForeings)
      return ;
    $blueprintTable->foreign($prefix.'_'.$columnKey)->references($columnKey)
    ->on($foreingTable)->onUpdate('cascade')->onDelete('cascade');
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
}

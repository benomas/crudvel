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

  public function makeStylesColumns($table){
    $table->string('icon')->nullable()->default("");
    /*
    $table->string('default_style')->nullable()->default("");
    $table->string('list_default_style')->nullable()->default("");
    $table->string('list_completed_style')->nullable()->default("");
    $table->string('list_hover_style')->nullable()->default("");
    */
  }

  public function userStamps($table){
    $table->bigInteger('created_by')->unsigned()->nullable();
    $table->bigInteger('updated_by')->unsigned()->nullable();
    $table->index("created_by");
    $table->index("updated_by");
  }

  public function userStampsDown($table){
    $table->dropIndex('created_by');
    $table->dropIndex('updated_by');
    $table->dropColumn('created_by');
    $table->dropColumn('updated_by');
  }

  public function catalog($table){
    $table->engine = 'InnoDB';
    $table->increments('id');
    $table->string('name');
    $table->text('description')->nullable();
    $this->defaultColumns($table);
    $table->index("name");
  }

  public function defaultColumns($table){
    $table->boolean('active')->default(true);
    $table->timestamps();
    $this->userStamps($table);
    $table->index("active");
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
    Schema::table($this->getSchemaTable(), function($table) use($callBack){
      disableForeignKeyConstraints();
      $callBack($table);
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

  public function makeForeing($table,$prefix,$foreingTable,$columnKey='id'){
    if(!$this->enableForeings)
      return ;
    $table->foreign($prefix.'_'.$columnKey)->references($columnKey)
    ->on($foreingTable)->onUpdate('cascade')->onDelete('cascade');
  }
}

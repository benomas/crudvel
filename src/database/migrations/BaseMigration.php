<?php
namespace Crudvel\Database\Migrations;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class BaseMigration extends Migration
{
    protected $mainTable;
    public function __construct(){
        preg_match('/(?:reate|lter)(.+?)Table/',get_class($this),$matches);
        $this->mainTable = strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $matches[1]));
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable($this->mainTable)) {
            Schema::create($this->mainTable, function (Blueprint $table) {
                $this->catalog($table);
            });
        }
    }

    public function down()
    {
        if(Schema::hasTable($this->mainTable)){            
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            Schema::drop($this->mainTable);
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
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

    public function change($callBack){
        if(!is_callable($callBack))
            return false;
        Schema::table($this->mainTable, function($table) use($callBack){
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            $callBack($table);
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        });
    }
}

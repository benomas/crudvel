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
                $this->defaultCatalog($table);
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
        $table->bigInteger('created_by')->unsigned();
        $table->bigInteger('updated_by')->unsigned();
        $table->index("created_by");
        $table->index("updated_by");
    }

    public function defaultCatalog($table){
        $table->engine = 'InnoDB';
        $table->increments('id');
        $table->string('name');
        $table->text('description');
        $table->boolean('active')->default(true);
        $table->timestamps();
        $this->userStamps($table);
        $table->index("name");
        $table->index("active");
    }
}

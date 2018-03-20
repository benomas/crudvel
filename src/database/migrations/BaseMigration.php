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
        $this->defaultCatalog();
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
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        $table->bigInteger('created_by')->unsigned();
        $table->bigInteger('updated_by')->unsigned();
        $table->foreign('created_by')
            ->references('id')
            ->on('users')
            ->onUpdate('cascade')
            ->onDelete('cascade');
        $table->foreign('updated_by')
            ->references('id')
            ->on('users')
            ->onUpdate('cascade')
            ->onDelete('cascade');
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    public function defaultCatalog(){
        if (!Schema::hasTable($this->mainTable)) {
            Schema::create($this->mainTable, function (Blueprint $table) {
                $table->increments('id');
                $table->string('name');
                $table->text('description');
                $table->boolean('active')->default(true);
                $table->timestamps();
                $this->userStamps($table);
            });
        }
    }
}

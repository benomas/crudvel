<?php
namespace Crudvel\Database\Migrations;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class BaseMigration extends Migration
{
    protected $mainTable;
    public function __construct(){
        preg_match('/(?:reate|lter)(.+?)Table/',get_class($this),$matches);
        $this->mainTable = strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $matches[1]));
    }

    public function up(){}

    public function down()
    {
        if(Schema::hasTable($this->mainTable)){            
            Schema::disableForeignKeyConstraints();
            Schema::drop($this->mainTable);
            Schema::enableForeignKeyConstraints();
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
}

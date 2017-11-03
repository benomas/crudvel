<?php
namespace Crudvel\Migrations;

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
        Schema::drop($this->mainTable);
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

<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
class CreateDomineeringRoleDominedRoleTable extends \Crudvel\Database\Migrations\BaseMigration{
  public function up(){
    if(!Schema::hasTable($this->mainTable)){
      Schema::create($this->mainTable, function (Blueprint $table) {
        Schema::disableForeignKeyConstraints();
        $table->bigIncrements('id');
        $table->bigInteger('domineering_role_id')->unsigned();
        $table->bigInteger('domined_role_id')->unsigned();
        Schema::enableForeignKeyConstraints();
      });
    }
  }
}

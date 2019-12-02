<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Crudvel\Customs\Database\Migrations\BaseMigration;

class CreatePermissionRoleTable extends BaseMigration
{
  public function up()
  {
    if(!Schema::hasTable($this->mainTable) && Schema::hasTable("roles") && Schema::hasTable("permissions")){
      Schema::create($this->mainTable, function (Blueprint $table) {
        $table->engine = 'InnoDB';

        $table->bigIncrements('id');
        $table->bigInteger('permission_id')->unsigned();
        $table->bigInteger('role_id')->unsigned();
        $table->timestamps();
        $table->foreign('permission_id')
          ->references('id')
          ->on('permissions')
          ->onUpdate('cascade')
          ->onDelete('cascade');
        $table->foreign('role_id')
          ->references('id')
          ->on('roles')
          ->onUpdate('cascade')
          ->onDelete('cascade');
      });
    }
  }
}

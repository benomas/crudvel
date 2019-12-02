<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Crudvel\Database\Migrations\BaseMigration;

class CreatePermissionRoleTablerightdatetag extends BaseMigration
{
  public function up()
  {
    if(!Schema::hasTable($this->mainTable) && Schema::hasTable("roles") && Schema::hasTable("permissions")){
      Schema::create($this->mainTable, function (Blueprint $table) {
        disableForeignKeyConstraints();
        $table->bigIncrements('id');
        $table->bigInteger('permission_id')->unsigned();
        $table->bigInteger('role_id')->unsigned();
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
        $table->engine = 'InnoDB';
        enableForeignKeyConstraints();
      });
    }
  }
}

<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Crudvel\Database\Migrations\BaseMigration;

class CreateRoleRoleTablerightdatetag extends BaseMigration
{
  public function up()
  {
  if(!Schema::hasTable($this->mainTable))
  {
    Schema::create($this->mainTable, function (Blueprint $table) {
    disableForeignKeyConstraints();
      $table->increments('id');
      $table->integer('domineering_role_id')->unsigned();
      $table->integer('domined_role_id')->unsigned();
      $table->engine = 'InnoDB';
      $table->foreign('domineering_role_id')
      ->references('id')
      ->on('roles')
      ->onUpdate('cascade')
      ->onDelete('cascade');
      $table->foreign('domined_role_id')
      ->references('id')
      ->on('roles')
      ->onUpdate('cascade')
      ->onDelete('cascade');
    enableForeignKeyConstraints();
    });
  }
  }
}

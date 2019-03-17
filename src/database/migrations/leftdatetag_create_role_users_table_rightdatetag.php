<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Crudvel\Database\Migrations\BaseMigration;

class CreateRoleUsersTablerightdatetag extends BaseMigration
{
  public function up()
  {
    if(!Schema::hasTable($this->mainTable) && Schema::hasTable("roles") && Schema::hasTable("users")){
      Schema::create($this->mainTable, function (Blueprint $table) {
        $table->increments('id');
        $table->integer('role_id')->unsigned();
        $table->integer('user_id')->unsigned();
        $table->timestamps();
        $table->foreign('user_id')
          ->references('id')
          ->on('users')
          ->onUpdate('cascade')
          ->onDelete('cascade');
        $table->foreign('role_id')
          ->references('id')
          ->on('roles')
          ->onUpdate('cascade')
          ->onDelete('cascade');
        $table->engine = 'InnoDB';
      });
    }
  }
}

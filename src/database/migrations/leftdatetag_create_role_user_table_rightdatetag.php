<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Crudvel\Database\Migrations\BaseMigration;

class CreateRoleUserTablerightdatetag extends BaseMigration
{
    public function up()
    {
        if(!Schema::hasTable($this->mainTable) && Schema::hasTable("roles") && Schema::hasTable("users")){
            Schema::create($this->mainTable, function (Blueprint $table) {
                DB::statement('SET FOREIGN_KEY_CHECKS=0;');
                $table->increments('id');
                $table->integer('role_id')->unsigned();
                $table->integer('user_id')->unsigned();
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
                DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            });
        }
    }
}
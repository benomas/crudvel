<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Crudvel\Database\Migrations\BaseMigration;

class AlterRoleUsersTablerightdatetag extends BaseMigration
{
    public function up()
    {
        if(Schema::hasTable($this->mainTable) && Schema::hasTable("roles") && Schema::hasTable("users")){
            Schema::table($this->mainTable, function($table){
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
            });
        }
    }

    public function down()
    {
        if(Schema::hasTable($this->mainTable) && Schema::hasTable("roles") && Schema::hasTable("users")){
            Schema::disableForeignKeyConstraints();
            Schema::table($this->mainTable, function($table){
                $table->dropForeign(['user_id','role_id']);
            });
            Schema::enableForeignKeyConstraints();
        }
    }
}

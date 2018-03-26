<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Crudvel\Database\Migrations\BaseMigration;
class CreateRolesTablerightdatetag extends BaseMigration
{
    public function up()
    {
        if(!Schema::hasTable($this->mainTable))
        {
            Schema::create('roles', function (Blueprint $table) {
                DB::statement('SET FOREIGN_KEY_CHECKS=0;');
                $table->increments('id');
                $table->string('slug');
                $table->string('name');
                $table->string('description');
                $table->boolean('active')->default(true);
                $table->timestamps();
                $this->userStamps($table);
                $table->engine = 'InnoDB';
                $table->unique('slug');
                DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            });
        }
    }
}
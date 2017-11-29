<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Crudvel\Database\Migrations\BaseMigration;

class CreateRolesTable extends BaseMigration
{
    public function up()
    {
        if(!Schema::hasTable($this->mainTable))
        {
            Schema::create('roles', function (Blueprint $table) {
                $table->increments('id');
                $table->string('slug');
                $table->string('name');
                $table->timestamps();
                $table->engine = 'InnoDB';
                $table->unique('slug');
            });
        }
    }
}

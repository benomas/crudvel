<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Customs\Crudvel\Database\Migrations\BaseMigration;

class CreateRolesTable extends BaseMigration
{
  public function up()
  {
    if(!Schema::hasTable($this->mainTable))
    {
      Schema::create('roles', function (Blueprint $table) {
        $table->engine = 'InnoDB';

        $table->bigIncrements('id');
        $table->string('slug');
        $table->string('name');
        $table->string('description');
        $table->boolean('active')->default(true);
        $table->timestamps();
        $table->bigInteger('created_by')->nullable();
        $table->bigInteger('updated_by')->nullable();
        $table->engine = 'InnoDB';
        $table->unique('slug');

        $table->index("name");
        $table->index("description");
        $table->index("active");
      });
    }
  }
}

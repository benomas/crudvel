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
        disableForeignKeyConstraints();
        $table->increments('id');
        $table->string('slug');
        $table->string('name');
        $table->string('description')->nullable();
        $table->boolean('active')->default(true);
        $table->timestamps();
        $this->userStamps($table);
        $table->engine = 'InnoDB';
        $table->unique('slug');
        enableForeignKeyConstraints();
      });
    }
  }
}

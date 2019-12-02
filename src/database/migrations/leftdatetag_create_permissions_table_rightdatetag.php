<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Crudvel\Database\Migrations\BaseMigration;

class CreatePermissionsTablerightdatetag extends BaseMigration
{
  public function up()
  {
    if(!Schema::hasTable($this->mainTable)){
      Schema::create($this->mainTable, function (Blueprint $table) {
        disableForeignKeyConstraints();
        $table->increments('id');
        $table->bigInteger('cat_permission_type_id')->unsigned();
        $table->string('slug');
        $table->string('name');
        $table->string('description')->nullable();
        $table->boolean('active')->default(true);
        $table->timestamps();
        $this->userStamps($table);
        $table->engine = 'InnoDB';
        $table->unique('slug');
        $table->foreign('cat_permission_type_id')
          ->references('id')
          ->on('cat_permission_types')
          ->onUpdate('cascade')
          ->onDelete('cascade');
        enableForeignKeyConstraints();
      });
    }
  }
}

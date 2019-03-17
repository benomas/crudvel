<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Crudvel\Database\Migrations\BaseMigration;
class CreateFilesTable extends BaseMigration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
      if(!Schema::hasTable($this->mainTable)){
          Schema::create($this->mainTable, function (Blueprint $table) {
              DB::statement('SET FOREIGN_KEY_CHECKS=0;');
              $table->engine = 'InnoDB';

              $table->bigIncrements("id");
              $table->string("disk")->default("public");
              $table->string("path");
              $table->string("absolute_path")->default("");
              $table->integer("cat_file_id")->unsigned();
              $table->bigInteger("resource_id")->unsigned();
              $this->defaultColumns($table);

              $table->foreign('cat_file_id')
                  ->references('id')
                  ->on('cat_files')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');

              $table->index("path");
              DB::statement('SET FOREIGN_KEY_CHECKS=1;');
          });
      }
  }
}

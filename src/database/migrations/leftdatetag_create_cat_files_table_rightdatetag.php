<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Crudvel\Database\Migrations\BaseMigration;
class CreateCatFilesTable extends BaseMigration
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
        $table->engine = 'InnoDB';

        $table->increments("id");
        $table->string("name");
        $table->string("slug");
        $table->text("description");
        $table->boolean('required')->default(true);
        $table->string("group",100)->nullable();
        $table->decimal("max_size",10,2);
        $table->decimal("min_size",10,2);
        $table->string("types");
        $table->boolean('multiple')->default(false);
        $table->string("public_path")->nullable();;
        $table->string("resource");
        $this->defaultColumns($table);

        $table->index("name");
        $table->index("required");
        $table->index("group");
        $table->index("types");
      });
    }
  }
}

<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Crudvel\Database\Migrations\BaseMigration;

class CreateCatPermissionTypesTablerightdatetag extends BaseMigration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable($this->mainTable)) {
            Schema::create($this->mainTable, function (Blueprint $table) {
                $this->catalog($table);
            });
            $this->change(function($table){
                $table->string('slug')->nullable()->after("name");
            });
        }
    }
}
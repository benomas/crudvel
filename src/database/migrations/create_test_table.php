<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTestTable extends BaseMigration
{
    public function up()
    {
        if(!Schema::hasTable("test") && Schema::hasTable("roles") && Schema::hasTable("permissions")){
            Schema::create("test", function (Blueprint $table) {
                $table->increments("id");
                $table->integer("permission_id")->unsigned();
                $table->integer("role_id")->unsigned();
                $table->timestamps();
                $table->foreign("permission_id")
                    ->references("id")
                    ->on("permissions")
                    ->onUpdate("cascade")
                    ->onDelete("cascade");
                $table->foreign("role_id")
                    ->references("id")
                    ->on("roles")
                    ->onUpdate("cascade")
                    ->onDelete("cascade");
                $table->engine = "InnoDB";
            });
        }
    }
    
    public function down()
    {
        if(Schema::hasTable("test")){            
            DB::statement("SET FOREIGN_KEY_CHECKS=0;");
            Schema::drop("test");
            DB::statement("SET FOREIGN_KEY_CHECKS=1;");
        }
    }
}

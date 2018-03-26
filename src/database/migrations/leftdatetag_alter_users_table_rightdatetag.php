<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Crudvel\Database\Migrations\BaseMigration;
class AlterUsersTablerightdatetag extends BaseMigration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        try{
            Schema::table($this->mainTable, function($table){
                DB::statement('SET FOREIGN_KEY_CHECKS=0;');
                $table->dropColumn('name');
                $table->string('username')->after("id");
                $table->string('first_name')->after("username");
                $table->string('last_name')->after("first_name");
                $table->dateTime('last_login')->nullable()->after("email");
                $this->userStamps($table);
                $table->boolean('active')->default(true)->after("created_by");
                DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            });
        }
        catch(\Exception $e){
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            customLog("error when try to alter users table");
        }
    }
 
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        try{
            Schema::table($this->mainTable, function($table){

                DB::statement('SET FOREIGN_KEY_CHECKS=0;');
                $table->string('name')->after("id");
                $table->dropColumn('username');
                $table->dropColumn('first_name');
                $table->dropColumn('last_name');
                $table->dropColumn('last_login');
                $table->dropColumn('created_by');
                $table->dropColumn('updated_by');
                $table->dropColumn('active');
                $this->userStampsDown($table);
                DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            });
        }
        catch(\Exception $e){
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            customLog("error when try to alter users table");
        }
    }
}
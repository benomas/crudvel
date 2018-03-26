<?php namespace Crudvel\Database\Seeds;

use Illuminate\Database\Seeder;
use DB;
use App\Models\CatPermissionType;

class CatPermissionTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        //
    	DB::transaction(function() {
			$data=[
				[
					"name"        =>"Sección",
					"description" =>"Permiso a nivel sección",
					"active"      =>1,
				],
				[
					"name"        =>"Recurso",
					"description" =>"Permiso a nivel recurso",
					"active"      =>1,
				],
				[
					"name"        =>"Acción",
					"description" =>"Permiso a nivel acción",
					"active"      =>1,
				],
				[
					"name"        =>"Campo",
					"description" =>"Permiso a nivel Campo",
					"active"      =>1,
				],
			];
			foreach ($data as $key => $value) {
				$catPermissionType = new CatPermissionType();
				$catPermissionType->fill($value)->save();
			}
    	});
    	DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}

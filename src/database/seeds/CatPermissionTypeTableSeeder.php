<?php namespace Crudvel\Database\Seeds;

use Crudvel\Database\Seeders\BaseSeeder;
use DB;

class CatPermissionTypeTableSeeder extends BaseSeeder
{
	protected $data      = [
		[
			"name"        =>"Sección",
			"slug"        =>"section",
			"description" =>"Permiso a nivel sección",
			"active"      =>1,
		],
		[
			"name"        =>"Recurso",
			"slug"        =>"resource",
			"description" =>"Permiso a nivel recurso",
			"active"      =>1,
		],
		[
			"name"        =>"Acción",
			"slug"        =>"action",
			"description" =>"Permiso a nivel acción",
			"active"      =>1,
		],
		[
			"name"        =>"Campo",
			"slug"        =>"field",
			"description" =>"Permiso a nivel campo",
			"active"      =>1,
		],
		[
			"name"        =>"Especial",
			"slug"        =>"special",
			"description" =>"Permiso especial",
			"active"      =>1,
		],
	];
}

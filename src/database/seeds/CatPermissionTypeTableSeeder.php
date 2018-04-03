<?php namespace Crudvel\Database\Seeds;

use Crudvel\Database\Seeds\BaseSeeder;
use DB;

class CatPermissionTypeTableSeeder extends BaseSeeder
{
	protected $data      = [
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
			"description" =>"Permiso a nivel campo",
			"active"      =>1,
		],
		[
			"name"        =>"Especial",
			"description" =>"Permiso especial",
			"active"      =>1,
		],
	];
}

<?php namespace Crudvel\Database\Seeds;

use Illuminate\Database\Seeder;
use DB;
use Crudvel\Traits\CrudTrait;
use Illuminate\Support\Facades\Schema;

class BaseSeeder extends Seeder
{
	protected $baseClass;
	protected $classType = "TableSeeder";
	protected $crudObjectName;
	protected $modelSource;
	protected $model;
  use CrudTrait;
  /**+
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
  	if(empty($this->data))
  		return false;
    $this->explodeClass();
		disableForeignKeyConstraints();
  	DB::transaction(function() {
		foreach ($this->data as $key => $value)
  			$this->modelInstanciator(true)->fill($value)->save();
  	});
		enableForeignKeyConstraints();
  }
}

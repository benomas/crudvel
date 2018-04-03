<?php namespace Crudvel\Database\Seeds;

use Illuminate\Database\Seeder;
use DB;
use Crudvel\Traits\CrudTrait;

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
    	DB::statement('SET FOREIGN_KEY_CHECKS=0;');
    	DB::transaction(function() {
			foreach ($this->data as $key => $value)
    			$this->modelInstanciator(true)->fill($value)->save();
    	});
    	DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}

<?php namespace Crudvel\Database\Seeds;

use Illuminate\Database\Seeder;
use DB;
use Illuminate\Support\Facades\Schema;

class BaseTestSeeder extends \Crudvel\Database\Seeds\BaseSeeder {
  protected $deleteBeforeInsert = false;

// [Specific Logic]
	public function run(){
    $this->prepareSeeder();
    DB::transaction(function() {
      $modelFactory = $this->getModelClass()::factory()->count($this->getSeedsToInsert())->create();

      if($this->getInjectedSeedControl())
        $this->getInjectedSeedControl()->afterCreation($modelFactory,$this);
    });
    $this->finishSeeder();
	}
// [End Specific Logic]

// [Getters]
// [End Getters]

// [Setters]
// [End Setters]
}

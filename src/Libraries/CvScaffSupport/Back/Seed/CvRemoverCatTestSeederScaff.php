<?php

namespace Crudvel\Libraries\CvScaffSupport\Back\Seed;

use \Crudvel\Interfaces\CvScaffInterface;
use Illuminate\Support\Str;

class CvRemoverCatTestSeederScaff extends \Crudvel\Libraries\CvScaffSupport\Back\CvBaseRemoverScaff implements CvScaffInterface
{
  use \Crudvel\Traits\CvScaffCatTrait;
  protected $relatedFilePath   = 'database/seeds/test/DatabaseSeeder.php';
  public function __construct(){
    parent::__construct();
  }
//[Getters]
//[End Getters]

//[Setters]
//[End Setters]

//[Stablishers]
//[End Stablishers]
  protected function fixFile(){
    pdd(
      '<slot>TableSeeder::class.*;',
      Str::studly(Str::singular($this->getResource()))
    );
    return $this->globalFileRegexRemover(
      $this->scapedRegexMaker(
        '<slot>TableSeeder::class.*;',
        Str::studly(Str::singular($this->getResource()))
      )
    );
  }

  protected function selfRepresentation(){
    return 'DatabaseSeeder';
  }
}

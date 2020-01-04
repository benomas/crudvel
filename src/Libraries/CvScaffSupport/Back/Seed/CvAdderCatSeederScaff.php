<?php

namespace Crudvel\Libraries\CvScaffSupport\Back\Seed;

use \Crudvel\Interfaces\CvScaffInterface;
use Illuminate\Support\Str;

class CvAdderCatSeederScaff extends \Crudvel\Libraries\CvScaffSupport\Back\CvBaseAdderScaff implements CvScaffInterface
{
  use \Crudvel\Traits\CvScaffCatTrait;
  protected $relatedFilePath            = 'database/seeds/DatabaseSeeder.php';
  protected $leftRegexGlobalRequiriment = 'call\(\[';
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
    $basePatern = '/<slot>TableSeeder::class/';
    $resource   = 'Database\Seeds\\'.Str::studly(Str::singular($this->getResource())).'TableSeeder::class';
    return $this->globalFileRegexAdder(
      $this->regexMaker($basePatern,'[^\s,]+'),
      $this->scapedRegexMaker($basePatern,$resource),
      $resource
    );
  }

  protected function selfRepresentation(){
    return 'DatabaseSeeder';
  }
}

<?php

namespace Crudvel\Libraries\CvScaffSupport\Back\Seed;

use \Crudvel\Interfaces\CvScaffInterface;
use Illuminate\Support\Str;

class CvAdderTestSeederScaff extends \Crudvel\Libraries\CvScaffSupport\Back\CvBaseAdderScaff implements CvScaffInterface
{
  protected $relatedFilePath   = 'database/seeders/test/DatabaseSeeder.php';
  protected $leftRegexGlobalRequiriment = 'run\(\)\{';
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
    $basePatern = '<slot>TableSeeder::cvIam.*;';
    $resource   = cvCaseFixer('studly|singular',$this->getResource());
    return $this->globalFileRegexAdder(
      $this->regexMaker($basePatern,'[^\s,]+'),
      $this->scapedRegexMaker($basePatern,$resource),
      $resource.'TableSeeder::cvIam()->setSeedsToInsert(10)->run();'
    );
  }

  protected function selfRepresentation(){
    return 'DatabaseSeeder';
  }
}

<?php

namespace Crudvel\Libraries\CvScaffSupport\Back\Seed;

use \Crudvel\Interfaces\CvScaffInterface;
use Illuminate\Support\Str;

class CvRemoverCatSeederScaff extends \Crudvel\Libraries\CvScaffSupport\Back\CvBaseRemoverScaff implements CvScaffInterface
{
  use \Crudvel\Traits\CvScaffCatTrait;
  protected $relatedFilePath   = 'database/seeds/DatabaseSeeder.php';
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
    $fileContent  = $this->getFile();
    $slugResource = 'Database\\\\Seeds\\\\'.Str::studly(Str::singular($this->getResource())).'TableSeeder::class';
    $patern = '/(call\(\[)((?>\s|\S)*?)(\W*?)(,'.$slugResource.')(\W*?)((?>\s|\S)*)/';
    $fix1   = preg_replace($patern, '$1$2$5$6', $fileContent);
    $patern = '/(call\(\[)((?>\s|\S)*?)(\s*?)('.$slugResource.')(\s*?,)((?>\s|\S)*)/';
    return $this->setFile(preg_replace($patern, '$1$2$3$6', $fix1));
  }
  protected function selfRepresentation(){
    return 'DatabaseSeeder';
  }
}

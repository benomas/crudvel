<?php

namespace Crudvel\Libraries\CvScaffSupport\Back\Seed;

use \Crudvel\Interfaces\CvScaffInterface;
use Illuminate\Support\Str;

class CvAdderSeederScaff extends \Crudvel\Libraries\CvScaffSupport\Back\CvBaseAdderScaff implements CvScaffInterface
{
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
    $fileContent    = $this->getFile();
    $pattern = '/((?>\s|\S)*call\(\[)((?>\s|\S)*?)(\s*?)(\S+?)(\s*?)(\]\);(?>\s|\S)*)/';
    preg_match($pattern,$fileContent,$matches);
    $seedCall = $matches[2] ?? null;
    if(!$seedCall)
      throw new \Exception('Error, call section is not defined');
    $slugResource = 'Database\\Seeds\\'.Str::studly(Str::singular($this->getResource())).'TableSeeder::class';
    //pdd($slugResource);
    preg_match('/\W*'.$slugResource.'\W*/',$seedCall,$matches2);
    if(count($matches2)){
      cvConsoler(cvBlueTC('no changes required')."\n");
      return $this;
    }
    $seedCall = "$seedCall{$matches[3]}{$matches[4]}";
    return $this->setFile(str_replace("$seedCall","$seedCall{$matches[3]},$slugResource",$fileContent));
  }
  protected function selfRepresentation(){
    return 'DatabaseSeeder';
  }
}

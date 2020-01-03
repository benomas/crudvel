<?php

namespace Crudvel\Libraries\CvScaffSupport\Back\ApiRoute;

use \Crudvel\Interfaces\CvScaffInterface;
use Illuminate\Support\Str;

class CvAdderCatApiRouteScaff extends \Crudvel\Libraries\CvScaffSupport\Back\CvBaseAdderScaff implements CvScaffInterface
{
  protected $relatedFilePath   = 'routes/api.php';
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
    $pattern = '/((?>\s|\S)*apiCrudvelResources\(\[)((?>\s|\S)*\[(.+)\](\s*))(\]\);(?>\s|\S)*)/';
    preg_match($pattern,$fileContent,$matches);
    $apiCrudvelResources = $matches[2] ?? null;
    if(!$apiCrudvelResources)
      throw new \Exception('Error, api crudvel resources section is not defined');
    $slugResource = fixedSlug(Str::plural($this->getResource()));
    preg_match('/\[\W*'.$slugResource.'\W*\]/',$apiCrudvelResources,$matches2);
    if(count($matches2)){
      cvConsoler(cvBlueTC('no changes required')."\n");
      return $this;
    }
    return $this->setFile(str_replace($apiCrudvelResources,"$apiCrudvelResources  ,[\"$slugResource\"]{$matches[4]}",$fileContent));
  }
  protected function selfRepresentation(){
    return 'api';
  }
}

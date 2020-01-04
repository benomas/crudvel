<?php

namespace Crudvel\Libraries\CvScaffSupport\Back\ApiRoute;

use \Crudvel\Interfaces\CvScaffInterface;
use Illuminate\Support\Str;

class CvRemoverCatApiRouteScaff extends \Crudvel\Libraries\CvScaffSupport\Back\CvBaseRemoverScaff implements CvScaffInterface
{
  use \Crudvel\Traits\CvScaffCatTrait;
  protected $relatedFilePath   = 'routes/api.php';
  public function __construct(){
    parent::__construct();
  }
  //[Getters]
  //[End Getters]

  //[Setters]
  //[End Setters]

  //[Stablishers]
  protected function fixFile(){
    $fileContent    = $this->getFile();
    $pattern = '/((?>\s|\S)*apiCrudvelResources\(\[)((?>\s|\S)*\[(.+)\](\s*))(\]\);(?>\s|\S)*)/';
    preg_match($pattern,$fileContent,$matches);
    $apiCrudvelResources = $matches[2] ?? null;
    if(!$apiCrudvelResources)
      throw new \Exception('Error, api crudvel resources section is not defined');
    $slugResource = fixedSlug(Str::plural($this->getResource()));
    preg_match('/(\])?((?>\s|,)*)(\[\W*'.$slugResource.'\W*\])((?>\s|,)*)/',$apiCrudvelResources,$matches2);
    $toRemove='';
    if(strlen($matches2[1]??''))
      $toRemove = $matches2[2]??'';
    $toRemove .= $matches2[3]??'';
    if(!strlen($matches2[1]??''))
      $toRemove .= $matches2[4]??'';

    if($toRemove===''){
      cvConsoler(cvInfo('no changes required')."\n");
      return $this;
    }
    $toRemove = str_replace($toRemove,"",$apiCrudvelResources);
    return $this->setFile(str_replace($apiCrudvelResources,$toRemove,$fileContent));
  }
  //[End Stablishers]
  protected function selfRepresentation(){
    return 'api';
  }
}

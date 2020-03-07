<?php

namespace Crudvel\Libraries\CvScaffSupport\Back\ApiRoute;

use \Crudvel\Interfaces\CvScaffInterface;
use Illuminate\Support\Str;

class CvAdderApiRouteScaff extends \Crudvel\Libraries\CvScaffSupport\Back\CvBaseAdderScaff implements CvScaffInterface
{
  protected $relatedFilePath            = 'routes/api.php';
  protected $leftRegexGlobalRequiriment = 'apiCrudvelResources\(\[';
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
    $basePatern       = '\[["\']<slot>["\']\]';
    $slugResource     = cvSlugCase(Str::plural($this->getResource()));
    return $this->globalFileRegexAdder(
      $this->regexMaker($basePatern,'[^\s,]+'),
      $this->scapedRegexMaker($basePatern,$slugResource),
      '[\''.$slugResource.'\']'
    );
  }
  protected function selfRepresentation(){
    return 'api';
  }
}

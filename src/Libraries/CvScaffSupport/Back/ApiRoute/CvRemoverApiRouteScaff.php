<?php

namespace Crudvel\Libraries\CvScaffSupport\Back\ApiRoute;

use \Crudvel\Interfaces\CvScaffInterface;
use Illuminate\Support\Str;

class CvRemoverApiRouteScaff extends \Crudvel\Libraries\CvScaffSupport\Back\CvBaseRemoverScaff implements CvScaffInterface
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
  protected function fixFile(){
    return $this->globalFileRegexRemover(
      $this->scapedRegexMaker(
        '\[["\']<slot>["\']\]',
        fixedSlug(Str::plural($this->getResource()))
      )
    );
  }
  //[End Stablishers]
  protected function selfRepresentation(){
    return 'api';
  }
}

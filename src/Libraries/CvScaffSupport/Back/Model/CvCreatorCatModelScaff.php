<?php

namespace Crudvel\Libraries\CvScaffSupport\Back\Model;

use \Crudvel\Interfaces\CvScaffInterface;
use Illuminate\Support\Str;

class CvCreatorCatModelScaff extends \Crudvel\Libraries\CvScaffSupport\Back\CvBaseCreatorScaff implements CvScaffInterface
{
  use \Crudvel\Traits\CvScaffCatTrait;
  protected $relatedTargetPath   = 'app/Models/';
  protected $relatedTemplatePath = 'vendor/benomas/crudvel/src/templates/back/cv_scaff_cat_model.txt';
  public function __construct(){
    parent::__construct();
  }
//[Getters]
//[End Getters]

//[Setters]
//[End Setters]

//[Stablishers]
//[End Stablishers]
  protected function selfRepresentation(){
    return Str::studly(Str::singular($this->getResource()));
  }
}

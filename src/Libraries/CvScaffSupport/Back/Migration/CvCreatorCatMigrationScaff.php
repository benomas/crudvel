<?php

namespace Crudvel\Libraries\CvScaffSupport\Back\Migration;

use \Crudvel\Interfaces\CvScaffInterface;
use Illuminate\Support\Str;
use Carbon\Carbon;

class CvCreatorCatMigrationScaff extends \Crudvel\Libraries\CvScaffSupport\Back\CvBaseCreatorScaff implements CvScaffInterface
{
  use \Crudvel\Traits\CvScaffCatTrait;
  protected $relatedTargetPath   = 'database/migrations/';
  protected $relatedTemplatePath = 'vendor/benomas/crudvel/src/templates/back/cv_scaff_cat_migration.txt';
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
    return Carbon::now()->format('Y_m_d_u').'_create_'.cvSnakeCase(Str::plural($this->getResource())).'_table';
  }
}
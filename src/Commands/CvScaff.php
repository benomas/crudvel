<?php namespace Crudvel\Commands;

class CvScaff extends \Crudvel\Commands\BaseCommand
{
  use \Crudvel\Traits\CacheTrait;
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'cv-scaff {context?} {mode?} {target?} {resource?} {force?}';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Advanced scaffolding command';

  /**
   * Create a new command instance.
   *
   * @return void
   */
  public function __construct()
  {
    parent::__construct();
  }

  /**
   * Execute the console command.
   *
   * @return mixed
   */
  public function handle()
  {
    $cvScaffBuilder = (new \Crudvel\Libraries\CvScaffSupport\CvBuilder($this))
      ->stablishContext($this->propertyReload('context'))
      ->stablishMode($this->propertyReload('mode'))
      ->stablishTarget($this->propertyReload('target'))
      ->stablishResource($this->propertyReload('resource'));

    if(($this->propertyReload('force'))===null)
      $this->cvCacheSetProperty('force',false);

    if($this->propertyReload('force'))
      $cvScaffBuilder->force();

    $cvScaffBuilder->build()->runScaff();
  }
}

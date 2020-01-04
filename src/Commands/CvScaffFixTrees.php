<?php namespace Crudvel\Commands;

class CvScaffFixTrees extends \Crudvel\Commands\BaseCommand
{
  use \Crudvel\Traits\CacheTrait;
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'cv-scaff-fix-trees';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'remove invalid classes';

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
    $cvScaffBuilder = (new \Crudvel\Libraries\CvScaffSupport\CvBuilder($this));
    $cvScaffBuilder->build()->fixScaffTrees();
  }
}

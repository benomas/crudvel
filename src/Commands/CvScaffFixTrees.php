<?php namespace Crudvel\Commands;

use Crudvel\Libraries\CvScaffSupport\CvBuilder;
use Crudvel\Traits\CacheTrait;

class CvScaffFixTrees extends BaseCommand
{
  use CacheTrait;
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
   * @return void
   */
  public function handle(): void {
    $cvScaffBuilder = (new CvBuilder($this));
    $cvScaffBuilder->build()->fixScaffTrees();
  }
}

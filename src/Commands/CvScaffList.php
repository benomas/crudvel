<?php namespace Crudvel\Commands;

use Crudvel\Libraries\CvScaffSupport\CvBuilder;

class CvScaffList extends BaseCommand
{
  use \Crudvel\Traits\CacheTrait;
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'cv-scaff-list';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Advanced scaffolding list command';

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
    (new CvBuilder($this,'Crudvel\Libraries\CvScaffSupport\CvScaffHelper'))
      ->build()
      ->cvScaffList()
      ->composerDump();
  }
}

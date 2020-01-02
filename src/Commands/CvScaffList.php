<?php namespace Crudvel\Commands;

class CvScaffList extends \Crudvel\Commands\BaseCommand
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
   * @return mixed
   */
  public function handle()
  {
    (new \Crudvel\Libraries\CvScaffSupport\CvBuilder($this,'Crudvel\Libraries\CvScaffSupport\CvScaffHelper'))
      ->build()
      ->cvScaffList()
      ->composerDump();
  }
}

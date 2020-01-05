<?php namespace Crudvel\Commands;

class CvScaffDeleteCatResource extends \Crudvel\Commands\BaseCommand
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'unscaff-cat-resource {resource?}';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Advanced dual cat delete resource scaffolding command';

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
    $resource = $this->propertyReload('resource');
    $this->call('unscaff-back-cat-resource',['resource' => $resource]);
    $this->call('unscaff-front-cat-resource',['resource' => $resource]);
    composerDump();
  }
}

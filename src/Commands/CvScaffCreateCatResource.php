<?php namespace Crudvel\Commands;

class CvScaffCreateCatResource extends BaseCommand
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'scaff-cat-resource {resource?}';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Advanced dual create resource scaffolding command';

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
    $resource = $this->propertyReload('resource');
    $this->call('scaff-back-cat-resource',['resource' => $resource]);
    $this->call('scaff-front-cat-resource',['resource' => $resource]);
    composerDump();
  }
}

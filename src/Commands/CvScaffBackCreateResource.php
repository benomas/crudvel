<?php namespace Crudvel\Commands;

class CvScaffBackCreateResource extends \Crudvel\Commands\BaseCommand
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'create-back-resource {resource?}';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Advanced back create resource scaffolding command';

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
    $this->call('cv-scaff',[
      'context'  => 'back',
      'mode'     => 'creator',
      'target'   => 'en-lang',
      'resource' => $resource,
    ]);
    $this->call('cv-scaff',[
      'context'  => 'back',
      'mode'     => 'creator',
      'target'   => 'es-lang',
      'resource' => $resource,
    ]);
    $this->call('cv-scaff',[
      'context'  => 'back',
      'mode'     => 'creator',
      'target'   => 'model',
      'resource' => $resource,
    ]);
    $this->call('cv-scaff',[
      'context'  => 'back',
      'mode'     => 'creator',
      'target'   => 'request',
      'resource' => $resource,
    ]);
    $this->call('cv-scaff',[
      'context'  => 'back',
      'mode'     => 'creator',
      'target'   => 'api-controller',
      'resource' => $resource,
    ]);
    $this->call('cv-scaff',[
      'context'  => 'back',
      'mode'     => 'creator',
      'target'   => 'migration',
      'resource' => $resource,
    ]);
    $this->call('cv-scaff',[
      'context'  => 'back',
      'mode'     => 'creator',
      'target'   => 'seeder',
      'resource' => $resource,
    ]);
    $this->call('cv-scaff',[
      'context'  => 'back',
      'mode'     => 'adder',
      'target'   => 'api-route',
      'resource' => $resource,
    ]);
    composerDump();
  }
}

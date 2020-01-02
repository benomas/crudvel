<?php namespace Crudvel\Commands;

class CvScaffBackDeleteResource extends \Crudvel\Commands\BaseCommand
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'delete-back-resource {resource?}';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Advanced back delete resource scaffolding command';

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
    $resource = $this->properyReload('resource');
    $this->call('cv-scaff',[
      'context'  => 'back',
      'mode'     => 'deleter',
      'target'   => 'en-lang',
      'resource' => $resource,
    ]);
    $this->call('cv-scaff',[
      'context'  => 'back',
      'mode'     => 'deleter',
      'target'   => 'es-lang',
      'resource' => $resource,
    ]);
    $this->call('cv-scaff',[
      'context'  => 'back',
      'mode'     => 'deleter',
      'target'   => 'model',
      'resource' => $resource,
    ]);
    $this->call('cv-scaff',[
      'context'  => 'back',
      'mode'     => 'deleter',
      'target'   => 'request',
      'resource' => $resource,
    ]);
    $this->call('cv-scaff',[
      'context'  => 'back',
      'mode'     => 'deleter',
      'target'   => 'api-controller',
      'resource' => $resource,
    ]);
    $this->call('cv-scaff',[
      'context'  => 'back',
      'mode'     => 'deleter',
      'target'   => 'migration',
      'resource' => $resource,
    ]);
    $this->call('cv-scaff',[
      'context'  => 'back',
      'mode'     => 'deleter',
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

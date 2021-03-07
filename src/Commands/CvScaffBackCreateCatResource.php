<?php namespace Crudvel\Commands;

class CvScaffBackCreateCatResource extends \Crudvel\Commands\BaseCommand
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'scaff-back-cat-resource {resource?}';

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
      'target'   => 'cat-en-lang',
      'resource' => $resource,
    ]);
    $this->call('cv-scaff',[
      'context'  => 'back',
      'mode'     => 'creator',
      'target'   => 'cat-es-lang',
      'resource' => $resource,
    ]);
    $this->call('cv-scaff',[
      'context'  => 'back',
      'mode'     => 'creator',
      'target'   => 'cat-model',
      'resource' => $resource,
    ]);
    $this->call('cv-scaff',[
      'context'  => 'back',
      'mode'     => 'creator',
      'target'   => 'cat-request',
      'resource' => $resource,
    ]);
    $this->call('cv-scaff',[
      'context'  => 'back',
      'mode'     => 'creator',
      'target'   => 'cat-api-controller',
      'resource' => $resource,
    ]);
    $this->call('cv-scaff',[
      'context'  => 'back',
      'mode'     => 'creator',
      'target'   => 'cat-migration',
      'resource' => $resource,
    ]);
    $this->call('cv-scaff',[
      'context'  => 'back',
      'mode'     => 'creator',
      'target'   => 'cat-seeder',
      'resource' => $resource,
    ]);
    $this->call('cv-scaff',[
      'context'  => 'back',
      'mode'     => 'creator',
      'target'   => 'cat-factory',
      'resource' => $resource,
    ]);
    $this->call('cv-scaff',[
      'context'  => 'back',
      'mode'     => 'creator',
      'target'   => 'cat-test-seeder',
      'resource' => $resource,
    ]);
    $this->call('cv-scaff',[
      'context'  => 'back',
      'mode'     => 'adder',
      'target'   => 'cat-api-route',
      'resource' => $resource,
    ]);
    $this->call('cv-scaff',[
      'context'  => 'back',
      'mode'     => 'adder',
      'target'   => 'cat-seeder',
      'resource' => $resource,
    ]);
    $this->call('cv-scaff',[
      'context'  => 'back',
      'mode'     => 'adder',
      'target'   => 'cat-test-seeder',
      'resource' => $resource,
    ]);
    $this->prepareApiEnv();
  }
}

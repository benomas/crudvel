<?php namespace Crudvel\Commands;

class CvScaffBackDeleteCatResource extends \Crudvel\Commands\BaseCommand
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'delete-back-cat-resource {resource?}';

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
    $resource = $this->propertyReload('resource');
    $this->call('cv-scaff',[
      'context'  => 'back',
      'mode'     => 'deleter',
      'target'   => 'cat-en-lang',
      'resource' => $resource,
    ]);
    $this->call('cv-scaff',[
      'context'  => 'back',
      'mode'     => 'deleter',
      'target'   => 'cat-es-lang',
      'resource' => $resource,
    ]);
    $this->call('cv-scaff',[
      'context'  => 'back',
      'mode'     => 'deleter',
      'target'   => 'cat-model',
      'resource' => $resource,
    ]);
    $this->call('cv-scaff',[
      'context'  => 'back',
      'mode'     => 'deleter',
      'target'   => 'cat-request',
      'resource' => $resource,
    ]);
    $this->call('cv-scaff',[
      'context'  => 'back',
      'mode'     => 'deleter',
      'target'   => 'cat-api-controller',
      'resource' => $resource,
    ]);
    $this->call('cv-scaff',[
      'context'  => 'back',
      'mode'     => 'deleter',
      'target'   => 'cat-migration',
      'resource' => $resource,
    ]);
    $this->call('cv-scaff',[
      'context'  => 'back',
      'mode'     => 'deleter',
      'target'   => 'cat-seeder',
      'resource' => $resource,
    ]);
    $this->call('cv-scaff',[
      'context'  => 'back',
      'mode'     => 'remover',
      'target'   => 'api-route',
      'resource' => $resource,
    ]);
    composerDump();
  }
}

<?php namespace Crudvel\Commands;

class CvScaffFrontDeleteCatResource extends \Crudvel\Commands\BaseCommand
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'unscaff-front-cat-resource {resource?}';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Advanced front delete resource scaffolding command';

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
      'context'  => 'front',
      'mode'     => 'deleter',
      'target'   => 'cat-en-lang',
      'resource' => $resource,
    ]);
    $this->call('cv-scaff',[
      'context'  => 'front',
      'mode'     => 'deleter',
      'target'   => 'cat-es-lang',
      'resource' => $resource,
    ]);
    $this->call('cv-scaff',[
      'context'  => 'front',
      'mode'     => 'remover',
      'target'   => 'crudvuel-cat-en-lang',
      'resource' => $resource,
    ]);
    $this->call('cv-scaff',[
      'context'  => 'front',
      'mode'     => 'remover',
      'target'   => 'crudvuel-cat-es-lang',
      'resource' => $resource,
    ]);
    $this->call('cv-scaff',[
      'context'  => 'front',
      'mode'     => 'deleter',
      'target'   => 'cat-create',
      'resource' => $resource,
    ]);
    $this->call('cv-scaff',[
      'context'  => 'front',
      'mode'     => 'deleter',
      'target'   => 'cat-edit',
      'resource' => $resource,
    ]);
    $this->call('cv-scaff',[
      'context'  => 'front',
      'mode'     => 'deleter',
      'target'   => 'cat-index',
      'resource' => $resource,
    ]);
    $this->call('cv-scaff',[
      'context'  => 'front',
      'mode'     => 'deleter',
      'target'   => 'cat-show',
      'resource' => $resource,
    ]);
    $this->call('cv-scaff',[
      'context'  => 'front',
      'mode'     => 'deleter',
      'target'   => 'cat-service',
      'resource' => $resource,
    ]);
    $this->call('cv-scaff',[
      'context'  => 'front',
      'mode'     => 'deleter',
      'target'   => 'cat-definition',
      'resource' => $resource,
    ]);
    $this->call('cv-scaff',[
      'context'  => 'front',
      'mode'     => 'remover',
      'target'   => 'cat-boot',
      'resource' => $resource,
    ]);
    $this->call('cv-scaff',[
      'context'  => 'front',
      'mode'     => 'remover',
      'target'   => 'cat-router',
      'resource' => $resource,
    ]);
    $this->call('cv-scaff',[
      'context'  => 'front',
      'mode'     => 'remover',
      'target'   => 'cat-layout',
      'resource' => $resource,
    ]);
    $crudvelFrontPath = config('packages.benomas.crudvel.crudvel.crudvel_front_path');
    customExec("cd /var/www/$crudvelFrontPath && npm run fixlint");
    cvConsoler("\n".cvPositive("fixlint completed")."\n");
  }
}

<?php namespace Crudvel\Commands;

class CvScaffFrontDeleteResource extends \Crudvel\Commands\BaseCommand
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'unscaff-front-resource {resource?}';

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
      'target'   => 'en-lang',
      'resource' => $resource,
    ]);
    $this->call('cv-scaff',[
      'context'  => 'front',
      'mode'     => 'deleter',
      'target'   => 'es-lang',
      'resource' => $resource,
    ]);
    $this->call('cv-scaff',[
      'context'  => 'front',
      'mode'     => 'remover',
      'target'   => 'crudvuel-en-lang',
      'resource' => $resource,
    ]);
    $this->call('cv-scaff',[
      'context'  => 'front',
      'mode'     => 'remover',
      'target'   => 'crudvuel-es-lang',
      'resource' => $resource,
    ]);
    $this->call('cv-scaff',[
      'context'  => 'front',
      'mode'     => 'deleter',
      'target'   => 'create',
      'resource' => $resource,
    ]);
    $this->call('cv-scaff',[
      'context'  => 'front',
      'mode'     => 'deleter',
      'target'   => 'edit',
      'resource' => $resource,
    ]);
    $this->call('cv-scaff',[
      'context'  => 'front',
      'mode'     => 'deleter',
      'target'   => 'index',
      'resource' => $resource,
    ]);
    $this->call('cv-scaff',[
      'context'  => 'front',
      'mode'     => 'deleter',
      'target'   => 'show',
      'resource' => $resource,
    ]);
    $this->call('cv-scaff',[
      'context'  => 'front',
      'mode'     => 'deleter',
      'target'   => 'service',
      'resource' => $resource,
    ]);
    $this->call('cv-scaff',[
      'context'  => 'front',
      'mode'     => 'deleter',
      'target'   => 'definition',
      'resource' => $resource,
    ]);
    $this->call('cv-scaff',[
      'context'  => 'front',
      'mode'     => 'remover',
      'target'   => 'boot',
      'resource' => $resource,
    ]);
    $this->call('cv-scaff',[
      'context'  => 'front',
      'mode'     => 'remover',
      'target'   => 'router',
      'resource' => $resource,
    ]);
    $this->call('cv-scaff',[
      'context'  => 'front',
      'mode'     => 'remover',
      'target'   => 'layout',
      'resource' => $resource,
    ]);
    $crudvelFrontPath = config('packages.benomas.crudvel.crudvel.crudvel_front_path');
    customExec("cd /var/www/$crudvelFrontPath && npm run fixlint");
    cvConsoler("\n".cvPositive("fixlint completed")."\n");
  }
}

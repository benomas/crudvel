<?php namespace Crudvel\Commands;

class CvScaffFrontCreateResource extends \Crudvel\Commands\BaseCommand
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'scaff-front-resource {resource?}';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Advanced front create resource scaffolding command';

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
      'mode'     => 'creator',
      'target'   => 'en-lang',
      'resource' => $resource,
    ]);
    $this->call('cv-scaff',[
      'context'  => 'front',
      'mode'     => 'creator',
      'target'   => 'es-lang',
      'resource' => $resource,
    ]);
    $this->call('cv-scaff',[
      'context'  => 'front',
      'mode'     => 'creator',
      'target'   => 'pt-lang',
      'resource' => $resource,
    ]);
    $this->call('cv-scaff',[
      'context'  => 'front',
      'mode'     => 'adder',
      'target'   => 'crudvuel-en-lang',
      'resource' => $resource,
    ]);
    $this->call('cv-scaff',[
      'context'  => 'front',
      'mode'     => 'adder',
      'target'   => 'crudvuel-es-lang',
      'resource' => $resource,
    ]);
    $this->call('cv-scaff',[
      'context'  => 'front',
      'mode'     => 'adder',
      'target'   => 'crudvuel-pt-lang',
      'resource' => $resource,
    ]);
    $this->call('cv-scaff',[
      'context'  => 'front',
      'mode'     => 'creator',
      'target'   => 'filler',
      'resource' => $resource,
    ]);
    $this->call('cv-scaff',[
      'context'  => 'front',
      'mode'     => 'creator',
      'target'   => 'skeleton',
      'resource' => $resource,
    ]);
    $this->call('cv-scaff',[
      'context'  => 'front',
      'mode'     => 'creator',
      'target'   => 'import-skeleton',
      'resource' => $resource,
    ]);
    $this->call('cv-scaff',[
      'context'  => 'front',
      'mode'     => 'creator',
      'target'   => 'create',
      'resource' => $resource,
    ]);
    $this->call('cv-scaff',[
      'context'  => 'front',
      'mode'     => 'creator',
      'target'   => 'edit',
      'resource' => $resource,
    ]);
    $this->call('cv-scaff',[
      'context'  => 'front',
      'mode'     => 'creator',
      'target'   => 'index',
      'resource' => $resource,
    ]);
    $this->call('cv-scaff',[
      'context'  => 'front',
      'mode'     => 'creator',
      'target'   => 'show',
      'resource' => $resource,
    ]);
    $this->call('cv-scaff',[
      'context'  => 'front',
      'mode'     => 'creator',
      'target'   => 'import',
      'resource' => $resource,
    ]);
    $this->call('cv-scaff',[
      'context'  => 'front',
      'mode'     => 'creator',
      'target'   => 'service',
      'resource' => $resource,
    ]);
    $this->call('cv-scaff',[
      'context'  => 'front',
      'mode'     => 'creator',
      'target'   => 'definition',
      'resource' => $resource,
    ]);
    $this->call('cv-scaff',[
      'context'  => 'front',
      'mode'     => 'adder',
      'target'   => 'boot',
      'resource' => $resource,
    ]);
    $this->call('cv-scaff',[
      'context'  => 'front',
      'mode'     => 'adder',
      'target'   => 'router',
      'resource' => $resource,
    ]);
    $this->call('cv-scaff',[
      'context'  => 'front',
      'mode'     => 'adder',
      'target'   => 'layout',
      'resource' => $resource,
    ]);
    $crudvelRootPath  = config('packages.benomas.crudvel.crudvel.crudvel_root_path');
    $crudvelFrontPath = config('packages.benomas.crudvel.crudvel.crudvel_front_path');
    customExec("cd {$crudvelRootPath}{$crudvelFrontPath} && npm run fixlint");
    cvConsoler("\n".cvPositive("fixlint completed")."\n");
  }
}

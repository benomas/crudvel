<?php namespace Crudvel\Commands;

class CvScaffFrontCreateCatResource extends \Crudvel\Commands\BaseCommand
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'scaff-front-cat-resource {resource?}';

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
      'target'   => 'cat-en-lang',
      'resource' => $resource,
    ]);
    $this->call('cv-scaff',[
      'context'  => 'front',
      'mode'     => 'creator',
      'target'   => 'cat-es-lang',
      'resource' => $resource,
    ]);
    $this->call('cv-scaff',[
      'context'  => 'front',
      'mode'     => 'adder',
      'target'   => 'crudvuel-cat-en-lang',
      'resource' => $resource,
    ]);
    $this->call('cv-scaff',[
      'context'  => 'front',
      'mode'     => 'adder',
      'target'   => 'crudvuel-cat-es-lang',
      'resource' => $resource,
    ]);
    $this->call('cv-scaff',[
      'context'  => 'front',
      'mode'     => 'creator',
      'target'   => 'cat-skeleton',
      'resource' => $resource,
    ]);
    $this->call('cv-scaff',[
      'context'  => 'front',
      'mode'     => 'creator',
      'target'   => 'cat-create',
      'resource' => $resource,
    ]);
    $this->call('cv-scaff',[
      'context'  => 'front',
      'mode'     => 'creator',
      'target'   => 'cat-filler',
      'resource' => $resource,
    ]);
    $this->call('cv-scaff',[
      'context'  => 'front',
      'mode'     => 'creator',
      'target'   => 'cat-edit',
      'resource' => $resource,
    ]);
    $this->call('cv-scaff',[
      'context'  => 'front',
      'mode'     => 'creator',
      'target'   => 'cat-index',
      'resource' => $resource,
    ]);
    $this->call('cv-scaff',[
      'context'  => 'front',
      'mode'     => 'creator',
      'target'   => 'cat-show',
      'resource' => $resource,
    ]);
    $this->call('cv-scaff',[
      'context'  => 'front',
      'mode'     => 'creator',
      'target'   => 'cat-service',
      'resource' => $resource,
    ]);
    $this->call('cv-scaff',[
      'context'  => 'front',
      'mode'     => 'creator',
      'target'   => 'cat-definition',
      'resource' => $resource,
    ]);
    $this->call('cv-scaff',[
      'context'  => 'front',
      'mode'     => 'adder',
      'target'   => 'cat-boot',
      'resource' => $resource,
    ]);
    $this->call('cv-scaff',[
      'context'  => 'front',
      'mode'     => 'adder',
      'target'   => 'cat-router',
      'resource' => $resource,
    ]);
    $this->call('cv-scaff',[
      'context'  => 'front',
      'mode'     => 'adder',
      'target'   => 'cat-layout',
      'resource' => $resource,
    ]);
    $crudvelRootPath  = config('packages.benomas.crudvel.crudvel.crudvel_root_path');
    $crudvelFrontPath = config('packages.benomas.crudvel.crudvel.crudvel_front_path');
    customExec("cd {$crudvelRootPath}{$crudvelFrontPath} && npm run fixlint");
    cvConsoler("\n".cvPositive("fixlint completed")."\n");
  }
}

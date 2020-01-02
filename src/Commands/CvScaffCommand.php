<?php namespace Crudvel\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Carbon\Carbon;

class CvScaffCommand extends Command {

  protected $signature   = 'cv-scaff {context}';
  protected $name        = "cv scaff";
  protected $description = 'Crudvel advances scaffolding';

  public function __construct(){
    parent::__construct();
  }

  public function handle()
  {
    $context= $this->argument("context");

    echo "proccess completed";
  }

  protected function getArguments()
  {
    return [
      [
        "modo", InputArgument::REQUIRED, "modo is required (create,delete)",
      ]
    ];
  }
}

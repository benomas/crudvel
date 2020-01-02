<?php namespace Crudvel\Commands;

use Illuminate\Console\Command;

class BaseCommand extends Command
{
  use \Crudvel\Traits\CacheTrait;
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'basecommand';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Command description';

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
  }

  protected function properyReload($property=null){
    if(!($propertyValue = $this->argument($property)??null))
      return $this->cvCacheGetProperty($property);
    $this->cvCacheSetProperty($property,$propertyValue);
    return $propertyValue;
  }

  public function propertyDefiner($property,$value){
    $this->cvCacheSetProperty($property,$value);
  }
}

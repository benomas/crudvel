<?php namespace Crudvel\Commands;

use App\Models\Role;

class CvRolePermissionLayout extends \Crudvel\Commands\BaseCommand
{
  use \Crudvel\Traits\CacheTrait;
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'cv-make-role-permissions-layout';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Generate excel to be source of role-permissions seeder';

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
    $dbRoles = Role::withoutRoot()->get();
    foreach($dbRoles AS $role){
      $spreadSheetConstructor = new \Crudvel\Libraries\SpreadSheetIO\Constructors\PermissionResourceSection($role->slug);
      $excel = new \Crudvel\Libraries\SpreadSheetIO\SpreadSheet($spreadSheetConstructor);
      $excel->storeSpreadSheet();
      $spreadSheetConstructor = new \Crudvel\Libraries\SpreadSheetIO\Constructors\PermissionResourceAction($role->slug);
      $excel = new \Crudvel\Libraries\SpreadSheetIO\SpreadSheet($spreadSheetConstructor);
      $excel->storeSpreadSheet();
    }
  }
}

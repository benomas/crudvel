<?php namespace Crudvel\Commands;

use App\Models\Role;
use Crudvel\Libraries\SpreadSheetIO\Constructors\{PermissionGlobalSpecials,PermissionResourceAction,PermissionResourceSection};
use Crudvel\Libraries\SpreadSheetIO\SpreadSheet;
use Crudvel\Traits\CacheTrait;

class CvRolePermissionLayout extends BaseCommand
{
  use CacheTrait;
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'cv-make-role-permissions-layout {reloadFromDb?}';

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
  public function handle(): mixed {
    $reloadFromDb = (bool) $this->propertyReload('reloadFromDb') ?? false;
    $dbRoles = Role::withoutRoot()->orderBy('id','ASC')->get();
    foreach($dbRoles AS $role){
      $spreadSheetConstructor = new PermissionResourceSection($role->slug);
      $excel = new SpreadSheet($spreadSheetConstructor);
      $excel->synchronize($reloadFromDb);
      $spreadSheetConstructor = new PermissionResourceAction($role->slug);
      $excel = new SpreadSheet($spreadSheetConstructor);
      $excel->synchronize($reloadFromDb);
      $spreadSheetConstructor = new PermissionGlobalSpecials($role->slug);
      $excel = new SpreadSheet($spreadSheetConstructor);
      $excel->synchronize($reloadFromDb);
    }
  }
}

<?php namespace Crudvel\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Illuminate\Support\Facades\Schema;

class InstallCommand extends Command {
  protected $signature   = 'install:crudvel';
  protected $description = 'Instala paquete';
  protected $name    = "install:crudvel";
  protected $migrationPath;

  public function __construct()
  {
    parent::__construct();
  }

  public function handle()
  {
    cvConsoler("\n".cvBlueTC('Crudvel Installation Process Start')."\n");
    try{
      $this->migrationsPath = base_path("database/migrations");
      if(!file_exists($this->migrationsPath))
        mkdir($this->migrationsPath);
      $migrations =[];

      $this->cloneFileData("User.php",base_path("vendor/benomas/crudvel/src/templates/user.txt"),base_path("app/Models"));

      if(!count(glob(database_path()."/migrations/*alter_users_table*.php")))
        $migrations[]= "alter_users_table";

      if(!count(glob(database_path()."/migrations/*create_roles_table*.php"))){
        $migrations[]= "create_roles_table";
        $this->cloneFileData("Role.php",base_path("vendor/benomas/crudvel/src/templates/role.txt"),base_path("app/Models"));
      }

      if(!count(glob(database_path()."/migrations/*create_role_role_table*.php")))
        $migrations[]= "create_role_role_table";

      if(!count(glob(database_path()."/migrations/*create_role_user_table*.php")))
        $migrations[]= "create_role_user_table";

      if(!count(glob(database_path()."/migrations/*create_cat_permission_types_table*.php"))){
        $migrations[]= "create_cat_permission_types_table";
        $this->cloneFileData("CatPermissionType.php",base_path("vendor/benomas/crudvel/src/templates/cat_permission_type.txt"),base_path("app/Models"));
      }

      if(!count(glob(database_path()."/migrations/*create_permissions_table*.php"))){
        $migrations[]= "create_permissions_table";
        $this->cloneFileData("Permission.php",base_path("vendor/benomas/crudvel/src/templates/permission.txt"),base_path("app/Models"));
      }

      if(!count(glob(database_path()."/migrations/*create_permission_role_table*.php")))
        $migrations[]= "create_permission_role_table";

      if(!count(glob(database_path()."/migrations/*create_cat_files_table*.php"))){
        $migrations[]= "create_cat_files_table";
        $this->cloneFileData("CatFile.php",base_path("vendor/benomas/crudvel/src/templates/cat_file.txt"),base_path("app/Models"));
      }

      if(!count(glob(database_path()."/migrations/*create_files_table*.php"))){
        $migrations[]= "create_files_table";
        $this->cloneFileData("File.php",base_path("vendor/benomas/crudvel/src/templates/file.txt"),base_path("app/Models"));
      }

      foreach ($migrations  as $baseName)
        $this->publishMigration($baseName);
    }
    catch(\Exception $e){
      return cvConsoler("\n".cvRedTC('Exception, the proccess fail.'));
    }

    $myFile = json_decode(file_get_contents(base_path('').'/composer.json'));
    $saveFile=false;
    if(!in_array('customs/crudvel',$myFile->autoload->classmap)){
      $myFile->autoload->classmap[]= 'customs/crudvel';
      $saveFile=true;
    }

    if(empty($myFile->autoload->files))
      $myFile->autoload->files=[];

    if(!in_array('vendor/benomas/crudvel/src/Helpers/helper.php',$myFile->autoload->files)){
      $myFile->autoload->files[] = 'vendor/benomas/crudvel/src/Helpers/helper.php';
      $saveFile=true;
    }

    if($saveFile)
      file_put_contents(base_path('').'/composer.json', json_encode($myFile, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES));

    Artisan::call('vendor:publish',['--provider'=>'Benomas\Crudvel\CrudvelServiceProvider']);
    cvConsoler("\n".cvGreenTC(customExec('composer dump-autoload')));
    cvConsoler("\n".cvGreenTC('Crudvel Installation Process Completed')."\n");
  }

  protected function getArguments()
  {
    return [
    ];
  }

  public function publishMigration($baseName){
    $migrationBaseFile = base_path("vendor/benomas/crudvel/src/database/migrations/leftdatetag_".$baseName."_rightdatetag.php");
    //$migrationBaseFile = base_path("database/migrations");
    $migration = file_get_contents($migrationBaseFile);
    $t     = microtime(true);
    $micro   = sprintf("%06d",($t - floor($t)) * 1000000);
    $now     = new \DateTime( date('Y-m-d H:i:s.'.$micro, $t) );
    $rightNow  = $now->format("YmdGisu"); // note at point on "u"
    $leftNow   = $now->format("Y_m_d_Gisu"); // note at point on "u"
    $migration = str_replace('leftdatetag',$leftNow , $migration);
    $migration = str_replace('rightdatetag',$rightNow , $migration);

    if(!file_exists(($fileName=$this->migrationsPath."/".($leftNow)."_".$baseName."_".$rightNow.".php")))
      file_put_contents($fileName, $migration);
    cvConsoler("\n".cvGreenTC(customExec('composer dump-autoload')));
  }

  function cloneFileData($file,$source,$targetFolder){

    if(!file_exists($source))
      return false;

    if(!file_exists($targetFolder))
      mkdir($targetFolder);

    if(!file_exists($targetFolder."/".$file))
      file_put_contents($targetFolder."/".$file, file_get_contents($source));
  }
}

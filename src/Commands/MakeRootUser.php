<?php namespace Crudvel\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Illuminate\Support\Facades\Schema;

class MakeRootUser extends Command {
  protected $signature   = 'make:root-user';
  protected $description = 'Instala paquete';
  protected $name    = "make:root-user";
  protected $migrationPath;

  public function __construct()
  {
    parent::__construct();
  }

  public function handle()
  {
    cvConsoler("\n".cvBlueTC('Creating root user'));
    Schema::disableForeignKeyConstraints();
    try{
      if(file_exists("app/Models/Role.php") && file_exists("app/Models/User.php") ){

        if(!($role = \App\Models\Role::withRoot()->first())){
          if(
            !($role = new \App\Models\Role())->fill([
              "slug"    =>"root",
              "name"    =>"Root",
              "description" =>"Usuario con super privilegios",
              "active"    =>1,
            ])->save()
          )
          {
            return cvConsoler("\n".cvRedTC('Exception, the proccess fail.'));
          }
        }
        if(!($user = \App\Models\User::withUserName("root")->first())){
          $defaultUser = config("packages.benomas.crudvel.crudvel.default_user");
          if(!$defaultUser){
            return cvConsoler("\n".cvRedTC('Exception, the proccess fail.'));
          }
          $defaultUser["password"]=bcrypt($defaultUser["password"]);
          if(!($user = new \App\Models\User())->fill($defaultUser)->save())
          {
            return cvConsoler("\n".cvRedTC('Exception, the proccess fail.'));
          }

        }
        $user->roles()->sync([$role->id]);
      }

    }
    catch(\Exception $e){
      return cvConsoler("\n".cvRedTC('Exception, the proccess fail.'));
    }

    Schema::enableForeignKeyConstraints();
    cvConsoler("\n".cvGreenTC('Root user created')."\n");
  }

  protected function getArguments()
  {
    return [
    ];
  }
}

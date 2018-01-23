<?php 
namespace Benomas\Crudvel\Commands;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Illuminate\Support\Facades\Schema;

class MakeRootUser extends Command {
    protected $signature   = 'make:root-user';
    protected $description = 'Instala paquete';
    protected $name        = "make:root-user";
    protected $migrationPath;
    
    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        echo "Creating root user";
        
        try{
            if(file_exists("app/Models/Role.php") && file_exists("app/Models/User.php") ){

                if(!($role = \App\Models\Role::withRoot()->first())){
                    if(
                        !($role = new \App\Models\Role())->fill([   
                            "slug"        =>"root",
                            "name"        =>"Root",
                            "description" =>"Usuario con super privilegios",
                            "active"      =>1,
                        ])->save()
                    )
                    {
                        echo "Exception, the proccess fail.";
                        return false;
                    }
                }
                if(!($user = \App\Models\User::withUserName("root")->first())){
                    if(
                        !($user = new \App\Models\User())->fill([
                            "username"   =>"root",
                            "first_name" =>"root",
                            "last_name"  =>"root",
                            "email"      =>"root@root.com",
                            "password"   =>bcrypt("root@root.com"),
                        ])->save()
                    )
                    {
                        echo "Exception, the proccess fail.";
                        return false;
                    }

                }
                $user->roles()->sync([$role->id]);
            }
        }
        catch(\Exception $e){
            echo "Exception, the proccess fail.";
            return false;
        }

        echo "Root user created";
    }

    protected function getArguments()
    {
        return [
        ];
    }
}
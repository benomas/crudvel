<?php 
namespace Benomas\Crudvel\Commands;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Illuminate\Support\Facades\Schema;

class MakeRootUser extends Command {
    protected $signature   = 'make:rootUser';
    protected $description = 'Instala paquete';
    protected $name        = "make:rootUser";
    protected $migrationPath;
    
    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        echo "Creating root user";
        try{
            
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

    public function publishMigration($baseName){
    }
}
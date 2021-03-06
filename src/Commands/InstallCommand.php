<?php 
namespace Benomas\Crudvel\Commands;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Illuminate\Support\Facades\Schema;

class InstallCommand extends Command {
    protected $signature   = 'install:crudvel';
    protected $description = 'Instala paquete';
    protected $name        = "install:crudvel";
    protected $migrationPath;
    
    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        echo "Crudvel Installation Process Start";
        try{
            $this->migrationsPath = base_path("database/migrations");
            if(!file_exists($this->migrationsPath))
                mkdir($this->migrationsPath);
            $migrations =[];
            if(!Schema::hasTable("roles"))
                $migrations[]= "create_roles_table";
            if(!Schema::hasTable("role_users"))
                $migrations[]= "create_role_users_table";
            if(!Schema::hasTable("permissions"))
                $migrations[]= "create_permissions_table";
            if(!Schema::hasTable("permission_role"))
                $migrations[]= "create_permission_role_table";
            foreach ($migrations  as $baseName) 
                $this->publishMigration($baseName);
        }
        catch(\Exception $e){
            echo "Exception, the proccess fail.";
            return false;
        }

        $myFile = json_decode(file_get_contents(base_path('').'/composer.json'));
        $saveFile=false;
        if(!in_array('crudvel/customs',$myFile->autoload->classmap)){
            $myFile->autoload->classmap[]= 'crudvel/customs';
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

        shell_exec('php artisan vendor:publish --provider="Benomas\Crudvel\CrudvelServiceProvider"');
        shell_exec('composer dump-autoload');
        echo "Crudvel Installation Process Completed";
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
        $t         = microtime(true);
        $micro     = sprintf("%06d",($t - floor($t)) * 1000000);
        $now       = new \DateTime( date('Y-m-d H:i:s.'.$micro, $t) );
        $rightNow  = $now->format("YmdGisu"); // note at point on "u"
        $leftNow   = $now->format("Y_m_d_Gisu"); // note at point on "u"
        $migration = str_replace('leftdatetag',$leftNow , $migration);
        $migration = str_replace('rightdatetag',$rightNow , $migration);
        
        if(!file_exists(($fileName=$this->migrationsPath."/".($leftNow)."_".$baseName."_".$rightNow.".php")))
            file_put_contents($fileName, $migration);
        shell_exec('composer dump-autoload');
    }
}
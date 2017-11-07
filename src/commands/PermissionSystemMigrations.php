<?php 
namespace Benomas\Crudvel\Commands;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Illuminate\Support\Facades\Schema;

class PermissionSystemMigrations extends Command {
    protected $signature   = 'loadPermissionMigrations';
    protected $description = 'Carga migraciones para soportar roles y permisos';
    protected $name        = "loadPermissionMigrations";
    protected $migrationPath;
    
    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        try{
            $this->migrationsPath = base_path("database/migrations");
            if(!file_exists($this->migrationsPath))
                mkdir($this->migrationsPath);
            $migrations =[];

            if(!Schema::hasTable("roles"))
                $migrations[]= "create_roles_table";
            $migrations[]= "alter_role_users_table";
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
    
        echo "proccess completed";
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
    }
}
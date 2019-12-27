<?php namespace Crudvel\Routes;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class BaseConsole{
  use \Crudvel\Traits\CacheTrait;
  use \Crudvel\Traits\CvPatronTrait;
  private $workspace;
  public function __construct(){
    $this->setWorkspace(fixedSlug(config('app.name')));
  }

  public function getWorkspace(){
    return $this->workspace??null;
  }

  public function setWorkspace($workspace=null){
    $this->workspace = $workspace??null;
    return $this;
  }

  public function caller(...$commands){
    foreach ($commands as $command) {
      $currentCommand = $command['command'] ?? $command;
      $params = $command['params']??null;
      if($params)
        Artisan::call($currentCommand,$params);
      else
        Artisan::call($currentCommand);
    }
  }

  public function loadReTest($callBack=null){
    $callBack = $callBack ?? function (){
      \Crudvel\Routes\BaseConsole::cvIam()->caller('my-test');
    };
    Artisan::command('retest',$callBack)->describe('retest command');
    return $this;
  }

  public function loadTest($callBack=null){
    $callBack = $callBack ?? function (){
      return cvConsoler(cvGreenTC('Test')."\n");
    };
    Artisan::command('my-test',$callBack)->describe('test command');
    return $this;
  }

  public function loadDropTables($callBack=null){
    $callBack = $callBack ?? function () {
      if(config('app.production.env')==='production')
        return cvConsoler(cvBrownTC('Este comando no puede ser ejecutado en ambiente productivo'));

      Artisan::call('logs:clear');
      DB::purge();

      try{DB::statement('DROP DATABASE fake_database');}
      catch(\Exception $e){}

      try{DB::statement('CREATE DATABASE fake_database');}
      catch(\Exception $e){
        Artisan::call('migrate:reset');
      }

      $defaultConnectionName         = config('database.default');
      $defaultConnection             = config('database.connections.'.$defaultConnectionName);
      $originalDatabase              = $defaultConnection['database'];
      $defaultConnection['database'] ='fake_database';
      config(['database.connections.'.$defaultConnectionName=>$defaultConnection]);
      DB::purge();
      try{DB::statement('DROP DATABASE '.$originalDatabase);}
      catch(\Exception $e){}
      try{DB::statement('CREATE DATABASE '.$originalDatabase);}
      catch(\Exception $e){}

      $defaultConnection['database'] =$originalDatabase;
      config(['database.connections.'.$defaultConnectionName=>$defaultConnection]);
      DB::purge();
      try{DB::statement('DROP DATABASE fake_database');}
      catch(\Exception $e){}
      cvConsoler(cvGreenTC('tablas eliminadas'));
    };
    Artisan::command('drop:tables',$callBack)->describe('Elimina todas las tablas');
    return $this;
  }

  public function loadWorskpaceUp($callBack=null){
    $callBack = $callBack ?? function (){
      \Crudvel\Routes\BaseConsole::cvIam()->caller($this->getWorkspace().':dev-refresh',$this->getWorkspace().':light-up');
    };
    Artisan::command($this->getWorkspace().':up', $callBack)->describe('Inicialize proyect from zero');
    return $this;
  }

  public function loadWorskpaceLightUp($callBack=null){
    $callBack = $callBack ?? function (){
      $commands = [
        'migrate',
        ['command'=>'vendor:publish','params'=>['--provider'=>'Benomas\Crudvel\CrudvelServiceProvider']],
        'install:crudvel',
        'migrate',
        'db:seed',
        'passport:install',
        'make:root-user',
      ];

      if(Schema::hasTable('oauth_clients')){
        cvConsoler(cvBrownTC('Ya se habia inicializado el proyecto, se remueven comandos que generarian conflictos')."\n");
        unset($commands[1]);
        unset($commands[2]);
        unset($commands[5]);
        unset($commands[6]);
      }
      \Crudvel\Routes\BaseConsole::cvIam()->caller(...$commands);
      if(config('app.env')!=='production')
        DB::table('oauth_clients')->WHERE('id',2)->UPDATE(['secret'=>'devdevdevdevdevdevdevdevdevdevdevdevdevd']);
    };
    Artisan::command($this->getWorkspace().":light-up {range?}", $callBack)->describe('Inicialize proyect from zero');
    return $this;
  }

  public function loadWorskpaceDown($callBack=null){
    $callBack = $callBack ?? function ($destroyMigrations=null){
      \Crudvel\Routes\BaseConsole::cvIam()->caller('drop:tables');
      if(!empty($destroyMigrations) && $destroyMigrations){
        foreach (glob(database_path().'/migrations/*alter_users_table*.php') as $filename) {
          unlink($filename);
        }
        foreach (glob(database_path().'/migrations/*create_roles_table*.php') as $filename) {
          unlink($filename);
        }
        foreach (glob(database_path().'/migrations/*create_role_user_table*.php') as $filename) {
          unlink($filename);
        }
        foreach (glob(database_path().'/migrations/*create_permissions_table*.php') as $filename) {
          unlink($filename);
        }
        foreach (glob(database_path().'/migrations/*create_permission_role_table*.php') as $filename) {
          unlink($filename);
        }
        foreach (glob(database_path().'/migrations/*create_cat_file_table*.php') as $filename) {
          unlink($filename);
        }
        foreach (glob(database_path().'/migrations/*create_file_table*.php') as $filename) {
          unlink($filename);
        }
      }
    };
    Artisan::command("{$this->getWorkspace()}:down {destroyMigrations?}",$callBack)->describe('Back to empty proyect');
    return $this;
  }

  public function loadTestSeed($callBack=null){
    $callBack = $callBack ?? function (){
      \Crudvel\Routes\BaseConsole::cvIam()->caller(['command'=>'db:seed','params'=>['--class'=>'Database\Seeds\Test\DatabaseSeeder']]);
    };
    Artisan::command('test:seed',$callBack)->describe('Run test seeders');
    return $this;
  }

  public function loadFixBackup($callBack=null){
    $callBack = $callBack ?? function (){
      if(config('app.production.env')==='production')
        return cvConsoler(cvRedTC('Este comando no puede ser ejecutado en ambiente productivo')."\n");

      Schema::disableForeignKeyConstraints();
      try{
        $backupFile      = database_path().'/backups/'.Str::slug(\Crudvel\Routes\BaseConsole::cvIam()->getWorkspace(),'_').'.sql';
        $fixedBackupFile = database_path().'/backups/fixed_'.Str::slug(\Crudvel\Routes\BaseConsole::cvIam()->getWorkspace(),'_').'.sql';
        if(!file_exists($backupFile))
          return cvConsoler(cvRedTC('No existe el respaldo')."\n");

        if(!($sql = file_get_contents($backupFile)))
          return cvConsoler(cvRedTC('No existe el respaldo')."\n");

        $sql = preg_replace('/`.*?`@`.*?`/', 'CURRENT_USER', $sql);
        if(file_exists($fixedBackupFile))
          unlink($fixedBackupFile);

        if(file_exists($fixedBackupFile))
          return cvConsoler(cvRedTC('No se pudo eliminar respaldo anterior')."\n");

        file_put_contents($fixedBackupFile, $sql);
        cvConsoler(cvGreenTC('Respaldo cargado')."\n");
      }
      catch(\Exception $e){
        cvConsoler(cvRedTC('Error al cargar respaldo')."\n");
        Schema::enableForeignKeyConstraints();
      }
      Schema::enableForeignKeyConstraints();
    };
    Artisan::command("fix:backup",$callBack)->describe('Respaldo corregido');
    return $this;
  }

  public function loadLoadBackup($callBack=null){
    $callBack = $callBack ?? function (){
      if(config('app.production.env')==='production')
        return cvConsoler(cvRedTC('Este comando no puede ser ejecutado en ambiente productivo')."\n");
      Schema::disableForeignKeyConstraints();
      try{
        $fixedBackupFile = database_path().'/backups/fixed_'.Str::slug(\Crudvel\Routes\BaseConsole::cvIam()->getWorkspace(),'_').'.sql';

        if(!file_exists($fixedBackupFile))
          return cvConsoler(cvRedTC('No existe el respaldo')."\n");
        Schema::disableForeignKeyConstraints();
        try{
          $currentMax = DB::select('SELECT @@global.max_allowed_packet AS max_allowed_packet');
          $currentMax = $currentMax[0]->max_allowed_packet;
          DB::unprepared('SET GLOBAL max_allowed_packet=524288000');
        }
        catch(\Exception $e){}
          DB::unprepared(file_get_contents($fixedBackupFile));
        try{
          DB::unprepared('SET GLOBAL max_allowed_packet='.((int) $currentMax));
        }
        catch(\Exception $e){}
          Schema::enableForeignKeyConstraints();
        cvConsoler(cvGreenTC('Respaldo cargado')."\n");
      }
      catch(\Exception $e){
        cvConsoler(cvRedTC('Error al cargar respaldo')."\n");
        Schema::enableForeignKeyConstraints();
      }
      Schema::enableForeignKeyConstraints();
    };
    Artisan::command('load:backup',$callBack)->describe('Carga respaldo');
    return $this;
  }

  public function loadReloadBackup($callBack=null){
    $callBack = $callBack ?? function (){
      if(config('app.production.env')==='production')
        return cvConsoler(cvBrownTC('Este comando no puede ser ejecutado en ambiente productivo')."\n");
      \Crudvel\Routes\BaseConsole::cvIam()->caller(
        'drop:tables',
        'fix:backup',
        'load:backup',
        'def-pass',
      );
    };
    Artisan::command('reload:backup',$callBack)->describe('Elimina todas las tablas y carga respaldo');
    return $this;
  }

  public function loadWorkspaceDevRefresh($callBack=null){
    $callBack = $callBack ?? function (){
      Artisan::call('config:cache');
      cvConsoler(cvBrownTC(customExec('composer update'))."\n");
      cvConsoler(cvBrownTC('composer update procesado ')."\n");
      cvConsoler(cvBrownTC(customExec('composer install'))."\n");
      cvConsoler(cvBrownTC('composer install procesado ')."\n");
      cvConsoler(cvBrownTC(customExec('composer update'))."\n");
      cvConsoler(cvBrownTC('composer update procesado ')."\n");
      Artisan::call('cache:clear');
      Artisan::call('config:cache');
      cvConsoler(cvBrownTC(customExec('composer dump-autoload'))."\n");
      cvConsoler(cvBrownTC('composer dump-autoload procesado ')."\n");
    };
    Artisan::command("{$this->getWorkspace()}:dev-refresh",$callBack)->describe('Install dependencies');
    return $this;
  }

  public function loadWorkspaceRefresh($callBack=null){
    $callBack = $callBack ?? function ($skipeDev=null, $range=''){
      if(config('app.production.env')==='production')
        return cvConsoler(cvBrownTC('Este comando no puede ser ejecutado en ambiente productivo')."\n");
      if($skipeDev)
        \Crudvel\Routes\BaseConsole::cvIam()->caller(\Crudvel\Routes\BaseConsole::cvIam()->getWorkspace().":light-refresh $range");
      else
        \Crudvel\Routes\BaseConsole::cvIam()->caller(
          \Crudvel\Routes\BaseConsole::cvIam()->getWorkspace().':down',
          \Crudvel\Routes\BaseConsole::cvIam()->getWorkspace().':up',
          'test:seed',
        );
    };
    Artisan::command("{$this->getWorkspace()}:refresh {skipeDev?} {range?}",$callBack)->describe('Restart the proyect from 0');
    return $this;
  }

  public function loadWorkspaceLightRefresh($callBack=null){
    $callBack = $callBack ?? function ($range=''){
      if(config('app.production.env')==='production')
        return cvConsoler(cvBrownTC('Este comando no puede ser ejecutado en ambiente productivo')."\n");

      \Crudvel\Routes\BaseConsole::cvIam()->caller(
        \Crudvel\Routes\BaseConsole::cvIam()->getWorkspace().':down',
        \Crudvel\Routes\BaseConsole::cvIam()->getWorkspace().":light-up $range",
        'test:seed',
      );
    };
    Artisan::command("{$this->getWorkspace()}:light-refresh {range?}",$callBack)->describe('Restart the proyect from 0');
    return $this;
  }

  public function loadScaff($callBack=null){
    $callBack = $callBack ?? function ($resourceName=null,$template=null){
      if(config('app.production.env')==='production')
        return cvConsoler(cvBrownTC('Este comando no puede ser ejecutado en ambiente productivo')."\n");

      if(!$resourceName)
        return cvConsoler(cvBlueTC('Nombre de recurso requerido')."\n");

      if($template)
        $template = "-s $template";
      else
        $template = '';

      $singularName = fixedSnake(Str::singular($resourceName));
      $pluralName   = fixedSnake(Str::plural($resourceName));

      foreach ([
        "pscaff -a scaff -r $singularName -p $pluralName $template"
      ] as $command) {
        $shellEcho  = customExec($command);
        cvConsoler(cvGreenTC($shellEcho)."\n");
        cvConsoler(cvGreenTC($command.' procesado ')."\n");
      }
    };
    Artisan::command('scaff {resourceName?} {template?',$callBack)->describe('Alias for pscaff scaff command');
    return $this;
  }

  public function loadUnScaff($callBack=null){
    $callBack = $callBack ?? function ($resourceName=null,$template=null){
      if(config('app.production.env')==='production')
        return cvConsoler(cvBrownTC('Este comando no puede ser ejecutado en ambiente productivo')."\n");

      if(!$resourceName)
        return cvConsoler(cvBlueTC('Nombre de recurso requerido')."\n");

      $singularName = fixedSnake(Str::singular($resourceName));
      $pluralName   = fixedSnake(Str::plural($resourceName));

      if($template)
        $template = "-s $template";
      else
        $template = '';

      foreach ([
        "pscaff -a unscaff -r $singularName -p $pluralName $template"
      ] as $command) {
        $shellEcho  = customExec($command);
        cvConsoler(cvGreenTC($shellEcho)."\n");
        cvConsoler(cvGreenTC($command.' procesado ')."\n");
      }
    };
    Artisan::command('unscaff {resourceName?} {template?}',$callBack)->describe('Alias for pscaff unscaff command');
    return $this;
  }

  public function loadMigrateGroup($callBack=null){
    $callBack = $callBack ?? function ($lastSegment=''){
      $paths = assetsMap(database_path("migrations/$lastSegment"),1);
      if(!is_array($paths))
        return ;
      sort($paths);
      foreach ($paths as $key=>$path)
        if(!is_dir(database_path("migrations/$lastSegment/$path")) && $path!=='BaseMigration.php' && !preg_match('/^Custom+/', $path, $matches, PREG_OFFSET_CAPTURE))
          \Crudvel\Routes\BaseConsole::cvIam()->caller(['command'=>'migrate','params'=>['--path'=>"/database/migrations/$lastSegment/$path"]]);
    };
    Artisan::command("migrate-group {lastSegment?}",$callBack)->describe('run migration group');
    return $this;
  }

  public function loadLogsClear($callBack=null){
    $callBack = $callBack ?? function (){
      if(!count(assetsMap(storage_path('logs'))))
        return ;
      $shellEcho = customExec($command='rm ' . storage_path('logs/*'));
      cvConsoler(cvBrownTC("$shellEcho\n"));
      cvConsoler(cvBrownTC($command.' procesado ')."\n");
    };
    Artisan::command("logs:clear",$callBack)->describe('Clear log files');
    return $this;
  }

  public function loadDefPass($callBack=null){
    $callBack = $callBack ?? function (){
      if(config("app.production.env")==="production")
        return cvConsoler(cvBrownTC('Este comando no puede ser ejecutado en ambiente productivo')."\n");
      \App\Models\User::noFilters()->update(["password"=>bcrypt("test")]);
      cvConsoler(cvGreenTC('def-pass procesado ')."\n");
    };
    Artisan::command("def-pass",$callBack)->describe('set test passwords');
    return $this;
  }

  public function loadSetMax($callBack=null){
    $callBack = $callBack ?? function (){
      DB::unprepared('SET GLOBAL max_allowed_packet=524288000');
      DB::unprepared('SET GLOBAL net_read_timeout=600');
      DB::unprepared('SET GLOBAL aria_checkpoint_interval=600');
      DB::unprepared('SET GLOBAL innodb_flushing_avg_loops=600');
      DB::unprepared('SET GLOBAL innodb_sync_spin_loops=600');
      cvConsoler(cvGreenTC('set-max procesado ')."\n");
    };
    Artisan::command("set-max",$callBack)->describe('set mysql maxes');
    return $this;
  }

  public function loadCreateWebClient($callBack=null){
    $callBack = $callBack ?? function (){
      if(DB::table('oauth_clients')->WHERE('oauth_clients.name','web-app')->count())
        return ;

      \Crudvel\Routes\BaseConsole::cvIam()->caller(['command'=>'passport:client','params'=>['--name'=> 'web-app','--password'=>null]]);

      if(config('app.env')!=='production')
        DB::table('oauth_clients')->WHERE('name','web-app')->UPDATE(['secret'=>'devdevdevdevdevdevdevdevdevdevdevdevdevd']);

    };
    Artisan::command("create:web-client",$callBack)->describe('Create password grant client for web app');
    return $this;
  }
}

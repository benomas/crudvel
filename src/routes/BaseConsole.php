<?php namespace Crudvel\Routes;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class BaseConsole{
  use \Crudvel\Traits\CacheTrait;
  use \Crudvel\Traits\CvPatronTrait;
  public $workspace;
  public function __construct(){
    $this->worksPace = fixedSlug(config('app.name'));
  }

  public function caller(...$commands){
    foreach ($commands as $command) {
      if($command['command']==='db:seed')
        cvConsoler(cvBlueTC('A continuacion se ejecutan seeders, este proceso puede tardar varios minutos'));
      Artisan::call($command['command'],$command['params']??[]);
    }
  }

  public function loadDropTables(){
    Artisan::command('drop:tables', function () {
      if(config('app.production.env')==='production'){
        cvConsoler(cvBrownTC('Este comando no puede ser ejecutado en ambiente productivo'));
        return false;
      }

      Artisan::call('logs:clear');
      DB::purge();

      try{DB::statement('DROP DATABASE fake_database');}
      catch(\Exception $e){}

      try{DB::statement('CREATE DATABASE fake_database');}
      catch(\Exception $e){
        Artisan::call('migrate:reset');
        return ;
      }

      $defaultConnectionName         = config('database.default');
      $defaultConnection             = config('database.connections.'.$defaultConnectionName);
      $originalDatabase              = $defaultConnection['database'];
      $defaultConnection['database'] ='fake_database';
      config(['database.connections.'.$defaultConnectionName=>$defaultConnection]);
      DB::purge();
      DB::statement('DROP DATABASE '.$originalDatabase);
      try{DB::statement('DROP DATABASE '.$originalDatabase);}
      catch(\Exception $e){}
        DB::statement('CREATE DATABASE '.$originalDatabase);
      try{DB::statement('CREATE DATABASE '.$originalDatabase);}
      catch(\Exception $e){}

      $defaultConnection['database'] =$originalDatabase;
      config(['database.connections.'.$defaultConnectionName=>$defaultConnection]);
      DB::purge();
      try{DB::statement('DROP DATABASE fake_database');}
      catch(\Exception $e){}
      cvConsoler(cvGreenTC('tablas eliminadas'));

    })->describe('Elimina todas las tablas');
    return $this;
  }

  public function loadWoskpaceUp(){
    Artisan::command($this->worksPace.':up', function (){
      $this->caller($this->worksPace.':dev-refresh',$this->worksPace.':light-up');
    })->describe('Inicialize proyect from zero');
    return $this;
  }

  public function loadWoskpaceLightUp(){
    Artisan::command("$this->worksPace:light-up {range?}", function () {

      $commands = [
        [
          'command'=>'migrate'
        ],
        [
          'command'=>'vendor:publish',
          'params'=>['provider'=>'Benomas\Crudvel\CrudvelServiceProvider']
        ],
        [
          'command'=>'install:crudvel'
        ],
        [
          'command'=>'migrate'
        ],
        [
          'command'=>'db:seed'
        ],
        [
          'command'=>'passport:install'
        ],
        [
          'command'=>'make:root-user'
        ],
      ];

      if(Schema::hasTable('oauth_clients')){
        cvConsoler(cvBrownTC('Ya se habia inicializado el proyecto, se remueven comandos que generarian conflictos'));
        unset($commands[1]);
        unset($commands[2]);
        unset($commands[5]);
        unset($commands[6]);
      }

      $this->caller(...$commands);

      if(config('app.env')!=='production')
        DB::table('oauth_clients')->WHERE('id',2)->UPDATE(['secret'=>'devdevdevdevdevdevdevdevdevdevdevdevdevd']);

    })->describe('Inicialize proyect from zero');
    return $this;
  }

  public function loadWoskpaceDown(){
    Artisan::command("{$this->worksPace}:down {destroyMigrations?}", function ($destroyMigrations=null) {

      $this->caller('drop:tables');

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

    })->describe('Back to empty proyect');
    return $this;
  }
/*

  Artisan::command("$worksPace:down {destroyMigrations?}", function ($destroyMigrations=null) {

    $commands = [
      'php artisan drop:tables'
    ];

    foreach ($commands as $command) {
      $shellEcho  = customExec($command);
      $this->info($shellEcho);
      $this->info($command.' procesado ');
    }

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

  })->describe('Back to empty proyect');

  Artisan::command('test:seed', function () {

    $commands = [
      'php artisan db:seed --class="Database\Seeds\Test\DatabaseSeeder"',
    ];
    foreach ($commands as $command) {
      $shellEcho  = customExec($command);
      $this->info($shellEcho);
      $this->info($command.' procesado ');
    }

  })->describe('Run test seeders');

  Artisan::command('mutate:seed', function () {

    $commands = [
      'php artisan db:seed --class="Database\Seeds\MutateSeeder"',
    ];
    foreach ($commands as $command) {
      $shellEcho  = customExec($command);
      $this->info($shellEcho);
      $this->info($command.' procesado ');
    }

  })->describe('Run mutator seeders');

  Artisan::command('fix:backup', function () use($worksPace) {
    if(config('app.production.env')==='production'){
      $this->info('Este comando no puede ser ejecutado en ambiente productivo');
      return false;
    }
    try{
      $backupFile      = database_path().'/backups/'.str_slug($worksPace,'_').'.sql';
      $fixedBackupFile = database_path().'/backups/fixed_'.str_slug($worksPace,'_').'.sql';
      if(!file_exists($backupFile)){
        $this->info('No existe el respaldo');
        return false;
      }

      $sql = file_get_contents($backupFile);
      if(!$sql){
        $this->info('No existe el respaldo');
        return false;
      }

      $sql = preg_replace('/`.*?`@`.*?`/', 'CURRENT_USER', $sql);
      if(file_exists($fixedBackupFile))
        unlink($fixedBackupFile);

      if(file_exists($fixedBackupFile)){
        $this->info('No se pudo eliminar respaldo anterior');
        return false;
      }

      file_put_contents($fixedBackupFile, $sql);
      $this->info('Respaldo cargado');
    }
    catch(\Exception $e){
      $this->info('Error al cargar respaldo');
      \Illuminate\Support\Facades\enableForeignKeyConstraints();
    }

  })->describe('Respaldo corregido');

  Artisan::command('load:backup', function () use($worksPace) {
    if(config('app.production.env')==='production')
      return $this->info('Este comando no puede ser ejecutado en ambiente productivo');
    try{
      $fixedBackupFile = database_path().'/backups/fixed_'.str_slug($worksPace,'_').'.sql';

      if(!file_exists($fixedBackupFile))
        return $this->info('No existe el respaldo');
      \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();
      try{
        $currentMax = \DB::select('SELECT @@global.max_allowed_packet AS max_allowed_packet');
        $currentMax = $currentMax[0]->max_allowed_packet;
        \DB::unprepared('SET GLOBAL max_allowed_packet=524288000');
      }
      catch(\Exception $e){}
        \DB::unprepared(file_get_contents($fixedBackupFile));
      try{
        \DB::unprepared('SET GLOBAL max_allowed_packet='.((int) $currentMax));
      }
      catch(\Exception $e){}
        \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();
      $this->info('Respaldo cargado');

    }
    catch(\Exception $e){
      $this->info('Error al cargar respaldo');
      \Illuminate\Support\Facades\enableForeignKeyConstraints();
    }

  })->describe('Carga respaldo');

  Artisan::command('reload:backup', function () {
    if(config('app.production.env')==='production'){
      $this->info('Este comando no puede ser ejecutado en ambiente productivo');
      return false;
    }
    $commands = [
      'php artisan drop:tables',
      'php artisan fix:backup',
      'php artisan load:backup',
      'php artisan def-pass',
    ];
    foreach ($commands as $command) {
      customExec($command);
      $this->info($command.' procesado ');
    }
  })->describe('Elimina todas las tablas y carga respaldo');

  Artisan::command("$worksPace:dev-refresh", function () {

    $commands = [
      'php artisan config:cache',
      'composer update',
      'composer install',
      'composer update',
      'php artisan cache:clear',
      'php artisan config:cache',
      'composer dump-autoload',
    ];

    foreach ($commands as $command) {
      $shellEcho  = customExec($command);
      $this->info($shellEcho);
      $this->info($command.' procesado ');
    }

  })->describe('Install dependencies');

  Artisan::command("$worksPace:refresh {skipeDev?} {range?}", function ($skipeDev=null, $range='') use($worksPace){

    if(config('app.production.env')==='production'){
      $this->info('Este comando no puede ser ejecutado en ambiente productivo');
      return false;
    }
    $commands = $skipeDev?["php artisan $worksPace:light-refresh $range"]:[
      "php artisan $worksPace:down",
      "php artisan $worksPace:up",
      "php artisan test:seed",
    ];

    foreach ($commands as $command) {
      $shellEcho  = customExec($command);
      $this->info($shellEcho);
      $this->info($command.' procesado ');
    }

  })->describe('Restart the proyect from 0');

  Artisan::command("$worksPace:light-refresh {range?}", function ($range='') use($worksPace){

    if(config('app.production.env')==='production'){
      $this->info('Este comando no puede ser ejecutado en ambiente productivo');
      return false;
    }

    $commands = [
      "php artisan $worksPace:down",
      "php artisan $worksPace:light-up $range",
      "php artisan test:seed",
    ];

    foreach ($commands as $command) {
      $shellEcho  = customExec($command);
      $this->info($shellEcho);
      $this->info($command.' procesado ');
    }

  })->describe('Restart the proyect from 0');

  Artisan::command("scaff {resourceName?}", function ($resourceName=null){

    if(config('app.production.env')==='production'){
      $this->info('Este comando no puede ser ejecutado en ambiente productivo');
      return false;
    }
    if(!$resourceName)
      return $this->info('Nombre de recurso requerido');

    $commands = [
      "pscaff -a scaff -R $resourceName",
    ];

    foreach ($commands as $command) {
      $shellEcho  = customExec($command);
      $this->info($shellEcho);
      $this->info($command.' procesado ');
    }

  })->describe('Alias for pscaff command');

  Artisan::command("migrate-group {lastSegment?}", function ($lastSegment='') {
    $paths = assetsMap(database_path("migrations/$lastSegment"),1);
    if(!is_array($paths))
      return ;
    sort($paths);
    foreach ($paths as $key=>$path)
      if(!is_dir(database_path("migrations/$lastSegment/$path")) && $path!=='BaseMigration.php' && !preg_match('/^Custom+/', $path, $matches, PREG_OFFSET_CAPTURE)){
        $shellEcho = customExec($command="php artisan migrate --path=/database/migrations/$lastSegment/$path");
        $this->info("$shellEcho\n");
        $this->info($command.' procesado');
      }
  })->describe('run migration group');

  Artisan::command('logs:clear', function() {
    if(!count(assetsMap(storage_path('logs'))))
      return ;
    $shellEcho = customExec($command='rm ' . storage_path('logs/*'));
    $this->info("$shellEcho\n");
    $this->info($command.' procesado ');
  })->describe('Clear log files');

  Artisan::command('def-pass', function () {
    if(config("app.production.env")==="production"){
      $this->info("Este comando no puede ser ejecutado en ambiente productivo");
      return false;
    }
    \App\Models\User::noFilters()->update(["password"=>bcrypt("test")]);
  })->describe('Usuarios de prueba creados');

  Artisan::command('set-max', function () {
    \DB::unprepared('SET GLOBAL max_allowed_packet=524288000');
    \DB::unprepared('SET GLOBAL net_read_timeout=600');
    \DB::unprepared('SET GLOBAL aria_checkpoint_interval=600');
    \DB::unprepared('SET GLOBAL innodb_flushing_avg_loops=600');
    \DB::unprepared('SET GLOBAL innodb_sync_spin_loops=600');
  })->describe('Recalcular ordenes');
*/
}

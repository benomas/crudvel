<?php



if ( ! function_exists('consoleDropTables'))
{
  function consoleDropTables()
  {
    Artisan::command('drop:tables', function () {
    	if(config('app.production.env')==='production'){
    		$this->info('Este comando no puede ser ejecutado en ambiente productivo');
    		return false;
    	}

      DB::purge();
      try{
        DB::statement('DROP DATABASE fake_database');
      }
      catch(\Exception $e){
      }

      DB::statement('CREATE DATABASE fake_database');
      $defaultConnectionName         = config('database.default');
      $defaultConnection             = config('database.connections.'.$defaultConnectionName);
      $originalDatabase              = $defaultConnection['database'];
      $defaultConnection['database'] ='fake_database';
      config(['database.connections.'.$defaultConnectionName=>$defaultConnection]);
      DB::purge();
      DB::statement('DROP DATABASE '.$originalDatabase);
      DB::statement('CREATE DATABASE '.$originalDatabase);
      $defaultConnection['database'] =$originalDatabase;
      config(['database.connections.'.$defaultConnectionName=>$defaultConnection]);
      DB::purge();
      DB::statement('DROP DATABASE fake_database');
    	$this->info('tablas eliminadas');

    })->describe('Elimina todas las tablas');
  }
}

if ( ! function_exists('consoleProyectUp'))
{
  function consoleProyectUp($worksPace)
  {
    if(empty($worksPace))
      return customLog('proyectSlug. Is undefined');
    Artisan::command("$worksPace:up", function () use($worksPace) {

    	$commands = [
    		"php artisan $worksPace:dev-refresh",
    		"php artisan $worksPace:light-up",
    	];

    	foreach ($commands as $command) {
    		if($command==='php db:seed')
    			$this->info('A continuacion se ejecutan seeders, este proceso puede tardar varios minutos');

    		$shellEcho  = customExec($command);
    		$this->info($shellEcho);
    		$this->info($command.' procesado ');
    	}

    	if(config('app.env')!=='production')
    		\DB::table('oauth_clients')->WHERE('id',2)->UPDATE(['secret'=>'devdevdevdevdevdevdevdevdevdevdevdevdevd']);

    })->describe('Inicialize proyect from zero');
  }
}

if ( ! function_exists('consoleProyectLightUp'))
{
  function consoleProyectLightUp($worksPace)
  {
    if(empty($worksPace))
      return customLog('proyectSlug. Is undefined');
    Artisan::command("$worksPace:light-up", function () {

    	$commands = [
    		'php artisan migrate',
    		'php artisan vendor:publish --provider="Benomas\Crudvel\CrudvelServiceProvider"',
    		'php artisan install:crudvel',
    		'php artisan migrate',
    		'php artisan db:seed',
    		'php artisan passport:install',
    		'php artisan make:root-user'
    	];

    	if(\Schema::hasTable('oauth_clients')){
    		$this->info('Ya se habia inicializado el proyecto, se remueven comandos que generarian conflictos');
    		unset($commands[1]);
    		unset($commands[2]);
    		unset($commands[5]);
    		unset($commands[6]);
    	}

    	foreach ($commands as $command) {
    		if($command==='php db:seed')
    			$this->info('A continuacion se ejecutan seeders, este proceso puede tardar varios minutos');

    		$shellEcho  = customExec($command);
    		$this->info($shellEcho);
    		$this->info($command.' procesado ');
    	}

    	if(config('app.env')!=='production')
    		\DB::table('oauth_clients')->WHERE('id',2)->UPDATE(['secret'=>'devdevdevdevdevdevdevdevdevdevdevdevdevd']);

    })->describe('Inicialize proyect from zero');
  }
}

if ( ! function_exists('consoleProyectDown'))
{
  function consoleProyectDown($worksPace)
  {
    if(empty($worksPace))
      return customLog('proyectSlug. Is undefined');
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
    	}

    })->describe('Back to empty proyect');
  }
}

if ( ! function_exists('consoleTestSeed'))
{
  function consoleTestSeed()
  {
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
  }
}

if ( ! function_exists('consoleMutateSeed'))
{
  function consoleMutateSeed()
  {
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
  }
}

if ( ! function_exists('consoleFixBackup'))
{
  function consoleFixBackup($worksPace)
  {
    if(empty($worksPace))
      return customLog('proyectSlug. Is undefined');
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
  }
}

if ( ! function_exists('consoleLoadBackup'))
{
  function consoleLoadBackup($worksPace)
  {
    if(empty($worksPace))
      return customLog('proyectSlug. Is undefined');
    Artisan::command('load:backup', function () use($worksPace) {
    	if(config('app.production.env')==='production')
    		return $this->info('Este comando no puede ser ejecutado en ambiente productivo');
    	try{
        $fixedBackupFile = database_path().'/backups/fixed_'.str_slug($worksPace,'_').'.sql';

        if(!file_exists($fixedBackupFile))
    			return $this->info('No existe el respaldo');
        \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();
      	\DB::unprepared(file_get_contents($fixedBackupFile));
        \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();
    		$this->info('Respaldo cargado');

    	}
      catch(\Exception $e){
    		$this->info('Error al cargar respaldo');
    		\Illuminate\Support\Facades\enableForeignKeyConstraints();
      }

    })->describe('Carga respaldo');
  }
}

if ( ! function_exists('consoleReloadBackup'))
{
  function consoleReloadBackup()
  {
    Artisan::command('reload:backup', function () {
    	if(config('app.production.env')==='production'){
    		$this->info('Este comando no puede ser ejecutado en ambiente productivo');
    		return false;
    	}
    	$commands = [
    		'php artisan drop:tables',
    		'php artisan fix:backup',
    		'php artisan load:backup',
    	];
    	foreach ($commands as $command) {
    		customExec($command);
    		$this->info($command.' procesado ');
    	}
    })->describe('Elimina todas las tablas y carga respaldo');
  }
}

if ( ! function_exists('consoleDevRefresh'))
{
  function consoleDevRefresh($worksPace)
  {
    if(empty($worksPace))
      return customLog('proyectSlug. Is undefined');
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
  }
}

if ( ! function_exists('consoleRefresh'))
{
  function consoleRefresh($worksPace)
  {
    if(empty($worksPace))
      return customLog('proyectSlug. Is undefined');
    Artisan::command("$worksPace:refresh", function () use($worksPace){

    	if(config('app.production.env')==='production'){
    		$this->info('Este comando no puede ser ejecutado en ambiente productivo');
    		return false;
    	}

    	$commands = [
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
  }
}

if ( ! function_exists('consoleLightRefresh'))
{
  function consoleLightRefresh($worksPace)
  {
    if(empty($worksPace))
      return customLog('proyectSlug. Is undefined');
    Artisan::command("$worksPace:light-refresh", function () use($worksPace){

    	if(config('app.production.env')==='production'){
    		$this->info('Este comando no puede ser ejecutado en ambiente productivo');
    		return false;
    	}

    	$commands = [
    		"php artisan $worksPace:down",
    		"php artisan $worksPace:light-up",
    		"php artisan test:seed",
    	];

    	foreach ($commands as $command) {
    		$shellEcho  = customExec($command);
    		$this->info($shellEcho);
    		$this->info($command.' procesado ');
    	}

    })->describe('Restart the proyect from 0');
  }
}

if ( ! function_exists('consolePscaff'))
{
  function consolePscaff()
  {
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
  }
}

if ( ! function_exists('consoleMigrateGroup'))
{
  function consoleMigrateGroup()
  {
    Artisan::command("migrate-group {lastSegment?}", function ($lastSegment='') {
      $paths = assetsMap(database_path("migrations/$lastSegment"),1);
      if(!is_array($paths))
        return ;
      sort($paths);
      foreach ($paths as $key=>$path)
        if(!is_dir(database_path("migrations/$lastSegment/$path")) && $path!=='BaseMigration.php'){
          $shellEcho = customExec($command="php artisan migrate --path=/database/migrations/$lastSegment/$path");
          $this->info("$shellEcho\n");
          $this->info($command.' procesado ');
        }
    })->describe('run migration group');
  }
}
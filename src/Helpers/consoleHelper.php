<?php

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
        'php artisan def-pass',
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
  }
}

if ( ! function_exists('consoleLightRefresh'))
{
  function consoleLightRefresh($worksPace)
  {
    if(empty($worksPace))
      return customLog('proyectSlug. Is undefined');
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
        if(!is_dir(database_path("migrations/$lastSegment/$path")) && $path!=='BaseMigration.php' && !preg_match('/^Custom+/', $path, $matches, PREG_OFFSET_CAPTURE)){
          $shellEcho = customExec($command="php artisan migrate --path=/database/migrations/$lastSegment/$path");
          $this->info("$shellEcho\n");
          $this->info($command.' procesado');
        }
    })->describe('run migration group');
  }
}

if ( ! function_exists('consoleLogsClear'))
{
  function consoleLogsClear()
  {
    Artisan::command('logs:clear', function() {
      if(!count(assetsMap(storage_path('logs'))))
        return ;
      $shellEcho = customExec($command='rm ' . storage_path('logs/*'));
      $this->info("$shellEcho\n");
      $this->info($command.' procesado ');
    })->describe('Clear log files');
  }
}

if ( ! function_exists('consoleDefPass'))
{
  function consoleDefPass()
  {
    Artisan::command('def-pass', function () {
      if(config("app.production.env")==="production"){
        $this->info("Este comando no puede ser ejecutado en ambiente productivo");
        return false;
      }
      \App\Models\User::noFilters()->update(["password"=>bcrypt("test")]);
    })->describe('Usuarios de prueba creados');
  }
}

if ( ! function_exists('consoleSetMySqlMaxes'))
{
  function consoleSetMySqlMaxes()
  {
    Artisan::command('set-max', function () {
      \DB::unprepared('SET GLOBAL max_allowed_packet=524288000');
      \DB::unprepared('SET GLOBAL net_read_timeout=600');
      \DB::unprepared('SET GLOBAL aria_checkpoint_interval=600');
      \DB::unprepared('SET GLOBAL innodb_flushing_avg_loops=600');
      \DB::unprepared('SET GLOBAL innodb_sync_spin_loops=600');
    })->describe('Recalcular ordenes');
  }
}

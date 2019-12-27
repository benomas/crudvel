<?php


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

<?php namespace Crudvel\Commands;

class FixCustomExt extends \Crudvel\Commands\BaseCommand
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'fix-cv-ext';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'laravel publish copy files with his original extensions, so this command change txts, to php';

  /**
   * Create a new command instance.
   *
   * @return void
   */
  public function __construct()
  {
    parent::__construct();
  }

  /**
   * Execute the console command.
   *
   * @return mixed
   */
  public function handle()
  {
    $this->changeext(base_path('customs/crudvel/'),'txt','php');
  }

  private function changeext($directory, $ext1, $ext2) {
    if($curdir = opendir($directory)) {
      while($file = readdir($curdir)) {
        if($file !== '.' && $file !== '..') {
          $srcfile = "$directory/$file";
          if(is_dir($srcfile)){
            $this->changeext($srcfile, $ext1, $ext2);
            continue;
          }

          $fileInfo = pathinfo($file);
          $currentExt = $fileInfo["extension"]??null;
          if ($currentExt===$ext1){
            $newFile = "$directory/{$fileInfo['filename']}.$ext2";
            if(!file_exists($newFile) && !(rename($srcfile, $newFile )))
              cvConsoler(cvRedTC("Error, file $srcfile cant be renamed to $newFile check it manually"). "\n");
            else
              if(file_exists($srcfile)){
                try{
                  unlink($srcfile);
                }catch(\Exception $e){
                  cvConsoler(cvRedTC("Error, file $srcfile cant be deleted check it manually"). "\n");
                }
              }
          }
        }
      }
      closedir($curdir);
    }
  }
}

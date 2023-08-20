<?php
namespace Crudvel\Libraries\Helpers;
use Crudvel\Libraries\Helpers\StaticCasesTrait;

trait StaticResourceTrait
{
  public static function cvSeeds(String $subPath='') {
    $conditionalSegment = !empty($subPath) ? '/' : '';

    $seedFiles = static::scanFilesOnDir(database_path("seeders{$conditionalSegment}{$subPath}"));
    $seeders   = [];

    foreach ($seedFiles as $seed) {
      if($seed===database_path('DatabaseSeeder.php'))
        continue;

      $seederPatter   = str_replace('.php','',str_replace(base_path(),'',$seed));
      $indexSeed      = str_replace('/','-',str_replace("database/seeders/$subPath",'',$seederPatter));
      $classSeparator = '';
      $seederClass = array_reduce(explode('/',$seederPatter),function($sClass,$segment) use(&$classSeparator){
        if ($segment ==='')
          return $sClass;

        $fix = "{$sClass}{$classSeparator}".StaticCasesTrait::cvUcfirstCase($segment);
        $classSeparator = '\\';
        return $fix;
      },'');

      if(class_exists($seederClass))
        $seeders[StaticCasesTrait::cvSlugCase($indexSeed)] = $seederClass;
    }

    return $seeders;
  }

  public static function scanFilesOnDir($source_dir){
    $files    = [];
    $segments = scandir($source_dir);
    $skipes = [
      '.' => true,
      '..' => true,
    ];

    foreach ($segments as $segment) {
      if(($skipes[$segment]??false))
        continue;

      if (!is_dir("{$source_dir}".DIRECTORY_SEPARATOR."{$segment}")){
        $files[]="{$source_dir}".DIRECTORY_SEPARATOR."{$segment}";

        continue;
      }

      $files = array_merge($files,static::scanFilesOnDir("{$source_dir}".DIRECTORY_SEPARATOR."{$segment}"));
    }

    return $files;
  }
}

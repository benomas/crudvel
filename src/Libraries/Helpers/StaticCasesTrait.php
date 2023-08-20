<?php
namespace Crudvel\Libraries\Helpers;

use Illuminate\Support\Str;

trait StaticCasesTrait
{
  public static function cvCamelCase($text) {
    return Str::camel($text);
  }

  public static function cvSlugCase($text) {
    return Str::slug(Str::kebab($text));
  }

  public static function cvSnakeCase($text) {
    return Str::snake(Str::camel($text));
  }

  public static function cvKebabCase($text) {
    return Str::kebab($text);
  }

  public static function cvStudlyCase($text) {
    return Str::studly($text);
  }

  public static function cvSingularCase($text) {
    return config("packages.benomas.crudvel.words.pluralToSingular.$text") ?? Str::singular($text);
  }

  public static function cvPluralCase($text) {
    return config("packages.benomas.crudvel.words.singularToPlural.$text") ?? Str::plural($text);
  }

  public static function cvLowerCase($text) {
    return strtolower($text);
  }

  public static function cvUpperCase($text) {
    return strtoupper($text);
  }

  public static function cvUcfirstCase($text) {
    return ucfirst($text);
  }

  public static function cvTitleCase($text) {
    return Str::title($text);
  }

  public static function cvCaseFixer($path,$text) {
    foreach(explode('|',$path) as $case)
      if(method_exists(static::class,static::cvCamelCase("cv $case Case")))
        $text = static::{static::cvCamelCase("cv $case Case")}($text);

    return $text;
  }
}

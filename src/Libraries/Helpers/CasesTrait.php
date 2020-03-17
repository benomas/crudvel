<?php
namespace Crudvel\Libraries\Helpers;

use Illuminate\Support\Str;

trait CasesTrait
{
  public function cvCamelCase($text) {
    return Str::camel($text);
  }

  public function cvSlugCase($text) {
    return Str::slug(Str::kebab($text));
  }

  public function cvSnakeCase($text) {
    return Str::snake(Str::camel($text));
  }

  public function cvKebabCase($text) {
    return Str::kebab($text);
  }

  public function cvStudlyCase($text) {
    return Str::studly($text);
  }

  public function cvSingularCase($text) {
    return config("packages.benomas.crudvel.words.pluralToSingular.$text") ?? Str::singular($text);
  }

  public function cvPluralCase($text) {
    return config("packages.benomas.crudvel.words.singularToPlural.$text") ?? Str::plural($text);
  }

  public function cvLowerCase($text) {
    return strtolower($text);
  }

  public function cvUpperCase($text) {
    return strtoupper($text);
  }

  public function cvUcfirstCase($text) {
    return ucfirst($text);
  }

  public function cvTitleCase($text) {
    return Str::title($text);
  }

  public function cvCaseFixer($path,$text) {
    foreach(array_reverse(explode('|',$path)) as $case)
      if(method_exists($this,$this->cvCamelCase("cv $case Case")))
        $text = $this->{$this->cvCamelCase("cv $case Case")}($text);

    return $text;
  }
}

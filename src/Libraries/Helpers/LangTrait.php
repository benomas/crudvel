<?php
namespace Crudvel\Libraries\Helpers;

use Illuminate\Support\Str;

trait LangTrait{
  public function cvResourceLang($resource = null){
    $langRelPath = cvCaseFixer('plural|slug',$resource);

    if(!$resource)
      return null;

    return __("crudvel/$langRelPath") ?? null;
  }

  public function cvFieldResourceLang($resource = '',$field=''){
    $resourceLang = $this->cvResourceLang($resource);

    return $resourceLang['fields'][$field] ?? $field;
  }

  public function dynamicFallBack () : string {
    $locale = app()->getLocale();
    $fallBackLocale = config('app.fallback_locale');

    if ($locale === $fallBackLocale)
      return 'en';

    return $fallBackLocale;
  }
}

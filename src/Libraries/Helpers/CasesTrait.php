<?php
namespace Crudvel\Libraries\Helpers;

use Illuminate\Support\Str;

trait CasesTrait
{
  public function cvCamelCase($text) {
    return \Crudvel\Libraries\Helpers\StaticCasesTrait::cvCamelCase($text);
  }

  public function cvSlugCase($text) {
    return \Crudvel\Libraries\Helpers\StaticCasesTrait::cvSlugCase($text);
  }

  public function cvSnakeCase($text) {
    return \Crudvel\Libraries\Helpers\StaticCasesTrait::cvSnakeCase($text);
  }

  public function cvKebabCase($text) {
    return \Crudvel\Libraries\Helpers\StaticCasesTrait::cvKebabCase($text);
  }

  public function cvStudlyCase($text) {
    return \Crudvel\Libraries\Helpers\StaticCasesTrait::cvStudlyCase($text);
  }

  public function cvSingularCase($text) {
    return \Crudvel\Libraries\Helpers\StaticCasesTrait::cvSingularCase($text);
  }

  public function cvPluralCase($text) {
    return \Crudvel\Libraries\Helpers\StaticCasesTrait::cvPluralCase($text);
  }

  public function cvLowerCase($text) {
    return \Crudvel\Libraries\Helpers\StaticCasesTrait::cvLowerCase($text);
  }

  public function cvUpperCase($text) {
    return \Crudvel\Libraries\Helpers\StaticCasesTrait::cvUpperCase($text);
  }

  public function cvUcfirstCase($text) {
    return \Crudvel\Libraries\Helpers\StaticCasesTrait::cvUcfirstCase($text);
  }

  public function cvTitleCase($text) {
    return \Crudvel\Libraries\Helpers\StaticCasesTrait::cvTitleCase($text);
  }

  public function cvCaseFixer($path,$text) {
    return \Crudvel\Libraries\Helpers\StaticCasesTrait::cvCaseFixer($path,$text);
  }
}

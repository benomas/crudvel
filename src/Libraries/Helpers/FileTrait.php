<?php
namespace Crudvel\Libraries\Helpers;

trait FileTrait
{
  public function base64Src($filePath){
    return file_exists($filePath)?'data:'.mime_content_type($filePath).';base64,'.base64_encode(file_get_contents($filePath)):null;
  }

  public function fileBaseName($file=''){
    return preg_replace('/^(.+?)(\.\w+)$/', '$1', $file);
  }

  public function getClassFromFile($path_to_file)
  {
      $contents = file_get_contents($path_to_file);
      $namespace = $class = "";
      $getting_namespace = $getting_class = false;
      foreach (token_get_all($contents) as $token) {
          if (is_array($token) && $token[0] == T_NAMESPACE) {
              $getting_namespace = true;
          }

          if (is_array($token) && $token[0] == T_CLASS) {
              $getting_class = true;
          }

          if ($getting_namespace === true) {
              if(is_array($token) && in_array($token[0], [T_STRING, T_NS_SEPARATOR])) {
                  $namespace .= $token[1];
              }
              else if ($token === ';') {
                  $getting_namespace = false;

              }
          }

          if ($getting_class === true) {
              if(is_array($token) && $token[0] == T_STRING) {
                  $class = $token[1];
                  break;
              }
          }
      }
      return $namespace ? $namespace . '\\' . $class : $class;
  }
}

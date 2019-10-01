<?php
namespace Crudvel\Libraries\Helpers;

trait StringRegexTrait
{
  // Find a exactly word, if it exists then return $string
  function findAndReturn($find, $return, $string){
    if (is_null($string) || strtolower($string) === 'null') return null;
    if($string === $find) return $return;
  }
  // Find a regex and replace, then trim
  function findAndReplace($reg, $replace, $string){
    if (is_null($string) || trim(strtolower($string) === 'null')) return null;
    return trim(preg_replace($reg, $replace, $string));
  }
  // Find a string 'null' and return a real null
  function convertRealNull($string){
    if(is_null($string) || trim(strtolower($string) === 'null')) return null;
    return trim($string);
  }

  // Find in a string if it has alphanumeric, if its true, then return a replace or return 1
  function findAlphaNum($string, $replace=null){
    $res = preg_match('/[\pL|0-9]+/u', $string);
    if($res && !is_null($replace)) return $replace;
    return $res;
  }

  // Find in a string if it has alpha, if its true, then return a replace or return 1
  function findAlpha($string, $replace=null){
    $res = preg_match('/[\pL]+/u', $string);
    if($res && !is_null($replace)) return $replace;
    return $res;
  }
}

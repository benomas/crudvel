<?php
namespace Crudvel\Libraries\Helpers;

trait StringRegexTrait
{
  // Find a exactly word, if it exists then return $string
  function hasAndReturn($find, $return, $string){
    if($string === $find) return $return;
    return null;
  }
  // Find a regex and replace, then trim
  function hasAndReplace($reg, $replace, $string){
    return trim(preg_replace($reg, $replace, $string));
  }
  // Find a string 'null' and return a real null
  function convertRealNull($string){
    if(is_null($string) || trim(strtolower($string) === 'null')) return null;
    return trim($string);
  }
  // Replace a null for something
  function replaceNull($something, $string){
    if(is_null($string) || trim(strtolower($string) === 'null')) return $something;
  }
  // Replace string if it has not alphanumeric
  function hasAlphaNumReplace($string, $replace){
    if(!preg_match('/[\pL|0-9]+/u', $string)) return $replace;
    return trim($string);
  }
  function hasAlphaNum($string){
    return preg_match('/[\pL|0-9]+/u', $string);
  }
  // Replace string if it has not alpha
  function hasAlphaReplace($string, $replace){
    if(!preg_match('/[\pL]+/u', $string)) return $replace;
    return trim($string);
  }
  function hasAlpha($string){
    return preg_match('/[\pL]+/u', $string);
  }
  function removeNewLinesAndSpaces($string){
    return trim(preg_replace('/\s\s+/', ' ', $string));
  }
}

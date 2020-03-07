<?php
include_once('vendor/benomas/crudvel/src/Libraries/CvResource/words.php');

$mixedWords['singularToPlural'] = array_merge($crudvelWords,[
  /**
   * put here singular-plural translation for current proyect
   * when package definitions dosnt match with local interpretation
   *
   */
]);

$mixedWords['pluralToSingular'] = array_merge($crudvelWords,[
  /**
   * put here plural-singular translation for current proyect
   * when package definitions dosnt match with local interpretation
   *
   */
]);

return $mixedWords;

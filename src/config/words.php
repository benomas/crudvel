<?php
include_once('vendor/benomas/crudvel/src/Libraries/CvResource/words.php');
if (empty($crudvelWords))
  $crudvelWords = [];
$mixedWords['singularToPlural'] = array_merge($crudvelWords['singularToPlural']??[],[
  /**
   * put here singular-plural translation for current proyect
   * when package definitions dosnt match with local interpretation
   *
   */
]);

$mixedWords['pluralToSingular'] = array_merge($crudvelWords['pluralToSingular']??[],[
  /**
   * put here plural-singular translation for current proyect
   * when package definitions dosnt match with local interpretation
   *
   */
    'cv_example' => 'new_cv_example',
    'cv example' => 'new cv example',
    'cvExample'  => 'newCvExample',
    'CvExample'  => 'NewCvExample',
    'CVEXAMPLE'  => 'NEWCVEXAMPLE'
]);

return $mixedWords;

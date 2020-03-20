<?php
$crudvelWords = [
  'singularToPlural'=>[
    'cv_example' => 'cv_examples',
    'cv example' => 'cv examples',
    'cvExample'  => 'cvExamples',
    'CvExample'  => 'CvExamples',
    'CVEXAMPLE'  => 'CVEXAMPLEs'
  ]
];
$crudvelWords[ 'pluralToSingular'] = array_flip($crudvelWords['singularToPlural']);

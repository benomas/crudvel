<?php

namespace Crudvel\Libraries\CvScaffSupport\Front\Lang;

use \Crudvel\Interfaces\CvScaffInterface;
use Illuminate\Support\Str;

class CvAdderCrudvuelEnLangScaff extends \Crudvel\Libraries\CvScaffSupport\Front\CvBaseAdderScaff implements CvScaffInterface
{
  protected $relatedFilePath   = 'src/i18n/en/crudvuel.js';
  public function __construct(){
    parent::__construct();
  }
  //[Getters]
  //[End Getters]

  //[Setters]
  //[End Setters]

  //[Stablishers]
  //[End Stablishers]
  protected function fixFile(){
    pdd('asd');
    $fileContent   = $this->getFile();
    preg_match('/import/',$fileContent,$matches);
    if(!count($matches))
      throw new \Exception('Error, crudvuel import section is not defined');
    $slugResource  = fixedSlug(Str::plural($this->getResource()));
    $camelResource = Str::camel(Str::plural($this->getResource()));
    $pattern = '/import\s+'.$camelResource.'\s+from\s+\'\.\/crudvuel\/'.$slugResource.'\'/';
    preg_match($pattern,$fileContent,$matches);
    if(count($matches)){
      cvConsoler(cvBlueTC('no changes required')."\n");
      return $this;
    }
    $pattern='/((?>\s|\S)*)(\s+)?(import(\s+)?(\S+)?(\s+)?from(\s+)?\'\.\/crudvuel\/(\S+)?\'(\s+)?)((?>\s|\S)*)/';
    $replace='$1$2$3$4';
    $fix1 = preg_replace($pattern, $replace, $fileContent);

    return $this->setFile(str_replace($importSection,"$importSection  ,[\"$slugResource\"]{$matches[4]}",$fileContent));
  }
  protected function selfRepresentation(){
    return 'crudvuel';
  }
}

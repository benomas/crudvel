<?php

namespace Crudvel\Libraries\CvScaffSupport;

use \Crudvel\Interfaces\CvScaffInterface;
use Illuminate\Support\Str;

class CvCreatorScaff extends \Crudvel\Libraries\CvScaffSupport\CvBaseCreatorScaff implements CvScaffInterface
{
  protected $relatedTemplatePath = 'vendor/benomas/crudvel/src/templates/cv_scaff.txt';
  protected $suggestedTargetRelatedPaths = [
    'l-api'            => 'app/Http/Controllers/Api/',
    'l-en'             => 'resources/lang/en/',
    'l-encrudvel'      => 'resources/lang/en/crudvel/',
    'l-es'             => 'resources/lang/es/',
    'l-escrudvel'      => 'resources/lang/es/crudvel/',
    'l-migrations'     => 'database/migrations/',
    'l-models'         => 'app/Models/',
    'l-requests'       => 'app/Http/Requests/',
    'l-seeds'          => 'database/seeds/',
    'l-test'           => 'database/seeds/test/',
    'l-factories'      => 'database/seeds/factories/',
    'cv-templates'     => 'vendor/benomas/crudvel/src/templates/',
    'cv-scaff'         => 'vendor/benomas/crudvel/src/Libraries/CvScaffSupport/',
    'cv-back'          => 'vendor/benomas/crudvel/src/Libraries/CvScaffSupport/Back/',
    'cv-apicontroller' => 'vendor/benomas/crudvel/src/Libraries/CvScaffSupport/Back/ApiController/',
    'cv-apiroute'      => 'vendor/benomas/crudvel/src/Libraries/CvScaffSupport/Back/ApiRoute/',
    'cv-langs'         => 'vendor/benomas/crudvel/src/Libraries/CvScaffSupport/Back/Langs/',
    'cv-migration'     => 'vendor/benomas/crudvel/src/Libraries/CvScaffSupport/Back/Migration/',
    'cv-model'         => 'vendor/benomas/crudvel/src/Libraries/CvScaffSupport/Back/Model/',
    'cv-request'       => 'vendor/benomas/crudvel/src/Libraries/CvScaffSupport/Back/Request/',
    'cv-seed'          => 'vendor/benomas/crudvel/src/Libraries/CvScaffSupport/Back/Seed/',
    'cv-apiroute'      => 'vendor/benomas/crudvel/src/Libraries/CvScaffSupport/Back/ApiRoute/',
    'cv-fron'          => 'vendor/benomas/crudvel/src/Libraries/CvScaffSupport/Back/Front',
    'empty'            => '',
  ];

  protected $modeOptions = [
    'creator',
    'adder',
    'remover',
    'fixer',
    'deleter',
  ];

  protected $contextOptions = [
    'back',
    'front',
  ];

  protected $sources= [
    'crudvel' => 'vendor/benomas/crudvel/src/Libraries/CvScaffSupport/scaffTree.json',
    'custom'  => 'crudvel/customs/Scaff/scaffTree.json',
  ];

  protected $source;
  protected $nameSpace;

  public function __construct(){
    parent::__construct();
  }
  //[Getters]
  protected function getTargetFileName(){
    return $this->getAbsolutTargetPath().
      'Cv'.
      Str::studly(Str::singular($this->getParam('target_mode'))).
      Str::studly(Str::singular($this->getParam('resource')).
      'Scaff.php'
    );
  }

  public function getSuggestedTargetRelatedPaths(){
    return $this->suggestedTargetRelatedPaths??[];
  }

  public function getModeOptions(){
    return $this->modeOptions??[];
  }

  public function getContextOptions(){
    return $this->contextOptions??[];
  }

  public function getSources(){
    return $this->sources??[];
  }

  public function getSource(){
    return $this->source??'vendor/benomas/crudvel/src/Libraries/CvScaffSupport/scaffTree.json';
  }

  public function getNameSpace(){
    return $this->nameSpace??'Crudvel\Libraries\CvScaffSupport';
  }
  //[End Getters]

  //[Setters]

  public function setSource($source=null){
    $this->source=$source??'crudvel';
    return $this;
  }

  public function setNameSpace($nameSpace=null){
    $this->nameSpace=$nameSpace??'Crudvel\Libraries\CvScaffSupport';
    return $this;
  }
  //[End Setters]

  //[Stablishers]
  protected function stablishRelatedTargetPath(){
    $fail=false;
    $partialRelatedTargetPath='';
    $aditionalRelatedTargetPathSegmet='';
    do{
      if($fail){
        cvConsoler(
          cvRedTC('invalid path '.
          base_path($partialRelatedTargetPath.$aditionalRelatedTargetPathSegmet).
          " set it again \n")
        );
      }
      $partialRelatedTargetPath = $this->getSuggestedTargetRelatedPaths()[$this->select(
        'Select a related path for target file',
        $this->getSuggestedTargetRelatedPaths(),
        'empty'
      )??''];

      $aditionalRelatedTargetPathSegmet = $this->ask('will you require adicional segment? set empty for no aditional segments');
      $fail=true;
    }while(!file_exists(base_path($partialRelatedTargetPath.$aditionalRelatedTargetPathSegmet)));

    $this->setRelatedTargetPath($partialRelatedTargetPath.$aditionalRelatedTargetPathSegmet);
  }
  protected function stablishAbsolutTargetPath(){
    $this->stablishRelatedTargetPath();
    return $this->setAbsolutTargetPath(base_path($this->getRelatedTargetPath()));
  }
  //[End Stablishers]

  protected function nameSpaceCalculator(){
    $fix1 = str_replace(['vendor/benomas/','src/','/'],['','','\\'],$this->getRelatedTargetPath());
    $fix2 = rtrim($fix1,'\\');
    $fix3 = ucwords($fix2);
    $this->setNameSpace($fix3);
    return $this;
  }

  protected function calculateParams(){
    parent::calculateParams();
    $extraParams = $this->getExtraParams();
    $extraParams['target_mode']=$this->select(
        'Select mode',
        $this->getModeOptions(),
        '0'
      );
    $extraParams['target_context']=$this->select(
        'Select context',
        $this->getContextOptions(),
        '0'
      );
    $this->setSource($this->getSources()[$this->select('Select source',$this->getSources(),'0')]??null);
    $this->nameSpaceCalculator();
    $extraParams['target_namespace'] = $this->getNameSpace();
    return $this->setExtraParams($extraParams);
  }

  private function fixScaffTree(){
    cvConsoler(cvBlueTC('scaffTree will be synchronized')."\n");
    $scaffTree = json_decode(file_get_contents($this->getSource()),true);
    $scaffTree[$this->getParam('target_context')]
      [$this->getParam('target_mode')]
      [fixedSlug(Str::singular($this->getParam('resource')))]
      ['class']=$this->getNameSpace().
        '\Cv'.Str::studly(Str::singular($this->getParam('target_mode'))).
        Str::studly(Str::singular($this->getParam('resource'))).
        'Scaff';
    try{
      file_put_contents($this->getSource(),json_encode($scaffTree,JSON_PRETTY_PRINT));
      cvConsoler(cvBlueTC('scaffTree was synced')."\n");
    }catch(\Exception $e){
      cvConsoler(cvBlueTC('fail to synchronize scaffTree')."\n");
    }
    return $this;
  }

  public function scaff() {
    parent::scaff();
    return $this->fixScaffTree();
  }
}

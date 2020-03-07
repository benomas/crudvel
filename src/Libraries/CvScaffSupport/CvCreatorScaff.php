<?php

namespace Crudvel\Libraries\CvScaffSupport;

use \Crudvel\Interfaces\CvScaffInterface;
use Illuminate\Support\Str;

class CvCreatorScaff extends \Crudvel\Libraries\CvScaffSupport\CvBaseCreatorScaff implements CvScaffInterface
{
  protected $fileExtension='.php';
  protected $relatedTemplatePath = 'vendor/benomas/crudvel/src/templates/cv_scaff.txt';
  protected $suggestedTargetRelatedPaths = [
    'laravel-templates'          => 'customs/crudvel/Scaff/Classes/templates/',
    'laravel-back-scaff'         => 'customs/crudvel/Scaff/Classes/',
    'laravel-back-back'          => 'customs/crudvel/Scaff/Classes/Back/',
    'laravel-back-apicontroller' => 'customs/crudvel/Scaff/Classes/Back/ApiController/',
    'laravel-back-apiroute'      => 'customs/crudvel/Scaff/Classes/Back/ApiRoute/',
    'laravel-back-langs'         => 'customs/crudvel/Scaff/Classes/Back/Langs/',
    'laravel-back-migration'     => 'customs/crudvel/Scaff/Classes/Back/Migration/',
    'laravel-back-model'         => 'customs/crudvel/Scaff/Classes/Back/Model/',
    'laravel-back-request'       => 'customs/crudvel/Scaff/Classes/Back/Request/',
    'laravel-back-seed'          => 'customs/crudvel/Scaff/Classes/Back/Seed/',
    'laravel-back-factory'       => 'customs/crudvel/Scaff/Classes/Back/Factory/',
    'laravel-back-apiroute'      => 'customs/crudvel/Scaff/Classes/Back/ApiRoute/',
    'laravel-front'              => 'customs/crudvel/Scaff/Classes/Front/',
    'laravel-front-lang'         => 'customs/crudvel/Scaff/Classes/Front/Lang/',
    'laravel-front-crud'         => 'customs/crudvel/Scaff/Classes/Front/Crud/',
    'laravel-front-service'      => 'customs/crudvel/Scaff/Classes/Front/Service/',
    'laravel-front-filler'       => 'customs/crudvel/Scaff/Classes/Front/Filler/',
    'laravel-front-definition'   => 'customs/crudvel/Scaff/Classes/Front/Definition/',
    'laravel-front-boot'         => 'customs/crudvel/Scaff/Classes/Front/Boot/',
    'laravel-front-router'       => 'customs/crudvel/Scaff/Classes/Front/Router/',
    'laravel-front-dashboard'    => 'customs/crudvel/Scaff/Classes/Front/Dashboard/',
    'crudvel-templates'          => 'vendor/benomas/crudvel/src/templates/',
    'crudvel-back-scaff'         => 'vendor/benomas/crudvel/src/Libraries/CvScaffSupport/',
    'crudvel-back-back'          => 'vendor/benomas/crudvel/src/Libraries/CvScaffSupport/Back/',
    'crudvel-back-apicontroller' => 'vendor/benomas/crudvel/src/Libraries/CvScaffSupport/Back/ApiController/',
    'crudvel-back-apiroute'      => 'vendor/benomas/crudvel/src/Libraries/CvScaffSupport/Back/ApiRoute/',
    'crudvel-back-langs'         => 'vendor/benomas/crudvel/src/Libraries/CvScaffSupport/Back/Langs/',
    'crudvel-back-migration'     => 'vendor/benomas/crudvel/src/Libraries/CvScaffSupport/Back/Migration/',
    'crudvel-back-model'         => 'vendor/benomas/crudvel/src/Libraries/CvScaffSupport/Back/Model/',
    'crudvel-back-request'       => 'vendor/benomas/crudvel/src/Libraries/CvScaffSupport/Back/Request/',
    'crudvel-back-seed'          => 'vendor/benomas/crudvel/src/Libraries/CvScaffSupport/Back/Seed/',
    'crudvel-back-factory'       => 'vendor/benomas/crudvel/src/Libraries/CvScaffSupport/Back/Factory/',
    'crudvel-back-apiroute'      => 'vendor/benomas/crudvel/src/Libraries/CvScaffSupport/Back/ApiRoute/',
    'crudvel-front'              => 'vendor/benomas/crudvel/src/Libraries/CvScaffSupport/Front/',
    'crudvel-front-lang'         => 'vendor/benomas/crudvel/src/Libraries/CvScaffSupport/Front/Lang/',
    'crudvel-front-crud'         => 'vendor/benomas/crudvel/src/Libraries/CvScaffSupport/Front/Crud/',
    'crudvel-front-service'      => 'vendor/benomas/crudvel/src/Libraries/CvScaffSupport/Front/Service/',
    'crudvel-front-filler'       => 'vendor/benomas/crudvel/src/Libraries/CvScaffSupport/Front/Filler/',
    'crudvel-front-definition'   => 'vendor/benomas/crudvel/src/Libraries/CvScaffSupport/Front/Definition/',
    'crudvel-front-boot'         => 'vendor/benomas/crudvel/src/Libraries/CvScaffSupport/Front/Boot/',
    'crudvel-front-router'       => 'vendor/benomas/crudvel/src/Libraries/CvScaffSupport/Front/Router/',
    'crudvel-front-dashboard'    => 'vendor/benomas/crudvel/src/Libraries/CvScaffSupport/Front/Dashboard/',
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
    'custom'  => 'customs/crudvel/Scaff/scaffTree.json',
  ];

  protected $source;
  protected $nameSpace;

  public function __construct(){
    parent::__construct();
  }
//[Getters]

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
    $absolutePath = null;
    do{
      if($absolutePath!==null){
        cvConsoler(
          cvNegative('invalid path '.
          $absolutePath.
          " set it again \n")
        );
      }
      $partialRelatedTargetPath = $this->getSuggestedTargetRelatedPaths()[$this->select(
        'Select a related path for target file',
        $this->getSuggestedTargetRelatedPaths(),
        'empty'
      )??''];

      $aditionalRelatedTargetPathSegmet = $this->ask('will you require adicional segment? set empty for no aditional segments');
      $absolutePath = base_path($partialRelatedTargetPath.$aditionalRelatedTargetPathSegmet);
      if(!file_exists($absolutePath)){
        if($this->confirm('Path ['.$absolutePath.'] doest exist, do you want to create it?')){
          try{
            mkdir($absolutePath);
          }catch(\Exception $e){
            cvConsoler(
              cvNegative('Error, path  '.
              $absolutePath.
              " set it again \n")
            );
          }
        }
      }
    }while(!file_exists($absolutePath));

    $this->setRelatedTargetPath($partialRelatedTargetPath.$aditionalRelatedTargetPathSegmet);
  }
  protected function stablishAbsolutTargetPath(){
    $this->stablishRelatedTargetPath();
    return $this->setAbsolutTargetPath(base_path($this->getRelatedTargetPath()));
  }
//[End Stablishers]

  protected function calculateTargetFileName(){
    return $this->getAbsolutTargetPath().
      'Cv'.
      Str::studly(Str::singular($this->getParam('target_mode'))).
      Str::studly(Str::singular($this->getParam('resource')).
      'Scaff'.
      $this->getFileExtension()
    );
  }

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
    cvConsoler(cvInfo('scaffTree will be synchronized')."\n");
    $scaffTree = json_decode(file_get_contents($this->getSource()),true);
    $scaffTree[$this->getParam('target_context')]
      [$this->getParam('target_mode')]
      [cvSlugCase(Str::singular($this->getParam('resource')))]
      ['class']=$this->getNameSpace().
        '\Cv'.Str::studly(Str::singular($this->getParam('target_mode'))).
        Str::studly(Str::singular($this->getParam('resource'))).
        'Scaff';
    try{
      file_put_contents($this->getSource(),json_encode($scaffTree,JSON_PRETTY_PRINT));
      cvConsoler(cvInfo('scaffTree was synced')."\n");
    }catch(\Exception $e){
      cvConsoler(cvInfo('fail to synchronize scaffTree')."\n");
    }
    return $this;
  }

  protected function selfRepresentation(){
    return '';
  }

  public function scaff() {
    parent::scaff();
    return $this->fixScaffTree();
  }
}

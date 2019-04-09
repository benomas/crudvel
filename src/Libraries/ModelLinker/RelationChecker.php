<?php
namespace Crudvel\Libraries\ModelLinker;
use DB;

class RelationChecker
{
  // Array that contents all relations to check
  protected $relationArray = [];

  public function __construct($relationArray)
  {
    $this->relationArray = $relationArray;
  }

  public function insertRelationInClass($rel){
    // determino si se inserta en el modelo o en el trait
    $rel['leftTraitFilePath'] = '/app/Traits/tbls/ConglomeradoTrait.php';

    // abro el archivo trait correpondiente al modelo
    // pdd(base_path().$rel['leftTraitFilePath']);
    $fileContents = file_get_contents(base_path().$rel['leftTraitFilePath']);
    // example relation:
    //  public function upmMallaPunto(){
    //   return $this->belongsTo($this->relClass('Muestreos'),'UPMID','UPMID');
    //   }

    // instancio los modelos

   // busco la etiqueta dentro del archivo
      $rightModel = lcfirst(class_basename(base64_decode($rel['encodedRightModel'])));
      $model = new $rel['rightModel'];
      pdd("eschema", $model, $model->getSchema());
      $leftModel = lcfirst(class_basename(base64_decode($rel['encodedLeftModel'])));
      $leftColumn = $rel["leftColumn"];
      $rightColumn = $rel["rightColumn"];
      $schema = 'Catalogos Test';

      $tpl = "\tpublic function ".$rightModel."(){
\t\treturn \$this->belongsTo(\$this->relClass('".$schema."'),'".$leftColumn."','".$rightColumn."');
\t}";
      $fileContents = preg_replace('/(\n\/\/\[?End Relationships\]?)/', "\n".$tpl."$1", $fileContents);
        pdd($fileContents);
      if(preg_replace('/(\n\/\/\[?End Relationships\]?)/', "\n".$tpl."$1", $fileContents)){
        pdd("fjdafda");
      }
    // inserto las nuevas lineas para la relacion
    return true;
  }

  public function checkIfRelationsExistInTraits()
  {
    // iter all relations
    foreach ($this->relationArray as $rel) {
      // check for rightModel (catalogs) exists in left table (details)
      // pdd($rel['leftModel'],lcfirst(class_basename($rel['rightModel'])) );
      $this->insertRelationInClass($rel);
      if (!method_exists($rel['leftModel'], lcfirst(class_basename($rel['rightModel'])))){
      // defino la relacion de la tabla izq
        if (!$this->insertRelationInClass($rel)) pdd("Error al insertar la relacion:", $rel);
      }
      // check for leftModel (details) exists in left table (catalogs)
      if(!method_exists($rel['rightModel'], lcfirst(class_basename($rel['leftModel'])))){
        // defino la relacion de la tabla derecha
        if (!$this->insertRelationInClass($rel)) pdd("Error al insertar la relacion:", $rel);
      }
    }
  }
}

<?php

namespace Crudvel\Interfaces;

interface CvCrudInterface
{
  public function getCamelPluralName();
  public function getCamelSingularName();
  public function getSlugPluralName();
  public function getSlugSingularName();
  public function getSnakePluralName();
  public function getSnakeSingularName();
  public function getStudlyPluralName();
  public function getStudlySingularName();
  public function getControllerClass();
  public function getControllerInstance();
  public function getModelClass();
  public function getModelBuilderInstance();
  public function getModelCollectionInstance();
  public function getRequestClass();
  public function getRequestInstance();
  public function getUserModelClass();
  public function getUserModelBuilderInstance();
  public function getUserModelCollectionInstance();
  public function getPaginatorClass();
  public function getPaginatorInstance();
  public function getCvResourceInstance();
  public function getRows();
  public function getRow();
  public function getCurrentAction();
  public function getCurrentActionKey();
  public function getFields();
  public function setCamelPluralName($camelPluralName=null);
  public function setCamelSingularName($camelSingularName=null);
  public function setSlugPluralName($slugPluralName=null);
  public function setSlugSingularName($slugSingularName=null);
  public function setSnakePluralName($snakePluralName=null);
  public function setSnakeSingularName($snakeSingularName=null);
  public function setStudlyPluralName($studlyPluralName=null);
  public function setStudlySingularName($studlySingularName=null);
  public function setControllerClass($controllerClass=null);
  public function setControllerInstance($controllerInstance=null);
  public function setModelClass($modelClass=null);
  public function setModelBuilderInstance($modelBuilderInstance=null);
  public function setModelCollectionInstance($modelCollectionInstance=null);
  public function setRequestClass($requestClass=null);
  public function setRequestInstance($requestInstance=null);
  public function setUserModelClass($userModelClass=null);
  public function setUserModelBuilderInstance($userModelBuilderInstance=null);
  public function setUserModelCollectionInstance($userModelCollectionInstance=null);
  public function setPaginatorClass($paginatorClass=null);
  public function setPaginatorInstance($paginatorInstance=null);
  public function setRows($rows=null);
  public function setRow($row=null);
  public function setCurrentAction($currentAction=null);
  public function setCurrentActionKey($currentActionKey=null);
  public function setFields($fields=null);
  public function setCvResource($cvResource);
  public function injectCvResource();
  public function autoSetPropertys(...$propertyRewriter);
  public function modelInstanciator($new=false);
  //public function setModelInstance();
  public function loadFields();
}

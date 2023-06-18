<?php

namespace Crudvel\Libraries\DbEngine;
use Crudvel\Libraries\DbEngine\EngineInterface;

class EngineContainer Implements EngineInterface
{
  protected $connectionEngines = [
    'sqlite'  => SqliteEngine::class,
    'mysql'   => MySqlEngine::class,
    'mariadb' => MariaDbEngine::class,
    'pgsql'   => PgsqlEngine::class,
    'sqlsrv'  => SqlsrvEngine::class,
  ];

  protected $dbEngine   = null;

  public function __construct($connection='mysql'){
    $this->setDbEngine($connection);
  }

  public function setDbEngine($connection){
    if(!($conectionEngine = $this->connectionEngines[$connection]??null))
      return $this;

    $this->dbEngine = new $conectionEngine();
    return $this;
  }

  public function getDbEngine(){
    return $this->dbEngine;
  }

  public function getConnection(){
    return $this->getDbEngine()->getConnection()??null;
  }

  public function setFilterQueryString($filterQuery=[]){
    $this->dbEngine->setFilterQueryString($filterQuery);
    return $this;
  }

  public function getFilterQueryString(){
    return $this->getDbEngine()->getFilterQueryString();
  }

  public function getLikeCommand(){
    return $this->getDbEngine()->getLikeCommand();
  }

}

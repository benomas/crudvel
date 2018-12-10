<?php namespace Crudvel\Controllers;
/**
 *
 *
 * @author Benomas benomas@gmail.com
 * @date   2017-05-08
 */

//Se extiende transactionController, para manejo de transacciones
use Carbon\Carbon;

class ApiController extends CustomController
{
    //arreglo de columnas que deben exisistir almenos como null, se requiere para procedimientos
    protected $forceSingleItemPagination =false;
    //arreglo de columnas que deben exisistir almenos como null, se requiere para procedimientos
    protected $forceNulls;
    //bandera que indica si index se carga desde tabla o desde vista
    protected $hasView;
    //arreglo con columnas que se permiten filtrar en paginado, si se setea con false, no se permitira filtrado, si se setea con null o no se setea, no se pondran restricciones en filtrado
    protected $filterables               =null;
    //arreglo con columnas que se permiten ordenar en paginado, si se setea con false, no se permitira ordenar, si se setea con null o no se setea, no se pondran restricciones en ordenar
    protected $orderables                =null;
    //boleado que indica si el controller permite paginacion de forma general
    protected $paginable                 =true;
    //boleado que indica si el controller permite paginacion de forma general
    protected $flexPaginable             =true;
    //boleado que indica si el controller permite paginacion de forma general
    protected $badPaginablePetition      =false;
    //arreglo con columnas que se permiten seleccionar en paginado, si se setea con false, no se permitira seleccionar de forma especifica, si se setea con null o no se setea, no se pondran restricciones en seleccionar de forma especifica
    protected $selectables               =null;
    //mapa de columnas join,
    protected $joinables                 =null;
    //boleado que indica si el ordenamiento sera ascendente o no
    protected $ascending;
    //boleado que indica si se permite filtrado columna por columna
    protected $byColumn;
    //arreglo de columnas a filtrar, cuando byColumn es verdadero, se debera agregar un valor de busqueda por cada columna
    protected $filterQuery;
    //texto que se busca de forma general, cuando byColumn es false
    protected $superFilterQuery;
    //texto que se busca de forma general, cuando byColumn es false
    protected $generalSearch;
    //entero con limite de valores a regresar por paginado
    protected $limit;
    //cadena con nombre de columna por la que se ordenaran los resultados de paginado
    protected $orderBy;
    //entero con pagina que se requiere obtener por paginado
    protected $page;
    //arreglo de columnas a seleccionar
    protected $selectQuery;
    //array de comparaciones validas
    protected $comparators               =["=","<",">","<>","<=",">=","like"];
    //array de comparaciones validas
    protected $logicConnectors           =["and","or"];
    //array de comparaciones validas
    protected $operatorTypes             =["con","com"];
    // current comparator;
    protected $comparator                ="like";
    protected $paginateCount             =0;
    protected $paginateData              =null;

    public function __construct(...$propertyRewriter){
        parent::__construct(...$propertyRewriter);
        $this->addActions("select","resourcePermissions");
    }

    public function resourcesExplode(){
        if(empty($this->langName))
            $this->langName = snake_case(str_plural($this->getCrudObjectName()));
        if(empty($this->rowLabel))
            $this->rowLabel = trans("crudvel/".$this->langName.".row_label");
        if(empty($this->rowsLabel))
            $this->rowsLabel = trans("crudvel/".$this->langName.".rows_label");
        if(empty($this->singularSlug))
            $this->singularSlug = str_slug($this->rowLabel);
        if(empty($this->pluralSlug))
            $this->pluralSlug = str_slug($this->rowsLabel);
        if(empty($this->rowName))
            $this->rowName = snake_case($this->getCrudObjectName());
    }

    public function callAction($method,$parameters=[]){
        return parent::callAction($method,$parameters);
    }
    //web routes
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return ($this->paginable && $this->extractPaginate())?
            $this->paginatedResponse():
            $this->apiSuccessResponse($this->model->get());
    }

    //web routes
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function sluged()
    {
        if($this->setSlugField())
            $this->slugedResponse=true;
        return $this->index();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        die();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $this->setStamps();
        if($this->persist())
            return ($this->paginable && $this->extractPaginate())?
                $this->paginatedResponse():
                $this->noPaginatedResponse();
        return $this->apiFailResponse();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return ($this->paginable && $this->extractPaginate())?
            $this->paginatedResponse():
            $this->noPaginatedResponse();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        die();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        $this->fields["id"] = $id;
        $this->setStamps();
        unset($this->fields["created_by"]);
        if($this->persist())
            return ($paginable = $this->paginable && $this->extractPaginate())?
                $this->paginatedResponse():
                $this->noPaginatedResponse();
        return $this->apiFailResponse();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return $this->model->first()->delete()?
                $this->apiSuccessResponse():
                $this->apiFailResponse();
    }


    public function activate($id){
        $this->fields["status"]=1;
        return $this->update($id);
    }

    public function deactivate($id){
        $this->fields["status"]=0;
        return $this->update($id);
    }

    //todo, fix for joins
    public function select(){
        if(!empty($this->filterables))
            foreach ($this->filterables as $filterable) {
                if(!empty($this->fields[$filterable]))
                    $this->model->where($filterable,$this->fields[$filterable]);
            }

        return $this->apiSuccessResponse(["data"=>$this->model->get()]);
    }

    public function permissions(){
        $actionPermittions=[];
        foreach($this->actions AS $action){
            if(resourceAccess($this->currentUser,str_plural(str_slug($this->crudObjectName)).".".str_slug($action)))
                $actionPermittions[$action]=true;
            else
                $actionPermittions[$action]=false;
        }
        return $this->apiSuccessResponse($actionPermittions);
    }

    protected function setStamps(){
        //$rightNow = Carbon::now()->toDateTimeString();
        $this->fields["created_by"] = $this->request->user()->id??null;
        $this->fields["updated_by"] = $this->request->user()->id??null;
        //$this->fields["created_at"] = $rightNow??null;
        //$this->fields["updated_at"] = $rightNow??null;
    }

    protected function getRequest(){
        $this->fields =  $this->request->all();

        if(isset($this->forceNulls) && is_array($this->forceNulls)){
            foreach($this->forceNulls AS $forceNull){
                if(!isset($this->fields[$forceNull]))
                    $this->fields[$forceNull]=null;
            }
        }
    }
    /**
     * Responde una peticion http de forma paginada de acuerdo a la combinacion de parametros mandados
     *
     * @param model   prefilteredModel  Modelo con filtros default para el servicio a paginar
     *
     * @author Benomas benomas@gmail.com
     * @date   2017-05-08
     * @return respuesta http paginada
     */
    protected function processPaginatedResponse($callBacks=null) {
        //si el modelo no esta definido o es nulo
        if(!isset($this->model) || $this->model===null)
            return ["data"=>[],"count"=>0];
        //add joins
        $this->joins();

        //si existe un array de columnas a seleccionar
        if(customNonEmptyArray($this->selectQuery)){
            if(
                isset($callBacks) &&
                isset($callBacks["selectQuery"]) &&
                is_callable($callBacks["selectQuery"])
            )
                $callBacks["selectQuery"]();
            $this->fixSelectables();
            $this->model->select($this->selectQuery);
        }

        //si existe un array de columnas a filtrar
        if(customNonEmptyArray($this->filterQuery)){
            $this->fixFilterables();
            isset($this->byColumn) && $this->byColumn==1?
                $this->filterByColumn($callBacks):
                $this->filter($callBacks);
        }
        $this->paginateCount = $this->model->count();

        //si se solicita limitar el numero de resultados
        if(isset($this->limit) && $this->limit){
            $this->model->limit($this->limit);
            //si se especifica una pagina a regresar
            if(isset($this->page) && $this->page){
                if($this->paginateCount >= $this->limit * ($this->page-1))
                    $this->model->skip($this->limit * ($this->page-1));
            }
        }

        if (isset($this->orderBy) && $this->orderBy){
            $this->fixOrderBy();
            $direction = isset($this->ascending) && $this->ascending==1?"ASC":"DESC";
            if(
                isset($callBacks) &&
                isset($callBacks["orderBy"]) &&
                isset($callBacks["orderBy"][$this->orderBy]) &&
                is_callable($callBacks["orderBy"][$this->orderBy])
            ){
                $callBacks["orderBy"][$this->orderBy]($direction);
            }
            else
                $this->model->orderBy($this->orderBy,$direction);
        }

        if($this->modelInstance && !empty($this->modelInstance->id))
            $this->model->id($this->modelInstance->id);
    }

    protected function paginateResponder(){
        if(!empty($this->modelInstance->id)|| $this->forceSingleItemPagination)
            $this->paginateData=$this->model->first();

        if(!$this->paginateData && !$this->slugedResponse)
            $this->paginateData = $this->model->get();

        if(!$this->paginateData){
            $keyed = $this->model->get()->keyBy(function ($item) {
                return str_slug($item[$this->slugField]);
            });
            $this->paginateData = $keyed->all();
        }
        return $this->apiSuccessResponse([
            "data"   =>$this->paginateData,
            "count"  =>$this->paginateCount,
            "message" =>trans("crudvel.api.success")
        ]);
    }

    /**
     * Responde una peticion http de forma paginada de acuerdo a la combinacion de parametros mandados
     *
     * @param model   prefilteredModel  Modelo con filtros default para el servicio a paginar
     *
     * @author Benomas benomas@gmail.com
     * @date   2017-05-08
     * @return respuesta http paginada
     */
    protected function paginatedResponse($callBacks=null) {
        $this->processPaginatedResponse($callBacks);
        return $this->paginateResponder();
    }

    public function noPaginatedResponse(){
        //add joins
        $this->joins();

        $response = $this->modelInstance;
        if(count($this->selectables)){
            $this->selectQuery = $this->selectables;
            $this->fixSelectables();
            if($this->modelInstance && $this->modelInstance->id)
                $this->model->id($this->modelInstance->id);
            $response = $this->model->select($this->selectQuery)->first();
        }

        return $this->apiSuccessResponse($response);
    }

    protected function filterByColumn($callBacks) {

        if(isset($callBacks) && isset($callBacks["filters"]) && is_callable($callBacks["filters"]) ){
            $callBacks["filters"]();
            return $this->model;
        }

        $this->model->where(function($query){
            foreach ($this->filterQuery as $field=>$filter){
                if (!$filter)
                    continue;

                if (is_string($filter)){
                    if($this->comparator==="like")
                        $query->where($field,$this->comparator,"%{$filter}%");
                    else
                        $query->where($field,$this->comparator,"{$filter}");
                }
            }
        });
    }

    protected function filter($callBacks) {
        if(!isset($this->generalSearch) || !$this->generalSearch)
            return $this->model;

        if(isset($callBacks) && isset($callBacks["generalFilter"]) && is_callable($callBacks["generalFilter"]) ){
            $callBacks["generalFilter"]();
            return $this->model;
        }
        $this->model->where(function($query){
            foreach ($this->filterQuery as $field=>$filter){
                $method=!isset($method)?"where":"orWhere";
                if($this->comparator==="like")
                    $query->{$method}($field,$this->comparator,"%{$this->generalSearch}%");
                else
                    $query->{$method}($field,$this->comparator,"{$this->generalSearch}");
            }
        });
    }

    /**
     * Carga parametros para paginacion basado en las propiedades pasados desde la peticion http
     *
     * @author Benomas benomas@gmail.com
     * @date   2017-05-08
     * @return boolean      if require pagine or not
     */
    protected function extractPaginate(){

        //si el controller no soporta paginado o si la peticion http no solicita paginación
        if( !isset($this->paginable)    ||
            !$this->paginable
        )
            return false;

        //!$this->request->has("paginate")
        //si la peticion http si solicita paginación
        $paginate = $this->request->get("paginate");

        //si la peticion http solicita paginación de forma incorrecta
        if(!customNonEmptyArray($paginate)){
            if(!$this->flexPaginable)
                return false;
            $paginate["selectQuery"] = $this->selectables;
            $paginate["filterQuery"] = $this->filterables;
            $this->badPaginablePetition=true;
        }
        //si selectables esta definida en false, no se aceptara seleccion personalizada de columnas
        if(isset($this->selectables) && $this->selectables===false){
            $this->selectQuery=false;
        }
        else{
            //definimos las columnas que deberan mandarse como respuesta a la petición
            $this->selectQuery = arrayIntersect(
                isset($paginate["selectQuery"])?$paginate["selectQuery"]:null,
                isset($this->selectables)?$this->selectables:null
            );
            $this->generalSearch=isset($paginate["generalSearch"])?$paginate["generalSearch"]:null;
        }

        //si filterables esta definida en false, no se aceptara filtrado por ninguna columna
        if(isset($this->filterables) && $this->filterables===false){
            $this->filterQuery=false;
        }
        else{
            //definimos las columnas que podran ser filtradas mediante fluent eloquent
            $this->filterQuery = arrayIntersect(
                isset($paginate["filterQuery"])?$paginate["filterQuery"]:null,
                isset($this->filterables)?$this->filterables:null,true
            );

            if(empty($this->filterQuery))
                $this->superFilterQuery = isset($paginate["sQ"])?$paginate["sQ"]:null;

            if(!empty($paginate["comparator"]) && in_array($paginate["comparator"],$this->comparators))
                $this->comparator = $paginate["comparator"];
        }

        //si orderables esta definida en false, no se aceptara ordenado por ninguna columna
        if(isset($this->orderables) && $this->orderables===false){
            $this->orderBy=false;
        }
        else{
            //definimos la columna de ordenamiento
            $this->orderBy = arrayIntersect(
                isset($paginate["orderBy"])?[$paginate["orderBy"]]:null,
                isset($this->orderables)?$this->orderables:null
            );

            if(customNonEmptyArray($this->orderBy)){
                $this->orderBy=$this->orderBy[0];
            }
            else{
                if(is_array($this->orderBy)){
                    $this->orderBy=null;
                }
            }
        }

        //se carga el resto de los parametros para paginar
        if(isset($paginate["limit"]) && fixedIsInt($paginate["limit"]))
            $this->limit=$paginate["limit"];

        if(isset($paginate["page"]) && fixedIsInt($paginate["page"]))
            $this->page=$paginate["page"];

        if(isset($paginate["ascending"]) && $paginate["ascending"])
            $this->ascending=1;

        if(isset($paginate["byColumn"]) && $paginate["byColumn"])
            $this->byColumn=1;

        if(isset($paginate["generalSearch"]))
            $this->generalSearch=$paginate["generalSearch"];

        return true;
    }

    public function superQueryFilterProcesor($mainTableName){
        if(empty($this->superFilterQuery) || empty($this->superFilterQuery[0]["sQ"]))
            return false;
        $this->processSubSuperQuery($mainTableName,$this->superFilterQuery);
    }

    public function processSubSuperQuery($mainTableName,$subSuperQuery,$currentConnector=null,$currentComparator=null,$subQuery=null){
        if(empty($subQuery))
            $subQuery=$this->modelInstance;
        if(isAssoc($subSuperQuery)){
            if(empty($subSuperQuery["sQ"])){
                if(
                    $currentConnector &&
                    $currentComparator &&
                    $this->vLogicarOperator($currentConnector) &&
                    $this->vComparator($currentComparator)
                ){
                    $keys = array_keys($subSuperQuery);
                    if(!empty($keys) && $this->vFilterable($keys[0])){
                        $queryMatch = $subSuperQuery[$keys[0]];
                        if($currentConnector==="and" ){
                            customLog("where ".$mainTableName.$keys[0].$currentComparator."%{$queryMatch}%");
                            if($currentComparator==="like")
                                $subQuery->where($mainTableName.$keys[0],$currentComparator,"%{$queryMatch}%");
                            else
                                $subQuery->where($mainTableName.$keys[0],$currentComparator,"{$queryMatch}");
                        }
                        if($currentConnector==="or"){
                            customLog("orWhere ".$mainTableName.$keys[0].$currentComparator."%{$queryMatch}%");
                            if($currentComparator==="like")
                                $subQuery->orWhere($mainTableName.$keys[0],$currentComparator,"%{$queryMatch}%");
                            else
                                $subQuery->orWhere($mainTableName.$keys[0],$currentComparator,"{$queryMatch}");
                        }
                    }
                }
            }
            else{
                if(isAssoc($subSuperQuery["sQ"])){
                    if(
                        $currentConnector &&
                        $this->vLogicarOperator($currentConnector) &&
                        $this->vOperatorType($subSuperQuery,"com") &&
                        $this->vComparator($subSuperQuery)
                    )
                        $this->processSubSuperQuery($mainTableName,$subSuperQuery["sQ"],$currentConnector,$subSuperQuery["op"],$subQuery);
                }
                else{
                    if(
                        $this->vOperatorType($subSuperQuery,"con") &&
                        $this->vLogicarOperator($subSuperQuery)
                    ){
                        if($currentConnector && $this->vLogicarOperator($currentConnector)){
                            if($currentConnector==="and"){
                                $subQuery->where(function($query) use($mainTableName,$subSuperQuery){
                                    $this->processSubSuperQuery($mainTableName,$subSuperQuery["sQ"],$subSuperQuery["op"],null,$query);
                                });
                            }
                            if($currentConnector==="or"){
                                $subQuery->orWhere(function($query) use($mainTableName,$subSuperQuery){
                                    $this->processSubSuperQuery($mainTableName,$subSuperQuery["sQ"],$subSuperQuery["op"],null,$query);
                                });
                            }
                        }
                        else
                            $this->processSubSuperQuery($mainTableName,$subSuperQuery["sQ"],$subSuperQuery["op"],null,$subQuery);
                    }
                }
            }
        }
        else{
            foreach ($subSuperQuery as $element) {
                if( !empty($element["sQ"])){
                    if($this->vOperatorType($element,"com") && $this->vComparator($element))
                        $this->processSubSuperQuery($mainTableName,$element["sQ"],$currentConnector,$element["op"],$subQuery);
                    else
                        $this->processSubSuperQuery($mainTableName,$element,$currentConnector,null,$subQuery);
                }
            }
        }
    }

    public function vOperatorType($container,$equalTo=null){
        $needle = !empty($container["opT"])?$container["opT"]:$container;
        if(empty($needle))
            return false;
        if(!in_array($needle,$this->operatorTypes))
            return false;
        if($equalTo && $needle!==$equalTo)
            return false;
        return true;
    }

    public function vLogicarOperator($container){
        $needle = !empty($container["op"])?$container["op"]:$container;
        if(empty($needle))
            return false;
        if(!in_array($needle,$this->logicConnectors))
            return false;
        return true;
    }

    public function vComparator($container){
        $needle = !empty($container["op"])?$container["op"]:$container;
        if(empty($needle))
            return false;
        if(!in_array($needle,$this->comparators))
            return false;
        return true;
    }

    public function vFilterable($property){
        if(empty($this->filterables))
            return true;
        if(!$this->filterables)
            return false;
        if(is_array($this->filterables) && in_array($property,$this->filterables))
            return true;
        return false;
    }

    /**
     * This function allow to transform simple selectable column to a table.column field, in this way
     * is posible to direct use joinable columns, the model needs to declare de join tables too, maybe * rewriting call_action function for global resource join, or by action for specific join
     *
     * @param simpleColumns   Array of selectable columns
     *
     * @author Benomas benomas@gmail.com
     * @date   2018-04-04
     * @return array with fixed seletable columns
     */

    public function fixSelectables(){
        $simpleColumns=$this->selectQuery;
        if(!empty($this->joinables) && count($this->joinables))
            foreach ($this->joinables as $joinableKey => $joinableValue)
                foreach ($simpleColumns as $simpleColumnKey => $simpleColumnValue)
                    if(!empty($joinableValue) && $simpleColumnValue===$joinableKey){
                        $this->selectQuery[$simpleColumnKey] = $joinableValue." AS ".$joinableKey;
                        unset($simpleColumns[$simpleColumnKey]);
                    }

        if($simpleColumns && count($simpleColumns))
            foreach ($simpleColumns as $simpleColumnKey => $simpleColumnValue)
                $this->selectQuery[$simpleColumnKey] = $this->mainTableName.$simpleColumnValue;
    }

    /**
     * This function allow to transform simple filterable column to a table.column field, in this way
     * is posible to direct use joinable columns, the model needs to declare de join tables too, maybe * rewriting call_action function for global resource join, or by action for specific join
     *
     * @param simpleColumns   Array of filterable columns
     *
     * @author Benomas benomas@gmail.com
     * @date   2018-04-04
     * @return array with fixed seletable columns
     */

    public function fixFilterables(){
        $simpleColumns=$this->filterQuery;
        if(!empty($this->joinables) && count($this->joinables))
            foreach ($this->joinables as $joinableKey => $joinableValue)
                foreach ($simpleColumns as $simpleColumnKey => $simpleColumnValue)
                    if(!empty($joinableValue) && $simpleColumnKey===$joinableKey){
                        $this->filterQuery[$joinableValue] = $simpleColumnValue;
                        unset($simpleColumns[$simpleColumnKey]);
                        unset($this->filterQuery[$simpleColumnKey]);
                    }

        if($simpleColumns && count($simpleColumns))
            foreach ($simpleColumns as $simpleColumnKey => $simpleColumnValue){
                unset($this->filterQuery[$simpleColumnKey]);
                $this->filterQuery[$this->mainTableName.$simpleColumnKey] = $simpleColumnValue;
            }
    }

    /**
     * This function allow to transform simple orderBy column to a table.column field, in this way
     * is posible to direct use joinable columns, the model needs to declare de join tables too, maybe * rewriting call_action function for global resource join, or by action for specific join
     *
     * @param simpleColumns   string of orderBy
     *
     * @author Benomas benomas@gmail.com
     * @date   2018-04-04
     * @return string with fixed orderBy column
     */
    public function fixOrderBy(){
        if(!empty($this->joinables[$this->orderBy]))
            $this->orderBy = $this->joinables[$this->orderBy];
        else
            $this->orderBy = $this->mainTableName.$this->orderBy;
    }
}

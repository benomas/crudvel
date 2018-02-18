<?php
/**
 *
 *
 * @author Benomas benomas@gmail.com
 * @date   2017-05-08
 */
namespace Crudvel\Controllers;

//Se extiende transactionController, para manejo de transacciones
use Carbon\Carbon;

class ApiController extends CustomController
{
    //arreglo de columnas que deben exisistir almenos como null, se requiere para procedimientos
    protected $forceNulls;
    //bandera que indica si index se carga desde tabla o desde vista
    protected $hasView;
    //arreglo con columnas que se permiten filtrar en paginado, si se setea con false, no se permitira filtrado, si se setea con null o no se setea, no se pondran restricciones en filtrado
    protected $filterables=null;
    //arreglo con columnas que se permiten ordenar en paginado, si se setea con false, no se permitira ordenar, si se setea con null o no se setea, no se pondran restricciones en ordenar
    protected $orderables=null;
    //boleado que indica si el controller permite paginacion de forma general
    protected $paginable=true;
    //arreglo con columnas que se permiten seleccionar en paginado, si se setea con false, no se permitira seleccionar de forma especifica, si se setea con null o no se setea, no se pondran restricciones en seleccionar de forma especifica
    protected $selectables=null;
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
    protected $comparators=["=","<",">","<>","<=",">=","like"];
    //array de comparaciones validas
    protected $logicConnectors=["and","or"];
    //array de comparaciones validas
    protected $operatorTypes=["con","com"];
    // current comparator;
    protected $comparator="like";

    //public function __construct($request,$model){
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
        return empty($this->model)?$this->apiNotFound():parent::callAction($method,$parameters);
    }
    //web routes
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    { 
        return ($paginable = $this->paginable && $this->extractPaginate())?
            $this->paginatedResponse():
            $this->apiSuccessResponse($this->model->get());
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
        return $this->persist()?$this->apiSuccessResponse($this->modelInstance):$this->apiFailResponse();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return ($paginable = $this->paginable && $this->extractPaginate())?
            $this->paginatedResponse():
            $this->apiSuccessResponse(count($this->selectables)?$this->model->select($this->selectables)->first():$this->modelInstance);
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
        $this->fields['id'] = $id;
        $this->setStamps();
        return $this->persist()?$this->apiSuccessResponse($this->modelInstance):$this->apiFailResponse();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->resetTransaction();
        $this->startTranstaction();
        $this->testTransaction(function(){
            return $this->model->first()->delete();
        });
        $this->transactionComplete();
        return $this->isTransactionCompleted()?
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

    public function select(){
        if(!empty($this->filterables))
            foreach ($this->filterables as $filterable) {
                if(!empty($this->fields[$filterable]))
                    $this->model->where($filterable,$this->fields[$filterable]);
            }

        return $this->apiSuccessResponse(['data'=>$this->model->get()]);
    }

    public function permissions(){
        $actionPermittions=[];
        foreach($this->actions AS $action){
            if(resourceAccess($this->currentUser,str_plural(str_slug($this->mainEntityName))."_".$action))
                $actionPermittions[$action]=true;
            else
                $actionPermittions[$action]=false;
        }
        return $this->apiSuccessResponse($actionPermittions);
    }

    protected function setStamps(){
        $rightNow = Carbon::now()->toDateTimeString();
        $this->fields['actualizado_usuarios_id'] = $this->request->user()->id;
        $this->fields['creado_usuarios_id']      = $this->request->user()->id;
        $this->fields['fecha_creacion']          = $rightNow;
        $this->fields['fecha_actualizacion']     = $rightNow;
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
    protected function paginatedResponse($callBacks=null) {

        //si el modelo no esta definido o es nulo
        if(!isset($this->model) || $this->model===null)
            return ['data'=>[],'count'=>0];

        //se define tabla principal de la consulta, necesario para cuando la consulta incluye joins
        $mainTableName = $this->model->getModel()->getTable().'.';

        //si existe un array de columnas a seleccionar
        if(customNonEmptyArray($this->selectQuery)){
            if(
                isset($callBacks) &&
                isset($callBacks['selectQuery']) &&
                is_callable($callBacks['selectQuery'])
            )
                $callBacks['selectQuery']();
            $this->model->select($this->selectQuery);
        }

        //si existe un array de columnas a filtrar
        if(customNonEmptyArray($this->filterQuery))
            isset($this->byColumn) && $this->byColumn==1?
                $this->filterByColumn($mainTableName,$callBacks):
                $this->filter($mainTableName,$callBacks);

        $count = $this->model->count();

        //si se solicita limitar el numero de resultados
        if(isset($this->limit) && $this->limit){
            $this->model->limit($this->limit);
            //si se especifica una pagina a regresar
            if(isset($this->page) && $this->page){
                if($count >= $this->limit * ($this->page-1))
                    $this->model->skip($this->limit * ($this->page-1));
            }
        }

        if (isset($this->orderBy) && $this->orderBy){
            $direction = isset($this->ascending) && $this->ascending==1?"ASC":"DESC";
            if(
                isset($callBacks) &&
                isset($callBacks['orderBy']) &&
                isset($callBacks['orderBy'][$this->orderBy]) &&
                is_callable($callBacks['orderBy'][$this->orderBy])
            ){
                $callBacks['orderBy'][$this->orderBy]($direction);
            }
            else
                $this->model->orderBy($mainTableName.$this->orderBy,$direction);
        }

        if(in_array($this->currentAction,$this->rowActions))
            return ['data'=>$this->model->first(),'count'=>$count];
        return ['data'=>$this->model->get(),'count'=>$count];
    }

    protected function filterByColumn($mainTableName,$callBacks) {

        if(isset($callBacks) && isset($callBacks['filters']) && is_callable($callBacks['filters']) ){
            $callBacks['filters']();
            return $this->model;
        }

        foreach ($this->filterQuery as $field=>$query){
            if (!$query)
                continue;

            if (is_string($query)){
                if($this->comparator==="like")
                    $this->model->where($mainTableName.$field,$this->comparator,"%{$query}%");
                else
                    $this->model->where($mainTableName.$field,$this->comparator,"{$query}");
            }
        }
    }

    protected function filter($mainTableName,$callBacks) {
        if(!isset($this->generalSearch) || !$this->generalSearch)
            return $this->model;

        if(isset($callBacks) && isset($callBacks['generalFilter']) && is_callable($callBacks['generalFilter']) ){
            $callBacks['generalFilter']();
            return $this->model;
        }

        foreach ($this->filterQuery as $field=>$query){
            $method=!isset($method)?"where":"orWhere";
            if($this->comparator==="like")
                $this->model->{$method}($mainTableName.$field,$this->comparator,"%{$this->generalSearch}%");
            else
                $this->model->{$method}($mainTableName.$field,$this->comparator,"{$this->generalSearch}");
        }
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
            !$this->paginable           ||
            !$this->request->has('paginate')
        )
            return false;

        //si la peticion http si solicita paginación
        $paginate = $this->request->get('paginate');

        //si la peticion http solicita paginación de forma incorrecta
        if(!customNonEmptyArray($paginate))
            return false;
        //si selectables esta definida en false, no se aceptara seleccion personalizada de columnas
        if(isset($this->selectables) && $this->selectables===false){
            $this->selectQuery=false;
        }
        else{
            //definimos las columnas que deberan mandarse como respuesta a la petición
            $this->selectQuery = arrayIntersect(
                isset($paginate['selectQuery'])?$paginate['selectQuery']:null,
                isset($this->selectables)?$this->selectables:null
            );
            $this->generalSearch=isset($paginate['generalSearch'])?$paginate['generalSearch']:null;
        }

        //si filterables esta definida en false, no se aceptara filtrado por ninguna columna
        if(isset($this->filterables) && $this->filterables===false){
            $this->filterQuery=false;
        }
        else{
            //definimos las columnas que podran ser filtradas mediante fluent eloquent
            $this->filterQuery = arrayIntersect(
                isset($paginate['filterQuery'])?$paginate['filterQuery']:null,
                isset($this->filterables)?$this->filterables:null,true
            );

            if(empty($this->filterQuery))
                $this->superFilterQuery = isset($paginate['sQ'])?$paginate['sQ']:null;
            
            if(!empty($paginate['comparator']) && in_array($paginate['comparator'],$this->comparators))
                $this->comparator = $paginate['comparator'];
        }

        //si orderables esta definida en false, no se aceptara ordenado por ninguna columna
        if(isset($this->orderables) && $this->orderables===false){
            $this->orderBy=false;
        }
        else{
            //definimos la columna de ordenamiento
            $this->orderBy = arrayIntersect(
                isset($paginate['orderBy'])?[$paginate['orderBy']]:null,
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
        if(isset($paginate['limit']) && fixedIsInt($paginate['limit']))
            $this->limit=$paginate['limit'];

        if(isset($paginate['page']) && fixedIsInt($paginate['page']))
            $this->page=$paginate['page'];

        if(isset($paginate['ascending']) && $paginate['ascending'])
            $this->ascending=1;

        if(isset($paginate['byColumn']) && $paginate['byColumn'])
            $this->byColumn=1;

        if(isset($paginate['generalSearch']))
            $this->generalSearch=$paginate['generalSearch'];

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
}
<?php 
namespace Crudvel\Controllers;

use DB;
use Crudvel\Traits\CrudTrait;
use App\Models\User;
use Illuminate\Routing\Controller as BaseController;
/*
    This is a customization of the controller, some controllers needs to save multiple data on different tables in one step, doing that without transaction is a bad idea.
    so the options is to doing it manually, but it is a lot of code, and it is always the same, to i get that code and put it togheter as methods, so now with the support of
    the anonymous functions, all this code can be reused, saving a lot of time.
*/
class CustomController extends BaseController {

    protected $transStatus;
    protected $committer;
    protected $crudObjectName;
    protected $modelSource;
    protected $requestSource;
    
    //modelo cargado en memoria
    protected $model;
    //validador autorizador anonimo de peticiones http
    protected $request;
    protected $currentAction;
    protected $currentActionId;
    protected $fields;
    //Acciones que se basan en un solo elemento
    protected $currentUser;
    protected $dirtyPropertys;
    protected $actions             = ["index","show","create","store","edit","update","destroy"];
    protected $actionsLangs        = [
        "index"   =>"Listado",
        "show",   =>"Ver",
        "create", =>"Crear",
        "store",  =>"Crear",
        "edit",   =>"Editar",
        "update", =>"Editar",
        "destroy" =>"Eliminar",
    ];
    protected $singleObjectActions = ["show","update","destroy"];
    use CrudTrait;

    public function __construct(...$propertyRewriter){
        $this->autoSetPropertys(...$propertyRewriter);
        $this->setCrudObjectName();
        $this->setModelInstance();
        $this->actionId=null;
    }

    public function setCrudObjectName(){
        if(empty($this->crudObjectName))
            $this->setEntityName();
    }

    public function setModelInstance(){
        $model = $this->modelSource?
            $this->modelSource:
            "App\Models\\".$this->crudObjectName;
        if(is_callable(array($model, 'noFilters')))
            $this->model = $model::noFilters();
    }

    public function setRequestInstance(){
        $request = $this->requestSource?
            $this->requestSource:
            "App\Http\Requests\\".$this->crudObjectName."Request";
        if(is_callable([$request,"capture"])){
            $this->request = app($request);
        }
    }

    public function callAction($method,$parameters=[]){
        $this->currentAction  = $method;
        $this->setRequestInstance();

        if(!in_array($this->currentAction,$this->actions))
            return $this->request->expectsJson()?$this->apiNotFound():$this->webNotFound();

        $this->setCurrentUser();
        if(!resourceAccess($this->currentUser,"inactives"))
            $this->model->actives();
        $preactionResponse = $this->preAction($method,$parameters);
        if($preactionResponse)
            return $preactionResponse;

        if(in_array($method,$this->singleObjectActions)){
            if(empty($parameters))
                return $this->request->expectsJson()?$this->apiNotFound():$this->webNotFound();
            $keys = array_keys($parameters);
            $this->actionId=$parameters[$this->mainArgumentName()];
            if(!$this->model->id($this->actionId)->count())
                return $this->request->expectsJson()?$this->apiNotFound():$this->webNotFound();
        }

        if(in_array($this->request->method(),["POST","PUT"]))
            $this->loadFields();

        return parent::callAction($method,$parameters);
    }

    public function preAction($method,$parameters){} //customize actions before normal ejecution
    public function postAction($next){ //customize actions after normal ejecution
        return $next;
    }

    public function modelator($action){} //$this->model

    protected function resetTransaction(){
        $this->committer   = null;
        $this->transStatus = null;
    }

    /**
     * Initialize control variable and start db transaction
     *
     * @author    Beni (benomas@gmail.com) 2016-12-20
     *
     * @return  void
     */
    protected function startTranstaction($committer=null){

        if( isset($this->transStatus) && $this->transStatus)
            return true;

        if(!isset($this->committer) && !$this->committer){
            if($committer)
                $this->committer = $committer;
        }
        else
            return true;

        $this->transStatus='transaction-in-progress';
        DB::beginTransaction();
    }

    /**
     * When a callback return false, the transaction fail script will be excecuted, changing de status of control variable and launching db rollback
     *
     * @author    Beni (benomas@gmail.com) 2016-12-20
     *
     * @return  data type to return
     */
    protected function transactionFail($cBFail=null){
        $this->transStatus='transaction-fail';
        DB::rollBack();

        if($cBFail && is_callable($cBFail))
            $cBFail();
    }

    /**
     * This method is for close the excecution of transactions, if the current value of the control variable is
     * transaction in progress, it will commit the db and change the control variable tu 'transaction completed'.
     * else some transaction step has been failed and the status of the control variable will be updated tu 'transaction-completed-with-error'
     *
     * @author    Beni (benomas@gmail.com) 2016-12-20
     *
     * @return  void
     */
    protected function transactionComplete($committer=null){

        if( isset($this->committer) &&  $this->committer && $this->committer !== $committer)
            return false;

        if($this->transStatus==='transaction-in-progress'){
            DB::commit();

            $this->transStatus='transaction-completed';
        }
        else
            $this->transStatus='transaction-completed-with-error';
    }

    /**
     * Excecute a transaction, and depends on the result of his anonymouse function , it will prepair de scenario for stop excecuting more transactions or
     * just don touch the current status
     *
     * @author    Beni (benomas@gmail.com) 2016-12-20
     *
     * @param    Anonymouse function    callBack    script to be excecuted has the main purpose of the transaction
     *
     * @param    Anonymouse function    cBFail      script to be excecuted when the transaction fails
     *
     * @param    Anonymouse function    cBSuccess   script to be excecuted when the transaction success
     *
     * @return  void
     */
    protected function testTransaction($callback,$errorCallBack=null,$tryCatch=true){
        $errorException=null;
        if($this->transStatus === 'transaction-in-progress' && is_callable($callback)){
            if($tryCatch){
                try{
                    if(!$callback())
                        $this->transactionFail();
                }
                catch(\Exception $e){
                    $errorException=$e;
                    $this->transactionFail();
                }
            }
            else{
                if(!$callback())
                    $this->transactionFail();
            }
            if($this->transStatus==='transaction-fail' && is_callable($errorCallBack))
                $errorCallBack($errorException);
        }
    }

    /**
     * Check if the curret set of transactions were all successfull completed or someone fail
     *
     * @author    Beni (benomas@gmail.com) 2016-12-20
     *
     * @return  boolean
     */
    protected function isTransactionCompleted(){
        return $this->transStatus==='transaction-completed';
    }

    protected function isThisCommitter($committer=null){

        if( !isset($this->committer))
            return true;
        return $this->committer === $committer;
    }

    public function persist($callBack=null){
        $this->resetTransaction();
        $this->startTranstaction();
        $this->testTransaction(function() use($callBack){
            $this->model = $this->model->findOrNew($this->actionId);
            $this->model->fill($this->fields);
            $this->dirtyPropertys = $this->model->getDirty();
            if(!$this->model->fill($this->fields)->save())
                return false;
            if($callBack && is_callable($callBack))
                return $callBack();
            return true;
        });
        $this->transactionComplete();
        return $this->isTransactionCompleted();
    }

    public function testConection(){

        $serviceCredentials=[
            'cmd'=>'login',
            'usuario'=>'z100',
            'password'=>'153759',
        ];

        $client = curl_init("http://34.194.56.184:8000/ws");
        curl_setopt($client,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($client,CURLOPT_POST,true);
        curl_setopt($client, CURLOPT_POSTFIELDS,$serviceCredentials);

        //paso 5
        $response = curl_exec($client);
        $json     = json_decode($response);
        dd($response);
        if(empty($json->data->token) && 0)
            return null;
    }

}
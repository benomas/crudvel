<?php 
namespace Crudvel\Controllers;

use DB;
use Crudvel\Traits\CrudTrait;
use Illuminate\Routing\Controller as BaseController;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Session;
/*
    This is a customization of the controller, some controllers needs to save multiple data on different tables in one step, doing that without transaction is a bad idea.
    so the options is to doing it manually, but it is a lot of code, and it is always the same, to i get that code and put it togheter as methods, so now with the support of
    the anonymous functions, all this code can be reused, saving a lot of time.
*/
class CustomController extends BaseController {

    protected $crudvel         = true;
    protected $prefix          = "";
    protected $classType       = "Controller";
    protected $baseClass       = "";
    public $resource;
    //public $baseResourceUrl;
    protected $transStatus;
    protected $committer;
    protected $crudObjectName;
    protected $modelSource;
    protected $requestSource;
    public $rows;
    public $row;
    public $rowName;
    public $rowsName;
    //modelo cargado en memoria
    protected $model;
    protected $modelInstance;
    protected $userModel;
    //validador autorizador anonimo
    protected $request;
    protected $currentAction;
    protected $currentActionId =null;
    protected $fields;
    protected $defaultFields;
    //Acciones que se basan en un solo elemento
    protected $currentUser;
    protected $dirtyPropertys;
    protected $langName;
    protected $debugg          = false;
    protected $actions         = [
        "index",
        "show",
        "create",
        "store",
        "edit",
        "update",
        "destroy",
        "active",
        "deactive",
        "import",
        "importing",
        "export",
        "exporting",
    ];
    protected $keyActions = [
        "show",
        "edit",
        "update",
        "destroy",
        "active",
        "deactive"
    ];
    protected $viewActions = [
        "index",
        "show",
        "create",
        "edit",
        "import",
    ];
    protected $rowsActions = [
        "index",
        "import",
        "importing",
        "export",
        "exporting",
    ];
    use CrudTrait;

    public function __construct(...$propertyRewriter){
        $this->autoSetPropertys(...$propertyRewriter);
        $this->explodeClass();
        $this->setModelInstance();
    }

    public function modelInstanciator($new=false){
        $model = $this->modelSource = $this->modelSource?
            $this->modelSource:
            "App\Models\\".$this->getCrudObjectName();
        if($new)
            return new $model();
        return $model::noFilters();
    }

    public function setModelInstance(){
        $this->model = $this->modelInstanciator();
    }

    public function setRequestInstance(){
        $request = $this->requestSource?
            $this->requestSource:
            "App\Http\Requests\\".$this->getCrudObjectName()."Request";
        
        if(is_callable([$request,"capture"])){
            $this->request = app($request);
        }
    }

    public function callAction($method,$parameters=[]){
        $this->currentAction  = $method;
        $this->setRequestInstance();

        if(!in_array($this->currentAction,$this->actions))
            return $this->request->wantsJson()?$this->apiNotFound():$this->webNotFound();

        $this->setCurrentUser();
        if(!resourceAccess($this->userModel,"inactives"))
            $this->model->actives();
        $this->loadFields();
        $preactionResponse = $this->preAction($method,$parameters);
        if($preactionResponse)
            return $preactionResponse;
        if(in_array($method,$this->keyActions)){
            if(empty($parameters))
                return $this->request->wantsJson()?$this->apiNotFound():$this->webNotFound();
            $this->currentActionId=$parameters[$this->mainArgumentName()];
            if(!$this->model->id($this->currentActionId)->count())
                return $this->request->wantsJson()?$this->apiNotFound():$this->webNotFound();
            $this->modelInstance =  $this->model->first();
        }
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
            if($tryCatch && !$this->debugg){
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
             if($this->transStatus==='transaction-fail' && is_callable($errorCallBack) && !$this->debugg)
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

    /**
     * Check if the curret set of transactions has fail status
     *
     * @author    Beni (benomas@gmail.com) 2016-12-20
     *
     * @return  boolean
     */
    protected function isFailedTransaction(){
        return $this->transStatus==='transaction-fail';
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
            $this->modelInstance->fill($this->fields);
            $this->dirtyPropertys = $this->modelInstance->getDirty();
            if(!$this->modelInstance->save())
                return false;
            if($callBack && is_callable($callBack))
                return $callBack();
            return true;
        });
        $this->transactionComplete();
        return $this->isTransactionCompleted();
    }

    public function export(){
        $data = [];
        $this->request->langsToImport($this->modelInstanciator(true)->getFillable());
        if(($rows = $this->model->get()))
            foreach($rows as $key=>$row)
                foreach ($this->request->exportImportProperties as $label=>$field)
                    $data[$key][] = $this->request->exportPropertyFixer($field,$row);

        array_unshift($data, array_keys($this->request->exportImportProperties));
        Excel::create(trans("crudvel/".$this->langName.".rows_label")." ".intval(microtime(true)), function ($excel) use ($data) {
            $excel->sheet('Hoja1', function ($sheet) use ($data) {
                $sheet->fromArray($data, "", "A1", true, false);
            });
        })->export('xlsx');
    }

    public function importing(){
        $fail=true;
        ini_set('max_execution_time',300);

        if ($this->request->hasFile('importation_file')) {
            $fail=false;
            $extension = $this->request->file('importation_file')->getClientOriginalExtension();
            $name      = uniqid();
            $filename  = $name . "." . $extension;
            $path      = public_path() . "/upload/importing/" . $filename;

            $this->request->file('importation_file')->move(public_path() . "/upload/importing/", $filename);
            $reader = Excel::load($path)->get();
            $this->request->inicializeImporter($this->modelInstanciator(true)->getFillable());
            $reader->each(function ($row){
                $this->resetTransaction();
                $this->startTranstaction();
                $this->testTransaction(function() use($row){
                    $this->request->firstImporterCall($row);
                    $this->request->fields = [];
                    foreach ($this->request->exportImportProperties as $label=>$field)
                        if(($dataFiled = $this->request->importPropertyFixer($label,$row))!==null)
                            $this->request->fields[$field] = $dataFiled;

                    if(!$this->request->validateImportingRow($row)){
                        $this->request->changeImporter("validationErrors",$this->request->currentValidator->errors());
                        return false;
                    }

                    $this->request->changeImporter();
                    if(($model = (  $this->request->currentAction==="store"?
                                        $this->modelInstanciator(true):
                                        $this->modelInstanciator()->id($row->{$this->request->slugedImporterRowIdentifier()})->first()
                                )
                        )
                    ){
                        $model->fill($this->request->fields);
                        if(!$model->isDirty()){
                            $this->request->changeTransactionType("Sin cambios");
                            return $this->importCallBack($model,$row);
                        }
                        if($model->save())
                            return $this->importCallBack($model,$row);
                        
                        $this->request->changeImporter("validationErrors",'Error de transacción');
                    }
                    return false;
                },null,false);
                $this->transactionComplete();
            });
            @unlink($path);
        }
        
        if($this->request->wantsJson())
            return $fail?$this->apiFailResponse():$this->apiSuccessResponse(["data"=>$this->request->importResults,"status"=>trans("crudvel.api.success")]);

        Session::flash('importResults', $this->request->importResults);
        return $fail?$this->webFailResponse():$this->webSuccessResponse();
    }

    public function importCallBack($model,$row){
        return true;
    }

    public function addActions(...$moreActions){
        $this->actions=array_merge($this->actions,$moreActions);
    }

    public function addKeyActions(...$moreActions){
        $this->keyActions=array_merge($this->keyActions,$moreActions);
    }

    public function addViewActions(...$moreActions){
        $this->viewActions=array_merge($this->viewActions,$moreActions);
    }
}
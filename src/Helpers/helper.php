<?php
if ( ! function_exists('array_fetch'))
{
    /**
     * Fetch a flattened array of a nested array element.
     *
     * @param  array   $array
     * @param  string  $key
     * @return array
     */
    function array_fetch($array, $key)
    {
        $results = array();
        foreach (explode('.', $key) as $segment)
        {
            foreach ($array as $value)
            {
                if (array_key_exists($segment, $value = (array) $value))
                {
                    $results[] = $value[$segment];
                }
            }
            $array = array_values($results);
        }
        return collect(array_values($results));
    }
}
/*
*custom helper, made by Beni at 2016-11-22, the principal porpouse is to bind DFP html from array.
*/
if(!function_exists("requestToModel")){
	/**
	 * load data only if exist, this function is necesary, because when request->field recibe an empty
	 * data, and it is assigned to a numeric field, php change the value to 0. other ways if you need to save
	 * a field not present in the request has a null or has empty string, you can then use the anonnymouse function
	 *
	 * @author	Beni (benomas@gmail.com) 2016-12-22
	 *
	 * @param	array	instances	the collection of the instances to check and assign from the request
	 * @param	moderlObject	modelObject, this params is necesary for update purposes
	 *
	 * @return  object or array
	 */
	function requestToModel($instances){
		$data=[];
		foreach($instances AS $intance){
			if(is_callable($intance)){
				$intance($data);
			}
			else{
				$segments = explode(".",$intance);
				$field = $segments[count($segments)-1];
				if(\Illuminate\Support\Facades\Input::has($intance)){
					$data[$field]=Input::get($intance);
				}
			}
		}
		return $data;
	}
}
if(!function_exists("toModelWithRequest")){
	/**
	 * load data only if exist, this function is necesary, because when request->field recive an empty
	 * data, and it is assigned to a numeric field, php change the value to 0. other ways if you need to save
	 * a field not present in the request has a null or has empty string, you can then use the anonnymouse function
	 *
	 * @author	Beni (benomas@gmail.com) 2016-12-22
	 *
	 * @param	array	instances	the collection of the instances to check and assign from the request
	 * @param	moderlObject	modelObject, this params is necesary for update purposes
	 *
	 * @return  object or array
	 */
	function toModelWithRequest($instances,$request){
		$data=[];
		foreach($instances AS $intance){
			if(is_callable($intance)){
				$intance($data);
			}
			else{
				$segments = explode(".",$intance);
				$field = $segments[count($segments)-1];
				if($request->has($intance)){
					$data[$field]=$request->get($intance);
				}
			}
		}
		return $data;
	}
}
if(!function_exists("factorial")){
	/**
	 * calculate factorial of n
	 *
	 * @author	Beni (benomas@gmail.com) 2017-01-18
	 *
	 * @param	iny	n	number for calculate the factorial
	 *
	 * @return  int factorial
	 */
	function factorial($n)
	{
		if($n===1)
			return 1;
		return $n * factorial($n-1);
	}
}
if(!function_exists("dateFormatSwitch")){
	/**
	 * switch between date format
	 *
	 * @author	Beni (benomas@gmail.com) 2017-01-18
	 *
	 * @param	string	date	date to convert
	 * @param	string	originFormant	format origin of the date
	 * @param	string	destineFormat	format destine of the date
	 *
	 * @return  string converted date
	 */
	function dateFormatSwitch($date,$originFormant="Y/m/d", $destineFormat="Y-m-d"){
		if( !isset($date) || !$date)
			return null;
		return \Carbon\Carbon::createFromFormat($originFormant,$date)->format($destineFormat);
	}
}
/**
 * check current role
 *
 * @param array    roles  list of target roles to compare
 *
 * @author Benomas benomas@gmail.com
 * @date   2017-05-24
 * @return boolean
 */
/*
if(!function_exists("inRoles")){
	function inRoles(...$roles){
		return !($user = \Cartalyst\Sentinel\Laravel\Facades\Sentinel::getUser())?
			0:
			$user->roles()->whereIn("roles.slug",$roles)->count();
	}
}
*/
if(!function_exists("ffDebugg")){
	function ffDebugg(...$params){
		dd($params);
	}
}
//alias of ffDebugg for fast call
if(!function_exists("audit")){
	function audit(...$params){
		dd($params);
	}
}
//alias of ffDebugg for fast call
if(!function_exists("capitalizeWithAccents")){
	function capitalizeWithAccents($originalString){
		if(!$originalString)
			return null;
		return str_replace(
			"á","Á",
			str_replace(
				"é","É",
				str_replace(
					"í","Í",
					str_replace(
						"ó","Ó",
						str_replace(
							"ú","Ú",
							str_replace(
								"ñ","Ñ",
								strtoupper($originalString)
							)
						)
					)
				)
			)
		);
	}
}
if(!function_exists("renameValidationKey")){
	function renameValidationKey(&$rules, $oldKey,$newKey){
		if(is_array($rules) && isset($rules[$oldKey]) && !isset($rules[$newKey])){
			$rules[$newKey] =  $rules[$oldKey];
			unset($rules[$oldKey]);
		}
	}
}
if(!function_exists("reloadFormValue")){
	function reloadFormValue($inputName,$model=null,$columnName=null,$otherValue=null){
		if($otherValue)
			return $otherValue;
		if(\Input::old($inputName)!==null)
			return \Input::old($inputName);
		if($model){
			if($columnName && isset($model->$columnName))
				return $model->$columnName;
			if($inputName && isset($model->$inputName))
				return $model->$inputName;
		}
		return "";
	}
}
if(!function_exists("customNonEmptyArray")){
	/**
	 * Verifica si el parametro mandado es diferente de null, es un array, y tiene almenos un elemento
	 *
	 * @param array   testArray  la variable a probar
	 *
	 * @author Benomas benomas@gmail.com
	 * @date   2017-05-08
	 * @return boolean
	 */
	function customNonEmptyArray($testArray){
		return $testArray && is_array($testArray) && count($testArray);
	}
}
if(!function_exists("arrayIntersect")){
	/**
	 * Si los dos parametros pasados son customNonEmptyArrays, entonces
	 * retorna un arreglo con solo los elementos que se repiten en ambos arreglos,
	 * si el primer parametro no es un customNonEmptyArray pero el segundo si, entonces,
	 * regresa un arreglo con todos los elementos del segundo arreglo, si ninguno de los arreglos
	 * es un customNonEmptyArray, entonces regresa null
	 *
	 * @param array   array1  primer arreglo
	 *
	 * @param array   array2  segundo arreglo
	 *
	 * @author Benomas benomas@gmail.com
	 * @date   2017-05-08
	 * @return array or null
	 */
	function arrayIntersect($array1=null,$array2=null,$isAsociative=false){
		//si el primer parametro no es un customNonEmptyArray
		if(!customNonEmptyArray($array2))
			return customNonEmptyArray($array1)? $array1:null;
		//si el segundo parametro no es un customNonEmptyArray
		if(!customNonEmptyArray($array1))
			return null;
		//si ambos parametros son customNonEmptyArrays
		$result = [];
		if($isAsociative){
			foreach ($array1 as $key=>$value)
				if(in_array($key,$array2))
					$result[$key]=$value;
		}
		else{
			foreach ($array2 as $value)
				if(in_array($value,$array1))
					$result[]=$value;
		}
		return $result;
	}
}
if(!function_exists("concatToArray")){
	/**
	 * Concatena un prefijo a cada elemento del array
	 *
	 * @param string   prefix  string con el valor a concatenar
	 *
	 * @author Benomas benomas@gmail.com
	 * @date   2017-05-08
	 * @return array
	 */
	function concatToArray($prefix=null,$baseArray=null){
		if(!customNonEmptyArray($baseArray))
			return $baseArray;
		foreach ($baseArray as $key=>$column) {
		}
	}
}
if(!function_exists("versionedAsset")){
	/**
	 * Genera la ruta para un asset, agregando su fecha de creacion, lo que permite controlar la forma como el navegador maneja el cache
	 *
	 * @param string   file  string con el valor a concatenar
	 *
	 * @author Benomas benomas@gmail.com
	 * @date   2017-05-08
	 * @return array
	 */
	function versionedAsset($file){
		return asset($file)."?creation=".filemtime("../public/".$file);
	}
}
if(!function_exists("trueCount")){
	/**
	 * Cuenta los valores true al evaluar un array de expresiones
	 *
	 * @param string   expresions  array con expresiones logicas
	 *
	 * @author Benomas benomas@gmail.com
	 * @date   2017-05-08
	 * @return array
	 */
	function trueCount(...$expresions){
		$trues=0;
		foreach ($expresions as $expresion) {
			if($expresion)
				$trues++;
		}
		return $trues;
	}
}
if(!function_exists("classTrans")){
	/**
	 * Cuenta los valores true al evaluar un array de expresiones
	 *
	 * @param string   expresions  array con expresiones logicas
	 *
	 * @author Benomas benomas@gmail.com
	 * @date   2017-05-08
	 * @return array
	 */
	function classTrans($person,$gender,$quantity,$lenguage="es"){
		switch($lenguage){
			case "es":
				$gender = strtolower($gender);
				switch ($person."-".$gender[0]."-".$quantity) {
					case "1-f-1":
						return "Yo";
					case "2-f-1":
						return "Tu";
					case "3-f-1":
						return "Ella";
					case "4-f-1":
						return "La";
					case "1-f-n":
						return "Nosotras";
					case "2-f-n":
						return "Ustedes";
					case "3-f-n":
						return "Ellas";
					case "4-f-n":
						return "Las";
					case "1-m-1":
						return "Yo";
					case "2-m-1":
						return "Tu";
					case "3-m-1":
						return "El";
					case "4-m-1":
						return "El";
					case "1-m-n":
						return "Nosotros";
					case "2-m-n":
						return "Ustedes";
					case "3-m-n":
						return "Ellos";
					case "4-m-n":
						return "Los";
				}
				return "";
		}
		return "";
	}
}
if(!function_exists("instanceTrans")){
	/**
	 * Cuenta los valores true al evaluar un array de expresiones
	 *
	 * @param string   expresions  array con expresiones logicas
	 *
	 * @author Benomas benomas@gmail.com
	 * @date   2017-05-08
	 * @return array
	 */
	function instanceTrans($instance,$gender,$quantity,$lenguage="es"){
		switch($lenguage){
			case "es":
				$gender = strtolower($gender);
				switch ($instance."-".$gender[0]."-".$quantity) {
					case "new-f-1":
						return "Nueva";
					case "new-m-1":
						return "Nuevo";
					case "new-f-n":
						return "Nuevas";
					case "new-m-n":
						return "Nuevos";
					case "some-f-1":
						return "Alguna";
					case "some-m-1":
						return "Algun";
					case "some-f-n":
						return "Algunas";
					case "some-m-n":
						return "Algun";
					case "any-f-1":
						return "Ninguna";
					case "any-m-1":
						return "Ningun";
					case "any-f-n":
						return "Ningunas";
					case "any-m-n":
						return "Ningunos";
				}
				return $instance;
		}
		return $instance;
	}
}
if(!function_exists("kageBunshinNoJutsu")){
	function kageBunshinNoJutsu($instance){
		return clone $instance;
	}
}
if(!function_exists("actionAccess")){
	/**
	 * check user permission
	 *
	 * @param user model instance   userInstance
	 * @param string 	resourceAction
	 *
	 * @author Benomas benomas@gmail.com
	 * @date   2017-05-08
	 * @return array
	 */
	function actionAccess($userModel,$actionResource,$reset=false){
		if($reset){
			unset($GLOBALS[$actionResource]);
			unset($GLOBALS["userInstance"]);
			unset($GLOBALS["isRoot"]);
		}
		if(!empty($GLOBALS[$actionResource]))
			return $GLOBALS[$actionResource];
		if(empty($userModel))
      return ($GLOBALS[$actionResource]=false);
      if(!empty($GLOBALS["userInstance"]) && $GLOBALS["userInstance"])
      	$user = $GLOBALS["userInstance"];
      else
      	$user = $GLOBALS["userInstance"] = $userModel->first();
		if(!$user || !$user->active)
      return ($GLOBALS[$actionResource]=false);
    //if permission revision is disabled through the model
		if(!\App\Models\Permission::$enablePermissionCheck)
      return ($GLOBALS[$actionResource]=true);
		if(!empty($GLOBALS["isRoot"]) && $GLOBALS["isRoot"])
      return ($GLOBALS[$actionResource]=true);
		if($user->isRoot()){
			$GLOBALS["isRoot"] = true;
			customLog($GLOBALS["isRoot"]);
      return ($GLOBALS[$actionResource]=true);
		}
		if(!\App\Models\Permission::action($actionResource)->count())
      return ($GLOBALS[$actionResource]=true);
      if(kageBunshinNoJutsu($userModel)->actionPermission($actionResource)->count())
        return ($GLOBALS[$actionResource]=true);
		return false;
	}
}
if(!function_exists("specialAccess")){
	/**
	 * check user permission
	 *
	 * @param user model instance   userInstance
	 * @param string 	resourceAction
	 *
	 * @author Benomas benomas@gmail.com
	 * @date   2017-05-08
	 * @return array
	 */
	function specialAccess($userInstace,$special){
		if(empty($userInstace) || !($user = $userInstace->first()))
      return false;
		if($user->isRoot())
      return true;
		if(!\App\Models\Permission::special($special)->count())
      return true;
		return kageBunshinNoJutsu($userInstace)->specialPermission($special)->count()>0;
	}
}
if(!function_exists("errorClass")){
	function errorClass($errors,$cField){
		return $errors->first($cField)?'has-error':'';
	}
}
if(!function_exists("validateGetActionResource")){
	function validateGetActionResource($action,$only=[],$excludes=[]){
		return ( empty($only["get"]) || !count($only["get"]) ||  in_array($action,$only["get"])) &&
			(empty($excludes["get"]) || !in_array($action,$excludes["get"]));
	}
}
if(!function_exists("validatePostActionResource")){
	function validatePostActionResource($action,$only=[],$excludes=[]){
		return ( empty($only["post"]) || !count($only["post"]) ||  in_array($action,$only["post"])) &&
			(empty($excludes["post"]) || !in_array($action,$excludes["post"]));
	}
}
if(!function_exists("crudvelResource")){
	function crudvelResource($resource,$controller=null,$conditionals=[]){
		if(empty($resource))
			return false;
		$urlSegments = explode("/",$resource);
		$rowName = str_slug(str_singular(end($urlSegments)),"_");
		if(!$controller)
			$controller=studly_case($rowName)."Controller";
    if(!count($conditionals)){
      Route::get($resource."/import", $controller."@import");
      Route::get($resource."/export", $controller."@export");
      Route::post($resource."/import", $controller."@importing");
      Route::post($resource."/export", $controller."@exporting");
      Route::put($resource."/{".$rowName."}/activate", $controller."@activate");
      Route::put($resource."/{".$rowName."}/deactivate", $controller."@deactivate");
      Route::resource($resource, $controller);
    }
    else{
      $only     = empty($conditionals["only"])?[]:$conditionals["only"];
      $excludes = empty($conditionals["excludes"])?[]:$conditionals["excludes"];
      if(validateGetActionResource("activate",$only,$excludes)) Route::get($resource."/{".$rowName."}/activate", $controller."@activate");
      if(validateGetActionResource("create",$only,$excludes)) Route::get($resource."/create", $controller."@create");
      if(validateGetActionResource("deactivate",$only,$excludes)) Route::get($resource."/{".$rowName."}/deactivate", $controller."@deactivate");
      if(validateGetActionResource("edit",$only,$excludes)) Route::get($resource."/{".$rowName."}/edit", $controller."@edit");
      if(validateGetActionResource("export",$only,$excludes)) Route::get($resource."/export", $controller."@export");
      if(validateGetActionResource("import",$only,$excludes)) Route::get($resource."/import", $controller."@import");
      if(validateGetActionResource("index",$only,$excludes)) Route::get($resource, $controller."@index");
      if(validateGetActionResource("show",$only,$excludes)) Route::get($resource."/{".$rowName."}/show", $controller."@show");
      if(validatePostActionResource("destroy",$only,$excludes)) Route::delete($resource."/{".$rowName."}", $controller."@destroy");
      if(validatePostActionResource("exporting",$only,$excludes)) Route::post($resource."/export", $controller."@exporting");
      if(validatePostActionResource("importing",$only,$excludes)) Route::post($resource."/import", $controller."@importing");
      if(validatePostActionResource("store",$only,$excludes)) Route::post($resource, $controller."@store");
      if(validatePostActionResource("update",$only,$excludes)) Route::put($resource."/{".$rowName."}", $controller."@update");
    }
	}
}
if(!function_exists("crudvelResources")){
	function crudvelResources($resources){
		foreach ($resources as $resource) {
			crudvelResource(...$resource);
		}
	}
}
if(!function_exists("apiCrudvelResource")){
	function apiCrudvelResource($resource,$controller=null,$conditionals=[],$translator=[]){
		if(empty($resource))
			return false;
		$urlSegments = explode(".",$resource);
		$baseSegmentResource = end($urlSegments);
    $rowName = !empty($translator[$baseSegmentResource])?
      $translator[$baseSegmentResource]:str_slug(str_singular($baseSegmentResource),"_");
    if(!$controller)
      $controller="Api\\".studly_case($rowName)."Controller";
    if(!count($conditionals)){
      if(count($urlSegments)>1){
        foreach ($urlSegments as $segment){
          $i=empty($i)?1:$i+1;
          $currentSegment=!empty($translator[$segment])?$segment:str_singular($segment);
          $prefixRoute = (empty($prefixRoute)?"":$prefixRoute."/").$segment.($i<count($urlSegments)?"/{".$currentSegment."}":"");
        }
      }
      else{
        $prefixRoute = $resource;
      }
      Route::get($prefixRoute."/sluged", $controller."@sluged")->name($resource.".sluged");
      Route::get($prefixRoute."/import", $controller."@import")->name($resource.".import");
      Route::get($prefixRoute."/exports", $controller."@exports")->name($resource.".exports");
      Route::get($prefixRoute."/{".$rowName."}/export", $controller."@export")->name($resource.".export");
      Route::post($prefixRoute."/import", $controller."@importing")->name($resource.".importing");
      Route::get($prefixRoute."/exporting", $controller."@exportings")->name($resource.".exportings");
      Route::get($prefixRoute."/{".$rowName."}/exporting", $controller."@exporting")->name($resource.".exporting");
      Route::post($prefixRoute."/resource-permissions", $controller."@resourcePermissions")->name($resource.".resource-permissions");
      Route::post($prefixRoute."/select", $controller."@select")->name($resource.".select");
      Route::put($prefixRoute."/{".$rowName."}/activate", $controller."@activate")->name($resource.".activate");
      Route::put($prefixRoute."/{".$rowName."}/deactivate", $controller."@deactivate")->name($resource.".deactivate");
      //Route::resource($resource, $controller);
      Route::get($prefixRoute, $controller."@index")->name($resource.".index");
      Route::get($prefixRoute."/{".$rowName."}", $controller."@show")->name($resource.".show");
      Route::post($prefixRoute, $controller."@store")->name($resource.".store");
      Route::put($prefixRoute."/{".$rowName."}", $controller."@update")->name($resource.".update");
      Route::delete($prefixRoute."/{".$rowName."}", $controller."@destroy")->name($resource.".destroy");
    }
    else{
      if(in_array("sluged",$conditionals))
        Route::get($resource."/sluged", $controller."@sluged")->name($resource.".sluged");
      if(in_array("import",$conditionals))
        Route::get($resource."/import", $controller."@import")->name($resource.".import");
      if(in_array("exports",$conditionals))
        Route::get($resource."/exports", $controller."@exports")->name($resource.".exports");
      if(in_array("export",$conditionals))
        Route::get($resource."/{".$rowName."}/export", $controller."@export")->name($resource.".export");
      if(in_array("importing",$conditionals))
        Route::post($resource."/import", $controller."@importing")->name($resource.".importing");
      if(in_array("exportings",$conditionals))
        Route::post($resource."/exporting", $controller."@exportings")->name($resource.".exportings");
      if(in_array("exporting",$conditionals))
        Route::post($resource."/{".$rowName."}/exporting", $controller."@exporting")->name($resource.".exporting");
      if(in_array("activate",$conditionals))
        Route::put($resource."/{".$rowName."}/activate", $controller."@activate")->name($resource.".activate");
      if(in_array("deactivate",$conditionals))
        Route::put($resource."/{".$rowName."}/deactivate", $controller."@deactivate")->name($resource.".deactivate");
      if(in_array("index",$conditionals))
        Route::get($resource, $controller."@index")->name($resource.".index");
      if(in_array("show",$conditionals))
        Route::get($resource."/{".$rowName."}", $controller."@show")->name($resource.".show");
      if(in_array("store",$conditionals))
        Route::post($resource, $controller."@store")->name($resource.".store");
      if(in_array("update",$conditionals))
        Route::put($resource."/{".$rowName."}", $controller."@update")->name($resource.".update");
      if(in_array("destroy",$conditionals))
        Route::delete($resource."/{".$rowName."}", $controller."@destroy")->name($resource.".destroy");
    }
	}
}
if(!function_exists("apiCrudvelResources")){
	function apiCrudvelResources($resources){
		foreach ($resources as $resource) {
			apiCrudvelResource(...$resource);
		}
	}
}
if(!function_exists("resourceByForeingKey")){
	function resourceByForeingKey($foreingKey){
		$foreingKey = str_replace("_id","",$foreingKey);
		return str_slug(str_plural($foreingKey));
	}
}
if(!function_exists("propertyByPosition")){
	function propertyByPosition($object,$position)
	{
		$cellCount=1;
		$currentProperty=null;
		if(!empty($object))
			foreach($object as $cellIndex=>$cell){
				$currentProperty=$cellIndex;
				if($position<=$cellCount)
					break;
				$cellCount++;
			}
		return $cellCount===$position?$currentProperty:null;
	}
}

if(!function_exists("fixedIsInt")){
	function fixedIsInt($intTest){
    return isset($intTest) && strval($intTest) === strval(intval($intTest));
	}
}
if(!function_exists("deletePathContent")){
	function deletePathContent($path,$subFolder=false) {
    foreach (($files = array_diff(scandir($path), array('.','..'))) as $file)
      (is_dir("$path/$file")) ? deletePathContent("$path/$file",true) : unlink("$path/$file");
    if($subFolder)
      return rmdir($path);
	}
}
if(!function_exists("pushCrudvuelActions")){
  function pushCrudvuelActions($resource=null,&$targetArray,$actions=null,$excludes=[]) {
    if(!$resource)
      return;
    if(!$actions){
      if(!in_array($resource,$excludes))
        $targetArray[] = $resource;
      $labelActions = trans("crudvel.actions");
      foreach ($labelActions as $labelActionKey => $labelActionValue)
        $actions[]=$labelActionKey;
    }
    foreach ($actions as $action)
      if(!in_array($action,$excludes))
        $targetArray[] = "$resource.$action";
  }
}
if(!function_exists("recursiveSqlSrvDisableForeing")){
	function recursiveSqlSrvDisableForeing() {
    if($tables = DB::connection()->getDoctrineSchemaManager()->listTableNames())
      foreach ($tables AS $table){
        $currentTable = \DB::table($table)->get();
        DB::STATEMENT(" ALTER TABLE ".$table." NOCHECK CONSTRAINT fk_name");
      }
  }
}
if(!function_exists("recursiveSqlSrvEnableForeing")){
	function recursiveSqlSrvEnableForeing() {
    if($tables = DB::connection()->getDoctrineSchemaManager()->listTableNames())
      foreach ($tables AS $table){
        DB::STATEMENT(" ALTER TABLE ".$table." NOCHECK CONSTRAINT fk_name");
      }
  }
}
if(!function_exists("disableForeignKeyConstraints")){
	function disableForeignKeyConstraints($connection=null) {
    if(!config('project.project_prevent_foreings_conflict') )
      return ;
    $connection = $connection ?? config('database.default');
    return \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();
    switch ($connection) {
      case 'mysql':
        \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();
        break;
      case 'sqlite':
        \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();
        break;
      case 'pgsql':
        \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();
        break;
      case 'mssql':
        \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();
        break;
      case 'sqlsrv':
        \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();
        break;
      default:
        // code...
        break;
    }
	}
}
if(!function_exists("enableForeignKeyConstraints")){
	function enableForeignKeyConstraints($connection=null) {
    if(!config('project.project_prevent_foreings_conflict') )
      return ;
    $connection = $connection ?? config('database.default');
    switch ($connection) {
      case 'mysql':
        \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();
        break;
      case 'sqlite':
        \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();
        break;
      case 'pgsql':
        \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();
        break;
      case 'mssql':
        \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();
        break;
      case 'sqlsrv':
        \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();
        break;
      default:
        //code
        break;
    }
	}
}
if(!function_exists("columnList")){
	function columnList($connectionName='sqlsrv',$table) {
    //return 'database.connections.sqlsrv.driver';
    if(!$connection = config('database.connections.'.$connectionName))
      return null;
    switch($connection['driver']){
      case 'sqlite':
        return sqliteColumnList($connectionName,$table);
        break;
      case 'mysql':
        return mysqlColumnList($connectionName,$table);
        break;
      case 'pgsql':
        return pgsqlColumnList($connectionName,$table);
        break;
      case 'sqlsrv':
        return sqlsrvColumnList($connectionName,$table);
        break;
      default:
        return null;
    }
    return null;
	}
}

if(!function_exists("sqliteColumnList")){
	function sqliteColumnList($connectionName=null,$table=null) {
    if(!$connectionName || !$table)
      return null;
    $columns = \DB::connection($connectionName)->getSchemaBuilder()->getColumnListing($table);
    $response =[];
    //Obtenenemos definicion de tabla
    $creationQuery = \DB::connection($connectionName)->
      select("
        SELECT
          sql
        FROM
          sqlite_master
        WHERE
          sql like 'CREATE TABLE $table (%' OR
          sql like 'CREATE TABLE $table(%' OR
          sql like 'CREATE TABLE \"$table\" (%' OR
          sql like 'CREATE TABLE\"$table\"(%'
      ");
    //Remplazamos caracteres invisibles
    if(empty($creationQuery[0])){
      customLog('fail to obtain sqlite create statment log',$table);
      return $response;
    }
    $replacer = preg_replace('/\n/', '', $creationQuery[0]->sql);
    $replacer = preg_replace('/\t/', '', $replacer);
    $replacer = preg_replace('/\r/', '', $replacer);
    //Limitamos texto sql de definicion de columnas
    $replacer = preg_replace('/(CREATE TABLE ["]{0,1}'.$table.'["]{0,1})(.*)/', '$2', $replacer);
    foreach ($columns as $column) {
      //Seleccionar definición de columna actual
      $currentCol = preg_replace('/(.*)?"{0,1}('.$column.')"{0,1}\s+(\w+.*?)(,|\))(.*)/', '$3', $replacer);
      //Seleccionar tipo de dato de columna actual
      $datatype   = preg_replace('/^(\w+)?(.*)/', '$1', $currentCol);
      //Seleccionar tamaño de dato de columna actual
      $length     = preg_replace('/^'.$datatype.'\s{0,1}\((\d+)?\)(.*)/', '$1', $currentCol);
      if(!preg_match('/^\d+$/', $length, $matches, PREG_OFFSET_CAPTURE)){
        $currentCol = preg_replace('/^'.$datatype.'\s(.*)/', '$1', $currentCol);
        $length = null;
      }
      else
        $currentCol = preg_replace('/^'.$datatype.'\s\('.$length.'\)\s(.*)/', '$1', $currentCol);
      $search     = preg_match('/NOT NULL/', $currentCol, $matches, PREG_OFFSET_CAPTURE);
      //Seleccionar si la columna actual es nulable
      $nullable   = $search?0:1;
      $currentCol = preg_replace('/(.*?)NOT NULL(.*?)/', '$1$3', $currentCol);
      //Seleccionar valor default de columna actual
      $default    = preg_replace('/(.*)(DEFAULT)\s(\d+)(.*)/', '$3', $currentCol);
      if($default === $currentCol)
        $default = 'no-default-value';

      //hardcoded exceptions ;(
      if(in_array($datatype,['',null])){
        if($column === 'activo')
          $type = 'boolean';
      }
      else
        $type = sqliteDataTypeTraductor($datatype);

      if(in_array($type,['string','char'])){
        $maxLength = \DB::connection($connectionName)->select("SELECT MAX(length($column)) FROM $table");
        if($maxLength>255){
          $length=null;
          $type='text';
        }else {
          if($maxLength>$length)
            $length = $maxLength;
        }
      }
      $response[]=[
        'name'     =>$column,
        'default'  =>$default,
        'nullable' =>$nullable,
        'type'     =>$type,
        'length'   =>$length,
      ];
    }
    return $response;
	}
}
if(!function_exists("mysqlColumnList")){
	function mysqlColumnList($connectionName=null,$table=null) {
    if(!$connectionName || !$table)
      return null;
    return null;
	}
}
if(!function_exists("pgsqlColumnList")){
	function pgsqlColumnList($connectionName=null,$table=null) {
    if(!$connectionName || !$table)
      return null;
    return null;
	}
}
if(!function_exists("sqlsrvColumnList")){
	function sqlsrvColumnList($connectionName=null,$table=null) {
    if(!$connectionName || !$table)
      return null;
    $columnsDefinitions = \DB::connection($connectionName)->
      SELECT("
        SELECT
          COLUMN_NAME AS [name],
          COLUMN_DEFAULT AS [default],
          IS_NULLABLE AS [nullable],
          DATA_TYPE AS [type],
          CHARACTER_MAXIMUM_LENGTH AS [length]
        FROM
          INFORMATION_SCHEMA.COLUMNS
        WHERE
          CONCAT(TABLE_SCHEMA,'.',TABLE_NAME)= '{$table}'
          OR  TABLE_NAME = '{$table}'
      ");
    $response=[];
    foreach ($columnsDefinitions as $key => $columnDefinition){
      $default = $columnDefinition->default;
      if($columnDefinition->default ===null){
        $columnDefinition->default = 'null';
        $default = 'null';
      }
      if($columnDefinition->nullable === 'YES'){
        $nullable = 1;
      }else{
        $nullable = 0;
        if($columnDefinition->default ==='null')
          $default = 'no-default-value';
      }
      $response[]=[
        'name'     =>$columnDefinition->name,
        'default'  =>preg_replace('/([\(]{0,1})(.+?)([\)]{0,1})/', '$2', $default),
        'nullable' =>$nullable,
        'type'     =>sqlsrvDataTypeTraductor($columnDefinition->type),
        'length'   =>$columnDefinition->length,
      ];
    }
    return $response;
	}
}
if(!function_exists("sqlsrvDataTypeTraductor")){
	function sqlsrvDataTypeTraductor($dataType) {
    switch(strtolower($dataType)){
      case 'bit':
        return 'boolean';
      case 'bool':
        return 'boolean';
      case 'boolean':
        return 'boolean';
      case 'time':
        return 'time';
      case 'date':
        return 'date';
      case 'datetime':
        return 'dateTime';
      case 'smalldatetime':
        return 'dateTime';
      case 'tinyint':
        return 'tinyInteger';
      case 'smallint':
        return 'smallInteger';
      case 'int':
        return 'integer';
      case 'nvarchar':
        return 'string';
      case 'varchar':
        return 'string';
      case 'char':
        return 'char';
      case 'text':
        return 'text';
      case 'ntext':
        return 'text';
      case 'float':
        return 'float';
      case 'decimal':
        return 'decimal';
      case 'real':
        return 'float';
      default:
        //Hack para identificar tipos de dato no resueltos
        if(!in_array(strtolower($dataType),['asc','references']))
          $GLOBALS['Reevaluar']=true;
        return $dataType.' este tipo de dato necesita reevaluarse';
    }
    return '';
  }
}
if(!function_exists("sqliteDataTypeTraductor")){
	function sqliteDataTypeTraductor($dataType) {
    switch(strtolower($dataType)){
      case 'bit':
        return 'boolean';
      case 'bool':
        return 'boolean';
      case 'boolean':
        return 'boolean';
      case 'time':
        return 'time';
      case 'date':
        return 'date';
      case 'datetime':
        return 'dateTime';
      case 'smalldatetime':
        return 'dateTime';
      case 'integer':
        return 'integer';
      case 'int':
        return 'integer';
      case 'smallint':
        return 'smallInteger';
      case 'varchar':
        return 'string';
      case 'nvarchar':
        return 'string';
      case 'string':
        return 'string';
      case 'char':
        return 'char';
      case 'text':
        return 'text';
      case 'ntext':
        return 'text';
      case 'float':
        return 'float';
      case 'decimal':
        return 'decimal';
      case 'real':
        return 'float';
      case 'numeric':
        return 'decimal';

      default:
        //Hack para identificar tipos de dato no resueltos
        if(!in_array(strtolower($dataType),['asc','references']))
          $GLOBALS['Reevaluar']=true;
        return $dataType.' este tipo de dato necesita reevaluarse';
    }
    return '';
  }
}
if(!function_exists("arrayTranspose")){
	function arrayTranspose($sourceArray) {
    return array_map(null, ...$sourceArray);
  }
}

if(!function_exists("assetsMap")){
  function assetsMap($source_dir, $directory_depth = 0, $hidden = FALSE)
  {
    if(!file_exists($source_dir))
      return [];
    if ($fp = @opendir($source_dir))
    {
      $filedata   = array();
      $new_depth  = $directory_depth - 1;
      $source_dir = rtrim($source_dir, '/').'/';

      while (FALSE !== ($file = readdir($fp)))
      {
        // Remove '.', '..', and hidden files [optional]
        if ( ! trim($file, '.') OR ($hidden == FALSE && $file[0] == '.'))
          continue;

        if (($directory_depth < 1 OR $new_depth > 0) && @is_dir($source_dir.$file))
          $filedata[$file] = assetsMap($source_dir.$file.'/', $new_depth, $hidden);
        else
          $filedata[] = $file;
      }

      closedir($fp);
      return $filedata;
    }
    echo 'can not open dir';
    return FALSE;
  }
}

if(!function_exists("customExec")){
  function customExec($command)
  {
    return exec('cd '.base_path().' && '.$command);
  }
}

if(!function_exists("facader")){
  function facader($className)
  {
    return (new $className);
  }
}

if(!function_exists("num2alpha")){
  function num2alpha($n)
  {
    for($r = ""; $n >= 0; $n = intval($n / 26) - 1)
      $r = chr($n%26 + 0x41) . $r;
    return $r;
  }
}

if(!function_exists("pdd")){
  function pdd(...$doDebugg){CvHelper::pdd(...$doDebugg);}
}

if(!function_exists("jdd")){
  function jdd(...$doDebugg){CvHelper::jdd(...$doDebugg);}
}

if(!function_exists("customLog")){
  function customLog(...$params){CvHelper::customLog(...$params);}
}

if(!function_exists("cvTest")){
  function cvTest($expresion=null){return CvHelper::cvTest($expresion);}
}

if(!function_exists("caller")){
  function cvCaller($depth=4,$property='function'){return CvHelper::caller($depth,$property);}
}

if(!function_exists("cvClassFile")){
  function cvClassFile($className){return CvHelper::classFile($className);}
}

if(!function_exists("cvBase64Src")){
  function cvBase64Src($filePath){return CvHelper::base64Src($filePath);}
}

if(!function_exists("uCSort")){
  function uCSort($itemI,$nextItem){return CvHelper::uCSort($itemI,$nextItem);}
}

if(!function_exists("uCProp")){
  function uCProp($uCProp){return CvHelper::uCProp($uCProp);}
}

if(!function_exists('cvConsoler')){
  function cvConsoler($message){return CvHelper::cvConsoler($message);}
}
if(!function_exists('cvBlackTC')){
  function cvBlackTC($message){return CvHelper::blackTC($message);}
}
if(!function_exists('cvRedTC')){
  function cvRedTC($message){return CvHelper::redCoTC($message);}
}
if(!function_exists('cvGreenTC')){
  function cvGreenTC($message){return CvHelper::greenTC($message);}
}
if(!function_exists('cvBrownTC')){
  function cvBrownTC($message){return CvHelper::brownTC($message);}
}
if(!function_exists('cvBlueTC')){
  function cvBlueTC($message){return CvHelper::blueTC($message);}
}
if(!function_exists('cvPurpleTC')){
  function cvPurpleTC($message){return CvHelper::purpleTC($message);}
}
if(!function_exists('cvCyanTC')){
  function cvCyanTC($message){return CvHelper::cyanTC($message);}
}
if(!function_exists('cvWhiteTC')){
  function cvWhiteTC($message){return CvHelper::whiteTC($message);}
}
if(!function_exists('cvDefauTC')){
  function cvDefauTC($message){return CvHelper::defauTC($message);}
}
if(!function_exists("getCheckPoint")){
  function getCheckPoint(){return CvHelper::getCheckPoint();}
}
if(!function_exists('cvConsolerLn')){
  function cvConsolerLn($messageStart='',$messageEnd=''){
    cvConsoler(cvCyanTC("$messageStart\n---------------------------------------------------------------------------------------------------\n$messageEnd"));
  }
}
if(!function_exists('cvHasAndReturn')){
  function cvHasAndReturn($find, $return, $string){
    return CvHelper::hasAndReturn($find, $return, $string);
  }
}
if(!function_exists('cvHasAndReplace')){
  // function cvHasAndReplace($string, $replace='', $reg ='/["&\'\*\+<=>\[\]\^`\|\{\}~æÆø£ƒªº®¬½¼¡«»░▒▓│┤©╣║╗╝¢¥┐└┴┬├─┼╚╔╩╦╠═╬¤┘┌█▄¦▀µþ¯´≡±‗¾¶§÷¸°¨·¹³²■]/'){
  function cvHasAndReplace($string, $replace='', $reg ='/["&\'\*\+<=>\[\]\^`\|\{\}\~]/'){
    return CvHelper::hasAndReplace($string, $replace, $reg);
  }
}
if(!function_exists('cvConvertRealNull')){
  function cvConvertRealNull($string){
    return CvHelper::convertRealNull($string);
  }
}
if(!function_exists('cvReplaceIfNotAlphaNum')){
  function cvReplaceIfNotAlphaNum($string, $replace){
    return CvHelper::replaceIfNotAlphaNum($string, $replace);
  }
}
if(!function_exists('cvReplaceIfNotAlpha')){
  function cvReplaceIfNotAlpha($string, $replace){
    return CvHelper::replaceIfNotAlpha($string, $replace);
  }
}
if(!function_exists('cvHasAlphaNum')){
  function cvHasAlphaNum($string){
    return CvHelper::hasAlphaNum($string);
  }
}
if(!function_exists('cvHasAlpha')){
  function cvHasAlpha($string){
    return CvHelper::hasAlpha($string);
  }
}
if(!function_exists('cvReplaceNull')){
  function cvReplaceNull($string){
    return CvHelper::replaceNull($string);
  }
}
if(!function_exists('cvRemoveNewLinesAndSpaces')){
  function cvRemoveNewLinesAndSpaces($string){
    return CvHelper::removeNewLinesAndSpaces($string);
  }
}
if(!function_exists('cvCustomTrim')){
  function cvCustomTrim($string, $character_mask = ' \t\n\r\0\x0B"&\'*+,-./:;=@\^_`|~'){
    return CvHelper::customTrim($string, $character_mask);
  }
}
if(!function_exists('cvGenericTextClean')){
  function cvGenericTextClean($string){
    return CvHelper::genericTextClean($string);
  }
}

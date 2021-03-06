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

if(!function_exists("customLog")){
	function customLog(...$params){
		$params = json_encode($params);
		$backtrace = debug_backtrace();
		\Illuminate\Support\Facades\Log::info("Log from ".$backtrace[0]["file"]." - ".$backtrace[1]["function"]." in the line: ".$backtrace[0]["line"]." with message: ".$params);
	}
}

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
	function arrayIntersect($array1=null,$array2=null){
		//si el primer parametro no es un customNonEmptyArray
		if(!customNonEmptyArray($array2))
			return customNonEmptyArray($array1)? $array1:null;
		//si el segundo parametro no es un customNonEmptyArray
		if(!customNonEmptyArray($array1))
			return null;

		//si ambos parametros son customNonEmptyArrays
		$result = [];
		foreach ($array1 as $key=>$value) {
			if(isset($array2[$key]))
				$result[$key]=$value;
		}
		return $result;
	}
}

if(!function_exists("optionalColumn")){
	function optionalColumn($context,$column){
		return !($colConfiguration = config("project.optional_columns"))
		|| !isset($colConfiguration[$context])
		|| !isset($colConfiguration[$context][$column])
		|| $colConfiguration[$context][$column];
	}
}

if(!function_exists("countOptionalColumns")){
	function countOptionalColumns($context,...$columns){
		$total = 0;
		foreach ($columns as $value) {
			if(optionalColumn($context,$value))
				$total++;
		}
		return $total;
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

if(!function_exists("shadowInstance")){

	function shadowInstance($instance){
		return clone $instance;
	}
}

if(!function_exists("resourceAccess")){
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

	function resourceAccess($userModel,$actionResource,$reset=false){
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

		if(!$user)
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

		if(!\App\Models\Permission::actionResource($actionResource)->count())
            return ($GLOBALS[$actionResource]=true);

        if(shadowInstance($userModel)->resourceActionPermission($actionResource)->count())
            return ($GLOBALS[$actionResource]=true);
		return false;
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
	        Route::get($resource."/{".$rowName."}/active", $controller."@active");
	        Route::get($resource."/{".$rowName."}/deactive", $controller."@deactive");
	        Route::resource($resource, $controller);
        }
        else{
			$only     = empty($conditionals["only"])?[]:$conditionals["only"];
			$excludes = empty($conditionals["excludes"])?[]:$conditionals["excludes"];
	        if(validateGetActionResource("active",$only,$excludes)) Route::get($resource."/{".$rowName."}/active", $controller."@active");
	        if(validateGetActionResource("create",$only,$excludes)) Route::get($resource."/create", $controller."@create");
	        if(validateGetActionResource("deactive",$only,$excludes)) Route::get($resource."/{".$rowName."}/deactive", $controller."@deactive");
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

if(!function_exists("resourceByForeingKey")){
	function resourceByForeingKey($foreingKey){
		$foreingKey = str_replace("_id","",$foreingKey);
		return str_slug(str_plural($foreingKey));
	}
}


if(!function_exists("pdd")){
	function pdd(...$doDebugg)
	{
		$backtrace = debug_backtrace();
		array_unshift(
			$doDebugg,
			"from ".$backtrace[0]['file']." - ".$backtrace[1]['function']." in the line: ".$backtrace[0]['line']);
		dd($doDebugg);
	}
}

if(!function_exists("jdd")){
	function jdd(...$doDebugg)
	{
		$backtrace = debug_backtrace();
		\Illuminate\Support\Facades\Log::info("Log from ".$backtrace[0]['file']." - ".$backtrace[1]['function']." in the line: ".$backtrace[0]['line']);
		array_unshift(
			$doDebugg,
			"from ".$backtrace[0]['file']." - ".$backtrace[1]['function']." in the line: ".$backtrace[0]['line']);
		echo json_encode($doDebugg);
		die();
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

if(!function_exists("assetsMap")){
	function assetsMap($source_dir, $directory_depth = 0, $hidden = FALSE)
	{
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
  
if(!function_exists("customLog")){
	function customLog(...$params){
		$params = json_encode($params);
		$backtrace = debug_backtrace();
		\Illuminate\Support\Facades\Log::info("Log from ".$backtrace[0]['file']." - ".$backtrace[1]['function']." in the line: ".$backtrace[0]['line']." with message: ".$params);
	}
}

if(!function_exists("customExec")){
  function customExec($command)
  {
    return exec('cd '.base_path().' && '.$command);
  }
}

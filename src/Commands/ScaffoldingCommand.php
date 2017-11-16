<?php 
namespace Benomas\Crudvel\Commands;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class ScaffoldingCommand extends Command {

    protected $signature   = 
    'scaffold {modo} {classes} {entity_segments} {api_segments} {web_segments} {entity} {maintable} {gender} {slug_entity} {snack_entity} {menu} {submenu} {defaults} {traits} {model_traits}';
    protected $name        = "scaffold";
    protected $description = 'Command description.';
    protected $controller_template;
    protected $api_controller_template;
    protected $web_controller_template;
    protected $request_template;
    protected $model_template;
    protected $repository_template;
    
    protected $classes=[];
    protected $entity="";
    protected $maintable="";
    protected $gender="";
    protected $slug_entity="";
    protected $snack_entity="";
    protected $entity_segments="";
    protected $api_segments="";
    protected $web_segments="";
    protected $menu="";
    protected $submenu="";
    protected $defaults="";
    protected $traits="";
    protected $model_traits="";

    public function __construct()
    {
        parent::__construct();
        $this->controller_template     = file_get_contents(__DIR__."/../templates/controller.txt");
        $this->api_controller_template = file_get_contents(__DIR__."/../templates/api_controller.txt");
        $this->web_controller_template = file_get_contents(__DIR__."/../templates/web_controller.txt");
        $this->request_template        = file_get_contents(__DIR__."/../templates/request.txt");
        $this->model_template          = file_get_contents(__DIR__."/../templates/model.txt");
        $this->repository_template     = file_get_contents(__DIR__."/../templates/repository.txt");
    }

    public function saveFiles(){
        $fixedEntitySegments = str_replace("\\","/",$this->entity_segments);
        $apiSegment = str_replace("\\","/",$this->api_segments);
        $webSegment = str_replace("\\","/",$this->web_segments);

        if(in_array("controller",$this->classes)){
            $controllerPath  = app_path()."/Http/Controllers/".$fixedEntitySegments;
            if(!file_exists($controllerPath))
                mkdir($controllerPath);
            if(!file_exists(($fileName=$controllerPath."/".($this->entity)."Controller.php")))
                file_put_contents($fileName, $this->controller_template);
        }
        if(in_array("api_controller",$this->classes)){
            $ApiControllerPath = app_path()."/Http/Controllers/".$apiSegment.$fixedEntitySegments;
            if(!file_exists($ApiControllerPath))
                mkdir($ApiControllerPath);
            if(!file_exists(($fileName=$ApiControllerPath."/".($this->entity)."Controller.php")))
                file_put_contents($fileName, $this->api_controller_template);
        }
        if(in_array("web_controller",$this->classes)){
            $WebControllerPath = app_path()."/Http/Controllers/".$webSegment.$fixedEntitySegments;
            if(!file_exists($WebControllerPath))
                mkdir($WebControllerPath);
            if(!file_exists(($fileName=$WebControllerPath."/".($this->entity)."Controller.php")))
                file_put_contents($fileName, $this->web_controller_template);
        }
        if(in_array("request",$this->classes)){
            $requestPath     = app_path()."/Http/Requests/".$fixedEntitySegments;
            if(!file_exists($requestPath))
                mkdir($requestPath);
            if(!file_exists(($fileName=$requestPath."/".($this->entity)."Request.php")))
                file_put_contents($fileName, $this->request_template);
        }
        if(in_array("model",$this->classes)){
            $modelPath       = app_path()."/Persistence/Models/".$fixedEntitySegments;
            if(!file_exists($modelPath))
                mkdir($modelPath);
            if(!file_exists(($fileName=$modelPath."/".($this->entity).".php")))
                file_put_contents($fileName, $this->model_template);
        }
        if(in_array("repository",$this->classes)){
            $repositoreyPath = app_path()."/Persistence/Repository/".$fixedEntitySegments;
            if(!file_exists($repositoreyPath))
                mkdir($repositoreyPath);
            if(!file_exists(($fileName=$repositoreyPath."/".($this->entity)."Repository.php")))
                file_put_contents($fileName, $this->repository_template);
        }
    }

    public function deleteFiles(){
        $fixedEntitySegments = str_replace("\\","/",$this->entity_segments);
        $apiSegment = str_replace("\\","/",$this->api_segments);
        $webSegment = str_replace("\\","/",$this->web_segments);

        if(in_array("controller",$this->classes)){
            $controllerPath  = app_path()."/Http/Controllers/".$fixedEntitySegments."/".($this->entity)."Controller.php";
            unlink($controllerPath);
        }
        if(in_array("api_controller",$this->classes)){
            $apiControllerPath  = app_path()."/Http/Controllers/".$apiSegment.$fixedEntitySegments."/".($this->entity)."Controller.php";
            unlink($apiControllerPath);
        }
        if(in_array("web_controller",$this->classes)){
            $webControllerPath  = app_path()."/Http/Controllers/".$webSegment.$fixedEntitySegments."/".($this->entity)."Controller.php";
            unlink($webControllerPath);
        }
        if(in_array("request",$this->classes)){
            $requestPath     = app_path()."/Http/Requests/".$fixedEntitySegments."/".($this->entity)."Request.php";
            unlink($requestPath);
        }
        if(in_array("model",$this->classes)){
            $modelPath       = app_path()."/Persistence/Models/".$fixedEntitySegments."/".($this->entity).".php";
            unlink($modelPath);
        }
        if(in_array("repository",$this->classes)){
            $repositoreyPath = app_path()."/Persistence/Repository/".$fixedEntitySegments."/".($this->entity)."Repository.php";
            unlink($repositoreyPath);
        }
    }

    public function makeController(){
        $this->controller_template = str_replace('$ENTITY$', Str::title($this->entity), $this->controller_template);
        $this->controller_template = str_replace('$ENTITYSEGMENTS$', Str::title($this->entity_segments), $this->controller_template);
        $this->controller_template = str_replace('$MAINTABLE$', Str::title($this->maintable), $this->controller_template);
    }

    public function makeApiController(){
        $this->controller_template = str_replace('$ENTITY$', Str::title($this->entity), $this->api_controller_template);
        $this->controller_template = str_replace('$ENTITYSEGMENTS$', Str::title($this->entity_segments), $this->api_controller_template);
        $this->controller_template = str_replace('$MAINTABLE$', Str::title($this->maintable), $this->api_controller_template);
        $this->controller_template = str_replace('$GENDER$', Str::title($this->gender), $this->api_controller_template);
        $this->controller_template = str_replace('$SLUGENTITY$', Str::title($this->slug_entity), $this->api_controller_template);
        $this->controller_template = str_replace('$SNACKENTITY$', Str::title($this->snack_entity), $this->api_controller_template);
    }

    public function makeWebController(){
        $this->controller_template = str_replace('$ENTITY$', Str::title($this->entity), $this->web_controller_template);
        $this->controller_template = str_replace('$ENTITYSEGMENTS$', Str::title($this->entity_segments), $this->web_controller_template);
        $this->controller_template = str_replace('$MAINTABLE$', Str::title($this->maintable), $this->web_controller_template);
        $this->controller_template = str_replace('$TRAITS$', Str::title($this->web_traits), $this->web_controller_template);
        $this->controller_template = str_replace('$GENDER$', Str::title($this->gender), $this->web_controller_template);
        $this->controller_template = str_replace('$SLUGENTITY$', Str::title($this->slug_entity), $this->web_controller_template);
        $this->controller_template = str_replace('$SNACKENTITY$', Str::title($this->snack_entity), $this->web_controller_template);
        $this->controller_template = str_replace('$MENU$', Str::title($this->menu), $this->web_controller_template);
        $this->controller_template = str_replace('$SUBMENU$', Str::title($this->submenu), $this->web_controller_template);
        $this->controller_template = str_replace('$DEFAULTS$', Str::title($this->defaults), $this->web_controller_template);
    }

    public function makeRequest(){
        $this->request_template = str_replace('$ENTITY$', Str::title($this->entity), $this->request_template);
        $this->request_template = str_replace('$ENTITYSEGMENTS$', Str::title($this->entity_segments), $this->request_template);
        $this->request_template = str_replace('$MAINTABLE$', Str::title($this->maintable), $this->request_template);
        $this->controller_template = str_replace('$SLUGENTITY$', Str::title($this->slug_entity), $this->request_template);
        $this->controller_template = str_replace('$SNACKENTITY$', Str::title($this->snack_entity), $this->request_template);
    }

    public function makeModel(){
        $this->model_template = str_replace('$ENTITY$', Str::title($this->entity), $this->model_template);
        $this->model_template = str_replace('$ENTITYSEGMENTS$', Str::title($this->entity_segments), $this->model_template);
        $this->model_template = str_replace('$MAINTABLE$', Str::title($this->maintable), $this->model_template);
        $this->controller_template = str_replace('$TRAITS$', Str::title($this->model_traits), $this->web_controller_template);
    }

    public function makeRepository(){
        $this->repository_template = str_replace('$ENTITY$', Str::title($this->entity), $this->repository_template);
        $this->repository_template = str_replace('$ENTITYSEGMENTS$', Str::title($this->entity_segments), $this->repository_template);
        $this->repository_template = str_replace('$MAINTABLE$', Str::title($this->maintable), $this->repository_template);
    }

    public function handle()
    {
        $modo                  = $this->argument("modo");
        $classes               = $this->argument("classes");
        $this->classes         = explode(",", $classes);
        $this->entity          =$this->argument("entity");
        $this->maintable       =$this->argument("maintable");
        $this->gender          =$this->argument("gender");
        $this->slug_entity     =$this->argument("slug_entity");
        $this->snack_entity    =$this->argument("snack_entity");
        $this->entity_segments =$this->argument("entity_segments");
        $this->api_segments    =$this->argument("api_segments");
        $this->web_segments    =$this->argument("web_segments");
        $this->menu            =$this->argument("menu");
        $this->submenu         =$this->argument("submenu");
        $this->defaults        =$this->argument("defaults");
        $this->traits          =$this->argument("traits");
        $this->model_traits    =$this->argument("model_traits");
/*
        dd([
            $this->classes,
            $this->entity,
            $this->maintable,
            $this->gender,
            $this->slug_entity,
            $this->snack_entity,
            $this->entity_segments,
            $this->api_segments,
            $this->web_segments,
            $this->menu,
            $this->submenu,
            $this->defaults,
            $this->traits,
            $this->model_traits,
        ]);*/

        if($modo==="create"){
            $this->maintable = $this->argument("maintable");
            //try{
                if(in_array("controller",$this->classes))
                    $this->makeController();
                if(in_array("request",$this->classes))
                    $this->makeRequest();
                if(in_array("model",$this->classes))
                    $this->makeModel();
                if(in_array("repository",$this->classes))
                    $this->makeRepository();
                $this->saveFiles();
                shell_exec('composer dump-autoload');
            
            /*}
            catch(\Exception $e){
                echo "Exception, the proccess fail.";
                return false;
            }*/
        }
        if($modo==="delete"){
            try{
                $this->deleteFiles();
                shell_exec('composer dump-autoload');
            }
            catch(\Exception $e){
                echo "Exception, the proccess fail.";
                return false;
            }
        }
        
        echo "proccess completed";
    }

    protected function getArguments()
    {
        return [
            [
                "modo", InputArgument::REQUIRED, "modo is required (create,delete)",
            ],
            [
                "classes", InputArgument::REQUIRED, "classes are required (controller, api_controller, web_controller,request,repository,model)",
            ],
            [
                "entity", InputArgument::REQUIRED, "Entidy is required",
            ],
            [
                "maintable", InputArgument::REQUIRED, "Entidy is required",
            ],
            [
                "gender", InputArgument::REQUIRED, "gender is required (F,M)",
            ],
            [
                "slug_entity", InputArgument::REQUIRED, "slug_entity is required",
            ],
            [
                "snack_entity", InputArgument::REQUIRED, "snack_entity is required",
            ],
            [
                "entity_segments", InputArgument::OPTIONAL, "specificPath is required",
            ],
            [
                "api_segments", InputArgument::OPTIONAL, "apiController specificPath is required",
            ],
            [
                "web_segments", InputArgument::OPTIONAL, "webController specificPath is required",
            ],
            [
                "menu", InputArgument::OPTIONAL, "menu is required",
            ],
            [
                "defaults", InputArgument::OPTIONAL, "defaults is required (['field'=>'value'])",
            ],
            [
                "submenu", InputArgument::OPTIONAL, "submenu is required",
            ],
            [
                "traits", InputArgument::OPTIONAL, "traits is required",
            ],
            [
                "model_traits", InputArgument::OPTIONAL, "model_traits is required",
            ],
        ];
    }
}
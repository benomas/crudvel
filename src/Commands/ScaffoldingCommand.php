<?php 
namespace Benomas\Crudvel\Commands;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Carbon\Carbon;

class ScaffoldingCommand extends Command {

    protected $signature   = 
    'scaffold {modo} {classes} {entity} {gender} {menu} {defaults} {web_traits} {model_traits} {entity_segments} {api_segments} {web_segments}  ';
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
    protected $gender="";
    protected $slug_entity="";
    protected $snack_entity="";
    protected $entity_segments="";
    protected $api_segments="";
    protected $web_segments="";
    protected $menu="";
    protected $defaults="";
    protected $web_traits="";
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
            $modelPath       = app_path()."/Models/".$fixedEntitySegments;
            if(!file_exists($modelPath))
                mkdir($modelPath);
            if(!file_exists(($fileName=$modelPath."/".($this->entity).".php")))
                file_put_contents($fileName, $this->model_template);
        }
        if(in_array("repository",$this->classes)){
            $repositoreyPath = app_path()."/Repository/".$fixedEntitySegments;
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

            if(file_exists(($controllerPath)))
                unlink($controllerPath);
        }
        if(in_array("api_controller",$this->classes)){
            $apiControllerPath  = app_path()."/Http/Controllers/".$apiSegment.$fixedEntitySegments."/".($this->entity)."Controller.php";

            if(file_exists(($apiControllerPath)))
                unlink($apiControllerPath);
        }
        if(in_array("web_controller",$this->classes)){
            $webControllerPath  = app_path()."/Http/Controllers/".$webSegment.$fixedEntitySegments."/".($this->entity)."Controller.php";
            
            if(file_exists(($webControllerPath)))
                unlink($webControllerPath);
        }
        if(in_array("request",$this->classes)){
            $requestPath     = app_path()."/Http/Requests/".$fixedEntitySegments."/".($this->entity)."Request.php";
            
            if(file_exists(($requestPath)))
                unlink($requestPath);
        }
        if(in_array("model",$this->classes)){
            $modelPath       = app_path()."/Models/".$fixedEntitySegments."/".($this->entity).".php";
            
            if(file_exists(($modelPath)))
                unlink($modelPath);
        }
        if(in_array("repository",$this->classes)){
            $repositoreyPath = app_path()."/Repository/".$fixedEntitySegments."/".($this->entity)."Repository.php";
            
            if(file_exists(($repositoreyPath)))
                unlink($repositoreyPath);

        }
    }

    public function makeController(){
        $this->controller_template = str_replace('$ENTITY$', $this->entity, $this->controller_template);
        $this->controller_template = str_replace('$ENTITYSEGMENTS$', Str::title($this->entity_segments), $this->controller_template);
        $this->controller_template = str_replace('$MAINTABLE$', $this->snack_entity, $this->controller_template);
    }

    public function makeApiController(){
        $this->api_controller_template = str_replace('$ENTITY$', $this->entity, $this->api_controller_template);
        $this->api_controller_template = str_replace('$ENTITYSEGMENTS$', Str::title($this->entity_segments), $this->api_controller_template);
        $this->api_controller_template = str_replace('$MAINTABLE$', $this->snack_entity, $this->api_controller_template);
        $this->api_controller_template = str_replace('$GENDER$', Str::title($this->gender), $this->api_controller_template);
        $this->api_controller_template = str_replace('$SLUGENTITY$', $this->slug_entity, $this->api_controller_template);
        $this->api_controller_template = str_replace('$SNACKENTITY$', $this->snack_entity, $this->api_controller_template);
    }

    public function makeWebController(){
        $this->web_controller_template = str_replace('$ENTITY$', $this->entity, $this->web_controller_template);
        $this->web_controller_template = str_replace('$ENTITYSEGMENTS$', Str::title($this->entity_segments), $this->web_controller_template);
        $this->web_controller_template = str_replace('$MAINTABLE$', $this->snack_entity, $this->web_controller_template);
        $this->web_controller_template = str_replace('$TRAITS$', $this->web_traits, $this->web_controller_template);
        $this->web_controller_template = str_replace('$GENDER$', Str::title($this->gender), $this->web_controller_template);
        $this->web_controller_template = str_replace('$SLUGENTITY$', $this->slug_entity, $this->web_controller_template);
        $this->web_controller_template = str_replace('$SNACKENTITY$', $this->snack_entity, $this->web_controller_template);
        $this->web_controller_template = str_replace('$MENU$', $this->menu, $this->web_controller_template);
        $this->web_controller_template = str_replace('$SUBMENU$', $this->slug_entity, $this->web_controller_template);
        $this->web_controller_template = str_replace('$DEFAULTS$', $this->defaults, $this->web_controller_template);
    }

    public function makeRequest(){
        $this->request_template = str_replace('$ENTITY$', $this->entity, $this->request_template);
        $this->request_template = str_replace('$ENTITYSEGMENTS$', Str::title($this->entity_segments), $this->request_template);
        $this->request_template = str_replace('$MAINTABLE$', $this->snack_entity, $this->request_template);
        $this->request_template = str_replace('$SLUGENTITY$', $this->slug_entity, $this->request_template);
        $this->request_template = str_replace('$SNACKENTITY$', $this->snack_entity, $this->request_template);
    }

    public function makeModel(){
        $this->model_template = str_replace('$ENTITY$', $this->entity, $this->model_template);
        $this->model_template = str_replace('$ENTITYSEGMENTS$', Str::title($this->entity_segments), $this->model_template);
        $this->model_template = str_replace('$MAINTABLE$', $this->snack_entity, $this->model_template);
        $this->model_template = str_replace('$TRAITS$', $this->model_traits, $this->model_template);
    }

    public function makeRepository(){
        $this->repository_template = str_replace('$ENTITY$', $this->entity, $this->repository_template);
        $this->repository_template = str_replace('$ENTITYSEGMENTS$', Str::title($this->entity_segments), $this->repository_template);
        $this->repository_template = str_replace('$MAINTABLE$', $this->snack_entity, $this->repository_template);
    }

    public function handle()
    {
        $modo                  = $this->argument("modo");
        $classes               = $this->argument("classes");
        $this->classes         = explode(",", $classes);
        $this->entity          = $this->argument("entity");
        $this->snack_entity    = str_plural(snake_case($this->entity));
        $this->gender          = $this->argument("gender");
        $this->slug_entity     = str_slug($this->snack_entity);
        $this->entity_segments = $this->argument("entity_segments");
        $this->api_segments    = $this->argument("api_segments");
        $this->web_segments    = $this->argument("web_segments");
        $this->menu            = $this->argument("menu");
        $this->defaults        = $this->argument("defaults");
        $this->web_traits      = $this->argument("web_traits");
        $this->model_traits    = $this->argument("model_traits");
        /*
        dd([
            "classes"         =>$this->classes,
            "entity"          =>$this->entity,
            "gender"          =>$this->gender,
            "slug_entity"     =>$this->slug_entity,
            "snack_entity"    =>$this->snack_entity,
            "entity_segments" =>$this->entity_segments,
            "api_segments"    =>$this->api_segments,
            "web_segments"    =>$this->web_segments,
            "menu"            =>$this->menu,
            "defaults"        =>$this->defaults,
            "web_traits"      =>$this->web_traits,
            "model_traits"    =>$this->model_traits,
        ]);
        */
        if($modo==="create"){
            try{
                if(in_array("controller",$this->classes))
                    $this->makeController();
                if(in_array("api_controller",$this->classes))
                    $this->makeApiController();
                if(in_array("web_controller",$this->classes))
                    $this->makeWebController();
                if(in_array("request",$this->classes))
                    $this->makeRequest();
                if(in_array("model",$this->classes))
                    $this->makeModel();
                if(in_array("repository",$this->classes))
                    $this->makeRepository();
                $this->saveFiles();
                if(in_array("migration",$this->classes))
                    shell_exec('php artisan make:migration create_'.$this->snack_entity.'_table_'.str_slug(Carbon::today()->toDateString(),""));
                shell_exec('composer dump-autoload');
            
            }
            catch(\Exception $e){
                echo "Exception, the proccess fail.";
                return false;
            }
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
                "classes", InputArgument::REQUIRED, "classes are required (controller, api_controller, web_controller,request,repository,model,migration)",
            ],
            [
                "entity", InputArgument::REQUIRED, "Entidy is required",
            ],
            [
                "gender", InputArgument::REQUIRED, "gender is required (F,M)",
            ],
            [
                "menu", InputArgument::OPTIONAL,
            ],
            [
                "defaults", InputArgument::OPTIONAL,
            ],
            [
                "web_traits", InputArgument::OPTIONAL,
            ],
            [
                "model_traits", InputArgument::OPTIONAL,
            ],
            [
                "entity_segments", InputArgument::OPTIONAL,
            ],
            [
                "api_segments", InputArgument::OPTIONAL,
            ],
            [
                "web_segments", InputArgument::OPTIONAL,
            ],
        ];
    }
}
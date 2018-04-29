# crudvel
Laravel clases customization

This package helps you to make and resfull api, implementing all the default laravel api endpoinds plus some aditionals.
The main concept of this package is a resource.
## A resource require:

```
-Lang file
-Migration file
-Model file
-ApiController file
-Request file
-Seed file (optional)
-Routing.
```

When you include this package as dependency in your laravel proyect, firts at all you will require to publish it, just run the next line in the terminal

``` php artisan vendor:publish --provider='Benomas\Crudvel\CrudvelServiceProvider' ```

After that you will get access to scaffolding commands 

## scaffold {modo} {classes} {entity} {entity_segments} {api_segments} {web_segments}

```
# mode
  -create|delete
# classes (this param contain all the class types as 1 param, so you need to define it as class,class,class... )
  -api_controller
  -web_controller (unused for now)
  -request
  -model
  -repository (unused for now)
  -migration
# entity
  name of the resource in StudlyCase, example CatState
#entity_segments
  directory structure, if you want to group the resources by your own way, just define here how will be structuring.
  for example, if you want to put de resource CatState inside catalogos folder, then you need to define entity segments as '\catalogs'
#api_segments
  Same as entity_segments. but only apply for api controllers
#web_segments
  Same as entity_segments. but only apply for web controllers
```
Example of calls

## Examples
```
  up
    php artisan scaffold create api_controller,request,model,migration test "" "\Api" ""
  down 
    php artisan scaffold delete api_controller,request,model,migration test "" "\Api" ""
```

This package is based in oop, For give you access to change de base logic, without touch core files. 
You will have a folder name crudvel/customs in the root of your proyect, here you can change de behavior of crudvel clases in a global way for your proyect.

After you create a resource with crudvel scaffolding, you require to complete the next steps in the order in the order that they are listed

```
  -Create     		  resources/lang/<lang>/crudvel/<resource(plural,slug)>
  -Edit     		    App/Models/<resource(singular,StudlyCase)>
  -Edit     		    App/Http/Requests/<resource(singular,StudlyCase)>Request
  -Edit     		    App/Http/Controllers/Api/<resource(singular,StudlyCase)>Controller
  -Edit			        database/migrations/<datetime+_create+_<resource>_+table+_datetime(plural,snake_case)>
  -Create(optional)	database/seeds/<resource+table_seeder(singular,StudlyCase)>
  -Edit       		  routes/api *Add in the apiCrudvelResources call (plural,slug)

```

## Current working branch is 4code-crudvel

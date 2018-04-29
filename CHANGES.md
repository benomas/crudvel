## Changes for the current proyects iteration

  ## Expert and combinatory pagination
    the package currently support basic and advanced pagination, but some current proyects require to include more sophisticated ways to search and paginate the data. 

  ## Expert pagination: 
    This kind of pagination was already includen in the package, but after some recent refactoring it is possible broken, so require to test again and fix
    What this mode of pagination do is to permit and infinity filters with combinations of or/and logical operator and "=","<",">","<>","<=",">=","like" comparators operators
    #combinatory paginations
    
    This kind of pagination is no develop at all in this package, but we have some successful implementation in another proyect.
    What suppose to do is. Get a string as search, split it in words, and find all the combinations of those words in the filterable columns.
    an example in a search like 'big red car'. the combinatory paginations will try with:

    #'big red car'
    #'big car red '
    #'red big car'
    #'red car big'
    #'car big red'
    #'car red big'
    #'big red'
    #'big car'
    #'red big'
    #'car big'
    #'red car'
    #'car red'
    #'big'
    #'red'
    #'car'

## Field permissions

    We have already some structure in database for manage field permissions. We have too some helper functions for validate this kind of permission, 
    but is not implemented yet in any proyect.
    This kind of permission will allow to the admins to restrict the way that a user have for interact with a resource, 
    in view and store ways. 

  ## Role domain
    We have already some structure in database for manage domain permissions. We have too some helper functions for validate this kind of permission, 
    but is not implemented yet in any proyect.
    This kind of permission will allow to the admins to define the range of roles that a role can interact.
    For example if we want that a user with role 'role0' can update a role 'role1', but cant update a role 'role2'.
    We define that role0 has domain over role1, that means that role0 don have domain over any other role different that 'role1'

  ## Owner permission
    We have already some code for manage owner permissions. but it is no generic, every resource need his own definition for detect how,
    and when a user can be owner of some data, for now the package includes a empty methods owner, this method required to be re-declared in
    the resource implementation, for add the specific login.
    For instance. When a low role 'finalUser' require to update his profile data. 
    #suppose that by default 'finalUser' has not permission to users resource.
    That means that 'finalUser' cant create or update any users data, including him self.
    With this specific permission owner we can give access to a low privilegies user like a 'finalUser' to change only his own data.
  ## Brute force protection.
    While this package consider by default to user the laravel passport package.
    It does not include a robust user library, so in this proyect we start from basic laravel authentication.
    The target of this feature is to get a level of protection similar to the sentinel security library https://cartalyst.com/manual/sentinel/2.0
  ## Public user register.
    The basic authentication system of laravel includes a way to auto register users, we need to ensure that the registration mechanism work with passport,
    and include a way to enable or disable this feature
  ## User password reset .
    The basic authentication system of laravel includes a way to reset password, we need to ensure that the reset mechanism work with passport,
    and include a way to enable or disable this feature
## Changes for the future proyects iteration
  ## Form scaffolding .
    One of the most interesting features that we are exploring, is to change the way that scaffolding work, we want the possibility to include a
    json file base for process the scaffolding. in our imagination that json will be fillet with data from a form, but this form needs to remember
    the current state of the scaffolding, and share data from the different parts of a resource, including the front data.
    For instance when we define a list of fields, that means that those fields will be reused for define:
     ```
      #api
        -model fillables, 
        -request validations
        -controller fillables,orderables, filterables...
        -migration
        -lang
      #web app
        -lang, 
        -resource definition
        -resource views
  ## Add support to future laravel versions .
    Right now the pachage is compatible with
    laravel 5.0, master branch
    laravel 5.4, 5.5 4code-crudvel branch
  

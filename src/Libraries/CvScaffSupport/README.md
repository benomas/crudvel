# CvScaff strategy

# Default tags
  <cv_singular_camel_(.*)_cv>
  <cv_plural_camel_(.*)_cv>
  <cv_singular_snake_(.*)_cv>
  <cv_plural_snake_(.*)_cv>
  <cv_singular_slug_(.*)_cv>
  <cv_plural_slug_(.*)_cv>
  <cv_singular_studly_(.*)_cv>
  <cv_plural_studly_(.*)_cv>
  <cv_singular_lower_(.*)_cv>
  <cv_plural_lower_(.*)_cv>
  <cv_singular_upper_(.*)_cv>
  <cv_plural_upper_(.*)_cv>

# This project use crudvel package, for create a standar resource, you required to folow the next steps

	-Run scaffolding command, see scaffolding.md file for examples
	-Create     		resources/lang/<es>/crudvel/<resource(plural,slug)>
	-Create     		resources/lang/<en>/crudvel/<resource(plural,slug)>
	-Edit     			App/Models/<resource(singular,StydlyCase)>
	-Edit     			App/Http/Requests/<resource(singular,StydlyCase)>
	-Edit     			App/Http/Controllers/Api/<resource(singular,StydlyCase)>
	-Edit				database/migrations/<datetime+create+resource+table+datetime(plural,snake_case)>
	-Create(optional)	database/seeds/<resource+table_seeder(singular,StydlyCase)>
	-Edit       		routes/api

you can create new templates self running cv-scaff-cv-scaff (new templete) -c

fix bitbucket origin
  git remote add origin  https://a-michel@bitbucket.org/prosubastasteam/pro-subastas-api.git

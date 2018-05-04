#!/usr/bin/python3
"""
CVtool
Copyright (C) 2018 - Energy1011[at]gmail(dot)com

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
"""

#This script to opens crudvel resource files in editor saving time open one by one
# To install and run this script in GNU/Linux for example, you need to copy this file to some dir inside in your main env variable path.
# To install runt he following command 'cp cvtool.py /usr/bin/cvtool'
import argparse
from subprocess import call
import string
import os.path
import re

print("Welcome to crudvel/crudvuel python-script-tool")
# Path list to open in backend crudvel project
paths = [ 
    [
# [0] = back
      'resources/lang/es/crudvel/<r_plural_lower_c>.php' ,
      'app/Models/<r_singular_studly_c>.php',
      'app/Http/Requests/<r_singular_studly_c>Request.php',
      'app/Http/Controllers/Api/<r_singular_studly_c>Controller.php',
      'database/seeds/<r_singular_studly_c>TableSeeder.php',
      'routes/api.php'
      ],
    [
# [1] = front 
      'src/i18n/es/crudvuel/<r_plural_slug_c)>.js',
      'src/i18n/es/crudvuel.js',
      'src/components/resources/<r_plural_slug_c>/CvCreate.vue',
      'src/components/resources/<r_plural_slug_c>/CvEdit.vue',
      'src/components/resources/<r_plural_slug_c>/CvIndex.vue',
      'src/components/resources/<r_plural_slug_c>/CvShow.vue',
      'src/services/<r_singular_studly_c>.js',
      'src/resource-definitions/<r_plural_camel_c)>.js',
      'src/plugins/boot.js',
      'src/router/Router.js',
      'src/layout/dashboard.js'
      ]
    ]

parser = argparse.ArgumentParser()
parser.add_argument('r_singular',help='Resource name in singular')
parser.add_argument('r_plural',help='Resource name in plural')
parser.add_argument('--editor',help='Editor to run <editor_command_name>, default is vim')
parser.add_argument('--action',help='Action to run, default is \'openfiles\'')
args = parser.parse_args()

if args.editor == None:
  # Default editor 'sublime_text' you can use your favorite editor like 'vim', just set te following var or use --editor argument
    args.editor = 'vim'

if args.action== None:
  # Default action 'openfiles'
    args.action = 'openfiles'

# Current dir has artisan ? is it a backend ?
if os.path.isfile('artisan'): 
  layer=0 
else: 
  layer=1

def launch_editor():
  # Wait for a keypress to continue
  try:
    input("Press enter to continue")
  except SyntaxError:
    pass
  call(paths[layer])

def open_resource_files():
  pathsToOpen = []
  # Insert resource in paths
  for index, path in enumerate(paths[layer]):
    paths[layer][index] = paths[layer][index].replace("<r_singular_studly_c>", args.r_singular.title())
    paths[layer][index] = paths[layer][index].replace("<r_plural_slug_c>", re.sub('\W+', '-', args.r_plural.lower()))
    paths[layer][index] = paths[layer][index].replace("<r_plural_camel_c>",''.join(x for x in args.r_plural.title() if not x.isspace()))
    paths[layer][index] = paths[layer][index].replace("<r_plural_lower_c>", args.r_plural.lower())
    print("esto hay")
    print(paths[layer][index])

    # remove path from list if it does not exist
    if not os.path.isfile(paths[layer][index]):
      print("[i] File: "+paths[layer][index]+" doesn't exist")
      #paths[layer].pop(index)
    else:
      pathsToOpen.append(paths[layer][index])

  paths[layer] = pathsToOpen

  if len(paths[layer]) > 0:
    paths[layer].insert(0,args.editor)
    launch_editor()
  else:
    print("Nothing to open, maybe wrong pwd (current working directory)")

if args.action == 'openfiles':
  open_resource_files()

#!/usr/bin/env python
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

print("Welcome to crudvel tool")
# Path list to open in backend crudvel project
paths = [ 'resources/lang/es/crudvel/<r_plural_lower_c>.php' ,
          'app/Models/<r_singular_studly_c>.php',
          'app/Http/Requests/<r_singular_studly_c>Request.php',
          'app/Http/Controllers/Api/<r_singular_studly_c>Controller.php',
          'database/seeds/<r_singular_studly>TableSeeder.php',
          'routes/api.php']

#paths = ['app/Models/<r_singular_studly_c>.php']
parser = argparse.ArgumentParser()
parser.add_argument('r_singular',help='Resource name in singular')
parser.add_argument('r_plural',help='Resource name in plural')
parser.add_argument('--editor',help='Editor to run <editor_command_name>, default is vim')
parser.add_argument('--action',help='Action to run, default is \'openfiles\'')
args = parser.parse_args()

if args.editor == None:
    # Default editor 'sublime_text' you can use your favorite editor like 'vim', just set te following var or use --editor argument
    args.editor = 'sublime_text'

if args.action== None:
    # Default action 'openfiles'
    args.action = 'openfiles'
def open_resource_files():
    # Insert resource in paths
    for index, path in enumerate(paths):
      paths[index] = string.replace(paths[index], '<r_singular_studly_c>', args.r_singular.title())
      paths[index] = string.replace(paths[index], '<r_plural_lower_c>', args.r_plural.lower())

      # remove path from list if it does not exist
      if not os.path.isfile(paths[index]):
        print("[i] File: "+paths[index]+" doesn't exist")
        paths.pop(index)

    paths.insert(0,args.editor)
    try:
        input("Press enter to continue")
    except SyntaxError:
        pass

if args.action == 'openfiles':
    open_resource_files()
    call(paths)

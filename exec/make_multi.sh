#!/bin/bash

############
# This shell script is used to create ATutor subsite directory. Enter a unique 
# subsite directory to be created as the first parameter. 
# ( e.g. ./make_multi.sh /var/www/subsite.atutor.com).
#
# Running the script will create the subsite directory, 
# create these directories/file in the subsite directory and make them writeable:
# 1. content directory 
# 2. themes directory
# 3. mods directory
# 4. include directory
# 5. include/config.inc.php file

#############
# Also see the mods/mange_multi/readme.multisite file for details on setting up 
# apache to allow multisite installations
###########

###########
# Make sure there's a subsite ID and Alias passed to this script
if [ -z $1 ]
	then 	
	echo "Error: No ATutor subsite directory was provided"
	exit 1
fi

subsite_dir=$1

if [ -d $subsite_dir ]
	then 	
	echo "Error: The site https://$subsite_dir_alias.$base_http exists"
	exit 1
fi 

#############
# Create the sub site's directory and make it writable
#
mkdir $subsite_dir
chmod a+rwx $subsite_dir

#############
# Create the sub site's content directory and make it writable
#
mkdir $subsite_dir/content
chmod a+rwx $subsite_dir/content

#############
# Create the sub site's content directory and make it writable
#
mkdir $subsite_dir/themes
chmod a+rwx $subsite_dir/themes

#############
# Create the sub site's content directory and make it writable
#
mkdir $subsite_dir/mods
chmod a+rwx $subsite_dir/mods

#############
# Create the sub site's content directory and make it writable
#
mkdir $subsite_dir/include

#############
# Create an empty config.inc.php file for the sub site and make it writable
#
touch $subsite_dir/include/config.inc.php 
chmod a+rwx $subsite_dir/include/config.inc.php

echo "DONE!"


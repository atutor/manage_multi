#!/bin/bash

###########
# This shell script is used to create ATutor subsite directory. Enter a unique 
# subsite directory to be created as the first parameter. 
# ( e.g. ./make_multi.sh /var/www/subsite.atutor.com).
#
# Running the script will create the subsite directory, 
# create these directories/file in the subsite directory and make them writeable except include directory:
# 1. content directory 
# 2. themes directory
# 3. mods directory
# 4. include directory
# 5. include/config.inc.php file

###########
# Also see the mods/mange_multi/readme.multisite file for details on setting up 
# apache to allow multisite installations
###########

###########
# Functions

create_dir() {
    dir=$1
    make_writable=$2

    mkdir $dir
    if [ $make_writable -eq 1 ]; then
        chmod a+rwx $dir
    fi

    if [  ! -d $dir ]; then 	
        echo "Error: Cannot create directory: $dir"
        exit 1
    fi 
}

######################
# Main process
######################

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
create_dir $subsite_dir 1

#############
# Create the sub site's content directory and make it writable
#
create_dir $subsite_dir/content 1

#############
# Create the sub site's content directory and make it writable
#
create_dir $subsite_dir/themes 1

#############
# Create the sub site's content directory and make it writable
#
# create_dir $subsite_dir/mods 1

#############
# Create the sub site's content directory
#
create_dir $subsite_dir/include 0

#############
# Create an empty config.inc.php file for the sub site and make it writable
#
config_file=$subsite_dir/include/config.inc.php

touch $config_file
chmod a+rwx $config_file

if [ ! -f $config_file ]; then
    echo "Error: Cannot create config file: $config_file"
    exit 1
fi 

echo "DONE!"
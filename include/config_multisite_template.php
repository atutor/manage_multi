<?php
/****************************************************************/
/* ATutor														*/
/****************************************************************/
/* Copyright (c) 2002-2010                                      */
/* Inclusive Design Institute                                   */
/* http://atutor.ca												*/
/*                                                              */
/* This program is free software. You can redistribute it and/or*/
/* modify it under the terms of the GNU General Public License  */
/* as published by the Free Software Foundation.				*/
/****************************************************************/
// $Id$

/**
 * Create the include/config.inc.php based on the config template
 * @param self-explanatory
 * @param true or false. If the file is written successfully, return true, otherwise, return false.
 */
function write_multisite_config_file($filename, $db_login, $db_pwd, $db_host, $db_port, $db_name, $tb_prefix,
         $comments, $content_dir, $smtp, $get_file) {
	global $multisite_config_template, $addslashes;

	$tokens = array('{USER}',
					'{PASSWORD}',
					'{HOST}',
					'{PORT}',
					'{DBNAME}',
					'{TABLE_PREFIX}',
					'{GENERATED_COMMENTS}'
				);

	$values = array(urldecode($db_login),
				$addslashes(urldecode($db_pwd)),
				$db_host,
				$db_port,
				$db_name,
				$tb_prefix,
				$comments
			);

	$multisite_config_template = str_replace($tokens, $values, $multisite_config_template);

	if (!$handle = @fopen($filename, 'wb')) {
		return false;
	}
	@ftruncate($handle,0);
	if (!@fwrite($handle, $multisite_config_template, strlen($multisite_config_template))) {
		return false;
	}

	@fclose($handle);
	return true;
}

$multisite_config_template = "<"."?php 
/************************************************************************/
/* ATutor                                                               */
/************************************************************************/
/* Copyright (c) 2002-2012                                              */
/* http://atutor.ca                                                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or        */
/* modify it under the terms of the GNU General Public License          */
/* as published by the Free Software Foundation.                        */
/************************************************************************/
{GENERATED_COMMENTS}
/************************************************************************/
/************************************************************************/
/* the database user name                                               */
define('DB_USER_MULTISITE',                    '{USER}');

/* the database password                                                */
define('DB_PASSWORD_MULTISITE',                '{PASSWORD}');

/* the database host                                                    */
define('DB_HOST_MULTISITE',                    '{HOST}');

/* the database tcp/ip port                                             */
define('DB_PORT_MULTISITE',                    '{PORT}');

/* the database name                                                    */
define('DB_NAME_MULTISITE',                    '{DBNAME}');

/* The prefix to add to table names to avoid conflicts with existing    */
/* tables. Default: AT_                                                 */
define('TABLE_PREFIX_MULTISITE',               '{TABLE_PREFIX}');

?".">";

?>
<?php
/************************************************************************/
/* ATutor                                                               */
/************************************************************************/
/* Copyright (c) 2002-2012                                              */
/* Inclusive Design Institute                                           */
/* http://atutor.ca                                                     */
/* This program is free software. You can redistribute it and/or        */
/* modify it under the terms of the GNU General Public License          */
/* as published by the Free Software Foundation.                        */
/************************************************************************/
// $Id$

if (!defined('AT_INCLUDE_PATH') || !defined('AT_MULTISITE_CONFIG_FILE')) { exit; }

$multisite_installed = true;

if (file_exists(AT_MULTISITE_CONFIG_FILE)) {
	include(AT_MULTISITE_CONFIG_FILE);
	
	if (!defined('AT_MULTISITE_INSTALL') || !AT_MULTISITE_INSTALL) {
		$multisite_installed = false;
	}
} else {
	$multisite_installed = false;
}

if (!$multisite_installed) {
	if ($msg) {
		require(AT_INCLUDE_PATH.'header.inc.php');
		$msg->printErrors(array('MULTISITE_CONFIG_FILE_NOT_EXIST', AT_MULTISITE_CONFIG_FILE));
		require(AT_INCLUDE_PATH.'footer.inc.php');
		exit;
	} else {
		exit('Cannot find the multisite config file "' . AT_MULTISITE_CONFIG_FILE . '". Run step "Initial Configuration" to create it.');
	}
}

if (defined('DB_NAME_MULTISITE')) {
	//$db_multisite = @mysql_connect(DB_HOST_MULTISITE . ':' . DB_PORT_MULTISITE, DB_USER_MULTISITE, DB_PASSWORD_MULTISITE);	
	$db_multisite = at_db_connect(DB_HOST_MULTISITE, DB_PORT_MULTISITE, DB_USER_MULTISITE, DB_PASSWORD_MULTISITE, '');	

	if (!$db_multisite) {
		/* AT_ERROR_NO_DB_CONNECT */
		require_once(AT_INCLUDE_PATH . 'classes/ErrorHandler/ErrorHandler.class.php');
		$err = new ErrorHandler();
		trigger_error('VITAL#Unable to connect to multisite db.', E_USER_ERROR);
		exit;
	}
}
?>
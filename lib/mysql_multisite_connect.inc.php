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

if (!defined('AT_INCLUDE_PATH') || !defined('MM_MULTISITE_CONFIG_FILE')) { exit; }

if (file_exists(MM_MULTISITE_CONFIG_FILE)) {
	include(MM_MULTISITE_CONFIG_FILE);
} else {
	require(AT_INCLUDE_PATH.'header.inc.php');
	$msg->printErrors(array('MM_MULTISITE_CONFIG_FILE_NOT_EXIST', MM_MULTISITE_CONFIG_FILE));
	require(AT_INCLUDE_PATH.'footer.inc.php');
	exit;
}

if (defined('DB_NAME_MULTISITE')) {
	$db_multisite = @mysql_connect(DB_HOST_MULTISITE . ':' . DB_PORT_MULTISITE, DB_USER_MULTISITE, DB_PASSWORD_MULTISITE);	

	if (!$db_multisite) {
		/* AT_ERROR_NO_DB_CONNECT */
		require_once(AT_INCLUDE_PATH . 'classes/ErrorHandler/ErrorHandler.class.php');
		$err = new ErrorHandler();
		trigger_error('VITAL#Unable to connect to multisite db.', E_USER_ERROR);
		exit;
	}
/*
	if (!@mysql_select_db(DB_NAME_MULTISITE, $db_multisite)) {
		require_once(AT_INCLUDE_PATH . 'classes/ErrorHandler/ErrorHandler.class.php');
		$err = new ErrorHandler();
		trigger_error('VITAL#DB connection established, but database "'.DB_NAME_MULTISITE.'" cannot be selected.',
		              E_USER_ERROR);
		exit;
	}
*/
}
?>
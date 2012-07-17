<?php
/************************************************************************/
/* ATutor																*/
/************************************************************************/
/* Copyright (c) 2002-2010                                              */
/* Inclusive Design Institute                                           */
/* http://atutor.ca                                                     */
/* This program is free software. You can redistribute it and/or        */
/* modify it under the terms of the GNU General Public License          */
/* as published by the Free Software Foundation.                        */
/************************************************************************/
// $Id$

define('AT_INCLUDE_PATH', '../../include/');
require (AT_INCLUDE_PATH.'vitals.inc.php');
admin_authenticate(AT_ADMIN_PRIV_MANAGE_MULTI);
require('classes/Subsite.class.php');

$site = $_REQUEST['site'];

if (isset($_POST['submit_no'])) {
	$msg->addFeedback('CANCELLED');
	header('Location: index_admin.php');
	exit;
} else if ($_POST['step'] == 2 && isset($_POST['submit_yes'])) {
	$subsite = new Subsite($site);
	$subsite->delete();
	
	if (!$msg->containsErrors()) {
		$msg->addFeedback('ACTION_COMPLETED_SUCCESSFULLY');
	}
	
	header('Location: index_admin.php');
	exit;
}

require(AT_INCLUDE_PATH.'header.inc.php'); 

if (!isset($_POST['step'])) {
	$hidden_vars['step']   = 1;
	$hidden_vars['site'] = $site;
	$msg->addConfirm(array('DELETE_SUBSITE_1', $site), $hidden_vars);
	$msg->printConfirm();
} else if ($_POST['step'] == 1) {
	$hidden_vars['step']   = 2;
	$hidden_vars['site'] = $site;
	$msg->addConfirm(array('DELETE_SUBSITE_2', $site), $hidden_vars);
	$msg->printConfirm();
}

require(AT_INCLUDE_PATH.'footer.inc.php'); 
?>
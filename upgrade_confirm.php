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

$in_sites = $_REQUEST['sites'];
$sites = explode(',', $in_sites);

if (isset($_POST['submit_no'])) {
	$msg->addFeedback('CANCELLED');
	header('Location: index_admin.php');
	exit;
} else if (isset($_POST['submit_yes'])) {
	foreach ($sites as $site) {
		$subsite = new Subsite($site);
		if ($subsite->upgrade()) {
			$msg->addFeedback(array('SUBSITE_UPGRADED', $site));
		}
		
		unset($subsite);
	}
	
	if (!$msg->containsErrors()) {
		$msg->addFeedback('ACTION_COMPLETED_SUCCESSFULLY');
	}
	
	header('Location: upgrade_subsite.php');
	exit;
}

require(AT_INCLUDE_PATH.'header.inc.php'); 

$hidden_vars['sites'] = $in_sites;
$msg->addConfirm(array('UPGRADE_SUBSITE', implode('<br />', $sites)), $hidden_vars);
$msg->printConfirm();

require(AT_INCLUDE_PATH.'footer.inc.php'); 
?>
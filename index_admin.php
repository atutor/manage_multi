<?php
define('AT_INCLUDE_PATH', '../../include/');
require (AT_INCLUDE_PATH.'vitals.inc.php');
admin_authenticate(AT_ADMIN_PRIV_MANAGE_MULTI);
require('mm_connect.php');

if($_REQUEST['delete'] == 'Delete'){
	require (AT_INCLUDE_PATH.'header.inc.php');
	$hidden_vars['del_site_id'] = intval($_REQUEST['site_id']);
	$confirm = array('DELETE_SUBSITE', $names_html);
	$msg->addConfirm($confirm, $hidden_vars);
	$msg->printConfirm();
	require (AT_INCLUDE_PATH.'footer.inc.php');
	exit;

}else if($_REQUEST['submit_yes'] == "Yes"){
	$delete_id = intval($_REQUEST['del_site_id']);
	$sql = "DELETE from subsites WHERE site_id = '$delete_id'";
	if($result = mysql_query($sql, $db_mm)){
		$msg->addFeedback('SITE_DELETED');
	}else{
		$msg->addFeedback('SITE_DELETED_FAILED');
	}
	header('Location:index_admin.php');
} else if ($_REQUEST['submit_no'] == "No"){
	$msg->addFeedback('CANCELLED');
	header('Location:index_admin.php');
} else if ($_REQUEST['submit'] == 'edit'){

//
}
require (AT_INCLUDE_PATH.'header.inc.php');
debug($_REQUEST);

// Display a table listing all subsites.
$sql = "SELECT * from subsites";
$result = mysql_query($sql, $db_mm);
include('index.tmpl.php');

require (AT_INCLUDE_PATH.'footer.inc.php'); ?>
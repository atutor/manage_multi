<?php
define('AT_INCLUDE_PATH', '../../include/');
require (AT_INCLUDE_PATH.'vitals.inc.php');
admin_authenticate(AT_ADMIN_PRIV_MANAGE_MULTI);
require('lib/mysql_multisite_connect.inc.php');
require('classes/Subsite.class.php');

if(isset($_POST['delete'], $_POST['site_url'])){
	header('Location: delete_subsite.php?site='.urlencode($_POST['site_url']));
	exit;
} else if(isset($_POST['enable'], $_POST['site_url'])){
	$subsite = new Subsite($_POST['site_url']);
	$subsite->enable();
	$msg->addFeedback('ACTION_COMPLETED_SUCCESSFULLY');
} else if (isset($_POST['disable'], $_POST['site_url'])){
	$subsite = new Subsite($_POST['site_url']);
	$subsite->disable();
	$msg->addFeedback('ACTION_COMPLETED_SUCCESSFULLY');
} else if (isset($_POST['disable']) || isset($_POST['enable']) || isset($_POST['delete'])) {
	$msg->addError('NO_ITEM_SELECTED');
}

// Display a table listing all subsites.
$db_tmp = $db;
$db = $db_multisite;
at_db_select(DB_NAME_MULTISITE, $db);

$sql = "SELECT * from " . TABLE_PREFIX_MULTISITE . "subsites";
$rows_subsites = queryDB($sql, array());
$db = $db_tmp;
at_db_select(DB_NAME, $db);
$_custom_head = '    <script src="'.$_base_path.'mods/manage_multi/js/manage_multi.js"></script>';
require (AT_INCLUDE_PATH.'header.inc.php');
$msg->printAll();

include('templates/index_admin.tmpl.php');

require (AT_INCLUDE_PATH.'footer.inc.php'); ?>
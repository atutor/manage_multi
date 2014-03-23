<?php
define('AT_INCLUDE_PATH', '../../include/');
require (AT_INCLUDE_PATH.'vitals.inc.php');
admin_authenticate(AT_ADMIN_PRIV_MANAGE_MULTI);
require_once('lib/mysql_multisite_connect.inc.php');
require('classes/Subsite.class.php');

if(isset($_POST['upgrade'], $_POST['site_url']) && is_array($_POST['site_url'])){
	$sites = implode(',', $_POST['site_url']);
	header('Location: upgrade_confirm.php?sites='.urlencode($sites));
	exit;
}
// Display a table listing all subsites.
$db_tmp = $db;
 
at_db_select(DB_NAME_MULTISITE, $db);

$sql = "SELECT * from " . TABLE_PREFIX_MULTISITE . "subsites";
$original_rows = queryDB($sql, array());
$rows = array();

foreach ($original_rows as $original_row) {
	$subsite = new Subsite($original_row['site_url']);
	$row = $original_row;
	$row['version'] = $subsite->get_atutor_version();
	array_push($rows, $row);
}

$db = $db_tmp;
$_custom_head = '    <script src="'.$_base_path.'mods/manage_multi/js/manage_multi.js"></script>';
require (AT_INCLUDE_PATH.'header.inc.php');
$msg->printAll();

include('templates/upgrade_subsite.tmpl.php');

require (AT_INCLUDE_PATH.'footer.inc.php'); ?>
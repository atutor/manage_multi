<?php
define('AT_INCLUDE_PATH', '../../include/');
require (AT_INCLUDE_PATH.'vitals.inc.php');
admin_authenticate(AT_ADMIN_PRIV_MANAGE_MULTI);


if($_REQUEST['submit_mysql']){
$mm_mysql_server = "localhost";
$mm_mysql_user = $addslashes($_REQUEST['mysql_user']);
$mm_mysql_password = $addslashes($_REQUEST['mysql_password']);
$mm_mysql_db_name = $addslashes($_REQUEST['mysql_db_name']);
$mm_mysql_port = "3306";
$db_mm = @mysql_connect($mm_mysql_server.':'.$mm_mysql_port, $mm_mysql_user, $mm_mysql_password, true);

	$sql = "CREATE DATABASE ".$mm_mysql_db_name;
//debug("sql".$sql);
//echo $sql;
	if($result = mysql_query($sql, $db_mm)){
		$selected_db = mysql_select_db($mm_mysql_db_name, $db_mm);
		$sql = "CREATE TABLE `subsites` (
			`site_id` mediumint(10) NOT NULL AUTO_INCREMENT,
			`site_name` varchar(25) NOT NULL,
			`site_URL` varchar(255) NOT NULL,
			`site_type`  ENUM('domain', 'directory'),
			`directory` varchar(255) NOT NULL, 
			`enabled` tinyint(1) NULL DEFAULT '0',
			PRIMARY KEY ( `site_id` )
			) ENGINE = MyISAM;";
			
		if($result = mysql_query($sql, $db_mm)){
				$msg->addFeedback('MM_CREATE_DB_SUCCESSFUL');
		} else{
			$msg->addError('MM_CREATE_TABLE_FAILED');
		}
	} else{
		$msg->addError('MM_CREATE_DB_FAILED');
	}

	$sql = "REPLACE INTO ".TABLE_PREFIX."config VALUES ('mm_mysql_server', $mm_mysql_server)";
	mysql_query($sql, $db);
	write_to_log(AT_ADMIN_LOG_REPLACE, 'config', mysql_affected_rows($db), $sql);
	$sql = "REPLACE INTO ".TABLE_PREFIX."config VALUES ('mm_mysql_user', '$mm_mysql_user')";
	mysql_query($sql, $db);
	write_to_log(AT_ADMIN_LOG_REPLACE, 'config', mysql_affected_rows($db), $sql);
	$sql = "REPLACE INTO ".TABLE_PREFIX."config VALUES ('mm_mysql_password', '$mm_mysql_password')";
	mysql_query($sql, $db);
	write_to_log(AT_ADMIN_LOG_REPLACE, 'config', mysql_affected_rows($db), $sql);
	$sql = "REPLACE INTO ".TABLE_PREFIX."config VALUES ('mm_mysql_db_name', '$mm_mysql_db_name')";
	mysql_query($sql, $db);
	write_to_log(AT_ADMIN_LOG_REPLACE, 'config', mysql_affected_rows($db), $sql);
	$sql = "REPLACE INTO ".TABLE_PREFIX."config VALUES ('mm_mysql_port', '$mm_mysql_port')";
	mysql_query($sql, $db);
	write_to_log(AT_ADMIN_LOG_REPLACE, 'config', mysql_affected_rows($db), $sql);
		

}



require (AT_INCLUDE_PATH.'header.inc.php');
?>

<div class="input-form">
<h2>Setup Subsite Database</h2>
<p>Use the form below to setup a publicly accessible database, independent of this ATutor installation, that can be accessed read-only by all subsites created.</p>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>"  name="setup_multi" method="post" class="form_input">
<label for="mysql_user">MySQL Username</label><input type="text" name="mysql_user" id="mysql_user" value="<?php echo $_config['mm_mysql_user']; ?>"/><br />
<label for="mysql_password">MySQL Password</label><input type="password" name="mysql_password" id="mysql_password" value="<?php echo $_config['mm_mysql_user']; ?>"/>
<input type="hidden" name="mysql_db_name" value="manage_multi" />
<input type="submit" name="submit_mysql" />
</form>
</div>


<?php
require (AT_INCLUDE_PATH.'footer.inc.php');
?>
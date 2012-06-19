<?php
define('AT_INCLUDE_PATH', '../../include/');
require (AT_INCLUDE_PATH.'vitals.inc.php');
admin_authenticate(AT_ADMIN_PRIV_MANAGE_MULTI);
require (AT_INCLUDE_PATH.'header.inc.php');
require('mm_connect.php');

if($_REQUEST['submit_create']){
$site_name = $addslashes($_REQUEST['site_name']);
$site_url = $addslashes($_REQUEST['site_url']);
$site_type = $addslashes($_REQUEST['site_type']);
$directory = $addslashes($_REQUEST['directory']);
$enabled = intval($_REQUEST['enabled']);


$sql = "INSERT into subsites VALUES('', '$site_name', '$site_url', '$site_type', '$directory', $enabled)";
//debug($sql);
	if($result = mysql_query($sql, $db_mm)){
		$id = mysql_insert_id($db);
		
		exec("../../make_multi.sh $id $site_name $directory", $output, $return_var);
		$msg->addFeedback('MM_CREATE_SITE_SUCCESSFUL');
		header('Location:index_admin.php');
	} else{
		$msg->addError('MM_CREATE_SITE_FAILED');
	}

} else if($_REQUEST['edit']){

}
debug('make multi script'.MAKE_MULTI_SCRIPT);
?>


<div class="input-form">
<h2>Create/Edit Subsite Database</h2>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" name="make_multi" method="post">

<label for="site_name">Site Alias</label><input type="text" name="site_name" id="site_name" value="<?php echo $row['site_name'];?>" /><br />
<label for="site_url">Site URL</label><input type="text" name="site_url"  id="site_url" value="" /><br />
<label for="site_type">Type</label>
<select name="site_type">
<option value="domain">Domain</option>
<option value="directory">Directory</option>
</select><br />

<label for="directory">Directory Path</label><input type="text" name="directory"  id="directory" value="" /><br />
<label for="enabled">Enabled</label><input type="checkbox" name="enabled"  id="enabled" value="1" /><br />
<input type="submit" name="submit_create" value="Create Site"/>
</form>
</div>
<?php
require (AT_INCLUDE_PATH.'footer.inc.php');
?>
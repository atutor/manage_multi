<?php
define('AT_INCLUDE_PATH', '../../include/');
require(AT_INCLUDE_PATH.'vitals.inc.php');
admin_authenticate(AT_ADMIN_PRIV_MANAGE_MULTI);

require('classes/Subsite.class.php');

if($_POST['submit']){
	// make sure the new subsite is unique
	$_POST['site_name'] = str_replace(' ', '', $_POST['site_name']);
	
	$subsite = new Subsite();
	$subsite->create($_POST['site_name'], $_POST['enabled']);
}

require(AT_INCLUDE_PATH.'header.inc.php');

$msg->printAll();
?>

<div class="input-form">
<h2>Create/Edit Subsite Database</h2>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" name="make_multi" method="post">
	<div class="row">
		<p><?php echo _AT('create_subsite_notes', realpath($make_multi_script), $subsite_main_dir); ?></p>
	</div>
	
	<div class="row">
		<span class="required" title="<?php echo _AT('required_field'); ?>">*</span>
		<label for="site_name"><?php echo _AT('subsite_url'); ?></label><br />
		<input type="text" name="site_name" id="site_name" value="<?php echo htmlspecialchars($_POST['site_name']);?>" /><?php echo '.' . MM_COMMON_DOMAIN; ?><br />
	</div>
	
	<div class="row">
		<label for="enabled"><?php echo _AT('enable');?></label>
		<input type="checkbox" name="enabled"  id="enabled" value="1"<?php if ($_POST['enabled']) echo ' checked="checked"'; ?> /><br />
	</div>
	<input type="submit" name="submit" value="Create Site"/>
</form>
</div>

<?php require (AT_INCLUDE_PATH.'footer.inc.php'); ?>
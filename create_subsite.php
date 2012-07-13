<?php
define('AT_INCLUDE_PATH', '../../include/');
require(AT_INCLUDE_PATH.'vitals.inc.php');
admin_authenticate(AT_ADMIN_PRIV_MANAGE_MULTI);

require('classes/Subsite.class.php');

$subsite = new Subsite();

if($_POST['submit']){
	// make sure the new subsite is unique
	$_POST['site_name'] = str_replace(' ', '', $_POST['site_name']);
	$_POST['site_display_name'] = trim($_POST['site_display_name']);
	$_POST['site_admin_email'] = trim($_POST['site_admin_email']);
	$_POST['instructor_username'] = trim($_POST['instructor_username']);
	$_POST['instructor_fname'] = str_replace('<', '', trim($_POST['instructor_fname']));
	$_POST['instructor_lname'] = str_replace('<', '', trim($_POST['instructor_lname']));
	$_POST['instructor_email'] = trim($_POST['instructor_email']);
	
	$subsite->create($_POST['site_name'], $_POST['site_display_name'], $_POST['site_admin_email'], $_POST['just_social'], $_POST['instructor_username'],
	                 $_POST['instructor_fname'], $_POST['instructor_lname'], $_POST['instructor_email'], $_POST['enabled']);
}

require(AT_INCLUDE_PATH.'header.inc.php');

$msg->printAll();
?>

<div class="input-form">
<h2>Create/Edit Subsite Database</h2>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" name="make_multi" method="post">
	<div class="row">
		<p><?php echo _AT('create_subsite_notes', realpath($subsite->make_multi_script), $subsite->subsite_main_dir); ?></p>
	</div>
	
	<fieldset class="group_form"><legend class="group_form"><?php echo _AT('site_info'); ?></legend>
	<div class="row">
		<span class="required" title="<?php echo _AT('required_field'); ?>">*</span>
		<label for="site_name"><?php echo _AT('subsite_url'); ?></label><br />
		<input type="text" name="site_name" id="site_name" value="<?php echo htmlspecialchars($_POST['site_name']);?>" /><?php echo '.' . MM_COMMON_DOMAIN; ?><br />
		<small>&middot; <?php echo _AT('site_name_contain_only'); ?><br />
		&middot; <?php echo _AT('20_max_chars'); ?></small>
	</div>
	
	<div class="row">
		<span class="required" title="<?php echo _AT('required_field'); ?>">*</span>
		<label for="site_display_name"><?php echo _AT('site_name'); ?></label><br />
		<input type="text" name="site_display_name" id="site_display_name" value="<?php echo htmlspecialchars($_POST['site_display_name']);?>" /><br />
	</div>
	
	<div class="row">
		<span class="required" title="<?php echo _AT('required_field'); ?>">*</span>
		<label for="site_admin_email"><?php echo _AT('site_admin_email'); ?></label><br />
		<input type="text" name="site_admin_email" id="site_admin_email" value="<?php echo htmlspecialchars($_POST['site_admin_email']);?>" /><br />
	</div>
	
	<div class="row">
		<span class="required" title="<?php echo _AT('required_field'); ?>">*</span>
		<label for="just_social"><?php echo _AT('just_social'); ?></label><br />
		<label for="social_y">Just Social</label><input type="radio" name="just_social" id="social_y" value="1" class="formfield" <?php echo ($_POST['just_social']==1)?' checked="checked"':''; ?>/>
		<label for="social_n">Social and LMS</label><input type="radio" name="just_social" id="social_n" value="0" class="formfield" <?php echo ($_POST['just_social']==0 || !isset($_POST['just_social']))?' checked="checked"':''; ?>/><br />
		<small>&middot; <?php echo _AT('just_social_notes'); ?></small>
	</div>
	
	<div class="row">
		<label for="enabled"><?php echo _AT('enable');?></label>
		<input type="checkbox" name="enabled"  id="enabled" value="1"<?php if ($_POST['enabled']) echo ' checked="checked"'; ?> /><br />
	</div>
	</fieldset>
	
	<fieldset class="group_form"><legend class="group_form"><?php echo _AT('instructor_info'); ?></legend>
	<div class="row">
		<span><?php echo _AT('instructor_notes'); ?></span>
	</div>

	<div class="row">
		<span class="required" title="<?php echo _AT('required_field'); ?>">*</span>
		<label for="instructor_username"><?php echo _AT('username'); ?></label><br />
		<input type="text" name="instructor_username" id="instructor_username" value="<?php echo htmlspecialchars($_POST['instructor_username']);?>" /><br />
	</div>

	<div class="row">
		<span class="required" title="<?php echo _AT('required_field'); ?>">*</span>
		<label for="instructor_fname"><?php echo _AT('first_name'); ?></label><br />
		<input type="text" name="instructor_fname" id="instructor_fname" value="<?php echo htmlspecialchars($_POST['instructor_fname']);?>" /><br />
	</div>

	<div class="row">
		<span class="required" title="<?php echo _AT('required_field'); ?>">*</span>
		<label for="instructor_lname"><?php echo _AT('last_name'); ?></label><br />
		<input type="text" name="instructor_lname" id="instructor_lname" value="<?php echo htmlspecialchars($_POST['instructor_lname']);?>" /><br />
	</div>

	<div class="row">
		<span class="required" title="<?php echo _AT('required_field'); ?>">*</span>
		<label for="instructor_email"><?php echo _AT('email'); ?></label><br />
		<input type="text" name="instructor_email" id="instructor_email" value="<?php echo htmlspecialchars($_POST['instructor_email']);?>" /><br />
	</div>
	</fieldset>

	<input type="submit" name="submit" value="Create Site"/>
</form>
</div>

<?php require (AT_INCLUDE_PATH.'footer.inc.php'); ?>
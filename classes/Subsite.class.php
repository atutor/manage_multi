<?php
/************************************************************************/
/* ATutor                                                               */
/************************************************************************/
/* Copyright (c) 2012                                                   */
/* Inclusive Design Institute                                           */
/* http://atutor.ca                                                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or        */
/* modify it under the terms of the GNU General Public License          */
/* as published by the Free Software Foundation.                        */
/************************************************************************/
// $Id$

/**
* Subsite
* Class for subsite creation
* @access	public
* @author	Cindy Qi Li
* @package	Patch
*/

if (!defined('AT_INCLUDE_PATH')) { exit; }

require(AT_INCLUDE_PATH . 'classes/sqlutility.class.php');
require(AT_INCLUDE_PATH . 'install/install.inc.php');
require(AT_INCLUDE_PATH . 'install/config_template.php');
require(AT_INCLUDE_PATH . '../mods/_core/file_manager/filemanager.inc.php');
require(AT_INCLUDE_PATH . '../mods/manage_multi/lib/mysql_multisite_connect.inc.php');

class Subsite {

	// all private
	var $make_multi_script;           // the shell script to create subsite directory
	var $subsite_main_dir;            // the main directory where all the subsites are created in
	var $site_url;                    // the url of the subsite, eg, myself.atutor.ca
	var $default_admin_user_name;     // The default subsite admin user name
	
	var $session_path;                // store the config.session_path of the main site which is equivalent with the one used by subsites
	var $main_site_contact_email;     // The from email used to send the confirmation at the end of the subsite creation
	
	// Subsite status
	var $enabled;

	/**
	* Constructor: Initialize object members
	* This is a multiple constructor that can be used to initialize 2 types of subsite object
	* 1. No parameter: create an empty subsite object in preparation for creating a new subsite
	* 2. One string parameter "subsite url": create an object for an existing subsite
	* @access  public
	*/
	function Subsite($site_url = null) 
	{
		global $msg;
		
		$this->subsite_main_dir = realpath($_SERVER['DOCUMENT_ROOT'] . '/../') . '/';
		$this->default_admin_user_name = 'admin';
		
		if ($site_url === null) { // in preparation to create a new subsite
			$this->make_multi_script = 'exec/make_multi.sh';
		} else { // an existing subsite
			$this->site_url = $this->get_valid_url($site_url);
			
			if (!$this->site_url) {
				$msg->addError(array('ONE_INVALID_URL', $site_url));
				return false;
			}
			$this->switch_subsite_manage_db();
			
			$info = $this->get_subsite_info($site_url);
			$this->enabled = $info['enabled'];
			
			$this->finalize();
		}
	}

	/**
	* Main process to create subsite.
	* @access  public
	* @return  true  if subsite is successfully created
	*          false if an error occurred. The error messages are saved into the global var $msg
	*          the progress or error information are saved into global var $msg
	* @author  Cindy Qi Li
	*/
	public function create($site_name, $site_display_name, $admin_email, $just_social, $instructor_username,
	                       $instructor_fname, $instructor_lname, $instructor_email, $enabled) 
	{
		global $msg, $addslashes;
		
		$site_name = $addslashes(str_replace(' ', '', $site_name));
		$admin_email = $addslashes($admin_email);
		$instructor_username = $addslashes($instructor_username);
		$instructor_fname = $addslashes($instructor_fname);
		$instructor_lname = $addslashes($instructor_lname);
		$instructor_email = $addslashes($instructor_email);
		$just_social = intval($just_social);
		$enabled = intval($enabled);
		
		// **** verify the input vars ****
		if (!$this->validate_input($site_name, $site_display_name, $admin_email, 
		     $instructor_username, $instructor_fname, $instructor_lname, $instructor_email)) {
			return false;
		}

		$this->site_url = $addslashes($this->get_site_url($site_name));
		
		$this->prepare_creation();
		
		// **** check the uniqueness of the requested site ****
		if (!$this->is_site_unique($this->site_url)) {
			$msg->addError(array("SUBSITE_ALREADY_EXIST", $this->site_url, implode(", ", $this->get_unique_site_urls($site_name))));
			$this->finalize();
			return false;
		}
		
		$subsite_full_path = $this->subsite_main_dir . $this->site_url;
		
		// **** create subsite phisical directory ****
		if (!$this->create_subsite_dir($subsite_full_path)) {
			$this->finalize();
			return false;
		}
		
		// **** create content sub-directories ****
		create_content_subdir($subsite_full_path . '/content', AT_INCLUDE_PATH . '../images/index.html');
		if ($msg->containsErrors()) {
			$this->finalize();
			return false;
		}
		
		// **** create and switch to subsite database ****
		// ToDo: Backup the global db due to the flaw that $sqlUtility->queryFromFile() excutes the query file on global db instance $db
		global $db;
		$backup_db = $db;
		
		$subsite_db_name = $this->get_unique_db_name($site_name, DB_HOST_MULTISITE, DB_PORT_MULTISITE, DB_USER_MULTISITE, DB_PASSWORD_MULTISITE);
		$db = create_and_switch_db(DB_HOST_MULTISITE, DB_PORT_MULTISITE, DB_USER_MULTISITE, DB_PASSWORD_MULTISITE, TABLE_PREFIX_MULTISITE, $subsite_db_name, false);
		if ($msg->containsErrors()) {
			$this->finalize();
			return false;
		}
		
		// **** import languages and tables into subsite database ****
		$sqlUtility = new SqlUtility();
		$sqlUtility->queryFromFile(AT_INCLUDE_PATH . 'install/db/atutor_schema.sql', TABLE_PREFIX_MULTISITE, false);
		$sqlUtility->queryFromFile(AT_INCLUDE_PATH . 'install/db/atutor_language_text.sql', TABLE_PREFIX_MULTISITE, false);
		if ($msg->containsErrors()) {
			$this->finalize();
			return false;
		}
		
		// revert back the global db instance
		$db = $backup_db;
		
		$msg->addFeedback('SUBSITE_TABLES_CREATED');
		
		// **** create mysql user/pwd for subsite database ****
		// the super mysql id for creating mysql user is stored in include/config_multisite.inc.php
		$mysql_account = $this->get_unique_mysql_account($subsite_db_name);
		$mysql_pwd = $this->create_mysql_user(DB_HOST_MULTISITE, $mysql_account, $subsite_db_name, DB_USER_MULTISITE);
		
		if (!$mysql_pwd) {
			$this->finalize();
			return false;
		}
		$msg->addFeedback(array('MYSQL_ACCT_CREATED', $mysql_account));
		
		// **** add admin/instructor accounts ****
		$admin_pwd = $this->get_random_string(10);
		$admin_pwd_encrypted = sha1($admin_pwd);
		
		$instructor_pwd = $this->get_random_string(10);
		$instructor_pwd_encrypted = sha1($instructor_pwd);
		
		install_step_accounts($this->default_admin_user_name, $admin_pwd_encrypted, $admin_email, $site_display_name,
		                      $admin_email, $instructor_username, $instructor_pwd_encrypted,
		                      $instructor_fname, $instructor_lname, $instructor_email,
		                      $just_social, '', $this->session_path, DB_HOST_MULTISITE, DB_PORT_MULTISITE, 
		                      $mysql_account, $mysql_pwd, $subsite_db_name, TABLE_PREFIX_MULTISITE);

		// **** Write subsite include/config.inc.php ****
		$filename = $this->subsite_main_dir . $this->site_url . '/include/config.inc.php';
		
		if (!file_exists($filename) || !is_writeable($filename)) {
			$msg->addError(array('FILE_NOT_WRITABLE', $filename));
			$this->finalize();
			return false;
		}
		
		$comments = '/*'.str_pad(' This file was generated by the ATutor '.$new_version. ' installation script.', 70, ' ').'*/'."\n".
		            '/*'.str_pad(' File generated '.date('Y-m-d H:m:s'), 70, ' ').'*/';
		$content_dir = $this->subsite_main_dir . $this->site_url . '/content/';
		
		$smtp = MAIL_USE_SMTP ? 'true' : 'false';
		$force_get_file = AT_FORCE_GET_FILE ? 'TRUE' : 'FALSE';
		
		write_config_file($filename, $mysql_account, $mysql_pwd, DB_HOST_MULTISITE, 
		                  DB_PORT_MULTISITE, $subsite_db_name, TABLE_PREFIX_MULTISITE,
		                  $comments, $content_dir, $smtp, $force_get_file);
		chmod($filename, 0444);
		$msg->addFeedback(array('CONFIG_FILE_WRITTEN', $filename));

		$this->switch_subsite_manage_db();
		
		// **** update database ****
		if (!$this->update_table($this->site_url, $enabled)) {
			$this->finalize();
			return false;
		}
		$msg->addFeedback('MANAGE_TABLE_UPDATED');
		
		// **** send email to admin with admin and instructor login information
		$this->send_email($this->main_site_contact_email, $admin_email, $full_site_url, $this->default_admin_user_name, $admin_pwd, $instructor_username, $instructor_pwd);
		
		$this->enabled = $enabled;
		
		$full_site_url = AT_SERVER_PROTOCOL . $this->site_url;
		$msg->addFeedback(array('CREATE_SUBSITE_SUCCESSFUL', $full_site_url, $full_site_url, $this->default_admin_user_name, $admin_pwd, $instructor_username, $instructor_pwd));
		
		$this->finalize();
		return true;
	}

	/**
	 * Delete a subsite
	 */
	public function delete() {
		if (!$this->site_url) return false;
		
		$site_dir = $this->subsite_main_dir . $this->site_url;
		// Parse subsite config file
		$config_file = $site_dir . '/include/config.inc.php';
		
		$site_configs = $this->parse_config_file($config_file);
		if (!$site_configs) {
			$this->finalize();
			return false;
		}
		
		// remove table entry
		$this->switch_subsite_manage_db();
		if (!$this->remove_table_entry($this->site_url)) {
			$this->finalize();
			return false;
		}
		
		// drop database
		if (!$this->drop_db($site_configs['DB_NAME'], DB_USER_MULTISITE)) {
			$this->finalize();
			return false;
		}
		
		// delete mysql account
		if (!$this->drop_mysql_user($site_configs['DB_HOST'], $site_configs['DB_USER'], DB_USER_MULTISITE)) {
			$this->finalize();
			return false;
		}
		
		// delete phisical directory
		if (!clr_dir($site_dir)) {
			$msg->addError(array('DEL_DIR_FAILED', $site_dir));
			$this->finalize();
			return false;
		}
		
		$this->finalize();
		return true;
	}
	
	/**
	 * Enable a subsite
	 */
	public function enable() {
		if (!$this->site_url) return false;
		
		$this->switch_subsite_manage_db();
		$this->set_status($this->site_url, 1);
		$this->finalize();
	}
	
	/**
	 * Enable a subsite
	 */
	public function disable() {
		if (!$this->site_url) return false;
		
		$this->switch_subsite_manage_db();
		$this->set_status($this->site_url, 0);
		$this->finalize();
	}
	
	/**
	 * Return the directory where subsites reside.
	 */
	public function get_subsite_main_dir() {
		return $this->subsite_main_dir;
	}
	
	/**
	 * Return the directory where subsites reside.
	 */
	public function get_make_multi_script() {
		return $this->make_multi_script;
	}
	
	/**
	 * Check whether the subsite is enabled
	 */
	public function isEnabled() {
		return $this->enabled ? true : false;
	}
	
	/**
	 * Check if the url is the valid
	 */
	private function get_valid_url($url) {
		return preg_match('/^[A-Za-z0-9.]+$/', $url) ? $url : false;
	}
	/** 
	 * Return the subsite information
	 */
	private function get_subsite_info($site_url) {
		global $db_multisite, $addslashes;
		
		$sql = "SELECT * FROM " . TABLE_PREFIX_MULTISITE . "subsites where site_url = '" . $addslashes($site_url) . "'";
		$result = mysql_query($sql, $db_multisite);
		return mysql_fetch_assoc($result);
	}
	
	/**
	 * Set enable/disable flag
	 */
	private function set_status($site_url, $enable) {
		global $db_multisite, $addslashes;
		
		$enable = intval($enable);
		
		$sql = "UPDATE " . TABLE_PREFIX_MULTISITE . "subsites SET enabled = '" . $enable . "' WHERE site_url = '" . $addslashes($site_url) . "'";
		return mysql_query($sql, $db_multisite);
	}
	
	/**
	 * Remove subsite from table "subsites"
	 */
	private function remove_table_entry($site_url) {
		global $db_multisite, $addslashes, $msg;
		
		$sql = "DELETE FROM " . TABLE_PREFIX_MULTISITE . "subsites WHERE site_url = '" . $addslashes($site_url) . "'";
		if (!mysql_query($sql, $db_multisite)) {
			$msg->addError('CANNOT_REMOVE_TABLE_ENTRY');
			return false;
		}
		return true;
	}
	
	/**
	 * Parse config file
	 * @param config file: the location of the config file
	 * @return an array of the database-related config information. Return false if an error occurred.
	 */
	private function parse_config_file($config_file) {
		if (!file_exists($config_file)) {
			$msg->addError(array('CONFIG_FILE_NOT_EXIST', $config_file));
			return false;
		}
		
		return parse_config_file($config_file);
	}
	
	/**
	 * Validate all the input parameters for the site creation
	 */
	private function validate_input($site_name, $site_display_name, $admin_email, 
	                 $instructor_username, $instructor_fname, $instructor_lname, $instructor_email) {
		global $msg;
		
		$missing_fields = array();
		
		if (site_name == '') {
			$missing_fields[] = _AT('site_url');
		} else if (strlen($site_name) > 20 || !(preg_match("/^[a-zA-Z0-9]([a-zA-Z0-9_-])*$/i", $site_name))) {
			$msg->addError(array('BAD_NAME', _AT('site_url')));
		}
		
		if ($site_display_name == '') {
			$missing_fields[] = _AT('site_name');
		}
		
		if ($admin_email == '') {
			$missing_fields[] = _AT('site_admin_email');
		} else if (!preg_match("/^[a-z0-9\._-]+@+[a-z0-9\._-]+\.+[a-z]{2,6}$/i", $admin_email)) {
			$msg->addError(array('CERTAIN_EMAIL_INVALID', _AT('site_admin_email') . ' ' . $admin_email));
		}
		
		if ($admin_email == '') {
			$missing_fields[] = _AT('site_admin_email');
		}

		if (site_display_name == '') {
			$missing_fields[] = _AT('site_name');
		} else if (strlen($site_name) > 20 || !(preg_match("/^[a-zA-Z0-9]([a-zA-Z0-9_-])*$/i", $site_name))) {
			$msg->addError(array('BAD_NAME', _AT('site_name')));
		}
		
		if ($instructor_username == '') {
			$missing_fields[] = _AT('username');
		}

		if ($instructor_fname == '') {
			$missing_fields[] = _AT('first_name');
		}
		
		if ($instructor_lname == '') {
			$missing_fields[] = _AT('last_name');
		}
		
		if ($instructor_email == '') {
			$missing_fields[] = _AT('instructor_email');
		} else if (!preg_match("/^[a-z0-9\._-]+@+[a-z0-9\._-]+\.+[a-z]{2,6}$/i", $instructor_email)) {
			$msg->addError(array('CERTAIN_EMAIL_INVALID', _AT('instructor_email') . ' ' . $instructor_email));
		}
		
		if ($missing_fields) {
			$missing_fields = implode(', ', $missing_fields);
			$msg->addError(array('EMPTY_FIELDS', $missing_fields));
		}
			
		return $msg->containsErrors() ? false : true;
	}
	
	/**
	 * switch to use the multisite management database
	 */
	private function switch_subsite_manage_db() {
		global $db_multisite;
		
		mysql_select_db(DB_NAME_MULTISITE, $db_multisite);
	}
	
	/**
	 * Prepare class vars and database for the subsite creation
	 */
	private function prepare_creation() {
		global $db_multisite, $db;
		
		// The selected db is still the ATutor main db at this point
		$sql = "SELECT value FROM " . TABLE_PREFIX . "config WHERE name='session_path'";
		$result = mysql_query($sql, $db);
		$row = mysql_fetch_assoc($result);
		$this->session_path = $row['value'];
		
		$sql = "SELECT value FROM " . TABLE_PREFIX . "config WHERE name='contact_email'";
		$result = mysql_query($sql, $db);
		$row = mysql_fetch_assoc($result);
		$this->main_site_contact_email = $row['value'];
		
		$this->switch_subsite_manage_db();
	}
	
	/**
	 * Create the phisical subsite directories and config file
	 */
	private function create_subsite_dir($subsite_full_path) {
		global $msg;
		global $db_multisite;
		
		// Create the phisical directory
		$shell_output = shell_exec($this->make_multi_script . " " . $subsite_full_path);
		
		if (!is_dir($subsite_full_path)) {
			$msg->addError(array('SHELL_PERMISSION', $this->make_multi_script, $subsite_full_path, $shell_output));
			return false;
		}
		return true;
	}
	
	/**
	 * Find out if the given mysql account already exists
	 * @param $account_name
	 * @return true/false
	 */
	private function is_mysql_account_unique($account_name) {
		global $db_multisite;
		
		$sql = "select user from mysql.user where user='" . $account_name . "'";
		$result = mysql_query($sql, $db_multisite);
		
		return mysql_num_rows($result) == 0 ? true : false;
	}
	
	/**
	 * Return a unique mysql user name
	 */
	private function get_unique_mysql_account($account_prefix) {
		if ($this->is_mysql_account_unique($account_prefix)) {
			return $account_prefix;
		} else {
			while (true) {
				$account_name = $this->get_suffixed_string($account_prefix);
				if ($this->is_mysql_account_unique($account_name)) {
					return $account_name;
				}
			}
		}
	}
	
	/**
	 * Create mysql user and grant full permission on subsite database
	 */
	private function create_mysql_user($db_host, $mysql_account, $subsite_db_name, $super_mysql_acccount) {
		global $db_multisite, $msg;
		
		$mysql_pwd = $this->get_random_string(10);
		
		$sql = "CREATE USER '" . $mysql_account . "'@'" . $db_host . "' IDENTIFIED BY '" . $mysql_pwd . "'";
		if (!mysql_query($sql, $db_multisite)) {
			$msg->addError(array('CREATE_MYSQL_ACCT_FAILED', $mysql_account, mysql_error($db_multisite), $super_mysql_acccount));
			return false;
		}
		
		$sql = "GRANT SELECT,INSERT,UPDATE,DELETE,CREATE,DROP ON " . $subsite_db_name . 
		       ".* TO '" . $mysql_account . "'@'" . $db_host . "'";
		if (!mysql_query($sql, $db_multisite)) {
			$msg->addError(array('GRANT_PRIV_FAILED', $super_mysql_acccount));
			return false;
		}
		
		return $mysql_pwd;
	}
	
	/**
	 * Drop database
	 */
	private function drop_db($db, $mysql_super_account) {
		global $db_multisite, $msg;
		
		$sql = "DROP DATABASE " . $db;
		if (!mysql_query($sql, $db_multisite)) {
			$msg->addError(array('DROP_DB_FAILED', $db, mysql_error($db_multisite), $mysql_super_account));
			return false;
		}
		
		return true;
	}
	
	/**
	 * Drop mysql user account
	 */
	private function drop_mysql_user($db_host, $mysql_account, $mysql_super_account) {
		global $db_multisite, $msg;
		
		$sql = "DROP USER '" . $mysql_account . "'@'" . $db_host . "'";
		
		if (!mysql_query($sql, $db_multisite)) {
			$msg->addError(array('DROP_MYSQL_ACCT_FAILED', $mysql_account, mysql_error($db_multisite), $mysql_super_account));
			return false;
		}
		
		return true;
	}
	
	/**
	 * Update table "subsites"
	 * @param site_url
	 * @param @enabled
	 * @return true/false
	 */
	private function update_table($site_url, $enabled) {
		global $db_multisite, $msg;
		
		// insert the new site into db
		$sql = "INSERT INTO " . TABLE_PREFIX_MULTISITE . "subsites(site_url, enabled) VALUES('" .$site_url ."', '" . $enabled ."')";
		
		if(mysql_query($sql, $db_multisite)){
			return true;
		} else{
			$msg->addError(array('UPDATE_DB_FAILED', mysql_error()));
			return false;
		}
	}
	/**
	 * Return the full URL based on the given site name
	 * @param string $site_name
	 * @return string site URL
	 * @see module.php for the definition of MM_COMMON_DOMAIN
	 */
	private function get_site_url($site_name) {
		return $site_name. '.' . MM_COMMON_DOMAIN;
	}
	
	/**
	 * Return a string that is suffixed with a fixed-length of the random integer string.
	 */
	private function get_suffixed_string($prefix, $digits = 4) {
		return $prefix . str_pad(rand(0, pow(10, $digits)-1), $digits, '0', STR_PAD_LEFT);
	}
	
	/**
	 * Return a random string with certain length.
	 * The random string only contains the charactors from the provided charset.
	 */
	private function get_random_string($length, $charset='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789'){
		$str = '';
		$count = strlen($charset);
		while ($length--) {
			$str .= $charset[mt_rand(0, $count-1)];
		}
		
		return $str;
	}
	
	/**
	 * Check if the site url is unique
	 * Make sure the uniqueness in database and the phisical directory
	 * @param string  $site_url
	 * @return true or false
	 */
	private function is_site_unique($site_url) {
		global $db_multisite;
		
		$sql = "SELECT * FROM " . TABLE_PREFIX_MULTISITE. "subsites WHERE site_url='" . $site_url . "'";
		$result = mysql_query($sql, $db_multisite);
		
		return (mysql_num_rows($result) == 0 && !is_dir($this->subsite_main_dir . $site_url)) ? true : false;
	}
	
	/**
	 * Return the requested number of unique site urls based on the given site name.
	 * For instance, if users' desired site name is "hello" but it has been used, return
	 * a number of other not-in-use site names that start with "hello", for instance, "hello1" etc. 
	 * @param string   $desire_site_name
	 * @param integer  $num_of_sites
	 * @return an array of suggested site urls: "hello1.atutor.com, hello2.atutor.com"
	 */
	function get_unique_site_urls($desired_site_name, $num_of_sites = 3) {
		$count = 0;
		
		while (true) {
			if ($count == $num_of_sites) {
				break;
			}
			
			// generate a randomized site name with the prefix of the desired site name.
			$new_site_url = $this->get_site_url($this->get_suffixed_string($desired_site_name));
			
			if ($this->is_site_unique($new_site_url)) {
				$count++;
				$sites[] = $new_site_url;
			}
		}
		
		return $sites;
	}

	/**
	 * Return a unique database name
	 */
	private function get_unique_db_name($db_prefix, $db_host, $db_port, $db_login, $db_pwd) {
		global $db_multisite;
		
		if (!mysql_select_db($db_prefix, $db_multisite)) {
			return $db_prefix;
		} else {
			while (true) {
				$db_name = $this->get_suffixed_string($db_prefix);
				if (!mysql_select_db($db_name, $db_multisite)) {
					return $db_name;
				}
			}
		}
	}
	
	/**
	 * send email
	 */
	private function send_email($from_email, $to_email, $full_subsite_url, $admin_username, $admin_pwd, $instructor_username, $instructor_pwd) {
		global $msg;
		
		require(AT_INCLUDE_PATH . 'classes/phpmailer/atutormailer.class.php');
		
		$mail = new ATutorMailer();

		$mail->From     = $from_email;
		$mail->AddAddress($to_email);
		$mail->Subject = SITE_NAME . ': ' . _AT('email_confirmation_subject');
		$mail->Body    = _AT('email_confirmation_subsite_msg', $full_subsite_url, $full_subsite_url, $admin_username, $admin_pwd, $instructor_username, $instructor_pwd)."\n\n";
		$mail->Send();

		$msg->addFeedback('CONFIRMATION_SENT');
	}
	/**
	 * switch back to ATutor main database
	 */
	private function finalize(){
		global $db;
		
		// switch back to the ATutor main database
		mysql_select_db(DB_NAME, $db);
	}
	
}

?>
<?php
/*******
 * doesn't allow this file to be loaded with a browser.
 */
if (!defined('AT_INCLUDE_PATH')) { exit; }

/******
 * this file must only be included within a Module obj
 */
if (!isset($this) || (isset($this) && (strtolower(get_class($this)) != 'module'))) { exit(__FILE__ . ' is not a Module'); }

/*******
 * assign the instructor and admin privileges to the constants.
 */
define('AT_PRIV_MANAGE_MULTI',       $this->getPrivilege());
define('AT_ADMIN_PRIV_MANAGE_MULTI', $this->getAdminPrivilege());


if (admin_authenticate(AT_ADMIN_PRIV_MANAGE_MULTI, TRUE) || admin_authenticate(AT_ADMIN_PRIV_ADMIN, TRUE)) {
	$this->_pages[AT_NAV_ADMIN] = array('mods/manage_multi/index_admin.php');
	$this->_pages['mods/manage_multi/index_admin.php']['title_var'] = 'manage_multi';
	$this->_pages['mods/manage_multi/index_admin.php']['parent']    = AT_NAV_ADMIN;
	
	$this->_pages['mods/manage_multi/create_subsite.php']['title_var'] = 'create_subsite';
	$this->_pages['mods/manage_multi/create_subsite.php']['parent']    = 'mods/manage_multi/index_admin.php';
	$this->_pages['mods/manage_multi/config_multi.php']['title_var'] = 'config_multi';
	$this->_pages['mods/manage_multi/config_multi.php']['parent']    = 'mods/manage_multi/index_admin.php';
	$this->_pages['mods/manage_multi/index_admin.php']['children']    = array('mods/manage_multi/config_multi.php', 'mods/manage_multi/create_subsite.php');
	
	define('MM_COMMON_DOMAIN', implode('.', array_splice(explode('.', $_SERVER['HTTP_HOST']), 1)));
}

?>
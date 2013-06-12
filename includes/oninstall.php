<?php

function xoops_module_install_uhq_iceauth(&$module) {

	global $xoopsDB;
	
	// Set anonymous access to the module

	$sql = "INSERT IGNORE INTO " . $xoopsDB->prefix('group_permission') . " VALUES (null, '" . XOOPS_GROUP_ANONYMOUS . "', '" . $module->getVar('mid') . "', 1, 'module_read')";
	if ( $xoopsDB->queryF($sql) ) {
		echo _MI_UHQICEAUTH_INSTALL_GOOD;
	}	else {
	  echo _MI_UHQICEAUTH_INSTALL_FAULT;
	}
	
	// Set Main Menu to visibility of 0
	
	$sql = "UPDATE " . $xoopsDB->prefix('modules') . " SET weight = 0 WHERE dirname = 'uhq_iceauth'";
	if ( $xoopsDB->queryF($sql) ) {
		echo _MI_UHQICEAUTH_WEIGHT_OK;
	} else {
		echo _MI_UHQICEAUTH_WEIGHT_NOK;
	}
	
  return true;	
}

?>
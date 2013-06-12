<?php

/*
UHQ-IceAuth :: XOOPS Module for IceCast Authentication
Copyright (C) 2008-2011 :: Ian A. Underwood :: xoops@underwood-hq.org

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License along
with this program; if not, write to the Free Software Foundation, Inc.,
51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
*/

include "../../../mainfile.php";

require_once XOOPS_ROOT_PATH . "/include/cp_header.php";
require_once XOOPS_ROOT_PATH . "/modules/uhq_iceauth/admin/functions.inc.php";

// Load frameworks
require_once XOOPS_ROOT_PATH."/Frameworks/art/functions.php";
require_once XOOPS_ROOT_PATH."/Frameworks/art/functions.admin.php";

// Load Template Engine & Disable Cachine
require_once XOOPS_ROOT_PATH . '/class/template.php';
if (!isset($xoopsTpl)) {
	$xoopsTpl = new XoopsTpl();
}
$xoopsTpl->xoops_setCaching(0);

// Grab operator if we have it

if ( isset($_REQUEST['op']) ) {
	$op = $_REQUEST['op'];
} else {
	$op = "none";
}

// Force Anon again

function uhqiceauth_anoncheck() {
	global $xoopsDB;	

	$modhandler		=& xoops_gethandler('module');
	$module			=& $modhandler->getByDirname('uhq_iceauth');
	
	$query = "SELECT COUNT(*) FROM ".$xoopsDB->prefix('group_permission');
	$query .= " WHERE gperm_itemid = '".$module->getVar('mid')."'";
	$query .= " AND gperm_name = 'module_read'";
	$query .= " AND gperm_groupid = '".XOOPS_GROUP_ANONYMOUS."'";
	
	$result = $xoopsDB->queryF($query);
	if ($result == false) {
		$status = _AM_UHQICEAUTH_SQLERR.$query."<br/>".$xoopsDB->error();
	} else {
		list($anonok) = $xoopsDB->fetchRow($result);
	}
	
	if ($anonok) {
		$status = _AM_UHQICEAUTH_ANON_OK;
	} else {
		$sql = "INSERT IGNORE INTO " . $xoopsDB->prefix('group_permission') . " VALUES (null, '" . XOOPS_GROUP_ANONYMOUS . "', '" . $module->getVar('mid') . "', 1, 'module_read')";
		if ( $xoopsDB->queryF($sql) ) {
			$status = _AM_UHQICEAUTH_ANON_RESTORED;
		} else {
	  		$status = _AM_UHQICEAUTH_ANON_FAILED;
		}
	}
	return $status;
}

// Check MIME types for all supported formats

function uhqiceauth_mimecheck() {
	global $uhqiceauth_intro_mimes;
	
	$fn = XOOPS_ROOT_PATH . "/class/mimetypes.inc.php";
	$fh = fopen($fn,"r");
	if (!$fh) {
		$data['error'] = _AM_UHQICEAUTH_ERR_OPENMIME;
		return false;
	}
	$mimefile = fread ($fh, filesize($fn));
	fclose($fh);

	if (!$mimefile) {
		$data['error'] = _AM_UHQICEAUTH_ERR_READMIME;
		return false;
	}
	$i=0;
	
	foreach($uhqiceauth_intro_mimes as &$type) {
		$data['list'][$i]['type'] = $type;
		if (strpos($mimefile,$type) ) {
			$data['list'][$i]['status'] = _AM_UHQICEAUTH_CODEC_FOUND;
		} else {
			$data['list'][$i]['status'] = _AM_UHQICEAUTH_CODEC_NFOUND;
		}
		$i++;
	}
	unset($type);
	
	return $data;
}

xoops_cp_header();

echo function_exists("loadModuleAdminMenu") ? loadModuleAdminMenu(0) : adminMenu(0);

// Go where we need to.

switch ($op) {
	case "none":
	default:
		// Summary Data
		$data['anon'] = uhqiceauth_anoncheck();
		$data['mod_geo'] = uhqiceauth_geocheck();
		$data['mime'] = uhqiceauth_mimecheck();
		
		// DB Counters
		$data['mpcount'] = uhqiceauth_summarycount("MP");	// Mount Point Summary
		$data['incount'] = uhqiceauth_summarycount("IN");	// Intro Summary
		$data['aucount'] = uhqiceauth_summarycount("AU");	// Authentication Summary
		$data['mlcount'] = uhqiceauth_summarycount("ML");	// Mount Log Summary
		$data['amcount'] = uhqiceauth_summarycount("AM");	// Active Mountpoints
		$data['spcount'] = uhqiceauth_summarycount("SP");	// Stream Login Summary
		$data['uacount'] = uhqiceauth_summarycount("UA");	// UA Bans
		$data['ipcount'] = uhqiceauth_summarycount("IP");	// IP Address Bans
		
		// Assign & Render Template
		$xoopsTpl->assign('data',$data);
		$xoopsTpl->display("db:admin/uhqiceauth_index.html");
		
		break;
}

xoops_cp_footer();

?>
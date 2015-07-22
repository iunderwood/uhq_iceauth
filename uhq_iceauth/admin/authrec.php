<?php

/*
UHQ-IceAuth :: XOOPS Module for IceCast Authentication
Copyright (C) 2008-2013 :: Ian A. Underwood :: xoops@underwood-hq.org

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

include_once __DIR__ . '/admin_header.php';

if (!isset($xoopsTpl)) {
	$xoopsTpl = new XoopsTpl();
}
$xoopsTpl->caching=0;

include XOOPS_ROOT_PATH . "/modules/uhq_iceauth/includes/sanity.php";
include XOOPS_ROOT_PATH . "/modules/uhq_iceauth/admin/functions.inc.php";
include XOOPS_ROOT_PATH . "/modules/uhq_iceauth/includes/auth.inc.php";

function uhqiceauth_authlist($authtype, $start, $limit, $orderby) {
	global $xoopsDB;
	$data = array();

	$query = "SELECT * FROM ".$xoopsDB->prefix('uhqiceauth_authtrail')." WHERE ";
	$query .= " authtype = '".$authtype."' ORDER BY sequence ".$orderby." LIMIT ".$start.", ".$limit;

	$result = $xoopsDB->queryF($query);

	if ($result == false) {
		$data['error'] = _AM_UHQICEAUTH_SQLERR.$query;
		return $data;
	} else {
		$data['order'] = $orderby;
		$data['limit'] = $limit;
		$data['type'] = $authtype;

		$i=0;
		while ($row = $xoopsDB->fetchArray($result) ) {
			$data['record'][$i] = $row;
			$data['record'][$i]['flag'] = strtolower($row['geocc']);
			if (file_exists(XOOPS_ROOT_PATH.'/modules/uhq_geolocate/includes/countryshort.php')) {
				include_once XOOPS_ROOT_PATH.'/modules/uhq_geolocate/includes/countryshort.php';
				if ($row['geocc'])
					$data['record'][$i]['ccname'] = $_UHQGEO_CC[$row['geocc']];
			}
			$i++;
		}
		$data['count'] = $i;
		return $data;
	}
}

function uhqiceauth_authrecord($sequence) {
	global $xoopsDB;
	$data = array();

	if ($sequence) {
		$query = "SELECT * FROM ".$xoopsDB->prefix('uhqiceauth_authtrail')." WHERE sequence = '".$sequence."'";
		$result = $xoopsDB->queryF($query);

		if ($result == false) {
			$data['error'] = _AM_UHQICEAUTH_SQLERR.$query;
		} else {
			if ($row = $xoopsDB->fetchArray($result) ) {
				$data = $row;
				if (uhqiceauth_checkrdns()) {
					$data['checkdns'] = true;
					$data['currentrdns'] = gethostbyaddr($row['userip']);
				}
			} else {
				$data['error'] = "Record #".$sequence." Not Found";
			}
		}
	} else {
		$data['error'] = "Record # Required.";
	}
	return $data;
}

// Now the fun begins!

if ( isset($_REQUEST['op']) ) {
	$op = $_REQUEST['op'];
} else {
	$op = "none";
}

$sane_REQUEST = uhqiceauth_dosanity();

switch ($op) {
	case "deleteall" :
		// Verify we have minimum parameters.
		if ( $sane_REQUEST['server'] && $sane_REQUEST['port'] && $sane_REQUEST['mount'] ) {
			if ( isset ($_REQUEST['verify']) ) {
				// Set up query and remove all records.
				$query = '';
			} else {
				// Verify we really want to delete all records.
			}
		} else {
			redirect_header("authrec.php",10,_AM_UHQICEAUTH_PARAMERR);
		}
		break;
	case "deleterec" :
		// Delete a specific record.
		redirect_header("authrec.php",10,_AM_UHQICEAUTH_NOTIMPLEMENTED);
		break;
	case "authrecord" :
		xoops_cp_header();
		$mainAdmin = new ModuleAdmin();
		echo $mainAdmin->addNavigation('authrec.php');

		$data = array();

		$data = uhqiceauth_authrecord($sane_REQUEST['sequence']);

		$xoopsTpl->assign('data',$data);
		$xoopsTpl->display("db:admin/uhqiceauth_authrec_detail.html");
		include_once __DIR__ . '/admin_footer.php';

		break;
	case "none" :
	default:
		xoops_cp_header();

		$mainAdmin = new ModuleAdmin();
		echo $mainAdmin->addNavigation('authrec.php');

		$data = array();

		$data['aucount'] = uhqiceauth_summarycount("AU");	// Authentication Summary

		if ( $data['aucount'] ) {
			$data['rec'][0] = uhqiceauth_authlist("L",0,15,"DESC");
			$data['rec'][1] = uhqiceauth_authlist("S",0,5,"DESC");
			$data['rec'][2] = uhqiceauth_authlist("A",0,5,"DESC");
		}

		$data['usegeo'] = uhqiceauth_geocheck();

		$xoopsTpl->assign('data',$data);
		$xoopsTpl->display("db:admin/uhqiceauth_authrec.html");
		include_once __DIR__ . '/admin_footer.php';

		break;
}

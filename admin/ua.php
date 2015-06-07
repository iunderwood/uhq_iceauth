<?php

/*
UHQ-IceAuth :: XOOPS Module for IceCast Authentication
Copyright (C) 2008-2015 :: Ian A. Underwood :: xoops@underwood-hq.org

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

include_once dirname(__FILE__) . '/admin_header.php';

if (!isset($xoopsTpl)) {
	$xoopsTpl = new XoopsTpl();
}
$xoopsTpl->caching=0;

include XOOPS_ROOT_PATH . "/modules/uhq_iceauth/includes/sanity.php";
include XOOPS_ROOT_PATH . "/modules/uhq_iceauth/admin/functions.inc.php";
include XOOPS_ROOT_PATH . "/modules/uhq_iceauth/includes/auth.inc.php";

include XOOPS_ROOT_PATH . "/class/xoopsformloader.php";

// Assign default operator
if ( isset($_REQUEST['op']) ) {
	$op = $_REQUEST['op'];
} else {
	$op = "none";
}

$sane_REQUEST = uhqiceauth_dosanity();

function uhqiceauth_uaform($title) {
	$form = new XoopsThemeForm($title,'uaform','ua.php', 'POST');

	$form->addElement(new XoopsFormText(_AM_UHQICEAUTH_FORM_USERAGENT, "useragent",40,50) );

	$form->addElement(new XoopsFormHidden("op", "insert") );
	$form->addElement(new XoopsFormHidden("verify", "1") );

	$form->addElement(new XoopsFormButton("",'post',$title,'submit') );
	$form->display();

	echo "<br/><br/><a href='ua.php'>"._AM_UHQICEAUTH_RETUA."</a>";
}

function uhqiceauth_ualist() {
	global $xoopsDB;

	$query = "SELECT * FROM ".$xoopsDB->prefix('uhqiceauth_uabans')." ORDER BY useragent";

	$result = $xoopsDB->queryF($query);
	if ($result == false) {
		// Return nothing on a DB error.
        return null;
	} else {
		$i=0;
		$data = array();
		while ($row = $xoopsDB->fetchArray($result) ) {
			$data['list'][$i] = $row;
			$i++;
		}
		return $data;
	}
}

// Return the last 10 log entries where a UA ban has been observed
function uhqiceauth_uaauthbans($start,$limit=10) {
	global $xoopsDB;

	$query = "SELECT * FROM ".$xoopsDB->prefix('uhqiceauth_authlog');
	$query .=" WHERE authinfo = 301 ORDER BY logtime DESC LIMIT ".$limit;

	$result = $xoopsDB->queryF($query);
	if ($result == false) {
		// Return nothing on a DB error.
        return null;
	} else {
		$i=0;
		$data = array();
		while ($row = $xoopsDB->fetchArray($result) ) {
			$data['banlog'][$i] = $row;
			$i++;
		}
		return $data;
	}
}

switch ($op) {
	case "insert":
		if ( isset($_REQUEST['verify']) ) {
			$query = "INSERT INTO ".$xoopsDB->prefix('uhqiceauth_uabans');
			$query .= " SET useragent = '".$sane_REQUEST['useragent']."'";
			$result = $xoopsDB->queryF($query);

			if ($result == false) {
				redirect_header("ua.php",10,_AM_UHQICEAUTH_SQLERR.$query."<br/>".$xoopsDB->error() );
				break;
			} else {
				redirect_header("ua.php",10,_AM_UHQICEAUTH_ADDED.$sane_REQUEST['useragent']._AM_UHQICEAUTH_SUCCESSFULLY);
				break;
			}
		} else {
			xoops_cp_header();
			$mainAdmin = new ModuleAdmin();
			echo $mainAdmin->addNavigation('ua.php');
			uhqiceauth_uaform(_AM_UHQICEAUTH_ADDUA);
			include_once dirname(__FILE__) . '/admin_footer.php';
		}
		break;
	case "delete":
		// Verify we have minimum parameters
		if ( $sane_REQUEST['sequence']  ) {

			// Delete Record
			$query = "DELETE FROM ".$xoopsDB->prefix('uhqiceauth_uabans')." WHERE sequence = '".$sane_REQUEST['sequence']."'";
			$result = $xoopsDB->queryF($query);
			if ($result == false) {
				$headerinfo = _AM_UHQICEAUTH_SQLERR.$query."<br/>".$xoopsDB->error()."<br/>";
			} else {
				$headerinfo = _AM_UHQICEAUTH_DELETED.$sane_REQUEST['sequence']._AM_UHQICEAUTH_SUCCESSFULLY."<br/><br/>";
			}
			redirect_header("ua.php",10,$headerinfo);
		} else {
			redirect_header("ua.php",10,_AM_UHQICEAUTH_PARAMERR);
		}
		break;
	case "testua":
		$result = uhqiceauth_ua_verify($sane_REQUEST['testua']);
		if ($result) {
			redirect_header("ua.php",30,$sane_REQUEST['testua']._AM_UHQICEAUTH_UA_PASS);
		} else {
			redirect_header("ua.php",30,$sane_REQUEST['testua']._AM_UHQICEAUTH_UA_FAIL);
		}
		break;
	default:
		xoops_cp_header();
		$mainAdmin = new ModuleAdmin();
		echo $mainAdmin->addNavigation('ua.php');

		// See if we have anything first.
		$data['uacount'] = uhqiceauth_summarycount("UA");;
		if ( $data['uacount'] ) {
			$data['uadata'] = uhqiceauth_ualist();
		}

		// Assign & Render Template
		$xoopsTpl->assign('data',$data);
		$xoopsTpl->display("db:admin/uhqiceauth_ua.html");

		include_once dirname(__FILE__) . '/admin_footer.php';
		break;
}
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

// Adjust icon path depending on the XOOPS version we're using.

$versioninfo=array();
preg_match_all('/\d+/',XOOPS_VERSION,$versioninfo);
if ( ($versioninfo[0][0] >= 2) && ($versioninfo[0][1] >= 4) ) {
  $menuiconpath = "/";
} else {
  $menuiconpath = "../../../../uhq_iceauth/";
}

// Assign goodies for Admin Menu

$i=0;
$adminmenu[$i]['title'] = _MI_UHQICEAUTH_ADMENU_0;
$adminmenu[$i]['link'] = "admin/index.php";
$adminmenu[$i]['icon'] = $menuiconpath."images/menu_index.png";

$i++;	// 1
$adminmenu[$i]['title'] = _MI_UHQICEAUTH_ADMENU_MOUNTS;
$adminmenu[$i]['link'] = "admin/servers.php";
$adminmenu[$i]['icon'] = $menuiconpath."images/menu_srv.png";

$i++;	// 2
$adminmenu[$i]['title'] = _MI_UHQICEAUTH_ADMENU_INTRO;
$adminmenu[$i]['link'] = "admin/intros.php";
$adminmenu[$i]['icon'] = $menuiconpath."images/menu_intro.png";

$i++;	// 3
$adminmenu[$i]['title'] = _MI_UHQICEAUTH_ADMENU_AUTHREC;
$adminmenu[$i]['link'] = "admin/authrec.php";
$adminmenu[$i]['icon'] = $menuiconpath."images/menu_auth.png";

$i++;	// 4
$adminmenu[$i]['title'] = _MI_UHQICEAUTH_ADMENU_ACCTREC;
$adminmenu[$i]['link'] = "admin/acctrec.php";
$adminmenu[$i]['icon'] = $menuiconpath."images/menu_acct.png";

$i++;	// 5
$adminmenu[$i]['title'] = _MI_UHQICEAUTH_ADMENU_MOUNTREC;
$adminmenu[$i]['link'] = "admin/mountrec.php";
$adminmenu[$i]['icon'] = $menuiconpath."images/menu_mount.png";

$i++;	// 6
$adminmenu[$i]['title'] = _MI_UHQICEAUTH_ADMENU_STREAMPASS;
$adminmenu[$i]['link'] = "admin/streampass.php";
$adminmenu[$i]['icon'] = $menuiconpath."images/menu_streampass.png";

$i++;	// 7
$adminmenu[$i]['title'] = _MI_UHQICEAUTH_ADMENU_UA;
$adminmenu[$i]['link'] = "admin/ua.php";
$adminmenu[$i]['icon'] = $menuiconpath."images/menu_ua.png";

$i++;	// 8
$adminmenu[$i]['title'] = _MI_UHQICEAUTH_ADMENU_IPBAN;
$adminmenu[$i]['link'] = "admin/ipbans.php";
$adminmenu[$i]['icon'] = $menuiconpath."images/menu_ipban.png";

?>
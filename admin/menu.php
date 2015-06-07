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

defined("XOOPS_ROOT_PATH") or die("XOOPS root path not defined");

$path = dirname(dirname(dirname(dirname(__FILE__))));
include_once $path . '/mainfile.php';

$dirname         = basename(dirname(dirname(__FILE__)));
$module_handler  = xoops_gethandler('module');
$module          = $module_handler->getByDirname($dirname);
$pathIcon32      = $module->getInfo('icons32');
$pathModuleAdmin = $module->getInfo('dirmoduleadmin');
$pathLanguage    = $path . $pathModuleAdmin;


if (!file_exists($fileinc = $pathLanguage . '/language/' . $GLOBALS['xoopsConfig']['language'] . '/' . 'main.php')) {
    $fileinc = $pathLanguage . '/language/english/main.php';
}

include_once $fileinc;

// Assign goodies for Admin Menu

global $adminmenu;

// Assign goodies for Admin Menu

$i=0;
$adminmenu[$i]["title"]	= _AM_MODULEADMIN_HOME;
$adminmenu[$i]['link']	= "admin/index.php";
$adminmenu[$i]["icon"] 	= '../../Frameworks/moduleclasses/icons/32/home.png';

$i++;
$adminmenu[$i]['title'] = _MI_UHQICEAUTH_ADMENU_SUMMARY;
$adminmenu[$i]['link']	= "admin/summary.php";
$adminmenu[$i]['icon']	= $pathIcon32."folder_blue.png";

$i++;	// 1
$adminmenu[$i]['title'] = _MI_UHQICEAUTH_ADMENU_MOUNTS;
$adminmenu[$i]['link']	= "admin/mountpoints.php";
$adminmenu[$i]['icon']	= $pathIcon32."menu_srv.png";

$i++;	// 2
$adminmenu[$i]['title'] = _MI_UHQICEAUTH_ADMENU_INTRO;
$adminmenu[$i]['link'] = "admin/intros.php";
$adminmenu[$i]['icon'] = $pathIcon32."menu_intro.png";

$i++;	// 3
$adminmenu[$i]['title'] = _MI_UHQICEAUTH_ADMENU_AUTHREC;
$adminmenu[$i]['link'] = "admin/authrec.php";
$adminmenu[$i]['icon'] = $pathIcon32."menu_auth.png";

$i++;	// 4
$adminmenu[$i]['title'] = _MI_UHQICEAUTH_ADMENU_ACCTREC;
$adminmenu[$i]['link'] = "admin/acctrec.php";
$adminmenu[$i]['icon'] = $pathIcon32."menu_acct.png";

$i++;	// 5
$adminmenu[$i]['title'] = _MI_UHQICEAUTH_ADMENU_MOUNTREC;
$adminmenu[$i]['link'] = "admin/mountrec.php";
$adminmenu[$i]['icon'] = $pathIcon32."menu_mount.png";

$i++;	// 6
$adminmenu[$i]['title'] = _MI_UHQICEAUTH_ADMENU_STREAMPASS;
$adminmenu[$i]['link'] = "admin/streampass.php";
$adminmenu[$i]['icon'] = $pathIcon32."menu_streampass.png";

$i++;	// 7
$adminmenu[$i]['title'] = _MI_UHQICEAUTH_ADMENU_UA;
$adminmenu[$i]['link'] = "admin/ua.php";
$adminmenu[$i]['icon'] = $pathIcon32."menu_ua.png";

$i++;	// 8
$adminmenu[$i]['title'] = _MI_UHQICEAUTH_ADMENU_IPBAN;
$adminmenu[$i]['link'] = "admin/ipbans.php";
$adminmenu[$i]['icon'] = $pathIcon32."menu_ipban.png";

// Admin About Page

$i++;
$adminmenu[$i]['title'] = _AM_MODULEADMIN_ABOUT;
$adminmenu[$i]["link"]  = "admin/about.php";
$adminmenu[$i]["icon"]  = '../../Frameworks/moduleclasses/icons/32/about.png';
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
include XOOPS_ROOT_PATH . "/class/xoopsformloader.php";

xoops_cp_header();
$mainAdmin = new ModuleAdmin();
echo $mainAdmin->addNavigation('ipbans.php');

echo "Nothing here ... yet.";

include_once dirname(__FILE__) . '/admin_footer.php';
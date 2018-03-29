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

// Admin page setup.

require_once __DIR__ . '/admin_header.php';

if (!isset($xoopsTpl)) {
    $xoopsTpl = new \XoopsTpl();
}
$xoopsTpl->caching = 0;

// Start Page Display

xoops_cp_header();
$adminObject = \Xmf\Module\Admin::getInstance();
$adminObject->displayNavigation(basename(__FILE__));

// Load module functions

require_once __DIR__ . '/functions.inc.php';

// Summary Data
$data['anon']    = uhqiceauth_anoncheck();
$data['mod_geo'] = uhqiceauth_geocheck();
$data['mime']    = uhqiceauth_mimecheck();

// DB Counters
$data['mpcount'] = uhqiceauth_summarycount('MP');   // Mount Point Summary
$data['incount'] = uhqiceauth_summarycount('IN');   // Intro Summary
$data['aucount'] = uhqiceauth_summarycount('AU');   // Authentication Summary
$data['mlcount'] = uhqiceauth_summarycount('ML');   // Mount Log Summary
$data['amcount'] = uhqiceauth_summarycount('AM');   // Active Mountpoints
$data['spcount'] = uhqiceauth_summarycount('SP');   // Stream Login Summary
$data['uacount'] = uhqiceauth_summarycount('UA');   // UA Bans
$data['ipcount'] = uhqiceauth_summarycount('IP');   // IP Address Bans

// Assign & Render Template
$xoopsTpl->assign('data', $data);
$xoopsTpl->display('db:admin/uhqiceauth_index.tpl');

require_once __DIR__ . '/admin_footer.php';

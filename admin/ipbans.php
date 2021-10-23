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

use Xmf\Module\Admin;
use XoopsModules\Uhqiceauth\{
    Helper
};
/** @var Admin $adminObject */
/** @var Helper $helper */

require_once __DIR__ . '/admin_header.php';

$helper      = Helper::getInstance();

if (!isset($xoopsTpl)) {
    $xoopsTpl = new \XoopsTpl();
}
$xoopsTpl->caching = 0;

require_once $helper->path('includes/sanity.php');
require_once $helper->path('admin/functions.inc.php');
require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';

xoops_cp_header();
$adminObject = Admin::getInstance();
$adminObject->displayNavigation(basename(__FILE__));

echo 'Nothing here ... yet.';

require_once __DIR__ . '/admin_footer.php';

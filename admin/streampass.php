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
use Xmf\Request;
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
require_once $helper->path('includes/functions.php');

// Admin Requirements
require_once $helper->path('admin/functions.inc.php');

// Assign default operator

if (Request::hasVar('op', 'REQUEST')) {
    $op = $_REQUEST['op'];
} else {
    $op = 'none';
}

$saneREQ = uhqiceauth_dosanity();

// Do our stuff.

switch ($op) {
    case 'del':
        if (isset($saneREQ['user'])) {
            // Check DB for entry

            $query = 'SELECT * FROM ' . $xoopsDB->prefix('uhqiceauth_streampass');
            $query .= " WHERE un = '" . utf8_encode($saneREQ['user']) . "'";

            $result = $xoopsDB->queryF($query);
            if (false === $result) {
                redirect_header('streampass.php', 10, _AM_UHQICEAUTH_SQLERR . $query . '<br>' . $xoopsDB->error());
            } else {
                $row = $xoopsDB->fetchArray($result);
                if ($row) {
                    $query  = 'DELETE FROM ' . $xoopsDB->prefix('uhqiceauth_streampass') . ' WHERE';
                    $query  .= " un = '" . utf8_encode($saneREQ['user']) . "'";
                    $result = $xoopsDB->queryF($query);

                    if (false === $result) {
                        redirect_header('streampass.php', 10, _AM_UHQICEAUTH_SQLERR . $query . '<br>' . $xoopsDB->error());
                    } else {
                        redirect_header('streampass.php', 10, _AM_UHQICEAUTH_SP_DELOK . $saneREQ['user']);
                    }
                } else {
                    redirect_header('streampass.php', 10, _AM_UHQICEAUTH_SP_NOPW);
                }
            }
            break;
        }
        redirect_header('streampass.php', 10, _AM_UHQICEAUTH_PARAMERR);

        break;
    case 'none':
    default:
        // Header
        xoops_cp_header();
        $adminObject = Admin::getInstance();
        $adminObject->displayNavigation(basename(__FILE__));

        // Stream Login Infos
        $data['spcount'] = uhqiceauth_summarycount('SP');
        if ($data['spcount'] > 0) {
            $data['spdata'] = uhqiceauth_raw_streampass();
        }

        // Assign & Render Template
        $xoopsTpl->assign('data', $data);
        $xoopsTpl->display('db:admin/uhqiceauth_streampass.tpl');

        // Footer
        require_once __DIR__ . '/admin_footer.php';
}

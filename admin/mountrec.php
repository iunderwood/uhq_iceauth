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

// Load Admin Includes

require_once __DIR__ . '/functions.inc.php';

require_once XOOPS_ROOT_PATH . '/modules/uhq_iceauth/includes/sanity.php';
require_once XOOPS_ROOT_PATH . '/modules/uhq_iceauth/includes/functions.php';

// Assign default operator

if (isset($_REQUEST['op'])) {
    $op = $_REQUEST['op'];
} else {
    $op = 'none';
}

$sane_REQUEST = uhqiceauth_dosanity();

function uhqiceauth_mount($start, $limit, $orderby)
{
    global $xoopsDB;

    $query = 'SELECT * FROM ' . $xoopsDB->prefix('uhqiceauth_mountlog');
    $query .= ' ORDER BY sequence ' . $orderby . ' LIMIT ' . $start . ', ' . $limit;

    $result = $xoopsDB->queryF($query);
    if (false === $result) {
        // Return nothing on a DB error.
        return null;
    } else {
        $i    = 0;
        $data = [];

        $data['start'] = $start;
        $data['limit'] = $limit;
        $data['sort']  = $orderby;

        while (false !== ($row = $xoopsDB->fetchArray($result))) {
            $data['list'][$i] = $row;
            $i++;
        }

        return $data;
    }
}

switch ($op) {
    case 'clearmount':
        if (isset($sane_REQUEST['server']) && isset($sane_REQUEST['port']) && isset($sane_REQUEST['mount'])) {
            global $xoopsDB;

            $query = 'DELETE FROM ' . $xoopsDB->prefix('uhqiceauth_activemounts');
            $query .= " WHERE server='" . $sane_REQUEST['server'] . "'";
            $query .= " AND port='" . $sane_REQUEST['port'] . "'";
            $query .= " AND mount='" . $sane_REQUEST['mount'] . "'";

            $result = $xoopsDB->queryF($query);
            if (false === $result) {
                $headerinfo = _AM_UHQICEAUTH_SQLERR . $query . '<br>' . $xoopsDB->error();
            } else {
                $headerinfo = _AM_UHQICEAUTH_DELETEDMOUNT . $sane_REQUEST['server'] . ':' . $sane_REQUEST['port'] . $sane_REQUEST['mount'] . _AM_UHQICEAUTH_SUCCESSFULLY;
            }
        } else {
            $headerinfo = _AM_UHQICEAUTH_PARAMERR;
        }

        // Redirect to main page when delete is done.

        redirect_header('mountrec.php', 10, $headerinfo);
        break;

    case 'none':
    default:
        xoops_cp_header();
        $adminObject = \Xmf\Module\Admin::getInstance();
        $adminObject->displayNavigation(basename(__FILE__));

        // Mountpoint Record Info
        $data['mlcount'] = uhqiceauth_summarycount('ML');
        if ($data['mlcount'] > 0) {
            $data['mldata'] = uhqiceauth_mount(0, 10, 'DESC');
        }

        // Active Mountpoint infos
        $data['amcount'] = uhqiceauth_summarycount('AM');
        if ($data['amcount'] > 0) {
            $data['amdata'] = uhqiceauth_raw_activemounts();
        }

        // Assign & Render Template
        $xoopsTpl->assign('data', $data);
        $xoopsTpl->display('db:admin/uhqiceauth_mountrec.tpl');

        require_once __DIR__ . '/admin_footer.php';
}

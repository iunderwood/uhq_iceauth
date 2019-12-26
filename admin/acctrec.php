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

require_once __DIR__ . '/admin_header.php';

if (!isset($xoopsTpl)) {
    $xoopsTpl = new XoopsTpl();
}
$xoopsTpl->caching = 0;

// Load required includes

require_once XOOPS_ROOT_PATH . '/modules/uhq_iceauth/includes/sanity.php';
require_once __DIR__ . '/functions.inc.php';

// Now the fun begins!

if (isset($_REQUEST['op'])) {
    $op = $_REQUEST['op'];
} else {
    $op = 'none';
}

$sane_REQUEST = uhqiceauth_dosanity();

function uhqiceauth_acct_agentsumconn($limit = 10, $days = 0)
{
    global $xoopsDB;

    $data = [];

    // Summary List
    $query = 'SELECT useragent, COUNT(useragent) AS total FROM ' . $xoopsDB->prefix('uhqiceauth_authtrail');
    $query .= " WHERE authtype = 'L' ";
    if ($days) {
        $query .= ' AND logtime > SUBDATE( NOW(), INTERVAL ' . $days . ' DAY)';
    }
    $query .= ' GROUP BY useragent ORDER BY total DESC';
    if ($limit) {
        $query .= ' LIMIT ' . $limit;
    }
    $result = $xoopsDB->queryF($query);

    if (!$result) {
        $data['error'] = _AM_UHQICEAUTH_SQLERR . $query . ' [' . $xoopsDB->error() . ']';

        return $data;
    }

    $data['limit'] = $limit;
    $data['days']  = $days;

    $i = 0;
    while ($row = $xoopsDB->fetchArray($result)) {
        $data['ua'][$i] = $row;
        $i++;
    }

    return $data;
}

function uhqiceauth_acct_agentsumtime($limit = 10, $days = 0)
{
    global $xoopsDB;

    // Summary List
    $query = 'SELECT useragent, SUM(duration) AS duration FROM ' . $xoopsDB->prefix('uhqiceauth_authtrail');
    $query .= " WHERE authtype = 'L' AND duration > 0 ";
    if ($days) {
        $query .= ' AND logtime > SUBDATE( NOW(), INTERVAL ' . $days . ' DAY)';
    }
    $query .= ' GROUP BY useragent ORDER BY duration DESC';
    if ($limit) {
        $query .= ' LIMIT ' . $limit;
    }

    $result = $xoopsDB->queryF($query);

    if (!$result) {
        $data['error'] = _AM_UHQICEAUTH_SQLERR . $query . ' [' . $xoopDB->error() . ']';

        return null;
    }

    $data['limit'] = $limit;
    $data['days']  = $days;

    $i = 0;

    while ($row = $xoopsDB->fetchArray($result)) {
        $data['ua'][$i]         = $row;
        $data['ua'][$i]['time'] = uhqiceauth_time($row['duration']);
        $i++;
    }

    return $data;
}

// Return connections, ttsl, and average connect time in a specific interval.

function uhqiceauth_ttsl($interval = null)
{
    global $xoopsDB;

    // Construct query

    $query = 'SELECT SUM(duration) AS total, AVG(duration) AS average, COUNT(duration) AS count ';
    $query .= 'FROM ( ';
    $query .= 'SELECT duration FROM ' . $xoopsDB->prefix('uhqiceauth_authtrail') . ' y WHERE ';

    if ($interval) {
        $query .= 'logtime > SUBDATE( NOW(), INTERVAL ' . $interval . ') ';
    } else {
        $query .= 'DATE(logtime) = DATE( NOW() ) ';
    }

    $query .= ') z';

    // Get Result

    $result = $xoopsDB->queryF($query);
    if (!$result) {
        return null;
    }
    $row = $xoopsDB->fetchArray($result);

    $row['ttsl'] = uhqiceauth_time((int)$row['total']);
    $row['avg']  = uhqiceauth_time((int)$row['average']);

    return $row;
}

xoops_cp_header();
$adminObject = \Xmf\Module\Admin::getInstance();
$adminObject->displayNavigation(basename(__FILE__));

$data = [];

$data['duacount'] = uhqiceauth_summarycount('DUA');

if ($data['duacount']) {
    $data['listbyconn'] = uhqiceauth_acct_agentsumconn(10, 90);
    $data['listbytime'] = uhqiceauth_acct_agentsumtime(10, 90);
}

$data['ttsl'][0]         = uhqiceauth_ttsl();
$data['ttsl'][0]['name'] = _AM_UHQICEAUTH_STATS_TTSL_TODAY . date('Y-m-d, H:i:s');

$data['ttsl'][1]         = uhqiceauth_ttsl('1 DAY');
$data['ttsl'][1]['name'] = _AM_UHQICEAUTH_STATS_TTSL_24H;

$data['ttsl'][2]         = uhqiceauth_ttsl('7 DAY');
$data['ttsl'][2]['name'] = _AM_UHQICEAUTH_STATS_TTSL_7D;

$data['ttsl'][3]         = uhqiceauth_ttsl('30 DAY');
$data['ttsl'][3]['name'] = _AM_UHQICEAUTH_STATS_TTSL_30D;

// Assign & Render Template
$xoopsTpl->assign('data', $data);
$xoopsTpl->display('db:admin/uhqiceauth_acctrec.tpl');

require_once __DIR__ . '/admin_footer.php';

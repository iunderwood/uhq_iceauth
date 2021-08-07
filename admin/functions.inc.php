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

// Function to return summary counts.  Null for DB error, otherwise returns an integer.

defined('XOOPS_ROOT_PATH') || exit('XOOPS root path not defined');

function uhqiceauth_summarycount($sumtype, $mountdata = null)
{
    global $xoopsDB;

    // Base Query

    switch ($sumtype) {
        case 'AM':  // Active Mount Points
            $query = 'SELECT COUNT(*) FROM ' . $xoopsDB->prefix('uhqiceauth_activemounts');
            break;
        case 'AU':  // Authentication Logs
            $query = 'SELECT COUNT(*) FROM ' . $xoopsDB->prefix('uhqiceauth_authtrail');
            break;
        case 'DUA': // Distinct User Agents
            $query = 'SELECT COUNT(DISTINCT useragent) FROM ' . $xoopsDB->prefix('uhqiceauth_authtrail') . " WHERE authtype = 'L'";
            break;
        case 'IN':  // Introductions
            $query = 'SELECT COUNT(*) FROM ' . $xoopsDB->prefix('uhqiceauth_intros');
            break;
        case 'IP':  // IP Address Bans
            $query = 'SELECT COUNT(*) FROM ' . $xoopsDB->prefix('uhqiceauth_ipbans');
            break;
        case 'ML':  // Mount Point Logs
            $query = 'SELECT COUNT(*) FROM ' . $xoopsDB->prefix('uhqiceauth_mountlog');
            break;
        case 'MP':  // Mount Points
            $query = 'SELECT COUNT(*) FROM ' . $xoopsDB->prefix('uhqiceauth_servers');
            break;
        case 'SP':  // Streampass Entries
            $query = 'SELECT COUNT(*) FROM ' . $xoopsDB->prefix('uhqiceauth_streampass');
            break;
        case 'UA':  // User Agent Bans
            $query = 'SELECT COUNT(*) FROM ' . $xoopsDB->prefix('uhqiceauth_uabans');
            break;
        default:
            break;
    }

    // Append mount point query if all required data is supplied.  Does not apply to all summary count types.

    if ($mountdata && $mountdata['server'] && $mountdata['port'] && $mountdata['mount']) {
        $query .= " WHERE server = '" . $mountdata['server'] . "'";
        $query .= " AND port = '" . $mountdata['port'] . "'";
        $query .= " AND mount = '" . $mountdata['mount'] . "'";
    }

    // Perform Query & return count.

    $result = $xoopsDB->queryF($query);

    if ($result) {
        [$count] = $xoopsDB->fetchRow($result);

        return $count;
    }

    return null;
}

// Take time in seconds, return friendly time in string.
function uhqiceauth_time($duration)
{
    // Hours
    $hours = (int)($duration / 3600);
    // Minutes
    $minutes = ($hours * 3600);
    // Seconds
    $seconds = (int)$duration % 60;

    // Prepare Output String
    $time = '';

    if ($hours < 10) {
        $time .= '0';
    }
    $time .= $hours . ':';

    if ($minutes < 10) {
        $time .= '0';
    }
    $time .= $minutes . ':';

    if ($seconds < 10) {
        $time .= '0';
    }
    $time .= $seconds;

    return $time;
}

// Define array of acceptable MIME type for the intro uploader

$uhqiceauth_intro_mimes = ['audio/ogg', 'video/ogg', 'audio/mpeg', 'video/mpeg', 'audio/aac'];

// Return status of Geolocation Module

function uhqiceauth_geocheck()
{
    global $xoops_getHandler;

    /** @var \XoopsModuleHandler $moduleHandler */
    $moduleHandler = xoops_getHandler('module');
    $geolocate     = $moduleHandler->getByDirname('uhqgeolocate');

    if (is_object($geolocate)) {
        $isok = $geolocate->getVar('isactive');
    } else {
        $isok = false;
    }

    return $isok;
}

// Check and force the module to be anonymous.

function uhqiceauth_anoncheck()
{
    global $xoopsDB;

    /** @var \XoopsModuleHandler $moduleHandler */
    $moduleHandler = xoops_getHandler('module');
    $module        = $moduleHandler->getByDirname('uhqiceauth');

    $query = 'SELECT COUNT(*) FROM ' . $xoopsDB->prefix('group_permission');
    $query .= " WHERE gperm_itemid = '" . $module->getVar('mid') . "'";
    $query .= " AND gperm_name = 'module_read'";
    $query .= " AND gperm_groupid = '" . XOOPS_GROUP_ANONYMOUS . "'";

    $result = $xoopsDB->queryF($query);
    if (false === $result) {
        $status = _AM_UHQICEAUTH_SQLERR . $query . '<br>' . $xoopsDB->error();
    } else {
        [$anonok] = $xoopsDB->fetchRow($result);
    }

    if ($anonok) {
        $status = _AM_UHQICEAUTH_ANON_OK;
    } else {
        $sql = 'INSERT IGNORE INTO ' . $xoopsDB->prefix('group_permission') . " VALUES (NULL, '" . XOOPS_GROUP_ANONYMOUS . "', '" . $module->getVar('mid') . "', 1, 'module_read')";
        if ($xoopsDB->queryF($sql)) {
            $status = _AM_UHQICEAUTH_ANON_RESTORED;
        } else {
            $status = _AM_UHQICEAUTH_ANON_FAILED;
        }
    }

    return $status;
}

// Check MIME types for all supported formats

function uhqiceauth_mimecheck()
{
    global $uhqiceauth_intro_mimes;

    $fn = XOOPS_ROOT_PATH . '/class/mimetypes.inc.php';
    $fh = fopen($fn, 'r');
    if (!$fh) {
        $data['error'] = _AM_UHQICEAUTH_ERR_OPENMIME;

        return false;
    }
    $mimefile = fread($fh, filesize($fn));
    fclose($fh);

    if (!$mimefile) {
        $data['error'] = _AM_UHQICEAUTH_ERR_READMIME;

        return false;
    }
    $i = 0;

    foreach ($uhqiceauth_intro_mimes as $type) {
        $data['list'][$i]['type'] = $type;
        if (mb_strpos($mimefile, $type)) {
            $data['list'][$i]['status'] = _AM_UHQICEAUTH_CODEC_FOUND;
        } else {
            $data['list'][$i]['status'] = _AM_UHQICEAUTH_CODEC_NFOUND;
        }
        $i++;
    }
    unset($type);

    return $data;
}

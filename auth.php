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

include __DIR__ . '/../../mainfile.php';

// Functions stored externally
require_once XOOPS_ROOT_PATH . '/modules/uhq_iceauth/includes/sanity.php';
require_once XOOPS_ROOT_PATH . '/modules/uhq_iceauth/includes/auth.inc.php';

// All logging functions should be turned off for this page.
$xoopsLogger->activated = false;

// Set our global variables

$iceheaders     = array();  // Array which stores all the headers auth adds
$iceheadercount = 0;    // Incrementing count of all headers
$print_hdr      = 1;         // Show header array by default.

// Okay, we need options!
if ($_REQUEST['action']) {

    // Make requests sane.
    $sane_REQUEST = uhqiceauth_dosanity();

    // Load module configuration
    $moduleHandler     = xoops_getHandler('module');
    $xoopsModule       = $moduleHandler->getByDirname('uhq_iceauth');
    $configHandler     = xoops_getHandler('config');
    $xoopsModuleConfig = $configHandler->getConfigsByCat(0, $xoopsModule->getVar('mid'));

    // Process Actions
    switch ($_REQUEST['action']) {
        case 'stream_auth':     // Too much common code
        case 'listener_add':
            // Make sure we have the mount, server, port, and requesting IP.
            if ($sane_REQUEST['server'] && $sane_REQUEST['port'] && $sane_REQUEST['mount'] && $sane_REQUEST['ip']) {

                // Try and locate mount in DB
                $query  = 'SELECT COUNT(*) FROM ' . $xoopsDB->prefix('uhqiceauth_servers') . ' WHERE ';
                $query  .= "server = '" . $sane_REQUEST['server'] . "' AND port = '" . $sane_REQUEST['port'] . "' AND mount = '" . $sane_REQUEST['mount'] . "';";
                $result = $xoopsDB->queryF($query);
                if ($result === false) {
                    uhqiceauth_header($xoopsModuleConfig['hdr_msg'] . _MD_UHQICEAUTH_ERROR_SQL);
                    echo '<b>' . _MD_UHQICEAUTH_ERROR_SQL . '</b> ' . $query;
                    break;
                } else {
                    list($svr_count) = $xoopsDB->fetchRow($result);
                }

                if ($svr_count) {   // If Mount Found

                    // Load mount data, break if query fails.
                    $query  = 'SELECT * FROM ' . $xoopsDB->prefix('uhqiceauth_servers') . ' WHERE ';
                    $query  .= "server = '" . $sane_REQUEST['server'] . "' AND port = '" . $sane_REQUEST['port'] . "' AND mount = '" . $sane_REQUEST['mount'] . "';";
                    $result = $xoopsDB->queryF($query);
                    if ($result === false) {
                        uhqiceauth_header($xoopsModuleConfig['hdr_msg'] . _MD_UHQICEAUTH_ERROR_SQL);
                        echo '<b>' . _MD_UHQICEAUTH_ERROR_SQL . '</b> ' . $query;
                        break;
                    }

                    $row                 = $xoopsDB->fetchArray($result);
                    $row['lst_auth_grp'] = explode('|', $row['lst_auth_grp']);
                    $row['src_auth_grp'] = explode('|', $row['src_auth_grp']);

                    if ($_REQUEST['action'] === 'listener_add') {

                        // Check here to make sure the specified UA isn't banned.
                        if (uhqiceauth_ua_verify($sane_REQUEST['agent']) === false) {
                            uhqiceauth_header($xoopsModuleConfig['hdr_msg'] . _MD_UHQICEAUTH_ERROR_UABAN, 1);
                            uhqiceauth_authlog($sane_REQUEST, 'L', false, 301);
                            break;
                        }

                        // Check against username/pw.  Handling is based on mount type.
                        $user = uhqiceauth_checkuser($sane_REQUEST['user'], $sane_REQUEST['pass']);

                        switch ($row['lst_auth_typ']) {
                            case 'A':  // Anonymous
                                // Reset user if auth failed.  Anonymous moutpoints don't care if the auth is correct.
                                if (!$user) {
                                    unset($sane_REQUEST['user']);
                                }
                                // Check UA Bans
                                // Check IP Bans

                                // User is good if we've got no bans to consider.

                                // Enforce a time limit.
                                if ($row['timelimit'] > 0) {
                                    uhqiceauth_header($xoopsModuleConfig['hdr_time'] . ' ' . $row['timelimit']);
                                }

                                // Intro Dump is last.
                                uhqiceauth_authlog($sane_REQUEST, 'L', true);
                                if (uhqiceauth_introdump($row, $xoopsModuleConfig['hdr_auth'])) {
                                    $print_hdr = 0;     // Intro dump good, supress printed header info.
                                }
                                break;
                            case 'D':  // Check User
                                if (!$user) {
                                    uhqiceauth_header($xoopsModuleConfig['hdr_msg'] . _MD_UHQICEAUTH_ERROR_CRED, 1);
                                    uhqiceauth_authlog($sane_REQUEST, 'L', false, 101);
                                    break;
                                }
                                // Make sure mount groups are acceptable
                                if (!uhqiceauth_checkgroup($user, $row['lst_auth_grp'])) {
                                    uhqiceauth_header($xoopsModuleConfig['hdr_msg'] . _MD_UHQICEAUTH_ERROR_GRP, 1);
                                    uhqiceauth_authlog($sane_REQUEST, 'L', false, 102);
                                    break;
                                }
                                // Check UA Bans
                                // Check IP Bans

                                // Enforce Time Limit
                                if ($row['timelimit'] > 0) {
                                    uhqiceauth_header($xoopsModuleConfig['hdr_time'] . ' ' . $row['timelimit']);
                                }

                                // Check for and dump any intros.
                                uhqiceauth_authlog($sane_REQUEST, 'L', true);
                                if (uhqiceauth_introdump($row, $xoopsModuleConfig['hdr_auth'])) {
                                    $print_hdr = 0;     // Intro dump good, supress printed header info.
                                }
                                break;
                            default:   // Error if Default
                                uhqiceauth_header($xoopsModuleConfig['hdr_msg'] . _MD_UHQICEAUTH_ERROR_LSTUNDEF . $row['lst_auth_typ'], 1);
                                break;
                        }
                    } else {
                        switch ($row['src_auth_typ']) {
                            case 'D':  // Check Database
                                $user = uhqiceauth_checkuser($sane_REQUEST['user'], $sane_REQUEST['pass']);
                                if (!$user) {
                                    uhqiceauth_header($xoopsModuleConfig['hdr_msg'] . _MD_UHQICEAUTH_ERROR_SRCCRED, 1);
                                    uhqiceauth_authlog($sane_REQUEST, 'S', false, 201);
                                    break;
                                }
                                // Make sure mount groups are acceptable
                                if (!uhqiceauth_checkgroup($user->Uid(), $row['src_auth_grp'])) {
                                    uhqiceauth_header($xoopsModuleConfig['hdr_msg'] . _MD_UHQICEAUTH_ERROR_GRP, 1);
                                    uhqiceauth_authlog($sane_REQUEST, 'S', false, 202);
                                    break;
                                }
                                // Report good if we've gotten this far.
                                uhqiceauth_header($xoopsModuleConfig['hdr_auth'], 1);
                                uhqiceauth_authlog($sane_REQUEST, 'S', true);
                                break;
                            case 'S':  // Static Definition
                                if (($row['src_auth_un'] == $sane_REQUEST['user'])
                                    && ($row['src_auth_pw'] == $sane_REQUEST['pass'])) {
                                    uhqiceauth_header($xoopsModuleConfig['hdr_auth'], 1);
                                    uhqiceauth_authlog($sane_REQUEST, 'S', true);
                                } else {
                                    uhqiceauth_header($xoopsModuleConfig['hdr_msg'] . _MD_UHQICEAUTH_ERROR_SRCCRED, 1);
                                    uhqiceauth_authlog($sane_REQUEST, 'S', false, 201);
                                }
                                break;
                            case 'N':  // Not Used
                                uhqiceauth_header($xoopsModuleConfig['hdr_msg'] . _MD_UHQICEAUTH_ERROR_SRCNOAUTH . $row['src_auth_typ'], 1);
                                uhqiceauth_authlog($sane_REQUEST, 'S', false, 203);
                                break;
                            default:
                                uhqiceauth_header($xoopsModuleConfig['hdr_msg'] . _MD_UHQICEAUTH_ERROR_SRCUNDEF . $row['src_auth_typ'], 1);
                                uhqiceauth_authlog($sane_REQUEST, 'S', false, 203);
                                break;
                        }
                    }
                } else {            // If Mount not found ...
                    if ($_REQUEST['action'] === 'listener_add') {
                        switch ($xoopsModuleConfig['undef_action']) {
                            case 'D': // Check against DB and default groups
                                // Make sure we have all the parameters we need
                                if ($sane_REQUEST['user'] && $sane_REQUEST['pass']) {
                                    // Make sure user is okay
                                    $user = uhqiceauth_checkuser($sane_REQUEST['user'], $sane_REQUEST['pass']);
                                    if (!$user) {
                                        uhqiceauth_header($xoopsModuleConfig['hdr_msg'] . _MD_UHQICEAUTH_ERROR_CRED, 1);
                                        break;
                                    }
                                    // Make sure mount groups are acceptable
                                    if (!uhqiceauth_checkgroup($user->Uid(), $xoopsModuleConfig['undef_group'])) {
                                        uhqiceauth_header($xoopsModuleConfig['hdr_msg'] . _MD_UHQICEAUTH_ERROR_GRP, 1);
                                        break;
                                    }
                                    // Report good if we've gotten this far.
                                    uhqiceauth_header($xoopsModuleConfig['hdr_auth'], 1);

                                    // Add time limit if we're using it
                                    if ($xoopsModuleConfig['undef_time'] > 0) {
                                        uhqiceauth_header($xoopsModuleConfig['hdr_time'] . ' ' . $xoopsModuleConfig['undef_time'], 1);
                                    }
                                } else {
                                    uhqiceauth_header($xoopsModuleConfig['hdr_msg'] . _MD_UHQICEAUTH_ERROR_UNPW, 1);
                                }
                                break;
                            case 'A': // Always Allow
                                uhqiceauth_header($xoopsModuleConfig['hdr_auth'], 1);

                                // Add time limit if we're using it
                                if ($xoopsModuleConfig['undef_time'] > 0) {
                                    uhqiceauth_header($xoopsModuleConfig['hdr_time'] . ' ' . $xoopsModuleConfig['undef_time'], 1);
                                }
                                break;
                            default: // Never Allow
                                uhqiceauth_header($xoopsModuleConfig['hdr_msg'] . _MD_UHQICEAUTH_ERROR_UNDEF . ' ' . $sane_REQUEST['mount'], 1);
                                break;
                        }
                    } else {
                        uhqiceauth_header($xoopsModuleConfig['hdr_msg'] . _MD_UHQICEAUTH_ERROR_SRCREQ . $sane_REQUEST['mount'], 1);
                    }
                }
            } else {
                uhqiceauth_header($xoopsModuleConfig['hdr_msg'] . _MD_UHQICEAUTH_ERROR_PARAM, 1);
            }
            break;
        case 'listener_remove':
            if ($sane_REQUEST['server'] && $sane_REQUEST['port'] && $sane_REQUEST['mount']
                && $sane_REQUEST['duration']) {
                // Locate Mount in DB
                $query  = 'SELECT COUNT(*) FROM ' . $xoopsDB->prefix('uhqiceauth_servers') . ' WHERE ';
                $query  .= "server = '" . $sane_REQUEST['server'] . "' AND port = '" . $sane_REQUEST['port'] . "' AND mount = '" . $sane_REQUEST['mount'] . "';";
                $result = $xoopsDB->queryF($query);
                if ($result === false) {
                    uhqiceauth_header($xoopsModuleConfig['hdr_msg'] . _MD_UHQICEAUTH_ERROR_SQL, 1);
                    echo '<b>' . _MD_UHQICEAUTH_ERROR_SQL . '</b> ' . $query;
                    break;
                } else {
                    list($svr_count) = $xoopsDB->fetchRow($result);
                }

                if ($svr_count) {   // If Mount Found
                    // Log only explicitly defined mounts.
                    uhqiceauth_header($xoopsModuleConfig['hdr_auth'], 1);
                    uhqiceauth_acctlog($sane_REQUEST);
                } else {
                    uhqiceauth_header($xoopsModuleConfig['hdr_msg'] . _MD_UHQICEAUTH_ERROR_UNDEF . $sane_REQUEST['mount'], 1);
                }
            } else {
                uhqiceauth_header($xoopsModuleConfig['hdr_msg'] . _MD_UHQICEAUTH_ERROR_PARAM, 1);
            }
            break;
        case 'mount_add':
        case 'mount_remove':
            if ($sane_REQUEST['server'] && $sane_REQUEST['port'] && $sane_REQUEST['mount']) {
                // Locate Mount in DB
                $query  = 'SELECT COUNT(*) FROM ' . $xoopsDB->prefix('uhqiceauth_servers') . ' WHERE ';
                $query  .= "server = '" . $sane_REQUEST['server'] . "' AND port = '" . $sane_REQUEST['port'] . "' AND mount = '" . $sane_REQUEST['mount'] . "';";
                $result = $xoopsDB->queryF($query);
                if ($result === false) {
                    uhqiceauth_header($xoopsModuleConfig['hdr_msg'] . _MD_UHQICEAUTH_ERROR_SQL, 1);
                    echo '<b>' . _MD_UHQICEAUTH_ERROR_SQL . '</b> ' . $query;
                    break;
                } else {
                    list($svr_count) = $xoopsDB->fetchRow($result);
                }
                if ($svr_count) {   // If Mount Found
                    // Log only explicitly defined mounts.
                    uhqiceauth_header($xoopsModuleConfig['hdr_auth'], 1);
                    uhqiceauth_mountlog($sane_REQUEST);
                } else {
                    uhqiceauth_header($xoopsModuleConfig['hdr_msg'] . _MD_UHQICEAUTH_ERROR_UNDEF . $sane_REQUEST['mount'], 1);
                }
            } else {
                uhqiceauth_header($xoopsModuleConfig['hdr_msg'] . _MD_UHQICEAUTH_ERROR_PARAM, 1);
            }
            break;
        case 'test':
            uhqiceauth_header($xoopsModuleConfig['hdr_msg'] . _MD_UHQICEAUTH_ERROR_TEST, 1);
            break;
        default:
            uhqiceauth_header($xoopsModuleConfig['hdr_msg'] . _MD_UHQICEAUTH_ERROR_ACTU, 1);
            break;
    }

    if ($print_hdr) {
        echo '<pre>';
        echo 'IceAuth Header ';
        print_r($iceheaders);
        echo '</pre>';
    }
} else {
    echo('This file is not meant to be called directly.');
}

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

require_once XOOPS_ROOT_PATH . '/modules/uhq_iceauth/includes/functions.php';

function b_uhqiceauth_activemounts_show()
{
    $block = array();

    // All we do is show what mountpoints are active.

    global $xoopsDB;

    $block = uhqiceauth_raw_activemounts();

    if ($block == null) {
        // DB error should be stored in the last DB call.
        $block['error'] = _MB_UHQICEAUTH_ACTIVE_DBERR . ' [' . $xoopsDB->error() . ']';
    }

    return $block;
}

function b_uhqiceauth_activemounts_edit()
{
}

// Block which displays and sets a user's authenticated stream PW.

function b_uhqiceauth_streampass_show()
{
    $block = array();

    global $xoopsUser;
    global $xoopsDB;

    // Make sure we have a valid user

    if (is_object($xoopsUser)) {
        $block['username'] = $xoopsUser->getVar('uname');

        // Check DB for entry

        $query = 'SELECT * FROM ' . $xoopsDB->prefix('uhqiceauth_streampass');
        $query .= " WHERE un = '" . utf8_encode(strtolower($block['username'])) . "'";

        $result = $xoopsDB->queryF($query);
        if ($result === false) {
            $block['error'] = $xoopsDB->error();

            return $block;
        } else {
            if ($row = $xoopsDB->fetchArray($result)) {
                $block['password'] = $row['pw'];
            } else {
                $block['password'] = uhqiceauth_randompw(8);

                $query = 'INSERT INTO ' . $xoopsDB->prefix('uhqiceauth_streampass') . ' SET';
                $query .= " pw = '" . $block['password'] . "',";
                $query .= ' added = now(),';
                $query .= " un = '" . utf8_encode(strtolower($block['username'])) . "'";

                $result = $xoopsDB->queryF($query);
                if ($result === false) {
                    $block['error'] = $xoopsDB->error();

                    return $block;
                }
            }
        }
    } else {
        return false;
    }

    return $block;
}

function b_uhqiceauth_streampass_edit()
{
}

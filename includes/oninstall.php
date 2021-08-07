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

function xoops_module_install_uhqiceauth(\XoopsModule $module)
{
    global $xoopsDB;

    // Set anonymous access to the module

    $sql = 'INSERT IGNORE INTO ' . $xoopsDB->prefix('group_permission') . " VALUES (NULL, '" . XOOPS_GROUP_ANONYMOUS . "', '" . $module->getVar('mid') . "', 1, 'module_read')";
    if ($xoopsDB->queryF($sql)) {
        echo _MI_UHQICEAUTH_INSTALL_GOOD;
    } else {
        echo _MI_UHQICEAUTH_INSTALL_FAULT;
    }

    // Set Main Menu to visibility of 0

    $sql = 'UPDATE ' . $xoopsDB->prefix('modules') . " SET weight = 0 WHERE dirname = 'uhqiceauth'";
    if ($xoopsDB->queryF($sql)) {
        echo _MI_UHQICEAUTH_WEIGHT_OK;
    } else {
        echo _MI_UHQICEAUTH_WEIGHT_NOK;
    }

    return true;
}

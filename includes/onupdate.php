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

function xoops_module_update_uhq_iceauth(XoopsModule $module, $oldversion = null)
{
    global $xoopsDB;

    // Add table to all versions less than v0.2.

    if ($oldversion < 20) {
        $query  = 'CREATE TABLE ' . $xoopsDB->prefix('uhqiceauth_servers') . ' (
                server          CHAR(50)    NOT NULL,
                port            INT         UNSIGNED NOT NULL,
                mount           CHAR(20)    NOT NULL,
                timelimit       INT         UNSIGNED NOT NULL,
                lst_auth_typ    CHAR(1)     NOT NULL,
                lst_auth_grp    VARCHAR(64) NOT NULL,
                src_auth_typ        CHAR(1)     NOT NULL,
                src_auth_grp    VARCHAR(64) NOT NULL,
                src_auth_un     CHAR(20)    NOT NULL,
                src_auth_pw     CHAR(20)    NOT NULL,
                hits_pass       INT         UNSIGNED NOT NULL,
                hits_fail       INT         UNSIGNED NOT NULL,
                PRIMARY KEY (server,port,mount)
            ) ENGINE=MyISAM;';
        $result = $xoopsDB->queryF($query);
        if (!$result) {
            $module->setErrors('Error adding DB table uhqiceauth_servers');

            return false;
        } else {
            // Upgrade successful.  Do next revision.
            $oldversion = 21;
        }
    }

    // Modify tables for v0.3

    if ($oldversion == 21) {
        $query  = 'ALTER TABLE ' . $xoopsDB->prefix('uhqiceauth_servers') . ' ADD src_hits_pass INT UNSIGNED NOT NULL AFTER hits_fail';
        $result = $xoopsDB->queryF($query);
        if (!$result) {
            $module->setErrors('Error adding src_hits_pass field.');

            return false;
        }
        $query  = 'ALTER TABLE ' . $xoopsDB->prefix('uhqiceauth_servers') . ' ADD src_hits_fail INT UNSIGNED NOT NULL AFTER src_hits_pass';
        $result = $xoopsDB->queryF($query);
        if (!$result) {
            $module->setErrors('Error adding src_hits_fail field.');

            return false;
        }

        $query  = 'CREATE TABLE ' . $xoopsDB->prefix('uhqiceauth_authtrail') . ' (
                sequence        INT         UNSIGNED NOT NULL AUTO_INCREMENT,
                logtime         DATETIME,
                server          CHAR(50)    NOT NULL,
                port            INT         UNSIGNED NOT NULL,
                mount           CHAR(20)    NOT NULL,
                authtype        CHAR(1)     NOT NULL,
                authstat        CHAR(1)     NOT NULL,
                clientid        INT         UNSIGNED NOT NULL,
                username        CHAR(20),
                useragent       CHAR(40),
                userip          CHAR(50)    NOT NULL,
                duration        INT         UNSIGNED,
                stoptime        DATETIME,
                PRIMARY KEY (sequence)
            ) ENGINE=MyISAM;';
        $result = $xoopsDB->queryF($query);
        if (!$result) {
            $module->setErrors('Error adding DB table uhqiceauth_authtrail');

            return false;
        }

        $query  = 'CREATE TABLE ' . $xoopsDB->prefix('uhqiceauth_accounting') . ' (
                sequence        INT         UNSIGNED NOT NULL AUTO_INCREMENT,
                logtime         TIMESTAMP,
                server          CHAR(50)    NOT NULL,
                port            INT         UNSIGNED NOT NULL,
                mount           CHAR(20)    NOT NULL,
                clientid        INT         UNSIGNED NOT NULL,
                username        CHAR(20),
                duration        INT         UNSIGNED NOT NULL,
                PRIMARY KEY (sequence)
            ) ENGINE=MyISAM;';
        $result = $xoopsDB->queryF($query);
        if (!$result) {
            $module->setErrors('Error adding DB table uhqiceauth_accounting');

            return false;
        }

        $oldversion = 30;
    }

    // Modify tables for v0.4

    if ($oldversion == 30) {
        $query  = 'ALTER TABLE ' . $xoopsDB->prefix('uhqiceauth_accounting') . ' ADD useragent CHAR(40) AFTER username';
        $result = $xoopsDB->queryF($query);
        if (!$result) {
            $module->setErrors('Error adding useragent field.');

            return false;
        }

        $query  = 'ALTER TABLE ' . $xoopsDB->prefix('uhqiceauth_accounting') . ' ADD userip CHAR(50) AFTER useragent';
        $result = $xoopsDB->queryF($query);
        if (!$result) {
            $module->setErrors('Error adding userip field.');

            return false;
        }

        $query  = 'ALTER TABLE ' . $xoopsDB->prefix('uhqiceauth_accounting') . ' ADD starttime DATETIME AFTER userip';
        $result = $xoopsDB->queryF($query);
        if (!$result) {
            $module->setErrors('Error adding DB starttime field.');

            return false;
        }

        $query  = 'CREATE TABLE ' . $xoopsDB->prefix('uhqiceauth_mountlog') . ' (
                sequence        INT         UNSIGNED NOT NULL AUTO_INCREMENT,
                logtime         TIMESTAMP,
                server          CHAR(50)    NOT NULL,
                port            INT         UNSIGNED NOT NULL,
                mount           CHAR(20)    NOT NULL,
                action          CHAR(1)     NOT NULL,
                PRIMARY KEY (sequence)
            ) ENGINE=MyISAM;';
        $result = $xoopsDB->queryF($query);
        if (!$result) {
            $module->setErrors('Error adding DB table uhqiceauth_mountlog');

            return false;
        }

        $query  = 'CREATE TABLE ' . $xoopsDB->prefix('uhqiceauth_activemounts') . ' (
                server          CHAR(50)    NOT NULL,
                port            INT         UNSIGNED NOT NULL,
                mount           CHAR(20)    NOT NULL,
                starttime       TIMESTAMP,
                PRIMARY KEY (server,port,mount)
            ) ENGINE=MyISAM;';
        $result = $xoopsDB->queryF($query);
        if (!$result) {
            $module->setErrors('Error adding DB table uhqiceauth_activemounts');

            return false;
        }

        $oldversion = 40;
    }

    if ($oldversion == 40) {

        // Set Main Menu to visibility of 0

        $sql = 'UPDATE ' . $xoopsDB->prefix('modules') . " SET weight = 0 WHERE dirname = 'uhq_iceauth'";
        if ($xoopsDB->queryF($sql)) {
            echo _MI_UHQICEAUTH_WEIGHT_OK;
        } else {
            echo _MI_UHQICEAUTH_WEIGHT_NOK;
        }

        $oldversion = 50;
    }
    // Return true if we get this far!

    if ($oldversion == 50) {

        // Need a larger User Agent field

        $query = 'ALTER TABLE ' . $xoopsDB->prefix('uhqiceauth_authtrail') . ' MODIFY useragent VARCHAR(96)';

        $result = $xoopsDB->queryF($query);
        if (!$result) {
            $module->setErrors('Error modifying useragent in uhqiceauth_authtrail');

            return false;
        }

        $query = 'ALTER TABLE ' . $xoopsDB->prefix('uhqiceauth_accounting') . ' MODIFY useragent VARCHAR(96)';

        $result = $xoopsDB->queryF($query);
        if (!$result) {
            $module->setErrors('Error modifying useragent in uhqiceauth_accounting');

            return false;
        }

        // New SQL Tables

        $query  = 'CREATE TABLE ' . $xoopsDB->prefix('uhqiceauth_intros') . ' (
                intronum        INT         UNSIGNED NOT NULL AUTO_INCREMENT,
                filename        CHAR(50)    NOT NULL,
                codec           CHAR(1)     NOT NULL,
                description     VARCHAR(255),
                PRIMARY KEY (intronum)
            ) ENGINE=MyISAM;';
        $result = $xoopsDB->queryF($query);
        if (!$result) {
            $module->setErrors('Error adding DB table uhqiceauth_intros');

            return false;
        }

        $query  = 'CREATE TABLE ' . $xoopsDB->prefix('uhqiceauth_intromap') . ' (
                intronum        INT         UNSIGNED NOT NULL,
                server          CHAR(50)    NOT NULL,
                port            INT         UNSIGNED NOT NULL,
                mount           CHAR(20)    NOT NULL,
                sequence        INT         UNSIGNED NOT NULL,
                PRIMARY KEY (intronum,server,port,mount)
            ) ENGINE=MyISAM;';
        $result = $xoopsDB->queryF($query);
        if (!$result) {
            $module->setErrors('Error adding DB table uhqiceauth_intromap');

            return false;
        }

        $query  = 'CREATE TABLE ' . $xoopsDB->prefix('uhqiceauth_streampass') . ' (
                un      VARCHAR(50) NOT NULL,
                pw      VARCHAR(50) NOT NULL,
                added   DATETIME,
                used    DATETIME,
                PRIMARY KEY (un,pw)
            ) ENGINE=MyISAM;';
        $result = $xoopsDB->queryF($query);
        if (!$result) {
            $module->setErrors('Error adding DB table uhqiceauth_streampass');

            return false;
        }

        $oldversion = 60;
    }

    if ($oldversion == 60) {

        // Adding FQDN recording to the authentication log.
        $query  = 'ALTER TABLE ' . $xoopsDB->prefix('uhqiceauth_authtrail') . ' ADD userrdns VARCHAR(64) AFTER userip';
        $result = $xoopsDB->queryF($query);
        if (!$result) {
            $module->setErrors('Error adding DB userrdns field.');

            return false;
        }

        // Adding authinfo to the authentication log to allow for specific comments.
        $query  = 'ALTER TABLE ' . $xoopsDB->prefix('uhqiceauth_authtrail') . ' ADD authinfo INT AFTER authstat';
        $result = $xoopsDB->queryF($query);
        if (!$result) {
            $module->setErrors('Error adding DB userrdns field.');

            return false;
        }

        // Adding user agent ban table.
        $query  = 'CREATE TABLE ' . $xoopsDB->prefix('uhqiceauth_uabans') . ' (
              sequence      INT         UNSIGNED NOT NULL AUTO_INCREMENT,
              useragent     VARCHAR(96),
              PRIMARY KEY (sequence)
            ) ENGINE=MyISAM;';
        $result = $xoopsDB->queryF($query);
        if (!$result) {
            $module->setErrors('Error adding DB table uhqiceauth_uabans');

            return false;
        }

        $oldversion = 70;
    }

    if ($oldversion == 70) {

        // Adding Geolocation Country Code to the authentication log

        $query  = 'ALTER TABLE ' . $xoopsDB->prefix('uhqiceauth_authtrail') . ' ADD geocc CHAR(2) AFTER stoptime';
        $result = $xoopsDB->queryF($query);
        if (!$result) {
            $module->setErrors('Error adding DB geocc field.');

            return false;
        }

        // Adding Geolocation Region to the authentication log

        $query  = 'ALTER TABLE ' . $xoopsDB->prefix('uhqiceauth_authtrail') . ' ADD georegion VARCHAR(128) AFTER geocc';
        $result = $xoopsDB->queryF($query);
        if (!$result) {
            $module->setErrors('Error adding DB georegion field.');

            return false;
        }

        // Adding Geolocation City to the authentication log

        $query  = 'ALTER TABLE ' . $xoopsDB->prefix('uhqiceauth_authtrail') . ' ADD geocity VARCHAR(128) AFTER georegion';
        $result = $xoopsDB->queryF($query);
        if (!$result) {
            $module->setErrors('Error adding DB geocity field.');

            return false;
        }

        // Need a larger User Agent field

        $query = 'ALTER TABLE ' . $xoopsDB->prefix('uhqiceauth_authtrail') . ' MODIFY useragent VARCHAR(128)';

        $result = $xoopsDB->queryF($query);
        if (!$result) {
            $module->setErrors('Error modifying useragent in uhqiceauth_authtrail');

            return false;
        }

        $query = 'ALTER TABLE ' . $xoopsDB->prefix('uhqiceauth_uabans') . ' MODIFY useragent VARCHAR(128)';

        $result = $xoopsDB->queryF($query);
        if (!$result) {
            $module->setErrors('Error modifying useragent in uhqiceauth_uabans');

            return false;
        }

        $query - 'DROP TABLE ' . $xoopsDB->prefix('uhqiceauth_accounting');
        $result = $xoopsDB->queryF($query);
        if (!$result) {
            $module->setErrors('Error removing table uhqiceauth_accounting');

            return false;
        }

        $oldversion = 80;
    }

    if ($oldversion == 80) {
        // Further modifications to the UA Ban Table.

        $query  = 'ALTER TABLE ' . $xoopsDB->prefix('uhqiceauth_uabans') . ' ADD matchtype CHAR(1) AFTER useragent';
        $result = $xoopsDB->queryF($query);
        if (!$result) {
            $module->setErrors('Error adding field matchtype in uhqiceauth_uabans');

            return false;
        }

        // Create IP Ban Table

        $query  = 'CREATE TABLE ' . $xoopsDB->prefix('uhqiceauth_ipbans') . ' (
                startip     INT         UNSIGNED,
                endip       INT         UNSIGNED,
                added       DATETIME,
                comment     VARCHAR(128),
                PRIMARY KEY (startip,endip)
            ) ENGINE=MyISAM;';
        $result = $xoopsDB->queryF($query);
        if (!$result) {
            $module->setErrors('Error adding DB table uhqiceauth_ipbans');

            return false;
        }

        $oldversion = 90;
    }

    if ($oldversion < 93) {
        // Add column to the auth table to store the referer.
        $query  = 'ALTER TABLE ' . $xoopsDB->prefix('uhqiceauth_authtrail') . ' ADD referer VARCHAR(128) AFTER useragent';
        $result = $xoopsDB->queryF($query);
        if (!$result) {
            $module->setErrors('Error adding field matchtype in uhqiceauth_uabans');

            return false;
        }
        $oldversion = 93;
    }

    return true;
}

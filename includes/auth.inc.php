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

// Returns User ID if username and password authenticate successfully.
function uhqiceauth_checkuser($un, $pw)
{
    global $xoopsDB;

    $memberHandler = xoops_getHandler('member');

    // Check PW against XOOPS DB First

    $user = $memberHandler->loginUser($un, $pw);

    if (!$user) {

        // Check PW against stream auth database next.

        $query = 'SELECT COUNT(*) FROM ' . $xoopsDB->prefix('uhqiceauth_streampass');
        $query .= " WHERE un='" . strtolower($un) . "' AND pw='" . $pw . "'";

        $result = $xoopsDB->queryF($query);
        if (false === $result) {
            // Return false if DB query fails.
        } else {
            // Query passed.  Fetch result.
            list($matches) = $xoopsDB->fetchRow($result);
            if ($matches > 0) {

                // If there's a match, attempt to update the match time.
                $query = 'UPDATE ' . $xoopsDB->prefix('uhqiceauth_streampass');
                $query .= " SET used=now() WHERE un='" . strtolower($un) . "' AND pw='" . $pw . "'";
                $xoopsDB->queryF($query);

                // Get the user record by name and return a uid
                $query  = 'SELECT uid FROM ' . $xoopsDB->prefix('users') . " WHERE uname LIKE '" . strtolower($un) . "'";
                $result = $xoopsDB->queryF($query);

                if ($result) {
                    list($uid) = $xoopsDB->fetchRow($result);

                    return $uid;
                }
            }
        }
    } else {
        return $user->Uid();
    }

    return false;
}

// Returns true if the user's groups intersect with the groups passed.
function uhqiceauth_checkgroup($uid, $grp)
{

    // Get User
    $memberHandler = xoops_getHandler('member');
    $user          = $memberHandler->getUser($uid);

    // Check Group
    $rtn = false;
    if (in_array(XOOPS_GROUP_ANONYMOUS, $grp)) {
        $rtn = true;
    } elseif (is_object($user)) {
        $rtn = count(array_intersect($user->getGroups(), $grp)) > 0;
    }

    return $rtn;
}

// The following functions check on variables in the module configuration.
function uhqiceauth_checkrdns()
{

    // Load module options
    $moduleHandler     = xoops_getHandler('module');
    $xoopsModule       = $moduleHandler->getByDirname('uhq_iceauth');
    $configHandler     = xoops_getHandler('config');
    $xoopsModuleConfig = $configHandler->getConfigsByCat(0, $xoopsModule->getVar('mid'));

    // Return true if reverse DNS is enabled in the configuration
    if (1 == $helper->getConfig('rdns')) {
        return true;
    } else {
        return false;
    }
}

function uhqiceauth_checklogadmin()
{

    // Load module options
    $moduleHandler     = xoops_getHandler('module');
    $xoopsModule       = $moduleHandler->getByDirname('uhq_iceauth');
    $configHandler     = xoops_getHandler('config');
    $xoopsModuleConfig = $configHandler->getConfigsByCat(0, $xoopsModule->getVar('mid'));

    // Return true if module-wide administrative update logging is enabled in the configuration
    if (1 == $helper->getConfig('logadminupdate')) {
        return true;
    } else {
        return false;
    }
}

// Puts the header in the HTTP header, and echoes it on a line of its own, if requested.
function uhqiceauth_header($hdr_txt)
{
    global $iceheaders;
    global $iceheadercount;

    header($hdr_txt);

    // Save added headers in an array.

    $iceheaders[$iceheadercount] = $hdr_txt;
    $iceheadercount++;
}

// Dumps the intro file if we've got one.
function uhqiceauth_introdump($mountrow = [], $header)
{
    global $xoopsDB;

    // Look and see if there are any intros.
    $query = 'SELECT COUNT(*) FROM ' . $xoopsDB->prefix('uhqiceauth_intromap');
    $query .= " WHERE server='" . $mountrow['server'] . "' AND";
    $query .= " port='" . $mountrow['port'] . "' AND";
    $query .= " mount='" . $mountrow['mount'] . "'";

    $result = $xoopsDB->queryF($query);
    if (false === $result) {
        $intros = 0;
    } else {
        list($intros) = $xoopsDB->fetchRow($result);
    }

    // Set header
    if (0 == $intros) {
        uhqiceauth_header($header, 1);

        return false;
    } else {
        // Dump intros.
        $query  = 'SELECT y.filename AS filename, y.codec AS codec FROM ' . $xoopsDB->prefix('uhqiceauth_intromap') . ' x,';
        $query  .= $xoopsDB->prefix('uhqiceauth_intros') . ' y ';
        $query  .= " WHERE x.server='" . $mountrow['server'] . "'";
        $query  .= " AND x.port='" . $mountrow['port'] . "'";
        $query  .= " AND x.mount='" . $mountrow['mount'] . "'";
        $query  .= ' AND x.intronum=y.intronum';
        $query  .= ' ORDER BY x.sequence';
        $result = $xoopsDB->queryF($query);
        if (false === $result) {
            // Base authentication if query fails.
            uhqiceauth_header($header, 1);

            return false;
        } else {
            uhqiceauth_header($header . ' withintro', 0);
        }
        while (false !== ($row = $xoopsDB->fetchArray($result))) {
            $fn = XOOPS_ROOT_PATH . '/modules/uhq_iceauth/intros/' . $row['filename'];
            if ($fh = fopen($fn, 'r')) {
                // Add MIME type
                switch ($row['codec']) {
                    case 'O':
                        uhqiceauth_header('Content-type: application/ogg', 0);
                        break;
                    case 'M':
                        uhqiceauth_header('Content-type: audio/mpeg', 0);
                        break;
                    case 'A':
                        uhqiceauth_header('Content-type: audio/aac', 0);
                        break;
                    case 'P':
                        uhqiceauth_header('Content-type: audio/aacp', 0);
                        break;
                    default:
                        uhqiceauth_header('Content-type: application/octet-stream', 0);
                        break;
                }
                // Copy file out
                while (!feof($fh)) {
                    $buffer = fread($fh, 2048);
                    print $buffer;
                }
            }
            fclose($fh);
        }

        return true;
    }
}

// Increments the hits for the listener counters.
function uhqiceauth_addhit($server, $port, $mount, $hit = false)
{
    global $xoopsDB;

    $query = 'UPDATE ' . $xoopsDB->prefix('uhqiceauth_servers') . ' SET ';
    if ($hit) {
        $query .= 'hits_pass = hits_pass + 1';
    } else {
        $query .= 'hits_fail = hits_fail + 1';
    }
    $query .= " WHERE server = '" . $server . "' AND port = '" . $port . "' AND mount = '" . $mount . "'";

    $result = $xoopsDB->queryF($query);
    if (false === $result) {
        echo '<b>SQL Error at:</b> ' . $query;
    }
}

// Increments the hits for the source counters.
function uhqiceauth_srcaddhit($server, $port, $mount, $hit = false)
{
    global $xoopsDB;

    $query = 'UPDATE ' . $xoopsDB->prefix('uhqiceauth_servers') . ' SET ';
    if ($hit) {
        $query .= 'src_hits_pass = src_hits_pass + 1';
    } else {
        $query .= 'src_hits_fail = src_hits_fail + 1';
    }
    $query .= " WHERE server = '" . $server . "' AND port = '" . $port . "' AND mount = '" . $mount . "'";

    $result = $xoopsDB->queryF($query);
    if (false === $result) {
        echo '<b>SQL Error at:</b> ' . $query;
    }
}

// Plug-in for Geolocation
function uhqiceauth_geolocate($ip)
{
    /** @var XoopsModuleHandler $moduleHandler */
    $moduleHandler = xoops_getHandler('module');
    $geolocate     = $moduleHandler->getByDirname('uhq_geolocate');
    if (is_object($geolocate)) {
        if ($geolocate->getVar('isactive')) {
            require_once XOOPS_ROOT_PATH . '/modules/uhq_geolocate/class/geolocate.class.php';

            $geoloc       = new geolocate;
            $geoloc->ipin = $ip;

            $location = $geoloc->locate();

            return $location;
        }
    }

    return null;
}

// Logs authentication requests.
function uhqiceauth_authlog($sane_REQUEST, $authtype, $authstat, $authinfo = null)
{
    global $xoopsDB;

    // Build logging insert
    $query = 'INSERT INTO ' . $xoopsDB->prefix('uhqiceauth_authtrail') . ' SET ';
    $query .= 'logtime = now(), ';
    $query .= "server = '" . $sane_REQUEST['server'] . "', ";
    $query .= "port = '" . $sane_REQUEST['port'] . "', ";
    $query .= "mount = '" . $sane_REQUEST['mount'] . "', ";

    // Change source authentication to A if there's an admin flag passed.
    if (('S' === $authtype) && $_REQUEST['admin']) {
        // Log if admin logging is true, otherwise skip the logs altogether.
        if (true === uhqiceauth_checklogadmin()) {
            $query .= "authtype = 'A', ";
        } else {
            return true;
        }
    } else {
        $query .= "authtype = '" . $authtype . "', ";
    }

    // If authentication passes, flag status as P, otherwise F.
    if (true === $authstat) {
        $query .= "authstat = 'P', ";
    } else {
        $query .= "authstat = 'F', ";
    }

    // Add any comment codes if they were passed.
    if (null != $authinfo) {
        $query .= "authinfo = '" . trim($authinfo) . "', ";
    }

    $query .= "clientid = '" . $sane_REQUEST['client'] . "', ";
    if (isset($sane_REQUEST['user'])) {
        $query .= "username = '" . $sane_REQUEST['user'] . "', ";
    }
    $query .= "useragent = '" . $sane_REQUEST['agent'] . "', ";
    $query .= "userip = '" . $sane_REQUEST['ip'] . "'";
    if (isset($sane_REQUEST['referer'])) {
        $query .= ", referer = '" . $sane_REQUEST['referer'] . "'";
    }

    // Do Geolocation
    $location = uhqiceauth_geolocate($sane_REQUEST['ip']);

    if (is_object($location)) {
        if (null != $location->country) {
            $query .= ", geocc = '" . $location->country . "' ";
        }
        if (null != $location->region) {
            $query .= ", georegion = '" . $location->region . "' ";
        }
        if (null != $location->city) {
            $query .= ", geocity = '" . $location->city . "'";
        }
    }

    // Make sure RNDS is enabled before we attempt to resolve.
    if (uhqiceauth_checkrdns()) {
        $query .= ", userrdns = '" . gethostbyaddr($sane_REQUEST['ip']) . "'";
    }

    $result = $xoopsDB->queryF($query);

    if (false === $result) {
        echo '<b>SQL Error at: </b> ' . $query;
    }

    // Run the hit counter
    if ('L' === $authtype) {
        uhqiceauth_addhit($sane_REQUEST['server'], $sane_REQUEST['port'], $sane_REQUEST['mount'], $authstat);
    } elseif ('S' === $authtype) {
        uhqiceauth_srcaddhit($sane_REQUEST['server'], $sane_REQUEST['port'], $sane_REQUEST['mount'], $authstat);
    }
}

// Logs accounting information when a listener disconnects.
function uhqiceauth_acctlog($sane_REQUEST)
{
    global $xoopsDB;

    // Locate an untagged authentication record within one minute of when we expect one.
    $query = 'SELECT * FROM ' . $xoopsDB->prefix('uhqiceauth_authtrail') . ' WHERE ';
    $query .= "server = '" . $sane_REQUEST['server'] . "' AND ";
    $query .= "port = '" . $sane_REQUEST['port'] . "' AND ";
    $query .= "mount = '" . $sane_REQUEST['mount'] . "' AND ";
    $query .= "clientid = '" . $sane_REQUEST['client'] . "' AND ";
    $query .= 'duration IS NULL AND ';

    // Limit queries to a 4-hour window.  This should cover most timezone changes.
    $query .= 'logtime > ( subtime(now(), SEC_TO_TIME(' . $sane_REQUEST['duration'] . '+7200)) ) AND ';
    $query .= 'logtime < ( subtime(now(), SEC_TO_TIME(' . $sane_REQUEST['duration'] . '-7200)) )';

    $authrecord = 0;

    $result = $xoopsDB->queryF($query);
    if (false === $result) {
        echo '<b>SQL Error at:</b> ' . $query;
    } else {
        $row = $xoopsDB->fetchArray($result);
        if ($row['sequence']) {
            $authrecord = $row['sequence'];
        }
    }

    if ($authrecord) {
        // Update authentication table, if we have infos.
        $query = 'UPDATE ' . $xoopsDB->prefix('uhqiceauth_authtrail') . ' SET ';
        $query .= "duration = '" . $sane_REQUEST['duration'] . "', ";
        $query .= 'stoptime = now()';
        $query .= " WHERE sequence = '" . $authrecord . "'";

        $result = $xoopsDB->queryF($query);
        if (false === $result) {
            echo '<b>SQL Error at:</b> ' . $query;
        }
    } else {
        // If we can't find it, build it with whatever info we've got.
        $query = 'INSERT INTO ' . $xoopsDB->prefix('uhqiceauth_authtrail') . ' SET ';
        $query .= "server = '" . $sane_REQUEST['server'] . "', ";
        $query .= "port = '" . $sane_REQUEST['port'] . "', ";
        $query .= "mount = '" . $sane_REQUEST['mount'] . "', ";
        $query .= "clientid = '" . $sane_REQUEST['client'] . "', ";
        $query .= "authtype = 'L',";
        $query .= "authstat = 'P',";
        $query .= "authinfo = '2',";    // 2 - Inserted Record
        if (isset($sane_REQUEST['user'])) {
            $query .= "username = '" . $sane_REQUEST['user'] . "', ";
        }
        $query .= "useragent = '" . $sane_REQUEST['agent'] . "', ";
        $query .= "userip = '" . $sane_REQUEST['ip'] . "', ";

        // Do Geolocation
        $location = uhqiceauth_geolocate($sane_REQUEST['ip']);

        if (is_object($location)) {
            if (null != $location->country) {
                $query .= "geocc = '" . $location->country . "', ";
            }
            if (null != $location->region) {
                $query .= "georegion = '" . $location->region . "', ";
            }
            if (null != $location->city) {
                $query .= "geocity = '" . $location->city . "', ";
            }
        }

        // Make sure RNDS is enabled before we attempt to resolve.
        if (uhqiceauth_checkrdns()) {
            $query .= "userrdns = '" . gethostbyaddr($sane_REQUEST['ip']) . "', ";
        }
        $query .= 'logtime = SUBTIME(now(), SEC_TO_TIME(' . $sane_REQUEST['duration'] . ')), ';
        $query .= 'stoptime = now(), ';
        $query .= "duration = '" . $sane_REQUEST['duration'] . "'";

        $result = $xoopsDB->queryF($query);

        if (false === $result) {
            echo '<b>SQL Error at:</b> ' . $query;
        }
    }
}

// Log mountpoint changes & maintain a list of active mountpoints.
function uhqiceauth_mountlog($sane_REQUEST)
{
    global $xoopsDB;

    if ('mount_add' === $_REQUEST['action']) {
        // Insert into Log
        $query = 'INSERT INTO ' . $xoopsDB->prefix('uhqiceauth_mountlog') . ' SET ';
        $query .= "server = '" . $sane_REQUEST['server'] . "', ";
        $query .= "port = '" . $sane_REQUEST['port'] . "', ";
        $query .= "mount = '" . $sane_REQUEST['mount'] . "', ";
        $query .= "action = 'A'";

        $result = $xoopsDB->queryF($query);
        if (false === $result) {
            echo '<b>SQL Error at:</b> ' . $query;
        }

        // Set Active Mountpoint
        $query = 'INSERT IGNORE INTO ' . $xoopsDB->prefix('uhqiceauth_activemounts') . ' SET ';
        $query .= "server = '" . $sane_REQUEST['server'] . "', ";
        $query .= "port = '" . $sane_REQUEST['port'] . "', ";
        $query .= "mount = '" . $sane_REQUEST['mount'] . "'";

        $result = $xoopsDB->queryF($query);
        if (false === $result) {
            echo '<b>SQL Error at:</b> ' . $query;
        }

        return true;
    }

    if ('mount_remove' === $_REQUEST['action']) {
        $query = 'INSERT INTO ' . $xoopsDB->prefix('uhqiceauth_mountlog') . ' SET ';
        $query .= "server = '" . $sane_REQUEST['server'] . "', ";
        $query .= "port = '" . $sane_REQUEST['port'] . "', ";
        $query .= "mount = '" . $sane_REQUEST['mount'] . "', ";
        $query .= "action = 'R'";

        $result = $xoopsDB->queryF($query);
        if (false === $result) {
            echo '<b>SQL Error at:</b> ' . $query;
        }

        // Remove Active Mountpoint
        $query  = 'DELETE FROM ' . $xoopsDB->prefix('uhqiceauth_activemounts') . ' WHERE ';
        $query  .= "server = '" . $sane_REQUEST['server'] . "' AND ";
        $query  .= "port = '" . $sane_REQUEST['port'] . "' AND ";
        $query  .= "mount = '" . $sane_REQUEST['mount'] . "'";
        $result = $xoopsDB->queryF($query);
        if (false === $result) {
            echo '<b>SQL Error at:</b> ' . $query;
        }

        return true;
    }
}

// Verify UA is acceptable.
function uhqiceauth_ua_verify($testua)
{
    global $xoopsDB;

    $query = 'SELECT * FROM ' . $xoopsDB->prefix('uhqiceauth_uabans');

    $result = $xoopsDB->queryF($query);

    if (false === $result) {
        // If the DB fails, pass the user agent test.
        return true;
    }

    while (false !== ($row = $xoopsDB->fetchArray($result))) {
        if (false === strpos($testua, $row['useragent'])) {
            // UA Pases.  Do nothing.
        } else {
            // UA Fails
            return false;
        }
    }

    // Test if we pass our UA tests.
    return true;
}

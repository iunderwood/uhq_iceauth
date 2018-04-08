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

use Xmf\Request;

include __DIR__ . '/../../mainfile.php';

// Simply clear out the temporary password in the stream table if the user requests it.  Redirect to page called from.

if (is_object($xoopsUser)) {
    $block['username'] = $xoopsUser->getVar('uname');

    // Check DB for entry

    $query = 'SELECT * FROM ' . $xoopsDB->prefix('uhqiceauth_streampass');
    $query .= " WHERE un = '" . utf8_encode($block['username']) . "'";

    $result = $xoopsDB->queryF($query);
    if (false === $result) {
        redirect_header(Request::getString('HTTP_REFERER', '', 'SERVER'), 10, _MD_UHQICEAUTH_CSP_DBERR);
    } else {
        if ($row = $xoopsDB->fetchArray($result)) {
            $query  = 'DELETE FROM ' . $xoopsDB->prefix('uhqiceauth_streampass') . ' WHERE';
            $query  .= " un = '" . utf8_encode($block['username']) . "'";
            $result = $xoopsDB->queryF($query);

            if (false === $result) {
                redirect_header(Request::getString('HTTP_REFERER', '', 'SERVER'), 10, _MD_UHQICEAUTH_CSP_DBERR);
            } else {
                redirect_header(Request::getString('HTTP_REFERER', '', 'SERVER'), 10, _MD_UHQICEAUTH_CSP_RESETOK);
            }
        } else {
            redirect_header(Request::getString('HTTP_REFERER', '', 'SERVER'), 10, _MD_UHQICEAUTH_CSP_NOPW);
        }
    }
} else {
    redirect_header(Request::getString('HTTP_REFERER', '', 'SERVER'), 10, _MD_UHQICEAUTH_CSP_LOGIN);
}

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

// Sanitize variables we expect for this module in $_REQUEST, that are put into queries.

use Xmf\Request;

function uhqiceauth_dosanity()
{
    $sanerequest = [];
    $myts        = \MyTextSanitizer::getInstance();

    // IceCast Variables
    if (Request::hasVar('server', 'REQUEST')) {
        $sanerequest['server'] = $myts->addSlashes($_REQUEST['server']);
    }
    if (Request::hasVar('port', 'REQUEST')) {
        $sanerequest['port'] = Request::getInt('port', 0, 'REQUEST');
    }
    if (Request::hasVar('mount', 'REQUEST')) {
        $sanerequest['mount'] = $myts->addSlashes($_REQUEST['mount']);
    }
    if (Request::hasVar('user', 'REQUEST')) {
        $sanerequest['user'] = $myts->addSlashes($_REQUEST['user']);
    }
    if (Request::hasVar('pass', 'REQUEST')) {
        $sanerequest['pass'] = $myts->addSlashes($_REQUEST['pass']);
    }
    if (Request::hasVar('client', 'REQUEST')) {
        $sanerequest['client'] = $myts->addSlashes($_REQUEST['client']);
    }
    if (Request::hasVar('ip', 'REQUEST')) {
        $sanerequest['ip'] = $myts->addSlashes($_REQUEST['ip']);
    }
    if (Request::hasVar('agent', 'REQUEST')) {
        $sanerequest['agent'] = $myts->addSlashes($_REQUEST['agent']);
    }
    if (Request::hasVar('duration', 'REQUEST')) {
        $sanerequest['duration'] = $myts->addSlashes($_REQUEST['duration']);
    }
    if (Request::hasVar('referer', 'REQUEST')) {
        $sanerequest['referer'] = $myts->addSlashes($_REQUEST['referer']);
    }

    // Module Variables
    if (Request::hasVar('timelimit', 'REQUEST')) {
        $sanerequest['timelimit'] = $myts->addSlashes($_REQUEST['timelimit']);
    }
    if (Request::hasVar('lst_auth_typ', 'REQUEST')) {
        $sanerequest['lst_auth_typ'] = $myts->addSlashes($_REQUEST['lst_auth_typ']);
    }
    if (Request::hasVar('lst_auth_grp', 'REQUEST')) {
        $sanerequest['lst_auth_grp'] = $myts->addSlashes($_REQUEST['lst_auth_grp']);
    }
    if (Request::hasVar('src_auth_typ', 'REQUEST')) {
        $sanerequest['src_auth_typ'] = $myts->addSlashes($_REQUEST['src_auth_typ']);
    }
    if (Request::hasVar('src_auth_grp', 'REQUEST')) {
        $sanerequest['src_auth_grp'] = $myts->addSlashes($_REQUEST['src_auth_grp']);
    }
    if (Request::hasVar('src_auth_un', 'REQUEST')) {
        $sanerequest['src_auth_un'] = $myts->addSlashes($_REQUEST['src_auth_un']);
    }
    if (Request::hasVar('src_auth_pw', 'REQUEST')) {
        $sanerequest['src_auth_pw'] = $myts->addSlashes($_REQUEST['src_auth_pw']);
    }
    if (Request::hasVar('o_server', 'REQUEST')) {
        $sanerequest['o_server'] = $myts->addSlashes($_REQUEST['o_server']);
    }
    if (Request::hasVar('o_port', 'REQUEST')) {
        $sanerequest['o_port'] = $myts->addSlashes($_REQUEST['o_port']);
    }
    if (Request::hasVar('o_mount', 'REQUEST')) {
        $sanerequest['o_mount'] = $myts->addSlashes($_REQUEST['o_mount']);
    }
    if (Request::hasVar('codec', 'REQUEST')) {
        $sanerequest['codec'] = $myts->addSlashes($_REQUEST['codec']);
    }
    if (Request::hasVar('description', 'REQUEST')) {
        $sanerequest['description'] = $myts->addSlashes($_REQUEST['description']);
    }
    if (Request::hasVar('intronum', 'REQUEST')) {
        $sanerequest['intronum'] = Request::getInt('intronum', 0, 'REQUEST');
    }

    // Administrative Fields
    if (Request::hasVar('sequence', 'REQUEST')) {
        $sanerequest['sequence'] = Request::getInt('sequence', 0, 'REQUEST');
    }
    if (Request::hasVar('testua', 'REQUEST')) {
        $sanerequest['testua'] = $myts->addSlashes($_REQUEST['testua']);
    }
    if (Request::hasVar('useragent', 'REQUEST')) {
        $sanerequest['useragent'] = $myts->addSlashes($_REQUEST['useragent']);
    }

    return $sanerequest;
}

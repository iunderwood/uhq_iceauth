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

function uhqiceauth_dosanity()
{
    $sanerequest = [];
    $myts        = \MyTextSanitizer::getInstance();

    // IceCast Variables
    if (isset($_REQUEST['server'])) {
        $sanerequest['server'] = $myts->addSlashes($_REQUEST['server']);
    }
    if (\Xmf\Request::hasVar('port', 'REQUEST')) {
        $sanerequest['port'] = \Xmf\Request::getInt('port', 0, 'REQUEST');
    }
    if (isset($_REQUEST['mount'])) {
        $sanerequest['mount'] = $myts->addSlashes($_REQUEST['mount']);
    }
    if (isset($_REQUEST['user'])) {
        $sanerequest['user'] = $myts->addSlashes($_REQUEST['user']);
    }
    if (isset($_REQUEST['pass'])) {
        $sanerequest['pass'] = $myts->addSlashes($_REQUEST['pass']);
    }
    if (isset($_REQUEST['client'])) {
        $sanerequest['client'] = $myts->addSlashes($_REQUEST['client']);
    }
    if (isset($_REQUEST['ip'])) {
        $sanerequest['ip'] = $myts->addSlashes($_REQUEST['ip']);
    }
    if (isset($_REQUEST['agent'])) {
        $sanerequest['agent'] = $myts->addSlashes($_REQUEST['agent']);
    }
    if (isset($_REQUEST['duration'])) {
        $sanerequest['duration'] = $myts->addSlashes($_REQUEST['duration']);
    }
    if (isset($_REQUEST['referer'])) {
        $sanerequest['referer'] = $myts->addSlashes($_REQUEST['referer']);
    }

    // Module Variables
    if (isset($_REQUEST['timelimit'])) {
        $sanerequest['timelimit'] = $myts->addSlashes($_REQUEST['timelimit']);
    }
    if (isset($_REQUEST['lst_auth_typ'])) {
        $sanerequest['lst_auth_typ'] = $myts->addSlashes($_REQUEST['lst_auth_typ']);
    }
    if (isset($_REQUEST['lst_auth_grp'])) {
        $sanerequest['lst_auth_grp'] = $myts->addSlashes($_REQUEST['lst_auth_grp']);
    }
    if (isset($_REQUEST['src_auth_typ'])) {
        $sanerequest['src_auth_typ'] = $myts->addSlashes($_REQUEST['src_auth_typ']);
    }
    if (isset($_REQUEST['src_auth_grp'])) {
        $sanerequest['src_auth_grp'] = $myts->addSlashes($_REQUEST['src_auth_grp']);
    }
    if (isset($_REQUEST['src_auth_un'])) {
        $sanerequest['src_auth_un'] = $myts->addSlashes($_REQUEST['src_auth_un']);
    }
    if (isset($_REQUEST['src_auth_pw'])) {
        $sanerequest['src_auth_pw'] = $myts->addSlashes($_REQUEST['src_auth_pw']);
    }
    if (isset($_REQUEST['o_server'])) {
        $sanerequest['o_server'] = $myts->addSlashes($_REQUEST['o_server']);
    }
    if (isset($_REQUEST['o_port'])) {
        $sanerequest['o_port'] = $myts->addSlashes($_REQUEST['o_port']);
    }
    if (isset($_REQUEST['o_mount'])) {
        $sanerequest['o_mount'] = $myts->addSlashes($_REQUEST['o_mount']);
    }
    if (isset($_REQUEST['codec'])) {
        $sanerequest['codec'] = $myts->addSlashes($_REQUEST['codec']);
    }
    if (isset($_REQUEST['description'])) {
        $sanerequest['description'] = $myts->addSlashes($_REQUEST['description']);
    }
    if (\Xmf\Request::hasVar('intronum', 'REQUEST')) {
        $sanerequest['intronum'] = \Xmf\Request::getInt('intronum', 0, 'REQUEST');
    }

    // Administrative Fields
    if (\Xmf\Request::hasVar('sequence', 'REQUEST')) {
        $sanerequest['sequence'] = \Xmf\Request::getInt('sequence', 0, 'REQUEST');
    }
    if (isset($_REQUEST['testua'])) {
        $sanerequest['testua'] = $myts->addSlashes($_REQUEST['testua']);
    }
    if (isset($_REQUEST['useragent'])) {
        $sanerequest['useragent'] = $myts->addSlashes($_REQUEST['useragent']);
    }

    return $sanerequest;
}

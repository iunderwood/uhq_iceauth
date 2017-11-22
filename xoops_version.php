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

// Modular Definitions

$modversion['version']       = 0.93;
$modversion['module_status'] = 'RC';
$modversion['release_date']  = '2013/06/22';
$modversion['name']          = _MI_UHQICEAUTH_NAME;
$modversion['description']   = _MI_UHQICEAUTH_DESC;
$modversion['author']        = 'Ian A. Underwood';
$modversion['credits']       = 'Underwood Headquarters';
$modversion['help']          = 'page=help';
$modversion['license']       = 'GNU GPL 2.0 or later';
$modversion['license_url']   = 'www.gnu.org/licenses/gpl-2.0.html';
$modversion['official']      = 0;
$modversion['image']         = 'assets/images/logoModule.png';
$modversion['dirname']       = basename(__DIR__);

// Better Help Section
// ------------------- Help files ------------------- //
$modversion['helpsection'] = [
    ['name' => _MI_UHQICEAUTH_OVERVIEW, 'link' => 'page=help'],
    ['name' => _MI_UHQICEAUTH_DISCLAIMER, 'link' => 'page=disclaimer'],
    ['name' => _MI_UHQICEAUTH_LICENSE, 'link' => 'page=license'],
    ['name' => _MI_UHQICEAUTH_SUPPORT, 'link' => 'page=support'],
    ['name' => _MI_UHQICEAUTH_MOUNT_POINTS, 'link' => 'page=mountpoints'],
];

// Administrative Defines
//$modversion['icons32']    = 'images/';
$modversion['modicons16'] = 'assets/images/icons/16';
$modversion['modicons32'] = 'assets/images/icons/32';

// About Section
$modversion['module_website_url']  = 'xoops.underwood-hq.org';
$modversion['module_website_name'] = 'XOOPS@UHQ';

// Minimums
$modversion['min_php']   = '5.5';
$modversion['min_xoops'] = '2.5.9';
$modversion['min_admin'] = '1.1';
$modversion['min_db']    = ['mysql' => '5.5'];

// Install Options
$modversion['onInstall'] = 'includes/oninstall.php';
$modversion['onUpdate']  = 'includes/onupdate.php';

// Database Items
$modversion['sqlfile']['mysql'] = 'sql/uhq_iceauth.sql';

$modversion['tables'][] = 'uhqiceauth_activemounts';
$modversion['tables'][] = 'uhqiceauth_authtrail';
$modversion['tables'][] = 'uhqiceauth_intromap';
$modversion['tables'][] = 'uhqiceauth_intros';
$modversion['tables'][] = 'uhqiceauth_ipbans';
$modversion['tables'][] = 'uhqiceauth_mountlog';
$modversion['tables'][] = 'uhqiceauth_servers';
$modversion['tables'][] = 'uhqiceauth_streampass';
$modversion['tables'][] = 'uhqiceauth_uabans';

// Configuration Items
$modversion['hasConfig']   = 1;
$modversion['system_menu'] = 1;

$modversion['config'][] = [
    'name'        => 'hdr_auth',
    'title'       => '_MI_UHQICEAUTH_MODCFG_HDRAUTH',
    'description' => '_MI_UHQICEAUTH_MODCFG_HDRAUTH_DESC',
    'formtype'    => 'textbox',
    'valuetype'   => 'text',
    'default'     => 'icecast-auth-user: 1'
];

$modversion['config'][] = [
    'name'        => 'hdr_msg',
    'title'       => '_MI_UHQICEAUTH_MODCFG_HDRMSG',
    'description' => '_MI_UHQICEAUTH_MODCFG_HDRMSG_DESC',
    'formtype'    => 'textbox',
    'valuetype'   => 'text',
    'default'     => 'icecast-auth-message: '
];

$modversion['config'][] = [
    'name'        => 'hdr_time',
    'title'       => '_MI_UHQICEAUTH_MODCFG_HDRTIME',
    'description' => '_MI_UHQICEAUTH_MODCFG_HDRTIME_DESC',
    'formtype'    => 'textbox',
    'valuetype'   => 'text',
    'default'     => 'icecast-auth-timelimit: '
];

$modversion['config'][] = [
    'name'        => 'undef_action',
    'title'       => '_MI_UHQICEAUTH_MODCFG_UNDEF',
    'description' => '_MI_UHQICEAUTH_MODCFG_UNDEF_DESC',
    'formtype'    => 'select',
    'valuetype'   => 'text',
    'options'     => [
        _MI_UHQICEAUTH_MODCFG_UNDEF_OPTA => 'A',
        _MI_UHQICEAUTH_MODCFG_UNDEF_OPTN => 'N',
        _MI_UHQICEAUTH_MODCFG_UNDEF_OPTD => 'D'
    ],
    'default'     => 'N'
];

$modversion['config'][] = [
    'name'        => 'undef_group',
    'title'       => '_MI_UHQICEAUTH_MODCFG_GROUPS',
    'description' => '_MI_UHQICEAUTH_MODCFG_GROUPS_DESC',
    'formtype'    => 'group_multi',
    'valuetype'   => 'array',
    'default'     => [XOOPS_GROUP_ADMIN]
];

$modversion['config'][] = [
    'name'        => 'undef_time',
    'title'       => '_MI_UHQICEAUTH_MODCFG_TIME',
    'description' => '_MI_UHQICEAUTH_MODCFG_TIME_DESC',
    'formtype'    => 'textbox',
    'valuetype'   => 'int',
    'default'     => 0
];

$modversion['config'][] = [
    'name'        => 'rdns',
    'title'       => '_MI_UHQICEAUTH_MODCFG_RDNS',
    'description' => '_MI_UHQICEAUTH_MODCFG_RDNS_DESC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 0
];

// Log Admin Updates?
$modversion['config'][] = [
    'name'        => 'logadminupdate',
    'title'       => '_MI_UHQICEAUTH_MODCFG_LOGADMIN',
    'description' => '_MI_UHQICEAUTH_MODCFG_LOGADMIN_DESC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 1
];

// Administrative Items
$modversion['hasAdmin']   = 1;
$modversion['adminindex'] = 'admin/index.php';
$modversion['adminmenu']  = 'admin/menu.php';

// Menu Items
$modversion['hasMain'] = 1;

// Templates
$modversion['templates'] = [
    ['file' => 'admin/uhqiceauth_intros.tpl', 'description' => _MI_UHQICEAUTH_TEMPLATE_INTROS],
    ['file' => 'admin/uhqiceauth_introplay.tpl', 'description' => _MI_UHQICEAUTH_TEMPLATE_INTROPLAY],
    ['file' => 'admin/uhqiceauth_mountpoints.tpl', 'description' => _MI_UHQICEAUTH_TEMPLATE_MOUNTPOINTS],
    ['file' => 'admin/uhqiceauth_authrec.tpl', 'description' => _MI_UHQICEAUTH_TEMPLATE_AUTH],
    ['file' => 'admin/uhqiceauth_acctrec.tpl', 'description' => _MI_UHQICEAUTH_TEMPLATE_ACCT],
    ['file' => 'admin/uhqiceauth_mountrec.tpl', 'description' => _MI_UHQICEAUTH_TEMPLATE_MOUNT],
    ['file' => 'admin/uhqiceauth_ua.tpl', 'description' => _MI_UHQICEAUTH_TEMPLATE_UA],
    ['file' => 'admin/uhqiceauth_authrec_detail.tpl', 'description' => _MI_UHQICEAUTH_TEMPLATE_AUTH_DET],
    ['file' => 'admin/uhqiceauth_streampass.tpl', 'description' => _MI_UHQICEAUTH_TEMPLATE_STREAMPASS],
    ['file' => 'admin/uhqiceauth_ipbans.tpl', 'description' => _MI_UHQICEAUTH_TEMPLATE_IPBANS],
    ['file' => 'admin/uhqiceauth_index.tpl', 'description' => _MI_UHQICEAUTH_TEMPLATE_ADMINDEX],
    ['file' => 'admin/uhqiceauth_mountpoints_del.tpl', 'description' => _MI_UHQICEAUTH_TEMPLATE_MOUNTPOINT_DEL]
];

// Blocks
$i                                       = 0;
$modversion['blocks'][$i]['file']        = 'uhqiceauth_blocks.php';
$modversion['blocks'][$i]['name']        = _MI_UHQICEAUTH_BLOCK_ACTIVEMOUNTS_NAME;
$modversion['blocks'][$i]['description'] = _MI_UHQICEAUTH_BLOCK_ACTIVEMOUNTS_DESC;
$modversion['blocks'][$i]['show_func']   = 'b_uhqiceauth_activemounts_show';
$modversion['blocks'][$i]['edit_func']   = 'b_uhqiceauth_activemounts_edit';
$modversion['blocks'][$i]['template']    = 'uhqiceauth_activemounts.tpl';
$modversion['blocks'][$i]['options']     = '';
$i++;
$modversion['blocks'][$i]['file']        = 'uhqiceauth_blocks.php';
$modversion['blocks'][$i]['name']        = _MI_UHQICEAUTH_BLOCK_STREAMPASS_NAME;
$modversion['blocks'][$i]['description'] = _MI_UHQICEAUTH_BLOCK_STREAMPASS_DESC;
$modversion['blocks'][$i]['show_func']   = 'b_uhqiceauth_streampass_show';
$modversion['blocks'][$i]['template']    = 'uhqiceauth_streampass.tpl';
$modversion['blocks'][$i]['options']     = '';

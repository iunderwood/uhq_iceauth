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

use Xmf\Module\Admin;
use XoopsModules\Uhqiceauth;
/** @var Uhqiceauth\Helper $helper */

$moduleDirName      = \basename(\dirname(__DIR__));
$moduleDirNameUpper = mb_strtoupper($moduleDirName);

$helper = Uhqiceauth\Helper::getInstance();
$helper->loadLanguage('common');
$helper->loadLanguage('feedback');

$pathIcon32 = Admin::menuIconPath('');
$pathModIcon32 = XOOPS_URL .   '/modules/' . $moduleDirName . '/assets/images/';
//if (is_object($helper->getModule()) && false !== $helper->getModule()->getInfo('modicons32')) {
//    $pathModIcon32 = $helper->url($helper->getModule()->getInfo('modicons32'));
//}

// Assign goodies for Admin Menu

global $adminObject;

// Assign goodies for Admin Menu

$adminmenu[] = [
    'title' => _MI_UHQICEAUTH_HOME,
    'link'  => 'admin/index.php',
    'icon'  => $pathIcon32 . 'home.png',
];

$adminmenu[] = [
    'title' => _MI_UHQICEAUTH_ADMENU_SUMMARY,
    'link'  => 'admin/summary.php',
    'icon'  => $pathModIcon32 . 'folder_blue.png',
];

$adminmenu[] = [
    'title' => _MI_UHQICEAUTH_ADMENU_MOUNTS,
    'link'  => 'admin/mountpoints.php',
    'icon'  => $pathModIcon32 . 'menu_srv.png',
];

$adminmenu[] = [
    'title' => _MI_UHQICEAUTH_ADMENU_INTRO,
    'link'  => 'admin/intros.php',
    'icon'  => $pathModIcon32 . 'menu_intro.png',
];

$adminmenu[] = [
    'title' => _MI_UHQICEAUTH_ADMENU_AUTHREC,
    'link'  => 'admin/authrec.php',
    'icon'  => $pathModIcon32 . 'menu_auth.png',
];

$adminmenu[] = [
    'title' => _MI_UHQICEAUTH_ADMENU_ACCTREC,
    'link'  => 'admin/acctrec.php',
    'icon'  => $pathModIcon32 . 'menu_acct.png',
];

$adminmenu[] = [
    'title' => _MI_UHQICEAUTH_ADMENU_MOUNTREC,
    'link'  => 'admin/mountrec.php',
    'icon'  => $pathModIcon32 . 'menu_mount.png',
];

$adminmenu[] = [
    'title' => _MI_UHQICEAUTH_ADMENU_STREAMPASS,
    'link'  => 'admin/streampass.php',
    'icon'  => $pathModIcon32 . 'menu_streampass.png',
];

$adminmenu[] = [
    'title' => _MI_UHQICEAUTH_ADMENU_UA,
    'link'  => 'admin/ua.php',
    'icon'  => $pathModIcon32 . 'menu_ua.png',
];

$adminmenu[] = [
    'title' => _MI_UHQICEAUTH_ADMENU_IPBAN,
    'link'  => 'admin/ipbans.php',
    'icon'  => $pathModIcon32 . 'menu_ipban.png',
];

// Blocks Admin
$adminmenu[] = [
    'title' => constant('CO_' . $moduleDirNameUpper . '_' . 'BLOCKS'),
    'link' => 'admin/blocksadmin.php',
    'icon' => $pathIcon32 . '/block.png',
];

if (is_object($helper->getModule()) && $helper->getConfig('displayDeveloperTools')) {
    $adminmenu[] = [
        'title' => constant('CO_' . $moduleDirNameUpper . '_' . 'ADMENU_MIGRATE'),
        'link' => 'admin/migrate.php',
        'icon' => $pathIcon32 . '/database_go.png',
    ];
}

$adminmenu[] = [
    'title' => _MI_UHQICEAUTH_ABOUT,
    'link'  => 'admin/about.php',
    'icon'  => $pathIcon32 .  'about.png',
];

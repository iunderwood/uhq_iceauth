<?php

// Module Information

define('_MI_UHQICEAUTH_NAME', 'UHQ_IceAuth');
define('_MI_UHQICEAUTH_DESC', 'This module allows IceCast 2.3.0 or higher to authenticate against your XOOPS database.');

// Installer Conditions

define('_MI_UHQICEAUTH_INSTALL_GOOD', 'Anonymous access granted successfully.');
define('_MI_UHQICEAUTH_INSTALL_FAULT', 'Anonymous access NOT granted.  You will need to do this manually.');

define('_MI_UHQICEAUTH_WEIGHT_OK', 'Module weight set to 0 successfully.');
define('_MI_UHQICEAUTH_WEIGHT_NOK', 'Module weight not set to 0.  You will need to do this manually.');

// Configuration Options

define('_MI_UHQICEAUTH_MODCFG_HDRAUTH', 'Auth Header');
define('_MI_UHQICEAUTH_MODCFG_HDRAUTH_DESC', 'Header which Icecast recognizes as successfully authenticated.');

define('_MI_UHQICEAUTH_MODCFG_HDRMSG', 'Message Header');
define('_MI_UHQICEAUTH_MODCFG_HDRMSG_DESC', 'Header which carries error reasons, or message information.');

define('_MI_UHQICEAUTH_MODCFG_HDRTIME', 'Time-Limit Header');
define('_MI_UHQICEAUTH_MODCFG_HDRTIME_DESC', 'Header which carries time limit in seconds, if used.');

define('_MI_UHQICEAUTH_MODCFG_UNDEF', 'Undefined Mount Handling');
define('_MI_UHQICEAUTH_MODCFG_UNDEF_DESC', 'Option which defines what to do if authentication is requested for an unknown mount.');
define('_MI_UHQICEAUTH_MODCFG_UNDEF_OPTA', 'Always Allow');
define('_MI_UHQICEAUTH_MODCFG_UNDEF_OPTN', 'Never Allow');
define('_MI_UHQICEAUTH_MODCFG_UNDEF_OPTD', 'Check UN/PW');

define('_MI_UHQICEAUTH_MODCFG_GROUPS', 'Default Groups');
define('_MI_UHQICEAUTH_MODCFG_GROUPS_DESC', 'Groups that can hear an undefined mount, if allowed.');

define('_MI_UHQICEAUTH_MODCFG_TIME', 'Default Time Limit');
define('_MI_UHQICEAUTH_MODCFG_TIME_DESC', 'Time limit in seconds for an undefined mount, if allowed.');

define('_MI_UHQICEAUTH_MODCFG_RDNS', 'Enable Reverse DNS');
define('_MI_UHQICEAUTH_MODCFG_RDNS_DESC', 'Utilize reverse DNS within client logs.  May impact performance.');

define('_MI_UHQICEAUTH_MODCFG_LOGADMIN', 'Log Admin Updates');
define('_MI_UHQICEAUTH_MODCFG_LOGADMIN_DESC', 'Log admin updates from a source.  Almost always metadata for non-Ogg streams.');

// Template Descriptions

define('_MI_UHQICEAUTH_TEMPLATE_INTROS', 'Admin: Intro Management Page');
define('_MI_UHQICEAUTH_TEMPLATE_INTROPLAY', 'Admin: Intro Play Popup');
define('_MI_UHQICEAUTH_TEMPLATE_MOUNTPOINTS', 'Admin: Mount Points Tab');
define('_MI_UHQICEAUTH_TEMPLATE_AUTH', 'Admin: Auth Records Tab');
define('_MI_UHQICEAUTH_TEMPLATE_ACCT', 'Admin: Accounting Records Tab');
define('_MI_UHQICEAUTH_TEMPLATE_MOUNT', 'Admin: Mount Records Tab');
define('_MI_UHQICEAUTH_TEMPLATE_STREAMPASS', 'Admin: Stream Login Tab');
define('_MI_UHQICEAUTH_TEMPLATE_UA', 'Admin: User Agent Bans Tab');
define('_MI_UHQICEAUTH_TEMPLATE_IPBANS', 'Admin: IP Bans Tab');
define('_MI_UHQICEAUTH_TEMPLATE_AUTH_DET', 'Admin: Auth Record Detail');
define('_MI_UHQICEAUTH_TEMPLATE_ADMINDEX', 'Admin: Index / Summary');
define('_MI_UHQICEAUTH_TEMPLATE_MOUNTPOINT_DEL', 'Admin: Verify Mount Point Deletion');

// Block Information

define('_MI_UHQICEAUTH_BLOCK_ACTIVEMOUNTS_NAME', 'Active Mountpoints');
define('_MI_UHQICEAUTH_BLOCK_ACTIVEMOUNTS_DESC', 'A list of all active known mountpoints reported.');

define('_MI_UHQICEAUTH_BLOCK_STREAMPASS_NAME', 'Stream Login');
define('_MI_UHQICEAUTH_BLOCK_STREAMPASS_DESC', "Block which displays the user's random stream password.");

// Admin Menus

define('_MI_UHQICEAUTH_ADMENU_SUMMARY', 'Summary');
define('_MI_UHQICEAUTH_ADMENU_MOUNTS', 'Mount Points');
define('_MI_UHQICEAUTH_ADMENU_INTRO', 'Intro Files');
define('_MI_UHQICEAUTH_ADMENU_AUTHREC', 'Auth Records');
define('_MI_UHQICEAUTH_ADMENU_ACCTREC', 'Accounting Records');
define('_MI_UHQICEAUTH_ADMENU_MOUNTREC', 'Mount Records');
define('_MI_UHQICEAUTH_ADMENU_STREAMPASS', 'Stream Logins');
define('_MI_UHQICEAUTH_ADMENU_UA', 'UA Bans');
define('_MI_UHQICEAUTH_ADMENU_IPBAN', 'IP Bans');

define('_MI_UHQICEAUTH_ADMENU_HOME', 'Home');
define('_MI_UHQICEAUTH_ADMENU_ABOUT', 'About');
define('_MI_UHQICEAUTH_ADMENU_INDEX', 'GeoLocator');

//0.93
//Help
define('_MI_UHQICEAUTH_DIRNAME', basename(dirname(__DIR__, 2)));
define('_MI_UHQICEAUTH_HELP_HEADER', __DIR__ . '/help/helpheader.tpl');
define('_MI_UHQICEAUTH_BACK_2_ADMIN', 'Back to Administration of ');
define('_MI_UHQICEAUTH_OVERVIEW', 'Overview');

//define('_MI_UHQICEAUTH_HELP_DIR', __DIR__);

//help multi-page
define('_MI_UHQICEAUTH_DISCLAIMER', 'Disclaimer');
define('_MI_UHQICEAUTH_LICENSE', 'License');
define('_MI_UHQICEAUTH_SUPPORT', 'Support');
define('_MI_UHQICEAUTH_MOUNT_POINTS', 'Mount Points');

define('_MI_UHQICEAUTH_HOME', 'Home');
define('_MI_UHQICEAUTH_ABOUT', 'About');

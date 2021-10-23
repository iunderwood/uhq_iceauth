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
use Xmf\Request;
use XoopsModules\Uhqiceauth\{
    Helper
};
/** @var Admin $adminObject */
/** @var Helper $helper */

require_once __DIR__ . '/admin_header.php';

$helper      = Helper::getInstance();

if (!isset($xoopsTpl)) {
    $xoopsTpl = new \XoopsTpl();
}
$xoopsTpl->caching = 0;

require_once $helper->path('includes/sanity.php');
require_once $helper->path('admin/functions.inc.php');
require_once $helper->path('includes/auth.inc.php');

require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';

// Assign default operator
if (Request::hasVar('op', 'REQUEST')) {
    $op = $_REQUEST['op'];
} else {
    $op = 'none';
}

$sane_REQUEST = uhqiceauth_dosanity();

function uhqiceauth_uaform($title)
{
    $form = new \XoopsThemeForm($title, 'uaform', 'ua.php', 'post', true);

    $form->addElement(new \XoopsFormText(_AM_UHQICEAUTH_FORM_USERAGENT, 'useragent', 40, 50));

    $form->addElement(new \XoopsFormHidden('op', 'insert'));
    $form->addElement(new \XoopsFormHidden('verify', '1'));

    $form->addElement(new \XoopsFormButton('', 'post', $title, 'submit'));
    $form->display();

    echo "<br><br><a href='ua.php'>" . _AM_UHQICEAUTH_RETUA . '</a>';
}

function uhqiceauth_ualist()
{
    global $xoopsDB;

    $query = 'SELECT * FROM ' . $xoopsDB->prefix('uhqiceauth_uabans') . ' ORDER BY useragent';

    $result = $xoopsDB->queryF($query);
    if (false === $result) {
        // Return nothing on a DB error.
        return null;
    }
    $i    = 0;
    $data = [];
    while (false !== ($row = $xoopsDB->fetchArray($result))) {
        $data['list'][$i] = $row;
        $i++;
    }

    return $data;
}

// Return the last 10 log entries where a UA ban has been observed
function uhqiceauth_uaauthbans($start, $limit = 10)
{
    global $xoopsDB;

    $query = 'SELECT * FROM ' . $xoopsDB->prefix('uhqiceauth_authlog');
    $query .= ' WHERE authinfo = 301 ORDER BY logtime DESC LIMIT ' . $limit;

    $result = $xoopsDB->queryF($query);
    if (false === $result) {
        // Return nothing on a DB error.
        return null;
    }
    $i    = 0;
    $data = [];
    while (false !== ($row = $xoopsDB->fetchArray($result))) {
        $data['banlog'][$i] = $row;
        $i++;
    }

    return $data;
}

switch ($op) {
    case 'insert':
        if (Request::hasVar('verify', 'REQUEST')) {
            $query  = 'INSERT INTO ' . $xoopsDB->prefix('uhqiceauth_uabans');
            $query  .= " SET useragent = '" . $sane_REQUEST['useragent'] . "'";
            $result = $xoopsDB->queryF($query);

            if (false === $result) {
                redirect_header('ua.php', 10, _AM_UHQICEAUTH_SQLERR . $query . '<br>' . $xoopsDB->error());
                break;
            }
            redirect_header('ua.php', 10, _AM_UHQICEAUTH_ADDED . $sane_REQUEST['useragent'] . _AM_UHQICEAUTH_SUCCESSFULLY);
            break;
        }
        xoops_cp_header();
        $adminObject = Admin::getInstance();
        $adminObject->displayNavigation(basename(__FILE__));
        uhqiceauth_uaform(_AM_UHQICEAUTH_ADDUA);
        require_once __DIR__ . '/admin_footer.php';

        break;
    case 'delete':
        // Verify we have minimum parameters
        if ($sane_REQUEST['sequence']) {
            // Delete Record
            $query  = 'DELETE FROM ' . $xoopsDB->prefix('uhqiceauth_uabans') . " WHERE sequence = '" . $sane_REQUEST['sequence'] . "'";
            $result = $xoopsDB->queryF($query);
            if (false === $result) {
                $headerinfo = _AM_UHQICEAUTH_SQLERR . $query . '<br>' . $xoopsDB->error() . '<br>';
            } else {
                $headerinfo = _AM_UHQICEAUTH_DELETED . $sane_REQUEST['sequence'] . _AM_UHQICEAUTH_SUCCESSFULLY . '<br><br>';
            }
            redirect_header('ua.php', 10, $headerinfo);
        } else {
            redirect_header('ua.php', 10, _AM_UHQICEAUTH_PARAMERR);
        }
        break;
    case 'testua':
        $result = uhqiceauth_ua_verify($sane_REQUEST['testua']);
        if ($result) {
            redirect_header('ua.php', 30, $sane_REQUEST['testua'] . _AM_UHQICEAUTH_UA_PASS);
        } else {
            redirect_header('ua.php', 30, $sane_REQUEST['testua'] . _AM_UHQICEAUTH_UA_FAIL);
        }
        break;
    default:
        xoops_cp_header();
        $adminObject = Admin::getInstance();
        $adminObject->displayNavigation(basename(__FILE__));

        // See if we have anything first.
        $data['uacount'] = uhqiceauth_summarycount('UA');
        if ($data['uacount']) {
            $data['uadata'] = uhqiceauth_ualist();
        }

        // Assign & Render Template
        $xoopsTpl->assign('data', $data);
        $xoopsTpl->display('db:admin/uhqiceauth_ua.tpl');

        require_once __DIR__ . '/admin_footer.php';
        break;
}

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

include_once __DIR__ . '/admin_header.php';

require_once XOOPS_ROOT_PATH . "/modules/uhq_iceauth/includes/sanity.php";
require_once XOOPS_ROOT_PATH . "/modules/uhq_iceauth/admin/functions.inc.php";
require_once XOOPS_ROOT_PATH . "/class/xoopsformloader.php";


if (!isset($xoopsTpl)) {
	$xoopsTpl = new XoopsTpl();
}
$xoopsTpl->caching=0;

// The next few functions hold our form informations.

function uhqiceauth_srvform($title, $formdata, $op) {

	// Insert some detaults if the data is null
	if ($formdata == null) {
		$formdata['timelimit'] = 0;			// No time limits by default
		$formdata['lst_auth_typ'] = "D";	// Verify Listener against DB
		$formdata['lst_auth_grp'] = "2";	// Listener must be registered
		$formdata['src_auth_typ'] = "S";	// Verify Source by UN/PW
		$formdata['src_auth_grp'] = "1";	// Source must be webmaster if unspecified.
		$formdata['src_auth_un'] = "source";	// Default source username
	}

	$form = new XoopsThemeForm($title,'srvform','mountpoints.php', 'post', true);

	$form->addElement(new XoopsFormText(_AM_UHQICEAUTH_FORM_IPFQDN, "server",40,50,$formdata['server']) );
	$form->addElement(new XoopsFormText(_AM_UHQICEAUTH_FORM_PORT, "port",5,5,$formdata['port']) );
	$form->addElement(new XoopsFormText(_AM_UHQICEAUTH_FORM_MOUNT, "mount",20,20,$formdata['mount']) );
	$form->addElement(new XoopsFormText(_AM_UHQICEAUTH_FORM_TIMEL,"timelimit",5,5,$formdata['timelimit']) );

	$form_l = new XoopsFormSelect(_AM_UHQICEAUTH_FORM_LAUTH, "lst_auth_typ", $formdata['lst_auth_typ'],1);
	$form_l->addOption('D',_AM_UHQICEAUTH_FORM_AUTHD);
	$form_l->addOption('A',_AM_UHQICEAUTH_FORM_AUTHA);
	$form->addElement($form_l);

	$form->addElement(new XoopsFormSelectGroup(_AM_UHQICEAUTH_FORM_LGRP,"lst_auth_grp",true,$formdata['lst_auth_grp'],3,true) );

	$form_s = new XoopsFormSelect(_AM_UHQICEAUTH_FORM_SAUTH, "src_auth_typ",$formdata['src_auth_typ'],1);
	$form_s->addOption('D',_AM_UHQICEAUTH_FORM_AUTHD);
	$form_s->addOption('S',_AM_UHQICEAUTH_FORM_AUTHS);
	$form_s->addOption('N',_AM_UHQICEAUTH_FORM_AUTHN);
	$form->addElement($form_s);

	$form->addElement(new XoopsFormSelectGroup(_AM_UHQICEAUTH_FORM_SGRP,"src_auth_grp",false,$formdata['src_auth_grp'],3,true) );

	$form->addElement(new XoopsFormText(_AM_UHQICEAUTH_FORM_SRCUN, "src_auth_un",20,20,$formdata['src_auth_un']) );
	$form->addElement(new XoopsFormText(_AM_UHQICEAUTH_FORM_SRCPW, "src_auth_pw",20,20,$formdata['src_auth_pw']) );

	if ($op == "edit") {
		// Set query to get current info, in case we change the IP/Port/Mountpoint
		$form->addElement(new XoopsFormHidden("o_server", $formdata['server']) );
		$form->addElement(new XoopsFormHidden("o_port", $formdata['port']) );
		$form->addElement(new XoopsFormHidden("o_mount", $formdata['mount']) );
		$form->addElement(new XoopsFormHidden("op", "edit") );
	} else {
		$form->addElement(new XoopsFormHidden("op", "insert") );
	}

	$form->addElement(new XoopsFormHidden("verify", "1") );

	$form->addElement(new XoopsFormButton("",'post',$title,'submit') );

	$form->display();

	echo "<br/><br/><a href='mountpoints.php'>"._AM_UHQICEAUTH_RETSERVERS."</a>";

}

function uhqiceauth_srvdelform($mountdata) {

	$form = new XoopsThemeForm(_AM_UHQICEAUTH_FORM_VDEL."<br/>".$mountdata['server'].":".$mountdata['port'].$mountdata['mount'],'srvdelform','mountpoints.php', POST);

	$form_c = new XoopsFormCheckbox(_AM_UHQICEAUTH_FORM_DELHD,'delhd','none');
	$form_c -> addOption(1,_AM_UHQICEAUTH_FORM_RD);

	$form->addElement($form_c);

	$form->addElement(new XoopsFormHidden("server", $mountdata['server']) );
	$form->addElement(new XoopsFormHidden("port", $mountdata['port']) );
	$form->addElement(new XoopsFormHidden("mount", $mountdata['mount']) );
	$form->addElement(new XoopsFormHidden("op","delete") );
	$form->addElement(new XoopsFormHidden("verify","1") );
	$form->addElement(new XoopsFormButton("",'post',_AM_UHQICEAUTH_FORM_DELBUTTON,'submit') );
	$form->display();
}

function uhqiceauth_srvintroform($title) {
	global $xoopsDB;
	global $sane_REQUEST;

	$form = new XoopsThemeForm($title." - ".$sane_REQUEST['server'].":".$sane_REQUEST['port'].$sane_REQUEST['mount'],'intromapform','mountpoints.php','post', true);

	// Selection of introduction

	$form_c = new XoopsFormSelect(_AM_UHQICEAUTH_INTROS_FILE,"intronum");

	$query = "SELECT * FROM ".$xoopsDB->prefix("uhqiceauth_intros");
	$result = $xoopsDB->queryF($query);
	if ($result == false) {
		// Do not add options.
	} else {
		// Load option from DB
		while ($row = $xoopsDB->fetchArray($result) ) {
			$form_c->addOption($row['intronum'],$row['filename']." :: ".$row['description']);
		}
	}
	$form->addElement($form_c);

	$form->addElement(new XoopsFormText(_AM_UHQICEAUTH_INTROS_SEQ,'sequence',1,1,'10') );

	$form->addElement(new XoopsFormHidden("server", $sane_REQUEST['server']) );
	$form->addElement(new XoopsFormHidden("port", $sane_REQUEST['port']) );
	$form->addElement(new XoopsFormHidden("mount", $sane_REQUEST['mount']) );
	$form->addElement(new XoopsFormHidden("op","addintro") );

	$form->addElement(new XoopsFormButton("",'post',$title,'submit') );


	$form->display();
}

// Grab operator if we have it

if ( isset($_REQUEST['op']) ) {
	$op = $_REQUEST['op'];
} else {
	$op = "none";
}

$sane_REQUEST = uhqiceauth_dosanity();

// Go where we need to.

switch ($op) {
	case "insert" :
		if ( isset($_REQUEST['verify']) ) {

			$lst_auth_grp = implode("|",$sane_REQUEST['lst_auth_grp']);
			$src_auth_grp = implode("|",$sane_REQUEST['src_auth_grp']);

			$query = "INSERT INTO ".$xoopsDB->prefix('uhqiceauth_servers');

			$query .= " SET server = '".$sane_REQUEST['server']."', ";
			$query .= "port = '".$sane_REQUEST['port']."', ";
			$query .= "mount = '".$sane_REQUEST['mount']."', ";
			$query .= "timelimit = '".$sane_REQUEST['timelimit']."', ";
			$query .= "lst_auth_typ = '".$sane_REQUEST['lst_auth_typ']."', ";
			$query .= "lst_auth_grp = '".$lst_auth_grp."', ";
			$query .= "src_auth_typ = '".$sane_REQUEST['src_auth_typ']."', ";
			$query .= "src_auth_grp = '".$src_auth_grp."', ";
			$query .= "src_auth_un = '".$sane_REQUEST['src_auth_un']."', ";
			$query .= "src_auth_pw = '".$sane_REQUEST['src_auth_pw']."'";

			$result = $xoopsDB->queryF($query);
			if ($result == false) {
				redirect_header("mountpoints.php",10,_AM_UHQICEAUTH_SQLERR.$query."<br/>".$xoopsDB->error() );
				break;
			} else {
				redirect_header("mountpoints.php",10,_AM_UHQICEAUTH_ADDED.$sane_REQUEST['server'].":".$sane_REQUEST['port'].$sane_REQUEST['mount']._AM_UHQICEAUTH_SUCCESSFULLY);
				break;
			}
		} else {
			xoops_cp_header();
			$mainAdmin = new ModuleAdmin();
			echo $mainAdmin->addNavigation('mountpoints.php');
			uhqiceauth_srvform(_AM_UHQICEAUTH_ADDSERVER);
			include_once __DIR__ . '/admin_footer.php';
		}
		break;
	case "edit" :
		// Verify we have minimum parameters
		if ( $sane_REQUEST['server'] && $sane_REQUEST['port'] && $sane_REQUEST['mount'] ) {

			// Load Record
			$query = "SELECT * FROM ".$xoopsDB->prefix('uhqiceauth_servers')." WHERE server ='";
			$query .= $sane_REQUEST['server']."' AND port = '".$sane_REQUEST['port']."' AND mount = '";
			$query .= $sane_REQUEST['mount']."';";
			$result = $xoopsDB->queryF($query);
			if ($result == false) {
				redirect_header("mountpoints.php",10,_AM_UHQICEAUTH_SQLERR.$query."<br/>".$xoopsDB->error() );
				break;
			}

			if ( isset($_REQUEST['verify']) ) {

				// Modify main record

				$lst_auth_grp = implode("|",$sane_REQUEST['lst_auth_grp']);
				$src_auth_grp = implode("|",$sane_REQUEST['src_auth_grp']);

				$query = "UPDATE ".$xoopsDB->prefix('uhqiceauth_servers');

				$query .= " SET server = '".$sane_REQUEST['server']."', ";
				$query .= "port = '".$sane_REQUEST['port']."', ";
				$query .= "mount = '".$sane_REQUEST['mount']."', ";
				$query .= "timelimit = '".$sane_REQUEST['timelimit']."', ";
				$query .= "lst_auth_typ = '".$sane_REQUEST['lst_auth_typ']."', ";
				$query .= "lst_auth_grp = '".$lst_auth_grp."', ";
				$query .= "src_auth_typ = '".$sane_REQUEST['src_auth_typ']."', ";
				$query .= "src_auth_grp = '".$src_auth_grp."', ";
				$query .= "src_auth_un = '".$sane_REQUEST['src_auth_un']."', ";
				$query .= "src_auth_pw = '".$sane_REQUEST['src_auth_pw']."'";

				$query .= " WHERE server ='";
				$query .= $sane_REQUEST['o_server']."' AND port = '".$sane_REQUEST['o_port']."' AND mount = '";
				$query .= $sane_REQUEST['o_mount']."';";

				$result = $xoopsDB->queryF($query);
				if ($result == false) {
					redirect_header("mountpoints.php",10,_AM_UHQICEAUTH_SQLERR.$query."<br/>".$xoopsDB->error() );
					break;
				} else {
					$headerinfo = _AM_UHQICEAUTH_CHANGED.$sane_REQUEST['server'].":".$sane_REQUEST['port'].$sane_REQUEST['mount']._AM_UHQICEAUTH_SUCCESSFULLY;
				}

				// Update any intro maps

				$query = "UPDATE ".$xoopsDB->prefix('uhqiceauth_intromap');
				$query .= " SET server='".$sane_REQUEST['server']."', ";
				$query .= " port='".$sane_REQUEST['port']."', ";
				$query .= " mount='".$sane_REQUEST['mount']."'";
				$query .= " WHERE server='";
				$query .= $sane_REQUEST['o_server']."' AND port = '".$sane_REQUEST['0Oport']."' AND mount = '";
				$query .= $sane_REQUEST['o_mount']."';";
				$result = $xoopsDB->queryF($query);
				if ($result == false) {
					$headerinfo .= "<br\>"._AM_UHQICEAUTH_SQLERR.$query."<br/>".$xoopsDB->error()."<br/>";
					$delok = 0;
				} else {
					$headerinfo .- "<br\>"._AM_UHQICEAUTH_CHANGEMAP.$sane_REQUEST['server'].":".$sane_REQUEST['port'].$sane_REQUEST['mount']._AM_UHQICEAUTH_SUCCESSFULLY."<br/>";
				}
				redirect_header("mountpoints.php",10,$headerinfo);
				break;
			} else {
				$row = $xoopsDB->fetchArray($result);
				$row['lst_auth_grp'] = explode("|",$row['lst_auth_grp']);
				$row['src_auth_grp'] = explode("|",$row['src_auth_grp']);

				xoops_cp_header();
				$mainAdmin = new ModuleAdmin();
				echo $mainAdmin->addNavigation('mountpoints.php');

				uhqiceauth_srvform(_AM_UHQICEAUTH_EDITSERVER,$row,"edit");
				include_once __DIR__ . '/admin_footer.php';

			}
		} else {
			redirect_header("mountpoints.php",10,_AM_UHQICEAUTH_PARAMERR);
		}
		break;
	case "addintro" :
		// Look for minimum parameters
		if ( $sane_REQUEST['server'] && $sane_REQUEST['port'] && $sane_REQUEST['mount'] ) {
			if ( isset($sane_REQUEST['intronum']) && isset ($sane_REQUEST['sequence']) ) {
				// Update DB
				$query = "INSERT INTO ".$xoopsDB->prefix('uhqiceauth_intromap');
				$query .= " SET intronum='".$sane_REQUEST['intronum']."', ";
				$query .= " server='".$sane_REQUEST['server']."', ";
				$query .= " port='".$sane_REQUEST['port']."', ";
				$query .= " mount='".$sane_REQUEST['mount']."', ";
				$query .= " sequence='".$sane_REQUEST['sequence']."'";

				$result = $xoopsDB->queryF($query);
				if ($result == false) {
					redirect_header("mountpoints.php",10,_AM_UHQICEAUTH_FORM_SQLERR.$query."<br/>".$xoopsDB->error() );
				} else {
					redirect_header("mountpoints.php",10,_AM_UHQICEAUTH_INTROS_MAPADD);
				}
			} else {
				xoops_cp_header();
				$mainAdmin = new ModuleAdmin();
				echo $mainAdmin->addNavigation('mountpoints.php');

				uhqiceauth_srvintroform(_AM_UHQICEAUTH_INTROS_MAPFORM);

				include_once __DIR__ . '/admin_footer.php';
			}
		} else {
			redirect_header("mountpoints.php",10,_AM_UHQICEAUTH_PARAMERR);
		}
		break;
	case "delintro" :
		// Look for minimum parameters
		if ( $sane_REQUEST['server'] && $sane_REQUEST['port'] && $sane_REQUEST['mount'] && $sane_REQUEST['intronum']) {
			// Remove mapping
			$query = "DELETE FROM ".$xoopsDB->prefix('uhqiceauth_intromap');
			$query .= " WHERE intronum='".$sane_REQUEST['intronum']."'";
			$query .= " AND server='".$sane_REQUEST['server']."'";
			$query .= " AND port='".$sane_REQUEST['port']."'";
			$query .= " AND mount='".$sane_REQUEST['mount']."'";
			$result = $xoopsDB->queryF($query);
			if ($result == false) {
				redirect_header("mountpoints.php",10,_AM_UHQICEAUTH_FORM_SQLERR."<br/>".$xoopsDB->error() );
			} else {
				redirect_header("mountpoints.php",10,_AM_UHQICEAUTH_INTROS_MAPDEL);
			}
		} else {
			redirect_header("mountpoints.php",10,_AM_UHQICEAUTH_PARAMERR);
		}
		break;
	case "delete" :
		// Verify we have minimum parameters
		if ( $sane_REQUEST['server'] && $sane_REQUEST['port'] && $sane_REQUEST['mount'] ) {

			// Load Record
			$query = "SELECT * FROM ".$xoopsDB->prefix('uhqiceauth_servers')." WHERE server = '";
			$query .= $sane_REQUEST['server']."' AND port = '".$sane_REQUEST['port']."' AND mount = '";
			$query .= $sane_REQUEST['mount']."';";
			$result = $xoopsDB->queryF($query);
			if ($result == false) {
				redirect_header("mountpoints.php",10,_AM_UHQICEAUTH_SQLERR.$query."<br/>".$xoopsDB->error());
				break;
			}

			if ( isset($_REQUEST['verify']) ) {
				// Delete Record
				$query = "DELETE FROM ".$xoopsDB->prefix('uhqiceauth_servers')." WHERE server = '";
				$query .= $sane_REQUEST['server']."' AND port = '".$sane_REQUEST['port']."' AND mount = '";
				$query .= $sane_REQUEST['mount']."';";
				$result = $xoopsDB->queryF($query);
				if ($result == false) {
					$headerinfo = _AM_UHQICEAUTH_SQLERR.$query."<br/>".$xoopsDB->error()."<br/>";
					$delok = 0;
				} else {
					$headerinfo = _AM_UHQICEAUTH_DELETED.$sane_REQUEST['server'].":".$sane_REQUEST['port'].$sane_REQUEST['mount']._AM_UHQICEAUTH_SUCCESSFULLY."<br/><br/>";
					$delok = 1;
				}

				// Remove from intro map.
				$query = "DELETE FROM ".$xoopsDB->prefix('uhqiceauth_intromap')." WHERE server ='";
				$query .= $sane_REQUEST['server']."' AND port = '".$sane_REQUEST['port']."' AND mount = '";
				$query .= $sane_REQUEST['mount']."';";
				$result = $xoopsDB->queryF($query);
				if ($result == false) {
					$headerinfo .= _AM_UHQICEAUTH_SQLERR.$query."<br/>".$xoopsDB->error()."<br/>";
					$delok = 0;
				} else {
					$headerinfo .= _AM_UHQICEAUTH_DELETEDMAP.$sane_REQUEST['server'].":".$sane_REQUEST['port'].$sane_REQUEST['mount']._AM_UHQICEAUTH_SUCCESSFULLY."<br/><br/>";
					$delok = 1;
				}

				// Delete historic data if tagged:

				// Delete from authtrail
				if ( isset($_REQUEST['delhd']) && ($delok == 1) ) {
					$query = "DELETE FROM ".$xoopsDB->prefix('uhqiceauth_authtrail')." WHERE server ='";
					$query .= $sane_REQUEST['server']."' AND port = '".$sane_REQUEST['port']."' AND mount = '";
					$query .= $sane_REQUEST['mount']."';";
					$result = $xoopsDB->queryF($query);
					if ($result == false) {
						$headerinfo .= _AM_UHQICEAUTH_SQLERR.$query."<br/>";
						$delok = 0;
					} else {
						$headerinfo .= _AM_UHQICEAUTH_DELETED._AM_UHQICEAUTH_AUTH_PLU._AM_UHQICEAUTH_SUCCESSFULLY."<br/>";
						$delok = 1;
					}
				}

				// Delete from mountlog
				if ( isset($_REQUEST['delhd']) && ($delok == 1) ) {
					$query = "DELETE FROM ".$xoopsDB->prefix('uhqiceauth_mountlog')." WHERE server ='";
					$query .= $sane_REQUEST['server']."' AND port = '".$sane_REQUEST['port']."' AND mount = '";
					$query .= $sane_REQUEST['mount']."';";
					$result = $xoopsDB->queryF($query);
					if ($result == false) {
						$headerinfo .= _AM_UHQICEAUTH_SQLERR.$query."<br/>".$xoopsDB->error() ."<br/>";
						$delok = 0;
					} else {
						$headerinfo .= _AM_UHQICEAUTH_DELETED._AM_UHQICEAUTH_MOUNTS_PLU._AM_UHQICEAUTH_SUCCESSFULLY."<br/>";
						$delok = 1;
					}
				}

				// Delete from activemounts
				if ( isset($_REQUEST['delhd']) && ($delok == 1) ) {
					$query = "DELETE FROM ".$xoopsDB->prefix('uhqiceauth_activemounts')." WHERE server ='";
					$query .= $sane_REQUEST['server']."' AND port = '".$sane_REQUEST['port']."' AND mount = '";
					$query .= $sane_REQUEST['mount']."';";
					if ($result == false) {
						$headerinfo .= _AM_UHQICEAUTH_SQLERR.$query."<br/>".$xoopsDB->error()."<br/>";
						$delok = 0;
					} else {
						$headerinfo .= "<br/>"._AM_UHQICEAUTH_REMDB._AM_UHQICEAUTH_SUCCESSFULLY;
						$delok = 1;
					}
				}
				redirect_header("mountpoints.php",10,$headerinfo);
			} else {
				xoops_cp_header();
				$mainAdmin = new ModuleAdmin();
				echo $mainAdmin->addNavigation('mountpoints.php');

				// Display Record
				$row = $xoopsDB->fetchArray($result);

				$data['mount'] = $row;

				$data['aucount'] = uhqiceauth_summarycount("AU",$row);	// Authentication Summary
				$data['mlcount'] = uhqiceauth_summarycount("ML",$row);	// Mount Log Summary

				// Render Template

				$xoopsTpl->assign('data',$data);
				$xoopsTpl->display("db:admin/uhqiceauth_mountpoints_del.html");

				// Print Delete Form
				uhqiceauth_srvdelform($row);
				include_once __DIR__ . '/admin_footer.php';
			}
		} else {
			redirect_header("mountpoints.php",10,_AM_UHQICEAUTH_PARAMERR);
		}
		break;
	case "reset" :
		// Verify we have minimum parameters
		if ( $sane_REQUEST['server'] && $sane_REQUEST['port'] && $sane_REQUEST['mount'] ) {
			$query = "UPDATE ".$xoopsDB->prefix('uhqiceauth_servers')." SET hits_pass = 0, hits_fail = 0, src_hits_pass = 0, src_hits_fail = 0 ";
			$query .= "WHERE server = '".$sane_REQUEST['server']."' AND port = '".$sane_REQUEST['port']."' AND mount = '";
			$query .= $sane_REQUEST['mount']."';";

			$result = $xoopsDB->queryF($query);
			if ($result == false ) {
				$headerinfo = _AM_UHQICEAUTH_SQLERR.$query."<br/>".$xoopsDB->error();
			} else {
			 	$headerinfo = _AM_UHQICEAUTH_RESET.$sane_REQUEST['server'].":".$sane_REQUEST['port'].$sane_REQUEST['mount']._AM_UHQICEAUTH_SUCCESSFULLY;
			}
		} else {
			$headerinfo = _AM_UHQICEAUTH_PARAMERR;
		}
		redirect_header("mountpoints.php",10,$headerinfo);
		break;
	case "none":
	default:
		xoops_cp_header();
		$mainAdmin = new ModuleAdmin();
		echo $mainAdmin->addNavigation('mountpoints.php');
        $mainAdmin->addItemButton(_AM_UHQICEAUTH_ADDSERVER, 'mountpoints.php?op=insert', 'add');
        echo $mainAdmin->renderButton("left"); // ‘right’ is default

		// See if we have anything first.
		$data['servercount'] = uhqiceauth_summarycount("MP");;
		if ( $data['servercount'] ) {
			// List Mount Points
			$query = "SELECT * FROM ".$xoopsDB->prefix('uhqiceauth_servers')." ORDER BY server, port, mount";
			$result = $xoopsDB->queryF($query);
			if ($result == false) {
				redirect_header("mountpoints.php",10,_AM_UHQICEAUTH_SQLERR.$query);
				break;
			} else {
				$i=0;
				while ($row = $xoopsDB->fetchArray($result) ) {
					$data['mounts'][$i] = $row;

					// Look for and list intros for each mount point.
					$query2 = "SELECT x.sequence as sequence, x.intronum as intronum, y.filename as filename ";
					$query2 .= " FROM ".$xoopsDB->prefix("uhqiceauth_intromap")." x,";
					$query2 .= $xoopsDB->prefix("uhqiceauth_intros")." y ";
					$query2 .= " WHERE x.server='".$row['server']."'";
					$query2 .= " AND x.port='".$row['port']."'";
					$query2 .= " AND x.mount='".$row['mount']."'";
					$query2 .= " AND x.intronum=y.intronum";
					$query2 .= " ORDER BY sequence";
					$result2 = $xoopsDB->queryF($query2);
					$i2=0;
					while ($row2 = $xoopsDB->fetchArray($result2) ) {
						$data['mounts'][$i]['intro'][$i2] = $row2;
						$i2++;
					}
					$i++;
				}
			}
		}
        $xoopsTpl->assign('data',$data);
		$xoopsTpl->display("db:admin/uhqiceauth_mountpoints.html");
		include_once __DIR__ . '/admin_footer.php';
		break;
}

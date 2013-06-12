<?php

/*
UHQ-IceAuth :: XOOPS Module for IceCast Authentication
Copyright (C) 2008-2011 :: Ian A. Underwood :: xoops@underwood-hq.org

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

// Function to return summary counts.  Null for DB error, otherwise returns an integer.

function uhqiceauth_summarycount ($sumtype, $mountdata=null) {
	global $xoopsDB;
	
	// Base Query
	
	switch($sumtype) {
		case "AM":	// Active Mount Points
			$query = "SELECT COUNT(*) FROM ".$xoopsDB->prefix('uhqiceauth_activemounts');
			break;
		case "AU":	// Authentication Logs
			$query = "SELECT COUNT(*) FROM ".$xoopsDB->prefix('uhqiceauth_authtrail');
			break;
		case "DUA":	// Distinct User Agents
			$query = "SELECT COUNT(DISTINCT useragent) FROM ".$xoopsDB->prefix('uhqiceauth_authtrail')." WHERE authtype = 'L'";
			break;
		case "IN":	// Introductions
			$query = "SELECT COUNT(*) FROM ".$xoopsDB->prefix('uhqiceauth_intros');
			break;
		case "IP":	// IP Address Bans
			$query = "SELECT COUNT(*) FROM ".$xoopsDB->prefix("uhqiceauth_ipbans");
			break;
		case "ML":	// Mount Point Logs
			$query = "SELECT COUNT(*) FROM ".$xoopsDB->prefix('uhqiceauth_mountlog');
			break;
		case "MP":	// Mount Points
			$query = "SELECT COUNT(*) FROM ".$xoopsDB->prefix('uhqiceauth_servers');
			break;
		case "SP":	// Streampass Entries
			$query = "SELECT COUNT(*) FROM ".$xoopsDB->prefix("uhqiceauth_streampass");
			break;
		case "UA":	// User Agent Bans
			$query = "SELECT COUNT(*) FROM ".$xoopsDB->prefix("uhqiceauth_uabans");
			break;
		default:
			break;
	}
	
	// Append mount point query if all required data is supplied.  Does not apply to all summary count types.
	
	if ( $mountdata['server'] && $mountdata['port'] && $mountdata['mount'] ) {
		$query .= " WHERE server = '".$mountdata['server']."'";
		$query .= " AND port = '".$mountdata['port']."'";
		$query .= " AND mount = '".$mountdata['mount']."'";
	}
	
	// Perform Query & return count.
	
	$result = $xoopsDB->queryF($query);
	
	if ($result) {
		list($count) = $xoopsDB->fetchRow($result);
		return $count;
	} else {
		return null;
	}
}

// Take time in seconds, return friendly time in string.
function uhqiceauth_time ($duration) {
	
	// Hours
	$hours = intval ($duration/3600);
	// Minutes
	$minutes = intval ( ($duration - ($hours * 3600) ) / 60);
	// Seconds
	$seconds = intval ($duration) % 60;
	
	// Prepare Output String
	$time = '';	
	
	if ($hours < 10) {
		$time .= "0";
	}
	$time .= $hours.":";
	
	if ($minutes < 10) {
		$time .= "0";
	}
	$time .= $minutes.":";
	
	if ($seconds < 10) {
		$time .= "0";
	}
	$time .= $seconds;
	
	return $time;
}

// Define array of acceptable MIME type for the intro uploader

$uhqiceauth_intro_mimes = array('audio/ogg','video/ogg','audio/mpeg','video/mpeg','audio/aac');

// Return status of Geolocation Module

function uhqiceauth_geocheck() {
	global $xoops_gethandler;
	
	$module_handler = &xoops_gethandler('module');
	$geolocate = &$module_handler->getByDirname('uhq_geolocate');
	
	if (is_object($geolocate)) {
		$isok = $geolocate->getVar('isactive');
	} else {
		$isok = false;
	}
	return $isok;
}
?>
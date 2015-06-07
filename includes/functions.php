<?php

/*
UHQ-IceAuth :: XOOPS Module for IceCast Authentication
Copyright (C) 2008-2015 :: Ian A. Underwood :: xoops@underwood-hq.org

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

// Generate a password of a certain length.
function uhqiceauth_randompw($length) {

	$i = 0;
	$code = "";

	while ($i < $length) {
		$type = rand(1,3);
		switch ($type) {
			case 1:	// Numerics
				$value = rand(48,57);
				break;
			case 2:	// Uppercase
				$value = rand(65,90);
				break;
			case 3:	// Lowercase
				$value = rand(97,122);
				break;
		}
		$code .= chr($value);
		$i++;
	}
	return $code;

}

// Return List of Active Mount Points and a total.
function uhqiceauth_raw_activemounts () {
	global $xoopsDB;

	$query = "SELECT * FROM ".$xoopsDB->prefix('uhqiceauth_activemounts');
	$query .= " ORDER BY starttime";

	$result = $xoopsDB->queryF($query);

	if ($result == false) {
		// return nothing on a DB error.  Error can be pulled later.
		return null;
	} else {
		$data = array();
		$i=0;
		while ($row = $xoopsDB->fetchArray($result) ) {
			$data['mount'][$i] = $row;
			$i++;
		}
		$data['mounts'] = $i;

		return $data;
	}
}

// Return list of stream logins.
function uhqiceauth_raw_streampass ($start=0,$limit=0) {
	global $xoopsDB;

	$query = "SELECT * FROM ".$xoopsDB->prefix("uhqiceauth_streampass");
	$query .= " ORDER BY un";

	$result = $xoopsDB->queryF($query);

	if ($result == false) {
		// Return nothing on a DB Error.
		return null;
	} else {
		$data = array();
		while ($row = $xoopsDB->fetchArray($result) ) {
			$data['list'][$i] = $row;
			$i++;
		}
		$data['total'] = $i;

		return $data;
	}
}
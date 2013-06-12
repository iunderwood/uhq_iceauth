<?php

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

?>
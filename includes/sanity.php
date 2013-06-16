<?php

// Sanitize variables we expect for this module in $_REQUEST, that are put into queries.

function uhqiceauth_dosanity() {
	
	$sanerequest = array();
	$myts =& MyTextsanitizer::getInstance();
	
	// IceCast Variables
	if ( isset($_REQUEST['server']) ) {
		$sanerequest['server'] = $myts->addSlashes($_REQUEST['server']);
	}
	if ( isset($_REQUEST['port']) ) {
		$sanerequest['port'] = intval($_REQUEST['port']);
	}
	if ( isset($_REQUEST['mount']) ) {
		$sanerequest['mount'] = $myts->addSlashes($_REQUEST['mount']);
	}
	if ( isset($_REQUEST['user']) ) {
		$sanerequest['user'] = $myts->addSlashes($_REQUEST['user']);
	}
	if ( isset($_REQUEST['pass']) ) {
		$sanerequest['pass'] = $myts->addSlashes($_REQUEST['pass']);
	}
	if ( isset($_REQUEST['client']) ) {
		$sanerequest['client'] = $myts->addSlashes($_REQUEST['client']);
	}
	if ( isset($_REQUEST['ip']) ) {
		$sanerequest['ip'] = $myts->addSlashes($_REQUEST['ip']);
	}
	if ( isset($_REQUEST['agent']) ) {
		$sanerequest['agent'] = $myts->addSlashes($_REQUEST['agent']);
	}
	if ( isset($_REQUEST['duration']) ) {
		$sanerequest['duration'] = $myts->addSlashes($_REQUEST['duration']);
	}
	if ( isset($_REQUEST['referer']) ) {
		$sanerequest['referer'] = $myts->addSlashes($_REQUEST['referer']);
	}

	// Module Variables
	if ( isset($_REQUEST['timelimit']) ) {
		$sanerequest['timelimit'] = $myts->addSlashes($_REQUEST['timelimit']);
	}
	if ( isset($_REQUEST['lst_auth_typ']) ) {
		$sanerequest['lst_auth_typ'] = $myts->addSlashes($_REQUEST['lst_auth_typ']);
	}
	if ( isset($_REQUEST['lst_auth_grp']) ) {
		$sanerequest['lst_auth_grp'] = $myts->addSlashes($_REQUEST['lst_auth_grp']);
	}
	if ( isset($_REQUEST['src_auth_typ']) ) {
		$sanerequest['src_auth_typ'] = $myts->addSlashes($_REQUEST['src_auth_typ']);
	}
	if ( isset($_REQUEST['src_auth_grp']) ) {
		$sanerequest['src_auth_grp'] = $myts->addSlashes($_REQUEST['src_auth_grp']);
	}
	if ( isset($_REQUEST['src_auth_un']) ) {
		$sanerequest['src_auth_un'] = $myts->addSlashes($_REQUEST['src_auth_un']);
	}
	if ( isset($_REQUEST['src_auth_pw']) ) {
		$sanerequest['src_auth_pw'] = $myts->addSlashes($_REQUEST['src_auth_pw']);
	}
	if ( isset($_REQUEST['o_server']) ) {
		$sanerequest['o_server'] = $myts->addSlashes($_REQUEST['o_server']);
	}
	if ( isset($_REQUEST['o_port']) ) {
		$sanerequest['o_port'] = $myts->addSlashes($_REQUEST['o_port']);
	}
	if ( isset($_REQUEST['o_mount']) ) {
		$sanerequest['o_mount'] = $myts->addSlashes($_REQUEST['o_mount']);
	}
	if ( isset($_REQUEST['codec']) ) {
		$sanerequest['codec'] = $myts->addSlashes($_REQUEST['codec']);
	}
	if ( isset($_REQUEST['description']) ) {
		$sanerequest['description'] = $myts->addSlashes($_REQUEST['description']);
	}
	if ( isset($_REQUEST['intronum']) ) {
		$sanerequest['intronum'] = intval($_REQUEST['intronum']);
	}
	// Administrative Fields
	if ( isset($_REQUEST['sequence']) ) {
		$sanerequest['sequence'] = intval($_REQUEST['sequence']);
	}
	
	return $sanerequest;
}

?>
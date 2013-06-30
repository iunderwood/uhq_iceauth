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

// Errors

define ("_AM_UHQICEAUTH_ERR_FETCH", "Upload Fetch Error");
define ("_AM_UHQICEAUTH_ERR_ULSAVE", "Upload Save Error");
define ("_AM_UHQICEAUTH_ERR_OPENMIME", "Unable to open mimetypes.");
define ("_AM_UHQICEAUTH_ERR_READMIME", "Unable to read mimetypes.");

// Server Form Elements

define ("_AM_UHQICEAUTH_FORM_IPFQDN", "Server IP/FQDN:");
define ("_AM_UHQICEAUTH_FORM_PORT","Port:");
define ("_AM_UHQICEAUTH_FORM_MOUNT","Mountpoint:");
define ("_AM_UHQICEAUTH_FORM_TIMEL","Time Limit (sec):");
define ("_AM_UHQICEAUTH_FORM_LAUTH","Listener Auth:");
define ("_AM_UHQICEAUTH_FORM_LGRP","Listener Groups:");
define ("_AM_UHQICEAUTH_FORM_SAUTH","Source Auth:");
define ("_AM_UHQICEAUTH_FORM_SGRP","Source Groups:");
define ("_AM_UHQICEAUTH_FORM_SRCUN","Source UN:");
define ("_AM_UHQICEAUTH_FORM_SRCPW","Source PW:");
define ("_AM_UHQICEAUTH_FORM_AUTHA","Anonymous");
define ("_AM_UHQICEAUTH_FORM_AUTHD","Check User");
define ("_AM_UHQICEAUTH_FORM_AUTHS","Source UN/PW");
define ("_AM_UHQICEAUTH_FORM_AUTHN","Not Used");
define ("_AM_UHQICEAUTH_FORM_USERAGENT","User Agent:");

// Codecs

define ("_AM_UHQICEAUTH_AAC","Advanced Audio Codec (AAC)");
define ("_AM_UHQICEAUTH_AACPLUS","Advanced Audio Codec (AAC+)");
define ("_AM_UHQICEAUTH_MP3","MPEG Layer 3 (MP3)");
define ("_AM_UHQICEAUTH_OGG","Ogg Vorbis");

// Verify Delete Form

define ("_AM_UHQICEAUTH_FORM_VDEL","Verify Delete");
define ("_AM_UHQICEAUTH_FORM_DELHD","Delete Historic Data?");
define ("_AM_UHQICEAUTH_FORM_RD","Remove Data");
define ("_AM_UHQICEAUTH_FORM_DELBUTTON","Delete");

// Header Elements

define ("_AM_UHQICEAUTH_PREF","Module Preferences");
define ("_AM_UHQICEAUTH_ADMIN","Administration");
define ("_AM_UHQICEAUTH_INDEX_ACCESS","Module Access Summary");
define ("_AM_UHQICEAUTH_INDEX_MODULES","Additional Modules");
define ("_AM_UHQICEAUTH_INDEX_MIME","MIME Type Summary");
define ("_AM_UHQICEAUTH_INDEX_STORAGE","Module Storage Summary");

// Responses

define ("_AM_UHQICEAUTH_SQLERR", "<br><b>SQL Error on:</b> ");
define ("_AM_UHQICEAUTH_NOTIMPLEMENTED","This feature is not yet implemented.");
define ("_AM_UHQICEAUTH_PARAMERR", "Insufficient Parameters");
define ("_AM_UHQICEAUTH_RETINDEX","Return to Index");
define ("_AM_UHQICEAUTH_RETSERVERS", "Return to Mountpoints");
define ("_AM_UHQICEAUTH_RETMOUNTS", "Return to Mountpoint Records");
define ("_AM_UHQICEAUTH_RETUA","Return to User Agent Bans");
define ("_AM_UHQICEAUTH_ADDED","Added ");
define ("_AM_UHQICEAUTH_CHANGED","Changed ");
define ("_AM_UHQICEAUTH_DELETED","Deleted ");
define ("_AM_UHQICEAUTH_DELETEDMOUNT","Deleted Mountpoint ");
define ("_AM_UHQICEAUTH_DELETEDMAP","Deleted Intro Maps for ");
define ("_AM_UHQICEAUTH_CHANGEMAP","Updated Intro Maps for ");
define ("_AM_UHQICEAUTH_RESET","Reset counters for ");
define ("_AM_UHQICEAUTH_SUCCESSFULLY"," successfully.");
define ("_AM_UHQICEAUTH_ADDSERVER", "Add Mount Point");
define ("_AM_UHQICEAUTH_ADDUA","Add User Agent");
define ("_AM_UHQICEAUTH_EDITSERVER", "Edit Mount Point");
define ("_AM_UHQICEAUTH_NOLIMIT","No Limit");
define ("_AM_UHQICEAUTH_NOINTROS","No Intros");
define ("_AM_UHQICEAUTH_UNDEF","Undefined: ");
define ("_AM_UHQICEAUTH_UNPW","Login: ");
define ("_AM_UHQICEAUTH_REMDB","Removed from all databases ");
define ("_AM_UHQICEAUTH_ANON_OK","<span style=\"color: green; \">Anonymous Access OK</span>");
define ("_AM_UHQICEAUTH_ANON_RESTORED","<span style=\"color: green; \">Anonymous Access Restored</span>");
define ("_AM_UHQICEAUTH_ANON_FAILED","<span style=\"color: red; \">Unable to set Anonymous Access</span>");
define ("_AM_UHQICEAUTH_CODEC_FOUND"," found!<br/>");
define ("_AM_UHQICEAUTH_CODEC_NFOUND"," <span style=\"color: red; \">not found</span>.  Uploads of this type may fail.<br/>");
define ("_AM_UHQICEAUTH_GEOLOC_OK","UHQ-GeoLocate: <span style=\"color: green; \">Installed</span><br/>");
define ("_AM_UHQICEAUTH_GEOLOC_NOK","UHQ-GeoLocate: Not Installed<br/>");
define ("_AM_UHQICEAUTH_SP_DELOK","Deleted PW for ");
define ("_AM_UHQICEAUTH_SP_NOPW","No Password for ");

// Delete Server Verify

define ("_AM_UHQICEAUTH_DEL_VER","Verify Delete");
define ("_AM_UHQICEAUTH_DEL_HIST","Historic Data Summary");

// Server List

define ("_AM_UHQICEAUTH_SVR_NONE","No Mount Points Configured");
define ("_AM_UHQICEAUTH_SVR_PLU"," Mount Points");
define ("_AM_UHQICEAUTH_SVR_ONE"," Mount Point");

// Authentication

define ("_AM_UHQICEAUTH_AUTH_NONE","No Authentication Records");
define ("_AM_UHQICEAUTH_AUTH_PLU"," Authentication Records");
define ("_AM_UHQICEAUTH_AUTH_ONE"," Authentication Record");

define ("_AM_UHQICEAUTH_STATS_NOREC","No");
define ("_AM_UHQICEAUTH_STATS_LCREC"," Listener Connect Records");
define ("_AM_UHQICEAUTH_STATS_SCREC"," Source Connect Records");
define ("_AM_UHQICEAUTH_STATS_AUREC"," Admin Update Records");
define ("_AM_UHQICEAUTH_STATS_MPREC"," Mountpoint Records");

define ("_AM_UHQICEAUTH_AUTH_ANON","<i>Anonymous</i>");
define ("_AM_UHQICEAUTH_AUTH_SCPASS"," login from ");
define ("_AM_UHQICEAUTH_AUTH_SCFAIL"," failed login from ");
define ("_AM_UHQICEAUTH_AUTH_LCPASS"," streamed from ");
define ("_AM_UHQICEAUTH_AUTH_LCFAIL"," failed login from ");
define ("_AM_UHQICEAUTH_AUTH_AUPASS"," updated from ");
define ("_AM_UHQICEAUTH_AUTH_AUFAIL"," failed update from ");

// Auth Detail Record

define ("_AM_UHQICEAUTH_ADR_DETAIL","Details for Record # ");
define ("_AM_UHQICEAUTH_ADR_LOGTIME","Log Time:");
define ("_AM_UHQICEAUTH_ADR_STOPTIME","Stop Time:");
define ("_AM_UHQICEAUTH_ADR_SECONDS","seconds");
define ("_AM_UHQICEAUTH_ADR_RECTYPE","Record Type:");
define ("_AM_UHQICEAUTH_ADR_LREC","Listener Record");
define ("_AM_UHQICEAUTH_ADR_SREC","Source Record");
define ("_AM_UHQICEAUTH_ADR_AREC","Admin Record");
define ("_AM_UHQICEAUTH_ADR_AUTHFAIL","Authentication Failed");
define ("_AM_UHQICEAUTH_ADR_SERVER","Server:");
define ("_AM_UHQICEAUTH_ADR_CLIENT","Client ID:");
define ("_AM_UHQICEAUTH_ADR_USER","Username:");
define ("_AM_UHQICEAUTH_ADR_ANON","Anonymous");
define ("_AM_UHQICEAUTH_ADR_USERIP","User IP:");
define ("_AM_UHQICEAUTH_ADR_NORES","No Resolution");
define ("_AM_UHQICEAUTH_ADR_NOCHG","No Change");
define ("_AM_UHQICEAUTH_ADR_NOW","Now");
define ("_AM_UHQICEAUTH_ADR_NOREC","Not Recorded");
define ("_AM_UHQICEAUTH_ADR_UA","User Agent:");
define ("_AM_UHQICEAUTH_ADR_UANP","UA Not Provided");
define ("_AM_UHQICEAUTH_ADR_GEOLOC","Geolocation: ");
define ("_AM_UHQICEAUTH_ADR_NOGEO","No Data Available");
define ("_AM_UHQICEAUTH_ADR_RETURN","Return to Authentication");
define ("_AM_UHQICEAUTH_ADR_REFERER","Referencing Site: ");

// Auth Information Comments

define ("_AM_UHQICEAUTH_AUTHINFO_1","Auth record inserted on connect.");
define ("_AM_UHQICEAUTH_AUTHINFO_2","Auth record inserted on disconnect.");
define ("_AM_UHQICEAUTH_AUTHINFO_10","Auth failed: Bad UN/PW combination.");
define ("_AM_UHQICEAUTH_AUTHINFO_11","Auth failed: Insufficient permission.");

// Accounting

define ("_AM_UHQICEAUTH_ACCT_SUM","Accounting Summary");

define ("_AM_UHQICEAUTH_ACCT_NONE","No Accounting Records");
define ("_AM_UHQICEAUTH_ACCT_PLU"," Accounting Records");
define ("_AM_UHQICEAUTH_ACCT_ONE"," Accounting Record");

define ("_AM_UHQICEAUTH_AGENTS_NONE","No Agents On Record");
define ("_AM_UHQICEAUTH_AGENTS_PLU"," Agents On Record");
define ("_AM_UHQICEAUTH_AGENTS_ONE"," Agent On Record");

// Stats

define ("_AM_UHQICEAUTH_STATS_TOP","Top ");
define ("_AM_UHQICEAUTH_STATS_FIRST","First ");
define ("_AM_UHQICEAUTH_STATS_LAST","Last ");
define ("_AM_UHQICEAUTH_STATS_AGENTCONN"," User Agents by Connections");
define ("_AM_UHQICEAUTH_STATS_AGENTTIME"," User Agents by Time");
define ("_AM_UHQICEAUTH_STATS_DAYS"," Days");

define ("_AM_UHQICEAUTH_STATS_TTSL","Total Time Spent Listening");
define ("_AM_UHQICEAUTH_STATS_TTSL_TODAY","Today :: Now ");
define ("_AM_UHQICEAUTH_STATS_TTSL_24H","Last 24 Hours");
define ("_AM_UHQICEAUTH_STATS_TTSL_7D","Last 7 Days");
define ("_AM_UHQICEAUTH_STATS_TTSL_30D","Last 30 Days");

// List Actions

define ("_AM_UHQICEAUTH_LIST_MEDIT","Edit Mountpoint");
define ("_AM_UHQICEAUTH_LIST_MDEL", "Delete Mountpoint");
define ("_AM_UHQICEAUTH_LIST_MRESET", "Reset Counters");
define ("_AM_UHQICEAUTH_LIST_MINTRO","Add Intro");

// List Elements

define ("_AM_UHQICEAUTH_LIST_SVR","Server");
define ("_AM_UHQICEAUTH_LIST_PORT","Port");
define ("_AM_UHQICEAUTH_LIST_MOUNT","Mountpoint");
define ("_AM_UHQICEAUTH_LIST_INTROS","Intros");
define ("_AM_UHQICEAUTH_LIST_TIMEL","Limit");
define ("_AM_UHQICEAUTH_LIST_LAUTH","Listener Auth");
define ("_AM_UHQICEAUTH_LIST_SAUTH","Source Auth");
define ("_AM_UHQICEAUTH_LIST_HITS","Hits");
define ("_AM_UHQICEAUTH_LIST_ACT","Action");
define ("_AM_UHQICEAUTH_LIST_ACT_EDIT","Edit");
define ("_AM_UHQICEAUTH_LIST_ACT_DEL","Delete");
define ("_AM_UHQICEAUTH_LIST_ACT_PLAY","Play");
define ("_AM_UHQICEAUTH_LIST_USERAGENT","User Agent Needle");
define ("_AM_UHQICEAUTH_LIST_CONNECTS","Connects");
define ("_AM_UHQICEAUTH_LIST_PASS","Pass");
define ("_AM_UHQICEAUTH_LIST_FAIL","Fail");
define ("_AM_UHQICEAUTH_LIST_TIME","Time");
define ("_AM_UHQICEAUTH_LIST_HASH","#");
define ("_AM_UHQICEAUTH_LIST_LOG","Log Entry");
define ("_AM_UHQICEAUTH_LIST_LFILE","Local Filename");
define ("_AM_UHQICEAUTH_LIST_CODEC","Codec");
define ("_AM_UHQICEAUTH_LIST_DESC","Description");
define ("_AM_UHQICEAUTH_LIST_REMOVE","Remove");
define ("_AM_UHQICEAUTH_LIST_GEO","Geo");
define ("_AM_UHQICEAUTH_LIST_UN","Username");
define ("_AM_UHQICEAUTH_LIST_PW","Password");
define ("_AM_UHQICEAUTH_LIST_DATESET","Date Set");
define ("_AM_UHQICEAUTH_LIST_DATEUSED","Date Used");
define ("_AM_UHQICEAUTH_LIST_INTERVAL","Interval");
define ("_AM_UHQICEAUTH_LIST_TTSL","TTSL");
define ("_AM_UHQICEAUTH_LIST_AVGTTSL","Average TTSL");
define ("_AM_UHQICEAUTH_LIST_REFERER","Referer");

// Mount Point Elements

define ("_AM_UHQICEAUTH_MOUNTS_NONE","No Mount Point Records Reported");
define ("_AM_UHQICEAUTH_MOUNTS_PLU"," Mount Point Records");
define ("_AM_UHQICEAUTH_MOUNTS_ONE"," Mount Point Record");

define ("_AM_UHQICEAUTH_AMOUNTS_NONE","No Active Mount Points Reported");
define ("_AM_UHQICEAUTH_AMOUNTS_PLU"," Active Mount Points");
define ("_AM_UHQICEAUTH_AMOUNTS_ONE"," Active Mount Point");

define ("_AM_UHQICEAUTH_MOUNTACT_A","Mount Point Active");
define ("_AM_UHQICEAUTH_MOUNTACT_R","Mount Point Removed");

// Stream PW Elements

define ("_AM_UHQICEAUTH_SP_NONE","No Stream Logins Reported");
define ("_AM_UHQICEAUTH_SP_PLU"," Stream Logins");
define ("_AM_UHQICEAUTH_SP_ONE"," Stream Login");

// UA Ban Elements

define ("_AM_UHQICEAUTH_UABAN_NONE","No User Agents Banned");
define ("_AM_UHQICEAUTH_UABAN_PLU"," User Agents");
define ("_AM_UHQICEAUTH_UABAN_ONE"," User Agent");

define ("_AM_UHQICEAUTH_UABAN_TEST","Test User Agent");
define ("_AM_UHQICEAUTH_UA_PASS"," passes UA testing.");
define ("_AM_UHQICEAUTH_UA_FAIL"," fails UA testing.");

// IP Ban Elements

define ("_AM_UHQICEAUTH_IPBAN_NONE","No IPs Banned");
define ("_AM_UHQICEAUTH_IPBAN_PLU"," IP Bans");
define ("_AM_UHQICEAUTH_IPBAN_ONE"," IP Ban");

// Intro Elements

define ("_AM_UHQICEAUTH_INTROS_NONE","No Intro Files Configured");
define ("_AM_UHQICEAUTH_INTROS_PLU"," Intro Files");
define ("_AM_UHQICEAUTH_INTROS_ONE"," Intro File");

define ("_AM_UHQICEAUTH_INTROS_ADD","Add Intro");
define ("_AM_UHQICEAUTH_INTROS_EDIT","Edit Intro");
define ("_AM_UHQICEAUTH_INTROS_DELETE","Delete Intro");
define ("_AM_UHQICEAUTH_INTROS_FILE","Intro File");
define ("_AM_UHQICEAUTH_INTROS_EDITFILE","Replace File");
define ("_AM_UHQICEAUTH_INTROS_CODEC","Select Codec");
define ("_AM_UHQICEAUTH_INTROS_DESC","Intro Description");

define ("_AM_UHQICEAUTH_INTROS_MAPFORM","Map introduction");
define ("_AM_UHQICEAUTH_INTROS_SEQ","File Sequence");
define ("_AM_UHQICEAUTH_INTROS_MAPADD","Intro mapped successfully!");
define ("_AM_UHQICEAUTH_INTROS_MAPDEL","Intro removed successfully!");

define ("_AM_UHQICEAUTH_INTROS_ULOK","Upload Successful");
define ("_AM_UHQICEAUTH_INTROS_UPDOK","Update Successful<br>Intro #");

define ("_AM_UHQICEAUTH_INTROS_PLAY_NOINTRO","No Intro specified.");
define ("_AM_UHQICEAUTH_INTROS_PLAY_NORECORD","Intro ID not found.");
define ("_AM_UHQICEAUTH_INTROS_PLAY_NOFILE","Unable to find file.");
define ("_AM_UHQICEAUTH_INTROS_PLAY_ERROR","Error: ");
define ("_AM_UHQICEAUTH_INTROS_PLAY_VLCREQ","You must have the VLC plug-in installed in order to use this feature.");
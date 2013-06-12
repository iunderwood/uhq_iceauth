CREATE TABLE uhqiceauth_servers (
	server			CHAR(50)	NOT NULL,
	port			INT			UNSIGNED NOT NULL,
	mount			CHAR(20)	NOT NULL,
	timelimit		INT			UNSIGNED NOT NULL,
	lst_auth_typ	CHAR(1)		NOT NULL,
	lst_auth_grp	VARCHAR(64)	NOT NULL,
	src_auth_typ	CHAR(1)		NOT NULL,
	src_auth_grp	VARCHAR(64)	NOT NULL,
	src_auth_un		CHAR(20)	NOT NULL,
	src_auth_pw		CHAR(20)	NOT NULL,
	hits_pass		INT			UNSIGNED NOT NULL,
	hits_fail		INT			UNSIGNED NOT NULL,
	src_hits_pass	INT			UNSIGNED NOT NULL,
	src_hits_fail	INT			UNSIGNED NOT NULL,
	PRIMARY KEY (server,port,mount)
) ENGINE=MyISAM;

CREATE TABLE uhqiceauth_authtrail (
	sequence		INT			UNSIGNED NOT NULL AUTO_INCREMENT,
	logtime			DATETIME,
	server			CHAR(50)	NOT NULL,
	port			INT			UNSIGNED NOT NULL,
	mount			CHAR(20)	NOT NULL,
	authtype		CHAR(1)		NOT NULL,
	authstat		CHAR(1)		NOT NULL,
	authinfo		INT,
	clientid		INT			UNSIGNED NOT NULL,
	username		CHAR(20),
	useragent		VARCHAR(128),
	userip			CHAR(50)	NOT NULL,
	userrdns		VARCHAR(64),
	duration		INT			UNSIGNED,
	stoptime		DATETIME,
	geocc			CHAR(2),
	georegion		VARCHAR(128),
	geocity			VARCHAR(128),
	PRIMARY KEY (sequence)
) ENGINE=MyISAM;

CREATE TABLE uhqiceauth_mountlog (
	sequence		INT			UNSIGNED NOT NULL AUTO_INCREMENT,
	logtime			TIMESTAMP,
	server			CHAR(50)	NOT NULL,
	port			INT			UNSIGNED NOT NULL,
	mount			CHAR(20)	NOT NULL,
	action			CHAR(1)		NOT NULL,
	PRIMARY KEY (sequence)
) ENGINE=MyISAM;

CREATE TABLE uhqiceauth_activemounts (
	server			CHAR(50)	NOT NULL,
	port			INT			UNSIGNED NOT NULL,
	mount			CHAR(20)	NOT NULL,
	starttime		TIMESTAMP,
	PRIMARY KEY (server,port,mount)
) ENGINE=MyISAM;

CREATE TABLE uhqiceauth_intros (
	intronum		INT			UNSIGNED NOT NULL AUTO_INCREMENT,
	filename		CHAR(50)	NOT NULL,
	codec			CHAR(1)		NOT NULL,
	description		VARCHAR(255),
	PRIMARY KEY (intronum)
) ENGINE=MyISAM;

CREATE TABLE uhqiceauth_intromap (
	intronum		INT			UNSIGNED NOT NULL,
	server			CHAR(50)	NOT NULL,
	port			INT			UNSIGNED NOT NULL,
	mount			CHAR(20)	NOT NULL,
	sequence		INT			UNSIGNED NOT NULL,
	PRIMARY KEY (intronum,server,port,mount)
) ENGINE=MyISAM;

CREATE TABLE uhqiceauth_streampass (
	un		VARCHAR(30) NOT NULL,
	pw		VARCHAR(15) NOT NULL,
	added	DATETIME,
	used	DATETIME,
	PRIMARY KEY (un,pw)
) ENGINE=MyISAM;

CREATE TABLE uhqiceauth_uabans (
  sequence		INT			UNSIGNED NOT NULL AUTO_INCREMENT,
  useragent		VARCHAR(128),
  matchtype		CHAR(1),
  PRIMARY KEY (sequence)
) ENGINE=MyISAM;

CREATE TABLE uhqiceauth_ipbans (
	startip		INT			UNSIGNED,
	endip		INT			UNSIGNED,
	added		DATETIME,
	comment		VARCHAR(128),
	PRIMARY KEY (startip,endip)
) ENGINE=MyISAM;
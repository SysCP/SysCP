# $Id$
# --------------------------------------------------------

#
# Table structure for table `ftp_groups`
#

DROP TABLE IF EXISTS `ftp_groups`;
CREATE TABLE `ftp_groups` (
  `id` int(20) NOT NULL auto_increment,
  `groupname` varchar(60) NOT NULL default '',
  `gid` int(5) NOT NULL default '0',
  `members` longtext NOT NULL,
  `customerid` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `groupname` (`groupname`),
  KEY `customerid` (`customerid`)
) TYPE=MyISAM ;

#
# Dumping data for table `ftp_groups`
#


# --------------------------------------------------------

#
# Table structure for table `ftp_users`
#

DROP TABLE IF EXISTS `ftp_users`;
CREATE TABLE `ftp_users` (
  `id` int(20) NOT NULL auto_increment,
  `username` varchar(20) NOT NULL default '',
  `uid` int(5) NOT NULL default '0',
  `gid` int(5) NOT NULL default '0',
  `password` varchar(20) NOT NULL default '',
  `homedir` varchar(255) NOT NULL default '',
  `shell` varchar(255) NOT NULL default '/bin/false',
  `login_enabled` enum('N','Y') NOT NULL default 'N',
  `login_count` int(15) NOT NULL default '0',
  `last_login` datetime NOT NULL default '0000-00-00 00:00:00',
  `up_count` int(15) NOT NULL default '0',
  `up_bytes` bigint(30) NOT NULL default '0',
  `down_count` int(15) NOT NULL default '0',
  `down_bytes` bigint(30) NOT NULL default '0',
  `customerid` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `username` (`username`),
  KEY `customerid` (`customerid`)
) TYPE=MyISAM ;

#
# Dumping data for table `ftp_users`
#

# --------------------------------------------------------


#
# Table structure for table `mail_users`
#

DROP TABLE IF EXISTS `mail_users`;
CREATE TABLE `mail_users` (
  `id` int(11) NOT NULL auto_increment,
  `email` varchar(128) NOT NULL default '',
  `password` varchar(128) NOT NULL default '',
  `password_enc` varchar(128) NOT NULL default '',
  `uid` int(11) NOT NULL default '0',
  `gid` int(11) NOT NULL default '0',
  `homedir` varchar(128) NOT NULL default '',
  `maildir` varchar(128) NOT NULL default '',
  `postfix` enum('Y','N') NOT NULL default 'Y',
  `domainid` int(11) NOT NULL default '0',
  `customerid` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `email` (`email`)
) TYPE=MyISAM ;

#
# Dumping data for table `mail_users`
#


# --------------------------------------------------------

#
# Table structure for table `mail_virtual`
#

DROP TABLE IF EXISTS `mail_virtual`;
CREATE TABLE `mail_virtual` (
  `id` int(11) NOT NULL auto_increment,
  `email` varchar(50) NOT NULL default '',
  `destination` text NOT NULL,
  `domainid` int(11) NOT NULL default '0',
  `customerid` int(11) NOT NULL default '0',
  `popaccountid` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM ;

#
# Dumping data for table `mail_virtual`
#

# --------------------------------------------------------


#
# Table structure for table `panel_admins`
#

DROP TABLE IF EXISTS `panel_admins`;
CREATE TABLE `panel_admins` (
  `adminid` int(11) unsigned NOT NULL auto_increment,
  `loginname` varchar(50) NOT NULL default '',
  `password` varchar(50) NOT NULL default '',
  `name` varchar(255) NOT NULL default '',
  `email` varchar(255) NOT NULL default '',
  `customers` int(15) NOT NULL default '0',
  `customers_used` int(15) NOT NULL default '0',
  `customers_see_all` tinyint(1) NOT NULL default '0',
  `domains` int(15) NOT NULL default '0',
  `domains_used` int(15) NOT NULL default '0',
  `domains_see_all` tinyint(1) NOT NULL default '0',
  `change_serversettings` tinyint(1) NOT NULL default '0',
  `diskspace` int(15) NOT NULL default '0',
  `diskspace_used` int(15) NOT NULL default '0',
  `mysqls` int(15) NOT NULL default '0',
  `mysqls_used` int(15) NOT NULL default '0',
  `emails` int(15) NOT NULL default '0',
  `emails_used` int(15) NOT NULL default '0',
  `email_forwarders` int(15) NOT NULL default '0',
  `email_forwarders_used` int(15) NOT NULL default '0',
  `ftps` int(15) NOT NULL default '0',
  `ftps_used` int(15) NOT NULL default '0',
  `subdomains` int(15) NOT NULL default '0',
  `subdomains_used` int(15) NOT NULL default '0',
  `traffic` int(15) NOT NULL default '0',
  `traffic_used` int(15) NOT NULL default '0',
  `deactivated` tinyint(1) NOT NULL default '0',
  `lastlogin_succ` int(11) unsigned NOT NULL default '0',
  `lastlogin_fail` int(11) unsigned NOT NULL default '0',
  `loginfail_count` int(11) unsigned NOT NULL default '0',
   PRIMARY KEY  (`adminid`)
) TYPE=MyISAM ;


# --------------------------------------------------------

#
# Table structure for table `panel_customers`
#

DROP TABLE IF EXISTS `panel_customers`;
CREATE TABLE `panel_customers` (
  `customerid` int(11) unsigned NOT NULL auto_increment,
  `loginname` varchar(50) NOT NULL default '',
  `password` varchar(50) NOT NULL default '',
  `adminid` int(11) unsigned NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  `surname` varchar(255) NOT NULL default '',
  `company` varchar(255) NOT NULL default '',
  `street` varchar(255) NOT NULL default '',
  `zipcode` varchar(255) NOT NULL default '',
  `city` varchar(255) NOT NULL default '',
  `phone` varchar(255) NOT NULL default '',
  `fax` varchar(255) NOT NULL default '',
  `email` varchar(255) NOT NULL default '',
  `customernumber` varchar(255) NOT NULL default '',
  `diskspace` int(15) NOT NULL default '0',
  `diskspace_used` int(15) NOT NULL default '0',
  `mysqls` int(15) NOT NULL default '0',
  `mysqls_used` int(15) NOT NULL default '0',
  `emails` int(15) NOT NULL default '0',
  `emails_used` int(15) NOT NULL default '0',
  `email_forwarders` int(15) NOT NULL default '0',
  `email_forwarders_used` int(15) NOT NULL default '0',
  `ftps` int(15) NOT NULL default '0',
  `ftps_used` int(15) NOT NULL default '0',
  `subdomains` int(15) NOT NULL default '0',
  `subdomains_used` int(15) NOT NULL default '0',
  `traffic` int(15) NOT NULL default '0',
  `traffic_used` int(15) NOT NULL default '0',
  `documentroot` varchar(255) NOT NULL default '',
  `createstdsubdomain` tinyint(1) NOT NULL default '0',
  `guid` int(5) NOT NULL default '0',
  `ftp_lastaccountnumber` int(11) NOT NULL default '0',
  `mysql_lastaccountnumber` int(11) NOT NULL default '0',
  `deactivated` tinyint(1) NOT NULL default '0',
  `lastlogin_succ` int(11) unsigned NOT NULL default '0',
  `lastlogin_fail` int(11) unsigned NOT NULL default '0',
  `loginfail_count` int(11) unsigned NOT NULL default '0',
   PRIMARY KEY  (`customerid`),
  KEY `loginname` (`loginname`)
) TYPE=MyISAM ;
#
# Dumping data for table `panel_customers`
#


# --------------------------------------------------------

#
# Table structure for table `panel_databases`
#

DROP TABLE IF EXISTS `panel_databases`;
CREATE TABLE `panel_databases` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `customerid` int(11) NOT NULL default '0',
  `databasename` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `customerid` (`customerid`)
) TYPE=MyISAM ;

#
# Dumping data for table `panel_databases`
#


# --------------------------------------------------------

#
# Table structure for table `panel_domains`
#
DROP TABLE IF EXISTS `panel_domains`;
CREATE TABLE `panel_domains` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `domain` varchar(255) NOT NULL default '',
  `adminid` int(11) unsigned NOT NULL default '0',
  `customerid` int(11) unsigned NOT NULL default '0',
  `documentroot` varchar(255) NOT NULL default '',
  `isemaildomain` tinyint(1) NOT NULL default '0',
  `iswildcarddomain` tinyint(1) NOT NULL default '0',
  `zonefile` varchar(255) NOT NULL default '',
  `parentdomainid` int(11) unsigned NOT NULL default '0',
  `openbasedir` tinyint(1) NOT NULL default '0',
  `safemode` tinyint(1) NOT NULL default '0',
  `speciallogfile` tinyint(1) NOT NULL default '0',
  `specialsettings` text NOT NULL,
  `deactivated` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `customerid` (`customerid`),
  KEY `parentdomain` (`parentdomainid`)
) TYPE=MyISAM ;

#
# Dumping data for table `panel_domains`
#


# --------------------------------------------------------

#
# Table structure for table `panel_htaccess`
#

DROP TABLE IF EXISTS `panel_htaccess`;
CREATE TABLE `panel_htaccess` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `customerid` int(11) unsigned NOT NULL default '0',
  `path` varchar(255) NOT NULL default '',
  `options_indexes` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM ;

#
# Dumping data for table `panel_htaccess`
#


# --------------------------------------------------------

#
# Table structure for table `panel_htpasswds`
#

DROP TABLE IF EXISTS `panel_htpasswds`;
CREATE TABLE `panel_htpasswds` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `customerid` int(11) unsigned NOT NULL default '0',
  `path` varchar(255) NOT NULL default '',
  `username` varchar(255) NOT NULL default '',
  `password` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `customerid` (`customerid`)
) TYPE=MyISAM ;

#
# Dumping data for table `panel_htpasswds`
#


# --------------------------------------------------------

#
# Table structure for table `panel_sessions`
#

DROP TABLE IF EXISTS `panel_sessions`;
CREATE TABLE `panel_sessions` (
  `hash` varchar(32) NOT NULL default '',
  `userid` int(11) unsigned NOT NULL default '0',
  `ipaddress` varchar(16) NOT NULL default '',
  `useragent` varchar(255) NOT NULL default '',
  `lastactivity` int(11) unsigned NOT NULL default '0',
  `language` varchar(64) NOT NULL default '',
  `adminsession` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`hash`),
  KEY `userid` (`userid`)
) TYPE=HEAP;

#
# Dumping data for table `panel_sessions`
#


# --------------------------------------------------------

#
# Table structure for table `panel_settings`
#

DROP TABLE IF EXISTS `panel_settings`;
CREATE TABLE `panel_settings` (
  `settingid` int(11) unsigned NOT NULL auto_increment,
  `settinggroup` varchar(255) NOT NULL default '',
  `varname` varchar(255) NOT NULL default '',
  `value` text NOT NULL,
  PRIMARY KEY  (`settingid`)
) TYPE=MyISAM ;


# --------------------------------------------------------

#
# Dumping data for table `panel_settings`
#

INSERT INTO `panel_settings` (`settingid`, `settinggroup`, `varname`, `value`) VALUES (1, 'session', 'sessiontimeout', '600');
INSERT INTO `panel_settings` (`settingid`, `settinggroup`, `varname`, `value`) VALUES (2, 'panel', 'adminmail', 'admin@SERVERNAME');
INSERT INTO `panel_settings` (`settingid`, `settinggroup`, `varname`, `value`) VALUES (3, 'panel', 'phpmyadmin_url', '');
INSERT INTO `panel_settings` (`settingid`, `settinggroup`, `varname`, `value`) VALUES (4, 'email', 'catchallkeyword', 'catchall');
INSERT INTO `panel_settings` (`settingid`, `settinggroup`, `varname`, `value`) VALUES (5, 'customer', 'accountprefix', 'web');
INSERT INTO `panel_settings` (`settingid`, `settinggroup`, `varname`, `value`) VALUES (6, 'customer', 'ftpprefix', 'ftp');
INSERT INTO `panel_settings` (`settingid`, `settinggroup`, `varname`, `value`) VALUES (7, 'customer', 'mysqlprefix', 'sql');
INSERT INTO `panel_settings` (`settingid`, `settinggroup`, `varname`, `value`) VALUES (8, 'system', 'lastaccountnumber', '0');
INSERT INTO `panel_settings` (`settingid`, `settinggroup`, `varname`, `value`) VALUES (9, 'system', 'lastguid', '9999');
INSERT INTO `panel_settings` (`settingid`, `settinggroup`, `varname`, `value`) VALUES (10, 'system', 'documentroot_prefix', '/var/kunden/webs/');
INSERT INTO `panel_settings` (`settingid`, `settinggroup`, `varname`, `value`) VALUES (11, 'system', 'logfiles_directory', '/var/kunden/logs/');
INSERT INTO `panel_settings` (`settingid`, `settinggroup`, `varname`, `value`) VALUES (12, 'system', 'ipaddress', 'SERVERIP');
INSERT INTO `panel_settings` (`settingid`, `settinggroup`, `varname`, `value`) VALUES (13, 'system', 'apacheconf_directory', '/etc/apache/');
INSERT INTO `panel_settings` (`settingid`, `settinggroup`, `varname`, `value`) VALUES (14, 'system', 'apachereload_command', '/etc/init.d/apache reload');
INSERT INTO `panel_settings` (`settingid`, `settinggroup`, `varname`, `value`) VALUES (15, 'system', 'last_traffic_run', '000000');
INSERT INTO `panel_settings` (`settingid`, `settinggroup`, `varname`, `value`) VALUES (16, 'system', 'vmail_uid', '2000');
INSERT INTO `panel_settings` (`settingid`, `settinggroup`, `varname`, `value`) VALUES (17, 'system', 'vmail_gid', '2000');
INSERT INTO `panel_settings` (`settingid`, `settinggroup`, `varname`, `value`) VALUES (18, 'system', 'vmail_homedir', '/var/kunden/mail/');
INSERT INTO `panel_settings` (`settingid`, `settinggroup`, `varname`, `value`) VALUES (19, 'system', 'bindconf_directory', '/etc/bind/');
INSERT INTO `panel_settings` (`settingid`, `settinggroup`, `varname`, `value`) VALUES (20, 'system', 'bindreload_command', '/etc/init.d/bind9 reload');
INSERT INTO `panel_settings` (`settingid`, `settinggroup`, `varname`, `value`) VALUES (21, 'system', 'binddefaultzone', 'default.zone');
INSERT INTO `panel_settings` (`settingid`, `settinggroup`, `varname`, `value`) VALUES (22, 'panel', 'version', '1.2.2-cvs3');
INSERT INTO `panel_settings` (`settingid`, `settinggroup`, `varname`, `value`) VALUES (23, 'system', 'hostname', 'SERVERNAME');
INSERT INTO `panel_settings` (`settingid`, `settinggroup`, `varname`, `value`) VALUES (24, 'login', 'maxloginattempts', '3');
INSERT INTO `panel_settings` (`settingid`, `settinggroup`, `varname`, `value`) VALUES (25, 'login', 'deactivatetime', '900');
INSERT INTO `panel_settings` (`settingid`, `settinggroup`, `varname`, `value`) VALUES (26, 'panel', 'webmail_url', '');
INSERT INTO `panel_settings` (`settingid`, `settinggroup`, `varname`, `value`) VALUES (27, 'panel', 'webftp_url', '');
INSERT INTO `panel_settings` (`settingid`, `settinggroup`, `varname`, `value`) VALUES (28, 'panel', 'standardlanguage', 'English');


# --------------------------------------------------------

#
# Table structure for table `panel_tasks`
#

DROP TABLE IF EXISTS `panel_tasks`;
CREATE TABLE `panel_tasks` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `type` int(11) NOT NULL default '0',
  `data` text NOT NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM ;

#
# Dumping data for table `panel_tasks`
#


# --------------------------------------------------------

#
# Table structure for table `panel_traffic`
#

DROP TABLE IF EXISTS `panel_traffic`;
CREATE TABLE `panel_traffic` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `customerid` int(11) unsigned NOT NULL default '0',
  `year` int(4) unsigned zerofill NOT NULL default '0000',
  `month` int(2) unsigned zerofill NOT NULL default '00',
  `day` int(2) unsigned zerofill NOT NULL default '00',
  `http` bigint(30) unsigned NOT NULL default '0',
  `ftp_up` bigint(30) unsigned NOT NULL default '0',
  `ftp_down` bigint(30) unsigned NOT NULL default '0',
  `mail` bigint(30) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `customerid` (`customerid`)
) TYPE=MyISAM ;

#
# Dumping data for table `panel_traffic`
#


# --------------------------------------------------------

#
# Table structure for table `panel_navigation`
# 

DROP TABLE IF EXISTS `panel_navigation`;
CREATE TABLE `panel_navigation` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `area` varchar(20) NOT NULL default '',
  `parent_id` int(11) unsigned NOT NULL default '0',
  `lang` varchar(255) NOT NULL default '',
  `url` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=25 ;

# 
# Dumping data for table `panel_navigation`
# 

INSERT INTO `panel_navigation` (`id`, `area`, `parent_id`, `lang`, `url`) VALUES (1, 'login', 0, 'login;login', '');
INSERT INTO `panel_navigation` (`id`, `area`, `parent_id`, `lang`, `url`) VALUES (2, 'customer', 0, 'menue;main;main', 'customer_index.php');
INSERT INTO `panel_navigation` (`id`, `area`, `parent_id`, `lang`, `url`) VALUES (3, 'customer', 2, 'menue;main;changepassword', 'customer_index.php?page=change_password');
INSERT INTO `panel_navigation` (`id`, `area`, `parent_id`, `lang`, `url`) VALUES (4, 'customer', 2, 'login;logout', 'customer_index.php?action=logout');
INSERT INTO `panel_navigation` (`id`, `area`, `parent_id`, `lang`, `url`) VALUES (5, 'customer', 0, 'menue;email;email', 'customer_email.php');
INSERT INTO `panel_navigation` (`id`, `area`, `parent_id`, `lang`, `url`) VALUES (6, 'customer', 5, 'menue;email;pop', 'customer_email.php?page=pop');
INSERT INTO `panel_navigation` (`id`, `area`, `parent_id`, `lang`, `url`) VALUES (7, 'customer', 5, 'menue;email;forwarders', 'customer_email.php?page=forwarders');
INSERT INTO `panel_navigation` (`id`, `area`, `parent_id`, `lang`, `url`) VALUES (8, 'customer', 0, 'menue;mysql;mysql', 'customer_mysql.php');
INSERT INTO `panel_navigation` (`id`, `area`, `parent_id`, `lang`, `url`) VALUES (9, 'customer', 8, 'menue;mysql;databases', 'customer_mysql.php?page=mysqls');
INSERT INTO `panel_navigation` (`id`, `area`, `parent_id`, `lang`, `url`) VALUES (10, 'customer', 0, 'menue;domains;domains', 'customer_domains.php');
INSERT INTO `panel_navigation` (`id`, `area`, `parent_id`, `lang`, `url`) VALUES (11, 'customer', 10, 'menue;domains;settings', 'customer_domains.php?page=domains');
INSERT INTO `panel_navigation` (`id`, `area`, `parent_id`, `lang`, `url`) VALUES (12, 'customer', 0, 'menue;ftp;ftp', 'customer_ftp.php');
INSERT INTO `panel_navigation` (`id`, `area`, `parent_id`, `lang`, `url`) VALUES (13, 'customer', 12, 'menue;ftp;accounts', 'customer_ftp.php?page=accounts');
INSERT INTO `panel_navigation` (`id`, `area`, `parent_id`, `lang`, `url`) VALUES (14, 'customer', 0, 'menue;extras;extras', 'customer_extras.php');
INSERT INTO `panel_navigation` (`id`, `area`, `parent_id`, `lang`, `url`) VALUES (15, 'customer', 14, 'menue;extras;directoryprotection', 'customer_extras.php?page=htpasswds');
INSERT INTO `panel_navigation` (`id`, `area`, `parent_id`, `lang`, `url`) VALUES (16, 'customer', 14, 'menue;extras;pathoptions', 'customer_extras.php?page=htaccess');
INSERT INTO `panel_navigation` (`id`, `area`, `parent_id`, `lang`, `url`) VALUES (17, 'admin', 0, 'admin;overview', 'admin_index.php?page=overview');
INSERT INTO `panel_navigation` (`id`, `area`, `parent_id`, `lang`, `url`) VALUES (18, 'admin', 0, 'menue;main;changepassword', 'admin_index.php?page=change_password');
INSERT INTO `panel_navigation` (`id`, `area`, `parent_id`, `lang`, `url`) VALUES (19, 'admin', 0, 'admin;customers', 'admin_customers.php?page=customers');
INSERT INTO `panel_navigation` (`id`, `area`, `parent_id`, `lang`, `url`) VALUES (20, 'admin', 0, 'admin;domains', 'admin_domains.php?page=domains');
INSERT INTO `panel_navigation` (`id`, `area`, `parent_id`, `lang`, `url`) VALUES (21, 'admin', 0, 'admin;admins', 'admin_admins.php?page=admins');
INSERT INTO `panel_navigation` (`id`, `area`, `parent_id`, `lang`, `url`) VALUES (22, 'admin', 0, 'admin;configfiles;serverconfiguration', 'admin_configfiles.php?page=configfiles');
INSERT INTO `panel_navigation` (`id`, `area`, `parent_id`, `lang`, `url`) VALUES (23, 'admin', 0, 'admin;serversettings', 'admin_settings.php?page=settings');
INSERT INTO `panel_navigation` (`id`, `area`, `parent_id`, `lang`, `url`) VALUES (24, 'admin', 0, 'login;logout', 'admin_index.php?action=logout');

#
# Table structure for table `panel_languages`
# 

DROP TABLE IF EXISTS `panel_languages`;
CREATE TABLE `panel_languages` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `language` varchar(30) NOT NULL default '',
  `file` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM ;

# 
# Dumping data for table `panel_languages`
# 

INSERT INTO `panel_languages` VALUES (1, 'Deutsch', 'lng/german.lng.php');
INSERT INTO `panel_languages` VALUES (2, 'English', 'lng/english.lng.php');
INSERT INTO `panel_languages` VALUES (3, 'Francais', 'lng/french.lng.php');
INSERT INTO `panel_languages` VALUES (4, 'Chinese', 'lng/zh-cn.lng.php');
               
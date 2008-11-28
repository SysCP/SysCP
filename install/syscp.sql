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
  `username` varchar(255) NOT NULL default '',
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
  `email` varchar(255) NOT NULL default '',
  `username` varchar(255) NOT NULL default '',
  `password` varchar(128) NOT NULL default '',
  `password_enc` varchar(128) NOT NULL default '',
  `uid` int(11) NOT NULL default '0',
  `gid` int(11) NOT NULL default '0',
  `homedir` varchar(255) NOT NULL default '',
  `maildir` varchar(255) NOT NULL default '',
  `postfix` enum('Y','N') NOT NULL default 'Y',
  `domainid` int(11) NOT NULL default '0',
  `customerid` int(11) NOT NULL default '0',
  `quota` bigint(13) NOT NULL default '0',
  `pop3` tinyint(1) NOT NULL default '1',
  `imap` tinyint(1) NOT NULL default '1',
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
  `email` varchar(255) NOT NULL default '',
  `email_full` varchar(255) NOT NULL default '',
  `destination` text NOT NULL,
  `domainid` int(11) NOT NULL default '0',
  `customerid` int(11) NOT NULL default '0',
  `popaccountid` int(11) NOT NULL default '0',
  `iscatchall` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `email` (`email`)
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
  `def_language` varchar(255) NOT NULL default '',
  `ip` tinyint(4) NOT NULL default '-1',
  `customers` int(15) NOT NULL default '0',
  `customers_used` int(15) NOT NULL default '0',
  `customers_see_all` tinyint(1) NOT NULL default '0',
  `domains` int(15) NOT NULL default '0',
  `domains_used` int(15) NOT NULL default '0',
  `domains_see_all` tinyint(1) NOT NULL default '0',
  `caneditphpsettings` tinyint(1) NOT NULL default '0',
  `change_serversettings` tinyint(1) NOT NULL default '0',
  `edit_billingdata` tinyint(1) NOT NULL DEFAULT '0',
  `diskspace` int(15) NOT NULL default '0',
  `diskspace_used` int(15) NOT NULL default '0',
  `mysqls` int(15) NOT NULL default '0',
  `mysqls_used` int(15) NOT NULL default '0',
  `emails` int(15) NOT NULL default '0',
  `emails_used` int(15) NOT NULL default '0',
  `email_accounts` int(15) NOT NULL default '0',
  `email_accounts_used` int(15) NOT NULL default '0',
  `email_forwarders` int(15) NOT NULL default '0',
  `email_forwarders_used` int(15) NOT NULL default '0',
  `email_quota` bigint(13) NOT NULL default '0',
  `email_quota_used` bigint(13) NOT NULL default '0',
  `ftps` int(15) NOT NULL default '0',
  `ftps_used` int(15) NOT NULL default '0',
  `tickets` int(15) NOT NULL default '-1',
  `tickets_used` int(15) NOT NULL default '0',
  `subdomains` int(15) NOT NULL default '0',
  `subdomains_used` int(15) NOT NULL default '0',
  `traffic` int(15) NOT NULL default '0',
  `traffic_used` int(15) NOT NULL default '0',
  `deactivated` tinyint(1) NOT NULL default '0',
  `lastlogin_succ` int(11) unsigned NOT NULL default '0',
  `lastlogin_fail` int(11) unsigned NOT NULL default '0',
  `loginfail_count` int(11) unsigned NOT NULL default '0',
  `reportsent` tinyint(4) unsigned NOT NULL default '0',
  `firstname` varchar( 255 ) NOT NULL default '',
  `title` varchar( 255 ) NOT NULL default '',
  `company` varchar( 255 ) NOT NULL default '',
  `street` varchar( 255 ) NOT NULL default '',
  `zipcode` varchar( 255 ) NOT NULL default '',
  `city` varchar( 255 ) NOT NULL default '',
  `country` varchar( 255 ) NOT NULL default '',
  `phone` varchar( 255 ) NOT NULL default '',
  `fax` varchar( 255 ) NOT NULL default '',
  `taxid` varchar( 255 ) NOT NULL default '',
  `contract_date` date NOT NULL,
  `contract_number` varchar( 255 ) NOT NULL default '',
  `contract_details` text NOT NULL default '',
  `included_domains_qty` int( 11 ) NOT NULL default '0',
  `included_domains_tld` varchar( 255 ) NOT NULL default '',
  `additional_traffic_fee` decimal( 10,2 ) NOT NULL default '0',
  `additional_traffic_unit` bigint( 30 ) NOT NULL default '0',
  `additional_diskspace_fee` decimal( 10,2 ) NOT NULL default '0',
  `additional_diskspace_unit` bigint( 30 ) NOT NULL default '0',
  `taxclass` int( 11 ) NOT NULL default '0',
  `setup_fee` decimal( 10,2 ) NOT NULL default '0',
  `interval_fee` decimal( 10,2 ) NOT NULL default '0',
  `interval_length` int( 11 ) NOT NULL default '0',
  `interval_type` varchar( 1 ) NOT NULL default 'm',
  `interval_payment` tinyint( 1 ) NOT NULL default '0',
  `calc_tax` tinyint( 1 ) NOT NULL default '1',
  `term_of_payment` int( 11 ) NOT NULL default '0',
  `payment_every` int( 11 ) NOT NULL default '0',
  `payment_method` int( 11 ) NOT NULL default '0',
  `bankaccount_holder` text NOT NULL default '',
  `bankaccount_number` varchar( 255 ) NOT NULL default '',
  `bankaccount_blz` varchar( 255 ) NOT NULL default '',
  `bankaccount_bank` varchar( 255 ) NOT NULL default '',
  `service_active` tinyint( 1 ) NOT NULL default '0',
  `servicestart_date` date NOT NULL,
  `serviceend_date` date NOT NULL,
  `lastinvoiced_date` date NOT NULL,
  `lastinvoiced_date_traffic` date NOT NULL,
  `lastinvoiced_date_diskspace` date NOT NULL,
  `customer_categories_once` text NOT NULL default '',
  `customer_categories_period` text NOT NULL default '',
  `invoice_fee` decimal( 10,2 ) NOT NULL default '0',
  `invoice_fee_hosting` decimal( 10,2 ) NOT NULL default '0',
  `invoice_fee_hosting_customers` decimal( 10,2 ) NOT NULL default '0',
  `invoice_fee_domains` decimal( 10,2 ) NOT NULL default '0',
  `invoice_fee_traffic` decimal( 10,2 ) NOT NULL default '0',
  `invoice_fee_diskspace` decimal( 10,2 ) NOT NULL default '0',
  `invoice_fee_other` decimal( 10,2 ) NOT NULL default '0',
  `can_manage_aps_packages` tinyint(1) NOT NULL default '1',
  `aps_packages` int(5) NOT NULL default '0',
  `aps_packages_used` int(5) NOT NULL default '0',
   PRIMARY KEY  (`adminid`),
   UNIQUE KEY `loginname` (`loginname`)
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
  `firstname` varchar(255) NOT NULL default '',
  `company` varchar(255) NOT NULL default '',
  `street` varchar(255) NOT NULL default '',
  `zipcode` varchar(255) NOT NULL default '',
  `city` varchar(255) NOT NULL default '',
  `phone` varchar(255) NOT NULL default '',
  `fax` varchar(255) NOT NULL default '',
  `email` varchar(255) NOT NULL default '',
  `customernumber` varchar(255) NOT NULL default '',
  `def_language` varchar(255) NOT NULL default '',
  `diskspace` bigint(30) NOT NULL default '0',
  `diskspace_used` bigint(30) NOT NULL default '0',
  `mysqls` int(15) NOT NULL default '0',
  `mysqls_used` int(15) NOT NULL default '0',
  `emails` int(15) NOT NULL default '0',
  `emails_used` int(15) NOT NULL default '0',
  `email_accounts` int(15) NOT NULL default '0',
  `email_accounts_used` int(15) NOT NULL default '0',
  `email_forwarders` int(15) NOT NULL default '0',
  `email_forwarders_used` int(15) NOT NULL default '0',
  `email_quota` bigint(13) NOT NULL default '0',
  `email_quota_used` bigint(13) NOT NULL default '0',
  `ftps` int(15) NOT NULL default '0',
  `ftps_used` int(15) NOT NULL default '0',
  `tickets` int(15) NOT NULL default '0',
  `tickets_used` int(15) NOT NULL default '0',
  `subdomains` int(15) NOT NULL default '0',
  `subdomains_used` int(15) NOT NULL default '0',
  `traffic` bigint(30) NOT NULL default '0',
  `traffic_used` bigint(30) NOT NULL default '0',
  `documentroot` varchar(255) NOT NULL default '',
  `standardsubdomain` int(11) NOT NULL default '0',
  `guid` int(5) NOT NULL default '0',
  `ftp_lastaccountnumber` int(11) NOT NULL default '0',
  `mysql_lastaccountnumber` int(11) NOT NULL default '0',
  `deactivated` tinyint(1) NOT NULL default '0',
  `phpenabled` tinyint(1) NOT NULL default '1',
  `lastlogin_succ` int(11) unsigned NOT NULL default '0',
  `lastlogin_fail` int(11) unsigned NOT NULL default '0',
  `loginfail_count` int(11) unsigned NOT NULL default '0',
  `reportsent` tinyint(4) unsigned NOT NULL default '0',
  `pop3` tinyint(1) NOT NULL default '1',
  `imap` tinyint(1) NOT NULL default '1',
  `taxid` varchar( 255 ) NOT NULL default '',
  `title` varchar( 255 ) NOT NULL default '',
  `country` varchar( 255 ) NOT NULL default '',
  `additional_service_description` text NOT NULL default '',
  `contract_date` date NOT NULL,
  `contract_number` varchar( 255 ) NOT NULL default '',
  `contract_details` text NOT NULL default '',
  `included_domains_qty` int( 11 ) NOT NULL default '0',
  `included_domains_tld` varchar( 255 ) NOT NULL default '',
  `additional_traffic_fee` decimal( 10,2 ) NOT NULL default '0',
  `additional_traffic_unit` bigint( 30 ) NOT NULL default '0',
  `additional_diskspace_fee` decimal( 10,2 ) NOT NULL default '0',
  `additional_diskspace_unit` bigint( 30 ) NOT NULL default '0',
  `taxclass` int( 11 ) NOT NULL default '0',
  `setup_fee` decimal( 10,2 ) NOT NULL default '0',
  `interval_fee` decimal( 10,2 ) NOT NULL default '0',
  `interval_length` int( 11 ) NOT NULL default '0',
  `interval_type` varchar( 1 ) NOT NULL default 'm',
  `interval_payment` tinyint( 1 ) NOT NULL default '0',
  `calc_tax` tinyint( 1 ) NOT NULL default '1',
  `term_of_payment` int( 11 ) NOT NULL default '0',
  `payment_every` int( 11 ) NOT NULL default '0',
  `payment_method` int( 11 ) NOT NULL default '0',
  `bankaccount_holder` text NOT NULL default '',
  `bankaccount_number` varchar( 255 ) NOT NULL default '',
  `bankaccount_blz` varchar( 255 ) NOT NULL default '',
  `bankaccount_bank` varchar( 255 ) NOT NULL default '',
  `service_active` tinyint( 1 ) NOT NULL default '0',
  `servicestart_date` date NOT NULL,
  `serviceend_date` date NOT NULL,
  `lastinvoiced_date` date NOT NULL,
  `lastinvoiced_date_traffic` date NOT NULL,
  `lastinvoiced_date_diskspace` date NOT NULL,
  `invoice_fee` decimal( 10,2 ) NOT NULL default '0',
  `invoice_fee_hosting` decimal( 10,2 ) NOT NULL default '0',
  `invoice_fee_domains` decimal( 10,2 ) NOT NULL default '0',
  `invoice_fee_traffic` decimal( 10,2 ) NOT NULL default '0',
  `invoice_fee_diskspace` decimal( 10,2 ) NOT NULL default '0',
  `invoice_fee_other` decimal( 10,2 ) NOT NULL default '0',
  `aps_packages` int(5) NOT NULL default '0',
  `aps_packages_used` int(5) NOT NULL default '0',
   PRIMARY KEY  (`customerid`),
   UNIQUE KEY `loginname` (`loginname`)
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
  `description` varchar(255) NOT NULL default '',
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
  `aliasdomain` int(11) unsigned NULL,
  `documentroot` varchar(255) NOT NULL default '',
  `ipandport` int(11) unsigned NOT NULL default '1',
  `isbinddomain` tinyint(1) NOT NULL default '0',
  `isemaildomain` tinyint(1) NOT NULL default '0',
  `email_only` tinyint(1) NOT NULL default '0',
  `iswildcarddomain` tinyint(1) NOT NULL default '0',
  `subcanemaildomain` tinyint(1) NOT NULL default '0',
  `caneditdomain` tinyint(1) NOT NULL default '1',
  `zonefile` varchar(255) NOT NULL default '',
  `dkim` tinyint(1) NOT NULL default '0',
  `dkim_id` int(11) unsigned NOT NULL,
  `dkim_privkey` text NOT NULL,
  `dkim_pubkey` text NOT NULL,
  `wwwserveralias` tinyint(1) NOT NULL default '1',
  `parentdomainid` int(11) unsigned NOT NULL default '0',
  `openbasedir` tinyint(1) NOT NULL default '0',
  `openbasedir_path` tinyint(1) NOT NULL default '0',
  `safemode` tinyint(1) NOT NULL default '0',
  `speciallogfile` tinyint(1) NOT NULL default '0',
  `ssl` tinyint(4) NOT NULL default '0',
  `ssl_redirect` tinyint(4) NOT NULL default '0',
  `ssl_ipandport` tinyint(4) NOT NULL default '0',
  `specialsettings` text NOT NULL,
  `deactivated` tinyint(1) NOT NULL default '0',
  `bindserial` varchar(10) NOT NULL default '2000010100',
  `add_date` int( 11 ) NOT NULL default '0',
  `registration_date` date NOT NULL,
  `taxclass` int( 11 ) NOT NULL default '0',
  `setup_fee` decimal( 10,2 ) NOT NULL default '0',
  `interval_fee` decimal( 10,2 ) NOT NULL default '0',
  `interval_length` int( 11 ) NOT NULL default '0',
  `interval_type` varchar( 1 ) NOT NULL default 'y',
  `interval_payment` tinyint( 1 ) NOT NULL default '0',
  `service_active` tinyint( 1 ) NOT NULL default '0',
  `servicestart_date` date NOT NULL,
  `serviceend_date` date NOT NULL,
  `lastinvoiced_date` date NOT NULL,
  `phpsettingid` INT( 11 ) UNSIGNED NOT NULL DEFAULT '1',
  `mod_fcgid_starter` int(4) default '-1',
  `mod_fcgid_maxrequests` int(4) default '-1',
  PRIMARY KEY  (`id`),
  KEY `customerid` (`customerid`),
  KEY `parentdomain` (`parentdomainid`),
  KEY `domain` (`domain`)
) TYPE=MyISAM ;

#
# Dumping data for table `panel_domains`
#


# --------------------------------------------------------

#
# Table structure for table `panel_ipsandports`
#
DROP TABLE IF EXISTS `panel_ipsandports`;
CREATE TABLE `panel_ipsandports` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `ip` varchar(39) NOT NULL default '',
  `port` int(5) NOT NULL default '80',
  `listen_statement` tinyint(1) NOT NULL default '0',
  `namevirtualhost_statement` tinyint(1) NOT NULL default '0',
  `vhostcontainer` tinyint(1) NOT NULL default '0',
  `vhostcontainer_servername_statement` tinyint(1) NOT NULL default '0',
  `specialsettings` text NOT NULL,
  `ssl` tinyint(4) NOT NULL default '0',
  `ssl_cert` tinytext,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM ;

#
# Dumping data for table `panel_ipsandports`
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
  `error404path` varchar(255) NOT NULL default '',
  `error403path` varchar(255) NOT NULL default '',
  `error500path` varchar(255) NOT NULL default '',
  `error401path` varchar(255) NOT NULL default '',
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
  `ipaddress` varchar(255) NOT NULL default '',
  `useragent` varchar(255) NOT NULL default '',
  `lastactivity` int(11) unsigned NOT NULL default '0',
  `lastpaging` varchar(255) NOT NULL default '',
  `formtoken` char(32) NOT NULL default '',
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
INSERT INTO `panel_settings` (`settingid`, `settinggroup`, `varname`, `value`) VALUES (5, 'customer', 'accountprefix', 'web');
INSERT INTO `panel_settings` (`settingid`, `settinggroup`, `varname`, `value`) VALUES (6, 'customer', 'ftpprefix', 'ftp');
INSERT INTO `panel_settings` (`settingid`, `settinggroup`, `varname`, `value`) VALUES (7, 'customer', 'mysqlprefix', 'sql');
INSERT INTO `panel_settings` (`settingid`, `settinggroup`, `varname`, `value`) VALUES (8, 'system', 'lastaccountnumber', '0');
INSERT INTO `panel_settings` (`settingid`, `settinggroup`, `varname`, `value`) VALUES (9, 'system', 'lastguid', '9999');
INSERT INTO `panel_settings` (`settingid`, `settinggroup`, `varname`, `value`) VALUES (10, 'system', 'documentroot_prefix', '/var/kunden/webs/');
INSERT INTO `panel_settings` (`settingid`, `settinggroup`, `varname`, `value`) VALUES (11, 'system', 'logfiles_directory', '/var/kunden/logs/');
INSERT INTO `panel_settings` (`settingid`, `settinggroup`, `varname`, `value`) VALUES (12, 'system', 'ipaddress', 'SERVERIP');
INSERT INTO `panel_settings` (`settingid`, `settinggroup`, `varname`, `value`) VALUES (14, 'system', 'apachereload_command', '/etc/init.d/apache reload');
INSERT INTO `panel_settings` (`settingid`, `settinggroup`, `varname`, `value`) VALUES (15, 'system', 'last_traffic_run', '000000');
INSERT INTO `panel_settings` (`settingid`, `settinggroup`, `varname`, `value`) VALUES (16, 'system', 'vmail_uid', '2000');
INSERT INTO `panel_settings` (`settingid`, `settinggroup`, `varname`, `value`) VALUES (17, 'system', 'vmail_gid', '2000');
INSERT INTO `panel_settings` (`settingid`, `settinggroup`, `varname`, `value`) VALUES (18, 'system', 'vmail_homedir', '/var/kunden/mail/');
INSERT INTO `panel_settings` (`settingid`, `settinggroup`, `varname`, `value`) VALUES (19, 'system', 'bindconf_directory', '/etc/bind/');
INSERT INTO `panel_settings` (`settingid`, `settinggroup`, `varname`, `value`) VALUES (20, 'system', 'bindreload_command', '/etc/init.d/bind9 reload');
INSERT INTO `panel_settings` (`settingid`, `settinggroup`, `varname`, `value`) VALUES (22, 'panel', 'version', '1.2.19-svn42');
INSERT INTO `panel_settings` (`settingid`, `settinggroup`, `varname`, `value`) VALUES (23, 'system', 'hostname', 'SERVERNAME');
INSERT INTO `panel_settings` (`settingid`, `settinggroup`, `varname`, `value`) VALUES (24, 'login', 'maxloginattempts', '3');
INSERT INTO `panel_settings` (`settingid`, `settinggroup`, `varname`, `value`) VALUES (25, 'login', 'deactivatetime', '900');
INSERT INTO `panel_settings` (`settingid`, `settinggroup`, `varname`, `value`) VALUES (26, 'panel', 'webmail_url', '');
INSERT INTO `panel_settings` (`settingid`, `settinggroup`, `varname`, `value`) VALUES (27, 'panel', 'webftp_url', '');
INSERT INTO `panel_settings` (`settingid`, `settinggroup`, `varname`, `value`) VALUES (28, 'panel', 'standardlanguage', 'English');
INSERT INTO `panel_settings` (`settingid`, `settinggroup`, `varname`, `value`) VALUES (29, 'system', 'mysql_access_host', 'localhost');
INSERT INTO `panel_settings` (`settingid`, `settinggroup`, `varname`, `value`) VALUES (30, 'panel', 'pathedit', 'Manual');
INSERT INTO `panel_settings` (`settingid`, `settinggroup`, `varname`, `value`) VALUES (32, 'system', 'lastcronrun', '');
INSERT INTO `panel_settings` (`settingid`, `settinggroup`, `varname`, `value`) VALUES (33, 'panel', 'paging', '20');
INSERT INTO `panel_settings` (`settingid`, `settinggroup`, `varname`, `value`) VALUES (34, 'system', 'defaultip', '0');
INSERT INTO `panel_settings` (`settingid`, `settinggroup`, `varname`, `value`) VALUES (35, 'system', 'phpappendopenbasedir', '/tmp/');
INSERT INTO `panel_settings` (`settingid`, `settinggroup`, `varname`, `value`) VALUES (36, 'panel', 'natsorting', '1');
INSERT INTO `panel_settings` (`settingid`, `settinggroup`, `varname`, `value`) VALUES (37, 'system', 'deactivateddocroot', '');
INSERT INTO `panel_settings` (`settingid`, `settinggroup`, `varname`, `value`) VALUES (38, 'system', 'mailpwcleartext', '1');
INSERT INTO `panel_settings` (`settingid`, `settinggroup`, `varname`, `value`) VALUES (39, 'system', 'last_tasks_run', '000000');
INSERT INTO `panel_settings` (`settingid`, `settinggroup`, `varname`, `value`) VALUES (40, 'customer', 'ftpatdomain', '0');
INSERT INTO `panel_settings` (`settingid`, `settinggroup`, `varname`, `value`) VALUES (41, 'system', 'nameservers', '');
INSERT INTO `panel_settings` (`settingid`, `settinggroup`, `varname`, `value`) VALUES (42, 'system', 'mxservers', '');
INSERT INTO `panel_settings` (`settingid`, `settinggroup`, `varname`, `value`) VALUES (43, 'system', 'mod_log_sql', '0');
INSERT INTO `panel_settings` (`settingid`, `settinggroup`, `varname`, `value`) VALUES (44, 'system', 'mod_fcgid', '0');
INSERT INTO `panel_settings` (`settingid`, `settinggroup`, `varname`, `value`) VALUES (45, 'panel', 'sendalternativemail', '0');
INSERT INTO `panel_settings` (`settingid`, `settinggroup`, `varname`, `value`) VALUES (46, 'system', 'apacheconf_vhost', '/etc/apache/vhosts.conf');
INSERT INTO `panel_settings` (`settingid`, `settinggroup`, `varname`, `value`) VALUES (47, 'system', 'apacheconf_diroptions', '/etc/apache/diroptions.conf');
INSERT INTO `panel_settings` (`settingid`, `settinggroup`, `varname`, `value`) VALUES (48, 'system', 'apacheconf_htpasswddir', '/etc/apache/htpasswd/');
INSERT INTO `panel_settings` (`settingid`, `settinggroup`, `varname`, `value`) VALUES (49, 'system', 'webalizer_quiet', '2');
INSERT INTO `panel_settings` (`settingid`, `settinggroup`, `varname`, `value`) VALUES (50, 'ticket', 'noreply_email', 'NO-REPLY@SERVERNAME');
INSERT INTO `panel_settings` (`settingid`, `settinggroup`, `varname`, `value`) VALUES (51, 'ticket', 'worktime_all', '1');
INSERT INTO `panel_settings` (`settingid`, `settinggroup`, `varname`, `value`) VALUES (52, 'ticket', 'worktime_begin', '00:00');
INSERT INTO `panel_settings` (`settingid`, `settinggroup`, `varname`, `value`) VALUES (53, 'ticket', 'worktime_end', '23:59');
INSERT INTO `panel_settings` (`settingid`, `settinggroup`, `varname`, `value`) VALUES (54, 'ticket', 'worktime_sat', '0');
INSERT INTO `panel_settings` (`settingid`, `settinggroup`, `varname`, `value`) VALUES (55, 'ticket', 'worktime_sun', '0');
INSERT INTO `panel_settings` (`settingid`, `settinggroup`, `varname`, `value`) VALUES (56, 'ticket', 'archiving_days', '5');
INSERT INTO `panel_settings` (`settingid`, `settinggroup`, `varname`, `value`) VALUES (57, 'system', 'last_archive_run', '000000');
INSERT INTO `panel_settings` (`settingid`, `settinggroup`, `varname`, `value`) VALUES (58, 'ticket', 'enabled', '1');
INSERT INTO `panel_settings` (`settingid`, `settinggroup`, `varname`, `value`) VALUES (59, 'ticket', 'concurrently_open', '5');
INSERT INTO `panel_settings` (`settingid`, `settinggroup`, `varname`, `value`) VALUES (60, 'ticket', 'noreply_name', 'SysCP Support');
INSERT INTO `panel_settings` (`settingid`, `settinggroup`, `varname`, `value`) VALUES (61, 'system', 'mod_fcgid_configdir', '/var/www/php-fcgi-scripts');
INSERT INTO `panel_settings` (`settingid`, `settinggroup`, `varname`, `value`) VALUES (62, 'system', 'mod_fcgid_tmpdir', '/var/kunden/tmp');
INSERT INTO `panel_settings` (`settingid`, `settinggroup`, `varname`, `value`) VALUES (63, 'ticket', 'reset_cycle', '2');
INSERT INTO `panel_settings` (`settingid`, `settinggroup`, `varname`, `value`) VALUES (64, 'panel', 'no_robots', '1');
INSERT INTO `panel_settings` (`settingid`, `settinggroup`, `varname`, `value`) VALUES (65, 'logger', 'enabled', '1');
INSERT INTO `panel_settings` (`settingid`, `settinggroup`, `varname`, `value`) VALUES (66, 'logger', 'log_cron', '0');
INSERT INTO `panel_settings` (`settingid`, `settinggroup`, `varname`, `value`) VALUES (67, 'logger', 'logfile', '');
INSERT INTO `panel_settings` (`settingid`, `settinggroup`, `varname`, `value`) VALUES (68, 'logger', 'logtypes', 'syslog,mysql');
INSERT INTO `panel_settings` (`settingid`, `settinggroup`, `varname`, `value`) VALUES (69, 'logger', 'severity', '1');
INSERT INTO `panel_settings` (`settingid`, `settinggroup`, `varname`, `value`) VALUES (70, 'system','ssl_cert_file','/etc/apache2/apache2.pem');
INSERT INTO `panel_settings` (`settingid`, `settinggroup`, `varname`, `value`) VALUES (71, 'system','use_ssl','1');
INSERT INTO `panel_settings` (`settingid`, `settinggroup`, `varname`, `value`) VALUES (72, 'system','openssl_cnf','[ req ]\r\ndefault_bits = 1024\r\ndistinguished_name = req_distinguished_name\r\nattributes = req_attributes\r\nprompt = no\r\noutput_password =\r\ninput_password =\r\n[ req_distinguished_name ]\r\nC = DE\r\nST = syscp\r\nL = syscp    \r\nO = Testcertificate\r\nOU = syscp        \r\nCN = @@domain_name@@\r\nemailAddress = @@email@@    \r\n[ req_attributes ]\r\nchallengePassword =\r\n');
INSERT INTO `panel_settings` (`settingid`, `settinggroup`, `varname`, `value`) VALUES (73, 'system', 'default_vhostconf', '');
INSERT INTO `panel_settings` (`settingid`, `settinggroup`, `varname`, `value`) VALUES (74, 'system', 'mail_quota_enabled', '0');
INSERT INTO `panel_settings` (`settingid`, `settinggroup`, `varname`, `value`) VALUES (75, 'system', 'mail_quota', '100');
INSERT INTO `panel_settings` (`settingid`, `settinggroup`, `varname`, `value`) VALUES (76, 'panel', 'decimal_places', '4');
INSERT INTO `panel_settings` (`settingid`, `settinggroup`, `varname`, `value`) VALUES (77, 'dkim', 'use_dkim', '0');
INSERT INTO `panel_settings` (`settingid`, `settinggroup`, `varname`, `value`) VALUES (78, 'system', 'webalizer_enabled', '1');
INSERT INTO `panel_settings` (`settingid`, `settinggroup`, `varname`, `value`) VALUES (79, 'system', 'awstats_enabled', '0');
INSERT INTO `panel_settings` (`settingid`, `settinggroup`, `varname`, `value`) VALUES (80, 'system', 'awstats_domain_file', '/etc/awstats/');
INSERT INTO `panel_settings` (`settingid`, `settinggroup`, `varname`, `value`) VALUES (81, 'system', 'awstats_model_file', '/etc/awstats/awstats.model.conf.syscp');
INSERT INTO `panel_settings` (`settingid`, `settinggroup`, `varname`, `value`) VALUES (82, 'dkim', 'dkim_prefix', '/etc/postfix/dkim/');
INSERT INTO `panel_settings` (`settingid`, `settinggroup`, `varname`, `value`) VALUES (83, 'dkim', 'dkim_domains', 'domains');
INSERT INTO `panel_settings` (`settingid`, `settinggroup`, `varname`, `value`) VALUES (84, 'dkim', 'dkim_dkimkeys', 'dkim-keys.conf');
INSERT INTO `panel_settings` (`settingid`, `settinggroup`, `varname`, `value`) VALUES (85, 'dkim', 'dkimrestart_command', '/etc/init.d/dkim-filter restart');
INSERT INTO `panel_settings` (`settingid`, `settinggroup`, `varname`, `value`) VALUES (86, 'panel', 'unix_names', '1');
INSERT INTO `panel_settings` (`settingid`, `settinggroup`, `varname`, `value`) VALUES (87, 'panel', 'allow_preset', '1');
INSERT INTO `panel_settings` (`settingid`, `settinggroup`, `varname`, `value`) VALUES (88, 'system', 'awstats_path', '/usr/share/awstats/VERSION/webroot/cgi-bin/');
INSERT INTO `panel_settings` (`settingid`, `settinggroup`, `varname`, `value`) VALUES (89, 'system', 'awstats_updateall_command', '/usr/bin/awstats_updateall.pl');
INSERT INTO `panel_settings` (`settingid`, `settinggroup`, `varname`, `value`) VALUES (90, 'billing', 'invoicenumber_count', '0');
INSERT INTO `panel_settings` (`settingid`, `settinggroup`, `varname`, `value`) VALUES (91, 'panel', 'allow_preset_admin', '0');
INSERT INTO `panel_settings` (`settingid`, `settinggroup`, `varname`, `value`) VALUES (92, 'billing', 'activate_billing', '0');
INSERT INTO `panel_settings` (`settingid`, `settinggroup`, `varname`, `value`) VALUES (93, 'billing', 'highlight_inactive', '0');
INSERT INTO `panel_settings` (`settingid`, `settinggroup`, `varname`, `value`) VALUES (94, 'system', 'httpuser', 'www-data');
INSERT INTO `panel_settings` (`settingid`, `settinggroup`, `varname`, `value`) VALUES (95, 'system', 'httpgroup', 'www-data');
INSERT INTO `panel_settings` (`settingid`, `settinggroup`, `varname`, `value`) VALUES (96, 'system', 'webserver', 'apache2');
INSERT INTO `panel_settings` (`settingid`, `settinggroup`, `varname`, `value`) VALUES (97, 'autoresponder', 'autoresponder_active', '0');
INSERT INTO `panel_settings` (`settingid`, `settinggroup`, `varname`, `value`) VALUES (98, 'autoresponder', 'last_autoresponder_run', '0');
INSERT INTO `panel_settings` (`settingid`, `settinggroup`, `varname`, `value`) VALUES (99, 'admin', 'show_version_login', '0');
INSERT INTO `panel_settings` (`settingid`, `settinggroup`, `varname`, `value`) VALUES (100, 'admin', 'show_version_footer', '0');
INSERT INTO `panel_settings` (`settingid`, `settinggroup`, `varname`, `value`) VALUES (101, 'admin', 'syscp_graphic', 'images/header.gif');
INSERT INTO `panel_settings` (`settingid`, `settinggroup`, `varname`, `value`) VALUES (102, 'system', 'mod_fcgid_wrapper', '0');
INSERT INTO `panel_settings` (`settingid`, `settinggroup`, `varname`, `value`) VALUES (103, 'system', 'mod_fcgid_starter', '0');
INSERT INTO `panel_settings` (`settingid`, `settinggroup`, `varname`, `value`) VALUES (104, 'system', 'mod_fcgid_peardir', '/usr/share/php/:/usr/share/php5/');
INSERT INTO `panel_settings` (`settingid`, `settinggroup`, `varname`, `value`) VALUES (105, 'system', 'index_file_extension', 'html');
INSERT INTO `panel_settings` (`settingid`, `settinggroup`, `varname`, `value`) VALUES (106, 'aps', 'items_per_page', '20');
INSERT INTO `panel_settings` (`settingid`, `settinggroup`, `varname`, `value`) VALUES (107, 'aps', 'upload_fields', '5');
INSERT INTO `panel_settings` (`settingid`, `settinggroup`, `varname`, `value`) VALUES (108, 'aps', 'aps_active', '0');
INSERT INTO `panel_settings` (`settingid`, `settinggroup`, `varname`, `value`) VALUES (109, 'aps', 'php-extension', '');
INSERT INTO `panel_settings` (`settingid`, `settinggroup`, `varname`, `value`) VALUES (110, 'aps', 'php-configuration', '');
INSERT INTO `panel_settings` (`settingid`, `settinggroup`, `varname`, `value`) VALUES (111, 'aps', 'webserver-htaccess', '');
INSERT INTO `panel_settings` (`settingid`, `settinggroup`, `varname`, `value`) VALUES (112, 'aps', 'php-function', '');
INSERT INTO `panel_settings` (`settingid`, `settinggroup`, `varname`, `value`) VALUES (113, 'aps', 'webserver-module', '');
INSERT INTO `panel_settings` (`settingid`, `settinggroup`, `varname`, `value`) VALUES (114, 'system', 'realtime_port', '0');
INSERT INTO `panel_settings` (`settingid`, `settinggroup`, `varname`, `value`) VALUES (115, 'session', 'allow_multiple_login', '0');
INSERT INTO `panel_settings` (`settingid`, `settinggroup`, `varname`, `value`) VALUES (116, 'panel', 'allow_domain_change_admin', '0');
INSERT INTO `panel_settings` (`settingid`, `settinggroup`, `varname`, `value`) VALUES (117, 'panel', 'allow_domain_change_customer', '0');
INSERT INTO `panel_settings` (`settingid`, `settinggroup`, `varname`, `value`) VALUES (118, 'system', 'mod_fcgid_maxrequests', '250');

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
# Table structure for table `panel_templates`
#

DROP TABLE IF EXISTS `panel_templates`;
CREATE TABLE `panel_templates` (
  `id` int(11) NOT NULL auto_increment,
  `adminid` int(11) NOT NULL default '0',
  `language` varchar(255) NOT NULL default '',
  `templategroup` varchar(255) NOT NULL default '',
  `varname` varchar(255) NOT NULL default '',
  `value` longtext NOT NULL,
  PRIMARY KEY  (id),
  KEY adminid (adminid)
) TYPE=MyISAM;

#
# Dumping data for table `panel_templates`
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
  `stamp` int(11) unsigned NOT NULL default '0',
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
# Table structure for table `panel_traffic_admins`
#

DROP TABLE IF EXISTS `panel_traffic_admins`;
CREATE TABLE `panel_traffic_admins` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `adminid` int(11) unsigned NOT NULL default '0',
  `year` int(4) unsigned zerofill NOT NULL default '0000',
  `month` int(2) unsigned zerofill NOT NULL default '00',
  `day` int(2) unsigned zerofill NOT NULL default '00',
  `stamp` int(11) unsigned NOT NULL default '0',
  `http` bigint(30) unsigned NOT NULL default '0',
  `ftp_up` bigint(30) unsigned NOT NULL default '0',
  `ftp_down` bigint(30) unsigned NOT NULL default '0',
  `mail` bigint(30) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `adminid` (`adminid`)
) TYPE=MyISAM ;

#
# Dumping data for table `panel_traffic_admins`
#



# --------------------------------------------------------

#
# Table structure for table `panel_diskspace`
#

CREATE TABLE `panel_diskspace` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `customerid` int(11) unsigned NOT NULL default '0',
  `year` int(4) unsigned zerofill NOT NULL default '0000',
  `month` int(2) unsigned zerofill NOT NULL default '00',
  `day` int(2) unsigned zerofill NOT NULL default '00',
  `stamp` int(11) unsigned NOT NULL default '0',
  `webspace` bigint(30) unsigned NOT NULL default '0',
  `mail` bigint(30) unsigned NOT NULL default '0',
  `mysql` bigint(30) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `customerid` (`customerid`)
) TYPE=MyISAM ;

#
# Dumping data for table `panel_diskspace`
#


# --------------------------------------------------------

#
# Table structure for table `panel_diskspace_admins`
#

CREATE TABLE `panel_diskspace_admins` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `adminid` int(11) unsigned NOT NULL default '0',
  `year` int(4) unsigned zerofill NOT NULL default '0000',
  `month` int(2) unsigned zerofill NOT NULL default '00',
  `day` int(2) unsigned zerofill NOT NULL default '00',
  `stamp` int(11) unsigned NOT NULL default '0',
  `webspace` bigint(30) unsigned NOT NULL default '0',
  `mail` bigint(30) unsigned NOT NULL default '0',
  `mysql` bigint(30) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `adminid` (`adminid`)
) TYPE=MyISAM ;

#
# Dumping data for table `panel_diskspace_admins`
#

# --------------------------------------------------------

#
# Table structure for table `panel_navigation`
#

DROP TABLE IF EXISTS `panel_navigation`;
CREATE TABLE `panel_navigation` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `area` varchar(20) NOT NULL default '',
  `parent_url` varchar(255) NOT NULL default '',
  `lang` varchar(255) NOT NULL default '',
  `url` varchar(255) NOT NULL default '',
  `order` int(4) NOT NULL default '0',
  `required_resources` varchar(255) NOT NULL default '',
  `new_window` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM ;

#
# Dumping data for table `panel_navigation`
#

INSERT INTO `panel_navigation` VALUES (1, 'login', '', 'login;login', 'login.nourl', '10', '', 0);
INSERT INTO `panel_navigation` VALUES (2, 'login', 'login.nourl', 'login;login', 'index.php', '10', '', 0);
INSERT INTO `panel_navigation` VALUES (3, 'customer', '', 'menue;main;main', 'customer_index.php', '10', '', 0);
INSERT INTO `panel_navigation` VALUES (4, 'customer', 'customer_index.php', 'menue;main;changepassword', 'customer_index.php?page=change_password', '10', '', 0);
INSERT INTO `panel_navigation` VALUES (5, 'customer', 'customer_index.php', 'menue;main;changelanguage', 'customer_index.php?page=change_language', '20', '', 0);
INSERT INTO `panel_navigation` VALUES (6, 'customer', 'customer_index.php', 'login;logout', 'customer_index.php?action=logout', '30', '', 0);
INSERT INTO `panel_navigation` VALUES (7, 'customer', '', 'menue;email;email', 'customer_email.php', '20', '', 0);
INSERT INTO `panel_navigation` VALUES (8, 'customer', 'customer_email.php', 'menue;email;emails', 'customer_email.php?page=emails', '10', 'emails', 0);
INSERT INTO `panel_navigation` VALUES (9, 'customer', '', 'menue;mysql;mysql', 'customer_mysql.php', '30', '', 0);
INSERT INTO `panel_navigation` VALUES (10, 'customer', 'customer_mysql.php', 'menue;mysql;databases', 'customer_mysql.php?page=mysqls', '10', 'mysqls', 0);
INSERT INTO `panel_navigation` VALUES (11, 'customer', '', 'menue;domains;domains', 'customer_domains.php', '40', '', 0);
INSERT INTO `panel_navigation` VALUES (12, 'customer', 'customer_domains.php', 'menue;domains;settings', 'customer_domains.php?page=domains', '10', '', 0);
INSERT INTO `panel_navigation` VALUES (13, 'customer', '', 'menue;ftp;ftp', 'customer_ftp.php', '50', '', 0);
INSERT INTO `panel_navigation` VALUES (14, 'customer', 'customer_ftp.php', 'menue;ftp;accounts', 'customer_ftp.php?page=accounts', '10', '', 0);
INSERT INTO `panel_navigation` VALUES (15, 'customer', '', 'menue;extras;extras', 'customer_extras.php', '60', '', 0);
INSERT INTO `panel_navigation` VALUES (16, 'customer', 'customer_extras.php', 'menue;extras;directoryprotection', 'customer_extras.php?page=htpasswds', '10', '', 0);
INSERT INTO `panel_navigation` VALUES (17, 'customer', 'customer_extras.php', 'menue;extras;pathoptions', 'customer_extras.php?page=htaccess', '20', '', 0);
INSERT INTO `panel_navigation` VALUES (18, 'admin', '', 'admin;overview', 'admin_index.php', '10', '', 0);
INSERT INTO `panel_navigation` VALUES (19, 'admin', 'admin_index.php', 'menue;main;changepassword', 'admin_index.php?page=change_password', '10', '', 0);
INSERT INTO `panel_navigation` VALUES (20, 'admin', 'admin_index.php', 'menue;main;changelanguage', 'admin_index.php?page=change_language', '20', '', 0);
INSERT INTO `panel_navigation` VALUES (21, 'admin', 'admin_index.php', 'login;logout', 'admin_index.php?action=logout', '30', '', 0);
INSERT INTO `panel_navigation` VALUES (22, 'admin', '', 'admin;resources', 'admin_resources.nourl', '20', 'customers', 0);
INSERT INTO `panel_navigation` VALUES (23, 'admin', 'admin_resources.nourl', 'admin;customers', 'admin_customers.php?page=customers', '10', 'customers', 0);
INSERT INTO `panel_navigation` VALUES (24, 'admin', 'admin_resources.nourl', 'admin;domains', 'admin_domains.php?page=domains', '20', 'domains', 0);
INSERT INTO `panel_navigation` VALUES (25, 'admin', 'admin_resources.nourl', 'admin;admins', 'admin_admins.php?page=admins', '30', 'change_serversettings', 0);
INSERT INTO `panel_navigation` VALUES (26, 'admin', '', 'admin;server', 'admin_server.nourl', '30', 'change_serversettings', 0);
INSERT INTO `panel_navigation` VALUES (27, 'admin', 'admin_server.nourl', 'admin;configfiles;serverconfiguration', 'admin_configfiles.php?page=configfiles', '10', 'change_serversettings', 0);
INSERT INTO `panel_navigation` VALUES (28, 'admin', 'admin_server.nourl', 'admin;serversettings', 'admin_settings.php?page=settings', '20', 'change_serversettings', 0);
INSERT INTO `panel_navigation` VALUES (29, 'admin', '', 'admin;misc', 'admin_misc.nourl', '40', '', 0);
INSERT INTO `panel_navigation` VALUES (30, 'admin', 'admin_misc.nourl', 'admin;templates;email', 'admin_templates.php?page=email', '10', '', 0);
INSERT INTO `panel_navigation` VALUES (31, 'admin', 'admin_server.nourl', 'admin;rebuildconf', 'admin_settings.php?page=rebuildconfigs', '30', 'change_serversettings', 0);
INSERT INTO `panel_navigation` VALUES (32, 'admin', 'admin_server.nourl', 'admin;ipsandports;ipsandports', 'admin_ipsandports.php?page=ipsandports', '25', 'change_serversettings', 0);
INSERT INTO `panel_navigation` VALUES (33, 'admin', 'admin_index.php', 'menue;main;username', 'admin_index.nourl', '5', '', 0);
INSERT INTO `panel_navigation` VALUES (34, 'customer', 'customer_index.php', 'menue;main;username', 'customer_index.nourl', '5', '', 0);
INSERT INTO `panel_navigation` VALUES (35, 'admin', 'admin_server.nourl', 'admin;updatecounters', 'admin_settings.php?page=updatecounters', '35', 'change_serversettings', 0);
INSERT INTO `panel_navigation` VALUES (36, 'customer', '', 'menue;ticket;ticket', 'customer_tickets.php', '20', 'ticket.enabled', 0);
INSERT INTO `panel_navigation` VALUES (37, 'customer', 'customer_tickets.php', 'menue;ticket;ticket', 'customer_tickets.php?page=tickets', 10, '', 0);
INSERT INTO `panel_navigation` VALUES (38, 'admin', '', 'admin;ticketsystem', 'admin_ticketsystem.nourl', '40', 'ticket.enabled', 0);
INSERT INTO `panel_navigation` VALUES (39, 'admin', 'admin_ticketsystem.nourl', 'menue;ticket;ticket', 'admin_tickets.php?page=tickets', '10', '', 0);
INSERT INTO `panel_navigation` VALUES (40, 'admin', 'admin_ticketsystem.nourl', 'menue;ticket;archive', 'admin_tickets.php?page=archive', '20', '', 0);
INSERT INTO `panel_navigation` VALUES (41, 'admin', 'admin_ticketsystem.nourl', 'menue;ticket;categories', 'admin_tickets.php?page=categories', '30', '', 0);
INSERT INTO `panel_navigation` VALUES (42, 'customer', '', 'menue;traffic;traffic', 'customer_traffic.php', 80, '', 0);
INSERT INTO `panel_navigation` VALUES (43, 'customer', 'customer_traffic.php', 'menue;traffic;current', 'customer_traffic.php?page=current', 10, '', 0);
INSERT INTO `panel_navigation` VALUES (44, 'admin', 'admin_misc.nourl', 'menue;logger;logger', 'admin_logger.php?page=log', '10', 'logger.enabled', 0);
INSERT INTO `panel_navigation` VALUES (45, 'admin', 'admin_misc.nourl', 'admin;message', 'admin_message.php?page=message', 10, '', 0);
INSERT INTO `panel_navigation` VALUES (46, 'customer', 'customer_email.php', 'emails;emails_add', 'customer_email.php?page=emails&action=add', '20', 'emails', 0);
INSERT INTO `panel_navigation` VALUES (47, 'admin', '', 'billing;billing', 'billing.nourl', '100', 'billing.activate_billing', '0');
INSERT INTO `panel_navigation` VALUES (48, 'admin', 'billing.nourl', 'billing;openinvoices', 'billing_openinvoices.php', '110', 'edit_billingdata', '0');
INSERT INTO `panel_navigation` VALUES (49, 'admin', 'billing.nourl', 'billing;openinvoices_admin', 'billing_openinvoices.php?mode=1', '115', 'edit_billingdata', '0');
INSERT INTO `panel_navigation` VALUES (50, 'admin', 'billing.nourl', 'billing;invoices', 'billing_invoices.php', '120', 'edit_billingdata', '0');
INSERT INTO `panel_navigation` VALUES (51, 'admin', 'billing.nourl', 'billing;invoices_admin', 'billing_invoices.php?mode=1', '125', 'edit_billingdata', '0');
INSERT INTO `panel_navigation` VALUES (52, 'admin', 'billing.nourl', 'billing;other', 'billing_other.php', '130', 'edit_billingdata', '0');
INSERT INTO `panel_navigation` VALUES (53, 'admin', 'billing.nourl', 'billing;taxclassesnrates', 'billing_taxrates.php', '140', 'edit_billingdata', '0');
INSERT INTO `panel_navigation` VALUES (54, 'admin', 'billing.nourl', 'billing;domains_templates', 'billing_domains_templates.php', '150', 'edit_billingdata', '0');
INSERT INTO `panel_navigation` VALUES (55, 'admin', 'billing.nourl', 'billing;other_templates', 'billing_other_templates.php', '160', 'edit_billingdata', '0');
INSERT INTO `panel_navigation` VALUES (56, 'admin', '', 'admin;aps', 'admin_aps.nourl', 45, 'aps.aps_active', 0);
INSERT INTO `panel_navigation` VALUES (57, 'admin', 'admin_aps.nourl', 'aps;scan', 'admin_aps.php?action=scan', 20, 'can_manage_aps_packages', 0);
INSERT INTO `panel_navigation` VALUES (58, 'admin', 'admin_aps.nourl', 'aps;upload', 'admin_aps.php?action=upload', 10, 'can_manage_aps_packages', 0);
INSERT INTO `panel_navigation` VALUES (59, 'admin', 'admin_aps.nourl', 'aps;managepackages', 'admin_aps.php?action=managepackages', 30, 'can_manage_aps_packages', 0);
INSERT INTO `panel_navigation` VALUES (60, 'admin', 'admin_aps.nourl', 'aps;manageinstances', 'admin_aps.php?action=manageinstances', 35, '', 0);
INSERT INTO `panel_navigation` VALUES (61, 'customer', '', 'customer;aps', 'customer_aps.nourl', 50, 'phpenabled', 0);
INSERT INTO `panel_navigation` VALUES (62, 'customer', 'customer_aps.nourl', 'aps;overview', 'customer_aps.php?action=overview', 10, '', 0);
INSERT INTO `panel_navigation` VALUES (63, 'customer', 'customer_aps.nourl', 'aps;status', 'customer_aps.php?action=customerstatus', 20, '', 0);
INSERT INTO `panel_navigation` VALUES (64, 'customer', 'customer_aps.nourl', 'aps;search', 'customer_aps.php?action=search', 30, '', 0);
INSERT INTO `panel_navigation` VALUES (65, 'admin', 'admin_server.nourl', 'menue;phpsettings;maintitle', 'admin_phpsettings.php?page=overview', 80, 'system.mod_fcgid', 0);
INSERT INTO `panel_navigation` VALUES (66, 'customer', 'customer_email.php', 'menue;email;autoresponder', 'customer_autoresponder.php', 40, 'autoresponder.autoresponder_active', 0);

# --------------------------------------------------------

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
INSERT INTO `panel_languages` VALUES (3, 'Fran&ccedil;ais', 'lng/french.lng.php');
INSERT INTO `panel_languages` VALUES (4, 'Chinese', 'lng/zh-cn.lng.php');
INSERT INTO `panel_languages` VALUES (5, 'Catalan', 'lng/catalan.lng.php');
INSERT INTO `panel_languages` VALUES (6, 'Espa&ntilde;ol', 'lng/spanish.lng.php');
INSERT INTO `panel_languages` VALUES (7, 'Portugu&ecirc;s', 'lng/portugues.lng.php');
INSERT INTO `panel_languages` VALUES (8, 'Russian', 'lng/russian.lng.php');
INSERT INTO `panel_languages` VALUES (9, 'Danish', 'lng/danish.lng.php');
INSERT INTO `panel_languages` VALUES (10, 'Italian', 'lng/italian.lng.php');
INSERT INTO `panel_languages` VALUES (11, 'Bulgarian', 'lng/bulgarian.lng.php');
INSERT INTO `panel_languages` VALUES (12, 'Slovak', 'lng/slovak.lng.php');
INSERT INTO `panel_languages` VALUES (13, 'Dutch', 'lng/dutch.lng.php');
INSERT INTO `panel_languages` VALUES (14, 'Hungarian', 'lng/hungarian.lng.php');
INSERT INTO `panel_languages` VALUES (15, 'Swedish', 'lng/swedish.lng.php');
INSERT INTO `panel_languages` VALUES (16, 'Czech', 'lng/czech.lng.php');

# --------------------------------------------------------

#
# Table structure for table `panel_tickets`
#

DROP TABLE IF EXISTS `panel_tickets`;
CREATE TABLE `panel_tickets` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `customerid` int(11) NOT NULL,
  `adminid` int(11) NOT NULL,
  `category` smallint(5) unsigned NOT NULL default '1',
  `priority` enum('1','2','3') NOT NULL default '3',
  `subject` varchar(70) NOT NULL,
  `message` text NOT NULL,
  `dt` int(15) NOT NULL,
  `lastchange` int(15) NOT NULL,
  `ip` varchar(20) NOT NULL,
  `status` enum('0','1','2','3') NOT NULL default '1',
  `lastreplier` enum('0','1') NOT NULL default '0',
  `answerto` int(11) unsigned NOT NULL,
  `by` enum('0','1') NOT NULL default '0',
  `archived` enum('0','1') NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `customerid` (`customerid`)
) ENGINE=MyISAM;


# --------------------------------------------------------

#
# Table structure for table `panel_ticket_categories`
#

DROP TABLE IF EXISTS `panel_ticket_categories`;
CREATE TABLE `panel_ticket_categories` (
  `id` smallint(5) unsigned NOT NULL auto_increment,
  `name` varchar(60) NOT NULL,
  `adminid` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM;

#
# Dumping data for table `panel_ticket_categories`
#


# --------------------------------------------------------

#
# Table structure for table `panel_syslog`
#

DROP TABLE IF EXISTS `panel_syslog`;
CREATE TABLE IF NOT EXISTS `panel_syslog` (
  `logid` bigint(20) NOT NULL auto_increment,
  `action` int(5) NOT NULL default '10',
  `type` int(5) NOT NULL default '0',
  `date` int(15) NOT NULL,
  `user` varchar(50) NOT NULL,
  `text` text NOT NULL,
  PRIMARY KEY  (`logid`)
) ENGINE=MyISAM;

#
# Dumping data for table `panel_syslog`
#


# --------------------------------------------------------

#
# Table structure for table `billing_service_categories`
#

CREATE TABLE  `billing_service_categories` (
 `id` INT( 11 ) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
 `category_name` VARCHAR( 255 ) NOT NULL DEFAULT '',
 `category_order` INT( 11 ) UNSIGNED NOT NULL DEFAULT '0',
 `category_classname` VARCHAR( 255 ) NOT NULL DEFAULT '',
 `category_classfile` VARCHAR( 255 ) NOT NULL DEFAULT '',
 `category_cachefield` VARCHAR( 255 ) NOT NULL DEFAULT '',
 `category_caption` VARCHAR( 255 ) NOT NULL DEFAULT '',
 `category_rowcaption_setup` VARCHAR( 255 ) NOT NULL DEFAULT '',
 `category_rowcaption_interval` VARCHAR( 255 ) NOT NULL DEFAULT ''
) TYPE = MYISAM ;

#
# Dumping data for table `billing_service_categories`
#

INSERT INTO `billing_service_categories` (`id`, `category_name`, `category_order`, `category_classname`, `category_classfile`, `category_cachefield`, `category_caption`, `category_rowcaption_setup`, `category_rowcaption_interval`) VALUES (1, 'hosting', 10, 'hosting', 'lib/billing_class_hosting.php', 'invoice_fee_hosting', 'hosting_caption', 'hosting_rowcaption_setup', 'hosting_rowcaption_interval');
INSERT INTO `billing_service_categories` (`id`, `category_name`, `category_order`, `category_classname`, `category_classfile`, `category_cachefield`, `category_caption`, `category_rowcaption_setup`, `category_rowcaption_interval`) VALUES (2, 'domains', 20, 'domains', 'lib/billing_class_domains.php', 'invoice_fee_domains', 'domains_caption', 'domains_rowcaption_setup', 'domains_rowcaption_interval');
INSERT INTO `billing_service_categories` (`id`, `category_name`, `category_order`, `category_classname`, `category_classfile`, `category_cachefield`, `category_caption`, `category_rowcaption_setup`, `category_rowcaption_interval`) VALUES (3, 'traffic', 30, 'traffic', 'lib/billing_class_traffic.php', 'invoice_fee_traffic', 'traffic_caption', 'traffic_rowcaption_setup', 'traffic_rowcaption_interval');
INSERT INTO `billing_service_categories` (`id`, `category_name`, `category_order`, `category_classname`, `category_classfile`, `category_cachefield`, `category_caption`, `category_rowcaption_setup`, `category_rowcaption_interval`) VALUES (4, 'diskspace', 40, 'diskspace', 'lib/billing_class_diskspace.php', 'invoice_fee_diskspace', 'diskspace_caption', 'diskspace_rowcaption_setup', 'diskspace_rowcaption_interval');
INSERT INTO `billing_service_categories` (`id`, `category_name`, `category_order`, `category_classname`, `category_classfile`, `category_cachefield`, `category_caption`, `category_rowcaption_setup`, `category_rowcaption_interval`) VALUES (5, 'other', 50, 'other', 'lib/billing_class_other.php', 'invoice_fee_other', 'other_caption', 'other_rowcaption_setup', 'other_rowcaption_interval');

# --------------------------------------------------------

#
# Table structure for table `billing_service_categories_admins`
#

CREATE TABLE  `billing_service_categories_admins` (
 `id` INT( 11 ) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
 `category_name` VARCHAR( 255 ) NOT NULL DEFAULT '',
 `category_order` INT( 11 ) UNSIGNED NOT NULL DEFAULT '0',
 `category_mode` TINYINT( 1 ) NOT NULL DEFAULT '0',
 `category_classname` VARCHAR( 255 ) NOT NULL DEFAULT '',
 `category_classfile` VARCHAR( 255 ) NOT NULL DEFAULT '',
 `category_cachefield` VARCHAR( 255 ) NOT NULL DEFAULT '',
 `category_caption` VARCHAR( 255 ) NOT NULL DEFAULT '',
 `category_rowcaption_setup` VARCHAR( 255 ) NOT NULL DEFAULT '',
 `category_rowcaption_interval` VARCHAR( 255 ) NOT NULL DEFAULT ''
) TYPE = MYISAM ;

#
# Dumping data for table `billing_service_categories_admins`
#

INSERT INTO `billing_service_categories_admins` (`id`, `category_name`, `category_order`, `category_mode`, `category_classname`, `category_classfile`, `category_cachefield`, `category_caption`, `category_rowcaption_setup`, `category_rowcaption_interval`) VALUES (1, 'hosting', 10, 0, 'hosting', 'lib/billing_class_hosting.php', 'invoice_fee_hosting', 'hosting_caption', 'hosting_rowcaption_setup', 'hosting_rowcaption_interval');
INSERT INTO `billing_service_categories_admins` (`id`, `category_name`, `category_order`, `category_mode`, `category_classname`, `category_classfile`, `category_cachefield`, `category_caption`, `category_rowcaption_setup`, `category_rowcaption_interval`) VALUES (2, 'hosting_customers', 20, 1, 'hosting', 'lib/billing_class_hosting.php', 'invoice_fee_hosting_customers', 'hosting_caption', 'hosting_rowcaption_setup_withloginname', 'hosting_rowcaption_interval_withloginname');
INSERT INTO `billing_service_categories_admins` (`id`, `category_name`, `category_order`, `category_mode`, `category_classname`, `category_classfile`, `category_cachefield`, `category_caption`, `category_rowcaption_setup`, `category_rowcaption_interval`) VALUES (3, 'domains', 30, 1, 'domains', 'lib/billing_class_domains.php', 'invoice_fee_domains', 'domains_caption', 'domains_rowcaption_setup', 'domains_rowcaption_interval');
INSERT INTO `billing_service_categories_admins` (`id`, `category_name`, `category_order`, `category_mode`, `category_classname`, `category_classfile`, `category_cachefield`, `category_caption`, `category_rowcaption_setup`, `category_rowcaption_interval`) VALUES (4, 'traffic', 40, 0, 'traffic', 'lib/billing_class_traffic.php', 'invoice_fee_traffic', 'traffic_caption', 'traffic_rowcaption_setup', 'traffic_rowcaption_interval');
INSERT INTO `billing_service_categories_admins` (`id`, `category_name`, `category_order`, `category_mode`, `category_classname`, `category_classfile`, `category_cachefield`, `category_caption`, `category_rowcaption_setup`, `category_rowcaption_interval`) VALUES (5, 'diskspace', 50, 0, 'diskspace', 'lib/billing_class_diskspace.php', 'invoice_fee_diskspace', 'diskspace_caption', 'diskspace_rowcaption_setup', 'diskspace_rowcaption_interval');
INSERT INTO `billing_service_categories_admins` (`id`, `category_name`, `category_order`, `category_mode`, `category_classname`, `category_classfile`, `category_cachefield`, `category_caption`, `category_rowcaption_setup`, `category_rowcaption_interval`) VALUES (6, 'other', 60, 1, 'other', 'lib/billing_class_other.php', 'invoice_fee_other', 'other_caption', 'other_rowcaption_setup', 'other_rowcaption_interval');

# --------------------------------------------------------

#
# Table structure for table `billing_service_domains_templates`
#

CREATE TABLE  `billing_service_domains_templates` (
 `id` INT( 11 ) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
 `tld` VARCHAR( 255 ) NOT NULL DEFAULT '',
 `valid_from` DATE NOT NULL,
 `valid_to` DATE NOT NULL,
 `taxclass` INT( 11 ) UNSIGNED NOT NULL DEFAULT '0',
 `setup_fee` DECIMAL( 10, 2 ) NOT NULL DEFAULT '0.00',
 `interval_fee` DECIMAL( 10, 2 ) NOT NULL DEFAULT '0.00',
 `interval_length` INT( 11 ) NOT NULL DEFAULT '0',
 `interval_type` VARCHAR( 1 ) NOT NULL DEFAULT 'y',
 `interval_payment` TINYINT( 1 ) NOT NULL DEFAULT '0'
) TYPE = MYISAM ;

#
# Dumping data for table `billing_service_domains_templates`
#


# --------------------------------------------------------

#
# Table structure for table `billing_service_other`
#

CREATE TABLE  `billing_service_other` (
 `id` INT( 11 ) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
 `customerid` INT( 11 ) NOT NULL DEFAULT '0',
 `templateid` INT( 11 ) NOT NULL DEFAULT '0',
 `service_type` VARCHAR( 255 ) NOT NULL DEFAULT '',
 `caption_setup` VARCHAR( 255 ) NOT NULL DEFAULT '',
 `caption_interval` VARCHAR( 255 ) NOT NULL DEFAULT '',
 `taxclass` INT( 11 ) UNSIGNED NOT NULL DEFAULT '0',
 `quantity` INT( 11 ) UNSIGNED NOT NULL DEFAULT '0',
 `setup_fee` DECIMAL( 10, 2 ) NOT NULL DEFAULT '0.00',
 `interval_fee` DECIMAL( 10, 2 ) NOT NULL DEFAULT '0.00',
 `interval_length` INT( 11 ) NOT NULL DEFAULT '0',
 `interval_type` VARCHAR( 1 ) NOT NULL DEFAULT 'm',
 `interval_payment` TINYINT( 1 ) NOT NULL DEFAULT '0',
 `service_active` TINYINT( 1 ) NOT NULL DEFAULT '0',
 `servicestart_date` DATE NOT NULL,
 `serviceend_date` DATE NOT NULL,
 `lastinvoiced_date` DATE NOT NULL
) TYPE = MYISAM ;

#
# Dumping data for table `billing_service_other`
#


# --------------------------------------------------------

#
# Table structure for table `billing_service_other_templates`
#

CREATE TABLE  `billing_service_other_templates` (
 `templateid` INT( 11 ) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
 `valid_from` DATE NOT NULL,
 `valid_to` DATE NOT NULL,
 `service_type` VARCHAR( 255 ) NOT NULL DEFAULT '',
 `caption_setup` VARCHAR( 255 ) NOT NULL DEFAULT '',
 `caption_interval` VARCHAR( 255 ) NOT NULL DEFAULT '',
 `taxclass` INT( 11 ) UNSIGNED NOT NULL DEFAULT '0',
 `setup_fee` DECIMAL( 10, 2 ) NOT NULL DEFAULT '0.00',
 `interval_fee` DECIMAL( 10, 2 ) NOT NULL DEFAULT '0.00',
 `interval_length` INT( 11 ) NOT NULL DEFAULT '0',
 `interval_type` VARCHAR( 1 ) NOT NULL DEFAULT 'm',
 `interval_payment` TINYINT( 1 ) NOT NULL DEFAULT '0'
) TYPE = MYISAM ;

#
# Dumping data for table `billing_service_other_templates`
#


# --------------------------------------------------------

#
# Table structure for table `billing_taxclasses`
#

CREATE TABLE  `billing_taxclasses` (
 `classid` INT( 11 ) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
 `classname` VARCHAR( 255 ) NOT NULL DEFAULT '',
 `default` TINYINT( 1 ) NOT NULL DEFAULT '0'
) TYPE = MYISAM ;

#
# Dumping data for table `billing_taxclasses`
#

INSERT INTO `billing_taxclasses` (`classid`, `classname`, `default`) VALUES ( NULL, 'MwSt Deutschland', '1' );
INSERT INTO `billing_taxclasses` (`classid`, `classname`, `default`) VALUES ( NULL, 'MwSt Deutschland (reduziert)', '0' );

# --------------------------------------------------------

#
# Table structure for table `billing_taxrates`
#

CREATE TABLE  `billing_taxrates` (
 `taxid` INT( 11 ) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
 `taxclass` INT( 11 ) UNSIGNED NOT NULL DEFAULT '0',
 `taxrate` DECIMAL( 4, 4 ) NOT NULL ,
 `valid_from` DATE NOT NULL
) TYPE = MYISAM ;

#
# Dumping data for table `billing_taxrates`
#

INSERT INTO `billing_taxrates` (`taxid`, `taxclass`, `taxrate`, `valid_from`) VALUES ( NULL, 1, 0.1600, '0' );
INSERT INTO `billing_taxrates` (`taxid`, `taxclass`, `taxrate`, `valid_from`) VALUES ( NULL, 1, 0.1900, '2007-01-01' );
INSERT INTO `billing_taxrates` (`taxid`, `taxclass`, `taxrate`, `valid_from`) VALUES ( NULL, 2, 0.0700, '0' );

# --------------------------------------------------------

#
# Table structure for table `billing_invoices`
#

CREATE TABLE  `billing_invoices` (
 `id` INT( 11 ) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
 `customerid` INT( 11 ) NOT NULL DEFAULT '0',
 `xml` LONGTEXT NOT NULL DEFAULT '',
 `invoice_date` DATE NOT NULL,
 `state` TINYINT( 1 ) NOT NULL DEFAULT '0',
 `state_change` INT( 11 ) NOT NULL DEFAULT '0',
 `invoice_number` VARCHAR( 255 ) NOT NULL DEFAULT '',
 `total_fee` DECIMAL( 10, 2 ) NOT NULL DEFAULT '0.00',
 `total_fee_taxed` DECIMAL( 10, 2 ) NOT NULL DEFAULT '0.00'
) TYPE = MYISAM ;

#
# Dumping data for table `billing_invoices`
#


# --------------------------------------------------------

#
# Table structure for table `billing_invoices_admins`
#

CREATE TABLE  `billing_invoices_admins` (
 `id` INT( 11 ) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
 `adminid` INT( 11 ) NOT NULL DEFAULT '0',
 `xml` LONGTEXT NOT NULL DEFAULT '',
 `invoice_date` DATE NOT NULL,
 `state` TINYINT( 1 ) NOT NULL DEFAULT '0',
 `state_change` INT( 11 ) NOT NULL DEFAULT '0',
 `invoice_number` VARCHAR( 255 ) NOT NULL DEFAULT '',
 `total_fee` DECIMAL( 10, 2 ) NOT NULL DEFAULT '0.00',
 `total_fee_taxed` DECIMAL( 10, 2 ) NOT NULL DEFAULT '0.00'
) TYPE = MYISAM ;

#
# Dumping data for table `billing_invoices_admins`
#


# --------------------------------------------------------

#
# Table structure for table `billing_invoice_changes`
#

CREATE TABLE  `billing_invoice_changes` (
 `id` INT( 11 ) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
 `customerid` INT( 11 ) UNSIGNED NOT NULL DEFAULT '0',
 `userid` INT( 11 ) UNSIGNED NOT NULL DEFAULT '0',
 `timestamp` INT( 11 ) UNSIGNED NOT NULL DEFAULT '0',
 `key` VARCHAR( 255 ) NOT NULL DEFAULT '',
 `action` TINYINT( 1 ) NOT NULL DEFAULT '0',
 `caption` VARCHAR( 255 ) NOT NULL DEFAULT '',
 `interval` VARCHAR( 255 ) NOT NULL DEFAULT '',
 `quantity` INT( 11 ) UNSIGNED NOT NULL DEFAULT '0',
 `total_fee` DECIMAL( 10, 2 ) NOT NULL DEFAULT '0.00',
 `taxrate` DECIMAL( 4, 4 ) NOT NULL
) TYPE = MYISAM ;

#
# Dumping data for table `billing_invoice_changes`
#


# --------------------------------------------------------

#
# Table structure for table `billing_invoice_changes_admins`
#

CREATE TABLE  `billing_invoice_changes_admins` (
 `id` INT( 11 ) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
 `adminid` INT( 11 ) UNSIGNED NOT NULL DEFAULT '0',
 `userid` INT( 11 ) UNSIGNED NOT NULL DEFAULT '0',
 `timestamp` INT( 11 ) UNSIGNED NOT NULL DEFAULT '0',
 `key` VARCHAR( 255 ) NOT NULL DEFAULT '',
 `action` TINYINT( 1 ) NOT NULL DEFAULT '0',
 `caption` VARCHAR( 255 ) NOT NULL DEFAULT '',
 `interval` VARCHAR( 255 ) NOT NULL DEFAULT '',
 `quantity` INT( 11 ) UNSIGNED NOT NULL DEFAULT '0',
 `total_fee` DECIMAL( 10, 2 ) NOT NULL DEFAULT '0.00',
 `taxrate` DECIMAL( 4, 4 ) NOT NULL
) TYPE = MYISAM ;

#
# Dumping data for table `billing_invoice_changes_admins`
#


# --------------------------------------------------------

#
# Table structure for table `mail_autoresponder`
#

CREATE TABLE `mail_autoresponder` (
  `email` varchar(255) NOT NULL default '',
  `message` text NOT NULL,
  `enabled` tinyint(1) NOT NULL default '0',
  `subject` varchar(255) NOT NULL default '',
  `customerid` int(11) NOT NULL default '0',
  PRIMARY KEY  (`email`),
  KEY `customerid` (`customerid`),
  FULLTEXT KEY `message` (`message`)
) ENGINE=MyISAM;

#
# Dumping data for table `mail_autoresponder`
#


# --------------------------------------------------------

#
# Table structure for table `panel_phpconfigs`
#

CREATE TABLE `panel_phpconfigs` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `description` varchar(50) NOT NULL,
  `binary` varchar(255) NOT NULL,
  `file_extensions` varchar(255) NOT NULL,
  `mod_fcgid_starter` int(4) NOT NULL DEFAULT '-1',
  `mod_fcgid_maxrequests` int(4) NOT NULL DEFAULT '-1',
  `phpsettings` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM;

#
# Dumping data for table `panel_phpconfigs`
#

INSERT INTO `panel_phpconfigs` (`id`, `description`, `binary`, `file_extensions`, `mod_fcgid_starter`, `mod_fcgid_maxrequests`, `phpsettings`) VALUES(1, 'Default Config', '/usr/bin/php-cgi', 'php', '-1', '-1', 'short_open_tag = On\r\nasp_tags = Off\r\nprecision = 14\r\noutput_buffering = 4096\r\nallow_call_time_pass_reference = Off\r\nsafe_mode = {SAFE_MODE}\r\nsafe_mode_gid = Off\r\nsafe_mode_include_dir = "{PEAR_DIR}"\r\nsafe_mode_allowed_env_vars = PHP_\r\nsafe_mode_protected_env_vars = LD_LIBRARY_PATH\r\nopen_basedir = "{OPEN_BASEDIR}"\r\ndisable_functions = exec,passthru,shell_exec,system,proc_close,proc_get_status,proc_nice,proc_open,proc_terminate\r\ndisable_classes =\r\nexpose_php = Off\r\nmax_execution_time = 30\r\nmax_input_time = 60\r\nmemory_limit = 16M\r\npost_max_size = 16M\r\nerror_reporting = E_ALL | ~E_NOTICE\r\ndisplay_errors = On\r\ndisplay_startup_errors = Off\r\nlog_errors = On\r\nlog_errors_max_len = 1024\r\nignore_repeated_errors = Off\r\nignore_repeated_source = Off\r\nreport_memleaks = On\r\ntrack_errors = Off\r\nhtml_errors = Off\r\nvariables_order = "GPCS"\r\nregister_globals = Off\r\nregister_argc_argv = Off\r\ngpc_order = "GPC"\r\nmagic_quotes_gpc = Off\r\nmagic_quotes_runtime = Off\r\nmagic_quotes_sybase = Off\r\ninclude_path = ".:{PEAR_DIR}"\r\nenable_dl = Off\r\nfile_uploads = On\r\nupload_tmp_dir = "{TMP_DIR}"\r\nupload_max_filesize = 32M\r\nallow_url_fopen = Off\r\nsendmail_path = "/usr/sbin/sendmail -t -f {CUSTOMER_EMAIL}"\r\nsession.save_handler = files\r\nsession.save_path = "{TMP_DIR}"\r\nsession.use_cookies = 1\r\nsession.name = PHPSESSID\r\nsession.auto_start = 0\r\nsession.cookie_lifetime = 0\r\nsession.cookie_path = /\r\nsession.cookie_domain =\r\nsession.serialize_handler = php\r\nsession.gc_probability = 1\r\nsession.gc_divisor = 1000\r\nsession.gc_maxlifetime = 1440\r\nsession.bug_compat_42 = 0\r\nsession.bug_compat_warn = 1\r\nsession.referer_check =\r\nsession.entropy_length = 16\r\nsession.entropy_file = /dev/urandom\r\nsession.cache_limiter = nocache\r\nsession.cache_expire = 180\r\nsession.use_trans_sid = 0\r\nsuhosin.simulation = Off\r\nsuhosin.mail.protect = 1\r\n');

# --------------------------------------------------------

#
# Tabellenstruktur f�r Tabelle `aps_instances`
#

CREATE TABLE IF NOT EXISTS `aps_instances` (
  `ID` int(4) NOT NULL auto_increment,
  `CustomerID` int(4) NOT NULL,
  `PackageID` int(4) NOT NULL,
  `Status` int(4) NOT NULL,
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM;

# --------------------------------------------------------

#
# Tabellenstruktur f�r Tabelle `aps_packages`
#

CREATE TABLE IF NOT EXISTS `aps_packages` (
  `ID` int(4) NOT NULL auto_increment,
  `Path` varchar(500) NOT NULL,
  `Name` varchar(500) NOT NULL,
  `Version` varchar(20) NOT NULL,
  `Release` int(4) NOT NULL,
  `Status` int(1) NOT NULL default '1',
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM;

# --------------------------------------------------------

#
# Tabellenstruktur f�r Tabelle `aps_settings`
#

CREATE TABLE IF NOT EXISTS `aps_settings` (
  `ID` int(4) NOT NULL auto_increment,
  `InstanceID` int(4) NOT NULL,
  `Name` varchar(250) NOT NULL,
  `Value` varchar(250) NOT NULL,
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM;

# --------------------------------------------------------

#
# Tabellenstruktur f�r Tabelle `aps_tasks`
#

CREATE TABLE IF NOT EXISTS `aps_tasks` (
  `ID` int(4) NOT NULL auto_increment,
  `InstanceID` int(4) NOT NULL,
  `Task` int(4) NOT NULL,
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM;

# --------------------------------------------------------

#
# Tabellenstruktur f�r Tabelle `aps_temp_settings`
#

CREATE TABLE IF NOT EXISTS `aps_temp_settings` (
  `ID` int(4) NOT NULL auto_increment,
  `PackageID` int(4) NOT NULL,
  `CustomerID` int(4) NOT NULL,
  `Name` varchar(250) NOT NULL,
  `Value` varchar(250) NOT NULL,
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM;

<?php
/**
 * filename: $Source$
 * begin: Sunday, Sep 12, 2004
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version. This program is distributed in the
 * hope that it will be useful, but WITHOUT ANY WARRANTY; without even the
 * implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU General Public License for more details.
 *
 * @author Florian Lippert <flo@redenswert.de>
 * @copyright (C) 2003-2004 Florian Lippert
 * @package System
 * @version $Id$
 */

	if($settings['panel']['version'] == '1.2-beta1' || $settings['panel']['version'] == '1.2-rc1')
	{
		$db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='1.2.0' WHERE `settinggroup`='panel' AND `varname`='version'");
		$settings['panel']['version'] = '1.2.0';
	}
	if($settings['panel']['version'] == '1.2.0')
	{
		$db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='1.2.1' WHERE `settinggroup`='panel' AND `varname`='version'");
		$settings['panel']['version'] = '1.2.1';
	}
	if($settings['panel']['version'] == '1.2.1')
	{
		$db->query("ALTER TABLE `".TABLE_PANEL_SESSIONS."` CHANGE `useragent` `useragent` VARCHAR( 255 ) NOT NULL");
		$db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='1.2.2' WHERE `settinggroup`='panel' AND `varname`='version'");
		$settings['panel']['version'] = '1.2.2';
	}
	if($settings['panel']['version'] == '1.2.2')
	{
		$db->query("
			CREATE TABLE `".TABLE_PANEL_NAVIGATION."` (
  				`id`        int(11)     unsigned NOT NULL auto_increment,
  				`area`      varchar(20)          NOT NULL default '',
  				`parent_id` int(11)     unsigned NOT NULL default '0',
  				`lang`      varchar(255)         NOT NULL default '',
  				`url`       varchar(255)         NOT NULL default '',
  			PRIMARY KEY  (`id`)
			) TYPE=MyISAM
		");
		$db->query("INSERT INTO `".TABLE_PANEL_NAVIGATION."` (`id`, `area`, `parent_id`, `lang`, `url`) VALUES (1, 'login', 0, 'login;login', '');");
		$db->query("INSERT INTO `".TABLE_PANEL_NAVIGATION."` (`id`, `area`, `parent_id`, `lang`, `url`) VALUES (2, 'customer', 0, 'menue;main;main', 'customer_index.php');");
		$db->query("INSERT INTO `".TABLE_PANEL_NAVIGATION."` (`id`, `area`, `parent_id`, `lang`, `url`) VALUES (3, 'customer', 2, 'menue;main;changepassword', 'customer_index.php?page=change_password');");
		$db->query("INSERT INTO `".TABLE_PANEL_NAVIGATION."` (`id`, `area`, `parent_id`, `lang`, `url`) VALUES (4, 'customer', 2, 'login;logout', 'customer_index.php?action=logout');");
		$db->query("INSERT INTO `".TABLE_PANEL_NAVIGATION."` (`id`, `area`, `parent_id`, `lang`, `url`) VALUES (5, 'customer', 0, 'menue;email;email', 'customer_email.php');");
		$db->query("INSERT INTO `".TABLE_PANEL_NAVIGATION."` (`id`, `area`, `parent_id`, `lang`, `url`) VALUES (6, 'customer', 5, 'menue;email;pop', 'customer_email.php?page=pop');");
		$db->query("INSERT INTO `".TABLE_PANEL_NAVIGATION."` (`id`, `area`, `parent_id`, `lang`, `url`) VALUES (7, 'customer', 5, 'menue;email;forwarders', 'customer_email.php?page=forwarders');");
		$db->query("INSERT INTO `".TABLE_PANEL_NAVIGATION."` (`id`, `area`, `parent_id`, `lang`, `url`) VALUES (8, 'customer', 0, 'menue;mysql;mysql', 'customer_mysql.php');");
		$db->query("INSERT INTO `".TABLE_PANEL_NAVIGATION."` (`id`, `area`, `parent_id`, `lang`, `url`) VALUES (9, 'customer', 8, 'menue;mysql;databases', 'customer_mysql.php?page=mysqls');");
		$db->query("INSERT INTO `".TABLE_PANEL_NAVIGATION."` (`id`, `area`, `parent_id`, `lang`, `url`) VALUES (10, 'customer', 0, 'menue;domains;domains', 'customer_domains.php');");
		$db->query("INSERT INTO `".TABLE_PANEL_NAVIGATION."` (`id`, `area`, `parent_id`, `lang`, `url`) VALUES (11, 'customer', 10, 'menue;domains;settings', 'customer_domains.php?page=domains');");
		$db->query("INSERT INTO `".TABLE_PANEL_NAVIGATION."` (`id`, `area`, `parent_id`, `lang`, `url`) VALUES (12, 'customer', 0, 'menue;ftp;ftp', 'customer_ftp.php');");
		$db->query("INSERT INTO `".TABLE_PANEL_NAVIGATION."` (`id`, `area`, `parent_id`, `lang`, `url`) VALUES (13, 'customer', 12, 'menue;ftp;accounts', 'customer_ftp.php?page=accounts');");
		$db->query("INSERT INTO `".TABLE_PANEL_NAVIGATION."` (`id`, `area`, `parent_id`, `lang`, `url`) VALUES (14, 'customer', 0, 'menue;extras;extras', 'customer_extras.php');");
		$db->query("INSERT INTO `".TABLE_PANEL_NAVIGATION."` (`id`, `area`, `parent_id`, `lang`, `url`) VALUES (15, 'customer', 14, 'menue;extras;directoryprotection', 'customer_extras.php?page=htpasswds');");
		$db->query("INSERT INTO `".TABLE_PANEL_NAVIGATION."` (`id`, `area`, `parent_id`, `lang`, `url`) VALUES (16, 'customer', 14, 'menue;extras;pathoptions', 'customer_extras.php?page=htaccess');");
		$db->query("INSERT INTO `".TABLE_PANEL_NAVIGATION."` (`id`, `area`, `parent_id`, `lang`, `url`) VALUES (17, 'admin', 0, 'admin;overview', 'admin_index.php?page=overview');");
		$db->query("INSERT INTO `".TABLE_PANEL_NAVIGATION."` (`id`, `area`, `parent_id`, `lang`, `url`) VALUES (18, 'admin', 0, 'menue;main;changepassword', 'admin_index.php?page=change_password');");
		$db->query("INSERT INTO `".TABLE_PANEL_NAVIGATION."` (`id`, `area`, `parent_id`, `lang`, `url`) VALUES (19, 'admin', 0, 'admin;customers', 'admin_customers.php?page=customers');");
		$db->query("INSERT INTO `".TABLE_PANEL_NAVIGATION."` (`id`, `area`, `parent_id`, `lang`, `url`) VALUES (20, 'admin', 0, 'admin;domains', 'admin_domains.php?page=domains');");
		$db->query("INSERT INTO `".TABLE_PANEL_NAVIGATION."` (`id`, `area`, `parent_id`, `lang`, `url`) VALUES (21, 'admin', 0, 'admin;admins', 'admin_admins.php?page=admins');");
		$db->query("INSERT INTO `".TABLE_PANEL_NAVIGATION."` (`id`, `area`, `parent_id`, `lang`, `url`) VALUES (22, 'admin', 0, 'admin;configfiles;serverconfiguration', 'admin_configfiles.php?page=configfiles');");
		$db->query("INSERT INTO `".TABLE_PANEL_NAVIGATION."` (`id`, `area`, `parent_id`, `lang`, `url`) VALUES (23, 'admin', 0, 'admin;serversettings', 'admin_settings.php?page=settings');");
		$db->query("INSERT INTO `".TABLE_PANEL_NAVIGATION."` (`id`, `area`, `parent_id`, `lang`, `url`) VALUES (24, 'admin', 0, 'login;logout', 'admin_index.php?action=logout');");

		$db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='1.2.2-cvs1' WHERE `settinggroup`='panel' AND `varname`='version'");
		$settings['panel']['version'] = '1.2.2-cvs1';
	}	
	if($settings['panel']['version'] == '1.2.2-cvs1')
	{
		$db->query("
			CREATE TABLE `".TABLE_PANEL_LANGUAGE."` (
  				`id`       int(11)      unsigned NOT NULL auto_increment,
  				`language` varchar(30)           NOT NULL default '',
  				`file`     varchar(255)          NOT NULL default '',
  			PRIMARY KEY  (`id`)
			) TYPE=MyISAM
		");
		$db->query("INSERT INTO `".TABLE_PANEL_LANGUAGE."` (`id`, `language`, `file`) VALUES (1, 'Deutsch', 'lng/german.lng.php');");
		$db->query("INSERT INTO `".TABLE_PANEL_LANGUAGE."` (`id`, `language`, `file`) VALUES (2, 'English', 'lng/english.lng.php');");
		$db->query("INSERT INTO `".TABLE_PANEL_LANGUAGE."` (`id`, `language`, `file`) VALUES (3, 'Francais', 'lng/french.lng.php');");
		$db->query("INSERT INTO `".TABLE_PANEL_LANGUAGE."` (`id`, `language`, `file`) VALUES (4, 'Chinese', 'lng/zh-cn.lng.php');");

		$db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='1.2.2-cvs2' WHERE `settinggroup`='panel' AND `varname`='version'");
		$settings['panel']['version'] = '1.2.2-cvs2';
	}
	if($settings['panel']['version'] == '1.2.2-cvs2')
	{
		if ( $settings['panel']['standardlanguage'] == 'german' )
		{
			$standardlanguage_new = 'Deutsch' ;
		}
		elseif ( $settings['panel']['standardlanguage'] == 'french' )
		{
			$standardlanguage_new = 'Francais' ;
		}
		else
		{
			$standardlanguage_new = 'English' ;
		}
		$db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='$standardlanguage_new' WHERE `settinggroup`='panel' AND `varname`='standardlanguage'");

		$db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='1.2.2-cvs3' WHERE `settinggroup`='panel' AND `varname`='version'");
		$settings['panel']['version'] = '1.2.2-cvs3';
	}
	if($settings['panel']['version'] == '1.2.2-cvs3')
	{
		$db->query("
			CREATE TABLE `".TABLE_PANEL_CRONSCRIPT."` (
			  `id` int(11) unsigned NOT NULL auto_increment,
			  `file` varchar(255) NOT NULL default '',
			  PRIMARY KEY  (`id`)
			) TYPE=MyISAM
		");
		$db->query("INSERT INTO `".TABLE_PANEL_CRONSCRIPT."` (`id`, `file`) VALUES (1, 'cron_traffic.php');");
		$db->query("INSERT INTO `".TABLE_PANEL_CRONSCRIPT."` (`id`, `file`) VALUES (2, 'cron_tasks.php');");
		$settings['panel']['version'] = '1.2.2-cvs4';
		$db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='1.2.2-cvs4' WHERE `settinggroup`='panel' AND `varname`='version'");
	}
	if($settings['panel']['version'] == '1.2.2-cvs4')
	{
		$db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='1.2.3' WHERE `settinggroup`='panel' AND `varname`='version'");
		$settings['panel']['version'] = '1.2.3';
	}
	if($settings['panel']['version'] == '1.2.3')
	{
		$db->query(
			'DELETE FROM `'.TABLE_PANEL_NAVIGATION.'` ' .
			'WHERE `lang` = "menue;mysql;phpmyadmin" OR `lang` = "menue;email;webmail" OR `lang` = "menue;ftp;webftp"'
		);

		$db->query(
			'ALTER TABLE `'.TABLE_PANEL_NAVIGATION.'` ADD `parent_url` VARCHAR( 255 ) NOT NULL AFTER `parent_id`, ' . 
			'ADD `required_resources` VARCHAR( 255 ) NOT NULL , ' . 
			'ADD `new_window` TINYINT( 1 ) UNSIGNED NOT NULL '
		);

		$updateNavigationResult = $db->query("SELECT `id`, `url` FROM `".TABLE_PANEL_NAVIGATION."` WHERE `parent_id` = '0'");
		while ($updateNavigationRow = $db->fetch_array($updateNavigationResult))
		{
			$db->query("UPDATE `".TABLE_PANEL_NAVIGATION."` SET `parent_url` = '".$updateNavigationRow['url']."' WHERE `parent_id` = '".$updateNavigationRow['id']."'");
		}

		$db->query('ALTER TABLE `'.TABLE_PANEL_NAVIGATION.'` DROP `parent_id`');

		$db->query("UPDATE `".TABLE_PANEL_NAVIGATION."` SET `required_resources` = 'emails' WHERE `lang` = 'menue;email;pop'");
		$db->query("UPDATE `".TABLE_PANEL_NAVIGATION."` SET `required_resources` = 'email_forwarders' WHERE `lang` = 'menue;email;forwarders'");
		$db->query("UPDATE `".TABLE_PANEL_NAVIGATION."` SET `required_resources` = 'mysqls' WHERE `lang` = 'menue;mysql;databases'");
		$db->query("UPDATE `".TABLE_PANEL_NAVIGATION."` SET `required_resources` = 'customers' WHERE `lang` = 'admin;customers'");
		$db->query("UPDATE `".TABLE_PANEL_NAVIGATION."` SET `required_resources` = 'domains' WHERE `lang` = 'admin;domains'");
		$db->query("UPDATE `".TABLE_PANEL_NAVIGATION."` SET `required_resources` = 'change_serversettings' WHERE `lang` = 'admin;admins' OR `lang` = 'admin;configfiles;serverconfiguration' OR `lang` = 'admin;serversettings'");

		if( $settings['panel']['phpmyadmin_url'] != '' )
		{
			$db->query(
				'INSERT INTO `'.TABLE_PANEL_NAVIGATION.'` ' .
				'SET `lang`       = "menue;mysql;phpmyadmin", ' .
				'    `url`        = "'.$settings['panel']['phpmyadmin_url'].'", ' .
				'    `area`       = "customer", ' .
				'    `new_window` = "1", ' .
				'    `required_resources` = "mysqls_used", ' .
				'    `parent_url` = "customer_mysql.php"'
			);
		}

		if( $settings['panel']['webmail_url'] != '' )
		{
			$db->query(
				'INSERT INTO `'.TABLE_PANEL_NAVIGATION.'` ' .
				'SET `lang`       = "menue;email;webmail", ' .
				'    `url`        = "'.$settings['panel']['webmail_url'].'", ' .
				'    `area`       = "customer", ' .
				'    `new_window` = "1", ' .
				'    `required_resources` = "emails_used", ' .
				'    `parent_url` = "customer_email.php"'
			);
		}

		if( $settings['panel']['webftp_url'] != '' )
		{
			$db->query(
				'INSERT INTO `'.TABLE_PANEL_NAVIGATION.'` ' .
				'SET `lang`       = "menue;ftp;webftp", ' .
				'    `url`        = "'.$settings['panel']['webftp_url'].'", ' .
				'    `area`       = "customer", ' .
				'    `new_window` = "1", ' .
				'    `parent_url` = "customer_ftp.php"'
			);
		}

		$db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='1.2.3-cvs1' WHERE `settinggroup`='panel' AND `varname`='version'");
		$settings['panel']['version'] = '1.2.3-cvs1';
	}
	if($settings['panel']['version'] == '1.2.3-cvs1')
	{
		$db->query(
			'ALTER TABLE `'.TABLE_PANEL_DATABASES.'` ' .
			'ADD `description` VARCHAR( 255 ) NOT NULL'
		);
		
		$db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='1.2.3-cvs2' WHERE `settinggroup`='panel' AND `varname`='version'");
		$settings['panel']['version'] = '1.2.3-cvs2';
	}
	if($settings['panel']['version'] == '1.2.3-cvs2')
	{
		$db->query("ALTER TABLE `".TABLE_MAIL_USERS."` ADD `username` VARCHAR( 128 ) NOT NULL");
		$db->query("UPDATE `".TABLE_MAIL_USERS."` SET `username`=`email`");
		
		$db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='1.2.3-cvs3' WHERE `settinggroup`='panel' AND `varname`='version'");
		$settings['panel']['version'] = '1.2.3-cvs3';
	}
	if($settings['panel']['version'] == '1.2.3-cvs3')
	{
		$db->query("UPDATE `".TABLE_PANEL_NAVIGATION."` SET `url`='index.php' WHERE `id`='1'");
		
		$db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='1.2.3-cvs4' WHERE `settinggroup`='panel' AND `varname`='version'");
		$settings['panel']['version'] = '1.2.3-cvs4';
	}
	if($settings['panel']['version'] == '1.2.3-cvs4')
	{
		$db->query("ALTER TABLE `".TABLE_PANEL_TRAFFIC."` ADD UNIQUE `date` ( `customerid` , `year` , `month` , `day` )");
		$db->query("
			CREATE TABLE `".TABLE_PANEL_TRAFFIC_ADMINS."` (
			  `id` int(11) unsigned NOT NULL auto_increment,
			  `adminid` int(11) unsigned NOT NULL default '0',
			  `year` int(4) unsigned zerofill NOT NULL default '0000',
			  `month` int(2) unsigned zerofill NOT NULL default '00',
			  `day` int(2) unsigned zerofill NOT NULL default '00',
			  `http` bigint(30) unsigned NOT NULL default '0',
			  `ftp_up` bigint(30) unsigned NOT NULL default '0',
			  `ftp_down` bigint(30) unsigned NOT NULL default '0',
			  `mail` bigint(30) unsigned NOT NULL default '0',
			  PRIMARY KEY  (`id`),
			  KEY `adminid` (`adminid`),
			  UNIQUE `date` (`adminid` , `year` , `month` , `day`)
			) TYPE=MyISAM
		");

		$db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='1.2.3-cvs5' WHERE `settinggroup`='panel' AND `varname`='version'");
		$settings['panel']['version'] = '1.2.3-cvs5';	
	}
	if($settings['panel']['version'] == '1.2.3-cvs5')
	{
		$db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='1.2.4' WHERE `settinggroup`='panel' AND `varname`='version'");
		$settings['panel']['version'] = '1.2.4';
	}
	if($settings['panel']['version'] == '1.2.4')
	{
		$db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='1.2.4-2' WHERE `settinggroup`='panel' AND `varname`='version'");
		$settings['panel']['version'] = '1.2.4-2';
	}
	if($settings['panel']['version'] == '1.2.4-2')
	{
		$db->query(
			'ALTER TABLE `'.TABLE_PANEL_HTACCESS.'` ADD `error404path` VARCHAR( 255 ) NOT NULL ,
				ADD `error403path` VARCHAR( 255 ) NOT NULL ,
				ADD `error500path` VARCHAR( 255 ) NOT NULL ,
				ADD `error401path` VARCHAR( 255 ) NOT NULL
		');
		$db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='1.2.4-2cvs1' WHERE `settinggroup`='panel' AND `varname`='version'");
		$settings['panel']['version'] = '1.2.4-2cvs1';
	}
	if($settings['panel']['version'] == '1.2.4-2cvs1')
	{
		$db->query(
			'ALTER TABLE `'.TABLE_PANEL_CUSTOMERS.'`
				ADD `email_accounts` INT( 15 ) NOT NULL AFTER `emails_used` ,
				ADD `email_accounts_used` INT( 15 ) NOT NULL AFTER `email_accounts`
		');
		$db->query(
			'ALTER TABLE `'.TABLE_PANEL_ADMINS.'`
				ADD `email_accounts` INT( 15 ) NOT NULL AFTER `emails_used` ,
				ADD `email_accounts_used` INT( 15 ) NOT NULL AFTER `email_accounts`
		');

		$db->query ( 'UPDATE `'.TABLE_PANEL_CUSTOMERS.'` SET `email_accounts` = `emails` ');
		$db->query ( 'UPDATE `'.TABLE_PANEL_ADMINS.'` SET `email_accounts` = `emails` ');

		$db->query ( 'UPDATE `'.TABLE_PANEL_NAVIGATION.'` SET `url` = "customer_email.php?page=emails", `lang` = "menue;email;emails" WHERE `id` = "6" ');
		$db->query ( 'DELETE FROM `'.TABLE_PANEL_NAVIGATION.'` WHERE `id` = "7" ');

		$db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='1.2.4-2cvs2' WHERE `settinggroup`='panel' AND `varname`='version'");
		$settings['panel']['version'] = '1.2.4-2cvs2';
	}
	if($settings['panel']['version'] == '1.2.4-2cvs2')
	{
		$db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='1.2.5' WHERE `settinggroup`='panel' AND `varname`='version'");
		$settings['panel']['version'] = '1.2.5';
	}
	if($settings['panel']['version'] == '1.2.5')
	{
		$db->query("UPDATE `".TABLE_FTP_USERS."` SET `password`=ENCRYPT(`password`)");
		$db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='1.2.5-cvs1' WHERE `settinggroup`='panel' AND `varname`='version'");
		$settings['panel']['version'] = '1.2.5-cvs1';
	}
	if($settings['panel']['version'] == '1.2.5-cvs1')
	{
		$db->query(
			'INSERT INTO `'.TABLE_PANEL_NAVIGATION.'` ' .
			'SET `lang`       = "admin;resources", ' .
			'    `url`        = "admin_resources.nourl", ' .
			'    `area`       = "admin"'
		);
		$db->query(
			'INSERT INTO `'.TABLE_PANEL_NAVIGATION.'` ' .
			'SET `lang`       = "admin;server", ' .
			'    `url`        = "admin_server.nourl", ' .
			'    `required_resources` = "change_serversettings", ' .
			'    `area`       = "admin"'
		);
		$db->query(
			'INSERT INTO `'.TABLE_PANEL_NAVIGATION.'` ' .
			'SET `lang`       = "login;login", ' .
			'    `url`        = "login.nourl", ' .
			'    `area`       = "login"'
		);

		$db->query(
			'UPDATE `'.TABLE_PANEL_NAVIGATION.'` ' .
			'SET `parent_url` = "admin_index.php" ' .
			'WHERE `url`      = "admin_index.php?page=change_password" OR ' .
			'      `url`      = "admin_index.php?action=logout"'
		);
		$db->query(
			'UPDATE `'.TABLE_PANEL_NAVIGATION.'` ' .
			'SET `parent_url` = "admin_resources.nourl" ' .
			'WHERE `url`      = "admin_customers.php?page=customers" OR ' .
			'      `url`      = "admin_domains.php?page=domains" OR ' .
			'      `url`      = "admin_admins.php?page=admins"'
		);
		$db->query(
			'UPDATE `'.TABLE_PANEL_NAVIGATION.'` ' .
			'SET `parent_url` = "admin_server.nourl" ' .
			'WHERE `url`      = "admin_configfiles.php?page=configfiles" OR ' .
			'      `url`      = "admin_settings.php?page=settings"'
		);
		$db->query(
			'UPDATE `'.TABLE_PANEL_NAVIGATION.'` ' .
			'SET `parent_url` = "login.nourl" ' .
			'WHERE `url`      = "index.php"'
		);

		$db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='1.2.5-cvs2' WHERE `settinggroup`='panel' AND `varname`='version'");
		$settings['panel']['version'] = '1.2.5-cvs2';
	}
	if($settings['panel']['version'] == '1.2.5-cvs2')
	{
		$db->query(
			'ALTER TABLE `'.TABLE_MAIL_VIRTUAL.'`
				ADD `email_full` VARCHAR( 50 ) NOT NULL AFTER `email` ,
				ADD `iscatchall` TINYINT( 1 ) UNSIGNED NOT NULL AFTER `popaccountid`
		');
		$db->query( 'UPDATE `'.TABLE_MAIL_VIRTUAL.'` SET `email_full` = `email`' );
		$email_virtual_result = $db->query ( 'SELECT `id`, `email` FROM `'.TABLE_MAIL_VIRTUAL.'`' );
		while ( $email_virtual_row = $db->fetch_array ( $email_virtual_result ) )
		{
			if($email_virtual_row['email']{0} == '@')
			{
				$email_full = $settings['email']['catchallkeyword'] . $email_virtual_row['email'] ;
				$db->query ( 'UPDATE `'.TABLE_MAIL_VIRTUAL.'` SET `email_full` = "' . $email_full . '", `iscatchall` = "1" WHERE `id` = "' . $email_virtual_row['id'] . '"' );
			}
		}

		$db->query ( ' DELETE FROM `'.TABLE_PANEL_SETTINGS.'` WHERE `settinggroup` = "email" AND `varname` = "catchallkeyword" ' ) ;

		$db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='1.2.5-cvs3' WHERE `settinggroup`='panel' AND `varname`='version'");
		$settings['panel']['version'] = '1.2.5-cvs3';
	}
	if($settings['panel']['version'] == '1.2.5-cvs3')
	{
		$db->query(
			'UPDATE `'.TABLE_PANEL_HTACCESS.'` ' .
			'SET `error404path` = "", ' .
			'    `error403path` = "", ' .
			'    `error401path` = "", ' .
			'    `error500path` = "" '
		);
		
		$result = $db->query(
			'SELECT `path` ' .
			'FROM `'.TABLE_PANEL_HTACCESS.'` '
		);
		while ($row = $db->fetch_array($result))
		{
			inserttask('3', $row['path']);
		}

		$db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='1.2.5-cvs4' WHERE `settinggroup`='panel' AND `varname`='version'");
		$settings['panel']['version'] = '1.2.5-cvs4';
	}
	if($settings['panel']['version'] == '1.2.5-cvs4')
	{
		$db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='1.2.6' WHERE `settinggroup`='panel' AND `varname`='version'");
		$settings['panel']['version'] = '1.2.6';
	}
	if($settings['panel']['version'] == '1.2.6')
	{
		$result = $db->query_first( 'SELECT `value` FROM `'.TABLE_PANEL_SETTINGS.'` WHERE `settinggroup` = \'panel\' AND `varname` = \'standardlanguage\'' );
		$def_language = $result['value'];
		
		$db->query( 'ALTER TABLE `'.TABLE_PANEL_ADMINS.'` ADD `def_language` VARCHAR( 255 ) NOT NULL AFTER `email`' );
		$db->query( 'UPDATE `'.TABLE_PANEL_ADMINS.'` SET `def_language` = \''.$def_language.'\'');

		$db->query( 'ALTER TABLE `'.TABLE_PANEL_CUSTOMERS.'` ADD `def_language` VARCHAR( 255 ) NOT NULL AFTER `customernumber`' );
		$db->query( 'UPDATE `'.TABLE_PANEL_CUSTOMERS.'` SET `def_language` = \''.$def_language.'\'' );

		$db->query( 'INSERT INTO `'.TABLE_PANEL_NAVIGATION.'` (`area`,`parent_url`,`lang`,`url`) VALUES (\'customer\',\'customer_index.php\',\'menue;main;changelanguage\',\'customer_index.php?page=change_language\')' );
		$db->query( 'INSERT INTO `'.TABLE_PANEL_NAVIGATION.'` (`area`,`parent_url`,`lang`,`url`) VALUES (\'admin\',\'admin_index.php\',\'menue;main;changelanguage\',\'admin_index.php?page=change_language\')' );
		
		$db->query( 'CREATE TABLE `'.TABLE_PANEL_TEMPLATES.'` (
  			`id` int(11) NOT NULL auto_increment,
  			`adminid` int(11) NOT NULL default \'0\',
  			`language` varchar(255) NOT NULL default \'\',
  			`templategroup` varchar(255) NOT NULL default \'\',
  			`varname` varchar(255) NOT NULL default \'\',
  			`value` longtext NOT NULL,
  			PRIMARY KEY  (`id`),
  			KEY `adminid` (`adminid`)
			) TYPE=MyISAM
		' );
		
		$db->query('INSERT INTO `'.TABLE_PANEL_TEMPLATES.'` (`id`, `adminid`, `language`, `templategroup`, `varname`, `value`) VALUES (1, 1, \'English\', \'mails\', \'createcustomer_subject\', \'Account informationen\')');
		$db->query('INSERT INTO `'.TABLE_PANEL_TEMPLATES.'` (`id`, `adminid`, `language`, `templategroup`, `varname`, `value`) VALUES (2, 1, \'English\', \'mails\', \'createcustomer_mailbody\', \'Hello {FIRSTNAME} {NAME},\r\n\r\nhere is your account information:\r\nUsername: {USERNAME}\r\nPassword: {PASSWORD}\r\n\r\nThank you,\r\nthe SysCP-Team\')');
		$db->query('INSERT INTO `'.TABLE_PANEL_TEMPLATES.'` (`id`, `adminid`, `language`, `templategroup`, `varname`, `value`) VALUES (3, 1, \'English\', \'mails\', \'pop_success_subject\', \'Mail account set up successfully\')');
		$db->query('INSERT INTO `'.TABLE_PANEL_TEMPLATES.'` (`id`, `adminid`, `language`, `templategroup`, `varname`, `value`) VALUES (4, 1, \'English\', \'mails\', \'pop_success_mailbody\', \'Hello,\r\nyour Mail account {EMAIL}\r\nwas set up successfully.\r\n\r\nThis is an automatically created\r\neMail, please do not answer!\r\n\r\nYours sincerely, the SysCP-Team\')');
		$db->query('INSERT INTO `'.TABLE_PANEL_TEMPLATES.'` (`id`, `adminid`, `language`, `templategroup`, `varname`, `value`) VALUES (5, 1, \'Deutsch\', \'mails\', \'createcustomer_subject\', \'Accountinformationen\')');
		$db->query('INSERT INTO `'.TABLE_PANEL_TEMPLATES.'` (`id`, `adminid`, `language`, `templategroup`, `varname`, `value`) VALUES (6, 1, \'Deutsch\', \'mails\', \'createcustomer_mailbody\', \'Hallo {FIRSTNAME} {NAME},\r\n\r\nhier ihre Accountinformationen:\r\n\r\nBenutzername: {USERNAME}\r\nPassword: {PASSWORD}\r\n\r\nVielen Dank,\r\nIhr SysCP-Team\')');
		$db->query('INSERT INTO `'.TABLE_PANEL_TEMPLATES.'` (`id`, `adminid`, `language`, `templategroup`, `varname`, `value`) VALUES (7, 1, \'Deutsch\', \'mails\', \'pop_success_subject\', \'eMail-Konto erfolgreich eingerichtet\')');
		$db->query('INSERT INTO `'.TABLE_PANEL_TEMPLATES.'` (`id`, `adminid`, `language`, `templategroup`, `varname`, `value`) VALUES (8, 1, \'Deutsch\', \'mails\', \'pop_success_mailbody\', \'Hallo,\r\n\r\nihr eMail-Konto {EMAIL}\r\nwurde erfolgreich eingerichtet.\r\nDies ist eine automatisch generierte\r\neMail, bitte antworten Sie nicht auf\r\ndiese Mitteilung.\r\n\r\nIhr SysCP-Team\')');
		$db->query('INSERT INTO `'.TABLE_PANEL_TEMPLATES.'` (`id`, `adminid`, `language`, `templategroup`, `varname`, `value`) VALUES (9, 1, \'Francais\', \'mails\', \'createcustomer_subject\', \'Informations de votre acc&egrave;s\')');
		$db->query('INSERT INTO `'.TABLE_PANEL_TEMPLATES.'` (`id`, `adminid`, `language`, `templategroup`, `varname`, `value`) VALUES (10, 1, \'Francais\', \'mails\', \'createcustomer_mailbody\', \'Bonjour {FIRSTNAME} {NAME},\r\n\r\nici vos informations d´acc&egrave;s:\r\n\r\nIdentifiant: {USERNAME}\r\nMot de passe: {PASSWORD}\r\n\r\nNous vous remercions,\r\nVotre Webmaster\')');
		$db->query('INSERT INTO `'.TABLE_PANEL_TEMPLATES.'` (`id`, `adminid`, `language`, `templategroup`, `varname`, `value`) VALUES (11, 1, \'Francais\', \'mails\', \'pop_success_subject\', \'Acc&egrave;s POP3 install&eacute;\')');
		$db->query('INSERT INTO `'.TABLE_PANEL_TEMPLATES.'` (`id`, `adminid`, `language`, `templategroup`, `varname`, `value`) VALUES (12, 1, \'Francais\', \'mails\', \'pop_success_mailbody\', \'Bonjour,\r\n\r\nvotre acc&egrave;s POP3 {EMAIL}\r\na &eacute;t&eacute; install&eacute; avec succ&egrave;s.\r\n\r\nC´est un e-mail g&eacute;ner&eacute; automatiquement, s´il vous plait ne repondez pas a ce message.\r\n\r\nVotre Webmaster\')');
		$db->query('INSERT INTO `'.TABLE_PANEL_TEMPLATES.'` (`id`, `adminid`, `language`, `templategroup`, `varname`, `value`) VALUES (13, 1, \'Chinese\', \'mails\', \'createcustomer_subject\', \'&#36134;&#25143;&#20449;&#24687;\')');
		$db->query('INSERT INTO `'.TABLE_PANEL_TEMPLATES.'` (`id`, `adminid`, `language`, `templategroup`, `varname`, `value`) VALUES (14, 1, \'Chinese\', \'mails\', \'createcustomer_mailbody\', \'&#24744;&#22909;{FIRSTNAME} {NAME},\n\n&#36825;&#37324;&#26159;&#24744;&#30340;&#36134;&#25143;&#20449;&#24687;:\n\n&#29992;&#25143;&#21517;: {USERNAME}\n&#23494;&#30721;: {PASSWORD}\n\n&#38750;&#24120;&#24863;&#35874;&#65292;&#24744;&#30340;&#26381;&#21153;&#23567;&#32452;\')');
		$db->query('INSERT INTO `'.TABLE_PANEL_TEMPLATES.'` (`id`, `adminid`, `language`, `templategroup`, `varname`, `value`) VALUES (15, 1, \'Chinese\', \'mails\', \'pop_success_subject\', \'POP3&#36134;&#25143;&#25104;&#21151;&#34987;&#21019;&#24314;\')');
		$db->query('INSERT INTO `'.TABLE_PANEL_TEMPLATES.'` (`id`, `adminid`, `language`, `templategroup`, `varname`, `value`) VALUES (16, 1, \'Chinese\', \'mails\', \'pop_success_mailbody\', \'&#20320;&#22909;&#20197;&#34987;&#25104;&#21151;&#21019;&#24314;&#36825;&#26159;&#19968;&#20010;&#33258;&#21160;&#29983;&#25104;&#30340;&#36825;&#26159;&#19968;&#20010;&#33258;&#21160;&#29983;&#25104;&#30340;&#37038;&#20214;&#65292;&#35831;&#19981;&#29992;&#31572;&#22797;&#36825;&#20010;&#36890;&#30693;&#24744;&#30340;&#26381;&#21153;&#23567;&#32452;\')');
		
		$db->query( 'INSERT INTO `'.TABLE_PANEL_NAVIGATION.'` (`area`,`lang`,`url`) VALUES (\'admin\',\'admin;templates;templates\',\'admin_templates.nourl\')' );
		$db->query( 'INSERT INTO `'.TABLE_PANEL_NAVIGATION.'` (`area`,`parent_url`,`lang`,`url`) VALUES (\'admin\',\'admin_templates.nourl\',\'admin;templates;email\',\'admin_templates.php?page=email\')' );
		
		$db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='1.2.6-cvs1' WHERE `settinggroup`='panel' AND `varname`='version'");
		$settings['panel']['version'] = '1.2.6-cvs1';
	}
	if($settings['panel']['version'] == '1.2.6-cvs1')
	{
		$db->query(
			'UPDATE `'.TABLE_PANEL_NAVIGATION.'` '.
			'SET `url`=\'admin_index.php\' '.
			'WHERE `url`=\'admin_index.php?page=overview\''
		);
		
		$db->query( 'ALTER TABLE `'.TABLE_PANEL_NAVIGATION.'` ADD `order` INT( 4 ) NOT NULL AFTER `url`' );
		
		$areas = array('login','admin','customer');
		foreach($areas as $area)
		{
			$result = $db->query(
				'SELECT * ' .
				'FROM `'.TABLE_PANEL_NAVIGATION.'` ' .
				'WHERE `area`=\''.$area.'\' AND (`parent_url`=\'\' OR `parent_url`=\' \') ' . 
				'ORDER BY `order`, `id` ASC'
			);
			$i=0;
			while ($row = $db->fetch_array($result))
			{
				$i++;
				$db->query(
					'UPDATE `'.TABLE_PANEL_NAVIGATION.'` '.
					'SET `order`=\''.($i*10).'\' '.
					'WHERE `id`=\''.$row['id'].'\''
				);
				$subResult = $db->query(
					'SELECT * '.
					'FROM `'.TABLE_PANEL_NAVIGATION.'` '.
					'WHERE `area`=\''.$area.'\' AND `parent_url`=\''.$row['url'].'\' ' . 
					'ORDER BY `order`, `id` ASC'
				);
				$j=0;
				while($subRow = $db->fetch_array($subResult))
				{
					$j++;
					$db->query(
						'UPDATE `'.TABLE_PANEL_NAVIGATION.'` '.
						'SET `order`=\''.($j*10).'\' '.
						'WHERE `id`=\''.$subRow['id'].'\''
					);
				}
			}
		}
		
		$db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='1.2.6-cvs2' WHERE `settinggroup`='panel' AND `varname`='version'");
		$settings['panel']['version'] = '1.2.6-cvs2';
	}
	if($settings['panel']['version'] == '1.2.6-cvs2')
	{
		if($sql['host'] == 'localhost')
		{
			$mysql_access_host = 'localhost';
		}
		else
		{
			$mysql_access_host = $serverip;
		}
		$db->query("INSERT INTO `".TABLE_PANEL_SETTINGS."` (`settinggroup`,`varname`,`value`) VALUES ('system','mysql_access_host','$mysql_access_host')");

		$db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='1.2.6-cvs3' WHERE `settinggroup`='panel' AND `varname`='version'");
		$settings['panel']['version'] = '1.2.6-cvs3';
	}
	if($settings['panel']['version'] == '1.2.6-cvs3')
	{
		$db->query("ALTER TABLE `".TABLE_PANEL_CUSTOMERS."` CHANGE `surname` `firstname` VARCHAR( 255 ) NOT NULL ");

		$db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='1.2.6-cvs4' WHERE `settinggroup`='panel' AND `varname`='version'");
		$settings['panel']['version'] = '1.2.6-cvs4';
	}
	if($settings['panel']['version'] == '1.2.6-cvs4')
	{
		$db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='1.2.7' WHERE `settinggroup`='panel' AND `varname`='version'");
		$settings['panel']['version'] = '1.2.7';
	}
	if($settings['panel']['version'] == '1.2.7')
	{
		inserttask('1');
		inserttask('3','/');
		$db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='1.2.7-cvs1' WHERE `settinggroup`='panel' AND `varname`='version'");
		$settings['panel']['version'] = '1.2.7-cvs1';
	}
	if($settings['panel']['version'] == '1.2.7-cvs1')
	{
		$db->query("ALTER TABLE `".TABLE_PANEL_CUSTOMERS."` CHANGE `createstdsubdomain` `standardsubdomain` INT( 11 ) NOT NULL ");
		$result=$db->query(
			'SELECT * ' .
			'FROM `'.TABLE_PANEL_CUSTOMERS.'` ' .
			'WHERE `standardsubdomain`=\'1\''
		);
		while($row=$db->fetch_array($result))
		{
			$db->query(
				"INSERT INTO `".TABLE_PANEL_DOMAINS."` " .
				"(`domain`, `customerid`, `adminid`, `documentroot`, `zonefile`, `isemaildomain`, `openbasedir`, `safemode`, `speciallogfile`, `specialsettings`) " .
				"VALUES ('{$row['loginname']}'.{$settings['system']['hostname']}', '{$row['customerid']}'', '{$row['adminid']}', '{$row['documentroot']}'', '', '0', '1', '1', '0', '')"
			);
			$domainid=$db->insert_id();
			$db->query(
				'UPDATE `'.TABLE_PANEL_CUSTOMERS.'` ' .
				'SET `standardsubdomain`=\''.$domainid.'\' ' .
				'WHERE `customerid`=\''.$row['customerid'].'\''
			);
		}
		inserttask('1');
		
		$db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='1.2.7-cvs2' WHERE `settinggroup`='panel' AND `varname`='version'");
		$settings['panel']['version'] = '1.2.7-cvs2';
	}

?>
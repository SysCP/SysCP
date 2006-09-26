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
		$db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='".$db->escape($standardlanguage_new)."' WHERE `settinggroup`='panel' AND `varname`='standardlanguage'");

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
			$db->query("UPDATE `".TABLE_PANEL_NAVIGATION."` SET `parent_url` = '".$db->escape($updateNavigationRow['url'])."' WHERE `parent_id` = '".(int)$updateNavigationRow['id']."'");
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
				'    `url`        = "'.$db->escape($settings['panel']['phpmyadmin_url']).'", ' .
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
				'    `url`        = "'.$db->escape($settings['panel']['webmail_url']).'", ' .
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
				'    `url`        = "'.$db->escape($settings['panel']['webftp_url']).'", ' .
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
				$db->query ( 'UPDATE `'.TABLE_MAIL_VIRTUAL.'` SET `email_full` = "' . $db->escape($email_full) . '", `iscatchall` = "1" WHERE `id` = "' . (int)$email_virtual_row['id'] . '"' );
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
		$db->query( 'UPDATE `'.TABLE_PANEL_ADMINS.'` SET `def_language` = \''.$db->escape($def_language).'\'');

		$db->query( 'ALTER TABLE `'.TABLE_PANEL_CUSTOMERS.'` ADD `def_language` VARCHAR( 255 ) NOT NULL AFTER `customernumber`' );
		$db->query( 'UPDATE `'.TABLE_PANEL_CUSTOMERS.'` SET `def_language` = \''.$db->escape($def_language).'\'' );

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
				'WHERE `area`=\''.$db->escape($area).'\' AND (`parent_url`=\'\' OR `parent_url`=\' \') ' .
				'ORDER BY `order`, `id` ASC'
			);
			$i=0;
			while ($row = $db->fetch_array($result))
			{
				$i++;
				$db->query(
					'UPDATE `'.TABLE_PANEL_NAVIGATION.'` '.
					'SET `order`=\''.($i*10).'\' '.
					'WHERE `id`=\''.(int)$row['id'].'\''
				);
				$subResult = $db->query(
					'SELECT * '.
					'FROM `'.TABLE_PANEL_NAVIGATION.'` '.
					'WHERE `area`=\''.$db->escape($area).'\' AND `parent_url`=\''.$db->escape($row['url']).'\' ' .
					'ORDER BY `order`, `id` ASC'
				);
				$j=0;
				while($subRow = $db->fetch_array($subResult))
				{
					$j++;
					$db->query(
						'UPDATE `'.TABLE_PANEL_NAVIGATION.'` '.
						'SET `order`=\''.($j*10).'\' '.
						'WHERE `id`=\''.(int)$subRow['id'].'\''
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
		$db->query("INSERT INTO `".TABLE_PANEL_SETTINGS."` (`settinggroup`,`varname`,`value`) VALUES ('system','mysql_access_host','".$db->escape($mysql_access_host)."')");

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
				"VALUES ('".$db->escape($row['loginname']).'.'.$db->escape($settings['system']['hostname'])."', '".(int)$row['customerid']."', '".(int)$row['adminid']."', '".$db->escape($row['documentroot'])."', '', '0', '1', '1', '0', '')"
			);
			$domainid=$db->insert_id();
			$db->query(
				'UPDATE `'.TABLE_PANEL_CUSTOMERS.'` ' .
				'SET `standardsubdomain`=\''.(int)$domainid.'\' ' .
				'WHERE `customerid`=\''.(int)$row['customerid'].'\''
			);
		}
		inserttask('1');

		$db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='1.2.7-cvs2' WHERE `settinggroup`='panel' AND `varname`='version'");
		$settings['panel']['version'] = '1.2.7-cvs2';
	}
	if($settings['panel']['version'] == '1.2.7-cvs2')
	{
		$db->query("ALTER TABLE `".TABLE_PANEL_DOMAINS."` ADD `isbinddomain` TINYINT( 1 ) NOT NULL DEFAULT '0' AFTER `documentroot`");
		$db->query("ALTER TABLE `".TABLE_PANEL_DOMAINS."` ADD `subcanemaildomain` TINYINT( 1 ) NOT NULL DEFAULT '0' AFTER `iswildcarddomain`");
		$db->query("ALTER TABLE `".TABLE_PANEL_DOMAINS."` ADD `caneditdomain` TINYINT( 1 ) NOT NULL DEFAULT '1' AFTER `subcanemaildomain`");

		$db->query(
			'UPDATE ' .
			'`'.TABLE_PANEL_DOMAINS.'` ' .
			'SET `isbinddomain`=\'1\'' .
			'WHERE `isemaildomain`=\'1\''
		);

		$standardsubdomainids=Array();
		$result=$db->query(
			'SELECT * ' .
			'FROM `'.TABLE_PANEL_CUSTOMERS.'` ' .
			'WHERE `standardsubdomain`<>\'0\''
		);
		while($row=$db->fetch_array($result))
		{
			$standardsubdomainids[]="'".(int)$row['standardsubdomain']."'";
		}
		$standardsubdomainids=implode(',',$standardsubdomainids);
		if ( $standardsubdomainids != '' )
		{
			$db->query(
				'UPDATE `'.TABLE_PANEL_DOMAINS.'` ' .
				'SET `caneditdomain`=\'0\' ' .
				'WHERE `id` IN('.$standardsubdomainids.')'
			);
		}

		inserttask('1');

		$db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='1.2.7-cvs3' WHERE `settinggroup`='panel' AND `varname`='version'");
		$settings['panel']['version'] = '1.2.7-cvs3';
	}
	if($settings['panel']['version'] == '1.2.7-cvs3')
	{
		$db->query("INSERT INTO `".TABLE_PANEL_LANGUAGE."` (`language`, `file`) VALUES ('Catalan', 'lng/catalan.lng.php');");

		$db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='1.2.7-cvs4' WHERE `settinggroup`='panel' AND `varname`='version'");
		$settings['panel']['version'] = '1.2.7-cvs4';
	}
	if($settings['panel']['version'] == '1.2.7-cvs4')
	{
		$db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='1.2.8' WHERE `settinggroup`='panel' AND `varname`='version'");
		$settings['panel']['version'] = '1.2.8';
	}
	if($settings['panel']['version'] == '1.2.8' || $settings['panel']['version'] == '1.2.8-cvs1')
	{
		$db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='1.2.9' WHERE `settinggroup`='panel' AND `varname`='version'");
		$settings['panel']['version'] = '1.2.9';
	}
	if($settings['panel']['version'] == '1.2.9')
	{
		$db->query("UPDATE `".TABLE_PANEL_LANGUAGE."` SET `language`='Fran&ccedil;ais' WHERE `language`='Francais'");
		$db->query("UPDATE `".TABLE_PANEL_TEMPLATES."` SET `language`='Fran&ccedil;ais' WHERE `language`='Francais'");
		$db->query("INSERT INTO `".TABLE_PANEL_LANGUAGE."` (`language`, `file`) VALUES ('Espa&ntilde;ol', 'lng/spanish.lng.php');");

		$db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='1.2.9-cvs1' WHERE `settinggroup`='panel' AND `varname`='version'");
		$settings['panel']['version'] = '1.2.9-cvs1';
	}
	if($settings['panel']['version'] == '1.2.9-cvs1')
	{
		$db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='1.2.10' WHERE `settinggroup`='panel' AND `varname`='version'");
		$settings['panel']['version'] = '1.2.10';
	}
	if($settings['panel']['version'] == '1.2.10')
	{
		$db->query("INSERT INTO `".TABLE_PANEL_LANGUAGE."` (`language`, `file`) VALUES ('Portugu&ecirc;s', 'lng/portugues.lng.php');");

		$db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='1.2.10-cvs1' WHERE `settinggroup`='panel' AND `varname`='version'");
		$settings['panel']['version'] = '1.2.10-cvs1';
	}
	if($settings['panel']['version'] == '1.2.10-cvs1')
	{
		$db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='1.2.11' WHERE `settinggroup`='panel' AND `varname`='version'");
		$settings['panel']['version'] = '1.2.11';
	}

	if($settings['panel']['version'] == '1.2.11')
	{
		$db->query("ALTER TABLE `".TABLE_PANEL_DOMAINS."` ADD `aliasdomain` INT( 11 ) UNSIGNED NULL AFTER `customerid`");

		$db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='1.2.11-cvs1' WHERE `settinggroup`='panel' AND `varname`='version'");
		$settings['panel']['version'] = '1.2.11-cvs1';
	}

	if($settings['panel']['version'] == '1.2.11-cvs1')
	{
		$db->query(
			'INSERT INTO `'.TABLE_PANEL_SETTINGS.'` ' .
			'SET `settingid` = \'\' , ' .
			'`settinggroup`  = \'panel\', ' .
			'`varname`       = \'pathedit\', ' .
			'`value`         = \'Manual\''
		);

		$db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='1.2.11-cvs2' WHERE `settinggroup`='panel' AND `varname`='version'");
		$settings['panel']['version'] = '1.2.11-cvs2';
	}

	if($settings['panel']['version'] == '1.2.11-cvs2')
	{
		$db->query(
			'INSERT INTO `'.TABLE_PANEL_NAVIGATION.'` ' .
			'SET `area` = \'admin\', ' .
			'    `parent_url` = \'admin_server.nourl\', ' .
			'    `lang` = \'admin;rebuildconf\', ' .
			'    `url` = \'admin_settings.php?page=rebuildconfigs\', ' .
			'    `order` = \'30\', ' .
			'    `required_resources` = \'change_serversettings\', ' .
			'    `new_window` = \'0\''
		);

		$db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='1.2.11-cvs3' WHERE `settinggroup`='panel' AND `varname`='version'");
		$settings['panel']['version'] = '1.2.11-cvs3';
	}

	if($settings['panel']['version'] == '1.2.11-cvs3')
	{
		$db->query(
			'ALTER TABLE `'.TABLE_MAIL_USERS.'` '.
			'CHANGE  `email`    `email`    VARCHAR( 255 ) NOT NULL , '.
			'CHANGE  `username` `username` VARCHAR( 255 ) NOT NULL , '.
			'CHANGE  `homedir`  `homedir`  VARCHAR( 255 ) NOT NULL , '.
			'CHANGE  `maildir`  `maildir`  VARCHAR( 255 ) NOT NULL '
		);
		$db->query(
			'ALTER TABLE `'.TABLE_MAIL_VIRTUAL.'` '.
			'CHANGE  `email`      `email`      VARCHAR( 255 ) NOT NULL , '.
			'CHANGE  `email_full` `email_full` VARCHAR( 255 ) NOT NULL '
		);

		$db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='1.2.11-cvs4' WHERE `settinggroup`='panel' AND `varname`='version'");
		$settings['panel']['version'] = '1.2.11-cvs4';
	}

	if($settings['panel']['version'] == '1.2.11-cvs4')
	{
		$db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='1.2.12' WHERE `settinggroup`='panel' AND `varname`='version'");
		$settings['panel']['version'] = '1.2.12';
	}

	if( $settings['panel']['version'] == '1.2.12' )
	{
		$db->query(
			'INSERT INTO `'.TABLE_PANEL_SETTINGS.'` ' .
			'SET `settinggroup` = \'system\', ' .
			'    `varname`      = \'apacheconf_filename\', ' .
			'    `value`        = \'vhosts.conf\' '
		);
		$db->query(
			'INSERT INTO `'.TABLE_PANEL_SETTINGS.'` ' .
			'SET `settinggroup` = \'system\', ' .
			'    `varname`      = \'lastcronrun\', ' .
			'    `value`        = \'\' '
		);
		$db->query(
			'INSERT INTO `'.TABLE_PANEL_SETTINGS.'` ' .
			'SET `settinggroup` = \'panel\', ' .
			'    `varname`      = \'paging\', ' .
			'    `value`        = \'20\' '
		);
		$db->query(
			'UPDATE `'.TABLE_PANEL_SETTINGS.'` ' .
			'SET `value` = \'1.2.12-svn1\' ' .
			'WHERE `settinggroup` = \'panel\' ' .
			'AND `varname` = \'version\''
		);

		$settings['panel']['version'] = '1.2.12-svn1';
	}
	if( $settings['panel']['version'] == '1.2.12-svn1' )
	{
		$db->query(
			'ALTER TABLE `'.TABLE_PANEL_DOMAINS.'` ' .
			'ADD `ipandport` int(11) unsigned NOT NULL default \'1\' AFTER `documentroot`'
		);
		$db->query(
			'CREATE TABLE `'.TABLE_PANEL_IPSANDPORTS.'` (
			`id` int(11) unsigned NOT NULL auto_increment,
			`ip` varchar(15) NOT NULL default \'\',
			`port` int(5) NOT NULL default \'80\',
			`default` int(1) NOT NULL default \'0\',
			PRIMARY KEY  (`id`)
			) TYPE=MyISAM'
		);
		$db->query(
			'INSERT INTO `'.TABLE_PANEL_IPSANDPORTS.'` ' .
			'(`ip`, `port`, `default`)' .
			'VALUES (\''.$settings['system']['ipaddress'].'\', \'80\', \'1\')'
		);
		$db->query(
			'INSERT INTO `'.TABLE_PANEL_NAVIGATION.'` ' .
			'(`area`, `parent_url`, `lang`, `url`, `order`, `required_resources`, `new_window`)' .
			'VALUES (\'admin\', \'admin_server.nourl\', \'admin;ipsandports;ipsandports\', \'admin_ipsandports.php?page=ipsandports\', \'40\', \'change_serversettings\', 0)'
		);
		$db->query(
			'UPDATE `'.TABLE_PANEL_SETTINGS.'` ' .
			'SET `value` = \'1.2.12-svn2\' ' .
			'WHERE `settinggroup` = \'panel\' ' .
			'AND `varname` = \'version\''
		);

		$settings['panel']['version'] = '1.2.12-svn2';
	}
	if( $settings['panel']['version'] == '1.2.12-svn2' )
	{
		$db->query(
			'UPDATE `'.TABLE_PANEL_SETTINGS.'` ' .
			'SET `value` = \'1.2.13-rc1\' ' .
			'WHERE `settinggroup` = \'panel\' ' .
			'AND `varname` = \'version\''
		);

		$settings['panel']['version'] = '1.2.13-rc1';
	}
	if( $settings['panel']['version'] == '1.2.13-rc1' )
	{
		$db->query(
			'UPDATE `'.TABLE_PANEL_SETTINGS.'` ' .
			'SET `value` = \'1.2.13-rc2\' ' .
			'WHERE `settinggroup` = \'panel\' ' .
			'AND `varname` = \'version\''
		);

		$settings['panel']['version'] = '1.2.13-rc2';
	}
	if( $settings['panel']['version'] == '1.2.13-rc2' )
	{
		$db->query(
			'UPDATE `'.TABLE_PANEL_SETTINGS.'` ' .
			'SET `value` = \'1.2.13-rc3\' ' .
			'WHERE `settinggroup` = \'panel\' ' .
			'AND `varname` = \'version\''
		);

		$settings['panel']['version'] = '1.2.13-rc3';
	}
	if( $settings['panel']['version'] == '1.2.13-rc3' )
	{
		// update lastcronrun to current date
		$query =
			'UPDATE `%s` ' .
			'SET `value` = UNIX_TIMESTAMP() ' .
			'WHERE `settinggroup` = \'system\' ' .
			'AND `varname` = \'lastcronrun\' ';
		$query = sprintf( $query, TABLE_PANEL_SETTINGS);
		$db->query( $query );
		// set new version
		$query =
			'UPDATE `%s` ' .
			'SET `value` = \'1.2.13-rc4\' ' .
			'WHERE `settinggroup` = \'panel\' ' .
			'AND `varname` = \'version\'';
		$query = sprintf( $query, TABLE_PANEL_SETTINGS);
		$db->query($query);
		$settings['panel']['version'] = '1.2.13-rc4';
	}
	if( $settings['panel']['version'] == '1.2.13-rc4' )
	{
		// set new version
		$query =
			'UPDATE `%s` ' .
			'SET `value` = \'1.2.13\' ' .
			'WHERE `settinggroup` = \'panel\' ' .
			'AND `varname` = \'version\'';
		$query = sprintf( $query, TABLE_PANEL_SETTINGS);
		$db->query($query);
		$settings['panel']['version'] = '1.2.13';
	}
	if( $settings['panel']['version'] == '1.2.13' )
	{
		//get highest accountnumber
		$query =
			'SELECT `loginname`	' .
			'FROM `' . TABLE_PANEL_CUSTOMERS . '` ' .
			'WHERE `loginname` LIKE \'' . $db->escape($settings['customer']['accountprefix']) . '%\';';
		$result = $db->query($query);
		$lastaccountnumber = 0;
		while ($row = $db->fetch_array($result))
		{
			$tmpnumber = intval(substr($row['loginname'], strlen($settings['customer']['accountprefix'])));
			if ( $tmpnumber > $lastaccountnumber )
			{
				$lastaccountnumber = $tmpnumber;
			}
		}
		//update the lastaccountnumber to refer to the highest account availible + 1
		$query =
			'UPDATE `%s` ' .
			'SET `value` = \''. (int)$lastaccountnumber . '\' ' .
			'WHERE `settinggroup` = \'system\' ' .
			'AND `varname` = \'lastaccountnumber\'';
		$query = sprintf( $query, TABLE_PANEL_SETTINGS);
		$db->query($query);
		$settings['system']['lastaccountnumber'] = $lastaccountnumber;

		// set new version
		$query =
			'UPDATE `%s` ' .
			'SET `value` = \'1.2.13-svn1\' ' .
			'WHERE `settinggroup` = \'panel\' ' .
			'AND `varname` = \'version\'';
		$query = sprintf( $query, TABLE_PANEL_SETTINGS);
		$db->query($query);
		$settings['panel']['version'] = '1.2.13-svn1';
	}
	if( $settings['panel']['version'] == '1.2.13-svn1' )
	{
		$query =
			'ALTER TABLE `%s` ADD `openbasedir_path` TINYINT( 1 ) UNSIGNED NOT NULL AFTER `openbasedir` ';
		$query = sprintf( $query, TABLE_PANEL_DOMAINS);
		$db->query($query);

		// set new version
		$query =
			'UPDATE `%s` ' .
			'SET `value` = \'1.2.13-svn2\' ' .
			'WHERE `settinggroup` = \'panel\' ' .
			'AND `varname` = \'version\'';
		$query = sprintf( $query, TABLE_PANEL_SETTINGS);
		$db->query($query);
		$settings['panel']['version'] = '1.2.13-svn2';
	}
	if( $settings['panel']['version'] == '1.2.13-svn2' )
	{
		// Show the logged-in username in "overview"
		$db->query(
			'INSERT INTO `'.TABLE_PANEL_NAVIGATION.'` ' .
			'(`area`, `parent_url`, `lang`, `url`, `order`, `required_resources`, `new_window`)' .
			'VALUES (\'admin\', \'admin_index.php\', \'menue;main;username\', \'admin_index.nourl\', \'5\', \'\', 0)'
		);
		$db->query(
			'INSERT INTO `'.TABLE_PANEL_NAVIGATION.'` ' .
			'(`area`, `parent_url`, `lang`, `url`, `order`, `required_resources`, `new_window`)' .
			'VALUES (\'customer\', \'customer_index.php\', \'menue;main;username\', \'customer_index.nourl\', \'5\', \'\', 0)'
		);

		// set new version
		$query =
			'UPDATE `%s` ' .
			'SET `value` = \'1.2.13-svn3\' ' .
			'WHERE `settinggroup` = \'panel\' ' .
			'AND `varname` = \'version\'';
		$query = sprintf( $query, TABLE_PANEL_SETTINGS);
		$db->query($query);
		$settings['panel']['version'] = '1.2.13-svn3';
	}
	if( $settings['panel']['version'] == '1.2.13-svn3' )
	{
		$result = $db->query_first(
			'SELECT `id` FROM `'.TABLE_PANEL_IPSANDPORTS.'` WHERE `default` = \'1\' '
		);
		$defaultip=$result['id'];

		$db->query(
			'INSERT INTO `'.TABLE_PANEL_SETTINGS.'` ' .
			'SET `settinggroup` = \'system\', ' .
			'    `varname`      = \'defaultip\', ' .
			'    `value`        = \''.(int)$defaultip.'\' '
		);

		$db->query( 'ALTER TABLE `'.TABLE_PANEL_IPSANDPORTS.'` DROP `default` ');

		// set new version
		$query =
			'UPDATE `%s` ' .
			'SET `value` = \'1.2.13-svn4\' ' .
			'WHERE `settinggroup` = \'panel\' ' .
			'AND `varname` = \'version\'';
		$query = sprintf( $query, TABLE_PANEL_SETTINGS);
		$db->query($query);
		$settings['panel']['version'] = '1.2.13-svn4';
	}
	if( $settings['panel']['version'] == '1.2.13-svn4' )
	{
		$db->query(
			'ALTER TABLE `'.TABLE_PANEL_SESSIONS.'` ' .
			' ADD `lastpaging` VARCHAR( 255 ) NOT NULL AFTER `lastactivity` '
		);

		// set new version
		$query =
			'UPDATE `%s` ' .
			'SET `value` = \'1.2.13-svn5\' ' .
			'WHERE `settinggroup` = \'panel\' ' .
			'AND `varname` = \'version\'';
		$query = sprintf( $query, TABLE_PANEL_SETTINGS);
		$db->query($query);
		$settings['panel']['version'] = '1.2.13-svn5';
	}
	if( $settings['panel']['version'] == '1.2.13-svn5' )
	{
		// set new version
		$query =
			'UPDATE `%s` ' .
			'SET `value` = \'1.2.14-rc1\' ' .
			'WHERE `settinggroup` = \'panel\' ' .
			'AND `varname` = \'version\'';
		$query = sprintf( $query, TABLE_PANEL_SETTINGS);
		$db->query($query);
		$settings['panel']['version'] = '1.2.14-rc1';
	}
	if( $settings['panel']['version'] == '1.2.14-rc1' )
	{
		$db->query("INSERT INTO `".TABLE_PANEL_LANGUAGE."` (`language`, `file`) VALUES ('Russian', 'lng/russian.lng.php');");

		// set new version
		$query =
			'UPDATE `%s` ' .
			'SET `value` = \'1.2.14-rc1-svn1\' ' .
			'WHERE `settinggroup` = \'panel\' ' .
			'AND `varname` = \'version\'';
		$query = sprintf( $query, TABLE_PANEL_SETTINGS);
		$db->query($query);
		$settings['panel']['version'] = '1.2.14-rc1-svn1';
	}
	if( $settings['panel']['version'] == '1.2.14-rc1-svn1' )
	{
		// set new version
		$query =
			'UPDATE `%s` ' .
			'SET `value` = \'1.2.14-rc2\' ' .
			'WHERE `settinggroup` = \'panel\' ' .
			'AND `varname` = \'version\'';
		$query = sprintf( $query, TABLE_PANEL_SETTINGS);
		$db->query($query);
		$settings['panel']['version'] = '1.2.14-rc2';
	}
	if( $settings['panel']['version'] == '1.2.14-rc2' )
	{
		// set new version
		$query =
			'UPDATE `%s` ' .
			'SET `value` = \'1.2.14-rc3\' ' .
			'WHERE `settinggroup` = \'panel\' ' .
			'AND `varname` = \'version\'';
		$query = sprintf( $query, TABLE_PANEL_SETTINGS);
		$db->query($query);
		$settings['panel']['version'] = '1.2.14-rc3';
	}
	if( $settings['panel']['version'] == '1.2.14-rc3' )
	{
		$db->query("INSERT INTO `".TABLE_PANEL_LANGUAGE."` (`language`, `file`) VALUES ('Danish', 'lng/danish.lng.php');");

		// set new version
		$query =
			'UPDATE `%s` ' .
			'SET `value` = \'1.2.14-rc3-svn1\' ' .
			'WHERE `settinggroup` = \'panel\' ' .
			'AND `varname` = \'version\'';
		$query = sprintf( $query, TABLE_PANEL_SETTINGS);
		$db->query($query);
		$settings['panel']['version'] = '1.2.14-rc3-svn1';
	}
	if( $settings['panel']['version'] == '1.2.14-rc3-svn1' )
	{
		$db->query("ALTER TABLE `".TABLE_PANEL_ADMINS."` CHANGE `diskspace` `diskspace` BIGINT( 30 ) NOT NULL DEFAULT '0';");
		$db->query("ALTER TABLE `".TABLE_PANEL_ADMINS."` CHANGE `diskspace_used` `diskspace_used` BIGINT( 30 ) NOT NULL DEFAULT '0';");
		$db->query("ALTER TABLE `".TABLE_PANEL_ADMINS."` CHANGE `traffic` `traffic` BIGINT( 30 ) NOT NULL DEFAULT '0';");
		$db->query("ALTER TABLE `".TABLE_PANEL_ADMINS."` CHANGE `traffic_used` `traffic_used` BIGINT( 30 ) NOT NULL DEFAULT '0';");
		$db->query("ALTER TABLE `".TABLE_PANEL_CUSTOMERS."` CHANGE `diskspace` `diskspace` BIGINT( 30 ) NOT NULL DEFAULT '0';");
		$db->query("ALTER TABLE `".TABLE_PANEL_CUSTOMERS."` CHANGE `diskspace_used` `diskspace_used` BIGINT( 30 ) NOT NULL DEFAULT '0';");
		$db->query("ALTER TABLE `".TABLE_PANEL_CUSTOMERS."` CHANGE `traffic` `traffic` BIGINT( 30 ) NOT NULL DEFAULT '0';");
		$db->query("ALTER TABLE `".TABLE_PANEL_CUSTOMERS."` CHANGE `traffic_used` `traffic_used` BIGINT( 30 ) NOT NULL DEFAULT '0';");
		$query = 'SELECT * FROM `'.TABLE_PANEL_LANGUAGE.'` WHERE `language` = \'Russian\';';
		$result = $db->query($query);
		if ($db->num_rows($result) == 0)
		{
			$db->query("INSERT INTO `".TABLE_PANEL_LANGUAGE."` (`language`, `file`) VALUES ('Russian', 'lng/russian.lng.php');");
		}
		// set new version
		$query =
			'UPDATE `%s` ' .
			'SET `value` = \'1.2.14-rc3-svn2\' ' .
			'WHERE `settinggroup` = \'panel\' ' .
			'AND `varname` = \'version\'';
		$query = sprintf( $query, TABLE_PANEL_SETTINGS);
		$db->query($query);
		$settings['panel']['version'] = '1.2.14-rc3-svn2';
	}
	if( $settings['panel']['version'] == '1.2.14-rc3-svn2' )
	{
		$db->query("INSERT INTO `".TABLE_PANEL_LANGUAGE."` (`language`, `file`) VALUES ('Italian', 'lng/italian.lng.php');");

		// set new version
		$query =
			'UPDATE `%s` ' .
			'SET `value` = \'1.2.14-rc3-svn3\' ' .
			'WHERE `settinggroup` = \'panel\' ' .
			'AND `varname` = \'version\'';
		$query = sprintf( $query, TABLE_PANEL_SETTINGS);
		$db->query($query);
		$settings['panel']['version'] = '1.2.14-rc3-svn3';
	}
	if( $settings['panel']['version'] == '1.2.14-rc3-svn3' )
	{
		// set new version
		$query =
			'UPDATE `%s` ' .
			'SET `value` = \'1.2.14-rc4\' ' .
			'WHERE `settinggroup` = \'panel\' ' .
			'AND `varname` = \'version\'';
		$query = sprintf( $query, TABLE_PANEL_SETTINGS);
		$db->query($query);
		$settings['panel']['version'] = '1.2.14-rc4';
	}
	if( $settings['panel']['version'] == '1.2.14-rc4' )
	{
		// set new version
		$query =
			'UPDATE `%s` ' .
			'SET `value` = \'1.2.14\' ' .
			'WHERE `settinggroup` = \'panel\' ' .
			'AND `varname` = \'version\'';
		$query = sprintf( $query, TABLE_PANEL_SETTINGS);
		$db->query($query);
		$settings['panel']['version'] = '1.2.14';
	}

?>
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
			'ALTER TABLE `panel_databases` ' .
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
		$db->query("UPDATE `".TABLE_PANEL_NAVIGATION."` SET `url`=`index.php` WHERE `id`='1'");
		
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

?>
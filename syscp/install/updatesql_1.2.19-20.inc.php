<?php

/**
 * This file is part of the SysCP project.
 * Copyright (c) 2003-2008 the SysCP Team (see authors).
 *
 * For the full copyright and license information, please view the COPYING
 * file that was distributed with this source code. You can also view the
 * COPYING file online at http://files.syscp.org/misc/COPYING.txt
 *
 * @copyright  (c) the authors
 * @author     Michael Kaufmann <mk@syscp-help.org>
 * @license    GPLv2 http://files.syscp.org/misc/COPYING.txt
 * @package    System
 * @version    $Id$
 */

if($settings['panel']['version'] == '1.2.19')
{
	$db->query('INSERT INTO `' . TABLE_PANEL_SETTINGS . '` (`settinggroup`, `varname`, `value`) VALUES (\'system\', \'mod_fcgid_configdir\', \'/var/www/php-fcgi-scripts\')');
	$db->query('INSERT INTO `' . TABLE_PANEL_SETTINGS . '` (`settinggroup`, `varname`, `value`) VALUES (\'system\', \'mod_fcgid_tmpdir\', \'/var/kunden/tmp\')');

	// set new version

	$query = 'UPDATE `%s` SET `value` = \'1.2.19-svn1\' WHERE `settinggroup` = \'panel\' AND `varname` = \'version\'';
	$query = sprintf($query, TABLE_PANEL_SETTINGS);
	$db->query($query);
	$settings['panel']['version'] = '1.2.19-svn1';
}

if($settings['panel']['version'] == '1.2.19-svn1')
{
	$db->query('INSERT INTO `' . TABLE_PANEL_LANGUAGE . '` (`language`, `file`) VALUES (\'Swedish\', \'lng/swedish.lng.php\');');

	// set new version

	$query = 'UPDATE `%s` SET `value` = \'1.2.19-svn2\' WHERE `settinggroup` = \'panel\' AND `varname` = \'version\'';
	$query = sprintf($query, TABLE_PANEL_SETTINGS);
	$db->query($query);
	$settings['panel']['version'] = '1.2.19-svn2';
}

if($settings['panel']['version'] == '1.2.19-svn2')
{
	$db->query('INSERT INTO `' . TABLE_PANEL_SETTINGS . '` (`settinggroup`, `varname`, `value`) VALUES (\'ticket\', \'reset_cycle\', \'2\')');

	// set new version

	$query = 'UPDATE `%s` SET `value` = \'1.2.19-svn3\' WHERE `settinggroup` = \'panel\' AND `varname` = \'version\'';
	$query = sprintf($query, TABLE_PANEL_SETTINGS);
	$db->query($query);
	$settings['panel']['version'] = '1.2.19-svn3';
}

if($settings['panel']['version'] == '1.2.19-svn3')
{
	$db->query('INSERT INTO `' . TABLE_PANEL_NAVIGATION . '` SET `area`=\'customer\', `parent_url`=\'\', `lang`=\'menue;traffic;traffic\', `url`=\'customer_traffic.php\', `order`=\'80\';');
	$db->query('INSERT INTO `' . TABLE_PANEL_NAVIGATION . '` SET `area`=\'customer\', `parent_url`=\'customer_traffic.php\', `lang`=\'menue;traffic;current\', `url`=\'customer_traffic.php?page=current\', `order`=\'10\';');

	// set new version

	$query = 'UPDATE `%s` SET `value` = \'1.2.19-svn4\' WHERE `settinggroup` = \'panel\' AND `varname` = \'version\'';
	$query = sprintf($query, TABLE_PANEL_SETTINGS);
	$db->query($query);
	$settings['panel']['version'] = '1.2.19-svn4';
}

if($settings['panel']['version'] == '1.2.19-svn4')
{
	$db->query('INSERT INTO `' . TABLE_PANEL_SETTINGS . '` (`settinggroup`, `varname`, `value`) VALUES (\'panel\', \'no_robots\', \'1\')');

	// set new version

	$query = 'UPDATE `%s` SET `value` = \'1.2.19-svn4.5\' WHERE `settinggroup` = \'panel\' AND `varname` = \'version\'';
	$query = sprintf($query, TABLE_PANEL_SETTINGS);
	$db->query($query);
	$settings['panel']['version'] = '1.2.19-svn4.5';
}

if($settings['panel']['version'] == '1.2.19-svn4.5')
{
	$db->query('INSERT INTO `' . TABLE_PANEL_SETTINGS . '` (`settinggroup`, `varname`, `value`) VALUES (\'logger\', \'enabled\', \'1\');');
	$db->query('INSERT INTO `' . TABLE_PANEL_SETTINGS . '` (`settinggroup`, `varname`, `value`) VALUES (\'logger\', \'log_cron\', \'0\');');
	$db->query('INSERT INTO `' . TABLE_PANEL_SETTINGS . '` (`settinggroup`, `varname`, `value`) VALUES (\'logger\', \'logfile\', \'\');');
	$db->query('INSERT INTO `' . TABLE_PANEL_SETTINGS . '` (`settinggroup`, `varname`, `value`) VALUES (\'logger\', \'logtypes\', \'syslog\');');
	$db->query('INSERT INTO `' . TABLE_PANEL_SETTINGS . '` (`settinggroup`, `varname`, `value`) VALUES (\'logger\', \'severity\', \'2\');');
	$db->query('INSERT INTO `' . TABLE_PANEL_NAVIGATION . '` SET `area`=\'admin\', `parent_url`=\'\', `lang`=\'admin;loggersystem\', `url`=\'admin_loggersystem.nourl\', `order`=\'60\';');
	$db->query('INSERT INTO `' . TABLE_PANEL_NAVIGATION . '` SET `area`=\'admin\', `parent_url`=\'admin_loggersystem.nourl\', `lang`=\'menue;logger;logger\', `url`=\'admin_logger.php?page=log\', `order`=\'10\';');
	$db->query('CREATE TABLE IF NOT EXISTS `panel_syslog` (
	  `logid` bigint(20) NOT NULL auto_increment,
	  `action` int(5) NOT NULL default \'10\',
	  `type` int(5) NOT NULL default \'0\',
	  `date` int(15) NOT NULL,
	  `user` varchar(50) NOT NULL,
	  `text` text NOT NULL,
	  PRIMARY KEY  (`logid`)
	  ) ENGINE=MyISAM;');

	// set new version

	$query = 'UPDATE `%s` SET `value` = \'1.2.19-svn6\' WHERE `settinggroup` = \'panel\' AND `varname` = \'version\'';
	$query = sprintf($query, TABLE_PANEL_SETTINGS);
	$db->query($query);
	$settings['panel']['version'] = '1.2.19-svn6';
}

// ok, from this version on, we need the php filter-extension!

if(!extension_loaded('filter'))
{
	$updatelog->logAction(ADM_ACTION, LOG_ERR, "You need to install the php filter-extension! Update to 1.2.19-svn6 aborted");

	// skipping the update will not work, this ends up in an endless redirection from index.php to updatesql.php and back to index.php

	die("You need to install the php filter-extension! Update to 1.2.19-svn6 aborted");
}
else
{
	if(!extension_loaded('bcmath'))
	{
		$updatelog->logAction(ADM_ACTION, LOG_WARNING, "The php extension 'bcmath' is not installed - SysCP will work without it but might not return exact traffic/space-usage values!");
	}

	$php_ob = @ini_get("open_basedir");

	if(!empty($php_ob)
	   && $php_ob != '')
	{
		$updatelog->logAction(ADM_ACTION, LOG_WARNING, "Detected enabled 'open_basedir', please disable open_basedir to make SysCP work properly!");
	}

	if($settings['panel']['version'] == '1.2.19-svn6')
	{
		$updatelog->logAction(ADM_ACTION, LOG_WARNING, "Updating from 1.2.19-svn6 to 1.2.19-svn7");
		$db->query("INSERT INTO `" . TABLE_PANEL_NAVIGATION . "` SET `area`='admin', `parent_url`='', `lang`='menu;message', `url`='admin_message.nourl', `order`=50");
		$db->query("INSERT INTO `" . TABLE_PANEL_NAVIGATION . "` SET `area`='admin', `parent_url`='admin_message.nourl', `lang`='admin;message', `url`='admin_message.php?page=message', `order`=10");
		$db->query("ALTER TABLE `" . TABLE_PANEL_DOMAINS . "` ADD `ssl` tinyint(4) NOT NULL default '0'");
		$db->query("ALTER TABLE `" . TABLE_PANEL_DOMAINS . "` ADD `ssl_redirect` tinyint(4) NOT NULL default '0'");
		$db->query("ALTER TABLE `" . TABLE_PANEL_DOMAINS . "` ADD `ssl_ipandport` tinyint(4) NOT NULL default '0'");
		$db->query("ALTER TABLE `" . TABLE_PANEL_IPSANDPORTS . "` ADD `ssl` tinyint(4) NOT NULL default '0'");
		$db->query("ALTER TABLE `" . TABLE_PANEL_IPSANDPORTS . "` ADD `ssl_cert` tinytext AFTER `ssl`");
		$db->query("INSERT INTO `" . TABLE_PANEL_SETTINGS . "` (`settinggroup`, `varname`, `value`) VALUES ('system','ssl_cert_file','/etc/apache2/apache2.pem')");
		$db->query("INSERT INTO `" . TABLE_PANEL_SETTINGS . "` (`settinggroup`, `varname`, `value`) VALUES ('system','use_ssl','1')");
		$db->query("INSERT INTO `" . TABLE_PANEL_SETTINGS . "` (`settinggroup`, `varname`, `value`) VALUES ('system','openssl_cnf','[ req ]\r\ndefault_bits = 1024\r\ndistinguished_name = req_distinguished_name\r\nattributes = req_attributes\r\nprompt = no\r\noutput_password =\r\ninput_password =\r\n[ req_distinguished_name ]\r\nC = DE\r\nST = syscp\r\nL = syscp    \r\nO = Testcertificate\r\nOU = syscp        \r\nCN = @@domain_name@@\r\nemailAddress = @@email@@    \r\n[ req_attributes ]\r\nchallengePassword =\r\n')");
		$db->query("INSERT INTO `" . TABLE_PANEL_SETTINGS . "` (`settinggroup`, `varname`, `value`) VALUES ('system','default_vhostconf', '')");
		$db->query("ALTER TABLE `" . TABLE_MAIL_USERS . "` ADD `quota` BIGINT( 13 ) NOT NULL DEFAULT '0' AFTER `customerid`, ADD `pop3` TINYINT( 1 ) NOT NULL DEFAULT '1' AFTER `quota` , ADD `imap` TINYINT( 1 ) NOT NULL DEFAULT '1' AFTER `pop3`");
		$db->query("INSERT INTO `" . TABLE_PANEL_SETTINGS . "` (`settinggroup`, `varname`, `value`) VALUES ('system', 'mail_quota_enabled', '0')");
		$db->query("INSERT INTO `" . TABLE_PANEL_SETTINGS . "` (`settinggroup`, `varname`, `value`) VALUES ('system', 'mail_quota', '104857600')");
		$db->query("ALTER TABLE `" . TABLE_PANEL_CUSTOMERS . "` ADD `email_quota` BIGINT( 13 ) NOT NULL DEFAULT '0' AFTER `email_forwarders_used` , ADD `email_quota_used` BIGINT( 13 ) NOT NULL DEFAULT '0' AFTER `email_quota`, ADD `imap` TINYINT( 1 ) NOT NULL DEFAULT '0', ADD `pop3` TINYINT( 1 ) NOT NULL DEFAULT '0'");
		$db->query("ALTER TABLE `" . TABLE_PANEL_ADMINS . "` ADD `email_quota` BIGINT( 13 ) NOT NULL DEFAULT '0' AFTER `email_forwarders_used` , ADD `email_quota_used` BIGINT( 13 ) NOT NULL DEFAULT '0' AFTER `email_quota`");
		$db->query("INSERT INTO `" . TABLE_PANEL_SETTINGS . "` (`settinggroup`, `varname`, `value`) VALUES ('panel', 'decimal_places', '4')");

		// set new version

		$query = 'UPDATE `%s` SET `value` = \'1.2.19-svn7\' WHERE `settinggroup` = \'panel\' AND `varname` = \'version\'';
		$query = sprintf($query, TABLE_PANEL_SETTINGS);
		$db->query($query);
		$settings['panel']['version'] = '1.2.19-svn7';
	}

	if($settings['panel']['version'] == '1.2.19-svn7')
	{
		$updatelog->logAction(ADM_ACTION, LOG_WARNING, "Updating from 1.2.19-svn7 to 1.2.19-svn8");
		$db->query("
				CREATE TABLE `mail_dkim` (
					`id` int(11) NOT NULL auto_increment,
					`domain_id` int(11) NOT NULL default '0',
					`publickey` text NOT NULL,
					PRIMARY KEY  (`id`)
				) ENGINE=MyISAM
			");
		$db->query("ALTER TABLE `" . TABLE_PANEL_DOMAINS . "` ADD `dkim` tinyint(1) NOT NULL default '0' AFTER `zonefile`");
		$db->query("INSERT INTO `" . TABLE_PANEL_SETTINGS . "` (`settinggroup`, `varname`, `value`) VALUES ('dkim', 'dkim_prefix', '/etc/postfix/dkim/')");
		$db->query("INSERT INTO `" . TABLE_PANEL_SETTINGS . "` (`settinggroup`, `varname`, `value`) VALUES ('dkim', 'dkim_domains', 'domains')");
		$db->query("INSERT INTO `" . TABLE_PANEL_SETTINGS . "` (`settinggroup`, `varname`, `value`) VALUES ('dkim', 'dkim_dkimkeys', 'dkim-keys.conf')");
		$db->query("INSERT INTO `" . TABLE_PANEL_SETTINGS . "` (`settinggroup`, `varname`, `value`) VALUES ('dkim', 'dkimrestart_command', '/etc/init.d/dkim-filter restart')");

		// set new version

		$query = 'UPDATE `%s` SET `value` = \'1.2.19-svn8\' WHERE `settinggroup` = \'panel\' AND `varname` = \'version\'';
		$query = sprintf($query, TABLE_PANEL_SETTINGS);
		$db->query($query);
		$settings['panel']['version'] = '1.2.19-svn8';
	}

	if($settings['panel']['version'] == '1.2.19-svn8')
	{
		$updatelog->logAction(ADM_ACTION, LOG_WARNING, "Updating from 1.2.19-svn8 to 1.2.19-svn9");
		$db->query("ALTER TABLE `" . TABLE_PANEL_ADMINS . "` ADD `caneditphpsettings` tinyint(1) NOT NULL default '0' AFTER `domains_see_all`");

		// set new version

		$query = 'UPDATE `%s` SET `value` = \'1.2.19-svn9\' WHERE `settinggroup` = \'panel\' AND `varname` = \'version\'';
		$query = sprintf($query, TABLE_PANEL_SETTINGS);
		$db->query($query);
		$settings['panel']['version'] = '1.2.19-svn9';
	}

	if($settings['panel']['version'] == '1.2.19-svn9')
	{
		$updatelog->logAction(ADM_ACTION, LOG_WARNING, "Updating from 1.2.19-svn9 to 1.2.19-svn10");

		// set new version

		$query = 'UPDATE `%s` SET `value` = \'1.2.19-svn10\' WHERE `settinggroup` = \'panel\' AND `varname` = \'version\'';
		$query = sprintf($query, TABLE_PANEL_SETTINGS);
		$db->query($query);
		$settings['panel']['version'] = '1.2.19-svn10';
	}

	if($settings['panel']['version'] == '1.2.19-svn10')
	{
		$updatelog->logAction(ADM_ACTION, LOG_WARNING, "Updating from 1.2.19-svn10 to 1.2.19-svn11");
		$db->query("ALTER TABLE `" . TABLE_PANEL_IPSANDPORTS . "` CHANGE `ip` `ip` VARCHAR(39) NOT NULL default ''");

		// set new version

		$query = 'UPDATE `%s` SET `value` = \'1.2.19-svn11\' WHERE `settinggroup` = \'panel\' AND `varname` = \'version\'';
		$query = sprintf($query, TABLE_PANEL_SETTINGS);
		$db->query($query);
		$settings['panel']['version'] = '1.2.19-svn11';
	}

	if($settings['panel']['version'] == '1.2.19-svn11')
	{
		$updatelog->logAction(ADM_ACTION, LOG_WARNING, "Updating from 1.2.19-svn11 to 1.2.19-svn12");
		$db->query("ALTER TABLE `" . TABLE_PANEL_ADMINS . "` ADD `ip` tinyint(4) NOT NULL default '-1' AFTER `def_language`");

		// set new version

		$query = 'UPDATE `%s` SET `value` = \'1.2.19-svn12\' WHERE `settinggroup` = \'panel\' AND `varname` = \'version\'';
		$query = sprintf($query, TABLE_PANEL_SETTINGS);
		$db->query($query);
		$settings['panel']['version'] = '1.2.19-svn12';
	}

	if($settings['panel']['version'] == '1.2.19-svn12')
	{
		$updatelog->logAction(ADM_ACTION, LOG_WARNING, "Updating from 1.2.19-svn12 to 1.2.19-svn13");
		$db->query("INSERT INTO `" . TABLE_PANEL_SETTINGS . "` (`settinggroup`, `varname`, `value`) VALUES ('dkim', 'use_dkim', '0')");

		// set new version

		$query = 'UPDATE `%s` SET `value` = \'1.2.19-svn13\' WHERE `settinggroup` = \'panel\' AND `varname` = \'version\'';
		$query = sprintf($query, TABLE_PANEL_SETTINGS);
		$db->query($query);
		$settings['panel']['version'] = '1.2.19-svn13';
	}

	if($settings['panel']['version'] == '1.2.19-svn13')
	{
		$updatelog->logAction(ADM_ACTION, LOG_WARNING, "Updating from 1.2.19-svn13 to 1.2.19-svn14");
		$db->query("ALTER TABLE `" . TABLE_PANEL_DOMAINS . "` ADD `email_only` tinyint(1) NOT NULL default '0' AFTER `isemaildomain`");
		$db->query("ALTER TABLE `" . TABLE_PANEL_DOMAINS . "` ADD `wwwserveralias` tinyint(1) NOT NULL default '1' AFTER `dkim`");

		// set new version

		$query = 'UPDATE `%s` SET `value` = \'1.2.19-svn14\' WHERE `settinggroup` = \'panel\' AND `varname` = \'version\'';
		$query = sprintf($query, TABLE_PANEL_SETTINGS);
		$db->query($query);
		$settings['panel']['version'] = '1.2.19-svn14';
	}

	if($settings['panel']['version'] == '1.2.19-svn14')
	{
		$updatelog->logAction(ADM_ACTION, LOG_WARNING, "Updating from 1.2.19-svn14 to 1.2.19-svn15");
		$db->query("INSERT INTO `" . TABLE_PANEL_SETTINGS . "` (`settinggroup`, `varname`, `value`) VALUES ('system', 'webalizer_enabled', '1')");
		$db->query("INSERT INTO `" . TABLE_PANEL_SETTINGS . "` (`settinggroup`, `varname`, `value`) VALUES ('system', 'awstats_enabled', '0')");
		$db->query("INSERT INTO `" . TABLE_PANEL_SETTINGS . "` (`settinggroup`, `varname`, `value`) VALUES ('system', 'awstats_domain_file', '/etc/awstats/')");
		$db->query("INSERT INTO `" . TABLE_PANEL_SETTINGS . "` (`settinggroup`, `varname`, `value`) VALUES ('system', 'awstats_model_file', '/etc/awstats/awstats.model.conf.syscp')");

		// set new version

		$query = 'UPDATE `%s` SET `value` = \'1.2.19-svn15\' WHERE `settinggroup` = \'panel\' AND `varname` = \'version\'';
		$query = sprintf($query, TABLE_PANEL_SETTINGS);
		$db->query($query);
		$settings['panel']['version'] = '1.2.19-svn15';
	}

	if($settings['panel']['version'] == '1.2.19-svn15')
	{
		$updatelog->logAction(ADM_ACTION, LOG_WARNING, "Updating from 1.2.19-svn15 to 1.2.19-svn16");
		$db->query("
			CREATE TABLE `panel_dns` (
			  `dnsid` bigint(15) NOT NULL auto_increment,
			  `domainid` int(11) NOT NULL,
			  `customerid` int(11) NOT NULL,
			  `adminid` int(11) NOT NULL,
			  `ipv4` varchar(15) NOT NULL,
			  `ipv6` varchar(39) NOT NULL,
			  `cname` varchar(255) NOT NULL,
			  `mx10` varchar(255) NOT NULL,
			  `mx20` varchar(255) NOT NULL,
			  `txt` text NOT NULL,
			  PRIMARY KEY  (`dnsid`),
			  UNIQUE KEY `domainid` (`domainid`)
			) ENGINE=MyISAM;
			");
		$db->query("INSERT INTO `" . TABLE_PANEL_SETTINGS . "` (`settinggroup`, `varname`, `value`) VALUES ('system', 'userdns', '0')");
		$db->query("INSERT INTO `" . TABLE_PANEL_SETTINGS . "` (`settinggroup`, `varname`, `value`) VALUES ('system', 'customerdns', '0')");

		// set new version

		$query = 'UPDATE `%s` SET `value` = \'1.2.19-svn16\' WHERE `settinggroup` = \'panel\' AND `varname` = \'version\'';
		$query = sprintf($query, TABLE_PANEL_SETTINGS);
		$db->query($query);
		$settings['panel']['version'] = '1.2.19-svn16';
	}

	if($settings['panel']['version'] == '1.2.19-svn16')
	{
		$updatelog->logAction(ADM_ACTION, LOG_WARNING, "Updating from 1.2.19-svn16 to 1.2.19-svn17");
		$db->query("INSERT INTO `" . TABLE_PANEL_SETTINGS . "` (`settinggroup`, `varname`, `value`) VALUES ('panel', 'unix_names', '1')");

		// set new version

		$query = 'UPDATE `%s` SET `value` = \'1.2.19-svn17\' WHERE `settinggroup` = \'panel\' AND `varname` = \'version\'';
		$query = sprintf($query, TABLE_PANEL_SETTINGS);
		$db->query($query);
		$settings['panel']['version'] = '1.2.19-svn17';
	}

	if($settings['panel']['version'] == '1.2.19-svn17')
	{
		$updatelog->logAction(ADM_ACTION, LOG_WARNING, "Updating from 1.2.19-svn17 to 1.2.19-svn18");
		$db->query("INSERT INTO `" . TABLE_PANEL_SETTINGS . "` (`settinggroup`, `varname`, `value`) VALUES ('panel', 'allow_preset', '1')");

		// set new version

		$query = 'UPDATE `%s` SET `value` = \'1.2.19-svn18\' WHERE `settinggroup` = \'panel\' AND `varname` = \'version\'';
		$query = sprintf($query, TABLE_PANEL_SETTINGS);
		$db->query($query);
		$settings['panel']['version'] = '1.2.19-svn18';
	}

	if($settings['panel']['version'] == '1.2.19-svn18')
	{
		$updatelog->logAction(ADM_ACTION, LOG_WARNING, "Updating from 1.2.19-svn18 to 1.2.19-svn19");

		// Update all email-admins and give'em unlimited email_quota resources

		$sql = "SELECT `adminid` FROM `" . TABLE_PANEL_ADMINS . "` 
			WHERE `emails` = '-1' 
			AND `email_accounts` = '-1' 
			AND `email_forwarders` = '-1'";
		$admins = $db->query($sql);

		while($admin = $db->fetch_array($admins))
		{
			$db->query("UPDATE `" . TABLE_PANEL_ADMINS . "` SET `email_quota` = '-1' WHERE `adminid` = '" . $admin['adminid'] . "'");
		}

		if($settings['system']['apacheversion'] == 'lighttpd'
		   && $settings['system']['apachereload_command'] == '/etc/init.d/lighttpd force-reload')
		{
			$db->query("UPDATE `" . TABLE_PANEL_SETTINGS . "` SET `value` = '/etc/init.d/lighttpd reload' WHERE `settinggroup` = 'system' AND `varname` = 'apachereload_command'");
		}

		// set new version

		$query = 'UPDATE `%s` SET `value` = \'1.2.19-svn19\' WHERE `settinggroup` = \'panel\' AND `varname` = \'version\'';
		$query = sprintf($query, TABLE_PANEL_SETTINGS);
		$db->query($query);
		$settings['panel']['version'] = '1.2.19-svn19';
	}

	if($settings['panel']['version'] == '1.2.19-svn19')
	{
		$updatelog->logAction(ADM_ACTION, LOG_WARNING, "Updating from 1.2.19-svn19 to 1.2.19-svn20");
		$db->query("INSERT INTO `" . TABLE_PANEL_SETTINGS . "` (`settinggroup`, `varname`, `value`) VALUES ('system', 'awstats_path', '/usr/share/awstats/VERSION/webroot/cgi-bin/')");
		$db->query("INSERT INTO `" . TABLE_PANEL_SETTINGS . "` (`settinggroup`, `varname`, `value`) VALUES ('system', 'awstats_updateall_command', '/usr/bin/awstats_updateall.pl')");

		// set new version

		$query = 'UPDATE `%s` SET `value` = \'1.2.19-svn20\' WHERE `settinggroup` = \'panel\' AND `varname` = \'version\'';
		$query = sprintf($query, TABLE_PANEL_SETTINGS);
		$db->query($query);
		$settings['panel']['version'] = '1.2.19-svn20';
	}

	if($settings['panel']['version'] == '1.2.19-svn20')
	{
		$updatelog->logAction(ADM_ACTION, LOG_WARNING, "Updating from 1.2.19-svn20 to 1.2.19-svn21");

		// ADDING BILLING

		$db->query("ALTER TABLE `" . TABLE_PANEL_ADMINS . "` 
		  ADD `firstname` VARCHAR( 255 ) NOT NULL DEFAULT '', 
		  ADD `title` VARCHAR( 255 ) NOT NULL DEFAULT '', 
		  ADD `company` VARCHAR( 255 ) NOT NULL DEFAULT '', 
		  ADD `street` VARCHAR( 255 ) NOT NULL DEFAULT '', 
		  ADD `zipcode` VARCHAR( 255 ) NOT NULL DEFAULT '', 
		  ADD `city` VARCHAR( 255 ) NOT NULL DEFAULT '', 
		  ADD `country` VARCHAR( 255 ) NOT NULL DEFAULT '', 
		  ADD `phone` VARCHAR( 255 ) NOT NULL DEFAULT '', 
		  ADD `fax` VARCHAR( 255 ) NOT NULL DEFAULT '', 
		  ADD `taxid` VARCHAR( 255 ) NOT NULL DEFAULT '', 
		  ADD `contract_date` DATE NOT NULL, 
		  ADD `contract_number` VARCHAR( 255 ) NOT NULL DEFAULT '', 
		  ADD `contract_details` TEXT NOT NULL DEFAULT '', 
		  ADD `included_domains_qty` INT( 11 ) NOT NULL DEFAULT '0', 
		  ADD `included_domains_tld` VARCHAR( 255 ) NOT NULL DEFAULT '', 
		  ADD `additional_traffic_fee` DECIMAL( 10,2 ) NOT NULL DEFAULT '0', 
		  ADD `additional_traffic_unit` BIGINT( 30 ) NOT NULL DEFAULT '0', 
		  ADD `additional_diskspace_fee` DECIMAL( 10,2 ) NOT NULL DEFAULT '0', 
		  ADD `additional_diskspace_unit` BIGINT( 30 ) NOT NULL DEFAULT '0', 
		  ADD `taxclass` INT( 11 ) NOT NULL DEFAULT '0', 
		  ADD `setup_fee` DECIMAL( 10,2 ) NOT NULL DEFAULT '0', 
		  ADD `interval_fee` DECIMAL( 10,2 ) NOT NULL DEFAULT '0', 
		  ADD `interval_length` INT( 11 ) NOT NULL DEFAULT '0', 
		  ADD `interval_type` VARCHAR( 1 ) NOT NULL DEFAULT 'm', 
		  ADD `interval_payment` TINYINT( 1 ) NOT NULL DEFAULT '0', 
		  ADD `calc_tax` TINYINT( 1 ) NOT NULL DEFAULT '1', 
		  ADD `term_of_payment` INT( 11 ) NOT NULL DEFAULT '0', 
		  ADD `payment_every` INT( 11 ) NOT NULL DEFAULT '0', 
		  ADD `payment_method` INT( 11 ) NOT NULL DEFAULT '0', 
		  ADD `bankaccount_holder` TEXT NOT NULL DEFAULT '', 
		  ADD `bankaccount_number` VARCHAR( 255 ) NOT NULL DEFAULT '', 
		  ADD `bankaccount_blz` VARCHAR( 255 ) NOT NULL DEFAULT '', 
		  ADD `bankaccount_bank` VARCHAR( 255 ) NOT NULL DEFAULT '', 
		  ADD `service_active` TINYINT( 1 ) NOT NULL DEFAULT '0', 
		  ADD `servicestart_date` DATE NOT NULL,
		  ADD `serviceend_date` DATE NOT NULL,
		  ADD `lastinvoiced_date` DATE NOT NULL,
		  ADD `lastinvoiced_date_traffic` DATE NOT NULL,
		  ADD `lastinvoiced_date_diskspace` DATE NOT NULL,
		  ADD `customer_categories_once` TEXT NOT NULL DEFAULT '', 
		  ADD `customer_categories_period` TEXT NOT NULL DEFAULT '', 
		  ADD `invoice_fee` DECIMAL( 10,2 ) NOT NULL DEFAULT '0', 
		  ADD `invoice_fee_hosting` DECIMAL( 10,2 ) NOT NULL DEFAULT '0', 
		  ADD `invoice_fee_hosting_customers` DECIMAL( 10,2 ) NOT NULL DEFAULT '0', 
		  ADD `invoice_fee_domains` DECIMAL( 10,2 ) NOT NULL DEFAULT '0', 
		  ADD `invoice_fee_traffic` DECIMAL( 10,2 ) NOT NULL DEFAULT '0', 
		  ADD `invoice_fee_diskspace` DECIMAL( 10,2 ) NOT NULL DEFAULT '0', 
		  ADD `invoice_fee_other` DECIMAL( 10,2 ) NOT NULL DEFAULT '0';");
		$db->query("ALTER TABLE `" . TABLE_PANEL_CUSTOMERS . "` 
		  ADD `taxid` VARCHAR( 255 ) NOT NULL DEFAULT '', 
		  ADD `title` VARCHAR( 255 ) NOT NULL DEFAULT '', 
		  ADD `country` VARCHAR( 255 ) NOT NULL DEFAULT '', 
		  ADD `additional_service_description` TEXT NOT NULL DEFAULT '', 
		  ADD `contract_date` DATE NOT NULL, 
		  ADD `contract_number` VARCHAR( 255 ) NOT NULL DEFAULT '', 
		  ADD `contract_details` TEXT NOT NULL DEFAULT '', 
		  ADD `included_domains_qty` INT( 11 ) NOT NULL DEFAULT '0', 
		  ADD `included_domains_tld` VARCHAR( 255 ) NOT NULL DEFAULT '', 
		  ADD `additional_traffic_fee` DECIMAL( 10,2 ) NOT NULL DEFAULT '0', 
		  ADD `additional_traffic_unit` BIGINT( 30 ) NOT NULL DEFAULT '0', 
		  ADD `additional_diskspace_fee` DECIMAL( 10,2 ) NOT NULL DEFAULT '0', 
		  ADD `additional_diskspace_unit` BIGINT( 30 ) NOT NULL DEFAULT '0', 
		  ADD `taxclass` INT( 11 ) NOT NULL DEFAULT '0', 
		  ADD `setup_fee` DECIMAL( 10,2 ) NOT NULL DEFAULT '0', 
		  ADD `interval_fee` DECIMAL( 10,2 ) NOT NULL DEFAULT '0', 
		  ADD `interval_length` INT( 11 ) NOT NULL DEFAULT '0', 
		  ADD `interval_type` VARCHAR( 1 ) NOT NULL DEFAULT 'm', 
		  ADD `interval_payment` TINYINT( 1 ) NOT NULL DEFAULT '0', 
		  ADD `calc_tax` TINYINT( 1 ) NOT NULL DEFAULT '1', 
		  ADD `term_of_payment` INT( 11 ) NOT NULL DEFAULT '0', 
		  ADD `payment_every` INT( 11 ) NOT NULL DEFAULT '0', 
		  ADD `payment_method` INT( 11 ) NOT NULL DEFAULT '0', 
		  ADD `bankaccount_holder` TEXT NOT NULL DEFAULT '', 
		  ADD `bankaccount_number` VARCHAR( 255 ) NOT NULL DEFAULT '', 
		  ADD `bankaccount_blz` VARCHAR( 255 ) NOT NULL DEFAULT '', 
		  ADD `bankaccount_bank` VARCHAR( 255 ) NOT NULL DEFAULT '', 
		  ADD `service_active` TINYINT( 1 ) NOT NULL DEFAULT '0', 
		  ADD `servicestart_date` DATE NOT NULL,
		  ADD `serviceend_date` DATE NOT NULL,
		  ADD `lastinvoiced_date` DATE NOT NULL,
		  ADD `lastinvoiced_date_traffic` DATE NOT NULL,
		  ADD `lastinvoiced_date_diskspace` DATE NOT NULL,
		  ADD `invoice_fee` DECIMAL( 10,2 ) NOT NULL DEFAULT '0', 
		  ADD `invoice_fee_hosting` DECIMAL( 10,2 ) NOT NULL DEFAULT '0', 
		  ADD `invoice_fee_domains` DECIMAL( 10,2 ) NOT NULL DEFAULT '0', 
		  ADD `invoice_fee_traffic` DECIMAL( 10,2 ) NOT NULL DEFAULT '0', 
		  ADD `invoice_fee_diskspace` DECIMAL( 10,2 ) NOT NULL DEFAULT '0', 
		  ADD `invoice_fee_other` DECIMAL( 10,2 ) NOT NULL DEFAULT '0';");
		$db->query("ALTER TABLE `panel_domains` 
		  ADD `add_date` INT( 11 ) NOT NULL DEFAULT '0', 
		  ADD `registration_date` DATE NOT NULL, 
		  ADD `taxclass` INT( 11 ) NOT NULL DEFAULT '0', 
		  ADD `setup_fee` DECIMAL( 10,2 ) NOT NULL DEFAULT '0', 
		  ADD `interval_fee` DECIMAL( 10,2 ) NOT NULL DEFAULT '0', 
		  ADD `interval_length` INT( 11 ) NOT NULL DEFAULT '0', 
		  ADD `interval_type` VARCHAR( 1 ) NOT NULL DEFAULT 'y', 
		  ADD `interval_payment` TINYINT( 1 ) NOT NULL DEFAULT '0', 
		  ADD `service_active` TINYINT( 1 ) NOT NULL DEFAULT '0', 
		  ADD `servicestart_date` DATE NOT NULL, 
		  ADD `serviceend_date` DATE NOT NULL, 
		  ADD `lastinvoiced_date` DATE NOT NULL;");
		$db->query("CREATE TABLE  `" . TABLE_BILLING_SERVICE_CATEGORIES . "` (
		 `id` INT( 11 ) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
		 `category_name` VARCHAR( 255 ) NOT NULL DEFAULT '',
		 `category_order` INT( 11 ) UNSIGNED NOT NULL DEFAULT '0',
		 `category_classname` VARCHAR( 255 ) NOT NULL DEFAULT '',
		 `category_classfile` VARCHAR( 255 ) NOT NULL DEFAULT '',
		 `category_cachefield` VARCHAR( 255 ) NOT NULL DEFAULT '',
		 `category_caption` VARCHAR( 255 ) NOT NULL DEFAULT '',
		 `category_rowcaption_setup` VARCHAR( 255 ) NOT NULL DEFAULT '',
		 `category_rowcaption_interval` VARCHAR( 255 ) NOT NULL DEFAULT ''
		) TYPE = MYISAM ;");
		$db->query("CREATE TABLE  `" . TABLE_BILLING_SERVICE_CATEGORIES_ADMINS . "` (
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
		) TYPE = MYISAM ;");
		$db->query("CREATE TABLE  `" . TABLE_BILLING_SERVICE_DOMAINS_TEMPLATES . "` (
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
		) TYPE = MYISAM ;");
		$db->query("CREATE TABLE  `" . TABLE_BILLING_SERVICE_OTHER . "` (
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
		) TYPE = MYISAM ;");
		$db->query("CREATE TABLE  `" . TABLE_BILLING_SERVICE_OTHER_TEMPLATES . "` (
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
		) TYPE = MYISAM ;");
		$db->query("CREATE TABLE  `" . TABLE_BILLING_TAXCLASSES . "` (
		 `classid` INT( 11 ) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
		 `classname` VARCHAR( 255 ) NOT NULL DEFAULT '',
		 `default` TINYINT( 1 ) NOT NULL DEFAULT '0'
		) TYPE = MYISAM ;");
		$db->query("CREATE TABLE  `" . TABLE_BILLING_TAXRATES . "` (
		 `taxid` INT( 11 ) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
		 `taxclass` INT( 11 ) UNSIGNED NOT NULL DEFAULT '0',
		 `taxrate` DECIMAL( 4, 4 ) NOT NULL ,
		 `valid_from` DATE NOT NULL
		) TYPE = MYISAM ;");
		$db->query("CREATE TABLE  `" . TABLE_BILLING_INVOICES . "` (
		 `id` INT( 11 ) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
		 `customerid` INT( 11 ) NOT NULL DEFAULT '0',
		 `xml` LONGTEXT NOT NULL DEFAULT '',
		 `invoice_date` DATE NOT NULL,
		 `state` TINYINT( 1 ) NOT NULL DEFAULT '0',
		 `state_change` INT( 11 ) NOT NULL DEFAULT '0',
		 `invoice_number` VARCHAR( 255 ) NOT NULL DEFAULT '',
		 `total_fee` DECIMAL( 10, 2 ) NOT NULL DEFAULT '0.00',
		 `total_fee_taxed` DECIMAL( 10, 2 ) NOT NULL DEFAULT '0.00'
		) TYPE = MYISAM ;");
		$db->query("CREATE TABLE  `" . TABLE_BILLING_INVOICES_ADMINS . "` (
		 `id` INT( 11 ) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
		 `adminid` INT( 11 ) NOT NULL DEFAULT '0',
		 `xml` LONGTEXT NOT NULL DEFAULT '',
		 `invoice_date` DATE NOT NULL,
		 `state` TINYINT( 1 ) NOT NULL DEFAULT '0',
		 `state_change` INT( 11 ) NOT NULL DEFAULT '0',
		 `invoice_number` VARCHAR( 255 ) NOT NULL DEFAULT '',
		 `total_fee` DECIMAL( 10, 2 ) NOT NULL DEFAULT '0.00',
		 `total_fee_taxed` DECIMAL( 10, 2 ) NOT NULL DEFAULT '0.00'
		) TYPE = MYISAM ;");
		$db->query("CREATE TABLE  `" . TABLE_BILLING_INVOICE_CHANGES . "` (
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
		) TYPE = MYISAM ;");
		$db->query("CREATE TABLE  `" . TABLE_BILLING_INVOICE_CHANGES_ADMINS . "` (
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
		) TYPE = MYISAM ;");
		$db->query("CREATE TABLE `" . TABLE_PANEL_DISKSPACE . "` (
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
		) TYPE=MyISAM ;");
		$db->query("CREATE TABLE `" . TABLE_PANEL_DISKSPACE_ADMINS . "` (
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
		) TYPE=MyISAM ;");
		$db->query("INSERT INTO `" . TABLE_BILLING_SERVICE_CATEGORIES . "` (`id`, `category_name`, `category_order`, `category_classname`, `category_classfile`, `category_cachefield`, `category_caption`, `category_rowcaption_setup`, `category_rowcaption_interval`) VALUES (1, 'hosting', 10, 'hosting', 'lib/billing_class_hosting.php', 'invoice_fee_hosting', 'hosting_caption', 'hosting_rowcaption_setup', 'hosting_rowcaption_interval');");
		$db->query("INSERT INTO `" . TABLE_BILLING_SERVICE_CATEGORIES . "` (`id`, `category_name`, `category_order`, `category_classname`, `category_classfile`, `category_cachefield`, `category_caption`, `category_rowcaption_setup`, `category_rowcaption_interval`) VALUES (2, 'domains', 20, 'domains', 'lib/billing_class_domains.php', 'invoice_fee_domains', 'domains_caption', 'domains_rowcaption_setup', 'domains_rowcaption_interval');");
		$db->query("INSERT INTO `" . TABLE_BILLING_SERVICE_CATEGORIES . "` (`id`, `category_name`, `category_order`, `category_classname`, `category_classfile`, `category_cachefield`, `category_caption`, `category_rowcaption_setup`, `category_rowcaption_interval`) VALUES (3, 'traffic', 30, 'traffic', 'lib/billing_class_traffic.php', 'invoice_fee_traffic', 'traffic_caption', 'traffic_rowcaption_setup', 'traffic_rowcaption_interval');");
		$db->query("INSERT INTO `" . TABLE_BILLING_SERVICE_CATEGORIES . "` (`id`, `category_name`, `category_order`, `category_classname`, `category_classfile`, `category_cachefield`, `category_caption`, `category_rowcaption_setup`, `category_rowcaption_interval`) VALUES (4, 'diskspace', 40, 'diskspace', 'lib/billing_class_diskspace.php', 'invoice_fee_diskspace', 'diskspace_caption', 'diskspace_rowcaption_setup', 'diskspace_rowcaption_interval');");
		$db->query("INSERT INTO `" . TABLE_BILLING_SERVICE_CATEGORIES . "` (`id`, `category_name`, `category_order`, `category_classname`, `category_classfile`, `category_cachefield`, `category_caption`, `category_rowcaption_setup`, `category_rowcaption_interval`) VALUES (5, 'other', 50, 'other', 'lib/billing_class_other.php', 'invoice_fee_other', 'other_caption', 'other_rowcaption_setup', 'other_rowcaption_interval');");
		$db->query("INSERT INTO `" . TABLE_BILLING_SERVICE_CATEGORIES_ADMINS . "` (`id`, `category_name`, `category_order`, `category_mode`, `category_classname`, `category_classfile`, `category_cachefield`, `category_caption`, `category_rowcaption_setup`, `category_rowcaption_interval`) VALUES (1, 'hosting', 10, 0, 'hosting', 'lib/billing_class_hosting.php', 'invoice_fee_hosting', 'hosting_caption', 'hosting_rowcaption_setup', 'hosting_rowcaption_interval');");
		$db->query("INSERT INTO `" . TABLE_BILLING_SERVICE_CATEGORIES_ADMINS . "` (`id`, `category_name`, `category_order`, `category_mode`, `category_classname`, `category_classfile`, `category_cachefield`, `category_caption`, `category_rowcaption_setup`, `category_rowcaption_interval`) VALUES (2, 'hosting_customers', 20, 1, 'hosting', 'lib/billing_class_hosting.php', 'invoice_fee_hosting_customers', 'hosting_caption', 'hosting_rowcaption_setup_withloginname', 'hosting_rowcaption_interval_withloginname');");
		$db->query("INSERT INTO `" . TABLE_BILLING_SERVICE_CATEGORIES_ADMINS . "` (`id`, `category_name`, `category_order`, `category_mode`, `category_classname`, `category_classfile`, `category_cachefield`, `category_caption`, `category_rowcaption_setup`, `category_rowcaption_interval`) VALUES (3, 'domains', 30, 1, 'domains', 'lib/billing_class_domains.php', 'invoice_fee_domains', 'domains_caption', 'domains_rowcaption_setup', 'domains_rowcaption_interval');");
		$db->query("INSERT INTO `" . TABLE_BILLING_SERVICE_CATEGORIES_ADMINS . "` (`id`, `category_name`, `category_order`, `category_mode`, `category_classname`, `category_classfile`, `category_cachefield`, `category_caption`, `category_rowcaption_setup`, `category_rowcaption_interval`) VALUES (4, 'traffic', 40, 0, 'traffic', 'lib/billing_class_traffic.php', 'invoice_fee_traffic', 'traffic_caption', 'traffic_rowcaption_setup', 'traffic_rowcaption_interval');");
		$db->query("INSERT INTO `" . TABLE_BILLING_SERVICE_CATEGORIES_ADMINS . "` (`id`, `category_name`, `category_order`, `category_mode`, `category_classname`, `category_classfile`, `category_cachefield`, `category_caption`, `category_rowcaption_setup`, `category_rowcaption_interval`) VALUES (5, 'diskspace', 50, 0, 'diskspace', 'lib/billing_class_diskspace.php', 'invoice_fee_diskspace', 'diskspace_caption', 'diskspace_rowcaption_setup', 'diskspace_rowcaption_interval');");
		$db->query("INSERT INTO `" . TABLE_BILLING_SERVICE_CATEGORIES_ADMINS . "` (`id`, `category_name`, `category_order`, `category_mode`, `category_classname`, `category_classfile`, `category_cachefield`, `category_caption`, `category_rowcaption_setup`, `category_rowcaption_interval`) VALUES (6, 'other', 60, 1, 'other', 'lib/billing_class_other.php', 'invoice_fee_other', 'other_caption', 'other_rowcaption_setup', 'other_rowcaption_interval');");
		$db->query("INSERT INTO `" . TABLE_BILLING_TAXCLASSES . "` (`classid`, `classname`, `default`) VALUES ( NULL, 'MwSt Deutschland', '1' );");
		$db->query("INSERT INTO `" . TABLE_BILLING_TAXCLASSES . "` (`classid`, `classname`, `default`) VALUES ( NULL, 'MwSt Deutschland (reduziert)', '0' );");
		$db->query("INSERT INTO `" . TABLE_BILLING_TAXRATES . "` (`taxid`, `taxclass`, `taxrate`, `valid_from`) VALUES ( NULL, 1, 0.1600, '0' );");
		$db->query("INSERT INTO `" . TABLE_BILLING_TAXRATES . "` (`taxid`, `taxclass`, `taxrate`, `valid_from`) VALUES ( NULL, 1, 0.1900, '2007-01-01' );");
		$db->query("INSERT INTO `" . TABLE_BILLING_TAXRATES . "` (`taxid`, `taxclass`, `taxrate`, `valid_from`) VALUES ( NULL, 2, 0.0700, '0' );");
		$db->query("INSERT INTO `" . TABLE_PANEL_NAVIGATION . "` (`id`, `area` , `parent_url`, `lang`, `url`, `order`, `required_resources`, `new_window`) VALUES (NULL, 'admin', '', 'billing;billing', 'billing.nourl', '100', 'customers_see_all', '0');");
		$db->query("INSERT INTO `" . TABLE_PANEL_NAVIGATION . "` (`id`, `area` , `parent_url`, `lang`, `url`, `order`, `required_resources`, `new_window`) VALUES (NULL, 'admin', 'billing.nourl', 'billing;openinvoices', 'billing_openinvoices.php', '110', 'customers_see_all', '0');");
		$db->query("INSERT INTO `" . TABLE_PANEL_NAVIGATION . "` (`id`, `area` , `parent_url`, `lang`, `url`, `order`, `required_resources`, `new_window`) VALUES (NULL, 'admin', 'billing.nourl', 'billing;openinvoices_admin', 'billing_openinvoices.php?mode=1', '115', 'customers_see_all', '0');");
		$db->query("INSERT INTO `" . TABLE_PANEL_NAVIGATION . "` (`id`, `area` , `parent_url`, `lang`, `url`, `order`, `required_resources`, `new_window`) VALUES (NULL, 'admin', 'billing.nourl', 'billing;invoices', 'billing_invoices.php', '120', 'customers_see_all', '0');");
		$db->query("INSERT INTO `" . TABLE_PANEL_NAVIGATION . "` (`id`, `area` , `parent_url`, `lang`, `url`, `order`, `required_resources`, `new_window`) VALUES (NULL, 'admin', 'billing.nourl', 'billing;invoices_admin', 'billing_invoices.php?mode=1', '125', 'customers_see_all', '0');");
		$db->query("INSERT INTO `" . TABLE_PANEL_NAVIGATION . "` (`id`, `area` , `parent_url`, `lang`, `url`, `order`, `required_resources`, `new_window`) VALUES (NULL, 'admin', 'billing.nourl', 'billing;other', 'billing_other.php', '130', 'customers_see_all', '0');");
		$db->query("INSERT INTO `" . TABLE_PANEL_NAVIGATION . "` (`id`, `area` , `parent_url`, `lang`, `url`, `order`, `required_resources`, `new_window`) VALUES (NULL, 'admin', 'billing.nourl', 'billing;taxclassesnrates', 'billing_taxrates.php', '140', 'customers_see_all', '0');");
		$db->query("INSERT INTO `" . TABLE_PANEL_NAVIGATION . "` (`id`, `area` , `parent_url`, `lang`, `url`, `order`, `required_resources`, `new_window`) VALUES (NULL, 'admin', 'billing.nourl', 'billing;domains_templates', 'billing_domains_templates.php', '150', 'customers_see_all', '0');");
		$db->query("INSERT INTO `" . TABLE_PANEL_NAVIGATION . "` (`id`, `area` , `parent_url`, `lang`, `url`, `order`, `required_resources`, `new_window`) VALUES (NULL, 'admin', 'billing.nourl', 'billing;other_templates', 'billing_other_templates.php', '160', 'customers_see_all', '0');");
		$db->query("INSERT INTO `" . TABLE_PANEL_SETTINGS . "` (`settingid`, `settinggroup`, `varname`, `value`) VALUES (NULL, 'billing', 'invoicenumber_count', '0');");

		// set new version

		$query = 'UPDATE `%s` SET `value` = \'1.2.19-svn21\' WHERE `settinggroup` = \'panel\' AND `varname` = \'version\'';
		$query = sprintf($query, TABLE_PANEL_SETTINGS);
		$db->query($query);
		$settings['panel']['version'] = '1.2.19-svn21';
	}
}

// php filter-extension check



?>
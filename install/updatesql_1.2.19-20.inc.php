<?php

/**
 * This file is part of the SysCP project.
 * Copyright (c) 2003-2007 the SysCP Team (see authors).
 *
 * For the full copyright and license information, please view the COPYING
 * file that was distributed with this source code. You can also view the
 * COPYING file online at http://files.syscp.org/misc/COPYING.txt
 *
 * @copyright  (c) the authors
 * @author     Michael Kaufmann <mk@syscp-help.org>
 * @license    GPLv2 http://files.syscp.org/misc/COPYING.txt
 * @package    System
 * @version    $Id: $
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
}

// php filter-extension check



?>
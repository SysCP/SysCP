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
 * @author     Florian Lippert <flo@syscp.org>
 * @license    GPLv2 http://files.syscp.org/misc/COPYING.txt
 * @package    Panel
 * @version    $Id$
 */

define('AREA', 'admin');

/**
 * Include our init.php, which manages Sessions, Language etc.
 */

require ("./lib/init.php");

if(($page == 'settings' || $page == 'overview')
   && $userinfo['change_serversettings'] == '1')
{
	if(isset($_POST['send'])
	   && $_POST['send'] == 'send')
	{
		wasFormCompromised();

		if($_POST['session_sessiontimeout'] != $settings['session']['sessiontimeout'])
		{
			$value = validate($_POST['session_sessiontimeout'], 'session timeout', '/^[0-9]+$/', 'sessiontimeoutiswrong');
			$db->query("UPDATE `" . TABLE_PANEL_SETTINGS . "` SET `value`='" . $db->escape($value) . "' WHERE `settinggroup`='session' AND `varname`='sessiontimeout'");
		}

		if($_POST['login_maxloginattempts'] != $settings['login']['maxloginattempts'])
		{
			$value = validate($_POST['login_maxloginattempts'], 'max login attempts', '/^[0-9]+$/', 'maxloginattemptsiswrong');
			$db->query("UPDATE `" . TABLE_PANEL_SETTINGS . "` SET `value`='" . $db->escape($value) . "' WHERE `settinggroup`='login' AND `varname`='maxloginattempts'");
		}

		if($_POST['login_deactivatetime'] != $settings['login']['deactivatetime'])
		{
			$value = validate($_POST['login_deactivatetime'], 'deactivate time', '/^[0-9]+$/', 'deactivatetimiswrong');
			$db->query("UPDATE `" . TABLE_PANEL_SETTINGS . "` SET `value`='" . $db->escape($value) . "' WHERE `settinggroup`='login' AND `varname`='deactivatetime'");
		}

		if($_POST['customer_accountprefix'] != $settings['customer']['accountprefix'])
		{
			$value = $_POST['customer_accountprefix'];

			if(validateUsername($value))
			{
				$db->query("UPDATE `" . TABLE_PANEL_SETTINGS . "` SET `value`='" . $db->escape($value) . "' WHERE `settinggroup`='customer' AND `varname`='accountprefix'");
			}
			else
			{
				standard_error('accountprefixiswrong');
				exit;
			}
		}

		if($_POST['customer_mysqlprefix'] != $settings['customer']['mysqlprefix'])
		{
			$value = $_POST['customer_mysqlprefix'];

			if(validateUsername($value))
			{
				$db->query("UPDATE `" . TABLE_PANEL_SETTINGS . "` SET `value`='" . $db->escape($value) . "' WHERE `settinggroup`='customer' AND `varname`='mysqlprefix'");
			}
			else
			{
				standard_error('mysqlprefixiswrong');
				exit;
			}
		}

		if($_POST['customer_ftpprefix'] != $settings['customer']['ftpprefix'])
		{
			$value = $_POST['customer_ftpprefix'];

			if(validateUsername($value))
			{
				$db->query("UPDATE `" . TABLE_PANEL_SETTINGS . "` SET `value`='" . $db->escape($value) . "' WHERE `settinggroup`='customer' AND `varname`='ftpprefix'");
			}
			else
			{
				standard_error('ftpprefixiswrong');
				exit;
			}
		}

		if($_POST['customer_ftpatdomain'] != $settings['customer']['ftpatdomain'])
		{
			$value = ($_POST['customer_ftpatdomain'] == '1' ? '1' : '0');
			$db->query("UPDATE `" . TABLE_PANEL_SETTINGS . "` SET `value`='" . $db->escape($value) . "' WHERE `settinggroup`='customer' AND `varname`='ftpatdomain'");
		}

		if($_POST['system_documentroot_prefix'] != $settings['system']['documentroot_prefix'])
		{
			$value = validate($_POST['system_documentroot_prefix'], 'documentroot prefix');
			$value = makeCorrectDir($value);
			$db->query("UPDATE `" . TABLE_PANEL_SETTINGS . "` SET `value`='" . $db->escape($value) . "' WHERE `settinggroup`='system' AND `varname`='documentroot_prefix'");
		}

		if($_POST['system_logfiles_directory'] != $settings['system']['logfiles_directory'])
		{
			$value = validate($_POST['system_logfiles_directory'], 'logfiles directory');
			$value = makeCorrectDir($value);
			$db->query("UPDATE `" . TABLE_PANEL_SETTINGS . "` SET `value`='" . $db->escape($value) . "' WHERE `settinggroup`='system' AND `varname`='logfiles_directory'");
		}

		if($_POST['system_ipaddress'] != $settings['system']['ipaddress'])
		{
			$value = $_POST['system_ipaddress'];
			$result_ipandport = $db->query("SELECT `id`, `ip`, `port` FROM `" . TABLE_PANEL_IPSANDPORTS . "` WHERE `ip`='" . $db->escape($value) . "'");

			if($db->num_rows($result_ipandport) == 0)
			{
				standard_error('ipiswrong');
				exit;
			}

			$db->query("UPDATE `" . TABLE_PANEL_SETTINGS . "` SET `value`='" . $db->escape($value) . "' WHERE `settinggroup`='system' AND `varname`='ipaddress'");
			inserttask('1');
			$mysql_access_host_array = array_map('trim', explode(',', $settings['system']['mysql_access_host']));
			$mysql_access_host_array[] = $value;
			$mysql_access_host_array = array_unique($mysql_access_host_array);
			$mysql_access_host = implode(',', $mysql_access_host_array);
			$db->query("UPDATE `" . TABLE_PANEL_SETTINGS . "` SET `value`='" . $db->escape($mysql_access_host) . "' WHERE `settinggroup`='system' AND `varname`='mysql_access_host'");
			$db_root = new db($sql['host'], $sql['root_user'], $sql['root_password']);
			correctMysqlUsers($db, $db_root, $mysql_access_host_array);
			$db_root->close();
			unset($db_root);
		}

		if($_POST['system_defaultip'] != $settings['system']['defaultip'])
		{
			$value = $_POST['system_defaultip'];
			$result_ipandport = $db->query("SELECT `id`, `ip`, `port` FROM `" . TABLE_PANEL_IPSANDPORTS . "` WHERE `id`='" . (int)$value . "'");

			if($db->num_rows($result_ipandport) == 0)
			{
				standard_error('ipiswrong');
				exit;
			}

			$customerstddomains = $db->query('SELECT `d`.`id` FROM `' . TABLE_PANEL_CUSTOMERS . '` `c` LEFT JOIN `' . TABLE_PANEL_DOMAINS . '` `d` ON `d`.`id` = `c`.`standardsubdomain` WHERE `c`.`standardsubdomain` <> \'0\' && `d`.`ipandport` = \'' . $db->escape($settings['system']['defaultip']) . '\'');
			$ids = array();

			while($row = $db->fetch_array($customerstddomains))
			{
				$ids[] = (int)$row['id'];
			}

			if(count($ids) > 0)
			{
				$db->query('UPDATE `' . TABLE_PANEL_DOMAINS . '` SET `ipandport`=\'' . (int)$value . '\' WHERE `id` IN (\'' . join("','", $ids) . '\')');
			}

			$db->query("UPDATE `" . TABLE_PANEL_SETTINGS . "` SET `value`='" . (int)$value . "' WHERE `settinggroup`='system' AND `varname`='defaultip'");
		}

		if($_POST['system_hostname'] != $settings['system']['hostname'])
		{
			$value = $idna_convert->encode(validate($_POST['system_hostname'], 'hostname'));
			$db->query("UPDATE `" . TABLE_PANEL_SETTINGS . "` SET `value`='" . $db->escape($value) . "' WHERE `settinggroup`='system' AND `varname`='hostname'");
			$result = $db->query('SELECT `standardsubdomain` FROM `' . TABLE_PANEL_CUSTOMERS . '` WHERE `standardsubdomain`!=\'0\'');
			$domains = array();

			while(($row = $db->fetch_array($result)) !== false)
			{
				$domains[] = '\'' . $db->escape($row['standardsubdomain']) . '\'';
			}

			if(count($domains) > 0)
			{
				$domains = join($domains, ',');
				$db->query('UPDATE `' . TABLE_PANEL_DOMAINS . '` SET `domain`=REPLACE(`domain`,\'' . $db->escape($settings['system']['hostname']) . '\',\'' . $db->escape($value) . '\') WHERE `id` IN (' . $domains . ')');
				inserttask('1');
			}
		}

		if($_POST['system_mysql_access_host'] != $settings['system']['mysql_access_host'])
		{
			$value = validate($_POST['system_mysql_access_host'], 'MySQL Access Host', '/^([a-z0-9\-\._]+, ?)*[a-z0-9\-\._]+$/i');
			$mysql_access_host_array = array_map('trim', explode(',', $value));

			if(in_array('127.0.0.1', $mysql_access_host_array)
			   && !in_array('localhost', $mysql_access_host_array))
			{
				$value.= ',localhost';
				$mysql_access_host_array[] = 'localhost';
			}

			if(!in_array('127.0.0.1', $mysql_access_host_array)
			   && in_array('localhost', $mysql_access_host_array))
			{
				$value.= ',127.0.0.1';
				$mysql_access_host_array[] = '127.0.0.1';
			}

			$db->query("UPDATE `" . TABLE_PANEL_SETTINGS . "` SET `value`='" . $db->escape($value) . "' WHERE `settinggroup`='system' AND `varname`='mysql_access_host'");
			$db_root = new db($sql['host'], $sql['root_user'], $sql['root_password']);
			correctMysqlUsers($db, $db_root, $mysql_access_host_array);
			$db_root->close();
			unset($db_root);
		}

		if($_POST['system_apacheconf_vhost'] != $settings['system']['apacheconf_vhost'])
		{
			$value = validate($_POST['system_apacheconf_vhost'], 'apacheconf vhost');
			$value = makeSecurePath($value);
			$db->query("UPDATE `" . TABLE_PANEL_SETTINGS . "` SET `value`='" . $db->escape($value) . "' WHERE `settinggroup`='system' AND `varname`='apacheconf_vhost'");
			inserttask('1');
		}

		if($_POST['system_apacheconf_diroptions'] != $settings['system']['apacheconf_diroptions'])
		{
			$value = validate($_POST['system_apacheconf_diroptions'], 'apacheconf diroptions');
			$value = makeSecurePath($value);
			$db->query("UPDATE `" . TABLE_PANEL_SETTINGS . "` SET `value`='" . $db->escape($value) . "' WHERE `settinggroup`='system' AND `varname`='apacheconf_diroptions'");
			inserttask('3');
		}

		if($_POST['system_apacheconf_htpasswddir'] != $settings['system']['apacheconf_htpasswddir'])
		{
			$value = validate($_POST['system_apacheconf_htpasswddir'], 'apacheconf htpasswddir');
			$value = makeCorrectDir($value);
			$db->query("UPDATE `" . TABLE_PANEL_SETTINGS . "` SET `value`='" . $db->escape($value) . "' WHERE `settinggroup`='system' AND `varname`='apacheconf_htpasswddir'");
			inserttask('3');
		}

		if($_POST['system_apachereload_command'] != $settings['system']['apachereload_command'])
		{
			$value = validate($_POST['system_apachereload_command'], 'apache reload command');
			$db->query("UPDATE `" . TABLE_PANEL_SETTINGS . "` SET `value`='" . $db->escape($value) . "' WHERE `settinggroup`='system' AND `varname`='apachereload_command'");
			inserttask('1');
		}

		if($_POST['system_modlogsql'] != $settings['system']['mod_log_sql'])
		{
			$value = ($_POST['system_modlogsql'] == '1' ? '1' : '0');
			$db->query("UPDATE `" . TABLE_PANEL_SETTINGS . "` SET `value`='" . $db->escape($value) . "' WHERE `settinggroup`='system' AND `varname`='mod_log_sql'");
			inserttask('1');
		}

		if($_POST['system_modfcgid'] != $settings['system']['mod_fcgid'])
		{
			$value = ($_POST['system_modfcgid'] == '1' ? '1' : '0');
			$db->query("UPDATE `" . TABLE_PANEL_SETTINGS . "` SET `value`='" . $db->escape($value) . "' WHERE `settinggroup`='system' AND `varname`='mod_fcgid'");
			inserttask('1');
		}

		if($_POST['system_phpappendopenbasedir'] != $settings['system']['phpappendopenbasedir'])
		{
			$value = validate($_POST['system_phpappendopenbasedir'], 'phpappendopenbasedir');
			$value = explode(':', $value);
			foreach($value as $number => $path)
			{
				$value[$number] = makeCorrectDir($path);
			}

			$value = implode(':', $value);

			// If user doesn't want to append anything we should not include the root here...

			if($value == '/')
			{
				$value = '';
			}

			$db->query("UPDATE `" . TABLE_PANEL_SETTINGS . "` SET `value`='" . $db->escape($value) . "' WHERE `settinggroup`='system' AND `varname`='phpappendopenbasedir'");
			inserttask('1');
		}

		if($_POST['system_deactivateddocroot'] != $settings['system']['deactivateddocroot'])
		{
			$value = validate($_POST['system_deactivateddocroot'], 'docroot for deactivated users');

			if($value != '')
			{
				$value = makeCorrectDir($value);
			}

			$db->query("UPDATE `" . TABLE_PANEL_SETTINGS . "` SET `value`='" . $db->escape($value) . "' WHERE `settinggroup`='system' AND `varname`='deactivateddocroot'");
			inserttask('1');
		}

		if($_POST['system_webalizer_quiet'] != $settings['system']['webalizer_quiet'])
		{
			$value = in_array($_POST['system_webalizer_quiet'], array(
				'0',
				'1',
				'2'
			)) ? $_POST['system_webalizer_quiet'] : '2';
			$db->query("UPDATE `" . TABLE_PANEL_SETTINGS . "` SET `value`='" . (int)$value . "' WHERE `settinggroup`='system' AND `varname`='webalizer_quiet'");
		}

		if($_POST['system_bindconf_directory'] != $settings['system']['bindconf_directory'])
		{
			$value = validate($_POST['system_bindconf_directory'], 'bind conf directory');
			$value = makeCorrectDir($value);
			$db->query("UPDATE `" . TABLE_PANEL_SETTINGS . "` SET `value`='" . $db->escape($value) . "' WHERE `settinggroup`='system' AND `varname`='bindconf_directory'");
		}

		if($_POST['system_bindreload_command'] != $settings['system']['bindreload_command'])
		{
			$value = validate($_POST['system_bindreload_command'], 'bind reload command');
			$db->query("UPDATE `" . TABLE_PANEL_SETTINGS . "` SET `value`='" . $db->escape($value) . "' WHERE `settinggroup`='system' AND `varname`='bindreload_command'");
		}

		if($_POST['system_nameservers'] != $settings['system']['nameservers'])
		{
			$value = validate($_POST['system_nameservers'], 'nameservers', '/^(([a-z0-9\-\._]+, ?)*[a-z0-9\-\._]+)?$/i');
			$db->query("UPDATE `" . TABLE_PANEL_SETTINGS . "` SET `value`='" . $db->escape($value) . "' WHERE `settinggroup`='system' AND `varname`='nameservers'");
		}

		if($_POST['system_mxservers'] != $settings['system']['mxservers'])
		{
			$value = validate($_POST['system_mxservers'], 'mxservers', '/^(([0-9]+ [a-z0-9\-\._]+, ?)*[0-9]+ [a-z0-9\-\._]+)?$/i');
			$db->query("UPDATE `" . TABLE_PANEL_SETTINGS . "` SET `value`='" . $db->escape($value) . "' WHERE `settinggroup`='system' AND `varname`='mxservers'");
		}

		if($_POST['system_vmail_uid'] != $settings['system']['vmail_uid'])
		{
			$value = validate($_POST['system_vmail_uid'], 'vmail uid', '/^[0-9]{1,5}$/', 'vmailuidiswrong');
			$db->query("UPDATE `" . TABLE_PANEL_SETTINGS . "` SET `value`='" . (int)$value . "' WHERE `settinggroup`='system' AND `varname`='vmail_uid'");
		}

		if($_POST['system_vmail_gid'] != $settings['system']['vmail_gid'])
		{
			$value = validate($_POST['system_vmail_gid'], 'vmail gid', '/^[0-9]{1,5}$/', 'vmailgidiswrong');
			$db->query("UPDATE `" . TABLE_PANEL_SETTINGS . "` SET `value`='" . (int)$value . "' WHERE `settinggroup`='system' AND `varname`='vmail_gid'");
		}

		if($_POST['system_vmail_homedir'] != $settings['system']['vmail_homedir'])
		{
			$value = validate($_POST['system_vmail_homedir'], 'vmail homedir');
			$value = makeCorrectDir($value);
			$db->query("UPDATE `" . TABLE_PANEL_SETTINGS . "` SET `value`='" . $db->escape($value) . "' WHERE `settinggroup`='system' AND `varname`='vmail_homedir'");
		}

		if($_POST['system_mailpwcleartext'] != $settings['system']['mailpwcleartext'])
		{
			$value = ($_POST['system_mailpwcleartext'] == '1' ? '1' : '0');
			$db->query("UPDATE `" . TABLE_PANEL_SETTINGS . "` SET `value`='" . $db->escape($value) . "' WHERE `settinggroup`='system' AND `varname`='mailpwcleartext'");
		}

		if($_POST['panel_sendalternativemail'] != $settings['panel']['sendalternativemail'])
		{
			$value = ($_POST['panel_sendalternativemail'] == '1' ? '1' : '0');
			$db->query("UPDATE `" . TABLE_PANEL_SETTINGS . "` SET `value`='" . $db->escape($value) . "' WHERE `settinggroup`='panel' AND `varname`='sendalternativemail'");
		}

		if($_POST['panel_adminmail'] != $settings['panel']['adminmail'])
		{
			$value = $idna_convert->encode($_POST['panel_adminmail']);

			if(!validateEmail($value))
			{
				standard_error('adminmailiswrong');
				exit;
			}

			$db->query("UPDATE `" . TABLE_PANEL_SETTINGS . "` SET `value`='" . $db->escape($value) . "' WHERE `settinggroup`='panel' AND `varname`='adminmail'");
		}

		if($_POST['panel_paging'] != $settings['panel']['paging'])
		{
			$value = validate($_POST['panel_paging'], 'paging', '/^[0-9]+$/', 'pagingiswrong');
			$db->query("UPDATE `" . TABLE_PANEL_SETTINGS . "` SET `value`='" . (int)$value . "' WHERE `settinggroup`='panel' AND `varname`='paging'");
		}

		if($_POST['panel_natsorting'] != $settings['panel']['natsorting'])
		{
			$value = ($_POST['panel_natsorting'] == '1' ? '1' : '0');
			$db->query("UPDATE `" . TABLE_PANEL_SETTINGS . "` SET `value`='" . $db->escape($value) . "' WHERE `settinggroup`='panel' AND `varname`='natsorting'");
		}

		if($_POST['panel_standardlanguage'] != $settings['panel']['standardlanguage'])
		{
			$value = $_POST['panel_standardlanguage'];

			if(!in_array($value, $languages))
			{
				standard_error('stringformaterror', 'standard language');
				exit;
			}

			$db->query("UPDATE `" . TABLE_PANEL_SETTINGS . "` SET `value`='" . $db->escape($value) . "' WHERE `settinggroup`='panel' AND `varname`='standardlanguage'");
		}

		if($_POST['panel_pathedit'] != $settings['panel']['pathedit'])
		{
			$value = validate($_POST['panel_pathedit'], 'path edit', '/^(?:Manual|Dropdown)$/');
			$db->query("UPDATE `" . TABLE_PANEL_SETTINGS . "` SET `value`='" . $db->escape($value) . "' WHERE `settinggroup`='panel' AND `varname`='pathedit'");
		}

		if($_POST['panel_phpmyadmin_url'] != $settings['panel']['phpmyadmin_url'])
		{
			$value = $_POST['panel_phpmyadmin_url'];

			if(!validateUrl($idna_convert->encode($value))
			   && $value != '')
			{
				standard_error('phpmyadminiswrong');
				exit;
			}

			if($settings['panel']['phpmyadmin_url'] != '')
			{
				// delete or update menu

				if($value == '')
				{
					//delete

					$query = 'DELETE FROM `' . TABLE_PANEL_NAVIGATION . '` WHERE `lang` = "menue;mysql;phpmyadmin"';
				}
				else
				{
					//update

					$query = 'UPDATE `' . TABLE_PANEL_NAVIGATION . '` SET `url`="' . $db->escape($value) . '" WHERE `lang` = "menue;mysql;phpmyadmin"';
				}
			}
			else
			{
				// insert into menu

				$query = 'SELECT MAX(`order`) AS `max` FROM `' . TABLE_PANEL_NAVIGATION . '` WHERE `area`=\'customer\' AND `parent_url`=\'customer_mysql.php\'';
				$max = $db->query_first($query);
				$new = floor($max['max']/10)+10;
				$query = 'INSERT INTO `' . TABLE_PANEL_NAVIGATION . '` SET `lang`       = "menue;mysql;phpmyadmin",  `url`        = "' . $db->escape($value) . '",  `order`      = "' . (int)$new . '",  `area`       = "customer",  `new_window` = "1",  `required_resources` = "mysqls_used",  `parent_url` = "customer_mysql.php"';
			}

			$db->query($query);
			$db->query("UPDATE `" . TABLE_PANEL_SETTINGS . "` SET `value`='" . $db->escape($value) . "' WHERE `settinggroup`='panel' AND `varname`='phpmyadmin_url'");
		}

		if($_POST['panel_webmail_url'] != $settings['panel']['webmail_url'])
		{
			$value = $_POST['panel_webmail_url'];

			if(!validateUrl($idna_convert->encode($value))
			   && $value != '')
			{
				standard_error('webmailiswrong');
				exit;
			}

			if($settings['panel']['webmail_url'] != '')
			{
				// delete or update menu

				if($value == '')
				{
					//delete

					$query = 'DELETE FROM `' . TABLE_PANEL_NAVIGATION . '` WHERE `lang` = "menue;email;webmail"';
				}
				else
				{
					//update

					$query = 'UPDATE `' . TABLE_PANEL_NAVIGATION . '` SET `url`="' . $db->escape($value) . '" WHERE `lang` = "menue;email;webmail"';
				}
			}
			else
			{
				// insert into menu

				$query = 'SELECT MAX(`order`) AS `max` FROM `' . TABLE_PANEL_NAVIGATION . '` WHERE `area`=\'customer\' AND `parent_url`=\'customer_email.php\'';
				$max = $db->query_first($query);
				$new = floor($max['max']/10)+10;
				$query = 'INSERT INTO `' . TABLE_PANEL_NAVIGATION . '` SET `lang`       = "menue;email;webmail",  `url`        = "' . $db->escape($value) . '",  `order`      = "' . (int)$new . '",  `area`       = "customer",  `new_window` = "1",  `required_resources` = "emails_used",  `parent_url` = "customer_email.php"';
			}

			$db->query($query);
			$db->query("UPDATE `" . TABLE_PANEL_SETTINGS . "` SET `value`='" . $db->escape($value) . "' WHERE `settinggroup`='panel' AND `varname`='webmail_url'");
		}

		if($_POST['panel_webftp_url'] != $settings['panel']['webftp_url'])
		{
			$value = $_POST['panel_webftp_url'];

			if(!validateUrl($idna_convert->encode($value))
			   && $value != '')
			{
				standard_error('webftpiswrong');
				exit;
			}

			if($settings['panel']['webftp_url'] != '')
			{
				// delete or update menu

				if($value == '')
				{
					//delete

					$query = 'DELETE FROM `' . TABLE_PANEL_NAVIGATION . '` WHERE `lang` = "menue;ftp;webftp"';
				}
				else
				{
					//update

					$query = 'UPDATE `' . TABLE_PANEL_NAVIGATION . '` SET `url`="' . $db->escape($value) . '" WHERE `lang` = "menue;ftp;webftp"';
				}
			}
			else
			{
				// insert into menu

				$query = 'SELECT MAX(`order`) AS `max` FROM `' . TABLE_PANEL_NAVIGATION . '` WHERE `area`=\'customer\' AND `parent_url`=\'customer_ftp.php\'';
				$max = $db->query_first($query);
				$new = floor($max['max']/10)+10;
				$query = 'INSERT INTO `' . TABLE_PANEL_NAVIGATION . '` SET `lang`       = "menue;ftp;webftp",  `url`        = "' . $db->escape($value) . '",  `order`      = "' . (int)$new . '",  `area`       = "customer",  `new_window` = "1",  `parent_url` = "customer_ftp.php"';
			}

			$db->query($query);
			$db->query("UPDATE `" . TABLE_PANEL_SETTINGS . "` SET `value`='" . $db->escape($value) . "' WHERE `settinggroup`='panel' AND `varname`='webftp_url'");
		}

		if($_POST['ticketsystemenabled'] != $settings['ticket']['enabled'])
		{
			$value = (int)$_POST['ticketsystemenabled'] == 1 ? 1 : 0;

			if($value == 1)
			{
				$db->query("INSERT INTO `" . TABLE_PANEL_NAVIGATION . "` (`area`, `parent_url`, `lang`, `url`, `order`, `required_resources`, `new_window`)
					VALUES
					 ('customer', '', 'menue;ticket;ticket', 'customer_tickets.php', '20', '', 0),
					 ('customer', 'customer_tickets.php', 'menue;ticket;ticket', 'customer_tickets.php?page=tickets', 10, '', 0),
					 ('admin', '', 'admin;ticketsystem', 'admin_ticketsystem.nourl', '40', '', 0),
					 ('admin', 'admin_ticketsystem.nourl', 'menue;ticket;ticket', 'admin_tickets.php?page=tickets', '10', '', 0),
					 ('admin', 'admin_ticketsystem.nourl', 'menue;ticket;archive', 'admin_tickets.php?page=archive', '20', '', 0),
					 ('admin', 'admin_ticketsystem.nourl', 'menue;ticket;categories', 'admin_tickets.php?page=categories', '30', '', 0);");
			}
			else
			{
				$value = 0;
				$db->query('DELETE FROM `' . TABLE_PANEL_NAVIGATION . '` WHERE `lang` = "menue;ticket;ticket"');
				$db->query('DELETE FROM `' . TABLE_PANEL_NAVIGATION . '` WHERE `lang` = "admin;ticketsystem"');
				$db->query('DELETE FROM `' . TABLE_PANEL_NAVIGATION . '` WHERE `lang` = "menue;ticket;archive"');
				$db->query('DELETE FROM `' . TABLE_PANEL_NAVIGATION . '` WHERE `lang` = "menue;ticket;categories"');
			}

			$db->query("UPDATE `" . TABLE_PANEL_SETTINGS . "` SET `value`='" . (int)$value . "' WHERE `settinggroup`='ticket' AND `varname`='enabled'");
			$settings['ticket']['enabled'] = $value;
		}

		if($_POST['ticket_noreply_email'] != $settings['ticket']['noreply_email']
		   && $settings['ticket']['enabled'] == 1)
		{
			$value = validate($_POST['ticket_noreply_email'], 'ticket_noreply_email');

			if(!validateEmail($value))
			{
				standard_error('noreplymailiswrong');
			}

			$db->query("UPDATE `" . TABLE_PANEL_SETTINGS . "` SET `value`='" . $db->escape($value) . "' WHERE `settinggroup`='ticket' AND `varname`='noreply_email'");
		}

		if($_POST['ticket_noreply_name'] != $settings['ticket']['noreply_name'])
		{
			$value = validate($_POST['ticket_noreply_name'], 'ticket_noreply_name');
			$db->query("UPDATE `" . TABLE_PANEL_SETTINGS . "` SET `value`='" . $db->escape($value) . "' WHERE `settinggroup`='ticket' AND `varname`='noreply_name'");
		}

		if($_POST['ticket_concurrently_open'] != $settings['ticket']['concurrently_open']
		   && $settings['ticket']['enabled'] == 1)
		{
			$value = validate($_POST['ticket_concurrently_open'], 'ticket_concurrently_open');
			$db->query("UPDATE `" . TABLE_PANEL_SETTINGS . "` SET `value`='" . (int)$value . "' WHERE `settinggroup`='ticket' AND `varname`='concurrently_open'");
		}

		if($_POST['ticket_archiving_days'] != $settings['ticket']['archiving_days']
		   && $settings['ticket']['enabled'] == 1)
		{
			$value = validate($_POST['ticket_archiving_days'], 'ticket_archiving_days', '/^[0-9]{1,2}$/');
			$db->query("UPDATE `" . TABLE_PANEL_SETTINGS . "` SET `value`='" . $db->escape($value) . "' WHERE `settinggroup`='ticket' AND `varname`='archiving_days'");
		}

		if($_POST['ticket_worktime_all'] != $settings['ticket']['worktime_all']
		   && $settings['ticket']['enabled'] == 1)
		{
			$value = ($_POST['ticket_worktime_all'] == '1' ? '1' : '0');
			$db->query("UPDATE `" . TABLE_PANEL_SETTINGS . "` SET `value`='" . $db->escape($value) . "' WHERE `settinggroup`='ticket' AND `varname`='worktime_all'");
		}

		if($_POST['ticket_worktime_begin'] != $settings['ticket']['worktime_begin']
		   && $settings['ticket']['enabled'] == 1)
		{
			$value = validate($_POST['ticket_worktime_begin'], 'ticket_worktime_begin', '/^[012][0-9]:[0-6][0-9]$/');
			$db->query("UPDATE `" . TABLE_PANEL_SETTINGS . "` SET `value`='" . $db->escape($value) . "' WHERE `settinggroup`='ticket' AND `varname`='worktime_begin'");
		}

		if($_POST['ticket_worktime_end'] != $settings['ticket']['worktime_end']
		   && $settings['ticket']['enabled'] == 1)
		{
			$value = validate($_POST['ticket_worktime_end'], 'ticket_worktime_end', '/^[012][0-9]:[0-6][0-9]$/');
			$db->query("UPDATE `" . TABLE_PANEL_SETTINGS . "` SET `value`='" . $db->escape($value) . "' WHERE `settinggroup`='ticket' AND `varname`='worktime_end'");
		}

		if($_POST['ticket_worktime_sat'] != $settings['ticket']['worktime_sat']
		   && $settings['ticket']['enabled'] == 1)
		{
			$value = ($_POST['ticket_worktime_sat'] == '1' ? '1' : '0');
			$db->query("UPDATE `" . TABLE_PANEL_SETTINGS . "` SET `value`='" . $db->escape($value) . "' WHERE `settinggroup`='ticket' AND `varname`='worktime_sat'");
		}

		if($_POST['ticket_worktime_sun'] != $settings['ticket']['worktime_sun']
		   && $settings['ticket']['enabled'] == 1)
		{
			$value = ($_POST['ticket_worktime_sun'] == '1' ? '1' : '0');
			$db->query("UPDATE `" . TABLE_PANEL_SETTINGS . "` SET `value`='" . $db->escape($value) . "' WHERE `settinggroup`='ticket' AND `varname`='worktime_sun'");
		}

		redirectTo($filename, Array(
			'page' => $page,
			's' => $s
		));
	}
	else
	{
		// build the languages list

		$query = 'SELECT * FROM `' . TABLE_PANEL_LANGUAGE . '` ';
		$result = $db->query($query);
		$languages_array = array();
		$languages = '';

		while($row = $db->fetch_array($result))
		{
			if(!isset($languages_array[$row['language']])
			   && !in_array($row['language'], $languages_array))
			{
				$languages_array[$row['language']] = $row['language'];
				$languages.= makeoption($row['language'], $row['language'], $settings['panel']['standardlanguage'], true);
			}
		}

		// build the IP addresses lists

		$result = $db->query('SELECT `id`, `ip`, `port` FROM `' . TABLE_PANEL_IPSANDPORTS . '` ORDER BY `ip` ASC, `port` ASC');
		$system_ipaddress_array = array();
		$system_ipaddress = '';
		$system_defaultip = '';

		while($row = $db->fetch_array($result))
		{
			if(!isset($system_ipaddress_array[$row['ip']])
			   && !in_array($row['ip'], $system_ipaddress_array))
			{
				$system_ipaddress_array[$row['ip']] = $row['ip'];
				$system_ipaddress.= makeoption($row['ip'], $row['ip'], $settings['system']['ipaddress'], true, true);
			}

			$system_defaultip.= makeoption($row['ip'] . ':' . $row['port'], $row['id'], $settings['system']['defaultip'], true, true);
		}

		$webalizer_quiet = makeoption($lng['admin']['webalizer']['normal'], '0', $settings['system']['webalizer_quiet'], true, true);
		$webalizer_quiet.= makeoption($lng['admin']['webalizer']['quiet'], '1', $settings['system']['webalizer_quiet'], true, true);
		$webalizer_quiet.= makeoption($lng['admin']['webalizer']['veryquiet'], '2', $settings['system']['webalizer_quiet'], true, true);

		// build the pathedit list

		$pathedit = '';
		foreach(array(
			'Manual',
			'Dropdown'
		) as $method)
		{
			$pathedit.= makeoption($method, $method, $settings['panel']['pathedit'], true, true);
		}

		$natsorting = makeyesno('panel_natsorting', '1', '0', $settings['panel']['natsorting']);
		$mailpwcleartext = makeyesno('system_mailpwcleartext', '1', '0', $settings['system']['mailpwcleartext']);
		$panel_sendalternativemail = makeyesno('panel_sendalternativemail', '1', '0', $settings['panel']['sendalternativemail']);
		$ftpatdomain = makeyesno('customer_ftpatdomain', '1', '0', $settings['customer']['ftpatdomain']);
		$system_modlogsql = makeyesno('system_modlogsql', '1', '0', $settings['system']['mod_log_sql']);
		$system_modfcgid = makeyesno('system_modfcgid', '1', '0', $settings['system']['mod_fcgid']);
		$ticket_worktime_sat = makeyesno('ticket_worktime_sat', '1', '0', $settings['ticket']['worktime_sat']);
		$ticket_worktime_sun = makeyesno('ticket_worktime_sun', '1', '0', $settings['ticket']['worktime_sun']);
		$ticket_worktime_all = makeyesno('ticket_worktime_all', '1', '0', $settings['ticket']['worktime_all']);
		$ticketsystemenabled = makeyesno('ticketsystemenabled', '1', '0', $settings['ticket']['enabled']);
		$settings = htmlentities_array($settings);
		eval("echo \"" . getTemplate("settings/settings") . "\";");
	}
}
elseif($page == 'rebuildconfigs'
       && $userinfo['change_serversettings'] == '1')
{
	if(isset($_POST['send'])
	   && $_POST['send'] == 'send')
	{
		wasFormCompromised();
		inserttask('1');
		inserttask('3');
		inserttask('4');
		inserttask('5');
		redirectTo('admin_index.php', array(
			's' => $s
		));
	}
	else
	{
		ask_yesno('admin_configs_reallyrebuild', $filename, array(
			'page' => $page
		));
	}
}
elseif($page == 'updatecounters'
       && $userinfo['change_serversettings'] == '1')
{
	if(isset($_POST['send'])
	   && $_POST['send'] == 'send')
	{
		wasFormCompromised();
		$updatecounters = updateCounters(true);
		$customers = '';
		foreach($updatecounters['customers'] as $customerid => $customer)
		{
			eval("\$customers.=\"" . getTemplate("settings/updatecounters_row_customer") . "\";");
		}

		$admins = '';
		foreach($updatecounters['admins'] as $adminid => $admin)
		{
			eval("\$admins.=\"" . getTemplate("settings/updatecounters_row_admin") . "\";");
		}

		eval("echo \"" . getTemplate("settings/updatecounters") . "\";");
	}
	else
	{
		ask_yesno('admin_counters_reallyupdate', $filename, array(
			'page' => $page
		));
	}
}
elseif($page == 'wipecleartextmailpws'
       && $userinfo['change_serversettings'] == '1')
{
	if(isset($_POST['send'])
	   && $_POST['send'] == 'send')
	{
		wasFormCompromised();
		$db->query("UPDATE `" . TABLE_MAIL_USERS . "` SET `password`='' ");
		$db->query("UPDATE `" . TABLE_PANEL_SETTINGS . "` SET `value`='0' WHERE `settinggroup`='system' AND `varname`='mailpwcleartext'");
		redirectTo('admin_settings.php', array(
			's' => $s
		));
	}
	else
	{
		ask_yesno('admin_cleartextmailpws_reallywipe', $filename, array(
			'page' => $page
		));
	}
}

?>

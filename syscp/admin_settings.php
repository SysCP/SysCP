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
		$_part = isset($_GET['part']) ? $_GET['part'] : '';

		if($_part == '')
		{
			$_part = isset($_POST['part']) ? $_POST['part'] : '';
		}

		if($_part != '')
		{
			if($_part == 'all')
			{
				$settings_all = true;
				$settings_part = false;
			}
			else
			{
				$settings_all = false;
				$settings_part = true;
			}

			$only_enabledisable = false;
		}
		else
		{
			$settings_all = false;
			$settings_part = false;
			$only_enabledisable = true;
		}

		if(($settings_part && $_part == 'panel')
		   || $settings_all)
		{
			if($_POST['panel_standardlanguage'] != $settings['panel']['standardlanguage']
			   && isset($_POST['panel_standardlanguage']))
			{
				$value = $_POST['panel_standardlanguage'];

				if(!in_array($value, $languages))
				{
					standard_error('stringformaterror', 'standard language');
					exit;
				}

				$db->query("UPDATE `" . TABLE_PANEL_SETTINGS . "` SET `value`='" . $db->escape($value) . "' WHERE `settinggroup`='panel' AND `varname`='standardlanguage'");
				$log->logAction(ADM_ACTION, LOG_INFO, "changed panel_standardlanguage from '" . $settings['panel']['standardlanguage'] . "' to '" . $value . "'");
			}

			if($_POST['panel_natsorting'] != $settings['panel']['natsorting']
			   && isset($_POST['panel_natsorting']))
			{
				$value = ($_POST['panel_natsorting'] == '1' ? '1' : '0');
				$db->query("UPDATE `" . TABLE_PANEL_SETTINGS . "` SET `value`='" . $db->escape($value) . "' WHERE `settinggroup`='panel' AND `varname`='natsorting'");
				$log->logAction(ADM_ACTION, LOG_INFO, "changed panel_natsorting from '" . $settings['panel']['natsorting'] . "' to '" . $value . "'");
			}

			if($_POST['panel_no_robots'] != $settings['panel']['no_robots']
			   && isset($_POST['panel_no_robots']))
			{
				$value = ($_POST['panel_no_robots'] == '1' ? '1' : '0');
				$db->query("UPDATE `" . TABLE_PANEL_SETTINGS . "` SET `value`='" . $db->escape($value) . "' WHERE `settinggroup`='panel' AND `varname`='no_robots'");
				$settings['panel']['no_robots'] = $value;
			}

			if($_POST['panel_paging'] != $settings['panel']['paging']
			   && isset($_POST['panel_paging']))
			{
				$value = validate($_POST['panel_paging'], 'paging', '/^[0-9]+$/', 'pagingiswrong');
				$db->query("UPDATE `" . TABLE_PANEL_SETTINGS . "` SET `value`='" . (int)$value . "' WHERE `settinggroup`='panel' AND `varname`='paging'");
				$log->logAction(ADM_ACTION, LOG_INFO, "changed panel_paging from '" . $settings['panel']['paging'] . "' to '" . $value . "'");
			}

			if($_POST['panel_pathedit'] != $settings['panel']['pathedit']
			   && isset($_POST['panel_pathedit']))
			{
				$value = validate($_POST['panel_pathedit'], 'path edit', '/^(?:Manual|Dropdown)$/');
				$db->query("UPDATE `" . TABLE_PANEL_SETTINGS . "` SET `value`='" . $db->escape($value) . "' WHERE `settinggroup`='panel' AND `varname`='pathedit'");
				$log->logAction(ADM_ACTION, LOG_INFO, "changed panel_pathedit from '" . $settings['panel']['pathedit'] . "' to '" . $value . "'");
			}

			if($_POST['panel_adminmail'] != $settings['panel']['adminmail']
			   && isset($_POST['panel_adminmail']))
			{
				$value = $idna_convert->encode($_POST['panel_adminmail']);

				if(!validateEmail($value))
				{
					standard_error('adminmailiswrong');
					exit;
				}

				$db->query("UPDATE `" . TABLE_PANEL_SETTINGS . "` SET `value`='" . $db->escape($value) . "' WHERE `settinggroup`='panel' AND `varname`='adminmail'");
				$log->logAction(ADM_ACTION, LOG_INFO, "changed panel_adminmail from '" . $settings['panel']['adminmail'] . "' to '" . $value . "'");
			}

			if($_POST['panel_decimal_places'] != $settings['panel']['decimal_places']
			   && isset($_POST['panel_decimal_places']))
			{
				$value = (int)$_POST['panel_decimal_places'];

				if($value < 0)
				{
					$value = 0;
				}

				/* too many decimal places are senseless */

				if($value > 15)
				{
					$value = 15;
				}

				$db->query("UPDATE `" . TABLE_PANEL_SETTINGS . "` SET `value`='" . (int)$value . "' WHERE `settinggroup`='panel' AND `varname`='decimal_places'");
				$log->logAction(ADM_ACTION, LOG_INFO, "changed panel_decimal_places from '" . $settings['panel']['decimal_places'] . "' to '" . (int)$value . "'");
			}

			if($_POST['panel_phpmyadmin_url'] != $settings['panel']['phpmyadmin_url']
			   && isset($_POST['panel_phpmyadmin_url']))
			{
				$value = $_POST['panel_phpmyadmin_url'];

				if(!validateUrl($idna_convert->encode($value))
				   && $value != '')
				{
					standard_error('phpmyadminiswrong');
					exit;
				}

				// update menu

				$db->query('UPDATE `' . TABLE_PANEL_NAVIGATION . '` SET `url`="' . $db->escape($value) . '" WHERE `lang` = "menue;mysql;phpmyadmin"');
				$log->logAction(ADM_ACTION, LOG_NOTICE, "set panel_phpmyadmin_url in navigation to '" . $value . "'");

				$db->query("UPDATE `" . TABLE_PANEL_SETTINGS . "` SET `value`='" . $db->escape($value) . "' WHERE `settinggroup`='panel' AND `varname`='phpmyadmin_url'");
				$log->logAction(ADM_ACTION, LOG_INFO, "changed panel_phpmyadmin_url from '" . $settings['panel']['phpmyadmin_url'] . "' to '" . $value . "'");
			}

			if($_POST['panel_webmail_url'] != $settings['panel']['webmail_url']
			   && isset($_POST['panel_webmail_url']))
			{
				$value = $_POST['panel_webmail_url'];

				if(!validateUrl($idna_convert->encode($value))
				   && $value != '')
				{
					standard_error('webmailiswrong');
					exit;
				}

				// update menu
				
				$db->query('UPDATE `' . TABLE_PANEL_NAVIGATION . '` SET `url`="' . $db->escape($value) . '" WHERE `lang` = "menue;email;webmail"');
				$log->logAction(ADM_ACTION, LOG_NOTICE, "set panel_webmail_url in navigation to '" . $value . "'");

				$db->query("UPDATE `" . TABLE_PANEL_SETTINGS . "` SET `value`='" . $db->escape($value) . "' WHERE `settinggroup`='panel' AND `varname`='webmail_url'");
				$log->logAction(ADM_ACTION, LOG_INFO, "changed panel_webmail_url from '" . $settings['panel']['webmail_url'] . "' to '" . $value . "'");
			}

			if($_POST['panel_webftp_url'] != $settings['panel']['webftp_url']
			   && isset($_POST['panel_webftp_url']))
			{
				$value = $_POST['panel_webftp_url'];

				if(!validateUrl($idna_convert->encode($value))
				   && $value != '')
				{
					standard_error('webftpiswrong');
					exit;
				}

				// update menu
				
				$db->query('UPDATE `' . TABLE_PANEL_NAVIGATION . '` SET `url`="' . $db->escape($value) . '" WHERE `lang` = "menue;ftp;webftp"');
				$log->logAction(ADM_ACTION, LOG_NOTICE, "set panel_webftp_url in navigation to '" . $value . "'");

				$db->query("UPDATE `" . TABLE_PANEL_SETTINGS . "` SET `value`='" . $db->escape($value) . "' WHERE `settinggroup`='panel' AND `varname`='webftp_url'");
				$log->logAction(ADM_ACTION, LOG_INFO, "changed panel_webftp_url from '" . $settings['panel']['webftp_url'] . "' to '" . $value . "'");
			}

			if($_POST['frontend_syscp_version_login'] != $settings['admin']['show_version_login']
			   && isset($_POST['frontend_syscp_version_login']))
			{
				$value = ($_POST['frontend_syscp_version_login'] == '1' ? '1' : '0');
				$db->query("UPDATE `" . TABLE_PANEL_SETTINGS . "` SET `value`='" . $db->escape($value) . "' WHERE `settinggroup`='admin' AND `varname`='show_version_login'");
				$log->logAction(ADM_ACTION, LOG_INFO, "changed admin_show_version_login from '" . $settings['admin']['show_version_login'] . "' to '" . $value . "'");
			}

			if($_POST['frontend_syscp_version_footer'] != $settings['admin']['show_version_footer']
			   && isset($_POST['frontend_syscp_version_footer']))
			{
				$value = ($_POST['frontend_syscp_version_footer'] == '1' ? '1' : '0');
				$db->query("UPDATE `" . TABLE_PANEL_SETTINGS . "` SET `value`='" . $db->escape($value) . "' WHERE `settinggroup`='admin' AND `varname`='show_version_footer'");
				$log->logAction(ADM_ACTION, LOG_INFO, "changed admin_show_version_footer from '" . $settings['admin']['show_version_footer'] . "' to '" . $value . "'");
			}

			if($_POST['frontend_syscp_graphic'] != $settings['admin']['syscp_graphic']
			   && isset($_POST['frontend_syscp_graphic']))
			{
				$value = validate($_POST['frontend_syscp_graphic'], 'frontend_syscp_graphic');
				$db->query("UPDATE `" . TABLE_PANEL_SETTINGS . "` SET `value`='" . $db->escape($value) . "' WHERE `settinggroup`='admin' AND `varname`='syscp_graphic'");
				$log->logAction(ADM_ACTION, LOG_INFO, "changed syscp_graphic from '" . $settings['system']['syscp_graphic'] . "' to '" . $value . "'");
			}

			if($_POST['panel_allow_domain_change_admin'] != $settings['panel']['allow_domain_change_admin']
			   && isset($_POST['panel_allow_domain_change_admin']))
			{
				$value = ($_POST['panel_allow_domain_change_admin'] == '1' ? '1' : '0');
				$db->query("UPDATE `" . TABLE_PANEL_SETTINGS . "` SET `value`='" . $db->escape($value) . "' WHERE `settinggroup`='panel' AND `varname`='allow_domain_change_admin'");
				$log->logAction(ADM_ACTION, LOG_INFO, "changed panel_allow_domain_change_admin from '" . $settings['panel']['allow_domain_change_admin'] . "' to '" . $value . "'");
			}

			if($_POST['panel_allow_domain_change_customer'] != $settings['panel']['allow_domain_change_customer']
			   && isset($_POST['panel_allow_domain_change_customer']))
			{
				$value = ($_POST['panel_allow_domain_change_customer'] == '1' ? '1' : '0');
				$db->query("UPDATE `" . TABLE_PANEL_SETTINGS . "` SET `value`='" . $db->escape($value) . "' WHERE `settinggroup`='panel' AND `varname`='allow_domain_change_customer'");
				$log->logAction(ADM_ACTION, LOG_INFO, "changed panel_allow_domain_change_customer from '" . $settings['panel']['allow_domain_change_customer'] . "' to '" . $value . "'");
			}
		}

		if(($settings_part && $_part == 'accounts')
		   || $settings_all)
		{
			if($_POST['session_sessiontimeout'] != $settings['session']['sessiontimeout']
			   && isset($_POST['session_sessiontimeout']))
			{
				$value = validate($_POST['session_sessiontimeout'], 'session timeout', '/^[0-9]+$/', 'sessiontimeoutiswrong');
				$db->query("UPDATE `" . TABLE_PANEL_SETTINGS . "` SET `value`='" . $db->escape($value) . "' WHERE `settinggroup`='session' AND `varname`='sessiontimeout'");
				$log->logAction(ADM_ACTION, LOG_INFO, "changed session_sessiontimeout from '" . $settings['session']['sessiontimeout'] . "' to '" . $value . "'");
			}

			if($_POST['session_allow_multiple_login'] != $settings['session']['allow_multiple_login']
			   && isset($_POST['session_allow_multiple_login']))
			{
				$value = ($_POST['session_allow_multiple_login'] == '1' ? '1' : '0');
				$db->query("UPDATE `" . TABLE_PANEL_SETTINGS . "` SET `value`='" . $db->escape($value) . "' WHERE `settinggroup`='session' AND `varname`='allow_multiple_login'");
				$log->logAction(ADM_ACTION, LOG_INFO, "changed session_allow_multiple_login from '" . $settings['session']['allow_multiple_login'] . "' to '" . $value . "'");
			}

			if($_POST['login_maxloginattempts'] != $settings['login']['maxloginattempts']
			   && isset($_POST['login_maxloginattempts']))
			{
				$value = validate($_POST['login_maxloginattempts'], 'max login attempts', '/^[0-9]+$/', 'maxloginattemptsiswrong');
				$db->query("UPDATE `" . TABLE_PANEL_SETTINGS . "` SET `value`='" . $db->escape($value) . "' WHERE `settinggroup`='login' AND `varname`='maxloginattempts'");
				$log->logAction(ADM_ACTION, LOG_INFO, "changed login_maxloginattempts from '" . $settings['login']['maxloginattempts'] . "' to '" . $value . "'");
			}

			if($_POST['login_deactivatetime'] != $settings['login']['deactivatetime']
			   && isset($_POST['login_deactivatetime']))
			{
				$value = validate($_POST['login_deactivatetime'], 'deactivate time', '/^[0-9]+$/', 'deactivatetimiswrong');
				$db->query("UPDATE `" . TABLE_PANEL_SETTINGS . "` SET `value`='" . $db->escape($value) . "' WHERE `settinggroup`='login' AND `varname`='deactivatetime'");
				$log->logAction(ADM_ACTION, LOG_INFO, "changed login_deactivatetime from '" . $settings['login']['deactivatetime'] . "' to '" . $value . "'");
			}

			if($_POST['customer_accountprefix'] != $settings['customer']['accountprefix']
			   && isset($_POST['customer_accountprefix']))
			{
				$value = $_POST['customer_accountprefix'];

				if(validateUsername($value, $settings['panel']['unix_names'], 14 - strlen($settings['customer']['mysqlprefix'])))
				{
					$db->query("UPDATE `" . TABLE_PANEL_SETTINGS . "` SET `value`='" . $db->escape($value) . "' WHERE `settinggroup`='customer' AND `varname`='accountprefix'");
					$log->logAction(ADM_ACTION, LOG_INFO, "changed customer_accountprefix from '" . $settings['customer']['accountprefix'] . "' to '" . $value . "'");
				}
				else
				{
					standard_error('accountprefixiswrong');
					exit;
				}
			}

			if($_POST['customer_mysqlprefix'] != $settings['customer']['mysqlprefix']
			   && isset($_POST['customer_mysqlprefix']))
			{
				$value = $_POST['customer_mysqlprefix'];

				if(validateUsername($value, $settings['panel']['unix_names'], 14 - strlen($_POST['customer_mysqlprefix'])))
				{
					$db->query("UPDATE `" . TABLE_PANEL_SETTINGS . "` SET `value`='" . $db->escape($value) . "' WHERE `settinggroup`='customer' AND `varname`='mysqlprefix'");
					$log->logAction(ADM_ACTION, LOG_INFO, "changed customer_mysqlprefix from '" . $settings['customer']['mysqlprefix'] . "' to '" . $value . "'");
				}
				else
				{
					standard_error('mysqlprefixiswrong');
					exit;
				}
			}

			if($_POST['customer_ftpprefix'] != $settings['customer']['ftpprefix']
			   && isset($_POST['customer_ftpprefix']))
			{
				$value = $_POST['customer_ftpprefix'];

				if(validateUsername($value, $settings['panel']['unix_names']))
				{
					$db->query("UPDATE `" . TABLE_PANEL_SETTINGS . "` SET `value`='" . $db->escape($value) . "' WHERE `settinggroup`='customer' AND `varname`='ftpprefix'");
					$log->logAction(ADM_ACTION, LOG_INFO, "changed customer_ftpprefix from '" . $settings['customer']['ftpprefix'] . "' to '" . $value . "'");
				}
				else
				{
					standard_error('ftpprefixiswrong');
					exit;
				}
			}

			if($_POST['customer_ftpatdomain'] != $settings['customer']['ftpatdomain']
			   && isset($_POST['customer_ftpatdomain']))
			{
				$value = ($_POST['customer_ftpatdomain'] == '1' ? '1' : '0');
				$db->query("UPDATE `" . TABLE_PANEL_SETTINGS . "` SET `value`='" . $db->escape($value) . "' WHERE `settinggroup`='customer' AND `varname`='ftpatdomain'");
				$log->logAction(ADM_ACTION, LOG_INFO, "changed customer_ftpatdomain from '" . $settings['customer']['ftpatdomain'] . "' to '" . $value . "'");
			}

			if($_POST['panel_allow_preset'] != $settings['panel']['allow_preset']
			   && isset($_POST['panel_allow_preset']))
			{
				$value = ($_POST['panel_allow_preset'] == '1' ? '1' : '0');
				$db->query("UPDATE `" . TABLE_PANEL_SETTINGS . "` SET `value`='" . $db->escape($value) . "' WHERE `settinggroup`='panel' AND `varname`='allow_preset'");
				$log->logAction(ADM_ACTION, LOG_INFO, "changed panel_allow_preset from '" . $settings['panel']['allow_preset'] . "' to '" . $value . "'");
			}

			if($_POST['panel_allow_preset_admin'] != $settings['panel']['allow_preset_admin']
			   && isset($_POST['panel_allow_preset_admin']))
			{
				$value = ($_POST['panel_allow_preset_admin'] == '1' ? '1' : '0');
				$db->query("UPDATE `" . TABLE_PANEL_SETTINGS . "` SET `value`='" . $db->escape($value) . "' WHERE `settinggroup`='panel' AND `varname`='allow_preset_admin'");
				$log->logAction(ADM_ACTION, LOG_INFO, "changed panel_allow_preset_admin from '" . $settings['panel']['allow_preset'] . "' to '" . $value . "'");
			}
		}

		if(($settings_part && $_part == 'system')
		   || $settings_all)
		{
			if($_POST['system_documentroot_prefix'] != $settings['system']['documentroot_prefix']
			   && isset($_POST['system_documentroot_prefix']))
			{
				$value = validate($_POST['system_documentroot_prefix'], 'documentroot prefix');
				$value = makeCorrectDir($value);
				$db->query("UPDATE `" . TABLE_PANEL_SETTINGS . "` SET `value`='" . $db->escape($value) . "' WHERE `settinggroup`='system' AND `varname`='documentroot_prefix'");
				$log->logAction(ADM_ACTION, LOG_INFO, "changed system_documentroot_prefix from '" . $settings['system']['documentroot_prefix'] . "' to '" . $value . "'");
			}

			if(isset($_POST['system_ipaddress'])
			   && substr($_POST['system_ipaddress'], 0, 1) == '[')
			{
				$_POST['system_ipaddress'] = substr($_POST['system_ipaddress'], 1, strlen($_POST['system_ipaddress']) - 2);
			}

			if($_POST['system_ipaddress'] != $settings['system']['ipaddress']
			   && isset($_POST['system_ipaddress']))
			{
				$value = validate_ip($_POST['system_ipaddress']);
				$result_ipandport = $db->query("SELECT `id`, `ip`, `port` FROM `" . TABLE_PANEL_IPSANDPORTS . "` WHERE `ip`='" . $value . "'");

				if($db->num_rows($result_ipandport) == 0)
				{
					standard_error('invalidip', $value);
					exit;
				}

				$db->query("UPDATE `" . TABLE_PANEL_SETTINGS . "` SET `value`='" . $db->escape($value) . "' WHERE `settinggroup`='system' AND `varname`='ipaddress'");
				$log->logAction(ADM_ACTION, LOG_INFO, "changed system_ipaddress from '" . $settings['system']['ipaddress'] . "' to '" . $value . "'");
				inserttask('1');
				$mysql_access_host_array = array_map('trim', explode(',', $settings['system']['mysql_access_host']));
				$mysql_access_host_array[] = $value;
				$mysql_access_host_array = array_unique($mysql_access_host_array);
				$mysql_access_host = implode(',', $mysql_access_host_array);
				$db->query("UPDATE `" . TABLE_PANEL_SETTINGS . "` SET `value`='" . $db->escape($mysql_access_host) . "' WHERE `settinggroup`='system' AND `varname`='mysql_access_host'");
				$log->logAction(ADM_ACTION, LOG_INFO, "changed system_mysql_access_host from '" . $settings['system']['mysql_access_host'] . "' to '" . $mysql_access_host . "'");
				$db_root = new db($sql['host'], $sql['root_user'], $sql['root_password']);
				correctMysqlUsers($db, $db_root, $mysql_access_host_array);
				$db_root->close();
				unset($db_root);
			}

			if($_POST['system_defaultip'] != $settings['system']['defaultip']
			   && isset($_POST['system_defaultip']))
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
					$log->logAction(ADM_ACTION, LOG_NOTICE, "changed domains-ipandport (" . explode(',', $ids) . ") to '" . $value . "'");
				}

				$db->query("UPDATE `" . TABLE_PANEL_SETTINGS . "` SET `value`='" . (int)$value . "' WHERE `settinggroup`='system' AND `varname`='defaultip'");
				$log->logAction(ADM_ACTION, LOG_INFO, "changed system_defaultip from '" . $settings['system']['defaultip'] . "' to '" . $value . "'");
			}

			if($_POST['system_hostname'] != $settings['system']['hostname']
			   && isset($_POST['system_hostname']))
			{
				$value = $idna_convert->encode(validate($_POST['system_hostname'], 'hostname'));
				$db->query("UPDATE `" . TABLE_PANEL_SETTINGS . "` SET `value`='" . $db->escape($value) . "' WHERE `settinggroup`='system' AND `varname`='hostname'");
				$log->logAction(ADM_ACTION, LOG_INFO, "changed system_hostname from '" . $settings['system']['hostname'] . "' to '" . $value . "'");
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
					$log->logAction(ADM_ACTION, LOG_NOTICE, "changed domains (" . explode(',', $domains) . " to '" . $value . "'");
					inserttask('1');
				}
			}

			if($_POST['system_mysql_access_host'] != $settings['system']['mysql_access_host']
			   && isset($_POST['system_mysql_access_host']))
			{
				$value = validate($_POST['system_mysql_access_host'], 'MySQL Access Host');
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

				foreach($mysql_access_host_array as $host_entry)
				{
					if(validate_ip($host_entry, true) == false
					   && validateDomain($host_entry) == false
					   && $host_entry != '%')
					{
						standard_error('invalidmysqlhost', $host_entry);
					}
				}

				$db->query("UPDATE `" . TABLE_PANEL_SETTINGS . "` SET `value`='" . $db->escape($value) . "' WHERE `settinggroup`='system' AND `varname`='mysql_access_host'");
				$log->logAction(ADM_ACTION, LOG_INFO, "changed system_mysql_access_host from '" . $settings['system']['mysql_access_host'] . "' to '" . $value . "'");
				$db_root = new db($sql['host'], $sql['root_user'], $sql['root_password']);
				correctMysqlUsers($db, $db_root, $mysql_access_host_array);
				$db_root->close();
				unset($db_root);
			}

			if($_POST['system_realtime_port'] != $settings['system']['realtime_port']
			   && isset($_POST['system_realtime_port']))
			{
				$value = validate($_POST['system_realtime_port'], 'realtime port', '/^[0-9]+$/');
				$db->query("UPDATE `" . TABLE_PANEL_SETTINGS . "` SET `value`='" . $db->escape($value) . "' WHERE `settinggroup`='system' AND `varname`='realtime_port'");
				$log->logAction(ADM_ACTION, LOG_INFO, "changed system_realtime_port from '" . $settings['system']['realtime_port'] . "' to '" . $value . "'");
			}

			if($_POST['index_file_extension'] != $settings['system']['index_file_extension']
			   && isset($_POST['index_file_extension']))
			{
				$value = validate($_POST['index_file_extension'], 'index_file_extension', '/^[a-zA-Z0-9]{1,6}$/', 'index_file_extension');
				$db->query("UPDATE `" . TABLE_PANEL_SETTINGS . "` SET `value`='" . $db->escape($value) . "' WHERE `settinggroup`='system' AND `varname`='index_file_extension'");
				$log->logAction(ADM_ACTION, LOG_INFO, "changed index_file_extension from '" . $settings['system']['index_file_extension'] . "' to '" . $value . "'");
			}
		}

		if(($settings_part && $_part == 'webserver')
		   || $settings_all
		   || $only_enabledisable)
		{
			if($_POST['panel_webserver_selected'] != $settings['system']['webserver']
			   && isset($_POST['panel_webserver_selected']))
			{
				$db->query("UPDATE `" . TABLE_PANEL_SETTINGS . "` SET `value`='" . $db->escape($_POST['panel_webserver_selected']) . "' WHERE `settinggroup`='system' AND `varname`='webserver'");
				$log->logAction(ADM_ACTION, LOG_INFO, "changed system_webserver from '" . $settings['system']['apacheconf_vhost'] . "' to '" . $_POST['panel_webserver_selected'] . "'");
				inserttask('1');
			}

			if($_POST['system_apacheconf_vhost'] != $settings['system']['apacheconf_vhost']
			   && isset($_POST['system_apacheconf_vhost']))
			{
				$value = validate($_POST['system_apacheconf_vhost'], 'apacheconf vhost');
				$value = makeSecurePath($value);
				$db->query("UPDATE `" . TABLE_PANEL_SETTINGS . "` SET `value`='" . $db->escape($value) . "' WHERE `settinggroup`='system' AND `varname`='apacheconf_vhost'");
				$log->logAction(ADM_ACTION, LOG_INFO, "changed system_apacheconf_vhost from '" . $settings['system']['apacheconf_vhost'] . "' to '" . $value . "'");
				inserttask('1');
			}

			if($_POST['system_apacheconf_diroptions'] != $settings['system']['apacheconf_diroptions']
			   && isset($_POST['system_apacheconf_diroptions']))
			{
				$value = validate($_POST['system_apacheconf_diroptions'], 'apacheconf diroptions');
				$value = makeSecurePath($value);
				$db->query("UPDATE `" . TABLE_PANEL_SETTINGS . "` SET `value`='" . $db->escape($value) . "' WHERE `settinggroup`='system' AND `varname`='apacheconf_diroptions'");
				$log->logAction(ADM_ACTION, LOG_INFO, "changed system_apacheconf_diroptions from '" . $settings['system']['apacheconf_diroptions'] . "' to '" . $value . "'");
				inserttask('1');
			}

			if($_POST['system_apacheconf_htpasswddir'] != $settings['system']['apacheconf_htpasswddir']
			   && isset($_POST['system_apacheconf_htpasswddir']))
			{
				$value = validate($_POST['system_apacheconf_htpasswddir'], 'apacheconf htpasswddir');
				$value = makeCorrectDir($value);
				$db->query("UPDATE `" . TABLE_PANEL_SETTINGS . "` SET `value`='" . $db->escape($value) . "' WHERE `settinggroup`='system' AND `varname`='apacheconf_htpasswddir'");
				$log->logAction(ADM_ACTION, LOG_INFO, "changed system_apacheconf_htpasswddir from '" . $settings['system']['apacheconf_htpasswddir'] . "' to '" . $value . "'");
				inserttask('1');
			}

			if($_POST['system_apachereload_command'] != $settings['system']['apachereload_command']
			   && isset($_POST['system_apachereload_command']))
			{
				$value = validate($_POST['system_apachereload_command'], 'apache reload command');
				$db->query("UPDATE `" . TABLE_PANEL_SETTINGS . "` SET `value`='" . $db->escape($value) . "' WHERE `settinggroup`='system' AND `varname`='apachereload_command'");
				$log->logAction(ADM_ACTION, LOG_INFO, "changed system_apachereload_command from '" . $settings['system']['apachereload_command'] . "' to '" . $value . "'");
				inserttask('1');
			}

			if($_POST['system_modlogsql'] != $settings['system']['mod_log_sql']
			   && isset($_POST['system_modlogsql']))
			{
				$value = ($_POST['system_modlogsql'] == '1' ? '1' : '0');
				$db->query("UPDATE `" . TABLE_PANEL_SETTINGS . "` SET `value`='" . $db->escape($value) . "' WHERE `settinggroup`='system' AND `varname`='mod_log_sql'");
				$log->logAction(ADM_ACTION, LOG_INFO, "changed system_mod_log_sql from '" . $settings['system']['mod_log_sql'] . "' to '" . $value . "'");
				inserttask('1');
			}

			if($_POST['system_logfiles_directory'] != $settings['system']['logfiles_directory']
			   && isset($_POST['system_logfiles_directory']))
			{
				$value = validate($_POST['system_logfiles_directory'], 'logfiles directory');
				$value = makeCorrectDir($value);
				$db->query("UPDATE `" . TABLE_PANEL_SETTINGS . "` SET `value`='" . $db->escape($value) . "' WHERE `settinggroup`='system' AND `varname`='logfiles_directory'");
				$log->logAction(ADM_ACTION, LOG_INFO, "changed system_logfiles_directory from '" . $settings['system']['logfiles_directory'] . "' to '" . $value . "'");
			}

			if($_POST['system_phpappendopenbasedir'] != $settings['system']['phpappendopenbasedir']
			   && isset($_POST['system_phpappendopenbasedir']))
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
				$log->logAction(ADM_ACTION, LOG_INFO, "changed system_phpappendopenbasedir from '" . $settings['system']['phpappendopenbasedir'] . "' to '" . $value . "'");
				inserttask('1');
			}

			if($_POST['system_deactivateddocroot'] != $settings['system']['deactivateddocroot']
			   && isset($_POST['system_deactivateddocroot']))
			{
				$value = validate($_POST['system_deactivateddocroot'], 'docroot for deactivated users');

				if($value != '')
				{
					$value = makeCorrectDir($value);
				}

				$db->query("UPDATE `" . TABLE_PANEL_SETTINGS . "` SET `value`='" . $db->escape($value) . "' WHERE `settinggroup`='system' AND `varname`='deactivateddocroot'");
				$log->logAction(ADM_ACTION, LOG_INFO, "changed system_deactivateddocroot from '" . $settings['system']['deactivateddocroot'] . "' to '" . $value . "'");
				inserttask('1');
			}

			if($_POST['system_default_vhostconf'] != $settings['system']['default_vhostconf']
			   && isset($_POST['system_default_vhostconf']))
			{
				$value = validate(str_replace("\r\n", "\n", $_POST['system_default_vhostconf']), 'system_default_vhostconf', '/^[^\0]*$/');
				$db->query("UPDATE `" . TABLE_PANEL_SETTINGS . "` SET `value`='" . $db->escape($value) . "' WHERE `settinggroup`='system' AND `varname`='default_vhostconf'");
				$log->logAction(ADM_ACTION, LOG_INFO, "changed system_default_vhostconf from '" . $settings['system']['default_vhostconf'] . "' to '" . $value . "'");
				inserttask('1');
			}
		}

		if(($settings_part && $_part == 'statistic')
		   || $settings_all
		   || $only_enabledisable)
		{
			if($_POST['system_awstats_enabled'] != $settings['system']['awstats_enabled']
			   && isset($_POST['system_awstats_enabled']))
			{
				$value = ($_POST['system_awstats_enabled'] == '1' ? '1' : '0');
				$db->query("UPDATE `" . TABLE_PANEL_SETTINGS . "` SET `value`='" . (int)$value . "' WHERE `settinggroup`='system' AND `varname`='awstats_enabled'");
				$log->logAction(ADM_ACTION, LOG_INFO, "changed system_awstats_enabled from '" . $settings['system']['awstats_enabled'] . "' to '" . $value . "'");
				inserttask('1');
			}

			if(!$only_enabledisable)
			{
				if($_POST['system_webalizer_quiet'] != $settings['system']['webalizer_quiet']
				   && isset($_POST['system_webalizer_quiet']))
				{
					$value = in_array($_POST['system_webalizer_quiet'], array(
						'0',
						'1',
						'2'
					)) ? $_POST['system_webalizer_quiet'] : '2';
					$db->query("UPDATE `" . TABLE_PANEL_SETTINGS . "` SET `value`='" . (int)$value . "' WHERE `settinggroup`='system' AND `varname`='webalizer_quiet'");
					$log->logAction(ADM_ACTION, LOG_INFO, "changed system_webalizer_quiet from '" . $settings['system']['webalizer_quiet'] . "' to '" . $value . "'");
				}

				if($settings['system']['awstats_enabled'] == '1')
				{
					if($_POST['system_awstats_domain_file'] != $settings['system']['awstats_domain_file']
					   && isset($_POST['system_awstats_domain_file']))
					{
						$value = validate($_POST['system_awstats_domain_file'], 'awstats domainfile directory');
						$value = makeCorrectDir($value);
						$db->query("UPDATE `" . TABLE_PANEL_SETTINGS . "` SET `value`='" . $db->escape($value) . "' WHERE `settinggroup`='system' AND `varname`='awstats_domain_file'");
						$log->logAction(ADM_ACTION, LOG_INFO, "changed system_awstats_domain_file from '" . $settings['system']['awstats_domain_file'] . "' to '" . $value . "'");
						inserttask('1');
					}

					if($_POST['system_awstats_model_file'] != $settings['system']['awstats_model_file']
					   && isset($_POST['system_awstats_model_file']))
					{
						$value = validate($_POST['system_awstats_model_file'], 'awstats model file');
						$value = makeCorrectFile($value);
						$db->query("UPDATE `" . TABLE_PANEL_SETTINGS . "` SET `value`='" . $db->escape($value) . "' WHERE `settinggroup`='system' AND `varname`='awstats_model_file'");
						$log->logAction(ADM_ACTION, LOG_INFO, "changed system_awstats_model_file from '" . $settings['system']['awstats_model_file'] . "' to '" . $value . "'");
						inserttask('1');
					}

					if($_POST['system_awstats_path'] != $settings['system']['awstats_path']
					   && isset($_POST['system_awstats_path']))
					{
						$value = validate($_POST['system_awstats_path'], 'awstats path');
						$value = makeCorrectDir($value);
						$db->query("UPDATE `" . TABLE_PANEL_SETTINGS . "` SET `value`='" . $db->escape($value) . "' WHERE `settinggroup`='system' AND `varname`='awstats_path'");
						$log->logAction(ADM_ACTION, LOG_INFO, "changed system_awstats_path from '" . $settings['system']['awstats_path'] . "' to '" . $value . "'");
						inserttask('1');
					}

					if($_POST['system_awstats_updateall_command'] != $settings['system']['awstats_updateall_command']
					   && isset($_POST['system_awstats_updateall_command']))
					{
						$value = validate($_POST['system_awstats_updateall_command'], 'awstats updateall command');
						$value = makeCorrectFile($value);
						$db->query("UPDATE `" . TABLE_PANEL_SETTINGS . "` SET `value`='" . $db->escape($value) . "' WHERE `settinggroup`='system' AND `varname`='awstats_updateall_command'");
						$log->logAction(ADM_ACTION, LOG_INFO, "changed system_awstats_updateall_command from '" . $settings['system']['awstats_updateall_command'] . "' to '" . $value . "'");
						inserttask('1');
					}
				}
			}
		}

		if(($settings_part && $_part == 'mail')
		   || $settings_all)
		{
			if($_POST['system_vmail_uid'] != $settings['system']['vmail_uid']
			   && isset($_POST['system_vmail_uid']))
			{
				$value = validate($_POST['system_vmail_uid'], 'vmail uid', '/^[0-9]{1,5}$/', 'vmailuidiswrong');
				$db->query("UPDATE `" . TABLE_PANEL_SETTINGS . "` SET `value`='" . (int)$value . "' WHERE `settinggroup`='system' AND `varname`='vmail_uid'");
				$log->logAction(ADM_ACTION, LOG_INFO, "changed system_vmail_uid from '" . $settings['system']['vmail_uid'] . "' to '" . $value . "'");
			}

			if($_POST['system_vmail_gid'] != $settings['system']['vmail_gid']
			   && isset($_POST['system_vmail_gid']))
			{
				$value = validate($_POST['system_vmail_gid'], 'vmail gid', '/^[0-9]{1,5}$/', 'vmailgidiswrong');
				$db->query("UPDATE `" . TABLE_PANEL_SETTINGS . "` SET `value`='" . (int)$value . "' WHERE `settinggroup`='system' AND `varname`='vmail_gid'");
				$log->logAction(ADM_ACTION, LOG_INFO, "changed system_vmail_gid from '" . $settings['system']['vmail_gid'] . "' to '" . $value . "'");
			}

			if($_POST['system_vmail_homedir'] != $settings['system']['vmail_homedir']
			   && isset($_POST['system_vmail_homedir']))
			{
				$value = validate($_POST['system_vmail_homedir'], 'vmail homedir');
				$value = makeCorrectDir($value);
				$db->query("UPDATE `" . TABLE_PANEL_SETTINGS . "` SET `value`='" . $db->escape($value) . "' WHERE `settinggroup`='system' AND `varname`='vmail_homedir'");
				$log->logAction(ADM_ACTION, LOG_INFO, "changed system_vmail_homedir from '" . $settings['system']['vmail_homedir'] . "' to '" . $value . "'");
			}

			if($_POST['panel_sendalternativemail'] != $settings['panel']['sendalternativemail']
			   && isset($_POST['panel_sendalternativemail']))
			{
				$value = ($_POST['panel_sendalternativemail'] == '1' ? '1' : '0');
				$db->query("UPDATE `" . TABLE_PANEL_SETTINGS . "` SET `value`='" . $db->escape($value) . "' WHERE `settinggroup`='panel' AND `varname`='sendalternativemail'");
				$log->logAction(ADM_ACTION, LOG_INFO, "changed panel_sendalternativemail from '" . $settings['panel']['sendalternativemail'] . "' to '" . $value . "'");
			}

			if($_POST['system_mail_quota_enabled'] != $settings['system']['mail_quota_enabled']
			   && isset($_POST['system_mail_quota_enabled']))
			{
				$value = ($_POST['system_mail_quota_enabled'] == '1' ? '1' : '0');
				$db->query("UPDATE `" . TABLE_PANEL_SETTINGS . "` SET `value`='" . (int)$value . "' WHERE `settinggroup`='system' AND `varname`='mail_quota_enabled'");
				$log->logAction(ADM_ACTION, LOG_INFO, "changed mail_quota_enabled from '" . $settings['system']['mail_quota_enabled'] . "' to '" . $value . "'");
			}

			if(isset($_POST['system_mail_quota']))
			{
				$value = validate($_POST['system_mail_quota'], 'system_mail_quota', '/^\d+$/', 'vmailquotawrong');
				$value = intval_ressource($value);

				if($value != $settings['system']['mail_quota'])
				{
					$db->query("UPDATE `" . TABLE_PANEL_SETTINGS . "` SET `value`='" . (int)$value . "' WHERE `settinggroup`='system' AND `varname`='mail_quota'");
					$log->logAction(ADM_ACTION, LOG_INFO, "changed mail_quota from '" . $settings['system']['mail_quota'] . "' MB to '" . $value . "' MB");
				}
			}

			if($_POST['autoresponder_active'] != $settings['autoresponder']['autoresponder_active'])
			{
				$value = ($_POST['autoresponder_active'] == '1' ? '1' : '0');
				$db->query("UPDATE `" . TABLE_PANEL_SETTINGS . "` SET `value`='" . (int)$value . "' WHERE `settinggroup`='autoresponder' AND `varname`='autoresponder_active'");
				$log->logAction(ADM_ACTION, LOG_INFO, "changed autoresponder_active from '" . $settings['autoresponder']['autoresponder_active'] . "' to '" . $value . "'");

				if((int)$value == 1)
				{
					$db->query("UPDATE `" . TABLE_PANEL_NAVIGATION . "` SET `required_resources` = 'emails' WHERE `area` = 'customer' AND `parent_url` = 'customer_email.php' AND `url` = 'customer_autoresponder.php'");					
				}
				else
				{
					$db->query("UPDATE `" . TABLE_PANEL_NAVIGATION . "` SET `required_resources` = 'autoresponder.autoresponder_active' WHERE `area` = 'customer' AND `parent_url` = 'customer_email.php' AND `url` = 'customer_autoresponder.php'");
				}
			}
		}

		if(($settings_part && $_part == 'nameserver')
		   || $settings_all)
		{
			if($_POST['system_bindconf_directory'] != $settings['system']['bindconf_directory']
			   && isset($_POST['system_bindconf_directory']))
			{
				$value = validate($_POST['system_bindconf_directory'], 'bind conf directory');
				$value = makeCorrectDir($value);
				$db->query("UPDATE `" . TABLE_PANEL_SETTINGS . "` SET `value`='" . $db->escape($value) . "' WHERE `settinggroup`='system' AND `varname`='bindconf_directory'");
				$log->logAction(ADM_ACTION, LOG_INFO, "changed system_bindconf_directory from '" . $settings['system']['bindconf_directory'] . "' to '" . $value . "'");
			}

			if($_POST['system_bindreload_command'] != $settings['system']['bindreload_command']
			   && isset($_POST['system_bindreload_command']))
			{
				$value = validate($_POST['system_bindreload_command'], 'bind reload command');
				$db->query("UPDATE `" . TABLE_PANEL_SETTINGS . "` SET `value`='" . $db->escape($value) . "' WHERE `settinggroup`='system' AND `varname`='bindreload_command'");
				$log->logAction(ADM_ACTION, LOG_INFO, "changed system_bindreload_command from '" . $settings['system']['bindreload_command'] . "' to '" . $value . "'");
			}

			if($_POST['system_nameservers'] != $settings['system']['nameservers']
			   && isset($_POST['system_nameservers']))
			{
				$value = validate($_POST['system_nameservers'], 'nameservers', '/^(([a-z0-9\-\._]+, ?)*[a-z0-9\-\._]+)?$/i');
				$db->query("UPDATE `" . TABLE_PANEL_SETTINGS . "` SET `value`='" . $db->escape($value) . "' WHERE `settinggroup`='system' AND `varname`='nameservers'");
				$log->logAction(ADM_ACTION, LOG_INFO, "changed system_nameservers from '" . $settings['system']['nameservers'] . "' to '" . $value . "'");
			}

			if($_POST['system_mxservers'] != $settings['system']['mxservers']
			   && isset($_POST['system_mxservers']))
			{
				$value = validate($_POST['system_mxservers'], 'mxservers', '/^(([0-9]+ [a-z0-9\-\._]+, ?)*[0-9]+ [a-z0-9\-\._]+)?$/i');
				$db->query("UPDATE `" . TABLE_PANEL_SETTINGS . "` SET `value`='" . $db->escape($value) . "' WHERE `settinggroup`='system' AND `varname`='mxservers'");
				$log->logAction(ADM_ACTION, LOG_INFO, "changed system_mxservers from '" . $settings['system']['mxservers'] . "' to '" . $value . "'");
			}
		}

		if(($settings_part && $_part == 'logging')
		   || $settings_all
		   || $only_enabledisable)
		{
			if($_POST['loggingenabled'] != $settings['logger']['enabled']
			   && isset($_POST['loggingenabled']))
			{
				$value = validate($_POST['loggingenabled'], 'loggingenabled');
				$db->query("UPDATE `" . TABLE_PANEL_SETTINGS . "` SET `value`='" . $db->escape($value) . "' WHERE `settinggroup`='logger' AND `varname`='enabled'");

				if((int)$value == 1)
				{
					$db->query("UPDATE `" . TABLE_PANEL_NAVIGATION . "` SET `required_resources` = 'change_serversettings' WHERE `area`='admin' AND `parent_url`='admin_misc.nourl' AND `lang`='menue;logger;logger' AND `url`='admin_logger.php?page=log'");
				}
				else
				{
					$db->query("UPDATE `" . TABLE_PANEL_NAVIGATION . "` SET `required_resources` = 'logger.enabled' WHERE `area`='admin' AND `parent_url`='admin_misc.nourl' AND `lang`='menue;logger;logger' AND `url`='admin_logger.php?page=log'");
				}

				$log->logAction(ADM_ACTION, LOG_INFO, "changed logger_enabled from '" . $settings['logger']['enabled'] . "' to '" . $value . "'");
				$settings['logger']['enabled'] = $value;
			}

			if(!$only_enabledisable)
			{
				if($_POST['logger_severity'] != $settings['logger']['severity']
				   && isset($_POST['logger_severity']))
				{
					$value = validate($_POST['logger_severity'], 'logger_severity');
					$db->query("UPDATE `" . TABLE_PANEL_SETTINGS . "` SET `value`='" . $db->escape($value) . "' WHERE `settinggroup`='logger' AND `varname`='severity'");
					$log->logAction(ADM_ACTION, LOG_INFO, "changed logger_severity from '" . $settings['logger']['severity'] . "' to '" . $value . "'");
				}

				if($_POST['logger_logtypes'] != $settings['logger']['logtypes']
				   && isset($_POST['logger_logtypes']))
				{
					$value = validate($_POST['logger_logtypes'], 'logger_logtypes');
					$db->query("UPDATE `" . TABLE_PANEL_SETTINGS . "` SET `value`='" . $db->escape($value) . "' WHERE `settinggroup`='logger' AND `varname`='logtypes'");
					$log->logAction(ADM_ACTION, LOG_INFO, "changed logger_logtypes from '" . $settings['logger']['logtypes'] . "' to '" . $value . "'");
				}

				if($_POST['logger_logfile'] != $settings['logger']['logfile']
				   && isset($_POST['logger_logfile']))
				{
					$value = validate($_POST['logger_logfile'], 'logger_logfile');

					if($value != '')
					{
						$fp = @fopen($value, 'a');

						if($fp !== false)
						{
							fclose($fp);
						}
						else
						{
							standard_error('cannotwritetologfile', $value);
						}
					}

					$db->query("UPDATE `" . TABLE_PANEL_SETTINGS . "` SET `value`='" . $db->escape($value) . "' WHERE `settinggroup`='logger' AND `varname`='logfile'");
					$log->logAction(ADM_ACTION, LOG_INFO, "changed logger_logfile from '" . $settings['logger']['logfile'] . "' to '" . $value . "'");
				}

				if($_POST['logger_log_cron'] != $settings['logger']['log_cron']
				   && isset($_POST['logger_log_cron']))
				{
					$value = validate($_POST['logger_log_cron'], 'logger_log_cron');
					$log->setCronLog($value);
					$log->logAction(ADM_ACTION, LOG_INFO, "changed logger_log_cron from '" . $settings['logger']['log_cron'] . "' to '" . $value . "'");
				}
			}
		}

		if(($settings_part && $_part == 'dkim')
		   || $settings_all
		   || $only_enabledisable)
		{
			if($_POST['use_dkim'] != $settings['dkim']['use_dkim']
			   && isset($_POST['use_dkim']))
			{
				$value = (int)$_POST['use_dkim'] == 1 ? 1 : 0;
				$db->query("UPDATE `" . TABLE_PANEL_SETTINGS . "` SET `value`='" . (int)$value . "' WHERE `settinggroup`='dkim' AND `varname`='use_dkim'");
				$log->logAction(ADM_ACTION, LOG_INFO, "changed use_dkim from '" . $settings['dkim']['use_dkim'] . "' to '" . $value . "'");
				$settings['dkim']['use_dkim'] = $value;
			}

			if(!$only_enabledisable)
			{
				if($_POST['dkim_prefix'] != $settings['dkim']['dkim_prefix']
				   && isset($_POST['dkim_prefix']))
				{
					$value = validate($_POST['dkim_prefix'], 'dkim_prefix');
					$db->query("UPDATE `" . TABLE_PANEL_SETTINGS . "` SET `value`='" . $db->escape($value) . "' WHERE `settinggroup`='dkim' AND `varname`='dkim_prefix'");
					$log->logAction(ADM_ACTION, LOG_INFO, "changed dkim_prefix from '" . $settings['dkim']['dkim_prefix'] . "' to '" . $value . "'");
				}

				if($_POST['dkim_domains'] != $settings['dkim']['dkim_domains']
				   && isset($_POST['dkim_domains']))
				{
					$value = validate($_POST['dkim_domains'], 'dkim_domains', "/^[a-z0-9\._]+$/i");
					$db->query("UPDATE `" . TABLE_PANEL_SETTINGS . "` SET `value`='" . $db->escape($value) . "' WHERE `settinggroup`='dkim' AND `varname`='dkim_domains'");
					$log->logAction(ADM_ACTION, LOG_INFO, "changed dkim_domains from '" . $settings['dkim']['dkim_domains'] . "' to '" . $value . "'");
				}

				if($_POST['dkim_dkimkeys'] != $settings['dkim']['dkim_dkimkeys']
				   && isset($_POST['dkim_dkimkeys']))
				{
					$value = validate($_POST['dkim_dkimkeys'], 'dkim_dkimkeys', "/^[a-z0-9\._]+$/i");
					$db->query("UPDATE `" . TABLE_PANEL_SETTINGS . "` SET `value`='" . $db->escape($value) . "' WHERE `settinggroup`='dkim' AND `varname`='dkim_dkimkeys'");
					$log->logAction(ADM_ACTION, LOG_INFO, "changed dkim_dkimkeys from '" . $settings['dkim']['dkim_dkimkeys'] . "' to '" . $value . "'");
				}

				if($_POST['dkimrestart_command'] != $settings['dkim']['dkimrestart_command']
				   && isset($_POST['dkimrestart_command']))
				{
					$value = validate($_POST['dkimrestart_command'], 'dkimrestart_command');
					$db->query("UPDATE `" . TABLE_PANEL_SETTINGS . "` SET `value`='" . $db->escape($value) . "' WHERE `settinggroup`='dkim' AND `varname`='dkimrestart_command'");
					$log->logAction(ADM_ACTION, LOG_INFO, "changed dkimrestart_command from '" . $settings['dkim']['dkimrestart_command'] . "' to '" . $value . "'");
				}
			}
		}

		if(($settings_part && $_part == 'ticket')
		   || $settings_all
		   || $only_enabledisable)
		{
			if($_POST['ticketsystemenabled'] != $settings['ticket']['enabled']
			   && isset($_POST['ticketsystemenabled']))
			{
				$value = (int)$_POST['ticketsystemenabled'] == 1 ? 1 : 0;

				$db->query("UPDATE `" . TABLE_PANEL_SETTINGS . "` SET `value`='" . (int)$value . "' WHERE `settinggroup`='ticket' AND `varname`='enabled'");
				$log->logAction(ADM_ACTION, LOG_INFO, "changed ticket_enabled from '" . $settings['ticket']['enabled'] . "' to '" . $value . "'");
				$settings['ticket']['enabled'] = $value;
			}

			if(!$only_enabledisable)
			{
				if($_POST['ticket_noreply_email'] != $settings['ticket']['noreply_email']
				   && $settings['ticket']['enabled'] == 1
				   && isset($_POST['ticket_noreply_email']))
				{
					$value = validate($_POST['ticket_noreply_email'], 'ticket_noreply_email');

					if(!validateEmail($value))
					{
						standard_error('noreplymailiswrong');
					}

					$db->query("UPDATE `" . TABLE_PANEL_SETTINGS . "` SET `value`='" . $db->escape($value) . "' WHERE `settinggroup`='ticket' AND `varname`='noreply_email'");
					$log->logAction(ADM_ACTION, LOG_INFO, "changed ticket_noreply_email from '" . $settings['ticket']['noreply_email'] . "' to '" . $value . "'");
				}

				if($_POST['ticket_noreply_name'] != $settings['ticket']['noreply_name']
				   && isset($_POST['ticket_noreply_name']))
				{
					$value = validate($_POST['ticket_noreply_name'], 'ticket_noreply_name');
					$db->query("UPDATE `" . TABLE_PANEL_SETTINGS . "` SET `value`='" . $db->escape($value) . "' WHERE `settinggroup`='ticket' AND `varname`='noreply_name'");
					$log->logAction(ADM_ACTION, LOG_INFO, "changed ticket_noreply_name from '" . $settings['ticket']['noreply_name'] . "' to '" . $value . "'");
				}

				if($_POST['ticket_reset_cycle'] != $settings['ticket']['reset_cycle']
				   && isset($_POST['ticket_reset_cycle']))
				{
					$value = $_POST['ticket_reset_cycle'];

					if(!in_array($value, array(
						0,
						1,
						2,
						3
					)))
					{
						standard_error('ticketresetcycleiswrong');
					}

					$db->query("UPDATE `" . TABLE_PANEL_SETTINGS . "` SET `value`='" . (int)$value . "' WHERE `settinggroup`='ticket' AND `varname`='reset_cycle'");
				}

				if($_POST['ticket_concurrently_open'] != $settings['ticket']['concurrently_open']
				   && $settings['ticket']['enabled'] == 1
				   && isset($_POST['ticket_concurrently_open']))
				{
					$value = validate($_POST['ticket_concurrently_open'], 'ticket_concurrently_open');
					$db->query("UPDATE `" . TABLE_PANEL_SETTINGS . "` SET `value`='" . (int)$value . "' WHERE `settinggroup`='ticket' AND `varname`='concurrently_open'");
					$log->logAction(ADM_ACTION, LOG_INFO, "changed ticket_concurrently_open from '" . $settings['ticket']['concurrently_open'] . "' to '" . $value . "'");
				}

				if($_POST['ticket_archiving_days'] != $settings['ticket']['archiving_days']
				   && $settings['ticket']['enabled'] == 1
				   && isset($_POST['ticket_archiving_days']))
				{
					$value = validate($_POST['ticket_archiving_days'], 'ticket_archiving_days', '/^[0-9]{1,2}$/');
					$db->query("UPDATE `" . TABLE_PANEL_SETTINGS . "` SET `value`='" . $db->escape($value) . "' WHERE `settinggroup`='ticket' AND `varname`='archiving_days'");
					$log->logAction(ADM_ACTION, LOG_INFO, "changed ticket_archiving_days from '" . $settings['ticket']['archiving_days'] . "' to '" . $value . "'");
				}

				if($_POST['ticket_worktime_all'] != $settings['ticket']['worktime_all']
				   && $settings['ticket']['enabled'] == 1
				   && isset($_POST['ticket_worktime_all']))
				{
					$value = ($_POST['ticket_worktime_all'] == '1' ? '1' : '0');
					$db->query("UPDATE `" . TABLE_PANEL_SETTINGS . "` SET `value`='" . $db->escape($value) . "' WHERE `settinggroup`='ticket' AND `varname`='worktime_all'");
					$log->logAction(ADM_ACTION, LOG_INFO, "changed ticket_worktime_all from '" . $settings['ticket']['worktime_all'] . "' to '" . $value . "'");
				}

				if($_POST['ticket_worktime_begin'] != $settings['ticket']['worktime_begin']
				   && $settings['ticket']['enabled'] == 1
				   && isset($_POST['ticket_worktime_begin']))
				{
					$value = validate($_POST['ticket_worktime_begin'], 'ticket_worktime_begin', '/^[012][0-9]:[0-6][0-9]$/');
					$db->query("UPDATE `" . TABLE_PANEL_SETTINGS . "` SET `value`='" . $db->escape($value) . "' WHERE `settinggroup`='ticket' AND `varname`='worktime_begin'");
					$log->logAction(ADM_ACTION, LOG_INFO, "changed ticket_worktime_begin from '" . $settings['ticket']['worktime_begin'] . "' to '" . $value . "'");
				}

				if($_POST['ticket_worktime_end'] != $settings['ticket']['worktime_end']
				   && $settings['ticket']['enabled'] == 1
				   && isset($_POST['ticket_worktime_end']))
				{
					$value = validate($_POST['ticket_worktime_end'], 'ticket_worktime_end', '/^[012][0-9]:[0-6][0-9]$/');
					$db->query("UPDATE `" . TABLE_PANEL_SETTINGS . "` SET `value`='" . $db->escape($value) . "' WHERE `settinggroup`='ticket' AND `varname`='worktime_end'");
					$log->logAction(ADM_ACTION, LOG_INFO, "changed ticket_worktime_end from '" . $settings['ticket']['worktime_end'] . "' to '" . $value . "'");
				}

				if($_POST['ticket_worktime_sat'] != $settings['ticket']['worktime_sat']
				   && $settings['ticket']['enabled'] == 1
				   && isset($_POST['ticket_worktime_sat']))
				{
					$value = ($_POST['ticket_worktime_sat'] == '1' ? '1' : '0');
					$db->query("UPDATE `" . TABLE_PANEL_SETTINGS . "` SET `value`='" . $db->escape($value) . "' WHERE `settinggroup`='ticket' AND `varname`='worktime_sat'");
					$log->logAction(ADM_ACTION, LOG_INFO, "changed ticket_worktime_sat from '" . $settings['ticket']['worktime_sat'] . "' to '" . $value . "'");
				}

				if($_POST['ticket_worktime_sun'] != $settings['ticket']['worktime_sun']
				   && $settings['ticket']['enabled'] == 1
				   && isset($_POST['ticket_worktime_sun']))
				{
					$value = ($_POST['ticket_worktime_sun'] == '1' ? '1' : '0');
					$db->query("UPDATE `" . TABLE_PANEL_SETTINGS . "` SET `value`='" . $db->escape($value) . "' WHERE `settinggroup`='ticket' AND `varname`='worktime_sun'");
					$log->logAction(ADM_ACTION, LOG_INFO, "changed ticket_worktime_sun from '" . $settings['ticket']['worktime_sun'] . "' to '" . $value . "'");
				}
			}
		}

		if(($settings_part && $_part == 'ssl')
		   || $settings_all
		   || $only_enabledisable)
		{
			if($_POST['use_ssl'] != $settings['system']['use_ssl']
			   && isset($_POST['use_ssl']))
			{
				$value = ($_POST['use_ssl'] == '1' ? '1' : '0');
				$db->query("UPDATE `" . TABLE_PANEL_SETTINGS . "` SET `value`='" . $value . "' WHERE `settinggroup`='system' AND `varname`='use_ssl'");
				$log->logAction(ADM_ACTION, LOG_INFO, "changed system_use_ssl from '" . $settings['system']['use_ssl'] . "' to '" . $value . "'");
				$settings['system']['use_ssl'] = $value;
			}

			if(!$only_enabledisable)
			{
				if($_POST['ssl_cert_file'] != $settings['system']['ssl_cert_file']
				   && isset($_POST['ssl_cert_file']))
				{
					$value = validate($_POST['ssl_cert_file'], 'ssl_cert_file');
					$db->query("UPDATE `" . TABLE_PANEL_SETTINGS . "` SET `value`='" . $db->escape($value) . "' WHERE `settinggroup`='system' AND `varname`='ssl_cert_file'");
					$log->logAction(ADM_ACTION, LOG_INFO, "changed system_ssl_cert_file from '" . $settings['system']['ssl_cert_file'] . "' to '" . $value . "'");
				}

				if($_POST['openssl_cnf'] != $settings['system']['openssl_cnf']
				   && isset($_POST['openssl_cnf']))
				{
					$value = $_POST['openssl_cnf'];
					$db->query("UPDATE `" . TABLE_PANEL_SETTINGS . "` SET `value`='" . $db->escape($value) . "' WHERE `settinggroup`='ssl' AND `varname`='openssl_cnf'");
					$log->logAction(ADM_ACTION, LOG_INFO, "changed system_openssl_cnf from '" . $settings['system']['openssl_cnf'] . "' to '" . $value . "'");
				}
			}
		}

		if(($settings_part && $_part == 'billing')
		   || $settings_all
		   || $only_enabledisable)
		{
			if($_POST['billing_activate_billing'] != $settings['billing']['activate_billing']
			   && isset($_POST['billing_activate_billing']))
			{
				$value = ($_POST['billing_activate_billing'] == '1' ? '1' : '0');
				$db->query("UPDATE `" . TABLE_PANEL_SETTINGS . "` SET `value`='" . $value . "' WHERE `settinggroup`='billing' AND `varname`='activate_billing'");
				$log->logAction(ADM_ACTION, LOG_INFO, "changed billing_activate_billing from '" . $settings['billing']['activate_billing'] . "' to '" . $value . "'");
				$settings['billing']['activate_billing'] = $value;
			}

			if(!$only_enabledisable)
			{
				if($_POST['billing_highlight_inactive'] != $settings['billing']['highlight_inactive']
				   && isset($_POST['billing_highlight_inactive']))
				{
					$value = ($_POST['billing_highlight_inactive'] == '1' ? '1' : '0');
					$db->query("UPDATE `" . TABLE_PANEL_SETTINGS . "` SET `value`='" . $db->escape($value) . "' WHERE `settinggroup`='billing' AND `varname`='highlight_inactive'");
					$log->logAction(ADM_ACTION, LOG_INFO, "changed billing_highlight_inactive from '" . $settings['billing']['highlight_inactive'] . "' to '" . $value . "'");
				}

				if($_POST['billing_invoicenumber_count'] != $settings['billing']['invoicenumber_count']
				   && isset($_POST['billing_invoicenumber_count']))
				{
					$value = intval($_POST['billing_invoicenumber_count']);
					$db->query("UPDATE `" . TABLE_PANEL_SETTINGS . "` SET `value`='" . $db->escape($value) . "' WHERE `settinggroup`='billing' AND `varname`='invoicenumber_count'");
					$log->logAction(ADM_ACTION, LOG_INFO, "changed billing_invoicenumber_count from '" . $settings['billing']['invoicenumber_count'] . "' to '" . $value . "'");
				}
			}
		}

		if(($settings_part && $_part == 'aps')
		   || $settings_all
		   || $only_enabledisable)
		{
			if($_POST['aps_activate_aps'] != $settings['aps']['aps_active'])
			{
				$value = ($_POST['aps_activate_aps'] == '1' ? '1' : '0');
				$db->query("UPDATE `" . TABLE_PANEL_SETTINGS . "` SET `value`='" . (int)$value . "' WHERE `settinggroup`='aps' AND `varname`='aps_active'");
				$log->logAction(ADM_ACTION, LOG_INFO, "changed aps_active from '" . $settings['aps']['aps_active'] . "' to '" . $value . "'");

				if((int)$value == 1)
				{
					$db->query('UPDATE `' . TABLE_PANEL_NAVIGATION . '` SET `required_resources` = "phpenabled" WHERE `url` = "customer_aps.nourl"');
				}
				else
				{
					$db->query('UPDATE `' . TABLE_PANEL_NAVIGATION . '` SET `required_resources` = "aps.aps_active" WHERE `url` = "customer_aps.nourl"');
				}
			}

			if(!$only_enabledisable)
			{
				if($_POST['upload_fields'] != $settings['aps']['upload_fields'])
				{
					$value = validate($_POST['upload_fields'], 'upload fields', '/^([1-9]{1,1}|[0-9]{2,99})$/');
					$db->query("UPDATE `" . TABLE_PANEL_SETTINGS . "` SET `value`='" . (int)$value . "' WHERE `settinggroup`='aps' AND `varname`='upload_fields'");
					$log->logAction(ADM_ACTION, LOG_INFO, "changed upload_fields from '" . $settings['aps']['upload_fields'] . "' to '" . $value . "'");
				}

				if($_POST['items_per_page'] != $settings['aps']['items_per_page'])
				{
					$value = validate($_POST['items_per_page'], 'items per page', '/^([1-9]{1,1}|[0-9]{2,99})$/');
					$db->query("UPDATE `" . TABLE_PANEL_SETTINGS . "` SET `value`='" . (int)$value . "' WHERE `settinggroup`='aps' AND `varname`='items_per_page'");
					$log->logAction(ADM_ACTION, LOG_INFO, "changed items_per_page from '" . $settings['aps']['items_per_page'] . "' to '" . $value . "'");
				}

				//write exceptions to database
				//php configuration

				$Webserver = '';
				$Config = '';
				$Extensions = '';
				$Functions = '';
				$WebserverHtaccess = '';
				$ConfigParams = array(
					'short_open_tag',
					'file_uploads',
					'magic_quotes_gpc',
					'register_globals',
					'allow_url_fopen',
					'safe_mode',
					'post_max_size',
					'memory_limit',
					'max_execution_time'
				);
				foreach($ConfigParams as $Param)
				{
					if(isset($_POST[$Param])
					   && $_POST[$Param] == '1')
					{
						$Config.= ',' . $Param;
					}
				}

				//php extensions

				$ExtensionParams = array(
					'gd',
					'pcre',
					'ioncube',
					'curl',
					'mcrypt',
					'imap'
				);
				foreach($ExtensionParams as $Param)
				{
					if(isset($_POST[$Param])
					   && $_POST[$Param] == '1')
					{
						if($Param == 'ioncube')
						{
							$Extensions.= ',ioncube loader,ioncube';
						}
						else
						{
							$Extensions.= ',' . $Param;
						}

						//if module is enabled, this two functions are available

						if($Param == 'mcrypt')
						{
							$Functions.= ',mcrypt_encrypt,mcrypt_decrypt';
						}
					}
				}

				//webserver modules

				$WebserverParams = array(
					'mod_perl',
					'mod_rewrite',
					'mod_access'
				);
				foreach($WebserverParams as $Param)
				{
					if(isset($_POST[$Param])
					   && $_POST[$Param] == '1')
					{
						if($Param == 'mod_rewrite')
						{
							$Webserver.= ',mod_rewrite.c,mod_rewrite';
						}
						else
						{
							$Webserver.= ',' . $Param;
						}
					}
				}

				//other webserver exceptions

				if(isset($_POST['fastcgi'])
				   && $_POST['fastcgi'] == '1')
				{
					$Webserver.= ',fcgid-any';
				}

				if(isset($_POST['htaccess'])
				   && $_POST['htaccess'] == '1')
				{
					$WebserverHtaccess.= 'htaccess';
				}

				$db->query("UPDATE `" . TABLE_PANEL_SETTINGS . "` SET `value`='" . $db->escape(substr($Extensions, 1)) . "' WHERE `settinggroup`='aps' AND `varname`='php-extension'");
				$db->query("UPDATE `" . TABLE_PANEL_SETTINGS . "` SET `value`='" . $db->escape(substr($Webserver, 1)) . "' WHERE `settinggroup`='aps' AND `varname`='webserver-module'");
				$db->query("UPDATE `" . TABLE_PANEL_SETTINGS . "` SET `value`='" . $db->escape(substr($Functions, 1)) . "' WHERE `settinggroup`='aps' AND `varname`='php-function'");
				$db->query("UPDATE `" . TABLE_PANEL_SETTINGS . "` SET `value`='" . $db->escape(substr($Config, 1)) . "' WHERE `settinggroup`='aps' AND `varname`='php-configuration'");
				$db->query("UPDATE `" . TABLE_PANEL_SETTINGS . "` SET `value`='" . $db->escape($WebserverHtaccess) . "' WHERE `settinggroup`='aps' AND `varname`='webserver-htaccess'");
			}
		}

		if(($settings_part && $_part == 'security')
		   || $settings_all
		   || $only_enabledisable)
		{
			if($_POST['panel_unix_names'] != $settings['panel']['unix_names']
			   && isset($_POST['panel_unix_names']))
			{
				$value = ($_POST['panel_unix_names'] == '1' ? '1' : '0');
				$db->query("UPDATE `" . TABLE_PANEL_SETTINGS . "` SET `value`='" . $db->escape($value) . "' WHERE `settinggroup`='panel' AND `varname`='unix_names'");
				$settings['panel']['unix_names'] = $value;
				$log->logAction(ADM_ACTION, LOG_INFO, "changed panel_unix_names from '" . $settings['panel']['unix_names'] . "' to '" . $value . "'");
			}

			if($_POST['system_mailpwcleartext'] != $settings['system']['mailpwcleartext']
			   && isset($_POST['system_mailpwcleartext']))
			{
				$value = ($_POST['system_mailpwcleartext'] == '1' ? '1' : '0');
				$db->query("UPDATE `" . TABLE_PANEL_SETTINGS . "` SET `value`='" . $db->escape($value) . "' WHERE `settinggroup`='system' AND `varname`='mailpwcleartext'");
				$log->logAction(ADM_ACTION, LOG_INFO, "changed system_mailpwcleartext from '" . $settings['system']['mailpwcleartext'] . "' to '" . $value . "'");
			}

			if($_POST['system_mod_fcgid_tmpdir'] != $settings['system']['mod_fcgid_tmpdir']
			   && isset($_POST['system_mod_fcgid_tmpdir']))
			{
				$value = validate($_POST['system_mod_fcgid_tmpdir'], 'fcgid tmpdir');
				$value = makeCorrectDir($value);
				$db->query("UPDATE `" . TABLE_PANEL_SETTINGS . "` SET `value`='" . $db->escape($value) . "' WHERE `settinggroup`='system' AND `varname`='mod_fcgid_tmpdir'");
				$log->logAction(ADM_ACTION, LOG_INFO, "changed mod_fcgid_tmpdir from '" . $settings['system']['mod_fcgid_tmpdir'] . "' to '" . $value . "'");
				inserttask('1');
			}

			if($_POST['system_mod_fcgid_configdir'] != $settings['system']['mod_fcgid_configdir']
			   && isset($_POST['system_mod_fcgid_configdir']))
			{
				$value = validate($_POST['system_mod_fcgid_configdir'], 'fcgid configdir');
				$value = makeCorrectDir($value);
				$db->query("UPDATE `" . TABLE_PANEL_SETTINGS . "` SET `value`='" . $db->escape($value) . "' WHERE `settinggroup`='system' AND `varname`='mod_fcgid_configdir'");
				$log->logAction(ADM_ACTION, LOG_INFO, "changed mod_fcgid_configdir from '" . $settings['system']['mod_fcgid_configdir'] . "' to '" . $value . "'");
				inserttask('1');
			}

			if(isset($_POST['system_mod_fcgid_wrapper'])
			   && $_POST['system_mod_fcgid_wrapper'] != $settings['system']['mod_fcgid_wrapper'])
			{
				$value = ($_POST['system_mod_fcgid_wrapper'] == '1' ? '1' : '0');
				$db->query("UPDATE `" . TABLE_PANEL_SETTINGS . "` SET `value`='" . $db->escape($value) . "' WHERE `settinggroup`='system' AND `varname`='mod_fcgid_wrapper'");
				$log->logAction(ADM_ACTION, LOG_INFO, "changed mod_fcgid_wrapper from '" . $settings['system']['mod_fcgid_wrapper'] . "' to '" . $value . "'");
				inserttask('1');
			}

			if(isset($_POST['system_mod_fcgid_starter'])
			   && $_POST['system_mod_fcgid_starter'] != $settings['system']['mod_fcgid_starter'])
			{
				$value = validate($_POST['system_mod_fcgid_starter'], 'fcgid starter', '/^[0-9]*$/', '', array('-1', ''));
				$db->query("UPDATE `" . TABLE_PANEL_SETTINGS . "` SET `value`='" . $db->escape($value) . "' WHERE `settinggroup`='system' AND `varname`='mod_fcgid_starter'");
				$log->logAction(ADM_ACTION, LOG_INFO, "changed mod_fcgid_starter from '" . $settings['system']['mod_fcgid_starter'] . "' to '" . $value . "'");
				inserttask('1');
			}

			if(isset($_POST['system_mod_fcgid_maxrequests'])
			   && $_POST['system_mod_fcgid_maxrequests'] != $settings['system']['mod_fcgid_maxrequests'])
			{
				$value = validate($_POST['system_mod_fcgid_maxrequests'], 'fcgid max requests', '/^[0-9]*$/', '', array('-1', ''));
				$db->query("UPDATE `" . TABLE_PANEL_SETTINGS . "` SET `value`='" . $db->escape($value) . "' WHERE `settinggroup`='system' AND `varname`='mod_fcgid_maxrequests'");
				$log->logAction(ADM_ACTION, LOG_INFO, "changed mod_fcgid_maxrequests from '" . $settings['system']['mod_fcgid_maxrequests'] . "' to '" . $value . "'");
				inserttask('1');
			}

			if(isset($_POST['system_mod_fcgid_peardir'])
			   && $_POST['system_mod_fcgid_peardir'] != $settings['system']['mod_fcgid_peardir'])
			{
				$value = validate($_POST['system_mod_fcgid_peardir'], 'system_mod_fcgid_peardir');
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

				$db->query("UPDATE `" . TABLE_PANEL_SETTINGS . "` SET `value`='" . $db->escape($value) . "' WHERE `settinggroup`='system' AND `varname`='mod_fcgid_peardir'");
				$log->logAction(ADM_ACTION, LOG_INFO, "changed mod_fcgid_peardir from '" . $settings['system']['mod_fcgid_peardir'] . "' to '" . $value . "'");
				inserttask('1');
			}

			if($_POST['system_modfcgid'] != $settings['system']['mod_fcgid']
			   && isset($_POST['system_modfcgid']))
			{
				$value = ($_POST['system_modfcgid'] == '1' ? '1' : '0');
				$db->query("UPDATE `" . TABLE_PANEL_SETTINGS . "` SET `value`='" . $db->escape($value) . "' WHERE `settinggroup`='system' AND `varname`='mod_fcgid'");

				$log->logAction(ADM_ACTION, LOG_INFO, "changed system_mod_fcgid from '" . $settings['system']['mod_fcgid'] . "' to '" . $value . "'");
				inserttask('1');
			}
		}

		redirectTo($filename, Array(
			'page' => $page,
			's' => $s,
			'part' => $_part
		));
	}
	else
	{
		$log->logAction(ADM_ACTION, LOG_NOTICE, "viewed admin_settings");

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

				if(filter_var($row['ip'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) === FALSE)
				{
					$row['ip'] = '[' . $row['ip'] . ']';
				}

				$system_ipaddress.= makeoption($row['ip'], $row['ip'], $settings['system']['ipaddress'], true, true);
			}

			$system_defaultip.= makeoption($row['ip'] . ':' . $row['port'], $row['id'], $settings['system']['defaultip'], true, true);
		}

		$webalizer_quiet = makeoption($lng['admin']['webalizer']['normal'], '0', $settings['system']['webalizer_quiet'], true, true);
		$webalizer_quiet.= makeoption($lng['admin']['webalizer']['quiet'], '1', $settings['system']['webalizer_quiet'], true, true);
		$webalizer_quiet.= makeoption($lng['admin']['webalizer']['veryquiet'], '2', $settings['system']['webalizer_quiet'], true, true);
		$ticket_reset_cycle = makeoption($lng['admin']['tickets']['daily'], '0', $settings['ticket']['reset_cycle'], true, true);
		$ticket_reset_cycle.= makeoption($lng['admin']['tickets']['weekly'], '1', $settings['ticket']['reset_cycle'], true, true);
		$ticket_reset_cycle.= makeoption($lng['admin']['tickets']['monthly'], '2', $settings['ticket']['reset_cycle'], true, true);
		$ticket_reset_cycle.= makeoption($lng['admin']['tickets']['yearly'], '3', $settings['ticket']['reset_cycle'], true, true);
		$loggingseverity = makeoption($lng['admin']['logger']['normal'], '1', $settings['logger']['severity'], true, true);
		$loggingseverity.= makeoption($lng['admin']['logger']['paranoid'], '2', $settings['logger']['severity'], true, true);

		// build the pathedit list

		$pathedit = '';
		foreach(array(
			'Manual',
			'Dropdown'
		) as $method)
		{
			$pathedit.= makeoption($method, $method, $settings['panel']['pathedit'], true, true);
		}

		$quota_enabled = makeyesno('system_mail_quota_enabled', '1', '0', $settings['system']['mail_quota_enabled']);
		$quota = $settings['system']['mail_quota'];
		$session_allow_multiple_login = makeyesno('session_allow_multiple_login', '1', '0', $settings['session']['allow_multiple_login']);
		$panel_allow_domain_change_admin = makeyesno('panel_allow_domain_change_admin', '1', '0', $settings['panel']['allow_domain_change_admin']);
		$panel_allow_domain_change_customer = makeyesno('panel_allow_domain_change_customer', '1', '0', $settings['panel']['allow_domain_change_customer']);
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
		$no_robots = makeyesno('panel_no_robots', '1', '0', $settings['panel']['no_robots']);
		$loggingenabled = makeyesno('loggingenabled', '1', '0', $settings['logger']['enabled']);
		$logginglogcron = makeyesno('logger_log_cron', '1', '0', $settings['logger']['log_cron']);
		$ssl_enabled = makeyesno('use_ssl', '1', '0', $settings['system']['use_ssl']);
		$webserver_selected = makeyesno('webserver', '1', '0', $settings['system']['webserver']);
		$dkimenabled = makeyesno('use_dkim', '1', '0', $settings['dkim']['use_dkim']);
		$system_awstats_enabled = makeyesno('system_awstats_enabled', '1', '0', $settings['system']['awstats_enabled']);
		$unix_names = makeyesno('panel_unix_names', '1', '0', $settings['panel']['unix_names']);
		$allow_preset = makeyesno('panel_allow_preset', '1', '0', $settings['panel']['allow_preset']);
		$allow_preset_admin = makeyesno('panel_allow_preset_admin', '1', '0', $settings['panel']['allow_preset_admin']);
		$billing_activate_billing = makeyesno('billing_activate_billing', '1', '0', $settings['billing']['activate_billing']);
		$billing_highlight_inactive = makeyesno('billing_highlight_inactive', '1', '0', $settings['billing']['highlight_inactive']);
		$autoresponder_active = makeyesno('autoresponder_active', '1', '0', $settings['autoresponder']['autoresponder_active']);
		$aps_activate_aps = makeyesno('aps_activate_aps', '1', '0', $settings['aps']['aps_active']);
		$frontend_syscp_version_login = makeyesno('frontend_syscp_version_login', '1', '0', $settings['admin']['show_version_login']);
		$frontend_syscp_version_footer = makeyesno('frontend_syscp_version_footer', '1', '0', $settings['admin']['show_version_footer']);
		$settings = htmlentities_array($settings);
		$settings_page = '';
		$webserver_selected = '';
		foreach(array(
			'apache2',
			'lighttpd'
		) as $method)
		{
			$webserver_selected.= makeoption($method, $method, $settings['system']['webserver']);
		}

		$system_modfcgid_wrapper = makeoption('ScriptAlias', 0, $settings['system']['mod_fcgid_wrapper']);
		$system_modfcgid_wrapper.= makeoption('FCGIWrapper', 1, $settings['system']['mod_fcgid_wrapper']);
		$_part = isset($_GET['part']) ? $_GET['part'] : '';

		if($_part == '')
		{
			$_part = isset($_POST['part']) ? $_POST['part'] : '';
		}

		if($_part == '')
		{
			eval("\$settings_page .= \"" . getTemplate("settings/settings_overview") . "\";");
		}

		if(isset($_part)
		   && $_part != '')
		{
			if($_part == 'panel'
			   || $_part == 'all')
			{
				eval("\$settings_page .= \"" . getTemplate("settings/settings_panel") . "\";");
			}

			if($_part == 'accounts'
			   || $_part == 'all')
			{
				eval("\$settings_page .= \"" . getTemplate("settings/settings_accounts") . "\";");
			}

			if($_part == 'system'
			   || $_part == 'all')
			{
				eval("\$settings_page .= \"" . getTemplate("settings/settings_system") . "\";");
			}

			if($_part == 'webserver'
			   || $_part == 'all')
			{
				eval("\$settings_page .= \"" . getTemplate("settings/settings_webserver") . "\";");
			}

			if($_part == 'statistic'
			   || $_part == 'all')
			{
				eval("\$settings_page .= \"" . getTemplate("settings/settings_statistic") . "\";");
			}

			if($_part == 'mail'
			   || $_part == 'all')
			{
				eval("\$settings_page .= \"" . getTemplate("settings/settings_mail") . "\";");
			}

			if($_part == 'nameserver'
			   || $_part == 'all')
			{
				eval("\$settings_page .= \"" . getTemplate("settings/settings_nameserver") . "\";");
			}

			if($_part == 'logging'
			   || $_part == 'all')
			{
				eval("\$settings_page .= \"" . getTemplate("settings/settings_logging") . "\";");
			}

			if($_part == 'dkim'
			   || $_part == 'all')
			{
				eval("\$settings_page .= \"" . getTemplate("settings/settings_dkim") . "\";");
			}

			if($_part == 'ticket'
			   || $_part == 'all')
			{
				eval("\$settings_page .= \"" . getTemplate("settings/settings_ticket") . "\";");
			}

			if($_part == 'ssl'
			   || $_part == 'all')
			{
				eval("\$settings_page .= \"" . getTemplate("settings/settings_ssl") . "\";");
			}

			if($_part == 'billing'
			   || $_part == 'all')
			{
				eval("\$settings_page .= \"" . getTemplate("settings/settings_billing") . "\";");
			}

			if($_part == 'aps'
			   || $_part == 'all')
			{
				eval("\$settings_page .= \"" . getTemplate("settings/settings_aps") . "\";");
			}

			if($_part == 'security'
			   || $_part == 'all')
			{
				eval("\$settings_page .= \"" . getTemplate("settings/settings_security") . "\";");
			}
		}

		eval("echo \"" . getTemplate("settings/settings_form_begin") . "\";");
		eval("echo \$settings_page;");
		eval("echo \"" . getTemplate("settings/settings_form_end") . "\";");
	}
}
elseif($page == 'rebuildconfigs'
       && $userinfo['change_serversettings'] == '1')
{
	if(isset($_POST['send'])
	   && $_POST['send'] == 'send')
	{
		$log->logAction(ADM_ACTION, LOG_INFO, "rebuild configfiles");
		inserttask('1');
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
		$log->logAction(ADM_ACTION, LOG_INFO, "updated resource-counters");
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
		$log->logAction(ADM_ACTION, LOG_WARNING, "wiped all cleartext mail passwords");
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
elseif($page == 'wipequotas'
       && $userinfo['change_serversettings'] == '1')
{
	if(isset($_POST['send'])
	   && $_POST['send'] == 'send')
	{
		$log->logAction(ADM_ACTION, LOG_WARNING, "wiped all mailquotas");
		// Set the quota to 0 which means unlimited
		$db->query("UPDATE `" . TABLE_MAIL_USERS . "` SET `quota`='0' ");
		$db->query("UPDATE " . TABLE_PANEL_CUSTOMERS ." SET `email_quota_used` = 0");
		redirectTo('admin_settings.php', array(
			's' => $s
		));
	}
	else
	{
		ask_yesno('admin_quotas_reallywipe', $filename, array(
			'page' => $page
		));
	}
}
elseif($page == 'enforcequotas'
       && $userinfo['change_serversettings'] == '1')
{
	if(isset($_POST['send'])
	   && $_POST['send'] == 'send')
	{
		// Fetch all accounts
		$result = $db->query("SELECT `quota`, `customerid` FROM " . TABLE_MAIL_USERS);
		while ($array = $db->fetch_array($result)) {
			$difference = $settings['system']['mail_quota'] - $array['quota'];
			$db->query("UPDATE " .TABLE_PANEL_CUSTOMERS . " SET `email_quota_used` = `email_quota_used` + " . (int)$difference . " WHERE `customerid` = '" . $array['customerid'] ."'");
		}
		// Set the new quota
		$db->query("UPDATE `" . TABLE_MAIL_USERS . "` SET `quota`='". $settings['system']['mail_quota'] ."'");
		// Update the Customer, if the used quota is bigger than the allowed quota
		$db->query("UPDATE " . TABLE_PANEL_CUSTOMERS ." SET `email_quota` = `email_quota_used` WHERE `email_quota` < `email_quota_used`");
		
		$log->logAction(ADM_ACTION, LOG_WARNING, 'enforcing mailquota to all customers: '.$settings['system']['mail_quota']. ' MB');
		redirectTo('admin_settings.php', array(
			's' => $s
		));
	}
	else
	{
		ask_yesno('admin_quotas_reallyenforce', $filename, array(
			'page' => $page
		));
	}
}

?>

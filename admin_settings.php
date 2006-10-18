<?php
/**
 * This file is part of the SysCP project.
 * Copyright (c) 2003-2006 the SysCP Team (see authors).
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
	require("./lib/init.php");

	if( ($page == 'settings' || $page == 'overview') && $userinfo['change_serversettings'] == '1')
	{
		if(isset($_POST['send']) && $_POST['send']=='send')
		{
			if($_POST['session_sessiontimeout']!=$settings['session']['sessiontimeout'])
			{
				$value=validate($_POST['session_sessiontimeout'], 'session timeout', '/^[0-9]+$/', 'sessiontimeoutiswrong');
				$db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='".$db->escape($value)."' WHERE `settinggroup`='session' AND `varname`='sessiontimeout'");
			}

			if($_POST['login_maxloginattempts']!=$settings['login']['maxloginattempts'])
			{
				$value=validate($_POST['login_maxloginattempts'], 'max login attempts', '/^[0-9]+$/', 'maxloginattemptsiswrong');
				$db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='".$db->escape($value)."' WHERE `settinggroup`='login' AND `varname`='maxloginattempts'");
			}

			if($_POST['login_deactivatetime']!=$settings['login']['deactivatetime'])
			{
				$value=validate($_POST['login_deactivatetime'], 'deactivate time', '/^[0-9]+$/', 'deactivatetimiswrong');
				$db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='".$db->escape($value)."' WHERE `settinggroup`='login' AND `varname`='deactivatetime'");
			}

			if($_POST['customer_accountprefix']!=$settings['customer']['accountprefix'])
			{
				$value=$_POST['customer_accountprefix'];
				if(check_username_prefix($value))
				{
					$db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='".$db->escape($value)."' WHERE `settinggroup`='customer' AND `varname`='accountprefix'");
				}
				else
				{
					standard_error('accountprefixiswrong');
					exit;
				}
			}

			if($_POST['customer_mysqlprefix']!=$settings['customer']['mysqlprefix'])
			{
				$value=$_POST['customer_mysqlprefix'];
				if(check_mysql_prefix($value))
				{
					$db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='".$db->escape($value)."' WHERE `settinggroup`='customer' AND `varname`='mysqlprefix'");
				}
				else
				{
					standard_error('mysqlprefixiswrong');
					exit;
				}
			}

			if($_POST['customer_ftpprefix']!=$settings['customer']['ftpprefix'])
			{
				$value=$_POST['customer_ftpprefix'];
				if(check_username_prefix($value))
				{
					$db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='".$db->escape($value)."' WHERE `settinggroup`='customer' AND `varname`='ftpprefix'");
				}
				else
				{
					standard_error('ftpprefixiswrong');
					exit;
				}
			}

			if($_POST['system_documentroot_prefix']!=$settings['system']['documentroot_prefix'])
			{
				$value=validate($_POST['system_documentroot_prefix'], 'documentroot prefix');
				$value=makeCorrectDir($value);
				$db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='".$db->escape($value)."' WHERE `settinggroup`='system' AND `varname`='documentroot_prefix'");
			}

			if($_POST['system_logfiles_directory']!=$settings['system']['logfiles_directory'])
			{
				$value=validate($_POST['system_logfiles_directory'], 'logfiles directory');
				$value=makeCorrectDir($value);
				$db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='".$db->escape($value)."' WHERE `settinggroup`='system' AND `varname`='logfiles_directory'");
			}

			if($_POST['system_ipaddress']!=$settings['system']['ipaddress'])
			{
				$value=$_POST['system_ipaddress'];

				$result_ipandport = $db->query("SELECT `id`, `ip`, `port` FROM `".TABLE_PANEL_IPSANDPORTS."` WHERE `ip`='".$db->escape($value)."'");

				if( $db->num_rows( $result_ipandport ) == 0 )
				{
					standard_error('ipiswrong');
					exit;
				}

				$db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='".$db->escape($value)."' WHERE `settinggroup`='system' AND `varname`='ipaddress'");

				inserttask('1');

				if($sql['host'] != 'localhost' && $sql['host'] != '127.0.0.1')
				{
					$mysql_access_host = $value;

					// Connect as root to change hostnames from where the server will be accessed to new ip
					$db_root = new db($sql['host'],$sql['root_user'],$sql['root_password']);

					// Update our sql unprivileged and privileged (root) user
					$update_users = '"' . $db->escape($sql['user']) . '", "' . $db->escape($sql['root_user']) . '"' ;

					// Update all customer databases
					$databases = $db->query('SELECT `databasename` FROM `' . TABLE_PANEL_DATABASES . '`');
					while ( $database = $db->fetch_array ( $databases ) )
					{
						$update_users .= ', "' . $db->escape($database['databasename']) .'"' ;
					}

					// Do the update
					$db_root->query("UPDATE `mysql`.`user` SET `HOST` = '".$db_root->escape($mysql_access_host)."' WHERE `User` IN (" . $update_users . ") AND `Host` = '" . $db_root->escape($settings['system']['mysql_access_host']) . "'");
					$db_root->query("UPDATE `mysql`.`db` SET `HOST` = '".$db_root->escape($mysql_access_host)."' WHERE `User` IN (" . $update_users . ") AND `Host` = '" . $db_root->escape($settings['system']['mysql_access_host']) . "'");
					$db_root->query("UPDATE `mysql`.`tables_priv` SET `HOST` = '".$db_root->escape($mysql_access_host)."' WHERE `User` IN (" . $update_users . ") AND `Host` = '" . $db_root->escape($settings['system']['mysql_access_host']) . "'");
					$db_root->query("UPDATE `mysql`.`columns_priv` SET `HOST` = '".$db_root->escape($mysql_access_host)."' WHERE `User` IN (" . $update_users . ") AND `Host` = '" . $db_root->escape($settings['system']['mysql_access_host']) . "'");

					// Clean up and disconnect
					$db_root->query("FLUSH PRIVILEGES;");
					$db_root->close();
					unset($db_root);

					$db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='".$db->escape($mysql_access_host)."' WHERE `settinggroup`='system' AND `varname`='mysql_access_host'");
				}
			}

			if($_POST['system_defaultip']!=$settings['system']['defaultip'])
			{
				$value=$_POST['system_defaultip'];

				$result_ipandport = $db->query("SELECT `id`, `ip`, `port` FROM `".TABLE_PANEL_IPSANDPORTS."` WHERE `id`='".(int)$value."'");

				if( $db->num_rows( $result_ipandport ) == 0 )
				{
					standard_error('ipiswrong');
					exit;
				}

				$customerstddomains = $db->query('SELECT `d`.`id` FROM `'.TABLE_PANEL_CUSTOMERS.'` `c` LEFT JOIN `'.TABLE_PANEL_DOMAINS.'` `d` ON `d`.`id` = `c`.`standardsubdomain` WHERE `c`.`standardsubdomain` <> \'0\' && `d`.`ipandport` = \''.$db->escape($settings['system']['defaultip']).'\'');
				$ids = array();
				while($row = $db->fetch_array($customerstddomains))
				{
					$ids[] = (int)$row['id'];
				}
				if(count($ids) > 0)
				{
					$db->query('UPDATE `'.TABLE_PANEL_DOMAINS.'` SET `ipandport`=\''.(int)$value.'\' WHERE `id` IN (\''.join("','",$ids).'\')');
				}

				$db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='".(int)$value."' WHERE `settinggroup`='system' AND `varname`='defaultip'");
			}

			if($_POST['system_hostname']!=$settings['system']['hostname'])
			{
				$value=$idna_convert->encode(validate($_POST['system_hostname'], 'hostname'));
				$db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='".$db->escape($value)."' WHERE `settinggroup`='system' AND `varname`='hostname'");
				$result=$db->query('SELECT `standardsubdomain` FROM `'.TABLE_PANEL_CUSTOMERS.'` WHERE `standardsubdomain`!=\'0\'');
				$domains=array();
				while(($row=$db->fetch_array($result)) !== false)
				{
					$domains[]='\''.$db->escape($row['standardsubdomain']).'\'';
				}
				if(count($domains) > 0) {
					$domains=join($domains,',');
					$db->query('UPDATE `'.TABLE_PANEL_DOMAINS.'` SET `domain`=REPLACE(`domain`,\''.$db->escape($settings['system']['hostname']).'\',\''.$db->escape($value).'\') WHERE `id` IN ('.$domains.')');
					inserttask('1');
				}
			}

			if($_POST['system_apacheconf_directory']!=$settings['system']['apacheconf_directory'])
			{
				$value=validate($_POST['system_apacheconf_directory'], 'apacheconf directory');
				$value=makeCorrectDir($value);
				$db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='".$db->escape($value)."' WHERE `settinggroup`='system' AND `varname`='apacheconf_directory'");
				inserttask('1');
			}

			if($_POST['system_apacheconf_filename']!=$settings['system']['apacheconf_filename'])
			{
				$value=validate($_POST['system_apacheconf_filename'], 'apacheconf filename', '/^[a-z0-9\._\/]+$/i');
				$db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='".$db->escape($value)."' WHERE `settinggroup`='system' AND `varname`='apacheconf_filename'");
				inserttask('1');
			}

			if($_POST['system_apachereload_command']!=$settings['system']['apachereload_command'])
			{
				$value=validate($_POST['system_apachereload_command'], 'apache reload command');
				$db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='".$db->escape($value)."' WHERE `settinggroup`='system' AND `varname`='apachereload_command'");
				inserttask('1');
			}

			if($_POST['system_bindconf_directory']!=$settings['system']['bindconf_directory'])
			{
				$value=validate($_POST['system_bindconf_directory'], 'bind conf directory');
				$value=makeCorrectDir($value);
				$db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='".$db->escape($value)."' WHERE `settinggroup`='system' AND `varname`='bindconf_directory'");
			}

			if($_POST['system_bindreload_command']!=$settings['system']['bindreload_command'])
			{
				$value=validate($_POST['system_bindreload_command'], 'bind reload command');
				$db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='".$db->escape($value)."' WHERE `settinggroup`='system' AND `varname`='bindreload_command'");
			}

			if($_POST['system_binddefaultzone']!=$settings['system']['binddefaultzone'])
			{
				$value=validate($_POST['system_binddefaultzone'], 'bind default zone', '/^[a-z0-9\-_]+$/i');
				$db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='".$db->escape($value)."' WHERE `settinggroup`='system' AND `varname`='binddefaultzone'");
			}

			if($_POST['system_vmail_uid']!=$settings['system']['vmail_uid'])
			{
				$value=validate($_POST['system_vmail_uid'], 'vmail uid', '/^[0-9]{1,5}$/', 'vmailuidiswrong');
				$db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='".(int)$value."' WHERE `settinggroup`='system' AND `varname`='vmail_uid'");
			}

			if($_POST['system_vmail_gid']!=$settings['system']['vmail_gid'])
			{
				$value=validate($_POST['system_vmail_gid'], 'vmail gid', '/^[0-9]{1,5}$/', 'vmailgidiswrong');
				$db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='".(int)$value."' WHERE `settinggroup`='system' AND `varname`='vmail_gid'");
			}

			if($_POST['system_vmail_homedir']!=$settings['system']['vmail_homedir'])
			{
				$value=validate($_POST['system_vmail_homedir'], 'vmail homedir');
				$value=makeCorrectDir($value);
				$db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='".$db->escape($value)."' WHERE `settinggroup`='system' AND `varname`='vmail_homedir'");
			}

			if($_POST['panel_adminmail']!=$settings['panel']['adminmail'])
			{
				$value=$idna_convert->encode($_POST['panel_adminmail']);
				if(!verify_email($value))
				{
					standard_error('adminmailiswrong');
					exit;
				}
				$db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='".$db->escape($value)."' WHERE `settinggroup`='panel' AND `varname`='adminmail'");
			}

			if($_POST['panel_paging']!=$settings['panel']['paging'])
			{
				$value=validate($_POST['panel_paging'], 'paging', '/^[0-9]+$/', 'pagingiswrong');
				$db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='".(int)$value."' WHERE `settinggroup`='panel' AND `varname`='paging'");
			}

			if($_POST['panel_standardlanguage']!=$settings['panel']['standardlanguage'])
			{
				$value = $_POST['panel_standardlanguage'];
				if( !in_array( $value, $languages ) )
				{
					standard_error( 'stringformaterror', 'standard language' );
					exit;
				}
				$db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='".$db->escape($value)."' WHERE `settinggroup`='panel' AND `varname`='standardlanguage'");
			}

			if($_POST['panel_pathedit']!=$settings['panel']['pathedit'])
			{
				$value = validate($_POST['panel_pathedit'], 'path edit', '/^(?:Manual|Dropdown)$/');
				$db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='".$db->escape($value)."' WHERE `settinggroup`='panel' AND `varname`='pathedit'");
			}

			if($_POST['panel_phpmyadmin_url']!=$settings['panel']['phpmyadmin_url'])
			{
				$value=$_POST['panel_phpmyadmin_url'];
				if(!verify_url($value) && $value != '')
				{
					standard_error('phpmyadminiswrong');
					exit;
				}
				if ($settings['panel']['phpmyadmin_url'] != '')
				{
					// delete or update menu
					if ($value == '')
					{
						//delete
						$query =
							'DELETE FROM `'.TABLE_PANEL_NAVIGATION.'` ' .
							'WHERE `lang` = "menue;mysql;phpmyadmin"';
					}
					else
					{
						//update
						$query =
							'UPDATE `'.TABLE_PANEL_NAVIGATION.'` ' .
							'SET `url`="'.$db->escape($value).'" ' .
							'WHERE `lang` = "menue;mysql;phpmyadmin"';
					}
				}
				else
				{
					// insert into menu
					$query=
						'SELECT MAX(`order`) AS `max` '.
						'FROM `'.TABLE_PANEL_NAVIGATION.'` '.
						'WHERE `area`=\'customer\' AND `parent_url`=\'customer_mysql.php\'';
					$max=$db->query_first($query);
					$new=floor($max['max']/10)+10;

					$query =
						'INSERT INTO `'.TABLE_PANEL_NAVIGATION.'` ' .
						'SET `lang`       = "menue;mysql;phpmyadmin", ' .
						'    `url`        = "'.$db->escape($value).'", ' .
						'    `order`      = "'.(int)$new.'", ' .
						'    `area`       = "customer", ' .
						'    `new_window` = "1", ' .
						'    `required_resources` = "mysqls_used", ' .
						'    `parent_url` = "customer_mysql.php"';
				}
				$db->query($query);
				$db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='".$db->escape($value)."' WHERE `settinggroup`='panel' AND `varname`='phpmyadmin_url'");
			}

			if($_POST['panel_webmail_url']!=$settings['panel']['webmail_url'])
			{
				$value=$_POST['panel_webmail_url'];
				if(!verify_url($value) && $value != '')
				{
					standard_error('webmailiswrong');
					exit;
				}
				if ($settings['panel']['webmail_url'] != '')
				{
					// delete or update menu
					if ($value == '')
					{
						//delete
						$query =
							'DELETE FROM `'.TABLE_PANEL_NAVIGATION.'` ' .
							'WHERE `lang` = "menue;email;webmail"';
					}
					else
					{
						//update
						$query =
							'UPDATE `'.TABLE_PANEL_NAVIGATION.'` ' .
							'SET `url`="'.$db->escape($value).'" ' .
							'WHERE `lang` = "menue;email;webmail"';
					}
				}
				else
				{
					// insert into menu
					$query=
						'SELECT MAX(`order`) AS `max` '.
						'FROM `'.TABLE_PANEL_NAVIGATION.'` '.
						'WHERE `area`=\'customer\' AND `parent_url`=\'customer_email.php\'';
					$max=$db->query_first($query);
					$new=floor($max['max']/10)+10;

					$query =
						'INSERT INTO `'.TABLE_PANEL_NAVIGATION.'` ' .
						'SET `lang`       = "menue;email;webmail", ' .
						'    `url`        = "'.$db->escape($value).'", ' .
						'    `order`      = "'.(int)$new.'", ' .
						'    `area`       = "customer", ' .
						'    `new_window` = "1", ' .
						'    `required_resources` = "emails_used", ' .
						'    `parent_url` = "customer_email.php"';
				}
				$db->query($query);
				$db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='".$db->escape($value)."' WHERE `settinggroup`='panel' AND `varname`='webmail_url'");
			}

			if($_POST['panel_webftp_url']!=$settings['panel']['webftp_url'])
			{
				$value=$_POST['panel_webftp_url'];
				if(!verify_url($value) && $value != '')
				{
					standard_error('webftpiswrong');
					exit;
				}
				if ($settings['panel']['webftp_url'] != '')
				{
					// delete or update menu
					if ($value == '')
					{
						//delete
						$query =
							'DELETE FROM `'.TABLE_PANEL_NAVIGATION.'` ' .
							'WHERE `lang` = "menue;ftp;webftp"';
					}
					else
					{
						//update
						$query =
							'UPDATE `'.TABLE_PANEL_NAVIGATION.'` ' .
							'SET `url`="'.$db->escape($value).'" ' .
							'WHERE `lang` = "menue;ftp;webftp"';
					}
				}
				else
				{
					// insert into menu
					$query=
						'SELECT MAX(`order`) AS `max` '.
						'FROM `'.TABLE_PANEL_NAVIGATION.'` '.
						'WHERE `area`=\'customer\' AND `parent_url`=\'customer_ftp.php\'';
					$max=$db->query_first($query);
					$new=floor($max['max']/10)+10;

					$query =
						'INSERT INTO `'.TABLE_PANEL_NAVIGATION.'` ' .
						'SET `lang`       = "menue;ftp;webftp", ' .
						'    `url`        = "'.$db->escape($value).'", ' .
						'    `order`      = "'.(int)$new.'", ' .
						'    `area`       = "customer", ' .
						'    `new_window` = "1", ' .
						'    `parent_url` = "customer_ftp.php"';
				}
				$db->query($query);
				$db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='".$db->escape($value)."' WHERE `settinggroup`='panel' AND `varname`='webftp_url'");
			}

			redirectTo ( $filename , Array ( 'page' => $page , 's' => $s ) ) ;
		}
		else
		{
			// build the languages list
			$query =
				'SELECT * ' .
				'FROM `'.TABLE_PANEL_LANGUAGE.'` ';
			$result = $db->query($query);
			$languages_array = array();
			$languages = '';
			while ($row = $db->fetch_array($result))
			{
				if( !isset( $languages_array[$row['language']] ) && !in_array( $row['language'], $languages_array ) )
				{
					$languages_array[$row['language']] = $row['language'];
					$languages .= makeoption($row['language'], $row['language'], $settings['panel']['standardlanguage'], true);
				}
			}

			// build the IP addresses lists
			$result=$db->query('SELECT `id`, `ip`, `port` FROM `'.TABLE_PANEL_IPSANDPORTS.'` ORDER BY `ip` ASC, `port` ASC');
			$system_ipaddress_array=array();
			$system_ipaddress='';
			$system_defaultip='';
			while($row=$db->fetch_array($result))
			{
				if( !isset( $system_ipaddress_array[$row['ip']] ) && !in_array( $row['ip'], $system_ipaddress_array ) )
				{
					$system_ipaddress_array[$row['ip']] = $row['ip'];
					$system_ipaddress.=makeoption($row['ip'], $row['ip'], $settings['system']['ipaddress'], true, true);
				}
				$system_defaultip.=makeoption($row['ip'].':'.$row['port'], $row['id'], $settings['system']['defaultip'], true, true);
			}

			// build the pathedit list
			$pathedit='';
			foreach (array('Manual','Dropdown') as $method)
			{
				$pathedit .= makeoption($method, $method, $settings['panel']['pathedit'], true, true);
			}

			$settings = htmlentities_array( $settings );
			eval("echo \"".getTemplate("settings/settings")."\";");
		}
	}

	elseif ( $page == 'rebuildconfigs' && $userinfo['change_serversettings'] == '1')
	{
		if ( isset( $_POST['send'] ) && $_POST['send'] == 'send' )
		{
			inserttask('1');
			inserttask('4');
			redirectTo ( 'admin_index.php' , array( 's' => $s ) ) ;
		}
		else
		{
			ask_yesno('admin_configs_reallyrebuild', $filename, array( 'page' => $page ) );
		}
	}

?>

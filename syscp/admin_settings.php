<?php
/**
 * filename: $Source$
 * begin: Friday, Aug 06, 2004
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
 * @package Panel
 * @version $Id$
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
				$value=addslashes($_POST['session_sessiontimeout']);
				if(!preg_match("/^[0-9]+$/", $value))
				{
					standard_error('sessiontimeoutiswrong');
					exit;
				}
				$db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='$value' WHERE `settinggroup`='session' AND `varname`='sessiontimeout'");
			}

			if($_POST['login_maxloginattempts']!=$settings['login']['maxloginattempts'])
			{
				$value=addslashes($_POST['login_maxloginattempts']);
				if(!preg_match("/^[0-9]+$/", $value))
				{
					standard_error('maxloginattemptsiswrong');
					exit;
				}
				$db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='$value' WHERE `settinggroup`='login' AND `varname`='maxloginattempts'");
			}

			if($_POST['login_deactivatetime']!=$settings['login']['deactivatetime'])
			{
				$value=addslashes($_POST['login_deactivatetime']);
				if(!preg_match("/^[0-9]+$/", $value))
				{
					standard_error('deactivatetimiswrong');
					exit;
				}
				$db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='$value' WHERE `settinggroup`='login' AND `varname`='deactivatetime'");
			}

			if($_POST['customer_accountprefix']!=$settings['customer']['accountprefix'])
			{
				$value=addslashes($_POST['customer_accountprefix']);
				if(check_username_prefix($value))
				{
					$db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='$value' WHERE `settinggroup`='customer' AND `varname`='accountprefix'");
				}
				else
				{
					standard_error('accountprefixiswrong');
					exit;
				}
			}

			if($_POST['customer_mysqlprefix']!=$settings['customer']['mysqlprefix'])
			{
				$value=addslashes($_POST['customer_mysqlprefix']);
				if(check_mysql_prefix($value))
				{
					$db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='$value' WHERE `settinggroup`='customer' AND `varname`='mysqlprefix'");
				}
				else
				{
					standard_error('mysqlprefixiswrong');
					exit;
				}
			}

			if($_POST['customer_ftpprefix']!=$settings['customer']['ftpprefix'])
			{
				$value=addslashes($_POST['customer_ftpprefix']);
				if(check_username_prefix($value))
				{
					$db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='$value' WHERE `settinggroup`='customer' AND `varname`='ftpprefix'");
				}
				else
				{
					standard_error('ftpprefixiswrong');
					exit;
				}
			}

			if($_POST['system_documentroot_prefix']!=$settings['system']['documentroot_prefix'])
			{
				$value=addslashes($_POST['system_documentroot_prefix']);
				$value=makeCorrectDir($value);
				$db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='$value' WHERE `settinggroup`='system' AND `varname`='documentroot_prefix'");
			}

			if($_POST['system_logfiles_directory']!=$settings['system']['logfiles_directory'])
			{
				$value=addslashes($_POST['system_logfiles_directory']);
				$value=makeCorrectDir($value);
				$db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='$value' WHERE `settinggroup`='system' AND `varname`='logfiles_directory'");
			}

			if($_POST['system_ipaddress']!=$settings['system']['ipaddress'])
			{
				$value=addslashes($_POST['system_ipaddress']);

				$result_ipandport = $db->query("SELECT `id`, `ip`, `port` FROM `".TABLE_PANEL_IPSANDPORTS."` WHERE `ip`='".$value."'");

				if( $db->num_rows( $result_ipandport ) == 0 )
				{
					standard_error('ipiswrong');
					exit;
				}

				$db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='$value' WHERE `settinggroup`='system' AND `varname`='ipaddress'");

				inserttask('1');

				if($sql['host'] != 'localhost')
				{
					$mysql_access_host = $value;

					// Connect as root to change hostnames from where the server will be accessed to new ip
					$db_root = new db($sql['host'],$sql['root_user'],$sql['root_password']);

					// Update our sql unprivileged and privileged (root) user
					$update_users = '"' . $sql['user'] . '", "' . $sql['root_user'] . '"' ;

					// Update all customer databases
					$databases = $db->query('SELECT `databasename` FROM `' . TABLE_PANEL_DATABASES . '`;');
					while ( $database = $db->fetch_array ( $databases ) )
					{
						$update_users .= ', "' . $database['databasename'] .'"' ;
					}

					// Do the update
					$db_root->query("UPDATE `mysql`.`user` SET `HOST` = '$mysql_access_host' WHERE `User` IN (" . $update_users . ") AND `Host` = '" . $settings['system']['mysql_access_host'] . "';");
					$db_root->query("UPDATE `mysql`.`db` SET `HOST` = '$mysql_access_host' WHERE `User` IN (" . $update_users . ") AND `Host` = '" . $settings['system']['mysql_access_host'] . "';");
					$db_root->query("UPDATE `mysql`.`tables_priv` SET `HOST` = '$mysql_access_host' WHERE `User` IN (" . $update_users . ") AND `Host` = '" . $settings['system']['mysql_access_host'] . "';");
					$db_root->query("UPDATE `mysql`.`columns_priv` SET `HOST` = '$mysql_access_host' WHERE `User` IN (" . $update_users . ") AND `Host` = '" . $settings['system']['mysql_access_host'] . "';");

					// Clean up and disconnect
					$db_root->query("FLUSH PRIVILEGES;");
					$db_root->close();
					unset($db_root);

					$db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='$mysql_access_host' WHERE `settinggroup`='system' AND `varname`='mysql_access_host'");
				}
			}

			if($_POST['system_defaultip']!=$settings['system']['defaultip'])
			{
				$value=addslashes($_POST['system_defaultip']);

				$result_ipandport = $db->query("SELECT `id`, `ip`, `port` FROM `".TABLE_PANEL_IPSANDPORTS."` WHERE `id`='".$value."'");

				if( $db->num_rows( $result_ipandport ) == 0 )
				{
					standard_error('ipiswrong');
					exit;
				}

				$customerstddomains = $db->query('SELECT `d`.`id` FROM `'.TABLE_PANEL_CUSTOMERS.'` `c` LEFT JOIN `'.TABLE_PANEL_DOMAINS.'` `d` ON `d`.`id` = `c`.`standardsubdomain` WHERE `c`.`standardsubdomain` <> \'0\' && `d`.`ipandport` = \''.$settings['system']['defaultip'].'\' ');
				$ids = array();
				while($row = $db->fetch_array($customerstddomains))
				{
					$ids[] = $row['id'];
				}
				if(count($ids) > 0)
				{
					$db->query('UPDATE `'.TABLE_PANEL_DOMAINS.'` SET `ipandport`=\''.$value.'\' WHERE `id` IN ('.join(',',$ids).')');
				}

				$db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='$value' WHERE `settinggroup`='system' AND `varname`='defaultip'");
			}

			if($_POST['system_hostname']!=$settings['system']['hostname'])
			{
				$value=addslashes($idna_convert->encode($_POST['system_hostname']));
				$db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='$value' WHERE `settinggroup`='system' AND `varname`='hostname'");
				$result=$db->query('SELECT `standardsubdomain` FROM `'.TABLE_PANEL_CUSTOMERS.'` WHERE `standardsubdomain`!=\'0\'');
				$domains=array();
				while(($row=$db->fetch_array($result)) !== false)
				{
					$domains[]='\''.$row['standardsubdomain'].'\'';
				}
				if(count($domains) > 0) {
					$domains=join($domains,',');
					$db->query('UPDATE `'.TABLE_PANEL_DOMAINS.'` SET `domain`=REPLACE(`domain`,\''.$settings['system']['hostname'].'\',\''.$value.'\') WHERE `id` IN ('.$domains.')');
					inserttask('1');
				}
			}

			if($_POST['system_apacheconf_directory']!=$settings['system']['apacheconf_directory'])
			{
				$value=addslashes($_POST['system_apacheconf_directory']);
				$value=makeCorrectDir($value);
				$db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='$value' WHERE `settinggroup`='system' AND `varname`='apacheconf_directory'");
				inserttask('1');
			}

			if($_POST['system_apacheconf_filename']!=$settings['system']['apacheconf_filename'])
			{
				$value=addslashes($_POST['system_apacheconf_filename']);
				$value=htmlentities($value);
				$db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='$value' WHERE `settinggroup`='system' AND `varname`='apacheconf_filename'");
				inserttask('1');
			}

			if($_POST['system_apachereload_command']!=$settings['system']['apachereload_command'])
			{
				$value=addslashes($_POST['system_apachereload_command']);
				$db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='$value' WHERE `settinggroup`='system' AND `varname`='apachereload_command'");
				inserttask('1');
			}

			if($_POST['system_bindconf_directory']!=$settings['system']['bindconf_directory'])
			{
				$value=addslashes($_POST['system_bindconf_directory']);
				$value=makeCorrectDir($value);
				$db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='$value' WHERE `settinggroup`='system' AND `varname`='bindconf_directory'");
			}

			if($_POST['system_bindreload_command']!=$settings['system']['bindreload_command'])
			{
				$value=addslashes($_POST['system_bindreload_command']);
				$db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='$value' WHERE `settinggroup`='system' AND `varname`='bindreload_command'");
			}

			if($_POST['system_binddefaultzone']!=$settings['system']['binddefaultzone'])
			{
				$value=addslashes($_POST['system_binddefaultzone']);
				$value=htmlentities($value);
				$db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='$value' WHERE `settinggroup`='system' AND `varname`='binddefaultzone'");
			}

			if($_POST['system_vmail_uid']!=$settings['system']['vmail_uid'])
			{
				$value=addslashes($_POST['system_vmail_uid']);
				if(!preg_match("/^[0-9]{1,5}$/", $value))
				{
					standard_error('vmailuidiswrong');
					exit;
				}
				$db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='$value' WHERE `settinggroup`='system' AND `varname`='vmail_uid'");
			}

			if($_POST['system_vmail_gid']!=$settings['system']['vmail_gid'])
			{
				$value=addslashes($_POST['system_vmail_gid']);
				if(!preg_match("/^[0-9]{1,5}$/", $value))
				{
					standard_error('vmailgidiswrong');
					exit;
				}
				$db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='$value' WHERE `settinggroup`='system' AND `varname`='vmail_gid'");
			}

			if($_POST['system_vmail_homedir']!=$settings['system']['vmail_homedir'])
			{
				$value=addslashes($_POST['system_vmail_homedir']);
				$value=makeCorrectDir($value);
				$db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='$value' WHERE `settinggroup`='system' AND `varname`='vmail_homedir'");
			}

			if($_POST['panel_adminmail']!=$settings['panel']['adminmail'])
			{
				$value=addslashes($idna_convert->encode($_POST['panel_adminmail']));
				if(!verify_email($value))
				{
					standard_error('adminmailiswrong');
					exit;
				}
				$db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='$value' WHERE `settinggroup`='panel' AND `varname`='adminmail'");
			}

			if($_POST['panel_paging']!=$settings['panel']['paging'])
			{
				$value=intval($_POST['panel_paging']);
				if(!preg_match("/^[0-9]+$/", $value))
				{
					standard_error('pagingiswrong');
					exit;
				}
				$db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='$value' WHERE `settinggroup`='panel' AND `varname`='paging'");
			}

			if($_POST['panel_standardlanguage']!=$settings['panel']['standardlanguage'])
			{
				$value = addslashes ( htmlentities ( _html_entity_decode ( $_POST['panel_standardlanguage'] ) ) ) ;
				$db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='$value' WHERE `settinggroup`='panel' AND `varname`='standardlanguage'");
			}

			if($_POST['panel_pathedit']!=$settings['panel']['pathedit'])
			{
				$value = addslashes ( htmlentities ( _html_entity_decode ( $_POST['panel_pathedit'] ) ) ) ;
				$db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='$value' WHERE `settinggroup`='panel' AND `varname`='pathedit'");
			}

			if($_POST['panel_phpmyadmin_url']!=$settings['panel']['phpmyadmin_url'])
			{
				$value=addslashes($_POST['panel_phpmyadmin_url']);
				if(!preg_match("/^https?\:\/\//", $value) && $value != '')
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
							'SET `url`="'.$value.'" ' .
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
						'    `url`        = "'.$value.'", ' .
						'    `order`      = "'.$new.'", ' .
						'    `area`       = "customer", ' .
						'    `new_window` = "1", ' .
						'    `required_resources` = "mysqls_used", ' .
						'    `parent_url` = "customer_mysql.php"';
				}
				$db->query($query);
				$db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='$value' WHERE `settinggroup`='panel' AND `varname`='phpmyadmin_url'");
			}

			if($_POST['panel_webmail_url']!=$settings['panel']['webmail_url'])
			{
				$value=addslashes($_POST['panel_webmail_url']);
				if(!preg_match("/^https?\:\/\//", $value) && $value != '')
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
							'SET `url`="'.$value.'" ' .
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
						'    `url`        = "'.$value.'", ' .
						'    `order`      = "'.$new.'", ' .
						'    `area`       = "customer", ' .
						'    `new_window` = "1", ' .
						'    `required_resources` = "emails_used", ' .
						'    `parent_url` = "customer_email.php"';
				}
				$db->query($query);
				$db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='$value' WHERE `settinggroup`='panel' AND `varname`='webmail_url'");
			}

			if($_POST['panel_webftp_url']!=$settings['panel']['webftp_url'])
			{
				$value=addslashes($_POST['panel_webftp_url']);
				if(!preg_match("/^https?\:\/\//", $value) && $value != '')
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
							'SET `url`="'.$value.'" ' .
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
						'    `url`        = "'.$value.'", ' .
						'    `order`      = "'.$new.'", ' .
						'    `area`       = "customer", ' .
						'    `new_window` = "1", ' .
						'    `parent_url` = "customer_ftp.php"';
				}
				$db->query($query);
				$db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='$value' WHERE `settinggroup`='panel' AND `varname`='webftp_url'");
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
					$languages .= makeoption($row['language'],$row['language'],$settings['panel']['standardlanguage']);
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
					$system_ipaddress.=makeoption($row['ip'],$row['ip'],$settings['system']['ipaddress']);
				}
				$system_defaultip.=makeoption($row['ip'].':'.$row['port'],$row['id'],$settings['system']['defaultip']);
			}

			// build the pathedit list
			$pathedit='';
			foreach (array('Manual','Dropdown') as $method)
			{
				$pathedit .= makeoption($method, $method, $settings['panel']['pathedit']);
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

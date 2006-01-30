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
//				echo 'session_sessiontimeout<br />';
				$value=addslashes($_POST['session_sessiontimeout']);
				$db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='$value' WHERE `settinggroup`='session' AND `varname`='sessiontimeout'");
			}

			if($_POST['login_maxloginattempts']!=$settings['login']['maxloginattempts'])
			{
				$value=addslashes($_POST['login_maxloginattempts']);
				$db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='$value' WHERE `settinggroup`='login' AND `varname`='maxloginattempts'");
			}

			if($_POST['login_deactivatetime']!=$settings['login']['deactivatetime'])
			{
				$value=addslashes($_POST['login_deactivatetime']);
				$db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='$value' WHERE `settinggroup`='login' AND `varname`='deactivatetime'");
			}

			if($_POST['customer_accountprefix']!=$settings['customer']['accountprefix'])
			{
//				echo 'customer_accountprefix<br />';
				$value=addslashes($_POST['customer_accountprefix']);
				if(check_username_prefix($value))
				{
					$db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='$value' WHERE `settinggroup`='customer' AND `varname`='accountprefix'");
				}
			}

			if($_POST['customer_mysqlprefix']!=$settings['customer']['mysqlprefix'])
			{
//				echo 'customer_mysqlprefix<br />';
				$value=addslashes($_POST['customer_mysqlprefix']);
				if(check_mysql_prefix($value))
				{
					$db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='$value' WHERE `settinggroup`='customer' AND `varname`='mysqlprefix'");
				}
			}

			if($_POST['customer_ftpprefix']!=$settings['customer']['ftpprefix'])
			{
//				echo 'customer_ftpprefix<br />';
				$value=addslashes($_POST['customer_ftpprefix']);
				if(check_username_prefix($value))
				{
					$db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='$value' WHERE `settinggroup`='customer' AND `varname`='ftpprefix'");
				}
			}

			if($_POST['system_documentroot_prefix']!=$settings['system']['documentroot_prefix'])
			{
//				echo 'system_documentroot_prefix<br />';
				$value=addslashes($_POST['system_documentroot_prefix']);
				$value=makeCorrectDir($value);
				$db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='$value' WHERE `settinggroup`='system' AND `varname`='documentroot_prefix'");
			}

			if($_POST['system_logfiles_directory']!=$settings['system']['logfiles_directory'])
			{
//				echo 'system_logfiles_directory<br />';
				$value=addslashes($_POST['system_logfiles_directory']);
				$value=makeCorrectDir($value);
				$db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='$value' WHERE `settinggroup`='system' AND `varname`='logfiles_directory'");
			}

			if($_POST['system_ipaddress']!=$settings['system']['ipaddress'])
			{
//				echo 'system_ipaddress<br />';
				$value=addslashes($_POST['system_ipaddress']);
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

			if($_POST['system_hostname']!=$settings['system']['hostname'])
			{
//				echo 'system_hostname<br />';
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
//				echo 'system_apacheconf_directory<br />';
				$value=addslashes($_POST['system_apacheconf_directory']);
				$value=makeCorrectDir($value);
				$db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='$value' WHERE `settinggroup`='system' AND `varname`='apacheconf_directory'");
				inserttask('1');
			}

			if($_POST['system_apachereload_command']!=$settings['system']['apachereload_command'])
			{
//				echo 'system_apachereload_command<br />';
				$value=addslashes($_POST['system_apachereload_command']);
				$db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='$value' WHERE `settinggroup`='system' AND `varname`='apachereload_command'");
				inserttask('1');
			}

			if($_POST['system_bindconf_directory']!=$settings['system']['bindconf_directory'])
			{
//				echo 'system_bindconf_directory<br />';
				$value=addslashes($_POST['system_bindconf_directory']);
				$value=makeCorrectDir($value);
				$db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='$value' WHERE `settinggroup`='system' AND `varname`='bindconf_directory'");
			}

			if($_POST['system_bindreload_command']!=$settings['system']['bindreload_command'])
			{
//				echo 'system_bindreload_command<br />';
				$value=addslashes($_POST['system_bindreload_command']);
				$db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='$value' WHERE `settinggroup`='system' AND `varname`='bindreload_command'");
			}

			if($_POST['system_binddefaultzone']!=$settings['system']['binddefaultzone'])
			{
//				echo 'system_binddefaultzone<br />';
				$value=addslashes($_POST['system_binddefaultzone']);
				$value=htmlentities($value);
				$db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='$value' WHERE `settinggroup`='system' AND `varname`='binddefaultzone'");
			}

			if($_POST['system_vmail_uid']!=$settings['system']['vmail_uid'])
			{
//				echo 'system_vmail_uid<br />';
				$value=addslashes($_POST['system_vmail_uid']);
				$db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='$value' WHERE `settinggroup`='system' AND `varname`='vmail_uid'");
			}

			if($_POST['system_vmail_gid']!=$settings['system']['vmail_gid'])
			{
//				echo 'system_vmail_gid<br />';
				$value=addslashes($_POST['system_vmail_gid']);
				$db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='$value' WHERE `settinggroup`='system' AND `varname`='vmail_gid'");
			}

			if($_POST['system_vmail_homedir']!=$settings['system']['vmail_homedir'])
			{
//				echo 'system_vmail_homedir<br />';
				$value=addslashes($_POST['system_vmail_homedir']);
				$value=makeCorrectDir($value);
				$db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='$value' WHERE `settinggroup`='system' AND `varname`='vmail_homedir'");
			}

			if($_POST['panel_adminmail']!=$settings['panel']['adminmail'])
			{
//				echo 'panel_adminmail<br />';
				$value=addslashes($idna_convert->encode($_POST['panel_adminmail']));
				$db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='$value' WHERE `settinggroup`='panel' AND `varname`='adminmail'");
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
//				echo 'panel_phpmyadmin_url<br />';
				$value=addslashes($_POST['panel_phpmyadmin_url']);
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
//				echo 'panel_webmail_url<br />';
				$value=addslashes($_POST['panel_webmail_url']);
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
//				echo 'panel_webftp_url<br />';
				$value=addslashes($_POST['panel_webftp_url']);
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
			// query the whole table
			$query =
				'SELECT * ' .
 				'FROM `'.TABLE_PANEL_LANGUAGE.'` ';
 			$result = $db->query($query);
 			// presort languages
			$langs2 = "";
 			while ($row = $db->fetch_array($result))
 			{
				$langs2[$row['language']] = $row['language'];
 			} 
			// buildup $languages2 for the login screen
			$languages2 = "";
			foreach ($langs2 as $key => $value)
 			{
				$languages2 .= makeoption($key,$value,$settings['panel']['standardlanguage']);
 			}
 
 			foreach (array('Manual','Dropdown') as $method)
			{
				$pathedit .= makeoption($method, $method, $settings['panel']['pathedit']);
			}
			
			eval("echo \"".getTemplate("settings/settings")."\";");
		}
	}

	elseif ( $page == 'rebuildconfigs' && $userinfo['change_serversettings'] == '1')
	{
		if ( $_POST['send'] && $_POST['send'] == 'send' )
		{
			inserttask('1');
			inserttask('4');
			redirectTo ( 'admin_index.php' , array( 's' => $s ) ) ;
		}
		else
		{
			ask_yesno('admin_configs_reallyrebuild', $filename, 'id='.$id.';page='.$page );
		}
			
	}
	
?>

<?php
/**
 * This file is part of the SysCP project. 
 * Copyright (c) 2003-2006 the SysCP Project. 
 * 
 * For the full copyright and license information, please view the COPYING 
 * file that was distributed with this source code. You can also view the
 * COPYING file online at http://files.syscp.org/misc/COPYING.txt
 * 
 * @author     Florian Lippert <flo@redenswert.de>
 * @copyright  (c) 2003-2006 Florian Lippert
 * @package    Org.Syscp.Core
 * @subpackage Panel.Admin
 * @license    GPLv2 http://files.syscp.org/misc/COPYING.txt
 * @version    $Id:admin_settings.php 460 2006-04-23 15:07:49 +0200 (So, 23 Apr 2006) martin $
 * 
 * @todo code cleanup is needed, exspecially in queries, loops, conditions
 * @todo code restructuring is needed, we need to seperate view, controller and model logic,
 *       as a preparation towards MVC patterns
 */
	define('AREA', 'admin');

	/**
	 * Include our init.php, which manages Sessions, Language etc.
	 */
	require_once '../lib/init.php';

	if( ($config->get('env.page') == 'settings' || $config->get('env.page') == 'overview') && $userinfo['change_serversettings'] == '1')
	{
		if(isset($_POST['send']) && $_POST['send']=='send')
		{
			if($_POST['session_sessiontimeout']!=$config->get('session.sessiontimeout'))
			{
				$value=addslashes($_POST['session_sessiontimeout']);
				$db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='$value' WHERE `settinggroup`='session' AND `varname`='sessiontimeout'");
			}

			if($_POST['login_maxloginattempts']!=$config->get('login.maxloginattempts'))
			{
				$value=addslashes($_POST['login_maxloginattempts']);
				$db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='$value' WHERE `settinggroup`='login' AND `varname`='maxloginattempts'");
			}

			if($_POST['login_deactivatetime']!=$config->get('login.deactivatetime'))
			{
				$value=addslashes($_POST['login_deactivatetime']);
				$db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='$value' WHERE `settinggroup`='login' AND `varname`='deactivatetime'");
			}

			if($_POST['customer_accountprefix']!=$config->get('customer.accountprefix'))
			{
//				echo 'customer_accountprefix<br />';
				$value=addslashes($_POST['customer_accountprefix']);
				if(check_username_prefix($value))
				{
					$db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='$value' WHERE `settinggroup`='customer' AND `varname`='accountprefix'");
				}
			}

			if($_POST['customer_mysqlprefix']!=$config->get('customer.mysqlprefix'))
			{
//				echo 'customer_mysqlprefix<br />';
				$value=addslashes($_POST['customer_mysqlprefix']);
				if(check_mysql_prefix($value))
				{
					$db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='$value' WHERE `settinggroup`='customer' AND `varname`='mysqlprefix'");
				}
			}

			if($_POST['customer_ftpprefix']!=$config->get('customer.ftpprefix'))
			{
//				echo 'customer_ftpprefix<br />';
				$value=addslashes($_POST['customer_ftpprefix']);
				if(check_username_prefix($value))
				{
					$db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='$value' WHERE `settinggroup`='customer' AND `varname`='ftpprefix'");
				}
			}

			if($_POST['system_documentroot_prefix']!=$config->get('system.documentroot_prefix'))
			{
//				echo 'system_documentroot_prefix<br />';
				$value=addslashes($_POST['system_documentroot_prefix']);
				$value=makeCorrectDir($value);
				$db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='$value' WHERE `settinggroup`='system' AND `varname`='documentroot_prefix'");
			}

			if($_POST['system_logfiles_directory']!=$config->get('system.logfiles_directory'))
			{
//				echo 'system_logfiles_directory<br />';
				$value=addslashes($_POST['system_logfiles_directory']);
				$value=makeCorrectDir($value);
				$db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='$value' WHERE `settinggroup`='system' AND `varname`='logfiles_directory'");
			}

			if($_POST['system_ipaddress']!=$config->get('system.ipaddress'))
			{
//				echo 'system_ipaddress<br />';
				$value=addslashes($_POST['system_ipaddress']);
				$db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='$value' WHERE `settinggroup`='system' AND `varname`='ipaddress'"); 
				$ipandport = $db->query('SELECT `id` FROM `'.TABLE_PANEL_IPSANDPORTS.'` WHERE `ip`=\''.$value.'\' AND `port`=\'80\''); 
 				if($db->num_rows($ipandport) > 0) 
 				{ 
 					$ipandport = $db->fetch_array($ipandport); 
					$ipandport = intval($ipandport['id']); 
				} 
				else 
				{ 
					$ipandport = $db->query_first('SELECT `id` FROM `'.TABLE_PANEL_IPSANDPORTS.'` WHERE `ip`=\''.$value.'\''); 
					$ipandport = intval($ipandport['id']); 
				} 
				$domains = $db->query('SELECT `standardsubdomain` FROM `'.TABLE_PANEL_CUSTOMERS.'` WHERE `standardsubdomain` <> \'0\''); 
				$ids = array(); 
				while(true === ($row = $db->fetch_array($domains))) 
				{ 
					$ids[] = $row['id']; 
				} 
				if(count($ids) > 0) 
				{ 
					$db->query('UPDATE `'.TABLE_PANEL_DOMAINS.'` SET `ipandport`=\''.$ipandport.'\' WHERE `id` IN ('.join(',',$ids).')'); 
				} 
				$hooks->call( 'core.updateDomain', 
				              array( 'id' => '*' ) );

				if( $config->get('sql.host') != 'localhost')
				{
					$mysql_access_host = $value;

					// Connect as root to change hostnames from where the server will be accessed to new ip
					$db_root = new Syscp_DB_Mysql( $config->get('sql.host'),
					                                        $config->get('sql.root.user'), 
					                                        $config->get('sql.root.password') );

					// Update our sql unprivileged and privileged (root) user
					$update_users = '"' . $config->get('sql.user') . '", ' . 
					                '"' . $config->get('sql.root.user') . '"' ;

					// Update all customer databases
					$databases = $db->query('SELECT `databasename` FROM `' . TABLE_PANEL_DATABASES . '`;');
					while ( $database = $db->fetch_array ( $databases ) )
					{
						$update_users .= ', "' . $database['databasename'] .'"' ;
					}

					// Do the update
					$db_root->query("UPDATE `mysql`.`user` SET `HOST` = '$mysql_access_host' WHERE `User` IN (" . $update_users . ") AND `Host` = '" . $config->get('system.mysql_access_host') . "';");
					$db_root->query("UPDATE `mysql`.`db` SET `HOST` = '$mysql_access_host' WHERE `User` IN (" . $update_users . ") AND `Host` = '" . $config->get('system.mysql_access_host') . "';");
					$db_root->query("UPDATE `mysql`.`tables_priv` SET `HOST` = '$mysql_access_host' WHERE `User` IN (" . $update_users . ") AND `Host` = '" . $config->get('system.mysql_access_host') . "';");
					$db_root->query("UPDATE `mysql`.`columns_priv` SET `HOST` = '$mysql_access_host' WHERE `User` IN (" . $update_users . ") AND `Host` = '" . $config->get('system.mysql_access_host') . "';");
	
					// Clean up and disconnect
					$db_root->query("FLUSH PRIVILEGES;");
					$db_root->close();
					unset($db_root);

					$db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='$mysql_access_host' WHERE `settinggroup`='system' AND `varname`='mysql_access_host'");
				}
			}

			if($_POST['system_hostname']!=$config->get('system.hostname'))
			{
//				echo 'system_hostname<br />';
				$value=addslashes($idna->encode($_POST['system_hostname']));
				$db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='$value' WHERE `settinggroup`='system' AND `varname`='hostname'");
				$result=$db->query('SELECT `standardsubdomain` FROM `'.TABLE_PANEL_CUSTOMERS.'` WHERE `standardsubdomain`!=\'0\'');
				$domains=array();
				while(($row=$db->fetch_array($result)) !== false)
				{
					$domains[]='\''.$row['standardsubdomain'].'\'';
				}
				if(count($domains) > 0) {
					$domains=join($domains,',');
					$db->query('UPDATE `'.TABLE_PANEL_DOMAINS.'` SET `domain`=REPLACE(`domain`,\''.$config->get('system.hostname').'\',\''.$value.'\') WHERE `id` IN ('.$domains.')');
					$hooks->call( 'core.updateDomain', 
					              array( 'id' => '*' ) );
				}
			}

			if($_POST['system_apacheconf_directory']!=$config->get('system.apacheconf_directory'))
			{
//				echo 'system_apacheconf_directory<br />';
				$value=addslashes($_POST['system_apacheconf_directory']);
				$value=makeCorrectDir($value);
				$db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='$value' WHERE `settinggroup`='system' AND `varname`='apacheconf_directory'");
				$hooks->call( 'core.updateDomain', 
				              array( 'id' => '*' ) );
			}

			if($_POST['system_apachereload_command']!=$config->get('system.apachereload_command'))
			{
//				echo 'system_apachereload_command<br />';
				$value=addslashes($_POST['system_apachereload_command']);
				$db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='$value' WHERE `settinggroup`='system' AND `varname`='apachereload_command'");
				$hooks->call( 'core.updateDomain', 
				              array( 'id' => '*' ) );
			}

			if($_POST['system_bindconf_directory']!=$config->get('system.bindconf_directory'))
			{
//				echo 'system_bindconf_directory<br />';
				$value=addslashes($_POST['system_bindconf_directory']);
				$value=makeCorrectDir($value);
				$db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='$value' WHERE `settinggroup`='system' AND `varname`='bindconf_directory'");
			}

			if($_POST['system_bindreload_command']!=$config->get('system.bindreload_command'))
			{
//				echo 'system_bindreload_command<br />';
				$value=addslashes($_POST['system_bindreload_command']);
				$db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='$value' WHERE `settinggroup`='system' AND `varname`='bindreload_command'");
			}

			if($_POST['system_binddefaultzone']!=$config->get('system.binddefaultzone'))
			{
//				echo 'system_binddefaultzone<br />';
				$value=addslashes($_POST['system_binddefaultzone']);
				$value=htmlentities($value);
				$db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='$value' WHERE `settinggroup`='system' AND `varname`='binddefaultzone'");
			}

			if($_POST['system_vmail_uid']!=$config->get('system.vmail_uid'))
			{
//				echo 'system_vmail_uid<br />';
				$value=addslashes($_POST['system_vmail_uid']);
				$db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='$value' WHERE `settinggroup`='system' AND `varname`='vmail_uid'");
			}

			if($_POST['system_vmail_gid']!=$config->get('system.vmail_gid'))
			{
//				echo 'system_vmail_gid<br />';
				$value=addslashes($_POST['system_vmail_gid']);
				$db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='$value' WHERE `settinggroup`='system' AND `varname`='vmail_gid'");
			}

			if($_POST['system_vmail_homedir']!=$config->get('system.vmail_homedir'))
			{
//				echo 'system_vmail_homedir<br />';
				$value=addslashes($_POST['system_vmail_homedir']);
				$value=makeCorrectDir($value);
				$db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='$value' WHERE `settinggroup`='system' AND `varname`='vmail_homedir'");
			}

			if($_POST['panel_adminmail']!=$config->get('panel.adminmail'))
			{
//				echo 'panel_adminmail<br />';
				$value=addslashes($idna->encode($_POST['panel_adminmail']));
				$db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='$value' WHERE `settinggroup`='panel' AND `varname`='adminmail'");
			}

			if($_POST['panel_paging']!=$config->get('panel.paging'))
			{
				$value=intval($_POST['panel_paging']);
				if ($value < 0) {
					$value = 0;
				}
				$db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='$value' WHERE `settinggroup`='panel' AND `varname`='paging'");
			}

			if($_POST['panel_standardlanguage']!=$config->get('panel.standardlanguage'))
			{
				$value = addslashes ( htmlentities ( _html_entity_decode ( $_POST['panel_standardlanguage'] ) ) ) ;
				$db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='$value' WHERE `settinggroup`='panel' AND `varname`='standardlanguage'");
			}

			if($_POST['panel_pathedit']!=$config->get('panel.pathedit'))
			{
				$value = addslashes ( htmlentities ( _html_entity_decode ( $_POST['panel_pathedit'] ) ) ) ;
				$db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='$value' WHERE `settinggroup`='panel' AND `varname`='pathedit'");
			}

			if($_POST['panel_phpmyadmin_url']!=$config->get('panel.phpmyadmin_url'))
			{
//				echo 'panel_phpmyadmin_url<br />';
				$value=addslashes($_POST['panel_phpmyadmin_url']);
				if ($config->get('panel.phpmyadmin_url') != '')
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

			if($_POST['panel_webmail_url']!=$config->get('panel.webmail_url'))
			{
//				echo 'panel_webmail_url<br />';
				$value=addslashes($_POST['panel_webmail_url']);
				if ($config->get('panel.webmail_url') != '')
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

			if($_POST['panel_webftp_url']!=$config->get('panel.webftp_url'))
			{
//				echo 'panel_webftp_url<br />';
				$value=addslashes($_POST['panel_webftp_url']);
				if ($config->get('panel.webftp_url') != '')
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

			redirectTo ( $config->get('env.filename') , Array ( 'page' => $config->get('env.page') , 's' => $config->get('env.s') ) ) ;
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
				$languages2 .= makeoption($key,$value,$config->get('panel.standardlanguage'));
 			}

			// build the IP addresses list
			$result=$db->query('SELECT `ip` FROM `'.TABLE_PANEL_IPSANDPORTS.'` ORDER BY `ip` ASC');
			$system_ipaddress_array='';
			while($row=$db->fetch_array($result))
			{
				$system_ipaddress_array[$row['ip']] = $row['ip'];
			}
			$system_ipaddress='';
			foreach($system_ipaddress_array as $key => $value)
			{
				$system_ipaddress.=makeoption($key,$value,$config->get('system.ipaddress'));
			}

			$pathedit='';
 			foreach (array('Manual','Dropdown') as $method)
			{
				$pathedit .= makeoption($method, $method, $config->get('panel.pathedit'));
			}
			
			eval("echo \"".getTemplate("settings/settings")."\";");
		}
	}

	elseif ( $config->get('env.page') == 'rebuildconfigs' && $userinfo['change_serversettings'] == '1')
	{
		if ( isset($_POST['send']) && $_POST['send'] == 'send' )
		{
			$hooks->call( 'core.updateDomain', 
			              array( 'id' => '*' ) );
			redirectTo ( 'admin_index.php' , array( 's' => $config->get('env.s') ) ) ;
		}
		else
		{
			ask_yesno('admin_configs_reallyrebuild', $config->get('env.filename'), 'id='.$config->get('env.id').';page='.$config->get('env.page') );
		}
			
	}
	
?>

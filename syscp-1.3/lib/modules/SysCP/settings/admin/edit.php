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
 * @package    Syscp.Modules
 * @subpackage Panel.Admin
 * @license    GPLv2 http://files.syscp.org/misc/COPYING.txt
 * @version    $Id:admin_settings.php 460 2006-04-23 15:07:49 +0200 (So, 23 Apr 2006) martin $
 *
 * @todo code cleanup is needed, exspecially in queries, loops, conditions
 * @todo code restructuring is needed, we need to seperate view, controller and model logic,
 *       as a preparation towards MVC patterns
 *
 * @todo the external links to webftp, phpmyadmin and webmail need a solution!!!
 */

	if ($this->User['change_serversettings'] == '1')
	{
		if(isset($_POST['send']) && $_POST['send']=='send')
		{
			if($_POST['session_sessiontimeout']!=$this->ConfigHandler->get('session.sessiontimeout'))
			{
				$value=addslashes($_POST['session_sessiontimeout']);
				$this->DatabaseHandler->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='$value' WHERE `settinggroup`='session' AND `varname`='sessiontimeout'");
			}

			if($_POST['login_maxloginattempts']!=$this->ConfigHandler->get('login.maxloginattempts'))
			{
				$value=addslashes($_POST['login_maxloginattempts']);
				$this->DatabaseHandler->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='$value' WHERE `settinggroup`='login' AND `varname`='maxloginattempts'");
			}

			if($_POST['login_deactivatetime']!=$this->ConfigHandler->get('login.deactivatetime'))
			{
				$value=addslashes($_POST['login_deactivatetime']);
				$this->DatabaseHandler->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='$value' WHERE `settinggroup`='login' AND `varname`='deactivatetime'");
			}

			if($_POST['customer_accountprefix']!=$this->ConfigHandler->get('customer.accountprefix'))
			{
//				echo 'customer_accountprefix<br />';
				$value=addslashes($_POST['customer_accountprefix']);
				if(check_username_prefix($value))
				{
					$this->DatabaseHandler->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='$value' WHERE `settinggroup`='customer' AND `varname`='accountprefix'");
				}
			}

			if($_POST['customer_mysqlprefix']!=$this->ConfigHandler->get('customer.mysqlprefix'))
			{
//				echo 'customer_mysqlprefix<br />';
				$value=addslashes($_POST['customer_mysqlprefix']);
				if(check_mysql_prefix($value))
				{
					$this->DatabaseHandler->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='$value' WHERE `settinggroup`='customer' AND `varname`='mysqlprefix'");
				}
			}

			if($_POST['customer_ftpprefix']!=$this->ConfigHandler->get('customer.ftpprefix'))
			{
//				echo 'customer_ftpprefix<br />';
				$value=addslashes($_POST['customer_ftpprefix']);
				if(check_username_prefix($value))
				{
					$this->DatabaseHandler->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='$value' WHERE `settinggroup`='customer' AND `varname`='ftpprefix'");
				}
			}

			if($_POST['system_documentroot_prefix']!=$this->ConfigHandler->get('system.documentroot_prefix'))
			{
//				echo 'system_documentroot_prefix<br />';
				$value=addslashes($_POST['system_documentroot_prefix']);
				$value=makeCorrectDir($value);
				$this->DatabaseHandler->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='$value' WHERE `settinggroup`='system' AND `varname`='documentroot_prefix'");
			}

			if($_POST['system_user_homedir'] != $this->ConfigHandler->get('system.user_homedir'))
			{
//				echo 'system_documentroot_prefix<br />';
				$value=addslashes($_POST['system_user_homedir']);
				$value=makeCorrectDir($value);
				$this->DatabaseHandler->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='$value' WHERE `settinggroup`='system' AND `varname`='user_homedir'");
			}

			if($_POST['system_apache_access_log'] != $this->ConfigHandler->get('system.apache_access_log'))
			{
//				echo 'system_logfiles_directory<br />';
				$value=addslashes($_POST['system_apache_access_log']);
				$this->DatabaseHandler->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='$value' WHERE `settinggroup`='system' AND `varname`='apache_access_log'");
			}
			if($_POST['system_apache_error_log']!=$this->ConfigHandler->get('system.apache_error_log'))
			{
//				echo 'system_logfiles_directory<br />';
				$value=addslashes($_POST['system_apache_error_log']);
				$this->DatabaseHandler->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='$value' WHERE `settinggroup`='system' AND `varname`='apache_error_log'");
			}

			if($_POST['system_ipaddress']!=$this->ConfigHandler->get('system.ipaddress'))
			{
//				echo 'system_ipaddress<br />';
				$value=addslashes($_POST['system_ipaddress']);
				$this->DatabaseHandler->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='$value' WHERE `settinggroup`='system' AND `varname`='ipaddress'");
				$ipandport = $this->DatabaseHandler->query('SELECT `id` FROM `'.TABLE_PANEL_IPSANDPORTS.'` WHERE `ip`=\''.$value.'\' AND `port`=\'80\'');
 				if($this->DatabaseHandler->num_rows($ipandport) > 0)
 				{
 					$ipandport = $this->DatabaseHandler->fetch_array($ipandport);
					$ipandport = intval($ipandport['id']);
				}
				else
				{
					$ipandport = $this->DatabaseHandler->queryFirst('SELECT `id` FROM `'.TABLE_PANEL_IPSANDPORTS.'` WHERE `ip`=\''.$value.'\'');
					$ipandport = intval($ipandport['id']);
				}
				$domains = $this->DatabaseHandler->query('SELECT `standardsubdomain` FROM `'.TABLE_PANEL_CUSTOMERS.'` WHERE `standardsubdomain` <> \'0\'');
				$ids = array();
				while(true === ($row = $this->DatabaseHandler->fetch_array($domains)))
				{
					$ids[] = $row['id'];
				}
				if(count($ids) > 0)
				{
					$this->DatabaseHandler->query('UPDATE `'.TABLE_PANEL_DOMAINS.'` SET `ipandport`=\''.$ipandport.'\' WHERE `id` IN ('.join(',',$ids).')');
				}
				$this->HookHandler->call( 'OnUpdateDomain',
				              array( 'id' => '*' ) );

				if( $this->ConfigHandler->get('sql.host') != 'localhost')
				{
					$mysql_access_host = $value;

					// Connect as root to change hostnames from where the server will be accessed to new ip
					// Begin root-session
					$dsn = sprintf('mysql://%s:%s@%s',
	            	               $this->ConfigHandler->get('sql.root.user'),
	            	               $this->ConfigHandler->get('sql.root.password'),
	            	               $this->ConfigHandler->get('sql.host'));
					$db_root = new Syscp_Handler_Database();
					$db_root->initialize(array('dsn'=>$dsn));

					// Update our sql unprivileged and privileged (root) user
					$update_users = '"' . $this->ConfigHandler->get('sql.user') . '", ' .
					                '"' . $this->ConfigHandler->get('sql.root.user') . '"' ;

					// Update all customer databases
					$databases = $this->DatabaseHandler->query('SELECT `databasename` FROM `' . TABLE_PANEL_DATABASES . '`;');
					while ( $database = $this->DatabaseHandler->fetch_array ( $databases ) )
					{
						$update_users .= ', "' . $database['databasename'] .'"' ;
					}

					// Do the update
					$db_root->query("UPDATE `mysql`.`user` SET `HOST` = '$mysql_access_host' WHERE `User` IN (" . $update_users . ") AND `Host` = '" . $this->ConfigHandler->get('system.mysql_access_host') . "';");
					$db_root->query("UPDATE `mysql`.`db` SET `HOST` = '$mysql_access_host' WHERE `User` IN (" . $update_users . ") AND `Host` = '" . $this->ConfigHandler->get('system.mysql_access_host') . "';");
					$db_root->query("UPDATE `mysql`.`tables_priv` SET `HOST` = '$mysql_access_host' WHERE `User` IN (" . $update_users . ") AND `Host` = '" . $this->ConfigHandler->get('system.mysql_access_host') . "';");
					$db_root->query("UPDATE `mysql`.`columns_priv` SET `HOST` = '$mysql_access_host' WHERE `User` IN (" . $update_users . ") AND `Host` = '" . $this->ConfigHandler->get('system.mysql_access_host') . "';");

					// Clean up and disconnect
					$db_root->query("FLUSH PRIVILEGES;");
					$db_root->close();
					unset($db_root);

					$this->DatabaseHandler->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='$mysql_access_host' WHERE `settinggroup`='system' AND `varname`='mysql_access_host'");
				}
			}

			if($_POST['system_hostname']!=$this->ConfigHandler->get('system.hostname'))
			{
//				echo 'system_hostname<br />';
				$value=addslashes($this->IdnaHandler->encode($_POST['system_hostname']));
				$this->DatabaseHandler->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='$value' WHERE `settinggroup`='system' AND `varname`='hostname'");
				$result=$this->DatabaseHandler->query('SELECT `standardsubdomain` FROM `'.TABLE_PANEL_CUSTOMERS.'` WHERE `standardsubdomain`!=\'0\'');
				$domains=array();
				while(($row=$this->DatabaseHandler->fetch_array($result)) !== false)
				{
					$domains[]='\''.$row['standardsubdomain'].'\'';
				}
				if(count($domains) > 0) {
					$domains=join($domains,',');
					$this->DatabaseHandler->query('UPDATE `'.TABLE_PANEL_DOMAINS.'` SET `domain`=REPLACE(`domain`,\''.$this->ConfigHandler->get('system.hostname').'\',\''.$value.'\') WHERE `id` IN ('.$domains.')');
					$this->HookHandler->call( 'OnUpdateDomain',
					              array( 'id' => '*' ) );
				}
			}

			if($_POST['system_apacheconf_directory']!=$this->ConfigHandler->get('system.apacheconf_directory'))
			{
//				echo 'system_apacheconf_directory<br />';
				$value=addslashes($_POST['system_apacheconf_directory']);
				$value=makeCorrectDir($value);
				$this->DatabaseHandler->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='$value' WHERE `settinggroup`='system' AND `varname`='apacheconf_directory'");
				$this->HookHandler->call( 'OnUpdateDomain',
				              array( 'id' => '*' ) );
			}

			if($_POST['system_apachereload_command']!=$this->ConfigHandler->get('system.apachereload_command'))
			{
//				echo 'system_apachereload_command<br />';
				$value=addslashes($_POST['system_apachereload_command']);
				$this->DatabaseHandler->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='$value' WHERE `settinggroup`='system' AND `varname`='apachereload_command'");
				$this->HookHandler->call( 'OnUpdateDomain',
				              array( 'id' => '*' ) );
			}

			if($_POST['system_bindconf_directory']!=$this->ConfigHandler->get('system.bindconf_directory'))
			{
//				echo 'system_bindconf_directory<br />';
				$value=addslashes($_POST['system_bindconf_directory']);
				$value=makeCorrectDir($value);
				$this->DatabaseHandler->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='$value' WHERE `settinggroup`='system' AND `varname`='bindconf_directory'");
			}

			if($_POST['system_bindreload_command']!=$this->ConfigHandler->get('system.bindreload_command'))
			{
//				echo 'system_bindreload_command<br />';
				$value=addslashes($_POST['system_bindreload_command']);
				$this->DatabaseHandler->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='$value' WHERE `settinggroup`='system' AND `varname`='bindreload_command'");
			}

			if($_POST['system_binddefaultzone']!=$this->ConfigHandler->get('system.binddefaultzone'))
			{
//				echo 'system_binddefaultzone<br />';
				$value=addslashes($_POST['system_binddefaultzone']);
				$value=htmlentities($value);
				$this->DatabaseHandler->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='$value' WHERE `settinggroup`='system' AND `varname`='binddefaultzone'");
			}

			if($_POST['system_vmail_uid']!=$this->ConfigHandler->get('system.vmail_uid'))
			{
//				echo 'system_vmail_uid<br />';
				$value=addslashes($_POST['system_vmail_uid']);
				$this->DatabaseHandler->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='$value' WHERE `settinggroup`='system' AND `varname`='vmail_uid'");
			}

			if($_POST['system_vmail_gid']!=$this->ConfigHandler->get('system.vmail_gid'))
			{
//				echo 'system_vmail_gid<br />';
				$value=addslashes($_POST['system_vmail_gid']);
				$this->DatabaseHandler->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='$value' WHERE `settinggroup`='system' AND `varname`='vmail_gid'");
			}

			if($_POST['system_vmail_homedir']!=$this->ConfigHandler->get('system.vmail_homedir'))
			{
//				echo 'system_vmail_homedir<br />';
				$value=addslashes($_POST['system_vmail_homedir']);
				$value=makeCorrectDir($value);
				$this->DatabaseHandler->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='$value' WHERE `settinggroup`='system' AND `varname`='vmail_homedir'");
			}

			if($_POST['panel_adminmail']!=$this->ConfigHandler->get('panel.adminmail'))
			{
//				echo 'panel_adminmail<br />';
				$value=addslashes($this->IdnaHandler->encode($_POST['panel_adminmail']));
				$this->DatabaseHandler->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='$value' WHERE `settinggroup`='panel' AND `varname`='adminmail'");
			}

			if($_POST['panel_paging']!=$this->ConfigHandler->get('panel.paging'))
			{
				$value=intval($_POST['panel_paging']);
				if ($value < 0) {
					$value = 0;
				}
				$this->DatabaseHandler->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='$value' WHERE `settinggroup`='panel' AND `varname`='paging'");
			}

			if($_POST['panel_standardlanguage']!=$this->ConfigHandler->get('panel.standardlanguage'))
			{
				$value = addslashes ( htmlentities ( _html_entity_decode ( $_POST['panel_standardlanguage'] ) ) ) ;
				$this->DatabaseHandler->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='$value' WHERE `settinggroup`='panel' AND `varname`='standardlanguage'");
			}

			if($_POST['panel_pathedit']!=$this->ConfigHandler->get('panel.pathedit'))
			{
				$value = addslashes ( htmlentities ( _html_entity_decode ( $_POST['panel_pathedit'] ) ) ) ;
				$this->DatabaseHandler->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='$value' WHERE `settinggroup`='panel' AND `varname`='pathedit'");
			}

			if($_POST['panel_phpmyadmin_url']!=$this->ConfigHandler->get('panel.phpmyadmin_url'))
			{
//				echo 'panel_phpmyadmin_url<br />';
				$value=addslashes($_POST['panel_phpmyadmin_url']);
				if ($this->ConfigHandler->get('panel.phpmyadmin_url') != '')
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
					$max=$this->DatabaseHandler->queryFirst($query);
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
				$this->DatabaseHandler->query($query);
				$this->DatabaseHandler->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='$value' WHERE `settinggroup`='panel' AND `varname`='phpmyadmin_url'");
			}

			if($_POST['panel_webmail_url']!=$this->ConfigHandler->get('panel.webmail_url'))
			{
//				echo 'panel_webmail_url<br />';
				$value=addslashes($_POST['panel_webmail_url']);
				if ($this->ConfigHandler->get('panel.webmail_url') != '')
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
					$max=$this->DatabaseHandler->queryFirst($query);
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
				$this->DatabaseHandler->query($query);
				$this->DatabaseHandler->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='$value' WHERE `settinggroup`='panel' AND `varname`='webmail_url'");
			}

			if($_POST['panel_webftp_url']!=$this->ConfigHandler->get('panel.webftp_url'))
			{
//				echo 'panel_webftp_url<br />';
				$value=addslashes($_POST['panel_webftp_url']);
				if ($this->ConfigHandler->get('panel.webftp_url') != '')
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
					$max=$this->DatabaseHandler->queryFirst($query);
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
				$this->DatabaseHandler->query($query);
				$this->DatabaseHandler->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='$value' WHERE `settinggroup`='panel' AND `varname`='webftp_url'");
			}

			$this->redirectTo(array('module' => 'settings',
			                        'action' => 'edit'));
		}
		else
		{
/*			// query the whole table
			$query =
				'SELECT * ' .
 				'FROM `'.TABLE_PANEL_LANGUAGE.'` ';
 			$result = $this->DatabaseHandler->query($query);
 			// presort languages
			$langs2 = "";
 			while ($row = $this->DatabaseHandler->fetch_array($result))
 			{
				$langs2[$row['language']] = $row['language'];
 			}
			// buildup $languages2 for the login screen
			$languages2 = "";
			foreach ($langs2 as $key => $value)
 			{
				$languages2 .= makeoption($key,$value,$this->ConfigHandler->get('panel.standardlanguage'));
 			}
*/
			// build language dropdown
			$lang_list = $this->L10nHandler->getList();

			// build the IP addresses list
			$result=$this->DatabaseHandler->query('SELECT `ip` FROM `'.TABLE_PANEL_IPSANDPORTS.'` ORDER BY `ip` ASC');
			$system_ipaddress_array='';
			while($row=$this->DatabaseHandler->fetch_array($result))
			{
				$system_ipaddress_array[$row['ip']] = $row['ip'];
			}
			$system_ipaddress = array();
			foreach($system_ipaddress_array as $key => $value)
			{
				$system_ipaddress[$key] = $value;
//				$system_ipaddress.=makeoption($key,$value,$this->ConfigHandler->get('system.ipaddress'));
			}

			$pathedit = array('Manual'   => 'Manual',
			                  'Dropdown' => 'Dropdown');
// 			foreach (array('Manual','Dropdown') as $method)
//			{
//				$pathedit .= makeoption($method, $method, $this->ConfigHandler->get('panel.pathedit'));
//			}

			//eval("echo \"".getTemplate("settings/settings")."\";");
			$this->TemplateHandler->set('system_ipaddress', $system_ipaddress);
			$this->TemplateHandler->set('lang_list', $lang_list);
			$this->TemplateHandler->set('pathedit', $pathedit);
			$this->TemplateHandler->setTemplate('SysCP/settings/admin/edit.tpl');
		}
	}


?>

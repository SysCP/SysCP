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
 * @package System
 * @version $Id$
 */
	if(@php_sapi_name() != 'cli' && @php_sapi_name() != 'cgi')
	{
		die('This script will only work in the shell.');
	}

	/**
	 * LOOK INTO TASKS TABLE TO SEE IF THERE ARE ANY UNDONE JOBS
	 */
	$debugMsg[] = '  cron_tasks: Searching for tasks to do';
	$result=$db->query("SELECT `id`, `type`, `data` FROM `".TABLE_PANEL_TASKS."` ORDER BY `type` ASC");
	while($row=$db->fetch_array($result))
	{
		if($row['data'] != '')
		{
			$row['data']=unserialize($row['data']);
		}

		/**
		 * TYPE=1 MEANS TO REBUILD APACHE VHOSTS.CONF
		 */
		if($row['type'] == '1')
		{
			$debugMsg[] = '  cron_tasks: Task1 started - vhost.conf rebuild';
			$vhosts_file='# '.$settings['system']['apacheconf_directory'].'vhosts.conf'."\n".'# Created '.date('d.m.Y H:i')."\n".'# Do NOT manually edit this file, all changes will be deleted after the next domain change at the panel.'."\n"."\n";
			$vhosts_file.='NameVirtualHost '.$settings['system']['ipaddress']."\n"."\n";

			$vhosts_file.='# DummyHost for DefaultSite'."\n";
			$vhosts_file.='<VirtualHost '.$settings['system']['ipaddress'].'>'."\n";
			$vhosts_file.='</VirtualHost>'."\n"."\n";

			$result_domains=$db->query("SELECT `d`.`id`, `d`.`domain`, `d`.`customerid`, `d`.`documentroot`, `d`.`parentdomainid`, `d`.`isemaildomain`, `d`.`iswildcarddomain`, `d`.`openbasedir`, `d`.`safemode`, `d`.`speciallogfile`, `d`.`specialsettings`, `pd`.`domain` AS `parentdomain`, `c`.`loginname`, `c`.`guid`, `c`.`email` FROM `".TABLE_PANEL_DOMAINS."` `d` LEFT JOIN `".TABLE_PANEL_CUSTOMERS."` `c` USING(`customerid`) LEFT JOIN `".TABLE_PANEL_DOMAINS."` `pd` ON(`pd`.`id` = `d`.`parentdomainid`) WHERE `d`.`deactivated` <> '1' ORDER BY `d`.`iswildcarddomain`, `d`.`domain` ASC");
			while($domain=$db->fetch_array($result_domains))
			{
				$debugMsg[] = '  cron_tasks: Task1 - Writing Domain '.$domain['id'].'::'.$domain['domain'];
				$vhosts_file.='# Domain ID: '.$domain['id'].' - CustomerID: '.$domain['customerid'].' - CustomerLogin: '.$domain['loginname']."\n";
				$vhosts_file.='<VirtualHost '.$settings['system']['ipaddress'].'>'."\n";
				$vhosts_file.='  ServerName '.$domain['domain']."\n";

				if($domain['iswildcarddomain'] == '1')
				{
					$alias = '*';
				}
				else
				{
					$alias = 'www';
				}
				$vhosts_file.='  ServerAlias '.$alias.'.'.$domain['domain']."\n";
				$vhosts_file.='  ServerAdmin '.$domain['email']."\n";

				if(preg_match('/^https?\:\/\//', $domain['documentroot']))
				{
					$vhosts_file.='  Redirect / '.$domain['documentroot']."\n";
				}
				else
				{
					$domain['documentroot'] = makeCorrectDir ($domain['documentroot']);
					$vhosts_file.='  DocumentRoot '.$domain['documentroot']."\n";
					if($domain['openbasedir'] == '1')
					{
						$vhosts_file.='  php_admin_value open_basedir '.$domain['documentroot']."\n";
					}
					if($domain['safemode'] == '1')
					{
						$vhosts_file.='  php_admin_flag safe_mode On '."\n";
					}

					if(!is_dir($domain['documentroot']))
					{
						safe_exec('mkdir -p '.$domain['documentroot']);
						safe_exec('chown -R '.$domain['guid'].':'.$domain['guid'].' '.$domain['documentroot']);
					}
					if($domain['speciallogfile'] == '1')
					{
						if($domain['parentdomainid'] == '0')
						{
							$speciallogfile = '-'.$domain['domain'];
						}
						else
						{
							$speciallogfile = '-'.$domain['parentdomain'];
						}
					}
					else
					{
						$speciallogfile = '';
					}
					$vhosts_file.='  ErrorLog '.$settings['system']['logfiles_directory'].$domain['loginname'].$speciallogfile.'-error.log'."\n";
					$vhosts_file.='  CustomLog '.$settings['system']['logfiles_directory'].$domain['loginname'].$speciallogfile.'-access.log combined'."\n";
				}
				$vhosts_file.=stripslashes($domain['specialsettings'])."\n";
				$vhosts_file.='</VirtualHost>'."\n";
				$vhosts_file.="\n";
			}

			$result_customers=$db->query("SELECT `customerid`, `loginname`, `guid`, `documentroot` FROM `".TABLE_PANEL_CUSTOMERS."` WHERE `createstdsubdomain`='1' AND `deactivated` <> '1'");
			while($customer=$db->fetch_array($result_customers))
			{
				$debugMsg[] = '  cron_tasks: Task1 - Writing Domain '.$customer['loginname'].'.'.$settings['system']['hostname'];
				$vhosts_file.='# Standardsubdomain - CustomerID: '.$customer['customerid'].' - CustomerLogin: '.$customer['loginname']."\n";
				$vhosts_file.='<VirtualHost '.$settings['system']['ipaddress'].'>'."\n";
				$vhosts_file.='  ServerName '.$customer['loginname'].'.'.$settings['system']['hostname']."\n";
				$vhosts_file.='  DocumentRoot '.$customer['documentroot'].'/'."\n";
				$vhosts_file.='  php_admin_value open_basedir '.$customer['documentroot'].'/'."\n";
				$vhosts_file.='  php_admin_flag safe_mode On '."\n";
				$vhosts_file.='  ErrorLog '.$settings['system']['logfiles_directory'].$customer['loginname'].'-error.log'."\n";
				$vhosts_file.='  CustomLog '.$settings['system']['logfiles_directory'].$customer['loginname'].'-access.log combined'."\n";
				$vhosts_file.='</VirtualHost>'."\n";
				$vhosts_file.="\n";
			}

			$vhosts_file_handler = fopen($settings['system']['apacheconf_directory'].'vhosts.conf', 'w');
			fwrite($vhosts_file_handler, $vhosts_file);
			fclose($vhosts_file_handler);
			safe_exec($settings['system']['apachereload_command']);
			$debugMsg[] = '   cron_tasks: Task1 - Apache reloaded';
		}

		/**
		 * TYPE=2 MEANS TO CREATE A NEW HOME AND CHOWN
		 */
		elseif($row['type'] == '2')
		{
			$debugMsg[] = '  cron_tasks: Task2 started - create new home';
			if(is_array($row['data']))
			{
				safe_exec('mkdir -p '.$settings['system']['documentroot_prefix'].$row['data']['loginname'].'/webalizer');
				safe_exec('mkdir -p '.$settings['system']['vmail_homedir'].$row['data']['loginname']);
				safe_exec('cp -a '.$pathtophpfiles.'/templates/misc/standardcustomer/* '.$settings['system']['documentroot_prefix'].$row['data']['loginname'].'/');
				safe_exec('chown -R '.$row['data']['uid'].':'.$row['data']['gid'].' '.$settings['system']['documentroot_prefix'].$row['data']['loginname']);
				safe_exec('chown -R '.$settings['system']['vmail_uid'].':'.$settings['system']['vmail_gid'].' '.$settings['system']['vmail_homedir'].$row['data']['loginname']);
			}
		}

		/**
		 * TYPE=3 MEANS TO CREATE/CHANGE/DELETE A HTACCESS AND/OR HTPASSWD
		 */
		elseif($row['type'] == '3')
		{
			$debugMsg[] = '  cron_tasks: Task3 started - create/change/del htaccess/htpasswd';
			if ( is_array($row['data']) )
			{
				$path = $row['data']['path'];
						$debugMsg[] = '  cron_tasks: Task3 - Path: '.$path;
				
				if (!is_dir($path))
				{
					$db->query(
						'DELETE FROM `'.TABLE_PANEL_HTACCESS.'` ' .
						'WHERE `path` = "'.$path.'"'
					);
					$db->query(
						'DELETE FROM `'.TABLE_PANEL_HTPASSWDS.'` ' .
						'WHERE `path` = "'.$path.'"'
					);
				}
				else
				{
					$htpasswd_file = '';
					$htaccess_file = '';
					$row_htaccess  = $db->query_first(
						'SELECT * ' .
						'FROM `'.TABLE_PANEL_HTACCESS.'` ' .
						'WHERE `path` = "'.$row['data']['path'].'"'
					);
					
					if ( $row_htaccess['options_indexes'] == '1' )
					{
						$htaccess_file .= 'Options Indexes'."\n";
								$debugMsg[] = '  cron_tasks: Task3 - Setting Options Indexes';
					}
					if ( $row_htaccess['error404path'] != '')
					{
						$htaccess_file .= 'ErrorDocument 404 '.$row_htaccess['error404path']."\n";
					}
					if ( $row_htaccess['error403path'] != '')
					{
						$htaccess_file .= 'ErrorDocument 403 '.$row_htaccess['error403path']."\n";
					}
					if ( $row_htaccess['error401path'] != '')
					{
						$htaccess_file .= 'ErrorDocument 401 '.$row_htaccess['error401path']."\n";
					}
					if ( $row_htaccess['error500path'] != '')
					{
						$htaccess_file .= 'ErrorDocument 500 '.$row_htaccess['error500path']."\n";
					}
				
					
					$result_htpasswd = $db->query(
						'SELECT `username`, `password` ' .
						'FROM `'.TABLE_PANEL_HTPASSWDS.'` ' .
						'WHERE `path` = "'.$row['data']['path'].'"'
					);
					if ( $db->num_rows($result_htpasswd) != 0 )
					{
								$debugMsg[] = '  cron_tasks: Task3 - Setting Password';
						$htaccess_file .= 'AuthType Basic'."\n";
						$htaccess_file .= 'AuthName "Restricted Area"'."\n";
						$htaccess_file .= 'AuthUserFile '.$row['data']['path'].'.htpasswd'."\n";
						$htaccess_file .= 'require valid-user'."\n";
		
						while ($row_htpasswd = $db->fetch_array($result_htpasswd))
						{
							$htpasswd_file .= $row_htpasswd['username'].':'.$row_htpasswd['password']."\n";
						}
					}
					if ($htaccess_file != '')
					{
						$htaccess_file_handler = fopen($row['data']['path'].'.htaccess', 'w');
						fwrite($htaccess_file_handler, $htaccess_file);
						fclose($htaccess_file_handler);
						$debugMsg[] = '  cron_tasks: Task3 - htaccess written';
					}
					if ($htpasswd_file != '')
					{
						$htpasswd_file_handler = fopen($row['data']['path'].'.htpasswd', 'w');
						fwrite($htpasswd_file_handler, $htpasswd_file);
						fclose($htpasswd_file_handler);
						$debugMsg[] = '  cron_tasks: Task3 - htpasswd written';
					}

					if($htaccess_file == '' && file_exists($row['data']['path'].'.htaccess') )
					{
						unlink($row['data']['path'].'.htaccess');
						$debugMsg[] = '  cron_tasks: Task3 - htaccess deleted';
					}
					if($htpasswd_file == '' && file_exists($row['data']['path'].'.htpasswd') )
					{
						unlink($row['data']['path'].'.htpasswd');
						$debugMsg[] = '  cron_tasks: Task3 - htpasswd deleted';
					}
				}
			}
		}

		/**
		 * TYPE=4 MEANS THAT SOMETHING IN THE BIND CONFIG HAS CHANGED. REBUILD syscp_bind.conf
		 */
		elseif($row['type'] == '4')
		{
			$debugMsg[] = '  cron_tasks: Task4 started - Rebuilding syscp_bind.conf';
			$bindconf_file='# '.$settings['system']['bindconf_directory'].'syscp_bind.conf'."\n".'# Created '.date('d.m.Y H:i')."\n".'# Do NOT manually edit this file, all changes will be deleted after the next domain change at the panel.'."\n"."\n";
			
			$result_domains=$db->query("SELECT `d`.`id`, `d`.`domain`, `d`.`customerid`, `d`.`zonefile`, `d`.`isemaildomain`, `c`.`loginname`, `c`.`guid` FROM `".TABLE_PANEL_DOMAINS."` `d` LEFT JOIN `".TABLE_PANEL_CUSTOMERS."` `c` USING(`customerid`) WHERE `d`.`isemaildomain` = '1' ORDER BY `d`.`domain` ASC");
			while($domain=$db->fetch_array($result_domains))
			{
				$debugMsg[] = '  cron_tasks: Task4 - Writing '.$domain['id'].'::'.$domain['domain'];
				if($domain['zonefile'] == '')
				{
					$domain['zonefile'] = $settings['system']['binddefaultzone'];
				}
				$bindconf_file.='# Domain ID: '.$domain['id'].' - CustomerID: '.$domain['customerid'].' - CustomerLogin: '.$domain['loginname']."\n";
				$bindconf_file.='zone "'.$domain['domain'].'" in {'."\n";
				$bindconf_file.='	type master;'."\n";
				$bindconf_file.='	file "'.$settings['system']['bindconf_directory'].$domain['zonefile'].'";'."\n";
				$bindconf_file.='	allow-query { any; };'."\n";
				$bindconf_file.='};'."\n";
				$bindconf_file.="\n";
			}
			$bindconf_file_handler = fopen($settings['system']['bindconf_directory'].'syscp_bind.conf', 'w');
			fwrite($bindconf_file_handler, $bindconf_file);
			fclose($bindconf_file_handler);
			$debugMsg[] = '  cron_tasks: Task4 - syscp_bind.conf written';
			safe_exec($settings['system']['bindreload_command']);
			$debugMsg[] = '  cron_tasks: Task4 - Bind9 reloaded';
		}
	}
	if($db->num_rows($result) != 0)
	{
		$db->query("DELETE FROM `".TABLE_PANEL_TASKS."`");
	}
 
?>
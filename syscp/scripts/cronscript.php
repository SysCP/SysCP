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
 
	$lockfile='/tmp/syscp_cron.lock';
	$pathtophpfiles='/var/www/syscp';

	$filename = 'cronscript.php';
	if(file_exists($lockfile))
	{
		/**
		 * Do not proceed further if the lockfile exists.
		 */
		die('Lockfile ('.$lockfile.') exists. Exiting...');
	}
	else
	{
		exec('touch '.$lockfile);
	}

	/**
	 * Includes the Usersettings eg. MySQL-Username/Passwort etc.
	 */
	require("$pathtophpfiles/lib/userdata.inc.php");

	/**
	 * Includes the MySQL-Tabledefinitions etc.
	 */
	require("$pathtophpfiles/lib/tables.inc.php");

	/**
	 * Includes the MySQL-Connection-Class
	 */
	require("$pathtophpfiles/lib/class_mysqldb.php");
	$db=new db($sql['host'],$sql['user'],$sql['password'],$sql['db']);
	$db_root=new db($sql['host'],$sql['root_user'],$sql['root_password'],'');
	if($db->link_id == 0 || $db_root->link_id == 0)
	{
		/**
		 * Do not proceed further if no database connection could be established (either normal or root)
		 */
		unlink($lockfile);
		die('Cant connect to mysqlserver. Please check userdata.inc.php! Exiting...');
	}

	unset($sql['password']);
	unset($db->password);

	$result=$db->query("SELECT `settingid`, `settinggroup`, `varname`, `value` FROM `".TABLE_PANEL_SETTINGS."`");
	while($row=$db->fetch_array($result))
	{
		$settings["$row[settinggroup]"]["$row[varname]"]=$row['value'];
	}
	unset($row);
	unset($result);
	
	if(!isset($settings['panel']['version']) || $settings['panel']['version'] != $version)
	{
		/**
		 * Do not proceed further if the Database version is not the same as the script version
		 */
		unlink($lockfile);
		die('Version of File doesnt match Version of Database. Exiting...');
	}

	/**
	 * Includes the Functions
	 */
	require("$pathtophpfiles/lib/functions.php");

	/**
	 * LOOK INTO TASKS TABLE TO SEE IF THERE ARE ANY UNDONE JOBS
	 */
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
			$vhosts_file='# '.$settings['system']['apacheconf_directory'].'vhosts.conf'."\n".'# Created '.date('d.m.Y H:i')."\n".'# Do NOT manually edit this file, all changes will be deleted after the next domain change at the panel.'."\n"."\n";
			$vhosts_file.='NameVirtualHost '.$settings['system']['ipaddress']."\n"."\n";

			$vhosts_file.='# DummyHost for DefaultSite'."\n";
			$vhosts_file.='<VirtualHost '.$settings['system']['ipaddress'].'>'."\n";
			$vhosts_file.='</VirtualHost>'."\n"."\n";

			$result_domains=$db->query("SELECT `d`.`id`, `d`.`domain`, `d`.`customerid`, `d`.`documentroot`, `d`.`isemaildomain`, `d`.`openbasedir`, `d`.`safemode`, `d`.`specialsettings`, `c`.`loginname`, `c`.`guid` FROM `".TABLE_PANEL_DOMAINS."` `d` LEFT JOIN `".TABLE_PANEL_CUSTOMERS."` `c` USING(`customerid`) WHERE `d`.`deactivated` <> '1' ORDER BY `d`.`domain` ASC");
			while($domain=$db->fetch_array($result_domains))
			{
				$vhosts_file.='# Domain ID: '.$domain['id'].' - CustomerID: '.$domain['customerid'].' - CustomerLogin: '.$domain['loginname']."\n";
				$vhosts_file.='<VirtualHost '.$settings['system']['ipaddress'].'>'."\n";
				$vhosts_file.='  ServerName '.$domain['domain']."\n";
				$vhosts_file.='  ServerAlias www.'.$domain['domain']."\n";
				$vhosts_file.='  ServerAdmin webmaster@'.$domain['domain']."\n";
				$vhosts_file.='  DocumentRoot '.$domain['documentroot']."\n";
				if($domain['openbasedir'] == '1')
				{
					$vhosts_file.='  php_admin_value open_basedir '.$domain['documentroot']."\n";
				}
				if($domain['safemode'] == '1')
				{
					$vhosts_file.='  php_admin_flag safe_mode On '."\n";
				}
				if($settings['system']['documentrootstyle'] == 'domain')
				{
					$vhosts_file.='  ErrorLog '.$settings['system']['logfiles_directory'].$domain['loginname'].'-'.$domain['domain'].'-error.log'."\n";
					$vhosts_file.='  CustomLog '.$settings['system']['logfiles_directory'].$domain['loginname'].'-'.$domain['domain'].'-access.log combined'."\n";
					if (!file_exists($domain['documentroot']))
					{
						exec('mkdir -p '.$domain['documentroot']);
						exec('chown -R '.$domain['guid'].':'.$domain['guid'].' '.$domain['documentroot']);
					}
					if (!file_exists($domain['documentroot'].'/webalizer'))
					{
						exec('mkdir -p '.$domain['documentroot'].'/webalizer');
						exec('chown -R '.$domain['guid'].':'.$domain['guid'].' '.$domain['documentroot'].'/webalizer');
					}
				}
				else
				{
					$vhosts_file.='  ErrorLog '.$settings['system']['logfiles_directory'].$domain['loginname'].'-error.log'."\n";
					$vhosts_file.='  CustomLog '.$settings['system']['logfiles_directory'].$domain['loginname'].'-access.log combined'."\n";
				}
				$vhosts_file.=$domain['specialsettings']."\n";
				$vhosts_file.='</VirtualHost>'."\n";
				$vhosts_file.="\n";
			}

			$result_customers=$db->query("SELECT `customerid`, `loginname`, `guid`, `documentroot` FROM `".TABLE_PANEL_CUSTOMERS."` WHERE `createstdsubdomain`='1' AND `deactivated` <> '1'");
			while($customer=$db->fetch_array($result_customers))
			{
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
			exec($settings['system']['apachereload_command']);
		}

		/**
		 * TYPE=2 MEANS TO CREATE A NEW HOME AND CHOWN
		 */
		elseif($row['type'] == '2')
		{
			if(is_array($row['data']))
			{
				exec('mkdir -p '.$settings['system']['documentroot_prefix'].$row['data']['loginname'].'/webalizer');
				exec('mkdir -p '.$settings['system']['vmail_homedir'].$row['data']['loginname']);
				exec('cp -a '.$pathtophpfiles.'/templates/misc/standardcustomer/* '.$settings['system']['documentroot_prefix'].$row['data']['loginname'].'/');
				exec('chown -R '.$row['data']['uid'].':'.$row['data']['gid'].' '.$settings['system']['documentroot_prefix'].$row['data']['loginname']);
				exec('chown -R '.$settings['system']['vmail_uid'].':'.$settings['system']['vmail_gid'].' '.$settings['system']['vmail_homedir'].$row['data']['loginname']);
			}
		}

		/**
		 * TYPE=3 MEANS TO CREATE/CHANGE/DELETE A HTACCESS AND HTPASSWD
		 */
		elseif($row['type'] == '3')
		{
			if(is_array($row['data']))
			{
				$path=$row['data']['path'];
				$htpasswd_file='';
				$result_sub=$db->query("SELECT `username`, `password` FROM `".TABLE_PANEL_HTPASSWDS."` WHERE `path` = '".$row['data']['path']."'");
				if($db->num_rows($result_sub) != 0)
				{
					$htaccess_file='AuthType Basic'."\n";
					$htaccess_file.='AuthName "Restricted Area"'."\n";
					$htaccess_file.='AuthUserFile '.$row['data']['path'].'.htpasswd'."\n";
					$htaccess_file.='require valid-user'."\n";

					$htaccess_file_handler = fopen($row['data']['path'].'.htaccess', 'w');
					fwrite($htaccess_file_handler, $htaccess_file);
					fclose($htaccess_file_handler);
	
					while($row_sub=$db->fetch_array($result_sub))
					{
						$htpasswd_file.=$row_sub['username'].':'.$row_sub['password']."\n";
					}

					$htpasswd_file_handler = fopen($row['data']['path'].'.htpasswd', 'w');
					fwrite($htpasswd_file_handler, $htpasswd_file);
					fclose($htpasswd_file_handler);
				}
				else
				{
					unlink($row['data']['path'].'.htaccess');
					unlink($row['data']['path'].'.htpasswd');
				}
			}
		}

		/**
		 * TYPE=4 MEANS THAT SOMETHING IN THE BIND CONFIG HAS CHANGED. REBUILD syscp_bind.conf
		 */
		elseif($row['type'] == '4')
		{
			$bindconf_file='# '.$settings['system']['bindconf_directory'].'syscp_bind.conf'."\n".'# Created '.date('d.m.Y H:i')."\n".'# Do NOT manually edit this file, all changes will be deleted after the next domain change at the panel.'."\n"."\n";
			
			$result_domains=$db->query("SELECT `d`.`id`, `d`.`domain`, `d`.`customerid`, `d`.`zonefile`, `d`.`isemaildomain`, `c`.`loginname`, `c`.`guid` FROM `".TABLE_PANEL_DOMAINS."` `d` LEFT JOIN `".TABLE_PANEL_CUSTOMERS."` `c` USING(`customerid`) WHERE `d`.`isemaildomain` = '1' ORDER BY `d`.`domain` ASC");
			while($domain=$db->fetch_array($result_domains))
			{
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
			exec($settings['system']['bindreload_command']);
		}
	}
	if($db->num_rows($result) != 0)
	{
		$db->query("DELETE FROM `".TABLE_PANEL_TASKS."`");
	}

	/**
	 * DAILY TRAFFIC AND DISKUSAGE MESSURE
	 */

	if($settings['system']['last_traffic_run'] != date('dmy'))
	{
		$yesterday=time()-(60*60*24);
		$result=$db->query("SELECT * FROM `".TABLE_PANEL_CUSTOMERS."` ORDER BY `customerid` ASC");
		while($row=$db->fetch_array($result))
		{
			/**
			 * HTTP-Traffic
			 */
			$httptraffic = 0;
			if($settings['system']['documentrootstyle'] == 'domain')
			{
				$domain_result = $db->query("SELECT `domain`, `documentroot` FROM `".TABLE_PANEL_DOMAINS."` WHERE `customerid` = '".$row['customerid']."'");
				while($domain_row = $db->fetch_array($domain_result))
				{
					$httptraffic += webalizer_hist($row['loginname'].'-'.$domain_row['domain'], $domain_row['documentroot'], $domain_row['domain']);
				}
			}
			else
			{
				$httptraffic = webalizer_hist($row['loginname'], $row['documentroot'], $row['loginname']);
			}

			/**
			 * FTP-Traffic
			 */
			$mailtraffic=0;
			$ftptraffic=Array();
			$ftptraffic=$db->query_first("SELECT SUM(`up_bytes`) AS `up_bytes_sum`, SUM(`down_bytes`) AS `down_bytes_sum` FROM `".TABLE_PROFTPD_USERS."` WHERE `customerid`='".$row['customerid']."'");

			/**
			 * Total Traffic
			 */
			if(date('d',$yesterday)!='01')
			{
				$oldtraffic=$db->query_first("SELECT SUM(`http`) AS `http_sum`, SUM(`ftp_up`) AS `ftp_up_sum`, SUM(`ftp_down`) AS `ftp_down_sum`, SUM(`mail`) AS `mail_sum` FROM `".TABLE_PANEL_TRAFFIC."` WHERE `year`='".date('Y',$yesterday)."' AND `month`='".date('m',$yesterday)."' AND `day`<'".date('d',$yesterday)."' AND `customerid`='".$row['customerid']."'");
				$new['http']=$httptraffic-$oldtraffic['http_sum'];
				$new['ftp_up']=($ftptraffic['up_bytes_sum']/1024)-$oldtraffic['ftp_up_sum'];
				$new['ftp_down']=($ftptraffic['down_bytes_sum']/1024)-$oldtraffic['ftp_down_sum'];
				$new['mail']=$mailtraffic-$oldtraffic['mail_sum'];
			}
			else
			{
				$new['http']=$httptraffic;
				$new['ftp_up']=($ftptraffic['up_bytes_sum']/1024);
				$new['ftp_down']=($ftptraffic['down_bytes_sum']/1024);
				$new['mail']=$mailtraffic;
			}

			$new['all']=$httptraffic+($ftptraffic['up_bytes_sum']/1024)+($ftptraffic['down_bytes_sum']/1024)+$mailtraffic;
			$db->query("INSERT INTO `".TABLE_PANEL_TRAFFIC."` (`customerid`, `year`, `month`, `day`, `http`, `ftp_up`, `ftp_down`, `mail`) VALUES('$row[customerid]', '".date('Y',$yesterday)."', '".date('m',$yesterday)."', '".date('d',$yesterday)."', '$new[http]', '$new[ftp_up]', '$new[ftp_down]', '$new[mail]');");

			if(date('d')=='01')
			{
				$db->query("UPDATE `".TABLE_PROFTPD_USERS."` SET `up_bytes`='0', `down_bytes`='0' WHERE `customerid`='".$row['customerid']."'");
			}

			/**
			 * WebSpace-Usage
			 */
			$webspaceusage=0;
			exec('du -s '.$row['documentroot'], $back);
			foreach($back as $backrow)
			{
				$webspaceusage=explode(' ',$backrow);
			}
			$webspaceusage=intval($webspaceusage['0']);
			unset($back);

			/**
			 * MailSpace-Usage
			 */
			$emailusage=0;
			exec('du -s '.$settings['system']['vmail_homedir'].$row['loginname'], $back);
			foreach($back as $backrow)
			{
				$emailusage=explode(' ',$backrow);
			}
			$emailusage=intval($emailusage['0']);
			unset($back);

			/**
			 * MySQLSpace-Usage
			 */
			$mysqlusage=0;
			$databases_result=$db->query("SELECT `databasename` FROM `".TABLE_PANEL_DATABASES."` WHERE `customerid`='".$row['customerid']."'");
			while($database_row=$db->fetch_array($databases_result))
			{
				$mysql_usage_result=$db_root->query("SHOW TABLE STATUS FROM `".$database_row['databasename']."`");
				while($mysql_usage_row=$db_root->fetch_array($mysql_usage_result))
				{
					$mysqlusage+=$mysql_usage_row['Data_length']+$mysql_usage_row['Index_length'];
				}
			}
			$mysqlusage=$mysqlusage/1024;

			/**
			 * Total Usage
			 */
			$diskusage=$webspaceusage+$emailusage+$mysqlusage;
			$db->query("UPDATE `".TABLE_PANEL_CUSTOMERS."` SET `diskspace_used`='$diskusage', `traffic_used`='".$new['all']."' WHERE `customerid`='".$row['customerid']."'");

		}
		$db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='".date('dmy')."' WHERE `settinggroup`='system' AND `varname`='last_traffic_run'");
	}
	
	unlink($lockfile);

?>

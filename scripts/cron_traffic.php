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

	$debugMsg[] = '  cron_traffic: Started...';
	/**
	 * DAILY TRAFFIC AND DISKUSAGE MESSURE
	 */

	if($settings['system']['last_traffic_run'] != date('dmy'))
	{
		$debugMsg[] = '  cron_traffic: Traffic run started...';
		$yesterday=time()-(60*60*24);
		$result=$db->query("SELECT * FROM `".TABLE_PANEL_CUSTOMERS."` ORDER BY `customerid` ASC");
		while($row=$db->fetch_array($result))
		{
			/**
			 * HTTP-Traffic
			 */
			$debugMsg[] = '  cron_traffic: http traffic for '.$row['loginname'].' started...';
			$httptraffic = 0;
			$httptraffic = webalizer_hist($row['loginname'], $row['documentroot'].'/webalizer/', $row['loginname']);

			$speciallogfiles_domains = $db->query("SELECT `domain` FROM `".TABLE_PANEL_DOMAINS."` WHERE `customerid` = '".$row['customerid']."' AND `isemaildomain` = '1' AND `speciallogfile` = '1'");
			while($speciallogfiles_domains_row = $db->fetch_array($speciallogfiles_domains))
			{
				$httptraffic += webalizer_hist($row['loginname'].'-'.$speciallogfiles_domains_row['domain'], $row['documentroot'].'/webalizer/'.$speciallogfiles_domains_row['domain'].'/', $speciallogfiles_domains_row['domain']);
			}

			/**
			 * FTP-Traffic
			 */
			$debugMsg[] = '  cron_traffic: ftp traffic for '.$row['loginname'].' started...';
			$mailtraffic=0;
			$ftptraffic=Array();
			$ftptraffic=$db->query_first("SELECT SUM(`up_bytes`) AS `up_bytes_sum`, SUM(`down_bytes`) AS `down_bytes_sum` FROM `".TABLE_FTP_USERS."` WHERE `customerid`='".$row['customerid']."'");

			/**
			 * Total Traffic
			 */
			if(date('d',$yesterday)!='01')
			{
				$debugMsg[] = '  cron_traffic: total traffic for '.$row['loginname'].' started';
				$oldtraffic=$db->query_first("SELECT SUM(`http`) AS `http_sum`, SUM(`ftp_up`) AS `ftp_up_sum`, SUM(`ftp_down`) AS `ftp_down_sum`, SUM(`mail`) AS `mail_sum` FROM `".TABLE_PANEL_TRAFFIC."` WHERE `year`='".date('Y',$yesterday)."' AND `month`='".date('m',$yesterday)."' AND `day`<'".date('d',$yesterday)."' AND `customerid`='".$row['customerid']."'");
				$new['http']=$httptraffic-$oldtraffic['http_sum'];
				$new['ftp_up']=($ftptraffic['up_bytes_sum']/1024)-$oldtraffic['ftp_up_sum'];
				$new['ftp_down']=($ftptraffic['down_bytes_sum']/1024)-$oldtraffic['ftp_down_sum'];
				$new['mail']=$mailtraffic-$oldtraffic['mail_sum'];
			}
			else
			{
				$debugMsg[] = '  cron_traffic: (new month) total traffic for '.$row['loginname'].' started';
				$new['http']=$httptraffic;
				$new['ftp_up']=($ftptraffic['up_bytes_sum']/1024);
				$new['ftp_down']=($ftptraffic['down_bytes_sum']/1024);
				$new['mail']=$mailtraffic;
			}

			$new['all']=$httptraffic+($ftptraffic['up_bytes_sum']/1024)+($ftptraffic['down_bytes_sum']/1024)+$mailtraffic;
			$db->query("INSERT INTO `".TABLE_PANEL_TRAFFIC."` (`customerid`, `year`, `month`, `day`, `http`, `ftp_up`, `ftp_down`, `mail`) VALUES('$row[customerid]', '".date('Y',$yesterday)."', '".date('m',$yesterday)."', '".date('d',$yesterday)."', '$new[http]', '$new[ftp_up]', '$new[ftp_down]', '$new[mail]');");

			if(date('d')=='01')
			{
				$db->query("UPDATE `".TABLE_FTP_USERS."` SET `up_bytes`='0', `down_bytes`='0' WHERE `customerid`='".$row['customerid']."'");
			}

			/**
			 * WebSpace-Usage
			 */
			$debugMsg[] = '  cron_traffic: calculating webspace usage for '.$row['loginname'];
			$webspaceusage=0;
			$back = safe_exec('du -s '.$row['documentroot']);
			foreach($back as $backrow)
			{
				$webspaceusage=explode(' ',$backrow);
			}
			$webspaceusage=intval($webspaceusage['0']);
			unset($back);

			/**
			 * MailSpace-Usage
			 */
			$debugMsg[] = '  cron_traffic: calculating mailspace usage for '.$row['loginname'];
			$emailusage=0;
			$back = safe_exec('du -s '.$settings['system']['vmail_homedir'].$row['loginname']);
			foreach($back as $backrow)
			{
				$emailusage=explode(' ',$backrow);
			}
			$emailusage=intval($emailusage['0']);
			unset($back);

			/**
			 * MySQLSpace-Usage
			 */
			$debugMsg[] = '  cron_traffic: calculating mysqlspace usage for '.$row['loginname'];
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

 
?>
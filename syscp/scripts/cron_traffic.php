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
 * @author     Martin Burchert <eremit@syscp.org>
 * @license    GPLv2 http://files.syscp.org/misc/COPYING.txt
 * @package    System
 * @version    $Id$
 */

	if(@php_sapi_name() != 'cli' && @php_sapi_name() != 'cgi' && @php_sapi_name() != 'cgi-fcgi')
	{
		die('This script will only work in the shell.');
	}

	fwrite( $debugHandler, '  cron_traffic: Started...');
	/**
	 * DAILY TRAFFIC AND DISKUSAGE MESSURE
	 */

	if($settings['system']['last_traffic_run'] != date('dmy'))
	{
		fwrite( $debugHandler, '  cron_traffic: Traffic run started...' . "\n");
		$yesterday=time()-(60*60*24);

		$admin_traffic = Array();

		$result=$db->query("SELECT * FROM `".TABLE_PANEL_CUSTOMERS."` ORDER BY `customerid` ASC");
		while($row=$db->fetch_array($result))
		{
			/**
			 * HTTP-Traffic
			 */
			fwrite( $debugHandler, '  cron_traffic: http traffic for '.$row['loginname'].' started...' . "\n");
			$httptraffic = 0;
			$httptraffic = floatval(webalizer_hist($row['loginname'], $row['documentroot'].'/webalizer/', $row['loginname']));

			$speciallogfiles_domains = $db->query("SELECT `domain` FROM `".TABLE_PANEL_DOMAINS."` WHERE `customerid` = '".
				(int)$row['customerid']."' AND `parentdomainid` = 0 AND `speciallogfile` = '1'");
			while($speciallogfiles_domains_row = $db->fetch_array($speciallogfiles_domains))
			{
				$httptraffic += floatval(webalizer_hist($row['loginname'].'-'.$speciallogfiles_domains_row['domain'], $row['documentroot'].'/webalizer/'.$speciallogfiles_domains_row['domain'].'/', $speciallogfiles_domains_row['domain']));
			}

			/**
			 * FTP-Traffic
			 */
			fwrite( $debugHandler, '  cron_traffic: ftp traffic for '.$row['loginname'].' started...' . "\n");
			$mailtraffic=0;
			$ftptraffic=Array();
			$ftptraffic=floatval($db->query_first("SELECT SUM(`up_bytes`) AS `up_bytes_sum`, SUM(`down_bytes`) AS `down_bytes_sum` FROM `".TABLE_FTP_USERS."` WHERE `customerid`='".(int)$row['customerid']."'"));

			/**
			 * Total Traffic
			 */
			if(date('d',$yesterday)!='01')
			{
				fwrite( $debugHandler, '  cron_traffic: total traffic for '.$row['loginname'].' started' . "\n");
				$oldtraffic=$db->query_first("SELECT SUM(`http`) AS `http_sum`, SUM(`ftp_up`) AS `ftp_up_sum`, SUM(`ftp_down`) AS `ftp_down_sum`, SUM(`mail`) AS `mail_sum` FROM `".TABLE_PANEL_TRAFFIC."` WHERE `year`='".date('Y',$yesterday)."' AND `month`='".date('m',$yesterday)."' AND `day`<'".date('d',$yesterday)."' AND `customerid`='".(int)$row['customerid']."'");
				$new['http']=floatval($httptraffic-$oldtraffic['http_sum']);
				$new['ftp_up']=floatval(($ftptraffic['up_bytes_sum']/1024)-$oldtraffic['ftp_up_sum']);
				$new['ftp_down']=floatval(($ftptraffic['down_bytes_sum']/1024)-$oldtraffic['ftp_down_sum']);
				$new['mail']=floatval($mailtraffic-$oldtraffic['mail_sum']);
			}
			else
			{
				fwrite( $debugHandler, '  cron_traffic: (new month) total traffic for '.$row['loginname'].' started' . "\n");
				$new['http']=floatval($httptraffic);
				$new['ftp_up']=floatval(($ftptraffic['up_bytes_sum']/1024));
				$new['ftp_down']=floatval(($ftptraffic['down_bytes_sum']/1024));
				$new['mail']=floatval($mailtraffic);
			}

			if(!isset($admin_traffic[$row['adminid']]))
			{
				$admin_traffic[$row['adminid']]['http'] = 0 ;
				$admin_traffic[$row['adminid']]['ftp_up'] = 0 ;
				$admin_traffic[$row['adminid']]['ftp_down'] = 0 ;
				$admin_traffic[$row['adminid']]['mail'] = 0 ;
			}

			$new['all']=$httptraffic+($ftptraffic['up_bytes_sum']/1024)+($ftptraffic['down_bytes_sum']/1024)+$mailtraffic;
			$db->query("REPLACE INTO `".TABLE_PANEL_TRAFFIC."` (`customerid`, `year`, `month`, `day`, `http`, `ftp_up`, `ftp_down`, `mail`) VALUES('".
				(int)$row['customerid']."', '".date('Y',$yesterday)."', '".date('m',$yesterday)."', '".date('d',$yesterday)."', '".
				(float)$new['http']."', '".(float)$new['ftp_up']."', '".(float)$new['ftp_down']."', '".(float)$new['mail']."')");
			$admin_traffic[$row['adminid']]['http'] += floatval($httptraffic);
			$admin_traffic[$row['adminid']]['ftp_up'] += floatval($ftptraffic['up_bytes_sum']/1024);
			$admin_traffic[$row['adminid']]['ftp_down'] += floatval($ftptraffic['down_bytes_sum']/1024);
			$admin_traffic[$row['adminid']]['mail'] += floatval($mailtraffic);

			if(date('d')=='01')
			{
				$db->query("UPDATE `".TABLE_FTP_USERS."` SET `up_bytes`='0', `down_bytes`='0' WHERE `customerid`='".(int)$row['customerid']."'");
			}

			/**
			 * WebSpace-Usage
			 */
			fwrite( $debugHandler, '  cron_traffic: calculating webspace usage for '.$row['loginname'] . "\n");
			$webspaceusage=0;
			$back = safe_exec('du -s '.escapeshellarg($row['documentroot']).'');
			foreach($back as $backrow)
			{
				$webspaceusage=explode(' ',$backrow);
			}
			$webspaceusage=floatval($webspaceusage['0']);
			unset($back);

			/**
			 * MailSpace-Usage
			 */
			fwrite( $debugHandler, '  cron_traffic: calculating mailspace usage for '.$row['loginname'] . "\n");
			$emailusage=0;
			$back = safe_exec('du -s '.escapeshellarg($settings['system']['vmail_homedir'].$row['loginname']).'');
			foreach($back as $backrow)
			{
				$emailusage=explode(' ',$backrow);
			}
			$emailusage=floatval($emailusage['0']);
			unset($back);

			/**
			 * MySQLSpace-Usage
			 */
			fwrite( $debugHandler, '  cron_traffic: calculating mysqlspace usage for '.$row['loginname'] . "\n");
			$mysqlusage=0;
			$databases_result=$db->query("SELECT `databasename` FROM `".TABLE_PANEL_DATABASES."` WHERE `customerid`='".(int)$row['customerid']."'");
			while($database_row=$db->fetch_array($databases_result))
			{
				$mysql_usage_result=$db_root->query("SHOW TABLE STATUS FROM `".$db_root->escape($database_row['databasename'])."`");
				while($mysql_usage_row=$db_root->fetch_array($mysql_usage_result))
				{
					$mysqlusage+=floatval($mysql_usage_row['Data_length']+$mysql_usage_row['Index_length']);
				}
			}
			$mysqlusage=floatval($mysqlusage/1024);

			/**
			 * Total Usage
			 */
			$diskusage=floatval($webspaceusage+$emailusage+$mysqlusage);
			$db->query("UPDATE `".TABLE_PANEL_CUSTOMERS."` SET `diskspace_used`='".(float)$diskusage."', `traffic_used`='".
				(float)$new['all']."' WHERE `customerid`='".(int)$row['customerid']."'");

		}

		/**
		 * Admin Usage
		 */
		$result=$db->query("SELECT `adminid` FROM `".TABLE_PANEL_ADMINS."` ORDER BY `adminid` ASC");
		while($row=$db->fetch_array($result))
		{
			if(isset($admin_traffic[$row['adminid']]))
			{
				$admin_traffic[$row['adminid']]['all'] = floatval(floatval($admin_traffic[$row['adminid']]['http']) + floatval($admin_traffic[$row['adminid']]['ftp_up']) + floatval($admin_traffic[$row['adminid']]['ftp_down']) + floatval($admin_traffic[$row['adminid']]['mail']));
				$db->query("REPLACE INTO `".TABLE_PANEL_TRAFFIC_ADMINS."` (`adminid`, `year`, `month`, `day`, `http`, `ftp_up`, `ftp_down`, `mail`) VALUES('".
					(int)$row['adminid']."', '".date('Y',$yesterday)."', '".date('m',$yesterday)."', '".date('d',$yesterday).
					"', '".(float)$admin_traffic[$row['adminid']]['http']."', '".(float)$admin_traffic[$row['adminid']]['ftp_up'].
					"', '".(float)$admin_traffic[$row['adminid']]['ftp_down']."', '".(float)$admin_traffic[$row['adminid']]['mail']."')");
				$db->query("UPDATE `".TABLE_PANEL_ADMINS."` SET `traffic_used`='".(float)$admin_traffic[$row['adminid']]['all']."' WHERE `adminid`='".(float)$row['adminid']."'");
			}
		}

		$db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='".date('dmy')."' WHERE `settinggroup`='system' AND `varname`='last_traffic_run'");
	}

?>
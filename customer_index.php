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

	define('AREA', 'customer');

	/**
	 * Include our init.php, which manages Sessions, Language etc.
	 */
	require("./lib/init.php");

	if($action == 'logout')
	{
		$db->query("DELETE FROM `".TABLE_PANEL_SESSIONS."` WHERE `userid` = '{$userinfo['customerid']}' AND `adminsession` = '0'");
		header("Location: ./index.php");
		exit;
	}

	if($page=='overview')
	{
		$domains = '';
		$result=$db->query("SELECT `domain` FROM `".TABLE_PANEL_DOMAINS."` WHERE `customerid`='".$userinfo['customerid']."' AND `isemaildomain`='1'");
		while($row=$db->fetch_array($result))
		{
			$row['domain'] = $idna_convert->decode($row['domain']);
			if($domains == '')
			{
				$domains=$row['domain'];
			}
			else
			{
				$domains.=', '.$row['domain'];
			}
		}
		
		$userinfo['email'] = $idna_convert->decode($userinfo['email']);

		$yesterday=time()-(60*60*24);
		$month=date('M Y', $yesterday);
/*		$traffic=$db->query_first("SELECT SUM(http) AS http_sum, SUM(ftp_up) AS ftp_up_sum, SUM(ftp_down) AS ftp_down_sum, SUM(mail) AS mail_sum FROM ".TABLE_PANEL_TRAFFIC." WHERE year='".date('Y')."' AND month='".date('m')."' AND day<='".date('d')."' AND customerid='".$userinfo['customerid']."'");
		$userinfo['traffic_used']=$traffic['http_sum']+$traffic['ftp_up_sum']+$traffic['ftp_down_sum']+$traffic['mail_sum'];*/

		$userinfo['diskspace']=round($userinfo['diskspace']/1024,4);
		$userinfo['diskspace_used']=round($userinfo['diskspace_used']/1024,4);
		$userinfo['traffic']=round($userinfo['traffic']/(1024*1024),4);
		$userinfo['traffic_used']=round($userinfo['traffic_used']/(1024*1024),4);

		$userinfo = str_replace_array('-1', $lng['customer']['unlimited'], $userinfo, 'diskspace traffic mysqls emails email_forwarders ftps subdomains');

		eval("echo \"".getTemplate("index/index")."\";");
	}

	elseif($page=='change_password')
	{
		if(isset($_POST['send']) && $_POST['send']=='send')
		{
			$old_password=addslashes($_POST['old_password']);
			if(md5($old_password) != $userinfo['password'])
			{
				standard_error('oldpasswordnotcorrect');
				exit;
			}
			$new_password=addslashes($_POST['new_password']);
			$new_password_confirm=addslashes($_POST['new_password_confirm']);
			if($old_password=='' || $new_password=='' || $new_password_confirm=='' || $new_password!=$new_password_confirm)
			{
				standard_error('notallreqfieldsorerrors');
				exit;
			}
			else
			{
				$db->query("UPDATE `".TABLE_PANEL_CUSTOMERS."` SET `password`='".md5($new_password)."' WHERE `customerid`='".$userinfo['customerid']."' AND `password`='".md5($old_password)."'");
				if(isset($_POST['change_main_ftp']) && $_POST['change_main_ftp']=='true')
				{
					$db->query("UPDATE `".TABLE_FTP_USERS."` SET `password`='$new_password' WHERE `customerid`='".$userinfo['customerid']."' AND `username`='".$userinfo['loginname']."'");
				}
				header("Location: $filename?s=$s");
			}
		}
		else {
			eval("echo \"".getTemplate("index/change_password")."\";");
		}
	}


?>

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

	if($action == 'logout')
	{
		$db->query("DELETE FROM `".TABLE_PANEL_SESSIONS."` WHERE `userid` = '{$userinfo['adminid']}' AND `adminsession` = '1'");
		redirectTo ( 'index.php' ) ;
		exit;
	}

	if(isset($_POST['id']))
	{
		$id=intval($_POST['id']);
	}
	elseif(isset($_GET['id']))
	{
		$id=intval($_GET['id']);
	}

	if($page=='overview')
	{
		$overview = $db->query_first("SELECT COUNT(*) AS `number_customers`,
				SUM(`diskspace_used`) AS `diskspace_used`,
				SUM(`mysqls_used`) AS `mysqls_used`,
				SUM(`emails_used`) AS `emails_used`,
				SUM(`email_accounts_used`) AS `email_accounts_used`,
				SUM(`email_forwarders_used`) AS `email_forwarders_used`,
				SUM(`ftps_used`) AS `ftps_used`,
				SUM(`subdomains_used`) AS `subdomains_used`,
				SUM(`traffic_used`) AS `traffic_used`
				FROM `".TABLE_PANEL_CUSTOMERS."`".( $userinfo['customers_see_all'] ? '' : " WHERE `adminid` = '{$userinfo['adminid']}' "));
		$overview['traffic_used']=round($overview['traffic_used']/(1024*1024),4);
		$overview['diskspace_used']=round($overview['diskspace_used']/1024,2);
		$number_domains = $db->query_first("SELECT COUNT(*) AS `number_domains` FROM `".TABLE_PANEL_DOMAINS."` WHERE `parentdomainid`='0'".( $userinfo['customers_see_all'] ? '' : " AND `adminid` = '{$userinfo['adminid']}' "));
		$overview['number_domains'] = $number_domains['number_domains'];

		$phpversion = phpversion();
		$phpmemorylimit = @ini_get("memory_limit");
		$mysqlserverversion = mysql_get_server_info();
		$mysqlclientversion = mysql_get_client_info();
		$webserverinterface = strtoupper(@php_sapi_name());
		
		if( (isset($_GET['lookfornewversion']) && $_GET['lookfornewversion'] == 'yes') || (isset($lookfornewversion) && $lookfornewversion == 'yes') )
		{
			$latestversion = @file('http://syscp.de/version/version.php');
			if(is_array($latestversion))
			{
				$lookfornewversion_lable = $latestversion[0];
				$lookfornewversion_link = $latestversion[1];
			}
			else
			{
				$lookfornewversion_lable = $lng['admin']['lookfornewversion']['error'];
				$lookfornewversion_link = "$filename?s=$s&amp;page=$page&amp;lookfornewversion=yes";
			}
		}
		else
		{
			$lookfornewversion_lable = $lng['admin']['lookfornewversion']['clickhere'];
			$lookfornewversion_link = "$filename?s=$s&amp;page=$page&amp;lookfornewversion=yes";
		}

		$userinfo['diskspace']=round($userinfo['diskspace']/1024,4);
		$userinfo['diskspace_used']=round($userinfo['diskspace_used']/1024,4);
		$userinfo['traffic']=round($userinfo['traffic']/(1024*1024),4);
		$userinfo['traffic_used']=round($userinfo['traffic_used']/(1024*1024),4);

		$userinfo = str_replace_array('-1', $lng['customer']['unlimited'], $userinfo, 'customers domains diskspace traffic mysqls emails email_accounts email_forwarders ftps subdomains');

		$cronlastrun = date("d.m.Y H:i:s", $settings['system']['lastcronrun']);
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

			if($old_password=='')
			{
				standard_error(array('stringisempty','oldpassword'));
			}
			elseif($new_password == '')
			{
				standard_error(array('stringisempty','newpassword'));
			}
			elseif($new_password_confirm == '')
			{
				standard_error(array('stringisempty','newpasswordconfirm'));
			}
			elseif($new_password!=$new_password_confirm)
			{
				standard_error('newpasswordconfirmerror');
			}

			else
			{
				$db->query("UPDATE `".TABLE_PANEL_ADMINS."` SET `password`='".md5($new_password)."' WHERE `adminid`='".$userinfo['adminid']."' AND `password`='".md5($old_password)."'");
		    	redirectTo ( $filename , Array ( 's' => $s ) ) ;
			}
		}
		else {
			eval("echo \"".getTemplate("index/change_password")."\";");
		}
	}
	
	elseif($page=='change_language')
	{
		if(isset($_POST['send']) && $_POST['send']=='send')
		{
			$def_language = addslashes ( htmlentities ( _html_entity_decode ( $_POST['def_language'] ) ) ) ;
			if(isset($languages[$def_language]))
			{
				$db->query("UPDATE `".TABLE_PANEL_ADMINS."` SET `def_language`='".$def_language."' WHERE `adminid`='".$userinfo['adminid']."'");
				$db->query("UPDATE `".TABLE_PANEL_SESSIONS."` SET `language`='".$def_language."' WHERE `hash`='".$s."'");
			}
			redirectTo ( $filename , Array ( 's' => $s ) ) ;
		}
		else
		{
			$language_options = '';
			while(list($language_file, $language_name) = each($languages))
			{
				$language_options .= makeoption($language_name, $language_file, $userinfo['def_language']);
			}
			eval("echo \"".getTemplate("index/change_language")."\";");
		}
	}

?>

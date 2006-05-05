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
 * @version    $Id:admin_index.php 460 2006-04-23 15:07:49 +0200 (So, 23 Apr 2006) martin $
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

	if($config->get('env.action') == 'logout')
	{
		$log->info( 'User logged out successfully...' );
		$authLog->info( sprintf('User %s logged out successfully...', $userinfo['loginname'] ) );
		
		$db->query("DELETE FROM `".TABLE_PANEL_SESSIONS."` WHERE `userid` = '{$userinfo['adminid']}' AND `adminsession` = '1'");
		redirectTo ( 'index.php' ) ;
		exit;
	}

//	if(isset($_POST['id']))
//	{
//		$id=intval($_POST['id']);
//	}
//	elseif(isset($_GET['id']))
//	{
//		$id=intval($_GET['id']);
//	}

	if($config->get('env.page') == 'overview' )
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
		
		$userinfo['diskspace']=round($userinfo['diskspace']/1024,4);
		$userinfo['diskspace_used']=round($userinfo['diskspace_used']/1024,4);
		$userinfo['traffic']=round($userinfo['traffic']/(1024*1024),4);
		$userinfo['traffic_used']=round($userinfo['traffic_used']/(1024*1024),4);

		$userinfo = str_replace_array('-1', $lng['customer']['unlimited'], $userinfo, 'customers domains diskspace traffic mysqls emails email_accounts email_forwarders ftps subdomains');

		$cronlastrun = date("d.m.Y H:i:s", $config->get('system.lastcronrun'));
		eval("echo \"".getTemplate("index/index")."\";");
	}
	
	elseif($config->get('env.page')=='change_password')
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
		    	redirectTo ( $config->get('env.filename') , Array ( 's' => $config->get('env.s') ) ) ;
			}
		}
		else {
			eval("echo \"".getTemplate("index/change_password")."\";");
		}
	}
	
	elseif($config->get('env.page')=='change_language')
	{
		if(isset($_POST['send']) && $_POST['send']=='send')
		{
			$def_language = addslashes ( htmlentities ( _html_entity_decode ( $_POST['def_language'] ) ) ) ;
			if( $language->hasLanguage( $def_language ) )
			{
				$db->query("UPDATE `".TABLE_PANEL_ADMINS."` SET `def_language`='".$def_language."' WHERE `adminid`='".$userinfo['adminid']."'");
				$db->query("UPDATE `".TABLE_PANEL_SESSIONS."` SET `language`='".$def_language."' WHERE `hash`='".$config->get('env.s')."'");
			}
			redirectTo ( $config->get('env.filename') , Array ( 's' => $config->get('env.s') ) ) ;
		}
		else
		{
			$language_options = '';
			$languages = $language->getList();
			while(list($language_file, $language_name) = each($languages))
			{
				$language_options .= makeoption($language_name, $language_file, $userinfo['def_language']);
			}
			eval("echo \"".getTemplate("index/change_language")."\";");
		}
	}

?>

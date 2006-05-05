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
 * @subpackage Panel.Customer
 * @license    GPLv2 http://files.syscp.org/misc/COPYING.txt
 * @version    $Id$
 * 
 * @todo code cleanup is needed, exspecially in queries, loops, conditions
 * @todo code restructuring is needed, we need to seperate view, controller and model logic,
 *       as a preparation towards MVC patterns
 * @todo Changelanguage broken!
 */

	define('AREA', 'customer');

	/**
	 * Include our init.php, which manages Sessions, Language etc.
	 */
	require_once '../lib/init.php';

	if($config->get('env.action') == 'logout')
	{
		$db->query("DELETE FROM `".TABLE_PANEL_SESSIONS."` WHERE `userid` = '{$userinfo['customerid']}' AND `adminsession` = '0'");
		redirectTo ( 'index.php' ) ;
		exit;
	}

	if($config->get('env.page')=='overview')
	{
		$domains = '';
		$result=$db->query("SELECT `domain` FROM `".TABLE_PANEL_DOMAINS."` WHERE `customerid`='".$userinfo['customerid']."' AND `parentdomainid`='0' AND `id` <> '" . $userinfo['standardsubdomain'] . "' ");
		while($row=$db->fetch_array($result))
		{
			$row['domain'] = $idna->decode($row['domain']);
			if($domains == '')
			{
				$domains=$row['domain'];
			}
			else
			{
				$domains.=', '.$row['domain'];
			}
		}
		
		$userinfo['email'] = $idna->decode($userinfo['email']);

		$yesterday=time()-(60*60*24);
		$month=date('M Y', $yesterday);
/*		$traffic=$db->query_first("SELECT SUM(http) AS http_sum, SUM(ftp_up) AS ftp_up_sum, SUM(ftp_down) AS ftp_down_sum, SUM(mail) AS mail_sum FROM ".TABLE_PANEL_TRAFFIC." WHERE year='".date('Y')."' AND month='".date('m')."' AND day<='".date('d')."' AND customerid='".$userinfo['customerid']."'");
		$userinfo['traffic_used']=$traffic['http_sum']+$traffic['ftp_up_sum']+$traffic['ftp_down_sum']+$traffic['mail_sum'];*/

		$userinfo['diskspace']=round($userinfo['diskspace']/1024,4);
		$userinfo['diskspace_used']=round($userinfo['diskspace_used']/1024,4);
		$userinfo['traffic']=round($userinfo['traffic']/(1024*1024),4);
		$userinfo['traffic_used']=round($userinfo['traffic_used']/(1024*1024),4);

		$userinfo = str_replace_array('-1', $lng['customer']['unlimited'], $userinfo, 'diskspace traffic mysqls emails email_accounts email_forwarders ftps subdomains');

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
				$db->query("UPDATE `".TABLE_PANEL_CUSTOMERS."` SET `password`='".md5($new_password)."' WHERE `customerid`='".$userinfo['customerid']."' AND `password`='".md5($old_password)."'");
				if(isset($_POST['change_main_ftp']) && $_POST['change_main_ftp']=='true')
				{
					$db->query("UPDATE `".TABLE_FTP_USERS."` SET `password`=ENCRYPT('$new_password') WHERE `customerid`='".$userinfo['customerid']."' AND `username`='".$userinfo['loginname']."'");
				}
				redirectTo ( $config->get('env.filename') , Array ( 's' => $config->get('env.s') ) ) ;
			}
		}
		else
		{
			eval("echo \"".getTemplate("index/change_password")."\";");
		}
	}

	elseif($config->get('env.page')=='change_language')
	{
		if(isset($_POST['send']) && $_POST['send']=='send')
		{
			$def_language = addslashes ( htmlentities ( _html_entity_decode ( $_POST['def_language'] ) ) ) ;
			if(isset($languages[$def_language]))
			{
				$db->query("UPDATE `".TABLE_PANEL_CUSTOMERS."` SET `def_language`='".$def_language."' WHERE `customerid`='".$userinfo['customerid']."'");
				$db->query("UPDATE `".TABLE_PANEL_SESSIONS."` SET `language`='".$def_language."' WHERE `hash`='".$s."'");
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

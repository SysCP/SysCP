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

	define('AREA', 'login');

	/**
	 * Include our init.php, which manages Sessions, Language etc.
	 */
	require("./lib/init.php");

	if($action == '')
	{
		$action = 'login';
	}

	if($action == 'logout')
	{
		$db->query("DELETE FROM `".TABLE_PANEL_SESSIONS."` WHERE `userid` = '{$userinfo['customerid']}' AND `adminsession` = '{$userinfo['adminsession']}'");
		header("Location: ./index.php");
		exit;
	}

	if($action=='login')
	{
		if(isset($_POST['send']) && $_POST['send']=='send')
		{
			$loginname = addslashes($_POST['loginname']);
			$password = addslashes($_POST['password']);

			if( substr($loginname, 0, strlen($settings['customer']['accountprefix'])) == $settings['customer']['accountprefix'])
			{
				$userinfo = $db->query_first("SELECT `customerid` AS `userid` FROM `".TABLE_PANEL_CUSTOMERS."` WHERE `loginname` = '$loginname' AND `password` = '".md5($password)."' AND `deactivated` <> '1'");
				$userinfo['adminsession'] = '0';
			}
			else/*if( substr($loginname, 0, strlen($settings['admin']['accountprefix'])) == $settings['admin']['accountprefix'])*/
			{
				$userinfo = $db->query_first("SELECT `adminid` AS `userid` FROM `".TABLE_PANEL_ADMINS."` WHERE `loginname` = '$loginname' AND `password` = '".md5($password)."' AND `deactivated` <> '1'");
				$userinfo['adminsession'] = '1';
			}

			if(isset($userinfo['userid']) && $userinfo['userid'] != '')
			{
				$s = md5(uniqid(microtime(),1));

				if(isset($_POST['language']) && isset($languages[$_POST['language']]))
				{
					$language = addslashes($_POST['language']);
				}
				else
				{
					$language = '';
				}

				$db->query("DELETE FROM `".TABLE_PANEL_SESSIONS."` WHERE `userid` = '{$userinfo['userid']}' AND `adminsession` = '{$userinfo['adminsession']}'");
				$db->query("INSERT INTO `".TABLE_PANEL_SESSIONS."` (`hash`, `userid`, `ipaddress`, `useragent`, `lastactivity`, `language`, `adminsession`) VALUES ('$s', '{$userinfo['userid']}', '".addslashes($remote_addr)."', '".addslashes($http_user_agent)."', '".time()."', '$language', '{$userinfo['adminsession']}')");
				
				if($userinfo['adminsession'] == '1')
				{
					header("Location: ./admin_index.php?s=$s");
				}
				else
				{
					header("Location: ./customer_index.php?s=$s");
				}
			}
			else
			{
				standard_error('login');
			}
		}
		else
		{
			$language_options = '';
			while(list($language_file, $language_name) = each($languages))
			{
				$language_options .= makeoption($language_name, $language_file, $language);
			}
			eval("echo \"".getTemplate("login")."\";");
		}
	}

?>

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
 * @license    GPLv2 http://files.syscp.org/misc/COPYING.txt
 * @package    Panel
 * @version    $Id$
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

	if($action=='login')
	{
		if(isset($_POST['send']) && $_POST['send']=='send')
		{
			$loginname = validate($_POST['loginname'], 'loginname');
			$password = validate($_POST['password'], 'password');

			$row = $db->query_first("SELECT `loginname` AS `customer` FROM `".TABLE_PANEL_CUSTOMERS.
				"` WHERE `loginname`='".$db->escape($loginname)."'");
			if ($row['customer'] == $loginname)
			{
				$table = "`".TABLE_PANEL_CUSTOMERS."`";
				$uid = 'customerid';
				$adminsession = '0';
			}
			else
			{
				$row = $db->query_first("SELECT `loginname` AS `admin` FROM `".TABLE_PANEL_ADMINS.
					"` WHERE `loginname`='".$db->escape($loginname)."'");
				if ($row['admin'] == $loginname)
				{
					$table = "`".TABLE_PANEL_ADMINS."`";
					$uid = 'adminid';
					$adminsession = '1';
				}
				else
				{
					standard_error('login');
					exit;
				}
			}

			$userinfo = $db->query_first("SELECT * FROM $table WHERE `loginname`='".$db->escape($loginname)."'");
			if ($userinfo['loginfail_count'] >= $settings['login']['maxloginattempts'] && $userinfo['lastlogin_fail'] > (time()-$settings['login']['deactivatetime']))
			{
				standard_error('login_blocked');
				exit;
			}
			elseif ($userinfo['password'] == md5($password))
			{
				// login correct
				// reset loginfail_counter, set lastlogin_succ
				$db->query("UPDATE $table SET `lastlogin_succ`='".time()."', `loginfail_count`='0' WHERE `$uid`='".(int)$userinfo[$uid]."'");
				$userinfo['userid'] = $userinfo[$uid];
				$userinfo['adminsession'] = $adminsession;
			}
			else
			{
				// login incorrect
				$db->query("UPDATE $table SET `lastlogin_fail`='".time()."', `loginfail_count`=`loginfail_count`+1 WHERE `$uid`='".(int)$userinfo[$uid]."'");
				unset($userinfo);
				standard_error('login');
				exit;
			}

			if(isset($userinfo['userid']) && $userinfo['userid'] != '')
			{
				$s = md5(uniqid(microtime(),1));
				
				if(isset($_POST['language']))
				{
					$language = validate($_POST['language'], 'language');
					if($language == 'profile')
					{
						$language = $userinfo['def_language'];
					}
					elseif(!isset($languages[$language]))
					{
						$language = $settings['panel']['standardlanguage'];
					}
				}
				else
				{
					$language = $settings['panel']['standardlanguage'];
				}

				$db->query("DELETE FROM `".TABLE_PANEL_SESSIONS."` WHERE `userid` = '".(int)$userinfo['userid']."' AND `adminsession` = '".$db->escape($userinfo['adminsession'])."'");
				$db->query("INSERT INTO `".TABLE_PANEL_SESSIONS."` (`hash`, `userid`, `ipaddress`, `useragent`, `lastactivity`, `language`, `adminsession`) VALUES ('".
					$db->escape($s)."', '".(int)$userinfo['userid']."', '".$db->escape($remote_addr)."', '".
					$db->escape($http_user_agent)."', '".time()."', '".$db->escape($language)."', '".$db->escape($userinfo['adminsession'])."')");
				
				if($userinfo['adminsession'] == '1')
				{
					redirectTo ( 'admin_index.php' , Array ( 's' => $s ) );
				}
				else
				{
					redirectTo ( 'customer_index.php' , Array ( 's' => $s ) );
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
			$language_options .= makeoption($lng['login']['profile_lng'], 'profile', 'profile', true, true);
			while(list($language_file, $language_name) = each($languages))
			{
				$language_options .= makeoption($language_name, $language_file, 'profile', true);
			}
			eval("echo \"".getTemplate("login")."\";");
		}
	}

?>

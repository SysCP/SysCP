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

	$filename = basename($_SERVER['PHP_SELF']);

	if(!file_exists('./lib/userdata.inc.php'))
	{
		die('You have to <a href="./install/install.php">configure</a> SysCP first!');
	}

	/**
	 * Includes the Usersettings eg. MySQL-Username/Passwort etc.
	 */
	require('./lib/userdata.inc.php');

	/**
	 * Includes the MySQL-Tabledefinitions etc.
	 */
	require('./lib/tables.inc.php');

	/**
	 * Includes the MySQL-Connection-Class
	 */
	require('./lib/class_mysqldb.php');
	$db = new db($sql['host'],$sql['user'],$sql['password'],$sql['db']);
	unset($sql['password']);
	unset($db->password);

	/**
	 * Selects settings from MySQL-Table
	 */
	$result = $db->query("SELECT `settinggroup`, `varname`, `value` FROM `".TABLE_PANEL_SETTINGS."`");
	while($row = $db->fetch_array($result))
	{
		$settings["{$row['settinggroup']}"]["{$row['varname']}"] = $row['value'];
	}
	unset($row);
	unset($result);

	if(!isset($settings['panel']['version']) || $settings['panel']['version'] != $version)
	{
		header('Location: ./install/updatesql.php');
		exit;
	}

	/**
	 * Includes the Functions
	 */
	require('./lib/functions.php');

	/**
	 * SESSION MANAGEMENT
	 */
	$remote_addr = htmlspecialchars($_SERVER['REMOTE_ADDR']);
	$http_user_agent = htmlspecialchars($_SERVER['HTTP_USER_AGENT']);
	$nosession = 0;
	unset($userinfo);
	unset($userid);
	unset($customerid);
	unset($adminid);
	unset($s);

	if(isset($_POST['s']))
	{
		$s = $_POST['s'];
	}
	elseif(isset($_GET['s']))
	{
		$s = $_GET['s'];
	}
	else
	{
		$nosession = 1;
	}

	$timediff = time()-$settings['session']['sessiontimeout'];
	$db->query("DELETE FROM `".TABLE_PANEL_SESSIONS."` WHERE `lastactivity` < '$timediff'");

	if(isset($s) && $s != "" && $nosession != 1)
	{
		$userinfo = $db->query_first("SELECT `s`.*, `u`.* ".
		"FROM `".TABLE_PANEL_SESSIONS."` `s` LEFT JOIN ".( AREA == 'admin' ? '`'.TABLE_PANEL_ADMINS.'` `u` ON (`s`.`userid` = `u`.`adminid`)' : '`'.TABLE_PANEL_CUSTOMERS.'` `u` ON (`s`.`userid` = `u`.`customerid`)' )."".
		"WHERE `s`.`hash`='".addslashes($s)."' ".
		"AND `s`.`ipaddress`='".addslashes($remote_addr)."' ".
		"AND `s`.`useragent`='".addslashes($http_user_agent)."' ".
		"AND `s`.`lastactivity` > '$timediff'".
		"AND `s`.`adminsession` = '".( AREA == 'admin' ? '1' : '0' )."'");
		
		if(
		   (
		    ( $userinfo['adminsession'] == '1' && AREA == 'admin' && isset($userinfo['adminid']) ) ||
		    ( $userinfo['adminsession'] == '0' && ( AREA == 'customer' || AREA == 'login' ) && isset($userinfo['customerid']) )
		   ) &&
		   (!isset($userinfo['deactivated']) || $userinfo['deactivated'] != '1')
		  )
		{
			$db->query("UPDATE `".TABLE_PANEL_SESSIONS."` SET `lastactivity`='".time()."' WHERE `hash`='".addslashes($s)."' AND `adminsession` = '".( AREA == 'admin' ? '1' : '0' )."'");
			$nosession = 0;
		}
		else
		{
			$nosession = 1;
		}
	}
	else
	{
		$nosession = 1;
	}

	/**
	 * Language Managament
	 */
	$languages = Array( 'german' => 'Deutsch' , 'english' => 'English' ) ;
	if(!isset($userinfo['language']) || !isset($languages[$userinfo['language']]))
	{
		if(isset($_GET['language']) && isset($languages[$_GET['language']]))
		{
			$language = addslashes($_GET['language']);
		}
		else
		{
			$language = $settings['panel']['standardlanguage'];
		}
	}
	else
	{
		$language = $userinfo['language'];
	}

	if(file_exists('./lng/'.$language.'.lng.php'))
	{
		/**
		 * Includes file /lng/$language.lng.php if it exists
		 */
		require('./lng/'.$language.'.lng.php');
	}

	/**
	 * Redirects to index.php (login page) if no session exists
	 */
	if($nosession == 1 && AREA != 'login')
	{
		unset($userinfo);
		header('Location: ./index.php');
	}

	/**
	 * Fills variables for navigation, header and footer
	 */
	eval("\$navigation = \"".getTemplate("navigation")."\";");
	eval("\$header = \"".getTemplate('header', '1')."\";");
	eval("\$footer = \"".getTemplate('footer', '1')."\";");

	if(isset($_POST['action']))
	{
		$action=$_POST['action'];
	}
	elseif(isset($_GET['action']))
	{
		$action=$_GET['action'];
	}
	else
	{
		$action='';
	}

	if(isset($_POST['page']))
	{
		$page=$_POST['page'];
	}
	elseif(isset($_GET['page']))
	{
		$page=$_GET['page'];
	}
	else
	{
		$page='';
	}

	if($page=='')
	{
		$page='overview';
	}

?>

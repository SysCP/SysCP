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

	// --- martin @ 18.07.2005 -------------------------------------------------
	// this short piece of code has been introduced to remove severe security
	// holes with register_globals=on set. 
	/**
	 * Register Globals Security Fix
	 * - unsetting every variable registered in $_REQUEST and as variable itself
	 */
	foreach ( $_REQUEST as $key => $value )
	{
		if ( isset( $$key ) )
		{
			unset( $$key );
		}
	}
	unset( $_ );
	unset( $value );
	unset( $key );
	// -------------------------------------------------------------------------	 

	$filename = basename($_SERVER['PHP_SELF']);

	if(!file_exists('./lib/userdata.inc.php'))
	{
		die('You have to <a href="./install/install.php">configure</a> SysCP first!');
	}

	/**
	 * Includes the Usersettings eg. MySQL-Username/Passwort etc.
	 */
	require('./lib/userdata.inc.php');

	if( !isset ($sql) || !is_array($sql) )
	{
		die('You have to <a href="./install/install.php">configure</a> SysCP first!');
	}

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
	 * Include our class_idna_convert_wrapper.php, which offers methods for de-
	 * and encoding IDN domains.
	 */
	require('./lib/class_idna_convert_wrapper.php');
	
	/**
	 * Create a new idna converter
	 */
	$idna_convert = new idna_convert_wrapper();

	/**
	 * Selects settings from MySQL-Table
	 */
	$settings = Array() ;
	$result = $db->query( 'SELECT `settinggroup`, `varname`, `value` FROM `'.TABLE_PANEL_SETTINGS.'`' );
	while($row = $db->fetch_array($result))
	{
		if(($row['settinggroup'] == 'system' && $row['varname'] == 'hostname') || ($row['settinggroup'] == 'panel' && $row['varname'] == 'adminmail')) {
			$settings["{$row['settinggroup']}"]["{$row['varname']}"] = $idna_convert->decode($row['value']);
		}
		else
		{
			$settings["{$row['settinggroup']}"]["{$row['varname']}"] = $row['value'];
		}
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
	unset($userinfo);
	unset($userid);
	unset($customerid);
	unset($adminid);
	unset($s);

	if(isset($_POST['s']))
	{
		$s = $_POST['s'];
		$nosession = 0;
	}
	elseif(isset($_GET['s']))
	{
		$s = $_GET['s'];
		$nosession = 0;
	}
	else
	{
		$s = '';
		$nosession = 1;
	}

	$timediff = time() - $settings['session']['sessiontimeout'];
	$db->query( 'DELETE FROM `' . TABLE_PANEL_SESSIONS . '` WHERE `lastactivity` < "' . $timediff . '"' ) ;

	$userinfo = Array();
	if(isset($s) && $s != "" && $nosession != 1)
	{
		$query = 'SELECT `s`.*, `u`.* ' .
			'FROM `' . TABLE_PANEL_SESSIONS . '` `s` ' .
			'LEFT JOIN `';

		if (AREA == 'admin')
		{
			$query .= TABLE_PANEL_ADMINS.'` `u` ON (`s`.`userid` = `u`.`adminid`)';
			$adminsession = '1' ;
		}
		else
		{
			$query .= TABLE_PANEL_CUSTOMERS.'` `u` ON (`s`.`userid` = `u`.`customerid`)';	
			$adminsession = '0' ;
		}

		$query .= 'WHERE `s`.`hash`="'.addslashes($s).'" ' .
			'AND `s`.`ipaddress`="'.addslashes($remote_addr).'" ' .
			'AND `s`.`useragent`="'.addslashes($http_user_agent).'" ' .
			'AND `s`.`lastactivity` > "'.$timediff.'" ' .
			'AND `s`.`adminsession` = "' . $adminsession . '"';

		$userinfo = $db->query_first($query);

		if(
		   (
		    ( $userinfo['adminsession'] == '1' && AREA == 'admin' && isset($userinfo['adminid']) ) ||
		    ( $userinfo['adminsession'] == '0' && ( AREA == 'customer' || AREA == 'login' ) && isset($userinfo['customerid']) )
		   ) &&
		   (!isset($userinfo['deactivated']) || $userinfo['deactivated'] != '1')
		  )
		{
			$query = 'UPDATE `'.TABLE_PANEL_SESSIONS.'` ' .
				'SET `lastactivity`="' . time() . '" ' .
				'WHERE `hash`="' . addslashes($s) . '" ' .
				'AND `adminsession` = "' . $adminsession . '"';

			$db->query($query);

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
	$langs     = array();
	$languages = array();

	// query the whole table
	$query =
		'SELECT * ' .
		'FROM `'.TABLE_PANEL_LANGUAGE.'` ';
	$result = $db->query($query);
	// presort languages
	while ($row = $db->fetch_array($result))
	{
		$langs[$row['language']][] = $row;
	} 
	// buildup $languages for the login screen
	foreach ($langs as $key => $value)
	{
		$languages[$key] = $key;
	}
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
	// include every english language file we can get
	foreach ($langs['English'] as $key => $value)
	{
		include_once makeSecurePath ( $value['file'] ) ;
	}
	// now include the selected language if its not english
	if ($language != 'English') 
	{
		foreach ($langs[$language] as $key => $value)
		{
			include_once makeSecurePath ( $value['file'] ) ;
		}
	}
	
	/**
	 * Redirects to index.php (login page) if no session exists
	 */
	if($nosession == 1 && AREA != 'login')
	{
		unset($userinfo);
		header('Location: ./index.php');
		exit;
	}

	/**
	 * Initialize Template Engine
	 */
	$templatecache = array(); 
	
	/**
	 * Fills variables for navigation, header and footer
	 */
	$navigation = getNavigation($s, $userinfo);
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

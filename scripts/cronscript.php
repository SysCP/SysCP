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

	$lockfile='/var/run/syscp_cron.lock';
	$pathtophpfiles='/var/www/syscp';

	$filename = 'cronscript.php';
	if(file_exists($lockfile))
	{
		/**
		 * Do not proceed further if the lockfile exists.
		 */
		die('Lockfile ('.$lockfile.') exists. Exiting...');
	}
	else
	{
		exec('touch '.$lockfile);
	}
	
	/**
	 * Includes the Usersettings eg. MySQL-Username/Passwort etc.
	 */
	require("$pathtophpfiles/lib/userdata.inc.php");

	/**
	 * Includes the MySQL-Tabledefinitions etc.
	 */
	require("$pathtophpfiles/lib/tables.inc.php");

	/**
	 * Includes the MySQL-Connection-Class
	 */
	require("$pathtophpfiles/lib/class_mysqldb.php");
	$db=new db($sql['host'],$sql['user'],$sql['password'],$sql['db']);
	$db_root=new db($sql['host'],$sql['root_user'],$sql['root_password'],'');
	if($db->link_id == 0 || $db_root->link_id == 0)
	{
		/**
		 * Do not proceed further if no database connection could be established (either normal or root)
		 */
		unlink($lockfile);
		die('Cant connect to mysqlserver. Please check userdata.inc.php! Exiting...');
	}

	unset($sql['password']);
	unset($db->password);

	$result=$db->query("SELECT `settingid`, `settinggroup`, `varname`, `value` FROM `".TABLE_PANEL_SETTINGS."`");
	while($row=$db->fetch_array($result))
	{
		$settings["$row[settinggroup]"]["$row[varname]"]=$row['value'];
	}
	unset($row);
	unset($result);
	
	if(!isset($settings['panel']['version']) || $settings['panel']['version'] != $version)
	{
		/**
		 * Do not proceed further if the Database version is not the same as the script version
		 */
		unlink($lockfile);
		die('Version of File doesnt match Version of Database. Exiting...');
	}

	/**
	 * Includes the Functions
	 */
	require("$pathtophpfiles/lib/functions.php");

	/**
	 * Backend Wrapper
	 */
	$query = 
		'SELECT * ' .
		'FROM `'.TABLE_PANEL_CRONSCRIPT.'` ';
	$result = $db->query($query);
	while ($row = $db->fetch_array($result))
	{
		include_once $pathtophpfiles.'/scripts/'.$row['file'];
	}

	unlink($lockfile);

?>

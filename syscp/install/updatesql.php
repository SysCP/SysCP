<?php
/**
 * filename: $Source$
 * begin: Sunday, Sep 12, 2004
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

	/**
	 * Inlcudes the Usersettings eg. MySQL-Username/Passwort etc.
	 */
	require('../lib/userdata.inc.php');

	/**
	 * Includes the MySQL-Tabledefinitions etc.
	 */
	require('../lib/tables.inc.php');

	/**
	 * Inlcudes the MySQL-Connection-Class
	 */
	require('../lib/class_mysqldb.php');
	$db=new db($sql['host'],$sql['user'],$sql['password'],$sql['db']);
	unset($sql['password']);
	unset($db->password);

	$result=$db->query("SELECT `settinggroup`, `varname`, `value` FROM `".TABLE_PANEL_SETTINGS."`");
	while($row=$db->fetch_array($result))
	{
		$settings["$row[settinggroup]"]["$row[varname]"]=$row['value'];
	}
	unset($row);
	unset($result);

	/**
	 * Inlcudes the Functions
	 */
	require('../lib/functions.php');


	/**
	 * First case: We are updating from a version < 1.0.10
	 */
	if(!isset($settings['panel']['version']) || (substr($settings['panel']['version'], 0, 3) == '1.0' && $settings['panel']['version'] != '1.0.10'))
	{
		include('./updatesql_1.0.inc.php');
	}

	/**
	 * Second case: We are updating from a version = 1.0.10
	 */
	if($settings['panel']['version'] == '1.0.10')
	{
		include('./updatesql_1.0-1.2.inc.php');
	}

	header('Location: ../index.php');
 
?>
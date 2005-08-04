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
	
	$cronscriptDebug = false;
	// --- martin @ 05.05.2005 -------------------------------------------------
	// reworked this part to remove a possible race condition, if the cronscript
	// was called more than once within execution time of the if-clause 
	$lockdir        = '/var/run/';
	$lockFilename   = 'syscp_cron.lock-';
	$lockfName      = $lockFilename.time();
	$lockfile		= $lockdir.$lockfName;
	$debugMsg[]     = 'Setting Lockfile to '.$lockfile;
	$pathtophpfiles = '/var/www/syscp';
	$debugMsg[]     = 'Setting SysCP installation path to '.$pathtophpfiles;
	$filename       = 'cronscript.php';
	
	touch($lockfile); 
	$lockDirHandle  = opendir($lockdir);
	while ($fName = readdir($lockDirHandle))
	{
		if ( $lockFilename == substr($fName, 0, strlen($lockFilename)) && $lockfName != $fName )
		{
			unlink($lockfile);		
			die('There is already a lockfile. Exiting...');
		}
	}
	//--------------------------------------------------------------------------	
	
	/**
	 * Includes the Usersettings eg. MySQL-Username/Passwort etc.
	 */
	require("$pathtophpfiles/lib/userdata.inc.php");
	$debugMsg[] = 'Userdatas included';

	/**
	 * Includes the MySQL-Tabledefinitions etc.
	 */
	require("$pathtophpfiles/lib/tables.inc.php");
	$debugMsg[] = 'Table definitions included';

	/**
	 * Includes the MySQL-Connection-Class
	 */
	require("$pathtophpfiles/lib/class_mysqldb.php");
	$debugMsg[] = 'Database Class has been loaded';
	$db      = new db($sql['host'],$sql['user'],$sql['password'],$sql['db']);
	$db_root = new db($sql['host'],$sql['root_user'],$sql['root_password'],'');
	if($db->link_id == 0 || $db_root->link_id == 0)
	{
		/**
		 * Do not proceed further if no database connection could be established (either normal or root)
		 */
		unlink($lockfile);
		die('Cant connect to mysqlserver. Please check userdata.inc.php! Exiting...');
	}
	$debugMsg[] = 'Database Connection established';
	
	// --- martin @ 04.08.2005 -----------------------------------------------------------------
	// changed the following lines to remove the whole sql referrence 
	unset( $sql               );
	unset( $db->password      );
	unset( $db_root->password );
	// -----------------------------------------------------------------------------------------

	$result=$db->query("SELECT `settingid`, `settinggroup`, `varname`, `value` FROM `".TABLE_PANEL_SETTINGS."`");
	while($row=$db->fetch_array($result))
	{
		$settings["$row[settinggroup]"]["$row[varname]"]=$row['value'];
	}
	unset($row);
	unset($result);
	$debugMsg[] = 'SysCP Settings has been loaded from the datbase';
	
	
	if(!isset($settings['panel']['version']) || $settings['panel']['version'] != $version)
	{
		/**
		 * Do not proceed further if the Database version is not the same as the script version
		 */
		unlink($lockfile);
		die('Version of File doesnt match Version of Database. Exiting...');
	}
	$debugMsg[] = 'SysCP Version and Database Version are correct';
	
	/**
	 * Includes the Functions
	 */
	require("$pathtophpfiles/lib/functions.php");
	$debugMsg[] = 'Functions has been included';

	/**
	 * Backend Wrapper
	 */
	$query = 
		'SELECT * ' .
		'FROM `'.TABLE_PANEL_CRONSCRIPT.'` ';
	$cronFileIncludeResult = $db->query($query);
	while ($cronFileIncludeRow = $db->fetch_array($cronFileIncludeResult))
	{
		$debugMsg[] = 'Processing ...'.$pathtophpfiles.'/scripts/'.$cronFileIncludeRow['file'];
		include_once $pathtophpfiles.'/scripts/'.$cronFileIncludeRow['file'];
		$debugMsg[] = 'Processing done!';
	}

	unlink($lockfile);
	$debugMsg[] = 'Deleting Lockfile!';
	
	$db->close();
	$debugMsg[] = 'Closing DB Connection!';
	
	if ($cronscriptDebug)
	{
		foreach ($debugMsg as $k => $v)
		{
			print $v."\n";
		}
	}
	
?>

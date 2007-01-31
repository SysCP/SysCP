<?php
/**
 * This file is part of the SysCP project.
 * Copyright (c) 2003-2007 the SysCP Team (see authors).
 *
 * For the full copyright and license information, please view the COPYING
 * file that was distributed with this source code. You can also view the
 * COPYING file online at http://files.syscp.org/misc/COPYING.txt
 *
 * @copyright  (c) the authors
 * @author     Florian Lippert <flo@syscp.org>
 * @author     Martin Burchert <eremit@syscp.org>
 * @license    GPLv2 http://files.syscp.org/misc/COPYING.txt
 * @package    System
 * @version    $Id$
 */

	if(@php_sapi_name() != 'cli' && @php_sapi_name() != 'cgi' && @php_sapi_name() != 'cgi-fcgi')
	{
		die('This script will only work in the shell.');
	}

	$cronscriptDebug = false;
	$lockdir        = '/var/run/';
	$lockFilename   = 'syscp_cron.lock-';
	$lockfName      = $lockFilename.time();
	$lockfile		= $lockdir.$lockfName;

	// guess the syscp installation path
	// normally you should not need to modify this script anymore, if your
	// syscp installation isn't in /var/www/syscp
	$pathtophpfiles = '';
	if( substr($_SERVER['PHP_SELF'], 0, 1) != '/' )
	{
		$pathtophpfiles = $_SERVER['PWD'];
	}
	$pathtophpfiles .= '/'.$_SERVER['PHP_SELF'];
	$pathtophpfiles = str_replace( array( '/./', '//' ), '/', $pathtophpfiles );
	$pathtophpfiles = dirname(dirname( $pathtophpfiles ));

	// should the syscp installation guessing not work correctly,
	// uncomment the following line, and put your path in there!
	//$pathtophpfiles = '/var/www/syscp';

	$filename       = 'cronscript.php';

	// create and open the lockfile!
	$debugHandler = fopen( $lockfile, 'w' );
	fwrite( $debugHandler, 'Setting Lockfile to '.$lockfile . "\n");
	fwrite( $debugHandler, 'Setting SysCP installation path to '.$pathtophpfiles . "\n");

	// open the lockfile directory and scan for existing lockfiles
	$lockDirHandle  = opendir($lockdir);
	while ($fName = readdir($lockDirHandle))
	{
		if ( $lockFilename == substr($fName, 0, strlen($lockFilename)) && $lockfName != $fName )
		{
			// close the current lockfile
			fclose( $debugHandler );
			// ... and delete it
			unlink($lockfile);
			die('There is already a lockfile. Exiting...' . "\n" .
			    'Take a look into the contents of ' . $lockdir . $lockFilename .
			    '* for more information!' );
		}
	}

	/**
	 * Includes the Usersettings eg. MySQL-Username/Passwort etc.
	 */
	require($pathtophpfiles.'/lib/userdata.inc.php');
	fwrite( $debugHandler, 'Userdatas included' . "\n");

	/**
	 * Includes the MySQL-Tabledefinitions etc.
	 */
	require($pathtophpfiles.'/lib/tables.inc.php');
	fwrite( $debugHandler, 'Table definitions included' . "\n");

	/**
	 * Includes the MySQL-Connection-Class
	 */
	require($pathtophpfiles.'/lib/class_mysqldb.php');
	fwrite( $debugHandler, 'Database Class has been loaded' . "\n");
	$db      = new db($sql['host'],$sql['user'],$sql['password'],$sql['db']);
	$db_root = new db($sql['host'],$sql['root_user'],$sql['root_password'],'');
	if($db->link_id == 0 || $db_root->link_id == 0)
	{
		/**
		 * Do not proceed further if no database connection could be established (either normal or root)
		 */
		fclose( $debugHandler );
		unlink($lockfile);
		die('Cant connect to mysqlserver. Please check userdata.inc.php! Exiting...');
	}
	fwrite( $debugHandler, 'Database Connection established' . "\n" );

	unset( $sql               );
	unset( $db->password      );
	unset( $db_root->password );

	$result=$db->query("SELECT `settingid`, `settinggroup`, `varname`, `value` FROM `".TABLE_PANEL_SETTINGS."`");
	while($row=$db->fetch_array($result))
	{
		$settings[$row['settinggroup']][$row['varname']]=$row['value'];
	}
	unset($row);
	unset($result);
	fwrite( $debugHandler, 'SysCP Settings has been loaded from the database' . "\n");


	if(!isset($settings['panel']['version']) || $settings['panel']['version'] != $version)
	{
		/**
		 * Do not proceed further if the Database version is not the same as the script version
		 */
		fclose( $debugHandler );
		unlink($lockfile);
		die('Version of File doesnt match Version of Database. Exiting...');
	}
	fwrite( $debugHandler, 'SysCP Version and Database Version are correct' . "\n");

	/**
	 * Includes the Functions
	 */
	require($pathtophpfiles.'/lib/functions.php');
	fwrite( $debugHandler, 'Functions has been included' . "\n" );

	/**
	 * Backend Wrapper
	 */
	$query =
		'SELECT * ' .
		'FROM `'.TABLE_PANEL_CRONSCRIPT.'` ';
	$cronFileIncludeResult = $db->query($query);
	while ($cronFileIncludeRow = $db->fetch_array($cronFileIncludeResult))
	{
		fwrite( $debugHandler, 'Processing ...'.$pathtophpfiles.'/scripts/'.$cronFileIncludeRow['file'] . "\n");
		include_once $pathtophpfiles.'/scripts/'.$cronFileIncludeRow['file'];
		fwrite( $debugHandler, 'Processing done!' . "\n");
	}

	fclose( $debugHandler );
	unlink($lockfile);

	$db->query(
		'UPDATE `'.TABLE_PANEL_SETTINGS.'` ' .
		'SET `value` = UNIX_TIMESTAMP() ' .
		'WHERE `settinggroup` = \'system\'  ' .
		'  AND `varname`      = \'lastcronrun\' '
		);

	$db->close();
?>
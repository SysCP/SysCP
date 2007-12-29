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

/**
 * STARTING REDUNDANT CODE, WHICH IS SOME KINDA HEADER FOR EVERY CRON SCRIPT.
 * When using this "header" you have to change $lockFilename for your needs.
 * Don't forget to also copy the footer which closes database connections
 * and the lockfile! (Note: This "header" also establishes a mysql-root-
 * connection, if you don't need it, see for the header in cron_tasks.php)
 */

if(@php_sapi_name() != 'cli'
   && @php_sapi_name() != 'cgi'
   && @php_sapi_name() != 'cgi-fcgi')
{
	die('This script will only work in the shell.');
}

$cronscriptDebug = false;
$lockdir = '/var/run/';
$lockFilename = 'syscp_cron_legacy.lock-';
$lockfName = $lockFilename . time();
$lockfile = $lockdir . $lockfName;

// guess the syscp installation path
// normally you should not need to modify this script anymore, if your
// syscp installation isn't in /var/www/syscp

$pathtophpfiles = '';

if(substr($_SERVER['PHP_SELF'], 0, 1) != '/')
{
	$pathtophpfiles = $_SERVER['PWD'];
}

$pathtophpfiles.= '/' . $_SERVER['PHP_SELF'];
$pathtophpfiles = str_replace(array(
	'/./',
	'//'
), '/', $pathtophpfiles);
$pathtophpfiles = dirname(dirname($pathtophpfiles));

// should the syscp installation guessing not work correctly,
// uncomment the following line, and put your path in there!
//$pathtophpfiles = '/var/www/syscp';
// create and open the lockfile!

$keepLockFile = false;
$debugHandler = fopen($lockfile, 'w');
fwrite($debugHandler, 'Setting Lockfile to ' . $lockfile . "\n");
fwrite($debugHandler, 'Setting SysCP installation path to ' . $pathtophpfiles . "\n");

// open the lockfile directory and scan for existing lockfiles

$lockDirHandle = opendir($lockdir);

while($fName = readdir($lockDirHandle))
{
	if($lockFilename == substr($fName, 0, strlen($lockFilename))
	   && $lockfName != $fName)
	{
		// close the current lockfile

		fclose($debugHandler);

		// ... and delete it

		unlink($lockfile);
		die('There is already a lockfile. Exiting...' . "\n" . 'Take a look into the contents of ' . $lockdir . $lockFilename . '* for more information!' . "\n");
	}
}

/**
 * Includes the Usersettings eg. MySQL-Username/Passwort etc.
 */

require ($pathtophpfiles . '/lib/userdata.inc.php');
fwrite($debugHandler, 'Userdatas included' . "\n");

/**
 * Includes the MySQL-Tabledefinitions etc.
 */

require ($pathtophpfiles . '/lib/tables.inc.php');
fwrite($debugHandler, 'Table definitions included' . "\n");

/**
 * Includes the MySQL-Connection-Class
 */

require ($pathtophpfiles . '/lib/class_mysqldb.php');
fwrite($debugHandler, 'Database Class has been loaded' . "\n");
$db = new db($sql['host'], $sql['user'], $sql['password'], $sql['db']);
$db_root = new db($sql['host'], $sql['root_user'], $sql['root_password'], '');

if($db->link_id == 0
   || $db_root->link_id == 0)
{
	/**
	 * Do not proceed further if no database connection could be established (either normal or root)
	 */

	fclose($debugHandler);
	unlink($lockfile);
	die('Cant connect to mysqlserver. Please check userdata.inc.php! Exiting...');
}

fwrite($debugHandler, 'Database Connection established' . "\n");
unset($sql);
unset($db->password);
unset($db_root->password);
$result = $db->query("SELECT `settingid`, `settinggroup`, `varname`, `value` FROM `" . TABLE_PANEL_SETTINGS . "`");

while($row = $db->fetch_array($result))
{
	$settings[$row['settinggroup']][$row['varname']] = $row['value'];
}

unset($row);
unset($result);
fwrite($debugHandler, 'SysCP Settings has been loaded from the database' . "\n");

if(!isset($settings['panel']['version'])
   || $settings['panel']['version'] != $version)
{
	/**
	 * Do not proceed further if the Database version is not the same as the script version
	 */

	fclose($debugHandler);
	unlink($lockfile);
	die('Version of File doesnt match Version of Database. Exiting...');
}

fwrite($debugHandler, 'SysCP Version and Database Version are correct' . "\n");

/**
 * Includes the Functions
 */

require ($pathtophpfiles . '/lib/functions.php');
fwrite($debugHandler, 'Functions has been included' . "\n");

/**
 * END REDUNDANT CODE (CRONSCRIPT "HEADER")
 */

/**
 * Check if table exists, otherwise create it
 */

$tables = getTables($db);

if(!isset($tables[TABLE_PANEL_CRONSCRIPT])
   || !is_array($tables[TABLE_PANEL_CRONSCRIPT]))
{
	$db->query('CREATE TABLE `' . TABLE_PANEL_CRONSCRIPT . '` ( ' . '  `id` int(11) unsigned NOT NULL auto_increment, ' . '  `file` varchar(255) NOT NULL default \'\', ' . '  PRIMARY KEY  (`id`) ' . ') TYPE=MyISAM ; ');
}

/**
 * Backend Wrapper
 */

$query = 'SELECT * ' . 'FROM `' . TABLE_PANEL_CRONSCRIPT . '` ';
$cronFileIncludeResult = $db->query($query);

while($cronFileIncludeRow = $db->fetch_array($cronFileIncludeResult))
{
	$cronFileIncludeFullPath = makeSecurePath($pathtophpfiles . '/scripts/' . $cronFileIncludeRow['file']);

	if(fileowner($cronFileIncludeFullPath) == fileowner($pathtophpfiles . '/scripts/' . $filename)
	   && filegroup($cronFileIncludeFullPath) == filegroup($pathtophpfiles . '/scripts/' . $filename))
	{
		fwrite($debugHandler, 'Processing ...' . $cronFileIncludeFullPath . "\n");
		include_once $cronFileIncludeFullPath;
		fwrite($debugHandler, 'Processing done!' . "\n");
	}
	else
	{
		fwrite($debugHandler, 'WARNING! uid and/or gid of "' . $cronFileIncludeFullPath . '" and "' . $pathtophpfiles . '/scripts/' . $filename . '" don\'t match! Execution aborted!' . "\n");
		$keepLockFile = true;
	}
}

/**
 * STARTING CRONSCRIPT FOOTER
 */

$db->close();
$db_root->close();
fwrite($debugHandler, 'Closing database connection' . "\n");
fclose($debugHandler);

if($keepLockFile === false)
{
	unlink($lockfile);
}

/**
 * END CRONSCRIPT FOOTER
 */

?>
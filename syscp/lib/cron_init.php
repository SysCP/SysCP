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
 * @author     Florian Aders <eleras@syscp.org>
 * @license    GPLv2 http://files.syscp.org/misc/COPYING.txt
 * @package    System
 * @version    $Id$
 */

if(@php_sapi_name() != 'cli'
   && @php_sapi_name() != 'cgi'
   && @php_sapi_name() != 'cgi-fcgi')
{
	die('This script will only work in the shell.');
}

$cronscriptDebug = false;
$lockdir = '/var/run/';
$lockFilename = 'syscp_' . basename($_SERVER['PHP_SELF'], '.php') . '.lock-';
$lockfName = $lockFilename . getmypid();
$lockfile = $lockdir . $lockfName;

// guess the syscp installation path
// normally you should not need to modify this script anymore, if your
// syscp installation isn't in /var/www/syscp

$pathtophpfiles = dirname(dirname(__FILE__));

// should the syscp installation guessing not work correctly,
// uncomment the following line, and put your path in there!
//$pathtophpfiles = '/var/www/syscp/';
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
		// Check if last run jailed out with an exception

		$croncontent = file($lockdir . $fName);
		$lastline = $croncontent[(count($croncontent)-1)];

		if($lastline == '=== Keep lockfile because of exception ===')
		{
			fclose($debugHandler);
			unlink($lockfile);
			die('Last cron jailed out with an exception. Exiting...' . "\n" . 'Take a look into the contents of ' . $lockdir . $fName . '* for more information!' . "\n");
		}

		// Check if cron is running or has died.

		$check_pid = substr(strstr($fName, "-"), 1);
		system("kill -CHLD " . $check_pid . " 1> /dev/null 2> /dev/null", $check_pid_return);

		if($check_pid_return == 1)
		{
			// Result:      Existing lockfile/pid isnt running
			//              Most likely it has died
			//
			// Action:      Remove it and continue
			//

			fwrite($debugHandler, 'Previous cronjob didn\'t exit clean. PID: ' . $check_pid . "\n");
			fwrite($debugHandler, 'Removing lockfile: ' . $lockdir . $fName . "\n");
			unlink($lockdir . $fName);
		}
		else
		{
			// Result:      A Cronscript with this pid
			//              is still running
			// Action:      remove my own Lock and die
			//
			// close the current lockfile

			fclose($debugHandler);

			// ... and delete it

			unlink($lockfile);
			die('There is already a Cronjob in progress. Exiting...' . "\n" . 'Take a look into the contents of ' . $lockdir . $lockFilename . '* for more information!' . "\n");
		}
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

// If one cronscript needs root, it should say $needrootdb = true before the include

if(isset($needrootdb)
   && $needrootdb === true)
{
	$db_root = new db($sql['host'], $sql['root_user'], $sql['root_password'], '');

	if($db_root->link_id == 0)
	{
		/**
		 * Do not proceed further if no database connection could be established
		 */

		fclose($debugHandler);
		unlink($lockfile);
		die('root can\'t connect to mysqlserver. Please check userdata.inc.php! Exiting...');
	}

	unset($db_root->password);
	fwrite($debugHandler, 'Database-rootconnection established' . "\n");
}

unset($sql['root_user'], $sql['root_password']);

if($db->link_id == 0)
{
	/**
	 * Do not proceed further if no database connection could be established
	 */

	fclose($debugHandler);
	unlink($lockfile);
	die('SysCP can\'t connect to mysqlserver. Please check userdata.inc.php! Exiting...');
}

fwrite($debugHandler, 'Database-connection established' . "\n");
unset($sql);
unset($db->password);
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
$cronbasedir = makeCorrectDir($pathtophpfiles . '/scripts/');
$crondir = opendir($cronbasedir);
$cronfilename = basename($_SERVER['PHP_SELF'], '.php');
$cronscriptFullName = makeCorrectFile($cronbasedir . basename($_SERVER['PHP_SELF']));

while(false !== ($checkfile = readdir($crondir)))
{
	$completeNameWithPath = makeCorrectFile($cronbasedir . $checkfile);

	if(substr($checkfile, 0, strlen($cronfilename . '.inc.')) == $cronfilename . '.inc.'
	   && substr($checkfile, -4) == '.php')
	{
		if(fileowner($cronscriptFullName) == fileowner($completeNameWithPath)
		   && filegroup($cronscriptFullName) == filegroup($completeNameWithPath))
		{
			fwrite($debugHandler, 'Including ...' . $completeNameWithPath . "\n");
			include ($completeNameWithPath);
		}
		else
		{
			fwrite($debugHandler, 'WARNING! uid and/or gid of "' . $cronscriptFullName . '" and "' . $completeNameWithPath . '" don\'t match! Execution aborted!' . "\n");
			$keepLockFile = true;
		}
	}
}

closedir($crondir);
unset($completeName, $crondir, $cronname, $cronscriptFullName, $cron_filename, $cronbasedir);
fwrite($debugHandler, 'Functions have been included' . "\n");

/**
 * Includes Logger-Classes
 */

require ($pathtophpfiles . '/lib/abstract/abstract_class_logger.php');
require ($pathtophpfiles . '/lib/class_syslogger.php');
require ($pathtophpfiles . '/lib/class_filelogger.php');
require ($pathtophpfiles . '/lib/class_mysqllogger.php');

/**
 * Includes the SyscpLogger class
 */

require ($pathtophpfiles . '/lib/class_syscplogger.php');

/**
 * Initialize logging
 */

$cronlog = SysCPLogger::getInstanceOf(null, $db, $settings);
fwrite($debugHandler, 'Logger has been included' . "\n");

/**
 * Includes the cron_httpd class
 */

require ($pathtophpfiles . '/lib/cron_httpd.class.php');
fwrite($debugHandler, 'Cron_httpd has been included' . "\n");


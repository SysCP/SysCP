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
 * @author     Florian Aders <eleras@syscp.org>
 * @license    GPLv2 http://files.syscp.org/misc/COPYING.txt
 * @package    System
 * @version    $Id$
 */

/**
 * STARTING REDUNDANT CODE, WHICH IS SOME KINDA HEADER FOR EVERY CRON SCRIPT.
 * When using this "header" you have to change $lockFilename for your needs.
 * Don't forget to also copy the footer which closes database connections
 * and the lockfile!
 */

if(@php_sapi_name() != 'cli'
   && @php_sapi_name() != 'cgi'
   && @php_sapi_name() != 'cgi-fcgi')
{
	die('This script will only work in the shell.');
}

$cronscriptDebug = false;
$lockdir = '/var/run/';
$lockFilename = 'syscp_cron_traffic_report.lock-';
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

if($db->link_id == 0)
{
	/**
	 * Do not proceed further if no database connection could be established
	 */

	fclose($debugHandler);
	unlink($lockfile);
	die('Cant connect to mysqlserver. Please check userdata.inc.php! Exiting...');
}

fwrite($debugHandler, 'Database Connection established' . "\n");
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
fwrite($debugHandler, 'Functions has been included' . "\n");

/**
 * END REDUNDANT CODE (CRONSCRIPT "HEADER")
 */

fwrite($debugHandler, 'Trafficreport run started...' . "\n");
$yesterday = time()-(60*60*24);

// Warn the customers at 90% traffic-usage

$result = $db->query("SELECT `c`.`customerid`, `c`.`adminid`, `c`.`name`, `c`.`firstname`, `c`.`traffic_used`, `c`.`traffic`, `c`.`email`, `a`.`name` AS `adminname`, `a`.`email` AS `adminmail` FROM `" . TABLE_PANEL_CUSTOMERS . "` AS `c` LEFT JOIN `panel_admins` AS `a` ON `a`.`adminid` = `c`.`adminid` WHERE `c`.`reportsent` = '0';");

while($row = $db->fetch_array($result))
{
	if((($row['traffic_used']*100)/$row['traffic']) >= 90)
	{
		$replace_arr = array(
			'NAME' => $ow['name'],
			'TRAFFICMAX' => $row['traffic'],
			'TRAFFICUSED' => $row['traffic_used']
		);

		// Get mail templates from database; the ones from 'admin' are fetched for fallback

		$result = $db->query_first('SELECT `value` FROM `' . TABLE_PANEL_TEMPLATES . '` WHERE `adminid`=\'' . (int)$row['adminid'] . '\' AND `language`=\'' . $db->escape($row['def_language']) . '\' AND `templategroup`=\'traffic\' AND `varname`=\'trafficninetypercent_subject\'');
		$mail_subject = html_entity_decode(replace_variables((($result['value'] != '') ? $result['value'] : $lng['mails']['trafficninetypercent']['subject']), $replace_arr));
		$result = $db->query_first('SELECT `value` FROM `' . TABLE_PANEL_TEMPLATES . '` WHERE `adminid`=\'' . (int)$row['adminid'] . '\' AND `language`=\'' . $db->escape($row['def_language']) . '\' AND `templategroup`=\'traffic\' AND `varname`=\'trafficninetypercent_mailbody\'');
		$mail_body = html_entity_decode(replace_variables((($result['value'] != '') ? $result['value'] : $lng['mails']['trafficninetypercent']['mailbody']), $replace_arr));
		mail(buildValidMailFrom($row['firstname'] . ' ' . $row['name'], $row['email']), $mail_subject, $mail_body, 'From: ' . buildValidMailFrom($row['adminname'], $row['adminemail']));
		$db->query('UPDATE `' . TABLE_PANEL_CUSTOMERS . '` ' . 'SET `reportsent`=\'1\' ' . 'WHERE `customerid`=\'' . (int)$row['customerid'] . '\'');
	}
}

// Warn the admins at 90% traffic-usage

$result = $db->query("SELECT * FROM `" . TABLE_PANEL_ADMINS . "`;");

while($row = $db->fetch_array($result))
{
	if((($row['traffic_used']*100)/$row['traffic']) >= 90)
	{
		$replace_arr = array(
			'NAME' => $ow['name'],
			'TRAFFICMAX' => $row['traffic'],
			'TRAFFICUSED' => $row['traffic_used']
		);

		// Get mail templates from database; the ones from 'admin' are fetched for fallback

		$result = $db->query_first('SELECT `value` FROM `' . TABLE_PANEL_TEMPLATES . '` WHERE `adminid`=\'' . (int)$row['adminid'] . '\' AND `language`=\'' . $db->escape($row['def_language']) . '\' AND `templategroup`=\'traffic\' AND `varname`=\'trafficninetypercent_subject\'');
		$mail_subject = html_entity_decode(replace_variables((($result['value'] != '') ? $result['value'] : $lng['mails']['trafficninetypercent']['subject']), $replace_arr));
		$result = $db->query_first('SELECT `value` FROM `' . TABLE_PANEL_TEMPLATES . '` WHERE `adminid`=\'' . (int)$row['adminid'] . '\' AND `language`=\'' . $db->escape($row['def_language']) . '\' AND `templategroup`=\'traffic\' AND `varname`=\'trafficninetypercent_mailbody\'');
		$mail_body = html_entity_decode(replace_variables((($result['value'] != '') ? $result['value'] : $lng['mails']['trafficninetypercent']['mailbody']), $replace_arr));
		mail(buildValidMailFrom($row['name'], $row['email']), $mail_subject, $mail_body, 'From: ' . buildValidMailFrom($row['name'], $row['email']));
		$db->query('UPDATE `' . TABLE_PANEL_ADMINS . '` ' . 'SET `reportsent`=\'1\' ' . 'WHERE `customerid`=\'' . (int)$row['adminid'] . '\'');
	}

	// Another month, let's build our report

	if(date('d') == '01')
	{
		$mail_subject = 'Trafficreport ' . date("m/y", $yesterday) . ' for ' . $row['name'];
		$mail_body = 'Trafficreport ' . date("m/y", $yesterday) . ' for ' . $row['name'] . "\n";
		$mail_body.= '---------------------------------------------' . "\n";
		$mail_body.= 'Loginname       Traffic used (Percent) | Traffic available' . "\n";
		$customers = $db->query("SELECT * FROM `" . TABLE_PANEL_CUSTOMERS . "` WHERE `adminid` = " . $row['adminid'] . ";");

		while($customer = $db->fetch_array($customers))
		{
			$mail_body.= sprintf('%-15s', $customer['loginname']) . ' ' . sprintf('%-12d', $customer['traffic_used']) . ' (' . sprintf('%00.3f%%', (($customer['traffic_used']*100)/$customer['traffic'])) . ')   ' . $customer['traffic'] . "\n";
		}

		$mail_body.= '---------------------------------------------' . "\n";
		$mail_body.= sprintf('%-15s', $row['loginname']) . ' ' . sprintf('%-12d', $row['traffic_used']) . ' (' . sprintf('%00.3f%%', (($row['traffic_used']*100)/$row['traffic'])) . ')   ' . $row['traffic'] . "\n";
		mail(buildValidMailFrom($row['name'], $row['email']), $mail_subject, $mail_body, 'From: ' . buildValidMailFrom($row['name'], $row['email']));
	}
}

// Another month, reset the reportstatus

if(date('d') == '01')
{
	$db->query('UPDATE `' . TABLE_PANEL_CUSTOMERS . '` ' . 'SET `reportsent` = \'0\';');
	$db->query('UPDATE `' . TABLE_PANEL_ADMINS . '` ' . 'SET `reportsent` = \'0\';');
}

$db->query('UPDATE `' . TABLE_PANEL_SETTINGS . '` ' . 'SET `value` = UNIX_TIMESTAMP() ' . 'WHERE `settinggroup` = \'system\'  ' . '  AND `varname`      = \'last_traffic_report_run\' ');

/**
 * STARTING CRONSCRIPT FOOTER
 */

$db->close();
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
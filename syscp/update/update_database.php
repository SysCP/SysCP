<?php

/**
 * This file is part of the SysCP project.
 * Copyright (c) 2003-2009 the SysCP Team (see authors).
 *
 * For the full copyright and license information, please view the COPYING
 * file that was distributed with this source code. You can also view the
 * COPYING file online at http://files.syscp.org/misc/COPYING.txt
 *
 * @copyright  (c) the authors
 * @author     Florian Lippert <flo@syscp.org>
 * @license    GPLv2 http://files.syscp.org/misc/COPYING.txt
 * @package    System
 * @version    $Id: updatesql.php 2692 2009-03-27 18:04:47Z flo $
 */

/**
 * Includes the Usersettings eg. MySQL-Username/Passwort etc.
 */

require ('../lib/userdata.inc.php');

/**
 * Includes the MySQL-Tabledefinitions etc.
 */

require ('../lib/tables.inc.php');

/**
 * Inlcudes the MySQL-Connection-Class
 */

require ('../lib/class_mysqldb.php');
$db = new db($sql['host'], $sql['user'], $sql['password'], $sql['db']);
unset($sql['password']);
unset($db->password);
$result = $db->query("SELECT `settinggroup`, `varname`, `value` FROM `" . TABLE_PANEL_SETTINGS . "`");

while($row = $db->fetch_array($result))
{
	$settings[$row['settinggroup']][$row['varname']] = $row['value'];
}

unset($row);
unset($result);

/**
 * Inlcudes the Functions
 */

require ('../lib/functions.php');

/**
 * Includes Logger-Classes
 */

require ('../lib/abstract/abstract_class_logger.php');
require ('../lib/class_syslogger.php');
require ('../lib/class_filelogger.php');
require ('../lib/class_mysqllogger.php');

/**
 * Includes the SyscpLogger class
 */

require ('../lib/class_syscplogger.php');
$updatelog = SysCPLogger::getInstanceOf(array('loginname' => 'updater'), $db, $settings);

// Call old legacy updater, so people who haven't used 1.4.2 yet will also get caught by the new updater
if(isset($settings['panel']['version'])
   && $settings['panel']['version'] != '1.4.2')
{
	redirectTo('../install/updatesql.php');
	exit;
}
// End legacy stuff

echo $settings['system']['dbversion'];
echo $dbversion;

if(!isset($settings['system']['dbversion']))
{
	$db->query('INSERT INTO `' . TABLE_PANEL_SETTINGS . '` SET `settinggroup` = \'system\', `varname` = \'dbversion\', `value` = \'0\'');
	$settings['system']['dbversion'] = '0';
}

if((int)$settings['system']['dbversion'] < (int)$dbversion)
{
	// Update
	for($i = (int)$settings['system']['dbversion'] + 1; $i <= (int)$dbversion; $i++)
	{
		$upfile_sql = './' . $i . '/up.sql';
		if(file_exists($upfile_sql) && is_readable($upfile_sql))
		{
			$sql_query = @file_get_contents($upfile_sql, 'r');
			$sql_query = remove_remarks($sql_query);
			$sql_query = split_sql_file($sql_query, ';');
			for ($i = 0;$i < sizeof($sql_query);$i++)
			{
				if(trim($sql_query[$i]) != '')
				{
					$result = $db->query($sql_query[$i]);
				}
			}
		}
		$upfile_php = './' . $i . '/up.php';
		if(file_exists($upfile_php) && is_readable($upfile_php))
		{
			require_once($upfile_php);
		}
		$db->query('UPDATE `' . TABLE_PANEL_SETTINGS . '` SET `value` = \'' . $i . '\' WHERE `settinggroup` = \'system\' AND `varname` = \'dbversion\'');
		$settings['system']['dbversion'] = $i;
		$updatelog->logAction(ADM_ACTION, LOG_WARNING, 'Updating database to version ' . $i);
	}
}
elseif((int)$settings['system']['dbversion'] > (int)$dbversion)
{
	// downgrade
	for($i = (int)$settings['system']['dbversion']; $i > (int)$dbversion; $i--)
	{
		$downfile_sql = './' . $i . '/down.sql';
		if(file_exists($downfile_sql) && is_readable($downfile_sql))
		{
			$sql_query = @file_get_contents($downfile_sql, 'r');
			$sql_query = remove_remarks($sql_query);
			$sql_query = split_sql_file($sql_query, ';');
			for ($i = 0;$i < sizeof($sql_query);$i++)
			{
				if(trim($sql_query[$i]) != '')
				{
					$result = $db->query($sql_query[$i]);
				}
			}
		}
		$downfile_php = './' . $i . '/down.php';
		if(file_exists($downfile_php) && is_readable($downfile_php))
		{
			require_once($downfile_php);
		}
		$db->query('UPDATE `' . TABLE_PANEL_SETTINGS . '` SET `value` = \'' . ( $i -1 ) . '\' WHERE `settinggroup` = \'system\' AND `varname` = \'dbversion\'');
		$settings['system']['dbversion'] = ( $i -1 );
		$updatelog->logAction(ADM_ACTION, LOG_WARNING, 'Downgrading database to version ' . ( $i -1 ));
	}
}

@chmod('../lib/userdata.inc.php', 0440);
header('Location: ../index.php');

?>
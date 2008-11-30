<?php

/**
 * This file is part of the SysCP project.
 * Copyright (c) 2003-2008 the SysCP Team (see authors).
 *
 * For the full copyright and license information, please view the COPYING
 * file that was distributed with this source code. You can also view the
 * COPYING file online at http://files.syscp.org/misc/COPYING.txt
 *
 * @copyright  (c) the authors
 * @author     Florian Aders <eleras@syscp.org>
 * @license    GPLv2 http://files.syscp.org/misc/COPYING.txt
 * @package    System
 * @version    $Id: updatesql_1.2.19-20.inc.php 2458 2008-11-30 13:50:23Z flo $
 */

if($settings['panel']['version'] == '1.4')
{
	$updatelog->logAction(ADM_ACTION, LOG_WARNING, "Updating from 1.4 to 1.4-svn1");

	// Going to fix the stuff the update 1.2.19-svn42 to 1.2.19-svn43 broke

	$result = $db->query("SELECT * FROM `" . TABLE_PANEL_DOMAINS . "` WHERE `documentroot` LIKE 'http%';");

	while($row = $db->fetch_array($result))
	{
		if(preg_match("#(https?)://?(.*)#i", $row['documentroot'], $matches))
		{
			$row['documentroot'] = $matches[1] . "://" . $matches[2];
			$db->query("UPDATE `" . TABLE_PANEL_DOMAINS . "` SET `documentroot` = '" . $db->escape($row['documentroot']) . "' WHERE `id` = '" . $row['id'] . "';");
		}
	}

	// set new version

	$query = 'UPDATE `%s` SET `value` = \'1.4-svn1\' WHERE `settinggroup` = \'panel\' AND `varname` = \'version\'';
	$query = sprintf($query, TABLE_PANEL_SETTINGS);
	$db->query($query);
	$settings['panel']['version'] = '1.4-svn1';
}

?>

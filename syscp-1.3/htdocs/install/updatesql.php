<?php

/**
 * This file is part of the SysCP project.
 * Copyright (c) 2003-2006 the SysCP Project.
 *
 * For the full copyright and license information, please view the COPYING
 * file that was distributed with this source code. You can also view the
 * COPYING file online at http://files.syscp.org/misc/COPYING.txt
 *
 * @author     Florian Lippert <flo@redenswert.de>
 * @copyright  (c) the authors
 * @package    Org.Syscp.Installer
 * @subpackage Updater
 * @license    GPLv2 http://files.syscp.org/misc/COPYING.txt
 * @version    $Id:updatesql.php 460 2006-04-23 15:07:49 +0200 (So, 23 Apr 2006) martin $
 */

/**
 * Includes the Usersettings eg. MySQL-Username/Passwort etc.
 */

require_once '../../etc/userdata.inc.php';

/**
 * Includes the MySQL-Tabledefinitions etc.
 */

require_once '../../etc/tables.inc.php';

/**
 * Inlcudes the MySQL-Connection-Class
 */

require_once '../../lib/classes/Org/Syscp/Core/DB/Mysql.class.php';
$db = new Syscp_DB_Mysql($sql['host'], $sql['user'], $sql['password'], $sql['db']);
unset($sql['password']);
unset($db->password);
$result = $db->query("SELECT `settinggroup`, `varname`, `value` FROM `".TABLE_PANEL_SETTINGS."`");

while($row = $db->fetch_array($result))
{
    $settings["$row[settinggroup]"]["$row[varname]"] = $row['value'];
}

unset($row);
unset($result);

/**
 * Inlcudes the Functions
 */

require_once '../../lib/functions.php';

/**
 * First case: We are updating from a version < 1.0.10
 */

if(!isset($settings['panel']['version'])
   || (substr($settings['panel']['version'], 0, 3) == '1.0' && $settings['panel']['version'] != '1.0.10'))
{
    require_once '../../lib/installer/updatesql_1.0.inc.php';
}

/**
 * Second case: We are updating from version = 1.0.10
 */

if($settings['panel']['version'] == '1.0.10')
{
    require_once '../../lib/installer/updatesql_1.0-1.2.inc.php';
}

/**
 * Third case: We are updating from a version > 1.2-beta1
 */

if(substr($settings['panel']['version'], 0, 3) == '1.2')
{
    require_once '../../lib/installer/updatesql_1.2.inc.php';
}

updateCounters();

//	inserttask ('1');

@chmod('../../etc/userdata.inc.php', 0440);
header('Location: ../index.php');

?>
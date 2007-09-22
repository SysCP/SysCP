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
$lockFilename = 'syscp_cron_traffic.lock-';
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
 * TRAFFIC AND DISKUSAGE MESSURE
 */

fwrite($debugHandler, 'Traffic run started...' . "\n");
$admin_traffic = array();
$domainlist = array();
$speciallogfile_domainlist = array();
$result_domainlist = $db->query("SELECT `id`, `domain`, `customerid`, `parentdomainid`, `speciallogfile` FROM `" . TABLE_PANEL_DOMAINS . "` ;");

while($row_domainlist = $db->fetch_array($result_domainlist))
{
    if(!isset($domainlist[$row_domainlist['customerid']]))
    {
        $domainlist[$row_domainlist['customerid']] = array();
    }

    $domainlist[$row_domainlist['customerid']][$row_domainlist['id']] = $row_domainlist['domain'];

    if($row_domainlist['parentdomainid'] == '0'
       && $row_domainlist['speciallogfile'] == '1')
    {
        if(!isset($speciallogfile_domainlist[$row_domainlist['customerid']]))
        {
            $speciallogfile_domainlist[$row_domainlist['customerid']] = array();
        }

        $speciallogfile_domainlist[$row_domainlist['customerid']][$row_domainlist['id']] = $row_domainlist['domain'];
    }
}

$result = $db->query("SELECT * FROM `" . TABLE_PANEL_CUSTOMERS . "` ORDER BY `customerid` ASC");

while($row = $db->fetch_array($result))
{
    /**
     * HTTP-Traffic
     */

    fwrite($debugHandler, 'http traffic for ' . $row['loginname'] . ' started...' . "\n");
    $httptraffic = 0;

    if(isset($domainlist[$row['customerid']])
       && is_array($domainlist[$row['customerid']])
       && count($domainlist[$row['customerid']]) != 0)
    {
        // Examining which caption to use for default webalizer stats...

        if($row['standardsubdomain'] != '0')
        {
            // ... of course we'd prefer to use the standardsubdomain ...

            $caption = $domainlist[$row['customerid']][$row['standardsubdomain']];
        }
        else
        {
            // ... but if there is no standardsubdomain, we have to use the loginname ...

            $caption = $row['loginname'];

            // ... which results in non-usable links to files in the stats, so lets have a look if we find a domain which is not speciallogfiledomain

            foreach($domainlist[$row['customerid']] as $domainid => $domain)
            {
                if(!isset($speciallogfile_domainlist[$row['customerid']])
                   || !isset($speciallogfile_domainlist[$row['customerid']][$domainid]))
                {
                    $caption = $domain;
                    break;
                }
            }
        }

        reset($domainlist[$row['customerid']]);
        $httptraffic = floatval(callWebalizerGetTraffic($row['loginname'], $row['documentroot'] . '/webalizer/', $caption, $domainlist[$row['customerid']]));

        if(isset($speciallogfile_domainlist[$row['customerid']])
           && is_array($speciallogfile_domainlist[$row['customerid']])
           && count($speciallogfile_domainlist[$row['customerid']]) != 0)
        {
            reset($speciallogfile_domainlist[$row['customerid']]);
            foreach($speciallogfile_domainlist[$row['customerid']] as $domainid => $domain)
            {
                $httptraffic+= floatval(callWebalizerGetTraffic($row['loginname'] . '-' . $domain, $row['documentroot'] . '/webalizer/' . $domain . '/', $domain, $domainlist[$row['customerid']]));
            }
        }
    }

    /**
     * FTP-Traffic
     */

    fwrite($debugHandler, 'ftp traffic for ' . $row['loginname'] . ' started...' . "\n");
    $ftptraffic = $db->query_first("SELECT SUM(`up_bytes`) AS `up_bytes_sum`, SUM(`down_bytes`) AS `down_bytes_sum` FROM `" . TABLE_FTP_USERS . "` WHERE `customerid`='" . (int)$row['customerid'] . "'");

    if(!is_array($ftptraffic))
    {
        $ftptraffic = array(
            'up_bytes_sum' => 0,
            'down_bytes_sum' => 0
        );
    }

    $db->query("UPDATE `" . TABLE_FTP_USERS . "` SET `up_bytes`='0', `down_bytes`='0' WHERE `customerid`='" . (int)$row['customerid'] . "'");

    /**
     * Mail-Traffic
     */

    $mailtraffic = 0;

    /**
     * Total Traffic
     */

    fwrite($debugHandler, 'total traffic for ' . $row['loginname'] . ' started' . "\n");
    $current = array();
    $current['http'] = floatval($httptraffic);
    $current['ftp_up'] = floatval(($ftptraffic['up_bytes_sum']/1024));
    $current['ftp_down'] = floatval(($ftptraffic['down_bytes_sum']/1024));
    $current['mail'] = floatval($mailtraffic);
    $current['all'] = $current['http']+$current['ftp_up']+$current['ftp_down']+$current['mail'];
    $db->query("INSERT INTO `" . TABLE_PANEL_TRAFFIC . "` (`customerid`, `year`, `month`, `day`, `stamp`, `http`, `ftp_up`, `ftp_down`, `mail`) VALUES('" . (int)$row['customerid'] . "', '" . date('Y') . "', '" . date('m') . "', '" . date('d') . "', '" . time() . "', '" . (float)$current['http'] . "', '" . (float)$current['ftp_up'] . "', '" . (float)$current['ftp_down'] . "', '" . (float)$current['mail'] . "')");
    $sum_month = $db->query_first("SELECT SUM(`http`) AS `http`, SUM(`ftp_up`) AS `ftp_up`, SUM(`ftp_down`) AS `ftp_down`, SUM(`mail`) AS `mail` FROM `" . TABLE_PANEL_TRAFFIC . "` WHERE `year`='" . date('Y') . "' AND `month`='" . date('m') . "' AND `customerid`='" . (int)$row['customerid'] . "'");
    $sum_month['all'] = $sum_month['http']+$sum_month['ftp_up']+$sum_month['ftp_down']+$sum_month['mail'];

    if(!isset($admin_traffic[$row['adminid']]))
    {
        $admin_traffic[$row['adminid']]['http'] = 0;
        $admin_traffic[$row['adminid']]['ftp_up'] = 0;
        $admin_traffic[$row['adminid']]['ftp_down'] = 0;
        $admin_traffic[$row['adminid']]['mail'] = 0;
        $admin_traffic[$row['adminid']]['all'] = 0;
        $admin_traffic[$row['adminid']]['sum_month'] = 0;
    }

    $admin_traffic[$row['adminid']]['http']+= $current['http'];
    $admin_traffic[$row['adminid']]['ftp_up']+= $current['ftp_up'];
    $admin_traffic[$row['adminid']]['ftp_down']+= $current['ftp_down'];
    $admin_traffic[$row['adminid']]['mail']+= $current['mail'];
    $admin_traffic[$row['adminid']]['all']+= $current['all'];
    $admin_traffic[$row['adminid']]['sum_month']+= $sum_month['all'];

    /**
     * WebSpace-Usage
     */

    fwrite($debugHandler, 'calculating webspace usage for ' . $row['loginname'] . "\n");
    $webspaceusage = 0;
    $back = safe_exec('du -s ' . escapeshellarg($row['documentroot']) . '');
    foreach($back as $backrow)
    {
        $webspaceusage = explode(' ', $backrow);
    }

    $webspaceusage = floatval($webspaceusage['0']);
    unset($back);

    /**
     * MailSpace-Usage
     */

    fwrite($debugHandler, 'calculating mailspace usage for ' . $row['loginname'] . "\n");
    $emailusage = 0;
    $back = safe_exec('du -s ' . escapeshellarg($settings['system']['vmail_homedir'] . $row['loginname']) . '');
    foreach($back as $backrow)
    {
        $emailusage = explode(' ', $backrow);
    }

    $emailusage = floatval($emailusage['0']);
    unset($back);

    /**
     * MySQLSpace-Usage
     */

    fwrite($debugHandler, 'calculating mysqlspace usage for ' . $row['loginname'] . "\n");
    $mysqlusage = 0;
    $databases_result = $db->query("SELECT `databasename` FROM `" . TABLE_PANEL_DATABASES . "` WHERE `customerid`='" . (int)$row['customerid'] . "'");

    while($database_row = $db->fetch_array($databases_result))
    {
        $mysql_usage_result = $db_root->query("SHOW TABLE STATUS FROM `" . $db_root->escape($database_row['databasename']) . "`");

        while($mysql_usage_row = $db_root->fetch_array($mysql_usage_result))
        {
            $mysqlusage+= floatval($mysql_usage_row['Data_length']+$mysql_usage_row['Index_length']);
        }
    }

    $mysqlusage = floatval($mysqlusage/1024);

    /**
     * Total Usage
     */

    $diskusage = floatval($webspaceusage+$emailusage+$mysqlusage);
    $db->query("UPDATE `" . TABLE_PANEL_CUSTOMERS . "` SET `diskspace_used`='" . (float)$diskusage . "', `traffic_used`='" . (float)$sum_month['all'] . "' WHERE `customerid`='" . (int)$row['customerid'] . "'");
}

/**
 * Admin Usage
 */

$result = $db->query("SELECT `adminid` FROM `" . TABLE_PANEL_ADMINS . "` ORDER BY `adminid` ASC");

while($row = $db->fetch_array($result))
{
    if(isset($admin_traffic[$row['adminid']]))
    {
        $db->query("INSERT INTO `" . TABLE_PANEL_TRAFFIC_ADMINS . "` (`adminid`, `year`, `month`, `day`, `stamp`, `http`, `ftp_up`, `ftp_down`, `mail`) VALUES('" . (int)$row['adminid'] . "', '" . date('Y') . "', '" . date('m') . "', '" . date('d') . "', '" . time() . "', '" . (float)$admin_traffic[$row['adminid']]['http'] . "', '" . (float)$admin_traffic[$row['adminid']]['ftp_up'] . "', '" . (float)$admin_traffic[$row['adminid']]['ftp_down'] . "', '" . (float)$admin_traffic[$row['adminid']]['mail'] . "')");
        $db->query("UPDATE `" . TABLE_PANEL_ADMINS . "` SET `traffic_used`='" . (float)$admin_traffic[$row['adminid']]['sum_month'] . "' WHERE `adminid`='" . (float)$row['adminid'] . "'");
    }
}

$db->query('UPDATE `' . TABLE_PANEL_SETTINGS . '` ' . 'SET `value` = UNIX_TIMESTAMP() ' . 'WHERE `settinggroup` = \'system\'  ' . '  AND `varname`      = \'last_traffic_run\' ');

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

/**
 * Function which make webalizer statistics and returns used traffic since last run
 *
 * @param string Name of logfile
 * @param string Place where stats should be build
 * @param string Caption for webalizer output
 * @return int Used traffic
 * @author Florian Lippert <flo@syscp.org>
 */

function callWebalizerGetTraffic($logfile, $outputdir, $caption, $usersdomainlist)
{
    global $settings;
    $returnval = 0;

    if(file_exists($settings['system']['logfiles_directory'] . $logfile . '-access.log'))
    {
        $domainargs = '';
        foreach($usersdomainlist as $domainid => $domain)
        {
            $domainargs.= ' -r "' . escapeshellarg($domain) . '"';
        }

        $outputdir = makeCorrectDir($outputdir);

        if(!file_exists($outputdir))
        {
            safe_exec('mkdir -p ' . escapeshellarg($outputdir));
        }

        if(file_exists($outputdir . 'webalizer.hist.1'))
        {
            unlink($outputdir . 'webalizer.hist.1');
        }

        if(file_exists($outputdir . 'webalizer.hist')
           && !file_exists($outputdir . 'webalizer.hist.1'))
        {
            safe_exec('cp ' . escapeshellarg($outputdir . 'webalizer.hist') . ' ' . escapeshellarg($outputdir . 'webalizer.hist.1'));
        }

        safe_exec('webalizer -o ' . escapeshellarg($outputdir) . ' -n ' . escapeshellarg($caption) . $domainargs . ' ' . escapeshellarg($settings['system']['logfiles_directory'] . $logfile . '-access.log'));

        /**
         * Format of webalizer.hist-files:
         * Month: $webalizer_hist_row['0']
         * Year:  $webalizer_hist_row['1']
         * KB:    $webalizer_hist_row['5']
         */

        $httptraffic = array();
        $webalizer_hist = @file_get_contents($outputdir . 'webalizer.hist');
        $webalizer_hist_rows = explode("\n", $webalizer_hist);
        foreach($webalizer_hist_rows as $webalizer_hist_row)
        {
            if($webalizer_hist_row != '')
            {
                $webalizer_hist_row = explode(' ', $webalizer_hist_row);

                if(isset($webalizer_hist_row['0'])
                   && isset($webalizer_hist_row['1'])
                   && isset($webalizer_hist_row['5']))
                {
                    $month = intval($webalizer_hist_row['0']);
                    $year = intval($webalizer_hist_row['1']);
                    $traffic = floatval($webalizer_hist_row['5']);

                    if(!isset($httptraffic[$year]))
                    {
                        $httptraffic[$year] = array();
                    }

                    $httptraffic[$year][$month] = $traffic;
                }
            }
        }

        reset($httptraffic);
        $httptrafficlast = array();
        $webalizer_lasthist = @file_get_contents($outputdir . 'webalizer.hist.1');
        $webalizer_lasthist_rows = explode("\n", $webalizer_lasthist);
        foreach($webalizer_lasthist_rows as $webalizer_lasthist_row)
        {
            if($webalizer_lasthist_row != '')
            {
                $webalizer_lasthist_row = explode(' ', $webalizer_lasthist_row);

                if(isset($webalizer_lasthist_row['0'])
                   && isset($webalizer_lasthist_row['1'])
                   && isset($webalizer_lasthist_row['5']))
                {
                    $month = intval($webalizer_lasthist_row['0']);
                    $year = intval($webalizer_lasthist_row['1']);
                    $traffic = floatval($webalizer_lasthist_row['5']);

                    if(!isset($httptrafficlast[$year]))
                    {
                        $httptrafficlast[$year] = array();
                    }

                    $httptrafficlast[$year][$month] = $traffic;
                }
            }
        }

        reset($httptrafficlast);
        foreach($httptraffic as $year => $months)
        {
            foreach($months as $month => $traffic)
            {
                if(!isset($httptrafficlast[$year][$month]))
                {
                    $returnval+= $traffic;
                }
                elseif($httptrafficlast[$year][$month] < $httptraffic[$year][$month])
                {
                    $returnval+= ($httptraffic[$year][$month]-$httptrafficlast[$year][$month]);
                }
            }
        }
    }

    return floatval($returnval);
}

?>
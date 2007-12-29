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
$lockFilename = 'syscp_cron_tasks.lock-';
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

/**
 * LOOK INTO TASKS TABLE TO SEE IF THERE ARE ANY UNDONE JOBS
 */

fwrite($debugHandler, '  cron_tasks: Searching for tasks to do' . "\n");
$result_tasks = $db->query("SELECT `id`, `type`, `data` FROM `" . TABLE_PANEL_TASKS . "` ORDER BY `id` ASC");
$resultIDs = array();

while($row = $db->fetch_array($result_tasks))
{
	$resultIDs[] = $row['id'];

	if($row['data'] != '')
	{
		$row['data'] = unserialize($row['data']);
	}

	/**
	 * TYPE=1 MEANS TO REBUILD APACHE VHOSTS.CONF
	 */

	if($row['type'] == '1')
	{
		fwrite($debugHandler, '  cron_tasks: Task1 started - ' . $settings['system']['apacheconf_filename'] . ' rebuild' . "\n");
		$vhosts_file = '# ' . $settings['system']['apacheconf_directory'] . $settings['system']['apacheconf_filename'] . "\n" . '# Created ' . date('d.m.Y H:i') . "\n" . '# Do NOT manually edit this file, all changes will be ' . ' deleted after the next domain change at the panel.' . "\n" . "\n";

		if(file_exists($settings['system']['apacheconf_directory'] . 'diroptions.conf'))
		{
			$vhosts_file.= 'Include ' . $settings['system']['apacheconf_directory'] . 'diroptions.conf' . "\n\n";
		}

		$result_ipsandports = $db->query("SELECT `ip`, `port`, `vhostcontainer`, `specialsettings` FROM `" . TABLE_PANEL_IPSANDPORTS . "` ORDER BY `ip` ASC, `port` ASC");

		while($row_ipsandports = $db->fetch_array($result_ipsandports))
		{
			$vhosts_file.= 'Listen ' . $row_ipsandports['ip'] . ':' . $row_ipsandports['port'] . "\n";
			$vhosts_file.= 'NameVirtualHost ' . $row_ipsandports['ip'] . ':' . $row_ipsandports['port'] . "\n";

			if($row_ipsandports['vhostcontainer'] == '1')
			{
				$vhosts_file.= '<VirtualHost ' . $row_ipsandports['ip'] . ':' . $row_ipsandports['port'] . '>' . "\n";

				if($row_ipsandports['specialsettings'] != '')
				{
					$vhosts_file.= $row_ipsandports['specialsettings'] . "\n";
				}

				$vhosts_file.= '</VirtualHost>' . "\n";
			}

			$vhosts_file.= "\n";
		}

		$result_domains = $db->query("SELECT `d`.`id`, `d`.`domain`, `d`.`customerid`, `d`.`documentroot`, CONCAT(`ip`.`ip`,':',`ip`.`port`) AS `ipandport`, `d`.`parentdomainid`, `d`.`isemaildomain`, `d`.`iswildcarddomain`, `d`.`openbasedir`, `d`.`openbasedir_path`, `d`.`safemode`, `d`.`speciallogfile`, `d`.`specialsettings`, `pd`.`domain` AS `parentdomain`, `c`.`loginname`, `c`.`guid`, `c`.`email`, `c`.`documentroot` AS `customerroot`, `c`.`deactivated`, `c`.`phpenabled` AS `phpenabled` FROM `" . TABLE_PANEL_DOMAINS . "` `d` LEFT JOIN `" . TABLE_PANEL_CUSTOMERS . "` `c` USING(`customerid`) LEFT JOIN `" . TABLE_PANEL_DOMAINS . "` `pd` ON (`pd`.`id` = `d`.`parentdomainid`) LEFT JOIN `" . TABLE_PANEL_IPSANDPORTS . "` `ip` ON (`d`.`ipandport` = `ip`.`id`) WHERE `d`.`aliasdomain` IS NULL ORDER BY `d`.`iswildcarddomain`, `d`.`domain` ASC");

		while($domain = $db->fetch_array($result_domains))
		{
			fwrite($debugHandler, '  cron_tasks: Task1 - Writing Domain ' . $domain['id'] . '::' . $domain['domain'] . "\n");
			$vhosts_file.= '# Domain ID: ' . $domain['id'] . ' - CustomerID: ' . $domain['customerid'] . ' - CustomerLogin: ' . $domain['loginname'] . "\n";

			if($domain['deactivated'] != '1'
			   || $settings['system']['deactivateddocroot'] != '')
			{
				$vhosts_file.= '<VirtualHost ' . $domain['ipandport'] . '>' . "\n";
				$vhosts_file.= '  ServerName ' . $domain['domain'] . "\n";
				$server_alias = '';
				$alias_domains = $db->query('SELECT `domain`, `iswildcarddomain` FROM `' . TABLE_PANEL_DOMAINS . '` WHERE `aliasdomain`=\'' . $domain['id'] . '\'');

				while(($alias_domain = $db->fetch_array($alias_domains)) !== false)
				{
					$server_alias.= ' ' . $alias_domain['domain'] . ' ' . (($alias_domain['iswildcarddomain'] == 1) ? '*' : 'www') . '.' . $alias_domain['domain'];
				}

				if($domain['iswildcarddomain'] == '1')
				{
					$alias = '*';
				}
				else
				{
					$alias = 'www';
				}

				$vhosts_file.= '  ServerAlias ' . $alias . '.' . $domain['domain'] . $server_alias . "\n";
				$vhosts_file.= '  ServerAdmin ' . $domain['email'] . "\n";

				if(preg_match('/^https?\:\/\//', $domain['documentroot']))
				{
					$vhosts_file.= '  Redirect / ' . $domain['documentroot'] . "\n";
				}
				else
				{
					$domain['customerroot'] = makeCorrectDir($domain['customerroot']);
					$domain['documentroot'] = makeCorrectDir($domain['documentroot']);

					if($domain['deactivated'] == '1'
					   && $settings['system']['deactivateddocroot'] != '')
					{
						$vhosts_file.= '  # Using docroot for deactivated users...' . "\n";
						$vhosts_file.= '  DocumentRoot "' . $settings['system']['deactivateddocroot'] . "\"\n";
					}
					else
					{
						$vhosts_file.= '  DocumentRoot "' . $domain['documentroot'] . "\"\n";
					}

					if($domain['phpenabled'] == '1')
					{
						// This vHost has PHP enabled...

						if($settings['system']['mod_fcgid'] == 1)
						{
							// ...and we are using mod_fcgid
							// TODO: Put this in the join on line 212

							$sql = "SELECT * FROM `ftp_users` WHERE `customerid` = " . $domain['customerid'];
							$result_ftp = $db->query($sql);

							if($db->num_rows($result_ftp) > 0)
							{
								$vhosts_file.= '  ScriptAlias /php/ /var/www/php-fcgi-scripts/' . $domain['loginname'] . '/' . $domain['domain'] . '/' . "\n";
								$vhosts_file.= '  SuexecUserGroup "' . $domain['loginname'] . '" "' . $domain['loginname'] . '"' . "\n";
								include ($pathtophpfiles . '/scripts/cron_tasks.inc.fcgi_create.php');
							}
						}
						else
						{
							// ...and we are using the regular mod_php

							if($domain['openbasedir'] == '1')
							{
								if($settings['system']['phpappendopenbasedir'] != '')
								{
									$_phpappendopenbasedir = ':' . $settings['system']['phpappendopenbasedir'];
								}
								else
								{
									$_phpappendopenbasedir = '';
								}

								if($domain['openbasedir_path'] == '1')
								{
									$vhosts_file.= '  php_admin_value open_basedir "' . $domain['customerroot'] . $_phpappendopenbasedir . "\"\n";
								}
								else
								{
									$vhosts_file.= '  php_admin_value open_basedir "' . $domain['documentroot'] . $_phpappendopenbasedir . "\"\n";
								}
							}
						}
					}

					mkDirWithCorrectOwnership($domain['customerroot'], $domain['documentroot'], $domain['guid'], $domain['guid']);

					if($domain['speciallogfile'] == '1')
					{
						if($domain['parentdomainid'] == '0')
						{
							$speciallogfile = '-' . $domain['domain'];
							$vhosts_file.= '  Alias /webalizer "' . $domain['customerroot'] . '/webalizer/' . $domain['domain'] . "\"\n";
						}
						else
						{
							$speciallogfile = '-' . $domain['parentdomain'];
							$vhosts_file.= '  Alias /webalizer "' . $domain['customerroot'] . '/webalizer/' . $domain['parentdomain'] . "\"\n";
						}
					}
					else
					{
						$speciallogfile = '';

						if($domain['customerroot'] != $domain['documentroot'])
						{
							$vhosts_file.= '  Alias /webalizer "' . $domain['customerroot'] . '/webalizer"' . "\n";
						}
					}

					if($settings['system']['mod_log_sql'] == 1)
					{
						// We are using mod_log_sql (http://www.outoforder.cc/projects/apache/mod_log_sql/)
						// TODO: See how we are able emulate the error_log

						$vhosts_file.= '  LogSQLTransferLogTable access_log' . "\n";
					}
					else
					{
						// The normal access/error - logging is enabled

						$vhosts_file.= '  ErrorLog "' . $settings['system']['logfiles_directory'] . $domain['loginname'] . $speciallogfile . '-error.log' . "\"\n";
						$vhosts_file.= '  CustomLog "' . $settings['system']['logfiles_directory'] . $domain['loginname'] . $speciallogfile . '-access.log" combined' . "\n";
					}
				}

				if($domain['specialsettings'] != '')
				{
					$vhosts_file.= $domain['specialsettings'] . "\n";
				}

				$vhosts_file.= '</VirtualHost>' . "\n";
				$vhosts_file.= "\n";
			}
			else
			{
				$vhosts_file.= '# Customer deactivated and a docroot for deactivated users hasn\'t been set.' . "\n";
			}
		}

		$vhosts_file_handler = fopen($settings['system']['apacheconf_directory'] . $settings['system']['apacheconf_filename'], 'w');
		fwrite($vhosts_file_handler, $vhosts_file);
		fclose($vhosts_file_handler);
		safe_exec($settings['system']['apachereload_command']);
		fwrite($debugHandler, '   cron_tasks: Task1 - Apache reloaded' . "\n");
	}

	/**
	 * TYPE=2 MEANS TO CREATE A NEW HOME AND CHOWN
	 */
	elseif ($row['type'] == '2')
	{
		fwrite($debugHandler, '  cron_tasks: Task2 started - create new home' . "\n");

		if(is_array($row['data']))
		{
			safe_exec('mkdir -p ' . escapeshellarg($settings['system']['documentroot_prefix'] . $row['data']['loginname'] . '/webalizer'));
			safe_exec('mkdir -p ' . escapeshellarg($settings['system']['vmail_homedir'] . $row['data']['loginname']));
			safe_exec('cp -a ' . $pathtophpfiles . '/templates/misc/standardcustomer/* ' . escapeshellarg($settings['system']['documentroot_prefix'] . $row['data']['loginname'] . '/'));
			safe_exec('chown -R ' . (int)$row['data']['uid'] . ':' . (int)$row['data']['gid'] . ' ' . escapeshellarg($settings['system']['documentroot_prefix'] . $row['data']['loginname']));
			safe_exec('chown -R ' . (int)$settings['system']['vmail_uid'] . ':' . (int)$settings['system']['vmail_gid'] . ' ' . escapeshellarg($settings['system']['vmail_homedir'] . $row['data']['loginname']));
		}
	}

	/**
	 * TYPE=3 MEANS TO CREATE/CHANGE/DELETE A HTACCESS AND/OR HTPASSWD
	 */
	elseif ($row['type'] == '3')
	{
		fwrite($debugHandler, '  cron_tasks: Task3 started - create/change/del htaccess/htpasswd' . "\n");
		$diroptions_file = '';
		$diroptions_file = '# ' . $settings['system']['apacheconf_directory'] . 'diroptions.conf' . "\n" . '# Created ' . date('d.m.Y H:i') . "\n" . '# Do NOT manually edit this file, all changes will be deleted after the next dir options change at the panel.' . "\n" . "\n";
		$result = $db->query('SELECT `htac`.*, `c`.`guid`, `c`.`documentroot` AS `customerroot` ' . 'FROM `' . TABLE_PANEL_HTACCESS . '` `htac` ' . 'LEFT JOIN `' . TABLE_PANEL_CUSTOMERS . '` `c` USING (`customerid`) ' . 'ORDER BY `htac`.`path`');
		$diroptions = array();

		while($row_diroptions = $db->fetch_array($result))
		{
			if($row_diroptions['customerid'] != 0
			   && isset($row_diroptions['customerroot'])
			   && $row_diroptions['customerroot'] != '')
			{
				$diroptions[$row_diroptions['path']] = $row_diroptions;
				$diroptions[$row_diroptions['path']]['htpasswds'] = array();
			}
		}

		$result = $db->query('SELECT `htpw`.*, `c`.`guid`, `c`.`documentroot` AS `customerroot` ' . 'FROM `' . TABLE_PANEL_HTPASSWDS . '` `htpw` ' . 'LEFT JOIN `' . TABLE_PANEL_CUSTOMERS . '` `c` USING (`customerid`) ' . 'ORDER BY `htpw`.`path`, `htpw`.`username`');

		while($row_htpasswds = $db->fetch_array($result))
		{
			if($row_htpasswds['customerid'] != 0
			   && isset($row_htpasswds['customerroot'])
			   && $row_htpasswds['customerroot'] != '')
			{
				if(!is_array($diroptions[$row_htpasswds['path']]))
				{
					$diroptions[$row_htpasswds['path']] = array();
				}

				$diroptions[$row_htpasswds['path']]['path'] = $row_htpasswds['path'];
				$diroptions[$row_htpasswds['path']]['guid'] = $row_htpasswds['guid'];
				$diroptions[$row_htpasswds['path']]['customerroot'] = $row_htpasswds['customerroot'];
				$diroptions[$row_htpasswds['path']]['customerid'] = $row_htpasswds['customerid'];
				$diroptions[$row_htpasswds['path']]['htpasswds'][] = $row_htpasswds;
			}
		}

		$htpasswd_files = array();
		foreach($diroptions as $row_diroptions)
		{
			mkDirWithCorrectOwnership($row_diroptions['customerroot'], $row_diroptions['path'], $row_diroptions['guid'], $row_diroptions['guid']);

			if(is_dir($row_diroptions['path']))
			{
				$diroptions_file.= '<Directory "' . $row_diroptions['path'] . '">' . "\n";

				if(isset($row_diroptions['options_indexes'])
				   && $row_diroptions['options_indexes'] == '1')
				{
					$diroptions_file.= '  Options +Indexes' . "\n";
					fwrite($debugHandler, '  cron_tasks: Task3 - Setting Options +Indexes' . "\n");
				}

				if(isset($row_diroptions['options_indexes'])
				   && $row_diroptions['options_indexes'] == '0')
				{
					$diroptions_file.= '  Options -Indexes' . "\n";
					fwrite($debugHandler, '  cron_tasks: Task3 - Setting Options -Indexes' . "\n");
				}

				if(isset($row_diroptions['error404path'])
				   && $row_diroptions['error404path'] != '')
				{
					$diroptions_file.= '  ErrorDocument 404 ' . $row_diroptions['error404path'] . "\n";
				}

				if(isset($row_diroptions['error403path'])
				   && $row_diroptions['error403path'] != '')
				{
					$diroptions_file.= '  ErrorDocument 403 ' . $row_diroptions['error403path'] . "\n";
				}

				//					if ( isset ( $row_diroptions['error401path'] ) && $row_diroptions['error401path'] != '')
				//					{
				//						$diroptions_file .= '  ErrorDocument 401 '.$row_diroptions['error401path']."\n";
				//					}

				if(isset($row_diroptions['error500path'])
				   && $row_diroptions['error500path'] != '')
				{
					$diroptions_file.= '  ErrorDocument 500 ' . $row_diroptions['error500path'] . "\n";
				}

				if(count($row_diroptions['htpasswds']) > 0)
				{
					$htpasswd_file = '';
					$htpasswd_filename = '';
					foreach($row_diroptions['htpasswds'] as $row_htpasswd)
					{
						if($htpasswd_filename == '')
						{
							$htpasswd_filename = $settings['system']['apacheconf_directory'] . 'htpasswd/' . $row_diroptions['customerid'] . '-' . $row_htpasswd['id'] . '-' . md5($row_diroptions['path']) . '.htpasswd';
							$htpasswd_files[] = basename($htpasswd_filename);
						}

						$htpasswd_file.= $row_htpasswd['username'] . ':' . $row_htpasswd['password'] . "\n";
					}

					fwrite($debugHandler, '  cron_tasks: Task3 - Setting Password' . "\n");
					$diroptions_file.= '  AuthType Basic' . "\n";
					$diroptions_file.= '  AuthName "Restricted Area"' . "\n";
					$diroptions_file.= '  AuthUserFile ' . $htpasswd_filename . "\n";
					$diroptions_file.= '  require valid-user' . "\n";

					if(!file_exists($settings['system']['apacheconf_directory'] . 'htpasswd/'))
					{
						$umask = umask();
						umask(0000);
						mkdir($settings['system']['apacheconf_directory'] . 'htpasswd/', 0751);
						umask($umask);
					}
					elseif(!is_dir($settings['system']['apacheconf_directory'] . 'htpasswd/'))
					{
						fwrite($debugHandler, '  cron_tasks: WARNING!!! ' . $settings['system']['apacheconf_directory'] . 'htpasswd/ is not a directory. htpasswd directory protection is disabled!!!' . "\n");
						echo 'WARNING!!! ' . $settings['system']['apacheconf_directory'] . 'htpasswd/ is not a directory. htpasswd directory protection is disabled!!!';
					}

					if(file_exists($settings['system']['apacheconf_directory'] . 'htpasswd/')
					   && is_dir($settings['system']['apacheconf_directory'] . 'htpasswd/'))
					{
						$htpasswd_file_handler = fopen($htpasswd_filename, 'w');
						fwrite($htpasswd_file_handler, $htpasswd_file);
						fclose($htpasswd_file_handler);
					}
				}

				$diroptions_file.= '</Directory>' . "\n\n";
			}
		}

		$diroptions_file_handler = fopen($settings['system']['apacheconf_directory'] . 'diroptions.conf', 'w');
		fwrite($diroptions_file_handler, $diroptions_file);
		fclose($diroptions_file_handler);
		safe_exec($settings['system']['apachereload_command']);
		$htpasswd_dir = makeCorrectDir($settings['system']['apacheconf_directory'] . 'htpasswd/');

		if(file_exists($htpasswd_dir)
		   && is_dir($htpasswd_dir))
		{
			$htpasswd_file_dirhandle = opendir($htpasswd_dir);

			while(false !== ($htpasswd_filename = readdir($htpasswd_file_dirhandle)))
			{
				if($htpasswd_filename != '.'
				   && $htpasswd_filename != '..'
				   && !in_array($htpasswd_filename, $htpasswd_files)
				   && file_exists($settings['system']['apacheconf_directory'] . 'htpasswd/' . $htpasswd_filename))
				{
					unlink($settings['system']['apacheconf_directory'] . 'htpasswd/' . $htpasswd_filename);
				}
			}
		}
	}

	/**
	 * TYPE=4 MEANS THAT SOMETHING IN THE BIND CONFIG HAS CHANGED. REBUILD syscp_bind.conf
	 */
	elseif ($row['type'] == '4')
	{
		fwrite($debugHandler, '  cron_tasks: Task4 started - Rebuilding syscp_bind.conf' . "\n");

		if(!file_exists($settings['system']['bindconf_directory'] . 'domains/'))
		{
			safe_exec('mkdir ' . escapeshellarg(makeCorrectDir($settings['system']['bindconf_directory'] . '/domains/')));
		}

		$bindconf_file = '# ' . $settings['system']['bindconf_directory'] . 'syscp_bind.conf' . "\n" . '# Created ' . date('d.m.Y H:i') . "\n" . '# Do NOT manually edit this file, all changes will be deleted after the next domain change at the panel.' . "\n" . "\n";
		$nameservers = (($settings['system']['nameservers'] != '') ? split(',', $settings['system']['nameservers']) : array());
		for ($i = 0;$i < count($nameservers);$i++)
		{
			if(!preg_match('/\.$/', $nameservers[$i]))
			{
				$nameservers[$i].= '.';
			}

			$array = array(
				'hostname' => trim($nameservers[$i]),
				'ip' => gethostbyname(trim($nameservers[$i]))
			);
			$nameservers[$i] = $array;
		}

		$mxservers = (($settings['system']['mxservers'] != '') ? split(',', $settings['system']['mxservers']) : array());
		for ($i = 0;$i < count($mxservers);$i++)
		{
			if(!preg_match('/\.$/', $mxservers[$i]))
			{
				$mxservers[$i].= '.';
			}
		}

		$known_domain_zonefiles = array();
		$result_domains = $db->query("SELECT `d`.`id`, `d`.`domain`, `d`.`iswildcarddomain`, `d`.`customerid`, `d`.`zonefile`, `d`.`bindserial`, `ip`.`ip`, `c`.`loginname`, `c`.`guid` FROM `" . TABLE_PANEL_DOMAINS . "` `d` LEFT JOIN `" . TABLE_PANEL_CUSTOMERS . "` `c` USING(`customerid`) LEFT JOIN `" . TABLE_PANEL_IPSANDPORTS . "` AS `ip` ON(`d`.`ipandport`=`ip`.`id`) WHERE `d`.`isbinddomain` = '1' ORDER BY `d`.`domain` ASC");

		while($domain = $db->fetch_array($result_domains))
		{
			fwrite($debugHandler, '  cron_tasks: Task4 - Writing ' . $domain['id'] . '::' . $domain['domain'] . "\n");

			if($domain['zonefile'] == '')
			{
				$date = date('Ymd');
				$bindserial = (preg_match('/^' . $date . '/', $domain['bindserial']) ? $domain['bindserial']+1 : $date . '00');
				$db->query('UPDATE `' . TABLE_PANEL_DOMAINS . '` SET `bindserial`=\'' . $bindserial . '\' WHERE `id`=\'' . $domain['id'] . '\'');
				$zonefile = '$TTL 1W' . "\n";

				if(count($nameservers) == 0)
				{
					$zonefile.= '@ IN SOA ns ' . str_replace('@', '.', $settings['panel']['adminmail']) . ' (' . "\n";
				}
				else
				{
					$zonefile.= '@ IN SOA ' . $nameservers[0]['hostname'] . ' ' . str_replace('@', '.', $settings['panel']['adminmail']) . ' (' . "\n";
				}

				$zonefile.= '	' . $bindserial . ' ; serial' . "\n" . '	8H ; refresh' . "\n" . '	2H ; retry' . "\n" . '	1W ; expiry' . "\n" . '	11h) ; minimum' . "\n" . '';

				if(count($nameservers) == 0)
				{
					$zonefile.= '		IN  NS	ns' . "\n" . 'ns		IN	A	' . $domain['ip'] . "\n";
				}
				else
				{
					foreach($nameservers as $nameserver)
					{
						$zonefile.= '		IN NS ' . $nameserver['hostname'] . "\n";
					}
				}

				if(count($mxservers) == 0)
				{
					$zonefile.= '		IN MX	10 mail' . "\n" . 'mail	IN A	' . $domain['ip'] . "\n";
				}
				else
				{
					foreach($mxservers as $mxserver)
					{
						$zonefile.= '		IN MX	' . $mxserver . "\n";
					}
				}

				$nssubdomains = $db->query('SELECT `domain` FROM `' . TABLE_PANEL_DOMAINS . '` WHERE `isbinddomain`=\'1\' AND `domain` LIKE \'%.' . $domain['domain'] . '\'');

				while($nssubdomain = $db->fetch_array($nssubdomains))
				{
					if(preg_match('/^[^\.]+\.' . $domain['domain'] . '/', $nssubdomain['domain']))
					{
						$nssubdomain = str_replace('.' . $domain['domain'], '', $nssubdomain['domain']);

						if(count($nameservers) == 0)
						{
							$zonefile.= $nssubdomain . '		IN  NS	ns.' . $nssubdomain . "\n";

							//												'ns		IN	A	' . $domain['ip'] . "\n";
						}
						else
						{
							foreach($nameservers as $nameserver)
							{
								$zonefile.= $nssubdomain . '		IN NS ' . $nameserver['hostname'] . "\n";
							}
						}
					}
				}

				$zonefile.= '		IN  A ' . $domain['ip'] . "\n" . 'www	IN  A ' . $domain['ip'] . "\n";

				if($domain['iswildcarddomain'] == '1')
				{
					$zonefile.= '*		IN  A ' . $domain['ip'] . "\n";
				}

				$subdomains = $db->query('SELECT `d`.`domain`, `ip`.`ip` AS `ip` FROM `' . TABLE_PANEL_DOMAINS . '` `d`, `' . TABLE_PANEL_IPSANDPORTS . '` `ip` WHERE `parentdomainid`=\'' . $domain['id'] . '\' AND `d`.`ipandport`=`ip`.`id`');

				while($subdomain = $db->fetch_array($subdomains))
				{
					$zonefile.= str_replace('.' . $domain['domain'], '', $subdomain['domain']) . '	IN A	' . $subdomain['ip'] . "\n";
				}

				$domain['zonefile'] = 'domains/' . $domain['domain'] . '.zone';
				$zonefile_name = makeCorrectFile($settings['system']['bindconf_directory'] . '/domains/' . $domain['domain'] . '.zone');
				$known_domain_zonefiles[] = basename($zonefile_name);
				$zonefile_handler = fopen($zonefile_name, 'w');
				fwrite($zonefile_handler, $zonefile);
				fclose($zonefile_handler);
				fwrite($debugHandler, '  cron_tasks: Task4 - `' . $zonefile_name . '` zone written' . "\n");
			}

			$bindconf_file.= '# Domain ID: ' . $domain['id'] . ' - CustomerID: ' . $domain['customerid'] . ' - CustomerLogin: ' . $domain['loginname'] . "\n";
			$bindconf_file.= 'zone "' . $domain['domain'] . '" in {' . "\n";
			$bindconf_file.= '	type master;' . "\n";
			$bindconf_file.= '	file "' . $settings['system']['bindconf_directory'] . $domain['zonefile'] . '";' . "\n";
			$bindconf_file.= '	allow-query { any; };' . "\n";

			if(count($nameservers) > 1)
			{
				$bindconf_file.= '	allow-transfer {' . "\n";
				for ($i = 1;$i < count($nameservers);$i++)
				{
					$bindconf_file.= '		' . $nameservers[$i]['ip'] . ';' . "\n";
				}

				$bindconf_file.= '	}' . "\n";
			}

			$bindconf_file.= '};' . "\n";
			$bindconf_file.= "\n";
			$bindconf_file_handler = fopen(makeCorrectFile($settings['system']['bindconf_directory'] . '/syscp_bind.conf'), 'w');
			fwrite($bindconf_file_handler, $bindconf_file);
			fclose($bindconf_file_handler);
			fwrite($debugHandler, '  cron_tasks: Task4 - syscp_bind.conf written' . "\n");
		}

		safe_exec($settings['system']['bindreload_command']);
		fwrite($debugHandler, '  cron_tasks: Task4 - Bind9 reloaded' . "\n");
		$domains_dir = makeCorrectDir($settings['system']['bindconf_directory'] . '/domains/');

		if(file_exists($domains_dir)
		   && is_dir($domains_dir))
		{
			$domain_file_dirhandle = opendir($domains_dir);

			while(false !== ($domain_filename = readdir($domain_file_dirhandle)))
			{
				if($domain_filename != '.'
				   && $domain_filename != '..'
				   && !in_array($domain_filename, $known_domain_zonefiles)
				   && file_exists($domains_dir . $domain_filename))
				{
					fwrite($debugHandler, '  cron_tasks: Task4 - unlinking ' . $domain_filename . "\n");
					unlink($domains_dir . $domain_filename);
				}
			}
		}
	}

	/**
	 * TYPE=5 MEANS THAT A NEW FTP-ACCOUNT HAS BEEN CREATED, CREATE THE DIRECTORY
	 */
	elseif ($row['type'] == '5')
	{
		$result_directories = $db->query('SELECT `f`.`homedir`, `f`.`uid`, `f`.`gid`, `c`.`documentroot` AS `customerroot` ' . 'FROM `' . TABLE_FTP_USERS . '` `f` ' . 'LEFT JOIN `' . TABLE_PANEL_CUSTOMERS . '` `c` USING (`customerid`) ');

		while($directory = $db->fetch_array($result_directories))
		{
			mkDirWithCorrectOwnership($directory['customerroot'], $directory['homedir'], $directory['uid'], $directory['gid']);
		}
	}
}

if($db->num_rows($result_tasks) != 0)
{
	$where = array();
	foreach($resultIDs as $id)
	{
		$where[] = '`id`=\'' . (int)$id . '\'';
	}

	$where = implode($where, ' OR ');
	$db->query('DELETE FROM `' . TABLE_PANEL_TASKS . '` WHERE ' . $where);
	unset($resultiDs);
	unset($where);
}

$db->query('UPDATE `' . TABLE_PANEL_SETTINGS . '` ' . 'SET `value` = UNIX_TIMESTAMP() ' . 'WHERE `settinggroup` = \'system\'  ' . '  AND `varname`      = \'last_tasks_run\' ');

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
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
 * @author     Florian Lippert <flo@syscp.org>
 * @author     Martin Burchert <eremit@syscp.org>
 * @license    GPLv2 http://files.syscp.org/misc/COPYING.txt
 * @package    System
 * @version    $Id: cron_tasks.php 1816 2008-04-27 12:23:50Z EleRas $
 */

/*
 * This script creates the php.ini's used by mod_suPHP+php-cgi
 */

if(@php_sapi_name() != 'cli'
   && @php_sapi_name() != 'cgi'
   && @php_sapi_name() != 'cgi-fcgi')
{
	die('This script only works in the shell.');
}

class apache
{
	var $db = false;
	var $logger = false;
	var $debugHandler = false;
	var $settings = array();
	
	function __construct( $db, $logger, $debugHandler, $settings )
	{
		$this->db = $db;
		$this->logger = $logger;
		$this->debugHandler = $debugHandler;
		$this->settings = $settings;
	}
	
	function createVhosts()
	{
		fwrite($this->debugHandler, '  apache::creatVhosts: rebuilding ' . $this->settings['system']['apacheconf_vhost'] . '' . "\n");
		$this->logger->logAction(CRON_ACTION, LOG_INFO, "rebuilding " . $this->settings['system']['apacheconf_vhost'] . "");
		$vhosts_file = '';

		if(isConfigDir($this->settings['system']['apacheconf_vhost'])
		   && !file_exists($this->settings['system']['apacheconf_vhost']))
		{
			safe_exec('mkdir ' . escapeshellarg(makeCorrectDir($this->settings['system']['apacheconf_vhost'])));
			$this->logger->logAction(CRON_ACTION, LOG_NOTICE, 'mkdir ' . escapeshellarg(makeCorrectDir($this->settings['system']['apacheconf_vhost'])));
		}

		$known_vhost_filenames = array();
		$result_ipsandports = $this->db->query("SELECT `ip`, `port`, `listen_statement`, `namevirtualhost_statement`, `vhostcontainer`, `vhostcontainer_servername_statement`, `specialsettings` FROM `" . TABLE_PANEL_IPSANDPORTS . "` ORDER BY `ip` ASC, `port` ASC");

		while($row_ipsandports = $this->db->fetch_array($result_ipsandports))
		{
			if($row_ipsandports['listen_statement'] == '1')
			{
				$vhosts_file.= 'Listen ' . $row_ipsandports['ip'] . ':' . $row_ipsandports['port'] . "\n";
				$this->logger->logAction(CRON_ACTION, LOG_NOTICE, 'inserted listen-statement');
			}

			if($row_ipsandports['namevirtualhost_statement'] == '1')
			{
				$vhosts_file.= 'NameVirtualHost ' . $row_ipsandports['ip'] . ':' . $row_ipsandports['port'] . "\n";
				$this->logger->logAction(CRON_ACTION, LOG_NOTICE, 'inserted namevirtualhost-statement');
			}

			if($row_ipsandports['vhostcontainer'] == '1')
			{
				$vhosts_file.= '<VirtualHost ' . $row_ipsandports['ip'] . ':' . $row_ipsandports['port'] . '>' . "\n";

				if($row_ipsandports['vhostcontainer_servername_statement'] == '1')
				{
					$vhosts_file.= ' ServerName ' . $this->settings['system']['hostname'] . "\n";
				}

				if($row_ipsandports['specialsettings'] != '')
				{
					$vhosts_file.= $row_ipsandports['specialsettings'] . "\n";
				}

				$vhosts_file.= '</VirtualHost>' . "\n";
				$this->logger->logAction(CRON_ACTION, LOG_NOTICE, 'inserted vhostcontainer');
			}

			if(isConfigDir($this->settings['system']['apacheconf_vhost']))
			{
				$vhosts_filename = makeCorrectFile($this->settings['system']['apacheconf_vhost'] . '/10_syscp_ipandport_' . $row_ipsandports['ip'] . '.' . $row_ipsandports['port'] . '.conf');
				$known_vhost_filenames[] = basename($vhosts_filename);

				// Apply header

				$vhosts_file = '# ' . $vhosts_filename . "\n" . '# Created ' . date('d.m.Y H:i') . "\n" . '# Do NOT manually edit this file, all changes will be deleted after the next domain change at the panel.' . "\n" . "\n" . $vhosts_file;

				// Save partial file

				$vhosts_file_handler = fopen($vhosts_filename, 'w');
				fwrite($vhosts_file_handler, $vhosts_file);
				fclose($vhosts_file_handler);

				// Reset vhosts_file

				$vhosts_file = '';
			}
			else
			{
				$vhosts_file.= "\n";
			}
		}

		$result_domains = $this->db->query("SELECT `d`.`id`, `d`.`domain`, `d`.`customerid`, `d`.`documentroot`, CONCAT(`ip`.`ip`,':',`ip`.`port`) AS `ipandport`, `d`.`parentdomainid`, `d`.`isemaildomain`, `d`.`iswildcarddomain`, `d`.`openbasedir`, `d`.`openbasedir_path`, `d`.`safemode`, `d`.`speciallogfile`, `d`.`specialsettings`, `pd`.`domain` AS `parentdomain`, `c`.`loginname`, `c`.`guid`, `c`.`email`, `c`.`documentroot` AS `customerroot`, `c`.`deactivated`, `c`.`phpenabled` AS `phpenabled` FROM `" . TABLE_PANEL_DOMAINS . "` `d` LEFT JOIN `" . TABLE_PANEL_CUSTOMERS . "` `c` USING(`customerid`) LEFT JOIN `" . TABLE_PANEL_DOMAINS . "` `pd` ON (`pd`.`id` = `d`.`parentdomainid`) LEFT JOIN `" . TABLE_PANEL_IPSANDPORTS . "` `ip` ON (`d`.`ipandport` = `ip`.`id`) WHERE `d`.`aliasdomain` IS NULL ORDER BY `d`.`iswildcarddomain`, `d`.`domain` ASC");

		while($domain = $this->db->fetch_array($result_domains))
		{
			fwrite($this->debugHandler, '  cron_tasks: Task1 - Writing Domain ' . $domain['id'] . '::' . $domain['domain'] . "\n");
			$vhosts_file.= '# Domain ID: ' . $domain['id'] . ' - CustomerID: ' . $domain['customerid'] . ' - CustomerLogin: ' . $domain['loginname'] . "\n";
			$this->logger->logAction(CRON_ACTION, LOG_INFO, 'creating vhostcontainer for domain #' . $domain['id'] . ', Customer ' . $domain['loginname']);

			if($domain['deactivated'] != '1'
			   || $this->settings['system']['deactivateddocroot'] != '')
			{
				$vhosts_file.= '<VirtualHost ' . $domain['ipandport'] . '>' . "\n";
				$vhosts_file.= '  ServerName ' . $domain['domain'] . "\n";
				$server_alias = '';
				$alias_domains = $this->db->query('SELECT `domain`, `iswildcarddomain` FROM `' . TABLE_PANEL_DOMAINS . '` WHERE `aliasdomain`=\'' . $domain['id'] . '\'');

				while(($alias_domain = $this->db->fetch_array($alias_domains)) !== false)
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
					   && $this->settings['system']['deactivateddocroot'] != '')
					{
						$vhosts_file.= '  # Using docroot for deactivated users...' . "\n";
						$vhosts_file.= '  DocumentRoot "' . $this->settings['system']['deactivateddocroot'] . "\"\n";
					}
					else
					{
						$vhosts_file.= '  DocumentRoot "' . $domain['documentroot'] . "\"\n";
					}

					if($domain['phpenabled'] == '1')
					{
						// This vHost has PHP enabled...

						if($this->settings['system']['mod_fcgid'] == 1)
						{
							// ...and we are using mod_fcgid
							// TODO: Put this in the join on line 212

							$sql = "SELECT * FROM `ftp_users` WHERE `customerid` = " . $domain['customerid'];
							$result_ftp = $this->db->query($sql);

							if($this->db->num_rows($result_ftp) > 0)
							{
								$vhosts_file.= '  ScriptAlias /php/ ' . $this->settings['system']['mod_fcgid_configdir'] . '/' . $domain['loginname'] . '/' . $domain['domain'] . '/' . "\n";
								$vhosts_file.= '  SuexecUserGroup "' . $domain['loginname'] . '" "' . $domain['loginname'] . '"' . "\n";
								createFcgiConfig($domain, $this->settings);
							}
						}
						else
						{
							// ...and we are using the regular mod_php

							if($domain['openbasedir'] == '1')
							{
								if($this->settings['system']['phpappendopenbasedir'] != '')
								{
									$_phpappendopenbasedir = ':' . $this->settings['system']['phpappendopenbasedir'];
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

							if($domain['safemode'] == '0')
							{
								$vhosts_file.= '  php_admin_flag safe_mode Off ' . "\n";
							}
							else
							{
								$vhosts_file.= '  php_admin_flag safe_mode On ' . "\n";
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

					if($this->settings['system']['mod_log_sql'] == 1)
					{
						// We are using mod_log_sql (http://www.outoforder.cc/projects/apache/mod_log_sql/)
						// TODO: See how we are able emulate the error_log

						$vhosts_file.= '  LogSQLTransferLogTable access_log' . "\n";
					}
					else
					{
						// The normal access/error - logging is enabled

						$vhosts_file.= '  ErrorLog "' . $this->settings['system']['logfiles_directory'] . $domain['loginname'] . $speciallogfile . '-error.log' . "\"\n";
						$vhosts_file.= '  CustomLog "' . $this->settings['system']['logfiles_directory'] . $domain['loginname'] . $speciallogfile . '-access.log" combined' . "\n";
					}
				}

				if($domain['specialsettings'] != '')
				{
					$vhosts_file.= $domain['specialsettings'] . "\n";
				}

				$vhosts_file.= '</VirtualHost>' . "\n";

				if(isConfigDir($this->settings['system']['apacheconf_vhost']))
				{
					$vhosts_filename = makeCorrectFile($this->settings['system']['apacheconf_vhost'] . '/' . ($domain['iswildcarddomain'] == '1' ? '30_syscp_wildcard_vhost' : '20_syscp_normal_vhost') . '_' . $domain['domain'] . '.conf');
					$known_vhost_filenames[] = basename($vhosts_filename);

					// Apply header

					$vhosts_file = '# ' . $vhosts_filename . "\n" . '# Created ' . date('d.m.Y H:i') . "\n" . '# Do NOT manually edit this file, all changes will be deleted after the next domain change at the panel.' . "\n" . "\n" . $vhosts_file;

					// Save partial file

					$vhosts_file_handler = fopen($vhosts_filename, 'w');
					fwrite($vhosts_file_handler, $vhosts_file);
					fclose($vhosts_file_handler);

					// Reset vhosts_file

					$vhosts_file = '';
				}
				else
				{
					$vhosts_file.= "\n";
				}
			}
			else
			{
				$vhosts_file.= '# Customer deactivated and a docroot for deactivated users hasn\'t been set.' . "\n";
			}
		}

		if(!isConfigDir($this->settings['system']['apacheconf_vhost']))
		{
			if(file_exists($this->settings['system']['apacheconf_diroptions']))
			{
				$vhosts_file.= 'Include ' . $this->settings['system']['apacheconf_diroptions'] . "\n\n";
			}

			$vhosts_filename = $this->settings['system']['apacheconf_vhost'];

			// Apply header

			$vhosts_file = '# ' . $vhosts_filename . "\n" . '# Created ' . date('d.m.Y H:i') . "\n" . '# Do NOT manually edit this file, all changes will be deleted after the next domain change at the panel.' . "\n" . "\n" . $vhosts_file;

			// Save big file

			$vhosts_file_handler = fopen($vhosts_filename, 'w');
			fwrite($vhosts_file_handler, $vhosts_file);
			fclose($vhosts_file_handler);
		}
		else
		{
			$vhosts_dir = makeCorrectDir($this->settings['system']['apacheconf_vhost']);

			if(file_exists($vhosts_dir)
			   && is_dir($vhosts_dir))
			{
				$vhost_file_dirhandle = opendir($vhosts_dir);

				while(false !== ($vhost_filename = readdir($vhost_file_dirhandle)))
				{
					if($vhost_filename != '.'
					   && $vhost_filename != '..'
					   && !in_array($vhost_filename, $known_vhost_filenames)
					   && preg_match('/^(10|20|30)_syscp_(ipandport|normal_vhost|wildcard_vhost)_(.+)\.conf$/', $vhost_filename)
					   && file_exists(makeCorrectFile($vhosts_dir . '/' . $vhost_filename)))
					{
						fwrite($this->debugHandler, '  cron_tasks: Task1 - unlinking ' . $vhost_filename . "\n");
						unlink(makeCorrectFile($vhosts_dir . '/' . $vhost_filename));
					}
				}
			}
		}

		$this->logger->logAction(CRON_ACTION, LOG_INFO, 'reloading apache');
		safe_exec($this->settings['system']['apachereload_command']);
		fwrite($this->debugHandler, '   cron_tasks: Task1 - Apache reloaded' . "\n");
	}
	
	function createFileDirOptions()
	{
		fwrite($this->debugHandler, '  cron_tasks: Task3 started - create/change/del htaccess/htpasswd' . "\n");
		$this->logger->logAction(CRON_ACTION, LOG_INFO, 'Task3 started - create/change/del htaccess/htpasswd');
		$diroptions_file = '';

		if(isConfigDir($this->settings['system']['apacheconf_diroptions'])
		   && !file_exists($this->settings['system']['apacheconf_diroptions']))
		{
			$this->logger->logAction(CRON_ACTION, LOG_NOTICE, 'mkdir ' . escapeshellarg(makeCorrectDir($this->settings['system']['apacheconf_diroptions'])));
			safe_exec('mkdir ' . escapeshellarg(makeCorrectDir($this->settings['system']['apacheconf_diroptions'])));
		}

		if(!file_exists($this->settings['system']['apacheconf_htpasswddir']))
		{
			$umask = umask();
			umask(0000);
			mkdir($this->settings['system']['apacheconf_htpasswddir'], 0751);
			umask($umask);
		}
		elseif(!is_dir($this->settings['system']['apacheconf_htpasswddir']))
		{
			fwrite($this->debugHandler, '  cron_tasks: WARNING!!! ' . $this->settings['system']['apacheconf_htpasswddir'] . ' is not a directory. htpasswd directory protection is disabled!!!' . "\n");
			echo 'WARNING!!! ' . $this->settings['system']['apacheconf_htpasswddir'] . ' is not a directory. htpasswd directory protection is disabled!!!';
			$this->logger->logAction(CRON_ACTION, LOG_WARNING, 'WARNING!!! ' . $this->settings['system']['apacheconf_htpasswddir'] . ' is not a directory. htpasswd directory protection is disabled!!!');
		}

		$result = $this->db->query('SELECT `htac`.*, `c`.`guid`, `c`.`documentroot` AS `customerroot` FROM `' . TABLE_PANEL_HTACCESS . '` `htac` LEFT JOIN `' . TABLE_PANEL_CUSTOMERS . '` `c` USING (`customerid`) ORDER BY `htac`.`path`');
		$diroptions = array();

		while($row_diroptions = $this->db->fetch_array($result))
		{
			if($row_diroptions['customerid'] != 0
			   && isset($row_diroptions['customerroot'])
			   && $row_diroptions['customerroot'] != '')
			{
				$diroptions[$row_diroptions['path']] = $row_diroptions;
				$diroptions[$row_diroptions['path']]['htpasswds'] = array();
			}
		}

		$result = $this->db->query('SELECT `htpw`.*, `c`.`guid`, `c`.`documentroot` AS `customerroot` FROM `' . TABLE_PANEL_HTPASSWDS . '` `htpw` LEFT JOIN `' . TABLE_PANEL_CUSTOMERS . '` `c` USING (`customerid`) ORDER BY `htpw`.`path`, `htpw`.`username`');

		while($row_htpasswds = $this->db->fetch_array($result))
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

		$known_diroptions_files = array();
		$known_htpasswd_files = array();
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
					fwrite($this->debugHandler, '  cron_tasks: Task3 - Setting Options +Indexes' . "\n");
				}

				if(isset($row_diroptions['options_indexes'])
				   && $row_diroptions['options_indexes'] == '0')
				{
					$diroptions_file.= '  Options -Indexes' . "\n";
					fwrite($this->debugHandler, '  cron_tasks: Task3 - Setting Options -Indexes' . "\n");
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
							$htpasswd_filename = makeCorrectFile($this->settings['system']['apacheconf_htpasswddir'] . '/' . $row_diroptions['customerid'] . '-' . $row_htpasswd['id'] . '-' . md5($row_diroptions['path']) . '.htpasswd');
							$known_htpasswd_files[] = basename($htpasswd_filename);
						}

						$htpasswd_file.= $row_htpasswd['username'] . ':' . $row_htpasswd['password'] . "\n";
					}

					if(file_exists($this->settings['system']['apacheconf_htpasswddir'])
					   && is_dir($this->settings['system']['apacheconf_htpasswddir']))
					{
						fwrite($this->debugHandler, '  cron_tasks: Task3 - Setting Password' . "\n");
						$diroptions_file.= '  AuthType Basic' . "\n";
						$diroptions_file.= '  AuthName "Restricted Area"' . "\n";
						$diroptions_file.= '  AuthUserFile ' . $htpasswd_filename . "\n";
						$diroptions_file.= '  require valid-user' . "\n";
						$htpasswd_file_handler = fopen($htpasswd_filename, 'w');
						fwrite($htpasswd_file_handler, $htpasswd_file);
						fclose($htpasswd_file_handler);
					}
				}

				$diroptions_file.= '</Directory>' . "\n";

				if(isConfigDir($this->settings['system']['apacheconf_diroptions']))
				{
					$diroptions_filename = makeCorrectFile($this->settings['system']['apacheconf_diroptions'] . '/40_syscp_diroption_' . md5($row_diroptions['path']) . '.conf');
					$known_diroptions_files[] = basename($diroptions_filename);

					// Apply header

					$diroptions_file = '# ' . $diroptions_filename . "\n" . '# Created ' . date('d.m.Y H:i') . "\n" . '# Do NOT manually edit this file, all changes will be deleted after the next dir options change at the panel.' . "\n" . "\n" . $diroptions_file;

					// Save partial file

					$diroptions_file_handler = fopen($diroptions_filename, 'w');
					fwrite($diroptions_file_handler, $diroptions_file);
					fclose($diroptions_file_handler);

					// Reset diroptions_file

					$diroptions_file = '';
				}
				else
				{
					$diroptions_file.= "\n";
				}
			}
		}

		if(!isConfigDir($this->settings['system']['apacheconf_diroptions']))
		{
			$diroptions_filename = $this->settings['system']['apacheconf_diroptions'];

			// Apply header

			$diroptions_file = '# ' . $diroptions_filename . "\n" . '# Created ' . date('d.m.Y H:i') . "\n" . '# Do NOT manually edit this file, all changes will be deleted after the next dir options change at the panel.' . "\n" . "\n" . $diroptions_file;

			// Save big file

			$diroptions_file_handler = fopen($diroptions_filename, 'w');
			fwrite($diroptions_file_handler, $diroptions_file);
			fclose($diroptions_file_handler);
		}
		else
		{
			$diroptions_dir = makeCorrectDir($this->settings['system']['apacheconf_diroptions']);

			if(file_exists($diroptions_dir)
			   && is_dir($diroptions_dir))
			{
				$diroptions_file_dirhandle = opendir($diroptions_dir);

				while(false !== ($diroptions_filename = readdir($diroptions_file_dirhandle)))
				{
					if($diroptions_filename != '.'
					   && $diroptions_filename != '..'
					   && !in_array($diroptions_filename, $known_diroptions_files)
					   && preg_match('/^40_syscp_diroption_(.+)\.conf$/', $diroptions_filename)
					   && file_exists(makeCorrectFile($this->settings['system']['apacheconf_diroptions'] . '/' . $diroptions_filename)))
					{
						$this->logger->logAction(CRON_ACTION, LOG_WARNING, 'Deleting ' . makeCorrectFile($this->settings['system']['apacheconf_diroptions'] . '/' . $diroptions_filename));
						unlink(makeCorrectFile($this->settings['system']['apacheconf_diroptions'] . '/' . $diroptions_filename));
					}
				}
			}
		}

		$this->logger->logAction(CRON_ACTION, LOG_INFO, 'Reloading apache');
		safe_exec($this->settings['system']['apachereload_command']);
		$htpasswd_dir = makeCorrectDir($this->settings['system']['apacheconf_htpasswddir']);

		if(file_exists($htpasswd_dir)
		   && is_dir($htpasswd_dir))
		{
			$htpasswd_file_dirhandle = opendir($htpasswd_dir);

			while(false !== ($htpasswd_filename = readdir($htpasswd_file_dirhandle)))
			{
				if($htpasswd_filename != '.'
				   && $htpasswd_filename != '..'
				   && !in_array($htpasswd_filename, $known_htpasswd_files)
				   && file_exists(makeCorrectFile($this->settings['system']['apacheconf_htpasswddir'] . '/' . $htpasswd_filename)))
				{
					$this->logger->logAction(CRON_ACTION, LOG_WARNING, 'Deleting ' . makeCorrectFile($this->settings['system']['apacheconf_htpasswddir'] . '/' . $htpasswd_filename));
					unlink(makeCorrectFile($this->settings['system']['apacheconf_htpasswddir'] . '/' . $htpasswd_filename));
				}
			}
		}
	}
}

?>
<?php

/**
 * Cron Tasks - http
 *
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version. This program is distributed in the
 * hope that it will be useful, but WITHOUT ANY WARRANTY; without even the
 * implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU General Public License for more details.
 *
 * @copyright  (c) the authors
 * @author     Michael Kaufmann <mkaufmann@nutime.de>
 * @license    http://www.gnu.org/licenses/gpl.txt
 * @package    Functions
 * @version    CVS: $Id:$
 * @link       http://www.nutime.de/
 */

class cron_httpd
{
	/**
	 * Database handler
	 * @var db
	 */

	private $db = false;

	/**
	 * Logging handler
	 * @var cronlog
	 */

	private $cronlog = false;

	/**
	 * Settings array
	 * @var settings
	 */

	private $settings = array();

	/**
	 * cron_httpd-Object-Array
	 * @var httpds
	 */

	static private $httpds = null;

	/* other vars */

	private $is_lighttpd = false;
	private $vhost_file = null;
	private $diroptions_file = null;
	private $known_vhost_filenames = null;
	private $known_diroptions_files = null;
	private $debugHandler = null;

	/**
	 * Class constructor.
	 *
	 * @param array userinfo
	 * @param array settings
	 */

	protected function __construct($db, $settings, $cronlog, $debugHandler)
	{
		$this->db = $db;
		$this->settings = $settings;
		$this->cronlog = $cronlog;
		$this->debugHandler = $debugHandler;

		if($this->settings['system']['apacheversion'] == 'lighttpd')
		{
			$this->is_lighttpd = true;
		}
	}

	/**
	 * Singleton ftw ;-)
	 *
	 */

	static public function getInstanceOf($_db, $_settings, $_cronlog, $_debugHandler)
	{
		if(!isset(self::$httpds[date("d", time())]))
		{
			self::$httpds[date("d", time())] = new cron_httpd($_db, $_settings, $_cronlog, $_debugHandler);
		}

		return self::$httpds[date("d", time())];
	}

	public function createSyscpVhost()
	{
		$this->vhosts_file = '';
		$this->known_vhost_filenames = array();
		$result_ipsandports = $this->db->query("SELECT `ip`, `port`, `listen_statement`, `namevirtualhost_statement`, `vhostcontainer`, `vhostcontainer_servername_statement`, `specialsettings` FROM `" . TABLE_PANEL_IPSANDPORTS . "` ORDER BY `ip` ASC, `port` ASC");

		while($row_ipsandports = $this->db->fetch_array($result_ipsandports))
		{
			if(!$this->is_lighttpd)
			{
				if($row_ipsandports['listen_statement'] == '1')
				{
					if(filter_var($row_ipsandports['ip'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4))
					{
						$this->vhosts_file.= 'Listen ' . $row_ipsandports['ip'] . ':' . $row_ipsandports['port'] . "\n";
						$this->cronlog->logAction(CRON_ACTION, LOG_NOTICE, 'inserted IPv4 listen-statement');
					}
					elseif(filter_var($row_ipsandports['ip'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV6))
					{
						$this->vhosts_file.= 'Listen [' . $row_ipsandports['ip'] . ']:' . $row_ipsandports['port'] . "\n";
						$this->cronlog->logAction(CRON_ACTION, LOG_NOTICE, 'inserted IPv6 listen-statement');
					}
				}

				if($row_ipsandports['namevirtualhost_statement'] == '1')
				{
					if(filter_var($row_ipsandports['ip'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4))
					{
						$this->vhosts_file.= 'NameVirtualHost ' . $row_ipsandports['ip'] . ':' . $row_ipsandports['port'] . "\n";
						$this->cronlog->logAction(CRON_ACTION, LOG_NOTICE, 'inserted IPv4 namevirtualhost-statement');
					}
					elseif(filter_var($row_ipsandports['ip'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV6))
					{
						$this->vhosts_file.= 'NameVirtualHost [' . $row_ipsandports['ip'] . ']:' . $row_ipsandports['port'] . "\n";
						$this->cronlog->logAction(CRON_ACTION, LOG_NOTICE, 'inserted IPv6 namevirtualhost-statement');
					}
				}

				if($row_ipsandports['vhostcontainer'] == '1')
				{
					if(filter_var($row_ipsandports['ip'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4))
					{
						$this->vhosts_file.= '<VirtualHost ' . $row_ipsandports['ip'] . ':' . $row_ipsandports['port'] . '>' . "\n";
						$this->cronlog->logAction(CRON_ACTION, LOG_NOTICE, 'inserted IPv4 vhostcontainer');
					}
					elseif(filter_var($row_ipsandports['ip'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV6))
					{
						$this->vhosts_file.= '<VirtualHost [' . $row_ipsandports['ip'] . ']:' . $row_ipsandports['port'] . '>' . "\n";
						$this->cronlog->logAction(CRON_ACTION, LOG_NOTICE, 'inserted IPv6 vhostcontainer');
					}

					if($row_ipsandports['vhostcontainer_servername_statement'] == '1')
					{
						$this->vhosts_file.= ' ServerName ' . $this->settings['system']['hostname'] . "\n";
					}

					if($row_ipsandports['specialsettings'] != '')
					{
						$this->vhosts_file.= $row_ipsandports['specialsettings'] . "\n";
					}

					$this->vhosts_file.= '</VirtualHost>' . "\n";
				}
			}

			/* !$this->is_lighttpd */

			if(isConfigDir($this->settings['system']['apacheconf_vhost']))
			{
				$vhosts_filename = makeCorrectFile($this->settings['system']['apacheconf_vhost'] . '/10_syscp_ipandport_' . $row_ipsandports['ip'] . '.' . $row_ipsandports['port'] . '.conf');
				$this->known_vhost_filenames[] = basename($vhosts_filename);

				// Apply header

				$this->vhosts_file = '# ' . $vhosts_filename . "\n" . '# Created ' . date('d.m.Y H:i') . "\n" . '# Do NOT manually edit this file, all changes will be deleted after the next domain change at the panel.' . "\n" . "\n" . $this->vhosts_file;

				// Save partial file

				$vhosts_file_handler = fopen($vhosts_filename, 'w');
				fwrite($vhosts_file_handler, $this->vhosts_file);
				fclose($vhosts_file_handler);

				// Reset vhosts_file

				$this->vhosts_file = '';
			}
			else
			{
				$this->vhosts_file.= "\n";
			}
		}
	}

	public function createCustomerVhosts()
	{
		$result_domains = $this->db->query("SELECT `d`.`id`, `d`.`domain`, `d`.`customerid`, `d`.`documentroot`, CONCAT(`ip`.`ip`,':',`ip`.`port`) AS `ipandport`, `d`.`parentdomainid`, `d`.`isemaildomain`, `d`.`iswildcarddomain`, `d`.`wwwserveralias`, `d`.`openbasedir`, `d`.`openbasedir_path`, `d`.`safemode`, `d`.`speciallogfile`, `d`.`specialsettings`, `pd`.`domain` AS `parentdomain`, `c`.`loginname`, `c`.`guid`, `c`.`email`, `c`.`documentroot` AS `customerroot`, `c`.`deactivated`, `c`.`phpenabled` AS `phpenabled` FROM `" . TABLE_PANEL_DOMAINS . "` `d` LEFT JOIN `" . TABLE_PANEL_CUSTOMERS . "` `c` USING(`customerid`) LEFT JOIN `" . TABLE_PANEL_DOMAINS . "` `pd` ON (`pd`.`id` = `d`.`parentdomainid`) LEFT JOIN `" . TABLE_PANEL_IPSANDPORTS . "` `ip` ON (`d`.`ipandport` = `ip`.`id`) WHERE `d`.`aliasdomain` IS NULL ORDER BY `d`.`iswildcarddomain`, `d`.`domain` ASC");

		while($domain = $this->db->fetch_array($result_domains))
		{
			if(isset($domain['domain'])
			   && $domain['domain'] != '')
			{
				fwrite($this->debugHandler, '  cron_tasks: Task1 - Writing Domain ' . $domain['id'] . '::' . $domain['domain'] . "\n");
				$this->cronlog->logAction(CRON_ACTION, LOG_NOTICE, 'cron_tasks: Task1 - Writing Domain ' . $domain['id'] . '::' . $domain['domain']);
				$this->vhosts_file.= '# Domain ID: ' . $domain['id'] . ' - CustomerID: ' . $domain['customerid'] . ' - CustomerLogin: ' . $domain['loginname'] . "\n";
				$this->createCustomerVhost($domain);
			}
		}
	}

	private function createCustomerVhost($domain = null)
	{
		if($domain['deactivated'] != '1'
		   || $this->settings['system']['deactivateddocroot'] != '')
		{
			if(!$this->is_lighttpd)
			{
				$this->vhosts_file.= '<VirtualHost ' . $domain['ipandport'] . '>' . "\n";
				$this->vhosts_file.= '  ServerName ' . $domain['domain'] . "\n";
			}
			else
			{
				$this->vhosts_file.= '$HTTP["host"] =~ "^(www\.|)' . $domain['domain'] . '$" { ' . "\n";
			}

			if(!$this->is_lighttpd)
			{
				$server_alias = '';
				$alias_domains = $this->db->query('SELECT `domain`, `iswildcarddomain`, `wwwserveralias` FROM `' . TABLE_PANEL_DOMAINS . '` WHERE `aliasdomain`=\'' . $domain['id'] . '\'');

				while(($alias_domain = $this->db->fetch_array($alias_domains)) !== false)
				{
					$server_alias.= ' ' . $alias_domain['domain'] . ' ';

					if($alias_domain['iswildcarddomain'] == '1')
					{
						$server_alias.= '*.' . $domain['domain'];
					}
					else
					{
						if($alias_domain['wwwserveralias'] == '1')
						{
							$server_alias.= 'www.' . $alias_domain['domain'];
						}
						else
						{
							$server_alias.= '';
						}
					}
				}

				if($domain['iswildcarddomain'] == '1')
				{
					$alias = '*.' . $domain['domain'];
				}
				else
				{
					if($domain['wwwserveralias'] == '1')
					{
						$alias = 'www.' . $domain['domain'];
					}
					else
					{
						$alias = '';
					}
				}

				$this->vhosts_file.= '  ServerAlias ' . $alias . $server_alias . "\n";
				$this->vhosts_file.= '  ServerAdmin ' . $domain['email'] . "\n";
			}

			if(preg_match('/^https?\:\/\//', $domain['documentroot']))
			{
				if(!$this->is_lighttpd)
				{
					$this->vhosts_file.= '  Redirect / ' . $domain['documentroot'] . "\n";
				}
				else
				{
					$this->vhosts_file.= '  url.redirect = (' . "\n";
					$this->vhosts_file.= '    "" 		=> "' . $domain['documentroot'] . '",' . "\n";
					$this->vhosts_file.= '  )' . "\n";
				}
			}
			else
			{
				$domain['customerroot'] = makeCorrectDir($domain['customerroot']);
				$domain['documentroot'] = makeCorrectDir($domain['documentroot']);

				if($domain['deactivated'] == '1'
				   && $this->settings['system']['deactivateddocroot'] != '')
				{
					if(!$this->is_lighttpd)
					{
						$this->vhosts_file.= '  # Using docroot for deactivated users...' . "\n";
						$this->vhosts_file.= '  DocumentRoot "' . $this->settings['system']['deactivateddocroot'] . "\"\n";
					}
					else
					{
						$this->vhosts_file.= '  # Using docroot for deactivated users...' . "\n";
						$this->vhosts_file.= '  server.document-root = "' . $this->settings['system']['deactivateddocroot'] . '"' . "\n";
					}
				}
				else
				{
					if(!$this->is_lighttpd)
					{
						$this->vhosts_file.= '  DocumentRoot "' . $domain['documentroot'] . "\"\n";
					}
					else
					{
						$this->vhosts_file.= '  server.document-root = "' . $domain['documentroot'] . '"' . "\n";
						$this->vhosts_file.= '  server.dir-listing   = "disable"' . "\n";
					}
				}

				if($domain['phpenabled'] == '1')
				{
					// This vHost has PHP enabled...

					if(!$this->is_lighttpd)
					{
						if($this->settings['system']['mod_fcgid'] == 1)
						{
							// ...and we are using mod_fcgid
							// TODO: Put this in the join on line 212

							$sql = "SELECT * FROM `ftp_users` WHERE `customerid` = " . $domain['customerid'];
							$result_ftp = $this->db->query($sql);

							if($this->db->num_rows($result_ftp) > 0)
							{
								$this->vhosts_file.= '<Directory "' . $domain['documentroot'] . '">' . "\n";
								$this->vhosts_file.= '  FCGIWrapper	' . $this->settings['system']['mod_fcgid_configdir'] . '/' . $domain['loginname'] . '/' . $domain['domain'] . '/' . 'php-fcgi-starter .php' . "\n";
								$this->vhosts_file.= '  AddHandler		fcgid-script   .php' . "\n";
								$this->vhosts_file.= '  Options		+FollowSymLinks -MultiViews +ExecCGI' . "\n";
								$this->vhosts_file.= '</Directory>' . "\n";
								$this->vhosts_file.= '  SuexecUserGroup "' . $domain['loginname'] . '" "' . $domain['loginname'] . '"' . "\n";
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
									$this->vhosts_file.= '  php_admin_value open_basedir "' . $domain['customerroot'] . $_phpappendopenbasedir . "\"\n";
								}
								else
								{
									$this->vhosts_file.= '  php_admin_value open_basedir "' . $domain['documentroot'] . $_phpappendopenbasedir . "\"\n";
								}
							}

							if($domain['safemode'] == '0')
							{
								$this->vhosts_file.= '  php_admin_flag safe_mode Off ' . "\n";
							}
							else
							{
								$this->vhosts_file.= '  php_admin_flag safe_mode On ' . "\n";
							}
						}
					}
					else
					{
						$this->vhosts_file.= "  fastcgi.server = (
\".php\" => (
	\"localhost\" => (
		\"socket\" => \"" . $this->settings['system']['mod_fcgid_tmpdir'] . "lighttpd-fcgi-sock-" . $domain['loginname'] . "\",
		\"broken-scriptfilename\" => \"enable\",
		\"bin-path\" => \"/usr/bin/php-cgi\",
		\"min-procs\" => 1,
		\"max-procs\" => 1,
		\"max-load-per-proc\" => 4,
		\"idle-timeout\" => 60,
		\"bin-environment\" => (
			\"UID\" => \"" . $domain['loginname'] . "\",
			\"GID\" => \"" . $domain['loginname'] . "\",
			\"PHP_FCGI_CHILDREN\" => \"0\",
			\"PHP_FCGI_MAX_REQUESTS\" => \"2000\"
		),
		\"bin-copy-environment\" => ( \"\" )
	)
)
 )\n";
					}
				}
				else
				{
					$this->vhosts_file.= '  php_flag engine off' . "\n";
				}

				mkDirWithCorrectOwnership($domain['customerroot'], $domain['documentroot'], $domain['guid'], $domain['guid']);

				if($domain['speciallogfile'] == '1')
				{
					if($domain['parentdomainid'] == '0')
					{
						$speciallogfile = '-' . $domain['domain'];

						if(!$this->is_lighttpd)
						{
							if($this->settings['system']['webalizer_enabled'] == '1')
							{
								$this->vhosts_file.= '  Alias /webalizer "' . $domain['customerroot'] . '/webalizer/' . $domain['domain'] . "\"\n";
							}
							elseif($this->settings['system']['awstats_enabled'] == '1')
							{
								$this->vhosts_file.= createAWStatsVhost($domain['domain']);
							}
						}
						else
						{
							if($this->settings['system']['webalizer_enabled'] == '1')
							{
								$this->vhosts_file.= '  alias.url = (' . "\n";
								$this->vhosts_file.= '  	"/webalizer/" => "' . $domain['customerroot'] . '/webalizer/' . $domain['domain'] . '",' . "\n";
								$this->vhosts_file.= '  )' . "\n";
							}
							elseif($this->settings['system']['awstats_enabled'] == '1')
							{
								$this->vhosts_file.= '  alias.url = (' . "\n";
								$this->vhosts_file.= '  	"/awstats/" => "' . $domain['customerroot'] . '/awstats/' . "\n";
								$this->vhosts_file.= '  )' . "\n";
							}
						}
					}
					else
					{
						$speciallogfile = '-' . $domain['parentdomain'];

						if(!$this->is_lighttpd)
						{
							if($this->settings['system']['webalizer_enabled'] == '1')
							{
								$this->vhosts_file.= '  Alias /webalizer "' . $domain['customerroot'] . '/webalizer/' . $domain['parentdomain'] . "\"\n";
							}
							elseif($this->settings['system']['awstats_enabled'] == '1')
							{
								$this->vhosts_file.= createAWStatsVhost($domain['parentdomain']);
							}
						}
						else
						{
							if($this->settings['system']['webalizer_enabled'] == '1')
							{
								$this->vhosts_file.= '  alias.url = (' . "\n";
								$this->vhosts_file.= '  	"/webalizer/" => "' . $domain['customerroot'] . '/webalizer/' . $domain['parentdomain'] . '",' . "\n";
								$this->vhosts_file.= '  )' . "\n";
							}
							elseif($this->settings['system']['awstats_enabled'] == '1')
							{
								$this->vhosts_file.= '  # awstats for lighttpd is not yet coded' . "\n";
							}
						}
					}
				}
				else
				{
					$speciallogfile = '';

					if($domain['customerroot'] != $domain['documentroot'])
					{
						if(!$this->is_lighttpd)
						{
							if($this->settings['system']['webalizer_enabled'] == '1')
							{
								$this->vhosts_file.= '  Alias /webalizer "' . $domain['customerroot'] . '/webalizer"' . "\n";
							}
							elseif($this->settings['system']['awstats_enabled'] == '1')
							{
								$this->vhosts_file.= createAWStatsVhost($domain['domain']);
							}
						}
						else
						{
							if($this->settings['system']['webalizer_enabled'] == '1')
							{
								$this->vhosts_file.= '  alias.url = (' . "\n";
								$this->vhosts_file.= '  	"/webalizer/" => "' . $domain['customerroot'] . '/webalizer/",' . "\n";
								$this->vhosts_file.= '  )' . "\n";
							}
							elseif($this->settings['system']['awstats_enabled'] == '1')
							{
								$this->vhosts_file.= '  # awstats for lighttpd is not yet coded' . "\n";
							}
						}
					}
				}

				if($this->settings['system']['mod_log_sql'] == 1)
				{
					// We are using mod_log_sql (http://www.outoforder.cc/projects/apache/mod_log_sql/)
					// TODO: See how we are able emulate the error_log

					if(!$this->is_lighttpd)
					{
						$this->vhosts_file.= '  LogSQLTransferLogTable access_log' . "\n";
					}
				}
				else
				{
					// The normal access/error - logging is enabled

					if(!$this->is_lighttpd)
					{
						if($this->settings['system']['awstats_enabled'] == '1')
						{
							// After inserting the AWStats information, be sure to build the awstats conf file as well

							createAWStatsConf($this->settings['system']['logfiles_directory'] . $domain['loginname'] . $speciallogfile . '-access.log', $domain['domain'], $alias . $server_alias);
						}

						$this->vhosts_file.= '  ErrorLog "' . $this->settings['system']['logfiles_directory'] . $domain['loginname'] . $speciallogfile . '-error.log' . "\"\n";
						$this->vhosts_file.= '  CustomLog "' . $this->settings['system']['logfiles_directory'] . $domain['loginname'] . $speciallogfile . '-access.log" combined' . "\n";
					}
					else
					{
						// server.errorlog might only be used ONCE in the lighty config!
						// $vhosts_file.= '  server.errorlog = "' . $this->settings['system']['logfiles_directory'] . $domain['loginname'] . '-error.log"' . "\n";

						$this->vhosts_file.= '  accesslog.filename = "' . $this->settings['system']['logfiles_directory'] . $domain['loginname'] . '-access.log"' . "\n";
					}
				}
			}

			if($domain['specialsettings'] != '')
			{
				$this->vhosts_file.= $domain['specialsettings'] . "\n";
			}

			if(!$this->is_lighttpd)
			{
				$this->vhosts_file.= '</VirtualHost>' . "\n";
			}
			else
			{
				$this->vhosts_file.= '}' . "\n";
			}

			if(isConfigDir($this->settings['system']['apacheconf_vhost']))
			{
				$vhosts_filename = makeCorrectFile($this->settings['system']['apacheconf_vhost'] . '/' . ($domain['iswildcarddomain'] == '1' ? '30_syscp_wildcard_vhost' : '20_syscp_normal_vhost') . '_' . $domain['domain'] . '.conf');
				$this->known_vhost_filenames[] = basename($vhosts_filename);

				// Apply header

				$this->vhosts_file = '# ' . $vhosts_filename . "\n" . '# Created ' . date('d.m.Y H:i') . "\n" . '# Do NOT manually edit this file, all changes will be deleted after the next domain change at the panel.' . "\n" . "\n" . $this->vhosts_file;

				// Save partial file

				$vhosts_file_handler = fopen($vhosts_filename, 'w');
				fwrite($vhosts_file_handler, $this->vhosts_file);
				fclose($vhosts_file_handler);

				// Reset vhosts_file

				$this->vhosts_file = '';
			}
			else
			{
				$this->vhosts_file.= "\n";
			}
		}
		else
		{
			$this->vhosts_file.= '# Customer deactivated and a docroot for deactivated users hasn\'t been set.' . "\n";
		}
	}

	public function safeVhostFile()
	{
		if(!isConfigDir($this->settings['system']['apacheconf_vhost']))
		{
			if(file_exists($this->settings['system']['apacheconf_diroptions']))
			{
				if(!$this->is_lighttpd)
				{
					$this->vhosts_file.= 'Include ' . $this->settings['system']['apacheconf_diroptions'] . "\n\n";
				}
				else
				{
					$this->vhosts_file.= 'include "' . $this->settings['system']['apacheconf_diroptions'] . '"' . "\n\n";
				}
			}

			$vhosts_filename = $this->settings['system']['apacheconf_vhost'];

			// Apply header

			$this->vhosts_file = '# ' . $vhosts_filename . "\n" . '# Created ' . date('d.m.Y H:i') . "\n" . '# Do NOT manually edit this file, all changes will be deleted after the next domain change at the panel.' . "\n" . "\n" . $this->vhosts_file;

			// Save big file

			$vhosts_file_handler = fopen($vhosts_filename, 'w');
			fwrite($vhosts_file_handler, $this->vhosts_file);
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
					   && !in_array($vhost_filename, $this->known_vhost_filenames)
					   && preg_match('/^(10|20|30)_syscp_(ipandport|normal_vhost|wildcard_vhost)_(.+)\.conf$/', $vhost_filename)
					   && file_exists(makeCorrectFile($vhosts_dir . '/' . $vhost_filename)))
					{
						fwrite($this->debugHandler, '  cron_tasks: Task1 - unlinking ' . $vhost_filename . "\n");
						$this->cronlog->logAction(CRON_ACTION, LOG_NOTICE, 'cron_tasks: Task1 - unlinking ' . $vhost_filename);
						unlink(makeCorrectFile($vhosts_dir . '/' . $vhost_filename));
					}
				}
			}
		}
	}

	public function createDiroptions()
	{
		$this->diroptions_file = '';

		if(isConfigDir($this->settings['system']['apacheconf_diroptions'])
		   && !file_exists($this->settings['system']['apacheconf_diroptions']))
		{
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
			$this->cronlog->logAction(CRON_ACTION, LOG_WARNING, 'cron_tasks: WARNING!!! ' . $this->settings['system']['apacheconf_htpasswddir'] . ' is not a directory. htpasswd directory protection is disabled!!!');
			fwrite($this->debugHandler, '  cron_tasks: WARNING!!! ' . $this->settings['system']['apacheconf_htpasswddir'] . ' is not a directory. htpasswd directory protection is disabled!!!' . "\n");
			echo 'WARNING!!! ' . $this->settings['system']['apacheconf_htpasswddir'] . ' is not a directory. htpasswd directory protection is disabled!!!';
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

		$this->known_diroptions_files = array();
		$known_htpasswd_files = array();
		foreach($diroptions as $row_diroptions)
		{
			mkDirWithCorrectOwnership($row_diroptions['customerroot'], $row_diroptions['path'], $row_diroptions['guid'], $row_diroptions['guid']);

			if(is_dir($row_diroptions['path']))
			{
				if(!$this->is_lighttpd)
				{
					$this->diroptions_file.= '<Directory "' . $row_diroptions['path'] . '">' . "\n";
				}
				else
				{
					$this->diroptions_file.= '$PHYSICAL["path"] == "' . $row_diroptions['path'] . '" {' . "\n";
				}

				if(isset($row_diroptions['options_indexes'])
				   && $row_diroptions['options_indexes'] == '1')
				{
					if(!$this->is_lighttpd)
					{
						$this->diroptions_file.= '  Options +Indexes' . "\n";
					}
					else
					{
						$this->diroptions_file.= '  server.dir-listing   = "enable"' . "\n";
					}

					fwrite($this->debugHandler, '  cron_tasks: Task3 - Setting Options +Indexes' . "\n");
				}

				if(isset($row_diroptions['options_indexes'])
				   && $row_diroptions['options_indexes'] == '0')
				{
					if(!$this->is_lighttpd)
					{
						$this->diroptions_file.= '  Options -Indexes' . "\n";
					}
					else
					{
						$this->diroptions_file.= '  server.dir-listing   = "disable"' . "\n";
					}

					fwrite($this->debugHandler, '  cron_tasks: Task3 - Setting Options -Indexes' . "\n");
				}

				if(isset($row_diroptions['error404path'])
				   && $row_diroptions['error404path'] != '')
				{
					if(!$this->is_lighttpd)
					{
						$this->diroptions_file.= '  ErrorDocument 404 ' . $row_diroptions['error404path'] . "\n";
					}
					else
					{
						$this->diroptions_file.= '  server.error-handler-404 = "' . $row_diroptions['error404path'] . '"' . "\n";
					}
				}

				if(isset($row_diroptions['error403path'])
				   && $row_diroptions['error403path'] != '')
				{
					if(!$this->is_lighttpd)
					{
						$this->diroptions_file.= '  ErrorDocument 403 ' . $row_diroptions['error403path'] . "\n";
					}
					else
					{
						$this->diroptions_file.= '  server.error-handler-403 = "' . $row_diroptions['error403path'] . '"' . "\n";
					}
				}

				if(isset($row_diroptions['error500path'])
				   && $row_diroptions['error500path'] != '')
				{
					if(!$this->is_lighttpd)
					{
						$this->diroptions_file.= '  ErrorDocument 500 ' . $row_diroptions['error500path'] . "\n";
					}
					else
					{
						$this->diroptions_file.= '  server.error-handler-500 = "' . $row_diroptions['error500path'] . '"' . "\n";
					}
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

						if(!$this->is_lighttpd)
						{
							$this->diroptions_file.= '  AuthType Basic' . "\n";
							$this->diroptions_file.= '  AuthName "Restricted Area"' . "\n";
							$this->diroptions_file.= '  AuthUserFile ' . $htpasswd_filename . "\n";
							$this->diroptions_file.= '  require valid-user' . "\n";
						}
						else
						{
							$this->diroptions_file.= '  auth.backend = "htpasswd"' . "\n";
							$this->diroptions_file.= '  auth.backend.htpasswd.userfile = "' . $htpasswd_filename . '"' . "\n";
							$this->diroptions_file.= '  auth.require = ("' . $row_diroptions['path'] . '" => (' . "\n";
							$this->diroptions_file.= '       "method"  => "basic",' . "\n";
							$this->diroptions_file.= '       "realm"   => "Restricted Area",' . "\n";
							$this->diroptions_file.= '       "require" => "valid-user"' . "\n";
							$this->diroptions_file.= '  ))' . "\n";
						}

						$htpasswd_file_handler = fopen($htpasswd_filename, 'w');
						fwrite($htpasswd_file_handler, $htpasswd_file);
						fclose($htpasswd_file_handler);
					}
				}

				if(!$this->is_lighttpd)
				{
					$this->diroptions_file.= '</Directory>' . "\n";
				}
				else
				{
					$this->diroptions_file.= '}' . "\n";
				}

				if(isConfigDir($this->settings['system']['apacheconf_diroptions']))
				{
					$diroptions_filename = makeCorrectFile($this->settings['system']['apacheconf_diroptions'] . '/40_syscp_diroption_' . md5($row_diroptions['path']) . '.conf');
					$this->known_diroptions_files[] = basename($diroptions_filename);

					// Apply header

					$this->diroptions_file = '# ' . $diroptions_filename . "\n" . '# Created ' . date('d.m.Y H:i') . "\n" . '# Do NOT manually edit this file, all changes will be deleted after the next dir options change at the panel.' . "\n" . "\n" . $this->diroptions_file;

					// Save partial file

					$diroptions_file_handler = fopen($diroptions_filename, 'w');
					fwrite($diroptions_file_handler, $this->diroptions_file);
					fclose($diroptions_file_handler);

					// Reset diroptions_file

					$this->diroptions_file = '';
				}
				else
				{
					$this->diroptions_file.= "\n";
				}
			}
		}

		/* remove old htpasswd files */

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
					unlink(makeCorrectFile($this->settings['system']['apacheconf_htpasswddir'] . '/' . $htpasswd_filename));
				}
			}
		}
	}

	public function saveDiroptions()
	{
		if(!isConfigDir($this->settings['system']['apacheconf_diroptions']))
		{
			$diroptions_filename = $this->settings['system']['apacheconf_diroptions'];

			// Apply header

			$this->diroptions_file = '# ' . $diroptions_filename . "\n" . '# Created ' . date('d.m.Y H:i') . "\n" . '# Do NOT manually edit this file, all changes will be deleted after the next dir options change at the panel.' . "\n" . "\n" . $this->diroptions_file;

			if($this->is_lighttpd)
			{
				// Parse user's homes for .htaccess files and write'em into syscp-htaccess.conf
				/*
				$this->diroptions_file.= "\n\n" . 'include "syscp-htaccess.conf"' . "\n";
				*/
			}

			// Save big file

			$diroptions_file_handler = fopen($diroptions_filename, 'w');
			fwrite($diroptions_file_handler, $this->diroptions_file);
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
					   && !in_array($diroptions_filename, $this->known_diroptions_files)
					   && preg_match('/^40_syscp_diroption_(.+)\.conf$/', $diroptions_filename)
					   && file_exists(makeCorrectFile($this->settings['system']['apacheconf_diroptions'] . '/' . $diroptions_filename)))
					{
						unlink(makeCorrectFile($this->settings['system']['apacheconf_diroptions'] . '/' . $diroptions_filename));
					}
				}
			}
		}
	}

	public function restartService()
	{
		if($this->settings['system']['awstats_enabled'] == '1')
		{
			$this->cronlog->logAction(CRON_ACTION, LOG_NOTICE, 'cron_tasks: Task1 - Running awstats_updateall.pl');
			fwrite($this->debugHandler, '   cron_tasks: Task1 - Running awstats_updateall.pl' . "\n");
			safe_exec($this->settings['system']['awstats_updateall_command'] . " now -awstatsprog=" . $this->settings['system']['awstats_path'] . "awstats.pl -configdir=" . $this->settings['system']['awstats_domain_file']);
			$this->cronlog->logAction(CRON_ACTION, LOG_NOTICE, 'cron_tasks: Task1 - awstats_updateall.pl done');
			fwrite($this->debugHandler, '   cron_tasks: Task1 - awstats_updateall.pl done' . "\n");
		}

		safe_exec($this->settings['system']['apachereload_command']);

		if($this->is_lighttpd)
		{
			$this->cronlog->logAction(CRON_ACTION, LOG_NOTICE, 'cron_tasks: Task1 - Lighttpd reloaded');
			fwrite($this->debugHandler, '   cron_tasks: Task1 - Lighttpd reloaded' . "\n");
		}
		else
		{
			$this->cronlog->logAction(CRON_ACTION, LOG_NOTICE, 'cron_tasks: Task1 - Apache reloaded');
			fwrite($this->debugHandler, '   cron_tasks: Task1 - Apache reloaded' . "\n");
		}
	}
}

?>
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
 * @version    $Id$
 */

if(@php_sapi_name() != 'cli'
   && @php_sapi_name() != 'cgi'
   && @php_sapi_name() != 'cgi-fcgi')
{
	die('This script only works in the shell.');
}

class apache
{
	private $db = false;
	private $logger = false;
	private $debugHandler = false;
	private $settings = array();
	protected $known_filenames = array();
	protected $virtualhosts_data = array();

	function __construct($db, $logger, $debugHandler, $settings)
	{
		$this->db = $db;
		$this->logger = $logger;
		$this->debugHandler = $debugHandler;
		$this->settings = $settings;
	}

	/*
	*	We compose the namevirtualhost entries
	*/

	public function reload()
	{
		fwrite($this->debugHandler, '   apache::reload: reloading apache' . "\n");
		$this->logger->logAction(CRON_ACTION, LOG_INFO, 'reloading apache');
		safe_exec($this->settings['system']['apachereload_command']);
	}

	public function createIpPort()
	{
		$result_ipsandports = $this->db->query("SELECT `ip`, `port`, `listen_statement`, `namevirtualhost_statement`," . " `vhostcontainer`, `vhostcontainer_servername_statement`," . " `specialsettings`, `ssl`, `ssl_cert`" . " FROM `" . TABLE_PANEL_IPSANDPORTS . "` ORDER BY `ip` ASC, `port` ASC");

		while($row_ipsandports = $this->db->fetch_array($result_ipsandports))
		{
			if(filter_var($row_ipsandports['ip'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4))
			{
				$ipport = $row_ipsandports['ip'] . ':' . $row_ipsandports['port'];
			}
			else
			{
				$ipport = '[' . $row_ipsandports['ip'] . ']:' . $row_ipsandports['port'];
			}

			fwrite($this->debugHandler, '  apache::createIpPort: creating ip/port settings for  ' . $ipport . "\n");
			$this->logger->logAction(CRON_ACTION, LOG_INFO, 'creating ip/port settings for  ' . $ipport);
			$vhosts_filename = makeCorrectFile($this->settings['system']['apacheconf_vhost'] . '/10_syscp_ipandport_' . trim(str_replace(':', '.', $row_ipsandports['ip']), '.') . '.' . $row_ipsandports['port'] . '.conf');

			if($row_ipsandports['listen_statement'] == '1')
			{
				$this->virtualhosts_data[$vhosts_filename].= 'Listen ' . $ipport . "\n";
				$this->logger->logAction(CRON_ACTION, LOG_DEBUG, $ipport . ' :: inserted listen-statement');
			}

			if($row_ipsandports['namevirtualhost_statement'] == '1')
			{
				$this->virtualhosts_data[$vhosts_filename].= 'NameVirtualHost ' . $ipport . "\n";
				$this->logger->logAction(CRON_ACTION, LOG_DEBUG, $ipport . ' :: inserted namevirtualhost-statement');
			}

			if($row_ipsandports['vhostcontainer'] == '1')
			{
				$this->virtualhosts_data[$vhosts_filename].= '<VirtualHost ' . $ipport . '>' . "\n";

				if($row_ipsandports['vhostcontainer_servername_statement'] == '1')
				{
					$this->virtualhosts_data[$vhosts_filename].= ' ServerName ' . $this->settings['system']['hostname'] . "\n";
				}

				if($row_ipsandports['specialsettings'] != '')
				{
					$this->virtualhosts_data[$vhosts_filename].= $row_ipsandports['specialsettings'] . "\n";
				}

				if($row_ipsandports['ssl'] == '1')
				{
					$this->virtualhosts_data[$vhosts_filename].= ' SSLEngine On' . "\n";
					$this->virtualhosts_data[$vhosts_filename].= ' SSLCertificateFile ' . $row_ipsandports['ssl_cert'] . "\n";
				}

				$this->virtualhosts_data[$vhosts_filename].= '</VirtualHost>' . "\n";
				$this->logger->logAction(CRON_ACTION, LOG_DEBUG, $ipport . ' :: inserted vhostcontainer');
			}

			unset($vhosts_filename);
		}
	}

	/*
	*	We put together the needed php options in the virtualhost entries
	*/

	protected function composePhpOptions($domain)
	{
		$php_options_text = '';

		// This vHost has PHP enabled...

		if($this->settings['system']['mod_fcgid'] == 1)
		{
			// ...and we are using mod_fcgid
			// TODO: Put this in the join on line 212

			$sql = "SELECT * FROM `ftp_users` WHERE `customerid` = " . $domain['customerid'];
			$result_ftp = $this->db->query($sql);

			if($this->db->num_rows($result_ftp) > 0)
			{
				if((int)$this->settings['system']['mod_fcgid_wrapper'] == 0)
				{
					$php_options_text.= '  SuexecUserGroup "' . $domain['loginname'] . '" "' . $domain['loginname'] . '"' . "\n";
					$php_options_text.= '  ScriptAlias /php/ ' . $this->settings['system']['mod_fcgid_configdir'] . '/' . $domain['loginname'] . '/' . $domain['domain'] . '/' . "\n";
				}
				else
				{
					$php_options_text.= '  SuexecUserGroup "' . $domain['loginname'] . '" "' . $domain['loginname'] . '"' . "\n";
					$php_options_text.= '  <Directory "' . $domain['documentroot'] . '">' . "\n";
					$php_options_text.= '    AddHandler fcgid-script .php' . "\n";
					$php_options_text.= '    FCGIWrapper ' . $this->settings['system']['mod_fcgid_configdir'] . '/' . $domain['loginname'] . '/' . $domain['domain'] . '/php-fcgi-starter .php' . "\n";
					$php_options_text.= '    Options +ExecCGI' . "\n";
					$php_options_text.= '  </Directory>' . "\n";
				}

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
					$php_options_text.= '  php_admin_value open_basedir "' . $domain['customerroot'] . $_phpappendopenbasedir . "\"\n";
				}
				else
				{
					$php_options_text.= '  php_admin_value open_basedir "' . $domain['documentroot'] . $_phpappendopenbasedir . "\"\n";
				}
			}

			if($domain['safemode'] == '0')
			{
				$php_options_text.= '  php_admin_flag safe_mode Off ' . "\n";
			}
			else
			{
				$php_options_text.= '  php_admin_flag safe_mode On ' . "\n";
			}
		}

		return $php_options_text;
	}

	/*
	*	We collect all servernames and Aliases
	*/

	protected function getServerNames($domain)
	{
		$servernames_text = '';
		$servernames_text.= '  ServerName ' . $domain['domain'] . "\n";

		if($domain['iswildcarddomain'] == '1')
		{
			$server_alias = '*.' . $domain['domain'];
		}
		else
		{
			if($domain['wwwserveralias'] == '1')
			{
				$server_alias = 'www.' . $domain['domain'];
			}
			else
			{
				$server_alias = '';
			}
		}

		$alias_domains = $this->db->query('SELECT `domain`, `iswildcarddomain`, `wwwserveralias` FROM `' . TABLE_PANEL_DOMAINS . '` WHERE `aliasdomain`=\'' . $domain['id'] . '\'');

		while(($alias_domain = $this->db->fetch_array($alias_domains)) !== false)
		{
			$server_alias.= ' ' . $alias_domain['domain'];

			if($alias_domain['iswildcarddomain'] == '1')
			{
				$server_alias.= ' *.' . $alias_domain['domain'];
			}
			else
			{
				if($alias_domain['wwwserveralias'] == '1')
				{
					$server_alias.= ' www.' . $alias_domain['domain'];
				}
			}
		}

		if(trim($server_alias) != '')
		{
			$servernames_text.= '  ServerAlias ' . $server_alias . "\n";
		}

		$servernames_text.= '  ServerAdmin ' . $domain['email'] . "\n";
		return $servernames_text;
	}

	/*
	*	Let's get the webroot
	*/

	protected function getWebroot($domain)
	{
		$webroot_text = '';
		$domain['customerroot'] = makeCorrectDir($domain['customerroot']);
		$domain['documentroot'] = makeCorrectDir($domain['documentroot']);

		if($domain['deactivated'] == '1'
		   && $this->settings['system']['deactivateddocroot'] != '')
		{
			$webroot_text.= '  # Using docroot for deactivated users...' . "\n";
			$webroot_text.= '  DocumentRoot "' . $this->settings['system']['deactivateddocroot'] . "\"\n";
		}
		else
		{
			$webroot_text.= '  DocumentRoot "' . $domain['documentroot'] . "\"\n";
		}

		return $webroot_text;
	}

	/*
	*	Lets set the text part for the stats software
	*/

	protected function getStats($domain)
	{
		$stats_text = '';

		if($domain['speciallogfile'] == '1')
		{
			if($domain['parentdomainid'] == '0')
			{
				$speciallogfile = '-' . $domain['domain'];
				$stats_text.= '  Alias /webalizer "' . $domain['customerroot'] . '/webalizer/' . $domain['domain'] . "\"\n";
			}
			else
			{
				$speciallogfile = '-' . $domain['parentdomain'];
				$stats_text.= '  Alias /webalizer "' . $domain['customerroot'] . '/webalizer/' . $domain['parentdomain'] . "\"\n";
			}
		}
		else
		{
			$speciallogfile = '';

			if($domain['customerroot'] != $domain['documentroot'])
			{
				$stats_text.= '  Alias /webalizer "' . $domain['customerroot'] . '/webalizer"' . "\n";
			}
		}

		return $stats_text;
	}

	/*
	*	Lets set the logfiles
	*/

	protected function getLogfiles($domain)
	{
		$logfiles_text = '';

		if($this->settings['system']['mod_log_sql'] == 1)
		{
			// We are using mod_log_sql (http://www.outoforder.cc/projects/apache/mod_log_sql/)
			// TODO: See how we are able emulate the error_log

			$logfiles_text.= '  LogSQLTransferLogTable access_log' . "\n";
		}
		else
		{
			// The normal access/error - logging is enabled

			$logfiles_text.= '  ErrorLog "' . $this->settings['system']['logfiles_directory'] . $domain['loginname'] . $speciallogfile . '-error.log' . "\"\n";
			$logfiles_text.= '  CustomLog "' . $this->settings['system']['logfiles_directory'] . $domain['loginname'] . $speciallogfile . '-access.log" combined' . "\n";
		}

		return $logfiles_text;
	}

	/*
	*	Get the filename for the virtualhost
	*/

	protected function getVhostFilename($domain, $ssl_vhost = false)
	{
		if($ssl_vhost === true)
		{
			$vhost_filename = makeCorrectFile($this->settings['system']['apacheconf_vhost'] . '/20_syscp_ssl_vhost_' . $domain['domain'] . '.conf');
		}
		else
		{
			$vhost_filename = makeCorrectFile($this->settings['system']['apacheconf_vhost'] . '/20_syscp_normal_vhost_' . $domain['domain'] . '.conf');
		}

		return $vhost_filename;
	}

	/*
	*	We compose the virtualhost entry for one domain
	*/

	protected function getVhostContent($domain, $ssl_vhost = false)
	{
		if($ssl_vhost === true
		   && $domain['ssl'] != '1')
		{
			return '';
		}

		if($ssl_vhost === true
		   && $domain['ssl'] == '1')
		{
			$query = "SELECT * FROM " . TABLE_PANEL_IPSANDPORTS . " WHERE `id`='" . $domain['ssl_ipandport'] . "'";
		}
		else
		{
			$query = "SELECT * FROM " . TABLE_PANEL_IPSANDPORTS . " WHERE `id`='" . $domain['ipandport'] . "'";
		}

		$ipandport = $this->db->query_first($query);
		$domain['ip'] = $ipandport['ip'];
		$domain['port'] = $ipandport['port'];
		$domain['ssl_cert'] = $ipandport['ssl_cert'];

		if(filter_var($domain['ip'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4))
		{
			$ipport = $domain['ip'] . ':' . $domain['port'];
		}
		else
		{
			$ipport = '[' . $domain['ip'] . ']:' . $domain['port'];
		}

		$vhost_content = '<VirtualHost ' . $ipport . '>' . "\n";
		$vhost_content.= $this->getServerNames($domain);

		if($ssl_vhost == false
		   && $domain['ssl'] == '1'
		   && $domain['ssl_redirect'] == '1')
		{
			$domain['documentroot'] = 'https://' . $domain['domain'];
		}

		if(preg_match('/^https?\:\/\//', $domain['documentroot'])
		   && $ssl_vhost == false)
		{
			$vhost_content.= '  Redirect / ' . $domain['documentroot'] . "\n";
		}
		else
		{
			if($ssl_vhost === true
			   && $domain['ssl'] == '1'
			   && $domain['ssl_cert'] != '')
			{
				$vhost_content.= '  SSLEngine On' . "\n";
				$vhost_content.= '  SSLCertificateFile ' . $domain['ssl_cert'] . "\n";
			}

			$vhost_content.= $this->getWebroot($domain);

			if($domain['phpenabled'] == '1')
			{
				$vhost_content.= $this->composePhpOptions($domain);
			}
			elseif($this->settings['system']['mod_fcgid'] != 1)
			{
				$vhost_content.= '  # PHP is disabled for this vHost' . "\n";
				$vhost_content.= '  php_flag engine off' . "\n";
			}
			else
			{
				$vhost_content.= '  # PHP is disabled for this vHost' . "\n";
			}

			mkDirWithCorrectOwnership($domain['customerroot'], $domain['documentroot'], $domain['guid'], $domain['guid']);
			$vhost_content.= $this->getStats($domain);
			$vhost_content.= $this->getLogfiles($domain);
		}

		if($domain['specialsettings'] != '')
		{
			$vhost_content.= $domain['specialsettings'] . "\n";
		}

		if($this->settings['system']['default_vhostconf'] != '')
		{
			$vhost_content.= $this->settings['system']['default_vhostconf'] . "\n";
		}

		$vhost_content.= '</VirtualHost>' . "\n";
		return $vhost_content;
	}

	/*
	*	We compose the virtualhost entries for the domains
	*/

	public function createVirtualHosts()
	{
		$result_domains = $this->db->query("SELECT `d`.`id`, `d`.`domain`, `d`.`customerid`, `d`.`documentroot`, `d`.`ssl`, " . "`d`.`parentdomainid`, `d`.`ipandport`, `d`.`ssl_ipandport`, `d`.`ssl_redirect`, " . "`d`.`isemaildomain`, `d`.`iswildcarddomain`, `d`.`wwwserveralias`, `d`.`openbasedir`, `d`.`openbasedir_path`, " . "`d`.`safemode`, `d`.`speciallogfile`, `d`.`specialsettings`, `pd`.`domain` AS `parentdomain`, `c`.`loginname`, `d`.`phpsettingid`, `c`.`adminid`, " . "`c`.`guid`, `c`.`email`, `c`.`documentroot` AS `customerroot`, `c`.`deactivated`, `c`.`phpenabled` AS `phpenabled`, `d`.`mod_fcgid_starter` " . "FROM `" . TABLE_PANEL_DOMAINS . "` `d` LEFT JOIN `" . TABLE_PANEL_CUSTOMERS . "` `c` USING(`customerid`) " . "LEFT JOIN `" . TABLE_PANEL_DOMAINS . "` `pd` ON (`pd`.`id` = `d`.`parentdomainid`) " . "WHERE `d`.`aliasdomain` IS NULL ORDER BY `d`.`iswildcarddomain`, `d`.`domain` ASC");

		while($domain = $this->db->fetch_array($result_domains))
		{
			fwrite($this->debugHandler, '  apache::createVirtualHosts: creating vhost container for domain ' . $domain['id'] . ', customer ' . $domain['loginname'] . "\n");
			$this->logger->logAction(CRON_ACTION, LOG_INFO, 'creating vhost container for domain ' . $domain['id'] . ', customer ' . $domain['loginname']);
			$vhosts_filename = $this->getVhostFilename($domain);

			// Apply header

			$this->virtualhosts_data[$vhosts_filename] = '# Domain ID: ' . $domain['id'] . ' - CustomerID: ' . $domain['customerid'] . ' - CustomerLogin: ' . $domain['loginname'] . "\n";

			if($domain['deactivated'] != '1'
			   || $this->settings['system']['deactivateddocroot'] != '')
			{
				$this->virtualhosts_data[$vhosts_filename].= $this->getVhostContent($domain);

				if($domain['ssl'] == '1')
				{
					// Adding ssl stuff if enabled

					$vhosts_filename_ssl = $this->getVhostFilename($domain, true);
					$this->virtualhosts_data[$vhosts_filename_ssl] = '# Domain ID: ' . $domain['id'] . ' (SSL) - CustomerID: ' . $domain['customerid'] . ' - CustomerLogin: ' . $domain['loginname'] . "\n";
					$this->virtualhosts_data[$vhosts_filename_ssl].= $this->getVhostContent($domain, true);
				}
			}
			else
			{
				$this->virtualhosts_data[$vhosts_filename].= '# Customer deactivated and a docroot for deactivated users hasn\'t been set.' . "\n";
			}
		}
	}

	/*
	*	We write the configs
	*/

	public function writeConfigs()
	{
		fwrite($this->debugHandler, '  apache::writeConfigs: rebuilding ' . $this->settings['system']['apacheconf_vhost'] . "\n");
		$this->logger->logAction(CRON_ACTION, LOG_INFO, "rebuilding " . $this->settings['system']['apacheconf_vhost']);

		if(!isConfigDir($this->settings['system']['apacheconf_vhost']))
		{
			// Save one big file

			foreach($this->virtualhosts_data as $vhosts_filename => $vhost_content)
			{
				$vhosts_file.= $vhost_content . "\n\n";
			}

			// Include diroptions file in case it exists

			if(file_exists($this->settings['system']['apacheconf_diroptions']))
			{
				$vhosts_file.= "\n" . 'Include ' . $this->settings['system']['apacheconf_diroptions'] . "\n\n";
			}

			$vhosts_filename = $this->settings['system']['apacheconf_vhost'];

			// Apply header

			$vhosts_file = '# ' . basename($vhosts_filename) . "\n" . '# Created ' . date('d.m.Y H:i') . "\n" . '# Do NOT manually edit this file, all changes will be deleted after the next domain change at the panel.' . "\n" . "\n" . $vhosts_file;
			$vhosts_file_handler = fopen($vhosts_filename, 'w');
			fwrite($vhosts_file_handler, $vhosts_file);
			fclose($vhosts_file_handler);
		}
		else
		{
			if(!file_exists($this->settings['system']['apacheconf_vhost']))
			{
				fwrite($this->debugHandler, '  apache::writeConfigs: mkdir ' . escapeshellarg(makeCorrectDir($this->settings['system']['apacheconf_vhost'])) . "\n");
				$this->logger->logAction(CRON_ACTION, LOG_NOTICE, 'mkdir ' . escapeshellarg(makeCorrectDir($this->settings['system']['apacheconf_vhost'])));
				safe_exec('mkdir ' . escapeshellarg(makeCorrectDir($this->settings['system']['apacheconf_vhost'])));
			}

			// Write a single file for every vhost

			foreach($this->virtualhosts_data as $vhosts_filename => $vhosts_file)
			{
				$this->known_filenames[] = basename($vhosts_filename);

				// Apply header

				$vhosts_file = '# ' . basename($vhosts_filename) . "\n" . '# Created ' . date('d.m.Y H:i') . "\n" . '# Do NOT manually edit this file, all changes will be deleted after the next domain change at the panel.' . "\n" . "\n" . $vhosts_file;
				$vhosts_file_handler = fopen($vhosts_filename, 'w');
				fwrite($vhosts_file_handler, $vhosts_file);
				fclose($vhosts_file_handler);
			}

			$this->wipeOutOldConfigs();
		}
	}

	/*
	*	We remove old config files
	*/

	private function wipeOutOldConfigs()
	{
		fwrite($this->debugHandler, '  apache::wipeOutOldConfigs: cleaning ' . $this->settings['system']['apacheconf_vhost'] . "\n");
		$this->logger->logAction(CRON_ACTION, LOG_INFO, "cleaning " . $this->settings['system']['apacheconf_vhost']);

		if(isConfigDir($this->settings['system']['apacheconf_vhost'])
		   && file_exists($this->settings['system']['apacheconf_vhost'])
		   && is_dir($this->settings['system']['apacheconf_vhost']))
		{
			$vhost_file_dirhandle = opendir($this->settings['system']['apacheconf_vhost']);

			while(false !== ($vhost_filename = readdir($vhost_file_dirhandle)))
			{
				if($vhost_filename != '.'
				   && $vhost_filename != '..'
				   && !in_array($vhost_filename, $this->known_filenames)
				   && preg_match('/^(10|20|30)_syscp_(ipandport|normal_vhost|wildcard_vhost|ssl_vhost)_(.+)\.conf$/', $vhost_filename)
				   && file_exists(makeCorrectFile($this->settings['system']['apacheconf_vhost'] . '/' . $vhost_filename)))
				{
					fwrite($this->debugHandler, '  apache::wipeOutOldConfigs: unlinking ' . $vhost_filename . "\n");
					$this->logger->logAction(CRON_ACTION, LOG_NOTICE, 'unlinking ' . $vhost_filename);
					unlink(makeCorrectFile($this->settings['system']['apacheconf_vhost'] . '/' . $vhost_filename));
				}
			}
		}
	}
}

?>

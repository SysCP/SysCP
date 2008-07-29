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

class bind
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
	
	function createZones()
	{
		fwrite($this->debugHandler, '  cron_tasks: Task4 started - Rebuilding syscp_bind.conf' . "\n");
		$this->logger->logAction(CRON_ACTION, LOG_INFO, 'Task4 started - Rebuilding syscp_bind.conf');

		if(!file_exists($this->settings['system']['bindconf_directory'] . 'domains/'))
		{
			$this->logger->logAction(CRON_ACTION, LOG_NOTICE, 'mkdir ' . escapeshellarg(makeCorrectDir($this->settings['system']['bindconf_directory'] . '/domains/')));
			safe_exec('mkdir ' . escapeshellarg(makeCorrectDir($this->settings['system']['bindconf_directory'] . '/domains/')));
		}

		$bindconf_file = '# ' . $this->settings['system']['bindconf_directory'] . 'syscp_bind.conf' . "\n" . '# Created ' . date('d.m.Y H:i') . "\n" . '# Do NOT manually edit this file, all changes will be deleted after the next domain change at the panel.' . "\n" . "\n";
		$nameservers = (($this->settings['system']['nameservers'] != '') ? split(',', $this->settings['system']['nameservers']) : array());
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

		$mxservers = (($this->settings['system']['mxservers'] != '') ? split(',', $this->settings['system']['mxservers']) : array());
		for ($i = 0;$i < count($mxservers);$i++)
		{
			if(!preg_match('/\.$/', $mxservers[$i]))
			{
				$mxservers[$i].= '.';
			}
		}

		$known_domain_zonefiles = array();
		$result_domains = $this->db->query("SELECT `d`.`id`, `d`.`domain`, `d`.`iswildcarddomain`, `d`.`customerid`, `d`.`zonefile`, `d`.`bindserial`, `ip`.`ip`, `c`.`loginname`, `c`.`guid` FROM `" . TABLE_PANEL_DOMAINS . "` `d` LEFT JOIN `" . TABLE_PANEL_CUSTOMERS . "` `c` USING(`customerid`) LEFT JOIN `" . TABLE_PANEL_IPSANDPORTS . "` AS `ip` ON(`d`.`ipandport`=`ip`.`id`) WHERE `d`.`isbinddomain` = '1' ORDER BY `d`.`domain` ASC");

		while($domain = $this->db->fetch_array($result_domains))
		{
			fwrite($this->debugHandler, '  cron_tasks: Task4 - Writing ' . $domain['id'] . '::' . $domain['domain'] . "\n");
			$this->logger->logAction(CRON_ACTION, LOG_INFO, 'Writing ' . $domain['id'] . '::' . $domain['domain']);

			if($domain['zonefile'] == '')
			{
				$date = date('Ymd');
				$bindserial = (preg_match('/^' . $date . '/', $domain['bindserial']) ? $domain['bindserial']+1 : $date . '00');
				$this->db->query('UPDATE `' . TABLE_PANEL_DOMAINS . '` SET `bindserial`=\'' . $bindserial . '\' WHERE `id`=\'' . $domain['id'] . '\'');
				$zonefile = '$TTL 1W' . "\n";

				if(count($nameservers) == 0)
				{
					$zonefile.= '@ IN SOA ns ' . str_replace('@', '.', $this->settings['panel']['adminmail']) . '. (' . "\n";
				}
				else
				{
					$zonefile.= '@ IN SOA ' . $nameservers[0]['hostname'] . ' ' . str_replace('@', '.', $this->settings['panel']['adminmail']) . '. (' . "\n";
				}

				$zonefile.= '	' . $bindserial . ' ; serial' . "\n" . '	8H ; refresh' . "\n" . '	2H ; retry' . "\n" . '	1W ; expiry' . "\n" . '	11h) ; minimum' . "\n";

				if(count($nameservers) == 0)
				{
					$zonefile.= '@	IN	NS	ns' . "\n" . 'ns	IN	A	' . $domain['ip'] . "\n";
				}
				else
				{
					foreach($nameservers as $nameserver)
					{
						$zonefile.= '@	IN	NS	' . trim($nameserver['hostname']) . "\n";
					}
				}

				if(count($mxservers) == 0)
				{
					$zonefile.= '@	IN	MX	10 mail' . "\n" . 'mail	IN	A	' . $domain['ip'] . "\n";
				}
				else
				{
					foreach($mxservers as $mxserver)
					{
						$zonefile.= '@	IN	MX	' . trim($mxserver) . "\n";
					}
				}

				$nssubdomains = $this->db->query('SELECT `domain` FROM `' . TABLE_PANEL_DOMAINS . '` WHERE `isbinddomain`=\'1\' AND `domain` LIKE \'%.' . $domain['domain'] . '\'');

				while($nssubdomain = $this->db->fetch_array($nssubdomains))
				{
					if(preg_match('/^[^\.]+\.' . $domain['domain'] . '/', $nssubdomain['domain']))
					{
						$nssubdomain = str_replace('.' . $domain['domain'], '', $nssubdomain['domain']);

						if(count($nameservers) == 0)
						{
							$zonefile.= $nssubdomain . '	IN	NS	ns.' . $nssubdomain . "\n";
						}
						else
						{
							foreach($nameservers as $nameserver)
							{
								$zonefile.= $nssubdomain . '	IN	NS	' . trim($nameserver['hostname']) . "\n";
							}
						}
					}
				}

				$zonefile.= '@	IN	A	' . $domain['ip'] . "\n";
				$zonefile.= 'www	IN	A	' . $domain['ip'] . "\n";

				if($domain['iswildcarddomain'] == '1')
				{
					$zonefile.= '*	IN  A	' . $domain['ip'] . "\n";
				}

				$subdomains = $this->db->query('SELECT `d`.`domain`, `ip`.`ip` AS `ip` FROM `' . TABLE_PANEL_DOMAINS . '` `d`, `' . TABLE_PANEL_IPSANDPORTS . '` `ip` WHERE `parentdomainid`=\'' . $domain['id'] . '\' AND `d`.`ipandport`=`ip`.`id`');

				while($subdomain = $this->db->fetch_array($subdomains))
				{
					$zonefile.= str_replace('.' . $domain['domain'], '', $subdomain['domain']) . '	IN	A	' . $subdomain['ip'] . "\n";
				}

				$domain['zonefile'] = 'domains/' . $domain['domain'] . '.zone';
				$zonefile_name = makeCorrectFile($this->settings['system']['bindconf_directory'] . '/domains/' . $domain['domain'] . '.zone');
				$known_domain_zonefiles[] = basename($zonefile_name);
				$zonefile_handler = fopen($zonefile_name, 'w');
				fwrite($zonefile_handler, $zonefile);
				fclose($zonefile_handler);
				fwrite($this->debugHandler, '  cron_tasks: Task4 - `' . $zonefile_name . '` zone written' . "\n");
			}

			$bindconf_file.= '# Domain ID: ' . $domain['id'] . ' - CustomerID: ' . $domain['customerid'] . ' - CustomerLogin: ' . $domain['loginname'] . "\n";
			$bindconf_file.= 'zone "' . $domain['domain'] . '" in {' . "\n";
			$bindconf_file.= '	type master;' . "\n";
			$bindconf_file.= '	file "' . $this->settings['system']['bindconf_directory'] . $domain['zonefile'] . '";' . "\n";
			$bindconf_file.= '	allow-query { any; };' . "\n";

			if(count($nameservers) > 1)
			{
				$bindconf_file.= '	allow-transfer {' . "\n";
				for ($i = 1;$i < count($nameservers);$i++)
				{
					$bindconf_file.= '		' . $nameservers[$i]['ip'] . ';' . "\n";
				}

				$bindconf_file.= '	};' . "\n";
			}

			$bindconf_file.= '};' . "\n";
			$bindconf_file.= "\n";
			$bindconf_file_handler = fopen(makeCorrectFile($this->settings['system']['bindconf_directory'] . '/syscp_bind.conf'), 'w');
			fwrite($bindconf_file_handler, $bindconf_file);
			fclose($bindconf_file_handler);
			fwrite($this->debugHandler, '  cron_tasks: Task4 - syscp_bind.conf written' . "\n");
			$this->logger->logAction(CRON_ACTION, LOG_INFO, 'syscp_bind.conf written');
		}

		safe_exec($this->settings['system']['bindreload_command']);
		fwrite($this->debugHandler, '  cron_tasks: Task4 - Bind9 reloaded' . "\n");
		$this->logger->logAction(CRON_ACTION, LOG_INFO, 'Bind9 reloaded');
		$domains_dir = makeCorrectDir($this->settings['system']['bindconf_directory'] . '/domains/');

		if(file_exists($domains_dir)
		   && is_dir($domains_dir))
		{
			$domain_file_dirhandle = opendir($domains_dir);

			while(false !== ($domain_filename = readdir($domain_file_dirhandle)))
			{
				if($domain_filename != '.'
				   && $domain_filename != '..'
				   && !in_array($domain_filename, $known_domain_zonefiles)
				   && file_exists(makeCorrectFile($domains_dir . '/' . $domain_filename)))
				{
					fwrite($this->debugHandler, '  cron_tasks: Task4 - unlinking ' . $domain_filename . "\n");
					$this->logger->logAction(CRON_ACTION, LOG_WARNING, 'Deleting ' . $domain_filename);
					unlink(makeCorrectFile($domains_dir . '/' . $domain_filename));
				}
			}
		}
	}
}
?>
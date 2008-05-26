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
 * @author     Michael Kaufmann <mk@syscp-help.org>
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

include (dirname(__FILE__) . '/../lib/cron_init.php');

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
		fwrite($debugHandler, '  cron_tasks: Task1 started - ' . $settings['system']['apacheconf_vhost'] . ' rebuild' . "\n");

		if(isConfigDir($settings['system']['apacheconf_vhost'])
		   && !file_exists($settings['system']['apacheconf_vhost']))
		{
			safe_exec('mkdir ' . escapeshellarg(makeCorrectDir($settings['system']['apacheconf_vhost'])));
			$cronlog->logAction(CRON_ACTION, LOG_NOTICE, 'mkdir ' . escapeshellarg(makeCorrectDir($settings['system']['apacheconf_vhost'])));
		}

		$http = cron_httpd::getInstanceOf($db, $settings, $cronlog, $debugHandler);
		$http->createSyscpVhost();
		$http->createCustomerVhosts();
		$http->safeVhostFile();
		$http->restartService();
	}

	/**
	 * TYPE=2 MEANS TO CREATE A NEW HOME AND CHOWN
	 */
	elseif ($row['type'] == '2')
	{
		fwrite($debugHandler, '  cron_tasks: Task2 started - create new home' . "\n");
		$cronlog->logAction(CRON_ACTION, LOG_NOTICE, 'cron_tasks: Task2 started - create new home');

		if(is_array($row['data']))
		{
			if($settings['system']['webalizier_enabled'] == '1')
			{
				safe_exec('mkdir -p ' . escapeshellarg($settings['system']['documentroot_prefix'] . $row['data']['loginname'] . '/webalizer'));
			}
			elseif($settings['system']['awstats_enabled'] == '1')
			{
				safe_exec('mkdir -p ' . escapeshellarg($settings['system']['documentroot_prefix'] . $row['data']['loginname'] . '/awstats'));
			}
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
		$cronlog->logAction(CRON_ACTION, LOG_NOTICE, 'cron_tasks: Task3 started - create/change/del htaccess/htpasswd');

		$http = cron_httpd::getInstanceOf($db, $settings, $cronlog, $debugHandler);
		$http->createDiroptions();
		$http->saveDiroptions();
		$http->restartService();
	}

	/**
	 * TYPE=4 MEANS THAT SOMETHING IN THE BIND CONFIG HAS CHANGED. REBUILD syscp_bind.conf
	 */
	elseif ($row['type'] == '4')
	{
		fwrite($debugHandler, '  cron_tasks: Task4 started - Rebuilding syscp_bind.conf' . "\n");
		$cronlog->logAction(CRON_ACTION, LOG_NOTICE, 'cron_tasks: Task4 started - Rebuilding syscp_bind.conf');

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
		$result_domains = $db->query("SELECT `d`.`id`, `d`.`domain`, `d`.`iswildcarddomain`, `d`.`customerid`, `d`.`zonefile`, `d`.`dkim`, `d`.`bindserial`, `ip`.`ip`, `c`.`loginname`, `c`.`guid` FROM `" . TABLE_PANEL_DOMAINS . "` `d` LEFT JOIN `" . TABLE_PANEL_CUSTOMERS . "` `c` USING(`customerid`) LEFT JOIN `" . TABLE_PANEL_IPSANDPORTS . "` AS `ip` ON(`d`.`ipandport`=`ip`.`id`) WHERE `d`.`isbinddomain` = '1' ORDER BY `d`.`domain` ASC");

		while($domain = $db->fetch_array($result_domains))
		{
			if(isset($domain['domain'])
			   && $domain['domain'] != '')
			{
				fwrite($debugHandler, '  cron_tasks: Task4 - Writing ' . $domain['id'] . '::' . $domain['domain'] . "\n");
				$cronlog->logAction(CRON_ACTION, LOG_NOTICE, 'cron_tasks: Task4 - Writing ' . $domain['id'] . '::' . $domain['domain']);
		
				if($domain['dkim'] == '1'
				   && $settings['dkim']['use_dkim'] == '1')
				{
					$dkimquery = $db->query('SELECT ' . TABLE_MAIL_DKIM . '.`id`, `publickey` FROM `' . TABLE_MAIL_DKIM . '`, `' . TABLE_PANEL_DOMAINS . '` WHERE ' . TABLE_PANEL_DOMAINS . '.`id`=' . TABLE_MAIL_DKIM . '.`domain_id` AND `domain`=\'' . $domain['domain'] . '\'');
					$dkimkey = $db->fetch_array($dkimquery);
		
					if($dkimkey['publickey'] == '')
					{
						// Create a new table entry
		
						$db->query('INSERT INTO ' . TABLE_MAIL_DKIM . ' SET `domain_id`=' . $domain['id']);
						$dkid = $db->insert_id()+2000;
		
						// Create a new private rsa certificate and put the public key into the database
		
						safe_exec("openssl genrsa -out " . $settings['dkim']['dkim_prefix'] . "dk" . $dkid . ".private 1024 2>/dev/null");
						$txtpubkey = safe_exec("openssl rsa -in " . $settings['dkim']['dkim_prefix'] . "dk" . $dkid . ".private -pubout -outform pem 2>/dev/null | grep -v \"^-\"  | tr -d '\n'");
						safe_exec("chmod 0640 " . $settings['dkim']['dkim_prefix'] . "dk" . $dkid . ".private");
						$db->query('UPDATE ' . TABLE_MAIL_DKIM . ' SET `publickey`=\'' . $txtpubkey . '\' WHERE `domain_id`=\'' . $domain['id'] . '\'');
					}
					else
					{
						$dkid = (int)$dkimkey['id']+2000;
						$txtpubkey = $dkimkey['publickey'];
					}
				}
		
				if($domain['zonefile'] == '')
				{
					$date = date('Ymd');
					$bindserial = (preg_match('/^' . $date . '/', $domain['bindserial']) ? $domain['bindserial']+1 : $date . '00');
					$db->query('UPDATE `' . TABLE_PANEL_DOMAINS . '` SET `bindserial`=\'' . $bindserial . '\' WHERE `id`=\'' . $domain['id'] . '\'');
					$zonefile = '$TTL 1W' . "\n";
		
					if(count($nameservers) == 0)
					{
						$zonefile.= '@ IN SOA ns ' . str_replace('@', '.', $settings['panel']['adminmail']) . '. (' . "\n";
					}
					else
					{
						$zonefile.= '@ IN SOA ' . $nameservers[0]['hostname'] . ' ' . str_replace('@', '.', $settings['panel']['adminmail']) . '. (' . "\n";
					}
		
					$zonefile.= '	' . $bindserial . ' ; serial' . "\n" . '	8H ; refresh' . "\n" . '	2H ; retry' . "\n" . '	1W ; expiry' . "\n" . '	11h) ; minimum' . "\n";
		
					if(count($nameservers) == 0)
					{
						if(filter_var($domain['ip'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4))
						{
							$zonefile.= '@	IN	NS	ns' . "\n" . 'ns	IN	A	' . $domain['ip'] . "\n";
						}
						elseif(filter_var($domain['ip'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV6))
						{
							$zonefile.= '@	IN	NS	ns' . "\n" . 'ns	IN	AAAA	' . $domain['ip'] . "\n";
						}
					}
					else
					{
						foreach($nameservers as $nameserver)
						{
							$zonefile.= '@	IN	NS	' . trim($nameserver['hostname']) . "\n";
						}
					}

					if($settings['system']['userdns'] == '1')
					{
						$result_dns = $db->query("SELECT * FROM `" . TABLE_PANEL_DNSENTRY . "` WHERE `domainid`='" . (int)$domain['id'] . "'");
						$row_dns = $db->fetch_array($result_dns);
						
						if($row_dns['mx10'] == ''
						   && $row_dns['mx20'] == '')
						{
							if(count($mxservers) == 0)
							{
								if(filter_var($domain['ip'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4))
								{
									$zonefile.= '@	IN	MX	10 mail' . "\n" . 'mail	IN	A	' . $domain['ip'] . "\n";
									$zonefile.= $domain['domain'] . '.	IN	TXT	"v=spf1 a ipv4:' . $domain['ip'] . ' ~all"' . "\n";
								}
								elseif(filter_var($domain['ip'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV6))
								{
									$zonefile.= '@	IN	MX	10 mail' . "\n" . 'mail	IN	AAAA	' . $domain['ip'] . "\n";
									$zonefile.= $domain['domain'] . '.	IN	TXT	"v=spf1 a mx ~all"' . "\n";
								}
							}
							else
							{
								foreach($mxservers as $mxserver)
								{
									$zonefile.= '@	IN	MX	' . trim($mxserver) . "\n";
								}
							}
						}
						else
						{
							if($row_dns['mx10'] != '')
							{
								$zonefile.= $domain['domain'] . '.	MX	10	' . $row_dns['mx10'] . "\n";
							}
							if($row_dns['mx20'] != '')
							{
								$zonefile.= $domain['domain'] . '.	MX	20	' . $row_dns['mx20'] . "\n";
							}
							
							if($row_dns['txt'] != '')
							{
								$zonefile.= $domain['domain'] . '.	IN	TXT	"' . $row_dns['txt'] . '"' . "\n";
							}
						}
					}
					else
					{
						if(count($mxservers) == 0)
						{
							if(filter_var($domain['ip'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4))
							{
								$zonefile.= '@	IN	MX	10 mail' . "\n" . 'mail	IN	A	' . $domain['ip'] . "\n";
								$zonefile.= $domain['domain'] . '.	IN	TXT	"v=spf1 a ipv4:' . $domain['ip'] . ' ~all"' . "\n";
							}
							elseif(filter_var($domain['ip'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV6))
							{
								$zonefile.= '@	IN	MX	10 mail' . "\n" . 'mail	IN	AAAA	' . $domain['ip'] . "\n";
								$zonefile.= $domain['domain'] . '.	IN	TXT	"v=spf1 a mx ~all"' . "\n";
							}
						}
						else
						{
							foreach($mxservers as $mxserver)
							{
								$zonefile.= '@	IN	MX	' . trim($mxserver) . "\n";
							}
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

					if($settings['system']['userdns'] == '1')
					{
						$result_dns = $db->query("SELECT * FROM `" . TABLE_PANEL_DNSENTRY . "` WHERE `domainid`='" . (int)$domain['id'] . "'");
						$row_dns = $db->fetch_array($result_dns);
						
						if($row_dns['cname'] != '')
						{
							$zonefile.= '@	IN	CNAME	' . $row_dns['cname'] . '.' . "\n";
							$zonefile.= 'www	IN	CNAME	' . $row_dns['cname'] . '.' . "\n";
							
							if($domain['iswildcarddomain'] == '1')
							{
								$zonefile.= '*	IN	CNAME	' . $row_dns['cname'] . '.' . "\n";
							}
						}
						else
						{
							if($row_dns['ipv4'] != '')
							{
								$zonefile.= '@	IN	A	' . $row_dns['ipv4'] . "\n";
								$zonefile.= 'www	IN	A	' . $row_dns['ipv4'] . "\n";
							}
						
							if($row_dns['ipv6'] != '')
							{
								$zonefile.= '@	IN	AAAA	' . $row_dns['ipv6'] . "\n";
								$zonefile.= 'www	IN	AAAA	' . $row_dns['ipv6'] . "\n";
							}
						
							if($domain['iswildcarddomain'] == '1')
							{
								if($row_dns['ipv4'] != '')
								{
									$zonefile.= '*	IN	A	' . $row_dns['ipv4'] . "\n";
								}
						
								if($row_dns['ipv6'] != '')
								{
									$zonefile.= '*	IN	AAAA	' . $row_dns['ipv6'] . "\n";
								}
							}
						}
					}
					else
					{
						if(filter_var($domain['ip'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4))
						{
							$zonefile.= '@	IN	A	' . $domain['ip'] . "\n";
							$zonefile.= 'www	IN	A	' . $domain['ip'] . "\n";
						}
						elseif(filter_var($domain['ip'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV6))
						{
							$zonefile.= '@	IN	AAAA	' . $domain['ip'] . "\n";
							$zonefile.= 'www	IN	AAAA	' . $domain['ip'] . "\n";
						}
		
						if($domain['iswildcarddomain'] == '1')
						{
							if(filter_var($domain['ip'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4))
							{
								$zonefile.= '*	IN  A	' . $domain['ip'] . "\n";
							}
							elseif(filter_var($domain['ip'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV6))
							{
								$zonefile.= '*	IN  AAAA	' . $domain['ip'] . "\n";
							}
						}
					}
					
					$subdomains = $db->query('SELECT `d`.`domain`, `ip`.`ip` AS `ip` FROM `' . TABLE_PANEL_DOMAINS . '` `d`, `' . TABLE_PANEL_IPSANDPORTS . '` `ip` WHERE `parentdomainid`=\'' . $domain['id'] . '\' AND `d`.`ipandport`=`ip`.`id`');
		
					while($subdomain = $db->fetch_array($subdomains))
					{
						if(filter_var($domain['ip'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4))
						{
							$zonefile.= str_replace('.' . $domain['domain'], '', $subdomain['domain']) . '	IN	A	' . $subdomain['ip'] . "\n";
						}
						elseif(filter_var($domain['ip'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV6))
						{
							$zonefile.= str_replace('.' . $domain['domain'], '', $subdomain['domain']) . '	IN	AAAA	' . $subdomain['ip'] . "\n";
						}
					}
		
					if($domain['dkim'] == '1'
					   && $settings['dkim']['use_dkim'] == '1')
					{
						$zonefile.= "dk" . $dkid . "_domainkey	IN	TXT	\"v=DKIM1; k=rsa; p=" . trim($txtpubkey) . "\"\n";
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
		
				if(count($nameservers) > 0)
				{
					$bindconf_file.= '	allow-transfer {' . "\n";
					for ($i = 0;$i < count($nameservers);$i++)
					{
						if(ip2long($nameservers[$i]['ip']))
						{
							$bindconf_file.= '              ' . $nameservers[$i]['ip'] . ';' . "\n";
						}
					}
		
					$bindconf_file.= '	};' . "\n";
				}
		
				$bindconf_file.= '};' . "\n";
				$bindconf_file.= "\n";
				$bindconf_file_handler = fopen(makeCorrectFile($settings['system']['bindconf_directory'] . '/syscp_bind.conf'), 'w');
				fwrite($bindconf_file_handler, $bindconf_file);
				fclose($bindconf_file_handler);
				fwrite($debugHandler, '  cron_tasks: Task4 - syscp_bind.conf written' . "\n");
			}
		}

		if($settings['dkim']['use_dkim'] == '1')
		{
			$result_domains = $db->query("SELECT " . TABLE_PANEL_DOMAINS . ".`id` AS `pdid`, `domain`, " . TABLE_MAIL_DKIM . ".`id` AS `dkid` FROM `" . TABLE_PANEL_DOMAINS . "` LEFT JOIN `" . TABLE_MAIL_DKIM . "` ON (" . TABLE_MAIL_DKIM . ".`domain_id`=" . TABLE_PANEL_DOMAINS . ".`id`) WHERE " . TABLE_PANEL_DOMAINS . ".`dkim`='1'");

			while($domain = $db->fetch_array($result_domains))
			{
				if(isset($domain['domain'])
				   && $domain['domain'] != '')
				{
					$dkimdomains.= $domain['domain'] . "\n";
					$dkid = (int)$domain['dkid']+2000;
					$dkimkeyfile.= "*@" . $domain['domain'] . ":" . $domain['domain'] . ":" . $settings['dkim']['dkim_prefix'] . "dk" . $dkid . "\n";
				}
			}

			if(!file_exists($settings['dkim']['dkim_prefix'] . $settings['dkim']['dkim_domains']))
			{
				safe_exec("touch " . $settings['dkim']['dkim_prefix'] . $settings['dkim']['dkim_domains']);
			}
			if(!file_exists($settings['dkim']['dkim_prefix'] . $settings['dkim']['dkim_dkimkeys']))
			{
				safe_exec("touch " . $settings['dkim']['dkim_prefix'] . $settings['dkim']['dkim_dkimkeys']);
			}
			$dkimdomains_handler = fopen($settings['dkim']['dkim_prefix'] . $settings['dkim']['dkim_domains'], "w");
			fwrite($dkimdomains_handler, $dkimdomains);
			fclose($dkimdomains_handler);
			safe_exec("chmod 0640 " . $settings['dkim']['dkim_prefix'] . $settings['dkim']['dkim_domains']);
			$dkimkeyfile_handler = fopen($settings['dkim']['dkim_prefix'] . $settings['dkim']['dkim_dkimkeys'], "w");
			fwrite($dkimkeyfile_handler, $dkimkeyfile);
			fclose($dkimkeyfile_handler);
			safe_exec("chmod 0640 " . $settings['dkim']['dkim_prefix'] . $settings['dkim']['dkim_dkimkeys']);
			safe_exec($settings['dkim']['dkimrestart_command']);
			fwrite($debugHandler, '  cron_tasks: Task4 - DKIM reloaded' . "\n");
			$cronlog->logAction(CRON_ACTION, LOG_NOTICE, 'cron_tasks: Task4 - DKIM reloaded');
		}

		safe_exec($settings['system']['bindreload_command']);
		fwrite($debugHandler, '  cron_tasks: Task4 - Bind9 reloaded' . "\n");
		$cronlog->logAction(CRON_ACTION, LOG_NOTICE, 'cron_tasks: Task4 - Bind9 reloaded');
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
				   && file_exists(makeCorrectFile($domains_dir . '/' . $domain_filename)))
				{
					fwrite($debugHandler, '  cron_tasks: Task4 - unlinking ' . $domain_filename . "\n");
					unlink(makeCorrectFile($domains_dir . '/' . $domain_filename));
				}
			}
		}
	}

	/**
	 * TYPE=5 MEANS THAT A NEW FTP-ACCOUNT HAS BEEN CREATED, CREATE THE DIRECTORY
	 */
	elseif ($row['type'] == '5')
	{
		$cronlog->logAction(CRON_ACTION, LOG_NOTICE, 'cron_tasks: Task5 - Creating new ftp account');
		$result_directories = $db->query('SELECT `f`.`homedir`, `f`.`uid`, `f`.`gid`, `c`.`documentroot` AS `customerroot` FROM `' . TABLE_FTP_USERS . '` `f` LEFT JOIN `' . TABLE_PANEL_CUSTOMERS . '` `c` USING (`customerid`) ');

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

$db->query('UPDATE `' . TABLE_PANEL_SETTINGS . '` SET `value` = UNIX_TIMESTAMP() WHERE `settinggroup` = \'system\'   AND `varname`      = \'last_tasks_run\' ');

/**
 * STARTING CRONSCRIPT FOOTER
 */

include ($pathtophpfiles . '/lib/cron_shutdown.php');

/**
 * END CRONSCRIPT FOOTER
 */

?>
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

include (dirname(__FILE__) . '/../lib/cron_init.php');

/**
 * END REDUNDANT CODE (CRONSCRIPT "HEADER")
 */

/**
 * LOOK INTO TASKS TABLE TO SEE IF THERE ARE ANY UNDONE JOBS
 */

fwrite($debugHandler, '  cron_tasks: Searching for tasks to do' . "\n");
$cronlog->logAction(CRON_ACTION, LOG_INFO, "Searching for tasks to do");
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
		$cronlog->logAction(CRON_ACTION, LOG_INFO, "Task1 started - " . $settings['system']['apacheconf_vhost'] . " rebuild");
		$vhosts_file = '';

		if(isConfigDir($settings['system']['apacheconf_vhost'])
		   && !file_exists($settings['system']['apacheconf_vhost']))
		{
			safe_exec('mkdir ' . escapeshellarg(makeCorrectDir($settings['system']['apacheconf_vhost'])));
			$cronlog->logAction(CRON_ACTION, LOG_NOTICE, 'mkdir ' . escapeshellarg(makeCorrectDir($settings['system']['apacheconf_vhost'])));
		}

		/*
		 *	Write the ipandport conf for apache
		*/

		$known_vhost_filenames = array();
		$result_ipsandports = $db->query("SELECT `ip`, `port`, `listen_statement`, `namevirtualhost_statement`, `vhostcontainer` ,`vhostcontainer_servername_statement`, `specialsettings` FROM `" . TABLE_PANEL_IPSANDPORTS . "` ORDER BY `ip` ASC, `port` ASC");

		while($row_ipsandports = $db->fetch_array($result_ipsandports))
		{
			$vhosts_file = '';

			if($row_ipsandports['listen_statement'] == '1')
			{
				if(filter_var($row_ipsandports['ip'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4))
				{
					$vhosts_file.= 'Listen ' . $row_ipsandports['ip'] . ':' . $row_ipsandports['port'] . "\n";
				}
				elseif(filter_var($row_ipsandports['ip'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV6))
				{
					$vhosts_file.= 'Listen [' . $row_ipsandports['ip'] . ']:' . $row_ipsandports['port'] . "\n";
				}

				$cronlog->logAction(CRON_ACTION, LOG_NOTICE, 'inserted listen-statement');
			}

			if($row_ipsandports['namevirtualhost_statement'] == '1')
			{
				if(filter_var($row_ipsandports['ip'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4))
				{
					$vhosts_file.= 'NameVirtualHost ' . $row_ipsandports['ip'] . ':' . $row_ipsandports['port'] . "\n";
				}
				elseif(filter_var($row_ipsandports['ip'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV6))
				{
					$vhosts_file.= 'NameVirtualHost [' . $row_ipsandports['ip'] . ']:' . $row_ipsandports['port'] . "\n";
				}

				$cronlog->logAction(CRON_ACTION, LOG_NOTICE, 'inserted namevirtualhost-statement');
			}

			if($row_ipsandports['vhostcontainer'] == '1')
			{
				if(filter_var($row_ipsandports['ip'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4))
				{
					$vhosts_file.= '<VirtualHost ' . $row_ipsandports['ip'] . ':' . $row_ipsandports['port'] . '>' . "\n";
				}
				elseif(filter_var($row_ipsandports['ip'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV6))
				{
					$vhosts_file.= '<VirtualHost [' . $row_ipsandports['ip'] . ']:' . $row_ipsandports['port'] . '>' . "\n";
				}

				if($row_ipsandports['vhostcontainer_servername_statement'] == '1')
				{
					$vhosts_file.= ' ServerName ' . $settings['system']['hostname'] . "\n";
				}

				if($row_ipsandports['specialsettings'] != '')
				{
					$vhosts_file.= $row_ipsandports['specialsettings'] . "\n";
				}

				$vhosts_file.= '</VirtualHost>' . "\n";
				$cronlog->logAction(CRON_ACTION, LOG_NOTICE, 'inserted vhostcontainer');
			}

			if(isConfigDir($settings['system']['apacheconf_vhost']))
			{
				$vhosts_filename = makeCorrectFile($settings['system']['apacheconf_vhost'] . '/10_syscp_ipandport_' . $row_ipsandports['ip'] . '.' . $row_ipsandports['port'] . '.conf');
				$known_vhost_filenames[] = basename($vhosts_filename);

				// Apply header

				$vhosts_file = '# ' . $vhosts_filename . "\n" . '# Created ' . date('d.m.Y H:i') . "\n" . '# Do NOT manually edit this file, all changes will be deleted after the next domain change at the panel.' . "\n" . $vhosts_file;

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

		/*
                 *      Write the vhost confs for apache
                */
		// CHECK	$result_domains = $db->query("SELECT `d`.`id`, `d`.`domain`, `d`.`customerid`, `d`.`documentroot`, CONCAT(`ip`.`ip`,':',`ip`.`port`) AS `ipandport`, `d`.`parentdomainid`, `d`.`isemaildomain`, `d`.`iswildcarddomain`, `d`.`wwwserveralias`, `d`.`openbasedir`, `d`.`openbasedir_path`, `d`.`safemode`, `d`.`speciallogfile`, `d`.`specialsettings`, `pd`.`domain` AS `parentdomain`, `c`.`loginname`, `c`.`guid`, `c`.`email`, `c`.`documentroot` AS `customerroot`, `c`.`deactivated`, `c`.`phpenabled` AS `phpenabled` FROM `" . TABLE_PANEL_DOMAINS . "` `d` LEFT JOIN `" . TABLE_PANEL_CUSTOMERS . "` `c` USING(`customerid`) LEFT JOIN `" . TABLE_PANEL_DOMAINS . "` `pd` ON (`pd`.`id` = `d`.`parentdomainid`) LEFT JOIN `" . TABLE_PANEL_IPSANDPORTS . "` `ip` ON (`d`.`ipandport` = `ip`.`id`) WHERE `d`.`aliasdomain` IS NULL ORDER BY `d`.`iswildcarddomain`, `d`.`domain` ASC");

		$result_domains = $db->query("SELECT `d`.`id`, `d`.`domain`, `d`.`customerid`, `d`.`documentroot`, `d`.`ipandport`, `d`.`parentdomainid`, `d`.`isemaildomain`, `d`.`iswildcarddomain`, `d`.`wwwserveralias`,`d`.`openbasedir`, `d`.`openbasedir_path`, `d`.`safemode`, `d`.`speciallogfile`, `d`.`ssl`, `d`.`ssl_redirect`, `d`.`ssl_ipandport`,`d`.`specialsettings`, `pd`.`domain` AS `parentdomain`, `c`.`loginname`, `c`.`guid`, `c`.`email`, `c`.`documentroot` AS `customerroot`, `c`.`deactivated`, `c`.`phpenabled` AS `phpenabled` FROM `" . TABLE_PANEL_DOMAINS . "` `d` LEFT JOIN `" . TABLE_PANEL_CUSTOMERS . "` `c` USING(`customerid`) LEFT JOIN `" . TABLE_PANEL_DOMAINS . "` `pd` ON (`pd`.`id` = `d`.`parentdomainid`) LEFT JOIN `" . TABLE_PANEL_IPSANDPORTS . "` `ip` ON (`d`.`ipandport` = `ip`.`id`) WHERE `d`.`aliasdomain` IS NULL AND `d`.`email_only`='0' ORDER BY `d`.`iswildcarddomain`, `d`.`domain` ASC");

		while($domain = $db->fetch_array($result_domains))
		{
			if(!empty($domain['domain']))
			{
				fwrite($debugHandler, '  cron_tasks: Task1 - Writing Domain ' . $domain['id'] . '::' . $domain['domain'] . "\n");
				$vhosts_header = '# Domain ID: ' . $domain['id'] . ' - CustomerID: ' . $domain['customerid'] . ' - CustomerLogin: ' . $domain['loginname'] . "\n";
				$cronlog->logAction(CRON_ACTION, LOG_INFO, 'creating vhostcontainer for domain #' . $domain['id'] . ', Customer ' . $domain['loginname']);

				if($domain['deactivated'] == '1'
				   && $settings['system']['deactivateddocroot'] == '')
				{
					$vhosts_data.= '# Customer deactivated and a docroot for deactivated users hasn\'t been set.' . "\n";
				}
				elseif($domain['deactivated'] == '1'
				       && $settings['system']['deactivateddocroot'] != '')
				{
					$documentroot = '  # Using docroot for deactivated users...' . "\n";
					$documentroot.= '  DocumentRoot "' . $settings['system']['deactivateddocroot'] . "\"\n";
				}
				elseif(preg_match('/^https?\:\/\//', $domain['documentroot']))
				{
					$documentroot = '  Redirect / ' . $domain['documentroot'] . "\n";
				}
				else
				{
					$documentroot = '  DocumentRoot "' . $domain['documentroot'] . "\"\n";
				}

				if(isset($documentroot))
				{
					$vhosts_data = '';

					//create the documentroot

					$domain['customerroot'] = makeCorrectDir($domain['customerroot']);
					mkDirWithCorrectOwnership($domain['customerroot'], $domain['documentroot'], $domain['guid'], $domain['guid']);
					$ssl = $domain['ssl'];
					$ipandport = $domain['ipandport'];
					$adminemail = $domain['email'];
					$domain_name = $domain['domain'];
					$alias_domains = $db->query('SELECT `domain`, `iswildcarddomain` FROM `' . TABLE_PANEL_DOMAINS . '` WHERE `aliasdomain`=\'' . $domain['id'] . '\'');

					while(($alias_domain = $db->fetch_array($alias_domains)) !== false)
					{
						$server_alias.= ' ' . $alias_domain['domain'] . ' ' . (($alias_domain['iswildcarddomain'] == 1) ? '*' : 'www') . '.' . $alias_domain['domain'];
					}

					if($domain['iswildcarddomain'] == '1')
					{
						$alias = '*';
					}
				}

				$ipandport = $db->query_first('SELECT * FROM ' . TABLE_PANEL_IPSANDPORTS . ' WHERE `id`="' . $domain[ipandport] . '"');

				if(filter_var($ipandport['ip'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4))
				{
					$domain['ipandport'] = $ipandport['ip'] . ':' . $ipandport['port'];
				}
				elseif(filter_var($ipandport['ip'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV6))
				{
					$domain['ipandport'] = '[' . $ipandport['ip'] . ']:' . $ipandport['port'];
				}
				else
				{
					$alias = 'www';
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
							$php_openbasedir = '  ScriptAlias /php/ ' . $settings['system']['mod_fcgid_configdir'] . '/' . $domain['loginname'] . '/' . $domain['domain'] . '/' . "\n";
							$php_openbasedir.= '  SuexecUserGroup "' . $domain['loginname'] . '" "' . $domain['loginname'] . '"' . "\n";
							createFcgiConfig($domain, $settings);
						}
					}

					if($domain['iswildcarddomain'] == '1')
					{
						$alias = '*';
					}
					else
					{
						$alias = 'www';
					}

					if($domain['wwwserveralias'] == '1')
					{
						$alias = 'www';
					}
					else
					{
						$alias = '';
					}

					$adminemail = $domain['email'];

					if(preg_match('/^https?\:\/\//', $domain['documentroot']))
					{
						$redirect = '  Redirect / ' . $domain['documentroot'] . "\n";
					}
					else
					{
						$domain['customerroot'] = makeCorrectDir($domain['customerroot']);
						$domain['documentroot'] = makeCorrectDir($domain['documentroot']);
						$alias_domains = $db->query('SELECT `domain`, `iswildcarddomain` FROM `' . TABLE_PANEL_DOMAINS . '` WHERE `aliasdomain`=\'' . $domain['id'] . '\'');

						while(($alias_domain = $db->fetch_array($alias_domains)) !== false)

						// ...and we are using the regular mod_php

						if($domain['openbasedir'] == '1')
						{
							$server_alias.= ' ' . $alias_domain['domain'] . ' ' . (($alias_domain['iswildcarddomain'] == 1) ? '*' : 'www') . '.' . $alias_domain['domain'];

							if($settings['system']['phpappendopenbasedir'] != '')
							{
								$_phpappendopenbasedir = ':' . $settings['system']['phpappendopenbasedir'];
							}
						}

						if($domain['deactivated'] == '1'
						   && $settings['system']['deactivateddocroot'] != '')
						{
							$documentroot.= '  # Using docroot for deactivated users...' . "\n";
							$documentroot.= '  DocumentRoot "' . $settings['system']['deactivateddocroot'] . "\"\n";
						}
						else
						{
							$documentroot.= '  DocumentRoot "' . $domain['documentroot'] . "\"\n";
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
										$php_openbasedir.= '  php_admin_value open_basedir "' . $domain['customerroot'] . $_phpappendopenbasedir . "\"\n";
									}
									else
									{
										$php_openbasedir.= '  php_admin_value open_basedir "' . $domain['documentroot'] . $_phpappendopenbasedir . "\"\n";
									}
								}

								if($domain['safemode'] == '0')
								{
									$safe_mode.= '  php_admin_flag safe_mode Off ' . "\n";
								}
								else
								{
									$safe_mode.= '  php_admin_flag safe_mode On ' . "\n";
								}

								$_phpappendopenbasedir = '';
							}
						}

						mkDirWithCorrectOwnership($domain['customerroot'], $domain['documentroot'], $domain['guid'], $domain['guid']);

						if($settings['system']['mod_log_sql'] == 1)
						{
							// We are using mod_log_sql (http://www.outoforder.cc/projects/apache/mod_log_sql/)
							// TODO: See how we are able emulate the error_log

							$log_file.= '  LogSQLTransferLogTable access_log' . "\n";
						}
						else
						{
							// The normal access/error - logging is enabled

							$log_file.= '  ErrorLog "' . $settings['system']['logfiles_directory'] . $domain['loginname'] . $speciallogfile . '-error.log' . "\"\n";
							$log_file.= '  CustomLog "' . $settings['system']['logfiles_directory'] . $domain['loginname'] . $speciallogfile . '-access.log" combined' . "\n";
						}
					}

					if($domain['specialsettings'] != '')
					{
						$specialsettings.= $domain['specialsettings'] . "\n";
					}

					if($domain['openbasedir_path'] == '1')
					{
						$php_openbasedir = '  php_admin_value open_basedir "' . $domain['customerroot'] . $_phpappendopenbasedir . "\"\n";
					}
					else
					{
						$php_openbasedir = '  php_admin_value open_basedir "' . $domain['documentroot'] . $_phpappendopenbasedir . "\"\n";
					}

					if($domain['safemode'] == '0')
					{
						$safe_mode = '  php_admin_flag safe_mode Off ' . "\n";
					}
					else
					{
						$safe_mode = '  php_admin_flag safe_mode On ' . "\n";
					}
				}

				if($domain['speciallogfile'] == '1')
				{
					if($domain['parentdomainid'] == '0')
					{
						$speciallogfile = '-' . $domain['domain'];
						$alias_webalizer = '  Alias /webalizer "' . $domain['customerroot'] . '/webalizer/' . $domain['domain'] . "\"\n";
					}
					else
					{
						$speciallogfile = '-' . $domain['parentdomain'];
						$alias_webalizer = '  Alias /webalizer "' . $domain['customerroot'] . '/webalizer/' . $domain['parentdomain'] . "\"\n";
					}
				}

				if($domain['customerroot'] != $domain['documentroot'])
				{
					$alias_webalizer = '  Alias /webalizer "' . $domain['customerroot'] . '/webalizer"' . "\n";
				}

				if($settings['system']['mod_log_sql'] == 1)
				{
					// We are using mod_log_sql (http://www.outoforder.cc/projects/apache/mod_log_sql/)
					// TODO: See how we are able emulate the error_log

					$log_file = '  LogSQLTransferLogTable access_log' . "\n";
				}
				else
				{
					// The normal access/error - logging is enabled

					$log_file = '  ErrorLog "' . $settings['system']['logfiles_directory'] . $domain['loginname'] . $speciallogfile . '-error.log' . "\"\n";
					$log_file.= '  CustomLog "' . $settings['system']['logfiles_directory'] . $domain['loginname'] . $speciallogfile . '-access.log" combined' . "\n";
				}
			}

			$php_sendmail = '  php_admin_value sendmail_path "/usr/sbin/sendmail -t -f ' . $domain['email'] . '"' . "\n";

			if($domain['specialsettings'] != '')
			{
				$specialsettings = $domain['specialsettings'] . "\n";
			}

			$vhosts_data = '';
			$vhosts_data.= $vhosts_header . "\n";
			$vhosts_data.= '<VirtualHost ' . $ipandport . '>' . "\n";
			$vhosts_data.= '  ServerName ' . $domain_name . "\n";

			if($domain['wwwserveralias'] == '1')
			{
				$vhosts_data.= '  ServerAlias ' . $alias . '.' . $domain_name . $server_alias . "\n";
			}

			$vhosts_data.= '  ServerAdmin ' . $adminemail . "\n";
			$vhosts_data.= $documentroot;
			$vhosts_data.= $alias_webalizer;
			$vhosts_data.= $php_openbasedir;
			$vhosts_data.= $php_sendmail;
			$vhosts_data.= $safe_mode;
			$vhosts_data.= $specialsettings;
			$vhosts_data.= $log_file;
			$vhosts_data.= '</VirtualHost>' . "\n";

			if($domain['ssl'] == "1"
			   && $domain['ssl_ipandport'] != "0"
			   && $settings['system']['use_ssl'] == "1")
			{
				if(!is_file($settings['system']['ssl_cert_file'])
				   && isset($settings['system']['ssl_cert_file']))
				{
					$cert_filename = $settings['system']['ssl_cert_file'];

					if($settings['system']['openssl_cnf'] != ""
					   && isset($settings['system']['openssl_cnf']))
					{
						$openssl_file = $settings['system']['openssl_cnf'];
						$openssl_file = str_replace("@@domain_name@@", $settings['system']['hostname'], $openssl_file);
						$openssl_file = str_replace("@@email@@", $settings['panel']['adminmail'], $openssl_file);

						// Apply header

						$openssl_filename = dirname($settings['system']['ssl_cert_file']) . "/openssl.cnf";

						// Save partial file

						$openssl_file_handler = fopen($openssl_filename, 'w');
						fwrite($openssl_file_handler, $openssl_file);
						fclose($openssl_file_handler);

						// Reset vhosts_file

						$openssl_file = '';
						$cmd = "openssl req -config " . $openssl_filename . " -new -x509 -nodes -out " . $cert_filename . " -keyout " . $cert_filename . " -days 9999";
						$output = safe_exec($cmd);
						unlink($openssl_filename);

						// Enable SSL for apache
						//$output = shell_exec("a2enmod ssl");
						// Don't know how to do this. Probably leave it to the user.
					}
				}
				elseif(!isset($settings['system']['ssl_cert_file']))
				{
					standard_error("SSL standard cert not set!\n");
				}
				else
				{
					$ssl_ipandport = $db->query_first('SELECT `ip`, `port`, `ssl_cert` FROM ' . TABLE_PANEL_IPSANDPORTS . ' WHERE `id`="' . $domain['ssl_ipandport'] . '"');
					$ssl_cert = $ssl_ipandport['ssl_cert'];

					if(filter_var($ssl_ipandport['ip'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV6))
					{
						$ssl_ipandport = '[' . $ssl_ipandport['ip'] . ']:' . $ssl_ipandport['port'];
					}
					else
					{
						$ssl_ipandport = $ssl_ipandport['ip'] . ":" . $ssl_ipandport['port'];
					}

					if($domain['ssl_redirect'] == "1")
					{
						/*
						 * Let's overwrite the vhosts_data if it's only a redirect
						*/

						$vhosts_data = '';
						$vhosts_data = $vhosts_header . "\n";
						$vhosts_data.= '<VirtualHost ' . $ssl_ipandport . '>' . "\n";
						$vhosts_data.= '  Redirect permanent / https://' . $domain['name'] . '/ ' . "\n";
						$vhosts_data.= '  ServerName ' . $domain['name'] . "\n";
						$vhosts_data.= '  ServerAlias ' . $alias . '.' . $domain['name'] . $server_alias . "\n";
						$vhosts_data.= '  ServerAdmin ' . $adminemail . "\n";
						$vhosts_data.= $log_file;
						$vhosts_data.= '</VirtualHost>' . "\n";
					}

					$vhosts_ssl_data.= $vhosts_ssl_header . "\n";
					$vhosts_ssl_data.= '<VirtualHost ' . $ssl_ipandport . '>' . "\n";
					$vhosts_ssl_data.= '  ServerName ' . $domain_name . "\n";

					if($domain['wwwserveralias'] == '1')
					{
						$vhosts_ssl_data.= '  ServerAlias ' . $alias . '.' . $domain_name . $server_alias . "\n";
					}

					$vhosts_ssl_data.= '  ServerAdmin ' . $adminemail . "\n";
					$vhosts_ssl_data.= $documentroot;
					$vhosts_ssl_data.= $alias_webalizer;
					$vhosts_ssl_data.= $php_openbasedir;
					$vhosts_ssl_data.= $php_sendmail;
					$vhosts_ssl_data.= $safe_mode;
					$vhosts_ssl_data.= $specialsettings;
					$vhosts_ssl_data.= $log_file;
					$vhosts_ssl_data.= '  #SSL Stuff' . "\n";
					$vhosts_ssl_data.= '  SSLEngine On' . "\n";

					if(empty($ssl_cert))
					{
						$ssl_cert_file = $settings['system']['ssl_cert_file'];
					}
					else
					{
						$vhosts_file.= "\n";
						$ssl_cert_file = $ssl_cert;
					}

					$vhosts_ssl_data.= '  SSLCertificateFile ' . $ssl_cert_file . "\n";
					$vhosts_ssl_data.= '</VirtualHost>' . "\n";
					$vhosts_ssl_file.= $vhosts_ssl_data;
				}

				$vhosts_file.= $vhosts_data;

				if(isConfigDir($settings['system']['apacheconf_vhost']))
				{
					$vhosts_filename = makeCorrectFile($settings['system']['apacheconf_vhost'] . '/' . ($domain['iswildcarddomain'] == '1' ? '30_syscp_wildcard_vhost' : '20_syscp_normal_vhost') . '_' . $domain['domain'] . '.conf');
					$vhosts_ssl_filename = makeCorrectFile($settings['system']['apacheconf_vhost'] . '/' . ($domain['iswildcarddomain'] == '1' ? '30_syscp_wildcard_vhost' : '20_syscp_ssl_vhost') . '_' . $domain['domain'] . '.conf');
					$known_vhost_filenames[] = basename($vhosts_filename);
					$known_vhost_ssl_filenames[] = basename($vhosts_ssl_filename);

					// Apply header (normal vhost)

					$vhosts_file = '# ' . $vhosts_filename . "\n" . '# Created ' . date('d.m.Y H:i') . "\n" . '# Do NOT manually edit this file, all changes will be deleted after the next domain change at the panel.' . "\n" . "\n" . $vhosts_file;
					$vhosts_file_handler = fopen($vhosts_filename, 'w');
					fwrite($vhosts_file_handler, $vhosts_file);
					fclose($vhosts_file_handler);

					// Reset vhosts_file

					$vhosts_file = '';

					if($domain['ssl'] == "1"
					   && $domain['ssl_ipandport'] != "0")
					{
						// Apply header (ssl vhost)

						$vhosts_ssl_file = '# ' . $vhosts_filename . "\n" . '# Created ' . date('d.m.Y H:i') . "\n" . '# Do NOT manually edit this file, all changes will be deleted after the next domain change at the panel.' . "\n" . "\n" . $vhosts_ssl_file;

						// Save partial file

						$vhosts_ssl_file_handler = fopen($vhosts_ssl_filename, 'w');
						fwrite($vhosts_ssl_file_handler, $vhosts_ssl_file);
						fclose($vhosts_ssl_file_handler);

						// Reset vhosts_file

						$vhosts_ssl_file = '';
					}
				}
				else
				{
					$vhosts_file.= '# Customer deactivated and a docroot for deactivated users hasn\'t been set.' . "\n";
					$vhosts_file.= "\n";

					if($domain['ssl'] == "1"
					   && $domain['ssl_ipandport'] != "0")
					{
						$vhosts_ssl_file.= "\n";
					}
				}
			}
		}

		if(!isConfigDir($settings['system']['apacheconf_vhost']))
		{
			if(file_exists($settings['system']['apacheconf_diroptions']))
			{
				$vhosts_file.= 'Include ' . $settings['system']['apacheconf_diroptions'] . "\n\n";
			}

			$vhosts_filename = $settings['system']['apacheconf_vhost'];

			// Apply header

			$vhosts_file = '# ' . $vhosts_filename . "\n" . '# Created ' . date('d.m.Y H:i') . "\n" . '# Do NOT manually edit this file, all changes will be deleted after the next domain change at the panel.' . "\n" . "\n" . $vhosts_file;

			// Save big file

			$vhosts_file_handler = fopen($vhosts_filename, 'w');
			fwrite($vhosts_file_handler, $vhosts_file);
			fclose($vhosts_file_handler);
		}
		else
		{
			$vhosts_dir = makeCorrectDir($settings['system']['apacheconf_vhost']);

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
						fwrite($debugHandler, '  cron_tasks: Task1 - unlinking ' . $vhost_filename . "\n");
						unlink(makeCorrectFile($vhosts_dir . '/' . $vhost_filename));
					}
				}
			}
		}

		safe_exec($settings['system']['apachereload_command']);
		fwrite($debugHandler, '   cron_tasks: Task1 - Apache reloaded' . "\n");
	}

	/**
	 * TYPE=2 MEANS TO CREATE A NEW HOME AND CHOWN
	 */
	elseif ($row['type'] == '2')
	{
		fwrite($debugHandler, '  cron_tasks: Task2 started - create new home' . "\n");
		$cronlog->logAction(CRON_ACTION, LOG_INFO, 'Task2 started - create new home');

		if(is_array($row['data']))
		{
			$cronlog->logAction(CRON_ACTION, LOG_NOTICE, 'Running: mkdir -p ' . escapeshellarg($settings['system']['documentroot_prefix'] . $row['data']['loginname'] . '/webalizer'));
			safe_exec('mkdir -p ' . escapeshellarg($settings['system']['documentroot_prefix'] . $row['data']['loginname'] . '/webalizer'));
			$cronlog->logAction(CRON_ACTION, LOG_NOTICE, 'Running: mkdir -p ' . escapeshellarg($settings['system']['vmail_homedir'] . $row['data']['loginname']));
			safe_exec('mkdir -p ' . escapeshellarg($settings['system']['vmail_homedir'] . $row['data']['loginname']));
			$cronlog->logAction(CRON_ACTION, LOG_NOTICE, 'Running: cp -a ' . $pathtophpfiles . '/templates/misc/standardcustomer/* ' . escapeshellarg($settings['system']['documentroot_prefix'] . $row['data']['loginname'] . '/'));
			safe_exec('cp -a ' . $pathtophpfiles . '/templates/misc/standardcustomer/* ' . escapeshellarg($settings['system']['documentroot_prefix'] . $row['data']['loginname'] . '/'));
			$cronlog->logAction(CRON_ACTION, LOG_NOTICE, 'Running: chown -R ' . (int)$row['data']['uid'] . ':' . (int)$row['data']['gid'] . ' ' . escapeshellarg($settings['system']['documentroot_prefix'] . $row['data']['loginname']));
			safe_exec('chown -R ' . (int)$row['data']['uid'] . ':' . (int)$row['data']['gid'] . ' ' . escapeshellarg($settings['system']['documentroot_prefix'] . $row['data']['loginname']));
			$cronlog->logAction(CRON_ACTION, LOG_NOTICE, 'Running: chown -R ' . (int)$settings['system']['vmail_uid'] . ':' . (int)$settings['system']['vmail_gid'] . ' ' . escapeshellarg($settings['system']['vmail_homedir'] . $row['data']['loginname']));
			safe_exec('chown -R ' . (int)$settings['system']['vmail_uid'] . ':' . (int)$settings['system']['vmail_gid'] . ' ' . escapeshellarg($settings['system']['vmail_homedir'] . $row['data']['loginname']));
		}
	}

	/**
	 * TYPE=3 MEANS TO CREATE/CHANGE/DELETE A HTACCESS AND/OR HTPASSWD
	 */
	elseif ($row['type'] == '3')
	{
		fwrite($debugHandler, '  cron_tasks: Task3 started - create/change/del htaccess/htpasswd' . "\n");
		$cronlog->logAction(CRON_ACTION, LOG_INFO, 'Task3 started - create/change/del htaccess/htpasswd');
		$diroptions_file = '';

		if(isConfigDir($settings['system']['apacheconf_diroptions'])
		   && !file_exists($settings['system']['apacheconf_diroptions']))
		{
			$cronlog->logAction(CRON_ACTION, LOG_NOTICE, 'mkdir ' . escapeshellarg(makeCorrectDir($settings['system']['apacheconf_diroptions'])));
			safe_exec('mkdir ' . escapeshellarg(makeCorrectDir($settings['system']['apacheconf_diroptions'])));
		}

		if(!file_exists($settings['system']['apacheconf_htpasswddir']))
		{
			$umask = umask();
			umask(0000);
			mkdir($settings['system']['apacheconf_htpasswddir'], 0751);
			umask($umask);
		}
		elseif(!is_dir($settings['system']['apacheconf_htpasswddir']))
		{
			fwrite($debugHandler, '  cron_tasks: WARNING!!! ' . $settings['system']['apacheconf_htpasswddir'] . ' is not a directory. htpasswd directory protection is disabled!!!' . "\n");
			echo 'WARNING!!! ' . $settings['system']['apacheconf_htpasswddir'] . ' is not a directory. htpasswd directory protection is disabled!!!';
			$cronlog->logAction(CRON_ACTION, LOG_WARNING, 'WARNING!!! ' . $settings['system']['apacheconf_htpasswddir'] . ' is not a directory. htpasswd directory protection is disabled!!!');
		}

		$result = $db->query('SELECT `htac`.*, `c`.`guid`, `c`.`documentroot` AS `customerroot` FROM `' . TABLE_PANEL_HTACCESS . '` `htac` LEFT JOIN `' . TABLE_PANEL_CUSTOMERS . '` `c` USING (`customerid`) ORDER BY `htac`.`path`');
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

		$result = $db->query('SELECT `htpw`.*, `c`.`guid`, `c`.`documentroot` AS `customerroot` FROM `' . TABLE_PANEL_HTPASSWDS . '` `htpw` LEFT JOIN `' . TABLE_PANEL_CUSTOMERS . '` `c` USING (`customerid`) ORDER BY `htpw`.`path`, `htpw`.`username`');

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
							$htpasswd_filename = makeCorrectFile($settings['system']['apacheconf_htpasswddir'] . '/' . $row_diroptions['customerid'] . '-' . $row_htpasswd['id'] . '-' . md5($row_diroptions['path']) . '.htpasswd');
							$known_htpasswd_files[] = basename($htpasswd_filename);
						}

						$htpasswd_file.= $row_htpasswd['username'] . ':' . $row_htpasswd['password'] . "\n";
					}

					if(file_exists($settings['system']['apacheconf_htpasswddir'])
					   && is_dir($settings['system']['apacheconf_htpasswddir']))
					{
						fwrite($debugHandler, '  cron_tasks: Task3 - Setting Password' . "\n");
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

				if(isConfigDir($settings['system']['apacheconf_diroptions']))
				{
					$diroptions_filename = makeCorrectFile($settings['system']['apacheconf_diroptions'] . '/40_syscp_diroption_' . md5($row_diroptions['path']) . '.conf');
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

		if(!isConfigDir($settings['system']['apacheconf_diroptions']))
		{
			$diroptions_filename = $settings['system']['apacheconf_diroptions'];

			// Apply header

			$diroptions_file = '# ' . $diroptions_filename . "\n" . '# Created ' . date('d.m.Y H:i') . "\n" . '# Do NOT manually edit this file, all changes will be deleted after the next dir options change at the panel.' . "\n" . "\n" . $diroptions_file;

			// Save big file

			$diroptions_file_handler = fopen($diroptions_filename, 'w');
			fwrite($diroptions_file_handler, $diroptions_file);
			fclose($diroptions_file_handler);
		}
		else
		{
			$diroptions_dir = makeCorrectDir($settings['system']['apacheconf_diroptions']);

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
					   && file_exists(makeCorrectFile($settings['system']['apacheconf_diroptions'] . '/' . $diroptions_filename)))
					{
						$cronlog->logAction(CRON_ACTION, LOG_WARNING, 'Deleting ' . makeCorrectFile($settings['system']['apacheconf_diroptions'] . '/' . $diroptions_filename));
						unlink(makeCorrectFile($settings['system']['apacheconf_diroptions'] . '/' . $diroptions_filename));
					}
				}
			}
		}

		$cronlog->logAction(CRON_ACTION, LOG_INFO, 'Reloading apache');
		safe_exec($settings['system']['apachereload_command']);
		$htpasswd_dir = makeCorrectDir($settings['system']['apacheconf_htpasswddir']);

		if(file_exists($htpasswd_dir)
		   && is_dir($htpasswd_dir))
		{
			$htpasswd_file_dirhandle = opendir($htpasswd_dir);

			while(false !== ($htpasswd_filename = readdir($htpasswd_file_dirhandle)))
			{
				if($htpasswd_filename != '.'
				   && $htpasswd_filename != '..'
				   && !in_array($htpasswd_filename, $known_htpasswd_files)
				   && file_exists(makeCorrectFile($settings['system']['apacheconf_htpasswddir'] . '/' . $htpasswd_filename)))
				{
					$cronlog->logAction(CRON_ACTION, LOG_WARNING, 'Deleting ' . makeCorrectFile($settings['system']['apacheconf_htpasswddir'] . '/' . $htpasswd_filename));
					unlink(makeCorrectFile($settings['system']['apacheconf_htpasswddir'] . '/' . $htpasswd_filename));
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
		$cronlog->logAction(CRON_ACTION, LOG_INFO, 'Task4 started - Rebuilding syscp_bind.conf');

		if(!file_exists($settings['system']['bindconf_directory'] . 'domains/'))
		{
			$cronlog->logAction(CRON_ACTION, LOG_NOTICE, 'mkdir ' . escapeshellarg(makeCorrectDir($settings['system']['bindconf_directory'] . '/domains/')));
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
			$zonefile = '';
			fwrite($debugHandler, '  cron_tasks: Task4 - Writing ' . $domain['id'] . '::' . $domain['domain'] . "\n");
			$cronlog->logAction(CRON_ACTION, LOG_INFO, 'Writing ' . $domain['id'] . '::' . $domain['domain']);

			if(!empty($domain['domain']))
			{
				if($domain['zonefile'] == '')
				{
					$date = date('Ymd');
					$bindserial = (preg_match('/^' . $date . '/', $domain['bindserial']) ? $domain['bindserial']+1 : $date . '00');
					$db->query('UPDATE `' . TABLE_PANEL_DOMAINS . '` SET `bindserial`=\'' . $bindserial . '\' WHERE `id`=\'' . $domain['id'] . '\'');
					$zonefile = '$TTL 200' . "\n";

					if(count($nameservers) == 0)
					{
						$zonefile.= '@ IN SOA ns ' . str_replace('@', '.', $settings['panel']['adminmail']) . '. (' . "\n";
					}
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
						$zonefile.= '@	IN	NS      ns' . "\n" . 'ns	IN	A	' . $domain['ip'] . "\n";
					}
					elseif(filter_var($domain['ip'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV6))
					{
						$zonefile.= '@	IN	NS      ns' . "\n" . 'ns	IN	AAAA	' . $domain['ip'] . "\n";
					}
				}
				else
				{
					foreach($nameservers as $nameserver)
					{
						$zonefile.= '@ IN SOA ns ' . str_replace('@', '.', $settings['panel']['adminmail']) . '. (' . "\n";
					}
				}

				if(filter_var($domain['ip'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4))
				{
					$zonefile.= $domain[domain] . '.  IN      TXT     "v=spf1 ip4:' . $domain[ip] . ' -all"';
				}
				elseif(filter_var($domain['ip'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV6))
				{
					$zonefile.= $domain[domain] . '.  IN      TXT     "v=spf1 ip6:' . $domain[ip] . ' -all"';
				}

				if(count($mxservers) == 0)
				{
					if(filter_var($domain['ip'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4))
					{
						$zonefile.= '@	IN	MX	10 mail' . "\n" . 'mail	IN	A	' . $domain['ip'] . "\n";
					}
					elseif(filter_var($domain['ip'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV6))
					{
						$zonefile.= '@	IN	MX	10 mail' . "\n" . 'mail	IN	AAAA	' . $domain['ip'] . "\n";
					}
				}
				else
				{
					foreach($mxservers as $mxserver)
					{
						$zonefile.= '@ IN SOA ' . $nameservers[0]['hostname'] . ' ' . str_replace('@', '.', $settings['panel']['adminmail']) . '. (' . "\n";
					}

					$zonefile.= '	' . $bindserial . ' ; serial' . "\n" . '	8H ; refresh' . "\n" . '	2H ; retry' . "\n" . '	1W ; expiry' . "\n" . '	11h) ; minimum' . "\n";
				}

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
						$zonefile.= '*	IN	A	' . $domain['ip'] . "\n";
					}
					elseif(filter_var($domain['ip'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV6))
					{
						$zonefile.= '*	IN	AAAA	' . $domain['ip'] . "\n";
					}
				}

				$bindconf_file.= '# Domain ID: ' . $domain['id'] . ' - CustomerID: ' . $domain['customerid'] . ' - CustomerLogin: ' . $domain['loginname'] . "\n";
				$bindconf_file.= 'zone "' . $domain['domain'] . '" in {' . "\n";
				$bindconf_file.= '	type master;' . "\n";

				if(empty($domain['zonefile']))
				{
					$bindconf_file.= '	file "' . $settings['system']['bindconf_directory'] . "/domains/" . $domain['domain'] . '";' . "\n";
				}
				else
				{
					$bindconf_file.= '	file "' . $domain['zonefile'] . '";' . "\n";
				}

				$bindconf_file.= '	allow-query { any; };';

				if(count($nameservers) > 0)
				{
					if(filter_var($subdomain['ip'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4))
					{
						$zonefile.= str_replace('.' . $domain['domain'], '', $subdomain['domain']) . '	IN	A	' . $subdomain['ip'] . "\n";
					}
					elseif(filter_var($subdomain['ip'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV6))
					{
						$zonefile.= str_replace('.' . $domain['domain'], '', $subdomain['domain']) . '	IN	AAAA	' . $subdomain['ip'] . "\n";
					}
				}

				#				$bindconf_file.= '};' . "\n";

				$bindconf_file.= "\n";
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

				$bindconf_file.= '	};' . "\n";
				$bindconf_file.= "\n";
			}

			file_put_contents($settings['system']['bindconf_directory'] . 'domains/' . $domain['domain'], $zonefile);
			$known_domain_zonefiles[] = $domain['domain'];
			fwrite($debugHandler, '  cron_tasks: Task4 - ' . $domain['domain'] . ' domain file written' . "\n");
			$cronlog->logAction(CRON_ACTION, LOG_INFO, $domain['domain'] . 'domain file written');
			$bindconf_file.= '};' . "\n";
			$bindconf_file.= "\n";
		}

		file_put_contents($settings['system']['bindconf_directory'] . '/syscp_bind.conf', $bindconf_file);
		$cronlog->logAction(CRON_ACTION, LOG_INFO, 'syscp_bind.conf written');

		if(is_file(substr($settings['system']['bindreload_command'], 0, strpos($settings['system']['bindreload_command'], ' '))))
		{
			safe_exec($settings['system']['bindreload_command']);
			fwrite($debugHandler, '  cron_tasks: Task4 - Bind9 reloaded' . "\n");
		}

		$cronlog->logAction(CRON_ACTION, LOG_INFO, 'Bind9 reloaded');
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
					$cronlog->logAction(CRON_ACTION, LOG_WARNING, 'Deleting ' . $domain_filename);
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
		$cronlog->logAction(CRON_ACTION, LOG_INFO, 'Creating new FTP-home');
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

	#	$db->query('DELETE FROM `' . TABLE_PANEL_TASKS . '` WHERE ' . $where);

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

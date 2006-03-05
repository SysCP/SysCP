<?php
/**
 * filename: $Source$
 * begin: Wednesday, Sep 08, 2004
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version. This program is distributed in the
 * hope that it will be useful, but WITHOUT ANY WARRANTY; without even the
 * implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU General Public License for more details.
 *
 * @author Florian Lippert <flo@redenswert.de>
 * @copyright (C) 2003-2004 Florian Lippert
 * @package Panel
 * @version $Id$
 */

	define('AREA', 'admin');

	/**
	 * Include our init.php, which manages Sessions, Language etc.
	 */
	require("./lib/init.php");


	$configfiles = Array
	(
		'debian_woody' => Array
		(
			'label' => 'Debian 3.0 (Woody)',
			'daemons' => Array
			(
				'apache' => Array
				(
					'label' => 'Apache Webserver (HTTP)',
					'commands' => Array
					(
						'echo -e "\\nInclude '.$settings['system']['apacheconf_directory'].'vhosts.conf" >> '.$settings['system']['apacheconf_directory'].'httpd.conf',
						'touch '.$settings['system']['apacheconf_directory'].'vhosts.conf',
						'mkdir -p '.$settings['system']['documentroot_prefix'],
						'mkdir -p '.$settings['system']['logfiles_directory']
					),
					'restart' => Array
					(
						'/etc/init.d/apache restart'
					)
				),
				'bind' => Array
				(
					'label' => 'Bind9 Nameserver (DNS)',
					'files' => Array
					(
						'etc_bind_default.zone' => '/etc/bind/default.zone'
					),
					'commands' => Array
					(
						'echo "include \"'.$settings['system']['bindconf_directory'].'syscp_bind.conf\";" >> /etc/bind/named.conf',
						'touch '.$settings['system']['bindconf_directory'].'syscp_bind.conf'
					),
					'restart' => Array
					(
						'/etc/init.d/bind9 restart'
					)
				),
				'courier' => Array
				(
					'label' => 'Courier (POP3/IMAP)',
					'files' => Array
					(
						'etc_courier_authdaemonrc' => '/etc/courier/authdaemonrc',
						'etc_courier_authmysqlrc' => '/etc/courier/authmysqlrc'
					),
					'restart' => Array
					(
						'/etc/init.d/courier-authdaemon restart',
						'/etc/init.d/courier-pop restart'
					)
				),
				'postfix' => Array
				(
					'label' => 'Postfix (MTA)',
					'files' => Array
					(
						'etc_postfix_main.cf' => '/etc/postfix/main.cf',
						'etc_postfix_mysql-transport.cf' => '/etc/postfix/mysql-transport.cf',
						'etc_postfix_mysql-virtual.cf' => '/etc/postfix/mysql-virtual.cf',
						'etc_postfix_mysql-virtual-maps.cf' => '/etc/postfix/mysql-virtual-maps.cf',
						'etc_postfix_sasl_smtpd.conf' => '/etc/postfix/sasl/smtpd.conf',
						'var_spool_postfix_etc_pam.d_smtp' => '/var/spool/postfix/etc/pam.d/smtp',
						'etc_init.d_postfix' => '/etc/init.d/postfix'
					),
					'commands' => Array
					(
						'mkdir -p /etc/postfix/sasl',
						'mkdir -p /var/spool/postfix/etc/pam.d',
						'mkdir -p /var/spool/postfix/lib/security',
						'mkdir -p /var/spool/postfix/var/run/mysqld',
						'cp /lib/security/pam_mysql.so /var/spool/postfix/lib/security/',
						'groupadd -g '.$settings['system']['vmail_gid'].' vmail',
						'useradd -u '.$settings['system']['vmail_uid'].' -g vmail vmail',
						'mkdir -p '.$settings['system']['vmail_homedir'],
						'chown -R vmail:vmail '.$settings['system']['vmail_homedir']
					),
					'restart' => Array
					(
						'/etc/init.d/postfix restart'
					)
				),
				'proftpd' => Array
				(
					'label' => 'ProFTPd (FTP)',
					'files' => Array
					(
						'etc_proftpd.conf' => '/etc/proftpd.conf'
					),
					'restart' => Array
					(
						'/etc/init.d/proftpd restart'
					)
				),
				'cron' => Array
				(
					'label' => 'Crond (cronscript)',
					'files' => Array
					(
						'etc_php4_syscpcron_php.ini' => '/etc/php4/syscpcron/php.ini',
						'etc_cron.d_syscp' => '/etc/cron.d/syscp'
					),
					'restart' => Array
					(
						'/etc/init.d/cron restart'
					)
				)
			)
		),
		'debian_sarge' => Array
		(
			'label' => 'Debian 3.1 (Sarge)',
			'daemons' => Array
			(
				'apache' => Array
				(
					'label' => 'Apache Webserver (HTTP)',
					'commands' => Array
					(
						'echo -e "\\nInclude '.$settings['system']['apacheconf_directory'].'vhosts.conf" >> '.$settings['system']['apacheconf_directory'].'httpd.conf',
						'touch '.$settings['system']['apacheconf_directory'].'vhosts.conf',
						'mkdir -p '.$settings['system']['documentroot_prefix'],
						'mkdir -p '.$settings['system']['logfiles_directory']
					),
					'restart' => Array
					(
						'/etc/init.d/apache restart'
					)
				),
				'bind' => Array
				(
					'label' => 'Bind9 Nameserver (DNS)',
					'files' => Array
					(
						'etc_bind_default.zone' => '/etc/bind/default.zone'
					),
					'commands' => Array
					(
						'echo "include \"'.$settings['system']['bindconf_directory'].'syscp_bind.conf\";" >> /etc/bind/named.conf',
						'touch '.$settings['system']['bindconf_directory'].'syscp_bind.conf'
					),
					'restart' => Array
					(
						'/etc/init.d/bind9 restart'
					)
				),
				'courier' => Array
				(
					'label' => 'Courier (POP3/IMAP)',
					'files' => Array
					(
						'etc_courier_authdaemonrc' => '/etc/courier/authdaemonrc',
						'etc_courier_authmysqlrc' => '/etc/courier/authmysqlrc'
					),
					'restart' => Array
					(
						'/etc/init.d/courier-authdaemon restart',
						'/etc/init.d/courier-pop restart'
					)
				),
				'postfix' => Array
				(
					'label' => 'Postfix (MTA)',
					'files' => Array
					(
						'etc_postfix_main.cf' => '/etc/postfix/main.cf',
						'etc_postfix_mysql-virtual_alias_maps.cf' => '/etc/postfix/mysql-virtual_alias_maps.cf',
						'etc_postfix_mysql-virtual_mailbox_domains.cf' => '/etc/postfix/mysql-virtual_mailbox_domains.cf',
						'etc_postfix_mysql-virtual_mailbox_maps.cf' => '/etc/postfix/mysql-virtual_mailbox_maps.cf',
						'etc_postfix_sasl_smtpd.conf' => '/etc/postfix/sasl/smtpd.conf',
						'etc_mysql_debian-start' => '/etc/mysql/debian-start'
					),
					'commands' => Array
					(
						'mkdir -p /etc/postfix/sasl',
						'mkdir -p /var/spool/postfix/etc/pam.d',
						'mkdir -p /var/spool/postfix/var/run/mysqld',
						'groupadd -g '.$settings['system']['vmail_gid'].' vmail',
						'useradd -u '.$settings['system']['vmail_uid'].' -g vmail vmail',
						'mkdir -p '.$settings['system']['vmail_homedir'],
						'chown -R vmail:vmail '.$settings['system']['vmail_homedir']
					),
					'restart' => Array
					(
						'/etc/init.d/postfix restart'
					)
				),
				'proftpd' => Array
				(
					'label' => 'ProFTPd (FTP)',
					'files' => Array
					(
						'etc_proftpd.conf' => '/etc/proftpd.conf'
					),
					'restart' => Array
					(
						'/etc/init.d/proftpd restart'
					)
				),
				'cron' => Array
				(
					'label' => 'Crond (cronscript)',
					'files' => Array
					(
						'etc_php4_syscpcron_php.ini' => '/etc/php4/syscpcron/php.ini',
						'etc_cron.d_syscp' => '/etc/cron.d/syscp'
					),
					'restart' => Array
					(
						'/etc/init.d/cron restart'
					)
				)
			)
		)
	);

	/*echo '<pre>';
	print_r($configfiles);
	echo '</pre>';*/

	if( ($page == 'configfiles' || $page == 'overview') && $userinfo['change_serversettings'] == '1')
	{
		if(isset($_GET['distribution']) && $_GET['distribution']!='' && isset($configfiles[$_GET['distribution']]) && is_array($configfiles[$_GET['distribution']]) &&
		   isset($_GET['daemon']) && $_GET['daemon']!='' && isset($configfiles[$_GET['distribution']]['daemons'][$_GET['daemon']]) && is_array($configfiles[$_GET['distribution']]['daemons'][$_GET['daemon']]))
		{
			$distribution = addslashes($_GET['distribution']);
			$daemon = addslashes($_GET['daemon']);

			if(isset($configfiles[$distribution]['daemons'][$daemon]['commands']) && is_array($configfiles[$distribution]['daemons'][$daemon]['commands']))
			{
				$commands = implode("\n", $configfiles[$distribution]['daemons'][$daemon]['commands']);
			}
			else
			{
				$commands = '';
			}

			$replace_arr = Array
			(
				'<SQL_UNPRIVILEGED_USER>' => $sql['user'],
				'<SQL_UNPRIVILEGED_PASSWORD>' => 'MYSQL_PASSWORD',
				'<SQL_DB>' => $sql['db'], 
				'<SQL_HOST>' => $sql['host'],
				'<SERVERNAME>' => $settings['system']['hostname'],
				'<SERVERIP>' => $settings['system']['ipaddress'],
				'<VIRTUAL_MAILBOX_BASE>' => $settings['system']['vmail_homedir'],
				'<VIRTUAL_UID_MAPS>' => $settings['system']['vmail_uid'],
				'<VIRTUAL_GID_MAPS>' => $settings['system']['vmail_gid']
			);
			$files = '';
			if(isset($configfiles[$distribution]['daemons'][$daemon]['files']) && is_array($configfiles[$distribution]['daemons'][$daemon]['files']))
			{
				while(list($filename, $realname) = each($configfiles[$distribution]['daemons'][$daemon]['files']))
				{
					$file_content = implode('', file('./templates/misc/configfiles/'.$distribution.'/'.$daemon.'/'.$filename));
					$file_content = strtr($file_content, $replace_arr);
					$file_content = htmlspecialchars($file_content);
					$numbrows = count(explode("\n", $file_content));
					eval("\$files.=\"".getTemplate("configfiles/configfiles_file")."\";");
				}
			}

			if(isset($configfiles[$distribution]['daemons'][$daemon]['restart']) && is_array($configfiles[$distribution]['daemons'][$daemon]['restart']))
			{
				$restart = implode("\n", $configfiles[$distribution]['daemons'][$daemon]['restart']);
			}
			else
			{
				$restart = '';
			}

			eval("echo \"".getTemplate("configfiles/configfiles")."\";");
		}
		else
		{
			$distributions = '';
			while (list($distribution_name, $distribution_details) = each($configfiles))
			{
				$daemons = '';
				while(list($daemon_name, $daemon_details) = each($distribution_details['daemons']))
				{
					eval("\$daemons.=\"".getTemplate("configfiles/choose_daemon")."\";");
				}
				eval("\$distributions.=\"".getTemplate("configfiles/choose_distribution")."\";");
			}
			eval("echo \"".getTemplate("configfiles/choose")."\";");
		}
	}

?>

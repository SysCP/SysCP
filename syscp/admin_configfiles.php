<?php
/**
 * This file is part of the SysCP project.
 * Copyright (c) 2003-2006 the SysCP Team (see authors).
 *
 * For the full copyright and license information, please view the COPYING
 * file that was distributed with this source code. You can also view the
 * COPYING file online at http://files.syscp.org/misc/COPYING.txt
 *
 * @copyright  (c) the authors
 * @author     Florian Lippert <flo@syscp.org>
 * @license    GPLv2 http://files.syscp.org/misc/COPYING.txt
 * @package    Panel
 * @version    $Id$
 */

	define('AREA', 'admin');

	/**
	 * Include our init.php, which manages Sessions, Language etc.
	 */
	require("./lib/init.php");


	$configfiles = Array
	(
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
			$distribution = $_GET['distribution'];
			$daemon = $_GET['daemon'];

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
					$file_content = file_get_contents('./templates/misc/configfiles/'.$distribution.'/'.$daemon.'/'.$filename);
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

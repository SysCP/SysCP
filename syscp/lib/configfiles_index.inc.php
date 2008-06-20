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
 * @license    GPLv2 http://files.syscp.org/misc/COPYING.txt
 * @package    Panel
 * @version    $Id$
 */

$configfiles = Array(
	'debian_sarge' => Array(
		'label' => 'Debian 3.1 (Sarge)',
		'services' => Array(
			'http' => Array(
				'label' => $lng['admin']['configfiles']['http'],
				'daemons' => Array(
					'apache' => Array(
						'label' => 'Apache' . ($settings['system']['apacheversion'] == 'apache2' ? '2' : ''),
						'commands' => Array(
							'touch ' . $settings['system']['apacheconf_vhost'],
							'touch ' . $settings['system']['apacheconf_diroptions'],
							'mkdir -p ' . $settings['system']['documentroot_prefix'],
							'mkdir -p ' . $settings['system']['logfiles_directory'],
							'echo -e "\\nInclude ' . $settings['system']['apacheconf_vhost'] . '" >> ' . makeCorrectFile(dirname($settings['system']['apacheconf_vhost']) . '/httpd.conf'),
							'apache-modconf apache disable mod_userdir'
						),
						'restart' => Array(
							'/etc/init.d/apache' . ($settings['system']['apacheversion'] == 'apache2' ? '2' : '') . ' restart'
						)
					)
				)
			),
			'dns' => Array(
				'label' => $lng['admin']['configfiles']['dns'],
				'daemons' => Array(
					'bind' => Array(
						'label' => 'Bind9',
						'commands' => Array(
							'echo "include \"' . $settings['system']['bindconf_directory'] . 'syscp_bind.conf\";" >> /etc/bind/named.conf',
							'touch ' . $settings['system']['bindconf_directory'] . 'syscp_bind.conf'
						),
						'restart' => Array(
							'/etc/init.d/bind9 restart'
						)
					),
				)
			),
			'mail' => Array(
				'label' => $lng['admin']['configfiles']['mail'],
				'daemons' => Array(
					'courier' => Array(
						'label' => 'Courier',
						'files' => Array(
							'etc_courier_authdaemonrc' => '/etc/courier/authdaemonrc',
							'etc_courier_authmysqlrc' => '/etc/courier/authmysqlrc'
						),
						'restart' => Array(
							'/etc/init.d/courier-authdaemon restart',
							'/etc/init.d/courier-pop restart'
						)
					),
				)
			),
			'smtp' => Array(
				'label' => $lng['admin']['configfiles']['smtp'],
				'daemons' => Array(
					'postfix' => Array(
						'label' => 'Postfix',
						'files' => Array(
							'etc_postfix_main.cf' => '/etc/postfix/main.cf',
							'etc_postfix_mysql-virtual_alias_maps.cf' => '/etc/postfix/mysql-virtual_alias_maps.cf',
							'etc_postfix_mysql-virtual_mailbox_domains.cf' => '/etc/postfix/mysql-virtual_mailbox_domains.cf',
							'etc_postfix_mysql-virtual_mailbox_maps.cf' => '/etc/postfix/mysql-virtual_mailbox_maps.cf',
							'etc_postfix_sasl_smtpd.conf' => '/etc/postfix/sasl/smtpd.conf'
						),
						'commands' => Array(
							'mkdir -p /etc/postfix/sasl',
							'mkdir -p /var/spool/postfix/etc/pam.d',
							'mkdir -p /var/spool/postfix/var/run/mysqld',
							'groupadd -g ' . $settings['system']['vmail_gid'] . ' vmail',
							'useradd -u ' . $settings['system']['vmail_uid'] . ' -g vmail vmail',
							'mkdir -p ' . $settings['system']['vmail_homedir'],
							'chown -R vmail:vmail ' . $settings['system']['vmail_homedir'],
							'touch /etc/postfix/mysql-virtual_alias_maps.cf',
							'touch /etc/postfix/mysql-virtual_mailbox_domains.cf',
							'touch /etc/postfix/mysql-virtual_mailbox_maps.cf',
							'touch /etc/postfix/sasl/smtpd.conf',
							'chmod 660 /etc/postfix/mysql-virtual_alias_maps.cf',
							'chmod 660 /etc/postfix/mysql-virtual_mailbox_domains.cf',
							'chmod 660 /etc/postfix/mysql-virtual_mailbox_maps.cf',
							'chmod 660 /etc/postfix/sasl/smtpd.conf',
							'chgrp postfix /etc/postfix/mysql-virtual_alias_maps.cf',
							'chgrp postfix /etc/postfix/mysql-virtual_mailbox_domains.cf',
							'chgrp postfix /etc/postfix/mysql-virtual_mailbox_maps.cf',
							'chgrp postfix /etc/postfix/sasl/smtpd.conf'
						),
						'restart' => Array(
							'/etc/init.d/postfix restart',
							'newaliases'
						)
					),
					'exim4' => Array(
						'label' => 'Exim4',
						'commands_1' => Array(
							'dpkg-reconfigure exim4-config',
							'# choose "no configuration at this time" and "splitted configuration files" in the dialog'
						),
						'files' => Array(
							'etc_exim4_conf.d_acl_30_exim4-config_check_rcpt.rul' => '/etc/exim4/conf.d/acl/30_exim4-config_check_rcpt.rul',
							'etc_exim4_conf.d_auth_30_syscp-config' => '/etc/exim4/conf.d/auth/30_syscp-config',
							'etc_exim4_conf.d_main_10_syscp-config_options' => '/etc/exim4/conf.d/main/10_syscp-config_options',
							'etc_exim4_conf.d_router_180_syscp-config' => '/etc/exim4/conf.d/router/180_syscp-config',
							'etc_exim4_conf.d_transport_30_syscp-config' => '/etc/exim4/conf.d/transport/30_syscp-config'
						),
						'commands_2' => Array(
							'chmod o-rx /var/lib/exim4',
							'chmod o-rx /etc/exim4/conf.d/main/10_syscp-config_options'
						),
						'restart' => Array(
							'/etc/init.d/exim4 restart'
						)
					),
					'dkim' => Array(
						'label' => 'DomainKey filter',
						'commands_1' => Array(
							'mkdir -p /etc/postfix/dkim'
						),
						'files' => Array(
							'dkim-filter.conf' => '/etc/postfix/dkim/dkim-filter.conf'
						),
						'commands_2' => Array(
							'chgrp postfix /etc/postfix/dkim/dkim-filter.conf',
							'echo "smtpd_milters = inet:localhost:8891\n
milter_macro_daemon_name = SIGNING\n
milter_default_action = accept\n" >> /etc/postfix/main.cf'
						),
						'restart' => Array(
							'/etc/init.d/dkim-filter restart'
						)
					)
				)
			),
			'ftp' => Array(
				'label' => $lng['admin']['configfiles']['ftp'],
				'daemons' => Array(
					'proftpd' => Array(
						'label' => 'ProFTPd',
						'files' => Array(
							'etc_proftpd.conf' => '/etc/proftpd.conf'
						),
						'restart' => Array(
							'/etc/init.d/proftpd restart'
						)
					),
				)
			),
			'etc' => Array(
				'label' => $lng['admin']['configfiles']['etc'],
				'daemons' => Array(
					'cron' => Array(
						'label' => 'Crond (cronscript)',
						'files' => Array(
							'etc_cron.d_syscp' => '/etc/cron.d/syscp'
						),
						'restart' => Array(
							'/etc/init.d/cron restart'
						)
					),
					'awstats' => Array(
						'label' => 'Awstats',
						'files' => Array(
							'etc_awstats_awstats.model.conf.syscp' => '/etc/awstats/awstats.model.conf.syscp',
							'etc_apache_vhosts_05_awstats.conf' => '/etc/apache' . ($settings['system']['apacheversion'] == 'apache2' ? '2' : '') . '/sites-enabled/05_awstats.conf'
						),
						'restart' => Array(
							'/etc/init.d/apache' . ($settings['system']['apacheversion'] == 'apache2' ? '2' : '') . ' restart'
						)
					)
				)
			)
		)
	),
	'debian_etch' => Array(
		'label' => 'Debian 4.0 (Etch)',
		'services' => Array(
			'http' => Array(
				'label' => $lng['admin']['configfiles']['http'],
				'daemons' => Array(
					'apache' => Array(
						'label' => 'Apache',
						'commands' => Array(
							'touch ' . $settings['system']['apacheconf_vhost'],
							'touch ' . $settings['system']['apacheconf_diroptions'],
							'mkdir -p ' . $settings['system']['documentroot_prefix'],
							'mkdir -p ' . $settings['system']['logfiles_directory'],
							'echo -e "\\nInclude ' . $settings['system']['apacheconf_vhost'] . '" >> ' . makeCorrectFile(dirname($settings['system']['apacheconf_vhost']) . '/httpd.conf'),
							'apache-modconf apache disable mod_userdir'
						),
						'restart' => Array(
							'/etc/init.d/apache restart'
						)
					),
					'apache2' => Array(
						'label' => 'Apache 2',
						'commands' => Array(
							'mkdir -p ' . $settings['system']['documentroot_prefix'],
							'mkdir -p ' . $settings['system']['logfiles_directory'],
							'a2dismod userdir'
						),
						'restart' => Array(
							'/etc/init.d/apache2 restart'
						)
					)
				)
			),
			'dns' => Array(
				'label' => $lng['admin']['configfiles']['dns'],
				'daemons' => Array(
					'bind' => Array(
						'label' => 'Bind9',
						'commands' => Array(
							'echo "include \"' . $settings['system']['bindconf_directory'] . 'syscp_bind.conf\";" >> /etc/bind/named.conf',
							'touch ' . $settings['system']['bindconf_directory'] . 'syscp_bind.conf'
						),
						'restart' => Array(
							'/etc/init.d/bind9 restart'
						)
					),
					'powerdns' => Array(
						'label' => 'PowerDNS',
						'files' => Array(
							'etc_powerdns_pdns.conf' => '/etc/powerdns/pdns.conf',
							'etc_powerdns_pdns-syscp.conf' => '/etc/powerdns/pdns_syscp.conf',
						),
						'restart' => Array(
							'/etc/init.d/pdns restart'
						)
					),
				)
			),
			'mail' => Array(
				'label' => $lng['admin']['configfiles']['mail'],
				'daemons' => Array(
					'courier' => Array(
						'label' => 'Courier',
						'files' => Array(
							'etc_courier_authdaemonrc' => '/etc/courier/authdaemonrc',
							'etc_courier_authmysqlrc' => '/etc/courier/authmysqlrc'
						),
						'restart' => Array(
							'/etc/init.d/courier-authdaemon restart',
							'/etc/init.d/courier-pop restart'
						)
					),
					'dovecot' => Array(
						'label' => 'Dovecot',
						'files' => Array(
							'etc_dovecot_dovecot.conf' => '/etc/dovecot/dovecot.conf',
							'etc_dovecot_dovecot-sql.conf' => '/etc/dovecot/dovecot-sql.conf'
						),
						'restart' => Array(
							'/etc/init.d/dovecot restart'
						)
					)
				)
			),
			'smtp' => Array(
				'label' => $lng['admin']['configfiles']['smtp'],
				'daemons' => Array(
					'postfix' => Array(
						'label' => 'Postfix',
						'files' => Array(
							'etc_postfix_main.cf' => '/etc/postfix/main.cf',
							'etc_postfix_mysql-virtual_alias_maps.cf' => '/etc/postfix/mysql-virtual_alias_maps.cf',
							'etc_postfix_mysql-virtual_mailbox_domains.cf' => '/etc/postfix/mysql-virtual_mailbox_domains.cf',
							'etc_postfix_mysql-virtual_mailbox_maps.cf' => '/etc/postfix/mysql-virtual_mailbox_maps.cf',
							'etc_postfix_sasl_smtpd.conf' => '/etc/postfix/sasl/smtpd.conf'
						),
						'commands' => Array(
							'mkdir -p /etc/postfix/sasl',
							'mkdir -p /var/spool/postfix/etc/pam.d',
							'mkdir -p /var/spool/postfix/var/run/mysqld',
							'groupadd -g ' . $settings['system']['vmail_gid'] . ' vmail',
							'useradd -u ' . $settings['system']['vmail_uid'] . ' -g vmail vmail',
							'mkdir -p ' . $settings['system']['vmail_homedir'],
							'chown -R vmail:vmail ' . $settings['system']['vmail_homedir'],
							'touch /etc/postfix/mysql-virtual_alias_maps.cf',
							'touch /etc/postfix/mysql-virtual_mailbox_domains.cf',
							'touch /etc/postfix/mysql-virtual_mailbox_maps.cf',
							'touch /etc/postfix/sasl/smtpd.conf',
							'chmod 660 /etc/postfix/mysql-virtual_alias_maps.cf',
							'chmod 660 /etc/postfix/mysql-virtual_mailbox_domains.cf',
							'chmod 660 /etc/postfix/mysql-virtual_mailbox_maps.cf',
							'chmod 660 /etc/postfix/sasl/smtpd.conf',
							'chgrp postfix /etc/postfix/mysql-virtual_alias_maps.cf',
							'chgrp postfix /etc/postfix/mysql-virtual_mailbox_domains.cf',
							'chgrp postfix /etc/postfix/mysql-virtual_mailbox_maps.cf',
							'chgrp postfix /etc/postfix/sasl/smtpd.conf',
						),
						'restart' => Array(
							'/etc/init.d/postfix restart',
							'newaliases'
						)
					),
					'exim4' => Array(
						'label' => 'Exim4',
						'commands_1' => Array(
							'dpkg-reconfigure exim4-config',
							'# choose "no configuration at this time" and "splitted configuration files" in the dialog'
						),
						'files' => Array(
							'etc_exim4_conf.d_acl_30_exim4-config_check_rcpt.rul' => '/etc/exim4/conf.d/acl/30_exim4-config_check_rcpt.rul',
							'etc_exim4_conf.d_auth_30_syscp-config' => '/etc/exim4/conf.d/auth/30_syscp-config',
							'etc_exim4_conf.d_main_10_syscp-config_options' => '/etc/exim4/conf.d/main/10_syscp-config_options',
							'etc_exim4_conf.d_router_180_syscp-config' => '/etc/exim4/conf.d/router/180_syscp-config',
							'etc_exim4_conf.d_transport_30_syscp-config' => '/etc/exim4/conf.d/transport/30_syscp-config'
						),
						'commands_2' => Array(
							'chmod o-rx /var/lib/exim4',
							'chmod o-rx /etc/exim4/conf.d/main/10_syscp-config_options'
						),
						'restart' => Array(
							'/etc/init.d/exim4 restart'
						)
					),
					'dkim' => Array(
						'label' => 'DomainKey filter',
						'commands_1' => Array(
							'mkdir -p /etc/postfix/dkim'
						),
						'files' => Array(
							'dkim-filter.conf' => '/etc/postfix/dkim/dkim-filter.conf'
						),
						'commands_2' => Array(
							'chgrp postfix /etc/postfix/dkim/dkim-filter.conf',
							'echo "smtpd_milters = inet:localhost:8891\n
milter_macro_daemon_name = SIGNING\n
milter_default_action = accept\n" >> /etc/postfix/main.cf'
						),
						'restart' => Array(
							'/etc/init.d/dkim-filter restart'
						)
					)
				)
			),
			'ftp' => Array(
				'label' => $lng['admin']['configfiles']['ftp'],
				'daemons' => Array(
					'proftpd' => Array(
						'label' => 'ProFTPd',
						'files' => Array(
							'etc_proftpd_modules.conf' => '/etc/proftpd/modules.conf',
							'etc_proftpd_proftpd.conf' => '/etc/proftpd/proftpd.conf'
						),
						'restart' => Array(
							'/etc/init.d/proftpd restart'
						)
					),
					'pure-ftpd' => Array(
						'label' => 'Pure FTPd',
						'files' => Array(
							'etc_pure-ftpd_conf_MinUID' => '/etc/pure-ftpd/conf/MinUID',
							'etc_pure-ftpd_conf_MySQLConfigFile' => '/etc/pure-ftpd/MySQLConfigFile',
							'etc_pure-ftpd_conf_NoAnonymous' => '/etc/pure-ftpd/conf/NoAnonymous',
							'etc_pure-ftpd_conf_MaxIdleTime' => '/etc/pure-ftpd/conf/MaxIdleTime',
							'etc_pure-ftpd_conf_ChrootEveryone' => '/etc/pure-ftpd/conf/ChrootEveryone',
							'etc_pure-ftpd_conf_PAMAuthentication' => '/etc/pure-ftpd/conf/PAMAuthentication',
							'etc_pure-ftpd_db_mysql.conf' => '/etc/pure-ftpd/db/mysql.conf',
							'etc_pure-ftpd_conf_CustomerProof' => '/etc/pure-ftpd/conf/CustomerProof',
							'etc_pure-ftpd_conf_Bind' => '/etc/pure-ftpd/conf/Bind',
							'etc_default_pure-ftpd-common' => '/etc/default/pure-ftpd-common'
						),
						'restart' => Array(
							'/etc/init.d/pure-ftpd-mysql restart'
						)
					),
				)
			),
			'etc' => Array(
				'label' => $lng['admin']['configfiles']['etc'],
				'daemons' => Array(
					'cron' => Array(
						'label' => 'Crond (cronscript)',
						'files' => Array(
							'etc_cron.d_syscp' => '/etc/cron.d/syscp'
						),
						'restart' => Array(
							'/etc/init.d/cron restart'
						)
					),
					'awstats' => Array(
						'label' => 'Awstats',
						'files' => Array(
							'etc_awstats_awstats.model.conf.syscp' => '/etc/awstats/awstats.model.conf.syscp',
							'etc_apache_vhosts_05_awstats.conf' => '/etc/apache' . ($settings['system']['apacheversion'] == 'apache2' ? '2' : '') . '/sites-enabled/05_awstats.conf'
						),
						'restart' => Array(
							'/etc/init.d/apache' . ($settings['system']['apacheversion'] == 'apache2' ? '2' : '') . ' restart'
						)
					)
				)
			)
		)
	),
	'gentoo' => Array(
		'label' => 'Gentoo',
		'services' => Array(
			'http' => Array(
				'label' => $lng['admin']['configfiles']['http'],
				'daemons' => Array(
					'apache' => Array(
						'label' => 'Apache2 Webserver',
						'commands' => Array(
							'touch ' . $settings['system']['apacheconf_vhost'],
							'chown root:0 ' . $settings['system']['apacheconf_vhost'],
							'chmod 0600 ' . $settings['system']['apacheconf_vhost'],
							'touch ' . $settings['system']['apacheconf_diroptions'],
							'chown root:0 ' . $settings['system']['apacheconf_diroptions'],
							'chmod 0600 ' . $settings['system']['apacheconf_diroptions'],
							'echo -e "\\nInclude ' . $settings['system']['apacheconf_vhost'] . '" >> ' . dirname($settings['system']['apacheconf_vhost']) . 'httpd.conf',
							'mkdir -p ' . $settings['system']['documentroot_prefix'],
							'mkdir -p ' . $settings['system']['logfiles_directory']
						),
						'restart' => Array(
							'rc-update add apache2 default',
							'/etc/init.d/apache2 restart'
						)
					),
					'lighttpd' => Array(
						'label' => 'Lighttpd Webserver',
						'files' => Array(
							'etc_lighttpd.conf' => '/etc/lighttpd/lighttpd.conf',
							'etc_mimetypes.conf' => '/etc/lighttpd/mimetypes.conf'
						),
						'commands' => Array(
							'touch ' . $settings['system']['apacheconf_vhost'],
							'chown root:0 ' . $settings['system']['apacheconf_vhost'],
							'chmod 0600 ' . $settings['system']['apacheconf_vhost'],
							'touch ' . $settings['system']['apacheconf_diroptions'],
							'chown root:0 ' . $settings['system']['apacheconf_diroptions'],
							'chmod 0600 ' . $settings['system']['apacheconf_diroptions'],
							'echo -e "# Lighttpd - SysCP vhosts\\n > "' . $settings['system']['apacheconf_vhost'],
							'echo -e "# Lighttpd - SysCP diroptions\\n > "' . $settings['system']['apacheconf_diroptions'],
							'echo -e \'\\ninclude "' . $settings['system']['apacheconf_vhost'] . '"\' >> /etc/lighttpd/lighttpd.conf',
							'echo -e \'\\ninclude "' . $settings['system']['apacheconf_diroptions'] . '"\' >> /etc/lighttpd/lighttpd.conf',
							'mkdir -p ' . $settings['system']['documentroot_prefix'],
							'mkdir -p ' . $settings['system']['logfiles_directory']
						),
						'restart' => Array(
							'rc-update add lighttpd default',
							'/etc/init.d/lighttpd restart'
						)
					)
				)
			),
			'dns' => Array(
				'label' => $lng['admin']['configfiles']['dns'],
				'daemons' => Array(
					'bind' => Array(
						'label' => 'Bind9 Nameserver',
						'files' => Array(
							'etc_bind_default.zone' => '/etc/bind/default.zone'
						),
						'commands' => Array(
							'echo "include \"' . $settings['system']['bindconf_directory'] . 'syscp_bind.conf\";" >> /etc/bind/named.conf',
							'touch ' . $settings['system']['bindconf_directory'] . 'syscp_bind.conf',
							'chown root:0 ' . $settings['system']['bindconf_directory'] . 'syscp_bind.conf',
							'chmod 0600 ' . $settings['system']['bindconf_directory'] . 'syscp_bind.conf'
						),
						'restart' => Array(
							'rc-update add named default',
							'/etc/init.d/named restart'
						)
					),
				)
			),
			'mail' => Array(
				'label' => $lng['admin']['configfiles']['mail'],
				'daemons' => Array(
					'courier' => Array(
						'label' => 'Courier-IMAP (POP3/IMAP)',
						'files' => Array(
							'etc_courier_authlib_authdaemonrc' => '/etc/courier/authlib/authdaemonrc',
							'etc_courier_authlib_authmysqlrc' => '/etc/courier/authlib/authmysqlrc',
							'etc_courier-imap_pop3d' => '/etc/courier-imap/pop3d',
							'etc_courier-imap_imapd' => '/etc/courier-imap/imapd',
							'etc_courier-imap_pop3d-ssl' => '/etc/courier-imap/pop3d-ssl',
							'etc_courier-imap_imapd-ssl' => '/etc/courier-imap/imapd-ssl'
						),
						'commands' => Array(
							'rm /etc/courier/authlib/authdaemonrc',
							'rm /etc/courier/authlib/authmysqlrc',
							'rm /etc/courier-imap/pop3d',
							'rm /etc/courier-imap/imapd',
							'rm /etc/courier-imap/pop3d-ssl',
							'rm /etc/courier-imap/imapd-ssl',
							'touch /etc/courier/authlib/authdaemonrc',
							'touch /etc/courier/authlib/authmysqlrc',
							'touch /etc/courier-imap/pop3d',
							'touch /etc/courier-imap/imapd',
							'touch /etc/courier-imap/pop3d-ssl',
							'touch /etc/courier-imap/imapd-ssl',
							'chown root:0 /etc/courier/authlib/authdaemonrc',
							'chown root:0 /etc/courier/authlib/authmysqlrc',
							'chown root:0 /etc/courier-imap/pop3d',
							'chown root:0 /etc/courier-imap/imapd',
							'chown root:0 /etc/courier-imap/pop3d-ssl',
							'chown root:0 /etc/courier-imap/imapd-ssl',
							'chmod 0600 /etc/courier/authlib/authdaemonrc',
							'chmod 0600 /etc/courier/authlib/authmysqlrc',
							'chmod 0600 /etc/courier-imap/pop3d',
							'chmod 0600 /etc/courier-imap/imapd',
							'chmod 0600 /etc/courier-imap/pop3d-ssl',
							'chmod 0600 /etc/courier-imap/imapd-ssl'
						),
						'restart' => Array(
							'rc-update add courier-authlib default',
							'rc-update add courier-pop3d default',
							'rc-update add courier-imapd default',
							'/etc/init.d/courier-authlib restart',
							'/etc/init.d/courier-pop3d restart',
							'/etc/init.d/courier-imapd restart'
						)
					),
				)
			),
			'smtp' => Array(
				'label' => $lng['admin']['configfiles']['smtp'],
				'daemons' => Array(
					'postfix' => Array(
						'label' => 'Postfix',
						'files' => Array(
							'etc_postfix_main.cf' => '/etc/postfix/main.cf',
							'etc_postfix_mysql-virtual_alias_maps.cf' => '/etc/postfix/mysql-virtual_alias_maps.cf',
							'etc_postfix_mysql-virtual_mailbox_domains.cf' => '/etc/postfix/mysql-virtual_mailbox_domains.cf',
							'etc_postfix_mysql-virtual_mailbox_maps.cf' => '/etc/postfix/mysql-virtual_mailbox_maps.cf',
							'etc_sasl2_smtpd.conf' => '/etc/sasl2/smtpd.conf'
						),
						'commands' => Array(
							'mkdir -p ' . $settings['system']['vmail_homedir'],
							'chown -R vmail:vmail ' . $settings['system']['vmail_homedir'],
							'chmod 0750 ' . $settings['system']['vmail_homedir'],
							'rm /etc/postfix/main.cf',
							'touch /etc/postfix/main.cf',
							'touch /etc/postfix/master.cf',
							'touch /etc/postfix/mysql-virtual_alias_maps.cf',
							'touch /etc/postfix/mysql-virtual_mailbox_domains.cf',
							'touch /etc/postfix/mysql-virtual_mailbox_maps.cf',
							'touch /etc/sasl2/smtpd.conf',
							'chown root:0 /etc/postfix/main.cf',
							'chown root:0 /etc/postfix/master.cf',
							'chown root:postfix /etc/postfix/mysql-virtual_alias_maps.cf',
							'chown root:postfix /etc/postfix/mysql-virtual_mailbox_domains.cf',
							'chown root:postfix /etc/postfix/mysql-virtual_mailbox_maps.cf',
							'chown root:0 /etc/sasl2/smtpd.conf',
							'chmod 0600 /etc/postfix/main.cf',
							'chmod 0600 /etc/postfix/master.cf',
							'chmod 0640 /etc/postfix/mysql-virtual_alias_maps.cf',
							'chmod 0640 /etc/postfix/mysql-virtual_mailbox_domains.cf',
							'chmod 0640 /etc/postfix/mysql-virtual_mailbox_maps.cf',
							'chmod 0600 /etc/sasl2/smtpd.conf'
						),
						'restart' => Array(
							'rc-update add postfix default',
							'/etc/init.d/postfix restart'
						)
					),
					'dkim' => Array(
						'label' => 'DomainKey filter',
						'commands_1' => Array(
							'mkdir -p /etc/postfix/dkim'
						),
						'files' => Array(
							'dkim-filter.conf' => '/etc/postfix/dkim/dkim-filter.conf'
						),
						'commands_2' => Array(
							'chgrp postfix /etc/postfix/dkim/dkim-filter.conf',
							'echo "smtpd_milters = inet:localhost:8891\n
milter_macro_daemon_name = SIGNING\n
milter_default_action = accept\n" >> /etc/postfix/main.cf'
						),
						'restart' => Array(
							'/etc/init.d/dkim-filter restart'
						)
					)
				)
			),
			'ftp' => Array(
				'label' => $lng['admin']['configfiles']['ftp'],
				'daemons' => Array(
					'proftpd' => Array(
						'label' => 'ProFTPd',
						'files' => Array(
							'etc_proftpd_proftpd.conf' => '/etc/proftpd/proftpd.conf'
						),
						'commands' => Array(
							'touch /etc/proftpd/proftpd.conf',
							'chown root:0 /etc/proftpd/proftpd.conf',
							'chmod 0600 /etc/proftpd/proftpd.conf'
						),
						'restart' => Array(
							'rc-update add proftpd default',
							'/etc/init.d/proftpd restart'
						)
					),
				)
			),
			'etc' => Array(
				'label' => $lng['admin']['configfiles']['etc'],
				'daemons' => Array(
					'cron' => Array(
						'label' => 'Crond (cronscript)',
						'files' => Array(
							'etc_php_syscp-cronjob_php.ini' => '/etc/php/syscp-cronjob/php.ini',
							'etc_cron.d_syscp' => '/etc/cron.d/syscp'
						),
						'commands' => Array(
							'touch /etc/cron.d/syscp',
							'chown root:0 /etc/cron.d/syscp',
							'chmod 0640 /etc/cron.d/syscp',
							'mkdir -p /etc/php/syscp-cronjob',
							'touch /etc/php/syscp-cronjob/php.ini',
							'chown -R root:0 /etc/php/syscp-cronjob',
							'chmod 0750 /etc/php/syscp-cronjob',
							'chmod 0640 /etc/php/syscp-cronjob/php.ini'
						),
						'restart' => Array(
							'rc-update add vixie-cron default',
							'/etc/init.d/vixie-cron restart'
						)
					),
					'awstats' => Array(
						'label' => 'Awstats',
						'files' => Array(
							'etc_awstats_awstats.model.conf.syscp' => '/etc/awstats/awstats.model.conf.syscp',
							'etc_apache_vhosts_05_awstats.conf' => '/etc/apache' . ($settings['system']['apacheversion'] == 'apache2' ? '2' : '') . '/sites-enabled/05_awstats.conf'
						),
						'restart' => Array(
							'/etc/init.d/apache2 restart'
						)
					),
					'libnss' => Array(
						'label' => 'libnss (system login with mysql)',
						'files' => Array(
							'etc_libnss-mysql.cfg' => '/etc/libnss-mysql.cfg',
							'etc_libnss-mysql-root.cfg' => '/etc/libnss-mysql-root.cfg'
						),
						'commands' => Array(
							'# if not installed:',
							'emerge -av libnss-mysql'
						),
						'restart' => Array(
							'rc-update add nscd default',
							'/etc/init.d/nscd restart'
						)
					)
				)
			)
		)
	),
	'suse_linux_10_0' => Array(
		'label' => 'SUSE Linux 10.0',
		'services' => Array(
			'http' => Array(
				'label' => $lng['admin']['configfiles']['http'],
				'daemons' => Array(
					'apache' => Array(
						'label' => 'Apache',
						'commands' => Array(
							'echo -e "\\nInclude ' . $settings['system']['apacheconf_vhost'] . '" >> ' . makeCorrectFile(dirname($settings['system']['apacheconf_vhost']) . '/httpd.conf'),
							'touch ' . $settings['system']['apacheconf_vhost'],
							'touch ' . $settings['system']['apacheconf_diroptions'],
							'mkdir -p ' . $settings['system']['documentroot_prefix'],
							'mkdir -p ' . $settings['system']['logfiles_directory']
						),
						'restart' => Array(
							'/etc/init.d/apache2 restart'
						)
					),
				)
			),
			'dns' => Array(
				'label' => $lng['admin']['configfiles']['dns'],
				'daemons' => Array(
					'bind' => Array(
						'label' => 'Bind9',
						'commands' => Array(
							'echo "include \"' . $settings['system']['bindconf_directory'] . 'syscp_bind.conf\";" >> /etc/named.conf',
							'touch ' . $settings['system']['bindconf_directory'] . 'syscp_bind.conf'
						),
						'restart' => Array(
							'/etc/init.d/named restart'
						)
					),
				)
			),
			'mail' => Array(
				'label' => $lng['admin']['configfiles']['mail'],
				'daemons' => Array(
					'courier' => Array(
						'label' => 'Courier',
						'files' => Array(
							'etc_authlib_authdaemonrc' => '/etc/authlib/authdaemonrc',
							'etc_authlib_authmysqlrc' => '/etc/authlib/authmysqlrc'
						),
						'restart' => Array(
							'/etc/init.d/courier-authdaemon restart',
							'/etc/init.d/courier-pop restart'
						)
					),
				)
			),
			'smtp' => Array(
				'label' => $lng['admin']['configfiles']['smtp'],
				'daemons' => Array(
					'postfix' => Array(
						'label' => 'Postfix',
						'files' => Array(
							'etc_postfix_main.cf' => '/etc/postfix/main.cf',
							'etc_postfix_mysql-virtual_alias_maps.cf' => '/etc/postfix/mysql-virtual_alias_maps.cf',
							'etc_postfix_mysql-virtual_mailbox_domains.cf' => '/etc/postfix/mysql-virtual_mailbox_domains.cf',
							'etc_postfix_mysql-virtual_mailbox_maps.cf' => '/etc/postfix/mysql-virtual_mailbox_maps.cf',
							'usr_lib_sasl2_smtpd.conf' => '/usr/lib/sasl2/smtpd.conf'
						),
						'commands' => Array(
							'mkdir -p /var/spool/postfix/etc/pam.d',
							'groupadd -g ' . $settings['system']['vmail_gid'] . ' vmail',
							'useradd -u ' . $settings['system']['vmail_uid'] . ' -g vmail vmail',
							'mkdir -p ' . $settings['system']['vmail_homedir'],
							'chown -R vmail:vmail ' . $settings['system']['vmail_homedir'],
							'touch /etc/postfix/mysql-virtual_alias_maps.cf',
							'touch /etc/postfix/mysql-virtual_mailbox_domains.cf',
							'touch /etc/postfix/mysql-virtual_mailbox_maps.cf',
							'touch /usr/lib/sasl2/smtpd.conf',
							'chmod 660 /etc/postfix/mysql-virtual_alias_maps.cf',
							'chmod 660 /etc/postfix/mysql-virtual_mailbox_domains.cf',
							'chmod 660 /etc/postfix/mysql-virtual_mailbox_maps.cf',
							'chmod 660 /usr/lib/sasl2/smtpd.conf',
							'chgrp postfix /etc/postfix/mysql-virtual_alias_maps.cf',
							'chgrp postfix /etc/postfix/mysql-virtual_mailbox_domains.cf',
							'chgrp postfix /etc/postfix/mysql-virtual_mailbox_maps.cf',
							'chgrp postfix /usr/lib/sasl2/smtpd.conf'
						),
						'restart' => Array(
							'/etc/init.d/postfix restart'
						)
					),
					'dkim' => Array(
						'label' => 'DomainKey filter',
						'commands_1' => Array(
							'mkdir -p /etc/postfix/dkim'
						),
						'files' => Array(
							'dkim-filter.conf' => '/etc/postfix/dkim/dkim-filter.conf'
						),
						'commands_2' => Array(
							'chgrp postfix /etc/postfix/dkim/dkim-filter.conf',
							'echo "smtpd_milters = inet:localhost:8891\n
milter_macro_daemon_name = SIGNING\n
milter_default_action = accept\n" >> /etc/postfix/main.cf'
						),
						'restart' => Array(
							'/etc/init.d/dkim-filter restart'
						)
					)
				)
			),
			'ftp' => Array(
				'label' => $lng['admin']['configfiles']['ftp'],
				'daemons' => Array(
					'proftpd' => Array(
						'label' => 'ProFTPd',
						'files' => Array(
							'etc_proftpd_modules.conf' => '/etc/proftpd/modules.conf',
							'etc_proftpd_proftpd.conf' => '/etc/proftpd/proftpd.conf'
						),
						'restart' => Array(
							'/etc/init.d/proftpd restart'
						)
					),
				)
			),
			'etc' => Array(
				'label' => $lng['admin']['configfiles']['etc'],
				'daemons' => Array(
					'cron' => Array(
						'label' => 'Crond (cronscript)',
						'files' => Array(
							'etc_cron.d_syscp' => '/etc/cron.d/syscp'
						),
						'restart' => Array(
							'/etc/init.d/cron restart'
						)
					),
					'awstats' => Array(
						'label' => 'Awstats',
						'files' => Array(
							'etc_awstats_awstats.model.conf.syscp' => '/etc/awstats/awstats.model.conf.syscp',
							'etc_apache_vhosts_05_awstats.conf' => '/etc/apache' . ($settings['system']['apacheversion'] == 'apache2' ? '2' : '') . '/sites-enabled/05_awstats.conf'
						),
						'restart' => Array(
							'/etc/init.d/apache2 restart'
						)
					)
				)
			)
		)
	)
);

?>

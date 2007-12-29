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

define('AREA', 'admin');

/**
 * Include our init.php, which manages Sessions, Language etc.
 */

require ("./lib/init.php");
$configfiles = Array(
    'debian_sarge' => Array(
        'label' => 'Debian 3.1 (Sarge)',
        'services' => Array(
            'http' => Array(
                'label' => $lng['admin']['configfiles']['http'],
                'daemons' => Array(
                    'apache' => Array(
                        'label' => 'Apache',
                        'commands' => Array(
                            'touch ' . $settings['system']['apacheconf_directory'] . $settings['system']['apacheconf_filename'],
                            'mkdir -p ' . $settings['system']['documentroot_prefix'],
                            'mkdir -p ' . $settings['system']['logfiles_directory'],
                            'echo -e "\\nInclude ' . $settings['system']['apacheconf_directory'] . $settings['system']['apacheconf_filename'] . '" >> ' . $settings['system']['apacheconf_directory'] . 'httpd.conf',
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
                            'etc_postfix_sasl_smtpd.conf' => '/etc/postfix/sasl/smtpd.conf',
                        ),
                        'commands' => Array(
                            'mkdir -p /etc/postfix/sasl',
                            'mkdir -p /var/spool/postfix/etc/pam.d',
                            'mkdir -p /var/spool/postfix/var/run/mysqld',
                            'groupadd -g ' . $settings['system']['vmail_gid'] . ' vmail',
                            'useradd -u ' . $settings['system']['vmail_uid'] . ' -g vmail vmail',
                            'mkdir -p ' . $settings['system']['vmail_homedir'],
                            'chown -R vmail:vmail ' . $settings['system']['vmail_homedir']
                        ),
                        'restart' => Array(
                            '/etc/init.d/postfix restart'
                        )
                    ),
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
                            'touch ' . $settings['system']['apacheconf_directory'] . $settings['system']['apacheconf_filename'],
                            'mkdir -p ' . $settings['system']['documentroot_prefix'],
                            'mkdir -p ' . $settings['system']['logfiles_directory'],
                            'echo -e "\\nInclude ' . $settings['system']['apacheconf_directory'] . $settings['system']['apacheconf_filename'] . '" >> ' . $settings['system']['apacheconf_directory'] . 'httpd.conf',
                            'apache-modconf apache disable mod_userdir'
                        ),
                        'restart' => Array(
                            '/etc/init.d/apache restart'
                        )
                    ),
                    'apache2' => Array(
                        'label' => 'Apache 2',
                        'commands' => Array(
                            'touch ' . $settings['system']['apacheconf_directory'] . $settings['system']['apacheconf_filename'],
                            'a2ensite '.$settings['system']['apacheconf_filename'],
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
                            'etc_postfix_sasl_smtpd.conf' => '/etc/postfix/sasl/smtpd.conf',
                        ),
                        'commands' => Array(
                            'mkdir -p /etc/postfix/sasl',
                            'mkdir -p /var/spool/postfix/etc/pam.d',
                            'mkdir -p /var/spool/postfix/var/run/mysqld',
                            'groupadd -g ' . $settings['system']['vmail_gid'] . ' vmail',
                            'useradd -u ' . $settings['system']['vmail_uid'] . ' -g vmail vmail',
                            'mkdir -p ' . $settings['system']['vmail_homedir'],
                            'chown -R vmail:vmail ' . $settings['system']['vmail_homedir']
                        ),
                        'restart' => Array(
                            '/etc/init.d/postfix restart'
                        )
                    ),
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
                            'echo -e "\\nInclude ' . $settings['system']['apacheconf_directory'] . $settings['system']['apacheconf_filename'] . '" >> ' . $settings['system']['apacheconf_directory'] . 'httpd.conf',
                            'touch ' . $settings['system']['apacheconf_directory'] . $settings['system']['apacheconf_filename'],
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
                        'label' => 'Bind',
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
                            'usr_lib_sasl2_smtpd.conf' => '/usr/lib/sasl2/smtpd.conf',
                        ),
                        'commands' => Array(
                            'mkdir -p /var/spool/postfix/etc/pam.d',
                            'groupadd -g ' . $settings['system']['vmail_gid'] . ' vmail',
                            'useradd -u ' . $settings['system']['vmail_uid'] . ' -g vmail vmail',
                            'mkdir -p ' . $settings['system']['vmail_homedir'],
                            'chown -R vmail:vmail ' . $settings['system']['vmail_homedir']
                        ),
                        'restart' => Array(
                            '/etc/init.d/postfix restart'
                        )
                    ),
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
                    )
                )
            )
        )
    )
);
$distribution = '';
$distributions_select = '';
$service = '';
$services_select = '';
$daemon = '';
$daemons_select = '';

if(isset($_GET['distribution'])
   && $_GET['distribution'] != ''
   && isset($configfiles[$_GET['distribution']])
   && is_array($configfiles[$_GET['distribution']]))
{
    $distribution = $_GET['distribution'];

    if(isset($_GET['service'])
       && $_GET['service'] != ''
       && isset($configfiles[$distribution]['services'][$_GET['service']])
       && is_array($configfiles[$distribution]['services'][$_GET['service']]))
    {
        $service = $_GET['service'];

        if(isset($_GET['daemon'])
           && $_GET['daemon'] != ''
           && isset($configfiles[$distribution]['services'][$service]['daemons'][$_GET['daemon']])
           && is_array($configfiles[$distribution]['services'][$service]['daemons'][$_GET['daemon']]))
        {
            $daemon = $_GET['daemon'];
        }
        else
        {
            foreach($configfiles[$distribution]['services'][$service]['daemons'] as $daemon_name => $daemon_details)
            {
                $daemons_select.= makeoption($daemon_details['label'], $daemon_name);
            }
        }
    }
    else
    {
        foreach($configfiles[$distribution]['services'] as $service_name => $service_details)
        {
            $services_select.= makeoption($service_details['label'], $service_name);
        }
    }
}
else
{
    foreach($configfiles as $distribution_name => $distribution_details)
    {
        $distributions_select.= makeoption($distribution_details['label'], $distribution_name);
    }
}

if($userinfo['change_serversettings'] == '1')
{
    if($distribution != ''
       && $service != ''
       && $daemon != '')
    {
        if(isset($configfiles[$distribution]['services'][$service]['daemons'][$daemon]['commands'])
           && is_array($configfiles[$distribution]['services'][$service]['daemons'][$daemon]['commands']))
        {
            $commands = implode("\n", $configfiles[$distribution]['services'][$service]['daemons'][$daemon]['commands']);
        }
        else
        {
            $commands = '';
        }

        $replace_arr = Array(
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

        if(isset($configfiles[$distribution]['services'][$service]['daemons'][$daemon]['files'])
           && is_array($configfiles[$distribution]['services'][$service]['daemons'][$daemon]['files']))
        {
            while(list($filename, $realname) = each($configfiles[$distribution]['services'][$service]['daemons'][$daemon]['files']))
            {
                $file_content = file_get_contents('./templates/misc/configfiles/' . $distribution . '/' . $daemon . '/' . $filename);
                $file_content = strtr($file_content, $replace_arr);
                $file_content = htmlspecialchars($file_content);
                $numbrows = count(explode("\n", $file_content));
                eval("\$files.=\"" . getTemplate("configfiles/configfiles_file") . "\";");
            }
        }

        if(isset($configfiles[$distribution]['services'][$service]['daemons'][$daemon]['restart'])
           && is_array($configfiles[$distribution]['services'][$service]['daemons'][$daemon]['restart']))
        {
            $restart = implode("\n", $configfiles[$distribution]['services'][$service]['daemons'][$daemon]['restart']);
        }
        else
        {
            $restart = '';
        }

        eval("echo \"" . getTemplate("configfiles/configfiles") . "\";");
    }
    elseif($page == 'overview')
    {
        $distributions = '';
        foreach($configfiles as $distribution_name => $distribution_details)
        {
            $services = '';
            foreach($distribution_details['services'] as $service_name => $service_details)
            {
                $daemons = '';
                foreach($service_details['daemons'] as $daemon_name => $daemon_details)
                {
                    eval("\$daemons.=\"" . getTemplate("configfiles/choose_daemon") . "\";");
                }

                eval("\$services.=\"" . getTemplate("configfiles/choose_service") . "\";");
            }

            eval("\$distributions.=\"" . getTemplate("configfiles/choose_distribution") . "\";");
        }

        eval("echo \"" . getTemplate("configfiles/choose") . "\";");
    }
    else
    {
        eval("echo \"" . getTemplate("configfiles/wizard") . "\";");
    }
}

?>

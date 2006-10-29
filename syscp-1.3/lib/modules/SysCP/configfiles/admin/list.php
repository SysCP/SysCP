<?php

/**
 * This file is part of the SysCP project.
 * Copyright (c) 2003-2006 the SysCP Project.
 *
 * For the full copyright and license information, please view the COPYING
 * file that was distributed with this source code. You can also view the
 * COPYING file online at http://files.syscp.org/misc/COPYING.txt
 *
 * @author     Florian Lippert <flo@redenswert.de>
 * @copyright  (c) 2003-2006 Florian Lippert
 * @package    Syscp.Modules
 * @subpackage Panel.Admin
 * @license    GPLv2 http://files.syscp.org/misc/COPYING.txt
 * @version    $Id: admin_configfiles.php 495 2006-05-26 17:04:00Z martin $
 *
 * @todo code cleanup is needed, exspecially in queries, loops, conditions
 * @todo code restructuring is needed, we need to seperate view, controller and model logic,
 *       as a preparation towards MVC patterns
 */

$configfiles = Array(
    'debian_sarge' => Array(
        'label' => 'Debian 3.1 (Sarge)',
        'daemons' => Array(
            'apache' => Array(
                'label' => 'Apache Webserver (HTTP)',
                'commands' => Array(
                    'echo -e "\\nInclude '.$this->ConfigHandler->get('system.apacheconf_directory').'vhosts.conf" >> '.$this->ConfigHandler->get('system.apacheconf_directory').'httpd.conf',
                    'touch '.$this->ConfigHandler->get('system.apacheconf_directory').'vhosts.conf'

                    //'mkdir -p '.$this->ConfigHandler->get('system.documentroot_prefix'),
                    //'mkdir -p '.$this->ConfigHandler->get('system.logfiles_directory')
                ),
                'restart' => Array(
                    '/etc/init.d/apache restart'
                )
            ),
            'bind' => Array(
                'label' => 'Bind9 Nameserver (DNS)',
                'files' => Array(
                    '/etc/bind/default.zone' => '/etc/bind/default.zone'
                ),
                'commands' => Array(
                    'echo "include \"'.$this->ConfigHandler->get('system.bindconf_directory').'syscp_bind.conf\";" >> /etc/bind/named.conf',
                    'touch '.$this->ConfigHandler->get('system.bindconf_directory').'syscp_bind.conf'
                ),
                'restart' => Array(
                    '/etc/init.d/bind9 restart'
                )
            ),
            'courier' => Array(
                'label' => 'Courier (POP3/IMAP)',
                'files' => Array(
                    '/etc/courier/authdaemonrc' => '/etc/courier/authdaemonrc',
                    '/etc/courier/authmysqlrc' => '/etc/courier/authmysqlrc'
                ),
                'restart' => Array(
                    '/etc/init.d/courier-authdaemon restart',
                    '/etc/init.d/courier-pop restart'
                )
            ),
            'postfix' => Array(
                'label' => 'Postfix (MTA)',
                'files' => Array(
                    '/etc/postfix/main.cf' => '/etc/postfix/main.cf',
                    '/etc/postfix/mysql-virtual_alias_maps.cf' => '/etc/postfix/mysql-virtual_alias_maps.cf',
                    '/etc/postfix/mysql-virtual_mailbox_domains.cf' => '/etc/postfix/mysql-virtual_mailbox_domains.cf',
                    '/etc/postfix/mysql-virtual_mailbox_maps.cf' => '/etc/postfix/mysql-virtual_mailbox_maps.cf',
                    '/etc/postfix/sasl/smtpd.conf' => '/etc/postfix/sasl/smtpd.conf',
                    '/etc/mysql/debian-start' => '/etc/mysql/debian-start'
                ),
                'commands' => Array(
                    'mkdir -p /etc/postfix/sasl',
                    'mkdir -p /var/spool/postfix/etc/pam.d',
                    'mkdir -p /var/spool/postfix/var/run/mysqld',
                    'groupadd -g '.$this->ConfigHandler->get('system.vmail_gid').' vmail',
                    'useradd -u '.$this->ConfigHandler->get('system.vmail_uid').' -g vmail vmail',
                    'mkdir -p '.$this->ConfigHandler->get('system.vmail_homedir'),
                    'chown -R vmail:vmail '.$this->ConfigHandler->get('system.vmail_homedir')
                ),
                'restart' => Array(
                    '/etc/init.d/postfix restart'
                )
            ),
            'proftpd' => Array(
                'label' => 'ProFTPd (FTP)',
                'files' => Array(
                    '/etc/proftpd.conf' => '/etc/proftpd.conf'
                ),
                'restart' => Array(
                    '/etc/init.d/proftpd restart'
                )
            ),
            'cron' => Array(
                'label' => 'Crond (cronscript)',
                'files' => Array(
                    '/etc/cron.d/syscp' => '/etc/cron.d/syscp'
                ),
                'restart' => Array(
                    '/etc/init.d/cron restart'
                )
            )
        )
    ),
    'ubuntu_dapper' => Array(
        'label' => 'Ubuntu 6.06 LTS (Dapper Drake)',
        'daemons' => Array(
            'apache' => Array(
                'label' => 'Apache2 Webserver (HTTP)',
                'commands' => Array(
                    'echo -e "\\nInclude '.$this->ConfigHandler->get('system.apacheconf_directory').'vhosts.conf" >> '.$this->ConfigHandler->get('system.apacheconf_directory').'httpd.conf',
                    'touch '.$this->ConfigHandler->get('system.apacheconf_directory').'vhosts.conf'

                    //'mkdir -p '.$this->ConfigHandler->get('system.documentroot_prefix'),
                    //'mkdir -p '.$this->ConfigHandler->get('system.logfiles_directory')
                ),
                'restart' => Array(
                    '/etc/init.d/apache2 restart'
                )
            ),
            'bind' => Array(
                'label' => 'Bind9 Nameserver (DNS)',
                'files' => Array(
                    '/etc/bind/default.zone' => '/etc/bind/default.zone'
                ),
                'commands' => Array(
                    'echo "include \"'.$this->ConfigHandler->get('system.bindconf_directory').'syscp_bind.conf\";" >> /etc/bind/named.conf',
                    'touch '.$this->ConfigHandler->get('system.bindconf_directory').'syscp_bind.conf'
                ),
                'restart' => Array(
                    '/etc/init.d/bind9 restart'
                )
            ),
            'courier' => Array(
                'label' => 'Courier (POP3/IMAP)',
                'files' => Array(
                    '/etc/courier/authdaemonrc' => '/etc/courier/authdaemonrc',
                    '/etc/courier/authmysqlrc' => '/etc/courier/authmysqlrc'
                ),
                'restart' => Array(
                    '/etc/init.d/courier-authdaemon restart',
                    '/etc/init.d/courier-pop restart'
                )
            ),
            'postfix' => Array(
                'label' => 'Postfix (MTA)',
                'files' => Array(
                    '/etc/postfix/main.cf' => '/etc/postfix/main.cf',
                    '/etc/postfix/mysql-virtual_alias_maps.cf' => '/etc/postfix/mysql-virtual_alias_maps.cf',
                    '/etc/postfix/mysql-virtual_mailbox_domains.cf' => '/etc/postfix/mysql-virtual_mailbox_domains.cf',
                    '/etc/postfix/mysql-virtual_mailbox_maps.cf' => '/etc/postfix/mysql-virtual_mailbox_maps.cf',
                    '/etc/postfix/sasl/smtpd.conf' => '/etc/postfix/sasl/smtpd.conf',
                    '/etc/mysql/debian-start' => '/etc/mysql/debian-start'
                ),
                'commands' => Array(
                    'mkdir -p /etc/postfix/sasl',
                    'mkdir -p /var/spool/postfix/etc/pam.d',
                    'mkdir -p /var/spool/postfix/var/run/mysqld',
                    'groupadd -g '.$this->ConfigHandler->get('system.vmail_gid').' vmail',
                    'useradd -u '.$this->ConfigHandler->get('system.vmail_uid').' -g vmail vmail',
                    'mkdir -p '.$this->ConfigHandler->get('system.vmail_homedir'),
                    'chown -R vmail:vmail '.$this->ConfigHandler->get('system.vmail_homedir')
                ),
                'restart' => Array(
                    '/etc/init.d/postfix restart'
                )
            ),
            'proftpd' => Array(
                'label' => 'ProFTPd (FTP)',
                'files' => Array(
                    '/etc/proftpd.conf' => '/etc/proftpd.conf'
                ),
                'restart' => Array(
                    '/etc/init.d/proftpd restart'
                )
            ),
            'cron' => Array(
                'label' => 'Crond (cronscript)',
                'files' => Array(
                    '/etc/cron.d/syscp' => '/etc/cron.d/syscp'
                ),
                'restart' => Array(
                    '/etc/init.d/cron restart'
                )
            ),
            'libnss-mysql' => array(
                'label' => 'libnss-mysql (users)',
                'files' => array(
                    '/etc/nsswitch.conf' => '/etc/nsswitch.conf',
                    '/etc/libnss-mysql-root.cfg' => '/etc/libnss-mysql-root.cfg',
                    '/etc/libnss-mysql.cfg' => '/etc/libnss-mysql.cfg'
                ),
                'restart' => array(
                    '/etc/init.d/nscd restart'
                )
            )
        )
    )
);

/*echo '<pre>';
	print_r($configfiles);
	echo '</pre>';*/

if($this->User['change_serversettings'] == '1')
{
    if(isset($_GET['distribution'])
       && $_GET['distribution'] != ''
       && isset($configfiles[$_GET['distribution']])
       && is_array($configfiles[$_GET['distribution']])
       && isset($_GET['daemon'])
       && $_GET['daemon'] != ''
       && isset($configfiles[$_GET['distribution']]['daemons'][$_GET['daemon']])
       && is_array($configfiles[$_GET['distribution']]['daemons'][$_GET['daemon']]))
    {
        $distribution = addslashes($_GET['distribution']);
        $daemon = addslashes($_GET['daemon']);

        if(isset($configfiles[$distribution]['daemons'][$daemon]['commands'])
           && is_array($configfiles[$distribution]['daemons'][$daemon]['commands']))
        {
            $commands = implode("\n", $configfiles[$distribution]['daemons'][$daemon]['commands']);
        }
        else
        {
            $commands = '';
        }

        $replace_arr = Array(
            '<SQL_UNPRIVILEGED_USER>' => $this->ConfigHandler->get('sql.user'),
            '<SQL_UNPRIVILEGED_PASSWORD>' => 'MYSQL_PASSWORD',
            '<SQL_DB>' => $this->ConfigHandler->get('sql.db'),
            '<SQL_HOST>' => $this->ConfigHandler->get('env.host'),
            '<SQL_ROOT_USER>' => $this->ConfigHandler->get('sql.root.user'),
            '<SQL_ROOT_PASSWORD>' => 'MYSQL_ROOT_PW',
            '<SERVERNAME>' => $this->ConfigHandler->get('system.hostname'),
            '<SERVERIP>' => $this->ConfigHandler->get('system.ipaddress'),
            '<VIRTUAL_MAILBOX_BASE>' => $this->ConfigHandler->get('system.vmail_homedir'),
            '<VIRTUAL_UID_MAPS>' => $this->ConfigHandler->get('system.vmail_uid'),
            '<VIRTUAL_GID_MAPS>' => $this->ConfigHandler->get('system.vmail_gid')
        );
        $files = array();

        if(isset($configfiles[$distribution]['daemons'][$daemon]['files'])
           && is_array($configfiles[$distribution]['daemons'][$daemon]['files']))
        {
            while(list($filename, $realname) = each($configfiles[$distribution]['daemons'][$daemon]['files']))
            {
                $file_content = implode('', file(SYSCP_PATH_LIB.'templates/configs/'.$distribution.'/'.$filename));
                $file_content = strtr($file_content, $replace_arr);
                $file_content = htmlspecialchars($file_content);
                $numbrows = count(explode("\n", $file_content));
                $temp = array();
                $temp['numbrows'] = $numbrows;
                $temp['content'] = $file_content;
                $temp['filename'] = $realname;
                $files[] = $temp;

                //					eval("\$files.=\"".getTemplate("configfiles/configfiles_file")."\";");
            }
        }

        if(isset($configfiles[$distribution]['daemons'][$daemon]['restart'])
           && is_array($configfiles[$distribution]['daemons'][$daemon]['restart']))
        {
            $restart = implode("\n", $configfiles[$distribution]['daemons'][$daemon]['restart']);
        }
        else
        {
            $restart = '';
        }

        $this->TemplateHandler->set('distribution', $distribution);
        $this->TemplateHandler->set('daemon', $daemon);
        $this->TemplateHandler->set('commands', $commands);
        $this->TemplateHandler->set('files', $files);
        $this->TemplateHandler->set('configfiles', $configfiles);
        $this->TemplateHandler->set('restart', $restart);
        $this->TemplateHandler->setTemplate('SysCP/configfiles/admin/details.tpl');
    }
    else
    {
        $this->TemplateHandler->set('configfiles', $configfiles);
        $this->TemplateHandler->setTemplate('SysCP/configfiles/admin/list.tpl');
    }
}

?>

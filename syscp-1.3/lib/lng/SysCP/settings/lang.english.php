<?php

/**
 * This file is part of the SysCP project.
 * Copyright (c) 2003-2006 the SysCP Project.
 *
 * For the full copyright and license information, please view the COPYING
 * file that was distributed with this source code. You can also view the
 * COPYING file online at http://files.syscp.org/misc/COPYING.txt
 *
 * @author     The SysCP Team <team@syscp.org>
 * @copyright  (c) 2006 The SysCP Team
 * @package    Syscp.Translation
 * @license    GPLv2 http://files.syscp.org/misc/COPYING.txt
 *
 */

/**
 * Normal strings
 */

$lng['SysCP']['settings']['accountprefix_desc'] = 'Which prefix should accounts have?';
$lng['SysCP']['settings']['accountprefix_title'] = 'Account Prefix';
$lng['SysCP']['settings']['adminmail_desc'] = 'What\'s the sender address for E-mails sent from the panel?';
$lng['SysCP']['settings']['adminmail_title'] = 'Sender Address';
$lng['SysCP']['settings']['apache_access_log_desc'] = 'Path to the Apache Access Logfile of a domain, you must use at least one replacer.<br/>You have the following replacers available:<br/><b>{LOGIN}</b>: Loginname of the account<br/><b>{USERHOME}</b>: Homedir of the account owning the domain<br/><b>{DOMAIN}</b>: Name of the domain';
$lng['SysCP']['settings']['apache_access_log_title'] = 'Apache Access Logfile';
$lng['SysCP']['settings']['apache_error_log_desc'] = 'Path to the Apache Error Logfile of a domain, you must use at least one replacer.<br/>You have the following replacers available:<br/><b>{LOGIN}</b>: Loginname of the account<br/><b>{USERHOME}</b>: Homedir of the account owning the domain<br/><b>{DOMAIN}</b>: Name of the domain';
$lng['SysCP']['settings']['apache_error_log_title'] = 'Apache Error Logfile';
$lng['SysCP']['settings']['apacheconf_directory_desc'] = 'Where should the Apache configuration files be placed?';
$lng['SysCP']['settings']['apacheconf_directory_title'] = 'Apache Configuration Directory';
$lng['SysCP']['settings']['apacheconf_filename_desc'] = 'How should the Apache configuration file be named?';
$lng['SysCP']['settings']['apacheconf_filename_title'] = 'Apache Configuration Filename';
$lng['SysCP']['settings']['apachereload_command_desc'] = 'What\'s the Apache reload command?';
$lng['SysCP']['settings']['apachereload_command_title'] = 'Apache Reload Command';
$lng['SysCP']['settings']['bindconf_directory_desc'] = 'Where should the Bind configuration files be placed?';
$lng['SysCP']['settings']['bindconf_directory_title'] = 'Bind Configuration Directory';
$lng['SysCP']['settings']['binddefaultzone_desc'] = 'What\'s the name of the default zone?';
$lng['SysCP']['settings']['binddefaultzone_title'] = 'Bind Default Zone';
$lng['SysCP']['settings']['bindreload_command_desc'] = 'What\'s the Bind reload command?';
$lng['SysCP']['settings']['bindreload_command_title'] = 'Bind Reload Command';
$lng['SysCP']['settings']['clearcache'] = 'Clear cache';
$lng['SysCP']['settings']['deactivatetime_desc'] = 'Time (in seconds) an account gets deactivated after too many login tries.';
$lng['SysCP']['settings']['deactivatetime_title'] = 'Deactivate Time';
$lng['SysCP']['settings']['documentroot_prefix_desc'] = 'The path to the domain documentroot. You have the following replacers available:<br/><b>{USERHOME}</b>: Homedir of the account owning the domain<br/><b>{LOGIN}</b>: Loginname of the account owning the domain<br/><b>{DOMAIN}</b>: Name of the domain';
$lng['SysCP']['settings']['documentroot_prefix_title'] = 'Domain Documentroot Directory';
$lng['SysCP']['settings']['ftpprefix_desc'] = 'Which prefix should FTP accounts have?';
$lng['SysCP']['settings']['ftpprefix_title'] = 'FTP Prefix';
$lng['SysCP']['settings']['hostname_desc'] = 'What\'s the hostname of this server?';
$lng['SysCP']['settings']['hostname_title'] = 'Hostname';
$lng['SysCP']['settings']['ipaddress_desc'] = 'What\'s the IP address of this server?';
$lng['SysCP']['settings']['ipaddress_title'] = 'IP Address';
$lng['SysCP']['settings']['language_desc'] = 'What\'s your default language?';
$lng['SysCP']['settings']['language_title'] = 'Default Language';
$lng['SysCP']['settings']['maxloginattempts_desc'] = 'Maximum login attempts after which the account gets deactivated.';
$lng['SysCP']['settings']['maxloginattempts_title'] = 'Max Login Attempts';
$lng['SysCP']['settings']['paging_desc'] = 'How many entries shall be displayed on one page? (0 = disable paging)';
$lng['SysCP']['settings']['paging_title'] = 'Entries Per Page';
$lng['SysCP']['settings']['pathedit_desc'] = 'Should a path be selected by a dropdown menu or by an input field?';
$lng['SysCP']['settings']['pathedit_title'] = 'Type of Path Input';
$lng['SysCP']['settings']['phpmyadmin_url_desc'] = 'What\'s the URL to phpMyAdmin? (Has to start with http:// or https://)';
$lng['SysCP']['settings']['phpmyadmin_url_title'] = 'phpMyAdmin URL';
$lng['SysCP']['settings']['rebuildconf'] = 'Rebuild config files';
$lng['SysCP']['settings']['server'] = 'Server';
$lng['SysCP']['settings']['session_timeout_desc'] = 'How long does a user have to be inactive before a session gets invalid (seconds)?';
$lng['SysCP']['settings']['session_timeout_title'] = 'Session Timeout';
$lng['SysCP']['settings']['settings'] = 'Settings';
$lng['SysCP']['settings']['sqlprefix_desc'] = 'Which prefix should SQL accounts have?';
$lng['SysCP']['settings']['sqlprefix_title'] = 'SQL Prefix';
$lng['SysCP']['settings']['user_homedir_desc'] = 'Home directory of the user. Please note, SysCP will make no different dirs per account if you don\'t use the replacer!<br/>All accounts would have the same homedir, so you must use at least one replacer.<br/>You have the following replacers available:<br/><b>{LOGIN}</b>: Loginname of the account';
$lng['SysCP']['settings']['user_homedir_title'] = 'User Home Directory';
$lng['SysCP']['settings']['vmail_gid_desc'] = 'Which GroupID should E-mails have?';
$lng['SysCP']['settings']['vmail_gid_title'] = 'E-mails GID';
$lng['SysCP']['settings']['vmail_homedir_desc'] = 'Where should all E-mails of a user be stored? Note that you must use at least one replacer.<br/>You have the following replacers available:<br/><b>{USERHOME}</b>: Homedir of the account<br/><b>{LOGIN}</b>: Loginname of the account';
$lng['SysCP']['settings']['vmail_homedir_title'] = 'User\'s E-mails Homedir';
$lng['SysCP']['settings']['vmail_uid_desc'] = 'Which UserID should E-mails have?';
$lng['SysCP']['settings']['vmail_uid_title'] = 'E-mails UID';
$lng['SysCP']['settings']['webftp_url_desc'] = 'What\'s the URL to WebFTP? (Has to start with http:// or https://)';
$lng['SysCP']['settings']['webftp_url_title'] = 'WebFTP URL';
$lng['SysCP']['settings']['webmail_url_desc'] = 'What\'s the URL to WebMail? (Has to start with http:// or https://)';
$lng['SysCP']['settings']['webmail_url_title'] = 'WebMail URL';
$lng['SysCP']['settings']['customerpathedit_title'] = 'Customer-Pathedit';
$lng['SysCP']['settings']['customerpathedit_description'] = 'Is the customer allowed to change the paths for ftp-accounts?';

/**
 * Errors & Questions
 */

$lng['SysCP']['settings']['question']['clearcache'] = 'Do you really want to clear the cache?';
$lng['SysCP']['settings']['question']['rebuildconf'] = 'Do you really want to rebuild your config files?';

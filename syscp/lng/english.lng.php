<?php
/**
 * filename: $Source$
 * begin: Friday, Aug 06, 2004
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
 * @package Language
 * @version $Id$
 */


/**
 * Global
 */
$lng['panel']['edit'] = 'edit';
$lng['panel']['delete'] = 'delete';
$lng['panel']['create'] = 'create';
$lng['panel']['save'] = 'save';
$lng['panel']['yes'] = 'yes';
$lng['panel']['no'] = 'no';
$lng['panel']['emptyfornochanges'] = 'empty for no changes';
$lng['panel']['emptyfordefault'] = 'empty for defaults';
$lng['panel']['path'] = 'Path';
$lng['panel']['toggle'] = 'Toggle';

/**
 * Login
 */
$lng['login']['username'] = 'Username';
$lng['login']['password'] = 'Password';
$lng['login']['language'] = 'Language';
$lng['login']['login'] = 'Login';
$lng['login']['logout'] = 'logout';

/**
 * Customer
 */
$lng['customer']['login'] = 'Username';
$lng['customer']['documentroot'] = 'Home directory';
$lng['customer']['name'] = 'Name';
$lng['customer']['surname'] = 'Surname';
$lng['customer']['company'] = 'Company';
$lng['customer']['street'] = 'Street';
$lng['customer']['zipcode'] = 'Zipcode';
$lng['customer']['city'] = 'city';
$lng['customer']['phone'] = 'phone';
$lng['customer']['fax'] = 'fax';
$lng['customer']['email'] = 'email';
$lng['customer']['customernumber'] = 'Customer ID';
$lng['customer']['diskspace'] = 'Webspace (MB)';
$lng['customer']['traffic'] = 'Traffic (GB)';
$lng['customer']['mysqls'] = 'MySQL-Databases';
$lng['customer']['emails'] = 'eMail-Addresses';
$lng['customer']['accounts'] = 'eMail-Accounts';
$lng['customer']['forwarders'] = 'eMail-Forwarders';
$lng['customer']['ftps'] = 'FTP-Accounts';
$lng['customer']['subdomains'] = 'Sub-Domain(s)';
$lng['customer']['domains'] = 'Domain(s)';
$lng['customer']['unlimited'] = 'unlimited';

/**
 * Customermenue
 */
$lng['menue']['main']['main'] = 'Main';
$lng['menue']['main']['changepassword'] = 'Change password';
$lng['menue']['email']['email'] = 'eMail';
$lng['menue']['email']['emails'] = 'Addresses';
$lng['menue']['email']['webmail'] = 'WebMail';
$lng['menue']['mysql']['mysql'] = 'MySQL';
$lng['menue']['mysql']['databases'] = 'Databases';
$lng['menue']['mysql']['phpmyadmin'] = 'phpMyAdmin';
$lng['menue']['domains']['domains'] = 'Domains';
$lng['menue']['domains']['settings'] = 'Settings';
$lng['menue']['ftp']['ftp'] = 'FTP';
$lng['menue']['ftp']['accounts'] = 'Accounts';
$lng['menue']['ftp']['webftp'] = 'WebFTP';
$lng['menue']['extras']['extras'] = 'Extras';
$lng['menue']['extras']['directoryprotection'] = 'Directory protection';
$lng['menue']['extras']['pathoptions'] = 'path options';

/**
 * Index
 */
$lng['index']['customerdetails'] = 'Customer Details';
$lng['index']['accountdetails'] = 'Account Details';

/**
 * Change Password
 */
$lng['changepassword']['old_password'] = 'Old password';
$lng['changepassword']['new_password'] = 'New password';
$lng['changepassword']['new_password_confirm'] = 'New password (confirm)';
$lng['changepassword']['new_password_ifnotempty'] = 'New password (empty = no change)';
$lng['changepassword']['also_change_ftp'] = ' also change password of the main FTP account';

/**
 * Domains
 */
$lng['domains']['description'] = 'Here you can create (Sub-)Domains and change their paths.<br />The system will need some time to apply the new settings after every change.';
$lng['domains']['domainsettings'] = 'Domain settings';
$lng['domains']['domainname'] = 'Domain name';
$lng['domains']['subdomain_add'] = 'Create subdomain';
$lng['domains']['subdomain_edit'] = 'Edit (sub)domain';
$lng['domains']['wildcarddomain'] = 'Create as wildcarddomain?';

/**
 * eMails
 */
$lng['emails']['description'] = 'Here you can create and change your eMail addresses.<br />An account is like your letterbox in front of your house. If someone sends you an email, it will be dropped into the account.<br /><br />To download your emails use the following settings in your mailprogram: (The data in <i>italics</i> has to be changed to the equivalents you typed in!)<br />Hostname: <b><i>Domainname</i></b><br />Username: <b><i>Account name / eMail address</i></b><br />Password: <b><i>the password you\'ve chosen</i></b>';
$lng['emails']['emailaddress'] = 'eMail-address';
$lng['emails']['emails_add'] = 'Create eMail-address';
$lng['emails']['emails_edit'] = 'Edit eMail-address';
$lng['emails']['catchall'] = 'Catchall';
$lng['emails']['iscatchall'] = 'Define as catchall-address?';
$lng['emails']['account'] = 'Account';
$lng['emails']['account_add'] = 'Create account';
$lng['emails']['account_delete'] = 'Delete account';
$lng['emails']['from'] = 'Source';
$lng['emails']['to'] = 'Destination';
$lng['emails']['forwarders'] = 'Forwarders';
$lng['emails']['forwarder_add'] = 'Create forwarder';

/**
 * FTP
 */
$lng['ftp']['description'] = 'Here you can create and change your FTP accounts.<br />The changes are made instantly and the accounts can be used immediately.';
$lng['ftp']['account_add'] = 'Create Account';

/**
 * MySQL
 */
$lng['mysql']['description'] = 'Here you can create and change your MySQL-Databases.<br />The changes are made instantly and the database can be used immediately.<br />At the menu on the left side you find the tool phpMyAdmin with which you can easily administer your database.<br /><br />To use your databases in your own php-scripts use the following settings: (The data in <i>italics</i> have to be changed into the equivalents you typed in!)<br />Hostname: <b>localhost</b><br />Username: <b><i>Databasename</i></b><br />Password: <b><i>the password you\'ve chosen</i></b><br />Database: <b><i>Databasename';
$lng['mysql']['databasename'] = 'user/database name';
$lng['mysql']['databasedescription'] = 'database description';
$lng['mysql']['database_create'] = 'Create database';

/**
 * Extras
 */
$lng['extras']['description'] = 'Here you can add some extras, for example directory protection.<br />The system will need some time to apply the new settings after every change.';
$lng['extras']['directoryprotection_add'] = 'Add directory protection';
$lng['extras']['view_directory'] = 'display directory content';
$lng['extras']['pathoptions_add'] = 'add path options';
$lng['extras']['directory_browsing'] = 'directory content browsing';
$lng['extras']['pathoptions_edit'] = 'edit path options';
$lng['extras']['error404path'] = '404';
$lng['extras']['error403path'] = '403';
$lng['extras']['error500path'] = '500';
$lng['extras']['error401path'] = '401';
$lng['extras']['errordocument404path'] = 'URL to ErrorDocument 404';
$lng['extras']['errordocument403path'] = 'URL to ErrorDocument 403';
$lng['extras']['errordocument500path'] = 'URL to ErrorDocument 500';
$lng['extras']['errordocument401path'] = 'URL to ErrorDocument 401';

/**
 * Errors
 */
$lng['error']['error'] = 'Error';
$lng['error']['directorymustexist'] = 'The directory you typed in has to exist. Please create it with your FTP client.';
$lng['error']['filemustexist'] = 'The file you typed in has to exist.';
$lng['error']['allresourcesused'] = 'You have already used all of your resources.';
$lng['error']['domains_cantdeletemaindomain'] = 'You cannot delete a domain which is used as an email-domain.';
$lng['error']['firstdeleteallsubdomains'] = 'You have to delete all Subdomains first before you can create a wildcard domain.';
$lng['error']['youhavealreadyacatchallforthisdomain'] = 'You have already defined a catchall for this domain.';
$lng['error']['ftp_cantdeletemainaccount'] = 'You cannot delete your main FTP account';
$lng['error']['login'] = 'The username or password you typed in is wrong. Please try it again!';
$lng['error']['login_blocked'] = 'This account has been suspended because of too many login errors. <br />Please try again in '.$settings['login']['deactivatetime'].' seconds.';
$lng['error']['notallreqfieldsorerrors'] = 'You have not filled in all or filled in some fields incorrectly.';
$lng['error']['oldpasswordnotcorrect'] = 'The old password is not correct.';
$lng['error']['youcantallocatemorethanyouhave'] = 'You cannot allocate more resources than you own for yourself.';
$lng['error']['youcantdeletechangemainadmin'] = 'You cannot delete or edit the main admin for security reasons.';
$lng['error']['mustbeurl'] = 'You have not typed a valid url';
/**
 * Questions
 */
$lng['question']['question'] = 'Securityquestion';
$lng['question']['admin_customer_reallydelete'] = 'Do you really want to delete the customer %s? This cannot be undone!';
$lng['question']['admin_domain_reallydelete'] = 'Do you really want to delete the domain %s?';
$lng['question']['admin_domain_reallydisablesecuritysetting'] = 'Do you really want to deactivate these Securitysettings (OpenBasedir and/or SafeMode)?';
$lng['question']['admin_admin_reallydelete'] = 'Do you really want to delete the admin %s? Every customer and domain will be reallocated to the main administrator.';
$lng['question']['domains_reallydelete'] = 'Do you really want to delete the domain %s?';
$lng['question']['email_reallydelete'] = 'Do you really want to delete the email-address %s?';
$lng['question']['email_reallydelete_account'] = 'Do you really want to delete the email-account of %s?';
$lng['question']['email_reallydelete_forwarder'] = 'Do you really want to delete the forwarder %s?';
$lng['question']['extras_reallydelete'] = 'Do you really want to delete the directory protection for %s?';
$lng['question']['extras_reallydelete_pathoptions'] = 'Do you really want to delete the path options for %s?';
$lng['question']['ftp_reallydelete'] = 'Do you really want to delete the FTP account %s?';
$lng['question']['mysql_reallydelete'] = 'Do you really want to delete the database %s? This cannot be undone!';

/**
 * Mails
 */
$lng['mails']['pop_success']['mailbody'] = 'Hello,\n\nyour Mail account $username\nwas set up successfully.\n\nThis is an automatically created\neMail, please do not answer!\n\nYours sincerely, the SysCP-Team';
$lng['mails']['pop_success']['subject'] = 'Mail account set up successfully';
$lng['mails']['createcustomer']['mailbody'] = 'Hello $surname $name,\n\nhere is your account information:\n\nUsername: $loginname\nPassword: $password\n\nThank you,\nthe SysCP-Team';
$lng['mails']['createcustomer']['subject'] = 'Account informationen';

/**
 * Admin
 */
$lng['admin']['overview'] = 'Overview';
$lng['admin']['ressourcedetails'] = 'Used resources';
$lng['admin']['systemdetails'] = 'System Details';
$lng['admin']['syscpdetails'] = 'SysCP Details';
$lng['admin']['installedversion'] = 'Installed Version';
$lng['admin']['latestversion'] = 'Latest Version';
$lng['admin']['lookfornewversion']['clickhere'] = 'search via webservice';
$lng['admin']['lookfornewversion']['error'] = 'Error while reading';
$lng['admin']['resources'] = 'Resources';
$lng['admin']['customer'] = 'Customer';
$lng['admin']['customers'] = 'Customers';
$lng['admin']['customer_add'] = 'Create customer';
$lng['admin']['customer_edit'] = 'Edit customer';
$lng['admin']['domains'] = 'Domains';
$lng['admin']['domain_add'] = 'Create domain';
$lng['admin']['domain_edit'] = 'Edit domain';
$lng['admin']['admin'] = 'Admin';
$lng['admin']['admins'] = 'Admins';
$lng['admin']['admin_add'] = 'Create admin';
$lng['admin']['admin_edit'] = 'Edit admin';
$lng['admin']['customers_see_all'] = 'Can see all customers?';
$lng['admin']['domains_see_all'] = 'Can see all domains?';
$lng['admin']['change_serversettings'] = 'Can change server settings?';
$lng['admin']['server'] = 'Server';
$lng['admin']['serversettings'] = 'Settings';
$lng['admin']['stdsubdomain'] = 'Standard subdomain';
$lng['admin']['stdsubdomain_add'] = 'Create standard subdomain';
$lng['admin']['deactivated'] = 'Deactivated';
$lng['admin']['deactivated_user'] = 'Deactivate User';
$lng['admin']['sendpassword'] = 'Send password';
$lng['admin']['ownvhostsettings'] = 'Own vHost-Settings';
$lng['admin']['configfiles']['serverconfiguration'] = 'Configuration';
$lng['admin']['configfiles']['files'] = '<b>Configfiles:</b> Please change the following files or create them with<br />the following content if they do not exist.<br /><b>Please Note:</b> The MySQL-password has not been replaced for security reasons.<br />Please replace &quot;MYSQL_PASSWORD&quot; on your own. If you forgot your MySQL-password<br />you\'ll find it in &quot;lib/userdata.inc.php&quot;.';
$lng['admin']['configfiles']['commands'] = '<b>Commands:</b> Please execute the following commands in a shell.';
$lng['admin']['configfiles']['restart'] = '<b>Restart:</b> Please execute the following commands in a shell in order to reload the new configuration.';

/**
 * Serversettings
 */
$lng['serversettings']['session_timeout']['title'] = 'Session Timeout';
$lng['serversettings']['session_timeout']['description'] = 'How long does a user have to be inactive before a session gets invalid (seconds)?';
$lng['serversettings']['accountprefix']['title'] = 'Customerprefix';
$lng['serversettings']['accountprefix']['description'] = 'Which prefix should customeraccounts have?';
$lng['serversettings']['mysqlprefix']['title'] = 'SQL Prefix';
$lng['serversettings']['mysqlprefix']['description'] = 'Which prefix should mysql accounts have?';
$lng['serversettings']['ftpprefix']['title'] = 'FTP Prefix';
$lng['serversettings']['ftpprefix']['description'] = 'Which prefix should ftp accounts have?';
$lng['serversettings']['documentroot_prefix']['title'] = 'Document directory';
$lng['serversettings']['documentroot_prefix']['description'] = 'Where should all data be stored?';
$lng['serversettings']['logfiles_directory']['title'] = 'Logfilesdirectory';
$lng['serversettings']['logfiles_directory']['description'] = 'Where should all log files be stored?';
$lng['serversettings']['ipaddress']['title'] = 'IP-Address';
$lng['serversettings']['ipaddress']['description'] = 'What\'s the IP-address of this server?';
$lng['serversettings']['hostname']['title'] = 'Hostname';
$lng['serversettings']['hostname']['description'] = 'What\'s the Hostname of this server?';
$lng['serversettings']['apacheconf_directory']['title'] = 'Apache configuration directory';
$lng['serversettings']['apacheconf_directory']['description'] = 'Where are the apache configfiles?';
$lng['serversettings']['apachereload_command']['title'] = 'Apache reload command';
$lng['serversettings']['apachereload_command']['description'] = 'What\'s the apache reload command?';
$lng['serversettings']['bindconf_directory']['title'] = 'Bind config directory';
$lng['serversettings']['bindconf_directory']['description'] = 'Where are the bind config files?';
$lng['serversettings']['bindreload_command']['title'] = 'Bind reload command';
$lng['serversettings']['bindreload_command']['description'] = 'What\'s the bind reload command?';
$lng['serversettings']['binddefaultzone']['title'] = 'Bind default zone';
$lng['serversettings']['binddefaultzone']['description'] = 'What\'s the name of the default zone?';
$lng['serversettings']['vmail_uid']['title'] = 'Mails-Uid';
$lng['serversettings']['vmail_uid']['description'] = 'Which UserID should mails have?';
$lng['serversettings']['vmail_gid']['title'] = 'Mails-Gid';
$lng['serversettings']['vmail_gid']['description'] = 'Which GroupID should mails have?';
$lng['serversettings']['vmail_homedir']['title'] = 'Mails-Homedir';
$lng['serversettings']['vmail_homedir']['description'] = 'Where should all mails be stored?';
$lng['serversettings']['adminmail']['title'] = 'Sender';
$lng['serversettings']['adminmail']['description'] = 'What\'s the senderaddress for emails sent from the Panel?';
$lng['serversettings']['phpmyadmin_url']['title'] = 'phpMyAdmin URL';
$lng['serversettings']['phpmyadmin_url']['description'] = 'What\'s the URL to phpMyAdmin? (have to start with http://)';
$lng['serversettings']['webmail_url']['title'] = 'WebMail URL';
$lng['serversettings']['webmail_url']['description'] = 'What\'s the URL to WebMail? (have to start with http://)';
$lng['serversettings']['webftp_url']['title'] = 'WebFTP URL';
$lng['serversettings']['webftp_url']['description'] = 'What\'s the URL to  WebFTP? (have to start with http://)';
$lng['serversettings']['language']['description'] = 'What\'s your standard server language?';
$lng['serversettings']['maxloginattempts']['title']       = 'Max Login Attempts';
$lng['serversettings']['maxloginattempts']['description'] = 'Maximum login attempts after which the account gets deactivated.';
$lng['serversettings']['deactivatetime']['title']       = 'Deactivate Time';
$lng['serversettings']['deactivatetime']['description'] = 'Time (sec.) an account gets deactivated after too many login tries.';

?>
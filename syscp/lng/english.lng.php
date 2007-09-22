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
 * @package    Language
 * @version    $Id$
 */

/**
 * Global
 */

$lng['translator'] = '';
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
$lng['panel']['next'] = 'next';
$lng['panel']['dirsmissing'] = 'Can not find or read the directory!';

/**
 * Login
 */

$lng['login']['username'] = 'Username';
$lng['login']['password'] = 'Password';
$lng['login']['language'] = 'Language';
$lng['login']['login'] = 'Login';
$lng['login']['logout'] = 'Logout';
$lng['login']['profile_lng'] = 'Profile language';

/**
 * Customer
 */

$lng['customer']['documentroot'] = 'Home directory';
$lng['customer']['name'] = 'Name';
$lng['customer']['firstname'] = 'First name';
$lng['customer']['company'] = 'Company';
$lng['customer']['street'] = 'Street';
$lng['customer']['zipcode'] = 'Zipcode';
$lng['customer']['city'] = 'City';
$lng['customer']['phone'] = 'Phone';
$lng['customer']['fax'] = 'Fax';
$lng['customer']['email'] = 'Email';
$lng['customer']['customernumber'] = 'Customer ID';
$lng['customer']['diskspace'] = 'Webspace (MB)';
$lng['customer']['traffic'] = 'Traffic (GB)';
$lng['customer']['mysqls'] = 'MySQL-Databases';
$lng['customer']['emails'] = 'E-mail-Addresses';
$lng['customer']['accounts'] = 'E-mail-Accounts';
$lng['customer']['forwarders'] = 'E-mail-Forwarders';
$lng['customer']['ftps'] = 'FTP-Accounts';
$lng['customer']['subdomains'] = 'Sub-Domain';
$lng['customer']['domains'] = 'Domain';
$lng['customer']['unlimited'] = 'unlimited';

/**
 * Customermenue
 */

$lng['menue']['main']['main'] = 'Main';
$lng['menue']['main']['changepassword'] = 'Change password';
$lng['menue']['main']['changelanguage'] = 'Change language';
$lng['menue']['email']['email'] = 'E-mail';
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

$lng['domains']['description'] = 'Here you can create (sub-)domains and change their paths.<br />The system will need some time to apply the new settings after every change.';
$lng['domains']['domainsettings'] = 'Domain settings';
$lng['domains']['domainname'] = 'Domain name';
$lng['domains']['subdomain_add'] = 'Create subdomain';
$lng['domains']['subdomain_edit'] = 'Edit (sub)domain';
$lng['domains']['wildcarddomain'] = 'Create as wildcarddomain?';
$lng['domains']['aliasdomain'] = 'Alias for domain';
$lng['domains']['noaliasdomain'] = 'No alias domain';

/**
 * E-mails
 */

$lng['emails']['description'] = 'Here you can create and change your e-mail addresses.<br />An account is like your letterbox in front of your house. If someone sends you an email, it will be dropped into the account.<br /><br />To download your emails use the following settings in your mailprogram: (The data in <i>italics</i> has to be changed to the equivalents you typed in!)<br />Hostname: <b><i>Domainname</i></b><br />Username: <b><i>Account name / e-mail address</i></b><br />Password: <b><i>the password you\'ve chosen</i></b>';
$lng['emails']['emailaddress'] = 'E-mail-address';
$lng['emails']['emails_add'] = 'Create e-mail-address';
$lng['emails']['emails_edit'] = 'Edit e-mail-address';
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
$lng['error']['directorymustexist'] = 'The directory %s must exist. Please create it with your FTP client.';
$lng['error']['filemustexist'] = 'The file %s must exist.';
$lng['error']['allresourcesused'] = 'You have already used all of your resources.';
$lng['error']['domains_cantdeletemaindomain'] = 'You cannot delete a domain which is used as an email-domain.';
$lng['error']['domains_canteditdomain'] = 'You cannot edit this domain. It has been disabled by the admin.';
$lng['error']['domains_cantdeletedomainwithemail'] = 'You cannot delete a domain which is used as an email-domain. Delete all email addresses first.';
$lng['error']['firstdeleteallsubdomains'] = 'You have to delete all Subdomains first before you can create a wildcard domain.';
$lng['error']['youhavealreadyacatchallforthisdomain'] = 'You have already defined a catchall for this domain.';
$lng['error']['ftp_cantdeletemainaccount'] = 'You cannot delete your main FTP account';
$lng['error']['login'] = 'The username or password you typed in is wrong. Please try it again!';
$lng['error']['login_blocked'] = 'This account has been suspended because of too many login errors. <br />Please try again in ' . $settings['login']['deactivatetime'] . ' seconds.';
$lng['error']['notallreqfieldsorerrors'] = 'You have not filled in all or filled in some fields incorrectly.';
$lng['error']['oldpasswordnotcorrect'] = 'The old password is not correct.';
$lng['error']['youcantallocatemorethanyouhave'] = 'You cannot allocate more resources than you own for yourself.';
$lng['error']['mustbeurl'] = 'You have not typed a valid or complete url (e.g. http://somedomain.com/error404.htm)';
$lng['error']['invalidpath'] = 'You have not chosen a valid url (maybe problems with the dirlisting?)';
$lng['error']['stringisempty'] = 'Missing Input in Field';
$lng['error']['stringiswrong'] = 'Wrong Input in Field';
$lng['error']['myloginname'] = '\'' . $lng['login']['username'] . '\'';
$lng['error']['mypassword'] = '\'' . $lng['login']['password'] . '\'';
$lng['error']['oldpassword'] = '\'' . $lng['changepassword']['old_password'] . '\'';
$lng['error']['newpassword'] = '\'' . $lng['changepassword']['new_password'] . '\'';
$lng['error']['newpasswordconfirm'] = '\'' . $lng['changepassword']['new_password_confirm'] . '\'';
$lng['error']['newpasswordconfirmerror'] = 'New password and confirmation does not match';
$lng['error']['myname'] = '\'' . $lng['customer']['name'] . '\'';
$lng['error']['myfirstname'] = '\'' . $lng['customer']['firstname'] . '\'';
$lng['error']['emailadd'] = '\'' . $lng['customer']['email'] . '\'';
$lng['error']['mydomain'] = '\'Domain\'';
$lng['error']['mydocumentroot'] = '\'Documentroot\'';
$lng['error']['loginnameexists'] = 'Login-Name %s already exists';
$lng['error']['emailiswrong'] = 'E-mail-Address %s contains invalid characters or is incomplete';
$lng['error']['loginnameiswrong'] = 'Login-Name %s contains invalid characters';
$lng['error']['userpathcombinationdupe'] = 'Combination of Username and Path already exists';
$lng['error']['patherror'] = 'General Error! path cannot be empty';
$lng['error']['errordocpathdupe'] = 'Option for path %s already exists';
$lng['error']['adduserfirst'] = 'Please create a customer first';
$lng['error']['domainalreadyexists'] = 'The domain %s is already assigned to a customer';
$lng['error']['nolanguageselect'] = 'No language selected.';
$lng['error']['nosubjectcreate'] = 'You must define a topic for this mail template.';
$lng['error']['nomailbodycreate'] = 'You must define a Mail-Text for this mail template.';
$lng['error']['templatenotfound'] = 'Template was not found.';
$lng['error']['alltemplatesdefined'] = 'You cant define more templates, all languages are supported already.';
$lng['error']['wwwnotallowed'] = 'www is not allowed for subdomains.';
$lng['error']['subdomainiswrong'] = 'The subdomain %s contains invalid characters.';
$lng['error']['domaincantbeempty'] = 'The domain-name can not be empty.';
$lng['error']['domainexistalready'] = 'The domain %s already exists.';
$lng['error']['domainisaliasorothercustomer'] = 'The selected alias domain is either itself an alias domain or belongs to another customer.';
$lng['error']['emailexistalready'] = 'The e-mail-Address %s already exists.';
$lng['error']['maindomainnonexist'] = 'The main-domain %s does not exist.';
$lng['error']['destinationnonexist'] = 'Please create your forwarder in the field \'Destination\'.';
$lng['error']['destinationalreadyexistasmail'] = 'The forwarder to %s already exists as active EMail-Address.';
$lng['error']['destinationalreadyexist'] = 'You have already defined a forwarder to %s .';
$lng['error']['destinationiswrong'] = 'The forwarder %s contains invalid character(s) or is incomplete.';
$lng['error']['domainname'] = $lng['domains']['domainname'];

/**
 * Questions
 */

$lng['question']['question'] = 'Security question';
$lng['question']['admin_customer_reallydelete'] = 'Do you really want to delete the customer %s? This cannot be undone!';
$lng['question']['admin_domain_reallydelete'] = 'Do you really want to delete the domain %s?';
$lng['question']['admin_domain_reallydisablesecuritysetting'] = 'Do you really want to deactivate these Security settings (OpenBasedir and/or SafeMode)?';
$lng['question']['admin_admin_reallydelete'] = 'Do you really want to delete the admin %s? Every customer and domain will be reassigned to your account.';
$lng['question']['admin_template_reallydelete'] = 'Do you really want to delete the template \'%s\'?';
$lng['question']['domains_reallydelete'] = 'Do you really want to delete the domain %s?';
$lng['question']['email_reallydelete'] = 'Do you really want to delete the email-address %s?';
$lng['question']['email_reallydelete_account'] = 'Do you really want to delete the email-account of %s?';
$lng['question']['email_reallydelete_forwarder'] = 'Do you really want to delete the forwarder %s?';
$lng['question']['extras_reallydelete'] = 'Do you really want to delete the directory protection for %s?';
$lng['question']['extras_reallydelete_pathoptions'] = 'Do you really want to delete the path options for %s?';
$lng['question']['ftp_reallydelete'] = 'Do you really want to delete the FTP account %s?';
$lng['question']['mysql_reallydelete'] = 'Do you really want to delete the database %s? This cannot be undone!';
$lng['question']['admin_configs_reallyrebuild'] = 'Do you really want to rebuild your apache and bind config files?';

/**
 * Mails
 */

$lng['mails']['pop_success']['mailbody'] = 'Hello,\n\nyour Mail account {EMAIL}\nwas set up successfully.\n\nThis is an automatically created\ne-mail, please do not answer!\n\nYours sincerely, the SysCP-Team';
$lng['mails']['pop_success']['subject'] = 'Mail account set up successfully';
$lng['mails']['createcustomer']['mailbody'] = 'Hello {FIRSTNAME} {NAME},\n\nhere is your account information:\n\nUsername: {USERNAME}\nPassword: {PASSWORD}\n\nThank you,\nthe SysCP-Team';
$lng['mails']['createcustomer']['subject'] = 'Account information';

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
$lng['admin']['subdomainforemail'] = 'Subdomains as emaildomains';
$lng['admin']['admin'] = 'Admin';
$lng['admin']['admins'] = 'Admins';
$lng['admin']['admin_add'] = 'Create admin';
$lng['admin']['admin_edit'] = 'Edit admin';
$lng['admin']['customers_see_all'] = 'Can see all customers?';
$lng['admin']['domains_see_all'] = 'Can see all domains?';
$lng['admin']['change_serversettings'] = 'Can change server settings?';
$lng['admin']['server'] = 'Server';
$lng['admin']['serversettings'] = 'Settings';
$lng['admin']['rebuildconf'] = 'Rebuild Config Files';
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
$lng['admin']['templates']['templates'] = 'Templates';
$lng['admin']['templates']['template_add'] = 'Add template';
$lng['admin']['templates']['template_edit'] = 'Edit template';
$lng['admin']['templates']['action'] = 'Action';
$lng['admin']['templates']['email'] = 'E-Mail';
$lng['admin']['templates']['subject'] = 'Subject';
$lng['admin']['templates']['mailbody'] = 'Mail body';
$lng['admin']['templates']['createcustomer'] = 'Welcome mail for new customers';
$lng['admin']['templates']['pop_success'] = 'Welcome mail for new email accounts';
$lng['admin']['templates']['template_replace_vars'] = 'Variables to be replaced in the template:';
$lng['admin']['templates']['FIRSTNAME'] = 'Replaced with the customers firstname.';
$lng['admin']['templates']['NAME'] = 'Replaced with the customers name.';
$lng['admin']['templates']['USERNAME'] = 'Replaced with the customers account username.';
$lng['admin']['templates']['PASSWORD'] = 'Replaced with the customers account password.';
$lng['admin']['templates']['EMAIL'] = 'Replaced with the address of the POP3/IMAP account.';

/**
 * Serversettings
 */

$lng['serversettings']['session_timeout']['title'] = 'Session Timeout';
$lng['serversettings']['session_timeout']['description'] = 'How long does a user have to be inactive before a session gets invalid (seconds)?';
$lng['serversettings']['accountprefix']['title'] = 'Customer prefix';
$lng['serversettings']['accountprefix']['description'] = 'Which prefix should customer accounts have?';
$lng['serversettings']['mysqlprefix']['title'] = 'SQL Prefix';
$lng['serversettings']['mysqlprefix']['description'] = 'Which prefix should mysql accounts have?';
$lng['serversettings']['ftpprefix']['title'] = 'FTP Prefix';
$lng['serversettings']['ftpprefix']['description'] = 'Which prefix should ftp accounts have?';
$lng['serversettings']['documentroot_prefix']['title'] = 'Home directory';
$lng['serversettings']['documentroot_prefix']['description'] = 'Where should all home directories be stored?';
$lng['serversettings']['logfiles_directory']['title'] = 'Logfiles directory';
$lng['serversettings']['logfiles_directory']['description'] = 'Where should all log files be stored?';
$lng['serversettings']['ipaddress']['title'] = 'IP-Address';
$lng['serversettings']['ipaddress']['description'] = 'What\'s the IP-address of this server?';
$lng['serversettings']['hostname']['title'] = 'Hostname';
$lng['serversettings']['hostname']['description'] = 'What\'s the Hostname of this server?';
$lng['serversettings']['apacheconf_directory']['title'] = 'Apache configuration directory';
$lng['serversettings']['apacheconf_directory']['description'] = 'Where should apache configfiles be saved?';
$lng['serversettings']['apachereload_command']['title'] = 'Apache reload command';
$lng['serversettings']['apachereload_command']['description'] = 'What\'s the apache command to reload apache configfiles?';
$lng['serversettings']['bindconf_directory']['title'] = 'Bind config directory';
$lng['serversettings']['bindconf_directory']['description'] = 'Where should bind configfiles be saved?';
$lng['serversettings']['bindreload_command']['title'] = 'Bind reload command';
$lng['serversettings']['bindreload_command']['description'] = 'What\'s the bind command to reload bind configfiles?';
$lng['serversettings']['binddefaultzone']['title'] = 'Bind default zone';
$lng['serversettings']['binddefaultzone']['description'] = 'What\'s the name of the default zone?';
$lng['serversettings']['vmail_uid']['title'] = 'Mails-UID';
$lng['serversettings']['vmail_uid']['description'] = 'Which UserID should mails have?';
$lng['serversettings']['vmail_gid']['title'] = 'Mails-GID';
$lng['serversettings']['vmail_gid']['description'] = 'Which GroupID should mails have?';
$lng['serversettings']['vmail_homedir']['title'] = 'Mails-Homedir';
$lng['serversettings']['vmail_homedir']['description'] = 'Where should all mails be stored?';
$lng['serversettings']['adminmail']['title'] = 'Sender';
$lng['serversettings']['adminmail']['description'] = 'What\'s the sender address for emails sent from the Panel?';
$lng['serversettings']['phpmyadmin_url']['title'] = 'phpMyAdmin URL';
$lng['serversettings']['phpmyadmin_url']['description'] = 'What\'s the URL to phpMyAdmin? (has to start with http(s)://)';
$lng['serversettings']['webmail_url']['title'] = 'WebMail URL';
$lng['serversettings']['webmail_url']['description'] = 'What\'s the URL to WebMail? (has to start with http(s)://)';
$lng['serversettings']['webftp_url']['title'] = 'WebFTP URL';
$lng['serversettings']['webftp_url']['description'] = 'What\'s the URL to  WebFTP? (has to start with http(s)://)';
$lng['serversettings']['language']['description'] = 'What\'s your standard server language?';
$lng['serversettings']['maxloginattempts']['title'] = 'Max Login Attempts';
$lng['serversettings']['maxloginattempts']['description'] = 'Maximum login attempts after which the account gets deactivated.';
$lng['serversettings']['deactivatetime']['title'] = 'Deactivate Time';
$lng['serversettings']['deactivatetime']['description'] = 'Time (sec.) an account gets deactivated after too many login tries.';
$lng['serversettings']['pathedit']['title'] = 'Type of path input';
$lng['serversettings']['pathedit']['description'] = 'Should a path be selected by a dropdown menu or by an input field?';

/**
 * CHANGED BETWEEN 1.2.12 and 1.2.13
 */

$lng['mysql']['description'] = 'Here you can create and change your MySQL-Databases.<br />The changes are made instantly and the database can be used immediately.<br />At the menu on the left side you find the tool phpMyAdmin with which you can easily administer your database.<br /><br />To use your databases in your own php-scripts use the following settings: (The data in <i>italics</i> have to be changed into the equivalents you typed in!)<br />Hostname: <b><SQL_HOST></b><br />Username: <b><i>Databasename</i></b><br />Password: <b><i>the password you\'ve chosen</i></b><br />Database: <b><i>Databasename</i></b>';

/**
 * ADDED BETWEEN 1.2.12 and 1.2.13
 */

$lng['admin']['cronlastrun'] = 'Last Cron';
$lng['serversettings']['apacheconf_filename']['title'] = 'Apache configuration filename';
$lng['serversettings']['apacheconf_filename']['description'] = 'How should the apache configuration file called?';
$lng['serversettings']['paging']['title'] = 'Entries per page';
$lng['serversettings']['paging']['description'] = 'How many entries shall be shown on one page? (0 = disable paging)';
$lng['error']['ipstillhasdomains'] = 'The IP/Port combination you want to delete still has domains assigned to it, please reassign those to other IP/Port combinations before deleting this IP/Port combination.';
$lng['error']['cantdeletedefaultip'] = 'You cannot delete the default reseller IP/Port combination, please make another IP/Port combination default for resellers before deleting this IP/Port combination.';
$lng['error']['cantdeletesystemip'] = 'You cannot delete the last system IP, either create a new IP/Port combination for the system IP or change the system IP.';
$lng['error']['myipaddress'] = '\'IP\'';
$lng['error']['myport'] = '\'Port\'';
$lng['error']['myipdefault'] = 'You need to select an IP/Port combination that should become default.';
$lng['error']['myipnotdouble'] = 'This IP/Port combination already exists.';
$lng['question']['admin_ip_reallydelete'] = 'Do you really want to delete the IP address %s?';
$lng['admin']['ipsandports']['ipsandports'] = 'IPs and Ports';
$lng['admin']['ipsandports']['add'] = 'Add IP/Port';
$lng['admin']['ipsandports']['edit'] = 'Edit IP/Port';
$lng['admin']['ipsandports']['ipandport'] = 'IP/Port';
$lng['admin']['ipsandports']['ip'] = 'IP';
$lng['admin']['ipsandports']['port'] = 'Port';

// ADDED IN 1.2.13-rc3

$lng['error']['cantchangesystemip'] = 'You cannot change the last system IP, either create another new IP/Port combination for the system IP or change the system IP.';
$lng['question']['admin_domain_reallydocrootoutofcustomerroot'] = 'Are you sure, you want the document root for this domain, not being within the customerroot of the customer?';

// ADDED IN 1.2.14-rc1

$lng['admin']['memorylimitdisabled'] = 'Disabled';
$lng['domain']['openbasedirpath'] = 'OpenBasedir-path';
$lng['domain']['docroot'] = 'Path from field above';
$lng['domain']['homedir'] = 'Home directory';
$lng['admin']['valuemandatory'] = 'This value is mandatory';
$lng['admin']['valuemandatorycompany'] = 'Either &quot;name&quot; and &quot;firstname&quot; or &quot;company&quot; must be filled';
$lng['menue']['main']['username'] = 'Logged in as: ';
$lng['panel']['urloverridespath'] = 'URL (overrides path)';
$lng['panel']['pathorurl'] = 'Path or URL';
$lng['error']['sessiontimeoutiswrong'] = 'Only numerical &quot;Session Timeout&quot; is allowed.';
$lng['error']['maxloginattemptsiswrong'] = 'Only numerical &quot;Max Login Attempts&quot; are allowed.';
$lng['error']['deactivatetimiswrong'] = 'Only numerical &quot;Deactivate Time&quot; is allowed.';
$lng['error']['accountprefixiswrong'] = 'The &quot;Customerprefix&quot; is wrong.';
$lng['error']['mysqlprefixiswrong'] = 'The &quot;SQL Prefix&quot; is wrong.';
$lng['error']['ftpprefixiswrong'] = 'The &quot;FTP Prefix&quot; is wrong.';
$lng['error']['ipiswrong'] = 'The &quot;IP-Address&quot; is wrong. Only a valid IP-address is allowed.';
$lng['error']['vmailuidiswrong'] = 'The &quot;Mails-uid&quot; is wrong. Only a numerical UID is allowed.';
$lng['error']['vmailgidiswrong'] = 'The &quot;Mails-gid&quot; is wrong. Only a numerical GID is allowed.';
$lng['error']['adminmailiswrong'] = 'The &quot;Sender-address&quot; is wrong. Only a valid email-address is allowed.';
$lng['error']['pagingiswrong'] = 'The &quot;Entries per Page&quot;-value is wrong. Only numerical characters are allowed.';
$lng['error']['phpmyadminiswrong'] = 'The phpMyAdmin-link is not a valid link.';
$lng['error']['webmailiswrong'] = 'The WebMail-link is not a valid link.';
$lng['error']['webftpiswrong'] = 'The WebFTP-link is not a valid link.';
$lng['domains']['hasaliasdomains'] = 'Has alias domain(s)';
$lng['serversettings']['defaultip']['title'] = 'Default IP/Port';
$lng['serversettings']['defaultip']['description'] = 'What\'s the default IP/Port combination?';
$lng['domains']['statstics'] = 'Usage Statistics';
$lng['panel']['ascending'] = 'ascending';
$lng['panel']['decending'] = 'decending';
$lng['panel']['search'] = 'Search';
$lng['panel']['used'] = 'used';

// ADDED IN 1.2.14-rc3

$lng['panel']['translator'] = 'Translator';

// ADDED IN 1.2.14-rc4

$lng['error']['stringformaterror'] = 'The value for the field &quot;%s&quot; is not in the expected format.';

// ADDED IN 1.2.15-rc1

$lng['admin']['serversoftware'] = 'Serversoftware';
$lng['admin']['phpversion'] = 'PHP-Version';
$lng['admin']['phpmemorylimit'] = 'PHP-Memory-Limit';
$lng['admin']['mysqlserverversion'] = 'MySQL Server Version';
$lng['admin']['mysqlclientversion'] = 'MySQL Client Version';
$lng['admin']['webserverinterface'] = 'Webserver Interface';
$lng['domains']['isassigneddomain'] = 'Is assigned domain';
$lng['serversettings']['phpappendopenbasedir']['title'] = 'Paths to append to OpenBasedir';
$lng['serversettings']['phpappendopenbasedir']['description'] = 'These paths (separated by colons) will be added to the OpenBasedir-statement in every vhost-container.';

// CHANGED IN 1.2.15-rc1

$lng['error']['loginnameissystemaccount'] = 'You cannot create accounts which are similar to systemaccounts (as for example begin with &quot;%s&quot;). Please enter another accountname.';
$lng['error']['youcantdeleteyourself'] = 'You cannot delete yourself for security reasons.';
$lng['error']['youcanteditallfieldsofyourself'] = 'Note: You cannot edit all fields of your own account for security reasons.';

// ADDED IN 1.2.16-svn1

$lng['serversettings']['natsorting']['title'] = 'Use natural human sorting in list view';
$lng['serversettings']['natsorting']['description'] = 'Sorts lists as web1 -> web2 -> web11 instead of web1 -> web11 -> web2.';

// ADDED IN 1.2.16-svn2

$lng['serversettings']['deactivateddocroot']['title'] = 'Docroot for deactivated users';
$lng['serversettings']['deactivateddocroot']['description'] = 'When a user is deactivated this path is used as his docroot. Leave empty for not creating a vhost at all.';

// ADDED IN 1.2.16-svn4

$lng['panel']['reset'] = 'discard changes';
$lng['admin']['accountsettings'] = 'Account settings';
$lng['admin']['panelsettings'] = 'Panel settings';
$lng['admin']['systemsettings'] = 'System settings';
$lng['admin']['webserversettings'] = 'Webserver settings';
$lng['admin']['mailserversettings'] = 'Mailserver settings';
$lng['admin']['nameserversettings'] = 'Nameserver settings';
$lng['admin']['updatecounters'] = 'Recalculate resource usage';
$lng['question']['admin_counters_reallyupdate'] = 'Do you really want to recalculate resource usage?';
$lng['panel']['pathDescription'] = 'If the directory doesn\'t exist, it will be created automatically.';

// ADDED IN 1.2.16-svn6

$lng['mails']['trafficninetypercent']['mailbody'] = 'Dear {NAME},\n\nYou used {TRAFFICUSED} MB of your available {TRAFFIC} MB of traffic.\nThis are more than 90%.\n\nYours sincerely, the SysCP-Team';
$lng['mails']['trafficninetypercent']['subject'] = 'Reaching your trafficlimit';
$lng['admin']['templates']['trafficninetypercent'] = 'Notification mail for customers when ninety percent of traffic is exhausted';
$lng['admin']['templates']['TRAFFIC'] = 'Replaced with the traffic, which was assigned to the customer.';
$lng['admin']['templates']['TRAFFICUSED'] = 'Replaced with the traffic, which was exhausted by the customer.';

// ADDED IN 1.2.16-svn7

$lng['admin']['ipsandports']['createvhostcontainer'] = 'Create vHost-Container';
$lng['admin']['subcanemaildomain']['never'] = 'Never';
$lng['admin']['subcanemaildomain']['choosableno'] = 'Choosable, default no';
$lng['admin']['subcanemaildomain']['choosableyes'] = 'Choosable, default yes';
$lng['admin']['subcanemaildomain']['always'] = 'Always';
$lng['changepassword']['also_change_webalizer'] = ' also change password of the webalizer statistics';

// ADDED IN 1.2.16-svn8

$lng['serversettings']['mailpwcleartext']['title'] = 'Also save passwords of mail accounts unencrypted in database';
$lng['serversettings']['mailpwcleartext']['description'] = 'If this is set to yes, all passwords will also be saved unencrypted (clearext, plain readable for everyone with database access) in the mail_users-table. Only activate this if you really need it!';
$lng['serversettings']['mailpwcleartext']['removelink'] = 'Click here to wipe all unencrypted passwords from the table.';
$lng['question']['admin_cleartextmailpws_reallywipe'] = 'Do you really want to wipe all unencrypted mail account passwords from the table mail_users? This cannot be reverted!';
$lng['admin']['configfiles']['overview'] = 'Overview';
$lng['admin']['configfiles']['wizard'] = 'Wizard';
$lng['admin']['configfiles']['distribution'] = 'Distribution';
$lng['admin']['configfiles']['service'] = 'Service';
$lng['admin']['configfiles']['daemon'] = 'Daemon';
$lng['admin']['configfiles']['http'] = 'Webserver (HTTP)';
$lng['admin']['configfiles']['dns'] = 'Nameserver (DNS)';
$lng['admin']['configfiles']['mail'] = 'Mailserver (POP3/IMAP)';
$lng['admin']['configfiles']['smtp'] = 'Mailserver (SMTP)';
$lng['admin']['configfiles']['ftp'] = 'FTP-Server';
$lng['admin']['configfiles']['etc'] = 'Others (System)';
$lng['admin']['configfiles']['choosedistribution'] = '-- Choose a distribution --';
$lng['admin']['configfiles']['chooseservice'] = '-- Choose a service --';
$lng['admin']['configfiles']['choosedaemon'] = '-- Choose a daemon --';
$lng['admin']['trafficlastrun'] = 'Last Traffic calculation';

// ADDED IN 1.2.16-svn10

$lng['serversettings']['ftpdomain']['title'] = 'FTP accounts @domain';
$lng['serversettings']['ftpdomain']['description'] = 'Customers can create Ftp accounts user@customerdomain?';

?>
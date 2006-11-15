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

$lng['SysCP']['domains']['add'] = 'Create domain';
$lng['SysCP']['domains']['aliasdomain'] = 'Alias for domain';
$lng['SysCP']['domains']['apacheaccesslogfile'] = 'Apache Access Logfile';
$lng['SysCP']['domains']['apacheerrorlogfile'] = 'Apache Error Logfile';
$lng['SysCP']['domains']['customer'] = 'Customer';
$lng['SysCP']['domains']['description'] = 'Here you can create (sub-)domains and change their paths.<br />The system will need some time to apply the new settings after every change.';
$lng['SysCP']['domains']['documentroot'] = 'Documentroot';
$lng['SysCP']['domains']['domain'] = 'Domain';
$lng['SysCP']['domains']['domainname'] = 'Domain name';
$lng['SysCP']['domains']['domains'] = 'Domains';
$lng['SysCP']['domains']['domainsettings'] = 'Domain settings';
$lng['SysCP']['domains']['edit'] = 'Edit domain';
$lng['SysCP']['domains']['emaildomain'] = 'Emaildomain';
$lng['SysCP']['domains']['ipport'] = 'IP/Port';
$lng['SysCP']['domains']['nameserver'] = 'Nameserver';
$lng['SysCP']['domains']['noaliasdomain'] = 'No alias domain';
$lng['SysCP']['domains']['openbasedir'] = 'OpenBasedir';
$lng['SysCP']['domains']['ownvhostsettings'] = 'Own vHost-Settings';
$lng['SysCP']['domains']['resources'] = 'Resources';
$lng['SysCP']['domains']['safemode'] = 'SafeMode';
$lng['SysCP']['domains']['settings'] = 'Settings';
$lng['SysCP']['domains']['subdomain_add'] = 'Create subdomain';
$lng['SysCP']['domains']['subdomain_edit'] = 'Edit (sub)domain';
$lng['SysCP']['domains']['subdomainforemail'] = 'Subdomains as emaildomains';
$lng['SysCP']['domains']['wildcarddomain'] = 'Create as wildcarddomain?';
$lng['SysCP']['domains']['zonefile'] = 'Zonefile';

/**
 * Errors & Questions
 */

$lng['SysCP']['domains']['error']['adduserfirst'] = 'Please create a customer first';
$lng['SysCP']['domains']['error']['cantdeletedomainwithemail'] = 'You cannot delete a domain which is used as an email-domain. Delete all email addresses first.';
$lng['SysCP']['domains']['error']['cantdeletemaindomain'] = 'You cannot delete a domain which is used as an email-domain.';
$lng['SysCP']['domains']['error']['canteditdomain'] = 'You cannot edit this domain. It has been disabled by the admin.';
$lng['SysCP']['domains']['error']['domainalreadyexists'] = 'The domain %s is already assigned to a customer';
$lng['SysCP']['domains']['error']['domaincantbeempty'] = 'The domain-name can not be empty.';
$lng['SysCP']['domains']['error']['domainexistalready'] = 'The domain %s already exists.';
$lng['SysCP']['domains']['error']['domainisaliasorothercustomer'] = 'The selected alias domain is either itself an alias domain or belongs to another customer.';
$lng['SysCP']['domains']['error']['firstdeleteallsubdomains'] = 'You have to delete all Subdomains first before you can create a wildcard domain.';
$lng['SysCP']['domains']['error']['maindomainnonexist'] = 'The main-domain %s does not exist.';
$lng['SysCP']['domains']['error']['subdomainiswrong'] = 'The subdomain %s contains invalid characters.';
$lng['SysCP']['domains']['error']['wwwnotallowed'] = 'www is not allowed for subdomains.';
$lng['SysCP']['domains']['question']['reallydelete'] = 'Do you really want to delete the domain %s?';
$lng['SysCP']['domains']['question']['reallydisablesecuritysetting'] = 'Do you really want to deactivate these Securitysettings (OpenBasedir and/or SafeMode)?';
$lng['SysCP']['domains']['question']['reallydocrootoutofcustomerroot'] = 'Are you sure, you want the document root for this domain, not being within the customerroot of the customer?';

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

$lng['SysCP']['domains']['add'] = 'Domain anlegen';
$lng['SysCP']['domains']['aliasdomain'] = 'Alias f&uuml;r Domain';
$lng['SysCP']['domains']['noaliasdomain'] = 'Keine Alias Domain';
$lng['SysCP']['domains']['apacheaccesslogfile'] = 'Apache-Zugriffs-Logfile';
$lng['SysCP']['domains']['apacheerrorlogfile'] = 'Apache-Fehler-Logfile';
$lng['SysCP']['domains']['customer'] = 'Kunde';
$lng['SysCP']['domains']['description'] = 'Hier k&ouml;nnen Sie (Sub-)Domains erstellen und deren Pfade &auml;ndern.<br />Nach jeder &Auml;nderung braucht das System etwas Zeit um die Konfiguration neu einzulesen.';
$lng['SysCP']['domains']['documentroot'] = 'Documentroot';
$lng['SysCP']['domains']['domain'] = 'Domain';
$lng['SysCP']['domains']['domainname'] = 'Domainname';
$lng['SysCP']['domains']['domains'] = 'Domains';
$lng['SysCP']['domains']['domainsettings'] = 'Domaineinstellungen';
$lng['SysCP']['domains']['edit'] = 'Domain bearbeiten';
$lng['SysCP']['domains']['emaildomain'] = 'eMaildomain';
$lng['SysCP']['domains']['ipport'] = 'IP/Port';
$lng['SysCP']['domains']['nameserver'] = 'Nameserver';
$lng['SysCP']['domains']['openbasedir'] = 'OpenBasedir';
$lng['SysCP']['domains']['ownvhostsettings'] = 'Eigene vHost-Einstellungen';
$lng['SysCP']['domains']['resources'] = 'Ressourcen';
$lng['SysCP']['domains']['safemode'] = 'SafeMode';
$lng['SysCP']['domains']['settings'] = 'Einstellungen';
$lng['SysCP']['domains']['subdomain_add'] = 'Subdomain anlegen';
$lng['SysCP']['domains']['subdomain_edit'] = '(Sub-)Domain bearbeiten';
$lng['SysCP']['domains']['subdomainforemail'] = 'Subdomains als E-Mail-Domains';
$lng['SysCP']['domains']['wildcarddomain'] = 'Als Wildcarddomain eintragen?';
$lng['SysCP']['domains']['zonefile'] = 'Zonefile';

/**
 * Errors & Questions
 */

$lng['SysCP']['domains']['error']['adduserfirst'] = 'Sie m&uuml;ssen zuerst einen Kunden anlegen.';
$lng['SysCP']['domains']['error']['cantdeletedomainwithemail'] = 'Sie k&ouml;nnen keine Domain l&ouml;schen die noch als E-Mail-Domain verwendet wird. L&ouml;schen Sie zuerst alle E-Mail-Adressen dieser Domain.';
$lng['SysCP']['domains']['error']['cantdeletemaindomain'] = 'Sie k&ouml;nnen keine Domain, die als E-Mail-Domain verwendet wird l&ouml;schen. ';
$lng['SysCP']['domains']['error']['canteditdomain'] = 'Sie k&ouml;nnen diese Domain nicht bearbeiten. Dies wurde durch den Admin verweigert';
$lng['SysCP']['domains']['error']['domainalreadyexists'] = 'Die Domain %s wurde bereits einem Kunden zugeordnet.';
$lng['SysCP']['domains']['error']['domaincantbeempty'] = 'Der Domain-Name darf nicht leer sein.';
$lng['SysCP']['domains']['error']['domainexistalready'] = 'Die Domain %s existiert bereits.';
$lng['SysCP']['domains']['error']['domainisaliasorothercustomer'] = 'Die ausgew&auml;hlte Aliasdomain ist entweder selber eine Aliasdomain oder geh&ouml;rt zu einem anderen Kunden.';
$lng['SysCP']['domains']['error']['firstdeleteallsubdomains'] = 'Sie m&uuml;ssen erst alle Subdomains l&ouml;schen, bevor Sie eine Wildcarddomain anlegen k&ouml;nnen.';
$lng['SysCP']['domains']['error']['maindomainnonexist'] = 'Die Haupt-Domain %s existiert nicht.';
$lng['SysCP']['domains']['error']['subdomainiswrong'] = 'Die Subdomain %s enth&auml;lt ung&uuml;ltige Zeichen.';
$lng['SysCP']['domains']['error']['wwwnotallowed'] = 'Ihre Subdomain darf nicht www heissen.';
$lng['SysCP']['domains']['question']['reallydelete'] = 'M&ouml;chten Sie die Domain &quot;%s&quot; wirklich l&ouml;schen?';
$lng['SysCP']['domains']['question']['reallydisablesecuritysetting'] = 'M&ouml;chten Sie die Sicherheitseinstellungen (OpenBasedir und/oder SafeMode)?';
$lng['SysCP']['domains']['question']['reallydocrootoutofcustomerroot'] = 'Sind Sie sicher, daﬂ das Documentroot f&uuml;r diese Domain nicht in dem Kundenverzeichnis das Kunden liegen soll?';

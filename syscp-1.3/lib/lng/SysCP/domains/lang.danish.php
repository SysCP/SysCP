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

$lng['SysCP']['domains']['add'] = 'Opret domæne';
$lng['SysCP']['domains']['aliasdomain'] = 'Domæne alias';
$lng['SysCP']['domains']['customer'] = 'Kunde';
$lng['SysCP']['domains']['description'] = 'Her kan du oprette domæner og subdomæner i systemet, og ændre de stier som er tilknyttet<br />Det tager et stykke tid for ændringer at blive opdateret i systemet.';
$lng['SysCP']['domains']['domainname'] = 'Domæne navn';
$lng['SysCP']['domains']['domains'] = 'Domæner';
$lng['SysCP']['domains']['domainsettings'] = 'Domæne indstillinger';
$lng['SysCP']['domains']['edit'] = 'Editer domæne';
$lng['SysCP']['domains']['ownvhostsettings'] = 'Egne vHost-indstillinger';
$lng['SysCP']['domains']['resources'] = 'Ressourcer';
$lng['SysCP']['domains']['subdomain_add'] = 'Opræt suddomæne';
$lng['SysCP']['domains']['subdomain_edit'] = 'Editer (sub)domæne';
$lng['SysCP']['domains']['subdomainforemail'] = 'Subdomæner som eMaildomæner';
$lng['SysCP']['domains']['wildcarddomain'] = 'Opret som wildcarddomæne?';

/**
 * Errors & Questions
 */

$lng['SysCP']['domains']['error']['adduserfirst'] = 'Opret venligst en kunde først';
$lng['SysCP']['domains']['error']['cantdeletedomainwithemail'] = 'Du kan ikke slette et domæne med tilknyttede eMail-adresser. Slet alle email adresser først.';
$lng['SysCP']['domains']['error']['cantdeletemaindomain'] = 'Du kan ikke slette et eMail-domæne.';
$lng['SysCP']['domains']['error']['canteditdomain'] = 'Du kan ikke lave ændringer i dette domæne, da det er blevet låst af administratoren.';
$lng['SysCP']['domains']['error']['domainalreadyexists'] = 'Domænet %s er allerede delligeret til en kunde';
$lng['SysCP']['domains']['error']['domaincantbeempty'] = 'The domain-name can not be empty.';
$lng['SysCP']['domains']['error']['domainexistalready'] = 'Domænet %s eksisterer allerede.';
$lng['SysCP']['domains']['error']['domainisaliasorothercustomer'] = 'Det valgte alias-domæne er enten selv et alias domæne, eller tilhører en anden kunde.';
$lng['SysCP']['domains']['error']['firstdeleteallsubdomains'] = 'Du skal først slette alle sub-domæner for du kan oprette et wildcarddomæne.';
$lng['SysCP']['domains']['error']['maindomainnonexist'] = 'Hoved-domænet %s eksisterer ikke.';
$lng['SysCP']['domains']['error']['subdomainiswrong'] = 'Sub-domænet %s indeholder ugyldige tegn.';
$lng['SysCP']['domains']['error']['wwwnotallowed'] = 'www er ikke tilladt som sub-domæne.';

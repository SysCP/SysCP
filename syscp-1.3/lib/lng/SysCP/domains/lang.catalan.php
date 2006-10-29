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

$lng['SysCP']['domains']['add'] = 'Crear domini';
$lng['SysCP']['domains']['aliasdomain'] = 'Sobrenom per a aquest domini';
$lng['SysCP']['domains']['customer'] = 'Client';
$lng['SysCP']['domains']['description'] = 'Des d\'aquí pots crear (sub)dominis i canviar les seves rutes.<br />El sistema necessitarà una mica de temps per aplicar els nous canvis un cop efectuats.';
$lng['SysCP']['domains']['domainname'] = 'Nom del domini';
$lng['SysCP']['domains']['domains'] = 'Dominis';
$lng['SysCP']['domains']['domainsettings'] = 'Opcions de domini';
$lng['SysCP']['domains']['edit'] = 'Editar domini';
$lng['SysCP']['domains']['ownvhostsettings'] = 'Opcions dels vhost propis';
$lng['SysCP']['domains']['resources'] = 'Personal';
$lng['SysCP']['domains']['subdomain_add'] = 'Crear subdomini';
$lng['SysCP']['domains']['subdomain_edit'] = 'Editar (sub)domini';
$lng['SysCP']['domains']['subdomainforemail'] = 'Subdomini com a subdomini de correu';
$lng['SysCP']['domains']['wildcarddomain'] = 'Crear un domini comodí? (wildcarddomain)';

/**
 * Errors & Questions
 */

$lng['SysCP']['domains']['error']['adduserfirst'] = 'Abans has de crear un client';
$lng['SysCP']['domains']['error']['cantdeletedomainwithemail'] = 'No pots esborrar aquest domini perquè està sent usat per una direcció de correu. Has d\'esborrar abans la direcció de correu';
$lng['SysCP']['domains']['error']['cantdeletemaindomain'] = 'No pots esborrar aquest domini perquè està sent usat en una adreça d\'email.';
$lng['SysCP']['domains']['error']['canteditdomain'] = 'No pots editar aquests dominis. Han estat bloquejats per l\'administrador';
$lng['SysCP']['domains']['error']['domainalreadyexists'] = 'El domini %s ja està assignat a un client';
$lng['SysCP']['domains']['error']['domaincantbeempty'] = 'El domini no pot ser un camp buit.';
$lng['SysCP']['domains']['error']['domainexistalready'] = 'Ja existeix el domini %s.';
$lng['SysCP']['domains']['error']['domainisaliasorothercustomer'] = 'El sobrenom de domini escollit ja existeix, o pertany a un altre client.';
$lng['SysCP']['domains']['error']['firstdeleteallsubdomains'] = 'No pots esborrar tots els subdominis si no tens un domini comodí (Wildcarddomain).';
$lng['SysCP']['domains']['error']['maindomainnonexist'] = 'El domini %s no existeix.';
$lng['SysCP']['domains']['error']['subdomainiswrong'] = 'El subdomini %s conté caràcters invàlids.';
$lng['SysCP']['domains']['error']['wwwnotallowed'] = 'www no és un subdomini permès.';

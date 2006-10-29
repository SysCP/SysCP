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

$lng['SysCP']['domains']['add'] = 'Crea dominio';
$lng['SysCP']['domains']['aliasdomain'] = 'Alias per questo dominio';
$lng['SysCP']['domains']['customer'] = 'Cliente';
$lng['SysCP']['domains']['description'] = 'Qui puoi creare (sotto)domini e cambiare il loro percorso.<br />Il sistema, dopo ogni cambiamento, necessita di un po\' di tempo per applicare le nuove impostazioni.';
$lng['SysCP']['domains']['domainname'] = 'Nome del dominio';
$lng['SysCP']['domains']['domains'] = 'Domini';
$lng['SysCP']['domains']['domainsettings'] = 'Opzioni del dominio';
$lng['SysCP']['domains']['edit'] = 'Modifica dominio';
$lng['SysCP']['domains']['ownvhostsettings'] = 'Impostazioni vHost speciali';
$lng['SysCP']['domains']['resources'] = 'Risorse';
$lng['SysCP']['domains']['subdomain_add'] = 'Crea sottodominio';
$lng['SysCP']['domains']['subdomain_edit'] = 'Modifica il (sotto)dominio';
$lng['SysCP']['domains']['subdomainforemail'] = 'Sottodominio utilizzabile come dominio Email';
$lng['SysCP']['domains']['wildcarddomain'] = 'Crea una wildcarddomain?';

/**
 * Errors & Questions
 */

$lng['SysCP']['domains']['error']['adduserfirst'] = 'Per favore crea prima un utente ...';
$lng['SysCP']['domains']['error']['cantdeletedomainwithemail'] = 'Non puoi cancellare un dominio usato come dominio Email. Cancella prima tutti gli indirizzi Email che lo utilizzano.';
$lng['SysCP']['domains']['error']['cantdeletemaindomain'] = 'Non puoi cancellare un dominio usato come dominio Email.';
$lng['SysCP']['domains']['error']['canteditdomain'] = 'Non puoi modificare questo dominio. La funzione è stata disabilitata dall\'admin.';
$lng['SysCP']['domains']['error']['domainalreadyexists'] = 'Il dominio %s è già assegnato ad un cliente.';
$lng['SysCP']['domains']['error']['domaincantbeempty'] = 'Il nome dominio non può essere vuoto.';
$lng['SysCP']['domains']['error']['domainexistalready'] = 'Il dominio %s esiste già.';
$lng['SysCP']['domains']['error']['domainisaliasorothercustomer'] = 'Il dominio alias selezionato è a sua volta un dominio alias o appartiene ad un altro cliente.';
$lng['SysCP']['domains']['error']['firstdeleteallsubdomains'] = 'Prima di creare un dominio wildcard, cancella tutti i sottodomini presenti per quel dominio.';
$lng['SysCP']['domains']['error']['maindomainnonexist'] = 'Il dominio principale %s non esiste.';
$lng['SysCP']['domains']['error']['subdomainiswrong'] = 'Il sottodominio %s contiene caratteri invalidi.';
$lng['SysCP']['domains']['error']['wwwnotallowed'] = 'www non è ammesso come sottodominio.';

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

$lng['SysCP']['domains']['add'] = 'Ajouter un Domaine';
$lng['SysCP']['domains']['aliasdomain'] = 'Pseudonyme pour un domaine';
$lng['SysCP']['domains']['customer'] = 'Compte';
$lng['SysCP']['domains']['description'] = 'Ici vous pouvez inscrire des Domaines et changer ses chemins.<br />Il faut un peu de temps après chaque changement pour relire la configuration.';
$lng['SysCP']['domains']['domainname'] = 'Nom du Domaine';
$lng['SysCP']['domains']['domains'] = 'Domaines';
$lng['SysCP']['domains']['domainsettings'] = 'Configuration des Domaines';
$lng['SysCP']['domains']['edit'] = 'Modifier le domaine';
$lng['SysCP']['domains']['ownvhostsettings'] = 'Configuration spéciale du vHost';
$lng['SysCP']['domains']['noaliasdomain'] = 'Domaine non-pseudonyme';
$lng['SysCP']['domains']['resources'] = 'Réssources';
$lng['SysCP']['domains']['subdomain_add'] = 'Ajouter un Sous-domaine';
$lng['SysCP']['domains']['subdomain_edit'] = 'Changer un Sous-domaine';
$lng['SysCP']['domains']['subdomainforemail'] = 'Sous-domaines comme domaine e-mail';
$lng['SysCP']['domains']['wildcarddomain'] = 'Domaine Wildcard?';

/**
 * Errors & Questions
 */

$lng['SysCP']['domains']['error']['adduserfirst'] = 'Vous devez ajouter un compte avant.';
$lng['SysCP']['domains']['error']['cantdeletedomainwithemail'] = 'Vous ne pouvez pas effacer un domaine qui est utilisé pour des e-mails. Il faut effacer toutes ses adresses avant.';
$lng['SysCP']['domains']['error']['cantdeletemaindomain'] = 'Vous ne pouvez pas effacer un domaine qui est utilisé pour des adresses e-mail. ';
$lng['SysCP']['domains']['error']['canteditdomain'] = 'Vous n\'avez pas le droit de configurer ce domaine.';
$lng['SysCP']['domains']['error']['domainalreadyexists'] = 'Vous avez déjà appliqué le domaine %s.';
$lng['SysCP']['domains']['error']['domaincantbeempty'] = 'Le nom de domaine ne doit pas être vide.';
$lng['SysCP']['domains']['error']['domainexistalready'] = 'Le domaine %s existe déjà.';
$lng['SysCP']['domains']['error']['domainisaliasorothercustomer'] = 'Le domaine pseudonyme choisi est un domaine pseudonyme soi-même ou fait partie d\'un autre client.';
$lng['SysCP']['domains']['error']['firstdeleteallsubdomains'] = 'Il faut effacer tous les sous-domaines avant d\'ajouter un domaine Wildcard.';
$lng['SysCP']['domains']['error']['maindomainnonexist'] = 'Le domaine %s n\'existe pas.';
$lng['SysCP']['domains']['error']['subdomainiswrong'] = 'Le sous-domaine %s contient des signes invalides.';
$lng['SysCP']['domains']['error']['wwwnotallowed'] = 'Un sous-domaine ne doit pas s\'appeler www.';

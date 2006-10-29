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

$lng['SysCP']['domains']['add'] = 'Crear el dominio';
$lng['SysCP']['domains']['aliasdomain'] = 'Alias para dominio';
$lng['SysCP']['domains']['customer'] = 'Cliente';
$lng['SysCP']['domains']['description'] = 'Aquí usted puede crear dominios (secundarios) y cambiar sus caminos.<br />El sistema necesitará un cierto tiempo para aplicar las nuevas configuraciones después de cada cambio.';
$lng['SysCP']['domains']['domainname'] = 'Nombre del dominio';
$lng['SysCP']['domains']['domains'] = 'Dominios';
$lng['SysCP']['domains']['domainsettings'] = 'Configuraciones del dominio';
$lng['SysCP']['domains']['edit'] = 'Corrija el dominio';
$lng['SysCP']['domains']['ownvhostsettings'] = 'vHost-Configuraciones propias';
$lng['SysCP']['domains']['resources'] = 'Recursos';
$lng['SysCP']['domains']['subdomain_add'] = 'Crear el secundario-dominio';
$lng['SysCP']['domains']['subdomain_edit'] = 'Corrija el dominio (secundario)';
$lng['SysCP']['domains']['subdomainforemail'] = 'dominio-secundario como dominio de email';
$lng['SysCP']['domains']['wildcarddomain'] = '¿Crear como comodÃn-dominio?';

/**
 * Errors & Questions
 */

$lng['SysCP']['domains']['error']['adduserfirst'] = 'Usted debe primero crear un Cliente';
$lng['SysCP']['domains']['error']['cantdeletedomainwithemail'] = 'Usted no puede Borar un Domain el cual esta siendo usado como e-mail Domain , Borre primero todos las Direcciones e-mail de este dominio.';
$lng['SysCP']['domains']['error']['cantdeletemaindomain'] = 'Usted no puede Borar un Domain el cual esta siendo usado como e-mail Domain.';
$lng['SysCP']['domains']['error']['canteditdomain'] = 'Usted no puede trabajar con este Domain . Debido a que el Admin se lo niega.';
$lng['SysCP']['domains']['error']['domainalreadyexists'] = 'El dominio %s se ha asignado ya a un cliente';
$lng['SysCP']['domains']['error']['domaincantbeempty'] = 'El nombre del dominio-Apellido no puede estar Vacio.';
$lng['SysCP']['domains']['error']['domainexistalready'] = 'El dominio %s existe ya.';
$lng['SysCP']['domains']['error']['domainisaliasorothercustomer'] = 'El alias de dominio seleccionado es un propio alias de dominio o pertenece a otro cliente.';
$lng['SysCP']['domains']['error']['firstdeleteallsubdomains'] = 'Usted debe primero borar todos los Subdomains, antes de Usted crear un dominio del comodÃn.';
$lng['SysCP']['domains']['error']['maindomainnonexist'] = 'El dominio-principal %s no existe.';
$lng['SysCP']['domains']['error']['subdomainiswrong'] = 'El dominio-secundario %s contiene caracteres inválidos.';
$lng['SysCP']['domains']['error']['wwwnotallowed'] = 'www no se permite como nombre para los secundario-dominios.';

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

$lng['SysCP']['email']['account'] = 'Account';
$lng['SysCP']['email']['account_add'] = 'Create account';
$lng['SysCP']['email']['account_delete'] = 'Delete account';
$lng['SysCP']['email']['add'] = 'Create eMail-address';
$lng['SysCP']['email']['catchall'] = 'Catchall';
$lng['SysCP']['email']['description'] = 'Here you can create and change your eMail addresses.<br />An account is like your letterbox in front of your house. If someone sends you an email, it will be dropped into the account.<br /><br />To download your emails use the following settings in your mailprogram: (The data in <i>italics</i> has to be changed to the equivalents you typed in!)<br />Hostname: <b><i>Domainname</i></b><br />Username: <b><i>Account name / eMail address</i></b><br />Password: <b><i>the password you have chosen</i></b>';
$lng['SysCP']['email']['edit'] = 'Edit eMail-address';
$lng['SysCP']['email']['email'] = 'eMail';
$lng['SysCP']['email']['emailaddress'] = 'eMail-address';
$lng['SysCP']['email']['emails'] = 'Addresses';
$lng['SysCP']['email']['forwarder_add'] = 'Create forwarder';
$lng['SysCP']['email']['forwarders'] = 'Forwarders';
$lng['SysCP']['email']['from'] = 'Source';
$lng['SysCP']['email']['iscatchall'] = 'Define as catchall-address?';
$lng['SysCP']['email']['to'] = 'Destination';

/**
 * Errors & Questions
 */

$lng['SysCP']['email']['error']['destinationalreadyexist'] = 'You have already defined a forwarder to %s .';
$lng['SysCP']['email']['error']['destinationalreadyexistasmail'] = 'The forwarder to %s already exists as active EMail-Address.';
$lng['SysCP']['email']['error']['destinationiswrong'] = 'The forwarder %s contains invalid character(s) or is incomplete.';
$lng['SysCP']['email']['error']['destinationnonexist'] = 'Please create your forwarder in the field \'Destination\'.';
$lng['SysCP']['email']['error']['domaincantbeempty'] = 'The domain-name can not be empty.';
$lng['SysCP']['email']['error']['emailexistalready'] = 'The eMail-Address %s already exists.';
$lng['SysCP']['email']['error']['emailiswrong'] = 'eMail-Address %s contains invalid characters or is incomplete';
$lng['SysCP']['email']['error']['maindomainnonexist'] = 'The main-domain %s does not exist.';
$lng['SysCP']['email']['error']['youhavealreadyacatchallforthisdomain'] = 'You have already defined a catchall for this domain.';
$lng['SysCP']['email']['question']['reallydelete'] = 'Do you really want to delete the email-address %s?';
$lng['SysCP']['email']['question']['reallydelete_account'] = 'Do you really want to delete the email-account of %s?';
$lng['SysCP']['email']['question']['reallydelete_forwarder'] = 'Do you really want to delete the forwarder %s?';

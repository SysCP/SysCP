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

$lng['SysCP']['email']['account'] = 'Konto';
$lng['SysCP']['email']['account_add'] = 'Account anlegen';
$lng['SysCP']['email']['account_delete'] = 'Konto l&ouml;schen';
$lng['SysCP']['email']['add'] = 'E-Mail-Adresse anlegen';
$lng['SysCP']['email']['catchall'] = 'Catchall';
$lng['SysCP']['email']['description'] = 'Hier k&ouml;nnen Sie Ihre E-Mail Adressen erzeugen und &auml;ndern.<br>Ein Account ist wie ein Briefkasten vor Ihrem Haus. Wenn Ihnen jemand eine E-Mail schreibt, wird sie an diesen Account &uuml;bergeben.<br><br>Um Ihre E-Mails herunterzuladen, verwenden Sie bitte folgende Einstellungen in Ihrem Mailprogramm: (Daten in <i>kursiver</i> Schrift, m&uuml;ssen entsprechend Ihrer Angaben abge&auml;ndert werden!)<br>Hostname: <i><strong>Domainname</strong></i><br>Benutzername: <i><strong>Account Name / E-Mail Adresse</strong></i><br>Passwort: <i><strong>Das von Ihnen gew&auml;hlte Passwort</strong></i>';
$lng['SysCP']['email']['edit'] = 'E-Mail-Adresse &auml;ndern';
$lng['SysCP']['email']['email'] = 'E-Mail';
$lng['SysCP']['email']['emailaddress'] = 'E-Mail-Adresse';
$lng['SysCP']['email']['emails'] = 'Adressen';
$lng['SysCP']['email']['forwarder_add'] = 'Weiterleitung hinzuf&uuml;gen';
$lng['SysCP']['email']['forwarders'] = 'Weiterleitungen';
$lng['SysCP']['email']['from'] = 'Von';
$lng['SysCP']['email']['iscatchall'] = 'Als Catchall-Adresse definieren?';
$lng['SysCP']['email']['to'] = 'Nach';

/**
 * Errors & Questions
 */

$lng['SysCP']['email']['error']['destinationalreadyexist'] = 'Es gibt bereits eine Weiterleitung nach %s .';
$lng['SysCP']['email']['error']['destinationalreadyexistasmail'] = 'Die Weiterleitung zu %s exisitiert bereits als aktive E-Mail-Adresse.';
$lng['SysCP']['email']['error']['destinationiswrong'] = 'Die Weiterleitungsadresse-Adresse %s enth&auml;lt ung&uuml;ltige Zeichen oder ist nicht vollst&auml;ndig.';
$lng['SysCP']['email']['error']['destinationnonexist'] = 'Bitte geben Sie Ihre Weiterleitungsadresse im Feld \'Nach\' ein.';
$lng['SysCP']['email']['error']['domaincantbeempty'] = 'Der Domain-Name darf nicht leer sein.';
$lng['SysCP']['email']['error']['emailexistalready'] = 'Die E-Mail-Adresse %s existiert bereits.';
$lng['SysCP']['email']['error']['emailiswrong'] = 'Die E-Mail-Adresse %s enth&auml;lt ung&uuml;ltige Zeichen oder ist nicht vollst&auml;ndig.';
$lng['SysCP']['email']['error']['maindomainnonexist'] = 'Die Haupt-Domain %s existiert nicht.';
$lng['SysCP']['email']['error']['youhavealreadyacatchallforthisdomain'] = 'Sie haben bereits eine Adresse als Catchall f&uuml;r diese Domain definiert.';
$lng['SysCP']['email']['question']['reallydelete'] = 'M&ouml;chten Sie die eMailadresse &quot;%s&quot; wirklich l&ouml;schen?';
$lng['SysCP']['email']['question']['reallydelete_account'] = 'M&ouml;chten Sie das eMailkonto &quot;%s&quot; wirklich l&ouml;schen?';
$lng['SysCP']['email']['question']['reallydelete_forwarder'] = 'M&ouml;chten Sie wirklich die Weiterleitung zu &quot;%s&quot; l&ouml;schen?';

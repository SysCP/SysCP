<?php

/**
 * This file is part of the SysCP project.
 * Copyright (c) 2003-2008 the SysCP Team (see authors).
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
$lng['panel']['edit'] = 'bearbeiten';
$lng['panel']['delete'] = 'l&ouml;schen';
$lng['panel']['create'] = 'anlegen';
$lng['panel']['save'] = 'Speichern';
$lng['panel']['yes'] = 'Ja';
$lng['panel']['no'] = 'Nein';
$lng['panel']['emptyfornochanges'] = 'leer f&uuml;r keine &Auml;nderung';
$lng['panel']['emptyfordefault'] = 'leer f&uuml;r Standardeinstellung';
$lng['panel']['path'] = 'Pfad';
$lng['panel']['toggle'] = 'Umschalten';
$lng['panel']['next'] = 'weiter';
$lng['panel']['dirsmissing'] = 'Verzeichnisse nicht verf&uuml;gbar oder lesbar';

/**
 * Login
 */

$lng['login']['username'] = 'Benutzername';
$lng['login']['password'] = 'Passwort';
$lng['login']['language'] = 'Sprache';
$lng['login']['login'] = 'Anmelden';
$lng['login']['logout'] = 'Abmelden';
$lng['login']['profile_lng'] = 'Profilsprache';

/**
 * Customer
 */

$lng['customer']['documentroot'] = 'Heimverzeichnis';
$lng['customer']['name'] = 'Name';
$lng['customer']['firstname'] = 'Vorname';
$lng['customer']['company'] = 'Firma';
$lng['customer']['street'] = 'Strasse';
$lng['customer']['zipcode'] = 'PLZ';
$lng['customer']['city'] = 'Ort';
$lng['customer']['phone'] = 'Telephon';
$lng['customer']['fax'] = 'Fax';
$lng['customer']['email'] = 'E-Mail';
$lng['customer']['customernumber'] = 'KundenNummer';
$lng['customer']['diskspace'] = 'Webspace (MB)';
$lng['customer']['traffic'] = 'Traffic (GB)';
$lng['customer']['mysqls'] = 'MySQL-Datenbanken';
$lng['customer']['emails'] = 'E-Mail-Adressen';
$lng['customer']['accounts'] = 'E-Mail-Konten';
$lng['customer']['forwarders'] = 'E-Mail-Weiterleitungen';
$lng['customer']['ftps'] = 'FTP-Konten';
$lng['customer']['subdomains'] = 'Sub-Domain(s)';
$lng['customer']['domains'] = 'Domain(s)';
$lng['customer']['unlimited'] = 'unbeschr&auml;nkt';

/**
 * Customermenue
 */

$lng['menue']['main']['main'] = 'Allgemein';
$lng['menue']['main']['changepassword'] = 'Passwort &auml;ndern';
$lng['menue']['main']['changelanguage'] = 'Sprache &auml;ndern';
$lng['menue']['email']['email'] = 'E-Mail';
$lng['menue']['email']['emails'] = 'Adressen';
$lng['menue']['email']['webmail'] = 'WebMail';
$lng['menue']['mysql']['mysql'] = 'MySQL';
$lng['menue']['mysql']['databases'] = 'Datenbanken';
$lng['menue']['mysql']['phpmyadmin'] = 'phpMyAdmin';
$lng['menue']['domains']['domains'] = 'Domains';
$lng['menue']['domains']['settings'] = 'Einstellungen';
$lng['menue']['ftp']['ftp'] = 'FTP';
$lng['menue']['ftp']['accounts'] = 'Benutzerkonten';
$lng['menue']['ftp']['webftp'] = 'WebFTP';
$lng['menue']['extras']['extras'] = 'Extras';
$lng['menue']['extras']['directoryprotection'] = 'Verzeichnisschutz';
$lng['menue']['extras']['pathoptions'] = 'Pfadoptionen';

/**
 * Index
 */

$lng['index']['customerdetails'] = 'Kundendaten';
$lng['index']['accountdetails'] = 'Accountdaten';

/**
 * Change Password
 */

$lng['changepassword']['old_password'] = 'Altes Passwort';
$lng['changepassword']['new_password'] = 'Neues Passwort';
$lng['changepassword']['new_password_confirm'] = 'Neues Passwort (best&auml;tigen)';
$lng['changepassword']['new_password_ifnotempty'] = 'Neues Passwort (leer = nicht &auml;ndern)';
$lng['changepassword']['also_change_ftp'] = ' Auch Passwort vom Haupt-FTP-Zugang &auml;ndern';

/**
 * Domains
 */

$lng['domains']['description'] = 'Hier k&ouml;nnen Sie (Sub-)Domains erstellen und deren Pfade &auml;ndern.<br />Nach jeder &Auml;nderung braucht das System etwas Zeit um die Konfiguration neu einzulesen.';
$lng['domains']['domainsettings'] = 'Domaineinstellungen';
$lng['domains']['domainname'] = 'Domainname';
$lng['domains']['subdomain_add'] = 'Subdomain anlegen';
$lng['domains']['subdomain_edit'] = '(Sub-)Domain bearbeiten';
$lng['domains']['wildcarddomain'] = 'Als Wildcarddomain eintragen?';
$lng['domains']['aliasdomain'] = 'Alias f&uuml;r Domain';
$lng['domains']['noaliasdomain'] = 'Keine Aliasdomain';

/**
 * eMails
 */

$lng['emails']['description'] = 'Hier k&ouml;nnen Sie Ihre E-Mail Adressen einrichten.<br />Ein Konto ist wie Ihr Briefkasten vor der Haust&uuml;re. Wenn jemand eine email an Sie schreibt, dann wird diese in dieses Konto gelegt.<br><br>Die Zugangsdaten von Ihrem Mailprogramm sind wie folgt: (Die Angaben in <i>kursiver</i> Schrift sind durch die jeweiligen Eintr&auml;ge zu ersetzen!)<br>Hostname: <b><i>Domainname</i></b><br>Benutzername: <b><i>Kontoname / E-Mail-Adresse</i></b><br>Passwort: <b><i>das gew&auml;hlte Passwort</i></b>';
$lng['emails']['emailaddress'] = 'E-Mail-Adresse';
$lng['emails']['emails_add'] = 'E-Mail-Adresse anlegen';
$lng['emails']['emails_edit'] = 'E-Mail-Adresse &auml;ndern';
$lng['emails']['catchall'] = 'Catchall';
$lng['emails']['iscatchall'] = 'Als Catchall-Adresse definieren?';
$lng['emails']['account'] = 'Konto';
$lng['emails']['account_add'] = 'Konto anlegen';
$lng['emails']['account_delete'] = 'Konto l&ouml;schen';
$lng['emails']['from'] = 'Von';
$lng['emails']['to'] = 'Nach';
$lng['emails']['forwarders'] = 'Weiterleitungen';
$lng['emails']['forwarder_add'] = 'Weiterleitung hinzuf&uuml;gen';

/**
 * FTP
 */

$lng['ftp']['description'] = 'Hier k&ouml;nnen Sie zus&auml;tzliche FTP-Konten einrichten.<br />Die &Auml;nderungen sind sofort wirksam und die FTP-Konten sofort benutzbar.';
$lng['ftp']['account_add'] = 'Benutzerkonto anlegen';

/**
 * MySQL
 */

$lng['mysql']['databasename'] = 'Benutzer-/Datenbankname';
$lng['mysql']['databasedescription'] = 'Datenbankbeschreibung';
$lng['mysql']['database_create'] = 'Datenbank anlegen';

/**
 * Extras
 */

$lng['extras']['description'] = 'Hier k&ouml;nnen Sie zus&auml;tzliche Extras einrichten, wie zum Beispiel Verzeichnisschutz.<br />Die &Auml;nderungen sind erst nach einer bestimmten Zeit wirksam.';
$lng['extras']['directoryprotection_add'] = 'Verzeichnisschutz anlegen';
$lng['extras']['view_directory'] = 'Verzeichnis anzeigen';
$lng['extras']['pathoptions_add'] = 'Pfadoptionen hinzuf&uuml;gen';
$lng['extras']['directory_browsing'] = 'Verzeichnisinhalt anzeigen';
$lng['extras']['pathoptions_edit'] = 'Pfadoptionen bearbeiten';
$lng['extras']['error404path'] = '404';
$lng['extras']['error403path'] = '403';
$lng['extras']['error500path'] = '500';
$lng['extras']['error401path'] = '401';
$lng['extras']['errordocument404path'] = 'URL zum Fehlerdokument 404';
$lng['extras']['errordocument403path'] = 'URL zum Fehlerdokument 403';
$lng['extras']['errordocument500path'] = 'URL zum Fehlerdokument 500';
$lng['extras']['errordocument401path'] = 'URL zum Fehlerdokument 401';

/**
 * Errors
 */

$lng['error']['error'] = 'Fehlermeldung';
$lng['error']['directorymustexist'] = 'Das Verzeichnis %s muss existieren. Legen Sie es bitte mit Ihrem FTP-Programm an.';
$lng['error']['filemustexist'] = 'Die Datei %s muss existieren.';
$lng['error']['allresourcesused'] = 'Sie haben bereits alle Ihnen zur Verf&uuml;gung stehenden Ressourcen verbraucht.';
$lng['error']['domains_cantdeletemaindomain'] = 'Sie k&ouml;nnen keine Domain, die als E-Mail-Domain verwendet wird l&ouml;schen. ';
$lng['error']['domains_canteditdomain'] = 'Sie k&ouml;nnen diese Domain nicht bearbeiten. Dies wurde durch den Admin verweigert';
$lng['error']['domains_cantdeletedomainwithemail'] = 'Sie k&ouml;nnen keine Domain l&ouml;schen die noch als E-Mail-Domain verwendet wird. L&ouml;schen Sie zuerst alle E-Mail-Adressen dieser Domain.';
$lng['error']['firstdeleteallsubdomains'] = 'Sie m&uuml;ssen erst alle Subdomains l&ouml;schen, bevor Sie eine Wildcarddomain anlegen k&ouml;nnen.';
$lng['error']['youhavealreadyacatchallforthisdomain'] = 'Sie haben bereits eine Adresse als Catchall f&uuml;r diese Domain definiert.';
$lng['error']['ftp_cantdeletemainaccount'] = 'Sie k&ouml;nnen Ihren Hauptaccount nicht l&ouml;schen.';
$lng['error']['login'] = 'Der angegebene Benutzername/Passwort ist falsch.';
$lng['error']['login_blocked'] = 'Dieser Account wurde aufgrund zu vieler Fehlversuche vorr&uuml;bergehend geschlossen. <br />Bitte versuchen Sie es in ' . $settings['login']['deactivatetime'] . ' Sekunden erneut.';
$lng['error']['notallreqfieldsorerrors'] = 'Sie haben nicht alle Felder oder ein Feld mit fehlerhaften Angaben ausgef&uuml;llt.';
$lng['error']['oldpasswordnotcorrect'] = 'Das alte Passwort ist nicht korrekt.';
$lng['error']['youcantallocatemorethanyouhave'] = 'Sie k&ouml;nnen nicht mehr Ressourcen verteilen als Sie noch frei haben.';
$lng['error']['mustbeurl'] = 'Sie m&uuml;ssen eine vollst&auml;ndige URL angeben (z.B. http://irgendwas.de/error404.htm)';
$lng['error']['invalidpath'] = 'Sie haben keine g&uuml;ltige URL ausgew&auml;hlt (Evtl. Probleme beim Verzeichnislisting?)';
$lng['error']['stringisempty'] = 'Fehlende Eingabe im Feld';
$lng['error']['stringiswrong'] = 'Falsche Eingabe im Feld';
$lng['error']['myloginname'] = '\'' . $lng['login']['username'] . '\'';
$lng['error']['mypassword'] = '\'' . $lng['login']['password'] . '\'';
$lng['error']['oldpassword'] = '\'' . $lng['changepassword']['old_password'] . '\'';
$lng['error']['newpassword'] = '\'' . $lng['changepassword']['new_password'] . '\'';
$lng['error']['newpasswordconfirm'] = '\'' . $lng['changepassword']['new_password_confirm'] . '\'';
$lng['error']['newpasswordconfirmerror'] = 'Das neue Passwort und die Best&auml;tigung sind nicht identisch.';
$lng['error']['myname'] = '\'' . $lng['customer']['name'] . '\'';
$lng['error']['myfirstname'] = '\'' . $lng['customer']['firstname'] . '\'';
$lng['error']['emailadd'] = '\'' . $lng['customer']['email'] . '\'';
$lng['error']['mydomain'] = '\'Domain\'';
$lng['error']['mydocumentroot'] = '\'Documentroot\'';
$lng['error']['loginnameexists'] = 'Der Login-Name %s existiert bereits.';
$lng['error']['emailiswrong'] = 'Die E-Mail-Adresse %s enth&auml;lt ung&uuml;ltige Zeichen oder ist nicht vollst&auml;ndig.';
$lng['error']['loginnameiswrong'] = 'Der Login-Name %s enth&auml;lt ung&uuml;ltige Zeichen.';
$lng['error']['userpathcombinationdupe'] = 'Kombination aus Benutzername und Pfad existiert bereits.';
$lng['error']['patherror'] = 'allgemeiner Fehler! Pfad darf nicht leer sein.';
$lng['error']['errordocpathdupe'] = 'Option f&uuml;r Pfad %s existiert bereits.';
$lng['error']['adduserfirst'] = 'Sie m&uuml;ssen zuerst einen Kunden anlegen.';
$lng['error']['domainalreadyexists'] = 'Die Domain %s wurde bereits einem Kunden zugeordnet.';
$lng['error']['nolanguageselect'] = 'Keine Sprache ausgew&auml;hlt.';
$lng['error']['nosubjectcreate'] = 'Sie m&uuml;ssen einen Betreff angeben.';
$lng['error']['nomailbodycreate'] = 'Sie m&uuml;ssen einen E-Mail-Text eingeben.';
$lng['error']['templatenotfound'] = 'Template wurde nicht gefunden.';
$lng['error']['alltemplatesdefined'] = 'Sie k&ouml;nnen keine weiteren Templates anlegen, da bereits alle Sprachen mit Templates versorgt sind.';
$lng['error']['wwwnotallowed'] = 'Ihre Subdomain darf nicht www heissen.';
$lng['error']['subdomainiswrong'] = 'Die Subdomain %s enth&auml;lt ung&uuml;ltige Zeichen.';
$lng['error']['domaincantbeempty'] = 'Der Domain-Name darf nicht leer sein.';
$lng['error']['domainexistalready'] = 'Die Domain %s existiert bereits.';
$lng['error']['domainisaliasorothercustomer'] = 'Die ausgew&auml;hlte Aliasdomain ist entweder selber eine Aliasdomain oder geh&ouml;rt zu einem anderen Kunden.';
$lng['error']['emailexistalready'] = 'Die E-Mail-Adresse %s existiert bereits.';
$lng['error']['maindomainnonexist'] = 'Die Haupt-Domain %s existiert nicht.';
$lng['error']['destinationnonexist'] = 'Bitte geben Sie Ihre Weiterleitungsadresse im Feld \'Nach\' ein.';
$lng['error']['destinationalreadyexistasmail'] = 'Die Weiterleitung zu %s exisitiert bereits als aktive E-Mail-Adresse.';
$lng['error']['destinationalreadyexist'] = 'Es gibt bereits eine Weiterleitung nach %s .';
$lng['error']['destinationiswrong'] = 'Die Weiterleitungsadresse-Adresse %s enth&auml;lt ung&uuml;ltige Zeichen oder ist nicht vollst&auml;ndig.';
$lng['error']['domainname'] = $lng['domains']['domainname'];

/**
 * Questions
 */

$lng['question']['question'] = 'Sicherheitsabfrage';
$lng['question']['admin_customer_reallydelete'] = 'Wollen Sie den Kunden %s wirklich l&ouml;schen?<br />ACHTUNG! Alle Daten gehen unwiderruflich verloren! Nach dem Vorgang m&uuml;ssen Sie die Daten aus dem Dateisystem noch manuell entfernen.';
$lng['question']['admin_domain_reallydelete'] = 'Wollen Sie die Domain %s wirklich l&ouml;schen?';
$lng['question']['admin_domain_reallyenablemailsystemhostname'] = 'Wollen Sie den Server Hostname wirklich als Emaildomain hinzuf&uuml;gen?';
$lng['question']['admin_domain_reallydisablesecuritysetting'] = 'Wollen Sie diese wichtigen Sicherheitseinstellungen (OpenBasedir und/oder SafeMode) wirklich deaktivieren?';
$lng['question']['admin_admin_reallydelete'] = 'Wollen Sie den Admin %s wirklich l&ouml;schen?<br />Alle Kunden und Domains werden Ihrem Account zugeteilt.';
$lng['question']['admin_template_reallydelete'] = 'Wollen Sie die Vorlage \'%s\' wirklich l&ouml;schen?';
$lng['question']['domains_reallydelete'] = 'Wollen Sie die Domain %s wirklich l&ouml;schen?';
$lng['question']['email_reallydelete'] = 'Wollen Sie die E-Mail-Adresse %s wirklich l&ouml;schen?';
$lng['question']['email_reallydelete_account'] = 'Wollen Sie das Konto von %s wirklich l&ouml;schen?';
$lng['question']['email_reallydelete_forwarder'] = 'Wollen Sie die Weiterleitung %s wirklich l&ouml;schen?';
$lng['question']['extras_reallydelete'] = 'Wollen Sie den Verzeichnisschutz f&uuml;r %s wirklich l&ouml;schen?';
$lng['question']['extras_reallydelete_pathoptions'] = 'Wollen Sie die Optionen f&uuml;r den Pfad %s wirklich l&ouml;schen?';
$lng['question']['ftp_reallydelete'] = 'Wollen Sie das FTP-Benutzerkonto %s wirklich l&ouml;schen?';
$lng['question']['mysql_reallydelete'] = 'Wollen Sie die Datenbank %s wirklich l&ouml;schen?<br />ACHTUNG! Alle Daten gehen unwiderruflich verloren!';
$lng['question']['admin_configs_reallyrebuild'] = 'Wollen Sie wirklich Ihre Apache und Bind Konfigurationsdateien neu erstellen lassen?';

/**
 * Mails
 */

$lng['mails']['pop_success']['mailbody'] = 'Hallo,\n\nihr E-Mail-Konto {USERNAME}\nwurde erfolgreich eingerichtet.\n\nDies ist eine automatisch generierte\neMail, bitte antworten Sie nicht auf\ndiese Mitteilung.\n\nIhr SysCP-Team';
$lng['mails']['pop_success']['subject'] = 'E-Mail-Konto erfolgreich eingerichtet';
$lng['mails']['createcustomer']['mailbody'] = 'Hallo {FIRSTNAME} {NAME},\n\nhier ihre Accountinformationen:\n\nBenutzername: {USERNAME}\nPassword: {PASSWORD}\n\nVielen Dank,\nIhr SysCP-Team';
$lng['mails']['createcustomer']['subject'] = 'Accountinformationen';

/**
 * Admin
 */

$lng['admin']['overview'] = '&Uuml;bersicht';
$lng['admin']['ressourcedetails'] = 'Verbrauchte Ressourcen';
$lng['admin']['systemdetails'] = 'Systemdetails';
$lng['admin']['syscpdetails'] = 'SysCP-Details';
$lng['admin']['installedversion'] = 'Installierte Version';
$lng['admin']['latestversion'] = 'Neueste Version';
$lng['admin']['lookfornewversion']['clickhere'] = 'per Webservice abfragen';
$lng['admin']['lookfornewversion']['error'] = 'Fehler beim Auslesen';
$lng['admin']['resources'] = 'Ressourcen';
$lng['admin']['customer'] = 'Kunde';
$lng['admin']['customers'] = 'Kunden';
$lng['admin']['customer_add'] = 'Kunden anlegen';
$lng['admin']['customer_edit'] = 'Kunden bearbeiten';
$lng['admin']['domains'] = 'Domains';
$lng['admin']['domain_add'] = 'Domain anlegen';
$lng['admin']['domain_edit'] = 'Domain bearbeiten';
$lng['admin']['subdomainforemail'] = 'Subdomains als E-Mail-Domains';
$lng['admin']['admin'] = 'Admin';
$lng['admin']['admins'] = 'Admins';
$lng['admin']['admin_add'] = 'Admin anlegen';
$lng['admin']['admin_edit'] = 'Admin bearbeiten';
$lng['admin']['customers_see_all'] = 'Kann alle Kunden sehen?';
$lng['admin']['domains_see_all'] = 'Kann alle Domains sehen?';
$lng['admin']['change_serversettings'] = 'Kann Servereinstellungen bearbeiten?';
$lng['admin']['server'] = 'Server';
$lng['admin']['serversettings'] = 'Einstellungen';
$lng['admin']['rebuildconf'] = 'Configs neuschreiben';
$lng['admin']['stdsubdomain'] = 'Standardsubdomain';
$lng['admin']['stdsubdomain_add'] = 'Standardsubdomain anlegen';
$lng['admin']['phpenabled'] = 'PHP verf&uuml;gbar';
$lng['admin']['deactivated'] = 'Gesperrt';
$lng['admin']['deactivated_user'] = 'Benutzer sperren';
$lng['admin']['sendpassword'] = 'Passwort zusenden';
$lng['admin']['ownvhostsettings'] = 'Eigene vHost-Einstellungen';
$lng['admin']['configfiles']['serverconfiguration'] = 'Konfiguration';
$lng['admin']['configfiles']['files'] = '<b>Konfigurationsdateien:</b> Bitte &auml;ndern Sie die entsprechenden Konfigurationsdateien<br />oder legen sie mit dem folgenden Inhalt neu an, falls sie nicht existieren.<br /><b>Bitte beachten Sie:</b> Das MySQL-Passwort wurde aus Sicherheitsgr&uuml;nden nicht ersetzt.<br />Bitte ersetzen Sie &quot;MYSQL_PASSWORD&quot; manuell durch das entsprechende Passwort.<br />Falls Sie es vergessen haben sollten, finden Sie es in der Datei &quot;lib/userdata.inc.php&quot;.';
$lng['admin']['configfiles']['commands'] = '<b>Kommandos:</b> Bitte f&uuml;hren Sie die folgenden Kommandos in einer Shell aus.';
$lng['admin']['configfiles']['restart'] = '<b>Neustart:</b> Bitte f&uuml;hren Sie die folgenden Kommandos zum Neuladen<br />der Konfigurationsdateien in einer Shell aus.';
$lng['admin']['templates']['templates'] = 'Vorlagen';
$lng['admin']['templates']['template_add'] = 'Vorlage hinzuf&uuml;gen';
$lng['admin']['templates']['template_edit'] = 'Vorlage bearbeiten';
$lng['admin']['templates']['action'] = 'Aktion';
$lng['admin']['templates']['email'] = 'E-Mail';
$lng['admin']['templates']['subject'] = 'Betreff';
$lng['admin']['templates']['mailbody'] = 'Mailtext';
$lng['admin']['templates']['createcustomer'] = 'Willkommensmail f&uuml;r neue Kunden';
$lng['admin']['templates']['pop_success'] = 'Willkommensmail f&uuml;r neue E-Mail Konten';
$lng['admin']['wwwserveralias'] = 'www. ServerAlias hinzuf&uuml;gen';
$lng['admin']['templates']['template_replace_vars'] = 'Variablen die in den Vorlagen ersetzt werden:';
$lng['admin']['templates']['FIRSTNAME'] = 'Wird mit dem Vornamen des Kunden ersetzt.';
$lng['admin']['templates']['NAME'] = 'Wird mit dem Namen des Kunden ersetzt.';
$lng['admin']['templates']['USERNAME'] = 'Wird mit dem Benutzernamen des neuen Kundenaccounts ersetzt.';
$lng['admin']['templates']['PASSWORD'] = 'Wird mit dem Passwort des neuen Kundenaccounts ersetzt.';
$lng['admin']['templates']['EMAIL'] = 'Wird mit der Adresse des neuen POP3/IMAP Kontos ersetzt.';

/**
 * Serversettings
 */

$lng['serversettings']['session_timeout']['title'] = 'Session Timeout';
$lng['serversettings']['session_timeout']['description'] = 'Wie lange muss ein Benutzer inaktiv sein, damit die Session ung&uuml;ltig wird? (Sekunden)';
$lng['serversettings']['accountprefix']['title'] = 'Kundenprefix';
$lng['serversettings']['accountprefix']['description'] = 'Welchen Prefix sollen die Kundenaccounts haben?';
$lng['serversettings']['mysqlprefix']['title'] = 'SQL-Prefix';
$lng['serversettings']['mysqlprefix']['description'] = 'Welchen Prefix sollen die MySQL-Benutzerkonten haben?';
$lng['serversettings']['ftpprefix']['title'] = 'FTP-Prefix';
$lng['serversettings']['ftpprefix']['description'] = 'Welchen Prefix sollen die FTP-Benutzerkonten haben?';
$lng['serversettings']['documentroot_prefix']['title'] = 'Heimatverzeichnis';
$lng['serversettings']['documentroot_prefix']['description'] = 'Wo sollen alle Heimatverzeichnisse der Kunden liegen?';
$lng['serversettings']['logfiles_directory']['title'] = 'Apache-Logdateien-Verzeichnis';
$lng['serversettings']['logfiles_directory']['description'] = 'Wo sollen alle Logdateien des Apache liegen?';
$lng['serversettings']['ipaddress']['title'] = 'IP-Adresse';
$lng['serversettings']['ipaddress']['description'] = 'Welche IP-Adresse hat der Server?';
$lng['serversettings']['hostname']['title'] = 'Hostname';
$lng['serversettings']['hostname']['description'] = 'Welchen Hostnamen hat der Server?';
$lng['serversettings']['apachereload_command']['title'] = 'Apache-Reload-Command';
$lng['serversettings']['apachereload_command']['description'] = 'Wie heisst das Skript zum Neuladen der Apache-Konfigurationsdateien?';
$lng['serversettings']['bindconf_directory']['title'] = 'Bind-Config-Directory';
$lng['serversettings']['bindconf_directory']['description'] = 'Wo liegen die Bind-Konfigurationsdateien?';
$lng['serversettings']['bindreload_command']['title'] = 'Bind-Reload-Command';
$lng['serversettings']['bindreload_command']['description'] = 'Wie heisst das Skript zum Neuladen der Bind-Konfigurationsdateien?';
$lng['serversettings']['binddefaultzone']['title'] = 'Bind-Default-Zone';
$lng['serversettings']['binddefaultzone']['description'] = 'Wie heisst die Default-Zone f&uuml;r alle Domains?';
$lng['serversettings']['vmail_uid']['title'] = 'Mails-Uid';
$lng['serversettings']['vmail_uid']['description'] = 'Welche UID sollen die Mails haben?';
$lng['serversettings']['vmail_gid']['title'] = 'Mails-Gid';
$lng['serversettings']['vmail_gid']['description'] = 'Welche GID sollen die Mails haben?';
$lng['serversettings']['vmail_homedir']['title'] = 'Mails-Homedir';
$lng['serversettings']['vmail_homedir']['description'] = 'Wo sollen die E-Mails liegen?';
$lng['serversettings']['adminmail']['title'] = 'Absenderadresse';
$lng['serversettings']['adminmail']['description'] = 'Wie ist die Absenderadresse f&uuml;r E-Mails aus dem Panel?';
$lng['serversettings']['phpmyadmin_url']['title'] = 'phpMyAdmin-URL';
$lng['serversettings']['phpmyadmin_url']['description'] = 'Wo liegt der phpMyAdmin? (muss mit http(s):// beginnen)';
$lng['serversettings']['webmail_url']['title'] = 'WebMail-URL';
$lng['serversettings']['webmail_url']['description'] = 'Wo liegt das WebMail? (muss mit http(s):// beginnen)';
$lng['serversettings']['webftp_url']['title'] = 'WebFTP-URL';
$lng['serversettings']['webftp_url']['description'] = 'Wo liegt das WebFTP? (muss mit http(s):// beginnen)';
$lng['serversettings']['language']['description'] = 'Welche Sprache ist ihre Standardsprache?';
$lng['serversettings']['maxloginattempts']['title'] = 'Max Loginversuche';
$lng['serversettings']['maxloginattempts']['description'] = 'Maximale Anzahl an Loginversuchen bis der Account deaktiviert wird.';
$lng['serversettings']['deactivatetime']['title'] = 'L&auml;nge der Deaktivierung';
$lng['serversettings']['deactivatetime']['description'] = 'Zeitraum (in sek.) f&uuml;r den der Account deaktiviert ist.';
$lng['serversettings']['pathedit']['title'] = 'Pfad-Eingabemethode';
$lng['serversettings']['pathedit']['description'] = 'Soll ein Pfad via Dropdown-Men&uuml; ausgew&auml;hlt oder manuell eingegeben werden k&ouml;nnen.';
$lng['serversettings']['nameservers']['title'] = 'Nameserver';
$lng['serversettings']['nameservers']['description'] = 'Eine durch Komma getrennte Liste mit den Hostnamen aller Nameserver. Der erste ist der prim&auml;re.';
$lng['serversettings']['mxservers']['title'] = 'MX Server';
$lng['serversettings']['mxservers']['description'] = 'Eine durch Komma getrenne Liste die ein Paar mit einer Nummer und den Hostnamen einen MX Servers, getrennt durch ein Leerzeichen, enthaelt (z.B. \'10 mx.example.com\').';

/**
 * CHANGED BETWEEN 1.2.12 and 1.2.13
 */

$lng['mysql']['description'] = 'Hier k&ouml;nnen Sie MySQL-Datenbanken anlegen und l&ouml;schen.<br>Die &Auml;nderungen werden sofort wirksam und die Datenbanken sofort benutzbar.<br>Im Men&uuml; finden Sie einen Link zum phpMyAdmin, mit dem Sie Ihre Datenbankeninhalte einfach bearbeiten k&ouml;nnen.<br><br>Die Zugangsdaten von php-Skripten sind wie folgt: (Die Angaben in <i>kursiver</i> Schrift sind durch die jeweiligen Eintr&auml;ge zu ersetzen!)<br>Hostname: <b><SQL_HOST></b><br>Benutzername: <b><i>Datenbankname</i></b><br>Passwort: <b><i>das gew&auml;hlte Passwort</i></b><br>Datenbank: <b><i>Datenbankname</i></b>';

/**
 * ADDED BETWEEN 1.2.12 and 1.2.13
 */

$lng['admin']['cronlastrun'] = 'Letzter Cronjob';
$lng['serversettings']['paging']['title'] = 'Eintr&auml;ge pro Seite';
$lng['serversettings']['paging']['description'] = 'Wieviele Eintr&auml;ge sollen auf einer Seite gezeigt werden? (0 = Paging deaktivieren)';
$lng['error']['ipstillhasdomains'] = 'Die IP/Port Kombination, die Sie l&ouml;schen wollen ist noch bei einer oder mehreren Domains eingetragen. Bitte &auml;ndern sie die Domains vorher auf eine andere IP/Port Kombination um diese l&ouml;schen zu k&ouml;nnen.';
$lng['error']['cantdeletedefaultip'] = 'Sie k&ouml;nnen die Standard IP/Port Kombination f&uuml;r Reseller nicht l&ouml;schen. Bitte setzen Sie eine andere IP/Port Kombination als Standard um diese l&ouml;schen zu k&ouml;nnen.';
$lng['error']['cantdeletesystemip'] = 'Sie k&ouml;nnen die letzte System IP nicht l&ouml;schen. Entweder legen Sie eine neue IP/Port Kombination als Systemeinstellung an oder &auml;ndern die System IP.';
$lng['error']['myipaddress'] = '\'IP\'';
$lng['error']['myport'] = '\'Port\'';
$lng['error']['myipdefault'] = 'Sie m&uuml;ssen eine IP/Port Kombination ausw&auml;hlen, die den Standard defninieren soll.';
$lng['error']['myipnotdouble'] = 'Diese Kombination aus IP und Post existiert bereits.';
$lng['question']['admin_ip_reallydelete'] = 'Wollen Sie wirklich die IP %s l&ouml;schen?';
$lng['admin']['ipsandports']['ipsandports'] = 'IPs und Ports';
$lng['admin']['ipsandports']['add'] = 'IP/Port hinzuf&uuml;gen';
$lng['admin']['ipsandports']['edit'] = 'IP/Port bearbeiten';
$lng['admin']['ipsandports']['ipandport'] = 'IP/Port';
$lng['admin']['ipsandports']['ip'] = 'IP';
$lng['admin']['ipsandports']['port'] = 'Port';

// ADDED IN 1.2.13-rc3

$lng['error']['cantchangesystemip'] = 'Sie k&ouml;nnen die letzte System IP nicht l&ouml;schen. Entweder legen Sie noch eine neue IP/Port Kombination als Systemeinstellung an oder &auml;ndern die System IP.';
$lng['question']['admin_domain_reallydocrootoutofcustomerroot'] = 'Sind Sie sicher, dass der DocumentRoot dieser Domain au&szlig;erhalb des Heimatverzeichnisses des Kunden liegen soll?';

// ADDED IN 1.2.14-rc1

$lng['admin']['memorylimitdisabled'] = 'Deaktiviert';
$lng['domain']['openbasedirpath'] = 'OpenBasedir-Pfad';
$lng['domain']['docroot'] = 'Oben eingegebener Pfad';
$lng['domain']['homedir'] = 'Heimverzeichnis';
$lng['admin']['valuemandatory'] = 'Dieses Feld muss ausgef&uuml;llt werden';
$lng['admin']['valuemandatorycompany'] = 'Entweder &quot;Name&quot; und &quot;Vorname&quot; oder &quot;Firma&quot; muss ausgef&uuml;llt werden';
$lng['menue']['main']['username'] = 'Angemeldet als: ';
$lng['panel']['urloverridespath'] = 'URL (&uuml;berschreibt Pfad)';
$lng['panel']['pathorurl'] = 'Pfad oder URL';
$lng['error']['sessiontimeoutiswrong'] = '&quot;Session-Timeout&quot; muss ein numerischer Wert sein.';
$lng['error']['maxloginattemptsiswrong'] = '&quot;Maximale Loginversuche&quot; muss ein numerischer Wert sein.';
$lng['error']['deactivatetimiswrong'] = '&quot;L&auml;nge der Deaktivierung&quot; muss numerisch sein.';
$lng['error']['accountprefixiswrong'] = 'Das &quot;Kundenprefix&quot; ist falsch.';
$lng['error']['mysqlprefixiswrong'] = 'Das &quot;SQL-Prefix&quot; ist falsch.';
$lng['error']['ftpprefixiswrong'] = 'Das &quot;FTP-Prefix&quot; ist falsch.';
$lng['error']['ipiswrong'] = 'Die &quot;IP-Adresse&quot; ist falsch. Es ist nur eine g&uuml;ltige IP-Adresse erlaubt.';
$lng['error']['vmailuidiswrong'] = 'Die &quot;Mails-UID&quot; ist falsch. Nur eine numerische UID ist erlaubt.';
$lng['error']['vmailgidiswrong'] = 'Die &quot;Mails-GID&quot; ist falsch. Nur eine numerische GID ist erlaubt.';
$lng['error']['adminmailiswrong'] = 'Die &quot;Absenderadresse&quot; ist fehlerhaft. Es ist nur eine g&uuml;ltige E-Mail-Adresse erlaubt';
$lng['error']['pagingiswrong'] = 'Die &quot;Eintr&auml;ge pro Seite&quot;-Einstellung ist falsch. Nur numerische Zeichen sind erlaubt.';
$lng['error']['phpmyadminiswrong'] = 'Die &quot;phpMyAdmin-URL&quot ist keine g&uuml;ltige URL.';
$lng['error']['webmailiswrong'] = 'Die &quot;WebMail-URL&quot ist keine g&uuml;ltige URL.';
$lng['error']['webftpiswrong'] = 'Die &quot;WebFTP-URL&quot ist keine g&uuml;ltige URL.';
$lng['domains']['hasaliasdomains'] = 'Hat Aliasdomain(s)';
$lng['serversettings']['defaultip']['title'] = 'Standard IP/Port';
$lng['serversettings']['defaultip']['description'] = 'Welche IP/Port-Kombination soll standardm&auml;&szlig;ig verwendet werden?';
$lng['domains']['statstics'] = 'Statistiken';
$lng['panel']['ascending'] = 'aufsteigend';
$lng['panel']['decending'] = 'absteigend';
$lng['panel']['search'] = 'Suche';
$lng['panel']['used'] = 'benutzt';

// ADDED IN 1.2.14-rc3

$lng['panel']['translator'] = '&Uuml;bersetzung';

// ADDED IN 1.2.14-rc4

$lng['error']['stringformaterror'] = 'Der Wert des Feldes &quot;%s&quot; ist nicht im erwarteten Format.';

// ADDED IN 1.2.15-rc1

$lng['admin']['serversoftware'] = 'Serversoftware';
$lng['admin']['phpversion'] = 'PHP-Version';
$lng['admin']['phpmemorylimit'] = 'PHP-Memory-Limit';
$lng['admin']['mysqlserverversion'] = 'MySQL Server Version';
$lng['admin']['mysqlclientversion'] = 'MySQL Client Version';
$lng['admin']['webserverinterface'] = 'Webserver Interface';
$lng['domains']['isassigneddomain'] = 'Ist zugewiesene Domain';
$lng['serversettings']['phpappendopenbasedir']['title'] = 'An OpenBasedir anzuh&auml;ngende Pfade';
$lng['serversettings']['phpappendopenbasedir']['description'] = 'Diese (durch Doppelpunkte getrennten) Pfade werden dem OpenBasedir-Statement in jedem vhost-Container angeh&auml;ngt.';

// CHANGED IN 1.2.15-rc1

$lng['error']['loginnameissystemaccount'] = 'Sie k&ouml;nnen keinen Account anlegen, welcher wie ein Systemaccount aussieht (also zum Beispiel mit &quot;%s&quot; anf&auml;ngt). Bitte w&auml;hlen Sie einen anderen Accountnamen.';
$lng['error']['youcantdeleteyourself'] = 'Aus Sicherheitsgr&uuml;nden k&ouml;nnen Sie sich nicht selbst l&ouml;schen.';
$lng['error']['youcanteditallfieldsofyourself'] = 'Hinweis: Aus Sicherheitsgr&uuml;nden k&ouml;nnen Sie nicht alle Felder Ihres eigenen Accoounts bearbeiten.';

// ADDED IN 1.2.16-svn1

$lng['serversettings']['natsorting']['title'] = 'Nat&uuml;rliche Sortierung in der Listenansicht nutzen';
$lng['serversettings']['natsorting']['description'] = 'Sortiert die Liste in der Reihenfolge web1 -> web2 -> web11 anstatt web1 -> web11 -> web2.';

// ADDED IN 1.2.16-svn2

$lng['serversettings']['deactivateddocroot']['title'] = 'Docroot f&uuml;r deaktivierte Benutzer';
$lng['serversettings']['deactivateddocroot']['description'] = 'Dieser Pfad wird als docroot f&uuml;r deaktivierte Benutzer verwendet. Wenn leer, wird kein vHost erstellt.';

// ADDED IN 1.2.16-svn4

$lng['panel']['reset'] = '&Auml;nderungen verwerfen';
$lng['admin']['accountsettings'] = 'Konteneinstellungen';
$lng['admin']['panelsettings'] = 'Paneleinstellungen';
$lng['admin']['systemsettings'] = 'Systemeinstellungen';
$lng['admin']['webserversettings'] = 'Webservereinstellungen';
$lng['admin']['mailserversettings'] = 'Mailservereinstellungen';
$lng['admin']['nameserversettings'] = 'Nameservereinstellungen';
$lng['admin']['updatecounters'] = 'Ressourcenverbrauch';
$lng['question']['admin_counters_reallyupdate'] = 'Wollen Sie den Ressourcenverbrauch neu berechnen?';
$lng['panel']['pathDescription'] = 'Wenn das Verzeichnis nicht existiert, wird es automatisch erstellt.';

// ADDED IN 1.2.16-svn6

$lng['mails']['trafficninetypercent']['mailbody'] = 'Sehr geehrte(r) {NAME},\n\nSie haben bereits {TRAFFICUSED} MB von Ihren insgesamt {TRAFFIC} MB Traffic verbraucht.\nDies sind mehr als 90%.\n\nVielen Dank,\ndas SysCP-Team';
$lng['mails']['trafficninetypercent']['subject'] = 'Sie erreichen bald Ihr Traffic-Limit';
$lng['admin']['templates']['trafficninetypercent'] = 'Hinweismail f&uuml;r Kunden, wenn sie 90% des Traffics verbraucht haben';
$lng['admin']['templates']['TRAFFIC'] = 'Wird mit Traffic, der dem Kunden zugewiesen wurde, ersetzt.';
$lng['admin']['templates']['TRAFFICUSED'] = 'Wird mit Traffic, der vom Kunden bereits verbraucht wurde, ersetzt.';

// ADDED IN 1.2.16-svn7

$lng['admin']['subcanemaildomain']['never'] = 'Nie';
$lng['admin']['subcanemaildomain']['choosableno'] = 'W&auml;hlbar, Standardwert: Nein';
$lng['admin']['subcanemaildomain']['choosableyes'] = 'W&auml;hlbar, Standardwert: Ja';
$lng['admin']['subcanemaildomain']['always'] = 'Immer';
$lng['changepassword']['also_change_webalizer'] = ' Auch Passwort vom Webalizer &auml;ndern';

// ADDED IN 1.2.16-svn8

$lng['serversettings']['mailpwcleartext']['title'] = 'Passw&ouml;rter der Mail-Konten auch im Klartext in der Datenbank speichern';
$lng['serversettings']['mailpwcleartext']['description'] = 'Wenn diese Einstellung auf Ja gesetzt wird, werden alle Passw&ouml;rter auch unverschl&uuml;sselt (also im Klartext, f&uuml;r jeden mit Zugriff auf die SysCP-Datenbank sofort lesbar) in der mail_users-Tabelle gespeichert. Aktivieren Sie diese Option nur dann, wenn Sie sie wirklich gebrauchen!';
$lng['serversettings']['mailpwcleartext']['removelink'] = 'Klicken Sie hier, um alle unverschl&uuml;sselten Passw&ouml;rter aus der Tabelle zu entfernen.';
$lng['question']['admin_cleartextmailpws_reallywipe'] = 'Wollen Sie wirklich alle unverschl&uuml;sselten Passw&ouml;rter aus der Tabelle mail_users entfernen? Dieser Schritt kann nicht r&uuml;ckg&auml;ngig gemacht werden!';
$lng['admin']['configfiles']['overview'] = '&Uuml;bersicht';
$lng['admin']['configfiles']['wizard'] = 'Assistent';
$lng['admin']['configfiles']['distribution'] = 'Distribution';
$lng['admin']['configfiles']['service'] = 'Service';
$lng['admin']['configfiles']['daemon'] = 'Daemon';
$lng['admin']['configfiles']['http'] = 'Webserver (HTTP)';
$lng['admin']['configfiles']['dns'] = 'Nameserver (DNS)';
$lng['admin']['configfiles']['mail'] = 'Mailserver (POP3/IMAP)';
$lng['admin']['configfiles']['smtp'] = 'Mailserver (SMTP)';
$lng['admin']['configfiles']['ftp'] = 'FTP-Server';
$lng['admin']['configfiles']['etc'] = 'Sonstige (System)';
$lng['admin']['configfiles']['choosedistribution'] = '-- Distribution w&auml;hlen --';
$lng['admin']['configfiles']['chooseservice'] = '-- Service w&auml;hlen --';
$lng['admin']['configfiles']['choosedaemon'] = '-- Daemon w&auml;hlen --';
$lng['admin']['trafficlastrun'] = 'Letzte Trafficberechnung';

// ADDED IN 1.2.16-svn10

$lng['serversettings']['ftpdomain']['title'] = 'FTP-Benutzerkonten @domain';
$lng['serversettings']['ftpdomain']['description'] = 'K&ouml;nnen Kunden FTP-Benutzerkonten user@customerdomain anlegen?';
$lng['panel']['back'] = 'Zur&uuml;ck';

// ADDED IN 1.2.16-svn12

$lng['serversettings']['mod_log_sql']['title'] = 'Logs in Datenbank zwischenspeichern';
$lng['serversettings']['mod_log_sql']['description'] = '<a href="http://www.outoforder.cc/projects/apache/mod_log_sql/" title="mod_log_sql">mod_log_sql</a> benutzen um die Webzugriffe tempor&auml;r zu speichern<br /><b>Dies ben&ouml;tigt eine spezielle Apache-Konfiguration</b>';
$lng['serversettings']['mod_fcgid']['title'] = 'PHP &uuml;ber mod_fcgid/suexec einbinden';
$lng['serversettings']['mod_fcgid']['description'] = 'mod_fcgid/suexec/libnss_mysql benutzen um PHP unter dem jeweiligen Useraccount laufen zu lassen<br /><b>Dies ben&ouml;tigt eine spezielle <a href="http://files.syscp.org/docs/mod_log_sql/" title="mod_log_sql - Dokumentation">Apache-Konfiguration</a></b>';
$lng['serversettings']['sendalternativemail']['title'] = 'Alternative E-Mail-Adresse benutzen';
$lng['serversettings']['sendalternativemail']['description'] = 'W&auml;hrend dem erstellen eines Accounts das Passwort an eine andere E-Mail-Adresse senden';
$lng['emails']['alternative_emailaddress'] = 'Alternative E-Mail-Adresse';
$lng['mails']['pop_success_alternative']['mailbody'] = 'Hallo,\n\nihr E-Mail-Konto {USERNAME}\nwurde erfolgreich eingerichtet.\nIhr Passwort lautet {PASSWORD}.\n\nDies ist eine automatisch generierte\neMail, bitte antworten Sie nicht auf\ndiese Mitteilung.\n\nIhr SysCP-Team';
$lng['mails']['pop_success_alternative']['subject'] = 'E-Mail-Konto erfolgreich eingerichtet';
$lng['admin']['templates']['pop_success_alternative'] = 'Willkommensmail f&uuml;r neue E-Mail Konten f&uuml;r die alternative Email Addresse';
$lng['admin']['templates']['EMAIL_PASSWORD'] = 'Wird mit dem Passwort des neuen POP3/IMAP Kontos ersetzt.';

// ADDED IN 1.2.16-svn13

$lng['error']['documentrootexists'] = 'Es existiert noch ein Verzeichnis &quot;%s&quot; f&uuml;r diesen Kunden. Bitte dieses vorher l&ouml;schen.';

// ADDED IN 1.2.16-svn14

$lng['serversettings']['apacheconf_vhost']['title'] = 'Apache vHost-Konfigurations-Datei/Verzeichnis-Name';
$lng['serversettings']['apacheconf_vhost']['description'] = 'Wo soll die vHost-Konfigurationen abgelegt werden? Sie k&ouml;nnen entweder eine Datei (also mit allen vhosts) oder einen Ordner (mit einer Datei pro vhost) angeben.';
$lng['serversettings']['apacheconf_diroptions']['title'] = 'Apache Verzeichnisoption-Konfigurations-Datei/Verzeichnis-Name';
$lng['serversettings']['apacheconf_diroptions']['description'] = 'Wo soll die Verzeichnisoption-Konfigurationen abgelegt werden? Sie k&ouml;nnen entweder eine Datei (also mit allen vhosts) oder einen Ordner (mit einer Datei pro vhost) angeben.';
$lng['serversettings']['apacheconf_htpasswddir']['title'] = 'Apache htpasswd Verzeichnisname';
$lng['serversettings']['apacheconf_htpasswddir']['description'] = 'Wo sollen die htpasswd-Dateien f&uuml; den Verzeichnisschutz abgelegt werden?';

// ADDED IN 1.2.16-svn15

$lng['error']['formtokencompromised'] = 'Das Formular scheint manipuliert worden zu sein. Aus Sicherheitsgr&uuml;nden wurden Sie ausgelogged.';
$lng['serversettings']['mysql_access_host']['title'] = 'MySQL-Access-Hosts';
$lng['serversettings']['mysql_access_host']['description'] = 'Eine durch Komma getrennte Liste mit den Hostnamen aller Hostnames/IP-Adressen von denen sich die Benutzer einloggen d&uuml;rfen.';

// CHANGED IN 1.2.18

$lng['serversettings']['mod_log_sql']['description'] = '<a href="http://www.outoforder.cc/projects/apache/mod_log_sql/" title="mod_log_sql">mod_log_sql</a> benutzen um die Webzugriffe tempor&auml;r zu speichern<br /><b>Dies ben&ouml;tigt eine spezielle <a href="http://files.syscp.org/docs/mod_log_sql/" title="mod_log_sql - Dokumentation">Apache-Konfiguration</a></b>';
$lng['serversettings']['mod_fcgid']['description'] = 'mod_fcgid/suexec/libnss_mysql benutzen um PHP unter dem jeweiligen Useraccount laufen zu lassen<br /><b>Dies ben&ouml;tigt eine spezielle Apache-Konfiguration</b>';

// ADDED IN 1.2.18-svn1

$lng['admin']['ipsandports']['create_listen_statement'] = 'Erstelle Listen-Eintrag';
$lng['admin']['ipsandports']['create_namevirtualhost_statement'] = 'Erstelle NameVirtualHost-Eintrag';
$lng['admin']['ipsandports']['create_vhostcontainer'] = 'Erstelle vHost-Container';
$lng['admin']['ipsandports']['create_vhostcontainer_servername_statement'] = 'Erstelle ServerName-Eintrag im vHost-Container';

// ADDED IN 1.2.18-svn2

$lng['admin']['webalizersettings'] = 'Webalizereinstellungen';
$lng['admin']['webalizer']['normal'] = 'Normal';
$lng['admin']['webalizer']['quiet'] = 'Leise';
$lng['admin']['webalizer']['veryquiet'] = 'Keine Ausgaben';
$lng['serversettings']['webalizer_quiet']['title'] = 'Webalizerausgabe';
$lng['serversettings']['webalizer_quiet']['description'] = 'Ausgabefreudigkeit des webalizer-Programms';

// ADDED IN 1.2.18-svn3

$lng['ticket']['admin_email'] = 'root@localhost';
$lng['ticket']['noreply_email'] = 'tickets@syscp';
$lng['admin']['ticketsystem'] = 'Support-Tickets';
$lng['menue']['ticket']['ticket'] = 'Support Tickets';
$lng['menue']['ticket']['categories'] = 'Support Kategorien';
$lng['menue']['ticket']['archive'] = 'Ticket-Archiv';
$lng['ticket']['description'] = 'Hier k&ouml;nnen Sie Hilfe-Anfragen an Ihren zust&auml;ndigen Administrator senden.<br />Benachrichtigungen werden per E-Mail verschickt.';
$lng['ticket']['ticket_new'] = 'Neues Support-Ticket erstellen';
$lng['ticket']['ticket_reply'] = 'Auf Support-Ticket antworten';
$lng['ticket']['ticket_reopen'] = 'Ticket wiederer&ouml;ffnen';
$lng['ticket']['ticket_newcateory'] = 'Neue Kategorie erstellen';
$lng['ticket']['ticket_editcateory'] = 'Kategorie bearbeiten';
$lng['ticket']['ticket_view'] = 'Ticketverlauf ansehen';
$lng['ticket']['ticketcount'] = 'Anzahl Tickets';
$lng['ticket']['ticket_answers'] = 'Antworten';
$lng['ticket']['lastchange'] = 'Letzte Aktualisierung';
$lng['ticket']['subject'] = 'Betreff';
$lng['ticket']['status'] = 'Status';
$lng['ticket']['lastreplier'] = 'Letzte Antwort';
$lng['ticket']['priority'] = 'Priorit&auml;t';
$lng['ticket']['low'] = '<span class="ticket_low">Niedrig</span>';
$lng['ticket']['normal'] = '<span class="ticket_normal">Normal</span>';
$lng['ticket']['high'] = '<span class="ticket_high">Hoch</span>';
$lng['ticket']['unf_low'] = 'Niedrig';
$lng['ticket']['unf_normal'] = 'Normal';
$lng['ticket']['unf_high'] = 'Hoch';
$lng['ticket']['lastchange'] = 'Letzte &Auml;nderung';
$lng['ticket']['lastchange_from'] = 'Anfangsdatum (tt.mm.jjjj)';
$lng['ticket']['lastchange_to'] = 'Enddatum (tt.mm.jjjj)';
$lng['ticket']['category'] = 'Kategorie';
$lng['ticket']['no_cat'] = 'Keine';
$lng['ticket']['message'] = 'Nachricht';
$lng['ticket']['show'] = 'Anschauen';
$lng['ticket']['answer'] = 'Antworten';
$lng['ticket']['close'] = 'Schlie&szlig;en';
$lng['ticket']['reopen'] = 'Wiederer&ouml;ffnen';
$lng['ticket']['archive'] = 'Archivieren';
$lng['ticket']['ticket_delete'] = 'Ticket l&ouml;schen';
$lng['ticket']['lastarchived'] = 'Zuletzt archivierte Tickets';
$lng['ticket']['archivedtime'] = 'Archiviert';
$lng['ticket']['open'] = 'Offen';
$lng['ticket']['wait_reply'] = 'Warte auf Antwort';
$lng['ticket']['replied'] = 'Beantwortet';
$lng['ticket']['closed'] = 'Geschlossen';
$lng['ticket']['staff'] = 'Mitarbeiter';
$lng['ticket']['customer'] = 'Kunde';
$lng['ticket']['old_tickets'] = 'Bisheriger Ticketverlauf';
$lng['ticket']['search'] = 'Archiv durchsuchen';
$lng['ticket']['nocustomer'] = 'Keine Angabe';
$lng['ticket']['archivesearch'] = 'Archiv Suchergebnis';
$lng['ticket']['noresults'] = 'Keine Tickets gefunden';
$lng['ticket']['notmorethanxopentickets'] = 'Zum Schutz gegen Spam k&ouml;nnen Sie nicht mehr als %s offene Tickets haben';
$lng['ticket']['supportstatus'] = 'Support-Status';
$lng['ticket']['supportavailable'] = '<span class="ticket_low">Der Support ist besetzt und steht zu Ihrer Verf&uuml;gung.</span>';
$lng['ticket']['supportnotavailable'] = '<span class="ticket_high">Der Support ist zur Zeit nicht besetzt.</span>';
$lng['admin']['templates']['ticket'] = 'Benachrichtigungs-Mails f&uuml;r Support-Tickets';
$lng['admin']['templates']['SUBJECT'] = 'Wird mit dem Betreff des Support-Tickets ersetzt';
$lng['admin']['templates']['new_ticket_for_customer'] = 'Kunden-Information das das Ticket &uuml;bermittelt wurde';
$lng['admin']['templates']['new_ticket_by_customer'] = 'Admin-Benachrichtigung f&uuml;r ein Ticket eines Kunden';
$lng['admin']['templates']['new_reply_ticket_by_customer'] = 'Admin-Benachrichtigung f&uuml;r ein beantwortetes Ticket';
$lng['admin']['templates']['new_ticket_by_staff'] = 'Kunden-Benachrichtigung f&uuml;r ein Ticket eines Mitarbeiters';
$lng['admin']['templates']['new_reply_ticket_by_staff'] = 'Kunden-Benachrichtigung f&uuml;r ein beantwortetes Ticket';
$lng['mails']['new_ticket_for_customer']['mailbody'] = 'Hallo {FIRSTNAME} {NAME},\n\nihr Support-Ticket mit dem Betreff "{SUBJECT}" wurde erfolgreich gesendet.\n\nSobald ihr Ticket beantwortet wurde, werden Sie per E-Mail benachrichtigt.\n\nVielen Dank,\nthe SysCP-Team';
$lng['mails']['new_ticket_for_customer']['subject'] = 'Wir haben Ihr Support-Ticket erhalten.';
$lng['mails']['new_ticket_by_customer']['mailbody'] = 'Hallo Admin,\n\nein neues Support-Ticket wurde uebermittelt.\n\nBitte melde Dich an um es aufzurufen.\n\nVielen Dank,\ndas SysCP-Team';
$lng['mails']['new_ticket_by_customer']['subject'] = 'Neues Support-Ticket';
$lng['mails']['new_reply_ticket_by_customer']['mailbody'] = 'Hallo Admin,\n\ndas Support-Ticket "{SUBJECT}" wurde von einem Kunden beantwortet.\n\nBitte melde Dich an um es aufzurufen.\n\nVielen Dank,\ndas SysCP-Team';
$lng['mails']['new_reply_ticket_by_customer']['subject'] = 'Neue Antwort zu einem Support-Ticket';
$lng['mails']['new_ticket_by_staff']['mailbody'] = 'Hallo {FIRSTNAME} {NAME},\n\nein Support-Ticket mit dem Betreff "{SUBJECT}" wurde an Sie &uuml;bermittelt.\n\nBitte melden Sie sich an, um das Ticket aufzurufen.\n\nVielen Dank,\ndas SysCP-Team';
$lng['mails']['new_ticket_by_staff']['subject'] = 'Neues Support-Ticket';
$lng['mails']['new_reply_ticket_by_staff']['mailbody'] = 'Hallo {FIRSTNAME} {NAME},\n\ndas Support-Ticket mit dem Betreff "{SUBJECT}" wurde von einem Mitarbeiter beantwortet.\n\nBitte melden Sie sich an, um das Ticket aufzurufen.\n\nVielen Dank,\ndas SysCP-Team';
$lng['mails']['new_reply_ticket_by_staff']['subject'] = 'Neue Antwort zu einem Support-Ticket';
$lng['question']['ticket_reallyclose'] = 'Wollen Sie das Ticket "%s" wirklich schlie&szlig;en?';
$lng['question']['ticket_reallydelete'] = 'Wollen Sie das Ticket "%s" wirklich l&ouml;schen?';
$lng['question']['ticket_reallydeletecat'] = 'Wollen Sie die Kategorie "%s" wirklich l&ouml;schen?';
$lng['question']['ticket_reallyarchive'] = 'Wollen Sie das Ticket "%s" wirklich in das Archiv verschieben?';
$lng['error']['mysubject'] = '\'' . $lng['ticket']['subject'] . '\'';
$lng['error']['mymessage'] = '\'' . $lng['ticket']['message'] . '\'';
$lng['error']['mycategory'] = '\'' . $lng['ticket']['category'] . '\'';
$lng['error']['nomoreticketsavailable'] = 'Sie haben Ihr Ticketkontingent aufgebraucht. Bitte kontaktieren Sie ihren Administrator.';
$lng['error']['nocustomerforticket'] = 'Keine Kunden vorhanden um ein Ticket zu erstellen.';
$lng['error']['categoryhastickets'] = 'In dieser Kategorie befinden sich noch Tickets.<br />Bitte l&ouml;schen Sie diese um die Kategorie zu l&ouml;schen';
$lng['error']['notmorethanxopentickets'] = $lng['ticket']['notmorethanxopentickets'];
$lng['admin']['ticketsettings'] = 'Support-Ticket Einstellungen';
$lng['admin']['archivelastrun'] = 'Letzte Ticket-Archivierung';
$lng['serversettings']['ticket']['noreply_email'] = 'Keine-Antwort E-Mail Adresse';
$lng['serversettings']['ticket']['noreply_email_desc'] = 'Die Absender-Adresse der Support-Tickets. Meist sowas wie KEINE-ANTWORT@domain.tld';
$lng['serversettings']['ticket']['worktime_begin'] = 'Beginn Support-Zeit (hh:mm)';
$lng['serversettings']['ticket']['worktime_begin_desc'] = 'Beginn der Zeit in der der Support besetzt ist.';
$lng['serversettings']['ticket']['worktime_end'] = 'Ende Support-Zeit (hh:mm)';
$lng['serversettings']['ticket']['worktime_end_desc'] = 'Ende der Zeit in der der Support besetzt ist.';
$lng['serversettings']['ticket']['worktime_sat'] = 'Support an Samstagen besetzt?';
$lng['serversettings']['ticket']['worktime_sun'] = 'Support an Sonntagen besetzt?';
$lng['serversettings']['ticket']['worktime_all'] = 'Kein zeitlich begrenzter Support';
$lng['serversettings']['ticket']['worktime_all_desc'] = 'Wenn "Ja" &uuml;berschreibt diese Option Start- und Endzeit des Supports';
$lng['serversettings']['ticket']['archiving_days'] = 'Nach wievielen Tagen sollen abgeschlossene Tickets archiviert werden?';
$lng['customer']['tickets'] = 'Support-Tickets';

// ADDED IN 1.2.18-svn4

$lng['admin']['domain_nocustomeraddingavailable'] = 'Es k&ouml;nnen derzeit keine Domains angelegt werden. Sie m&uuml;ssen zuerst einen Kunden anlegen';
$lng['serversettings']['ticket']['enable'] = 'Ticketsystem aktivieren';
$lng['serversettings']['ticket']['concurrentlyopen'] = 'Wieviele Tickets kann ein Kunde gleichzeitig &ouml;ffnen?';
$lng['error']['norepymailiswrong'] = 'Die &quot;Keine-Antwort-Adresse&quot; ist fehlerhaft. Es ist nur eine g&uuml;ltige E-Mail-Adresse erlaubt';
$lng['error']['tadminmailiswrong'] = 'Die &quot;Ticket-Admin-Adresse&quot; ist fehlerhaft. Es ist nur eine g&uuml;ltige E-Mail-Adresse erlaubt';
$lng['ticket']['awaitingticketreply'] = 'Sie haben %s unbeantwortete(s) Support-Ticket(s)';

// ADDED IN 1.2.18-svn5

$lng['serversettings']['ticket']['noreply_name'] = 'Ticket E-Mail Absendername';

// ADDED IN 1.2.19-svn

$lng['serversettings']['mod_fcgid']['configdir'] = 'FCGI Konfigurations-Verzeichnis';
$lng['serversettings']['mod_fcgid']['configdir_desc'] = 'Wo sollen alle Konfigurationsdateien von FCGI liegen?';
$lng['serversettings']['mod_fcgid']['tmpdir'] = 'FCGI Temp Verzeichnis';

// ADDED IN 1.2.19-svn3

$lng['serversettings']['ticket']['reset_cycle'] = 'Turnus verbrauchte Tickets zur&uuml;cksetzen';
$lng['serversettings']['ticket']['reset_cycle_desc'] = 'Setzt die Anzahl der vom Kunden verbrauchten Tickets in dem angegebenen Turnus auf 0';
$lng['admin']['tickets']['daily'] = 'T&auml;glich';
$lng['admin']['tickets']['weekly'] = 'W&ouml;chentlich';
$lng['admin']['tickets']['monthly'] = 'Monatlich';
$lng['admin']['tickets']['yearly'] = 'J&auml;hrlich';
$lng['error']['ticketresetcycleiswrong'] = 'Der Turnus des Ticket-Zur&uuml;cksetzen muss "T&auml;glich", "W&ouml;chentlich", "Monatlich" oder "J&auml;hrlich" sein.';

// ADDED IN 1.2.19-svn4

$lng['menue']['traffic']['traffic'] = 'Traffic';
$lng['menue']['traffic']['current'] = 'Aktueller Monat';
$lng['traffic']['month'] = "Monat";
$lng['traffic']['months'][1] = "Januar";
$lng['traffic']['months'][2] = "Februar";
$lng['traffic']['months'][3] = "M&auml;rz";
$lng['traffic']['months'][4] = "April";
$lng['traffic']['months'][5] = "Mai";
$lng['traffic']['months'][6] = "Juni";
$lng['traffic']['months'][7] = "Juli";
$lng['traffic']['months'][8] = "August";
$lng['traffic']['months'][9] = "September";
$lng['traffic']['months'][10] = "Oktober";
$lng['traffic']['months'][11] = "November";
$lng['traffic']['months'][12] = "Dezember";
$lng['traffic']['mb'] = "Traffic (MB)";
$lng['traffic']['day'] = "Tag";
$lng['traffic']['distribution'] = '<font color="#019522">FTP</font> | <font color="#0000FF">HTTP</font> | <font color="#800000">Mail</font>';
$lng['traffic']['sumhttp'] = 'Summe HTTP-Traffic in';
$lng['traffic']['sumftp'] = 'Summe FTP-Traffic in';
$lng['traffic']['summail'] = 'Summe Mail-Traffic in';

// ADDED IN 1.2.19-svn4.5

$lng['serversettings']['no_robots']['title'] = 'Erlaube die Indizierung deines SysCP durch Suchmaschinen';

// ADDED IN 1.2.19-svn6

$lng['admin']['loggersettings'] = 'Log Einstellungen';
$lng['serversettings']['logger']['enable'] = 'Logging ja/nein';
$lng['serversettings']['logger']['severity'] = 'Logging Level';
$lng['admin']['logger']['normal'] = 'Normal';
$lng['admin']['logger']['paranoid'] = 'Paranoid';
$lng['serversettings']['logger']['types'] = 'Log-Art(en)';
$lng['serversettings']['logger']['types_desc'] = 'Tragen Sie hier die gew&uuml;nschten Logtypen kommagetrennt ein.<br />M&ouml;gliche Logtypen sind: syslog, file, mysql';
$lng['serversettings']['logger']['logfile'] = 'Log-Datei Pfad inklusive Dateinamen';
$lng['error']['logerror'] = 'Log-Fehler: %s';
$lng['serversettings']['logger']['logcron'] = 'Log Cronjobs (einen Durchgang)';
$lng['question']['logger_reallytruncate'] = 'Wollen Sie die Tabelle "%s" wirklich leeren?';
$lng['admin']['loggersystem'] = 'System-Logging';
$lng['menue']['logger']['logger'] = 'System-Logging';
$lng['logger']['date'] = 'Datum';
$lng['logger']['type'] = 'Typ';
$lng['logger']['action'] = 'Aktion';
$lng['logger']['user'] = 'Benutzer';
$lng['logger']['truncate'] = 'Log leeren';

// ADDED IN 1.2.19-svn7

$lng['serversettings']['ssl']['use_ssl'] = 'SSL nutzen?';
$lng['serversettings']['ssl']['ssl_cert_file'] = 'Pfad zum Zertifikat?';
$lng['serversettings']['ssl']['openssl_cnf'] = 'Standardwerte zum Erstellen eines Zertifikats';
$lng['panel']['reseller'] = 'Reseller';
$lng['panel']['admin'] = 'Administrator';
$lng['panel']['customer'] = 'Kunde/n';
$lng['error']['nomessagetosend'] = 'Keine Nachricht angegeben';
$lng['error']['noreceipientsgiven'] = 'Keine Empf&auml;nger angegeben';
$lng['admin']['emaildomain'] = 'E-Maildomain';
$lng['admin']['email_only'] = 'Nur E-Mail?';
$lng['admin']['wwwserveralias'] = 'Einen &quot;www.&quot; ServerAlias hinzuf&uuml;gen';
$lng['admin']['ipsandports']['enable_ssl'] = 'Ist dies ein SSL-Port?';
$lng['admin']['ipsandports']['ssl_cert_file'] = 'Pfad zum Zertifikat';
$lng['panel']['send'] = 'Versenden';
$lng['admin']['subject'] = 'Betreff';
$lng['admin']['receipient'] = 'Empf&auml;nger';
$lng['admin']['message'] = 'Nachricht schreiben';
$lng['admin']['text'] = 'Nachricht';
$lng['menu']['message'] = 'Nachrichten';
$lng['error']['errorsendingmail'] = 'Das Versenden der Nachricht an &quot;%s&quot; schlug fehl.';
$lng['error']['cannotreaddir'] = 'Der Ordner &quot;%s&quot; kann nicht gelesen werden';
$lng['message']['success'] = 'Nachricht erfolgreich an %s Empf&auml;nger gesendet';
$lng['message']['noreceipients'] = 'Es wurde keine E-Mail versendet da sich keine Empf&auml;nger in der Datenbank befinden';
$lng['admin']['sslsettings'] = 'SSL Einstellungen';
$lng['cronjobs']['notyetrun'] = 'Bisher nicht gestartet';
$lng['install']['servername_should_be_fqdn'] = 'Der Servername sollte eine FQDN sein und keine IP Adresse';
$lng['serversettings']['default_vhostconf']['title'] = 'Standard Vhost-Einstellungen';
$lng['emails']['quota'] = 'Kontingent';
$lng['emails']['quota_type']['byte'] = 'B';
$lng['emails']['quota_type']['kilobyte'] = 'KB';
$lng['emails']['quota_type']['megabyte'] = 'MB';
$lng['emails']['quota_type']['gigabyte'] = 'GB';
$lng['emails']['noquota'] = 'Kein Kontingent';
$lng['emails']['updatequota'] = 'Update';
$lng['serversettings']['mail_quota']['title'] = 'Mailbox-Kontingent';
$lng['serversettings']['mail_quota']['description'] = 'Standard-Kontingent f&uuml;r neuerstellte E-Mail Benutzerkonten';
$lng['serversettings']['mail_quota_enabled']['title'] = 'Nutze E-Mail Kontingent f&uuml;r Kunden';
$lng['serversettings']['mail_quota_enabled']['description'] = 'Aktiviere Kontingent f&uuml;r E-Mailkonten. Standard ist <b>Nein</b> da dies eine spezielle Konfiguration voraussetzt.';
$lng['serversettings']['mail_quota_enabled']['removelink'] = 'Hier klicken, um alle E-Mail Kontingente zu entfernen';
$lng['question']['admin_quotas_reallywipe'] = 'Sind Sie sicher, dass alle E-Mail Kontingente aus der Tabelle mail_users entfernt werden sollen? Dieser Schritt kann nicht r&uuml;ckg&auml;ngig gemacht werden!';
$lng['error']['vmailquotawrong'] = 'Die Kontingent-Gr&ouml;&szlig;e muss zwischen 1 und 999 liegen';
$lng['customer']['email_quota'] = 'E-Mail Kontingent';
$lng['customer']['email_imap'] = 'E-Mail IMAP';
$lng['customer']['email_pop3'] = 'E-Mail POP3';
$lng['customer']['mail_quota'] = 'E-Mail Kontingent';
$lng['error']['invalidip'] = 'Ung&uuml;ltige IP Adresse: %s';
$lng['serversettings']['decimal_places'] = 'Nachkommastellen bei der Ausgabe von Traffic/Webspace';

// ADDED IN 1.2.19-svn8

$lng['admin']['dkimsettings'] = 'DomainKey - Einstellungen';
$lng['dkim']['dkim_prefix']['title'] = 'DKIM Prefix';
$lng['dkim']['dkim_prefix']['description'] = 'Wie lautet der Pfad zu den DKIM RSA-Dateien sowie den Einstellungedateien des Milter-Plugins?';
$lng['dkim']['dkim_domains']['title'] = 'DKIM Domains Dateiname';
$lng['dkim']['dkim_domains']['description'] = '<strong>Dateiname</strong> der DKIM Domains Angabe aus der dkim-milter-Konfigurationsdatei';
$lng['dkim']['dkim_dkimkeys']['title'] = 'DKIM KeyList Dateiname';
$lng['dkim']['dkim_dkimkeys']['description'] = '<strong>Dateiname</strong> der DKIM KeyList Angabe aus der dkim-milter-Konfigurationsdatei';
$lng['dkim']['dkimrestart_command']['title'] = 'DKIM Milter Restart Kommando';
$lng['dkim']['dkimrestart_command']['description'] = 'Wie lautet das Kommando zum Neustarten des DKIM Milter Dienstes?';

// ADDED IN 1.2.19-svn9

$lng['admin']['caneditphpsettings'] = 'Kann PHP-bezogene Domaineinstellungen machen?';

// ADDED IN 1.2.19-svn12

$lng['admin']['allips'] = 'Alle IP\'s';
$lng['panel']['nosslipsavailable'] = 'F&uuml;r diesen Server wurden noch keine SSL IP/Port Kombinationen eingetragen';
$lng['ticket']['by'] = 'von';
$lng['dkim']['use_dkim']['title'] = 'DKIM Support aktivieren?';
$lng['dkim']['use_dkim']['description'] = 'Wollen Sie das Domain Keys (DKIM) System benutzen?';
$lng['error']['invalidmysqlhost'] = 'Ung&uuml;ltige MySQL Host Adresse: %s';
$lng['error']['cannotuseawstatsandwebalizeratonetime'] = 'Webalizer und Awstats k&ouml;nnen nicht zur gleichen Zeit aktiviert werden, bitte w&auml;hlen Sie eines aus';
$lng['serversettings']['webalizer_enabled'] = 'Nutze Webalizer Statistiken';
$lng['serversettings']['awstats_enabled'] = 'Nutze AWStats Statistiken';
$lng['admin']['awstatssettings'] = 'Awstats Einstellungen';
$lng['serversettings']['awstats_domain_file']['title'] = 'Awstats Domain-Dateien Ordner';
$lng['serversettings']['awstats_model_file']['title'] = 'Awstats Model Datei';

// ADDED IN 1.2.19-svn16

$lng['admin']['domain_dns_settings'] = 'Domain DNS Einstellungen';
$lng['dns']['destinationip'] = 'Domain IP';
$lng['dns']['standardip'] = 'Server Standard IP';
$lng['dns']['a_record'] = 'A-Eintrag (IPv6 optional)';
$lng['dns']['cname_record'] = 'CNAME-Eintrag';
$lng['dns']['mxrecords'] = 'MX Eintr&auml;ge definieren';
$lng['dns']['standardmx'] = 'Server Standard MX Eintrag';
$lng['dns']['mxconfig'] = 'Eigene MX Eintr&auml;ge';
$lng['dns']['priority10'] = 'Priorit&auml;t 10';
$lng['dns']['priority20'] = 'Priorit&auml;t 20';
$lng['dns']['txtrecords'] = 'TXT Eintr&auml;ge definieren';
$lng['dns']['txtexample'] = 'Beispiel (SPF-Eintrag):<br />v=spf1 ip4:xxx.xxx.xx.0/23 -all';
$lng['serversettings']['selfdns']['title'] = 'Manuelle DNS Einstellungen f&uuml;r Domains';
$lng['serversettings']['selfdnscustomer']['title'] = 'Erlaube Kunden eigene DNS Einstellungen vornehmen zu k&ouml;nnen';
$lng['admin']['activated'] = 'Aktiviert';
$lng['admin']['statisticsettings'] = 'Statistik Einstellungen';
$lng['admin']['or'] = 'oder';

// ADDED IN 1.2.19-svn17

$lng['serversettings']['unix_names']['title'] = 'Benutze UNIX kompatible Benutzernamen';
$lng['serversettings']['unix_names']['description'] = 'Erlaubt die Nutzung von <strong>-</strong> und <strong>_</strong> in Benutzernamen wenn <strong>Nein</strong>';
$lng['error']['cannotwritetologfile'] = 'Logdatei %s konnte nicht f&uuml;r Schreiboperationen ge&ouml;ffnet werden.';
$lng['admin']['sysload'] = 'System-Auslastung';
$lng['admin']['noloadavailable'] = 'nicht verf&uuml;gbar';
$lng['admin']['nouptimeavailable'] = 'nicht verf&uuml;gbar';
$lng['panel']['backtooverview'] = 'Zur&uuml;ck zur &Uuml;bersicht';
$lng['admin']['nosubject'] = '(Kein Betreff)';
$lng['admin']['configfiles']['statistics'] = 'Statistik';
$lng['login']['forgotpwd'] = 'Passwort vergessen?';
$lng['login']['presend'] = 'Passwort zur&uuml;cksetzen';
$lng['login']['email'] = 'E-Mail Adresse';
$lng['login']['remind'] = 'Passwort zur&uuml;cksetzen';
$lng['login']['usernotfound'] = 'Fehler: Unbekannter Benutzer!';
$lng['pwdreminder']['subject'] = 'SysCP - Passwort zur&uuml;ckgesetzt';
$lng['pwdreminder']['body'] = 'Hallo %s,\n\ndein SysCP Passwort wurde zur&uuml;ckgesetzt!\nDas neue Passwort lautet: %p\n\nVielen Dank,\nIhr SysCP-Team';
$lng['pwdreminder']['success'] = 'Passwort erfolgreich zur&uuml;ckgesetzt.<br />Sie sollten nun eine E-Mail mit dem neuen Passwort erhalten.';

// ADDED IN 1.2.19-svn18

$lng['serversettings']['allow_preset'] = 'Erlaube das Zur&uuml;cksetzen des Kundenpassworts';
$lng['pwdreminder']['notallowed'] = 'Das Zur&uuml;cksetzen des Passworts ist deaktiviert';

// ADDED IN 1.2.19-svn20

$lng['serversettings']['awstats_path']['title'] = 'Pfad zum awstats cgi-bin Ordner';
$lng['serversettings']['awstats_path']['description'] = 'z.B. /usr/share/webapps/awstats/6.1/webroot/cgi-bin/';
$lng['serversettings']['awstats_updateall_command']['title'] = 'Pfad zu &quot;awstats_updateall.pl&quot;';
$lng['serversettings']['awstats_updateall_command']['description'] = 'z.B. /usr/bin/awstats_updateall.pl';

?>

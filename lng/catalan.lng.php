<?php
/**
 * filename: $Source$
 * begin: Friday, Aug 06, 2004
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version. This program is distributed in the
 * hope that it will be useful, but WITHOUT ANY WARRANTY; without even the
 * implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU General Public License for more details.
 *
 * @author Jordi Romero (jordi@jrom.net) 
 * @copyright (C) 2005 Jordi Romero
 * @package Language
 * @version $Id$
 */


/**
 * Global
 */
$lng['panel']['edit'] = 'editar';
$lng['panel']['delete'] = 'esborrar';
$lng['panel']['create'] = 'crear';
$lng['panel']['save'] = 'guardar';
$lng['panel']['yes'] = 'sí';
$lng['panel']['no'] = 'no';
$lng['panel']['emptyfornochanges'] = 'deixeu-ho buit per no canviar-ne el valor';
$lng['panel']['emptyfordefault'] = 'deixeu-ho buit per usar el valor predeterminat';
$lng['panel']['path'] = 'Ruta';
$lng['panel']['toggle'] = 'Marcar/Desmarcar';
$lng['panel']['next'] = 'següent';

/**
 * Login
 */
$lng['login']['username'] = 'Nom d\'usuari';
$lng['login']['password'] = 'Contrassenya';
$lng['login']['language'] = 'Idioma';
$lng['login']['login'] = 'Identificar-se';
$lng['login']['logout'] = 'sortir';
$lng['login']['profile_lng'] = 'Idioma del perfil';

/**
 * Customer
 */
$lng['customer']['login'] = 'Nom d\'usuari';
$lng['customer']['documentroot'] = 'Directori HOME';
$lng['customer']['name'] = 'Nom';
$lng['customer']['surname'] = 'Cognom';
$lng['customer']['company'] = 'Empresa';
$lng['customer']['street'] = 'Carrer';
$lng['customer']['zipcode'] = 'Codi Postal';
$lng['customer']['city'] = 'ciutat';
$lng['customer']['phone'] = 'telèfon';
$lng['customer']['fax'] = 'fax';
$lng['customer']['email'] = 'email';
$lng['customer']['customernumber'] = 'ID de client';
$lng['customer']['diskspace'] = 'Espai Web (MB)';
$lng['customer']['traffic'] = 'Trafic (GB)';
$lng['customer']['mysqls'] = 'Bases de dades MySQL';
$lng['customer']['emails'] = 'Adreces de correu';
$lng['customer']['accounts'] = 'Comptes d\'e-mail';
$lng['customer']['forwarders'] = 'Redireccionadors de correu';
$lng['customer']['ftps'] = 'Comptes FTP';
$lng['customer']['subdomains'] = 'Subdomini(s)';
$lng['customer']['domains'] = 'Domini(s)';
$lng['customer']['unlimited'] = 'ilimitat';

/**
 * Customermenue
 */
$lng['menue']['main']['main'] = 'Principal';
$lng['menue']['main']['changepassword'] = 'Canviar Clau';
$lng['menue']['main']['changelanguage'] = 'Canviar Idioma';
$lng['menue']['email']['email'] = 'email';
$lng['menue']['email']['emails'] = 'Adreces';
$lng['menue']['email']['webmail'] = 'WebMail';
$lng['menue']['mysql']['mysql'] = 'MySQL';
$lng['menue']['mysql']['databases'] = 'Bases de dades';
$lng['menue']['mysql']['phpmyadmin'] = 'phpMyAdmin';
$lng['menue']['domains']['domains'] = 'Dominis';
$lng['menue']['domains']['settings'] = 'Opcions';
$lng['menue']['ftp']['ftp'] = 'FTP';
$lng['menue']['ftp']['accounts'] = 'Comptes';
$lng['menue']['ftp']['webftp'] = 'WebFTP';
$lng['menue']['extras']['extras'] = 'Extres';
$lng['menue']['extras']['directoryprotection'] = 'Protecció de directori(s)';
$lng['menue']['extras']['pathoptions'] = 'Opcions de la ruta (PATH)';

/**
 * Index
 */
$lng['index']['customerdetails'] = 'Detalls del client';
$lng['index']['accountdetails'] = 'Detalls del compte';

/**
 * Change Password
 */
$lng['changepassword']['old_password'] = 'Clau antiga';
$lng['changepassword']['new_password'] = 'Clau nova';
$lng['changepassword']['new_password_confirm'] = 'Clau nova (confirmació)';
$lng['changepassword']['new_password_ifnotempty'] = 'Clau nova (Deixa-ho buit per no canviar)';
$lng['changepassword']['also_change_ftp'] = ' canvia també la clau del compte principal del FTP';

/**
 * Domains
 */
$lng['domains']['description'] = 'Des d\'aquí pots crear (sub)dominis i canviar les seves rutes.<br />El sistema necessitarà una mica de temps per aplicar els nous canvis un cop efectuats.';
$lng['domains']['domainsettings'] = 'Opcions de domini';
$lng['domains']['domainname'] = 'Nom del domini';
$lng['domains']['subdomain_add'] = 'Crear subdomini';
$lng['domains']['subdomain_edit'] = 'Editar (sub)domini';
$lng['domains']['wildcarddomain'] = 'Crear un domini comodí? (wildcarddomain)';

/**
 * eMails
 */
$lng['emails']['description'] = 'Des d\'aquí pots modificar les adreces de correu, crear-ne de noves o esborrar les que hi ha.<br />Pensa que després de crear la adreça de correu, has de crear o bé un COMPTE o bé un REDIRECCIONADOR, sense una de les dues coses la adreça és inútil.<br /><br />Per baixar-te el correu en el teu client de correu (Outlook, Thunderbird, ...) utilitza la següent informació: (La informació en <i>cursiva</i> ha de ser substituida per la que correspongui al compte de correu en questió!)<br />Servidor de correu entrant o sortint: <b><i>el teu domini</i></b><br />Nom dusuari: <b><i>(usuari@exemple.com)</i></b> (ATENCIÓ: És imprescindible posar la adreça sencera en el camp de l\'usuari, en cas contrari no funcionaria)<br />Clau: <b><i>la clau del compte de correu</i></b>';
$lng['emails']['emailaddress'] = 'adreces d\'email';
$lng['emails']['emails_add'] = 'Crear adreça d\'email';
$lng['emails']['emails_edit'] = 'Editar adreces d\'email';
$lng['emails']['catchall'] = 'Compte comodí';
$lng['emails']['iscatchall'] = 'Definir els Comptes comodí?';
$lng['emails']['account'] = 'Compte';
$lng['emails']['account_add'] = 'Crear compte';
$lng['emails']['account_delete'] = 'Esborrar compte';
$lng['emails']['from'] = 'Origen';
$lng['emails']['to'] = 'Destí';
$lng['emails']['forwarders'] = 'Redireccions';
$lng['emails']['forwarder_add'] = 'Crear redirecció';

/**
 * FTP
 */
$lng['ftp']['description'] = 'Aquí pots crear els teus comptes FTP.<br />Els canis s\'aplicaran a l\'instant.';
$lng['ftp']['account_add'] = 'Crear compte';

/**
 * MySQL
 */
$lng['mysql']['description'] = 'Pendent de traduir<br />Here you can create and change your MySQL-Databases.<br />The changes are made instantly and the database can be used immediately.<br />At the menu on the left side you find the tool phpMyAdmin with which you can easily administer your database.<br /><br />To use your databases in your own php-scripts use the following settings: (The data in <i>italics</i> have to be changed into the equivalents you typed in!)<br />Hostname: <b>localhost</b><br />Username: <b><i>Databasename</i></b><br />Password: <b><i>the password you\'ve chosen</i></b><br />Database: <b><i>Databasename';
$lng['mysql']['databasename'] = 'nom d\'usuari/base de dades';
$lng['mysql']['databasedescription'] = 'descripció de la base de dades';
$lng['mysql']['database_create'] = 'Crear base de dades';

/**
 * Extras
 */
$lng['extras']['description'] = 'Aquí pots controlar alguns extres, com protecció de directoris.<br />El sistema requereix una mica de temps en aplicar els canvis un cop fets.';
$lng['extras']['directoryprotection_add'] = 'Afegir protecció de directori';
$lng['extras']['view_directory'] = 'mostra el contingut del directori';
$lng['extras']['pathoptions_add'] = 'afegir opcions de la ruta (PATH)';
$lng['extras']['directory_browsing'] = 'navegar pel contingut del directori';
$lng['extras']['pathoptions_edit'] = 'editar opcions de la ruta (PATH)';
$lng['extras']['error404path'] = '404';
$lng['extras']['error403path'] = '403';
$lng['extras']['error500path'] = '500';
$lng['extras']['error401path'] = '401';
$lng['extras']['errordocument404path'] = 'Ruta a ErrorDocument 404';
$lng['extras']['errordocument403path'] = 'Ruta a ErrorDocument 403';
$lng['extras']['errordocument500path'] = 'Ruta a ErrorDocument 500';
$lng['extras']['errordocument401path'] = 'Ruta a ErrorDocument 401';

/**
 * Errors
 */
$lng['error']['error'] = 'Error';
$lng['error']['directorymustexist'] = 'El directori que has escrit no existeix. Si us plau, crea\'l per FTP.';
$lng['error']['filemustexist'] = 'El fitxer ha d\'existir.';
$lng['error']['allresourcesused'] = 'Ja has gastat tots els teus recursos!';
$lng['error']['domains_cantdeletemaindomain'] = 'No pots esborrar aquest domini perquè està sent usat en una adreça d\'email.';
$lng['error']['domains_canteditdomain'] = 'No pots editar aquests dominis. Han estat bloquejats per l\'administrador';
$lng['error']['domains_cantdeletedomainwithemail'] = 'No pots esborrar aquest domini perquè està sent usat per una direcció de correu. Has d\'esborrar abans la direcció de correu';
$lng['error']['firstdeleteallsubdomains'] = 'No pots esborrar tots els subdominis si no tens un domini comodí (Wildcarddomain).';
$lng['error']['youhavealreadyacatchallforthisdomain'] = 'Ja tens un compte comodí';
$lng['error']['ftp_cantdeletemainaccount'] = 'No pots esborrar el compte principal FTP';
$lng['error']['login'] = 'El nom d\'usuari o la Clau són incorrectes. Sisplau torna-ho a intentar!';
$lng['error']['login_blocked'] = 'Aquest compte ha estat suspès a causa de massa intents fraudulents d\'identificació. <br />Si us plau, torna a provar-ho en '.$settings['login']['deactivatetime'].' segons.';
$lng['error']['notallreqfieldsorerrors'] = 'No has omplert tots els camps o algun camp és incorrecte.';
$lng['error']['oldpasswordnotcorrect'] = 'La clau antiga no és la correcta.';
$lng['error']['youcantallocatemorethanyouhave'] = 'No pots ocupar més espai del que tens assignat!';
$lng['error']['youcantdeletechangemainadmin'] = 'L\'usuari admin és sagrat...';
$lng['error']['mustbeurl'] = 'No has escrit una URL correcte';

/**
 * Questions
 */
$lng['question']['question'] = 'Preguntes de seguretat..';
$lng['question']['admin_customer_reallydelete'] = 'Estas segur que vols esborrar el client %s? Aquesta acció és irreversible!';
$lng['question']['admin_domain_reallydelete'] = 'Segur que vols esborrar el domini %s?';
$lng['question']['admin_domain_reallydisablesecuritysetting'] = 'Segur que vols desactivar aquesta opció de seguretat? (OpenBasedir and/or SafeMode)?';
$lng['question']['admin_admin_reallydelete'] = 'Segur que vols esborrar l\'administrador %s? Tots els seus clients aniran a parar a l\'administrador principal.';
$lng['question']['admin_template_reallydelete'] = 'Segur que vols esborrar aquesta plantilla \'%s\'?';
$lng['question']['domains_reallydelete'] = 'Segur que vols esborrar el domini %s?';
$lng['question']['email_reallydelete'] = 'Segur que vols esborrar la adreça %s?';
$lng['question']['email_reallydelete_account'] = 'Segur que vols esborrar el compte %s?';
$lng['question']['email_reallydelete_forwarder'] = 'Segur que vols esborrar la redirecció %s?';
$lng['question']['extras_reallydelete'] = 'Segur que vols esborrar la direcció de directori de %s?';
$lng['question']['extras_reallydelete_pathoptions'] = 'Segur que vols eliminar les opcions de ruta (PATH) de %s?';
$lng['question']['ftp_reallydelete'] = 'Segur que vols esborrar el compte FTP %s?';
$lng['question']['mysql_reallydelete'] = 'Segur que vols eliminar la base de dades %s? Aquesta acció és irreversible!';

/**
 * Mails
 */
$lng['mails']['pop_success']['mailbody'] = 'Hola,\n\nel teu compte d\'email $destination\ns\'ha creat satisfactoriament.\n\nAixò és un missatge creat automàticament, si us plau uno responguis. Gràcies.';
$lng['mails']['pop_success']['subject'] = 'Compte de correu creat satisfactoriament';
$lng['mails']['createcustomer']['mailbody'] = 'Hola $surname $name,\n\n aquí te la seva informació:\n\nNom d\'usuari: $loginname\nClau: $password\n\n Gràcies per tot, disfruta del teu compte';
$lng['mails']['createcustomer']['subject'] = 'Informació del compte';

/**
 * Admin
 */
$lng['admin']['overview'] = 'Resum';
$lng['admin']['ressourcedetails'] = 'Recursos utilitzats';
$lng['admin']['systemdetails'] = 'Detalls del sistema';
$lng['admin']['syscpdetails'] = 'Detalls de SysCP';
$lng['admin']['installedversion'] = 'Versió';
$lng['admin']['latestversion'] = 'Última versió';
$lng['admin']['lookfornewversion']['clickhere'] = 'cerca a internet';
$lng['admin']['lookfornewversion']['error'] = 'Error carregant';
$lng['admin']['resources'] = 'Personal';
$lng['admin']['customer'] = 'Client';
$lng['admin']['customers'] = 'Clients';
$lng['admin']['customer_add'] = 'Crear client';
$lng['admin']['customer_edit'] = 'Editar client';
$lng['admin']['domains'] = 'Dominis';
$lng['admin']['domain_add'] = 'Crear domini';
$lng['admin']['domain_edit'] = 'Editar domini';
$lng['admin']['subdomainforemail'] = 'Subdomini com a subdomini de correu';
$lng['admin']['admin'] = 'Administrador';
$lng['admin']['admins'] = 'Administradors';
$lng['admin']['admin_add'] = 'Crear administrador';
$lng['admin']['admin_edit'] = 'Editar administrador';
$lng['admin']['customers_see_all'] = 'Pot veure tots els clients?';
$lng['admin']['domains_see_all'] = 'Pot veure tots els dominis?';
$lng['admin']['change_serversettings'] = 'Pot canviar configuracions del servidor?';
$lng['admin']['server'] = 'Servidor';
$lng['admin']['serversettings'] = 'Opcions del servidor';
$lng['admin']['stdsubdomain'] = 'Subdomini estàndar';
$lng['admin']['stdsubdomain_add'] = 'Crear subdomini estàndar';
$lng['admin']['deactivated'] = 'Desactivat';
$lng['admin']['deactivated_user'] = 'Desactivar Usuari';
$lng['admin']['sendpassword'] = 'Enviar Clau';
$lng['admin']['ownvhostsettings'] = 'Opcions dels vhost propis';
$lng['admin']['configfiles']['serverconfiguration'] = 'Configuracions del servidor';
$lng['admin']['configfiles']['files'] = '<b>Fitxers de configuració:</b> Si us plau, canvia els continguts fitxers o crea\'ls<br />amb el cotingut que surt a continuació si no existeixen.<br /><b>Nota:</b> El Mysql-password no ha estat modificat per questions de seguretat.<br />Canvia &quot;MYSQL_PASSWORD&quot; per la clau que desitgi';
$lng['admin']['configfiles']['commands'] = '<b>Comandaments:</b> Executa\'ls en una consola.';
$lng['admin']['configfiles']['restart'] = '<b>Restart:</b> Executa els següents comandaments en la consola per carregar la nova configuració.';
$lng['admin']['templates']['templates'] = 'Plantilles';
$lng['admin']['templates']['template_add'] = 'Afegir plantilla';
$lng['admin']['templates']['template_edit'] = 'Editar plantilla';
$lng['admin']['templates']['action'] = 'Acció';
$lng['admin']['templates']['email'] = 'E-Mail';
$lng['admin']['templates']['subject'] = 'Assumpte';
$lng['admin']['templates']['mailbody'] = 'Cos del missatge';
$lng['admin']['templates']['createcustomer'] = 'Missatge de benvinguda a nous clients';
$lng['admin']['templates']['pop_success'] = 'Missatge de benvinguda a les noves comptes de correu';
$lng['admin']['templates']['template_replace_vars'] = 'Variables per substituir a la plantilla:';
$lng['admin']['templates']['FIRSTNAME'] = 'Substituit pel cognom del client.';
$lng['admin']['templates']['NAME'] = 'Substituit pel nom del client.';
$lng['admin']['templates']['USERNAME'] = 'Reemplaçat pel nom d\'usuari';
$lng['admin']['templates']['PASSWORD'] = 'Reemplaçat per la contrassenya.';
$lng['admin']['templates']['EMAIL'] = 'Reemplaçat per l\'adreça de correu';

/**
 * Serversettings
 */
$lng['serversettings']['session_timeout']['title'] = 'Sessió Timeout';
$lng['serversettings']['session_timeout']['description'] = 'Quant triga un usuari en esdevenir inactiu (segons)?';
$lng['serversettings']['accountprefix']['title'] = 'Prefix del client';
$lng['serversettings']['accountprefix']['description'] = 'Quin prefix han de tenir els clients?';
$lng['serversettings']['mysqlprefix']['title'] = 'Prefix SQL';
$lng['serversettings']['mysqlprefix']['description'] = 'Quin prefix han de tenir els comptes SQL?';
$lng['serversettings']['ftpprefix']['title'] = 'Prefix FTP';
$lng['serversettings']['ftpprefix']['description'] = 'Quin prefix han de tenir els comptes FTP?';
$lng['serversettings']['documentroot_prefix']['title'] = 'Directori principal';
$lng['serversettings']['documentroot_prefix']['description'] = 'On es desen els documents?';
$lng['serversettings']['logfiles_directory']['title'] = 'Directori de logs';
$lng['serversettings']['logfiles_directory']['description'] = 'On es desen els fitxers de registre (log)?';
$lng['serversettings']['ipaddress']['title'] = 'Adreça IP';
$lng['serversettings']['ipaddress']['description'] = 'Quina és la adreça IP del servidor?';
$lng['serversettings']['hostname']['title'] = 'Hostname';
$lng['serversettings']['hostname']['description'] = 'Quin és el hostname del servidor?';
$lng['serversettings']['apacheconf_directory']['title'] = 'Directori de configuració d\'Apache';
$lng['serversettings']['apacheconf_directory']['description'] = 'On estan els fitxers de configuració d\'Apache?';
$lng['serversettings']['apachereload_command']['title'] = 'Comanda de reinici d\'Apache';
$lng['serversettings']['apachereload_command']['description'] = 'Quina és la comanda per reiniciar Apache?';
$lng['serversettings']['bindconf_directory']['title'] = 'Directori de configuració de Bind';
$lng['serversettings']['bindconf_directory']['description'] = 'On són els fitxers de configuració de Bind?';
$lng['serversettings']['bindreload_command']['title'] = 'Comanda de reinici de Bind';
$lng['serversettings']['bindreload_command']['description'] = 'Quina és la comanda per reiniciar Bind?';
$lng['serversettings']['binddefaultzone']['title'] = 'Zona predeterminada de Bind';
$lng['serversettings']['binddefaultzone']['description'] = 'Quina és la zona per defecte de Bind?';
$lng['serversettings']['vmail_uid']['title'] = 'UID de les adreces de correu';
$lng['serversettings']['vmail_uid']['description'] = 'Quina User ID han de tenir les adreces de correu?';
$lng['serversettings']['vmail_gid']['title'] = 'GID de les adreces de correu';
$lng['serversettings']['vmail_gid']['description'] = 'Quina Group ID han de tenir les adreces de correu?';
$lng['serversettings']['vmail_homedir']['title'] = 'Directori dels correus';
$lng['serversettings']['vmail_homedir']['description'] = 'Quin és el directori on es desaran tots els missatges de correu?';
$lng['serversettings']['adminmail']['title'] = 'Remitent';
$lng['serversettings']['adminmail']['description'] = 'Quin és el remitent dels missatges del SysCP?';
$lng['serversettings']['phpmyadmin_url']['title'] = 'Adreça de phpMyAdmin';
$lng['serversettings']['phpmyadmin_url']['description'] = 'Quina és la URL del phpMyAdmin? (ha de començar amb http://)';
$lng['serversettings']['webmail_url']['title'] = 'Adreça de WebMail';
$lng['serversettings']['webmail_url']['description'] = 'Quina és la URL del WebMail? (ha de començar amb http://)';
$lng['serversettings']['webftp_url']['title'] = 'Adreça de WebFTP';
$lng['serversettings']['webftp_url']['description'] = 'Quina és la URL del WebFTP? (ha de començar amb http://)';
$lng['serversettings']['language']['description'] = 'Quin és l\'idioma per defecte?';
$lng['serversettings']['maxloginattempts']['title']       = 'Intents de logueix màxims';
$lng['serversettings']['maxloginattempts']['description'] = 'Número de vegades que pots intentar identificar-te abans de que la compta es desactivi.';
$lng['serversettings']['deactivatetime']['title']       = 'Temps de desactivacio';
$lng['serversettings']['deactivatetime']['description'] = 'Segons que la compta estarà inactiva quan s\'ha produit un seguit d\'intents frustrats d\'identificació.';

?>
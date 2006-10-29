<?php

/**
 * This file is part of the SysCP project.
 * Copyright (c) 2003-2006 the SysCP Project.
 *
 * For the full copyright and license information, please view the COPYING
 * file that was distributed with this source code. You can also view the
 * COPYING file online at http://files.syscp.org/misc/COPYING.txt
 *
 * @author     Tim Zielosko <tim.zielosko@syscp.org>
 * @author     Aldo Reset <aldo.reset@placenet.org>
 * @copyright  (c) 2004 - 2005 Tim Zielosko
 * @copyright  (c) 2006 Tim Zielosko, Aldo Reset
 * @package    Syscp.Misc
 * @subpackage Language
 * @license    GPLv2 http://files.syscp.org/misc/COPYING.txt
 * @version    $Id$
 */

/**
 * Global
 */

$lng['panel']['edit'] = 'Modifier';
$lng['panel']['delete'] = 'Effacer';
$lng['panel']['create'] = 'Ajouter';
$lng['panel']['save'] = 'Sauvegarder';
$lng['panel']['yes'] = 'Oui';
$lng['panel']['no'] = 'Non';
$lng['panel']['emptyfornochanges'] = 'Veuillez laisser vide pour aucun changement';
$lng['panel']['emptyfordefault'] = 'Veuillez laisser vide pour l\'option standard';
$lng['panel']['path'] = 'Chemin';
$lng['panel']['toggle'] = 'Activer/D&eacute;sactiver';
$lng['panel']['next'] = 'continuer';
$lng['panel']['dirsmissing'] = 'Dossiers non disponibles ou illisibles';

/**
 * Login
 */

$lng['login']['username'] = 'Identifiant';
$lng['login']['password'] = 'Mot de passe';
$lng['login']['language'] = 'Langue';
$lng['login']['login'] = 'S\'identifier';
$lng['login']['logout'] = 'Se d&eacute;connecter';
$lng['login']['profile_lng'] = 'Langage du profil';

/**
 * Customer
 */

$lng['customer']['documentroot'] = 'Chemin';
$lng['customer']['name'] = 'Nom';
$lng['customer']['firstname'] = 'Pr&eacute;nom';
$lng['customer']['company'] = 'Entreprise';
$lng['customer']['street'] = 'Rue';
$lng['customer']['zipcode'] = 'Code postal';
$lng['customer']['city'] = 'Ville';
$lng['customer']['phone'] = 'T&eacute;l&eacute;phone';
$lng['customer']['fax'] = 'Fax';
$lng['customer']['email'] = 'e-mail';
$lng['customer']['customernumber'] = 'Numero du client';
$lng['customer']['diskspace'] = 'Webspace (MB)';
$lng['customer']['traffic'] = 'Trafic (GB)';
$lng['customer']['mysqls'] = 'Base(s) de donn&eacute;es MySQL';
$lng['customer']['emails'] = 'Adresse(s) e-mail';
$lng['customer']['accounts'] = 'Acc&egrave;s e-mail';
$lng['customer']['forwarders'] = 'Remailer e-mail';
$lng['customer']['ftps'] = 'Acc&egrave;s FTP';
$lng['customer']['subdomains'] = 'Sous-Domaine(s)';
$lng['customer']['domains'] = 'Domaine(s)';
$lng['customer']['unlimited'] = 'illimit&eacute;';

/**
 * Customermenue
 */

$lng['menue']['main']['main'] = 'General';
$lng['menue']['main']['changepassword'] = 'Changer de mot de passe';
$lng['menue']['main']['changelanguage'] = 'Changer de langage';
$lng['menue']['email']['email'] = 'e-mail';
$lng['menue']['email']['emails'] = 'Adresse(s)';
$lng['menue']['email']['webmail'] = 'Webmail';
$lng['menue']['mysql']['mysql'] = 'MySQL';
$lng['menue']['mysql']['databases'] = 'Bases de donn&eacute;es';
$lng['menue']['mysql']['phpmyadmin'] = 'phpMyAdmin';
$lng['menue']['domains']['domains'] = 'Domaines';
$lng['menue']['domains']['settings'] = 'R&eacute;glages';
$lng['menue']['ftp']['ftp'] = 'FTP';
$lng['menue']['ftp']['accounts'] = 'Acc&egrave;s';
$lng['menue']['ftp']['webftp'] = 'WebFTP';
$lng['menue']['extras']['extras'] = 'Extras';
$lng['menue']['extras']['directoryprotection'] = 'Protection des dossiers';
$lng['menue']['extras']['pathoptions'] = 'Options du chemin';

/**
 * Index
 */

$lng['index']['customerdetails'] = 'Donn&eacute;es du compte';
$lng['index']['accountdetails'] = 'Donn&eacute;es de l\'acc&egrave;s';

/**
 * Change Password
 */

$lng['changepassword']['old_password'] = 'Ancien mot de passe';
$lng['changepassword']['new_password'] = 'Nouveau mot de passe';
$lng['changepassword']['new_password_confirm'] = 'Nouveau mot de passe (confirmer)';
$lng['changepassword']['new_password_ifnotempty'] = 'Nouveau mot de passe (Veuillez laisser vide pour aucun changement)';
$lng['changepassword']['also_change_ftp'] = ' Changer aussi le mot de passe de l\'acc&egrave;s FTP general';

/**
 * Domains
 */

$lng['domains']['description'] = 'Ici vous pouvez inscrire des Domaines et changer ses chemins.<br />Il faut un peu de temps apr&egrave;s chaque changement pour relire la configuration.';
$lng['domains']['domainsettings'] = 'Configuration des Domaines';
$lng['domains']['domainname'] = 'Nom du Domaine';
$lng['domains']['subdomain_add'] = 'Ajouter un Sous-domaine';
$lng['domains']['subdomain_edit'] = 'Changer un Sous-domaine';
$lng['domains']['wildcarddomain'] = 'Domaine Wildcard?';
$lng['domains']['aliasdomain'] = 'Pseudonyme pour un domaine';
$lng['domains']['noaliasdomain'] = 'Domaine non-pseudonyme';

/**
 * eMails
 */

$lng['emails']['description'] = 'Ici vous pouvez ajouter vos boites &agrave; e-mail.<br><br>Les donn&eacute;es pour configurer votre logiciel e-mail sont celles-la: <br><br>Nom du server: <b><i>votre domaine</i></b><br>Identifiant: <b><i>l\'adresse e-mail</i></b><br>Mot de passe: <b><i>le mot de passe que vous avez choisi</i></b>';
$lng['emails']['emailaddress'] = 'Adresse';
$lng['emails']['emails_add'] = 'Ajouter une Adresse';
$lng['emails']['emails_edit'] = 'Changer une adresse';
$lng['emails']['catchall'] = 'Catchall';
$lng['emails']['iscatchall'] = 'D&eacute;finer comme adresse catchall?';
$lng['emails']['account'] = 'Acc&egrave;s';
$lng['emails']['account_add'] = 'Ajouter un acc&egrave;s';
$lng['emails']['account_delete'] = 'Effacer acc&egrave;s';
$lng['emails']['from'] = 'de';
$lng['emails']['to'] = '&agrave;';
$lng['emails']['forwarders'] = 'Re-exp&eacute;dissions';
$lng['emails']['forwarder_add'] = 'Ajouter un Renvoi';

/**
 * FTP
 */

$lng['ftp']['description'] = 'Ici vous pouvez ajouter des acc&egrave;s FTP suppl&eacute;mentaire.<br />Les changements sont tout de suite op&eacute;rant et l\'acc&egrave;s est disponible.';
$lng['ftp']['account_add'] = 'Ajouter un acc&egrave;s';

/**
 * MySQL
 */

$lng['mysql']['description'] = 'Ici vous pouvez ajouter et effacer des bases de donn&eacute;es MySQL.<br>Les changements sont tout de suite op&eacute;rant et les banques sont disponibles.<br>Sur le menu on trouve un lien &agrave; phpMyAdmin, avec lequel vous pouvez modifier vos banques de donn&eacute;es.<br><br>L\'acc&egrave;s de PHP fonctionne comme ca: (Il faut modifier les valeurs en <i>italique</i> en mettant ce que c\'est!)<br><br>$connection = mysql_connect("localhost", "<i>Votre identifiant</i>", "<i>Votre mot de passe</i>");<br>mysql_select_db("<i>Le nom de la banque</i>", $connection);';
$lng['mysql']['databasename'] = 'Nom de la base';
$lng['mysql']['databasedescription'] = 'Description de la base';
$lng['mysql']['database_create'] = 'Ajouter une base de donn&eacute;es';

/**
 * Extras
 */

$lng['extras']['description'] = 'Ici vous pouvez ajouter des extras suppl&eactue;mentaires, par example la protection des listes.<br />Il faut un peu de temps apr&egrave;s chaque changement pour reliser la configuration.';
$lng['extras']['directoryprotection_add'] = 'Ajouter une protection de dossier';
$lng['extras']['view_directory'] = 'Faire voir le dossier';
$lng['extras']['pathoptions_add'] = 'Ajouter des options de chemin';
$lng['extras']['directory_browsing'] = 'Montrer le contenu des dossiers';
$lng['extras']['pathoptions_edit'] = 'Modifier les options de chemin';
$lng['extras']['error404path'] = '404';
$lng['extras']['error403path'] = '403';
$lng['extras']['error500path'] = '500';
$lng['extras']['error401path'] = '401';
$lng['extras']['errordocument404path'] = 'Chemin du document erreur 404';
$lng['extras']['errordocument403path'] = 'Chemin du document erreur 403';
$lng['extras']['errordocument500path'] = 'Chemin du document erreur 500';
$lng['extras']['errordocument401path'] = 'Chemin du document erreur 401';

/**
 * Errors
 */

$lng['error']['error'] = 'Erreur';
$lng['error']['directorymustexist'] = 'Le dossier que vous avez choisi n\'existe pas. S\'il vous plait ajouter le avec votre client FTP.';
$lng['error']['filemustexist'] = 'Le fichier que vous avez choisi n\'existe pas.';
$lng['error']['allresourcesused'] = 'Vous avez d&eacute;j&agrave; us&eacute;s toutes les ressources.';
$lng['error']['domains_cantdeletemaindomain'] = 'Vous ne pouvez pas effacer un domaine qui est utilis&eacute; pour des adresses e-mail. ';
$lng['error']['domains_canteditdomain'] = 'Vous n\'avez pas le droit de configurer ce domaine.';
$lng['error']['domains_cantdeletedomainwithemail'] = 'Vous ne pouvez pas effacer un domaine qui est utilis&eacute; pour des e-mails. Il faut effacer toutes ses adresses avant.';
$lng['error']['firstdeleteallsubdomains'] = 'Il faut effacer tous les sous-domaines avant d\'ajouter un domaine Wildcard.';
$lng['error']['youhavealreadyacatchallforthisdomain'] = 'Vous avez d&eacute;j&agrave; defin&eacute; une adresse catchall pour ce domaine.';
$lng['error']['ftp_cantdeletemainaccount'] = 'Vous ne pouvez pas effacer votre acc&egrave;s principal.';
$lng['error']['login'] = 'Identifiant / mot de passe invalide.';
$lng['error']['login_blocked'] = 'Cet acc&egrave;s &eacute;tait bloqu&eacute; &agrave; cause de nombreux login invalides.<br />S\'il vous-plait l\'essayer encore dans %s secondes.';
$lng['error']['notallreqfieldsorerrors'] = 'Vous n\'avez pas rempli toutes les cases ou vous l\'avez rempli avec des valeurs invalides.';
$lng['error']['oldpasswordnotcorrect'] = 'L\'ancien mot de passe n\'est pas correct.';
$lng['error']['youcantallocatemorethanyouhave'] = 'Vous ne pouvez pas distribuer plus de ressource qu\'il n\'en reste.';
$lng['error']['youcantdeletechangemainadmin'] = 'Pour des raisons de la s&eacute;curit&eacute; ce n\'est pas possible d\'effacer ou modifier l\'administrateur principal.';
$lng['error']['mustbeurl'] = 'Vous n\'avez pas dict&eacute; une adresse URL valide.';
$lng['error']['invalidpath'] = 'Vous n\'avez pas choisi une adresse URL valide (Probablement &agrave; cause de probl&egrave;s avec le listing de dossiers?)';
$lng['error']['stringisempty'] = 'Entr&eacute;e manquante';
$lng['error']['stringiswrong'] = 'Entr&eacute;e invalide';
$lng['error']['myloginname'] = '\''.$lng['login']['username'].'\'';
$lng['error']['mypassword'] = '\''.$lng['login']['password'].'\'';
$lng['error']['oldpassword'] = '\''.$lng['changepassword']['old_password'].'\'';
$lng['error']['newpassword'] = '\''.$lng['changepassword']['new_password'].'\'';
$lng['error']['newpasswordconfirm'] = '\''.$lng['changepassword']['new_password_confirm'].'\'';
$lng['error']['newpasswordconfirmerror'] = 'Les deux nouveaux mots de passe ne sont pas identiques.';
$lng['error']['myname'] = '\''.$lng['customer']['name'].'\'';
$lng['error']['myfirstname'] = '\''.$lng['customer']['firstname'].'\'';
$lng['error']['emailadd'] = '\''.$lng['customer']['email'].'\'';
$lng['error']['mydomain'] = '\'domaine\'';
$lng['error']['mydocumentroot'] = '\'Documentroot\'';
$lng['error']['loginnameexists'] = 'L\'identifiant %s existe d&eacute;j&agrave;.';
$lng['error']['emailiswrong'] = 'L\'adresse %s contient des signes invalides ou n\'est pas complet.';
$lng['error']['loginnameiswrong'] = 'L\'identifiant %s contient des signes invalides.';
$lng['error']['userpathcombinationdupe'] = 'Cette combination d\'identifiant et sentier existe d&eacute;j&agrave;.';
$lng['error']['patherror'] = 'Erreur g&eacute;n&eacute;ral! Le sentier ne doit pas &ecirc;tre vide.';
$lng['error']['errordocpathdupe'] = 'Il y a d&eacute;j&agrave; une option concernant le sentier %s.';
$lng['error']['adduserfirst'] = 'Vous devez ajouter un compte avant.';
$lng['error']['domainalreadyexists'] = 'Vous avez d&eacute;j&agrave; appliqu&eacute; le domaine %s.';
$lng['error']['nolanguageselect'] = 'Aucun langage choisi.';
$lng['error']['nosubjectcreate'] = 'Il faut donner un sujet.';
$lng['error']['nomailbodycreate'] = 'Il faut &eactute;crire un texte.';
$lng['error']['templatenotfound'] = 'Aucun template trouv&eacute;.';
$lng['error']['alltemplatesdefined'] = 'Vous avez d&eacute;j&agrave; appliqu&eacute des templates pour toutes les langues.';
$lng['error']['wwwnotallowed'] = 'Un sous-domaine ne doit pas s\'appeler www.';
$lng['error']['subdomainiswrong'] = 'Le sous-domaine %s contient des signes invalides.';
$lng['error']['domaincantbeempty'] = 'Le nom de domaine ne doit pas &ecirc;tre vide.';
$lng['error']['domainexistalready'] = 'Le domaine %s existe d&eacute;j&agrave;.';
$lng['error']['domainisaliasorothercustomer'] = 'Le domaine pseudonyme choisi est un domaine pseudonyme soi-m&ecirc;me ou fait partie d\'un autre client.';
$lng['error']['emailexistalready'] = 'L\'adresse %s existe d&eacute;j&agrave;.';
$lng['error']['maindomainnonexist'] = 'Le domaine %s n\'existe pas.';
$lng['error']['destinationnonexist'] = 'S\'il-vous-plait &eacutecrivez votre adresse de revoi au panneau \'&agrave;\'.';
$lng['error']['destinationalreadyexistasmail'] = 'Le renvoi vers l\'adresse %s existe d&eacute;j&agrave; comme adresse active.';
$lng['error']['destinationalreadyexist'] = 'Il y a d&eacute;j&agrave; une re-exp&eacute;dition vers l\'adresse %s.';
$lng['error']['destinationiswrong'] = 'L\'adresse %s contient des signes invalides ou n\'est pas complete.';
$lng['error']['domainname'] = $lng['domains']['domainname'];

/**
 * Questions
 */

$lng['question']['question'] = 'Question de s&eacute;curit&eacute;';
$lng['question']['admin_customer_reallydelete'] = 'Voulez-vous vraiment effacer le compte %s?<br />ATTENTION! Toutes les donn&eacute;es vont &ecirc;tre effac&eacute;es! Apr&egrave;s ceci fait il faut effacer les dossiers du system des fichiers manuellement.';
$lng['question']['admin_domain_reallydelete'] = 'Voulez-vous vraiment effacer le domaine %s?';
$lng['question']['admin_domain_reallydisablesecuritysetting'] = 'Voulez-vous vraiment d&eacute;sactiver ces modes importants (OpenBasedir et/o&ugrave; SafeMode) ?';
$lng['question']['admin_admin_reallydelete'] = 'Voulez-vous vraiment effacer l\'administrateur %s?<br />Tout ses comptes vont &ecirc;tre affect&eacute; au administrateur principal.';
$lng['question']['admin_template_reallydelete'] = 'Voulez-vous vraiment supprimer le template \'%s\'?';
$lng['question']['domains_reallydelete'] = 'Voulez-vous vraiment effacer le domaine %s?';
$lng['question']['email_reallydelete'] = 'Voulez-vous vraiment effacer l\'adresse e-mail %s?';
$lng['question']['email_reallydelete_account'] = 'Voulez-vous vraiment effacer l\'acc&egrave;s d\'e-mail %s?';
$lng['question']['email_reallydelete_forwarder'] = 'Voulez-vous vraiment effacer le renvoi vers %s?';
$lng['question']['extras_reallydelete'] = 'Voulez-vous vraiment effacer la protection du dossier %s?';
$lng['question']['extras_reallydelete_pathoptions'] = 'Voulez-vous vraiment effacer les options du chemin %s?';
$lng['question']['ftp_reallydelete'] = 'Voulez-vous vraiment effacer l\'acc&egrave;s eMail %s?';
$lng['question']['mysql_reallydelete'] = 'Voulez-vous vraiment effacer la banque de donn&eacute;s %s?<br />ATTENTION: Toutes les donn&eacute;es vont &ecirc;tre effac&eacute;es!';
$lng['question']['admin_configs_reallyrebuild'] = 'Voulez-vous vraiment laisser refaire les fichiers de configuration de Apache et Bind?';

/**
 * Mails
 */

$lng['mails']['pop_success']['mailbody'] = 'Bonjour,\n\nvotre acc&egrave;s POP3 {EMAIL}\na &eacute;t&eacute; install&eacute; avec succ&egrave;s.\n\nC\'est un e-mail g&eacute;ner&eacute; automatiquement, s\'il vous plait ne repondez pas a ce message.\n\nVotre Webmaster';
$lng['mails']['pop_success']['subject'] = 'Acc&egrave;s POP3 install&eacute;';
$lng['mails']['createcustomer']['mailbody'] = 'Bonjour {FIRSTNAME} {NAME},\n\nici vos informations d\'acc&egrave;s:\n\nIdentifiant: {USERNAME}\nMot de passe: {PASSWORD}\n\nNous vous remercions,\nVotre Webmaster';
$lng['mails']['createcustomer']['subject'] = 'Informations de votre acc&egrave;s';

/**
 * Admin
 */

$lng['admin']['overview'] = 'Sommaire';
$lng['admin']['ressourcedetails'] = 'Ressources utilis&eacute;s';
$lng['admin']['systemdetails'] = 'Details du system';
$lng['admin']['syscpdetails'] = 'Details de SysCP';
$lng['admin']['installedversion'] = 'Version install&eacute;e';
$lng['admin']['latestversion'] = 'La plus r&eacute;cente version';
$lng['admin']['lookfornewversion']['clickhere'] = 'V&eacute; par internet';
$lng['admin']['lookfornewversion']['error'] = 'Erreur en triant';
$lng['admin']['resources'] = 'R&eacute;ssources';
$lng['admin']['customer'] = 'Compte';
$lng['admin']['customers'] = 'Comptes';
$lng['admin']['customer_add'] = 'Ajouter un compte';
$lng['admin']['customer_edit'] = 'Modifier un compte';
$lng['admin']['domains'] = 'Domaines';
$lng['admin']['domain_add'] = 'Ajouter un Domaine';
$lng['admin']['domain_edit'] = 'Modifier le domaine';
$lng['admin']['subdomainforemail'] = 'Sous-domaines comme domaine e-mail';
$lng['admin']['admin'] = 'Administrateur';
$lng['admin']['admins'] = 'Administrateurs';
$lng['admin']['admin_add'] = 'Ajouter un administrateur';
$lng['admin']['admin_edit'] = 'Modifier un administrateur';
$lng['admin']['customers_see_all'] = 'Peut voir tous les comptes?';
$lng['admin']['domains_see_all'] = 'Peut voir tous les Domaines?';
$lng['admin']['change_serversettings'] = 'Peut modifier la configuration du server?';
$lng['admin']['server'] = 'Server';
$lng['admin']['serversettings'] = 'R&eacute;glage';
$lng['admin']['rebuildconf'] = 'Refaire la configuration';
$lng['admin']['stdsubdomain'] = 'Sous-domaine-type';
$lng['admin']['stdsubdomain_add'] = 'Ajouter un sous-domaine-type';
$lng['admin']['deactivated'] = 'Bloqu&eacute;';
$lng['admin']['deactivated_user'] = 'Bloquer utilisateur';
$lng['admin']['sendpassword'] = 'Envoyer mot de passe';
$lng['admin']['ownvhostsettings'] = 'Configuration sp&eacute;ciale du vHost';
$lng['admin']['configfiles']['serverconfiguration'] = 'Configuration';
$lng['admin']['configfiles']['files'] = '<b>Fichiers de configuration:</b> S\'il vous-plait modifiez les fichiers correspondants<br />ou cr&eacute;ez les avec les contenu ci-dessous.<br /><b>IMPORTANT:</b> Le mot de passe MySQL n\'est pas donn&eacute;s dans les dates ci-dessus<br />&agrave; cause des raisons de s&eacute;curit&eacute;. S\'il vous-plait substituez les &quot;MYSQL_PASSWORD&quot;<br />manuellement avec le mot de passe. En cas de l\'avoir oubli&eacute;, vous le trouvez dans<br />le fichier &quot;lib/userdata.inc.php&quot;.';
$lng['admin']['configfiles']['commands'] = '<b>Commandes:</b> S\'il vous-plait ex&eacute;cuter les commandes ci-dessous sur le shell.';
$lng['admin']['configfiles']['restart'] = '<b>R&eacute;demarrer:</b> S\'il vous-plait ex&eacute;cuter les commandes ci-dessous pour<br />r&eacute;initialiser les fichiers de configuration.';
$lng['admin']['templates']['templates'] = 'Templates';
$lng['admin']['templates']['template_add'] = 'Ajouter un template';
$lng['admin']['templates']['template_edit'] = 'Modifier un template';
$lng['admin']['templates']['action'] = 'Action';
$lng['admin']['templates']['email'] = 'E-Mail';
$lng['admin']['templates']['subject'] = 'R&eacute;f&eacute;rence';
$lng['admin']['templates']['mailbody'] = 'Texte du mail';
$lng['admin']['templates']['createcustomer'] = 'Mail de bienvenu pour des nouveaux clients';
$lng['admin']['templates']['pop_success'] = 'Mail de bienvenu pour des nouveaux acc&egrave;s e-mail';
$lng['admin']['templates']['template_replace_vars'] = 'Les variables qui vont &ecirc;tre remplac&eacute;es dans le template:';
$lng['admin']['templates']['FIRSTNAME'] = 'Va &ecirc;tre remplac&eacute; par le pr&eacute;nom.';
$lng['admin']['templates']['NAME'] = 'Va &ecirc;tre remplac&eacute; par le nom.';
$lng['admin']['templates']['USERNAME'] = 'Va &ecirc;tre remplac&eacute; par le login.';
$lng['admin']['templates']['PASSWORD'] = 'Va &ecirc;tre remplac&eacute; par le mot de passe du client.';
$lng['admin']['templates']['EMAIL'] = 'Va &ecirc;tre remplac&eacute; par l\'acc&egrave;s e-mail.';

/**
 * Serversettings
 */

$lng['serversettings']['session_timeout']['title'] = 'Session Timeout';
$lng['serversettings']['session_timeout']['description'] = 'Combien de secondes d\'inactivit&eacute; pour que votre session se ferme?';
$lng['serversettings']['accountprefix']['title'] = 'Pr&eacute;fix des comptes';
$lng['serversettings']['accountprefix']['description'] = 'Quel pr&eacute;fix doivent-ils avoir les comptes?';
$lng['serversettings']['mysqlprefix']['title'] = 'Pr&eacute;fix SQL';
$lng['serversettings']['mysqlprefix']['description'] = 'Quel pr&eacute;fix doivent-elles avoir les banques de donn&eacute;es?';
$lng['serversettings']['ftpprefix']['title'] = 'Pr&eacute;fix FTP';
$lng['serversettings']['ftpprefix']['description'] = 'Quel pr&eacute;fix doivent-ils avoir les acc&egrave;s FTP?';
$lng['serversettings']['documentroot_prefix']['title'] = 'Documentdirectory';
$lng['serversettings']['documentroot_prefix']['description'] = 'O&ugrave; doivent &ecirc;tre tous les comptes';
$lng['serversettings']['logfiles_directory']['title'] = 'Logfilesdirectory';
$lng['serversettings']['logfiles_directory']['description'] = 'O&ugrave; doivent &ecirc;tre les archives d\'acc&egrave;s?';
$lng['serversettings']['ipaddress']['title'] = 'Adresse IP';
$lng['serversettings']['ipaddress']['description'] = 'Quelle est l\'adresse IP du server?';
$lng['serversettings']['hostname']['title'] = 'Hostname';
$lng['serversettings']['hostname']['description'] = 'Quel est le hostname du server?';
$lng['serversettings']['apacheconf_directory']['title'] = 'Apache-Config-Directory';
$lng['serversettings']['apacheconf_directory']['description'] = 'O&ugrave; est sauvegard&eacute;e la configuration de l\'Apache?';
$lng['serversettings']['apachereload_command']['title'] = 'Apache-Reload-Command';
$lng['serversettings']['apachereload_command']['description'] = 'Comment est la commande pour red&eacute;marrer l\Apache?';
$lng['serversettings']['bindconf_directory']['title'] = 'Bind-Config-Directory';
$lng['serversettings']['bindconf_directory']['description'] = 'O&ugrave; est sauvegard&eacute;e la configuration du BIND?';
$lng['serversettings']['bindreload_command']['title'] = 'Bind-Reload-Command';
$lng['serversettings']['bindreload_command']['description'] = 'Comment est la commande pour red&eacute;marrer le BIND?';
$lng['serversettings']['binddefaultzone']['title'] = 'Bind-Default-Zone';
$lng['serversettings']['binddefaultzone']['description'] = 'Comment s\'appelle la zone standard des tous les domaines?';
$lng['serversettings']['vmail_uid']['title'] = 'Mails-Uid';
$lng['serversettings']['vmail_uid']['description'] = 'Quel UID doivent avoir les e-mails?';
$lng['serversettings']['vmail_gid']['title'] = 'Mails-Gid';
$lng['serversettings']['vmail_gid']['description'] = 'Quel GID doivent avoir les e-mails?';
$lng['serversettings']['vmail_homedir']['title'] = 'Mails-Homedir';
$lng['serversettings']['vmail_homedir']['description'] = 'O&ugrave; doivent &ecirc;tre les e-mails?';
$lng['serversettings']['adminmail']['title'] = 'Adresse de l\'exp&eacute;diteur';
$lng['serversettings']['adminmail']['description'] = 'Quelle est l\'adresse standard des e-mails qui sont envoy&eacute;s de SysCP?';
$lng['serversettings']['phpmyadmin_url']['title'] = 'Adresse URL phpMyAdmin';
$lng['serversettings']['phpmyadmin_url']['description'] = '&Agrave; quelle adresse se trouve le phpMyAdmin?';
$lng['serversettings']['webmail_url']['title'] = 'Adresse URL WebMail';
$lng['serversettings']['webmail_url']['description'] = '&Agrave; quelle adresse se trouve le WebMail?';
$lng['serversettings']['webftp_url']['title'] = 'Adresse URL WebFTP';
$lng['serversettings']['webftp_url']['description'] = '&Agrave; quelle adresse se trouve le WebFTP?';
$lng['serversettings']['language']['description'] = 'Quelle langue est la langue pr&eacute;d&eacute;finie?';
$lng['serversettings']['maxloginattempts']['title'] = 'Nombre d\'essais maximum';
$lng['serversettings']['maxloginattempts']['description'] = 'Nombre de tentatives maximales avant la d&eacute;sactivation de l\'acc&egrave;s.';
$lng['serversettings']['deactivatetime']['title'] = 'Dur&eacute;e de la d&eacute;activation';
$lng['serversettings']['deactivatetime']['description'] = 'Dur&eacute;e (en secondes) pendant laquelle l\acc&egrave;s reste d&eacute;activ&eacute;.';
$lng['serversettings']['pathedit']['title'] = 'Mode d\'indication du chemin';
$lng['serversettings']['pathedit']['description'] = 'Choisir un chemin par menu Dropdown ou pouvoir l\'entrer manuellement.';

/**
 * ADDED BETWEEN 1.2.12 and 1.2.13
 */

$lng['admin']['cronlastrun'] = 'Derni&egrave;re Tache Cron';
$lng['serversettings']['apacheconf_filename']['title'] = 'Nom fichier de configuration Apache';
$lng['serversettings']['apacheconf_filename']['description'] = 'Quel nom utiliserez-vous pour le fichier de configuration Apache?';
$lng['serversettings']['paging']['title'] = 'R&eacute;sultats par page';
$lng['serversettings']['paging']['description'] = 'Combien de r&eacute;sultats par page ? (0 = D&eacute;sactive la pagination)';
$lng['error']['ipstillhasdomains'] = 'La combinaison IP/port est encore utilis&eacute;e, svp r&eacute;assignez le ou les domaines concern&eacute;s &agrave; une autre combinaison avant de supprimer celle-ci.';
$lng['error']['cantdeletedefaultip'] = 'Vous ne pouvez pas supprimer cette combinaison IP/Port, svp attribuez une autre combinaison par d&eacute;faut &agrave; ce revendeur avant de supprimer celle-ci.';
$lng['error']['cantdeletesystemip'] = 'Vous ne pouvez pas supprimer, cr&eacute;er ou modifier  l\'IP syst&egrave;me.';
$lng['error']['myipaddress'] = '\'IP\'';
$lng['error']['myport'] = '\'Port\'';
$lng['error']['myipdefault'] = 'Choissez une combinaison IP/port par d&eacute;faut.';
$lng['error']['myipnotdouble'] = 'Cette combinaison existe d&eacute;j&agrave;.';
$lng['question']['admin_ip_reallydelete'] = 'Voulez-vous r&eacute;ellement supprimer l\'adresse IP %s ?';
$lng['admin']['ipsandports']['ipsandports'] = 'Ips et ports';
$lng['admin']['ipsandports']['add'] = 'Ajouter IP/port';
$lng['admin']['ipsandports']['edit'] = 'Modifier IP/port';
$lng['admin']['ipsandports']['ipandport'] = 'IP/Port';
$lng['admin']['ipsandports']['ip'] = 'IP';
$lng['admin']['ipsandports']['port'] = 'Port';
$lng['admin']['ipsandports']['default'] = 'IP/port par d&eacute;faut pour les revendeurs';

?>
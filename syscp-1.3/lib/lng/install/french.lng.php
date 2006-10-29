<?php

/**
 * This file is part of the SysCP project.
 * Copyright (c) 2003-2006 the SysCP Project.
 *
 * For the full copyright and license information, please view the COPYING
 * file that was distributed with this source code. You can also view the
 * COPYING file online at http://files.syscp.org/misc/COPYING.txt
 *
 * @author     Tim Zielosko <mail@zielosko.net>
 * @copyright  (c) the authors
 * @package    Org.Syscp.Installer
 * @subpackage Language
 * @license    GPLv2 http://files.syscp.org/misc/COPYING.txt
 * @version    $Id$
 */

/**
 * Begin
 */

$lng['install']['language'] = 'Langue';
$lng['install']['welcome'] = 'Bienvenue à l´installation de SysCP';
$lng['install']['welcometext'] = 'Merci beacoup d´avoir choisi SysCP. Pour installer SysCP remplissez les cases ci-dessous avec les dates demandées.<br /><b>Attention:</b> Une banque de données déjà existante qui a le même nom que vous choisissez ci-dessous va être effacée!';
$lng['install']['database'] = 'Banque de données';
$lng['install']['mysql_hostname'] = 'Hostname MySQL';
$lng['install']['mysql_database'] = 'Banque de données MySQL';
$lng['install']['mysql_unpriv_user'] = 'Utilisateur pour l´accès inprivilégié à MySQL';
$lng['install']['mysql_unpriv_pass'] = 'Mot de passe pour l´accès inprivilégié à MySQL';
$lng['install']['mysql_root_user'] = 'Utilisateur pour l´accès root à MySQL';
$lng['install']['mysql_root_pass'] = 'Mot de passe pour l´accès root à MySQL';
$lng['install']['admin_account'] = 'Accès administrative';
$lng['install']['admin_user'] = 'Login de l´administrateur';
$lng['install']['admin_pass'] = 'Mot de passe de l´administrateur';
$lng['install']['admin_pass_confirm'] = 'Mot de passe de l´administrateur (Confirmation)';
$lng['install']['serversettings'] = 'Configuration du server';
$lng['install']['servername'] = 'Nom du server (FQDN)';
$lng['install']['serverip'] = 'Adresse IP du server';
$lng['install']['next'] = 'Continuer';

/**
 * Progress
 */

$lng['install']['testing_mysql'] = 'Verifiant le login root de MySQL...';
$lng['install']['erasing_old_db'] = 'Effacant la vielle banque de données...';
$lng['install']['create_mysqluser_and_db'] = 'Créant banque de données et utilisateur...';
$lng['install']['testing_new_db'] = 'Verifiant banque de données et utilisateur...';
$lng['install']['importing_data'] = 'Important les données dans la banque de données...';
$lng['install']['changing_data'] = 'Conformant les données importés...';
$lng['install']['adding_admin_user'] = 'Appliquant l´administrateur...';
$lng['install']['creating_configfile'] = 'Créant le fichier de configuration...';
$lng['install']['creating_configfile_succ'] = 'OK, userdata.inc.php était sauvegardé à lib/.';
$lng['install']['creating_configfile_temp'] = 'Fichier était sauvegardé à /tmp/userdata.inc.php, s´il-vous plait le déplacer à lib/.';
$lng['install']['creating_configfile_failed'] = 'Erreur en créant lib/userdata.inc.php, s´il-vous plait créez le avec le content ci-dessous:';
$lng['install']['syscp_succ_installed'] = 'SysCP était installé avec succès.';
$lng['install']['click_here_to_login'] = 'Continuer à l´écran login.';

?>
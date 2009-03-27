<?php

/**
 * This file is part of the SysCP project.
 * Copyright (c) 2003-2009 the SysCP Team (see authors).
 *
 * For the full copyright and license information, please view the COPYING
 * file that was distributed with this source code. You can also view the
 * COPYING file online at http://files.syscp.org/misc/COPYING.txt
 *
 * @copyright  (c) the authors
 * @author     Florian Lippert <flo@syscp.org>
 * @license    GPLv2 http://files.syscp.org/misc/COPYING.txt
 * @package    Panel
 * @version    $Id$
 */

// prevent syscp pages from being cached

header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Content-type: text/html; charset=ISO-8859-1");

// ensure that default timezone is set
if(function_exists("date_default_timezone_set") && function_exists("date_default_timezone_get"))
{
	@date_default_timezone_set(@date_default_timezone_get());
}

/**
 * Register Globals Security Fix
 * - unsetting every variable registered in $_REQUEST and as variable itself
 */

foreach($_REQUEST as $key => $value)
{
	if(isset($$key))
	{
		unset($$key);
	}
}

unset($_);
unset($value);
unset($key);
$filename = basename($_SERVER['PHP_SELF']);

if(!file_exists('./lib/userdata.inc.php'))
{
	die('You have to <a href="./install/install.php">configure</a> SysCP first!');
}

if(!is_readable('./lib/userdata.inc.php'))
{
	die('You have to make the file "./lib/userdata.inc.php" readable for the http-process!');
}

/**
 * Includes the Usersettings eg. MySQL-Username/Passwort etc.
 */

require ('./lib/userdata.inc.php');

if(!isset($sql)
   || !is_array($sql))
{
	$config_hint = file_get_contents('./templates/misc/configurehint.tpl');
	die($config_hint);
}

/**
 * Includes the MySQL-Tabledefinitions etc.
 */

require ('./lib/tables.inc.php');

/**
 * Includes the MySQL-Connection-Class
 */

require ('./lib/class_mysqldb.php');
$db = new db($sql['host'], $sql['user'], $sql['password'], $sql['db']);
unset($sql['password']);
unset($db->password);

// we will try to unset most of the $sql information if they are not needed
// by the calling script

if($filename == 'admin_configfiles.php')
{
	// Configfiles needs host, user, db

	unset($sql['root_user']);
	unset($sql['root_password']);
}
elseif($filename == 'customer_mysql.php'
       || $filename == 'admin_customers.php')
{
	// customer mysql needs root pw, root user, host for database creation
	// admin customers needs it for database deletion

	unset($sql['user']);
	unset($sql['db']);
}
elseif($filename == 'admin_settings.php')
{
	// admin settings needs the  host, user, db, root user, root pw
}
else
{
	// Other scripts doesn't need anything at all

	unset($sql['host']);
	unset($sql['user']);
	unset($sql['db']);
	unset($sql['root_user']);
	unset($sql['root_password']);
}

/**
 * Include our class_idna_convert_wrapper.php, which offers methods for de-
 * and encoding IDN domains.
 */

require ('./lib/class_idna_convert_wrapper.php');

/**
 * Create a new idna converter
 */

$idna_convert = new idna_convert_wrapper();

/**
 * Includes the Functions
 */

require ('./lib/functions.php');

/**
 * Includes the Paging class
 */

require ('./lib/class_paging.php');

/**
 * Includes the Ticket class
 */

require ('./lib/class_ticket.php');

/**
 * Includes Logger-Classes
 */

require ('./lib/abstract/abstract_class_logger.php');
require ('./lib/class_syslogger.php');
require ('./lib/class_filelogger.php');
require ('./lib/class_mysqllogger.php');

/**
 * Includes the SyscpLogger class
 */

require ('./lib/class_syscplogger.php');

/**
 * Includes the mailing facility
 */

require ('./lib/class.phpmailer.php');

/**
 * Reverse magic_quotes_gpc=on to have clean GPC data again
 */

if(get_magic_quotes_gpc())
{
	$in = array(&$_GET, &$_POST, &$_COOKIE
	);

	while(list($k, $v) = each($in))
	{
		foreach($v as $key => $val)
		{
			if(!is_array($val))
			{
				$in[$k][$key] = stripslashes($val);
				continue;
			}

			$in[] = & $in[$k][$key];
		}
	}

	unset($in);
}

/**
 * Selects settings from MySQL-Table
 */

$settings = Array();
$result = $db->query('SELECT `settinggroup`, `varname`, `value` FROM `' . TABLE_PANEL_SETTINGS . '`');

while($row = $db->fetch_array($result))
{
	if(($row['settinggroup'] == 'system' && $row['varname'] == 'hostname')
	   || ($row['settinggroup'] == 'panel' && $row['varname'] == 'adminmail'))
	{
		$settings[$row['settinggroup']][$row['varname']] = $idna_convert->decode($row['value']);
	}
	else
	{
		$settings[$row['settinggroup']][$row['varname']] = $row['value'];
	}
}

unset($row);
unset($result);

if(!isset($settings['panel']['version'])
   || $settings['panel']['version'] != $version)
{
	redirectTo('install/updatesql.php');
	exit;
}

/**
 * SESSION MANAGEMENT
 */

$remote_addr = $_SERVER['REMOTE_ADDR'];
$http_user_agent = $_SERVER['HTTP_USER_AGENT'];
unset($userinfo);
unset($userid);
unset($customerid);
unset($adminid);
unset($s);

if(isset($_POST['s']))
{
	$s = $_POST['s'];
	$nosession = 0;
}
elseif(isset($_GET['s']))
{
	$s = $_GET['s'];
	$nosession = 0;
}
else
{
	$s = '';
	$nosession = 1;
}

$timediff = time() - $settings['session']['sessiontimeout'];
$db->query('DELETE FROM `' . TABLE_PANEL_SESSIONS . '` WHERE `lastactivity` < "' . (int)$timediff . '"');
$userinfo = Array();

if(isset($s)
   && $s != ""
   && $nosession != 1)
{
	$query = 'SELECT `s`.*, `u`.* FROM `' . TABLE_PANEL_SESSIONS . '` `s` LEFT JOIN `';

	if(AREA == 'admin')
	{
		$query.= TABLE_PANEL_ADMINS . '` `u` ON (`s`.`userid` = `u`.`adminid`)';
		$adminsession = '1';
	}
	else
	{
		$query.= TABLE_PANEL_CUSTOMERS . '` `u` ON (`s`.`userid` = `u`.`customerid`)';
		$adminsession = '0';
	}

	$query.= 'WHERE `s`.`hash`="' . $db->escape($s) . '" AND `s`.`ipaddress`="' . $db->escape($remote_addr) . '" AND `s`.`useragent`="' . $db->escape($http_user_agent) . '" AND `s`.`lastactivity` > "' . (int)$timediff . '" AND `s`.`adminsession` = "' . $db->escape($adminsession) . '"';
	$userinfo = $db->query_first($query);

	if((($userinfo['adminsession'] == '1' && AREA == 'admin' && isset($userinfo['adminid'])) || ($userinfo['adminsession'] == '0' && (AREA == 'customer' || AREA == 'login') && isset($userinfo['customerid'])))
	   && (!isset($userinfo['deactivated']) || $userinfo['deactivated'] != '1'))
	{
		$userinfo['newformtoken'] = strtolower(md5(uniqid(microtime(), 1)));
		$query = 'UPDATE `' . TABLE_PANEL_SESSIONS . '` SET `lastactivity`="' . time() . '", `formtoken`="' . $userinfo['newformtoken'] . '" WHERE `hash`="' . $db->escape($s) . '" AND `adminsession` = "' . $db->escape($adminsession) . '"';
		$db->query($query);
		$nosession = 0;
	}
	else
	{
		$nosession = 1;
	}
}
else
{
	$nosession = 1;
}

/**
 * Language Managament
 */

$langs = array();
$languages = array();

// query the whole table

$query = 'SELECT * FROM `' . TABLE_PANEL_LANGUAGE . '` ';
$result = $db->query($query);

// presort languages

while($row = $db->fetch_array($result))
{
	$langs[$row['language']][] = $row;
}

// buildup $languages for the login screen

foreach($langs as $key => $value)
{
	$languages[$key] = $key;
}

if(!isset($userinfo['def_language'])
   || !isset($languages[$userinfo['def_language']]))
{
	if(isset($_GET['language'])
	   && isset($languages[$_GET['language']]))
	{
		$language = $_GET['language'];
	}
	else
	{
		$language = $settings['panel']['standardlanguage'];
	}
}
else
{
	$language = $userinfo['def_language'];
}

// include every english language file we can get

foreach($langs['English'] as $key => $value)
{
	include_once makeSecurePath($value['file']);
}

// now include the selected language if its not english

if($language != 'English')
{
	foreach($langs[$language] as $key => $value)
	{
		include_once makeSecurePath($value['file']);
	}
}

/**
 * Redirects to index.php (login page) if no session exists
 */

if($nosession == 1
   && AREA != 'login')
{
	unset($userinfo);
	redirectTo('index.php');
	exit;
}

/**
 * Initialize Template Engine
 */

$templatecache = array();

/**
 * Logic moved out of lng-file
 */

if(isset($userinfo['loginname'])
   && $userinfo['loginname'] != '')
{
	$lng['menue']['main']['username'].= $userinfo['loginname'];

	/**
	 * Initialize logging
	 */

	$log = SysCPLogger::getInstanceOf($userinfo, $db, $settings);
}

if($settings['billing']['activate_billing'] == '1')
{
	/**
	 * Include Billing classes
	 */

	require ('./lib/billing_class_invoice.php');
	require ('./lib/billing_class_taxcontroller.php');
	require ('./lib/billing_class_servicecategory.php');
	require ('./lib/billing_class_pdf.php');
	require ('./lib/billing_class_pdfinvoice.php');
	require ('./lib/billing_class_pdfreminder.php');
}
else
{
	/**
	 * Deactivate Billing for all users
	 */

	$userinfo['edit_billingdata'] = '0';
}

/**
 * Fills variables for navigation, header and footer
 */

$navigation = getNavigation($s, $userinfo);
eval("\$header = \"" . getTemplate('header', '1') . "\";");
eval("\$footer = \"" . getTemplate('footer', '1') . "\";");

if(isset($_POST['action']))
{
	$action = $_POST['action'];
}
elseif(isset($_GET['action']))
{
	$action = $_GET['action'];
}
else
{
	$action = '';
}

if(isset($_POST['page']))
{
	$page = $_POST['page'];
}
elseif(isset($_GET['page']))
{
	$page = $_GET['page'];
}
else
{
	$page = '';
}

if($page == '')
{
	$page = 'overview';
}

/**
 * Initialize the mailingsystem
 */

$mail = new PHPMailer();
$mail->From = $settings['panel']['adminmail'];

?>

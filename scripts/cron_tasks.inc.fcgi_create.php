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
 * @author     Florian Aders <eleras@syscp.org>
 * @author     Luca Longinotti <chtekk@syscp.org>
 * @author     Michael Kaufmann <mk@syscp-help.org>
 * @author     Sven Skrabal <info@nexpa.de>
 * @license    GPLv2 http://files.syscp.org/misc/COPYING.txt
 * @package    System
 * @version    $Id$
 */

/*
 * This script creates the php.ini's used by mod_fcgid/FastCGI
 */

if(@php_sapi_name() != 'cli'
   && @php_sapi_name() != 'cgi'
   && @php_sapi_name() != 'cgi-fcgi')
{
	die('This script only works in the shell.');
}

function createFcgiConfig($domain, $settings)
{
	global $db;

	//create basic variables for config
	$baseconfigdir = $settings['system']['mod_fcgid_configdir'];
	$basetmpdir = $settings['system']['mod_fcgid_tmpdir'];
	$peardir = $settings['system']['mod_fcgid_peardir'];
	$configdir = $baseconfigdir . '/' . $domain['loginname'] . '/' . $domain['domain'] . '/';
	$tmpdir = $basetmpdir . '/' . $domain['loginname'] . '/';
	$openbasedir = '';

	if($domain['openbasedir'] == '1')
	{
		if($domain['openbasedir_path'] == '0')
		{
			$openbasedir = $domain['documentroot'] . ':' . $tmpdir . ':' . $peardir . ':' . $settings['system']['phpappendopenbasedir'];
		}
		else
		{
			$openbasedir = $domain['customerroot'] . ':' . $tmpdir . ':' . $peardir . ':' . $settings['system']['phpappendopenbasedir'];
		}
	}
	else
	{
		$openbasedir = 'none';
	}

	$safemode = ($domain['safemode'] == '0' ? 'Off' : 'On');

	// create config dir if necessary
	if(!is_dir($configdir))
	{
		safe_exec('mkdir -p ' . escapeshellarg($configdir));
		safe_exec('chown ' . $domain['guid'] . ':' . $domain['guid'] . ' ' . escapeshellarg($configdir));
	}

	// create tmp dir if necessary
	if(!is_dir($tmpdir))
	{
		safe_exec('mkdir -p ' . escapeshellarg($tmpdir));
		safe_exec('chown -R ' . $domain['guid'] . ':' . $domain['guid'] . ' ' . escapeshellarg($tmpdir));
		safe_exec('chmod 0750 ' . escapeshellarg($tmpdir));
	}

	// create starter
	$starter_file  = "#!/bin/sh\n\n";
	$starter_file .= "#\n";
	$starter_file .= "# starter created/changed on " . date("Y.m.d H:i:s") . " for domain '" . $domain['domain'] . "' with id #" . $domain['id'] . "\n";
	$starter_file .= "# Do not change anything in this file, it will be overwritten by the SysCP Cronjob!\n";
	$starter_file .= "#\n\n";
	$starter_file .= "PHPRC=" . escapeshellarg($configdir) . "\n";
	$starter_file .= "export PHPRC\n";
	$starter_file .= "PHP_FCGI_CHILDREN=" . (int)$settings['system']['mod_fcgid_starter'] . "\n";
	$starter_file .= "export PHP_FCGI_CHILDREN\n";
	$starter_file .= "exec /usr/bin/php-cgi -c " . escapeshellarg($configdir) . "\n";

	//remove +i attibute, so starter can be overwritten
	if(file_exists($configdir . 'php-fcgi-starter'))
	{
		safe_exec('chattr -i ' . escapeshellarg($configdir . 'php-fcgi-starter'));
	}

	$starter_file_handler = fopen($configdir . 'php-fcgi-starter', 'w');
	fwrite($starter_file_handler, $starter_file);
	fclose($starter_file_handler);

	safe_exec('chmod 750 ' . escapeshellarg($configdir . 'php-fcgi-starter'));
	safe_exec('chown ' . $domain['guid'] . ':' . $domain['guid'] . ' ' . escapeshellarg($configdir . 'php-fcgi-starter'));
	safe_exec('chattr +i ' . escapeshellarg($configdir . 'php-fcgi-starter'));

	// define the php.ini
	$phpsetting = $db->query("SELECT * FROM `" . TABLE_PANEL_PHPCONFIGS . "` WHERE `id` = " . (int)$domain['phpsettingid']);
	$row = $db->fetch_array($phpsetting);
	$description = $row['description'];
	$config_file_php_ini = $row['phpsettings'];

	$admin = $db->query("SELECT `email`, `loginname` FROM `" . TABLE_PANEL_ADMINS . "` WHERE `adminid` = " . (int)$domain['adminid']);
	$row = $db->fetch_array($admin);

	$replace_arr = array(
		'SAFE_MODE' => $safemode,
		'PEAR_DIR' => $peardir,
		'OPEN_BASEDIR' => $openbasedir,
		'OPEN_BASEDIR_GLOBAL' => $settings['system']['phpappendopenbasedir'],
		'TMP_DIR' => $tmpdir,
		'CUSTOMER_EMAIL' => $domain['email'],
		'ADMIN_EMAIL' => $row['email'],
		'DOMAIN' => $domain['domain'],
		'CUSTOMER' => $domain['loginname'],
		'ADMIN' => $row['loginname']
	);

	$config_file_php_ini = replace_variables($config_file_php_ini, $replace_arr);

	//insert a small header for the file
	$header  = ";\n";
	$header .= "; php.ini created/changed on " . date("Y.m.d H:i:s") . " for domain '" . $domain['domain'] . "' with id #" . $domain['id'] . " from php template '" . $description . "'\n";
	$header .= "; Do not change anything in this file, it will be overwritten by the SysCP Cronjob!\n";
	$header .= ";\n\n";
	$config_file_php_ini = $header . $config_file_php_ini;

	$config_file_handler = fopen($configdir . 'php.ini', 'w');
	fwrite($config_file_handler, $config_file_php_ini);
	fclose($config_file_handler);

	safe_exec('chown root:0 "' . $configdir . 'php.ini"');
	safe_exec('chmod 0644 "' . $configdir . 'php.ini"');
}
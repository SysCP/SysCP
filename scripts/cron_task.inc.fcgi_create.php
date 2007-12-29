<?php

/*
 * This script creates the php.ini's used by mod_suPHP+php-cgi
 */

if(@php_sapi_name() != 'cli'
   && @php_sapi_name() != 'cgi'
   && @php_sapi_name() != 'cgi-fcgi')
{
	die('This script only works in the shell.');
}

$baseconfigdir = '/var/www/php-fcgi-scripts';
$basetmpdir = '/var/kunden/tmp';
$peardir = '/usr/share/php/:/usr/share/php5/';
$configdir = $baseconfigdir . '/' . $domain['loginname'] . '/' . $domain['domain'] . '/';
$tmpdir = $basetmpdir . '/' . $domain['loginname'] . '/';

if($domain['openbasedir'] == '1')
{
	$openbasedircomment = '';
	$openbasedir = $domain['customerroot'] . ':' . $tmpdir . ':' . $peardir . ':' . $settings['system']['phpappendopenbasedir'];
}

if($domain['openbasedir'] == '0')
{
	$openbasedircomment = ';';
	$openbasedir = '';
}

$safemode = ($domain['safemode'] == '0' ? 'Off' : 'On');

// For future use, need to change templates for that
// $phperrordisplay = ($domain['php_errordisplay'] == '0' ? 'Off' : 'On');

$phperrordisplay = 'On';

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

if(!file_exists($configdir . 'php-fcgi-starter'))
{
	$starter_file = '#!/bin/sh' . "\n";
	$starter_file.= '#PHP_FCGI_CHILDREN=4' . "\n";
	$starter_file.= '#export PHP_FCGI_CHILDREN' . "\n";
	$starter_file.= 'exec /usr/bin/php-cgi -c ' . escapeshellarg($configdir) . "\n";
	$starter_file_handler = fopen($configdir . 'php-fcgi-starter', 'w');
	fwrite($starter_file_handler, $starter_file);
	fclose($starter_file_handler);
	safe_exec('chmod 750 ' . escapeshellarg($configdir . 'php-fcgi-starter'));
	safe_exec('chown ' . $domain['guid'] . ':' . $domain['guid'] . ' ' . escapeshellarg($configdir . 'php-fcgi-starter'));
	safe_exec('chattr +i ' . escapeshellarg($configdir . 'php-fcgi-starter'));
}

// define the php.ini

$config_file_php_ini = 'short_open_tag = On
asp_tags = Off
precision = 14
output_buffering = 4096
allow_call_time_pass_reference = Off
safe_mode = ' . $safemode . '
safe_mode_gid = Off
safe_mode_include_dir = "' . $peardir . '"
safe_mode_allowed_env_vars = PHP_
safe_mode_protected_env_vars = LD_LIBRARY_PATH
' . $openbasedircomment . 'open_basedir = "' . $openbasedir . '"
disable_functions = exec,passthru,shell_exec,system,proc_close,proc_get_status,proc_nice,proc_open,proc_terminate
disable_classes =
expose_php = Off
max_execution_time = 30
max_input_time = 60
memory_limit = 16M
post_max_size = 16M
error_reporting = E_ALL
display_errors = ' . $phperrordisplay . '
display_startup_errors = Off
log_errors = On
log_errors_max_len = 1024
ignore_repeated_errors = Off
ignore_repeated_source = Off
report_memleaks = On
track_errors = Off
html_errors = Off
variables_order = "GPCS"
register_globals = Off
register_argc_argv = Off
gpc_order = "GPC"
magic_quotes_gpc = Off
magic_quotes_runtime = Off
magic_quotes_sybase = Off
include_path = ".:' . $peardir . '"
enable_dl = Off
file_uploads = On
upload_tmp_dir = "' . $tmpdir . '"
upload_max_filesize = 32M
allow_url_fopen = Off
sendmail_path = "/usr/sbin/sendmail -t -f ' . $domain['email'] . '"
session.save_handler = files
session.save_path = "' . $tmpdir . '"
session.use_cookies = 1
session.name = PHPSESSID
session.auto_start = 0
session.cookie_lifetime = 0
session.cookie_path = /
session.cookie_domain =
session.serialize_handler = php
session.gc_probability = 1
session.gc_divisor = 1000
session.gc_maxlifetime = 1440
session.bug_compat_42 = 0
session.bug_compat_warn = 1
session.referer_check =
session.entropy_length = 16
session.entropy_file = /dev/urandom
session.cache_limiter = nocache
session.cache_expire = 180
session.use_trans_sid = 0
suhosin.simulation = Off
suhosin.mail.protect = 1
';

if(!file_exists($configdir . 'php.ini'))
{
	$config_file_handler = fopen($configdir . 'php.ini', 'w');
	fwrite($config_file_handler, $config_file_php_ini);
	fclose($config_file_handler);
	safe_exec('chown root:0 "' . $configdir . 'php.ini"');
	safe_exec('chmod 0644 "' . $configdir . 'php.ini"');
}
else
{
	$phpini = file_get_contents($configdir . 'php.ini');
	$search[0] = '/safe_mode = (On|Off|1|0|True|False)/i';
	$search[1] = '/safe_mode_include_dir = "(.*)"/i';
	$search[2] = '/;?open_basedir = "(.*)"/i';
	$search[3] = '/display_errors = (On|Off|1|0|True|False)/i';
	$search[4] = '/error_log = "(.*)"/i';
	$search[5] = '/include_path = ".:(.*)"/i';
	$search[6] = '/upload_tmp_dir = "(.*)"/i';
	$search[7] = '#sendmail_path = "/usr/sbin/sendmail -t -f (.*)"#i';
	$search[8] = '/session.save_path = "(.*)"/i';
	$replace[0] = 'safe_mode = ' . $safemode;
	$replace[1] = 'safe_mode_include_dir = "' . $peardir . '"';
	$replace[2] = $openbasedircomm . 'open_basedir = "' . $openbasedir . '"';
	$replace[3] = 'display_errors = ' . $phperrordisplay;
	$replace[4] = 'error_log = "' . $logfile . '"';
	$replace[5] = 'include_path = ".:' . $peardir . '"';
	$replace[6] = 'upload_tmp_dir = "' . $tmpdir . '"';
	$replace[7] = 'sendmail_path = "/usr/sbin/sendmail -t -f ' . $domain['email'] . '"';
	$replace[8] = 'session.save_path = "' . $tmpdir . '"';
	$phpini = preg_replace($search, $replace, $phpini);
	$config_file_handler = fopen($configdir . 'php.ini', 'w');
	fwrite($config_file_handler, $config_file_php_ini);
	fclose($config_file_handler);
	safe_exec('chown root:0 "' . $configdir . 'php.ini"');
	safe_exec('chmod 0644 "' . $configdir . 'php.ini"');
}


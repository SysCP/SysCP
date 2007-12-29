<?php

/**
 * This file is part of the SysCP project.
 * Copyright (c) 2003-2007 the SysCP Team (see authors).
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

define('AREA', 'admin');

/**
 * Include our init.php, which manages Sessions, Language etc.
 */

require ("./lib/init.php");
require ("./lib/configfiles_index.inc.php");

$distribution = '';
$distributions_select = '';
$service = '';
$services_select = '';
$daemon = '';
$daemons_select = '';

if(isset($_GET['distribution'])
   && $_GET['distribution'] != ''
   && isset($configfiles[$_GET['distribution']])
   && is_array($configfiles[$_GET['distribution']]))
{
	$distribution = $_GET['distribution'];

	if(isset($_GET['service'])
	   && $_GET['service'] != ''
	   && isset($configfiles[$distribution]['services'][$_GET['service']])
	   && is_array($configfiles[$distribution]['services'][$_GET['service']]))
	{
		$service = $_GET['service'];

		if(isset($_GET['daemon'])
		   && $_GET['daemon'] != ''
		   && isset($configfiles[$distribution]['services'][$service]['daemons'][$_GET['daemon']])
		   && is_array($configfiles[$distribution]['services'][$service]['daemons'][$_GET['daemon']]))
		{
			$daemon = $_GET['daemon'];
		}
		else
		{
			foreach($configfiles[$distribution]['services'][$service]['daemons'] as $daemon_name => $daemon_details)
			{
				$daemons_select.= makeoption($daemon_details['label'], $daemon_name);
			}
		}
	}
	else
	{
		foreach($configfiles[$distribution]['services'] as $service_name => $service_details)
		{
			$services_select.= makeoption($service_details['label'], $service_name);
		}
	}
}
else
{
	foreach($configfiles as $distribution_name => $distribution_details)
	{
		$distributions_select.= makeoption($distribution_details['label'], $distribution_name);
	}
}

if($userinfo['change_serversettings'] == '1')
{
	if($distribution != ''
	   && $service != ''
	   && $daemon != '')
	{
		if(isset($configfiles[$distribution]['services'][$service]['daemons'][$daemon]['commands'])
		   && is_array($configfiles[$distribution]['services'][$service]['daemons'][$daemon]['commands']))
		{
			$commands = implode("\n", $configfiles[$distribution]['services'][$service]['daemons'][$daemon]['commands']);
		}
		else
		{
			$commands = '';
		}

		$replace_arr = Array(
			'<SQL_UNPRIVILEGED_USER>' => $sql['user'],
			'<SQL_UNPRIVILEGED_PASSWORD>' => 'MYSQL_PASSWORD',
			'<SQL_DB>' => $sql['db'],
			'<SQL_HOST>' => $sql['host'],
			'<SERVERNAME>' => $settings['system']['hostname'],
			'<SERVERIP>' => $settings['system']['ipaddress'],
			'<VIRTUAL_MAILBOX_BASE>' => $settings['system']['vmail_homedir'],
			'<VIRTUAL_UID_MAPS>' => $settings['system']['vmail_uid'],
			'<VIRTUAL_GID_MAPS>' => $settings['system']['vmail_gid']
		);
		$files = '';

		if(isset($configfiles[$distribution]['services'][$service]['daemons'][$daemon]['files'])
		   && is_array($configfiles[$distribution]['services'][$service]['daemons'][$daemon]['files']))
		{
			while(list($filename, $realname) = each($configfiles[$distribution]['services'][$service]['daemons'][$daemon]['files']))
			{
				$file_content = file_get_contents('./templates/misc/configfiles/' . $distribution . '/' . $daemon . '/' . $filename);
				$file_content = strtr($file_content, $replace_arr);
				$file_content = htmlspecialchars($file_content);
				$numbrows = count(explode("\n", $file_content));
				eval("\$files.=\"" . getTemplate("configfiles/configfiles_file") . "\";");
			}
		}

		if(isset($configfiles[$distribution]['services'][$service]['daemons'][$daemon]['restart'])
		   && is_array($configfiles[$distribution]['services'][$service]['daemons'][$daemon]['restart']))
		{
			$restart = implode("\n", $configfiles[$distribution]['services'][$service]['daemons'][$daemon]['restart']);
		}
		else
		{
			$restart = '';
		}

		eval("echo \"" . getTemplate("configfiles/configfiles") . "\";");
	}
	elseif($page == 'overview')
	{
		$distributions = '';
		foreach($configfiles as $distribution_name => $distribution_details)
		{
			$services = '';
			foreach($distribution_details['services'] as $service_name => $service_details)
			{
				$daemons = '';
				foreach($service_details['daemons'] as $daemon_name => $daemon_details)
				{
					eval("\$daemons.=\"" . getTemplate("configfiles/choose_daemon") . "\";");
				}

				eval("\$services.=\"" . getTemplate("configfiles/choose_service") . "\";");
			}

			eval("\$distributions.=\"" . getTemplate("configfiles/choose_distribution") . "\";");
		}

		eval("echo \"" . getTemplate("configfiles/choose") . "\";");
	}
	else
	{
		eval("echo \"" . getTemplate("configfiles/wizard") . "\";");
	}
}

?>

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
 * @author Florian Lippert <flo@redenswert.de>
 * @copyright (C) 2003-2004 Florian Lippert
 * @package Panel
 * @version $Id$
 */

	define('AREA', 'admin');

	/**
	 * Include our init.php, which manages Sessions, Language etc.
	 */
	require("./lib/init.php");

	if(isset($_POST['id']))
	{
		$id=intval($_POST['id']);
	}
	elseif(isset($_GET['id']))
	{
		$id=intval($_GET['id']);
	}

	if($page=='overview')
	{
		$overview = $db->query_first("SELECT COUNT(*) AS `number_customers`,
				SUM(`diskspace_used`) AS `diskspace_used`,
				SUM(`mysqls_used`) AS `mysqls_used`,
				SUM(`emails_used`) AS `emails_used`,
				SUM(`email_forwarders_used`) AS `email_forwarders_used`,
				SUM(`ftps_used`) AS `ftps_used`,
				SUM(`subdomains_used`) AS `subdomains_used`,
				SUM(`traffic_used`) AS `traffic_used`
				FROM `".TABLE_PANEL_CUSTOMERS."`");
		$overview['traffic_used']=round($overview['traffic_used']/(1024*1024),4);
		$overview['diskspace_used']=round($overview['diskspace_used']/1024,2);
		$number_domains = $db->query_first("SELECT COUNT(*) AS `number_domains` FROM `".TABLE_PANEL_DOMAINS."` WHERE `parentdomainid`='0'");
		$overview['number_domains'] = $number_domains['number_domains'];

		$phpversion = phpversion();
		$phpmemorylimit = @ini_get("memory_limit");
		$mysqlserverversion = mysql_get_server_info();
		$mysqlclientversion = mysql_get_client_info();
		$webserverinterface = strtoupper(@php_sapi_name());
		
		if( (isset($_GET['lookfornewversion']) && $_GET['lookfornewversion'] == 'yes') || (isset($lookfornewversion) && $lookfornewversion == 'yes') )
		{
			$latestversion = @file('http://syscp.redenswert.de/version/version.php');
			if(is_array($latestversion))
			{
				$lookfornewversion_lable = $latestversion[0];
				$lookfornewversion_link = $latestversion[1];
			}
			else
			{
				$lookfornewversion_lable = $lng['admin']['lookfornewversion']['error'];
				$lookfornewversion_link = "$filename?s=$s&page=$page&lookfornewversion=yes";
			}
		}
		else
		{
			$lookfornewversion_lable = $lng['admin']['lookfornewversion']['clickhere'];
			$lookfornewversion_link = "$filename?s=$s&page=$page&lookfornewversion=yes";
		}

		eval("echo \"".getTemplate("index/index")."\";");
	}

?>

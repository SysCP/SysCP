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

	define('AREA', 'customer');

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
		eval("echo \"".getTemplate("domains/domains")."\";");
	}
	elseif($page=='domains')
	{
		if($action=='')
		{
			$result=$db->query("SELECT `id`, `customerid`, `domain`, `documentroot`, `isemaildomain` FROM `".TABLE_PANEL_DOMAINS."` WHERE `customerid`='".$userinfo['customerid']."' ORDER BY `domain` ASC");
			$domains='';
			while($row=$db->fetch_array($result))
			{
				$row['documentroot']=str_replace($userinfo['documentroot'],'',$row['documentroot']);
				eval("\$domains.=\"".getTemplate("domains/domains_domain")."\";");
			}
			if($userinfo['subdomains_used'] < $userinfo['subdomains'] || $userinfo['subdomains'] == '-1')
			{
				if($db->num_rows($result) > 15)
				{
					eval("\$domains=\"".getTemplate("domains/domains_adddomain")."\".\$domains;");
				}
				eval("\$domains.=\"".getTemplate("domains/domains_adddomain")."\";");
			}
			eval("echo \"".getTemplate("domains/domainlist")."\";");
		}

		elseif($action=='delete' && $id!=0)
		{
			$result=$db->query_first("SELECT `id`, `customerid`, `domain`, `documentroot`, `isemaildomain` FROM `".TABLE_PANEL_DOMAINS."` WHERE `customerid`='".$userinfo['customerid']."' AND `id`='$id'");
			if(isset($result['isemaildomain']) && $result['isemaildomain']!='1')
			{
				if(isset($_POST['send']) && $_POST['send']=='send')
				{
					$result=$db->query("DELETE FROM `".TABLE_PANEL_DOMAINS."` WHERE `customerid`='".$userinfo['customerid']."' AND `id`='$id'");
					$result=$db->query("UPDATE `".TABLE_PANEL_CUSTOMERS."` SET `subdomains_used`=`subdomains_used`-1 WHERE `customerid`='".$userinfo['customerid']."'");
					inserttask('1');
					header("Location: $filename?page=$page&s=$s");
				}
				else {
					ask_yesno('domains_reallydelete', $filename, "id=$id;page=$page;action=$action");
				}
			}
			else
			{
				standard_error('domains_cantdeletemaindomain');
			}
		}

		elseif($action=='add')
		{
			if($userinfo['subdomains_used'] < $userinfo['subdomains'] || $userinfo['subdomains'] == '-1')
			{
				if(isset($_POST['send']) && $_POST['send']=='send')
				{
					$subdomain=addslashes($_POST['subdomain']);
					$domain=addslashes($_POST['domain']);
					$domain_check=$db->query_first("SELECT `id`, `customerid`, `domain`, `documentroot`, `isemaildomain`, `openbasedir`, `safemode`, `speciallogfile`, `specialsettings` FROM `".TABLE_PANEL_DOMAINS."` WHERE `domain`='$domain' AND `customerid`='".$userinfo['customerid']."' AND `isemaildomain`='1' AND `iswildcarddomain`='0' ");
					$completedomain=$subdomain.'.'.$domain;
					$completedomain_check=$db->query_first("SELECT `id`, `customerid`, `domain`, `documentroot`, `isemaildomain` FROM `".TABLE_PANEL_DOMAINS."` WHERE `domain`='$completedomain' AND `customerid`='".$userinfo['customerid']."'");

					$path=addslashes($_POST['path']);
					if(substr($path, 0, 7) != 'http://')
					{
						$path=makeCorrectDir($path);
						$path=$userinfo['documentroot'].$path;
						if(!is_dir($path))
						{
							standard_error('directorymustexist');
							exit;
						}
					}

					if($path=='' || $subdomain=='' || $domain=='' || $completedomain_check['domain']==$completedomain || $domain_check['domain']!=$domain)
					{
						standard_error('notallreqfieldsorerrors');
						exit;
					}
					else
					{
						$result=$db->query("INSERT INTO `".TABLE_PANEL_DOMAINS."` (`customerid`, `domain`, `documentroot`, `parentdomainid`, `openbasedir`, `safemode`, `speciallogfile`, `specialsettings`) VALUES ('".$userinfo['customerid']."', '$completedomain', '$path', '".$domain_check['id']."', '".$domain_check['openbasedir']."', '".$domain_check['safemode']."', '".$domain_check['speciallogfile']."', '".$domain_check['specialsettings']."')");
						$result=$db->query("UPDATE `".TABLE_PANEL_CUSTOMERS."` SET `subdomains_used`=`subdomains_used`+1 WHERE `customerid`='".$userinfo['customerid']."'");
						inserttask('1');
						header("Location: $filename?page=$page&s=$s");
					}
				}
				else
				{
					$result=$db->query("SELECT `id`, `domain`, `documentroot`, `isemaildomain` FROM `".TABLE_PANEL_DOMAINS."` WHERE `customerid`='".$userinfo['customerid']."' AND `isemaildomain`='1' AND `iswildcarddomain`='0' ORDER BY `domain` ASC");
					$domains='';
					while($row=$db->fetch_array($result))
					{
						$domains.=makeoption($row['domain'],$row['domain']);
					}
					eval("echo \"".getTemplate("domains/domains_add")."\";");
				}
			}
		}

		elseif($action=='edit' && $id!=0)
		{
			$result=$db->query_first("SELECT `id`, `customerid`, `domain`, `documentroot`, `isemaildomain`, `iswildcarddomain` FROM `".TABLE_PANEL_DOMAINS."` WHERE `customerid`='".$userinfo['customerid']."' AND `id`='$id'");
			if(isset($result['customerid']) && $result['customerid']==$userinfo['customerid'])
			{
				if(isset($_POST['send']) && $_POST['send']=='send')
				{
					$path=addslashes($_POST['path']);
					if(substr($path, 0, 7) != 'http://')
					{
						$path=makeCorrectDir($path);
						$path=$userinfo['documentroot'].$path;
						if(!is_dir($path))
						{
							standard_error('directorymustexist');
							exit;
						}
					}

					if(isset($_POST['iswildcarddomain']) && $_POST['iswildcarddomain'] == '1')
					{
						$wildcarddomaincheck = $db->query("SELECT `id` FROM `".TABLE_PANEL_DOMAINS."` WHERE `parentdomainid` = '{$result['id']}'");
						if($db->num_rows($wildcarddomaincheck) != '0')
						{
							standard_error('firstdeleteallsubdomains');
							exit;
						}
						$iswildcarddomain = '1';
					}
					else
					{
						$iswildcarddomain = '0';
					}

					if($path=='')
					{
						standard_error('notallreqfieldsorerrors');
						exit;
					}
					else
					{
						if($path != $result['documentroot'] || $iswildcarddomain != $result['iswildcarddomain'])
						{
							inserttask('1');
							$result=$db->query("UPDATE `".TABLE_PANEL_DOMAINS."` SET `documentroot`='$path', `iswildcarddomain`='$iswildcarddomain' WHERE `customerid`='".$userinfo['customerid']."' AND `id`='$id'");
						}
						header("Location: $filename?page=$page&s=$s");
					}
				}
				else {
					$result['documentroot']=str_replace($userinfo['documentroot'],'',$result['documentroot']);
					$iswildcarddomain=makeyesno('iswildcarddomain', '1', '0', $result['iswildcarddomain']);
					eval("echo \"".getTemplate("domains/domains_edit")."\";");
				}
			}
		}
	}

?>

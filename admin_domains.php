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

	if($page == 'domains' || $page == 'overview')
	{
		if($action=='')
		{
			if(isset($_GET['sortby']))
			{
				$sortby=addslashes($_GET['sortby']);
			}
			else
			{
				$sortby='domain';
			}
			if(isset($_GET['sortorder']) && strtolower($_GET['sortorder'])=='desc')
			{
				$sortorder='DESC';
			}
			else
			{
				$sortorder='ASC';
			}

			$domains='';
			$result=$db->query(
				"SELECT `d`.`id`, `d`.`domain`, `d`.`customerid`, `d`.`documentroot`, `d`.`zonefile`, `d`.`openbasedir`, `d`.`safemode`, `d`.`isemaildomain`, `d`.`parentdomainid`, `c`.`loginname`, `c`.`name`, `c`.`firstname` " .
				"FROM `".TABLE_PANEL_DOMAINS."` `d` " .
				"LEFT JOIN `".TABLE_PANEL_CUSTOMERS."` `c` USING(`customerid`) " .
				"WHERE `d`.`parentdomainid`='0' ".( $userinfo['customers_see_all'] ? '' : " AND `d`.`adminid` = '{$userinfo['adminid']}' ")."" .
				"ORDER BY `$sortby` $sortorder"
			);
			$domain_array=array();
			while($row=$db->fetch_array($result))
			{
				$row['domain'] = $idna_convert->decode($row['domain']);
				$domain_array[$row['domain']] = $row;
			}
			ksort($domain_array);
			foreach($domain_array as $row)
			{
				$standardsubdomain = false;
				$sql = 'SELECT `customerid` FROM `'.TABLE_PANEL_CUSTOMERS.'` WHERE `standardsubdomain`=\''.$row['id'].'\'';
				$result = $db->query_first($sql);
				if(isset($result['customerid']))
				{
					$standardsubdomain = true;
				}
				eval("\$domains.=\"".getTemplate("domains/domains_domain")."\";");
			}
			eval("echo \"".getTemplate("domains/domains")."\";");
		}

		elseif($action=='delete' && $id!=0)
		{
			$result=$db->query_first("SELECT `d`.`id`, `d`.`domain`, `d`.`customerid`, `d`.`documentroot`, `d`.`isemaildomain`, `d`.`zonefile` FROM `".TABLE_PANEL_DOMAINS."` `d`, `".TABLE_PANEL_CUSTOMERS."` `c` WHERE `d`.`id`='$id' AND `d`.`id` <> `c`.`standardsubdomain`".( $userinfo['customers_see_all'] ? '' : " AND `d`.`adminid` = '{$userinfo['adminid']}' "));
			if($result['domain']!='')
			{
				if(isset($_POST['send']) && $_POST['send']=='send')
				{
					$db->query("DELETE FROM `".TABLE_MAIL_USERS."` WHERE `domainid` IN (SELECT `id` FROM `".TABLE_PANEL_DOMAINS."` WHERE (`id`='$id' OR `parentdomainid`='$id') AND `isemaildomain`='1')");
					$db->query("DELETE FROM `".TABLE_MAIL_VIRTUAL."` WHERE `domainid` IN (SELECT `id` FROM `".TABLE_PANEL_DOMAINS."` WHERE (`id`='$id' OR `parentdomainid`='$id') AND `isemaildomain`='1')");
					$db->query("DELETE FROM `".TABLE_PANEL_DOMAINS."` WHERE `id`='$id' OR `parentdomainid`='".$result['id']."'");
					$deleted_domains = $db->affected_rows();
					$db->query("UPDATE `".TABLE_PANEL_CUSTOMERS."` SET `subdomains_used` = `subdomains_used` - 0".($deleted_domains - 1)." WHERE `customerid` = '{$result['customerid']}'");
					$db->query("UPDATE `".TABLE_PANEL_ADMINS."` SET `domains_used` = `domains_used` - 1 WHERE `adminid` = '{$userinfo['adminid']}'");
					updateCounters () ;

					inserttask('1');
					inserttask('4');

					header("Location: $filename?page=$page&s=$s");
				}
				else
				{
					ask_yesno('admin_domain_reallydelete', $filename, "id=$id;page=$page;action=$action", $idna_convert->decode($result['domain']));
				}
			}
		}

		elseif($action=='add')
		{
			if($userinfo['domains_used'] < $userinfo['domains'] || $userinfo['domains'] == '-1')
			{
				if(isset($_POST['send']) && $_POST['send']=='send')
				{
					$domain = $idna_convert->encode(addslashes($_POST['domain']));
					$customerid = intval($_POST['customerid']);
					$subcanemaildomain = intval($_POST['subcanemaildomain']);
					$isemaildomain = intval($_POST['isemaildomain']);
					$customer = $db->query_first("SELECT `documentroot` FROM `".TABLE_PANEL_CUSTOMERS."` WHERE `customerid`='$customerid'");
					$documentroot = $customer['documentroot'];
					if($userinfo['change_serversettings'] == '1')
					{
						$isbinddomain = $_POST['isbinddomain'];
						$caneditdomain = intval($_POST['caneditdomain']);
						$zonefile = addslashes($_POST['zonefile']);
						$openbasedir = intval($_POST['openbasedir']);
						$safemode = intval($_POST['safemode']);
						$speciallogfile = intval($_POST['speciallogfile']);
						$specialsettings = str_replace("\r\n", "\n", $_POST['specialsettings']);
						if(isset($_POST['documentroot']) && $_POST['documentroot'] != '')
						{
							$documentroot = addslashes($_POST['documentroot']);
							if(!preg_match('/^https?\:\/\//', $documentroot))
							{
								$documentroot = makeCorrectDir($documentroot);
							}
						}
					}
					else
					{
						$isbinddomain = '1';
						$caneditdomain = '1';
						$zonefile = '';
						$openbasedir = '1';
						$safemode = '1';
						$speciallogfile = '1';
						$specialsettings = '';
					}

					$domain_check = $db->query_first("SELECT `id`, `domain` FROM `".TABLE_PANEL_DOMAINS."` WHERE `domain` = '$domain'");

					if($openbasedir != '1')
					{
						$openbasedir = '0';
					}
					if($safemode != '1')
					{
						$safemode = '0';
					}
					if($speciallogfile != '1')
					{
						$speciallogfile = '0';
					}
					if($isbinddomain != '1')
					{
						$isbinddomain = '0';
					}
					if($isemaildomain != '1')
					{
						$isemaildomain = '0';
					}
					if($subcanemaildomain != '1')
					{
						$subcanemaildomain = '0';
					}
					if($caneditdomain != '1')
					{
						$caneditdomain = '0';
					}
					

					if($domain=='' || $documentroot=='' || $customerid==0 || $domain_check['domain'] == $domain)
					{
						standard_error('notallreqfieldsorerrors');
						exit;
					}
					else
					{ 
						if(($openbasedir == '0' || $safemode == '0') && (!isset($_POST['reallydoit']) || $_POST['reallydoit'] != 'reallydoit'))
						{
							ask_yesno('admin_domain_reallydisablesecuritysetting', $filename, "page=$page;action=$action;domain=$domain;documentroot=$documentroot;customerid=$customerid;isbinddomain=$isbinddomain;isemaildomain=$isemaildomain;subcanemaildomain=$subcanemaildomain;caneditdomain=$caneditdomain;zonefile=$zonefile;speciallogfile=$speciallogfile;openbasedir=$openbasedir;safemode=$safemode;specialsettings=".urlencode($specialsettings).";reallydoit=reallydoit");
							exit;
						}
						if(isset($_POST['reallydoit']) && $_POST['reallydoit'] == 'reallydoit') 
						{
							$specialsettings = urldecode($specialsettings);
						}

						$specialsettings = addslashes($specialsettings);
						$db->query("INSERT INTO `".TABLE_PANEL_DOMAINS."` (`domain`, `customerid`, `adminid`, `documentroot`, `zonefile`, `isbinddomain`, `isemaildomain`, `subcanemaildomain`, `caneditdomain`, `openbasedir`, `safemode`, `speciallogfile`, `specialsettings`) VALUES ('$domain', '$customerid', '{$userinfo['adminid']}', '$documentroot', '$zonefile', '$isbinddomain', '$isemaildomain', '$subcanemaildomain', '$caneditdomain', '$openbasedir', '$safemode', '$speciallogfile', '$specialsettings')");
						$domainid=$db->insert_id();
						$db->query("UPDATE `".TABLE_PANEL_ADMINS."` SET `domains_used` = `domains_used` + 1 WHERE `adminid` = '{$userinfo['adminid']}'");

						inserttask('1');
						inserttask('4');

						header("Location: $filename?page=$page&s=$s");
					}
				}
				else
				{
					$customers='';
					$result_customers=$db->query("SELECT `customerid`, `loginname`, `name`, `firstname` FROM `".TABLE_PANEL_CUSTOMERS."` ".( $userinfo['customers_see_all'] ? '' : " WHERE `adminid` = '{$userinfo['adminid']}' ")." ORDER BY `name` ASC");
					while($row_customer=$db->fetch_array($result_customers))
					{
						$customers.=makeoption($row_customer['name'].' '.$row_customer['firstname'].' ('.$row_customer['loginname'].')',$row_customer['customerid']);
					}
					$isbinddomain=makeyesno('isbinddomain', '1', '0', '1');
					$isemaildomain=makeyesno('isemaildomain', '1', '0', '1');
					$subcanemaildomain=makeyesno('subcanemaildomain', '1', '0', '0');
					$caneditdomain=makeyesno('caneditdomain', '1', '0', '1');
					$openbasedir=makeyesno('openbasedir', '1', '0', '1');
					$safemode=makeyesno('safemode', '1', '0', '1');
					$speciallogfile=makeyesno('speciallogfile', '1', '0', '0');
					eval("echo \"".getTemplate("domains/domains_add")."\";");
				}
			}
		}

		elseif($action=='edit' && $id!=0)
		{
			$result=$db->query_first(
				"SELECT `d`.`id`, `d`.`domain`, `d`.`customerid`, `d`.`documentroot`, `d`.`isbinddomain`, `d`.`isemaildomain`, `d`.`subcanemaildomain`, `d`.`caneditdomain`, `d`.`zonefile`, `d`.`openbasedir`, `d`.`safemode`, `d`.`speciallogfile`, `d`.`specialsettings`, `c`.`loginname`, `c`.`name`, `c`.`firstname` " .
				"FROM `".TABLE_PANEL_DOMAINS."` `d` " .
				"LEFT JOIN `".TABLE_PANEL_CUSTOMERS."` `c` USING(`customerid`) " .
				"WHERE `d`.`parentdomainid`='0' AND `d`.`id`='$id'".( $userinfo['customers_see_all'] ? '' : " AND `d`.`adminid` = '{$userinfo['adminid']}' ")
			);
			if($result['domain']!='')
			{
				if(isset($_POST['send']) && $_POST['send']=='send')
				{
					$isemaildomain = intval($_POST['isemaildomain']);
					$subcanemaildomain = intval($_POST['subcanemaildomain']);
					$caneditdomain = intval($_POST['caneditdomain']);
					if($userinfo['change_serversettings'] == '1')
					{
						$isbinddomain = $_POST['isbinddomain'];
						$zonefile = addslashes($_POST['zonefile']);
						$openbasedir = intval($_POST['openbasedir']);
						$safemode = intval($_POST['safemode']);
						$specialsettings = str_replace("\r\n", "\n", $_POST['specialsettings']);

						$documentroot = addslashes($_POST['documentroot']);
						if($documentroot=='')
						{
							$customer=$db->query_first("SELECT `documentroot` FROM ".TABLE_PANEL_CUSTOMERS." WHERE `customerid`='".$result['customerid']."'");
							$documentroot=$customer['documentroot'];
						}
						if(!preg_match('/^https?\:\/\//', $documentroot))
						{
							$documentroot = makeCorrectDir($documentroot);
						}
					}
					else
					{
						$isbinddomain = $result['isbinddomain'];
						$zonefile = $result['zonefile'];
						$openbasedir = $result['openbasedir'];
						$safemode = $result['safemode'];
						$specialsettings = $result['specialsettings'];
						$documentroot = $result['documentroot'];
					}

					if($openbasedir != '1')
					{
						$openbasedir = '0';
					}
					if($safemode != '1')
					{
						$safemode = '0';
					}
					if($isbinddomain != '1')
					{
						$isbinddomain = '0';
					}
					if($isemaildomain != '1')
					{
						$isemaildomain = '0';
					}
					if($subcanemaildomain != '1')
					{
						$subcanemaildomain = '0';
					}
					if($caneditdomain != '1')
					{
						$caneditdomain = '0';
					}
					
					if(($openbasedir == '0' || $safemode == '0') && (!isset($_POST['reallydoit']) || $_POST['reallydoit'] != 'reallydoit') && $userinfo['change_serversettings'] == '1')
					{
						ask_yesno('admin_domain_reallydisablesecuritysetting', $filename, "id=$id;page=$page;action=$action;documentroot=$documentroot;isbinddomain=$isbinddomain;isemaildomain=$isemaildomain;subcanemaildomain=$subcanemaildomain;caneditdomain=$caneditdomain;zonefile=$zonefile;openbasedir=$openbasedir;safemode=$safemode;specialsettings=".urlencode($specialsettings).";reallydoit=reallydoit");
						exit;
					}
					if(isset($_POST['reallydoit']) && $_POST['reallydoit'] == 'reallydoit') 
					{
						$specialsettings = urldecode($specialsettings);
					}

					if($documentroot != $result['documentroot'] || $openbasedir != $result['openbasedir'] || $safemode != $result['safemode'] || $specialsettings != $result['specialsettings'])
					{
						inserttask('1');
					}
					if($isbinddomain != $result['isbinddomain'] || $zonefile != $result['zonefile'])
					{
						inserttask('4');
					}
					if($isemaildomain == '0' && $result['isemaildomain'] == '1')
					{
						$db->query("DELETE FROM `".TABLE_MAIL_USERS."` WHERE `domainid`='$id' ");
						$db->query("DELETE FROM `".TABLE_MAIL_VIRTUAL."` WHERE `domainid`='$id' ");
					}

					$specialsettings = addslashes($specialsettings);
					$result=$db->query("UPDATE `".TABLE_PANEL_DOMAINS."` SET `documentroot`='$documentroot', `isbinddomain`='$isbinddomain', `isemaildomain`='$isemaildomain', `subcanemaildomain`='$subcanemaildomain', `caneditdomain`='$caneditdomain', `zonefile`='$zonefile', `openbasedir`='$openbasedir', `safemode`='$safemode', `specialsettings`='$specialsettings' WHERE `id`='$id'");
					$result=$db->query("UPDATE `".TABLE_PANEL_DOMAINS."` SET `openbasedir`='$openbasedir', `safemode`='$safemode', `specialsettings`='$specialsettings'  WHERE `parentdomainid`='$id'");
	
					header("Location: $filename?page=$page&s=$s");
				}
				else
				{
					$result['domain'] = $idna_convert->decode($result['domain']);
					$result['specialsettings'] = stripslashes($result['specialsettings']);
					$isbinddomain=makeyesno('isbinddomain', '1', '0', $result['isbinddomain']);
					$isemaildomain=makeyesno('isemaildomain', '1', '0', $result['isemaildomain']);
					$subcanemaildomain=makeyesno('subcanemaildomain', '1', '0', $result['subcanemaildomain']);
					$caneditdomain=makeyesno('caneditdomain', '1', '0', $result['caneditdomain']);
					$openbasedir=makeyesno('openbasedir', '1', '0', $result['openbasedir']);
					$safemode=makeyesno('safemode', '1', '0', $result['safemode']);
					$speciallogfile=($result['speciallogfile'] == 1 ? $lng['panel']['yes'] : $lng['panel']['no']);
					eval("echo \"".getTemplate("domains/domains_edit")."\";");
				}
			}
		}
	}

?>

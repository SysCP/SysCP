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
			$result=$db->query("SELECT `d`.`id`, `d`.`domain`, `d`.`customerid`, `d`.`documentroot`, `d`.`zonefile`, `d`.`openbasedir`, `d`.`safemode`, `c`.`name`, `c`.`surname` FROM `".TABLE_PANEL_DOMAINS."` `d` LEFT JOIN `".TABLE_PANEL_CUSTOMERS."` `c` USING(`customerid`) WHERE `d`.`isemaildomain`='1' ORDER BY `$sortby` $sortorder");
			while($row=$db->fetch_array($result))
			{
				eval("\$domains.=\"".getTemplate("domains/domains_domain")."\";");
			}
			eval("echo \"".getTemplate("domains/domains")."\";");
		}

		elseif($action=='delete' && $id!=0)
		{
			$result=$db->query_first("SELECT `id`, `domain`, `customerid`, `documentroot`, `isemaildomain`, `zonefile` FROM `".TABLE_PANEL_DOMAINS."` WHERE `id`='$id'");
			if($result['domain']!='')
			{
				if(isset($_POST['send']) && $_POST['send']=='send')
				{
					$db->query("DELETE FROM `".TABLE_PANEL_DOMAINS."` WHERE `id`='$id' OR `parentdomainid`='".$result['id']."'");
					$db->query("DELETE FROM `".TABLE_POSTFIX_TRANSPORT."` WHERE `domainid`='$id'");
					$db->query("DELETE FROM `".TABLE_POSTFIX_USERS."` WHERE `domainid`='$id'");
					$db->query("DELETE FROM `".TABLE_POSTFIX_VIRTUAL."` WHERE `domainid`='$id'");

					inserttask('1');
					inserttask('4');

					header("Location: $filename?page=$page&s=$s");
				}
				else {
					ask_yesno('admin_domain_reallydelete', $filename, "id=$id;page=$page;action=$action");
				}
			}
		}

		elseif($action=='add')
		{
			if(isset($_POST['send']) && $_POST['send']=='send')
			{
				$domain=addslashes($_POST['domain']);
				$zonefile=addslashes($_POST['zonefile']);
				$customerid=intval($_POST['customerid']);
				$openbasedir=intval($_POST['openbasedir']);
				$safemode=intval($_POST['safemode']);
				$specialsettings=str_replace("\r\n", "\n", addslashes($_POST['specialsettings']));

				$domain_check = $db->query_first("SELECT `id`, `domain` FROM `".TABLE_PANEL_DOMAINS."` WHERE `domain` = '$domain'");

				if($settings['system']['documentrootstyle'] == 'domain')
				{
					$customer = $db->query_first("SELECT `documentroot` FROM `".TABLE_PANEL_CUSTOMERS."` WHERE `customerid`='$customerid'");
					$documentroot = $customer['documentroot'].'/'.$domain;
				}
				elseif($_POST['documentroot'] == '')
				{
					$customer = $db->query_first("SELECT `documentroot` FROM `".TABLE_PANEL_CUSTOMERS."` WHERE `customerid`='$customerid'");
					$documentroot = $customer['documentroot'];
				}
				else
				{
					$documentroot=addslashes($_POST['documentroot']);
				}

				$documentroot=str_replace('..','',$documentroot);
				if(substr($documentroot, -1, 1) != '/')
				{
					$documentroot.='/';
				}
				if(substr($documentroot, 0, 1) != '/')
				{
					$documentroot='/'.$documentroot;
				}

				if($openbasedir != '1')
				{
					$openbasedir = '0';
				}
				if($safemode != '1')
				{
					$safemode = '0';
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
						ask_yesno('admin_domain_reallydisablesecuritysetting', $filename, "page=$page;action=$action;domain=$domain;documentroot=$documentroot;zonefile=$zonefile;openbasedir=$openbasedir;customerid=$customerid;safemode=$safemode;specialsettings=$specialsettings;reallydoit=reallydoit");
						exit;
					}

					$db->query("INSERT INTO `".TABLE_PANEL_DOMAINS."` (`domain`, `customerid`, `documentroot`, `zonefile`, `isemaildomain`, `openbasedir`, `safemode`, `specialsettings`) VALUES ('$domain', '$customerid', '$documentroot', '$zonefile', '1', '$openbasedir', '$safemode', '$specialsettings')");
					$domainid=$db->insert_id();
					$db->query("INSERT INTO `".TABLE_POSTFIX_TRANSPORT."` (`domain`, `destination`, `domainid`, `customerid`) VALUES ('$domain', 'virtual:', '$domainid', '$customerid')");

					inserttask('1');
					inserttask('4');

					header("Location: $filename?page=$page&s=$s");
				}
			}
			else
			{
				$customers='';
				$result_customers=$db->query("SELECT `customerid`, `name`, `surname` FROM `".TABLE_PANEL_CUSTOMERS."` ORDER BY `name` ASC");
				while($row_customer=$db->fetch_array($result_customers))
				{
					$customers.=makeoption($row_customer['name'].' '.$row_customer['surname'].' ('.$row_customer['customerid'].')',$row_customer['customerid']);
				}
				$openbasedir=makeyesno('openbasedir', '1', '0', '1');
				$safemode=makeyesno('safemode', '1', '0', '1');
				eval("echo \"".getTemplate("domains/domains_add")."\";");
			}
		}

		elseif($action=='edit' && $id!=0)
		{
			$result=$db->query_first("SELECT `d`.`id`, `d`.`domain`, `d`.`customerid`, `d`.`documentroot`, `d`.`zonefile`, `d`.`openbasedir`, `d`.`safemode`, `d`.`specialsettings`, `c`.`name`, `c`.`surname` FROM `".TABLE_PANEL_DOMAINS."` `d` LEFT JOIN `".TABLE_PANEL_CUSTOMERS."` `c` USING(`customerid`) WHERE `d`.`isemaildomain`='1' AND `id`='$id'");
			if($result['domain']!='')
			{
				if(isset($_POST['send']) && $_POST['send']=='send')
				{
					$zonefile=addslashes($_POST['zonefile']);
					$openbasedir=intval($_POST['openbasedir']);
					$safemode=intval($_POST['safemode']);
					$specialsettings=str_replace("\r\n", "\n", addslashes($_POST['specialsettings']));

					if($settings['system']['documentrootstyle'] == 'customer')
					{
						$documentroot = addslashes($_POST['documentroot']);
						if($documentroot=='')
						{
							$customer=$db->query_first("SELECT `documentroot` FROM ".TABLE_PANEL_CUSTOMERS." WHERE `customerid`='".$result['customerid']."'");
							$documentroot=$customer['documentroot'];
						}

						$documentroot = str_replace('..', '' ,$documentroot);
						if(substr($documentroot, -1, 1) != '/')
						{
							$documentroot .= '/';
						}
						if(substr($documentroot, 0, 1) != '/')
						{
							$documentroot = '/'.$documentroot;
						}
					}
					else
					{
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
					
					if(($openbasedir == '0' || $safemode == '0') && (!isset($_POST['reallydoit']) || $_POST['reallydoit'] != 'reallydoit'))
					{
						ask_yesno('admin_domain_reallydisablesecuritysetting', $filename, "id=$id;page=$page;action=$action;documentroot=$documentroot;zonefile=$zonefile;openbasedir=$openbasedir;safemode=$safemode;specialsettings=$specialsettings;reallydoit=reallydoit");
						exit;
					}

					if($documentroot != $result['documentroot'] || $openbasedir != $result['openbasedir'] || $safemode != $result['safemode'] || $specialsettings != $result['specialsettings'])
					{
						inserttask('1');
					}
					if($zonefile != $result['zonefile'])
					{
						inserttask('4');
					}

					$result=$db->query("UPDATE `".TABLE_PANEL_DOMAINS."` SET `documentroot`='$documentroot', `zonefile`='$zonefile', `openbasedir`='$openbasedir', `safemode`='$safemode', `specialsettings`='$specialsettings' WHERE `id`='$id'");
					$result=$db->query("UPDATE `".TABLE_PANEL_DOMAINS."` SET `openbasedir`='$openbasedir', `safemode`='$safemode', `specialsettings`='$specialsettings'  WHERE `parentdomainid`='$id'");
	
					header("Location: $filename?page=$page&s=$s");
				}
				else
				{
					$openbasedir=makeyesno('openbasedir', '1', '0', $result['openbasedir']);
					$safemode=makeyesno('safemode', '1', '0', $result['safemode']);
					eval("echo \"".getTemplate("domains/domains_edit")."\";");
				}
			}
		}
	}

?>

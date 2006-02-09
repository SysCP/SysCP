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
 * @author    Florian Lippert <flo@redenswert.de>
 * @copyright (C) 2003-2004 Florian Lippert
 * @package   Panel
 * @version   $Id$
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
			$result = $db->query(
				"SELECT `d`.`id`, " .
				"       `d`.`customerid`, " .
				"       `d`.`domain`, " .
				"       `d`.`documentroot`, " .
				"       `d`.`isemaildomain`, " .
				"       `d`.`caneditdomain`, " .
				"       `d`.`iswildcarddomain`, " .
				"       `d`.`parentdomainid`, " .
				"		`ad`.`domain` AS `aliasdomain` " .
				"FROM `".TABLE_PANEL_DOMAINS."` `d` " .
				"LEFT JOIN `".TABLE_PANEL_DOMAINS."` `ad` ON `d`.`aliasdomain`=`ad`.`id` " .
				"WHERE `d`.`customerid`='".$userinfo['customerid']."' " .
				"  AND `d`.`id` <> ".$userinfo['standardsubdomain']
			);
 			$domains='';
			$rows = $db->num_rows($result);
			if ($settings['panel']['paging'] > 0)
			{
				$pages = intval($rows / $settings['panel']['paging']);
			}
			else
			{
				$pages = 0;
			}
			if ($pages != 0)
			{
				if(isset($_GET['no']))
				{
					$pageno = intval($_GET['no']);
				}
				else
				{
					$pageno = 1;
				}
				if ($pageno > $pages)
				{
					$pageno = $pages + 1;
				}
				elseif ($pageno < 1)
				{
					$pageno = 1;
				}
				$pagestart = ($pageno - 1) * $settings['panel']['paging'];
				$result=$db->query(
					"SELECT `d`.`id`, " .
					"       `d`.`customerid`, " .
					"       `d`.`domain`, " .
					"       `d`.`documentroot`, " .
					"       `d`.`isemaildomain`, " .
					"       `d`.`caneditdomain`, " .
					"       `d`.`iswildcarddomain`, " .
					"       `d`.`parentdomainid`, " .
					"		`ad`.`domain` AS `aliasdomain` " .
					"FROM `".TABLE_PANEL_DOMAINS."` `d` " .
					"LEFT JOIN `".TABLE_PANEL_DOMAINS."` `ad` ON `d`.`aliasdomain`=`ad`.`id` " .
					"WHERE `d`.`customerid`='".$userinfo['customerid']."' " .
					"  AND `d`.`id` <> ".$userinfo['standardsubdomain'] .
					" LIMIT $pagestart , ".$settings['panel']['paging'].";"
				);
				$paging = '';
				for ($count = 1; $count <= $pages+1; $count++)
				{
					if ($count == $pageno)
					{
						$paging .= "<a href=\"$filename?s=$s&page=$page&no=$count\"><b>$count</b></a>&nbsp;";
					}
					else
					{
						$paging .= "<a href=\"$filename?s=$s&page=$page&no=$count\">$count</a>&nbsp;";
					}
				}
			}
			else
			{
				$paging = "";
			}
 			$parentdomains_count=0;
 			$domains_count=0;
 			$domain_array=array();
			while( $row = $db->fetch_array($result) )
 			{
 				$row['domain'] = $idna_convert->decode($row['domain']);
				$domainparts = explode('.',$row['domain']);
				$domainparts = array_reverse($domainparts);
				$sortkey = '';
				foreach ($domainparts as $key => $part)
				{
					$sortkey .= $part.'.';
				}
				$domain_array[$sortkey] = $row;
 			}
			$domain_id_array=array();
			foreach($domain_array as $sortkey => $row)
			{
				$domain_id_array[$row['id']] = $sortkey;
			}
			$domain_sort_array=array();
			foreach($domain_array as $sortkey => $row)
			{
				if ($row['parentdomainid'] == 0)
				{
					$domain_sort_array[$sortkey]=array($sortkey=>$row);
				}
				else
				{
					$domain_sort_array[$domain_id_array[$row['parentdomainid']]][$sortkey] = $row;
				}
			}
			$domain_array=array();
			ksort($domain_sort_array);
			foreach($domain_sort_array as $subarray)
			{
				ksort($subarray);
				$domain_array=array_merge($domain_array,$subarray);
			}
			$parentdomainid = 0;
 			foreach($domain_array as $row)
 			{
 				$row['documentroot']=str_replace($userinfo['documentroot'],'',$row['documentroot']);

				if ($row['parentdomainid'] == 0)
				{
					eval("\$domains.=\"".getTemplate("domains/domains_delimiter")."\";");
				}
				$aliasdomain=false;
				$result=$db->query_first('SELECT COUNT(`id`) AS `count` FROM `'.TABLE_PANEL_DOMAINS.'` WHERE `aliasdomain`=\''.$row['id'].'\'');
				if($result['count'] > 0)
				{
					$aliasdomain=true;
				}

 				eval("\$domains.=\"".getTemplate("domains/domains_domain")."\";");
 				if($row['parentdomainid'] == '0' && $row['iswildcarddomain'] != '1' && $row['caneditdomain'] == '1')
 				{
					$parentdomains_count++;
				}
				$domains_count++;
			}

			eval("echo \"".getTemplate("domains/domainlist")."\";");
		}

		elseif($action=='delete' && $id!=0)
		{
			$result=$db->query_first("SELECT `id`, `customerid`, `domain`, `documentroot`, `isemaildomain`, `parentdomainid` FROM `".TABLE_PANEL_DOMAINS."` WHERE `customerid`='".$userinfo['customerid']."' AND `id`='$id'");
			$alias_check=$db->query_first('SELECT COUNT(`id`) AS `count` FROM `'.TABLE_PANEL_DOMAINS.'` WHERE `aliasdomain`=\''.$id.'\'');
			if(isset($result['parentdomainid']) && $result['parentdomainid']!='0' && $alias_check['count'] == 0)
			{
				if(isset($_POST['send']) && $_POST['send']=='send')
				{
					if($result['isemaildomain'] == '1')
					{
						$emails=$db->query_first('SELECT COUNT(`id`) AS `count` FROM `'.TABLE_MAIL_VIRTUAL.'` WHERE `customerid`=\''.$userinfo['customerid'].'\' AND `domainid`=\''.$id.'\'');
						if($emails['count'] != '0')
						{
							standard_error('domains_cantdeletedomainwithemail');
						}
					}
					$result=$db->query("DELETE FROM `".TABLE_PANEL_DOMAINS."` WHERE `customerid`='".$userinfo['customerid']."' AND `id`='$id'");
					$result=$db->query("UPDATE `".TABLE_PANEL_CUSTOMERS."` SET `subdomains_used`=`subdomains_used`-1 WHERE `customerid`='".$userinfo['customerid']."'");
					inserttask('1');
					redirectTo ( $filename , Array ( 'page' => $page , 's' => $s ) ) ;
				}
				else
				{
					ask_yesno('domains_reallydelete', $filename, "id=$id;page=$page;action=$action", $idna_convert->decode($result['domain']));
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
					$subdomain = $idna_convert->encode(preg_replace(Array('/\:(\d)+$/','/^https?\:\/\//'),'',addslashes($_POST['subdomain'])));
					$domain=$idna_convert->encode(addslashes($_POST['domain']));
					$domain_check=$db->query_first("SELECT `id`, `customerid`, `domain`, `documentroot`, `isemaildomain`, `openbasedir`, `safemode`, `speciallogfile`, `specialsettings` FROM `".TABLE_PANEL_DOMAINS."` WHERE `domain`='$domain' AND `customerid`='".$userinfo['customerid']."' AND `parentdomainid`='0' AND `iswildcarddomain`='0' AND `caneditdomain`='1' ");
					$completedomain=$subdomain.'.'.$domain;
					$completedomain_check=$db->query_first("SELECT `id`, `customerid`, `domain`, `documentroot`, `isemaildomain` FROM `".TABLE_PANEL_DOMAINS."` WHERE `domain`='$completedomain' AND `customerid`='".$userinfo['customerid']."' AND `caneditdomain` = '1'");
					$aliasdomain = intval($_POST['alias']);
					
					$aliasdomain_check=array('id' => 0);
					if($aliasdomain!=0)
					{
						$aliasdomain_check = $db->query_first('SELECT `id` FROM `'.TABLE_PANEL_DOMAINS.'` `d`,`'.TABLE_PANEL_CUSTOMERS.'` `c` WHERE `d`.`customerid`=\''.$userinfo['customerid'].'\' AND `d`.`aliasdomain` IS NULL AND `d`.`id`<>`c`.`standardsubdomain` AND `c`.`customerid`=\''.$userinfo['customerid'].'\' AND `d`.`id`=\''.$aliasdomain.'\'');
					}
					
					$path=addslashes($_POST['path']);
					if(!preg_match('/^https?\:\/\//', $path))
					{
						$path=makeCorrectDir($path);
						$userpath=$path;
						$path=$userinfo['documentroot'].$path;
						if(!is_dir($path))
						{
							standard_error('directorymustexist',$userpath);
							exit;
						}
					}

					if($path=='')
					{
						standard_error('patherror');
					}
					elseif($subdomain=='')
					{
						standard_error(array('stringisempty','domainname'));
					}
					elseif($subdomain=='www')
					{
						standard_error('wwwnotallowed');
					}
					elseif(preg_match('/.*\..*/',$subdomain))
					{
						standard_error('subdomainiswrong',$subdomain);
					}
					elseif($domain=='')
					{
						standard_error('domaincantbeempty');
					}
					elseif($completedomain_check['domain']==$completedomain)
					{
						standard_error('domainexistalready',$completedomain);
					}
					elseif($domain_check['domain']!=$domain)
					{
						standard_error('maindomainnonexist',$domain);
					}
					elseif($aliasdomain_check['id']!=$aliasdomain)
					{
						standard_error('domainisaliasorothercustomer');
					}

					else
					{
						$result=$db->query("INSERT INTO `".TABLE_PANEL_DOMAINS."` (`customerid`, `domain`, `documentroot`, `aliasdomain`, `parentdomainid`, `isemaildomain`, `openbasedir`, `safemode`, `speciallogfile`, `specialsettings`) VALUES ('".$userinfo['customerid']."', '$completedomain', '$path', ".(($aliasdomain != 0) ? "'".$aliasdomain."'" : "NULL").", '".$domain_check['id']."', '0', '".$domain_check['openbasedir']."', '".$domain_check['safemode']."', '".$domain_check['speciallogfile']."', '".$domain_check['specialsettings']."')");
						$result=$db->query("UPDATE `".TABLE_PANEL_CUSTOMERS."` SET `subdomains_used`=`subdomains_used`+1 WHERE `customerid`='".$userinfo['customerid']."'");
						inserttask('1');
    					redirectTo ( $filename , Array ( 'page' => $page , 's' => $s ) ) ;
					}
				}
				else
				{
					$result=$db->query("SELECT `id`, `domain`, `documentroot`, `isemaildomain` FROM `".TABLE_PANEL_DOMAINS."` WHERE `customerid`='".$userinfo['customerid']."' AND `parentdomainid`='0' AND `iswildcarddomain`='0' AND `caneditdomain`='1' ORDER BY `domain` ASC");
					$domains='';
					while($row=$db->fetch_array($result))
					{
						$domains.=makeoption($idna_convert->decode($row['domain']),$row['domain']);
					}
					$aliasdomains=makeoption($lng['domains']['noaliasdomain'],0);
					$result_domains=$db->query("SELECT `d`.`id`, `d`.`domain` FROM `".TABLE_PANEL_DOMAINS."` `d`, `".TABLE_PANEL_CUSTOMERS."` `c` WHERE `d`.`aliasdomain` IS NULL AND `d`.`id` <> `c`.`standardsubdomain` AND `d`.`customerid`=`c`.`customerid` AND `d`.`customerid`=".$userinfo['customerid']." ORDER BY `d`.`domain` ASC");
					while($row_domain=$db->fetch_array($result_domains))
					{
						$aliasdomains.=makeoption($idna_convert->decode($row_domain['domain']),$row_domain['id']);
					}
					$pathSelect = makePathfield( $userinfo['documentroot'], $userinfo['guid'], 
					                             $userinfo['guid'], $settings['panel']['pathedit'] );				
					eval("echo \"".getTemplate("domains/domains_add")."\";");
				}
			}
		}

		elseif($action=='edit' && $id!=0)
		{
			$result=$db->query_first("SELECT `d`.`id`, `d`.`customerid`, `d`.`domain`, `d`.`documentroot`, `d`.`isemaildomain`, `d`.`iswildcarddomain`, `d`.`parentdomainid`, `d`.`aliasdomain`, `pd`.`subcanemaildomain` FROM `".TABLE_PANEL_DOMAINS."` `d`, `".TABLE_PANEL_DOMAINS."` `pd` WHERE `d`.`customerid`='".$userinfo['customerid']."' AND `d`.`id`='$id' AND ((`d`.`parentdomainid`!='0' AND `pd`.`id`=`d`.`parentdomainid`) OR (`d`.`parentdomainid`='0' AND `pd`.`id`=`d`.`id`)) AND `d`.`caneditdomain`='1'");
			$alias_check=$db->query_first('SELECT COUNT(`id`) AS count FROM `'.TABLE_PANEL_DOMAINS.'` WHERE `aliasdomain`=\''.$result['id'].'\'');
			$alias_check=$alias_check['count'];
			
			if(isset($result['customerid']) && $result['customerid']==$userinfo['customerid'])
			{
				if(isset($_POST['send']) && $_POST['send']=='send')
				{
					$path=addslashes($_POST['path']);
					$aliasdomain = intval($_POST['alias']);
					if(!preg_match('/^https?\:\/\//', $path))
					{
						$path=makeCorrectDir($path);
						$userpath=$path;
						$path=$userinfo['documentroot'].$path;
						if(!is_dir($path))
						{
							standard_error('directorymustexist',$userpath);
							exit;
						}
					}

					if(isset($_POST['iswildcarddomain']) && $_POST['iswildcarddomain'] == '1' && $result['parentdomainid'] == '0' && $userinfo['subdomains'] != '0')
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

					if($result['parentdomainid'] != '0' && $result['subcanemaildomain'] == '1' && isset ($_POST['isemaildomain']) )
					{
						$isemaildomain = intval($_POST['isemaildomain']);
					}
					else
					{
						$isemaildomain = $result['isemaildomain'];
					}

					$aliasdomain_check=array('id' => 0);
					if($aliasdomain!=0)
					{
						$aliasdomain_check = $db->query_first('SELECT `id` FROM `'.TABLE_PANEL_DOMAINS.'` `d`,`'.TABLE_PANEL_CUSTOMERS.'` `c` WHERE `d`.`customerid`=\''.$result['customerid'].'\' AND `d`.`aliasdomain` IS NULL AND `d`.`id`<>`c`.`standardsubdomain` AND `c`.`customerid`=\''.$result['customerid'].'\' AND `d`.`id`=\''.$aliasdomain.'\'');
					}
					if($aliasdomain_check['id']!=$aliasdomain)
					{
						standard_error('domainisaliasorothercustomer');
					}
					
					if($path=='')
					{
						standard_error('patherror');
						exit;
					}
					else
					{
						if(($result['isemaildomain'] == '1') && ($isemaildomain == '0'))
						{
							$db->query("DELETE FROM `".TABLE_MAIL_USERS."` WHERE `customerid`='".$userinfo['customerid']."' AND `domainid`='$id'");
							$db->query("DELETE FROM `".TABLE_MAIL_VIRTUAL."` WHERE `customerid`='".$userinfo['customerid']."' AND `domainid`='$id'");
						}
						if($path != $result['documentroot'] || $isemaildomain != $result['isemaildomain'] || $iswildcarddomain != $result['iswildcarddomain'] || $aliasdomain != $result['aliasdomain'])
						{
							inserttask('1');
							$result=$db->query("UPDATE `".TABLE_PANEL_DOMAINS."` SET `documentroot`='$path', `isemaildomain`='$isemaildomain', `iswildcarddomain`='$iswildcarddomain', `aliasdomain`=".(($aliasdomain!=0 && $alias_check==0) ? '\''.$aliasdomain.'\'' : 'NULL')." WHERE `customerid`='".$userinfo['customerid']."' AND `id`='$id'");
						}
            			redirectTo ( $filename , Array ( 'page' => $page , 's' => $s ) ) ;
					}
				}
				else
				{
					$result['domain'] = $idna_convert->decode($result['domain']);
					$domains=makeoption($lng['domains']['noaliasdomain'],0,$result['aliasdomain']);
					$result_domains=$db->query("SELECT `d`.`id`, `d`.`domain` FROM `".TABLE_PANEL_DOMAINS."` `d`, `".TABLE_PANEL_CUSTOMERS."` `c` WHERE `d`.`aliasdomain` IS NULL AND `d`.`id`<>'".$result['id']."' AND `c`.`standardsubdomain`<>`d`.`id` AND `d`.`customerid`='".$userinfo['customerid']."' AND `c`.`customerid`=`d`.`customerid` ORDER BY `d`.`domain` ASC");
					while($row_domain=$db->fetch_array($result_domains))
					{
						$domains.=makeoption($idna_convert->decode($row_domain['domain']),$row_domain['id'],$result['aliasdomain']);
					}
					$pathSelect = makePathfield( $userinfo['documentroot'], $userinfo['guid'], 
					                             $userinfo['guid'], $settings['panel']['pathedit'],
					                             $result['documentroot'] );				
//					$result['documentroot']=str_replace($userinfo['documentroot'],'',$result['documentroot']);
					$iswildcarddomain=makeyesno('iswildcarddomain', '1', '0', $result['iswildcarddomain']);
					$isemaildomain=makeyesno('isemaildomain', '1', '0', $result['isemaildomain']);
					eval("echo \"".getTemplate("domains/domains_edit")."\";");
				}
			}
			else
			{
				standard_error('domains_canteditdomain');
			}
		}
	}

?>
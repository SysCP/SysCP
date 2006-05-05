<?php
/**
 * This file is part of the SysCP project. 
 * Copyright (c) 2003-2006 the SysCP Project. 
 * 
 * For the full copyright and license information, please view the COPYING 
 * file that was distributed with this source code. You can also view the
 * COPYING file online at http://files.syscp.org/misc/COPYING.txt
 * 
 * @author     Florian Lippert <flo@redenswert.de>
 * @copyright  (c) 2003-2006 Florian Lippert
 * @package    Org.Syscp.Core
 * @subpackage Panel.Admin
 * @license    GPLv2 http://files.syscp.org/misc/COPYING.txt
 * @version    $Id:admin_domains.php 460 2006-04-23 15:07:49 +0200 (So, 23 Apr 2006) martin $
 * 
 * @todo code cleanup is needed, exspecially in queries, loops, conditions
 * @todo code restructuring is needed, we need to seperate view, controller and model logic,
 *       as a preparation towards MVC patterns
 */


	define('AREA', 'admin');

	/**
	 * Include our init.php, which manages Sessions, Language etc.
	 */
	require_once '../lib/init.php';

//	if(isset($_POST['id']))
//	{
//		$id=intval($_POST['id']);
//	}
//	elseif(isset($_GET['id']))
//	{
//		$id=intval($_GET['id']);
//	}

	if($config->get('env.page') == 'domains' || $config->get('env.page') == 'overview')
	{
		if($config->get('env.action')=='')
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
				"SELECT `d`.`id`, `d`.`domain`, `d`.`customerid`, `d`.`documentroot`, CONCAT(`ip`.`ip`,':',`ip`.`port`) AS `ipandport`, `d`.`zonefile`, `d`.`openbasedir`, `d`.`safemode`, `d`.`isemaildomain`, `d`.`parentdomainid`, `c`.`loginname`, `c`.`name`, `c`.`firstname`, `ad`.`domain` AS `alias` " .
				"FROM `".TABLE_PANEL_DOMAINS."` `d` " .
				"LEFT JOIN `".TABLE_PANEL_CUSTOMERS."` `c` USING(`customerid`) " .
				"LEFT JOIN `".TABLE_PANEL_DOMAINS."` `ad` ON `d`.`aliasdomain`=`ad`.`id` " .
				"LEFT JOIN `".TABLE_PANEL_IPSANDPORTS."` `ip` ON (`d`.`ipandport` = `ip`.`id`) " .
				"WHERE `d`.`parentdomainid`='0' ".( $userinfo['customers_see_all'] ? '' : " AND `d`.`adminid` = '{$userinfo['adminid']}' ")."" .
				"ORDER BY `$sortby` $sortorder"
			);
			$rows = $db->num_rows($result);
			if ($config->get('panel.paging') > 0)
			{
				$pages = intval($rows / $config->get('panel.paging'));
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
				$pagestart = ($pageno - 1) * $config->get('panel.paging');
				$result=$db->query(
					"SELECT `d`.`id`, `d`.`domain`, `d`.`customerid`, `d`.`documentroot`, CONCAT(`ip`.`ip`,':',`ip`.`port`) AS `ipandport`, `d`.`zonefile`, `d`.`openbasedir`, `d`.`safemode`, `d`.`isemaildomain`, `d`.`parentdomainid`, `c`.`loginname`, `c`.`name`, `c`.`firstname`, `ad`.`domain` AS `alias` " .
					"FROM `".TABLE_PANEL_DOMAINS."` `d` " .
					"LEFT JOIN `".TABLE_PANEL_CUSTOMERS."` `c` USING(`customerid`) " .
					"LEFT JOIN `".TABLE_PANEL_DOMAINS."` `ad` ON `d`.`aliasdomain`=`ad`.`id` " .
					"LEFT JOIN `".TABLE_PANEL_IPSANDPORTS."` `ip` ON (`d`.`ipandport` = `ip`.`id`) " .
					"WHERE `d`.`parentdomainid`='0' ".( $userinfo['customers_see_all'] ? '' : " AND `d`.`adminid` = '{$userinfo['adminid']}' ")."" .
					"ORDER BY `$sortby` $sortorder " .
					"LIMIT $pagestart , ".$config->get('panel.paging').";"
				);
				$paging = '';
				for ($count = 1; $count <= $pages+1; $count++)
				{
					if ($count == $pageno)
					{
						$paging .= "<a href=\"".$config->get('env.filename')."?s=".$config->get('env.s')."&page=".$config->get('env.page')."&no=$count\"><b>$count</b></a>&nbsp;";
					}
					else
					{
						$paging .= "<a href=\"".$config->get('env.filename')."?s=".$config->get('env.s')."&page=".$config->get('env.page')."&no=$count\">$count</a>&nbsp;";
					}
				}
			}
			else
			{
				$paging = "";
			}
			$domain_array=array();
			while($row=$db->fetch_array($result))
			{
				$row['domain'] = $idna->decode($row['domain']);
				$domain_array[$row['domain']] = $row;
			}
//			ksort($domain_array);
			foreach($domain_array as $row)
			{
				$standardsubdomain = false;
				// --- martin @ 03.08.2005 ---------------------------------------------------------
				// changed query variable not to collide with an elemental config variable
				$query = 
					'SELECT `customerid` ' .
					'FROM   `'.TABLE_PANEL_CUSTOMERS.'` ' .
					'WHERE  `standardsubdomain` = \''.$row['id'].'\'';
				$result = $db->query_first( $query );
				// ---------------------------------------------------------------------------------
				if(isset($result['customerid']))
				{
					$standardsubdomain = true;
				}
				$aliasdomain=false;
				$result=$db->query_first('SELECT COUNT(`id`) AS `count` FROM `'.TABLE_PANEL_DOMAINS.'` WHERE `aliasdomain`=\''.$row['id'].'\'');
				if($result['count'] > 0)
				{
					$aliasdomain=true;
				}
				eval("\$domains.=\"".getTemplate("domains/domains_domain")."\";");
			}
			eval("echo \"".getTemplate("domains/domains")."\";");
		}

		elseif($config->get('env.action')=='delete' && $config->get('env.id')!=0)
		{
			$result=$db->query_first("SELECT `d`.`id`, `d`.`domain`, `d`.`customerid`, `d`.`documentroot`, `d`.`isemaildomain`, `d`.`zonefile` FROM `".TABLE_PANEL_DOMAINS."` `d`, `".TABLE_PANEL_CUSTOMERS."` `c` WHERE `d`.`id`='".$config->get('env.id')."' AND `d`.`id` <> `c`.`standardsubdomain`".( $userinfo['customers_see_all'] ? '' : " AND `d`.`adminid` = '{$userinfo['adminid']}' "));
			$alias_check=$db->query_first('SELECT COUNT(`id`) AS `count` FROM `'.TABLE_PANEL_DOMAINS.'` WHERE `aliasdomain`=\''.$config->get('env.id').'\'');
			if($result['domain']!='' && $alias_check['count'] == 0)
			{
				if(isset($_POST['send']) && $_POST['send']=='send')
				{
					$query =
						'SELECT `id` ' .
						'FROM `'.TABLE_PANEL_DOMAINS.'` ' .
						'WHERE (`id`="'.$config->get('env.id').'" OR `parentdomainid`="'.$config->get('env.id').'") ' .
						'  AND  `isemaildomain`="1"';
					$subResult = $db->query($query);
					$idString = array();
					while ( $subRow = $db->fetch_array($subResult) )
					{
						$idString[] = '`domainid` = "'.$subRow['id'].'"';
					}
					$idString = implode(' OR ', $idString);
					if ( $idString != '' )
					{
						$query = 
							'DELETE FROM `'.TABLE_MAIL_USERS.'` ' .
							'WHERE '.$idString;
						$db->query($query);
						$query = 
							'DELETE FROM `'.TABLE_MAIL_VIRTUAL.'` ' .
							'WHERE '.$idString;
						$db->query($query);
					}
					$db->query("DELETE FROM `".TABLE_PANEL_DOMAINS."` WHERE `id`='".$config->get('env.id')."' OR `parentdomainid`='".$result['id']."'");
					$deleted_domains = $db->affected_rows();
					$db->query("UPDATE `".TABLE_PANEL_CUSTOMERS."` SET `subdomains_used` = `subdomains_used` - 0".($deleted_domains - 1)." WHERE `customerid` = '{$result['customerid']}'");
					$db->query("UPDATE `".TABLE_PANEL_ADMINS."` SET `domains_used` = `domains_used` - 1 WHERE `adminid` = '{$userinfo['adminid']}'");
					updateCounters () ;

					$hooks->call( 'core.deleteDomain', 
					              array( 'id' => $config->get('env.id') ) );

					redirectTo ( $config->get('env.filename') , Array ( 'page' => $config->get('env.page') , 's' => $config->get('env.s') ) ) ;
				}
				else
				{
					ask_yesno('admin_domain_reallydelete', $config->get('env.filename'), "id=".$config->get('env.id').";page=".$config->get('env.page').";action=".$config->get('env.action'), $idna->decode($result['domain']));
				}
			}
		}

		elseif($config->get('env.action')=='add')
		{
			if($userinfo['domains_used'] < $userinfo['domains'] || $userinfo['domains'] == '-1')
			{
				if(isset($_POST['send']) && $_POST['send']=='send')
				{
					$domain = $idna->encode(preg_replace(Array('/\:(\d)+$/','/^https?\:\/\//'),'',addslashes($_POST['domain'])));
					$customerid = intval($_POST['customerid']);
					$subcanemaildomain = intval($_POST['subcanemaildomain']);
					$isemaildomain = intval($_POST['isemaildomain']);
					$aliasdomain = intval($_POST['alias']);
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
						$ipandport = intval($_POST['ipandport']);
						if(isset($_POST['documentroot']) && $_POST['documentroot'] != '')
						{
							if ( substr($_POST['documentroot'],0,1) != '/' )
							{
								$documentroot .= '/'.addslashes($_POST['documentroot']);
							}
							else 
							{
								$documentroot = addslashes($_POST['documentroot']);
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
						$ipandport = $db->query_first('SELECT `id` FROM `'.TABLE_PANEL_IPSANDPORTS.'` WHERE `default`=\'1\'');
						$ipandport = intval($ipandport['id']);
					}
					if(!preg_match('/^https?\:\/\//', $documentroot))
					{
						$documentroot = makeCorrectDir($documentroot);
					}

					$domain_check = $db->query_first("SELECT `id`, `domain` FROM `".TABLE_PANEL_DOMAINS."` WHERE `domain` = '$domain'");
					$aliasdomain_check=array('id' => 0);
					if($aliasdomain!=0)
					{
						$aliasdomain_check = $db->query_first('SELECT `id` FROM `'.TABLE_PANEL_DOMAINS.'` `d`,`'.TABLE_PANEL_CUSTOMERS.'` `c` WHERE `d`.`customerid`=\''.$customerid.'\' AND `d`.`aliasdomain` IS NULL AND `d`.`id`<>`c`.`standardsubdomain` AND `c`.`customerid`=\''.$customerid.'\' AND `d`.`id`=\''.$aliasdomain.'\'');
					}

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


					if($domain=='')
					{
						standard_error(array('stringisempty','mydomain'));
					}
					elseif(!check_domain($domain))
					{
						standard_error(array('stringiswrong','mydomain'));
					}
					elseif($documentroot=='')
					{
						standard_error(array('stringisempty','mydocumentroot'));
					}
					elseif($customerid==0)
					{
						standard_error('adduserfirst');
					}
					elseif($domain_check['domain'] == $domain)
					{
						standard_error('domainalreadyexists',$domain);
					}
					elseif($aliasdomain_check['id']!=$aliasdomain)
					{
						standard_error('domainisaliasorothercustomer');
					}

					else
					{
						if( ($openbasedir == '0' || $safemode == '0') 
						    && (!isset($_POST['reallydoit']) 
						       || $_POST['reallydoit'] != 'reallydoit') )
						{
							ask_yesno('admin_domain_reallydisablesecuritysetting', $config->get('env.filename'), "page=".$config->get('env.page').";action=".$config->get('env.action').";domain=$domain;documentroot=$documentroot;customerid=$customerid;alias=$aliasdomain;isbinddomain=$isbinddomain;isemaildomain=$isemaildomain;subcanemaildomain=$subcanemaildomain;caneditdomain=$caneditdomain;zonefile=$zonefile;speciallogfile=$speciallogfile;openbasedir=$openbasedir;ipandport=$ipandport;safemode=$safemode;specialsettings=".urlencode($specialsettings).";reallydoit=reallydoit");
							exit;
						}
						$pattern = sprintf('^%s', $customer['documentroot']);
						if( !ereg($pattern,$documentroot) 
						    && ( !isset($_POST['reallydocroot'] ) 
						       || $_POST['reallydocroot'] != 'reallydocroot') )
						{
							$params = "page=".$config->get('env.page').";action=".$config->get('env.action').";domain=$domain;documentroot=$documentroot;customerid=$customerid;alias=$aliasdomain;isbinddomain=$isbinddomain;isemaildomain=$isemaildomain;subcanemaildomain=$subcanemaildomain;caneditdomain=$caneditdomain;zonefile=$zonefile;speciallogfile=$speciallogfile;openbasedir=$openbasedir;ipandport=$ipandport;safemode=$safemode;specialsettings=".urlencode($specialsettings).';reallydocroot=reallydocroot';
							if ( isset($_POST['reallydoit']) )
							{
								$params .= ';reallydoit=reallydoit';
							}
							ask_yesno('admin_domain_reallydocrootoutofcustomerroot', $config->get('env.filename'), $params );
							exit;
						  	
						}
						if( (isset($_POST['reallydoit']) && $_POST['reallydoit'] == 'reallydoit') 
						   || (isset( $_POST['reallydocroot']) && $_POST['reallydocroot'] == 'reallydocroot' ) )
						{
							$specialsettings = urldecode($specialsettings);
						}

						$specialsettings = addslashes($specialsettings);
						$db->query("INSERT INTO `".TABLE_PANEL_DOMAINS."` (`domain`, `customerid`, `adminid`, `documentroot`, `ipandport`, `aliasdomain`, `zonefile`, `isbinddomain`, `isemaildomain`, `subcanemaildomain`, `caneditdomain`, `openbasedir`, `safemode`, `speciallogfile`, `specialsettings`) VALUES ('$domain', '$customerid', '{$userinfo['adminid']}', '$documentroot', '$ipandport', ".(($aliasdomain!=0) ? '\''.$aliasdomain.'\'' : 'NULL').", '$zonefile', '$isbinddomain', '$isemaildomain', '$subcanemaildomain', '$caneditdomain', '$openbasedir', '$safemode', '$speciallogfile', '$specialsettings')");
						$domainid=$db->insert_id();
						$db->query("UPDATE `".TABLE_PANEL_ADMINS."` SET `domains_used` = `domains_used` + 1 WHERE `adminid` = '{$userinfo['adminid']}'");

						$hooks->call( 'core.createDomain', 
						              array( 'id' => $domainid ) );

    					redirectTo ( $config->get('env.filename') , Array ( 'page' => $config->get('env.page') , 's' => $config->get('env.s') ) ) ;
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
					$ipsandports='';
					$result_ipsandports=$db->query("SELECT `id`, `ip`, `port` FROM `".TABLE_PANEL_IPSANDPORTS."` ORDER BY `ip` ASC");
					$result_ipsandports_default=$db->query_first("SELECT `id` FROM `".TABLE_PANEL_IPSANDPORTS."` WHERE `default` = '1'");
					while($row_ipandport=$db->fetch_array($result_ipsandports))
					{
						$ipsandports.=makeoption($row_ipandport['ip'].':'.$row_ipandport['port'],$row_ipandport['id'],$result_ipsandports_default['id']);
					}
					$standardsubdomains=array();
					$result_standardsubdomains=$db->query('SELECT `id` FROM `'.TABLE_PANEL_DOMAINS.'` `d`, `'.TABLE_PANEL_CUSTOMERS.'` `c` WHERE `d`.`id`=`c`.`standardsubdomain`');
					while($row_standardsubdomain=$db->fetch_array($result_standardsubdomains))
					{
						$standardsubdomains[]=$row_standardsubdomain['id'];
					}
					if(count($standardsubdomains)>0)
					{
						$standardsubdomains='AND `d`.`id` NOT IN ('.join(',',$standardsubdomains).') ';
					}
					else
					{
						$standardsubdomains='';
					}
					$domains=makeoption($lng['domains']['noaliasdomain'],0);
					$result_domains=$db->query("SELECT `d`.`id`, `d`.`domain`, `c`.`loginname` FROM `".TABLE_PANEL_DOMAINS."` `d`, `".TABLE_PANEL_CUSTOMERS."` `c` WHERE `d`.`aliasdomain` IS NULL AND `d`.`parentdomainid`=0 ".$standardsubdomains.( $userinfo['customers_see_all'] ? '' : "AND `d`.`adminid` = '{$userinfo['adminid']}' ")."AND `d`.`customerid`=`c`.`customerid` ORDER BY `loginname`, `domain` ASC");
					while($row_domain=$db->fetch_array($result_domains))
					{
						$domains.=makeoption($idna->decode($row_domain['domain']).' ('.$row_domain['loginname'].')',$row_domain['id']);
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

		elseif($config->get('env.action')=='edit' && $config->get('env.id')!=0)
		{
			$result=$db->query_first(
				"SELECT `d`.`id`, `d`.`domain`, `d`.`customerid`, `d`.`documentroot`, `d`.`ipandport`, `d`.`aliasdomain`, `d`.`isbinddomain`, `d`.`isemaildomain`, `d`.`subcanemaildomain`, `d`.`caneditdomain`, `d`.`zonefile`, `d`.`openbasedir`, `d`.`safemode`, `d`.`speciallogfile`, `d`.`specialsettings`, `c`.`loginname`, `c`.`name`, `c`.`firstname` " .
				"FROM `".TABLE_PANEL_DOMAINS."` `d` " .
				"LEFT JOIN `".TABLE_PANEL_CUSTOMERS."` `c` USING(`customerid`) " .
				"WHERE `d`.`parentdomainid`='0' AND `d`.`id`='".$config->get('env.id')."'".( $userinfo['customers_see_all'] ? '' : " AND `d`.`adminid` = '{$userinfo['adminid']}' ")
			);
			$alias_check=$db->query_first('SELECT COUNT(`id`) AS count FROM `'.TABLE_PANEL_DOMAINS.'` WHERE `aliasdomain`=\''.$result['id'].'\'');
			$alias_check=$alias_check['count'];
			if($result['domain']!='')
			{
				if(isset($_POST['send']) && $_POST['send']=='send')
				{
					$customer=$db->query_first("SELECT `documentroot` FROM ".TABLE_PANEL_CUSTOMERS." WHERE `customerid`='".$result['customerid']."'");

					$aliasdomain = intval($_POST['alias']);
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
						$ipandport = intval($_POST['ipandport']);

						$documentroot = addslashes($_POST['documentroot']);
						if($documentroot=='')
						{
							$documentroot=$customer['documentroot'];
						}
					}
					else
					{
						$isbinddomain = $result['isbinddomain'];
						$zonefile = $result['zonefile'];
						$openbasedir = $result['openbasedir'];
						$safemode = $result['safemode'];
						$specialsettings = $result['specialsettings'];
						$ipandport = $result['ipandport'];
						$documentroot = $result['documentroot'];
					}
					if(!preg_match('/^https?\:\/\//', $documentroot))
					{
						$documentroot = makeCorrectDir($documentroot);
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
					
					$aliasdomain_check=array('id' => 0);
					if($aliasdomain!=0)
					{
						$aliasdomain_check = $db->query_first('SELECT `id` FROM `'.TABLE_PANEL_DOMAINS.'` `d`,`'.TABLE_PANEL_CUSTOMERS.'` `c` WHERE `d`.`customerid`=\''.$result['customerid'].'\' AND `d`.`aliasdomain` IS NULL AND `d`.`id`<>`c`.`standardsubdomain` AND `c`.`customerid`=\''.$result['customerid'].'\' AND `d`.`id`=\''.$aliasdomain.'\'');
					}
					if($aliasdomain_check['id']!=$aliasdomain)
					{
						standard_error('domainisaliasorothercustomer');
					}
					
					if(($openbasedir == '0' || $safemode == '0') && (!isset($_POST['reallydoit']) || $_POST['reallydoit'] != 'reallydoit') && $userinfo['change_serversettings'] == '1')
					{
						ask_yesno('admin_domain_reallydisablesecuritysetting', $config->get('env.filename'), "id=".$config->get('env.id').";page=".$config->get('env.page').";action=".$config->get('env.action').";documentroot=$documentroot;alias=$aliasdomain;isbinddomain=$isbinddomain;isemaildomain=$isemaildomain;subcanemaildomain=$subcanemaildomain;caneditdomain=$caneditdomain;zonefile=$zonefile;openbasedir=$openbasedir;ipandport=$ipandport;safemode=$safemode;specialsettings=".urlencode($specialsettings).";reallydoit=reallydoit");
						exit;
					}
					$pattern = sprintf('^%s', $customer['documentroot']);
					if( !ereg($pattern,$documentroot) 
					    && ( !isset($_POST['reallydocroot'] ) 
					       || $_POST['reallydocroot'] != 'reallydocroot') )
					{
						$params = "id=".$config->get('env.id').";page=".$config->get('env.page').";action=".$config->get('env.action').";documentroot=$documentroot;alias=$aliasdomain;isbinddomain=$isbinddomain;isemaildomain=$isemaildomain;subcanemaildomain=$subcanemaildomain;caneditdomain=$caneditdomain;zonefile=$zonefile;openbasedir=$openbasedir;ipandport=$ipandport;safemode=$safemode;specialsettings=".urlencode($specialsettings).';reallydocroot=reallydocroot';
						if ( isset($_POST['reallydoit']) )
						{
							$params .= ';reallydoit=reallydoit';
						}
						ask_yesno('admin_domain_reallydocrootoutofcustomerroot', $config->get('env.filename'), $params );
						exit;
					  	
					}

					if( (isset($_POST['reallydoit']) && $_POST['reallydoit'] == 'reallydoit') 
					   || (isset( $_POST['reallydocroot']) && $_POST['reallydocroot'] == 'reallydocroot' ) )
					{
						$specialsettings = urldecode($specialsettings);
					}

					if(    $documentroot != $result['documentroot'] 
					    || $ipandport != $result['ipandport'] 
					    || $openbasedir != $result['openbasedir'] 
					    || $safemode != $result['safemode'] 
					    || $specialsettings != $result['specialsettings'])
					{
						$hooks->call( 'core.updateDomain',
						              array( 'id' => $config->get('env.id') ) );
					}
					if($isbinddomain != $result['isbinddomain'] || $zonefile != $result['zonefile'] || $ipandport != $result['ipandport'])
					{
						$hooks->call( 'core.updateDomain',
						              array( 'id' => $config->get('env.id') ) );
					}
					if($isemaildomain == '0' && $result['isemaildomain'] == '1')
					{
						$db->query("DELETE FROM `".TABLE_MAIL_USERS."` WHERE `domainid`='".$config->get('env.id')."' ");
						$db->query("DELETE FROM `".TABLE_MAIL_VIRTUAL."` WHERE `domainid`='".$config->get('env.id')."' ");
					}

					$specialsettings = addslashes($specialsettings);
					$result=$db->query("UPDATE `".TABLE_PANEL_DOMAINS."` SET `documentroot`='$documentroot', `ipandport`='$ipandport', `aliasdomain`=".(($aliasdomain!=0 && $alias_check==0) ? '\''.$aliasdomain.'\'' : 'NULL').", `isbinddomain`='$isbinddomain', `isemaildomain`='$isemaildomain', `subcanemaildomain`='$subcanemaildomain', `caneditdomain`='$caneditdomain', `zonefile`='$zonefile', `openbasedir`='$openbasedir', `safemode`='$safemode', `specialsettings`='$specialsettings' WHERE `id`='".$config->get('env.id')."'");
					$result=$db->query("UPDATE `".TABLE_PANEL_DOMAINS."` SET `ipandport`='$ipandport', `openbasedir`='$openbasedir', `safemode`='$safemode', `specialsettings`='$specialsettings'  WHERE `parentdomainid`='".$config->get('env.id')."'");
	
					redirectTo ( $config->get('env.filename') , Array ( 'page' => $config->get('env.page') , 's' => $config->get('env.s') ) ) ;
				}
				else
				{
					$result['domain'] = $idna->decode($result['domain']);
					$domains=makeoption($lng['domains']['noaliasdomain'],0,$result['aliasdomain']);
					$result_domains=$db->query("SELECT `d`.`id`, `d`.`domain` FROM `".TABLE_PANEL_DOMAINS."` `d`, `".TABLE_PANEL_CUSTOMERS."` `c` WHERE `d`.`aliasdomain` IS NULL AND `d`.`parentdomainid`=0 AND `d`.`id`<>'".$result['id']."' AND `c`.`standardsubdomain`<>`d`.`id` AND `d`.`customerid`='".$result['customerid']."' AND `c`.`customerid`=`d`.`customerid` ORDER BY `d`.`domain` ASC");
					while($row_domain=$db->fetch_array($result_domains))
					{
						$domains.=makeoption($idna->decode($row_domain['domain']),$row_domain['id'],$result['aliasdomain']);
					}
					$ipsandports='';
					$result_ipsandports=$db->query("SELECT `id`, `ip`, `port` FROM `".TABLE_PANEL_IPSANDPORTS."` ORDER BY `ip` ASC");
					while($row_ipandport=$db->fetch_array($result_ipsandports))
					{
						$ipsandports.=makeoption($row_ipandport['ip'].':'.$row_ipandport['port'],$row_ipandport['id'],$result['ipandport']);
					}
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

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

if(isset($_POST['id']))
{
	$id = intval($_POST['id']);
}
elseif(isset($_GET['id']))
{
	$id = intval($_GET['id']);
}

if($page == 'domains'
   || $page == 'overview')
{
	if($action == '')
	{
		$log->logAction(ADM_ACTION, LOG_NOTICE, "viewed admin_domains");
		$fields = array(
			'd.domain' => $lng['domains']['domainname'],
			'ip.ip' => $lng['admin']['ipsandports']['ip'],
			'ip.port' => $lng['admin']['ipsandports']['port'],
			'c.name' => $lng['customer']['name'],
			'c.firstname' => $lng['customer']['firstname'],
			'c.company' => $lng['customer']['company'],
			'c.loginname' => $lng['login']['username'],
			'd.aliasdomain' => $lng['domains']['aliasdomain']
		);
		$paging = new paging($userinfo, $db, TABLE_PANEL_DOMAINS, $fields, $settings['panel']['paging'], $settings['panel']['natsorting']);
		$domains = '';
		$result = $db->query("SELECT `d`.`id`, `d`.`domain`, `d`.`customerid`, `d`.`documentroot`, CONCAT(`ip`.`ip`,':',`ip`.`port`) AS `ipandport`, `d`.`zonefile`, `d`.`openbasedir`, `d`.`safemode`, `d`.`isemaildomain`, `d`.`parentdomainid`, `c`.`loginname`, `c`.`name`, `c`.`firstname`, `c`.`company`, `c`.`standardsubdomain`, `ad`.`id` AS `aliasdomainid`, `ad`.`domain` AS `aliasdomain`, `da`.`id` AS `domainaliasid`, `da`.`domain` AS `domainalias`, `ip`.`id` AS `ipid`, `ip`.`ip`, `ip`.`port` " . "FROM `" . TABLE_PANEL_DOMAINS . "` `d` " . "LEFT JOIN `" . TABLE_PANEL_CUSTOMERS . "` `c` USING(`customerid`) " . "LEFT JOIN `" . TABLE_PANEL_DOMAINS . "` `ad` ON `d`.`aliasdomain`=`ad`.`id` " . "LEFT JOIN `" . TABLE_PANEL_DOMAINS . "` `da` ON `da`.`aliasdomain`=`d`.`id` " . "LEFT JOIN `" . TABLE_PANEL_IPSANDPORTS . "` `ip` ON (`d`.`ipandport` = `ip`.`id`) " . "WHERE `d`.`parentdomainid`='0' " . ($userinfo['customers_see_all'] ? '' : " AND `d`.`adminid` = '" . (int)$userinfo['adminid'] . "' ") . " " . $paging->getSqlWhere(true) . " " . $paging->getSqlOrderBy() . " " . $paging->getSqlLimit());
		$paging->setEntries($db->num_rows($result));
		$sortcode = $paging->getHtmlSortCode($lng);
		$arrowcode = $paging->getHtmlArrowCode($filename . '?page=' . $page . '&s=' . $s);
		$searchcode = $paging->getHtmlSearchCode($lng);
		$pagingcode = $paging->getHtmlPagingCode($filename . '?page=' . $page . '&s=' . $s);
		$domain_array = array();

		while($row = $db->fetch_array($result))
		{
			$row['domain'] = $idna_convert->decode($row['domain']);
			$row['aliasdomain'] = $idna_convert->decode($row['aliasdomain']);
			$row['domainalias'] = $idna_convert->decode($row['domainalias']);
			$domain_array[$row['domain']] = $row;
		}

		/**
		 * We need ksort/krsort here to make sure idna-domains are also sorted correctly
		 */

		if($paging->sortfield == 'd.domain'
		   && $paging->sortorder == 'asc')
		{
			ksort($domain_array);
		}
		elseif($paging->sortfield == 'd.domain'
		       && $paging->sortorder == 'desc')
		{
			krsort($domain_array);
		}

		$i = 0;
		$count = 0;
		foreach($domain_array as $row)
		{
			if($paging->checkDisplay($i))
			{
				$row = htmlentities_array($row);
				eval("\$domains.=\"" . getTemplate("domains/domains_domain") . "\";");
				$count++;
			}

			$i++;
		}

		// Let's see how many customers we have

		$customers = $db->query("SELECT `customerid` FROM " . TABLE_PANEL_CUSTOMERS);
		$countcustomers = $db->num_rows($customers);
		unset($customers);

		// Display the list

		eval("echo \"" . getTemplate("domains/domains") . "\";");
	}
	elseif($action == 'delete'
	       && $id != 0)
	{
		/*
		$result = $db->query_first("SELECT `d`.`id`, `d`.`domain`, `d`.`customerid`, `d`.`documentroot`, `d`.`isemaildomain`, `d`.`zonefile` FROM `" . TABLE_PANEL_DOMAINS . "` `d`, `" . TABLE_PANEL_CUSTOMERS . "` `c` WHERE `d`.`id`='" . (int)$id . "' AND `d`.`id` <> `c`.`standardsubdomain`" . ($userinfo['customers_see_all'] ? '' : " AND `d`.`adminid` = '" . (int)$userinfo['adminid'] . "' "));
		*/

		$result = $db->query_first("SELECT `id`, `customerid`, `domain` FROM `" . TABLE_PANEL_DOMAINS . "` WHERE `id`='" . $id . "'");
		$alias_check = $db->query_first('SELECT COUNT(`id`) AS `count` FROM `' . TABLE_PANEL_DOMAINS . '` WHERE `aliasdomain`=\'' . (int)$id . '\'');

		if($result['domain'] != ''
		   && $alias_check['count'] == 0)
		{
			if(isset($_POST['send'])
			   && $_POST['send'] == 'send')
			{
				$customerid = $db->query_first('SELECT `customerid` FROM `' . TABLE_PANEL_DOMAINS . '` WHERE `id`="' . $_POST['id'] . '"');
				$query = 'SELECT `id` FROM `' . TABLE_PANEL_DOMAINS . '` WHERE (`id`="' . (int)$id . '" OR `parentdomainid`="' . (int)$id . '")  AND  `isemaildomain`="1"';
				$subResult = $db->query($query);
				$idString = array();

				while($subRow = $db->fetch_array($subResult))
				{
					$idString[] = '`domainid` = "' . (int)$subRow['id'] . '"';
				}

				$idString = implode(' OR ', $idString);

				if($idString != '')
				{
					$query = 'DELETE FROM `' . TABLE_MAIL_USERS . '` WHERE ' . $idString;
					$db->query($query);
					$query = 'DELETE FROM `' . TABLE_MAIL_VIRTUAL . '` WHERE ' . $idString;
					$db->query($query);
					$log->logAction(ADM_ACTION, LOG_NOTICE, "deleted domain/s from mail-tables");
				}

				$db->query("DELETE FROM `" . TABLE_PANEL_DOMAINS . "` WHERE `id`='" . (int)$id . "' OR `parentdomainid`='" . (int)$result['id'] . "'");
				$deleted_domains = $db->affected_rows();
				$db->query("UPDATE `" . TABLE_PANEL_CUSTOMERS . "` SET `subdomains_used` = `subdomains_used` - 0" . ($deleted_domains-1) . " WHERE `customerid` = '" . (int)$result['customerid'] . "'");
				$db->query("UPDATE `" . TABLE_PANEL_ADMINS . "` SET `domains_used` = `domains_used` - 1 WHERE `adminid` = '" . (int)$userinfo['adminid'] . "'");
				$db->query('UPDATE `' . TABLE_PANEL_CUSTOMERS . '` SET `standardsubdomain`=\'0\' WHERE `customerid`=\'' . (int)$customerid['customerid'] . '\'');
				if($settings['system']['userdns'] == '1')
				{
					$db->query("DELETE FROM `" . TABLE_PANEL_DNSENTRY . "` WHERE `domainid`='" . (int)$id . "'");
				}
				$log->logAction(ADM_ACTION, LOG_INFO, "deleted domain/subdomains (#" . $result['id'] . ")");
				updateCounters();
				inserttask('1');
				inserttask('4');
				redirectTo($filename, Array(
					'page' => $page,
					's' => $s
				));
			}
			else
			{
				ask_yesno('admin_domain_reallydelete', $filename, array(
					'id' => $id,
					'page' => $page,
					'action' => $action
				), $idna_convert->decode($result['domain']));
			}

			$db->query('UPDATE `' . TABLE_PANEL_CUSTOMERS . '` SET `standardsubdomain`=\'0\' WHERE `customerid`=\'' . (int)$customerid['customerid'] . '\'');
		}
	}
	elseif($action == 'add')
	{
		if($userinfo['domains_used'] < $userinfo['domains']
		   || $userinfo['domains'] == '-1')
		{
			if(isset($_POST['send'])
			   && $_POST['send'] == 'send')
			{
				if($_POST['domain'] == $settings['system']['hostname']
				   && empty($_POST['reallydoit']))
				{
					ask_yesno('admin_domain_reallyenablemailsystemhostname', $filename, array(
						'page' => $page,
						'action' => $action,
						'domain' => $domain,
						'documentroot' => $documentroot,
						'customerid' => $customerid,
						'alias' => $aliasdomain,
						'isbinddomain' => $isbinddomain,
						'isemaildomain' => $isemaildomain,
						'subcanemaildomain' => $subcanemaildomain,
						'caneditdomain' => $caneditdomain,
						'zonefile' => $zonefile,
						'dkim' => $dkim,
						'speciallogfile' => $speciallogfile,
						'openbasedir' => $openbasedir,
						'ipandport' => $ipandport,
						'safemode' => $safemode,
						'specialsettings' => $specialsettings,
						'reallydoit' => 'reallydoit'
					));
					exit;
				}

				$domain = $idna_convert->encode(preg_replace(Array(
					'/\:(\d)+$/',
					'/^https?\:\/\//'
				), '', validate($_POST['domain'], 'domain')));
				$customerid = intval($_POST['customerid']);
				$subcanemaildomain = intval($_POST['subcanemaildomain']);
				$isemaildomain = intval($_POST['isemaildomain']);
				$aliasdomain = intval($_POST['alias']);
				$customer = $db->query_first("SELECT `documentroot` FROM `" . TABLE_PANEL_CUSTOMERS . "` WHERE `customerid`='" . (int)$customerid . "'");
				$documentroot = $customer['documentroot'];

				if($userinfo['change_serversettings'] == '1')
				{
					$isbinddomain = intval($_POST['isbinddomain']);
					$caneditdomain = intval($_POST['caneditdomain']);
					$zonefile = validate($_POST['zonefile'], 'zonefile');
					if(isset($_POST['dkim']))
					{
						$dkim = intval($_POST['dkim']);
					}
					else
					{
						$dkim = '1';
					}
					$wwwserveralias = intval($_POST['wwwserveralias']);
					$openbasedir = intval($_POST['openbasedir']);
					$safemode = intval($_POST['safemode']);
					$speciallogfile = intval($_POST['speciallogfile']);
					$specialsettings = validate(str_replace("\r\n", "\n", $_POST['specialsettings']), 'specialsettings', '/^[^\0]*$/');
					$ipandport = intval($_POST['ipandport']);
					$ipandport_check = $db->query_first("SELECT `id`, `ip`, `port` FROM `" . TABLE_PANEL_IPSANDPORTS . "` WHERE `id` = '" . (int)$ipandport . "'");

					if(!isset($ipandport_check['id'])
					   || $ipandport_check['id'] == '0')
					{
						$ipandport = $settings['system']['defaultip'];
					}

					validate($_POST['documentroot'], 'documentroot');

					if(isset($_POST['documentroot'])
					   && $_POST['documentroot'] != '')
					{
						if(substr($_POST['documentroot'], 0, 1) != '/'
						   && !preg_match('/^https?\:\/\//', $_POST['documentroot']))
						{
							$documentroot.= '/' . $_POST['documentroot'];
						}
						else
						{
							$documentroot = $_POST['documentroot'];
						}
					}
					
					if($settings['system']['userdns'] == '1')
					{
						$dns_destinationipv4 = '';
						$dns_destinationipv6 = '';
						$dns_destinationcname = '';
						$dns_mx10 = '';
						$dns_mx20 = '';
						$dns_txt = '';
						
						if(isset($_POST['dns_destip_type']))
						{
							$dns_destip_type = (int)$_POST['dns_destip_type'];
						}
						else
						{
							$dns_destip_type = 0;
						}
						$dns_destmx_type = ($_POST['dns_destmx_type'] == '1' ? '1' : '0');
						
						if($dns_destip_type == 1)
						{
							if(isset($_POST['dns_destinationipv4'])
							   && $_POST['dns_destinationipv4'] != '')
							{
								$dns_destinationipv4 = validate_ip($_POST['dns_destinationipv4']);
							}
							else
							{
								$dns_destinationipv4 = $settings['system']['ipaddress'];
							}
							if(isset($_POST['dns_destinationipv6'])
							   && $_POST['dns_destinationipv6'] != '')
							{
								$dns_destinationipv6 = validate_ip($_POST['dns_destinationipv6']);
							}
							else
							{
								$dns_destinationipv6 = '';
							}
						}
						elseif($dns_destip_type == 2)
						{
							$dns_destinationcname = validateDomain($_POST['dns_destinationcname']);
						}
						else
						{
							$dns_destinationipv4 = $settings['system']['ipaddress'];
						}
						
						if($dns_destmx_type == '1')
						{
							if(isset($_POST['dns_mxentry10'])
							   && $_POST['dns_mxentry10'] != '')
							{
								$dns_mx10 = validateDomain($_POST['dns_mxentry10']);
							}
							else
							{
								if($settings['system']['maxservers'] != '')
								{
									$mxsrvs = explode(',', $settings['system']['maxservers']);
									$dns_mx10 = $mxsrvs[0];
								}
								else
								{
									$dns_mx10 = $settings['system']['hostname'];
								}
							}
							if(isset($_POST['dns_mxentry20'])
							   && $_POST['dns_mxentry20'] != '')
							{
								$dns_mx20 = validateDomain($_POST['dns_mxentry20']);
							}
							else
							{
								if($settings['system']['maxservers'] != '')
								{
									$mxsrvs = explode(',', $settings['system']['maxservers']);
									if(isset($mxsrvs[1])
									   && $mxsrvs[1] != '')
									{
										$dns_mx20 = $mxsrvs[1];
									}
									else
									{
										$dns_mx20 = $mxsrvs[0];
									}
								}
								else
								{
									$dns_mx20 = $settings['system']['hostname'];
								}
							}
						}
						else
						{
							$dns_mx10 = $settings['system']['hostname'];
							$dns_mx20 = $settings['system']['hostname'];
						}
						
						if(isset($_POST['dns_txtrecords'])
						   && $_POST['dns_txtrecords'] != '')
						{
							$dns_txt = validate($_POST['dns_txtrecords'], 'dns txt entry');
						}
						else
						{
							$dns_txt = '';
						}
					}
				}
				elseif($userinfo['caneditphpsettings'] == '1')
				{
					$isbinddomain = '1';
					$caneditdomain = '1';
					$zonefile = '';
					$dkim = '1';
					$openbasedir = intval($_POST['openbasedir']);
					$safemode = intval($_POST['safemode']);
					$speciallogfile = '1';
					$specialsettings = '';
					$wwwserveralias = '1';
					$ipandport = $settings['system']['defaultip'];
					if($settings['system']['userdns'] == '1')
					{
						$dns_destinationipv4 = $settings['system']['ipaddress'];
						$dns_destinationipv6 = '';
						$dns_destinationcname = '';
						$dns_mx10 = $settings['system']['hostname'];
						$dns_mx20 = $settings['system']['hostname'];
						$dns_txt = '';
					}
				}
				else
				{
					$isbinddomain = '1';
					$caneditdomain = '1';
					$zonefile = '';
					$dkim = '1';
					$openbasedir = '1';
					$safemode = '1';
					$speciallogfile = '1';
					$specialsettings = '';
					$wwwserveralias = '1';
					$ipandport = $settings['system']['defaultip'];
					if($settings['system']['userdns'] == '1')
					{
						$dns_destinationipv4 = $settings['system']['ipaddress'];
						$dns_destinationipv6 = '';
						$dns_destinationcname = '';
						$dns_mx10 = $settings['system']['hostname'];
						$dns_mx20 = $settings['system']['hostname'];
						$dns_txt = '';
					}
				}

				if(!preg_match('/^https?\:\/\//', $documentroot))
				{
					$documentroot = makeCorrectDir($documentroot);
				}

				$domain_check = $db->query_first("SELECT `id`, `domain` FROM `" . TABLE_PANEL_DOMAINS . "` WHERE `domain` = '" . $db->escape(strtolower($domain)) . "'");
				$aliasdomain_check = array(
					'id' => 0
				);

				if($aliasdomain != 0)
				{
					$aliasdomain_check = $db->query_first('SELECT `id` FROM `' . TABLE_PANEL_DOMAINS . '` `d`,`' . TABLE_PANEL_CUSTOMERS . '` `c` WHERE `d`.`customerid`=\'' . (int)$customerid . '\' AND `d`.`aliasdomain` IS NULL AND `d`.`id`<>`c`.`standardsubdomain` AND `c`.`customerid`=\'' . (int)$customerid . '\' AND `d`.`id`=\'' . (int)$aliasdomain . '\'');
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

				if($subcanemaildomain != '1'
				   && $subcanemaildomain != '2'
				   && $subcanemaildomain != '3')
				{
					$subcanemaildomain = '0';
				}

				if($dkim != '1')
				{
					$dkim = '0';
				}

				if($caneditdomain != '1')
				{
					$caneditdomain = '0';
				}

				if($settings['system']['use_ssl'] == "1"
				   && isset($_POST['ssl_ipandport']))
				{
					$ssl = ($_POST['ssl'] == '1' ? '1' : '0');
					$ssl_redirect = ($_POST['ssl_redirect'] == '1' ? '1' : '0');
					$ssl_ipandport = (int)$_POST['ssl_ipandport'];
				}
				else
				{
					$ssl = 0;
					$ssl_redirect = 0;
					$ssl_ipandport = 0;
				}
				
				if($domain == '')
				{
					standard_error(array(
						'stringisempty',
						'mydomain'
					));
				}
				elseif(!validateDomain($domain))
				{
					standard_error(array(
						'stringiswrong',
						'mydomain'
					));
				}
				elseif($documentroot == '')
				{
					standard_error(array(
						'stringisempty',
						'mydocumentroot'
					));
				}
				elseif($customerid == 0)
				{
					standard_error('adduserfirst');
				}
				elseif(strtolower($domain_check['domain']) == strtolower($domain))
				{
					standard_error('domainalreadyexists', $idna_convert->decode($domain));
				}
				elseif($aliasdomain_check['id'] != $aliasdomain)
				{
					standard_error('domainisaliasorothercustomer');
				}
				else
				{
					if(($openbasedir == '0' || $safemode == '0')
					   && (!isset($_POST['reallydoit']) || $_POST['reallydoit'] != 'reallydoit'))
					{
						ask_yesno('admin_domain_reallydisablesecuritysetting', $filename, array(
							'page' => $page,
							'action' => $action,
							'domain' => $domain,
							'documentroot' => $documentroot,
							'customerid' => $customerid,
							'alias' => $aliasdomain,
							'isbinddomain' => $isbinddomain,
							'isemaildomain' => $isemaildomain,
							'subcanemaildomain' => $subcanemaildomain,
							'caneditdomain' => $caneditdomain,
							'zonefile' => $zonefile,
							'dkim' => $dkim,
							'speciallogfile' => $speciallogfile,
							'openbasedir' => $openbasedir,
							'ipandport' => $ipandport,
							'ssl' => $ssl,
							'ssl_redirect' => $ssl_redirect,
							'ssl_ipandport' => $ssl_ipandport,
							'safemode' => $safemode,
							'specialsettings' => $specialsettings,
							'reallydoit' => 'reallydoit'
						));
						exit;
					}

					if(strpos($documentroot, $customer['documentroot']) !== 0
					   && (!isset($_POST['reallydocroot']) || $_POST['reallydocroot'] != 'reallydocroot')
					   && !preg_match('/^https?\:\/\//', $documentroot))
					{
						$params = array(
							'page' => $page,
							'action' => $action,
							'domain' => $domain,
							'documentroot' => $documentroot,
							'customerid' => $customerid,
							'alias' => $aliasdomain,
							'isbinddomain' => $isbinddomain,
							'isemaildomain' => $isemaildomain,
							'subcanemaildomain' => $subcanemaildomain,
							'caneditdomain' => $caneditdomain,
							'zonefile' => $zonefile,
							'dkim' => $dkim,
							'speciallogfile' => $speciallogfile,
							'openbasedir' => $openbasedir,
							'ipandport' => $ipandport,
							'ssl' => $ssl,
							'ssl_redirect' => $ssl_redirect,
							'ssl_ipandport' => $ssl_ipandport,
							'safemode' => $safemode,
							'specialsettings' => $specialsettings,
							'reallydocroot' => 'reallydocroot'
						);

						if(isset($_POST['reallydoit']))
						{
							$params['reallydoit'] = 'reallydoit';
						}

						ask_yesno('admin_domain_reallydocrootoutofcustomerroot', $filename, $params);
						exit;
					}

					if($_POST['isemail_only'] == '1')
					{
						$isemail_only = "1";
						$isemaildomain = "1";
						$isbinddomain = "1";
						$subcanemaildomain = "0";
						$caneditdomain = "0";
					}

					$db->query("INSERT INTO `" . TABLE_PANEL_DOMAINS . "` (`domain`, `customerid`, `adminid`, `documentroot`, `ipandport`,`aliasdomain`, `zonefile`, `dkim`, `wwwserveralias`, `isbinddomain`, `isemaildomain`, `email_only`, `subcanemaildomain`, `caneditdomain`, `openbasedir`, `safemode`,`speciallogfile`, `specialsettings`, `ssl`, `ssl_redirect`, `ssl_ipandport`)VALUES ('" . $db->escape($domain) . "', '" . (int)$customerid . "', '" . (int)$userinfo['adminid'] . "', '" . $db->escape($documentroot) . "', '" . $db->escape($ipandport) . "', " . (($aliasdomain != 0) ? '\'' . $db->escape($aliasdomain) . '\'' : 'NULL') . ", '" . $db->escape($zonefile) . "', '" . $db->escape($dkim) . "', '" . $db->escape($wwwserveralias) . "', '" . $db->escape($isbinddomain) . "', '" . $db->escape($isemaildomain) . "', '" . $db->escape($isemail_only) . "', '" . $db->escape($subcanemaildomain) . "', '" . $db->escape($caneditdomain) . "', '" . $db->escape($openbasedir) . "', '" . $db->escape($safemode) . "', '" . $db->escape($speciallogfile) . "', '" . $db->escape($specialsettings) . "', '" . $ssl . "', '" . $ssl_redirect . "' , '" . $ssl_ipandport . "')");
					$domainid = $db->insert_id();
					$db->query("INSERT INTO `" . TABLE_PANEL_DNSENTRY . "` (`domainid`, `customerid`, `adminid`, `ipv4`, `ipv6`, `cname`, `mx10`, `mx20`, `txt`) VALUES ('" . (int)$domainid . "', '" . (int)$customerid . "', '" . (int)$userinfo['adminid'] . "', '" . $db->escape($dns_destinationipv4) . "', '" . $db->escape($dns_destinationipv6) . "', '" . $db->escape($dns_destinationcname) . "', '" . $db->escape($dns_mx10) . "', '" . $db->escape($dns_mx20) . "', '" . $db->escape($dns_txt) . "')");
					$db->query("UPDATE `" . TABLE_PANEL_ADMINS . "` SET `domains_used` = `domains_used` + 1 WHERE `adminid` = '" . (int)$userinfo['adminid'] . "'");
					$log->logAction(ADM_ACTION, LOG_INFO, "added domain '" . $domain . "'");
					inserttask('1');
					inserttask('4');
					redirectTo($filename, Array(
						'page' => $page,
						's' => $s
					));
				}
			}
			else
			{
				$customers = '';
				$result_customers = $db->query("SELECT `customerid`, `loginname`, `name`, `firstname`, `company` FROM `" . TABLE_PANEL_CUSTOMERS . "` " . ($userinfo['customers_see_all'] ? '' : " WHERE `adminid` = '" . (int)$userinfo['adminid'] . "' ") . " ORDER BY `name` ASC");

				while($row_customer = $db->fetch_array($result_customers))
				{
					if($row_customer['company'] == '')
					{
						$customers.= makeoption($row_customer['name'] . ', ' . $row_customer['firstname'] . ' (' . $row_customer['loginname'] . ')', $row_customer['customerid']);
					}
					else
					{
						if($row_customer['name'] != ''
						   && $row_customer['firstname'] != '')
						{
							$customers.= makeoption($row_customer['name'] . ', ' . $row_customer['firstname'] . ' | ' . $row_customer['company'] . ' (' . $row_customer['loginname'] . ')', $row_customer['customerid']);
						}
						else
						{
							$customers.= makeoption($row_customer['company'] . ' (' . $row_customer['loginname'] . ')', $row_customer['customerid']);
						}
					}
				}

				if($userinfo['ip'] == "-1")
				{
					$result_ipsandports = $db->query("SELECT `id`, `ip`, `port` FROM `" . TABLE_PANEL_IPSANDPORTS . "` WHERE `ssl`='0' ORDER BY `ip` ASC");
					$result_ssl_ipsandports = $db->query("SELECT `id`, `ip`, `port` FROM `" . TABLE_PANEL_IPSANDPORTS . "` WHERE `ssl`='1' ORDER BY `ip` ASC");
				}
				else
				{
					$result_ipsandports = $db->query("SELECT `id`, `ip`, `port` FROM `" . TABLE_PANEL_IPSANDPORTS . "` WHERE `ssl`='0' AND `id`='" . $userinfo['ip'] . "' ORDER BY `ip` ASC");
					$result_ssl_ipsandports = $db->query("SELECT `id`, `ip`, `port` FROM `" . TABLE_PANEL_IPSANDPORTS . "` WHERE `ssl`='1' AND `id`='" . $userinfo['ip'] . "' ORDER BY `ip` ASC");
				}
				
				$ipsandports = '';
				while($row_ipandport = $db->fetch_array($result_ipsandports))
				{
					$ipsandports.= makeoption($row_ipandport['ip'] . ':' . $row_ipandport['port'], $row_ipandport['id']);
				}
					
				$ssl_ipsandports = '';
				while($row_ssl_ipandport = $db->fetch_array($result_ssl_ipsandports))
				{
					$ssl_ipsandports.= makeoption($row_ssl_ipandport['ip'] . ':' . $row_ssl_ipandport['port'], $row_ssl_ipandport['id'], $settings['system']['defaultip']);
				}
				
				if($ssl_ipsandports == '')
				{
					$show_ssl_ipsandports = 0;
				}
				else
				{
					$show_ssl_ipsandports = 1;
				}


				$ssl = makeyesno('ssl', '1', '0', $result['ssl']);
				$ssl_redirect = makeyesno('ssl_redirect', '1', '0', $result['ssl_redirect']);
				$standardsubdomains = array();
				$result_standardsubdomains = $db->query('SELECT `id` FROM `' . TABLE_PANEL_DOMAINS . '` `d`, `' . TABLE_PANEL_CUSTOMERS . '` `c` WHERE `d`.`id`=`c`.`standardsubdomain`');

				while($row_standardsubdomain = $db->fetch_array($result_standardsubdomains))
				{
					$standardsubdomains[] = $db->escape($row_standardsubdomain['id']);
				}

				if(count($standardsubdomains) > 0)
				{
					$standardsubdomains = 'AND `d`.`id` NOT IN (' . join(',', $standardsubdomains) . ') ';
				}
				else
				{
					$standardsubdomains = '';
				}

				$domains = makeoption($lng['domains']['noaliasdomain'], 0, NULL, true);
				$result_domains = $db->query("SELECT `d`.`id`, `d`.`domain`, `c`.`loginname` FROM `" . TABLE_PANEL_DOMAINS . "` `d`, `" . TABLE_PANEL_CUSTOMERS . "` `c` WHERE `d`.`aliasdomain` IS NULL AND `d`.`parentdomainid`=0 " . $standardsubdomains . ($userinfo['customers_see_all'] ? '' : " AND `d`.`adminid` = '" . (int)$userinfo['adminid'] . "'") . " AND `d`.`customerid`=`c`.`customerid` ORDER BY `loginname`, `domain` ASC");

				while($row_domain = $db->fetch_array($result_domains))
				{
					$domains.= makeoption($idna_convert->decode($row_domain['domain']) . ' (' . $row_domain['loginname'] . ')', $row_domain['id']);
				}

				$isbinddomain = makeyesno('isbinddomain', '1', '0', '1');
				$isemaildomain = makeyesno('isemaildomain', '1', '0', '1');
				$isemail_only = makeyesno('isemail_only', '1', '0', '0');
				$subcanemaildomain = makeoption($lng['admin']['subcanemaildomain']['never'], '0', '0', true, true) . makeoption($lng['admin']['subcanemaildomain']['choosableno'], '1', '0', true, true) . makeoption($lng['admin']['subcanemaildomain']['choosableyes'], '2', '0', true, true) . makeoption($lng['admin']['subcanemaildomain']['always'], '3', '0', true, true);
				$dkim = makeyesno('dkim', '1', '0', '1');
				$wwwserveralias = makeyesno('wwwserveralias', '1', '0', '1');
				$caneditdomain = makeyesno('caneditdomain', '1', '0', '1');
				$openbasedir = makeyesno('openbasedir', '1', '0', '1');
				$safemode = makeyesno('safemode', '1', '0', '1');
				$speciallogfile = makeyesno('speciallogfile', '1', '0', '0');
				eval("echo \"" . getTemplate("domains/domains_add") . "\";");
			}
		}
	}
	elseif($action == 'edit'
	       && $id != 0)
	{
		$result = $db->query_first("SELECT `d`.`id`, `d`.`domain`, `d`.`customerid`, `d`.`email_only`, `d`.`documentroot`, `d`.`ssl`, `d`.`ssl_redirect`, `d`.`ssl_ipandport`,`d`.`ipandport`, `d`.`aliasdomain`, `d`.`isbinddomain`, `d`.`isemaildomain`, `d`.`subcanemaildomain`, `d`.`dkim`, `d`.`caneditdomain`, `d`.`zonefile`, `d`.`wwwserveralias`, `d`.`openbasedir`, `d`.`safemode`, `d`.`speciallogfile`, `d`.`specialsettings`, `c`.`loginname`, `c`.`name`, `c`.`firstname`, `c`.`company` " . "FROM `" . TABLE_PANEL_DOMAINS . "` `d` " . "LEFT JOIN `" . TABLE_PANEL_CUSTOMERS . "` `c` USING(`customerid`) " . "WHERE `d`.`parentdomainid`='0' AND `d`.`id`='" . (int)$id . "'" . ($userinfo['customers_see_all'] ? '' : " AND `d`.`adminid` = '" . (int)$userinfo['adminid'] . "' "));
		$alias_check = $db->query_first('SELECT COUNT(`id`) AS count FROM `' . TABLE_PANEL_DOMAINS . '` WHERE `aliasdomain`=\'' . (int)$result['id'] . '\'');
		$alias_check = $alias_check['count'];

		if($result['domain'] != '')
		{
			if(isset($_POST['send'])
			   && $_POST['send'] == 'send')
			{
				$customer = $db->query_first("SELECT `documentroot` FROM " . TABLE_PANEL_CUSTOMERS . " WHERE `customerid`='" . (int)$result['customerid'] . "'");
				$aliasdomain = intval($_POST['alias']);
				$isemaildomain = intval($_POST['isemaildomain']);
				$subcanemaildomain = intval($_POST['subcanemaildomain']);
				$caneditdomain = intval($_POST['caneditdomain']);
				$wwwserveralias = intval($_POST['wwwserveralias']);

				if($userinfo['change_serversettings'] == '1')
				{
					$isbinddomain = intval($_POST['isbinddomain']);
					$zonefile = validate($_POST['zonefile'], 'zonefile');
					$dkim = intval($_POST['dkim']);
					$openbasedir = intval($_POST['openbasedir']);
					$safemode = intval($_POST['safemode']);
					$specialsettings = validate(str_replace("\r\n", "\n", $_POST['specialsettings']), 'specialsettings', '/^[^\0]*$/');
					$ipandport = intval($_POST['ipandport']);
					$ipandport_check = $db->query_first("SELECT `id`, `ip`, `port` FROM `" . TABLE_PANEL_IPSANDPORTS . "` WHERE `id` = '" . $db->escape($ipandport) . "' AND `ssl`='0'");

					if(!isset($ipandport_check['id'])
					   || $ipandport_check['id'] == '0')
					{
						$ipandport = $settings['system']['defaultip'];
					}

					$documentroot = validate($_POST['documentroot'], 'documentroot');

					if($documentroot == '')
					{
						$documentroot = $customer['documentroot'];
					}
				}
				elseif($userinfo['caneditphpsettings'] == '1')
				{
					$isbinddomain = $result['isbinddomain'];
					$zonefile = $result['zonefile'];
					$dkim = $result['dkim'];
					$openbasedir = intval($_POST['openbasedir']);
					$safemode = intval($_POST['safemode']);
					$specialsettings = $result['specialsettings'];
					$ipandport = $result['ipandport'];
					$documentroot = $result['documentroot'];
				}
				else
				{
					$isbinddomain = $result['isbinddomain'];
					$zonefile = $result['zonefile'];
					$dkim = $result['dkim'];
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

				if($subcanemaildomain != '1'
				   && $subcanemaildomain != '2'
				   && $subcanemaildomain != '3')
				{
					$subcanemaildomain = '0';
				}

				if($dkim != '1')
				{
					$dkim = '0';
				}

				if($caneditdomain != '1')
				{
					$caneditdomain = '0';
				}

				$aliasdomain_check = array(
					'id' => 0
				);

				if($aliasdomain != 0)
				{
					$aliasdomain_check = $db->query_first('SELECT `id` FROM `' . TABLE_PANEL_DOMAINS . '` `d`,`' . TABLE_PANEL_CUSTOMERS . '` `c` WHERE `d`.`customerid`=\'' . (int)$result['customerid'] . '\' AND `d`.`aliasdomain` IS NULL AND `d`.`id`<>`c`.`standardsubdomain` AND `c`.`customerid`=\'' . (int)$result['customerid'] . '\' AND `d`.`id`=\'' . (int)$aliasdomain . '\'');
				}

				if($aliasdomain_check['id'] != $aliasdomain)
				{
					standard_error('domainisaliasorothercustomer');
				}
				
				if($settings['system']['use_ssl'] == "1"
				   && isset($_POST['ssl_ipandport']))
				{
					$ssl = ($_POST['ssl'] == '1' ? '1' : '0');
					$ssl_redirect = ($_POST['ssl_redirect'] == '1' ? '1' : '0');
					$ssl_ipandport = (int)$_POST['ssl_ipandport'];
				}
				else
				{
					$ssl = 0;
					$ssl_redirect = 0;
					$ssl_ipandport = 0;
				}

				if(($openbasedir == '0' || $safemode == '0')
				   && (!isset($_POST['reallydoit']) || $_POST['reallydoit'] != 'reallydoit')
				   && $userinfo['change_serversettings'] == '1')
				{
					ask_yesno('admin_domain_reallydisablesecuritysetting', $filename, array(
						'id' => $id,
						'page' => $page,
						'action' => $action,
						'documentroot' => $documentroot,
						'alias' => $aliasdomain,
						'isbinddomain' => $isbinddomain,
						'isemaildomain' => $isemaildomain,
						'subcanemaildomain' => $subcanemaildomain,
						'caneditdomain' => $caneditdomain,
						'zonefile' => $zonefile,
						'dkim' => $dkim,
						'wwwserveralias' => $wwwserveralias,
						'openbasedir' => $openbasedir,
						'ipandport' => $ipandport,
						'ssl' => $ssl,
						'ssl_redirect' => $ssl_redirect,
						'ssl_ipandport' => $ssl_ipandport,
						'safemode' => $safemode,
						'specialsettings' => $specialsettings,
						'reallydoit' => 'reallydoit'
					));
					exit;
				}

				if(substr($documentroot, 0, strlen($customer['documentroot'])) != $customer['documentroot']
				   && (!isset($_POST['reallydocroot']) || $_POST['reallydocroot'] != 'reallydocroot')
				   && !preg_match('/^https?\:\/\//', $documentroot))
				{
					$params = array(
						'id' => $id,
						'page' => $page,
						'action' => $action,
						'documentroot' => $documentroot,
						'alias' => $aliasdomain,
						'isbinddomain' => $isbinddomain,
						'isemaildomain' => $isemaildomain,
						'subcanemaildomain' => $subcanemaildomain,
						'caneditdomain' => $caneditdomain,
						'zonefile' => $zonefile,
						'dkim' => $dkim,
						'wwwserveralias' => $wwwserveralias,
						'openbasedir' => $openbasedir,
						'ipandport' => $ipandport,
						'ssl' => $ssl,
						'ssl_redirect' => $ssl_redirect,
						'ssl_ipandport' => $ssl_ipandport,
						'safemode' => $safemode,
						'specialsettings' => $specialsettings,
						'reallydocroot' => 'reallydocroot'
					);

					if(isset($_POST['reallydoit']))
					{
						$params['reallydoit'] = 'reallydoit';
					}

					ask_yesno('admin_domain_reallydocrootoutofcustomerroot', $filename, $params);
					exit;
				}
				
				if($settings['system']['userdns'] == '1')
				{
					$dns_destinationipv4 = '';
					$dns_destinationipv6 = '';
					$dns_destinationcname = '';
					$dns_mx10 = '';
					$dns_mx20 = '';
					$dns_txt = '';
					
					if(isset($_POST['dns_destip_type']))
					{
						$dns_destip_type = (int)$_POST['dns_destip_type'];
					}
					else
					{
						$dns_destip_type = 0;
					}
					$dns_destmx_type = ($_POST['dns_destmx_type'] == '1' ? '1' : '0');
					
					if($dns_destip_type == 1)
					{
						if(isset($_POST['dns_destinationipv4'])
						   && $_POST['dns_destinationipv4'] != '')
						{
							$dns_destinationipv4 = validate_ip($_POST['dns_destinationipv4']);
						}
						else
						{
							$dns_destinationipv4 = $settings['system']['ipaddress'];
						}
						if(isset($_POST['dns_destinationipv6'])
						   && $_POST['dns_destinationipv6'] != '')
						{
							$dns_destinationipv6 = validate_ip($_POST['dns_destinationipv6']);
						}
						else
						{
							$dns_destinationipv6 = '';
						}
					}
					elseif($dns_destip_type == 2)
					{
						$dns_destinationcname = validateDomain($_POST['dns_destinationcname']);
					}
					else
					{
						$dns_destinationipv4 = $settings['system']['ipaddress'];
					}
					
					if($dns_destmx_type == '1')
					{
						if(isset($_POST['dns_mxentry10'])
						   && $_POST['dns_mxentry10'] != '')
						{
							$dns_mx10 = validateDomain($_POST['dns_mxentry10']);
						}
						else
						{
							if($settings['system']['maxservers'] != '')
							{
								$mxsrvs = explode(',', $settings['system']['maxservers']);
								$dns_mx10 = $mxsrvs[0];
							}
							else
							{
								$dns_mx10 = $settings['system']['hostname'];
							}
						}
						if(isset($_POST['dns_mxentry20'])
						   && $_POST['dns_mxentry20'] != '')
						{
							$dns_mx20 = validateDomain($_POST['dns_mxentry20']);
						}
						else
						{
							if($settings['system']['maxservers'] != '')
							{
								$mxsrvs = explode(',', $settings['system']['maxservers']);
								if(isset($mxsrvs[1])
								   && $mxsrvs[1] != '')
								{
									$dns_mx20 = $mxsrvs[1];
								}
								else
								{
									$dns_mx20 = $mxsrvs[0];
								}
							}
							else
							{
								$dns_mx20 = $settings['system']['hostname'];
							}
						}
					}
					else
					{
						$dns_mx10 = $settings['system']['hostname'];
						$dns_mx20 = $settings['system']['hostname'];
					}
					
					if(isset($_POST['dns_txtrecords'])
					   && $_POST['dns_txtrecords'] != '')
					{
						$dns_txt = validate($_POST['dns_txtrecords'], 'dns txt entry');
					}
					else
					{
						$dns_txt = '';
					}
				}

				if($documentroot != $result['documentroot']
				   || $ipandport != $result['ipandport']
				   || $openbasedir != $result['openbasedir']
				   || $safemode != $result['safemode']
				   || $specialsettings != $result['specialsettings']
				   || $aliasdomain != $result['aliasdomain'])
				{
					inserttask('1');
				}

				if($isbinddomain != $result['isbinddomain']
				   || $zonefile != $result['zonefile']
				   || $dkim != $result['dkim']
				   || $ipandport != $result['ipandport'])
				{
					inserttask('4');
				}

				if($isemaildomain == '0'
				   && $result['isemaildomain'] == '1')
				{
					$db->query("DELETE FROM `" . TABLE_MAIL_USERS . "` WHERE `domainid`='" . (int)$id . "' ");
					$db->query("DELETE FROM `" . TABLE_MAIL_VIRTUAL . "` WHERE `domainid`='" . (int)$id . "' ");
					$log->logAction(ADM_ACTION, LOG_NOTICE, "deleted domain #" . $id . " from mail-tables");
				}

				$updatechildren = '';

				if($subcanemaildomain == '0'
				   && $result['subcanemaildomain'] != '0')
				{
					$updatechildren = ', `isemaildomain`=\'0\' ';
				}
				elseif($subcanemaildomain == '3'
				       && $result['subcanemaildomain'] != '3')
				{
					$updatechildren = ', `isemaildomain`=\'1\' ';
				}

				if($_POST['isemail_only'] == '1')
				{
					$isemail_only = "1";
					$isemaildomain = "1";
				}

				$result = $db->query("UPDATE `" . TABLE_PANEL_DOMAINS . "` SET `documentroot`='" . $db->escape($documentroot) . "', `ipandport`='" . $db->escape($ipandport) . "', `aliasdomain`=" . (($aliasdomain != 0 && $alias_check == 0) ? '\'' . $db->escape($aliasdomain) . '\'' : 'NULL') . ", `isbinddomain`='" . $db->escape($isbinddomain) . "', `isemaildomain`='" . $db->escape($isemaildomain) . "', `email_only`='" . $db->escape($isemail_only) . "', `subcanemaildomain`='" . $db->escape($subcanemaildomain) . "', `dkim`='" . $db->escape($dkim) . "', `caneditdomain`='" . $db->escape($caneditdomain) . "', `zonefile`='" . $db->escape($zonefile) . "', `wwwserveralias`='" . $db->escape($wwwserveralias) . "', `openbasedir`='" . $db->escape($openbasedir) . "', `safemode`='" . $db->escape($safemode) . "', `specialsettings`='" . $db->escape($specialsettings) . "' WHERE `id`='" . (int)$id . "'");
				$result = $db->query("UPDATE `" . TABLE_PANEL_DOMAINS . "` SET `ipandport`='" . $db->escape($ipandport) . "', `openbasedir`='" . $db->escape($openbasedir) . "', `safemode`='" . $db->escape($safemode) . "', `specialsettings`='" . $db->escape($specialsettings) . "'" . $updatechildren . " WHERE `parentdomainid`='" . (int)$id . "'");
				$result = $db->query("UPDATE `" . TABLE_PANEL_DOMAINS . "` SET `ssl`='" . (int)$ssl . "', `ssl_redirect`='" . (int)$ssl_redirect . "', `ssl_ipandport`='" . (int)$ssl_ipandport . "'  WHERE `id`='" . (int)$id . "'");
				if($settings['system']['userdns'] == '1')
				{
					$result = $db->query("UPDATE `" . TABLE_PANEL_DNSENTRY . "` SET `ipv4`='" . $db->escape($dns_destinationipv4) . "', `ipv6`='" . $db->escape($dns_destinationipv6) . "', `cname`='" . $db->escape($dns_destinationcname) . "', `mx10`='" . $db->escape($dns_mx10) . "', `mx20`='" . $db->escape($dns_mx20) . "', `txt`='" . $db->escape($dns_txt) . "' WHERE `domainid`='" . (int)$id . "'");
				}
				$log->logAction(ADM_ACTION, LOG_INFO, "edited domain #" . $id);
				redirectTo($filename, Array(
					'page' => $page,
					's' => $s
				));
			}
			else
			{
				$result['domain'] = $idna_convert->decode($result['domain']);
				$domains = makeoption($lng['domains']['noaliasdomain'], 0, NULL, true);
				$result_domains = $db->query("SELECT `d`.`id`, `d`.`domain`  FROM `" . TABLE_PANEL_DOMAINS . "` `d`, `" . TABLE_PANEL_CUSTOMERS . "` `c` WHERE `d`.`aliasdomain` IS NULL AND `d`.`parentdomainid`=0 AND `d`.`id`<>'" . (int)$result['id'] . "' AND `c`.`standardsubdomain`<>`d`.`id` AND `d`.`customerid`='" . (int)$result['customerid'] . "' AND `c`.`customerid`=`d`.`customerid` ORDER BY `d`.`domain` ASC");

				while($row_domain = $db->fetch_array($result_domains))
				{
					$domains.= makeoption($idna_convert->decode($row_domain['domain']), $row_domain['id'], $result['aliasdomain']);
				}

				if($userinfo['ip'] == "-1")
				{
					$result_ipsandports = $db->query("SELECT `id`, `ip`, `port` FROM `" . TABLE_PANEL_IPSANDPORTS . "` WHERE `ssl`='0' ORDER BY `ip` ASC");
					$result_ssl_ipsandports = $db->query("SELECT `id`, `ip`, `port` FROM `" . TABLE_PANEL_IPSANDPORTS . "` WHERE `ssl`='1' ORDER BY `ip` ASC");
				}
				else
				{
					$result_ipsandports = $db->query("SELECT `id`, `ip`, `port` FROM `" . TABLE_PANEL_IPSANDPORTS . "` WHERE `ssl`='0' AND `id`='" . $userinfo['ip'] . "' ORDER BY `ip` ASC");
					$result_ssl_ipsandports = $db->query("SELECT `id`, `ip`, `port` FROM `" . TABLE_PANEL_IPSANDPORTS . "` WHERE `ssl`='1' AND `id`='" . $userinfo['ip'] . "' ORDER BY `ip` ASC");
				}
				
				$ipsandports = '';
				while($row_ipandport = $db->fetch_array($result_ipsandports))
				{
					$ipsandports.= makeoption($row_ipandport['ip'] . ':' . $row_ipandport['port'], $row_ipandport['id']);
				}
					
				$ssl_ipsandports = '';
				while($row_ssl_ipandport = $db->fetch_array($result_ssl_ipsandports))
				{
					$ssl_ipsandports.= makeoption($row_ssl_ipandport['ip'] . ':' . $row_ssl_ipandport['port'], $row_ssl_ipandport['id'], $settings['system']['defaultip']);
				}
				
				if($ssl_ipsandports == '')
				{
					$show_ssl_ipsandports = 0;
				}
				else
				{
					$show_ssl_ipsandports = 1;
				}
				
				if($settings['system']['userdns'] == '1')
				{
					$result_dns = $db->query("SELECT * FROM `" . TABLE_PANEL_DNSENTRY . "` WHERE `domainid` = '" . (int)$result['id'] . "'");
					$row_dns = $db->fetch_array($result_dns);
					
					if($row_dns['ipv4'] == $settings['system']['ipaddress']
					   && $row_dns['ipv6'] == ''
					   && $row_dns['cname'] == '')
					{
						$dns_destip_type_0_checked = 'checked="checked"';
						$dns_destip_type_1_checked = '';
						$dns_destip_type_2_checked = '';
						
						$dns_destinationipv4 = '';
						$dns_destinationipv6 = '';
						$dns_destinationcname = '';
					}
					elseif($row_dns['ipv4'] != ''
					   && $row_dns['ipv4'] != $settings['system']['ipaddress'])
					{
						$dns_destip_type_0_checked = '';
						$dns_destip_type_1_checked = 'checked="checked"';
						$dns_destip_type_2_checked = '';
						
						$dns_destinationipv4 = $row_dns['ipv4'];
						$dns_destinationipv6 = $row_dns['ipv6'];
						$dns_destinationcname = '';
					}
					elseif($row_dns['cname'] != '')
					{
						$dns_destip_type_0_checked = '';
						$dns_destip_type_1_checked = '';
						$dns_destip_type_2_checked = 'checked="checked"';
						
						$dns_destinationipv4 = '';
						$dns_destinationipv6 = '';
						$dns_destinationcname = $row_dns['cname'];
					}
					else
					{
						$dns_destip_type_0_checked = 'checked="checked"';
						$dns_destip_type_1_checked = '';
						$dns_destip_type_2_checked = '';
						
						$dns_destinationipv4 = '';
						$dns_destinationipv6 = '';
						$dns_destinationcname = '';
					}
					
					if($row_dns['mx10'] != ''
					   && (($settings['system']['mxservers'] != ''
					      && in_array($row_dns['mx10'], $settings['system']['mxservers']))
					      || $row_dns['mx10'] == $settings['system']['hostname']))
					{
						$dns_destmx_type_0_checked = 'checked="checked"';
						$dns_destmx_type_1_checked = '';
						
						$dns_mxentry10 = '';
						$dns_mxentry20 = '';
					}
					elseif($row_dns['mx10'] != ''
					   && ($settings['system']['mxservers'] == ''
					      || !in_array($row_dns['mx10'], $settings['system']['mxservers']))
					   && $row_dns['mx10'] != $settings['system']['hostname'])
					{
						$dns_destmx_type_0_checked = '';
						$dns_destmx_type_1_checked = 'checked="checked"';
						
						$dns_mxentry10 = $row_dns['mx10'];
						$dns_mxentry20 = $row_dns['mx20'];
					}
					else
					{
						$dns_destmx_type_0_checked = 'checked="checked"';
						$dns_destmx_type_1_checked = '';
						
						$dns_mxentry10 = '';
						$dns_mxentry20 = '';
					}
					
					if($row_dns['txt'] != '')
					{
						$dns_txtrecords = $row_dns['txt'];
					}
					else
					{
						$dns_txtrecords = '';
					}
				}

				$result['specialsettings'] = $result['specialsettings'];
				$isbinddomain = makeyesno('isbinddomain', '1', '0', $result['isbinddomain']);
				$wwwserveralias = makeyesno('wwwserveralias', '1', '0', $result['wwwserveralias']);
				$isemaildomain = makeyesno('isemaildomain', '1', '0', $result['isemaildomain']);
				$isemail_only = makeyesno('isemail_only', '1', '0', $result['email_only']);
				$ssl = makeyesno('ssl', '1', '0', $result['ssl']);
				$ssl_redirect = makeyesno('ssl_redirect', '1', '0', $result['ssl_redirect']);
				$subcanemaildomain = makeoption($lng['admin']['subcanemaildomain']['never'], '0', $result['subcanemaildomain'], true, true);
				$subcanemaildomain.= makeoption($lng['admin']['subcanemaildomain']['choosableno'], '1', $result['subcanemaildomain'], true, true);
				$subcanemaildomain.= makeoption($lng['admin']['subcanemaildomain']['choosableyes'], '2', $result['subcanemaildomain'], true, true);
				$subcanemaildomain.= makeoption($lng['admin']['subcanemaildomain']['always'], '3', $result['subcanemaildomain'], true, true);
				$dkim = makeyesno('dkim', '1', '0', $result['dkim']);
				$caneditdomain = makeyesno('caneditdomain', '1', '0', $result['caneditdomain']);
				$openbasedir = makeyesno('openbasedir', '1', '0', $result['openbasedir']);
				$safemode = makeyesno('safemode', '1', '0', $result['safemode']);
				$speciallogfile = ($result['speciallogfile'] == 1 ? $lng['panel']['yes'] : $lng['panel']['no']);
				$result = htmlentities_array($result);
				eval("echo \"" . getTemplate("domains/domains_edit") . "\";");
			}
		}
	}
}

?>

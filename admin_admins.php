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

if($page == 'admins'
   && $userinfo['change_serversettings'] == '1')
{
	if($action == '')
	{
		$log->logAction(ADM_ACTION, LOG_NOTICE, "viewed admin_admins");
		$fields = array(
			'loginname' => $lng['login']['username'],
			'name' => $lng['customer']['name'],
			'diskspace' => $lng['customer']['diskspace'],
			'diskspace_used' => $lng['customer']['diskspace'] . ' (' . $lng['panel']['used'] . ')',
			'traffic' => $lng['customer']['traffic'],
			'traffic_used' => $lng['customer']['traffic'] . ' (' . $lng['panel']['used'] . ')',
			'mysqls' => $lng['customer']['mysqls'],
			'mysqls_used' => $lng['customer']['mysqls'] . ' (' . $lng['panel']['used'] . ')',
			'ftps' => $lng['customer']['ftps'],
			'ftps_used' => $lng['customer']['ftps'] . ' (' . $lng['panel']['used'] . ')',
			'tickets' => $lng['customer']['tickets'],
			'tickets_used' => $lng['customer']['tickets'] . ' (' . $lng['panel']['used'] . ')',
			'subdomains' => $lng['customer']['subdomains'],
			'subdomains_used' => $lng['customer']['subdomains'] . ' (' . $lng['panel']['used'] . ')',
			'emails' => $lng['customer']['emails'],
			'emails_used' => $lng['customer']['emails'] . ' (' . $lng['panel']['used'] . ')',
			'email_accounts' => $lng['customer']['accounts'],
			'email_accounts_used' => $lng['customer']['accounts'] . ' (' . $lng['panel']['used'] . ')',
			'email_forwarders' => $lng['customer']['forwarders'],
			'email_forwarders_used' => $lng['customer']['forwarders'] . ' (' . $lng['panel']['used'] . ')',
			'email_quota' => $lng['customer']['email_quota'],
			'email_quota_used' => $lng['customer']['email_quota'] . ' (' . $lng['panel']['used'] . ')',
			'deactivated' => $lng['admin']['deactivated']
		);
		$paging = new paging($userinfo, $db, TABLE_PANEL_ADMINS, $fields, $settings['panel']['paging'], $settings['panel']['natsorting']);
		$admins = '';
		$result = $db->query("SELECT * FROM `" . TABLE_PANEL_ADMINS . "` " . $paging->getSqlWhere(false) . " " . $paging->getSqlOrderBy() . " " . $paging->getSqlLimit());
		$paging->setEntries($db->num_rows($result));
		$sortcode = $paging->getHtmlSortCode($lng, true);
		$arrowcode = $paging->getHtmlArrowCode($filename . '?page=' . $page . '&s=' . $s);
		$searchcode = $paging->getHtmlSearchCode($lng);
		$pagingcode = $paging->getHtmlPagingCode($filename . '?page=' . $page . '&s=' . $s);
		$i = 0;
		$count = 0;

		while($row = $db->fetch_array($result))
		{
			if($paging->checkDisplay($i))
			{
				$row['traffic_used'] = round($row['traffic_used']/(1024*1024), $settings['panel']['decimal_places']);
				$row['traffic'] = round($row['traffic']/(1024*1024), $settings['panel']['decimal_places']);
				$row['diskspace_used'] = round($row['diskspace_used']/1024, $settings['panel']['decimal_places']);
				$row['diskspace'] = round($row['diskspace']/1024, $settings['panel']['decimal_places']);
				$row = str_replace_array('-1', 'UL', $row, 'customers domains diskspace traffic mysqls emails email_accounts email_forwarders email_quota ftps subdomains');
				$row = htmlentities_array($row);
				eval("\$admins.=\"" . getTemplate("admins/admins_admin") . "\";");
				$count++;
			}

			$i++;
		}

		eval("echo \"" . getTemplate("admins/admins") . "\";");
	}
	elseif($action == 'su')
	{
		$result = $db->query_first("SELECT * FROM `" . TABLE_PANEL_ADMINS . "` WHERE `adminid` = '" . (int)$id . "'");

		if($result['loginname'] != ''
		   && $result['adminid'] != $userinfo['userid'])
		{
			$result = $db->query_first("SELECT * FROM `" . TABLE_PANEL_SESSIONS . "` WHERE `userid`='" . (int)$userinfo['userid'] . "'");
			$s = md5(uniqid(microtime(), 1));
			$db->query("INSERT INTO `" . TABLE_PANEL_SESSIONS . "` (`hash`, `userid`, `ipaddress`, `useragent`, `lastactivity`, `language`, `adminsession`) VALUES ('" . $db->escape($s) . "', '" . (int)$id . "', '" . $db->escape($result['ipaddress']) . "', '" . $db->escape($result['useragent']) . "', '" . time() . "', '" . $db->escape($result['language']) . "', '1')");
			$log->logAction(ADM_ACTION, LOG_INFO, "switched adminuser and is now '" . $result['loginname'] . "'");
			redirectTo('admin_index.php', Array(
				's' => $s
			));
		}
		else
		{
			redirectTo('index.php', Array(
				'action' => 'login'
			));
		}
	}
	elseif($action == 'delete'
	       && $id != 0)
	{
		$result = $db->query_first("SELECT * FROM `" . TABLE_PANEL_ADMINS . "` WHERE `adminid`='" . (int)$id . "'");

		if($result['loginname'] != '')
		{
			if($result['adminid'] == $userinfo['userid'])
			{
				standard_error('youcantdeleteyourself');
				exit;
			}

			if(isset($_POST['send'])
			   && $_POST['send'] == 'send')
			{
				$db->query("DELETE FROM `" . TABLE_PANEL_ADMINS . "` WHERE `adminid`='" . (int)$id . "'");
				$db->query("DELETE FROM `" . TABLE_PANEL_TRAFFIC_ADMINS . "` WHERE `adminid`='" . (int)$id . "'");
				$db->query("UPDATE `" . TABLE_PANEL_CUSTOMERS . "` SET `adminid` = '" . (int)$userinfo['userid'] . "' WHERE `adminid` = '" . (int)$id . "'");
				$db->query("UPDATE `" . TABLE_PANEL_DOMAINS . "` SET `adminid` = '" . (int)$userinfo['userid'] . "' WHERE `adminid` = '" . (int)$id . "'");
				$log->logAction(ADM_ACTION, LOG_INFO, "deleted admin '" . $result['loginname'] . "'");
				updateCounters();
				redirectTo($filename, Array(
					'page' => $page,
					's' => $s
				));
			}
			else
			{
				ask_yesno('admin_admin_reallydelete', $filename, array(
					'id' => $id,
					'page' => $page,
					'action' => $action
				), $result['loginname']);
			}
		}
	}
	elseif($action == 'add')
	{
		if(isset($_POST['send'])
		   && $_POST['send'] == 'send')
		{
			$name = validate($_POST['name'], 'name');
			$email = $idna_convert->encode(validate($_POST['email'], 'email'));
			$loginname = validate($_POST['loginname'], 'loginname');
			$password = validate($_POST['admin_password'], 'password');
			$email = $idna_convert->encode(validate($_POST['email'], 'email'));
			$def_language = validate($_POST['def_language'], 'default language');
			$customers = intval_ressource($_POST['customers']);
			$domains = intval_ressource($_POST['domains']);
			$subdomains = intval_ressource($_POST['subdomains']);
			$emails = intval_ressource($_POST['emails']);
			$email_accounts = intval_ressource($_POST['email_accounts']);
			$email_forwarders = intval_ressource($_POST['email_forwarders']);
			$email_quota = intval_ressource($_POST['email_quota']);
			$email_quota_type = validate($_POST['email_quota_type'], 'quota type');
			$ftps = intval_ressource($_POST['ftps']);
			$tickets = intval_ressource($_POST['tickets']);
			$mysqls = intval_ressource($_POST['mysqls']);
			$customers_see_all = intval($_POST['customers_see_all']);
			$domains_see_all = intval($_POST['domains_see_all']);
			$change_serversettings = intval($_POST['change_serversettings']);
			$diskspace = intval_ressource($_POST['diskspace']);
			$traffic = doubleval_ressource($_POST['traffic']);
			$diskspace = $diskspace*1024;
			$traffic = $traffic*1024*1024;
			$email_quota = getQuotaInBytes($email_quota, $email_quota_type);

			// Check if the account already exists

			$loginname_check = $db->query_first("SELECT `loginname` FROM `" . TABLE_PANEL_CUSTOMERS . "` WHERE `loginname` = '" . $db->escape($loginname) . "'");
			$loginname_check_admin = $db->query_first("SELECT `loginname` FROM `" . TABLE_PANEL_ADMINS . "` WHERE `loginname` = '" . $db->escape($loginname) . "'");

			if($loginname == '')
			{
				standard_error(array(
					'stringisempty',
					'myloginname'
				));
			}
			elseif(strtolower($loginname_check['loginname']) == strtolower($loginname)
			       || strtolower($loginname_check_admin['loginname']) == strtolower($loginname))
			{
				standard_error('loginnameexists', $loginname);
			}

			// Accounts which match systemaccounts are not allowed, filtering them

			elseif (preg_match('/^' . preg_quote($settings['customer']['accountprefix'], '/') . '([0-9]+)/', $loginname))
			{
				standard_error('loginnameissystemaccount', $settings['customer']['accountprefix']);
			}
			elseif(!validateUsername($loginname))
			{
				standard_error('loginnameiswrong', $loginname);
			}
			elseif($name == '')
			{
				standard_error(array(
					'stringisempty',
					'myname'
				));
			}
			elseif($email == '')
			{
				standard_error(array(
					'stringisempty',
					'emailadd'
				));
			}
			elseif($password == '')
			{
				standard_error(array(
					'stringisempty',
					'mypassword'
				));
			}
			elseif(!validateEmail($email))
			{
				standard_error('emailiswrong', $email);
			}
			else
			{
				if($customers_see_all != '1')
				{
					$customers_see_all = '0';
				}

				if($domains_see_all != '1')
				{
					$domains_see_all = '0';
				}

				if($change_serversettings != '1')
				{
					$change_serversettings = '0';
				}

				$result = $db->query("INSERT INTO `" . TABLE_PANEL_ADMINS . "` (`loginname`, `password`, `name`, `email`, `def_language`, `change_serversettings`, `customers`, `customers_see_all`, `domains`, `domains_see_all`, `diskspace`, `traffic`, `subdomains`, `emails`, `email_accounts`, `email_forwarders`, `email_quota`, `ftps`, `tickets`, `mysqls`)
					                   VALUES ('" . $db->escape($loginname) . "', '" . md5($password) . "', '" . $db->escape($name) . "', '" . $db->escape($email) . "','" . $db->escape($def_language) . "', '" . $db->escape($change_serversettings) . "', '" . $db->escape($customers) . "', '" . $db->escape($customers_see_all) . "', '" . $db->escape($domains) . "', '" . $db->escape($domains_see_all) . "', '" . $db->escape($diskspace) . "', '" . $db->escape($traffic) . "', '" . $db->escape($subdomains) . "', '" . $db->escape($emails) . "', '" . $db->escape($email_accounts) . "', '" . $db->escape($email_forwarders) . "', '" . $db->escape($email_quota) . "', '" . $db->escape($ftps) . "', '" . $db->escape($tickets) . "', '" . $db->escape($mysqls) . "')");
				$adminid = $db->insert_id();
				$log->logAction(ADM_ACTION, LOG_INFO, "added admin '" . $loginname . "'");
				redirectTo($filename, Array(
					'page' => $page,
					's' => $s
				));
			}
		}
		else
		{
			$language_options = '';

			while(list($language_file, $language_name) = each($languages))
			{
				$language_options.= makeoption($language_name, $language_file, $userinfo['language'], true);
			}

			$change_serversettings = makeyesno('change_serversettings', '1', '0', '0');
			$customers_see_all = makeyesno('customers_see_all', '1', '0', '0');
			$domains_see_all = makeyesno('domains_see_all', '1', '0', '0');
			$quota_type_option = makeQuotaOption();
			eval("echo \"" . getTemplate("admins/admins_add") . "\";");
		}
	}
	elseif($action == 'edit'
	       && $id != 0)
	{
		$result = $db->query_first("SELECT * FROM `" . TABLE_PANEL_ADMINS . "` WHERE `adminid`='" . (int)$id . "'");

		if($result['loginname'] != '')
		{
			if(isset($_POST['send'])
			   && $_POST['send'] == 'send')
			{
				$name = validate($_POST['name'], 'name');
				$email = $idna_convert->encode(validate($_POST['email'], 'email'));

				if($result['adminid'] == $userinfo['userid'])
				{
					$password = '';
					$def_language = $result['def_language'];
					$deactivated = $result['deactivated'];
					$customers = $result['customers'];
					$domains = $result['domains'];
					$subdomains = $result['subdomains'];
					$emails = $result['emails'];
					$email_accounts = $result['email_accounts'];
					$email_forwarders = $result['email_forwarders'];
					$email_quota = $result['email_quota'];
					$email_quota_type = getQuotaType($result['email_quota']);
					$ftps = $result['ftps'];
					$tickets = $result['tickets'];
					$mysqls = $result['mysqls'];
					$customers_see_all = $result['customers_see_all'];
					$domains_see_all = $result['domains_see_all'];
					$change_serversettings = $result['change_serversettings'];
					$diskspace = $result['diskspace'];
					$traffic = $result['traffic'];
				}
				else
				{
					$password = validate($_POST['admin_password'], 'new password');
					$def_language = validate($_POST['def_language'], 'default language');
					$deactivated = intval($_POST['deactivated']);
					$customers = intval_ressource($_POST['customers']);
					$domains = intval_ressource($_POST['domains']);
					$subdomains = intval_ressource($_POST['subdomains']);
					$emails = intval_ressource($_POST['emails']);
					$email_accounts = intval_ressource($_POST['email_accounts']);
					$email_forwarders = intval_ressource($_POST['email_forwarders']);
					$email_quota = intval_ressource($_POST['email_quota']);
					$email_quota_type = validate($_POST['email_quota_type'], 'quota type');
					$ftps = intval_ressource($_POST['ftps']);
					$tickets = intval_ressource($_POST['tickets']);
					$mysqls = intval_ressource($_POST['mysqls']);
					$customers_see_all = intval($_POST['customers_see_all']);
					$domains_see_all = intval($_POST['domains_see_all']);
					$change_serversettings = intval($_POST['change_serversettings']);
					$diskspace = intval($_POST['diskspace']);
					$traffic = doubleval_ressource($_POST['traffic']);
					$diskspace = $diskspace*1024;
					$traffic = $traffic*1024*1024;
				}

				if($name == '')
				{
					standard_error(array(
						'stringisempty',
						'myname'
					));
				}
				elseif($email == '')
				{
					standard_error(array(
						'stringisempty',
						'emailadd'
					));
				}
				elseif(!validateEmail($email))
				{
					standard_error('emailiswrong', $email);
				}
				else
				{
					$updatepassword = '';

					if($password != '')
					{
						$updatepassword = "`password`='" . md5($password) . "', ";
					}

					if($deactivated != '1')
					{
						$deactivated = '0';
					}

					if($customers_see_all != '1')
					{
						$customers_see_all = '0';
					}

					if($domains_see_all != '1')
					{
						$domains_see_all = '0';
					}

					if($change_serversettings != '1')
					{
						$change_serversettings = '0';
					}

					$email_quota = getQuotaInBytes($email_quota, $email_quota_type);
					$db->query("UPDATE `" . TABLE_PANEL_ADMINS . "` SET `name`='" . $db->escape($name) . "', `email`='" . $db->escape($email) . "', `def_language`='" . $db->escape($def_language) . "', `change_serversettings` = '" . $db->escape($change_serversettings) . "', `customers` = '" . $db->escape($customers) . "', `customers_see_all` = '" . $db->escape($customers_see_all) . "', `domains` = '" . $db->escape($domains) . "', `domains_see_all` = '" . $db->escape($domains_see_all) . "', " . $updatepassword . " `diskspace`='" . $db->escape($diskspace) . "', `traffic`='" . $db->escape($traffic) . "', `subdomains`='" . $db->escape($subdomains) . "', `emails`='" . $db->escape($emails) . "', `email_accounts` = '" . $db->escape($email_accounts) . "', `email_forwarders`='" . $db->escape($email_forwarders) . "', `email_quota`='" . $db->escape($email_quota) . "', `ftps`='" . $db->escape($ftps) . "', `tickets`='" . $db->escape($tickets) . "', `mysqls`='" . $db->escape($mysqls) . "', `deactivated`='" . $db->escape($deactivated) . "' WHERE `adminid`='" . $db->escape($id) . "'");
					$log->logAction(ADM_ACTION, LOG_INFO, "edited admin '#" . $id . "'");
					redirectTo($filename, Array(
						'page' => $page,
						's' => $s
					));
				}
			}
			else
			{
				$result['traffic'] = round($result['traffic']/(1024*1024), $settings['panel']['decimal_places']);
				$result['diskspace'] = round($result['diskspace']/1024, $settings['panel']['decimal_places']);
				$result['email'] = $idna_convert->decode($result['email']);
				$quota_type_option = makeQuotaOption(getQuotaType($result['email_quota']));
				$result['email_quota'] = getQuota($result['email_quota']);
				$language_options = '';

				while(list($language_file, $language_name) = each($languages))
				{
					$language_options.= makeoption($language_name, $language_file, $result['def_language'], true);
				}

				$change_serversettings = makeyesno('change_serversettings', '1', '0', $result['change_serversettings']);
				$customers_see_all = makeyesno('customers_see_all', '1', '0', $result['customers_see_all']);
				$domains_see_all = makeyesno('domains_see_all', '1', '0', $result['domains_see_all']);
				$deactivated = makeyesno('deactivated', '1', '0', $result['deactivated']);
				$result = htmlentities_array($result);
				eval("echo \"" . getTemplate("admins/admins_edit") . "\";");
			}
		}
	}
}

?>
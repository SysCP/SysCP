<?php

/**
 * This file is part of the SysCP project.
 * Copyright (c) 2003-2009 the SysCP Team (see authors).
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

if($page == 'customers'
   && $userinfo['customers'] != '0')
{
	// Load taxclasses if billing is activated

	if($settings['billing']['activate_billing'] == '1')
	{
		$taxclasses = array(
			'0' => $lng['panel']['default']
		);
		$taxclasses_option = makeoption($lng['panel']['default'], 0, 0, true);
		$taxclasses_result = $db->query('SELECT `classid`, `classname` FROM `' . TABLE_BILLING_TAXCLASSES . '` ');

		while($taxclasses_row = $db->fetch_array($taxclasses_result))
		{
			$taxclasses[$taxclasses_row['classid']] = $taxclasses_row['classname'];
			$taxclasses_option.= makeoption($taxclasses_row['classname'], $taxclasses_row['classid']);
		}
	}

	if($action == '')
	{
		$log->logAction(ADM_ACTION, LOG_NOTICE, "viewed admin_customers");
		$fields = array(
			'c.loginname' => $lng['login']['username'],
			'a.loginname' => $lng['admin']['admin'],
			'c.name' => $lng['customer']['name'],
			'c.firstname' => $lng['customer']['firstname'],
			'c.company' => $lng['customer']['company'],
			'c.diskspace' => $lng['customer']['diskspace'],
			'c.diskspace_used' => $lng['customer']['diskspace'] . ' (' . $lng['panel']['used'] . ')',
			'c.traffic' => $lng['customer']['traffic'],
			'c.traffic_used' => $lng['customer']['traffic'] . ' (' . $lng['panel']['used'] . ')',
			'c.mysqls' => $lng['customer']['mysqls'],
			'c.mysqls_used' => $lng['customer']['mysqls'] . ' (' . $lng['panel']['used'] . ')',
			'c.ftps' => $lng['customer']['ftps'],
			'c.ftps_used' => $lng['customer']['ftps'] . ' (' . $lng['panel']['used'] . ')',
			'c.subdomains' => $lng['customer']['subdomains'],
			'c.subdomains_used' => $lng['customer']['subdomains'] . ' (' . $lng['panel']['used'] . ')',
			'c.emails' => $lng['customer']['emails'],
			'c.emails_used' => $lng['customer']['emails'] . ' (' . $lng['panel']['used'] . ')',
			'c.email_accounts' => $lng['customer']['accounts'],
			'c.email_accounts_used' => $lng['customer']['accounts'] . ' (' . $lng['panel']['used'] . ')',
			'c.email_forwarders' => $lng['customer']['forwarders'],
			'c.email_forwarders_used' => $lng['customer']['forwarders'] . ' (' . $lng['panel']['used'] . ')',
			'c.email_quota' => $lng['customer']['email_quota'],
			'c.email_quota_used' => $lng['customer']['email_quota'] . ' (' . $lng['panel']['used'] . ')',
			'c.deactivated' => $lng['admin']['deactivated'],
			'c.phpenabled' => $lng['admin']['phpenabled']
		);

		if($settings['ticket']['enabled'] == 1)
		{
			$fields['c.tickets'] = $lng['customer']['tickets'];
			$fields['c.tickets_used'] = $lng['customer']['tickets'] . ' (' . $lng['panel']['used'] . ')';
		}

		$paging = new paging($userinfo, $db, TABLE_PANEL_CUSTOMERS, $fields, $settings['panel']['paging'], $settings['panel']['natsorting']);
		$customers = '';
		$result = $db->query("SELECT `c`.*, `a`.`loginname` AS `adminname` " . "FROM `" . TABLE_PANEL_CUSTOMERS . "` `c`, `" . TABLE_PANEL_ADMINS . "` `a` " . "WHERE " . ($userinfo['customers_see_all'] ? '' : " `c`.`adminid` = '" . (int)$userinfo['adminid'] . "' AND ") . "`c`.`adminid`=`a`.`adminid` " . $paging->getSqlWhere(true) . " " . $paging->getSqlOrderBy() . " " . $paging->getSqlLimit());
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
				$domains = $db->query_first("SELECT COUNT(`id`) AS `domains` " . "FROM `" . TABLE_PANEL_DOMAINS . "` " . "WHERE `customerid`='" . (int)$row['customerid'] . "' AND `parentdomainid`='0' AND `id`<> '" . (int)$row['standardsubdomain'] . "'");
				$row['domains'] = intval($domains['domains']);
				$row['traffic_used'] = round($row['traffic_used'] / (1024 * 1024), $settings['panel']['decimal_places']);
				$row['traffic'] = round($row['traffic'] / (1024 * 1024), $settings['panel']['decimal_places']);
				$row['diskspace_used'] = round($row['diskspace_used'] / 1024, $settings['panel']['decimal_places']);
				$row['diskspace'] = round($row['diskspace'] / 1024, $settings['panel']['decimal_places']);
				$row = str_replace_array('-1', 'UL', $row, 'diskspace traffic mysqls emails email_accounts email_forwarders ftps tickets subdomains');
				$enable_billing_data_edit = ($row['servicestart_date'] == '0000-00-00' || ($row['interval_payment'] == CONST_BILLING_INTERVALPAYMENT_PREPAID && calculateDayDifference(time(), $row['lastinvoiced_date']) >= 0));
				$highlight_row = ($row['service_active'] != '1' && $settings['billing']['activate_billing'] == '1' && $settings['billing']['highlight_inactive'] == '1');
				$row = htmlentities_array($row);
				eval("\$customers.=\"" . getTemplate("customers/customers_customer") . "\";");
				$count++;
			}

			$i++;
		}

		eval("echo \"" . getTemplate("customers/customers") . "\";");
	}
	elseif($action == 'su'
	       && $id != 0)
	{
		$result = $db->query_first("SELECT * FROM `" . TABLE_PANEL_CUSTOMERS . "` WHERE `customerid`='" . (int)$id . "' " . ($userinfo['customers_see_all'] ? '' : " AND `adminid` = '" . (int)$userinfo['adminid'] . "' "));
		$destination_user = $result['loginname'];

		if($destination_user != '')
		{
			$result = $db->query_first("SELECT * FROM `" . TABLE_PANEL_SESSIONS . "` WHERE `userid`='" . (int)$userinfo['userid'] . "' AND `hash`='" . $db->escape($s) . "'");
			$s = md5(uniqid(microtime(), 1));
			$db->query("INSERT INTO `" . TABLE_PANEL_SESSIONS . "` (`hash`, `userid`, `ipaddress`, `useragent`, `lastactivity`, `language`, `adminsession`) VALUES ('" . $db->escape($s) . "', '" . (int)$id . "', '" . $db->escape($result['ipaddress']) . "', '" . $db->escape($result['useragent']) . "', '" . time() . "', '" . $db->escape($result['language']) . "', '0')");
			$log->logAction(ADM_ACTION, LOG_INFO, "switched user and is now '" . $destination_user . "'");
			redirectTo('customer_index.php', Array('s' => $s));
		}
		else
		{
			redirectTo('index.php', Array('action' => 'login'));
		}
	}
	elseif($action == 'delete'
	       && $id != 0)
	{
		$result = $db->query_first("SELECT * FROM `" . TABLE_PANEL_CUSTOMERS . "` WHERE `customerid`='" . (int)$id . "' " . ($userinfo['customers_see_all'] ? '' : " AND `adminid` = '" . $db->escape($userinfo['adminid']) . "' "));

		if($result['loginname'] != '')
		{
			$enable_billing_data_edit = ($result['servicestart_date'] == '0000-00-00' || ($result['interval_payment'] == CONST_BILLING_INTERVALPAYMENT_PREPAID && calculateDayDifference(time(), $result['lastinvoiced_date']) >= 0));

			if($enable_billing_data_edit !== true)
			{
				standard_error('service_still_active');
				exit;
			}

			if(isset($_POST['send'])
			   && $_POST['send'] == 'send')
			{
				$databases = $db->query("SELECT * FROM " . TABLE_PANEL_DATABASES . " WHERE customerid='" . (int)$id . "' ORDER BY `dbserver`");
				$db_root = new db($sql_root[0]['host'], $sql_root[0]['user'], $sql_root[0]['password'], '');
				unset($db_root->password);
				$last_dbserver = 0;

				while($row_database = $db->fetch_array($databases))
				{
					if($last_dbserver != $row_database['dbserver'])
					{
						$db_root->query('FLUSH PRIVILEGES;');
						$db_root->close();
						$db_root = new db($sql_root[$row_database['dbserver']]['host'], $sql_root[$row_database['dbserver']]['user'], $sql_root[$row_database['dbserver']]['password'], '');
						unset($db_root->password);
						$last_dbserver = $row_database['dbserver'];
					}

					foreach(array_unique(explode(',', $settings['system']['mysql_access_host'])) as $mysql_access_host)
					{
						$mysql_access_host = trim($mysql_access_host);
						$db_root->query('REVOKE ALL PRIVILEGES ON * . * FROM `' . $db_root->escape($row_database['databasename']) . '`@`' . $db_root->escape($mysql_access_host) . '`');
						$db_root->query('REVOKE ALL PRIVILEGES ON `' . str_replace('_', '\_', $db_root->escape($row_database['databasename'])) . '` . * FROM `' . $db_root->escape($row_database['databasename']) . '`@`' . $db_root->escape($mysql_access_host) . '`');
						$db_root->query('DELETE FROM `mysql`.`user` WHERE `User` = "' . $db_root->escape($row_database['databasename']) . '" AND `Host` = "' . $db_root->escape($mysql_access_host) . '"');
					}

					$db_root->query('DROP DATABASE IF EXISTS `' . $db_root->escape($row_database['databasename']) . '`');
				}

				$db_root->query('FLUSH PRIVILEGES;');
				$db_root->close();
				$db->query("DELETE FROM `" . TABLE_PANEL_CUSTOMERS . "` WHERE `customerid`='" . (int)$id . "'");
				$db->query("DELETE FROM `" . TABLE_PANEL_DATABASES . "` WHERE `customerid`='" . (int)$id . "'");
				$db->query("DELETE FROM `" . TABLE_PANEL_DOMAINS . "` WHERE `customerid`='" . (int)$id . "'");
				$domains_deleted = $db->affected_rows();
				$db->query("DELETE FROM `" . TABLE_PANEL_HTPASSWDS . "` WHERE `customerid`='" . (int)$id . "'");
				$db->query("DELETE FROM `" . TABLE_PANEL_SESSIONS . "` WHERE `userid`='" . (int)$id . "' AND `adminsession` = '0'");
				$db->query("DELETE FROM `" . TABLE_PANEL_TRAFFIC . "` WHERE `customerid`='" . (int)$id . "'");
				$db->query("DELETE FROM `" . TABLE_MAIL_USERS . "` WHERE `customerid`='" . (int)$id . "'");
				$db->query("DELETE FROM `" . TABLE_MAIL_VIRTUAL . "` WHERE `customerid`='" . (int)$id . "'");
				$db->query("DELETE FROM `" . TABLE_FTP_GROUPS . "` WHERE `customerid`='" . (int)$id . "'");
				$db->query("DELETE FROM `" . TABLE_FTP_USERS . "` WHERE `customerid`='" . (int)$id . "'");
				$db->query("DELETE FROM `" . TABLE_MAIL_AUTORESPONDER . "` WHERE `customerid`='" . (int)$id . "'");
				$admin_update_query = "UPDATE `" . TABLE_PANEL_ADMINS . "` SET `customers_used` = `customers_used` - 1 ";
				$admin_update_query.= ", `domains_used` = `domains_used` - 0" . (int)($domains_deleted - $result['subdomains_used']);

				if($result['mysqls'] != '-1')
				{
					$admin_update_query.= ", `mysqls_used` = `mysqls_used` - 0" . (int)$result['mysqls'];
				}

				if($result['emails'] != '-1')
				{
					$admin_update_query.= ", `emails_used` = `emails_used` - 0" . (int)$result['emails'];
				}

				if($result['email_accounts'] != '-1')
				{
					$admin_update_query.= ", `email_accounts_used` = `email_accounts_used` - 0" . (int)$result['email_accounts'];
				}

				if($result['email_forwarders'] != '-1')
				{
					$admin_update_query.= ", `email_forwarders_used` = `email_forwarders_used` - 0" . (int)$result['email_forwarders'];
				}

				if($result['email_quota'] != '-1')
				{
					$admin_update_query.= ", `email_quota_used` = `email_quota_used` - 0" . (int)$result['email_quota'];
				}

				if($result['subdomains'] != '-1')
				{
					$admin_update_query.= ", `subdomains_used` = `subdomains_used` - 0" . (int)$result['subdomains'];
				}

				if($result['ftps'] != '-1')
				{
					$admin_update_query.= ", `ftps_used` = `ftps_used` - 0" . (int)$result['ftps'];
				}

				if($result['tickets'] != '-1')
				{
					$admin_update_query.= ", `tickets_used` = `tickets_used` - 0" . (int)$result['tickets'];
				}

				if(($result['diskspace'] / 1024) != '-1')
				{
					$admin_update_query.= ", `diskspace_used` = `diskspace_used` - 0" . (int)$result['diskspace'];
				}

				$admin_update_query.= " WHERE `adminid` = '" . (int)$result['adminid'] . "'";
				$db->query($admin_update_query);
				$log->logAction(ADM_ACTION, LOG_INFO, "deleted user '" . $result['loginname'] . "'");
				inserttask('1');
				inserttask('4');
				redirectTo($filename, Array('page' => $page, 's' => $s));
			}
			else
			{
				ask_yesno('admin_customer_reallydelete', $filename, array('id' => $id, 'page' => $page, 'action' => $action), $result['loginname']);
			}
		}
	}
	elseif($action == 'add')
	{
		if($userinfo['customers_used'] < $userinfo['customers']
		   || $userinfo['customers'] == '-1')
		{
			if(isset($_POST['send'])
			   && $_POST['send'] == 'send')
			{
				$name = validate($_POST['name'], 'name');
				$firstname = validate($_POST['firstname'], 'first name');
				$title = validate($_POST['title'], 'title');
				$company = validate($_POST['company'], 'company');
				$street = validate($_POST['street'], 'street');
				$zipcode = validate($_POST['zipcode'], 'zipcode', '/^[0-9 \-A-Z]*$/');
				$city = validate($_POST['city'], 'city');
				$country = validate($_POST['country'], 'country');
				$phone = validate($_POST['phone'], 'phone', '/^[0-9\- \+\(\)\/]*$/');
				$fax = validate($_POST['fax'], 'fax', '/^[0-9\- \+\(\)\/]*$/');
				$email = $idna_convert->encode(validate($_POST['email'], 'email'));
				$customernumber = validate($_POST['customernumber'], 'customer number', '/^[A-Za-z0-9 \-]*$/Di');
				$taxid = validate($_POST['taxid'], html_entity_decode($lng['customer']['taxid']), '/^[A-Z]{2,3}[\s ]*[a-zA-Z0-9\-]+$/i', 'stringformaterror', '');
				$def_language = validate($_POST['def_language'], 'default language');
				$diskspace = intval_ressource($_POST['diskspace']);

				if(isset($_POST['diskspace_ul']))
				{
					$diskspace = - 1;
				}

				$traffic = doubleval_ressource($_POST['traffic']);

				if(isset($_POST['traffic_ul']))
				{
					$traffic = - 1;
				}

				$subdomains = intval_ressource($_POST['subdomains']);

				if(isset($_POST['subdomains_ul']))
				{
					$subdomains = - 1;
				}

				$emails = intval_ressource($_POST['emails']);

				if(isset($_POST['emails_ul']))
				{
					$emails = - 1;
				}

				$email_accounts = intval_ressource($_POST['email_accounts']);

				if(isset($_POST['email_accounts_ul']))
				{
					$email_accounts = - 1;
				}

				$email_forwarders = intval_ressource($_POST['email_forwarders']);

				if(isset($_POST['email_forwarders_ul']))
				{
					$email_forwarders = - 1;
				}

				if($settings['system']['mail_quota_enabled'] == '1')
				{
					$email_quota = validate($_POST['email_quota'], 'email_quota', '/^\d+$/', 'vmailquotawrong', array('0', ''));

					if(isset($_POST['email_quota_ul']))
					{
						$email_quota = - 1;
					}
				}
				else
				{
					$email_quota = - 1;
				}

				$email_imap = intval_ressource($_POST['email_imap']);
				$email_pop3 = intval_ressource($_POST['email_pop3']);
				$ftps = intval_ressource($_POST['ftps']);

				if(isset($_POST['ftps_ul']))
				{
					$ftps = - 1;
				}

				$tickets = ($settings['ticket']['enabled'] == 1 ? intval_ressource($_POST['tickets']) : 0);

				if(isset($_POST['tickets_ul'])
				   && $settings['ticket']['enabled'] == '1')
				{
					$tickets = - 1;
				}

				$mysqls = intval_ressource($_POST['mysqls']);

				if(isset($_POST['mysqls_ul']))
				{
					$mysqls = - 1;
				}

				if($settings['aps']['aps_active'] == '1')
				{
					$number_of_aps_packages = intval_ressource($_POST['number_of_aps_packages']);

					if(isset($_POST['number_of_aps_packages_ul']))
					{
						$number_of_aps_packages = - 1;
					}
				}
				else
				{
					$number_of_aps_packages = 0;
				}

				$createstdsubdomain = intval($_POST['createstdsubdomain']);
				$password = validate($_POST['customer_password'], 'password');
				$sendpassword = intval($_POST['sendpassword']);
				$phpenabled = intval($_POST['phpenabled']);
				$diskspace = $diskspace * 1024;
				$traffic = $traffic * 1024 * 1024;

				if($userinfo['edit_billingdata'] == '1'
				   && $settings['billing']['activate_billing'] == '1')
				{
					$contract_date = validate($_POST['contract_date'], html_entity_decode($lng['customer']['contract_date']), '/^(19|20)\d\d[-](0[1-9]|1[012])[-](0[1-9]|[12][0-9]|3[01])$/', '', array('0000-00-00', '0', ''));
					$contract_number = validate($_POST['contract_number'], html_entity_decode($lng['customer']['contract_number']));
					$included_domains_qty = intval($_POST['included_domains_qty']);
					$included_domains_tld = $idna_convert->encode(validate($_POST['included_domains_tld'], html_entity_decode($lng['customer']['included_domains'])));
					$additional_traffic_fee = doubleval(str_replace(',', '.', $_POST['additional_traffic_fee']));
					$additional_traffic_unit = doubleval_ressource($_POST['additional_traffic_unit']) * 1024 * 1024;
					$additional_diskspace_fee = doubleval(str_replace(',', '.', $_POST['additional_diskspace_fee']));
					$additional_diskspace_unit = doubleval_ressource($_POST['additional_diskspace_unit']) * 1024;
					$interval_fee = doubleval(str_replace(',', '.', $_POST['interval_fee']));
					$interval_length = intval($_POST['interval_length']);
					$interval_type = (in_array($_POST['interval_type'], getIntervalTypes('array')) ? $_POST['interval_type'] : 'm');
					$setup_fee = doubleval(str_replace(',', '.', $_POST['setup_fee']));
					$calc_tax = intval($_POST['calc_tax']);
					$term_of_payment = validate($_POST['term_of_payment'], html_entity_decode($lng['customer']['term_of_payment']), '/^[0-9]+$/', '', array(0, ''));
					$payment_every = intval($_POST['payment_every']);
					$payment_method = intval($_POST['payment_method']);
					$bankaccount_holder = validate($_POST['bankaccount_holder'], html_entity_decode($lng['customer']['bankaccount_holder']), '/^[^\0]*$/');
					$bankaccount_number = validate($_POST['bankaccount_number'], html_entity_decode($lng['customer']['bankaccount_number']));
					$bankaccount_blz = validate($_POST['bankaccount_blz'], html_entity_decode($lng['customer']['bankaccount_blz']));
					$bankaccount_bank = validate($_POST['bankaccount_bank'], html_entity_decode($lng['customer']['bankaccount_bank']));
					$service_active = intval($_POST['service_active']);
					$interval_payment = intval($_POST['interval_payment']);
					$additional_service_description = validate($_POST['additional_service_description'], $lng['customer']['additional_service_description']);

					if(isset($_POST['taxclass'])
					   && intval($_POST['taxclass']) != 0
					   && isset($taxclasses[$_POST['taxclass']]))
					{
						$taxclass = $_POST['taxclass'];
					}
					else
					{
						$taxclass = '0';
					}

					if($service_active == 1
					   && isset($_POST['servicestart_date']))
					{
						$servicestart_date = validate($_POST['servicestart_date'], html_entity_decode($lng['service']['start_date']), '/^(19|20)\d\d[-](0[1-9]|1[012])[-](0[1-9]|[12][0-9]|3[01])$/', '', array('0000-00-00', '0', ''));
					}
				}
				else
				{
					$contract_date = '';
					$contract_number = '';
					$included_domains_qty = 0;
					$included_domains_tld = '';
					$additional_traffic_fee = 0;
					$additional_traffic_unit = 0;
					$additional_diskspace_fee = 0;
					$additional_diskspace_unit = 0;
					$interval_fee = 0;
					$interval_length = 0;
					$interval_type = 'm';
					$setup_fee = 0;
					$calc_tax = 0;
					$term_of_payment = '';
					$payment_every = 0;
					$payment_method = 0;
					$bankaccount_holder = '';
					$bankaccount_number = '';
					$bankaccount_blz = '';
					$bankaccount_bank = '';
					$service_active = 0;
					$interval_payment = 0;
					$additional_service_description = '';
					$taxclass = 0;
					$servicestart_date = 0;
				}

				if(((($userinfo['diskspace_used'] + $diskspace) > $userinfo['diskspace']) && ($userinfo['diskspace'] / 1024) != '-1')
				   || ((($userinfo['mysqls_used'] + $mysqls) > $userinfo['mysqls']) && $userinfo['mysqls'] != '-1')
				   || ((($userinfo['emails_used'] + $emails) > $userinfo['emails']) && $userinfo['emails'] != '-1')
				   || ((($userinfo['email_accounts_used'] + $email_accounts) > $userinfo['email_accounts']) && $userinfo['email_accounts'] != '-1')
				   || ((($userinfo['email_forwarders_used'] + $email_forwarders) > $userinfo['email_forwarders']) && $userinfo['email_forwarders'] != '-1')
				   || ((($userinfo['email_quota_used'] + $email_quota) > $userinfo['email_quota']) && $userinfo['email_quota'] != '-1' && $settings['system']['mail_quota_enabled'] == '1')
				   || ((($userinfo['ftps_used'] + $ftps) > $userinfo['ftps']) && $userinfo['ftps'] != '-1')
				   || ((($userinfo['tickets_used'] + $tickets) > $userinfo['tickets']) && $userinfo['tickets'] != '-1')
				   || ((($userinfo['subdomains_used'] + $subdomains) > $userinfo['subdomains']) && $userinfo['subdomains'] != '-1')
				   || ((($userinfo['aps_packages_used'] + $number_of_aps_packages) > $userinfo['aps_packages']) && $userinfo['aps_packages'] != '-1' && $settings['aps']['aps_active'] == '1')
				   || (($diskspace / 1024) == '-1' && ($userinfo['diskspace'] / 1024) != '-1')
				   || ($mysqls == '-1' && $userinfo['mysqls'] != '-1')
				   || ($emails == '-1' && $userinfo['emails'] != '-1')
				   || ($email_accounts == '-1' && $userinfo['email_accounts'] != '-1')
				   || ($email_forwarders == '-1' && $userinfo['email_forwarders'] != '-1')
				   || ($email_quota == '-1' && $userinfo['email_quota'] != '-1' && $settings['system']['mail_quota_enabled'] == '1')
				   || ($ftps == '-1' && $userinfo['ftps'] != '-1')
				   || ($tickets == '-1' && $userinfo['tickets'] != '-1')
				   || ($subdomains == '-1' && $userinfo['subdomains'] != '-1')
				   || ($number_of_aps_packages == '-1' && $userinfo['aps_packages'] != '-1'))
				{
					standard_error('youcantallocatemorethanyouhave');
					exit;
				}

				// Either $name and $firstname or the $company must be inserted

				if($name == ''
				   && $company == '')
				{
					standard_error(array('stringisempty', 'myname'));
				}
				elseif($firstname == ''
				       && $company == '')
				{
					standard_error(array('stringisempty', 'myfirstname'));
				}
				elseif($email == '')
				{
					standard_error(array('stringisempty', 'emailadd'));
				}
				elseif(!validateEmail($email))
				{
					standard_error('emailiswrong', $email);
				}
				else
				{
					if(isset($_POST['loginname'])
					   && $_POST['loginname'] != '')
					{
						$accountnumber = intval($settings['system']['lastaccountnumber']);
						$loginname = validate($_POST['loginname'], 'loginname', '/^[a-z0-9\-_]+$/i');

						// Accounts which match systemaccounts are not allowed, filtering them

						if(preg_match('/^' . preg_quote($settings['customer']['accountprefix'], '/') . '([0-9]+)/', $loginname))
						{
							standard_error('loginnameissystemaccount', $settings['customer']['accountprefix']);
						}
					}
					else
					{
						$accountnumber = intval($settings['system']['lastaccountnumber']) + 1;
						$loginname = $settings['customer']['accountprefix'] . $accountnumber;
					}

					// Check if the account already exists

					$loginname_check = $db->query_first("SELECT `loginname` FROM `" . TABLE_PANEL_CUSTOMERS . "` WHERE `loginname` = '" . $db->escape($loginname) . "'");
					$loginname_check_admin = $db->query_first("SELECT `loginname` FROM `" . TABLE_PANEL_ADMINS . "` WHERE `loginname` = '" . $db->escape($loginname) . "'");

					if(strtolower($loginname_check['loginname']) == strtolower($loginname)
					   || strtolower($loginname_check_admin['loginname']) == strtolower($loginname))
					{
						standard_error('loginnameexists', $loginname);
					}
					elseif(!validateUsername($loginname, $settings['panel']['unix_names'], 14 - strlen($settings['customer']['mysqlprefix'])))
					{
						standard_error('loginnameiswrong', $loginname);
					}

					$guid = intval($settings['system']['lastguid']) + 1;
					$documentroot = makeCorrectDir($settings['system']['documentroot_prefix'] . '/' . $loginname);

					if($service_active == 1)
					{
						$service_active = '1';

						if(!isset($servicestart_date)
						   || $servicestart_date == '0000-00-00')
						{
							$servicestart_date = date('Y-m-d');
						}
					}
					else
					{
						$service_active = '0';
						$servicestart_date = '0000-00-00';
					}

					if($calc_tax != '1')
					{
						$calc_tax = '0';
					}

					if($interval_payment != '1')
					{
						$interval_payment = '0';
					}

					if(!isset($lng['customer']['payment_methods'][$payment_method]))
					{
						$payment_method = 0;
					}

					if(file_exists($documentroot))
					{
						standard_error('documentrootexists', $documentroot);
					}

					if($createstdsubdomain != '1')
					{
						$createstdsubdomain = '0';
					}

					if($phpenabled != '0')
					{
						$phpenabled = '1';
					}

					if($password == '')
					{
						$password = substr(md5(uniqid(microtime(), 1)), 12, 6);
					}

					$result = $db->query("INSERT INTO `" . TABLE_PANEL_CUSTOMERS . "` (`adminid`, `loginname`, `password`, `name`, `firstname`, `title`, `company`, `street`, `zipcode`, `city`, `country`, `phone`, `fax`, `email`, `customernumber`, `def_language`, `documentroot`, `guid`, `diskspace`, `traffic`, `subdomains`, `emails`, `email_accounts`, `email_forwarders`, `email_quota`, `ftps`, `tickets`, `mysqls`, `standardsubdomain`, `phpenabled`, `imap`, `pop3`, `contract_date`, `contract_number`, `taxid`, `included_domains_qty`, `included_domains_tld`, `additional_traffic_fee`, `additional_traffic_unit`,`additional_diskspace_fee`, `additional_diskspace_unit`, `interval_fee`, `interval_length`, `interval_type`, `interval_payment`, `setup_fee`, `taxclass`, `service_active`, `servicestart_date`, `term_of_payment`, `calc_tax`, `payment_every`, `payment_method`, `bankaccount_holder`, `bankaccount_number`, `bankaccount_blz`, `bankaccount_bank`, `additional_service_description`, `aps_packages`)  VALUES ('" . (int)$userinfo['adminid'] . "', '" . $db->escape($loginname) . "', '" . md5($password) . "', '" . $db->escape($name) . "', '" . $db->escape($firstname) . "', '" . $db->escape($title) . "', '" . $db->escape($company) . "', '" . $db->escape($street) . "', '" . $db->escape($zipcode) . "', '" . $db->escape($city) . "', '" . $db->escape($country) . "', '" . $db->escape($phone) . "', '" . $db->escape($fax) . "', '" . $db->escape($email) . "', '" . $db->escape($customernumber) . "','" . $db->escape($def_language) . "', '" . $db->escape($documentroot) . "', '" . $db->escape($guid) . "', '" . $db->escape($diskspace) . "', '" . $db->escape($traffic) . "', '" . $db->escape($subdomains) . "', '" . $db->escape($emails) . "', '" . $db->escape($email_accounts) . "', '" . $db->escape($email_forwarders) . "', '" . $db->escape($email_quota) . "', '" . $db->escape($ftps) . "', '" . $db->escape($tickets) . "', '" . $db->escape($mysqls) . "', '0', '" . $db->escape($phpenabled) . "', '" . $db->escape($email_imap) . "', '" . $db->escape($email_pop3) . "', '" . $db->escape($contract_date) . "', '" . $db->escape($contract_number) . "', '" . $db->escape($taxid) . "', '" . $db->escape($included_domains_qty) . "', '" . $db->escape($included_domains_tld) . "', '" . $db->escape($additional_traffic_fee) . "', '" . $db->escape($additional_traffic_unit) . "','" . $db->escape($additional_diskspace_fee) . "', '" . $db->escape($additional_diskspace_unit) . "','" . $db->escape($interval_fee) . "', '" . $db->escape($interval_length) . "', '" . $db->escape($interval_type) . "', '" . $db->escape($interval_payment) . "', '" . $db->escape($setup_fee) . "', '" . $db->escape($taxclass) . "', '" . $db->escape($service_active) . "', '" . $db->escape($servicestart_date) . "', '" . $db->escape($term_of_payment) . "', '" . $db->escape($calc_tax) . "', '" . $db->escape($payment_every) . "', '" . $db->escape($payment_method) . "', '" . $db->escape($bankaccount_holder) . "', '" . $db->escape($bankaccount_number) . "', '" . $db->escape($bankaccount_blz) . "', '" . $db->escape($bankaccount_bank) . "', '" . $db->escape($additional_service_description) . "', " . (int)$number_of_aps_packages . ")");
					$customerid = $db->insert_id();
					$admin_update_query = "UPDATE `" . TABLE_PANEL_ADMINS . "` SET `customers_used` = `customers_used` + 1";

					if($mysqls != '-1')
					{
						$admin_update_query.= ", `mysqls_used` = `mysqls_used` + 0" . (int)$mysqls;
					}

					if($emails != '-1')
					{
						$admin_update_query.= ", `emails_used` = `emails_used` + 0" . (int)$emails;
					}

					if($email_accounts != '-1')
					{
						$admin_update_query.= ", `email_accounts_used` = `email_accounts_used` + 0" . (int)$email_accounts;
					}

					if($email_forwarders != '-1')
					{
						$admin_update_query.= ", `email_forwarders_used` = `email_forwarders_used` + 0" . (int)$email_forwarders;
					}

					if($email_quota != '-1')
					{
						$admin_update_query.= ", `email_quota_used` = `email_quota_used` + 0" . (int)$email_quota;
					}

					if($subdomains != '-1')
					{
						$admin_update_query.= ", `subdomains_used` = `subdomains_used` + 0" . (int)$subdomains;
					}

					if($ftps != '-1')
					{
						$admin_update_query.= ", `ftps_used` = `ftps_used` + 0" . (int)$ftps;
					}

					if($tickets != '-1'
					   && $settings['ticket']['enabled'] == 1)
					{
						$admin_update_query.= ", `tickets_used` = `tickets_used` + 0" . (int)$tickets;
					}

					if(($diskspace / 1024) != '-1')
					{
						$admin_update_query.= ", `diskspace_used` = `diskspace_used` + 0" . (int)$diskspace;
					}

					if($number_of_aps_packages != '-1')
					{
						$admin_update_query.= ", `aps_packages_used` = `aps_packages_used` + 0" . (int)$number_of_aps_packages;
					}

					$admin_update_query.= " WHERE `adminid` = '" . (int)$userinfo['adminid'] . "'";
					$db->query($admin_update_query);
					$db->query("UPDATE `" . TABLE_PANEL_SETTINGS . "` " . "SET `value`='" . $db->escape($guid) . "' " . "WHERE `settinggroup`='system' AND `varname`='lastguid'");

					if($accountnumber != intval($settings['system']['lastaccountnumber']))
					{
						$db->query("UPDATE `" . TABLE_PANEL_SETTINGS . "` " . "SET `value`='" . $db->escape($accountnumber) . "' " . "WHERE `settinggroup`='system' AND `varname`='lastaccountnumber'");
					}

					$log->logAction(ADM_ACTION, LOG_INFO, "added user '" . $loginname . "'");
					inserttask('2', $loginname, $guid, $guid);

					// Add htpasswd for the webalizer stats

					if(CRYPT_STD_DES == 1)
					{
						$saltfordescrypt = substr(md5(uniqid(microtime(), 1)), 4, 2);
						$htpasswdPassword = crypt($password, $saltfordescrypt);
					}
					else
					{
						$htpasswdPassword = crypt($password);
					}

					$db->query("INSERT INTO `" . TABLE_PANEL_HTPASSWDS . "` " . "(`customerid`, `username`, `password`, `path`) " . "VALUES ('" . (int)$customerid . "', '" . $db->escape($loginname) . "', '" . $db->escape($htpasswdPassword) . "', '" . $db->escape(makeCorrectDir($documentroot . '/webalizer/')) . "')");
					$log->logAction(ADM_ACTION, LOG_NOTICE, "automatically added webalizer htpasswd for user '" . $loginname . "'");

					if($settings['system']['awstats_enabled'] == '1')
					{
						$db->query("INSERT INTO `" . TABLE_PANEL_HTPASSWDS . "` " . "(`customerid`, `username`, `password`, `path`) " . "VALUES ('" . (int)$customerid . "', '" . $db->escape($loginname) . "', '" . $db->escape($htpasswdPassword) . "', '" . $db->escape(makeCorrectDir($documentroot . '/awstats/')) . "')");
						$log->logAction(ADM_ACTION, LOG_NOTICE, "automatically added awstats htpasswd for user '" . $loginname . "'");
					}

					inserttask('1');
					$result = $db->query("INSERT INTO `" . TABLE_FTP_USERS . "` " . "(`customerid`, `username`, `password`, `homedir`, `login_enabled`, `uid`, `gid`) " . "VALUES ('" . (int)$customerid . "', '" . $db->escape($loginname) . "', ENCRYPT('" . $db->escape($password) . "'), '" . $db->escape($documentroot) . "', 'y', '" . (int)$guid . "', '" . (int)$guid . "')");
					$result = $db->query("INSERT INTO `" . TABLE_FTP_GROUPS . "` " . "(`customerid`, `groupname`, `gid`, `members`) " . "VALUES ('" . (int)$customerid . "', '" . $db->escape($loginname) . "', '" . $db->escape($guid) . "', '" . $db->escape($loginname) . "')");
					$log->logAction(ADM_ACTION, LOG_NOTICE, "automatically added ftp-account for user '" . $loginname . "'");

					if($createstdsubdomain == '1')
					{
						$db->query("INSERT INTO `" . TABLE_PANEL_DOMAINS . "` " . "(`domain`, `customerid`, `adminid`, `parentdomainid`, `ipandport`, `documentroot`, `zonefile`, `isemaildomain`, `caneditdomain`, `openbasedir`, `safemode`, `speciallogfile`, `specialsettings`) " . "VALUES ('" . $db->escape($loginname . '.' . $settings['system']['hostname']) . "', '" . (int)$customerid . "', '" . (int)$userinfo['adminid'] . "', '-1', '" . $db->escape($settings['system']['defaultip']) . "', '" . $db->escape($documentroot) . "', '', '0', '0', '1', '1', '0', '')");
						$domainid = $db->insert_id();
						$db->query('UPDATE `' . TABLE_PANEL_CUSTOMERS . '` SET `standardsubdomain`=\'' . (int)$domainid . '\' WHERE `customerid`=\'' . (int)$customerid . '\'');
						$log->logAction(ADM_ACTION, LOG_NOTICE, "automatically added standardsubdomain for user '" . $loginname . "'");
						inserttask('1');
					}

					if($sendpassword == '1')
					{
						$replace_arr = array(
							'FIRSTNAME' => $firstname,
							'NAME' => $name,
							'TITLE' => $title,
							'COMPANY' => $company,
							'SALUTATION' => getCorrectUserSalutation(array('firstname' => $firstname, 'name' => $name, 'company' => $company)),
							'USERNAME' => $loginname,
							'PASSWORD' => $password
						);

						// Get mail templates from database; the ones from 'admin' are fetched for fallback

						$result = $db->query_first('SELECT `value` FROM `' . TABLE_PANEL_TEMPLATES . '` WHERE `adminid`=\'' . (int)$userinfo['adminid'] . '\' AND `language`=\'' . $db->escape($def_language) . '\' AND `templategroup`=\'mails\' AND `varname`=\'createcustomer_subject\'');
						$mail_subject = html_entity_decode(replace_variables((($result['value'] != '') ? $result['value'] : $lng['mails']['createcustomer']['subject']), $replace_arr));
						$result = $db->query_first('SELECT `value` FROM `' . TABLE_PANEL_TEMPLATES . '` WHERE `adminid`=\'' . (int)$userinfo['adminid'] . '\' AND `language`=\'' . $db->escape($def_language) . '\' AND `templategroup`=\'mails\' AND `varname`=\'createcustomer_mailbody\'');
						$mail_body = html_entity_decode(replace_variables((($result['value'] != '') ? $result['value'] : $lng['mails']['createcustomer']['mailbody']), $replace_arr));

						$mail->From = $userinfo['email'];
						$mail->FromName = $userinfo['name'];
						$mail->Subject = $mail_subject;
						$mail->Body = $mail_body;
						$mail->AddAddress($email, getCorrectUserSalutation(array('firstname' => $firstname, 'name' => $name, 'company' => $company)));

						if(!$mail->Send())
						{
							if($mail->ErrorInfo != '')
							{
								$mailerr_msg = $mail->ErrorInfo;
							}
							else
							{
								$mailerr_msg = $email;
							}

							$log->logAction(ADM_ACTION, LOG_ERR, "Error sending mail: " . $mailerr_msg);
							standard_error('errorsendingmail', $email);
						}

						$mail->ClearAddresses();
						$log->logAction(ADM_ACTION, LOG_NOTICE, "automatically sent password to user '" . $loginname . "'");
					}

					redirectTo($filename, Array('page' => $page, 's' => $s));
				}
			}
			else
			{
				$language_options = '';

				while(list($language_file, $language_name) = each($languages))
				{
					$language_options.= makeoption($language_name, $language_file, $userinfo['def_language'], true);
				}

				$diskspace_ul = makecheckbox('diskspace_ul', $lng['customer']['unlimited'], '-1', false, '0', true, true);
				$traffic_ul = makecheckbox('traffic_ul', $lng['customer']['unlimited'], '-1', false, '0', true, true);
				$subdomains_ul = makecheckbox('subdomains_ul', $lng['customer']['unlimited'], '-1', false, '0', true, true);
				$emails_ul = makecheckbox('emails_ul', $lng['customer']['unlimited'], '-1', false, '0', true, true);
				$email_accounts_ul = makecheckbox('email_accounts_ul', $lng['customer']['unlimited'], '-1', false, '0', true, true);
				$email_forwarders_ul = makecheckbox('email_forwarders_ul', $lng['customer']['unlimited'], '-1', false, '0', true, true);
				$email_quota_ul = makecheckbox('email_quota_ul', $lng['customer']['unlimited'], '-1', false, '0', true, true);
				$ftps_ul = makecheckbox('ftps_ul', $lng['customer']['unlimited'], '-1', false, '0', true, true);
				$tickets_ul = makecheckbox('tickets_ul', $lng['customer']['unlimited'], '-1', false, '0', true, true);
				$mysqls_ul = makecheckbox('mysqls_ul', $lng['customer']['unlimited'], '-1', false, '0', true, true);
				$number_of_aps_packages_ul = makecheckbox('number_of_aps_packages_ul', $lng['customer']['unlimited'], '-1', false, '0', true, true);
				$createstdsubdomain = makeyesno('createstdsubdomain', '1', '0', '1');
				$email_imap = makeyesno('email_imap', '1', '0', '1');
				$email_pop3 = makeyesno('email_pop3', '1', '0', '1');
				$sendpassword = makeyesno('sendpassword', '1', '0', '1');
				$phpenabled = makeyesno('phpenabled', '1', '0', '1');
				$contract_date = date('Y-m-d');
				$interval_type = getIntervalTypes('option', 'm');
				$service_active = makeyesno('service_active', '1', '0', '0');
				$interval_payment = makeoption($lng['service']['interval_payment_prepaid'], '0', '0', true) . makeoption($lng['service']['interval_payment_postpaid'], '1', '0', true);
				$payment_method = '';
				foreach($lng['customer']['payment_methods'] as $payment_method_id => $payment_method_name)
				{
					$payment_method.= makeoption($payment_method_name, $payment_method_id, 0, true);
				}

				$calc_tax = makeyesno('calc_tax', '1', '0', '1');
				eval("echo \"" . getTemplate("customers/customers_add") . "\";");
			}
		}
	}
	elseif($action == 'edit'
	       && $id != 0)
	{
		$result = $db->query_first("SELECT * FROM `" . TABLE_PANEL_CUSTOMERS . "` WHERE `customerid`='" . (int)$id . "' " . ($userinfo['customers_see_all'] ? '' : " AND `adminid` = '" . (int)$userinfo['adminid'] . "' "));

		if($result['loginname'] != '')
		{
			$override_billing_data_edit = (isset($_GET['override_billing_data_edit']) && $_GET['override_billing_data_edit'] == '1') || (isset($_POST['override_billing_data_edit']) && $_POST['override_billing_data_edit'] == '1');
			$enable_billing_data_edit = ($result['servicestart_date'] == '0000-00-00' || ($result['interval_payment'] == CONST_BILLING_INTERVALPAYMENT_PREPAID && calculateDayDifference(time(), $result['lastinvoiced_date']) >= 0) || $override_billing_data_edit === true);

			if(isset($_POST['send'])
			   && $_POST['send'] == 'send')
			{
				$name = validate($_POST['name'], 'name');
				$firstname = validate($_POST['firstname'], 'first name');
				$title = validate($_POST['title'], 'title');
				$company = validate($_POST['company'], 'company');
				$street = validate($_POST['street'], 'street');
				$zipcode = validate($_POST['zipcode'], 'zipcode', '/^[0-9 \-A-Z]*$/');
				$city = validate($_POST['city'], 'city');
				$country = validate($_POST['country'], 'country');
				$phone = validate($_POST['phone'], 'phone', '/^[0-9\- \+\(\)\/]*$/');
				$fax = validate($_POST['fax'], 'fax', '/^[0-9\- \+\(\)\/]*$/');
				$email = $idna_convert->encode(validate($_POST['email'], 'email'));
				$customernumber = validate($_POST['customernumber'], 'customer number', '/^[A-Za-z0-9 \-]*$/Di');
				$taxid = validate($_POST['taxid'], html_entity_decode($lng['customer']['taxid']), '/^[A-Z]{2,3}[\s ]*[a-zA-Z0-9\-]+$/i', 'stringformaterror', '');
				$def_language = validate($_POST['def_language'], 'default language');
				$password = validate($_POST['customer_password'], 'new password');
				$diskspace = intval_ressource($_POST['diskspace']);
				$diskspace = intval_ressource($_POST['diskspace']);

				if(isset($_POST['diskspace_ul']))
				{
					$diskspace = - 1;
				}

				$traffic = doubleval_ressource($_POST['traffic']);

				if(isset($_POST['traffic_ul']))
				{
					$traffic = - 1;
				}

				$subdomains = intval_ressource($_POST['subdomains']);

				if(isset($_POST['subdomains_ul']))
				{
					$subdomains = - 1;
				}

				$emails = intval_ressource($_POST['emails']);

				if(isset($_POST['emails_ul']))
				{
					$emails = - 1;
				}

				$email_accounts = intval_ressource($_POST['email_accounts']);

				if(isset($_POST['email_accounts_ul']))
				{
					$email_accounts = - 1;
				}

				$email_forwarders = intval_ressource($_POST['email_forwarders']);

				if(isset($_POST['email_forwarders_ul']))
				{
					$email_forwarders = - 1;
				}

				if($settings['system']['mail_quota_enabled'] == '1')
				{
					$email_quota = validate($_POST['email_quota'], 'email_quota', '/^\d+$/', 'vmailquotawrong', array('0', ''));

					if(isset($_POST['email_quota_ul']))
					{
						$email_quota = - 1;
					}
				}
				else
				{
					$email_quota = - 1;
				}

				$email_imap = intval_ressource($_POST['email_imap']);
				$email_pop3 = intval_ressource($_POST['email_pop3']);
				$ftps = intval_ressource($_POST['ftps']);

				if(isset($_POST['ftps_ul']))
				{
					$ftps = - 1;
				}

				$tickets = ($settings['ticket']['enabled'] == 1 ? intval_ressource($_POST['tickets']) : 0);

				if(isset($_POST['tickets_ul'])
				   && $settings['ticket']['enabled'] == '1')
				{
					$tickets = - 1;
				}

				$mysqls = intval_ressource($_POST['mysqls']);

				if(isset($_POST['mysqls_ul']))
				{
					$mysqls = - 1;
				}

				$number_of_aps_packages = intval_ressource($_POST['number_of_aps_packages']);

				if(isset($_POST['number_of_aps_packages_ul']))
				{
					$number_of_aps_packages = - 1;
				}

				$createstdsubdomain = intval($_POST['createstdsubdomain']);
				$deactivated = intval($_POST['deactivated']);
				$phpenabled = intval($_POST['phpenabled']);
				$diskspace = $diskspace * 1024;
				$traffic = $traffic * 1024 * 1024;

				if($userinfo['edit_billingdata'] == '1'
				   && $settings['billing']['activate_billing'] == '1')
				{
					$contract_date = validate($_POST['contract_date'], html_entity_decode($lng['customer']['contract_date']), '/^(19|20)\d\d[-](0[1-9]|1[012])[-](0[1-9]|[12][0-9]|3[01])$/', '', array('0000-00-00', '0', ''));
					$contract_number = validate($_POST['contract_number'], html_entity_decode($lng['customer']['contract_number']));
					$calc_tax = intval($_POST['calc_tax']);
					$term_of_payment = validate($_POST['term_of_payment'], html_entity_decode($lng['customer']['term_of_payment']), '/^[0-9]+$/', '', array(0, ''));
					$payment_method = intval($_POST['payment_method']);
					$bankaccount_holder = validate($_POST['bankaccount_holder'], html_entity_decode($lng['customer']['bankaccount_holder']), '/^[^\0]*$/');
					$bankaccount_number = validate($_POST['bankaccount_number'], html_entity_decode($lng['customer']['bankaccount_number']));
					$bankaccount_blz = validate($_POST['bankaccount_blz'], html_entity_decode($lng['customer']['bankaccount_blz']));
					$bankaccount_bank = validate($_POST['bankaccount_bank'], html_entity_decode($lng['customer']['bankaccount_bank']));
					$service_active = intval($_POST['service_active']);
					$interval_payment = intval($_POST['interval_payment']);
					$additional_service_description = validate($_POST['additional_service_description'], $lng['customer']['additional_service_description']);
				}
				else
				{
					$contract_date = $result['contract_date'];
					$contract_number = $result['contract_date'];
					$calc_tax = $result['calc_tax'];
					$term_of_payment = $result['term_of_payment'];
					$payment_method = $result['payment_method'];
					$bankaccount_holder = $result['bankaccount_holder'];
					$bankaccount_number = $result['bankaccount_number'];
					$bankaccount_blz = $result['bankaccount_blz'];
					$bankaccount_bank = $result['bankaccount_bank'];
					$service_active = $result['service_active'];
					$interval_payment = $result['interval_payment'];
					$additional_service_description = $result['additional_service_description'];
				}

				if($enable_billing_data_edit === true
				   && $userinfo['edit_billingdata'] == '1'
				   && $settings['billing']['activate_billing'] == '1')
				{
					$interval_fee = doubleval(str_replace(',', '.', $_POST['interval_fee']));
					$interval_length = intval($_POST['interval_length']);
					$interval_type = (in_array($_POST['interval_type'], getIntervalTypes('array')) ? $_POST['interval_type'] : 'm');
					$setup_fee = doubleval(str_replace(',', '.', $_POST['setup_fee']));
					$payment_every = intval($_POST['payment_every']);
					$additional_traffic_fee = doubleval(str_replace(',', '.', $_POST['additional_traffic_fee']));
					$additional_traffic_unit = doubleval_ressource($_POST['additional_traffic_unit']) * 1024 * 1024;
					$additional_diskspace_fee = doubleval(str_replace(',', '.', $_POST['additional_diskspace_fee']));
					$additional_diskspace_unit = doubleval_ressource($_POST['additional_diskspace_unit']) * 1024;
					$included_domains_qty = intval($_POST['included_domains_qty']);
					$included_domains_tld = $idna_convert->encode(validate($_POST['included_domains_tld'], html_entity_decode($lng['customer']['included_domains'])));

					if(isset($_POST['taxclass'])
					   && intval($_POST['taxclass']) != 0
					   && isset($taxclasses[$_POST['taxclass']]))
					{
						$taxclass = $_POST['taxclass'];
					}
					else
					{
						$taxclass = '0';
					}

					if($result['service_active'] == 0
					   && $service_active == 0)
					{
						$servicestart_date = $result['servicestart_date'];
					}
					else
					{
						$servicestart_date = validate($_POST['servicestart_date'], html_entity_decode($lng['service']['start_date']), '/^(19|20)\d\d[-](0[1-9]|1[012])[-](0[1-9]|[12][0-9]|3[01])$/', '', array('0000-00-00', '0', ''));
					}

					$serviceend_date = $result['serviceend_date'];
				}
				else
				{
					$interval_fee = $result['interval_fee'];
					$interval_length = $result['interval_length'];
					$interval_type = $result['interval_type'];
					$setup_fee = $result['setup_fee'];
					$taxclass = $result['taxclass'];
					$payment_every = $result['payment_every'];
					$additional_traffic_fee = $result['additional_traffic_fee'];
					$additional_traffic_unit = $result['additional_traffic_unit'];
					$additional_diskspace_fee = $result['additional_diskspace_fee'];
					$additional_diskspace_unit = $result['additional_diskspace_unit'];
					$included_domains_qty = $result['included_domains_qty'];
					$included_domains_tld = $result['included_domains_tld'];
					$servicestart_date = $result['servicestart_date'];
					$serviceend_date = $result['serviceend_date'];
				}

				if(((($userinfo['diskspace_used'] + $diskspace - $result['diskspace']) > $userinfo['diskspace']) && ($userinfo['diskspace'] / 1024) != '-1')
				   || ((($userinfo['mysqls_used'] + $mysqls - $result['mysqls']) > $userinfo['mysqls']) && $userinfo['mysqls'] != '-1')
				   || ((($userinfo['emails_used'] + $emails - $result['emails']) > $userinfo['emails']) && $userinfo['emails'] != '-1')
				   || ((($userinfo['email_accounts_used'] + $email_accounts - $result['email_accounts']) > $userinfo['email_accounts']) && $userinfo['email_accounts'] != '-1')
				   || ((($userinfo['email_forwarders_used'] + $email_forwarders - $result['email_forwarders']) > $userinfo['email_forwarders']) && $userinfo['email_forwarders'] != '-1')
				   || ((($userinfo['email_quota_used'] + $email_quota - $result['email_quota']) > $userinfo['email_quota']) && $userinfo['email_quota'] != '-1' && $settings['system']['mail_quota_enabled'] == '1')
				   || ((($userinfo['ftps_used'] + $ftps - $result['ftps']) > $userinfo['ftps']) && $userinfo['ftps'] != '-1')
				   || ((($userinfo['tickets_used'] + $tickets - $result['tickets']) > $userinfo['tickets']) && $userinfo['tickets'] != '-1')
				   || ((($userinfo['subdomains_used'] + $subdomains - $result['subdomains']) > $userinfo['subdomains']) && $userinfo['subdomains'] != '-1')
				   || (($diskspace / 1024) == '-1' && ($userinfo['diskspace'] / 1024) != '-1')
				   || ((($userinfo['aps_packages'] + $number_of_aps_packages - $result['aps_packages']) > $userinfo['aps_packages']) && $userinfo['aps_packages'] != '-1' && $settings['aps']['aps_active'] == '1')
				   || ($mysqls == '-1' && $userinfo['mysqls'] != '-1')
				   || ($emails == '-1' && $userinfo['emails'] != '-1')
				   || ($email_accounts == '-1' && $userinfo['email_accounts'] != '-1')
				   || ($email_forwarders == '-1' && $userinfo['email_forwarders'] != '-1')
				   || ($email_quota == '-1' && $userinfo['email_quota'] != '-1' && $settings['system']['mail_quota_enabled'] == '1')
				   || ($ftps == '-1' && $userinfo['ftps'] != '-1')
				   || ($tickets == '-1' && $userinfo['tickets'] != '-1')
				   || ($subdomains == '-1' && $userinfo['subdomains'] != '-1')
				   || ($number_of_aps_packages == '-1' && $userinfo['aps_packages'] != '-1'))
				{
					standard_error('youcantallocatemorethanyouhave');
					exit;
				}

				// Either $name and $firstname or the $company must be inserted

				if($name == ''
				   && $company == '')
				{
					standard_error(array('stringisempty', 'myname'));
				}
				elseif($firstname == ''
				       && $company == '')
				{
					standard_error(array('stringisempty', 'myfirstname'));
				}
				elseif($email == '')
				{
					standard_error(array('stringisempty', 'emailadd'));
				}
				elseif(!validateEmail($email))
				{
					standard_error('emailiswrong', $email);
				}
				else
				{
					if($password != '')
					{
						$password = md5($password);
					}
					else
					{
						$password = $result['password'];
					}

					if($service_active == 1)
					{
						// Check whether service is already started

						$service_active = '1';

						if(($result['servicestart_date'] == '0000-00-00')
						   && ($servicestart_date == '0000-00-00' || $servicestart_date == '0' || $servicestart_date == ''))
						{
							// We are starting the service now.

							$servicestart_date = date('Y-m-d');
						}

						// Check whether service has previously ended

						if($result['serviceend_date'] != '0000-00-00')
						{
							// We are continuing the service.

							$serviceend_date = '0000-00-00';
						}
					}
					else
					{
						$service_active = '0';

						// Check whether service has started and hasn't yet ended

						if(($result['servicestart_date'] != '0000-00-00')
						   && ($result['serviceend_date'] == '0000-00-00'))
						{
							// We are ending the service now.

							$serviceend_date = date('Y-m-d');

							// We don't need to set servicestart_date to 0 because the billing module will do this after the final invoice
						}
					}

					if($interval_payment != '1')
					{
						$interval_payment = '0';
					}

					if($calc_tax != '1')
					{
						$calc_tax = '0';
					}

					if(!isset($lng['customer']['payment_methods'][$payment_method]))
					{
						$payment_method = 0;
					}

					if($createstdsubdomain != '1')
					{
						$createstdsubdomain = '0';
					}

					if($createstdsubdomain == '1'
					   && $result['standardsubdomain'] == '0')
					{
						$db->query("INSERT INTO `" . TABLE_PANEL_DOMAINS . "` " . "(`domain`, `customerid`, `adminid`, `parentdomainid`, `ipandport`, `documentroot`, `zonefile`, `isemaildomain`, `caneditdomain`, `openbasedir`, `safemode`, `speciallogfile`, `specialsettings`) " . "VALUES ('" . $db->escape($result['loginname'] . '.' . $settings['system']['hostname']) . "', '" . (int)$result['customerid'] . "', '" . (int)$userinfo['adminid'] . "', '-1', '" . $db->escape($settings['system']['defaultip']) . "', '" . $db->escape($result['documentroot']) . "', '', '0', '0', '1', '1', '0', '')");
						$domainid = $db->insert_id();
						$db->query('UPDATE `' . TABLE_PANEL_CUSTOMERS . '` SET `standardsubdomain`=\'' . (int)$domainid . '\' WHERE `customerid`=\'' . (int)$result['customerid'] . '\'');
						$log->logAction(ADM_ACTION, LOG_NOTICE, "automatically added standardsubdomain for user '" . $result['loginname'] . "'");
						inserttask('1');
					}

					if($createstdsubdomain == '0'
					   && $result['standardsubdomain'] != '0')
					{
						$db->query('DELETE FROM `' . TABLE_PANEL_DOMAINS . '` WHERE `id`=\'' . (int)$result['standardsubdomain'] . '\'');
						$db->query('UPDATE `' . TABLE_PANEL_CUSTOMERS . '` SET `standardsubdomain`=\'0\' WHERE `customerid`=\'' . (int)$result['customerid'] . '\'');
						$log->logAction(ADM_ACTION, LOG_NOTICE, "automatically deleted standardsubdomain for user '" . $result['loginname'] . "'");
						inserttask('1');
					}

					if($deactivated != '1')
					{
						$deactivated = '0';
					}

					if($phpenabled != '0')
					{
						$phpenabled = '1';
					}

					if($phpenabled != $result['phpenabled'])
					{
						inserttask('1');
					}

					if($deactivated != $result['deactivated'])
					{
						$db->query("UPDATE `" . TABLE_MAIL_USERS . "` SET `postfix`='" . (($deactivated) ? 'N' : 'Y') . "', `pop3`='" . (($deactivated) ? '0' : '1') . "', `imap`='" . (($deactivated) ? '0' : '1') . "' WHERE `customerid`='" . (int)$id . "'");
						$db->query("UPDATE `" . TABLE_FTP_USERS . "` SET `login_enabled`='" . (($deactivated) ? 'N' : 'Y') . "' WHERE `customerid`='" . (int)$id . "'");
						$db->query("UPDATE `" . TABLE_PANEL_DOMAINS . "` SET `deactivated`='" . (int)$deactivated . "' WHERE `customerid`='" . (int)$id . "'");
						$log->logAction(ADM_ACTION, LOG_INFO, "deactivated user '" . $result['loginname'] . "'");
						inserttask('1');
					}

					// Disable or enable POP3 Login for customers Mail Accounts

					if($email_pop3 != $result['pop3'])
					{
						$db->query("UPDATE `" . TABLE_MAIL_USERS . "` SET `pop3`='" . (int)$email_pop3 . "' WHERE `customerid`='" . (int)$id . "'");
					}

					// Disable or enable IMAP Login for customers Mail Accounts

					if($email_imap != $result['imap'])
					{
						$db->query("UPDATE `" . TABLE_MAIL_USERS . "` SET `imap`='" . (int)$email_imap . "' WHERE `customerid`='" . (int)$id . "'");
					}

					$db->query("UPDATE `" . TABLE_PANEL_CUSTOMERS . "` SET `name`='" . $db->escape($name) . "', `firstname`='" . $db->escape($firstname) . "', `title`='" . $db->escape($title) . "', `company`='" . $db->escape($company) . "', `street`='" . $db->escape($street) . "', `zipcode`='" . $db->escape($zipcode) . "', `city`='" . $db->escape($city) . "', `country`='" . $db->escape($country) . "', `phone`='" . $db->escape($phone) . "', `fax`='" . $db->escape($fax) . "', `email`='" . $db->escape($email) . "', `customernumber`='" . $db->escape($customernumber) . "', `def_language`='" . $db->escape($def_language) . "', `password` = '" . $password . "', `diskspace`='" . $db->escape($diskspace) . "', `traffic`='" . $db->escape($traffic) . "', `subdomains`='" . $db->escape($subdomains) . "', `emails`='" . $db->escape($emails) . "', `email_accounts` = '" . $db->escape($email_accounts) . "', `email_forwarders`='" . $db->escape($email_forwarders) . "', `ftps`='" . $db->escape($ftps) . "', `tickets`='" . $db->escape($tickets) . "', `mysqls`='" . $db->escape($mysqls) . "', `deactivated`='" . $db->escape($deactivated) . "', `phpenabled`='" . $db->escape($phpenabled) . "', `email_quota`='" . $db->escape($email_quota) . "', `imap`='" . $db->escape($email_imap) . "', `pop3`='" . $db->escape($email_pop3) . "', `contract_date`='" . $db->escape($contract_date) . "', `contract_number`='" . $db->escape($contract_number) . "', `taxid`='" . $db->escape($taxid) . "', `included_domains_qty`='" . $db->escape($included_domains_qty) . "', `included_domains_tld`='" . $db->escape($included_domains_tld) . "', `additional_traffic_fee`='" . $db->escape($additional_traffic_fee) . "', `additional_traffic_unit`='" . $db->escape($additional_traffic_unit) . "', `additional_diskspace_fee`='" . $db->escape($additional_diskspace_fee) . "', `additional_diskspace_unit`='" . $db->escape($additional_diskspace_unit) . "', `interval_fee`='" . $db->escape($interval_fee) . "', `interval_length`='" . $db->escape($interval_length) . "', `interval_type`='" . $db->escape($interval_type) . "', `interval_payment`='" . $db->escape($interval_payment) . "', `setup_fee`='" . $db->escape($setup_fee) . "', `taxclass`='" . $db->escape($taxclass) . "', `service_active`='" . $db->escape($service_active) . "', `servicestart_date`='" . $db->escape($servicestart_date) . "', `serviceend_date`='" . $db->escape($serviceend_date) . "', `term_of_payment`='" . $db->escape($term_of_payment) . "', `calc_tax`='" . $db->escape($calc_tax) . "', `payment_every`='" . $db->escape($payment_every) . "', `payment_method`='" . $db->escape($payment_method) . "', `bankaccount_holder`='" . $db->escape($bankaccount_holder) . "', `bankaccount_number`='" . $db->escape($bankaccount_number) . "', `bankaccount_blz`='" . $db->escape($bankaccount_blz) . "', `bankaccount_bank`='" . $db->escape($bankaccount_bank) . "', `additional_service_description`='" . $db->escape($additional_service_description) . "', `aps_packages`=" . (int)$number_of_aps_packages . " WHERE `customerid`='" . (int)$id . "'");
					$admin_update_query = "UPDATE `" . TABLE_PANEL_ADMINS . "` SET `customers_used` = `customers_used` ";

					if($mysqls != '-1'
					   || $result['mysqls'] != '-1')
					{
						$admin_update_query.= ", `mysqls_used` = `mysqls_used` ";

						if($mysqls != '-1')
						{
							$admin_update_query.= " + 0" . (int)$mysqls . " ";
						}

						if($result['mysqls'] != '-1')
						{
							$admin_update_query.= " - 0" . (int)$result['mysqls'] . " ";
						}
					}

					if($emails != '-1'
					   || $result['emails'] != '-1')
					{
						$admin_update_query.= ", `emails_used` = `emails_used` ";

						if($emails != '-1')
						{
							$admin_update_query.= " + 0" . (int)$emails . " ";
						}

						if($result['emails'] != '-1')
						{
							$admin_update_query.= " - 0" . (int)$result['emails'] . " ";
						}
					}

					if($email_accounts != '-1'
					   || $result['email_accounts'] != '-1')
					{
						$admin_update_query.= ", `email_accounts_used` = `email_accounts_used` ";

						if($email_accounts != '-1')
						{
							$admin_update_query.= " + 0" . (int)$email_accounts . " ";
						}

						if($result['email_accounts'] != '-1')
						{
							$admin_update_query.= " - 0" . (int)$result['email_accounts'] . " ";
						}
					}

					if($email_forwarders != '-1'
					   || $result['email_forwarders'] != '-1')
					{
						$admin_update_query.= ", `email_forwarders_used` = `email_forwarders_used` ";

						if($email_forwarders != '-1')
						{
							$admin_update_query.= " + 0" . (int)$email_forwarders . " ";
						}

						if($result['email_forwarders'] != '-1')
						{
							$admin_update_query.= " - 0" . (int)$result['email_forwarders'] . " ";
						}
					}

					if($email_quota != '-1'
					   || $result['email_quota'] != '-1')
					{
						$admin_update_query.= ", `email_quota_used` = `email_quota_used` ";

						if($email_quota != '-1')
						{
							$admin_update_query.= " + 0" . (int)$email_quota . " ";
						}

						if($result['email_quota'] != '-1')
						{
							$admin_update_query.= " - 0" . (int)$result['email_quota'] . " ";
						}
					}

					if($subdomains != '-1'
					   || $result['subdomains'] != '-1')
					{
						$admin_update_query.= ", `subdomains_used` = `subdomains_used` ";

						if($subdomains != '-1')
						{
							$admin_update_query.= " + 0" . (int)$subdomains . " ";
						}

						if($result['subdomains'] != '-1')
						{
							$admin_update_query.= " - 0" . (int)$result['subdomains'] . " ";
						}
					}

					if($ftps != '-1'
					   || $result['ftps'] != '-1')
					{
						$admin_update_query.= ", `ftps_used` = `ftps_used` ";

						if($ftps != '-1')
						{
							$admin_update_query.= " + 0" . (int)$ftps . " ";
						}

						if($result['ftps'] != '-1')
						{
							$admin_update_query.= " - 0" . (int)$result['ftps'] . " ";
						}
					}

					if($tickets != '-1'
					   || $result['tickets'] != '-1')
					{
						$admin_update_query.= ", `tickets_used` = `tickets_used` ";

						if($tickets != '-1')
						{
							$admin_update_query.= " + 0" . (int)$tickets . " ";
						}

						if($result['tickets'] != '-1')
						{
							$admin_update_query.= " - 0" . (int)$result['tickets'] . " ";
						}
					}

					if(($diskspace / 1024) != '-1'
					   || ($result['diskspace'] / 1024) != '-1')
					{
						$admin_update_query.= ", `diskspace_used` = `diskspace_used` ";

						if(($diskspace / 1024) != '-1')
						{
							$admin_update_query.= " + 0" . (int)$diskspace . " ";
						}

						if(($result['diskspace'] / 1024) != '-1')
						{
							$admin_update_query.= " - 0" . (int)$result['diskspace'] . " ";
						}
					}

					if($number_of_aps_packages != '-1'
					   || $result['aps_packages'] != '-1')
					{
						$admin_update_query.= ", `aps_packages_used` = `aps_packages_used` ";

						if($number_of_aps_packages != '-1')
						{
							$admin_update_query.= " + 0" . (int)$number_of_aps_packages . " ";
						}

						if($result['aps_packages'] != '-1')
						{
							$admin_update_query.= " - 0" . (int)$result['aps_packages'] . " ";
						}
					}

					$admin_update_query.= " WHERE `adminid` = '" . (int)$result['adminid'] . "'";
					$db->query($admin_update_query);
					$log->logAction(ADM_ACTION, LOG_INFO, "edited user '" . $result['loginname'] . "'");
					$redirect_props = Array(
						'page' => $page,
						's' => $s
					);

					if(isset($_POST['enable_billing_data_edit']))
					{
						$redirect_props['action'] = $action;
						$redirect_props['id'] = $id;
						$redirect_props['override_billing_data_edit'] = '1';
					}

					redirectTo($filename, $redirect_props);
				}
			}
			else
			{
				$language_options = '';

				while(list($language_file, $language_name) = each($languages))
				{
					$language_options.= makeoption($language_name, $language_file, $result['def_language'], true);
				}

				$result['traffic'] = round($result['traffic'] / (1024 * 1024), $settings['panel']['decimal_places']);
				$result['diskspace'] = round($result['diskspace'] / 1024, $settings['panel']['decimal_places']);
				$result['email'] = $idna_convert->decode($result['email']);
				$diskspace_ul = makecheckbox('diskspace_ul', $lng['customer']['unlimited'], '-1', false, $result['diskspace'], true, true);

				if($result['diskspace'] == '-1')
				{
					$result['diskspace'] = '';
				}

				$traffic_ul = makecheckbox('traffic_ul', $lng['customer']['unlimited'], '-1', false, $result['traffic'], true, true);

				if($result['traffic'] == '-1')
				{
					$result['traffic'] = '';
				}

				$subdomains_ul = makecheckbox('subdomains_ul', $lng['customer']['unlimited'], '-1', false, $result['subdomains'], true, true);

				if($result['subdomains'] == '-1')
				{
					$result['subdomains'] = '';
				}

				$emails_ul = makecheckbox('emails_ul', $lng['customer']['unlimited'], '-1', false, $result['emails'], true, true);

				if($result['emails'] == '-1')
				{
					$result['emails'] = '';
				}

				$email_accounts_ul = makecheckbox('email_accounts_ul', $lng['customer']['unlimited'], '-1', false, $result['email_accounts'], true, true);

				if($result['email_accounts'] == '-1')
				{
					$result['email_accounts'] = '';
				}

				$email_forwarders_ul = makecheckbox('email_forwarders_ul', $lng['customer']['unlimited'], '-1', false, $result['email_forwarders'], true, true);

				if($result['email_forwarders'] == '-1')
				{
					$result['email_forwarders'] = '';
				}

				$email_quota_ul = makecheckbox('email_quota_ul', $lng['customer']['unlimited'], '-1', false, $result['email_quota'], true, true);

				if($result['email_quota'] == '-1')
				{
					$result['email_quota'] = '';
				}

				$ftps_ul = makecheckbox('ftps_ul', $lng['customer']['unlimited'], '-1', false, $result['ftps'], true, true);

				if($result['ftps'] == '-1')
				{
					$result['ftps'] = '';
				}

				$tickets_ul = makecheckbox('tickets_ul', $lng['customer']['unlimited'], '-1', false, $result['tickets'], true, true);

				if($result['tickets'] == '-1')
				{
					$result['tickets'] = '';
				}

				$mysqls_ul = makecheckbox('mysqls_ul', $lng['customer']['unlimited'], '-1', false, $result['mysqls'], true, true);

				if($result['mysqls'] == '-1')
				{
					$result['mysqls'] = '';
				}

				$number_of_aps_packages_ul = makecheckbox('number_of_aps_packages_ul', $lng['customer']['unlimited'], '-1', false, $result['aps_packages'], true, true);

				if($result['aps_packages'] == '-1')
				{
					$result['aps_packages'] = '';
				}

				$createstdsubdomain = makeyesno('createstdsubdomain', '1', '0', (($result['standardsubdomain'] != '0') ? '1' : '0'));
				$phpenabled = makeyesno('phpenabled', '1', '0', $result['phpenabled']);
				$deactivated = makeyesno('deactivated', '1', '0', $result['deactivated']);
				$email_imap = makeyesno('email_imap', '1', '0', $result['imap']);
				$email_pop3 = makeyesno('email_pop3', '1', '0', $result['pop3']);

				if($settings['billing']['activate_billing'] == '1')
				{
					$result['additional_traffic_unit'] = round($result['additional_traffic_unit'] / (1024 * 1024), 4);
					$result['additional_diskspace_unit'] = round($result['additional_diskspace_unit'] / (1024), 4);
					$result['included_domains_tld'] = $idna_convert->decode($result['included_domains_tld']);
					$interval_type = getIntervalTypes('option', $result['interval_type']);
					$service_active = ($result['service_active'] == '0' ? $lng['panel']['no'] : '') . ($result['service_active'] == '1' ? $lng['panel']['yes'] : '');
					$service_active_options = makeyesno('service_active', '1', '0', $result['service_active']);
					$interval_payment = ($result['interval_payment'] == '0' ? $lng['service']['interval_payment_prepaid'] : '') . ($result['interval_payment'] == '1' ? $lng['service']['interval_payment_postpaid'] : '');
					$interval_payment_options = makeoption($lng['service']['interval_payment_prepaid'], '0', $result['interval_payment'], true) . makeoption($lng['service']['interval_payment_postpaid'], '1', $result['interval_payment'], true);
					$calc_tax = ($result['calc_tax'] == '0' ? $lng['panel']['no'] : '') . ($result['calc_tax'] == '1' ? $lng['panel']['yes'] : '');
					$calc_tax_options = makeyesno('calc_tax', '1', '0', $result['calc_tax']);
					$payment_method = '';
					$taxclasses_option = '';
					foreach($lng['customer']['payment_methods'] as $payment_method_id => $payment_method_name)
					{
						$payment_method.= makeoption($payment_method_name, $payment_method_id, (int)$result['payment_method'], true);
					}

					foreach($taxclasses as $classid => $classname)
					{
						$taxclasses_option.= makeoption($classname, $classid, $result['taxclass']);
					}
				}

				$result = htmlentities_array($result);
				eval("echo \"" . getTemplate("customers/customers_edit") . "\";");
			}
		}
	}
	elseif($action == 'pdf'
	       && $settings['billing']['activate_billing'] == '1')
	{
		$result = $db->query_first("SELECT * FROM `" . TABLE_PANEL_CUSTOMERS . "` WHERE `customerid`='" . (int)$id . "' " . ($userinfo['customers_see_all'] ? '' : " AND `adminid` = '" . (int)$userinfo['adminid'] . "' "));

		if($result['loginname'] != '')
		{
			require_once ('./lib/billing_class_pdf.php');
			require_once ('./lib/billing_class_pdfcontract.php');
			$contract = new pdfContract();
			$contract->processData($result, $lng);
			$contract->outputBrowser();
		}
	}
}

?>

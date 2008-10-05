<?php

/**
 * This file is part of the SysCP project.
 * Copyright (c) 2003-2008 the SysCP Team (see authors).
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
	if($action == 'add'
	   || $action == 'edit')
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

		$service_categories = array();
		$service_categories_option = '';
		$service_categories_result = $db->query('SELECT `id`, `category_name`, `category_caption` FROM `' . TABLE_BILLING_SERVICE_CATEGORIES_ADMINS . '` WHERE `category_mode` = \'1\' ORDER BY `category_order` ASC ');

		while($service_categories_row = $db->fetch_array($service_categories_result))
		{
			if(isset($lng['billing']['categories'][$service_categories_row['category_caption']])
			   && $lng['billing']['categories'][$service_categories_row['category_caption']] != '')
			{
				$service_categories_row['category_caption'] = $lng['billing']['categories'][$service_categories_row['category_caption']];
			}

			$service_categories[$service_categories_row['id']] = $service_categories_row['category_caption'];
			$service_categories_option.= makeoption($service_categories_row['category_caption'], $service_categories_row['id']);
		}
	}

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
				$highlight_row = ($row['service_active'] != '1' && $settings['billing']['activate_billing'] == '1' && $settings['billing']['highlight_inactive'] == '1');
				$row['traffic_used'] = round($row['traffic_used']/(1024*1024), $settings['panel']['decimal_places']);
				$row['traffic'] = round($row['traffic']/(1024*1024), $settings['panel']['decimal_places']);
				$row['diskspace_used'] = round($row['diskspace_used']/1024, $settings['panel']['decimal_places']);
				$row['diskspace'] = round($row['diskspace']/1024, $settings['panel']['decimal_places']);
				$row = str_replace_array('-1', 'UL', $row, 'customers domains diskspace traffic mysqls emails email_accounts email_forwarders email_quota ftps subdomains tickets');
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
		$destination_admin = $result['loginname'];

		if($destination_admin != ''
		   && $result['adminid'] != $userinfo['userid'])
		{
			$result = $db->query_first("SELECT * FROM `" . TABLE_PANEL_SESSIONS . "` WHERE `userid`='" . (int)$userinfo['userid'] . "'");
			$s = md5(uniqid(microtime(), 1));
			$db->query("INSERT INTO `" . TABLE_PANEL_SESSIONS . "` (`hash`, `userid`, `ipaddress`, `useragent`, `lastactivity`, `language`, `adminsession`) VALUES ('" . $db->escape($s) . "', '" . (int)$id . "', '" . $db->escape($result['ipaddress']) . "', '" . $db->escape($result['useragent']) . "', '" . time() . "', '" . $db->escape($result['language']) . "', '1')");
			$log->logAction(ADM_ACTION, LOG_INFO, "switched adminuser and is now '" . $destination_admin . "'");
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

			if(isset($_POST['taxid'])
			   && $_POST['taxid'] != '')
			{
				$taxid = validate($_POST['taxid'], html_entity_decode($lng['customer']['taxid']), '/^[A-Z]{2,3}[\s ]*[a-zA-Z0-9\-]+$/i');
			}
			else
			{
				$taxid = '';
			}

			$loginname = validate($_POST['loginname'], 'loginname');
			$password = validate($_POST['admin_password'], 'password');
			$def_language = validate($_POST['def_language'], 'default language');
			$customers = intval_ressource($_POST['customers']);

			if(isset($_POST['customers_ul']))
			{
				$customers = -1;
			}

			$domains = intval_ressource($_POST['domains']);

			if(isset($_POST['domains_ul']))
			{
				$domains = -1;
			}

			$subdomains = intval_ressource($_POST['subdomains']);

			if(isset($_POST['subdomains_ul']))
			{
				$subdomains = -1;
			}

			$emails = intval_ressource($_POST['emails']);

			if(isset($_POST['emails_ul']))
			{
				$emails = -1;
			}

			$email_accounts = intval_ressource($_POST['email_accounts']);

			if(isset($_POST['email_accounts_ul']))
			{
				$email_accounts = -1;
			}

			$email_forwarders = intval_ressource($_POST['email_forwarders']);

			if(isset($_POST['email_forwarders_ul']))
			{
				$email_forwarders = -1;
			}

			if($settings['system']['mail_quota_enabled'] == '1')
			{
				$email_quota = intval_ressource($_POST['email_quota']);
				$email_quota_type = validate($_POST['email_quota_type'], 'quota type');

				if(isset($_POST['email_quota_ul']))
				{
					$email_quota = -1;
				}
			}
			else
			{
				$email_quota = '-1';
			}

			$ftps = intval_ressource($_POST['ftps']);

			if(isset($_POST['ftps_ul']))
			{
				$ftps = -1;
			}

			$tickets = intval_ressource($_POST['tickets']);

			if(isset($_POST['tickets_ul'])
			   && $settings['ticket']['enabled'] == '1')
			{
				$tickets = -1;
			}

			$mysqls = intval_ressource($_POST['mysqls']);

			if(isset($_POST['mysqls_ul']))
			{
				$mysqls = -1;
			}

			$customers_see_all = intval($_POST['customers_see_all']);
			$domains_see_all = intval($_POST['domains_see_all']);
			$caneditphpsettings = intval($_POST['caneditphpsettings']);
			$change_serversettings = intval($_POST['change_serversettings']);

			if($settings['billing']['activate_billing'] == '1')
			{
				$edit_billingdata = intval($_POST['edit_billingdata']);
			}
			else
			{
				$edit_billingdata = $result['edit_billingdata'];
			}

			$diskspace = intval_ressource($_POST['diskspace']);

			if(isset($_POST['diskspace_ul']))
			{
				$diskspace = -1;
			}

			$traffic = doubleval_ressource($_POST['traffic']);

			if(isset($_POST['traffic_ul']))
			{
				$traffic = -1;
			}
			$diskspace = $diskspace*1024;
			$traffic = $traffic*1024*1024;
			$email_quota = getQuotaInBytes($email_quota, $email_quota_type);

			$ipaddress = intval_ressource($_POST['ipaddress']);

			if($settings['billing']['activate_billing'] == '1')
			{
				$contract_date = validate($_POST['contract_date'], html_entity_decode($lng['customer']['contract_date']), '/^(19|20)\d\d[-](0[1-9]|1[012])[-](0[1-9]|[12][0-9]|3[01])$/');
				$contract_number = validate($_POST['contract_number'], html_entity_decode($lng['customer']['contract_number']));
				$included_domains_qty = intval($_POST['included_domains_qty']);
				$included_domains_tld = $idna_convert->encode(validate($_POST['included_domains_tld'], html_entity_decode($lng['customer']['included_domains'])));
				$additional_traffic_fee = doubleval(str_replace(',', '.', $_POST['additional_traffic_fee']));
				$additional_traffic_unit = doubleval_ressource($_POST['additional_traffic_unit'])*1024*1024;
				$additional_diskspace_fee = doubleval(str_replace(',', '.', $_POST['additional_diskspace_fee']));
				$additional_diskspace_unit = doubleval_ressource($_POST['additional_diskspace_unit'])*1024;
				$interval_fee = doubleval(str_replace(',', '.', $_POST['interval_fee']));
				$interval_length = intval($_POST['interval_length']);
				$interval_type = (in_array($_POST['interval_type'], getIntervalTypes('array')) ? $_POST['interval_type'] : 'm');
				$setup_fee = doubleval(str_replace(',', '.', $_POST['setup_fee']));
				$calc_tax = intval($_POST['calc_tax']);
				$term_of_payment = validate($_POST['term_of_payment'], html_entity_decode($lng['customer']['term_of_payment']), '/^[0-9]+$/');
				$payment_every = intval($_POST['payment_every']);
				$payment_method = intval($_POST['payment_method']);
				$bankaccount_holder = validate($_POST['bankaccount_holder'], html_entity_decode($lng['customer']['bankaccount_holder']), '/^[^\0]*$/');
				$bankaccount_number = validate($_POST['bankaccount_number'], html_entity_decode($lng['customer']['bankaccount_number']));
				$bankaccount_blz = validate($_POST['bankaccount_blz'], html_entity_decode($lng['customer']['bankaccount_blz']));
				$bankaccount_bank = validate($_POST['bankaccount_bank'], html_entity_decode($lng['customer']['bankaccount_bank']));
				$service_active = intval($_POST['service_active']);
				$interval_payment = intval($_POST['interval_payment']);

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
					if($_POST['servicestart_date'] == '0'
					   || $_POST['servicestart_date'] == '')
					{
						$servicestart_date = '0';
					}
					else
					{
						$servicestart_date = validate($_POST['servicestart_date'], html_entity_decode($lng['service']['start_date']), '/^(19|20)\d\d[-](0[1-9]|1[012])[-](0[1-9]|[12][0-9]|3[01])$/');
					}
				}

				$customer_categories_once_array = array();

				if(is_array($_POST['customer_categories_once']))
				{
					foreach($_POST['customer_categories_once'] as $service_category_id)
					{
						if(isset($service_categories[$service_category_id]))
						{
							$customer_categories_once_array[] = $service_category_id;
						}
					}

					$customer_categories_once = implode('-', $customer_categories_once_array);
				}
				else
				{
					$customer_categories_once = '';
				}

				$customer_categories_period_array = array();

				if(is_array($_POST['customer_categories_period']))
				{
					foreach($_POST['customer_categories_period'] as $service_category_id)
					{
						if(isset($service_categories[$service_category_id]))
						{
							$customer_categories_period_array[] = $service_category_id;
						}
					}

					$customer_categories_period = implode('-', $customer_categories_period_array);
				}
				else
				{
					$customer_categories_period = '';
				}
			}
			else
			{
				$contract_date = '0000-00-00';
				$contract_number = '0';
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
				$taxclass = '0';
				$servicestart_date = '0000-00-00';
				$customer_categories_once_array = array();
				$customer_categories_once = '';
				$customer_categories_period_array = array();
				$customer_categories_period = '';
			}

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
				if($service_active == 1)
				{
					$service_active = '1';

					if(!isset($servicestart_date)
					   || $servicestart_date == '0')
					{
						$servicestart_date = date('Y-m-d');
					}
				}
				else
				{
					$service_active = '0';
					$servicestart_date = '0';
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

				if($customers_see_all != '1')
				{
					$customers_see_all = '0';
				}

				if($domains_see_all != '1')
				{
					$domains_see_all = '0';
				}

				if($caneditphpsettings != '1')
				{
					$caneditphpsettings = '0';
				}

				if($change_serversettings != '1')
				{
					$change_serversettings = '0';
				}

				if($edit_billingdata != '1')
				{
					$edit_billingdata = '0';
				}

				$result = $db->query("INSERT INTO `" . TABLE_PANEL_ADMINS . "` (`loginname`, `password`, `name`, `firstname`, `title`, `company`, `street`, `zipcode`, `city`, `country`, `phone`, `fax`, `email`, `def_language`, `change_serversettings`, `edit_billingdata`, `customers`, `customers_see_all`, `domains`, `domains_see_all`, `caneditphpsettings`, `diskspace`, `traffic`, `subdomains`, `emails`, `email_accounts`, `email_forwarders`, `email_quota`, `ftps`, `tickets`, `mysqls`, `ip`, `contract_date`, `contract_number`, `taxid`, `additional_traffic_fee`, `additional_traffic_unit`,`additional_diskspace_fee`, `additional_diskspace_unit`, `interval_fee`, `interval_length`, `interval_type`, `interval_payment`, `setup_fee`, `taxclass`, `service_active`, `servicestart_date`, `term_of_payment`, `calc_tax`, `payment_every`, `payment_method`, `bankaccount_holder`, `bankaccount_number`, `bankaccount_blz`, `bankaccount_bank`, `customer_categories_once`, `customer_categories_period`)
					                   VALUES ('" . $db->escape($loginname) . "', '" . md5($password) . "', '" . $db->escape($name) . "', '" . $db->escape($firstname) . "', '" . $db->escape($title) . "', '" . $db->escape($company) . "', '" . $db->escape($street) . "', '" . $db->escape($zipcode) . "', '" . $db->escape($city) . "', '" . $db->escape($country) . "', '" . $db->escape($phone) . "', '" . $db->escape($fax) . "', '" . $db->escape($email) . "','" . $db->escape($def_language) . "', '" . $db->escape($change_serversettings) . "', '" . $db->escape($edit_billingdata) . "', '" . $db->escape($customers) . "', '" . $db->escape($customers_see_all) . "', '" . $db->escape($domains) . "', '" . $db->escape($domains_see_all) . "', '" . (int)$caneditphpsettings . "', '" . $db->escape($diskspace) . "', '" . $db->escape($traffic) . "', '" . $db->escape($subdomains) . "', '" . $db->escape($emails) . "', '" . $db->escape($email_accounts) . "', '" . $db->escape($email_forwarders) . "', '" . $db->escape($email_quota) . "', '" . $db->escape($ftps) . "', '" . $db->escape($tickets) . "', '" . $db->escape($mysqls) . "', '" . (int)$ipaddress . "', '" . $db->escape($contract_date) . "', '" . $db->escape($contract_number) . "', '" . $db->escape($taxid) . "', '" . $db->escape($additional_traffic_fee) . "', '" . $db->escape($additional_traffic_unit) . "','" . $db->escape($additional_diskspace_fee) . "', '" . $db->escape($additional_diskspace_unit) . "','" . $db->escape($interval_fee) . "', '" . $db->escape($interval_length) . "', '" . $db->escape($interval_type) . "', '" . $db->escape($interval_payment) . "', '" . $db->escape($setup_fee) . "', '" . $db->escape($taxclass) . "', '" . $db->escape($service_active) . "', '" . $db->escape($servicestart_date) . "', '" . $db->escape($term_of_payment) . "', '" . $db->escape($calc_tax) . "', '" . $db->escape($payment_every) . "', '" . $db->escape($payment_method) . "', '" . $db->escape($bankaccount_holder) . "', '" . $db->escape($bankaccount_number) . "', '" . $db->escape($bankaccount_blz) . "', '" . $db->escape($bankaccount_bank) . "', '" . $db->escape($customer_categories_once) . "', '" . $db->escape($customer_categories_period) . "')");
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

			$ipaddress = '';
			$ipaddress = makeoption($lng['admin']['allips'], "-1");
			$result = $db->query('SELECT `id`, `ip` FROM `' . TABLE_PANEL_IPSANDPORTS . '` ORDER BY `ip` ASC');

			while($row = $db->fetch_array($result))
			{
				$ipaddress.= makeoption($row['ip'], $row['id']);
			}

			$customers_ul = makecheckbox('customers_ul', $lng['customer']['unlimited'], '-1', false, '0', true, true);
			$diskspace_ul = makecheckbox('diskspace_ul', $lng['customer']['unlimited'], '-1', false, '0', true, true);
			$traffic_ul = makecheckbox('traffic_ul', $lng['customer']['unlimited'], '-1', false, '0', true, true);
			$domains_ul = makecheckbox('domains_ul', $lng['customer']['unlimited'], '-1', false, '0', true, true);
			$subdomains_ul = makecheckbox('subdomains_ul', $lng['customer']['unlimited'], '-1', false, '0', true, true);
			$emails_ul = makecheckbox('emails_ul', $lng['customer']['unlimited'], '-1', false, '0', true, true);
			$email_accounts_ul = makecheckbox('email_accounts_ul', $lng['customer']['unlimited'], '-1', false, '0', true, true);
			$email_forwarders_ul = makecheckbox('email_forwarders_ul', $lng['customer']['unlimited'], '-1', false, '0', true, true);
			$email_quota_ul = makecheckbox('email_quota_ul', $lng['customer']['unlimited'], '-1', false, '0', true, true);
			$ftps_ul = makecheckbox('ftps_ul', $lng['customer']['unlimited'], '-1', false, '0', true, true);
			$tickets_ul = makecheckbox('tickets_ul', $lng['customer']['unlimited'], '-1', false, '0', true, true);
			$mysqls_ul = makecheckbox('mysqls_ul', $lng['customer']['unlimited'], '-1', false, '0', true, true);
			$change_serversettings = makeyesno('change_serversettings', '1', '0', '0');
			$edit_billingdata = makeyesno('edit_billingdata', '1', '0', '0');
			$customers_see_all = makeyesno('customers_see_all', '1', '0', '0');
			$domains_see_all = makeyesno('domains_see_all', '1', '0', '0');
			$caneditphpsettings = makeyesno('caneditphpsettings', '1', '0', '0');
			$quota_type_option = makeQuotaOption();
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
			eval("echo \"" . getTemplate("admins/admins_add") . "\";");
		}
	}
	elseif($action == 'edit'
	       && $id != 0)
	{
		$result = $db->query_first("SELECT * FROM `" . TABLE_PANEL_ADMINS . "` WHERE `adminid`='" . (int)$id . "'");

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

				if(isset($_POST['taxid'])
				   && $_POST['taxid'] != '')
				{
					$taxid = validate($_POST['taxid'], html_entity_decode($lng['customer']['taxid']), '/^[A-Z]{2,3}[\s ]*[a-zA-Z0-9\-]+$/i');
				}
				else
				{
					$taxid = '';
				}

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
					$caneditphpsettings = $result['caneditphpsettings'];
					$change_serversettings = $result['change_serversettings'];
					$edit_billingdata = $result['edit_billingdata'];
					$diskspace = $result['diskspace'];
					$traffic = $result['traffic'];
					$ipaddress = $result['ip'];
				}
				else
				{
					$password = validate($_POST['admin_password'], 'new password');
					$def_language = validate($_POST['def_language'], 'default language');
					$deactivated = intval($_POST['deactivated']);
					$customers = intval_ressource($_POST['customers']);

					if(isset($_POST['customers_ul']))
					{
						$customers = -1;
					}

					$domains = intval_ressource($_POST['domains']);

					if(isset($_POST['domains_ul']))
					{
						$domains = -1;
					}

					$subdomains = intval_ressource($_POST['subdomains']);

					if(isset($_POST['subdomains_ul']))
					{
						$subdomains = -1;
					}

					$emails = intval_ressource($_POST['emails']);

					if(isset($_POST['emails_ul']))
					{
						$email = -1;
					}

					$email_accounts = intval_ressource($_POST['email_accounts']);

					if(isset($_POST['email_accounts_ul']))
					{
						$email_accounts = -1;
					}

					$email_forwarders = intval_ressource($_POST['email_forwarders']);

					if(isset($_POST['email_forwarders_ul']))
					{
						$email_forwarders = -1;
					}

					if($settings['system']['mail_quota_enabled'] == '1')
					{
						if(isset($_POST['email_quota_ul']))
						{
							$email_quota = -1;
						}
						else
						{
							$email_quota = intval_ressource($_POST['email_quota']);
						}
						$email_quota_type = validate($_POST['email_quota_type'], 'quota type');
					}
					else
					{
						$email_quota = -1;
					}

					$ftps = intval_ressource($_POST['ftps']);

					if(isset($_POST['ftps_ul']))
					{
						$ftps = -1;
					}

					$tickets = intval_ressource($_POST['tickets']);

					if(isset($_POST['tickets_ul']))
					{
						$tickets = -1;
					}

					$mysqls = intval_ressource($_POST['mysqls']);

					if(isset($_POST['mysqls_ul']))
					{
						$mysqls = -1;
					}

					$customers_see_all = intval($_POST['customers_see_all']);
					$domains_see_all = intval($_POST['domains_see_all']);
					$caneditphpsettings = intval($_POST['caneditphpsettings']);
					$change_serversettings = intval($_POST['change_serversettings']);

					if($settings['billing']['activate_billing'] == '1')
					{
						$edit_billingdata = intval($_POST['edit_billingdata']);
					}
					else
					{
						$edit_billingdata = $result['edit_billingdata'];
					}

					$diskspace = intval($_POST['diskspace']);

					if(isset($_POST['diskspace_ul']))
					{
						$diskspace = -1;
					}

					$traffic = doubleval_ressource($_POST['traffic']);

					if(isset($_POST['traffic_ul']))
					{
						$traffic = -1;
					}

					$diskspace = $diskspace*1024;
					$traffic = $traffic*1024*1024;

					$ipaddress = intval_ressource($_POST['ipaddress']);
				}

				if($settings['billing']['activate_billing'] == '1')
				{
					$contract_date = validate($_POST['contract_date'], html_entity_decode($lng['customer']['contract_date']), '/^(19|20)\d\d[-](0[1-9]|1[012])[-](0[1-9]|[12][0-9]|3[01])$/');
					$contract_number = validate($_POST['contract_number'], html_entity_decode($lng['customer']['contract_number']));
					$calc_tax = intval($_POST['calc_tax']);
					$term_of_payment = validate($_POST['term_of_payment'], html_entity_decode($lng['customer']['term_of_payment']), '/^[0-9]+$/');
					$payment_method = intval($_POST['payment_method']);
					$bankaccount_holder = validate($_POST['bankaccount_holder'], html_entity_decode($lng['customer']['bankaccount_holder']), '/^[^\0]*$/');
					$bankaccount_number = validate($_POST['bankaccount_number'], html_entity_decode($lng['customer']['bankaccount_number']));
					$bankaccount_blz = validate($_POST['bankaccount_blz'], html_entity_decode($lng['customer']['bankaccount_blz']));
					$bankaccount_bank = validate($_POST['bankaccount_bank'], html_entity_decode($lng['customer']['bankaccount_bank']));
					$service_active = intval($_POST['service_active']);
					$interval_payment = intval($_POST['interval_payment']);
					$customer_categories_once_array = array();

					if(is_array($_POST['customer_categories_once']))
					{
						foreach($_POST['customer_categories_once'] as $service_category_id)
						{
							if(isset($service_categories[$service_category_id]))
							{
								$customer_categories_once_array[] = $service_category_id;
							}
						}

						$customer_categories_once = implode('-', $customer_categories_once_array);
					}
					else
					{
						$customer_categories_once = '';
					}

					$customer_categories_period_array = array();

					if(is_array($_POST['customer_categories_period']))
					{
						foreach($_POST['customer_categories_period'] as $service_category_id)
						{
							if(isset($service_categories[$service_category_id]))
							{
								$customer_categories_period_array[] = $service_category_id;
							}
						}

						$customer_categories_period = implode('-', $customer_categories_period_array);
					}
					else
					{
						$customer_categories_period = '';
					}
				}
				else
				{
					$contract_date = $result['contract_date'];
					$contract_number = $result['contract_number'];
					$calc_tax = $result['calc_tax'];
					$term_of_payment = $result['term_of_payment'];
					$payment_method = $result['payment_method'];
					$bankaccount_holder = $result['bankaccount_holder'];
					$bankaccount_number = $result['bankaccount_number'];
					$bankaccount_blz = $result['bankaccount_blz'];
					$bankaccount_bank = $result['bankaccount_bank'];
					$service_active = $result['service_active'];
					$interval_payment = $result['interval_payment'];
					$customer_categories_once_array = explode('-', $result['customer_categories_once']);
					$customer_categories_once = $result['customer_categories_once'];
					$customer_categories_period_array = explode('-', $result['customer_categories_period']);
					$customer_categories_period = $result['customer_categories_period'];
				}

				if($enable_billing_data_edit === true
				   && $settings['billing']['activate_billing'] == '1')
				{
					$interval_fee = doubleval(str_replace(',', '.', $_POST['interval_fee']));
					$interval_length = intval($_POST['interval_length']);
					$interval_type = (in_array($_POST['interval_type'], getIntervalTypes('array')) ? $_POST['interval_type'] : 'm');
					$setup_fee = doubleval(str_replace(',', '.', $_POST['setup_fee']));
					$payment_every = intval($_POST['payment_every']);
					$additional_traffic_fee = doubleval(str_replace(',', '.', $_POST['additional_traffic_fee']));
					$additional_traffic_unit = doubleval_ressource($_POST['additional_traffic_unit'])*1024*1024;
					$additional_diskspace_fee = doubleval(str_replace(',', '.', $_POST['additional_diskspace_fee']));
					$additional_diskspace_unit = doubleval_ressource($_POST['additional_diskspace_unit'])*1024;

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
						if($_POST['servicestart_date'] == '0'
						   || $_POST['servicestart_date'] == '')
						{
							$servicestart_date = '0';
						}
						else
						{
							$servicestart_date = validate($_POST['servicestart_date'], html_entity_decode($lng['service']['start_date']), '/^(19|20)\d\d[-](0[1-9]|1[012])[-](0[1-9]|[12][0-9]|3[01])$/');
						}
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

				if($name == ''
				   && $company == '')
				{
					standard_error(array(
						'stringisempty',
						'myname'
					));
				}
				elseif($firstname == ''
				       && $company == '')
				{
					standard_error(array(
						'stringisempty',
						'myfirstname'
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
						   && ($servicestart_date == '0' || $servicestart_date == ''))
						{
							// We are starting the service now.

							$servicestart_date = date('Y-m-d');
						}

						// Check whether service has previously ended

						if($result['serviceend_date'] != '0000-00-00')
						{
							// We are continuing the service.

							$serviceend_date = '0';
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

					if($caneditphpsettings != '1')
					{
						$caneditphpsettings = '0';
					}

					if($change_serversettings != '1')
					{
						$change_serversettings = '0';
					}

					$email_quota = getQuotaInBytes($email_quota, $email_quota_type);
					$db->query("UPDATE `" . TABLE_PANEL_ADMINS . "` SET `name`='" . $db->escape($name) . "', `firstname`='" . $db->escape($firstname) . "', `title`='" . $db->escape($title) . "', `company`='" . $db->escape($company) . "', `street`='" . $db->escape($street) . "', `zipcode`='" . $db->escape($zipcode) . "', `city`='" . $db->escape($city) . "', `country`='" . $db->escape($country) . "', `phone`='" . $db->escape($phone) . "', `fax`='" . $db->escape($fax) . "', `email`='" . $db->escape($email) . "', `def_language`='" . $db->escape($def_language) . "', `change_serversettings` = '" . $db->escape($change_serversettings) . "', `edit_billingdata` = '" . $db->escape($edit_billingdata) . "', `customers` = '" . $db->escape($customers) . "', `customers_see_all` = '" . $db->escape($customers_see_all) . "', `domains` = '" . $db->escape($domains) . "', `domains_see_all` = '" . $db->escape($domains_see_all) . "', `caneditphpsettings` = '" . (int)$caneditphpsettings . "', `password` = '" . $password . "', `diskspace`='" . $db->escape($diskspace) . "', `traffic`='" . $db->escape($traffic) . "', `subdomains`='" . $db->escape($subdomains) . "', `emails`='" . $db->escape($emails) . "', `email_accounts` = '" . $db->escape($email_accounts) . "', `email_forwarders`='" . $db->escape($email_forwarders) . "', `email_quota`='" . $db->escape($email_quota) . "', `ftps`='" . $db->escape($ftps) . "', `tickets`='" . $db->escape($tickets) . "', `mysqls`='" . $db->escape($mysqls) . "', `ip`='" . (int)$ipaddress . "', `contract_date`='" . $db->escape($contract_date) . "', `contract_number`='" . $db->escape($contract_number) . "', `taxid`='" . $db->escape($taxid) . "', `additional_traffic_fee`='" . $db->escape($additional_traffic_fee) . "', `additional_traffic_unit`='" . $db->escape($additional_traffic_unit) . "', `additional_diskspace_fee`='" . $db->escape($additional_diskspace_fee) . "', `additional_diskspace_unit`='" . $db->escape($additional_diskspace_unit) . "', `interval_fee`='" . $db->escape($interval_fee) . "', `interval_length`='" . $db->escape($interval_length) . "', `interval_type`='" . $db->escape($interval_type) . "', `interval_payment`='" . $db->escape($interval_payment) . "', `setup_fee`='" . $db->escape($setup_fee) . "', `taxclass`='" . $db->escape($taxclass) . "', `service_active`='" . $db->escape($service_active) . "', `servicestart_date`='" . $db->escape($servicestart_date) . "', `serviceend_date`='" . $db->escape($serviceend_date) . "', `term_of_payment`='" . $db->escape($term_of_payment) . "', `calc_tax`='" . $db->escape($calc_tax) . "', `payment_every`='" . $db->escape($payment_every) . "', `payment_method`='" . $db->escape($payment_method) . "', `bankaccount_holder`='" . $db->escape($bankaccount_holder) . "', `bankaccount_number`='" . $db->escape($bankaccount_number) . "', `bankaccount_blz`='" . $db->escape($bankaccount_blz) . "', `bankaccount_bank`='" . $db->escape($bankaccount_bank) . "', `customer_categories_once`='" . $db->escape($customer_categories_once) . "', `customer_categories_period`='" . $db->escape($customer_categories_period) . "', `deactivated`='" . $db->escape($deactivated) . "' WHERE `adminid`='" . $db->escape($id) . "'");
					$log->logAction(ADM_ACTION, LOG_INFO, "edited admin '#" . $id . "'");
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
				$result['traffic'] = round($result['traffic']/(1024*1024), $settings['panel']['decimal_places']);
				$result['diskspace'] = round($result['diskspace']/1024, $settings['panel']['decimal_places']);
				$result['email'] = $idna_convert->decode($result['email']);
				$customers_ul = makecheckbox('customers_ul', $lng['customer']['unlimited'], '-1', false, $result['customers'], true, true);

				if($result['customers'] == '-1')
				{
					$result['customers'] = '';
				}

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

				$domains_ul = makecheckbox('domains_ul', $lng['customer']['unlimited'], '-1', false, $result['domains'], true, true);

				if($result['domains'] == '-1')
				{
					$result['domains'] = '';
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
					$quota_type_option = makeQuotaOption(getQuotaType($result['email_quota']));
					$result['email_quota'] = '';
				}
				else
				{
					$quota_type_option = makeQuotaOption(getQuotaType($result['email_quota']));
					$result['email_quota'] = getQuota($result['email_quota']);
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

				$language_options = '';

				while(list($language_file, $language_name) = each($languages))
				{
					$language_options.= makeoption($language_name, $language_file, $result['def_language'], true);
				}

				$ipaddress = '';
				$ipaddress = makeoption($lng['admin']['allips'], "-1");
				$result2 = $db->query('SELECT `id`, `ip` FROM `' . TABLE_PANEL_IPSANDPORTS . '` ORDER BY `ip` ASC');

				while($row = $db->fetch_array($result2))
				{
					if($row['ip'] == "")
					{
						continue;
					}

					if($row['id'] == $result['ip'])
					{
						$ipaddress.= makeoption($row['ip'], $row['id'], $result['ip']);
					}
					else
					{
						$ipaddress.= makeoption($row['ip'], $row['id']);
					}
				}

				$change_serversettings = makeyesno('change_serversettings', '1', '0', $result['change_serversettings']);
				$edit_billingdata = makeyesno('edit_billingdata', '1', '0', $result['edit_billingdata']);
				$customers_see_all = makeyesno('customers_see_all', '1', '0', $result['customers_see_all']);
				$domains_see_all = makeyesno('domains_see_all', '1', '0', $result['domains_see_all']);
				$caneditphpsettings = makeyesno('caneditphpsettings', '1', '0', $result['caneditphpsettings']);
				$deactivated = makeyesno('deactivated', '1', '0', $result['deactivated']);
				$result['additional_traffic_unit'] = round($result['additional_traffic_unit']/(1024*1024), 4);
				$result['additional_diskspace_unit'] = round($result['additional_diskspace_unit']/(1024), 4);
				$interval_type = getIntervalTypes('option', $result['interval_type']);
				$service_active = makeyesno('service_active', '1', '0', $result['service_active']);
				$interval_payment = makeoption($lng['service']['interval_payment_prepaid'], '0', $result['interval_payment'], true) . makeoption($lng['service']['interval_payment_postpaid'], '1', $result['interval_payment'], true);
				$payment_method = '';
				foreach($lng['customer']['payment_methods'] as $payment_method_id => $payment_method_name)
				{
					$payment_method.= makeoption($payment_method_name, $payment_method_id, (int)$result['payment_method'], true);
				}

				$taxclasses_option = '';
				foreach($taxclasses as $classid => $classname)
				{
					$taxclasses_option.= makeoption($classname, $classid, $result['taxclass']);
				}

				$calc_tax = makeyesno('calc_tax', '1', '0', $result['calc_tax']);
				$service_categories_option_once = '';
				$service_categories_option_period = '';
				foreach($service_categories as $service_category_id => $service_category_caption)
				{
					$service_categories_option_once.= makeoption($service_category_caption, $service_category_id, explode('-', $result['customer_categories_once']));
					$service_categories_option_period.= makeoption($service_category_caption, $service_category_id, explode('-', $result['customer_categories_period']));
				}

				$result = htmlentities_array($result);
				eval("echo \"" . getTemplate("admins/admins_edit") . "\";");
			}
		}
	}
}

?>
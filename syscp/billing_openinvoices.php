<?php

/**
 * Manage Open Invoices (billing_openinvoices.php)
 *
 * This file manages open invoices (list customers, list rows, edit rows, delete rows, generate pdf preview, fixate invoice)
 *
 * @author Florian Lippert <flo@syscp.org>
 * @version 1.0
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

if($userinfo['customers_see_all'] == '1')
{
	if(isset($_GET['mode'])
	   && intval($_GET['mode']) === 1)
	{
		$mode = 1;
	}
	elseif(isset($_POST['mode'])
	       && intval($_POST['mode']) === 1)
	{
		$mode = 1;
	}
	else
	{
		$mode = 0;
	}

	if($page == ''
	   || $page == 'overview')
	{
		if($action == '')
		{
			if($mode === 1)
			{
				$fields = array(
					'u.loginname' => $lng['login']['username'],
					'u.name' => $lng['customer']['name'],
					'u.firstname' => $lng['customer']['firstname'],
					'u.company' => $lng['customer']['company'],
					'u.street' => $lng['customer']['street'],
					'u.zipcode' => $lng['customer']['zipcode'],
					'u.city' => $lng['customer']['city'],
					'u.lastinvoiced_date' => $lng['service']['lastinvoiced_date'],
					'u.contract_number' => $lng['customer']['contract_number'],
					'u.contract_date' => $lng['customer']['contract_date'],
					'u.servicestart_date' => $lng['service']['start_date'],
					'u.lastinvoiced_date' => $lng['service']['lastinvoiced_date'],
					'u.invoice_fee' => $lng['billing']['invoice_fee'],
				);
				$paging = new paging($userinfo, $db, getModeDetails($mode, 'TABLE_PANEL_USERS', 'table'), $fields, $settings['panel']['paging'], $settings['panel']['natsorting']);
				$result = $db->query("SELECT `u`.`adminid`, `u`.`loginname`, `u`.`name`, `u`.`firstname`, `u`.`company`, `u`.`street`, `u`.`zipcode`, `u`.`city`, `u`.`contract_number`, `u`.`contract_date`, `u`.`servicestart_date`, `u`.`lastinvoiced_date`, `u`.`invoice_fee` " . "FROM `" . getModeDetails($mode, 'TABLE_PANEL_USERS', 'table') . "` `u` " . "WHERE ( `u`.`invoice_fee_hosting` > 0 OR `u`.`invoice_fee_hosting_customers` > 0 ) " . $paging->getSqlWhere(true) . " " . $paging->getSqlOrderBy() . " " . $paging->getSqlLimit());
				$paging->setEntries($db->num_rows($result));
			}
			else
			{
				$fields = array(
					'u.loginname' => $lng['login']['username'],
					'a.loginname' => $lng['admin']['admin'],
					'u.name' => $lng['customer']['name'],
					'u.firstname' => $lng['customer']['firstname'],
					'u.company' => $lng['customer']['company'],
					'u.street' => $lng['customer']['street'],
					'u.zipcode' => $lng['customer']['zipcode'],
					'u.city' => $lng['customer']['city'],
					'u.contract_number' => $lng['customer']['contract_number'],
					'u.contract_date' => $lng['customer']['contract_date'],
					'u.servicestart_date' => $lng['service']['start_date'],
					'u.lastinvoiced_date' => $lng['service']['lastinvoiced_date'],
					'u.invoice_fee' => $lng['billing']['invoice_fee'],
				);
				$paging = new paging($userinfo, $db, getModeDetails($mode, 'TABLE_PANEL_USERS', 'table'), $fields, $settings['panel']['paging'], $settings['panel']['natsorting']);
				$result = $db->query("SELECT `u`.`customerid`, `u`.`loginname`, `u`.`name`, `u`.`firstname`, `u`.`company`, `u`.`street`, `u`.`zipcode`, `u`.`city`, `u`.`contract_number`, `u`.`contract_date`, `u`.`servicestart_date`, `u`.`lastinvoiced_date`, `u`.`invoice_fee`, `a`.`loginname` AS `adminname` " . "FROM `" . getModeDetails($mode, 'TABLE_PANEL_USERS', 'table') . "` `u`, `" . TABLE_PANEL_ADMINS . "` `a` " . "WHERE `u`.`adminid`=`a`.`adminid` AND `u`.`invoice_fee_hosting` > 0 " . $paging->getSqlWhere(true) . " " . $paging->getSqlOrderBy() . " " . $paging->getSqlLimit());
				$paging->setEntries($db->num_rows($result));
			}

			$sortcode = $paging->getHtmlSortCode($lng, true);
			$arrowcode = $paging->getHtmlArrowCode($filename . '?mode=' . $mode . '&s=' . $s);
			$searchcode = $paging->getHtmlSearchCode($lng);
			$pagingcode = $paging->getHtmlPagingCode($filename . '?mode=' . $mode . '&s=' . $s);
			$i = 0;
			$count = 0;
			$users = '';

			while($row = $db->fetch_array($result))
			{
				if($paging->checkDisplay($i))
				{
					$row['userid'] = $row[getModeDetails($mode, 'TABLE_PANEL_USERS', 'key')];
					$row['contract_date'] = makeNicePresentableDate($row['contract_date'], $lng['panel']['dateformat_function']);
					$row['servicestart_date'] = makeNicePresentableDate($row['servicestart_date'], $lng['panel']['dateformat_function']);
					$row['lastinvoiced_date'] = makeNicePresentableDate($row['lastinvoiced_date'], $lng['panel']['dateformat_function']);
					$row = htmlentities_array($row);
					eval("\$users.=\"" . getTemplate("billing/openinvoices_overview_row") . "\";");
					$count++;
				}

				$i++;
			}

			eval("echo \"" . getTemplate("billing/openinvoices_overview") . "\";");
		}

		if($action == 'cacheinvoicefees')
		{
			$number_users = $db->query_first('SELECT COUNT(*) AS `number_users` FROM `' . getModeDetails($mode, 'TABLE_PANEL_USERS', 'table'));
			$number_users = intval($number_users['number_users']);

			if(isset($_GET['begin'])
			   && isset($_GET['count'])
			   && $number_users != 0)
			{
				$begin = intval($_GET['begin']);
				$count = intval($_GET['count']);

				if($begin < $number_users
				   && $count != 0)
				{
					echo $begin . '/' . $number_users . ' (' . round(($begin / $number_users) * 100) . '%)';
					$users = cacheInvoiceFees($mode, $begin, $count);
					echo '|';
					foreach($users as $userid => $user)
					{
						echo $user['loginname'] . ' (' . $userid . ') : ' . $user['total'] . '&euro;<br />';
					}
				}
				else
				{
					echo 'ready';
				}

				exit;
			}

			eval("echo \"" . getTemplate("billing/openinvoices_cacheinvoicefees") . "\";");
		}
	}

	if($page == 'invoice')
	{
		$user = $db->query_first('SELECT * FROM `' . getModeDetails($mode, 'TABLE_PANEL_USERS', 'table') . '` WHERE `' . getModeDetails($mode, 'TABLE_PANEL_USERS', 'key') . '` = \'' . $db->escape($id) . '\' ');

		if(!is_array($user)
		   || !isset($user[getModeDetails($mode, 'TABLE_PANEL_USERS', 'key')])
		   || $user[getModeDetails($mode, 'TABLE_PANEL_USERS', 'key')] != $id)
		{
			standard_error('notallreqfieldsorerrors');
			exit;
		}

		if(!isset($user['customer_categories_once']))
		{
			$user['customer_categories_once'] = '';
		}

		if(!isset($user['customer_categories_period']))
		{
			$user['customer_categories_period'] = '';
		}

		if($action == '')
		{
			$invoiceItems = array();
			$credit_note = 0;
			$myInvoice = new invoice(&$db, $mode, explode('-', $user['customer_categories_once']), explode('-', $user['customer_categories_period']));

			if($myInvoice->collect($id) === true)
			{
				$invoiceItems = $myInvoice->exportArray($lng, true);
				$credit_note = $myInvoice->getCreditNote();
			}

			if(isset($_GET['editkey']))
			{
				$editkey = validate($_GET['editkey'], 'key', '/^[a-f0-9]{32}$/');
			}
			else
			{
				$editkey = '';
			}

			$invoice_rows = '';
			$count = 0;
			$total_fee = 0;
			$total_fee_taxed = 0;
			foreach($invoiceItems as $group => $groupItems)
			{
				$group_items = '';
				foreach($groupItems['rows'] as $row)
				{
					$count++;

					if(!isset($row['deleted']))
					{
						$row['deleted'] = false;
					}

					if($row['deleted'] !== true)
					{
						$total_fee+= $row['total_fee'];
						$total_fee_taxed+= $row['total_fee_taxed'];
					}

					if($row['key'] == $editkey)
					{
						eval("\$group_items.=\"" . getTemplate("billing/openinvoices_invoice_row_edit") . "\";");
					}
					else
					{
						eval("\$group_items.=\"" . getTemplate("billing/openinvoices_invoice_row") . "\";");
					}

					if(isset($row['history'])
					   && is_array($row['history']))
					{
						$rowspan = count($row['history']) + 1;
						eval("\$group_items.=\"" . getTemplate("billing/openinvoices_invoice_history") . "\";");
						foreach($row['history'] as $timestamp => $history_row)
						{
							$history_row['taxrate_percent'] = $history_row['taxrate'] * 100;
							$history_row['total_fee'] = sprintf("%01.2f", $history_row['total_fee']);
							$history_row['tax'] = sprintf("%01.2f", round(($history_row['total_fee'] * $history_row['taxrate']), 2));
							$history_row['total_fee_taxed'] = sprintf("%01.2f", round(($history_row['total_fee'] + $history_row['tax']), 2));

							if(!isset($history_row['action'])
							   || $history_row['action'] == '0'
							   || $history_row['action'] == '')
							{
								eval("\$group_items.=\"" . getTemplate("billing/openinvoices_invoice_history_row_original") . "\";");
							}
							elseif($history_row['action'] == '1')
							{
								$deleted_time = date($lng['panel']['dateformat_function'], $timestamp) . ' ' . date($lng['panel']['timeformat_function'], $timestamp);
								$deleted_by = $history_row['userid'];
								eval("\$group_items.=\"" . getTemplate("billing/openinvoices_invoice_history_row_deleted") . "\";");
							}
							elseif($history_row['action'] == '2')
							{
								$edited_time = date($lng['panel']['dateformat_function'], $timestamp) . ' ' . date($lng['panel']['timeformat_function'], $timestamp);
								$edited_by = $history_row['userid'];
								eval("\$group_items.=\"" . getTemplate("billing/openinvoices_invoice_history_row_edited") . "\";");
							}
						}
					}
				}

				eval("\$invoice_rows.=\"" . getTemplate("billing/openinvoices_invoice_group") . "\";");
			}

			$total_fee_taxed-= $credit_note;
			eval("echo \"" . getTemplate("billing/openinvoices_invoice") . "\";");
		}

		if($action == 'delete')
		{
			if(isset($_POST['key']))
			{
				$key = validate($_POST['key'], 'key', '/^[a-f0-9]{32}$/');
			}
			elseif(isset($_GET['key']))
			{
				$key = validate($_GET['key'], 'key', '/^[a-f0-9]{32}$/');
			}
			else
			{
				$key = '';
			}

			if(isset($_POST['send'])
			   && $_POST['send'] == 'send')
			{
				$db->query('INSERT INTO `' . getModeDetails($mode, 'TABLE_BILLING_INVOICE_CHANGES', 'table') . '` (`' . getModeDetails($mode, 'TABLE_BILLING_INVOICE_CHANGES', 'key') . '`, `userid`, `timestamp`, `key`, `action`) VALUES(\'' . $db->escape($id) . '\', \'' . $db->escape($userinfo['userid']) . '\', \'' . $db->escape(time()) . '\', \'' . $db->escape($key) . '\', \'1\')');
				cacheInvoiceFees($mode, null, null, $id);
				redirectTo($filename, Array('s' => $s, 'id' => $id, 'mode' => $mode, 'page' => $page));
			}
			else
			{
				ask_yesno('billing_invoice_row_reallydelete', $filename, array('id' => $id, 'mode' => $mode, 'key' => $key, 'page' => $page, 'action' => $action));
			}
		}

		if($action == 'edit'
		   && isset($_POST['key'])
		   && isset($_POST['caption'])
		   && isset($_POST['total_fee'])
		   && isset($_POST['send'])
		   && $_POST['send'] == 'send')
		{
			$key = validate($_POST['key'], 'key', '/^[a-f0-9]{32}$/');
			$caption = validate($_POST['caption'], html_entity_decode($lng['billing']['caption']));
			$interval = validate($_POST['interval'], html_entity_decode($lng['billing']['interval']));
			$quantity = doubleval($_POST['quantity']);

			if(isset($_POST['taxrate']))
			{
				$taxrate = doubleval(str_replace(',', '.', $_POST['taxrate']));
			}
			elseif(isset($_POST['taxrate_percent']))
			{
				$taxrate = doubleval(str_replace(',', '.', $_POST['taxrate_percent'])) / 100;
			}
			else
			{
				$texrate = 0;
			}

			$total_fee = doubleval(str_replace(',', '.', $_POST['total_fee']));
			$db->query('INSERT INTO `' . getModeDetails($mode, 'TABLE_BILLING_INVOICE_CHANGES', 'table') . '` (`' . getModeDetails($mode, 'TABLE_BILLING_INVOICE_CHANGES', 'key') . '`, `userid`, `timestamp`, `key`, `action`, `caption`, `interval`, `taxrate`, `quantity`, `total_fee`) VALUES(\'' . $db->escape($id) . '\', \'' . $db->escape($userinfo['userid']) . '\', \'' . $db->escape(time()) . '\', \'' . $db->escape($key) . '\', \'2\', \'' . $db->escape($caption) . '\', \'' . $db->escape($interval) . '\', \'' . $db->escape($taxrate) . '\', \'' . $db->escape($quantity) . '\', \'' . $db->escape($total_fee) . '\')');
			cacheInvoiceFees($mode, null, null, $id);
			redirectTo($filename, Array('s' => $s, 'id' => $id, 'mode' => $mode, 'page' => $page));
		}

		if($action == 'reset')
		{
			if(isset($_POST['key']))
			{
				$key = validate($_POST['key'], 'key', '/^[a-f0-9]{32}$/');
			}
			elseif(isset($_GET['key']))
			{
				$key = validate($_GET['key'], 'key', '/^[a-f0-9]{32}$/');
			}
			else
			{
				$key = '';
			}

			if(isset($_POST['send'])
			   && $_POST['send'] == 'send')
			{
				if(isset($key)
				   && $key != '')
				{
					$db->query('DELETE FROM `' . getModeDetails($mode, 'TABLE_BILLING_INVOICE_CHANGES', 'table') . '` WHERE `' . getModeDetails($mode, 'TABLE_BILLING_INVOICE_CHANGES', 'key') . '` = \'' . $db->escape($id) . '\' AND `key` = \'' . $key . '\'');
				}
				else
				{
					$db->query('DELETE FROM `' . getModeDetails($mode, 'TABLE_BILLING_INVOICE_CHANGES', 'table') . '` WHERE `' . getModeDetails($mode, 'TABLE_BILLING_INVOICE_CHANGES', 'key') . '` = \'' . $db->escape($id) . '\'');
				}

				cacheInvoiceFees($mode, null, null, $id);
				redirectTo($filename, Array('s' => $s, 'id' => $id, 'mode' => $mode, 'page' => $page));
			}
			else
			{
				if(isset($key)
				   && $key != '')
				{
					ask_yesno('billing_invoice_row_reallyreset_key', $filename, array('id' => $id, 'mode' => $mode, 'page' => $page, 'action' => $action, 'key' => $key));
				}
				else
				{
					ask_yesno('billing_invoice_row_reallyreset', $filename, array('id' => $id, 'mode' => $mode, 'page' => $page, 'action' => $action));
				}
			}
		}

		if($action == 'fixinvoice')
		{
			$invoice_number_preset = strtr($lng['invoice']['invoicenumbertemplate'], array('{number}' => ((int)$settings['billing']['invoicenumber_count'] + 1), '{year}' => date('Y'), '{month}' => date('m'), '{day}' => date('d')));

			if(isset($_POST['send'])
			   && $_POST['send'] == 'send')
			{
				$invoice_number = validate($_POST['invoice_number'], html_entity_decode($lng['billing']['number']));

				if(isset($lng['invoice']['states'][$_POST['state']]))
				{
					$state = intval($_POST['state']);
				}

				$myInvoice = new invoice(&$db, $mode, explode('-', $user['customer_categories_once']), explode('-', $user['customer_categories_period']));

				if($myInvoice->collect($id, true) === true)
				{
					$invoiceXmlString = $myInvoice->exportXml($lng, $invoice_number);
					$invoiceXml = new SimpleXMLElement($invoiceXmlString);
					$db->query('INSERT INTO `' . getModeDetails($mode, 'TABLE_BILLING_INVOICES', 'table') . '` (`' . getModeDetails($mode, 'TABLE_BILLING_INVOICES', 'key') . '`, `xml`, `invoice_date`, `invoice_number`, `state`, `state_change`, `total_fee`, `total_fee_taxed`) VALUES(\'' . $db->escape($id) . '\', \'' . $db->escape($invoiceXmlString) . '\', \'' . $db->escape(date('Y-m-d')) . '\', \'' . $db->escape($invoice_number) . '\', \'' . $db->escape($state) . '\', \'' . time() . '\', \'' . $db->escape((string)$invoiceXml->total_fee[0]) . '\', \'' . $db->escape((string)$invoiceXml->total_fee_taxed[0]) . '\' ) ');

					if(preg_match('/^' . strtr($lng['invoice']['invoicenumbertemplate'], array('/' => '\/', '{number}' => '(\d+)', '{year}' => date('Y'), '{month}' => date('m'), '{day}' => date('d'))) . '$/', $invoice_number, $invoicenumber_count))
					{
						$db->query('UPDATE `' . TABLE_PANEL_SETTINGS . '` SET `value` = \'' . ((int)$invoicenumber_count[1]) . '\' WHERE `settinggroup` = \'billing\' AND `varname` = \'invoicenumber_count\'');
					}
				}

				cacheInvoiceFees($mode, null, null, $id);
				redirectTo($filename, Array('s' => $s, 'mode' => $mode, 'page' => 'overview'));
			}
			else
			{
				$invoice_states_option = '';
				foreach($lng['invoice']['states'] as $stateid => $statename)
				{
					$invoice_states_option.= makeoption($statename, $stateid, $result['state'], true);
				}

				eval("echo \"" . getTemplate("billing/openinvoices_invoice_fix") . "\";");
			}
		}

		if($action == 'preview')
		{
			$myInvoice = new invoice(&$db, $mode, explode('-', $user['customer_categories_once']), explode('-', $user['customer_categories_period']));

			if($myInvoice->collect($id) === true)
			{
				$invoice = new pdfInvoice();
				$invoice->processData($myInvoice->exportXml($lng, $lng['invoice']['preview']), $lng);
				$invoice->outputBrowser();
			}
		}
	}
}

?>
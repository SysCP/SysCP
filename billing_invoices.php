<?php

/**
 * Manage Invoices (billing_invoices.php)
 *
 * This file manages domain invoices (list, change state, pdf, cancellation-pdf, reminder-pdf)
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

	if($action == '')
	{
		$fields = array(
			'i.invoice_number' => $lng['billing']['number'],
			'i.invoice_date' => $lng['billing']['invoice_date'],
			'i.state' => $lng['invoice']['state'],
			'i.state_change' => $lng['invoice']['state_change'],
			'i.total_fee' => $lng['invoice']['total_fee'],
			'i.total_fee_taxed' => $lng['invoice']['total_fee_taxed'],
			'c.loginname' => $lng['login']['username'],
			'c.name' => $lng['customer']['name'],
			'c.firstname' => $lng['customer']['firstname'],
			'c.company' => $lng['customer']['company'],
		);
		$paging = new paging($userinfo, $db, TABLE_BILLING_INVOICES, $fields, $settings['panel']['paging'], $settings['panel']['natsorting']);
		$customers = '';
		$result = $db->query("SELECT `i`.*, `c`.* " . "FROM `" . getModeDetails($mode, 'TABLE_BILLING_INVOICES', 'table') . "` `i` LEFT JOIN `" . getModeDetails($mode, 'TABLE_PANEL_USERS', 'table') . "` `c` USING (`" . getModeDetails($mode, 'TABLE_PANEL_USERS', 'key') . "`) " . $paging->getSqlWhere() . " " . $paging->getSqlOrderBy() . " " . $paging->getSqlLimit());
		$paging->setEntries($db->num_rows($result));
		$sortcode = $paging->getHtmlSortCode($lng, true);
		$arrowcode = $paging->getHtmlArrowCode($filename . '?mode=' . $mode . '&s=' . $s);
		$searchcode = $paging->getHtmlSearchCode($lng);
		$pagingcode = $paging->getHtmlPagingCode($filename . '?mode=' . $mode . '&s=' . $s);
		$i = 0;
		$count = 0;

		while($row = $db->fetch_array($result))
		{
			if($paging->checkDisplay($i))
			{
				$row['invoice_date'] = makeNicePresentableDate($row['invoice_date'], $lng['panel']['dateformat_function']);
				$row['state_change'] = date($lng['panel']['dateformat_function'], $row['state_change']);
				$row = htmlentities_array($row);
				eval("\$customers.=\"" . getTemplate("billing/invoices_row") . "\";");
				$count++;
			}

			$i++;
		}

		eval("echo \"" . getTemplate("billing/invoices") . "\";");
	}

	if($action == 'edit')
	{
		$result = $db->query_first('SELECT * FROM `' . getModeDetails($mode, 'TABLE_BILLING_INVOICES', 'table') . '` WHERE `id` = \'' . $id . '\' ');

		if($result['id'] == $id
		   && $id != '0')
		{
			$invoiceXml = new SimpleXMLElement($result['xml']);
			$contact = array(
				'name' => utf8_decode($invoiceXml->address->name[0]),
				'firstname' => utf8_decode($invoiceXml->address->firstname[0]),
				'title' => utf8_decode($invoiceXml->address->title[0]),
				'company' => utf8_decode($invoiceXml->address->company[0]),
				'street' => utf8_decode($invoiceXml->address->street[0]),
				'zipcode' => utf8_decode($invoiceXml->address->zipcode[0]),
				'city' => utf8_decode($invoiceXml->address->city[0]),
				'country' => utf8_decode($invoiceXml->address->country[0]),
			);

			if(isset($_POST['send'])
			   && $_POST['send'] == 'send')
			{
				if(isset($lng['invoice']['states'][$_POST['state']])
				   && $result['state'] <= (int)$_POST['state'])
				{
					$state = intval($_POST['state']);
					$db->query('UPDATE `' . getModeDetails($mode, 'TABLE_BILLING_INVOICES', 'table') . '` SET `state` = \'' . $db->escape($state) . '\', `state_change` = \'' . time() . '\' WHERE `id` = \'' . $id . '\' ');
				}

				if($_POST['name'] != $contact['name']
				   || $_POST['firstname'] != $contact['firstname']
				   || $_POST['title'] != $contact['title']
				   || $_POST['company'] != $contact['company']
				   || $_POST['street'] != $contact['street']
				   || $_POST['zipcode'] != $contact['zipcode']
				   || $_POST['city'] != $contact['city']
				   || $_POST['country'] != $contact['country'])
				{
					$invoiceXml->address->name[0] = utf8_encode(htmlspecialchars(validate($_POST['name'], 'name')));
					$invoiceXml->address->firstname[0] = utf8_encode(htmlspecialchars(validate($_POST['firstname'], 'first name')));
					$invoiceXml->address->title[0] = utf8_encode(htmlspecialchars(validate($_POST['title'], 'title')));
					$invoiceXml->address->company[0] = utf8_encode(htmlspecialchars(validate($_POST['company'], 'company')));
					$invoiceXml->address->street[0] = utf8_encode(htmlspecialchars(validate($_POST['street'], 'street')));
					$invoiceXml->address->zipcode[0] = utf8_encode(htmlspecialchars(validate($_POST['zipcode'], 'zipcode', '/^[0-9 \-A-Z]*$/')));
					$invoiceXml->address->city[0] = utf8_encode(htmlspecialchars(validate($_POST['city'], 'city')));
					$invoiceXml->address->country[0] = utf8_encode(htmlspecialchars(validate($_POST['country'], 'country')));
					$db->query('UPDATE `' . getModeDetails($mode, 'TABLE_BILLING_INVOICES', 'table') . '` SET `xml` = \'' . $db->escape($invoiceXml->asXML()) . '\' WHERE `id` = \'' . $id . '\' ');
				}

				redirectTo($filename, Array(
					's' => $s,
					'mode' => $mode
				));
			}
			else
			{
				$result['invoice_date'] = makeNicePresentableDate($result['invoice_date'], $lng['panel']['dateformat_function']);
				$invoice_states_option = '';
				foreach($lng['invoice']['states'] as $stateid => $statename)
				{
					if((int)$result['state'] <= (int)$stateid)
					{
						$invoice_states_option.= makeoption($statename, $stateid, $result['state'], true);
					}
				}

				$contact = htmlentities_array($contact);
				eval("echo \"" . getTemplate("billing/invoices_edit") . "\";");
			}
		}
	}

	if($action == 'pdf')
	{
		$result = $db->query_first('SELECT * FROM `' . getModeDetails($mode, 'TABLE_BILLING_INVOICES', 'table') . '` WHERE `id` = \'' . $id . '\' ');

		if($result['id'] == $id
		   && $id != '0')
		{
			$invoice = new pdfInvoice();

			if((int)$result['state'] >= CONST_BILLING_INVOICESTATE_CANCELLED_NO_REINVOICE)
			{
				$invoice->cancellation = true;
			}

			$invoice->processData($result['xml'], $lng);
			$invoice->outputBrowser();
		}
	}

	if($action == 'reminder')
	{
		$result = $db->query_first('SELECT * FROM `' . getModeDetails($mode, 'TABLE_BILLING_INVOICES', 'table') . '` WHERE `id` = \'' . $id . '\' ');

		if($result['id'] == $id
		   && $id != '0')
		{
			$invoice = new pdfReminder();
			$invoice->processData($result['xml'], $lng);
			$invoice->outputBrowser();
		}
	}
}

?>
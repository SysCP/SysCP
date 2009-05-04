<?php

/**
 * Manage Other Services (billing_other.php)
 *
 * This file manages other services (list, add, delete, edit)
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
	$other_templates = array(
		'0' => $lng['panel']['default']
	);
	$other_templates_option = makeoption($lng['panel']['default'], 0, 0, true);
	$other_templates_result = $db->query('SELECT * FROM `' . TABLE_BILLING_SERVICE_OTHER_TEMPLATES . '` ');

	while($other_templates_row = $db->fetch_array($other_templates_result))
	{
		$other_templates[$other_templates_row['templateid']] = $other_templates_row['caption_setup'] . ' / ' . $other_templates_row['caption_interval'];
		$other_templates_option.= makeoption($other_templates_row['caption_setup'] . ' / ' . $other_templates_row['caption_interval'], $other_templates_row['templateid']);
	}

	$customers = array();
	$customers_option = makeoption($lng['panel']['please_choose'], 0, 0, true);
	$customers_result = $db->query('SELECT * FROM `' . TABLE_PANEL_CUSTOMERS . '`');

	while($customers_row = $db->fetch_array($customers_result))
	{
		$customers[$customers_row['customerid']] = $customers_row['loginname'] . ' (' . $customers_row['name'] . ', ' . $customers_row['firstname'] . ')';
		$customers_option.= makeoption($customers_row['loginname'] . ' (' . $customers_row['name'] . ', ' . $customers_row['firstname'] . ')', $customers_row['customerid']);
	}

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

	if($action == '')
	{
		$fields = array(
			'c.loginname' => $lng['login']['username'],
			'c.name' => $lng['customer']['name'],
			'c.firstname' => $lng['customer']['firstname'],
			'c.company' => $lng['customer']['company'],
			'o.caption_setup' => $lng['billing']['caption_setup'],
			'o.caption_interval' => $lng['billing']['caption_interval'],
			'o.quantity' => $lng['service']['quantity'],
			'o.interval_fee' => $lng['service']['interval_fee'],
			'o.interval_length' => $lng['service']['interval_length'],
			'o.setup_fee' => $lng['service']['setup_fee'],
			'o.service_active' => $lng['service']['active'],
			'o.servicestart_date' => $lng['service']['start_date'],
			'o.lastinvoiced_date' => $lng['service']['lastinvoiced_date'],
		);
		$paging = new paging($userinfo, $db, TABLE_BILLING_SERVICE_OTHER, $fields, $settings['panel']['paging'], $settings['panel']['natsorting']);
		$customers = '';
		$result = $db->query("SELECT `o`.*, `c`.`loginname`, `c`.`name`, `c`.`firstname`, `c`.`company` " . "FROM `" . TABLE_BILLING_SERVICE_OTHER . "` `o` LEFT JOIN `" . TABLE_PANEL_CUSTOMERS . "` `c` ON( `o`.`customerid` = `c`.`customerid` ) " . $paging->getSqlWhere(true) . " " . $paging->getSqlOrderBy() . " " . $paging->getSqlLimit());
		$paging->setEntries($db->num_rows($result));
		$sortcode = $paging->getHtmlSortCode($lng, true);
		$arrowcode = $paging->getHtmlArrowCode($filename . '?s=' . $s);
		$searchcode = $paging->getHtmlSearchCode($lng);
		$pagingcode = $paging->getHtmlPagingCode($filename . '?s=' . $s);
		$i = 0;
		$otherservices = '';

		while($row = $db->fetch_array($result))
		{
			if($paging->checkDisplay($i))
			{
				$enable_billing_data_edit = ($row['servicestart_date'] == '0000-00-00' || ($row['interval_payment'] == CONST_BILLING_INTERVALPAYMENT_PREPAID && calculateDayDifference(time(), $row['lastinvoiced_date']) >= 0));
				$row = htmlentities_array($row);
				eval("\$otherservices.=\"" . getTemplate("billing/other_row") . "\";");
			}

			$i++;
		}

		eval("echo \"" . getTemplate("billing/other") . "\";");
	}

	if($action == 'add')
	{
		if(isset($_POST['send'])
		   && $_POST['send'] == 'send')
		{
			if(!isset($_POST['customerid'])
			   || intval($_POST['customerid']) == 0
			   || !isset($customers[$_POST['customerid']]))
			{
				standard_error('notallreqfieldsorerrors');
				exit;
			}
			else
			{
				$customerid = $_POST['customerid'];
			}

			if(isset($_POST['templateid'])
			   && intval($_POST['templateid']) != 0
			   && isset($other_templates[$_POST['templateid']]))
			{
				$templateid = $_POST['templateid'];
			}
			else
			{
				$templateid = '0';
			}

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

			$caption_setup = validate($_POST['caption_setup'], html_entity_decode($lng['billing']['caption_setup']));
			$caption_interval = validate($_POST['caption_interval'], html_entity_decode($lng['billing']['caption_interval']));
			$quantity = doubleval(str_replace(',', '.', $_POST['quantity']));
			$interval_fee = doubleval(str_replace(',', '.', $_POST['interval_fee']));
			$interval_length = intval($_POST['interval_length']);
			$interval_type = (in_array($_POST['interval_type'], getIntervalTypes('array')) ? $_POST['interval_type'] : 'y');
			$setup_fee = doubleval(str_replace(',', '.', $_POST['setup_fee']));
			$service_active = intval($_POST['service_active']);
			$interval_payment = intval($_POST['interval_payment']);

			if($service_active == 1
			   && isset($_POST['servicestart_date']))
			{
				$servicestart_date = validate($_POST['servicestart_date'], html_entity_decode($lng['service']['start_date']), '/^(19|20)\d\d[-](0[1-9]|1[012])[-](0[1-9]|[12][0-9]|3[01])$/', '', array('0000-00-00', '0', ''));
			}

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

			if($interval_payment != '1')
			{
				$interval_payment = '0';
			}

			$db->query('INSERT INTO `' . TABLE_BILLING_SERVICE_OTHER . '` (`customerid`, `templateid`, `caption_setup`, `caption_interval`, `taxclass`, `quantity`, `interval_fee`, `interval_length`, `interval_type`, `interval_payment`, `setup_fee`, `service_active`, `servicestart_date`) VALUES(\'' . $db->escape($customerid) . '\', \'' . $db->escape($templateid) . '\', \'' . $db->escape($caption_setup) . '\', \'' . $db->escape($caption_interval) . '\', \'' . $db->escape($taxclass) . '\', \'' . $db->escape($quantity) . '\', \'' . $db->escape($interval_fee) . '\', \'' . $db->escape($interval_length) . '\', \'' . $db->escape($interval_type) . '\', \'' . $db->escape($interval_payment) . '\', \'' . $db->escape($setup_fee) . '\', \'' . $db->escape($service_active) . '\', \'' . $db->escape($servicestart_date) . '\') ');
			redirectTo($filename, Array('s' => $s));
		}
		else
		{
			$interval_type = getIntervalTypes('option');
			$service_active = makeyesno('service_active', '1', '0', '0');
			$interval_payment = makeoption($lng['service']['interval_payment_prepaid'], '0', '0', true) . makeoption($lng['service']['interval_payment_postpaid'], '1', '0', true);
			eval("echo \"" . getTemplate("billing/other_add") . "\";");
		}
	}

	if($action == 'delete')
	{
		$result = $db->query_first('SELECT * FROM `' . TABLE_BILLING_SERVICE_OTHER . '` WHERE `id` = \'' . $id . '\' ');

		if($result['id'] == $id
		   && $id != '0')
		{
			if(isset($_POST['send'])
			   && $_POST['send'] == 'send')
			{
				$db->query('DELETE FROM `' . TABLE_BILLING_SERVICE_OTHER . '` WHERE `id` = \'' . $id . '\' ');
				redirectTo($filename, Array('s' => $s));
			}
			else
			{
				$result = $db->query_first('SELECT * FROM `' . TABLE_BILLING_SERVICE_OTHER . '` WHERE `id` = \'' . $id . '\' ');
				$result['valid_from'] = date('Y-m-d', $result['valid_from']);
				ask_yesno('billing_other_service_reallydelete', $filename, array('id' => $id, 'action' => $action));
			}
		}
	}

	if($action == 'edit')
	{
		$result = $db->query_first('SELECT * FROM `' . TABLE_BILLING_SERVICE_OTHER . '` WHERE `id` = \'' . $id . '\' ');

		if($result['id'] == $id
		   && $id != '0')
		{
			$override_billing_data_edit = (isset($_GET['override_billing_data_edit']) && $_GET['override_billing_data_edit'] == '1') || (isset($_POST['override_billing_data_edit']) && $_POST['override_billing_data_edit'] == '1');
			$enable_billing_data_edit = ($result['servicestart_date'] == '0000-00-00' || ($result['interval_payment'] == CONST_BILLING_INTERVALPAYMENT_PREPAID && calculateDayDifference(time(), $result['lastinvoiced_date']) >= 0) || $override_billing_data_edit === true);

			if(isset($_POST['send'])
			   && $_POST['send'] == 'send')
			{
				if($enable_billing_data_edit === true)
				{
					if(isset($_POST['templateid'])
					   && intval($_POST['templateid']) != 0
					   && isset($other_templates[$_POST['templateid']]))
					{
						$templateid = $_POST['templateid'];
					}
					else
					{
						$templateid = '0';
					}

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

					$quantity = doubleval(str_replace(',', '.', $_POST['quantity']));
					$interval_fee = doubleval(str_replace(',', '.', $_POST['interval_fee']));
					$interval_length = intval($_POST['interval_length']);
					$interval_type = (in_array($_POST['interval_type'], getIntervalTypes('array')) ? $_POST['interval_type'] : 'y');
					$setup_fee = doubleval(str_replace(',', '.', $_POST['setup_fee']));

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
					$templateid = $result['templateid'];
					$taxclass = $result['taxclass'];
					$quantity = $result['quantity'];
					$interval_fee = $result['interval_fee'];
					$interval_length = $result['interval_length'];
					$interval_type = $result['interval_type'];
					$setup_fee = $result['setup_fee'];
					$servicestart_date = $result['servicestart_date'];
					$serviceend_date = $result['serviceend_date'];
				}

				$caption_setup = validate($_POST['caption_setup'], html_entity_decode($lng['billing']['caption_setup']));
				$caption_interval = validate($_POST['caption_interval'], html_entity_decode($lng['billing']['caption_interval']));
				$service_active = intval($_POST['service_active']);
				$interval_payment = intval($_POST['interval_payment']);

				if($interval_payment != '1')
				{
					$interval_payment = '0';
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

				$db->query('UPDATE `' . TABLE_BILLING_SERVICE_OTHER . '` SET `templateid` = \'' . $db->escape($templateid) . '\', `caption_setup` = \'' . $db->escape($caption_setup) . '\', `caption_interval` = \'' . $db->escape($caption_interval) . '\', `taxclass` = \'' . $db->escape($taxclass) . '\', `quantity` = \'' . $db->escape($quantity) . '\', `interval_fee` = \'' . $db->escape($interval_fee) . '\', `interval_length` = \'' . $db->escape($interval_length) . '\', `interval_type` = \'' . $db->escape($interval_type) . '\', `interval_payment` = \'' . $db->escape($interval_payment) . '\', `setup_fee` = \'' . $db->escape($setup_fee) . '\', `service_active` = \'' . $db->escape($service_active) . '\', `servicestart_date` = \'' . $db->escape($servicestart_date) . '\', `serviceend_date` = \'' . $db->escape($serviceend_date) . '\'  WHERE `id` = \'' . $id . '\' ');
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
			else
			{
				$interval_type = getIntervalTypes('option', $result['interval_type']);
				$service_active = makeyesno('service_active', '1', '0', $result['service_active']);
				$interval_payment = makeoption($lng['service']['interval_payment_prepaid'], '0', $result['interval_payment'], true) . makeoption($lng['service']['interval_payment_postpaid'], '1', $result['interval_payment'], true);
				$other_templates_option = '';
				foreach($other_templates as $templateid => $caption)
				{
					$other_templates_option.= makeoption($caption, $templateid, $result['templateid']);
				}

				$taxclasses_option = '';
				foreach($taxclasses as $classid => $classname)
				{
					$taxclasses_option.= makeoption($classname, $classid, $result['taxclass']);
				}

				eval("echo \"" . getTemplate("billing/other_edit") . "\";");
			}
		}
	}
}

?>
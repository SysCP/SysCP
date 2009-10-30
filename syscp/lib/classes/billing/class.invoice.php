<?php

/**
 * Main Invoice Processing Class (billing_class_invoice.php)
 *
 * @author    Former03 GmbH :: Florian Lippert <flo@syscp.org>
 * @package   Billing
 * @version   $Id$
 */

/**
 * This class mainly processes the invoice, managing the collectors, taxrates and import/export of XML/Arrays.
 * @package   Billing
 */

class invoice
{
	/**
	 * Database handler
	 * @var db
	 */

	var $db = false;

	/**
	 * my UserId
	 * @var int
	 */

	var $userId = 0;

	/**
	 * Information about user (all fields in panel_admins/panel_customers)
	 * @var array
	 */

	var $user = array();

	/**
	 * Mode, 0 for customer, 1 for admin/reseller
	 * @var int
	 */

	var $mode = 0;

	/**
	 * Only in admin mode: this array contains all service categories (ids)
	 * of customer's setup fees that should be included in the reseller invoice.
	 * @var array
	 */

	var $adminmode_include_once = array();

	/**
	 * Only in admin mode: this array contains all service categories (ids)
	 * of customer's interval fees that should be included in the reseller invoice.
	 * @var array
	 */

	var $adminmode_include_period = array();

	/**
	 * Holds relations between admins and customers.
	 * @var array
	 */

	var $admin2customers = array();

	/**
	 * Holds relations between customers and admins.
	 * @var array
	 */

	var $customer2admin = array();

	/**
	 * Holds all lines of the invoice.
	 * @var array
	 */

	var $invoice = array();

	/**
	 * Holds all servicecategories and their details.
	 * @var array
	 */

	var $service_categories = array();

	/**
	 * Holds all cancelled invoices.
	 * @var array
	 */

	var $cancelledInvoices = array();

	/**
	 * Class constructor of invoice. Gets reference for database connection,
	 * admin mode and (if applicable) adminmode includes.
	 * It gathers all service categories for the appropriate mode from database
	 * and fills self::service_categories.
	 * If in admin mode, it fills self::admin2customer and self::customer2admin.
	 *
	 * @param db    Reference to database handler
	 * @param int   For admin mode set 1, otherwise 0
	 * @param array If in adminmode, this holds the adminmode includes for setup fees
	 * @param array If in adminmode, this holds the adminmode includes for interval fees
	 *
	 * @author Former03 GmbH :: Florian Lippert <flo@syscp.org>
	 */

	function __construct($db, $mode = 0, $adminmode_include_once = array(), $adminmode_include_period = array())
	{
		$this->db = $db;

		if($mode === 1)
		{
			$this->mode = 1;
			$this->adminmode_include_once = $adminmode_include_once;
			$this->adminmode_include_period = $adminmode_include_period;
		}

		$service_categories_result = $this->db->query('SELECT * FROM `' . getModeDetails($this->mode, 'TABLE_BILLING_SERVICE_CATEGORIES', 'table') . '` ORDER BY `id` ASC');

		while($service_categories_row = $this->db->fetch_array($service_categories_result))
		{
			$this->service_categories[$service_categories_row['category_name']] = $service_categories_row;
		}

		if($this->mode === 1)
		{
			$customer2admin_result = $this->db->query('SELECT `customerid`, `adminid` FROM `' . TABLE_PANEL_CUSTOMERS . '` ');

			while($customer2admin = $this->db->fetch_array($customer2admin_result))
			{
				$this->customer2admin[$customer2admin['customerid']] = $customer2admin['adminid'];

				if(!isset($this->admin2customers[$customer2admin['adminid']]))
				{
					$this->admin2customers[$customer2admin['adminid']] = array();
				}

				$this->admin2customers[$customer2admin['adminid']][] = $customer2admin['customerid'];
			}
		}
	}

	/**
	 * This is the main invoice collector. It collects all items for a userid and also
	 * can fix the invoice and imports all cancelled invoices which should be reinvoiced.
	 * For every service category it fires up the collector, fetches data and pushes the
	 * rows through the taxcontroller to apply taxes before they finally get stored in
	 * self::invoices.
	 *
	 * @param int   UserId to handle
	 * @param bool  Fix invoice
	 * @return bool true if everything went okay, false if something went wrong
	 *
	 * @author Former03 GmbH :: Florian Lippert <flo@syscp.org>
	 */

	function collect($userId, $fixInvoice = false)
	{
		$this->userId = $userId;
		$this->user = $this->db->query_first('SELECT * FROM `' . getModeDetails($this->mode, 'TABLE_PANEL_USERS', 'table') . '` WHERE `' . getModeDetails($this->mode, 'TABLE_PANEL_USERS', 'key') . '` = \'' . $this->userId . '\' ');

		if($this->userId == 0
		   || !is_array($this->user)
		   || empty($this->user)
		   || $this->user[getModeDetails($this->mode, 'TABLE_PANEL_USERS', 'key')] != $this->userId)
		{
			return false;
		}

		$taxController = new taxController(&$this->db);

		if($this->user['calc_tax'] === '1')
		{
			$taxController->calc_tax = true;
		}
		else
		{
			$taxController->calc_tax = false;
		}

		$cancelledInvoices_result = $this->db->query('SELECT * FROM `' . getModeDetails($this->mode, 'TABLE_BILLING_INVOICES', 'table') . '` WHERE `' . getModeDetails($this->mode, 'TABLE_BILLING_INVOICES', 'key') . '` = \'' . $this->userId . '\' AND ( `state` = \'' . CONST_BILLING_INVOICESTATE_CANCELLED_REINVOICE_WITHOUT_CREDIT_NOTE . '\' OR `state` = \'' . CONST_BILLING_INVOICESTATE_CANCELLED_REINVOICE_WITH_CREDIT_NOTE . '\' ) ');

		while($cancelledInvoices_row = $this->db->fetch_array($cancelledInvoices_result))
		{
			$this->importXml($cancelledInvoices_row['xml']);
			$this->cancelledInvoices[$cancelledInvoices_row['id']] = $cancelledInvoices_row;
		}

		if($fixInvoice === true
		   && !empty($this->cancelledInvoices))
		{
			$this->db->query('UPDATE `' . getModeDetails($this->mode, 'TABLE_BILLING_INVOICES', 'table') . '` SET `state` = \'' . CONST_BILLING_INVOICESTATE_CANCELLED_REINVOICED . '\', `state_change` = \'' . time() . '\' WHERE `' . getModeDetails($this->mode, 'TABLE_BILLING_INVOICES', 'key') . '` = \'' . $this->userId . '\' AND ( `state` = \'' . CONST_BILLING_INVOICESTATE_CANCELLED_REINVOICE_WITHOUT_CREDIT_NOTE . '\' OR `state` = \'' . CONST_BILLING_INVOICESTATE_CANCELLED_REINVOICE_WITH_CREDIT_NOTE . '\' ) AND `id` IN ( ' . implode(', ', array_keys($this->cancelledInvoices)) . ' ) ');
		}

		foreach($this->service_categories as $service_category => $service_category_details)
		{
			if(!class_exists($service_category_details['category_classname']))
			{
				require_once ('./' . makeCorrectFile($service_category_details['category_classfile']));
			}

			if(class_exists($service_category_details['category_classname']))
			{
				$subject = 0;
				$mode = $this->mode;
				$include_setup_fee = false;
				$include_interval_fee = false;

				if($this->mode === 1
				   && intval($service_category_details['category_mode']) === 1)
				{
					if(isset($this->admin2customers[$this->userId]))
					{
						$subject = $this->admin2customers[$this->userId];
						$mode = 0;

						// We have to set mode to customer because we are feeding an array of customer ids, so serviceCategory should also work in customer mode

						if(in_array($service_category_details['id'], $this->adminmode_include_once))
						{
							$include_setup_fee = true;
						}

						if(in_array($service_category_details['id'], $this->adminmode_include_period))
						{
							$include_interval_fee = true;
						}
					}
				}
				else
				{
					$subject = $this->userId;
					$include_setup_fee = true;
					$include_interval_fee = true;
				}

				if($subject != 0)
				{
					$currentServiceCategory = new $service_category_details['category_classname'](&$this->db, $mode, $service_category_details['category_name']);
					$currentServiceCategory->fetchData($subject);
					$this->invoice = array_merge($this->invoice, $taxController->applyTaxRate($currentServiceCategory->collect($fixInvoice, $include_setup_fee, $include_interval_fee)));
					unset($currentServiceCategory);
				}
			}
		}

		return true;
	}

	/**
	 * This method exports the invoice rows as an 2-dim array with the following style:
	 *
	 *	array(5) {
	 *	  ["hosting"]=>
	 *	  array(5) {
	 *	    ["caption"]=>
	 *	    string(7) "HOSTING"
	 *	    ["service_date_begin"]=>
	 *	    string(10) "2008-01-01"
	 *	    ["service_date_end"]=>
	 *	    string(9) "2008-6-30"
	 *	    ["interval"]=>
	 *	    string(23) "01.01.2008 - 30.06.2008"
	 *	    ["rows"]=>
	 *	    array(1) {
	 *	      [0]=>
	 *	      array(15) {
	 *	        ["service_type"]=>
	 *	        string(7) "hosting"
	 *	        ["service_occurence"]=>
	 *	        string(4) "once"
	 *	        ["quantity"]=>
	 *	        int(1)
	 *	        ["setup_fee"]=>
	 *	        string(6) "200.00"
	 *	        ["taxclass"]=>
	 *	        string(1) "1"
	 *	        ["service_date"]=>
	 *	        string(10) "2008-01-01"
	 *	        ["description"]=>
	 *	        array(2) {
	 *	          ["loginname"]=>
	 *	          string(9) "reseller1"
	 *	          ["caption"]=>
	 *	          string(38) "Hostingpaket - Einrichtungsgeb&uuml;hr"
	 *	        }
	 *	        ["taxrate"]=>
	 *	        string(6) "0.1900"
	 *	        ["total_fee"]=>
	 *	        string(6) "200.00"
	 *	        ["key"]=>
	 *	        string(32) "090578d4cb0b28db6f2a17f8b268dbc5"
	 *	        ["interval"]=>
	 *	        string(10) "01.01.2008"
	 *	        ["single_fee"]=>
	 *	        string(6) "200.00"
	 *	        ["tax"]=>
	 *	        string(5) "38.00"
	 *	        ["total_fee_taxed"]=>
	 *	        string(6) "238.00"
	 *	        ["taxrate_percent"]=>
	 *	        float(19)
	 *	      }
	 *	    }
	 *	  }
	 *	}
	 *
	 * @param array  Language array
	 * @param bool   True if changes should be attached to the array (useful in edit mode ;-)
	 * @return array The array, see above for example.
	 *
	 * @author Former03 GmbH :: Florian Lippert <flo@syscp.org>
	 */

	function exportArray($lng = array(), $attachHistory = false)
	{
		if($this->userId == 0
		   || !is_array($this->user)
		   || empty($this->user)
		   || $this->user[getModeDetails($this->mode, 'TABLE_PANEL_USERS', 'key')] != $this->userId)
		{
			return false;
		}

		$invoice_changes = array();
		$invoice_changes_result = $this->db->query('SELECT * FROM `' . getModeDetails($this->mode, 'TABLE_BILLING_INVOICE_CHANGES', 'table') . '` WHERE `' . getModeDetails($this->mode, 'TABLE_BILLING_INVOICE_CHANGES', 'key') . '` = \'' . $this->userId . '\' ORDER BY `timestamp` ASC');

		while($invoice_changes_row = $this->db->fetch_array($invoice_changes_result))
		{
			$invoice_changes[$invoice_changes_row['key']][$invoice_changes_row['timestamp']] = $invoice_changes_row;
		}

		$returnval = array();
		reset($this->invoice);
		foreach($this->invoice as $rowid => $invoice_row)
		{
			if(!empty($invoice_row))
			{
				if(!isset($invoice_row['service_occurence']))
				{
					if(isset($invoice_row['service_date_begin'])
					   && isset($invoice_row['service_date_end']))
					{
						$invoice_row['service_occurence'] = 'period';
					}
					elseif(isset($invoice_row['service_date']))
					{
						$invoice_row['service_occurence'] = 'once';
					}
				}

				if(!isset($invoice_row['key']))
				{
					// Begin Key generation to detect changes in invoice

					$invoice_row['key'] = $invoice_row['service_type'] . '-' . $invoice_row['service_occurence'] . '-';

					if(isset($invoice_row['service_occurence']))
					{
						switch($invoice_row['service_occurence'])
						{
							case 'once':
								$invoice_row['key'].= $invoice_row['service_date'] . '-';
								break;
							case 'period':
								$invoice_row['key'].= $invoice_row['service_date_begin'] . '-' . $invoice_row['service_date_end'] . '-';
								break;
						}
					}

					reset($invoice_row['description']);
					foreach($invoice_row['description'] as $description_key => $description_value)
					{
						$invoice_row['key'].= $description_key . '.' . $description_value . '-';
					}

					$invoice_row['key'] = md5($invoice_row['key']);

					// End key generation
				}

				if(isset($invoice_row['service_occurence']))
				{
					switch($invoice_row['service_occurence'])
					{
						case 'once':
							$rowcaption_field = 'category_rowcaption_setup';

							if(!isset($invoice_row['interval']))
							{
								$invoice_row['interval'] = makeNicePresentableDate($invoice_row['service_date'], $lng['panel']['dateformat_function']);
							}

							break;
						case 'period':
							$rowcaption_field = 'category_rowcaption_interval';

							if(!isset($invoice_row['interval']))
							{
								$invoice_row['service_date_end'] = manipulateDate($invoice_row['service_date_end'], '-', 1, 'd');

								if(calculateDayDifference($invoice_row['service_date_begin'], $invoice_row['service_date_end']) != 0)
								{
									$invoice_row['interval'] = makeNicePresentableDate($invoice_row['service_date_begin'], $lng['panel']['dateformat_function']) . ' - ' . makeNicePresentableDate($invoice_row['service_date_end'], $lng['panel']['dateformat_function']);
								}
								else
								{
									$invoice_row['interval'] = makeNicePresentableDate($invoice_row['service_date_begin'], $lng['panel']['dateformat_function']);
								}
							}

							break;
					}
				}

				if(!isset($invoice_row['description']['caption'])
				   || $invoice_row['description']['caption'] == '')
				{
					if(isset($invoice_row['description']['caption_class'])
					   && isset($lng['billing']['categories'][($this->service_categories[$invoice_row['service_type']][$rowcaption_field] . '_' . $invoice_row['description']['caption_class'])]))
					{
						$invoice_row['description']['caption'] = $lng['billing']['categories'][($this->service_categories[$invoice_row['service_type']][$rowcaption_field] . '_' . $invoice_row['description']['caption_class'])];
					}
					elseif(isset($lng['billing']['categories'][$this->service_categories[$invoice_row['service_type']][$rowcaption_field]]))
					{
						$invoice_row['description']['caption'] = $lng['billing']['categories'][$this->service_categories[$invoice_row['service_type']][$rowcaption_field]];
					}
					else
					{
						$invoice_row['description']['caption'] = $this->service_categories[$invoice_row['service_type']]['category_rowcaption'];
					}
				}

				reset($invoice_row['description']);
				foreach($invoice_row['description'] as $description_key => $description_value)
				{
					$invoice_row['description']['caption'] = str_replace('{' . $description_key . '}', $description_value, $invoice_row['description']['caption']);
				}

				// See if the key is set and what to do

				$show_row = true;

				if(isset($invoice_changes[$invoice_row['key']])
				   && is_array($invoice_changes[$invoice_row['key']]))
				{
					if($attachHistory === true)
					{
						$invoice_row['history'] = $invoice_changes[$invoice_row['key']];
						$invoice_row['history'][0] = array(
							'caption' => $invoice_row['description']['caption'],
							'interval' => $invoice_row['interval'],
							'taxrate' => $invoice_row['taxrate'],
							'quantity' => $invoice_row['quantity'],
							'total_fee' => $invoice_row['total_fee']
						);
						krsort($invoice_row['history']);
					}

					foreach($invoice_changes[$invoice_row['key']] as $timestamp => $invoice_changes_row)
					{
						switch($invoice_changes_row['action'])
						{
							case '1':

								// Don't show this row

								$invoice_row['deleted'] = true;
								$show_row = $attachHistory;
								break;
							case '2':

								// Change caption, interval, taxrate and total_fee

								$invoice_row['deleted'] = false;

								if(isset($invoice_changes_row['caption']))
								{
									$invoice_row['description']['caption'] = $invoice_changes_row['caption'];
								}

								if(isset($invoice_changes_row['interval']))
								{
									$invoice_row['interval'] = $invoice_changes_row['interval'];
								}

								if(isset($invoice_changes_row['taxrate']))
								{
									$invoice_row['taxrate'] = $invoice_changes_row['taxrate'];
								}

								if(isset($invoice_changes_row['quantity']))
								{
									$invoice_row['quantity'] = $invoice_changes_row['quantity'];
								}

								if(isset($invoice_changes_row['total_fee']))
								{
									$invoice_row['total_fee'] = $invoice_changes_row['total_fee'];
								}

								break;
						}
					}
				}

				if($show_row)
				{
					if(!isset($returnval[$invoice_row['service_type']])
					   || !is_array($returnval[$invoice_row['service_type']]))
					{
						if(isset($this->service_categories[$invoice_row['service_type']])
						   && is_array($this->service_categories[$invoice_row['service_type']])
						   && isset($this->service_categories[$invoice_row['service_type']]['category_caption'])
						   && $this->service_categories[$invoice_row['service_type']]['category_caption'] != '')
						{
							if(isset($lng['billing']['categories'][$this->service_categories[$invoice_row['service_type']]['category_caption']]))
							{
								$caption = $lng['billing']['categories'][$this->service_categories[$invoice_row['service_type']]['category_caption']];
							}
							else
							{
								$caption = $this->service_categories[$invoice_row['service_type']]['category_caption'];
							}
						}

						$returnval[$invoice_row['service_type']] = array(
							'caption' => $caption,
							'service_date_begin' => 0,
							'service_date_end' => 0,
							'interval' => '',
							'rows' => array()
						);
					}

					if(isset($invoice_row['service_occurence']))
					{
						switch($invoice_row['service_occurence'])
						{
							case 'once':

								if(calculateDayDifference($invoice_row['service_date'], $returnval[$invoice_row['service_type']]['service_date_begin']) > 0
								   || $returnval[$invoice_row['service_type']]['service_date_begin'] == 0)
								{
									$returnval[$invoice_row['service_type']]['service_date_begin'] = $invoice_row['service_date'];
								}

								if(calculateDayDifference($returnval[$invoice_row['service_type']]['service_date_end'], $invoice_row['service_date']) > 0
								   || $returnval[$invoice_row['service_type']]['service_date_end'] == 0)
								{
									$returnval[$invoice_row['service_type']]['service_date_end'] = $invoice_row['service_date'];
								}

								break;
							case 'period':

								if(calculateDayDifference($invoice_row['service_date_begin'], $returnval[$invoice_row['service_type']]['service_date_begin']) > 0
								   || $returnval[$invoice_row['service_type']]['service_date_begin'] == 0)
								{
									$returnval[$invoice_row['service_type']]['service_date_begin'] = $invoice_row['service_date_begin'];
								}

								if(calculateDayDifference($returnval[$invoice_row['service_type']]['service_date_end'], $invoice_row['service_date_end']) > 0
								   || $returnval[$invoice_row['service_type']]['service_date_end'] == 0)
								{
									$returnval[$invoice_row['service_type']]['service_date_end'] = $invoice_row['service_date_end'];
								}

								break;
						}

						if(calculateDayDifference($returnval[$invoice_row['service_type']]['service_date_begin'], $returnval[$invoice_row['service_type']]['service_date_end']) != 0)
						{
							$returnval[$invoice_row['service_type']]['interval'] = makeNicePresentableDate($returnval[$invoice_row['service_type']]['service_date_begin'], $lng['panel']['dateformat_function']) . ' - ' . makeNicePresentableDate($returnval[$invoice_row['service_type']]['service_date_end'], $lng['panel']['dateformat_function']);
						}
						else
						{
							$returnval[$invoice_row['service_type']]['interval'] = makeNicePresentableDate($returnval[$invoice_row['service_type']]['service_date_begin'], $lng['panel']['dateformat_function']);
						}
					}

					$invoice_row['single_fee'] = $invoice_row['total_fee'];
					$invoice_row['total_fee']*= $invoice_row['quantity'];
					$invoice_row['tax'] = sprintf("%01.2f", round(($invoice_row['total_fee'] * $invoice_row['taxrate']), 2));
					$invoice_row['total_fee_taxed'] = sprintf("%01.2f", round(($invoice_row['total_fee'] + $invoice_row['tax']), 2));
					$invoice_row['taxrate_percent'] = $invoice_row['taxrate'] * 100;
					$invoice_row['total_fee'] = sprintf("%01.2f", $invoice_row['total_fee']);
					$returnval[$invoice_row['service_type']]['rows'][] = $invoice_row;
				}
			}
		}

		return $returnval;
	}

	/**
	 * This method returns an 2-dim array of total_fees (w/ and w/o tax)
	 * per service category in the following style:
	 *
	 *	array(1) {
	 *	  ["hosting"]=>
	 *	  array(2) {
	 *	    ["total_fee"]=>
	 *	    float(800)
	 *	    ["total_fee_taxed"]=>
	 *	    float(952)
	 *	  }
	 *	}
	 *
	 * @param array  Language array
	 * @return array The array, see above for example.
	 *
	 * @author Former03 GmbH :: Florian Lippert <flo@syscp.org>
	 */

	function getTotalFee($lng = array())
	{
		if($this->userId == 0
		   || !is_array($this->user)
		   || empty($this->user)
		   || $this->user[getModeDetails($this->mode, 'TABLE_PANEL_USERS', 'key')] != $this->userId)
		{
			return false;
		}

		$invoice = $this->exportArray($lng);
		$total_fee = array();
		foreach($invoice as $service_type => $service_details)
		{
			if(!isset($total_fee[$service_type]))
			{
				$total_fee[$service_type] = array(
					'total_fee' => 0,
					'total_fee_taxed' => 0
				);
			}

			foreach($service_details['rows'] as $rowid => $row)
			{
				$total_fee[$service_type]['total_fee']+= $row['total_fee'];
				$total_fee[$service_type]['total_fee_taxed']+= $row['total_fee_taxed'];
			}
		}

		return $total_fee;
	}

	/**
	 * This method returns a nice utf8-formatted XML file
	 * with the data it got from self::exportArray.
	 *
	 * @param array   Language array
	 * @param string  Invoice number
	 * @return string Contains the XML data.
	 *
	 * @author Former03 GmbH :: Florian Lippert <flo@syscp.org>
	 */

	function exportXml($lng = array(), $invoice_number = '')
	{
		if($this->userId == 0
		   || !is_array($this->user)
		   || empty($this->user)
		   || $this->user[getModeDetails($this->mode, 'TABLE_PANEL_USERS', 'key')] != $this->userId)
		{
			return false;
		}

		$invoice = $this->exportArray($lng);
		$invoiceXml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><invoice></invoice>');
		$invoiceXml->addChild('invoice_number', utf8_encode(htmlspecialchars($invoice_number)));
		$invoiceXml->addChild('invoice_date', makeNicePresentableDate(date('Y-m-d'), $lng['panel']['dateformat_function']));
		$address = $invoiceXml->addChild('address');
		$address->addChild('name', utf8_encode(htmlspecialchars($this->user['name'])));
		$address->addChild('firstname', utf8_encode(htmlspecialchars($this->user['firstname'])));
		$address->addChild('title', utf8_encode(htmlspecialchars($this->user['title'])));
		$address->addChild('company', utf8_encode(htmlspecialchars($this->user['company'])));
		$address->addChild('street', utf8_encode(htmlspecialchars($this->user['street'])));
		$address->addChild('zipcode', utf8_encode(htmlspecialchars($this->user['zipcode'])));
		$address->addChild('city', utf8_encode(htmlspecialchars($this->user['city'])));
		$address->addChild('country', utf8_encode(htmlspecialchars($this->user['country'])));
		$billing = $invoiceXml->addChild('billing');
		$billing->addChild('contract_number', utf8_encode(htmlspecialchars($this->user['contract_number'])));
		$billing->addChild('contract_details', utf8_encode(htmlspecialchars(sprintf(html_entity_decode($lng['invoice']['contract_details_template']), makeNicePresentableDate($this->user['contract_date'], $lng['panel']['dateformat_function']), ((int)$this->user['diskspace'] / 1024 == '-1' ? html_entity_decode($lng['customer']['unlimited']) : (string)(round((int)$this->user['diskspace'] / 1024, 2))), $this->user['additional_diskspace_fee'], (string)(round((int)$this->user['additional_diskspace_unit'] / 1024, 4)), ((int)$this->user['traffic'] / (1024 * 1024) == '-1' ? html_entity_decode($lng['customer']['unlimited']) : (string)(round((int)$this->user['traffic'] / (1024 * 1024), 4))), $this->user['additional_traffic_fee'], (string)(round((int)$this->user['additional_traffic_unit'] / (1024 * 1024), 4)), $this->user['included_domains_qty'], $this->user['interval_fee'],

		// The following two lines just make nice plural/singlar forms out of our interval_length (eg "1 Month" instead of "1 Months")

		str_replace('1 ' . $lng['panel']['intervalfee_type'][$this->user['interval_type']], $lng['panel']['intervalfee_type_one'][$this->user['interval_type']], $this->user['interval_length'] . ' ' . $lng['panel']['intervalfee_type'][$this->user['interval_type']]), str_replace('1 ' . $lng['panel']['intervalfee_type'][$this->user['interval_type']], '1 ' . $lng['panel']['intervalfee_type_one'][$this->user['interval_type']], (string)((int)$this->user['interval_length'] * (int)$this->user['payment_every']) . ' ' . $lng['panel']['intervalfee_type'][$this->user['interval_type']])))));
		$billing->addChild('payment_method', utf8_encode(htmlspecialchars($this->user['payment_method'])));
		$billing->addChild('term_of_payment', utf8_encode(htmlspecialchars($this->user['term_of_payment'])));
		$billing->addChild('bankaccount_holder', utf8_encode(htmlspecialchars($this->user['bankaccount_holder'])));
		$billing->addChild('bankaccount_number', utf8_encode(htmlspecialchars($this->user['bankaccount_number'])));
		$billing->addChild('bankaccount_blz', utf8_encode(htmlspecialchars($this->user['bankaccount_blz'])));
		$billing->addChild('bankaccount_bank', utf8_encode(htmlspecialchars($this->user['bankaccount_bank'])));
		$billing->addChild('taxid', utf8_encode(htmlspecialchars($this->user['taxid'])));
		$billing->addChild('calc_tax', utf8_encode(htmlspecialchars($this->user['calc_tax'])));
		$total_fee = 0;
		$total_fee_taxed = 0;
		$tax = array();
		$allservices_begin = 0;
		$allservices_end = 0;
		foreach($invoice as $service_type => $service_details)
		{
			$service_category = $invoiceXml->addChild('service_category');
			$service_category->addAttribute('service_type', utf8_encode(htmlspecialchars($service_type)));
			$service_category->addChild('caption', utf8_encode(htmlspecialchars(html_entity_decode($service_details['caption']))));
			$service_category->addChild('interval', utf8_encode(htmlspecialchars(html_entity_decode($service_details['interval']))));

			if(calculateDayDifference($service_details['service_date_begin'], $service_details['service_date_end']) != 0)
			{
				$invoiceXml->addChild('service_date_begin', utf8_encode(htmlspecialchars($service_details['service_date_begin'])));
				$invoiceXml->addChild('service_date_end', utf8_encode(htmlspecialchars($service_details['service_date_end'])));
			}
			else
			{
				$invoiceXml->addChild('service_date', utf8_encode(htmlspecialchars($service_details['service_date_begin'])));
			}

			if(calculateDayDifference($service_details['service_date_begin'], $allservices_begin) > 0
			   || $allservices_begin == 0)
			{
				$allservices_begin = $service_details['service_date_begin'];
			}

			if(calculateDayDifference($allservices_end, $service_details['service_date_end']) > 0
			   || $allservices_end == 0)
			{
				$allservices_end = $service_details['service_date_end'];
			}

			foreach($service_details['rows'] as $rowid => $row)
			{
				$invoice_row = $service_category->addChild('invoice_row');
				$invoice_row->addAttribute('key', utf8_encode(htmlspecialchars($row['key'])));
				$invoice_row->addChild('service_occurence', utf8_encode(htmlspecialchars($row['service_occurence'])));

				switch($row['service_occurence'])
				{
					case 'once':
						$invoice_row->addAttribute('date', utf8_encode(htmlspecialchars(makeNicePresentableDate($row['service_date'], 'Ymd'))));
						$invoice_row->addChild('service_date', utf8_encode(htmlspecialchars($row['service_date'])));
						break;
					case 'period':
						$invoice_row->addAttribute('date', utf8_encode(htmlspecialchars(makeNicePresentableDate($row['service_date_begin'], 'Ymd'))));
						$invoice_row->addChild('service_date_begin', utf8_encode(htmlspecialchars($row['service_date_begin'])));
						$invoice_row->addChild('service_date_end', utf8_encode(htmlspecialchars($row['service_date_end'])));
						break;
				}

				$invoice_row->addChild('caption', utf8_encode(htmlspecialchars(html_entity_decode($row['description']['caption']))));
				$invoice_row->addChild('interval', utf8_encode(htmlspecialchars($row['interval'])));
				$invoice_row->addChild('quantity', utf8_encode(htmlspecialchars($row['quantity'])));
				$invoice_row->addChild('single_fee', utf8_encode(htmlspecialchars($row['single_fee'])));
				$invoice_row->addChild('total_fee', utf8_encode(htmlspecialchars($row['total_fee'])));
				$invoice_row->addChild('taxrate', utf8_encode(htmlspecialchars($row['taxrate'])));
				$invoice_row->addChild('tax', utf8_encode(htmlspecialchars($row['tax'])));
				$invoice_row->addChild('total_fee_taxed', utf8_encode(htmlspecialchars($row['total_fee_taxed'])));

				if(!isset($tax[$row['taxrate']]))
				{
					$tax[$row['taxrate']] = 0;
				}

				$tax[$row['taxrate']]+= $row['tax'];
				$total_fee+= $row['total_fee'];
				$total_fee_taxed+= $row['total_fee_taxed'];
			}
		}

		if(calculateDayDifference($allservices_begin, $allservices_end) != 0)
		{
			$invoiceXml->addChild('invoice_period', utf8_encode(htmlspecialchars(makeNicePresentableDate($allservices_begin, $lng['panel']['dateformat_function']) . ' - ' . makeNicePresentableDate($allservices_end, $lng['panel']['dateformat_function']))));
		}
		else
		{
			$invoiceXml->addChild('invoice_period', utf8_encode(htmlspecialchars(makeNicePresentableDate($allservices_begin, $lng['panel']['dateformat_function']))));
		}

		$credit_note = $this->getCreditNote();

		if($credit_note != 0)
		{
			$invoiceXml->addChild('credit_note', utf8_encode(htmlspecialchars(sprintf("%01.2f", $credit_note))));
			$total_fee_taxed-= $credit_note;
		}

		$invoiceXml->addChild('total_fee', utf8_encode(htmlspecialchars(sprintf("%01.2f", $total_fee))));
		foreach($tax as $taxrate => $taxamount)
		{
			$taxXml = $invoiceXml->AddChild('tax', utf8_encode(htmlspecialchars(sprintf("%01.2f", $taxamount))));
			$taxXml->addAttribute('taxrate', utf8_encode(htmlspecialchars($taxrate)));
		}

		$invoiceXml->addChild('total_fee_taxed', utf8_encode(htmlspecialchars(sprintf("%01.2f", $total_fee_taxed))));
		return $invoiceXml->asXML();
	}

	/**
	 * This method imports a given XML string to the invoice.
	 *
	 * @param string  XML data of invoice to be imported.
	 *
	 * @author Former03 GmbH :: Florian Lippert <flo@syscp.org>
	 */

	function importXml($invoiceXmlString)
	{
		if($this->userId == 0
		   || !is_array($this->user)
		   || empty($this->user)
		   || $this->user[getModeDetails($this->mode, 'TABLE_PANEL_USERS', 'key')] != $this->userId)
		{
			return false;
		}

		$invoiceXml = new SimpleXMLElement($invoiceXmlString);
		foreach($invoiceXml->service_category as $service_details)
		{
			foreach($service_details->invoice_row as $invoice_row)
			{
				$this->invoice[] = array(
					'service_type' => utf8_decode((string)$service_details['service_type']),
					'service_occurence' => utf8_decode((string)$invoice_row->service_occurence[0]),
					'key' => utf8_decode((string)$invoice_row['key']),
					'service_date' => utf8_decode((string)$invoice_row->service_date[0]),
					'service_date_begin' => utf8_decode((string)$invoice_row->service_date_begin[0]),
					'service_date_end' => utf8_decode((string)$invoice_row->service_date_end[0]),
					'description' => array(
						'caption' => utf8_decode((string)$invoice_row->caption[0]),
						'old_invoice_number' => utf8_decode((string)$invoiceXml->invoice_number[0]),
						'old_invoice_date' => utf8_decode((string)$invoiceXml->invoice_date[0])
					),
					'interval' => utf8_decode((string)$invoice_row->interval[0]),
					'quantity' => utf8_decode((string)$invoice_row->quantity[0]),
					'total_fee' => utf8_decode((string)$invoice_row->single_fee[0]),

					// We need single fee here, because big master exportArray is multiplying it by quantity

					'taxrate' => utf8_decode((string)$invoice_row->taxrate[0]),
					'total_fee_taxed' => utf8_decode((string)$invoice_row->total_fee_taxed[0])
				);
			}
		}
	}

	/**
	 * This method returns the cumulated credit note of all reinvoiced invoices.
	 *
	 * @return double Cumulated credit note
	 *
	 * @author Former03 GmbH :: Florian Lippert <flo@syscp.org>
	 */

	function getCreditNote()
	{
		if($this->userId == 0
		   || !is_array($this->user)
		   || empty($this->user)
		   || $this->user[getModeDetails($this->mode, 'TABLE_PANEL_USERS', 'key')] != $this->userId)
		{
			return false;
		}

		$returnval = 0;
		foreach($this->cancelledInvoices as $cancelledInvoice_Id => $cancelledInvoice)
		{
			if($cancelledInvoice['state'] == CONST_BILLING_INVOICESTATE_CANCELLED_REINVOICE_WITH_CREDIT_NOTE)
			{
				$returnval+= $cancelledInvoice['total_fee_taxed'];
			}
		}

		return $returnval;
	}
}

?>

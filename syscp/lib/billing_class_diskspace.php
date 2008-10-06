<?php

/**
 * Service Category class for Diskspace (billing_class_diskspace.php)
 *
 * @author    Former03 GmbH :: Florian Lippert <flo@syscp.org>
 * @package   Billing
 * @version   $Id$
 */

/**
 * This class extends serviceCategory and processes diskspace.
 * This serviceCategory overrides some parameters heavily to gather data from the same line as the hosting service.
 * @package   Billing
 */

class diskspace extends serviceCategory
{
	/**
	 * This array holds all diskspace usage with keys to userid and date.
	 * @var array
	 */

	var $diskspace_data = array();

	/**
	 * Class constructor of diskspace. Gets reference for database connection,
	 * admin mode and service name.
	 *
	 * @param db     Reference to database handler
	 * @param int    For admin mode set 1, otherwise 0
	 * @param string The name of the service
	 *
	 * @author Former03 GmbH :: Florian Lippert <flo@syscp.org>
	 */

	function __construct($db, $mode = 0, $service_name = '')
	{
		$this->allowLastInvoicedDatePastServiceStart = true;
		$this->toInvoiceTableData = array(
			'table' => getModeDetails($mode, 'TABLE_PANEL_USERS', 'table'),
			'keyfield' => getModeDetails($mode, 'TABLE_PANEL_USERS', 'key'),
			'condfield' => getModeDetails($mode, 'TABLE_PANEL_USERS', 'key')
		);

		if($service_name == '')
		{
			$service_name = 'diskspace';
		}

		parent::__construct(&$db, $mode, $service_name);
	}

	/**
	 * This method is a wrapper for parent::collect. Before launching it,
	 * we will gather information about diskspace usage for all given userids.
	 *
	 * @param bool   Should we fix invoice (means we call self::setLastInvoiced to latest invoiced date).
	 * @param bool   Should we include the setup fee?
	 * @param bool   Should we include the interval fees?
	 * @return array All invoice rows
	 *
	 * @author Former03 GmbH :: Florian Lippert <flo@syscp.org>
	 */

	function collect($fixInvoice = false, $include_setup_fee = false, $include_interval_fee = false)
	{
		$diskspace_result = $this->db->query('SELECT `' . getModeDetails($this->mode, 'TABLE_PANEL_DISKSPACE', 'key') . '`, `year`, `month`, `day`, AVG(`webspace`+`mail`+`mysql`) as diskspace FROM `' . getModeDetails($this->mode, 'TABLE_PANEL_DISKSPACE', 'table') . '` WHERE `' . getModeDetails($this->mode, 'TABLE_PANEL_DISKSPACE', 'key') . '` IN ( ' . implode(', ', $this->userIds) . ' ) GROUP BY `' . getModeDetails($this->mode, 'TABLE_PANEL_DISKSPACE', 'key') . '`, `year`, `month`, `day`');

		while($diskspace_row = $this->db->fetch_array($diskspace_result))
		{
			if(!isset($this->diskspace_data[$diskspace_row[getModeDetails($this->mode, 'TABLE_PANEL_DISKSPACE', 'key')]])
			   || !is_array($this->diskspace_data[$diskspace_row[getModeDetails($this->mode, 'TABLE_PANEL_DISKSPACE', 'key')]]))
			{
				$this->diskspace_data[$diskspace_row[getModeDetails($this->mode, 'TABLE_PANEL_DISKSPACE', 'key')]] = array();
			}

			$date = $diskspace_row['year'] . '-' . $diskspace_row['month'] . '-' . $diskspace_row['day'];

			if(!isset($this->diskspace_data[$diskspace_row[getModeDetails($this->mode, 'TABLE_PANEL_DISKSPACE', 'key')]][$date])
			   && checkDateArray(transferDateToArray($date)))
			{
				$this->diskspace_data[$diskspace_row[getModeDetails($this->mode, 'TABLE_PANEL_DISKSPACE', 'key')]][$date] = (int)$diskspace_row['diskspace'];
			}
		}

		reset($this->userIds);
		foreach($this->userIds as $userId)
		{
			// Using fixed values here, because those settings are always the same. interval_fee will be calculated lateron, when diskspace consumption is calculated

			$this->service_details[$userId]['service_active'] = '1';
			$this->service_details[$userId]['interval_fee'] = '0.00';
			$this->service_details[$userId]['interval_length'] = '1';
			$this->service_details[$userId]['interval_type'] = 'm';
			$this->service_details[$userId]['interval_payment'] = '1';

			// Always postpaid, we can't invoice this month/payment_term's diskspace, if it hasn't finished yet

			$this->service_details[$userId]['setup_fee'] = '0.00';
			$this->service_details[$userId]['payment_every'] = '1';
			$this->service_details[$userId]['lastinvoiced_date'] = $this->service_details[$userId]['lastinvoiced_date_diskspace'];

			// We still want to be able to calculate diskspace usage in case of no service information

			if($this->service_details[$userId]['lastinvoiced_date'] == '0000-00-00'
			   && $this->service_details[$userId]['servicestart_date'] == '0000-00-00'
			   && isset($this->diskspace_data[$userId])
			   && is_array($this->diskspace_data[$userId])
			   && !empty($this->diskspace_data[$userId]))
			{
				// Get the date of first appereance of diskspace

				ksort($this->diskspace_data[$userId]);
				$dates = array_keys($this->diskspace_data[$userId]);
				$this->service_details[$userId]['servicestart_date'] = $dates[0];
			}
		}

		return parent::collect($fixInvoice, $include_setup_fee, $include_interval_fee);
	}

	/**
	 * We need another setLastInvoiced here to store the lastinvoiced_date for diskspace separately.
	 *
	 * @param int   The serviceId
	 * @param array Service details
	 * @return bool The returncode of the sql query
	 *
	 * @author Former03 GmbH :: Florian Lippert <flo@syscp.org>
	 */

	function setLastInvoiced($serviceId, $service_detail)
	{
		$query = 'UPDATE `' . $this->toInvoiceTableData['table'] . '` SET `lastinvoiced_date_diskspace` = \'' . $service_detail['lastinvoiced_date'] . '\' ';
		$query.= ' WHERE `' . $this->toInvoiceTableData['keyfield'] . '` = \'' . $serviceId . '\' ';
		return $this->db->query($query);
	}

	/**
	 * Here we always set taxclass to 1, as diskspace doesn't have valid templates.
	 *
	 * @param date   The date when the template should have been valid
	 * @param array  All appropriate template keys
	 * @return array The valid template
	 *
	 * @author Former03 GmbH :: Florian Lippert <flo@syscp.org>
	 */

	function findValidTemplate($timestamp, $templatekeys)
	{
		return array_merge(array(
			'taxclass' => '1'
		), parent::findValidTemplate($timestamp, $templatekeys));
	}

	/**
	 * We don't have setup fees in diskspace service category.
	 *
	 * @param array  Service details
	 * @param array  Service description
	 * @return array The invoice row
	 *
	 * @author Former03 GmbH :: Florian Lippert <flo@syscp.org>
	 */

	function buildInvoiceRowSetupFee($service_detail, $service_description)
	{
		return array();
	}

	/**
	 * Here we calculate the diskspace usage, as we get the interval from the collector at this point.
	 * All the rest is done in parent::buildInvoiceRowIntervalFee.
	 *
	 * @param array  Service details
	 * @param array  Service description
	 * @return array The invoice row
	 *
	 * @author Former03 GmbH :: Florian Lippert <flo@syscp.org>
	 */

	function buildInvoiceRowIntervalFee($service_detail, $service_description)
	{
		$diskspace_total = 0;

		if(isset($this->diskspace_data[$service_detail[$this->toInvoiceTableData['keyfield']]])
		   && is_array($this->diskspace_data[$service_detail[$this->toInvoiceTableData['keyfield']]])
		   && !empty($this->diskspace_data[$service_detail[$this->toInvoiceTableData['keyfield']]]))
		{
			reset($this->diskspace_data[$service_detail[$this->toInvoiceTableData['keyfield']]]);
			foreach($this->diskspace_data[$service_detail[$this->toInvoiceTableData['keyfield']]] as $date => $diskspace)
			{
				if(calculateDayDifference($service_detail['service_date_begin'], $date) >= 0
				   && calculateDayDifference($date, $service_detail['service_date_end']) > 0)
				{
					$diskspace_total+= $diskspace;
				}
			}
		}

		$diskspace_total = $diskspace_total/calculateDayDifference($service_detail['service_date_begin'], $service_detail['service_date_end']);
		$service_description['diskspace_included'] = round(($service_detail['diskspace']/(1024)), 2);
		$service_description['diskspace_total'] = round(($diskspace_total/(1024)), 2);

		if($service_detail['diskspace'] < $diskspace_total
		   && $service_description['diskspace_included'] != '-1'
		   && (int)$service_detail['additional_diskspace_unit'] != 0)
		{
			$diskspace_exceeded = $diskspace_total-$service_detail['diskspace'];

			// Wir casten auf int um die Dezimalstellen zu entfernen. Danach wird 1 addiert ("je angefangenes gb diskspace")

			$service_detail['interval_fee'] = (int)((int)($diskspace_exceeded/$service_detail['additional_diskspace_unit'])+1)*$service_detail['additional_diskspace_fee'];
		}
		else
		{
			$service_detail['interval_fee'] = '0.00';
		}

		if($service_description['diskspace_included'] == '-1')
		{
			$service_description['caption_class'] = 'unlimited';
		}

		return parent::buildInvoiceRowIntervalFee($service_detail, $service_description);
	}
}

?>
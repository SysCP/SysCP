<?php

/**
 * Service Category class for Traffic (billing_class_traffic.php)
 *
 * @author    Former03 GmbH :: Florian Lippert <flo@syscp.org>
 * @package   Billing
 * @version   $Id$
 */

/**
 * This class extends serviceCategory and processes traffic.
 * This serviceCategory overrides some parameters heavily to gather data from the same line as the hosting service.
 * @package   Billing
 */

class traffic extends serviceCategory
{
	/**
	 * This array holds all traffic usage with keys to userid and date.
	 * @var array
	 */

	var $traffic_data = array();

	/**
	 * Class constructor of traffic. Gets reference for database connection,
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
			$service_name = 'traffic';
		}

		parent::__construct(&$db, $mode, $service_name);
	}

	/**
	 * This method is a wrapper for parent::collect. Before launching it,
	 * we will gather information about traffic usage for all given userids.
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
		$traffic_result = $this->db->query('SELECT `' . getModeDetails($this->mode, 'TABLE_PANEL_TRAFFIC', 'key') . '`, `year`, `month`, `day`, SUM(`http`+`ftp_down`+`ftp_up`+`mail`) as traffic FROM `' . getModeDetails($this->mode, 'TABLE_PANEL_TRAFFIC', 'table') . '` WHERE `' . getModeDetails($this->mode, 'TABLE_PANEL_TRAFFIC', 'key') . '` IN ( ' . implode(', ', $this->userIds) . ' ) GROUP BY `' . getModeDetails($this->mode, 'TABLE_PANEL_TRAFFIC', 'key') . '`, `year`, `month`, `day`');

		while($traffic_row = $this->db->fetch_array($traffic_result))
		{
			if(!isset($this->traffic_data[$traffic_row[getModeDetails($this->mode, 'TABLE_PANEL_TRAFFIC', 'key')]])
			   || !is_array($this->traffic_data[$traffic_row[getModeDetails($this->mode, 'TABLE_PANEL_TRAFFIC', 'key')]]))
			{
				$this->traffic_data[$traffic_row[getModeDetails($this->mode, 'TABLE_PANEL_TRAFFIC', 'key')]] = array();
			}

			$date = $traffic_row['year'] . '-' . $traffic_row['month'] . '-' . $traffic_row['day'];

			if(!isset($this->traffic_data[$traffic_row[getModeDetails($this->mode, 'TABLE_PANEL_TRAFFIC', 'key')]][$date])
			   && checkDateArray(transferDateToArray($date)))
			{
				$this->traffic_data[$traffic_row[getModeDetails($this->mode, 'TABLE_PANEL_TRAFFIC', 'key')]][$date] = (int)$traffic_row['traffic'];
			}
		}

		reset($this->userIds);
		foreach($this->userIds as $userId)
		{
			// Using fixed values here, because those settings are always the same. interval_fee will be calculated lateron, when traffic consumption is calculated

			$this->service_details[$userId]['service_active'] = '1';
			$this->service_details[$userId]['interval_fee'] = '0.00';
			$this->service_details[$userId]['interval_length'] = '1';
			$this->service_details[$userId]['interval_type'] = 'm';
			$this->service_details[$userId]['interval_payment'] = '1';

			// Always postpaid, we can't invoice this month/payment_term's traffic, if it hasn't finished yet

			$this->service_details[$userId]['setup_fee'] = '0.00';
			$this->service_details[$userId]['payment_every'] = '1';
			$this->service_details[$userId]['lastinvoiced_date'] = $this->service_details[$userId]['lastinvoiced_date_traffic'];

			// We still want to be able to calculate traffic usage in case of no service information

			if(($this->service_details[$userId]['lastinvoiced_date'] == '0' || $this->service_details[$userId]['lastinvoiced_date'] == '')
			   && ($this->service_details[$userId]['servicestart_date'] == '0' || $this->service_details[$userId]['servicestart_date'] == '')
			   && isset($this->traffic_data[$userId])
			   && is_array($this->traffic_data[$userId])
			   && !empty($this->traffic_data[$userId]))
			{
				// Get the date of first appereance of traffic

				ksort($this->traffic_data[$userId]);
				$dates = array_keys($this->traffic_data[$userId]);
				$this->service_details[$userId]['servicestart_date'] = $dates[0];
			}
		}

		return parent::collect($fixInvoice, $include_setup_fee, $include_interval_fee);
	}

	/**
	 * We need another setLastInvoiced here to store the lastinvoiced_date for traffic separately.
	 *
	 * @param int   The serviceId
	 * @param array Service details
	 * @return bool The returncode of the sql query
	 *
	 * @author Former03 GmbH :: Florian Lippert <flo@syscp.org>
	 */

	function setLastInvoiced($serviceId, $service_detail)
	{
		$query = 'UPDATE `' . $this->toInvoiceTableData['table'] . '` SET `lastinvoiced_date_traffic` = \'' . $service_detail['lastinvoiced_date'] . '\' ';
		$query.= ' WHERE `' . $this->toInvoiceTableData['keyfield'] . '` = \'' . $serviceId . '\' ';
		return $this->db->query($query);
	}

	/**
	 * Here we always set taxclass to 1, as traffic doesn't have valid templates.
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
	 * We don't have setup fees in traffic service category.
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
	 * Here we calculate the traffic usage, as we get the interval from the collector at this point.
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
		$traffic_total = 0;

		if(isset($this->traffic_data[$service_detail[$this->toInvoiceTableData['keyfield']]])
		   && is_array($this->traffic_data[$service_detail[$this->toInvoiceTableData['keyfield']]])
		   && !empty($this->traffic_data[$service_detail[$this->toInvoiceTableData['keyfield']]]))
		{
			reset($this->traffic_data[$service_detail[$this->toInvoiceTableData['keyfield']]]);
			foreach($this->traffic_data[$service_detail[$this->toInvoiceTableData['keyfield']]] as $date => $traffic)
			{
				if(calculateDayDifference($service_detail['service_date_begin'], $date) >= 0
				   && calculateDayDifference($date, $service_detail['service_date_end']) > 0)
				{
					$traffic_total+= $traffic;
				}
			}
		}

		$service_description['traffic_included'] = round(($service_detail['traffic'] / (1024 * 1024)), 2);
		$service_description['traffic_total'] = round(($traffic_total / (1024 * 1024)), 2);

		if($service_detail['traffic'] < $traffic_total
		   && $service_description['traffic_included'] != '-1'
		   && (int)$service_detail['additional_traffic_unit'] != 0)
		{
			$traffic_exceeded = $traffic_total - $service_detail['traffic'];

			// Wir casten auf int um die Dezimalstellen zu entfernen. Danach wird 1 addiert ("je angefangenes gb traffic")

			$service_detail['interval_fee'] = (int)((int)($traffic_exceeded / $service_detail['additional_traffic_unit']) + 1) * $service_detail['additional_traffic_fee'];
		}
		else
		{
			$service_detail['interval_fee'] = '0.00';
		}

		if($service_description['traffic_included'] == '-1')
		{
			$service_description['caption_class'] = 'unlimited';
		}

		return parent::buildInvoiceRowIntervalFee($service_detail, $service_description);
	}
}

?>
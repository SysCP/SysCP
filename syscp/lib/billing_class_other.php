<?php

/**
 * Service Category class for Other Services (billing_class_other.php)
 *
 * @author    Former03 GmbH :: Florian Lippert <flo@syscp.org>
 * @package   Billing
 * @version   $Id$
 */

/**
 * This class extends serviceCategory and processes other services,
 * basically this is just setting the source of services correctly
 * and managing the overriding of service_type if needed
 * @package   Billing
 */

class other extends serviceCategory
{
	/**
	 * Class constructor of other. Gets reference for database connection,
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
		$this->toInvoiceTableData = array(
			'table' => TABLE_BILLING_SERVICE_OTHER,
			'keyfield' => 'id',
			'condfield' => 'customerid'
		);
		$this->serviceTemplateTableData = array(
			'table' => TABLE_BILLING_SERVICE_OTHER_TEMPLATES,
			'keyfield' => 'templateid'
		);

		if($service_name == '')
		{
			$service_name = 'other';
		}

		parent::__construct(&$db, $mode, $service_name);
	}

	/**
	 * We add the service type to service description and call the parent method afterwards.
	 *
	 * @param array  Service details
	 * @param array  Service description
	 * @return array The invoice row
	 *
	 * @author Former03 GmbH :: Florian Lippert <flo@syscp.org>
	 */

	function buildInvoiceRowSetupFee($service_detail, $service_description)
	{
		if(isset($service_detail['service_type'])
		   && $service_detail['service_type'] != '')
		{
			$service_description['service_type'] = $service_detail['service_type'];
		}

		return parent::buildInvoiceRowSetupFee($service_detail, $service_description);
	}

	/**
	 * We add the service type to service description and call the parent method afterwards.
	 *
	 * @param array  Service details
	 * @param array  Service description
	 * @return array The invoice row
	 *
	 * @author Former03 GmbH :: Florian Lippert <flo@syscp.org>
	 */

	function buildInvoiceRowIntervalFee($service_detail, $service_description)
	{
		if(isset($service_detail['service_type'])
		   && $service_detail['service_type'] != '')
		{
			$service_description['service_type'] = $service_detail['service_type'];
		}

		return parent::buildInvoiceRowIntervalFee($service_detail, $service_description);
	}
}

?>
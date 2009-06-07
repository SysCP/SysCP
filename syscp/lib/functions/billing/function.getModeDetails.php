<?php

/**
 * Returns appropriate table names and -keys depending on mode (admin or customer).
 *
 * @param  int   The mode
 * @param  string Subject, eg tablename.
 * @param  string Key, eg 'table' or 'key'
 * @return string Table or Key
 *
 * @author Former03 GmbH :: Florian Lippert <flo@syscp.org>
 */

function getModeDetails($mode, $subject, $key)
{
	$modes = array(
		0 => array(
			'TABLE_PANEL_USERS' => array(
				'table' => TABLE_PANEL_CUSTOMERS,
				'key' => 'customerid'
			),
			'TABLE_PANEL_TRAFFIC' => array(
				'table' => TABLE_PANEL_TRAFFIC,
				'key' => 'customerid'
			),
			'TABLE_PANEL_DISKSPACE' => array(
				'table' => TABLE_PANEL_DISKSPACE,
				'key' => 'customerid'
			),
			'TABLE_BILLING_INVOICES' => array(
				'table' => TABLE_BILLING_INVOICES,
				'key' => 'customerid'
			),
			'TABLE_BILLING_INVOICE_CHANGES' => array(
				'table' => TABLE_BILLING_INVOICE_CHANGES,
				'key' => 'customerid'
			),
			'TABLE_BILLING_SERVICE_CATEGORIES' => array(
				'table' => TABLE_BILLING_SERVICE_CATEGORIES,
				'key' => 'customerid'
			),
		),
		1 => array(
			'TABLE_PANEL_USERS' => array(
				'table' => TABLE_PANEL_ADMINS,
				'key' => 'adminid'
			),
			'TABLE_PANEL_TRAFFIC' => array(
				'table' => TABLE_PANEL_TRAFFIC_ADMINS,
				'key' => 'adminid'
			),
			'TABLE_PANEL_DISKSPACE' => array(
				'table' => TABLE_PANEL_DISKSPACE_ADMINS,
				'key' => 'adminid'
			),
			'TABLE_BILLING_INVOICES' => array(
				'table' => TABLE_BILLING_INVOICES_ADMINS,
				'key' => 'adminid'
			),
			'TABLE_BILLING_INVOICE_CHANGES' => array(
				'table' => TABLE_BILLING_INVOICE_CHANGES_ADMINS,
				'key' => 'adminid'
			),
			'TABLE_BILLING_SERVICE_CATEGORIES' => array(
				'table' => TABLE_BILLING_SERVICE_CATEGORIES_ADMINS,
				'key' => 'adminid'
			),
		)
	);

	if(isset($modes[$mode])
	   && isset($modes[$mode][$subject])
	   && isset($modes[$mode][$subject][$key]))
	{
		return $modes[$mode][$subject][$key];
	}
	else
	{
		return false;
	}
}

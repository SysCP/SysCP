<?php

/**
 * Calculates invoice fees and stores it in panel_users,
 * according to details in billing_service_categories.
 *
 * @param  int   The mode
 * @param  int   Userid to begin with Subject, eg tablename.
 * @param  int   Number of Users we should handle in this run
 * @param  int   Single userid we should focus on.
 * @return array Results like current invoice fees etc.
 *
 * @author Former03 GmbH :: Florian Lippert <flo@syscp.org>
 */

function cacheInvoiceFees($mode = 0, $begin = null, $count = null, $userid = null)
{
	global $db;
	$returnval = array();
	$service_categories_result = $db->query('SELECT * FROM `' . getModeDetails($mode, 'TABLE_BILLING_SERVICE_CATEGORIES', 'table') . '` ORDER BY `id` ASC');

	while($service_categories_row = $db->fetch_array($service_categories_result))
	{
		$service_categories[$service_categories_row['category_name']] = $service_categories_row;

		if($service_categories_row['category_cachefield'] != '')
		{
			$zeroUpdates[$service_categories_row['category_cachefield']] = 0;
		}
	}

	if($userid !== null
	   && intval($userid) !== 0)
	{
		$userSelection = " WHERE `" . getModeDetails($mode, 'TABLE_PANEL_USERS', 'key') . "` = '" . $userid . "' ";
	}
	else
	{
		$userSelection = '';
	}

	if($begin !== null
	   && intval($count) !== 0)
	{
		$limit = ' LIMIT ' . intval($begin) . ', ' . intval($count);
	}
	else
	{
		$limit = '';
	}

	$users = $db->query("SELECT * FROM `" . getModeDetails($mode, 'TABLE_PANEL_USERS', 'table') . "` " . $userSelection . ' ' . $limit);

	while($user = $db->fetch_array($users))
	{
		if(!isset($user['customer_categories_once']))
		{
			$user['customer_categories_once'] = '';
		}

		if(!isset($user['customer_categories_period']))
		{
			$user['customer_categories_period'] = '';
		}

		$myInvoice = new invoice($db, $mode, explode('-', $user['customer_categories_once']), explode('-', $user['customer_categories_period']));

		if($myInvoice->collect($user[getModeDetails($mode, 'TABLE_PANEL_USERS', 'key')]) === true)
		{
			$total_fee_taxed = 0;
			$myUpdates = $zeroUpdates;
			$total_fees_array = $myInvoice->getTotalFee($lng);
			foreach($total_fees_array as $service_type => $total_fee_array)
			{
				if(isset($service_categories[$service_type])
				   && isset($service_categories[$service_type]['category_cachefield'])
				   && $service_categories[$service_type]['category_cachefield'] != '')
				{
					$myUpdates[$service_categories[$service_type]['category_cachefield']] = $total_fee_array['total_fee_taxed'];
					$total_fee_taxed+= $total_fee_array['total_fee_taxed'];
				}
			}

			$updates = '';
			foreach($myUpdates as $myField => $myValue)
			{
				$updates.= ', `' . $myField . '` = \'' . $myValue . '\' ';
			}

			$db->query('UPDATE `' . getModeDetails($mode, 'TABLE_PANEL_USERS', 'table') . '` SET `invoice_fee` = \'' . $total_fee_taxed . '\' ' . $updates . ' WHERE `' . getModeDetails($mode, 'TABLE_PANEL_USERS', 'key') . '` = \'' . $user[getModeDetails($mode, 'TABLE_PANEL_USERS', 'key')] . '\' ');
			$returnval[$user[getModeDetails($mode, 'TABLE_PANEL_USERS', 'key')]] = $myUpdates;
			$returnval[$user[getModeDetails($mode, 'TABLE_PANEL_USERS', 'key')]]['total'] = $total_fee_taxed;
			$returnval[$user[getModeDetails($mode, 'TABLE_PANEL_USERS', 'key')]]['loginname'] = $user['loginname'];
		}
	}

	return $returnval;
}

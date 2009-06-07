<?php

/**
 * Get full month name for interval short
 *
 * @param  string one digit short of month (y,m,d,h,i,s)
 * @param  bool   should we add a plural s?
 * @return mixed  the full month name
 *
 * @author Former03 GmbH :: Florian Lippert <flo@syscp.org>
 */

function getFullIntervalName($intervalType, $pluralS = false)
{
	switch(strtolower($intervalType))
	{
		case 'y':
			$payment_every_type_fullname = 'year';
			break;
		case 'm':
			$payment_every_type_fullname = 'month';
			break;
		case 'd':
			$payment_every_type_fullname = 'day';
			break;
		default:
			$payment_every_type_fullname = false;
	}

	if($pluralS === true
	   && $payment_every_type_fullname !== false)
	{
		$payment_every_type_fullname.= 's';
	}

	return $payment_every_type_fullname;
}

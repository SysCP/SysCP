<?php

/**
 * Get all date interval types as an array or option code
 *
 * @param  string Either array or option, affects the value returned by function
 * @param  string Only relevant when first argument is option, this one will be the selected one
 * @return mixed  Depends on first option, array of intervaltypes or optioncode of intervaltypes ready to be inserted in a select statement
 *
 * @author Former03 GmbH :: Florian Lippert <flo@syscp.org>
 */

function getIntervalTypes($what = 'array', $selected = '')
{
	global $lng;
	$intervalTypes = array(
		'y',
		'm',
		'd'
	);

	if(!in_array($selected, $intervalTypes))
	{
		$selected = '';
	}

	switch($what)
	{
		case 'option':
			$returnval = '';
			foreach($intervalTypes as $intervalFeeType)
			{
				$returnval.= makeoption($lng['panel']['intervalfee_type'][$intervalFeeType], $intervalFeeType, $selected);
			}

			break;
		case 'array':
		default:
			$returnval = $intervalTypes;
			break;
	}

	return $returnval;
}

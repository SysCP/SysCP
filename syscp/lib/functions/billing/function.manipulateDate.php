<?php

/**
 * Manipulates a date, like adding a month or so and correcting it afterwards
 * (2008-01-33 -> 2008-02-02)
 *
 * @param  array  The date array
 * @param  string The operation, may be '+', 'add', 'sum' or '-', 'subtract', 'subduct'
 * @param  int    Number if days/month/years
 * @param  string Either 'y', 'm', 'd', depending on what part to change.
 * @param  array  A valid date array with original date, mandatory for more than one manipulation on same date.
 * @return date   The manipulated date array
 *
 * @author Former03 GmbH :: Florian Lippert <flo@syscp.org>
 */

function manipulateDate($date, $operation, $count, $type, $original_date = null)
{
	$newdate = $date;
	$date = transferDateToArray($date);

	if(checkDateArray($date) === true
	   && isset($date[$type]))
	{
		switch($operation)
		{
			case '+':
			case 'add':
			case 'sum':
				$date[$type]+= (int)$count;
				break;
			case '-':
			case 'subtract':
			case 'subduct':
				$date[$type]-= (int)$count;
				break;
		}

		if($original_date !== null
		   && ($original_date = transferDateToArray($original_date)) !== false
		   && $type == 'm')
		{
			if($original_date['d'] > getDaysForMonth($date['m'], $date['y']))
			{
				$date['d'] = getDaysForMonth($date['m'], $date['y']) - (getDaysForMonth($original_date['m'], $original_date['y']) - $original_date['d']);
			}
			else
			{
				$date['d'] = $original_date['d'];
			}
		}

		while(checkDateArray($date) === false)
		{
			if($date['d'] > getDaysForMonth($date['m'], $date['y']))
			{
				$date['d']-= getDaysForMonth($date['m'], $date['y']);
				$date['m']++;
			}

			if($date['d'] < 1)
			{
				$date['m']--;
				$date['d']+= getDaysForMonth($date['m'], $date['y']);

				// Adding here, because date[d] is negative
			}

			if($date['m'] > 12)
			{
				$date['m']-= 12;
				$date['y']++;
			}

			if($date['m'] < 1)
			{
				$date['y']--;
				$date['m']+= 12;
			}
		}

		$newdate = $date['y'] . '-' . $date['m'] . '-' . $date['d'];
	}

	return $newdate;
}

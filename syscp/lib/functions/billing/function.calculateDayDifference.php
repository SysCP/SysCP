<?php

/**
 * Calculates the number of days between first and second parameter
 *
 * @param  int Date 1
 * @param  int Date 2
 * @return int Number of days
 *
 * @author Former03 GmbH :: Florian Lippert <flo@syscp.org>
 */

function calculateDayDifference($begin, $end)
{
	$daycount = 0;
	$begin = transferDateToArray($begin);
	$end = transferDateToArray($end);
	$direction = 1;

	// Sanity check, if our given array is in the right format

	if(checkDateArray($begin) === true
	   && checkDateArray($end) === true)
	{
		if(strtotime($end['y'] . '-' . $end['m'] . '-' . $end['d']) < strtotime($begin['y'] . '-' . $begin['m'] . '-' . $begin['d']))
		{
			$tmp = $end;
			$end = $begin;
			$begin = $tmp;
			unset($tmp);
			$direction = (-1);
		}

		$yeardiff = (int)$end['y'] - (int)$begin['y'];
		$monthdiff = ((int)$end['m'] + 12 * $yeardiff) - (int)$begin['m'];
		for ($i = 0;$i < abs($monthdiff);$i++)
		{
			$daycount+= getDaysForMonth($begin['m'] + $i, $begin['y']);
		}

		$daycount+= $end['d'] - $begin['d'];
		$daycount*= $direction;
	}

	return $daycount;
}

<?php

/**
 * Determines the number of days at a specified month/year.
 *
 * @param  int The month
 * @param  int The year
 * @return int Number of days
 *
 * @author Former03 GmbH :: Florian Lippert <flo@syscp.org>
 */

function getDaysForMonth($month, $year)
{
	if((int)$month > 12)
	{
		$year+= (int)($month / 12);
		$month = $month % 12;
	}

	if((int)($month) == 0)
	{
		$month = 12;
	}

	$months = array(
		1 => 31,
		2 => 28,
		3 => 31,
		4 => 30,
		5 => 31,
		6 => 30,
		7 => 31,
		8 => 31,
		9 => 30,
		10 => 31,
		11 => 30,
		12 => 31
	);

	if(getDaysForYear($month, $year) == 366)
	{
		$months[2] = '29';
	}

	return $months[intval($month)];
}

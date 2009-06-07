<?php

/**
 * Determines the number of days at a specified year.
 *
 * @param  int The year
 * @return int Number of days
 *
 * @author Former03 GmbH :: Florian Lippert <flo@syscp.org>
 */

function getDaysForYear($month, $year)
{
	if($month >= 3)$year++;
	return ((($year % 4) == 0 && ($year % 100) != 0) ? 366 : 365);
}

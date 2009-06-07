<?php

/**
 * Checks if a date array is valid.
 *
 * @param  array The date array
 * @return bool  True if valid, false otherwise.
 *
 * @author Former03 GmbH :: Florian Lippert <flo@syscp.org>
 */

function checkDateArray($date)
{
	return (is_array($date) && isset($date['y']) && isset($date['m']) && isset($date['d']) && (int)$date['m'] >= 1 && (int)$date['m'] <= 12 && (int)$date['d'] >= 1 && (int)$date['d'] <= getDaysForMonth($date['m'], $date['y']));
}

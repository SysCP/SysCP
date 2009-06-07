<?php

/**
 * Makes nice array out of a date.
 *
 * @param  mixed The date: either string (2008-02-14), unix timestamp, or array.
 * @return array The array( 'y' => 2008, 'm' => 2, 'd' => 14 );
 *
 * @author Former03 GmbH :: Florian Lippert <flo@syscp.org>
 */

function transferDateToArray($date)
{
	if(!is_array($date))
	{
		if(is_numeric($date))
		{
			$date = array(
				'y' => (int)date('Y', $date),
				'm' => (int)date('m', $date),
				'd' => (int)date('d', $date)
			);
		}
		else
		{
			$date = explode('-', $date);
			$date = array(
				'y' => (int)$date[0],
				'm' => (int)$date[1],
				'd' => (int)$date[2]
			);
		}
	}
	else
	{
		$date['y'] = (int)$date['y'];
		$date['m'] = (int)$date['m'];
		$date['d'] = (int)$date['d'];
	}

	return $date;
}

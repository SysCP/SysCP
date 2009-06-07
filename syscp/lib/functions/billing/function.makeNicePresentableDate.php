<?php

/**
 * Simple reformater for a date strtotime understands
 *
 * @param  string A date strtotime understands
 * @param  string Time format, may contain anything date() can handle.
 * @return string New nicely formatted date
 *
 * @author Former03 GmbH :: Florian Lippert <flo@syscp.org>
 */

function makeNicePresentableDate($date, $format = 'Y-m-d')
{
	return date($format, strtotime($date));
}

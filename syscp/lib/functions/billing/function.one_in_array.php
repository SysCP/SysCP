<?php

/**
 * Wrapper for in_array, which can also handle an array as needle.
 *
 * @param  mixed Either array or string, if string it behaves like in_array.
 * @param  array A haystack to search in.
 * @param  bool  See in_array documentation for details, will passed directly.
 * @return bool  True if one needle is in the haystack.
 *
 * @author Former03 GmbH :: Florian Lippert <flo@syscp.org>
 */

function one_in_array($needles, $haystack, $strict = false)
{
	$returnval = false;

	if(!is_array($needles))
	{
		$needle = $needles;
		unset($needles);
		$needles = array(
			$needle
		);
		unset($needle);
	}

	foreach($needles as $needle)
	{
		if(in_array($needle, $haystack, $strict))
		{
			$returnval = true;
		}
	}

	return $returnval;
}

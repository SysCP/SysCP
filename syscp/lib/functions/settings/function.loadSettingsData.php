<?php

/**
 * This file is part of the SysCP project.
 * Copyright (c) 2003-2009 the SysCP Team (see authors).
 *
 * For the full copyright and license information, please view the COPYING
 * file that was distributed with this source code. You can also view the
 * COPYING file online at http://files.syscp.org/misc/COPYING.txt
 *
 * @copyright  (c) the authors
 * @author     Florian Lippert <flo@syscp.org>
 * @license    GPLv2 http://files.syscp.org/misc/COPYING.txt
 * @package    Functions
 * @version    $Id$
 */

function loadSettingsData()
{
	global $lng;
	
	$settings_data_files = array();
	$settings_data_dirname = './actions/admin/settings/';
	$settings_data_dirhandle = opendir($settings_data_dirname);
	while(false !== ($settings_data_filename = readdir($settings_data_dirhandle)))
	{
		if($settings_data_filename != '.' && $settings_data_filename != '..' && $settings_data_filename != '' && substr($settings_data_filename, -4 ) == '.php')
		{
			$settings_data_files[] = $settings_data_dirname . $settings_data_filename;
		}
	}
	
	sort($settings_data_files);
	
	$settings_data = array();
	
	foreach($settings_data_files as $settings_data_filename)
	{
		$settings_data = array_merge_recursive($settings_data, include($settings_data_filename));
	}
	
	return $settings_data;
}

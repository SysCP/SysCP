<?php
/**
 * filename: $Source$
 * begin: Sunday, Sep 12, 2004
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version. This program is distributed in the
 * hope that it will be useful, but WITHOUT ANY WARRANTY; without even the
 * implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU General Public License for more details.
 *
 * @author Florian Lippert <flo@redenswert.de>
 * @copyright (C) 2003-2004 Florian Lippert
 * @package System
 * @version $Id$
 */

	if($settings['panel']['version'] == '1.2-beta1' || $settings['panel']['version'] == '1.2-rc1')
	{
		$db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='1.2.0' WHERE `settinggroup`='panel' AND `varname`='version'");
		$settings['panel']['version'] = '1.2.0';
	}
	if($settings['panel']['version'] == '1.2.0')
	{
		$db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='1.2.1' WHERE `settinggroup`='panel' AND `varname`='version'");
		$settings['panel']['version'] = '1.2.1';
	}
	if($settings['panel']['version'] == '1.2.1')
	{
		$db->query("ALTER TABLE `".TABLE_PANEL_SESSIONS."` CHANGE `useragent` `useragent` VARCHAR( 255 ) NOT NULL");
		$db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='1.2.2' WHERE `settinggroup`='panel' AND `varname`='version'");
		$settings['panel']['version'] = '1.2.2';
	}

?>
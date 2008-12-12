<?php

/**
 * This file is part of the SysCP project.
 * Copyright (c) 2003-2008 the SysCP Team (see authors).
 *
 * For the full copyright and license information, please view the COPYING
 * file that was distributed with this source code. You can also view the
 * COPYING file online at http://files.syscp.org/misc/COPYING.txt
 *
 * @copyright  (c) the authors
 * @author     Florian Aders <eleras@syscp.org>
 * @license    GPLv2 http://files.syscp.org/misc/COPYING.txt
 * @package    System
 * @version    $Id: updatesql_1.2.19-20.inc.php 2458 2008-11-30 13:50:23Z flo $
 */

if($settings['panel']['version'] == '1.4')
{
	$updatelog->logAction(ADM_ACTION, LOG_WARNING, "Updating from 1.4 to 1.4-svn1");

	// Going to fix the stuff the update 1.2.19-svn42 to 1.2.19-svn43 broke

	$result = $db->query("SELECT * FROM `" . TABLE_PANEL_DOMAINS . "` WHERE `documentroot` LIKE 'http%';");

	while($row = $db->fetch_array($result))
	{
		if(preg_match("#(https?)://?(.*)#i", $row['documentroot'], $matches))
		{
			$row['documentroot'] = $matches[1] . "://" . $matches[2];
			$db->query("UPDATE `" . TABLE_PANEL_DOMAINS . "` SET `documentroot` = '" . $db->escape($row['documentroot']) . "' WHERE `id` = '" . $row['id'] . "';");
		}
	}

	// set new version

	$query = 'UPDATE `%s` SET `value` = \'1.4-svn1\' WHERE `settinggroup` = \'panel\' AND `varname` = \'version\'';
	$query = sprintf($query, TABLE_PANEL_SETTINGS);
	$db->query($query);
	$settings['panel']['version'] = '1.4-svn1';
}

if($settings['panel']['version'] == '1.4-svn1')
{
	$updatelog->logAction(ADM_ACTION, LOG_WARNING, "Updating from 1.4-svn1 to 1.4.1");

	// set new version

	$query = 'UPDATE `%s` SET `value` = \'1.4.1\' WHERE `settinggroup` = \'panel\' AND `varname` = \'version\'';
	$query = sprintf($query, TABLE_PANEL_SETTINGS);
	$db->query($query);
	$settings['panel']['version'] = '1.4.1';
}

if($settings['panel']['version'] == '1.4.1')
{
	$updatelog->logAction(ADM_ACTION, LOG_WARNING, "Updating from 1.4.1 to 1.4.1-svn1");

	// remove double menu entries billing, bug #1003
	$db->query("DELETE FROM `" . TABLE_PANEL_NAVIGATION . "` WHERE `url` = 'billing.nourl' AND `lang` = 'billing;billing'");
	$db->query("DELETE FROM `" . TABLE_PANEL_NAVIGATION . "` WHERE `url` = 'billing_openinvoices.php' AND `lang` = 'billing;openinvoices'");
	$db->query("DELETE FROM `" . TABLE_PANEL_NAVIGATION . "` WHERE `url` = 'billing_openinvoices.php?mode=1' AND `lang` = 'billing;openinvoices_admin'");
	$db->query("DELETE FROM `" . TABLE_PANEL_NAVIGATION . "` WHERE `url` = 'billing_invoices.php' AND `lang` = 'billing;invoices'");
	$db->query("DELETE FROM `" . TABLE_PANEL_NAVIGATION . "` WHERE `url` = 'billing_invoices.php?mode=1' AND `lang` = 'billing;invoices_admin'");
	$db->query("DELETE FROM `" . TABLE_PANEL_NAVIGATION . "` WHERE `url` = 'billing_other.php' AND `lang` = 'billing;other'");
	$db->query("DELETE FROM `" . TABLE_PANEL_NAVIGATION . "` WHERE `url` = 'billing_taxrates.php' AND `lang` = 'billing;taxclassesnrates'");
	$db->query("DELETE FROM `" . TABLE_PANEL_NAVIGATION . "` WHERE `url` = 'billing_domains_templates.php' AND `lang` = 'billing;domains_templates'");
	$db->query("DELETE FROM `" . TABLE_PANEL_NAVIGATION . "` WHERE `url` = 'billing_other_templates.php' AND `lang` = 'billing;other_templates'");
 
	if($settings['billing']['activate_billing'] == '1')
	{
		$_value = 'edit_billingdata';
	}
	else
	{
		$_value = 'billing.activate_billing';
	}

	// add a "fresh" billing menu
	$db->query("INSERT INTO `" . TABLE_PANEL_NAVIGATION . "` (`area`, `parent_url`, `lang`, `url`, `order`, `required_resources`, `new_window`) VALUES('admin', '', 'billing;billing', 'billing.nourl', 100, '".$_value."', 0)");
	$db->query("INSERT INTO `" . TABLE_PANEL_NAVIGATION . "` (`area`, `parent_url`, `lang`, `url`, `order`, `required_resources`, `new_window`) VALUES('admin', 'billing.nourl', 'billing;openinvoices', 'billing_openinvoices.php', 110, 'edit_billingdata', 0)");
	$db->query("INSERT INTO `" . TABLE_PANEL_NAVIGATION . "` (`area`, `parent_url`, `lang`, `url`, `order`, `required_resources`, `new_window`) VALUES('admin', 'billing.nourl', 'billing;openinvoices_admin', 'billing_openinvoices.php?mode=1', 115, 'edit_billingdata', 0)");
	$db->query("INSERT INTO `" . TABLE_PANEL_NAVIGATION . "` (`area`, `parent_url`, `lang`, `url`, `order`, `required_resources`, `new_window`) VALUES('admin', 'billing.nourl', 'billing;invoices', 'billing_invoices.php', 120, 'edit_billingdata', 0)");
	$db->query("INSERT INTO `" . TABLE_PANEL_NAVIGATION . "` (`area`, `parent_url`, `lang`, `url`, `order`, `required_resources`, `new_window`) VALUES('admin', 'billing.nourl', 'billing;invoices_admin', 'billing_invoices.php?mode=1', 125, 'edit_billingdata', 0)");
	$db->query("INSERT INTO `" . TABLE_PANEL_NAVIGATION . "` (`area`, `parent_url`, `lang`, `url`, `order`, `required_resources`, `new_window`) VALUES('admin', 'billing.nourl', 'billing;other', 'billing_other.php', 130, 'edit_billingdata', 0)");
	$db->query("INSERT INTO `" . TABLE_PANEL_NAVIGATION . "` (`area`, `parent_url`, `lang`, `url`, `order`, `required_resources`, `new_window`) VALUES('admin', 'billing.nourl', 'billing;taxclassesnrates', 'billing_taxrates.php', 140, 'edit_billingdata', 0)");
	$db->query("INSERT INTO `" . TABLE_PANEL_NAVIGATION . "` (`area`, `parent_url`, `lang`, `url`, `order`, `required_resources`, `new_window`) VALUES('admin', 'billing.nourl', 'billing;domains_templates', 'billing_domains_templates.php', 150, 'edit_billingdata', 0)");
	$db->query("INSERT INTO `" . TABLE_PANEL_NAVIGATION . "` (`area`, `parent_url`, `lang`, `url`, `order`, `required_resources`, `new_window`) VALUES('admin', 'billing.nourl', 'billing;other_templates', 'billing_other_templates.php', 160, 'edit_billingdata', 0)");

	// fix aps-menu so it's shown to admin's when aps is enabled, bug #1002
	if($settings['aps']['aps_active'] == '1')
	{
		$db->query("UPDATE `" . TABLE_PANEL_NAVIGATION . "` SET `required_resources` = 'can_manage_aps_packages' WHERE `area` = 'admin' AND `lang` = 'admin;aps' AND `url` = 'admin_aps.nourl'");
	}

	// remove double menu entries phpconfiguration, bug #1024
	$db->query("DELETE FROM `" . TABLE_PANEL_NAVIGATION . "` WHERE `parent_url`='admin_server.nourl' AND `lang`='menue;phpsettings;maintitle' AND `url`='admin_phpsettings.php?page=overview'");

	// and add a clean menu-entry
	$db->query("INSERT INTO `" . TABLE_PANEL_NAVIGATION . "`  (`area`, `parent_url`, `lang`, `url`, `order`, `required_resources`, `new_window`) VALUES ('admin', 'admin_server.nourl', 'menue;phpsettings;maintitle', 'admin_phpsettings.php?page=overview', 80, 'system.mod_fcgid', 0)");

	// give at least ONE admin the permission to edit phpsettings, bug #1031
	$cntCanEditPHP = $db->query_first("SELECT COUNT(`caneditphpsettings`) as `cnt` FROM `" . TABLE_PANEL_ADMINS . "` WHERE `caneditphpsettings` = '1'");
	if($cntCanEditPHP['cnt'] <= 0)
	{
		// none of the admins can edit php-settings, 
		//so we give those who can edit serversettings the right to edit php-settings
		$db->query("UPDATE `" . TABLE_PANEL_ADMINS . "` SET `caneditphpsettings` = '1' WHERE `change_serversettings` = '1'");
	}

	// set new version

	$query = 'UPDATE `%s` SET `value` = \'1.4.1-svn1\' WHERE `settinggroup` = \'panel\' AND `varname` = \'version\'';
	$query = sprintf($query, TABLE_PANEL_SETTINGS);
	$db->query($query);
	$settings['panel']['version'] = '1.4.1-svn1';
}

if($settings['panel']['version'] == '1.4.1-svn1')
{
	$updateto = '1.4.1-svn2';
	$updatelog->logAction(ADM_ACTION, LOG_WARNING, "Updating from " . $settings['panel']['version'] . " to " . $updateto);

	/*
	 * Special cases: phpmyadmin, webftp, webmail
	 */
	// phpmyadmin

	if(!isset($settings['panel']['phpmyadmin_url']))
	{
		$settings['panel']['phpmyadmin_url'] = '';
	}

	$result = $db->query('SELECT * FROM `' . TABLE_PANEL_NAVIGATION . '` WHERE `lang` = "menue;mysql;phpmyadmin"');
	$nums = $db->num_rows($result);

	if($nums == 0)
	{
		// in previous versions, menu entries have been deleted from the database when setting the
		// feature to enabled='off' so we need to add it again here!

		$db->query("INSERT INTO `" . TABLE_PANEL_NAVIGATION . "` SET `lang` = 'menue;mysql;phpmyadmin', `url`='" . $db->escape($settings['panel']['phpmyadmin_url']) . "', `area`='customer', `new_window`='1', `required_resources` = 'mysqls_used', `order` = '99', `parent_url` = 'customer_mysql.php'");
	}
	elseif($settings['panel']['phpmyadmin_url'] != '')
	{
		$db->query("UPDATE `" . TABLE_PANEL_NAVIGATION . "` SET `url`='" . $db->escape($settings['panel']['phpmyadmin_url']) . "' WHERE `lang` = 'menue;mysql;phpmyadmin' AND `area`='customer' AND `parent_url` = 'customer_mysql.php'");
	}

	// webftp

	if(!isset($settings['panel']['webftp_url']))
	{
		$settings['panel']['webftp_url'] = '';
	}

	$result = $db->query('SELECT * FROM `' . TABLE_PANEL_NAVIGATION . '` WHERE `lang` = "menue;ftp;webftp"');
	$nums = $db->num_rows($result);

	if($nums == 0)
	{
		// in previous versions, menu entries have been deleted from the database when setting the
		// feature to enabled='off' so we need to add it again here!

		$db->query("INSERT INTO `" . TABLE_PANEL_NAVIGATION . "` SET `lang` = 'menue;ftp;webftp', `url`='" . $db->escape($settings['panel']['webftp_url']) . "', `area`='customer', `new_window`='1', `order` = '99', `parent_url` = 'customer_ftp.php'");
	}
	elseif($settings['panel']['webftp_url'] != '')
	{
		$db->query("UPDATE `" . TABLE_PANEL_NAVIGATION . "` SET `url`='" . $db->escape($settings['panel']['webftp_url']) . "' WHERE `lang` = 'menue;ftp;webftp' AND `area`='customer' AND `parent_url` = 'customer_ftp.php'");
	}

	// webmail

	if(!isset($settings['panel']['webmail_url']))
	{
		$settings['panel']['webmail_url'] = '';
	}

	$result = $db->query('SELECT * FROM `' . TABLE_PANEL_NAVIGATION . '` WHERE `lang` = "menue;email;webmail"');
	$nums = $db->num_rows($result);

	if($nums == 0)
	{
		// in previous versions, menu entries have been deleted from the database when setting the
		// feature to enabled='off' so we need to add it again here!

		$db->query("INSERT INTO `" . TABLE_PANEL_NAVIGATION . "` SET `lang` = 'menue;email;webmail', `url`='" . $db->escape($settings['panel']['webmail_url']) . "', `area`='customer', `new_window`='1', `required_resources` = 'emails_used', `order` = '99', `parent_url` = 'customer_email.php'");
	}
	elseif($settings['panel']['webmail_url'] != '')
	{
		$db->query("UPDATE `" . TABLE_PANEL_NAVIGATION . "` SET `url`='" . $db->escape($settings['panel']['webmail_url']) . "' WHERE `lang` = 'menue;email;webmail' AND `area`='customer' AND `parent_url` = 'customer_email.php'");
	}

	// set new version

	$query = 'UPDATE `%s` SET `value` = \'' . $updateto . '\' WHERE `settinggroup` = \'panel\' AND `varname` = \'version\'';
	$query = sprintf($query, TABLE_PANEL_SETTINGS);
	$db->query($query);
	$settings['panel']['version'] = $updateto;
}

if($settings['panel']['version'] == '1.4.1-svn2')
{
	$updateto = '1.4.1-svn3';
	$updatelog->logAction(ADM_ACTION, LOG_WARNING, "Updating from " . $settings['panel']['version'] . " to " . $updateto);
	
	//fixing menuentry for aps, refs #1088
	$db->query("UPDATE `" . TABLE_PANEL_NAVIGATION . "` SET `required_resources` = 'aps.aps_active' WHERE `area` = 'admin' AND `lang` = 'admin;aps' AND `url` = 'admin_aps.nourl'");

	// set new version

	$query = 'UPDATE `%s` SET `value` = \'' . $updateto . '\' WHERE `settinggroup` = \'panel\' AND `varname` = \'version\'';
	$query = sprintf($query, TABLE_PANEL_SETTINGS);
	$db->query($query);
	$settings['panel']['version'] = $updateto;
}

?>
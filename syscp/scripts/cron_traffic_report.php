<?php

/**
 * This file is part of the SysCP project.
 * Copyright (c) 2003-2007 the SysCP Team (see authors).
 *
 * For the full copyright and license information, please view the COPYING
 * file that was distributed with this source code. You can also view the
 * COPYING file online at http://files.syscp.org/misc/COPYING.txt
 *
 * @copyright  (c) the authors
 * @author     Florian Aders <eleras@syscp.org>
 * @license    GPLv2 http://files.syscp.org/misc/COPYING.txt
 * @package    System
 * @version    $Id$
 */

/**
 * STARTING REDUNDANT CODE, WHICH IS SOME KINDA HEADER FOR EVERY CRON SCRIPT.
 * When using this "header" you have to change $lockFilename for your needs.
 * Don't forget to also copy the footer which closes database connections
 * and the lockfile!
 */

include (dirname(__FILE__) . '/../lib/cron_init.php');

/**
 * END REDUNDANT CODE (CRONSCRIPT "HEADER")
 */

fwrite($debugHandler, 'Trafficreport run started...' . "\n");
$yesterday = time()-(60*60*24);

// Warn the customers at 90% traffic-usage

$result = $db->query("SELECT `c`.`customerid`, `c`.`adminid`, `c`.`name`, `c`.`firstname`, `c`.`traffic_used`, `c`.`traffic`, `c`.`email`, `a`.`name` AS `adminname`, `a`.`email` AS `adminmail` FROM `" . TABLE_PANEL_CUSTOMERS . "` AS `c` LEFT JOIN `panel_admins` AS `a` ON `a`.`adminid` = `c`.`adminid` WHERE `c`.`reportsent` = '0';");

while($row = $db->fetch_array($result))
{
	if(isset($row['traffic']) && $row['traffic'] > 0 && (($row['traffic_used']*100)/$row['traffic']) >= 90)
	{
		$replace_arr = array(
			'NAME' => $ow['name'],
			'TRAFFICMAX' => $row['traffic'],
			'TRAFFICUSED' => $row['traffic_used']
		);

		// Get mail templates from database; the ones from 'admin' are fetched for fallback

		$result = $db->query_first('SELECT `value` FROM `' . TABLE_PANEL_TEMPLATES . '` WHERE `adminid`=\'' . (int)$row['adminid'] . '\' AND `language`=\'' . $db->escape($row['def_language']) . '\' AND `templategroup`=\'traffic\' AND `varname`=\'trafficninetypercent_subject\'');
		$mail_subject = html_entity_decode(replace_variables((($result['value'] != '') ? $result['value'] : $lng['mails']['trafficninetypercent']['subject']), $replace_arr));
		$result = $db->query_first('SELECT `value` FROM `' . TABLE_PANEL_TEMPLATES . '` WHERE `adminid`=\'' . (int)$row['adminid'] . '\' AND `language`=\'' . $db->escape($row['def_language']) . '\' AND `templategroup`=\'traffic\' AND `varname`=\'trafficninetypercent_mailbody\'');
		$mail_body = html_entity_decode(replace_variables((($result['value'] != '') ? $result['value'] : $lng['mails']['trafficninetypercent']['mailbody']), $replace_arr));
		mail(buildValidMailFrom($row['firstname'] . ' ' . $row['name'], $row['email']), $mail_subject, $mail_body, 'From: ' . buildValidMailFrom($row['adminname'], $row['adminemail']));
		$db->query('UPDATE `' . TABLE_PANEL_CUSTOMERS . '` SET `reportsent`=\'1\' WHERE `customerid`=\'' . (int)$row['customerid'] . '\'');
	}
}

// Warn the admins at 90% traffic-usage

$result = $db->query("SELECT * FROM `" . TABLE_PANEL_ADMINS . "`;");

while($row = $db->fetch_array($result))
{
	if(isset($row['traffic']) && $row['traffic'] > 0 && (($row['traffic_used']*100)/$row['traffic']) >= 90)
	{
		$replace_arr = array(
			'NAME' => $row['name'],
			'TRAFFICMAX' => $row['traffic'],
			'TRAFFICUSED' => $row['traffic_used']
		);

		// Get mail templates from database; the ones from 'admin' are fetched for fallback

		$result = $db->query_first('SELECT `value` FROM `' . TABLE_PANEL_TEMPLATES . '` WHERE `adminid`=\'' . (int)$row['adminid'] . '\' AND `language`=\'' . $db->escape($row['def_language']) . '\' AND `templategroup`=\'traffic\' AND `varname`=\'trafficninetypercent_subject\'');
		$mail_subject = html_entity_decode(replace_variables((($result['value'] != '') ? $result['value'] : $lng['mails']['trafficninetypercent']['subject']), $replace_arr));
		$result = $db->query_first('SELECT `value` FROM `' . TABLE_PANEL_TEMPLATES . '` WHERE `adminid`=\'' . (int)$row['adminid'] . '\' AND `language`=\'' . $db->escape($row['def_language']) . '\' AND `templategroup`=\'traffic\' AND `varname`=\'trafficninetypercent_mailbody\'');
		$mail_body = html_entity_decode(replace_variables((($result['value'] != '') ? $result['value'] : $lng['mails']['trafficninetypercent']['mailbody']), $replace_arr));
		mail(buildValidMailFrom($row['name'], $row['email']), $mail_subject, $mail_body, 'From: ' . buildValidMailFrom($row['name'], $row['email']));
		$db->query('UPDATE `' . TABLE_PANEL_ADMINS . '` SET `reportsent`=\'1\' WHERE `customerid`=\'' . (int)$row['adminid'] . '\'');
	}

	// Another month, let's build our report

	if(date('d') == '01')
	{
		$mail_subject = 'Trafficreport ' . date("m/y", $yesterday) . ' for ' . $row['name'];
		$mail_body = 'Trafficreport ' . date("m/y", $yesterday) . ' for ' . $row['name'] . "\n";
		$mail_body.= '---------------------------------------------' . "\n";
		$mail_body.= 'Loginname       Traffic used (Percent) | Traffic available' . "\n";
		$customers = $db->query("SELECT * FROM `" . TABLE_PANEL_CUSTOMERS . "` WHERE `adminid` = " . $row['adminid'] . ";");

		while($customer = $db->fetch_array($customers))
		{
			$mail_body.= sprintf('%-15s', $customer['loginname']) . ' ' . sprintf('%-12d', $customer['traffic_used']) . ' (' . sprintf('%00.3f%%', (($customer['traffic_used']*100)/$customer['traffic'])) . ')   ' . $customer['traffic'] . "\n";
		}

		$mail_body.= '---------------------------------------------' . "\n";
		$mail_body.= sprintf('%-15s', $row['loginname']) . ' ' . sprintf('%-12d', $row['traffic_used']) . ' (' . sprintf('%00.3f%%', (($row['traffic_used']*100)/$row['traffic'])) . ')   ' . $row['traffic'] . "\n";
		mail(buildValidMailFrom($row['name'], $row['email']), $mail_subject, $mail_body, 'From: ' . buildValidMailFrom($row['name'], $row['email']));
	}
}

// Another month, reset the reportstatus

if(date('d') == '01')
{
	$db->query('UPDATE `' . TABLE_PANEL_CUSTOMERS . '` SET `reportsent` = \'0\';');
	$db->query('UPDATE `' . TABLE_PANEL_ADMINS . '` SET `reportsent` = \'0\';');
}

$db->query('UPDATE `' . TABLE_PANEL_SETTINGS . '` SET `value` = UNIX_TIMESTAMP() WHERE `settinggroup` = \'system\'   AND `varname`      = \'last_traffic_report_run\' ');

/**
 * STARTING CRONSCRIPT FOOTER
 */

include ($pathtophpfiles . '/lib/cron_shutdown.php');

/**
 * END CRONSCRIPT FOOTER
 */

?>
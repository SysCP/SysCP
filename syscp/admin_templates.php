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
 * @author     Michael Duergner <michael@duergner.com>
 * @author     Florian Lippert <flo@syscp.org>
 * @license    GPLv2 http://files.syscp.org/misc/COPYING.txt
 * @package    Panel
 * @version    $Id$
 */

define('AREA', 'admin');

/**
 * Include our init.php, which manages Sessions, Language etc.
 */

require ("./lib/init.php");

if($page == 'email')
{
	if(isset($_POST['subjectid']))
	{
		$subjectid = intval($_POST['subjectid']);
		$mailbodyid = intval($_POST['mailbodyid']);
	}
	elseif(isset($_GET['subjectid']))
	{
		$subjectid = intval($_GET['subjectid']);
		$mailbodyid = intval($_GET['mailbodyid']);
	}

	if($action == '')
	{
		$available_templates = array(
			'createcustomer',
			'pop_success',
			'trafficninetypercent',
			'new_ticket_by_customer',
			'new_ticket_for_customer',
			'new_ticket_by_staff',
			'new_reply_ticket_by_customer',
			'new_reply_ticket_by_staff'
		);

		if($settings['panel']['sendalternativemail'] == 1)
		{
			$available_templates[] = 'pop_success_alternative';
		}

		$templates_array = array();
		$result = $db->query("SELECT `id`, `language`, `varname` FROM `" . TABLE_PANEL_TEMPLATES . "` WHERE `adminid`='" . (int)$userinfo['adminid'] . "' AND `templategroup`='mails' ORDER BY `language`, `varname`");

		while($row = $db->fetch_array($result))
		{
			$parts = array();
			preg_match('/^([a-z]([a-z_]+[a-z])*)_(mailbody|subject)$/', $row['varname'], $parts);
			$templates_array[$row['language']][$parts[1]][$parts[3]] = $row['id'];
		}

		$templates = '';
		foreach($templates_array as $language => $template_defs)
		{
			foreach($template_defs as $action => $email)
			{
				$subjectid = $email['subject'];
				$mailbodyid = $email['mailbody'];
				$template = $lng['admin']['templates'][$action];
				eval("\$templates.=\"" . getTemplate("templates/templates_template") . "\";");
			}
		}

		$add = false;

		while(list($language_file, $language_name) = each($languages))
		{
			$templates_done = array();
			$result = $db->query('SELECT `varname` FROM `' . TABLE_PANEL_TEMPLATES . '` WHERE `adminid`=\'' . (int)$userinfo['adminid'] . '\' AND `language`=\'' . $db->escape($language_name) . '\' AND `templategroup`=\'mails\' AND `varname` LIKE \'%_subject\'');

			while(($row = $db->fetch_array($result)) != false)
			{
				$templates_done[] = str_replace('_subject', '', $row['varname']);
			}

			if(count(array_diff($available_templates, $templates_done)) > 0)
			{
				$add = true;
			}
		}

		eval("echo \"" . getTemplate("templates/templates") . "\";");
	}
	elseif($action == 'delete'
	       && $subjectid != 0
	       && $mailbodyid != 0)
	{
		$result = $db->query_first("SELECT `language`, `varname` FROM `" . TABLE_PANEL_TEMPLATES . "` WHERE `adminid`='" . (int)$userinfo['adminid'] . "' AND `id`='" . (int)$subjectid . "'");

		if($result['varname'] != '')
		{
			if(isset($_POST['send'])
			   && $_POST['send'] == 'send')
			{
				$db->query("DELETE FROM `" . TABLE_PANEL_TEMPLATES . "` WHERE `adminid`='" . (int)$userinfo['adminid'] . "' AND (`id`='" . (int)$subjectid . "' OR `id`='" . (int)$mailbodyid . "')");
				$log->logAction(ADM_ACTION, LOG_INFO, "deleted template '" . $result['language'] . ' - ' . $lng['admin']['templates'][str_replace('_subject', '', $result['varname'])] . "'");
				redirectTo($filename, Array(
					'page' => $page,
					's' => $s
				));
			}
			else
			{
				ask_yesno('admin_template_reallydelete', $filename, array(
					'subjectid' => $subjectid,
					'mailbodyid' => $mailbodyid,
					'page' => $page,
					'action' => $action
				), $result['language'] . ' - ' . $lng['admin']['templates'][str_replace('_subject', '', $result['varname'])]);
			}
		}
	}
	elseif($action == 'add')
	{
		$available_templates = array(
			'createcustomer',
			'pop_success',
			'trafficninetypercent',
			'new_ticket_by_customer',
			'new_ticket_for_customer',
			'new_ticket_by_staff',
			'new_reply_ticket_by_customer',
			'new_reply_ticket_by_staff'
		);

		if($settings['panel']['sendalternativemail'] == 1)
		{
			$available_templates[] = 'pop_success_alternative';
		}

		if(isset($_POST['prepare'])
		   && $_POST['prepare'] == 'prepare')
		{
			$language = validate($_POST['language'], 'language');
			$templates = array();
			$result = $db->query('SELECT `varname` FROM `' . TABLE_PANEL_TEMPLATES . '` WHERE `adminid`=\'' . (int)$userinfo['adminid'] . '\' AND `language`=\'' . $db->escape($language) . '\' AND `templategroup`=\'mails\' AND `varname` LIKE \'%_subject\'');

			while(($row = $db->fetch_array($result)) != false)
			{
				$templates[] = str_replace('_subject', '', $row['varname']);
			}

			$templates = array_diff($available_templates, $templates);
			$template_options = '';
			foreach($templates as $template)
			{
				$template_options.= makeoption($lng['admin']['templates'][$template], $template, NULL, true);
			}

			eval("echo \"" . getTemplate("templates/templates_add_2") . "\";");
		}
		elseif(isset($_POST['send'])
		       && $_POST['send'] == 'send')
		{
			$language = validate($_POST['language'], 'language', '/^[^\r\n\0"\']+$/', 'nolanguageselect');
			$template = validate($_POST['template'], 'template');
			$subject = validate($_POST['subject'], 'subject', '/^[^\r\n\0]+$/', 'nosubjectcreate');
			$mailbody = validate($_POST['mailbody'], 'mailbody', '/^[^\0]+$/', 'nomailbodycreate');
			$templates = array();
			$result = $db->query('SELECT `varname` FROM `' . TABLE_PANEL_TEMPLATES . '` WHERE `adminid`=\'' . (int)$userinfo['adminid'] . '\' AND `language`=\'' . $db->escape($language) . '\' AND `templategroup`=\'mails\' AND `varname` LIKE \'%_subject\'');

			while(($row = $db->fetch_array($result)) != false)
			{
				$templates[] = str_replace('_subject', '', $row['varname']);
			}

			$templates = array_diff($available_templates, $templates);

			if(array_search($template, $templates) === false)
			{
				standard_error('templatenotfound');
			}
			else
			{
				$result = $db->query("INSERT INTO `" . TABLE_PANEL_TEMPLATES . "` (`adminid`, `language`, `templategroup`, `varname`, `value`)
					                   VALUES ('" . (int)$userinfo['adminid'] . "', '" . $db->escape($language) . "', 'mails', '" . $db->escape($template) . "_subject','" . $db->escape($subject) . "')");
				$result = $db->query("INSERT INTO `" . TABLE_PANEL_TEMPLATES . "` (`adminid`, `language`, `templategroup`, `varname`, `value`)
					                   VALUES ('" . (int)$userinfo['adminid'] . "', '" . $db->escape($language) . "', 'mails', '" . $db->escape($template) . "_mailbody','" . $db->escape($mailbody) . "')");
				$log->logAction(ADM_ACTION, LOG_INFO, "added template '" . $language . ' - ' . $template . "'");
				redirectTo($filename, Array(
					'page' => $page,
					's' => $s
				));
			}
		}
		else
		{
			$add = false;
			$language_options = '';

			while(list($language_file, $language_name) = each($languages))
			{
				$templates = array();
				$result = $db->query('SELECT `varname` FROM `' . TABLE_PANEL_TEMPLATES . '` WHERE `adminid`=\'' . (int)$userinfo['adminid'] . '\' AND `language`=\'' . $db->escape($language_name) . '\' AND `templategroup`=\'mails\' AND `varname` LIKE \'%_subject\'');

				while(($row = $db->fetch_array($result)) != false)
				{
					$templates[] = str_replace('_subject', '', $row['varname']);
				}

				if(count(array_diff($available_templates, $templates)) > 0)
				{
					$add = true;
					$language_options.= makeoption($language_name, $language_file, $userinfo['language'], true);
				}
			}

			if($add)
			{
				eval("echo \"" . getTemplate("templates/templates_add_1") . "\";");
			}
			else
			{
				standard_error('alltemplatesdefined');
				exit;
			}
		}
	}
	elseif($action == 'edit')
	{
		$result = $db->query_first("SELECT `language`, `varname`, `value` FROM `" . TABLE_PANEL_TEMPLATES . "` WHERE `adminid`='" . (int)$userinfo['adminid'] . "' AND `id`='" . (int)$subjectid . "'");

		if($result['varname'] != '')
		{
			if(isset($_POST['send'])
			   && $_POST['send'] == 'send')
			{
				$subject = validate($_POST['subject'], 'subject', '/^[^\r\n\0]+$/', 'nosubjectcreate');
				$mailbody = validate($_POST['mailbody'], 'mailbody', '/^[^\0]+$/', 'nomailbodycreate');
				$db->query("UPDATE `" . TABLE_PANEL_TEMPLATES . "` SET `value`='" . $db->escape($subject) . "' WHERE `adminid`='" . (int)$userinfo['adminid'] . "' AND `id`='" . (int)$subjectid . "'");
				$db->query("UPDATE `" . TABLE_PANEL_TEMPLATES . "` SET `value`='" . $db->escape($mailbody) . "' WHERE `adminid`='" . (int)$userinfo['adminid'] . "' AND `id`='" . (int)$mailbodyid . "'");
				$log->logAction(ADM_ACTION, LOG_INFO, "edited template '" . $result['varname'] . "'");
				redirectTo($filename, Array(
					'page' => $page,
					's' => $s
				));
			}
			else
			{
				$result = htmlentities_array($result);
				$template = $lng['admin']['templates'][str_replace('_subject', '', $result['varname'])];
				$subject = $result['value'];
				$result = $db->query_first("SELECT `language`, `varname`, `value` FROM `" . TABLE_PANEL_TEMPLATES . "` WHERE `id`='$mailbodyid'");
				$result = htmlentities_array($result);
				$mailbody = $result['value'];
				eval("echo \"" . getTemplate("templates/templates_edit") . "\";");
			}
		}
	}
}

?>

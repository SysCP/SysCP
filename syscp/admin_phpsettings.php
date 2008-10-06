<?php

/**
 * This file is part of the SysCP project.
 * Copyright (c) 2003-2008 the SysCP Team (see authors).
 *
 * For the full copyright and license information, please view the COPYING
 * file that was distributed with this source code. You can also view the
 * COPYING file online at http://files.syscp.org/misc/COPYING.txt
 *
 * @copyright	(c) the authors
 * @author		Sven Skrabal <info@nexpa.de>
 * @license		GPLv2 http://files.syscp.org/misc/COPYING.txt
 * @package		Panel
 * @version		$Id$
 * @todo
 */

define('AREA', 'admin');

/**
 * Include our init.php, which manages Sessions, Language etc.
 */

require ("./lib/init.php");

if(isset($_POST['id']))
{
	$id = intval($_POST['id']);
}
elseif(isset($_GET['id']))
{
	$id = intval($_GET['id']);
}

if($page == 'overview')
{
	if($action == 'edit')
	{
		$result = $db->query("SELECT * FROM `" . TABLE_PANEL_PHPCONFIGS . "` WHERE `id` = " . (int)$id);

		if($db->num_rows($result) > 0
		   && (int)$userinfo['caneditphpsettings'] == 1)
		{
			if(isset($_POST['phpsettings'])
			   && isset($_POST['description']))
			{
				$value = validate($_POST['description'], 'description');

				if(strlen($value) == 0
				   || strlen($value) > 50)
				{
					standard_error('descriptioninvalid');
				}

				$db->query("UPDATE `" . TABLE_PANEL_PHPCONFIGS . "` SET `description` = '" . $db->escape($value) . "', `phpsettings` = '" . $db->escape($_POST['phpsettings']) . "' WHERE `id` = " . (int)$id);
				inserttask('1');
				$log->logAction(ADM_ACTION, LOG_INFO, "php.ini setting with description '" . $value . "' has been changed by '" . $userinfo['loginname'] . "'");
				redirectTo($filename, Array(
					'page' => $page,
					's' => $s
				));
				exit;
			}
			else
			{
				$row = $db->fetch_array($result);
				eval("echo \"" . getTemplate("phpconfig/overview_edit") . "\";");
				exit;
			}
		}
		else
		{
			standard_error('nopermissionsorinvalidid');
			exit;
		}
	}

	if($action == 'delete')
	{
		if(isset($_POST['send'])
		   && $_POST['send'] == 'send')
		{
			$result = $db->query("SELECT * FROM `" . TABLE_PANEL_PHPCONFIGS . "` WHERE `id` = " . (int)$id);

			if($db->num_rows($result) > 0
			   && (int)$userinfo['caneditphpsettings'] == 1
			   && $id != 1)
			{
				$db->query("UPDATE `" . TABLE_PANEL_DOMAINS . "` SET `phpsettingid` = 1 WHERE `phpsettingid` = " . (int)$id);
				$db->query("DELETE FROM `" . TABLE_PANEL_PHPCONFIGS . "` WHERE `id` = " . (int)$id);
				inserttask('1');
				$log->logAction(ADM_ACTION, LOG_INFO, "php.ini setting with id #" . (int)$id . " has been deleted by '" . $userinfo['loginname'] . "'");
				redirectTo($filename, Array(
					'page' => $page,
					's' => $s
				));
				exit;
			}
			else
			{
				standard_error('nopermissionsorinvalidid');
				exit;
			}
		}
		else
		{
			ask_yesno('phpsetting_reallydelete', $filename, array(
				'id' => $id,
				'page' => $page,
				'action' => $action
			), $result['loginname']);
			exit;
		}
	}

	if($action == 'add')
	{
		if((int)$userinfo['caneditphpsettings'] == 1)
		{
			if(isset($_POST['phpsettings'])
			   && isset($_POST['description']))
			{
				$value = validate($_POST['description'], 'description');

				if(strlen($value) == 0
				   || strlen($value) > 50)
				{
					standard_error('descriptioninvalid');
				}

				$db->query("INSERT INTO `" . TABLE_PANEL_PHPCONFIGS . "` SET `description` = '" . $db->escape($value) . "', `phpsettings` = '" . $db->escape($_POST['phpsettings']) . "'");
				inserttask('1');
				$log->logAction(ADM_ACTION, LOG_INFO, "php.ini setting with description '" . $value . "' has been created by '" . $userinfo['loginname'] . "'");
				redirectTo($filename, Array(
					'page' => $page,
					's' => $s
				));
				exit;
			}
			else
			{
				$result = $db->query("SELECT * FROM `" . TABLE_PANEL_PHPCONFIGS . "` WHERE `id` = 1");
				$row = $db->fetch_array($result);
				eval("echo \"" . getTemplate("phpconfig/overview_add") . "\";");
				exit;
			}
		}
		else
		{
			standard_error('nopermissionsorinvalidid');
			exit;
		}
	}

	if($action == 'view')
	{
		$result = $db->query("SELECT * FROM `" . TABLE_PANEL_PHPCONFIGS . "` WHERE `id` = " . (int)$id);
		$row = $db->fetch_array($result);
		$log->logAction(ADM_ACTION, LOG_INFO, "php.ini setting with description '" . $row['description'] . "' has been viewed by '" . $userinfo['loginname'] . "'");
		eval("echo \"" . getTemplate("phpconfig/overview_view") . "\";");
		exit;
	}
	else
	{
		$tablecontent = '';
		$result = $db->query("SELECT * FROM `" . TABLE_PANEL_PHPCONFIGS . "`");

		while($row = $db->fetch_array($result))
		{
			$domainresult = false;

			if((int)$userinfo['domains_see_all'] == 0)
			{
				$domainresult = $db->query("SELECT * FROM `" . TABLE_PANEL_DOMAINS . "` WHERE `adminid` = " . (int)$userinfo['userid'] . " AND `phpsettingid` = " . (int)$row['id']);
			}
			else
			{
				$domainresult = $db->query("SELECT * FROM `" . TABLE_PANEL_DOMAINS . "` WHERE `phpsettingid` = " . (int)$row['id']);
			}

			$domains = '';

			if($db->num_rows($domainresult) > 0)
			{
				while($row2 = $db->fetch_array($domainresult))
				{
					$domains.= $row2['domain'] . '<br/>';
				}
			}
			else
			{
				$domains = $lng['admin']['phpsettings']['notused'];
			}

			eval("\$tablecontent.=\"" . getTemplate("phpconfig/overview_overview") . "\";");
		}

		$log->logAction(ADM_ACTION, LOG_INFO, "php.ini setting overview has been viewed by '" . $userinfo['loginname'] . "'");
		eval("echo \"" . getTemplate("phpconfig/overview") . "\";");
	}
}

?>
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
 * @author     Florian Lippert <flo@syscp.org>
 * @author     Michael Duergner <michael@duergner.com>
 * @author     Luca Longinotti <chtekk@syscp.org>
 * @license    GPLv2 http://files.syscp.org/misc/COPYING.txt
 * @package    Panel
 * @version    $Id$
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

if($page == 'ipsandports'
   || $page == 'overview')
{
	if($action == '')
	{
		$log->logAction(ADM_ACTION, LOG_NOTICE, "viewed admin_ipsandports");
		$fields = array(
			'ip' => $lng['admin']['ipsandports']['ip'],
			'port' => $lng['admin']['ipsandports']['port']
		);
		$paging = new paging($userinfo, $db, TABLE_PANEL_IPSANDPORTS, $fields, $settings['panel']['paging'], $settings['panel']['natsorting']);
		$ipsandports = '';
		$result = $db->query("SELECT `id`, `ip`, `port`, `listen_statement`, `namevirtualhost_statement`, `vhostcontainer`, `vhostcontainer_servername_statement`, `specialsettings` FROM `" . TABLE_PANEL_IPSANDPORTS . "` " . $paging->getSqlWhere(false) . " " . $paging->getSqlOrderBy() . " " . $paging->getSqlLimit());
		$paging->setEntries($db->num_rows($result));
		$sortcode = $paging->getHtmlSortCode($lng);
		$arrowcode = $paging->getHtmlArrowCode($filename . '?page=' . $page . '&s=' . $s);
		$searchcode = $paging->getHtmlSearchCode($lng);
		$pagingcode = $paging->getHtmlPagingCode($filename . '?page=' . $page . '&s=' . $s);
		$i = 0;
		$count = 0;

		while($row = $db->fetch_array($result))
		{
			if($paging->checkDisplay($i))
			{
				$row = htmlentities_array($row);
				eval("\$ipsandports.=\"" . getTemplate("ipsandports/ipsandports_ipandport") . "\";");
				$count++;
			}

			$i++;
		}

		eval("echo \"" . getTemplate("ipsandports/ipsandports") . "\";");
	}
	elseif($action == 'delete'
	       && $id != 0)
	{
		$result = $db->query_first("SELECT `id`, `ip`, `port` FROM `" . TABLE_PANEL_IPSANDPORTS . "` WHERE `id`='" . (int)$id . "'");

		if(isset($result['id'])
		   && $result['id'] == $id)
		{
			$result_checkdomain = $db->query_first("SELECT `id` FROM `" . TABLE_PANEL_DOMAINS . "` WHERE `ipandport`='" . (int)$id . "'");

			if($result_checkdomain['id'] == '')
			{
				if($result['id'] != $settings['system']['defaultip'])
				{
					$result_sameipotherport = $db->query_first("SELECT `id` FROM `" . TABLE_PANEL_IPSANDPORTS . "` WHERE `ip`='" . $db->escape($result['ip']) . "' AND `id`!='" . (int)$id . "'");

					if(($result['ip'] != $settings['system']['ipaddress'])
					   || ($result['ip'] == $settings['system']['ipaddress'] && $result_sameipotherport['id'] != ''))
					{
						$result = $db->query_first("SELECT `ip`, `port` FROM `" . TABLE_PANEL_IPSANDPORTS . "` WHERE `id`='" . (int)$id . "'");

						if($result['ip'] != '')
						{
							if(isset($_POST['send'])
							   && $_POST['send'] == 'send')
							{
								$db->query("DELETE FROM `" . TABLE_PANEL_IPSANDPORTS . "` WHERE `id`='" . (int)$id . "'");
								$log->logAction(ADM_ACTION, LOG_WARNING, "deleted IP/port '" . $result['ip'] . ":" . $result['port'] . "'");
								inserttask('1');
								inserttask('4');
								redirectTo($filename, Array(
									'page' => $page,
									's' => $s
								));
							}
							else
							{
								ask_yesno('admin_ip_reallydelete', $filename, array(
									'id' => $id,
									'page' => $page,
									'action' => $action
								), $result['ip'] . ':' . $result['port']);
							}
						}
					}
					else
					{
						standard_error('cantdeletesystemip');
					}
				}
				else
				{
					standard_error('cantdeletedefaultip');
				}
			}
			else
			{
				standard_error('ipstillhasdomains');
			}
		}
	}
	elseif($action == 'add')
	{
		if(isset($_POST['send'])
		   && $_POST['send'] == 'send')
		{
			$ip = validate_ip($_POST['ip']);
			$port = validate($_POST['port'], 'port', '/^(([1-9])|([1-9][0-9])|([1-9][0-9][0-9])|([1-9][0-9][0-9][0-9])|([1-5][0-9][0-9][0-9][0-9])|(6[0-4][0-9][0-9][0-9])|(65[0-4][0-9][0-9])|(655[0-2][0-9])|(6553[0-5]))$/Di', array(
				'stringisempty',
				'myport'
			));
			$listen_statement = intval($_POST['listen_statement']);
			$namevirtualhost_statement = intval($_POST['namevirtualhost_statement']);
			$vhostcontainer = intval($_POST['vhostcontainer']);
			$specialsettings = validate(str_replace("\r\n", "\n", $_POST['specialsettings']), 'specialsettings', '/^[^\0]*$/');
			$vhostcontainer_servername_statement = intval($_POST['vhostcontainer_servername_statement']);

			if($listen_statement != '1')
			{
				$listen_statement = '0';
			}

			if($namevirtualhost_statement != '1')
			{
				$namevirtualhost_statement = '0';
			}

			if($vhostcontainer != '1')
			{
				$vhostcontainer = '0';
			}

			if($vhostcontainer_servername_statement != '1')
			{
				$vhostcontainer_servername_statement = '0';
			}

			$result_checkfordouble = $db->query_first("SELECT `id` FROM `" . TABLE_PANEL_IPSANDPORTS . "` WHERE `ip`='" . $db->escape($ip) . "' AND `port`='" . (int)$port . "'");

			if($result_checkfordouble['id'] != '')
			{
				standard_error('myipnotdouble');
			}
			else
			{
				$db->query("INSERT INTO `" . TABLE_PANEL_IPSANDPORTS . "` (`ip`, `port`, `listen_statement`, `namevirtualhost_statement`, `vhostcontainer`, `vhostcontainer_servername_statement`, `specialsettings`, `ssl`, `ssl_cert`) VALUES ('" . $db->escape($ip) . "', '" . (int)$port . "', '" . (int)$vhostcontainer . "', '" . (int)$namevirtualhost_statement . "', '" . (int)$vhostcontainer . "', '" . (int)$vhostcontainer_servername_statement . "', '" . $db->escape($specialsettings) . "', '" . $_POST['ssl'] . "', '" . $_POST['ssl_cert_file'] . "')");
				$log->logAction(ADM_ACTION, LOG_WARNING, "added IP/port '" . $ip . ":" . $port . "'");
				inserttask('1');
				inserttask('4');
				redirectTo($filename, Array(
					'page' => $page,
					's' => $s
				));
			}
		}
		else
		{
			if(!empty($result['ssl_cert']))
			{
				$ssl_cert_file = $result['ssl_cert'];
			}
			else
			{
				$ssl_cert_file = $settings['system']['ssl_cert_file'];
			}

			$enable_ssl = makeyesno('ssl', '1', '0', '0');
			$listen_statement = makeyesno('listen_statement', '1', '0', '1');
			$namevirtualhost_statement = makeyesno('namevirtualhost_statement', '1', '0', '1');
			$vhostcontainer = makeyesno('vhostcontainer', '1', '0', '1');
			$vhostcontainer_servername_statement = makeyesno('vhostcontainer_servername_statement', '1', '0', '1');
			$ssl_cert_file = $settings['system']['ssl_cert_file'];
			eval("echo \"" . getTemplate("ipsandports/ipsandports_add") . "\";");
		}
	}
	elseif($action == 'edit'
	       && $id != 0)
	{
		$result = $db->query_first("SELECT `id`, `ip`, `port`, `listen_statement`, `namevirtualhost_statement`, `vhostcontainer`, `vhostcontainer_servername_statement`, `specialsettings`, `ssl`, `ssl_cert` FROM `" . TABLE_PANEL_IPSANDPORTS . "` WHERE `id`='" . (int)$id . "'");

		if($result['ip'] != '')
		{
			if(isset($_POST['send'])
			   && $_POST['send'] == 'send')
			{
				$ip = validate_ip($_POST['ip']);
				$port = validate($_POST['port'], 'port', '/^(([1-9])|([1-9][0-9])|([1-9][0-9][0-9])|([1-9][0-9][0-9][0-9])|([1-5][0-9][0-9][0-9][0-9])|(6[0-4][0-9][0-9][0-9])|(65[0-4][0-9][0-9])|(655[0-2][0-9])|(6553[0-5]))$/Di', array(
					'stringisempty',
					'myport'
				));
				$result_checkfordouble = $db->query_first("SELECT `id` FROM `" . TABLE_PANEL_IPSANDPORTS . "` WHERE `ip`='" . $db->escape($ip) . "' AND `port`='" . (int)$port . "'");
				$result_sameipotherport = $db->query_first("SELECT `id` FROM `" . TABLE_PANEL_IPSANDPORTS . "` WHERE `ip`='" . $db->escape($result['ip']) . "' AND `id`!='" . (int)$id . "'");
				$listen_statement = intval($_POST['listen_statement']);
				$namevirtualhost_statement = intval($_POST['namevirtualhost_statement']);
				$vhostcontainer = intval($_POST['vhostcontainer']);
				$specialsettings = validate(str_replace("\r\n", "\n", $_POST['specialsettings']), 'specialsettings', '/^[^\0]*$/');
				$vhostcontainer_servername_statement = intval($_POST['vhostcontainer_servername_statement']);

				if($listen_statement != '1')
				{
					$listen_statement = '0';
				}

				if($namevirtualhost_statement != '1')
				{
					$namevirtualhost_statement = '0';
				}

				if($vhostcontainer != '1')
				{
					$vhostcontainer = '0';
				}

				if($vhostcontainer_servername_statement != '1')
				{
					$vhostcontainer_servername_statement = '0';
				}

				if($result['ip'] != $ip
				   && $result['ip'] == $settings['system']['ipaddress']
				   && $result_sameipotherport['id'] == '')
				{
					standard_error('cantchangesystemip');
				}
				elseif($result_checkfordouble['id'] != ''
				       && $result_checkfordouble['id'] != $id)
				{
					standard_error('myipnotdouble');
				}
				else
				{
					$db->query("UPDATE `" . TABLE_PANEL_IPSANDPORTS . "` SET `ip`='" . $db->escape($ip) . "', `port`='" . (int)$port . "', `listen_statement`='" . (int)$listen_statement . "', `namevirtualhost_statement`='" . (int)$namevirtualhost_statement . "', `vhostcontainer`='" . (int)$vhostcontainer . "', `vhostcontainer_servername_statement`='" . (int)$vhostcontainer_servername_statement . "', `specialsettings`='" . $db->escape($specialsettings) . "', `ssl`='" . $_POST['ssl'] . "', `ssl_cert`='" . $_POST['ssl_cert_file'] . "' WHERE `id`='" . (int)$id . "'");
					$log->logAction(ADM_ACTION, LOG_WARNING, "changed IP/port from '" . $result['ip'] . ":" . $result['port'] . "' to '" . $ip . ":" . $port . "'");
					inserttask('1');
					inserttask('4');
					redirectTo($filename, Array(
						'page' => $page,
						's' => $s
					));
				}
			}
			else
			{
				$enable_ssl = makeyesno('ssl', '1', '0', $result['ssl']);
				$result = htmlentities_array($result);
				$listen_statement = makeyesno('listen_statement', '1', '0', $result['listen_statement']);
				$namevirtualhost_statement = makeyesno('namevirtualhost_statement', '1', '0', $result['namevirtualhost_statement']);
				$vhostcontainer = makeyesno('vhostcontainer', '1', '0', $result['vhostcontainer']);
				$vhostcontainer_servername_statement = makeyesno('vhostcontainer_servername_statement', '1', '0', $result['vhostcontainer_servername_statement']);

				if(!empty($result['ssl_cert']))
				{
					$ssl_cert_file = $result['ssl_cert'];
				}
				else
				{
					$ssl_cert_file = $settings['system']['ssl_cert_file'];
				}

				eval("echo \"" . getTemplate("ipsandports/ipsandports_edit") . "\";");
			}
		}
	}
}

?>

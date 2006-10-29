<?php

/**
 * This file is part of the SysCP project.
 * Copyright (c) 2003-2006 the SysCP Project.
 *
 * For the full copyright and license information, please view the COPYING
 * file that was distributed with this source code. You can also view the
 * COPYING file online at http://files.syscp.org/misc/COPYING.txt
 *
 * @author     Florian Lippert <flo@redenswert.de>
 * @copyright  (c) 2003-2006 Florian Lippert
 * @package    Syscp.Modules
 * @subpackage Index
 * @license    GPLv2 http://files.syscp.org/misc/COPYING.txt
 * @version    $Id:admin_index.php 460 2006-04-23 15:07:49 +0200 (So, 23 Apr 2006) martin $
 */

$overview = $this->DatabaseHandler->queryFirst("SELECT COUNT(*) AS `number_customers`,
				SUM(`diskspace_used`) AS `diskspace_used`,
				SUM(`mysqls_used`) AS `mysqls_used`,
				SUM(`emails_used`) AS `emails_used`,
				SUM(`email_accounts_used`) AS `email_accounts_used`,
				SUM(`email_forwarders_used`) AS `email_forwarders_used`,
				SUM(`ftps_used`) AS `ftps_used`,
				SUM(`subdomains_used`) AS `subdomains_used`,
				SUM(`traffic_used`) AS `traffic_used`
				FROM `".TABLE_PANEL_CUSTOMERS."`".($this->User['customers_see_all'] ? '' : " WHERE `adminid` = '{$this->User['adminid']}' "));
$overview['traffic_used'] = round($overview['traffic_used']/(1024*1024*1024), 4);
$overview['diskspace_used'] = round($overview['diskspace_used']/(1024*1024), 2);
$number_domains = $this->DatabaseHandler->queryFirst("SELECT COUNT(*) AS `number_domains` FROM `".TABLE_PANEL_DOMAINS."` WHERE `parentdomainid`='0'".($this->User['customers_see_all'] ? '' : " AND `adminid` = '{$this->User['adminid']}' "));
$overview['number_domains'] = $number_domains['number_domains'];
$this->User['diskspace'] = round($this->User['diskspace']/1024, 4);
$this->User['diskspace_used'] = round($this->User['diskspace_used']/1024, 4);
$this->User['traffic'] = round($this->User['traffic']/(1024*1024), 4);
$this->User['traffic_used'] = round($this->User['traffic_used']/(1024*1024), 4);
$this->User = str_replace_array('-1', $this->L10nHandler->get('SysCP.globallang.unlimited'), $this->User, 'customers domains diskspace traffic mysqls emails email_accounts email_forwarders ftps subdomains');
$cronlastrun = date("d.m.Y H:i:s", $this->ConfigHandler->get('system.lastcronrun'));

// we need to make the memory check this huge, because if php is compiled with
// --disable-memorylimit we get a blank string back from ini_get

$memorylimit = ini_get('memory_limit');

if($memorylimit == '')
{
    $memorylimit = $this->L10nHandler->get('SysCP.index.disabled');
}

$this->TemplateHandler->set('overview', $overview);
$this->TemplateHandler->set('serversoftware', $_SERVER['SERVER_SOFTWARE']);
$this->TemplateHandler->set('phpversion', phpversion());
$this->TemplateHandler->set('phpmemorylimit', $memorylimit);
$this->TemplateHandler->set('mysqlserverversion', mysql_get_server_info());
$this->TemplateHandler->set('mysqlclientversion', mysql_get_client_info());
$this->TemplateHandler->set('webserverinterface', strtoupper(@php_sapi_name()));
$this->TemplateHandler->set('cronlastrun', $cronlastrun);
$this->TemplateHandler->setTemplate('SysCP/index/admin/index.tpl');

<?php

/**
 * This file is part of the SysCP project.
 * Copyright (c) 2003-2006 the SysCP Project.
 *
 * For the full copyright and license information, please view the COPYING
 * file that was distributed with this source code. You can also view the
 * COPYING file online at http://files.syscp.org/misc/COPYING.txt
 *
 * @author     The SysCP Team <team@syscp.org>
 * @copyright  (c) 2006 The SysCP Team
 * @package    Syscp.Translation
 * @license    GPLv2 http://files.syscp.org/misc/COPYING.txt
 *
 */

/**
 * Normal strings
 */

$lng['SysCP']['ipsandports']['add'] = 'Add IP/Port';
$lng['SysCP']['ipsandports']['default'] = 'Default reseller IP/Port';
$lng['SysCP']['ipsandports']['edit'] = 'Edit IP/Port';
$lng['SysCP']['ipsandports']['ip'] = 'IP';
$lng['SysCP']['ipsandports']['ipandport'] = 'IP/Port';
$lng['SysCP']['ipsandports']['ipsandports'] = 'IPs and Ports';
$lng['SysCP']['ipsandports']['port'] = 'Port';
$lng['SysCP']['ipsandports']['server'] = 'Server';

/**
 * Errors & Questions
 */

$lng['SysCP']['ipsandports']['error']['change_systemip'] = 'You cannot change the last system IP, either create another new IP/Port combination for the system IP or change the system IP.';
$lng['SysCP']['ipsandports']['error']['delete_defaultip'] = 'You cannot delete the default reseller IP/Port combination, please make another IP/Port combination default for resellers before deleting this IP/Port combination.';
$lng['SysCP']['ipsandports']['error']['delete_systemip'] = 'You cannot delete the last system IP, either create a new IP/Port combination for the system IP or change the system IP.';
$lng['SysCP']['ipsandports']['error']['ip_duplicate'] = 'This IP/Port combination already exists.';
$lng['SysCP']['ipsandports']['error']['ip_has_domains'] = 'The IP/Port combination you want to delete still has domains assigned to it, please reassign those to other IP/Port combinations before deleting this IP/Port combination.';
$lng['SysCP']['ipsandports']['error']['select_defaultip'] = 'You need to select an IP/Port combination that should become default.';
$lng['SysCP']['ipsandports']['question']['delete'] = 'Do you really want to delete the IP address %s?';

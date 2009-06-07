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
 * @package    Panel
 * @version    $Id$
 */

return array(
	'groups' => array(
		'billing' => array(
			'title' => $lng['admin']['billingsettings'],
			'fields' => array(
				'billing_enable' => array(
					'label' => $lng['serversettings']['billing']['activate_billing'],
					'settinggroup' => 'billing',
					'varname' => 'activate_billing',
					'type' => 'bool',
					'default' => false,
					'save_method' => 'storeSettingField',
					),
				'billing_highlight_inactive' => array(
					'label' => $lng['serversettings']['billing']['highlight_inactive'],
					'settinggroup' => 'billing',
					'varname' => 'highlight_inactive',
					'type' => 'bool',
					'default' => false,
					'save_method' => 'storeSettingField',
					),
				'billing_' => array(
					'label' => $lng['serversettings']['billing']['invoicenumber_count'],
					'settinggroup' => 'billing',
					'varname' => 'invoicenumber_count',
					'type' => 'int',
					'default' => 0,
					'save_method' => 'storeSettingField',
					),
				),
			),
		),
	);

?>
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

return array (
	'admin' => array (
		array (
			'label' => $lng['billing']['billing'],
			'required_resources' => 'edit_billingdata',
			'show_element' => ( $settings['billing']['activate_billing'] == true ),
			'elements' => array (
				array (
					'url' => 'billing_openinvoices.php',
					'label' => $lng['billing']['openinvoices'],
				),
				array (
					'url' => 'billing_openinvoices.php?mode=1',
					'label' => $lng['billing']['openinvoices_admin'],
				),
				array (
					'url' => 'billing_invoices.php',
					'label' => $lng['billing']['invoices'],
				),
				array (
					'url' => 'billing_invoices.php?mode=1',
					'label' => $lng['billing']['invoices_admin'],
				),
				array (
					'url' => 'billing_other.php',
					'label' => $lng['billing']['other'],
				),
				array (
					'url' => 'billing_taxrates.php',
					'label' => $lng['billing']['taxclassesnrates'],
				),
				array (
					'url' => 'billing_domains_templates.php',
					'label' => $lng['billing']['domains_templates'],
				),
				array (
					'url' => 'billing_other_templates.php',
					'label' => $lng['billing']['other_templates'],
				),
			),
		),
	),
);
?>
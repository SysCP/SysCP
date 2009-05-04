<?php
return array (
	'admin' => array (
		array (
			'label' => $lng['billing']['billing'],
			'required_resources' => 'edit_billingdata',
			'elements' => array (
				array (
					'url' => 'billing_openinvoices.php',
					'label' => $lng['billing']['openinvoices'],
					'required_resources' => 'edit_billingdata',
				),
				array (
					'url' => 'billing_openinvoices.php?mode=1',
					'label' => $lng['billing']['openinvoices_admin'],
					'required_resources' => 'edit_billingdata',
				),
				array (
					'url' => 'billing_invoices.php',
					'label' => $lng['billing']['invoices'],
					'required_resources' => 'edit_billingdata',
				),
				array (
					'url' => 'billing_invoices.php?mode=1',
					'label' => $lng['billing']['invoices_admin'],
					'required_resources' => 'edit_billingdata',
				),
				array (
					'url' => 'billing_other.php',
					'label' => $lng['billing']['other'],
					'required_resources' => 'edit_billingdata',
				),
				array (
					'url' => 'billing_taxrates.php',
					'label' => $lng['billing']['taxclassesnrates'],
					'required_resources' => 'edit_billingdata',
				),
				array (
					'url' => 'billing_domains_templates.php',
					'label' => $lng['billing']['domains_templates'],
					'required_resources' => 'edit_billingdata',
				),
				array (
					'url' => 'billing_other_templates.php',
					'label' => $lng['billing']['other_templates'],
					'required_resources' => 'edit_billingdata',
				),
			),
		),
	),
);
?>
<?php
return array (
	'customer' => array (
		array (
			'label' => $lng['customer']['aps'],
			'show_element' => ( $settings['aps']['aps_active'] == true ),
			'elements' => array (
				array (
					'url' => 'customer_aps.php?action=overview',
					'label' => $lng['aps']['overview'],
				),
				array (
					'url' => 'customer_aps.php?action=customerstatus',
					'label' => $lng['aps']['status'],
				),
				array (
					'url' => 'customer_aps.php?action=search',
					'label' => $lng['aps']['search'],
				),
			),
		),
	),
	'admin' => array (
		array (
			'label' => $lng['admin']['aps'],
			'show_element' => ( $settings['aps']['aps_active'] == true ),
			'elements' => array (
				array (
					'url' => 'admin_aps.php?action=upload',
					'label' => $lng['aps']['upload'],
					'required_resources' => 'can_manage_aps_packages',
				),
				array (
					'url' => 'admin_aps.php?action=scan',
					'label' => $lng['aps']['scan'],
					'required_resources' => 'can_manage_aps_packages',
				),
				array (
					'url' => 'admin_aps.php?action=managepackages',
					'label' => $lng['aps']['managepackages'],
					'required_resources' => 'can_manage_aps_packages',
				),
				array (
					'url' => 'admin_aps.php?action=manageinstances',
					'label' => $lng['aps']['manageinstances'],
					'required_resources' => 'can_manage_aps_packages',
				),
			),
		),
	),
);
?>
<?php
return array (
	'customer' => array (
		array (
			'url' => 'customer_tickets.php',
			'label' => $lng['menue']['ticket']['ticket'],
			'show_element' => ( $settings['ticket']['enabled'] == true ),
			'elements' => array (
				array (
					'url' => 'customer_tickets.php?page=tickets',
					'label' => $lng['menue']['ticket']['ticket'],
				),
			),
		),
	),
	'admin' => array (
		array (
			'label' => $lng['admin']['ticketsystem'],
			'show_element' => ( $settings['ticket']['enabled'] == true ),
			'elements' => array (
				array (
					'url' => 'admin_tickets.php?page=tickets',
					'label' => $lng['menue']['ticket']['ticket'],
				),
				array (
					'url' => 'admin_tickets.php?page=archive',
					'label' => $lng['menue']['ticket']['archive'],
				),
				array (
					'url' => 'admin_tickets.php?page=categories',
					'label' => $lng['menue']['ticket']['categories'],
				),
			),
		),
	),
);
?>
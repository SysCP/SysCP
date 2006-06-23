<?php

/**#@+
 * Development Part
 * @todo Consider how to change this settings by using config files
 */
//define('SYSCP_DEFAULT_THEME', 'default');
//define('SYSCP_CLEAR_CACHE', false);
error_reporting( E_ALL );
/**#@-*/
/**
 * Prevent SysCP pages from being cached by RFC compliant proxies.
 */
header ("Cache-Control: no-cache, must-revalidate");
header ("Pragma: no-cache");
/**
 * Register Global Security Fix
 * We unset every variable being set in $_REQUEST and global, basically
 * we are reverting the registrations Register_Globals did.
 */
foreach ( $_REQUEST as $key => $value )
{
	if ( isset( $$key ) )
	{
		unset( $$key );
	}
}
unset( $_ );
unset( $value );
unset( $key );

/**
 * Load the SysCP basic class, which provides some core functions
 */
require_once '../lib/classes/Syscp.class.php';

// Load the FrontController with his dependant interfaces and exceptions
Syscp::uses('Syscp.FrontController.Interface');
Syscp::uses('Syscp.FrontController.Web');

/**
 * Instanciate and Dispatch a new Controller
 */
$ctrl = new Syscp_FrontController_Web();
$ctrl->initialize(array());
$ctrl->dispatch();

?>
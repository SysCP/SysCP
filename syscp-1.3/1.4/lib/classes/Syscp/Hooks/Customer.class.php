<?php
/**
 * This file is part of the SysCP project. 
 * Copyright (c) 2003-2006 the SysCP Project. 
 * 
 * For the full copyright and license information, please view the COPYING 
 * file that was distributed with this source code. You can also view the
 * COPYING file online at http://files.syscp.org/misc/COPYING.txt
 * 
 * @author     Martin Burchert <eremit@syscp.org>
 * @author     Michael Duergner <michael@duergner.com>
 * @copyright  (c) the authors
 * @package    Org.Syscp.Core
 * @subpackage Hooks
 * @license    GPLv2 http://files.syscp.org/misc/COPYING.txt
 * @version    $Id: class_idna_convert_wrapper.php 425 2006-03-18 09:48:20Z martin $
 */
 
/**
 * The customer hook implementation. 
 * 
 * This hook implements the customer specific functions regarding the 
 * creation of customer directories.
 *
 * @package    Org.Syscp.Core
 * @subpackage Hooks
 *
 * @author     Martin Burchert <martin.burchert@syscp.org>
 * @version    1.0
 */
class Syscp_Hooks_Customer extends Syscp_BaseHook 
{
	/**
	 * Filename of the file this hook is implemented in. 
	 * Consider this variable to be class specific constant.
	 *
	 * @var    string
	 * @access private
	 */
	var $FILE;  // CONST later

	/**
	 * Classname of this class
	 * Consider this variable to be class specific constant.
	 *
	 * @var    string
	 * @access private
	 */
	var $CLASS; // CONST later
	
	/**
	 * Class Constructor
	 *
	 * @author  Martin Burchert <eremit@syscp.org>
	 * 
	 * @since   1.0
	 * @access  public
	 * 
	 * @return  Org_Syscp_Core_Hooks_Apache
	 */
	function __construct()
	{
		$this->FILE  = 'lib/classes/Syscp/Hooks/Customer.class.php';
		$this->CLASS = __CLASS__;
	}
	
	/**
	 * core.createCustomer Hook
	 * 
	 * This hook basically only schedules the cronCreateCustomer() function 
	 * call for the backend. 
	 *
	 * @author  Martin Burchert <eremit@syscp.org>
	 * 
	 * @since   1.0
	 * @access  public
	 * 
	 * @param   array  $params  Parameters to be used in this hook call
	 * 
	 * @return  void
	 */
	function createCustomer( $params = array() )
	{
		$this->_hooks->schedule( $this->FILE, $this->CLASS, 'cronCreateCustomer', $params );
	}
	
	/**
	 * This method should _ONLY_ be called from the backend cronscript. 
	 * 
	 * This method creates the directories for a new customer.
	 *
	 * @author  Martin Burchert <eremit@syscp.org>
	 * 
	 * @since   1.0
	 * @access  public
	 * 
	 * @param   array  $params  Parameters to be used in this hook call
	 * 
	 * @return  void
	 * 
	 * @todo Reimplement this function to use templates later on. 
	 */
	function cronCreateCustomer( $params = array() )
	{
		// load the config and db vars from our attributes
		$config = $this->_config;
		$db     = $this->_db;
		$log    = $this->_log;

		if(    isset($params['loginname']) 
		    && isset($params['uid'])
		    && isset($params['gid']) )
		{
			$log->info( sprintf( '-- cronCreateCustomer: creating customer directories for user %s',
			                     $params['loginname'] ) );
			$userHome = $config->get('system.documentroot_prefix').$params['loginname'];
			$mailHome = $config->get('system.vmail_homedir').$params['loginname'];
			safe_exec('mkdir -p "'.$userHome.'/webalizer"');
			safe_exec('mkdir -p "'.$mailHome.'"');
			safe_exec('cp -a '.SYSCP_PATH_BASE.'templates/misc/standardcustomer/* "'.$userHome.'/"');
			safe_exec('chown -R '.$params['uid'].':'.$params['gid'].' "'.$userHome.'"');
			safe_exec('chown -R '.$config->get('system.vmail_uid').':'.$config->get('system.vmail_gid').' "'.$mailHome.'"');
		}
	}
}

?>
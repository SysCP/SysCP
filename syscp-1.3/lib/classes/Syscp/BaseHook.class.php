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
 * @author     Michael Dürgner <michael@duergner.com>
 * @copyright  (c) the authors
 * @package    Org.Syscp.Core
 * @subpackage Hooks
 * @license    GPLv2 http://files.syscp.org/misc/COPYING.txt
 * @version    $Id: class_idna_convert_wrapper.php 425 2006-03-18 09:48:20Z martin $
 */
 
/**
 * The basic hook which has to be the mother of all hooks. 
 * 
 * If you want to write your own hook, you must inherit from this base hook, 
 * and you are not allowed to overwrite any function of this base hook.
 *
 * @package    Org.Syscp.Core
 * @subpackage Hooks
 *
 * @author     Martin Burchert <martin.burchert@syscp.org>
 * @author     Michael Dürgner <michael@duergner.com>
 * @version    1.0
 */
class Syscp_BaseHook 
{
	/**
	 * An instanciated database connection
	 *
	 * @var     Syscp_DB_Mysql
	 * @access  protected
	 */
	var $_db;
	
	/**
	 * An instanciated config object
	 *
	 * @var     Org_Syscp_Core_Config
	 * @access  protected
	 */
	var $_config;
	
	/**
	 * An instanciated hook manager object
	 *
	 * @var     Org_Syscp_Core_HookManager
	 * @access  protected
	 */
	var $_hooks;
	
	/**
	 * An instanciated log object
	 *
	 * @var     Log
	 * @access  protected
	 */
	var $_log;
	
	/**
	 * Initialized the hook with the needed envoiremental objects
	 *
	 * @author  Martin Burchert <eremit@syscp.org>
	 * 
	 * @since   1.0
	 * @access  public
	 * 
	 * @param   Syscp_DB_Mysql     $db      A database connection object
	 * @param   Org_Syscp_Core_Config       $config  A config object 
	 * @param   Org_Syscp_Core_HookManager  $hooks   A hook manager object
	 * @param   Log                         $log     A log object
	 * 
	 * @return  void
	 * 
	 * @final
	 */
	function initialize( $db = null, $config = null, $hooks = null, $log = null )
	{
		$this->_db     = $db;
		$this->_config = $config;
		$this->_hooks  = $hooks;
		$this->_log    = $log;
	}
}

?>
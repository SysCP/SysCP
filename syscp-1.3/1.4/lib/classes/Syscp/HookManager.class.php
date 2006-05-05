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
 * A hook manager model used to maintain hooking in the SysCP application
 *
 * @package    Org.Syscp.Core
 * @subpackage Hooks
 *
 * @author     Martin Burchert <martin.burchert@syscp.org>
 * @author     Michael Dürgner <michael@duergner.com>
 * @version    1.0
 */
class Syscp_HookManager
{
	/**
	 * An instanciated database connection
	 *
	 * @var     Syscp_DB_Mysql
	 * @access  private
	 */
	var $_db;
	
	/**
	 * An instanciated config object
	 *
	 * @var     Org_Syscp_Core_Config
	 * @access  private
	 */
	var $_config;
	
	/**
	 * The list of hooks being known to the hookmanager
	 *
	 * @var     array
	 * @access  private
	 */
	var $_hookList;
	
	/**
	 * Class Constructor
	 *
	 * @author  Martin Burchert <eremit@syscp.org>
	 * 
	 * @since   1.0
	 * @access  public
	 * 
	 * @param   Syscp_DB_Mysql  $db      An instanciated databse 
	 *                                            connection object
	 * @param   Org_Syscp_Core_Config    $config  An instanciated config object
	 * 
	 * @return  Org_Syscp_Core_HookManager
	 */
	function __construct( $db = null, $config = null )
	{
		$this->_db       = $db;
		$this->_config   = $config; 
		$this->_hookList = array();
		
		if ( !is_null($this->_db) )
		{
			$this->loadHooklist( $this->_db );
		}
	}
	
	/**
	 * Executed the given hook with the given params
	 *
	 * @author  Martin Burchert <eremit@syscp.org>
	 * 
	 * @since   1.0
	 * @access  public
	 * 
	 * @param   string  $hook    Name of the hook to be executed
	 * @param   array   $params  Array of parameters given to the hook
	 */
	function call( $hook, $params = array() )
	{
		// lets start and check if there is a hook defined in our
		// local list of hooks
		if( isset($this->_hookList[$hook]) && is_array($this->_hookList[$hook]) )
		{
			// we are lazy lets copy the hooklist to a shorter var
			$list = $this->_hookList[$hook];
			
			// and now we iterate it
			foreach( $list as $key => $value )
			{
				// default the obj to null
				$list[$key]['obj'] = null;
				// load and instanciate the classes we need
				if ( file_exists( SYSCP_PATH_BASE.$value['file'] ) )
				{
					// load
					require_once SYSCP_PATH_BASE.$value['file'];
					// instanciate
					$list[$key]['obj'] = new $value['class'];
					// init
					$list[$key]['obj']->initialize( $this->_db, $this->_config, $this );
				}
			}
			
			// this time we iterate again and call every needed function
			foreach( $list as $key => $value )
			{
				// extract the method name
				$method = $value['method'];
				
				// make a copy of the params, to ensure no params are 
				// somehow changed in the hook
				$_params = $params;

				if ( method_exists( $list[$key]['obj'], $method ))
				{
					// call the hook
					$list[$key]['obj']->$method( $_params );
				}
				else 
				{
					// @TODO We need to throw an error here!
				}
			}
		}
	}
	
	/**
	 * Schedules a file/class/method call for later execution
	 *
	 * @author  Martin Burchert <eremit@syscp.org>
	 * 
	 * @since   1.0
	 * @access  public
	 * 
	 * @param   string  $file    Name of the file which contains the given class
	 * @param   string  $class   Name of the class which contains the given method
	 * @param   string  $method  Name of the method to be excuted
	 * @param   array   $params  Array of parameters given to method
	 * 
	 * @return  void
	 */
	function schedule( $file, $class, $method, $params )
	{
		$query = 
			'INSERT INTO `%s` ' .
			'SET `file`   = \'%s\', ' .
			'    `class`  = \'%s\', ' .
			'    `method` = \'%s\', ' .
			'    `params` = \'%s\' ';
		$query = sprintf( $query, 
		                  TABLE_PANEL_TASKS,
		                  $file,
		                  $class,
		                  $method,
		                  urlencode( serialize($params) ) );
		$this->_db->query($query);
		
	}
	
	/**
	 * Adds a hook to the internal hook list
	 *
	 * @author  Martin Burchert <eremit@syscp.org>
	 * 
	 * @since   1.0
	 * @access  public
	 * 
	 * @param   string   $hook      Name of the hook
	 * @param   integer  $priority  Priority of the hook
	 * @param   string   $file      File, where the hook class can be found
	 * @param   string   $class     Class, where the hook method can be found
	 * @param   string   $method    Method to be executed
	 */
	function addHook( $hook, $priority, $file, $class, $method )
	{
		// First we need to check if there is already another
		// hook with the same priority. If it's so, we do
		// first come first serve, and add the new hook at
		// the first empty position after the existing hook
		while( isset($this->_hookList[$hook][$priority] ) )
		{
			$priority++;
		}
		
		// store the hook 
		$this->_hookList[$hook][$priority]['file']   = $file;
		$this->_hookList[$hook][$priority]['class']  = $class;
		$this->_hookList[$hook][$priority]['method'] = $method;
	}
	
	/**
	 * Loads the stored hooklist from the database
	 *
	 * @author  Martin Burchert <eremit@syscp.org>
	 * 
	 * @since   1.0
	 * @access  public
	 * 
	 * @param   Syscp_DB_Mysql  $db  Instanciated database connection
	 * 
	 * @return  void
	 */
	function loadHooklist( $db = null )
	{
		// Check the database connection parameter and try to
		// load the classes connection if this function hasn't one
		if ( is_null($db) )
		{
			$db = $this->_db;
		}
		
		// We check again, if we now have a valid database
		// conncetion
		if ( is_null($db) )
		{
			// we don't have a valid connection and return with FALSE
			// @TODO: should throw an exception later on
			return false;
		}
		
		// retrieve full list of all hooks 
		$query  = 'SELECT * FROM `%s`';
		$query  = sprintf( $query, TABLE_PANEL_HOOKS );
		$result = $db->query( $query );
		
		// add them to the local hook manager's storage
		while ( false !== ($row = $db->fetch_array( $result ) ) )
		{
			$this->addHook( sprintf( '%s.%s', $row['module'], $row['hook']), 
			                $row['priority'], 
			                $row['file'], 
			                $row['class'], 
			                $row['method'] );
		}
	}

}
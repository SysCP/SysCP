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
 * @copyright  (c) 2006, the authors
 * @package    Syscp.Framework
 * @subpackage Syscp.Handler
 * @license    GPLv2 http://files.syscp.org/misc/COPYING.txt
 * @version    $Id:admin_admins.php 460 2006-04-23 15:07:49 +0200 (So, 23 Apr 2006) martin $
 */

/**
 * @todo Extended documentation here!
 *
 * @author     Martin Burchert <eremit@syscp.org>
 * @copyright  (c) 2006, The Authors
 * @package    Syscp.Framework
 * @subpackage Syscp.Handler
 */
class Syscp_Handler_Hook implements Syscp_Handler_Hook_Interface
{
	private $DatabaseHandler = null;
	private $ConfigHandler   = null;
	private $TemplateHandler = null;
	private $hookList        = array();
	private $hookConfig = array();

	const ERROR_MISSING_PARAM  = 'You need to specify the %s parameter.';
	const ERROR_NO_DBHANDLER   = 'Syscp_Handler_Hook::loadHooklist() needs a valid instance of Syscp_Handler_Database_Interface';
	const ERROR_METHOD_MISSING = 'The requested method %s is not implemented in the hook implementation.';
	const ERROR_PARAM_TYPE    = 'The param %s needs to be an instance of %s.';

	public function initialize($params = array())
	{
		// we don't want to repeat ourselves
		$required = array('hookConfig'      => '',
		                  'DatabaseHandler' => 'Syscp_Handler_Database_Interface',
		                  'ConfigHandler'   => 'Syscp_Handler_Config_Interface',
		                  'TemplateHandler' => 'Syscp_Handler_Template_Interface',
		                  'LogHandler'      => 'Syscp_Handler_Log_Interface');
		foreach($required as $index => $instanceOf)
		{
			if(isset($params[$index]))
			{
				if($instanceOf == '' || $params[$index] instanceof $instanceOf)
				{
					$this->$index = $params[$index];
				}
				else
				{
					$error = sprintf(self::ERROR_PARAM_TYPE, $index, $instanceOf);
					throw new Syscp_Handler_Hook_Exception($error);
				}
			}
			else
			{
				$error = sprintf(self::ERROR_MISSING_PARAM, $index);
				throw new Syscp_Handler_Hook_Exception($error);
			}
		}
		$this->loadHooklist();
	}

	private function loadHooklist()
	{
		// Check the database connection parameter and try to
		// load the classes connection if this function hasn't one
		//if ( is_null($this->DatabaseHandler) )
		//{
		//	$error = self::ERROR_NO_DBHANDLER;
		//	throw new Syscp_Handler_Hook_Exception($error);
		//}

		// retrieve full list of all hooks
		//$query  = 'SELECT * FROM `%s`';
		//$query  = sprintf( $query, TABLE_PANEL_HOOKS );
		//$result = $this->DatabaseHandler->query( $query );

		// add them to the local hook manager's storage
		foreach($this->hookConfig as $row)
		{
			$this->addHook( $row['hook'],
			                $row['priority'],
			                $row['file'],
			                $row['class'],
			                $row['method'] );
		}
	}

	public function call($hookName, $params = array())
	{
		// lets start and check if there is a hook defined in our
		// local list of hooks
		if (isset($this->hookList[$hookName]) && is_array($this->hookList[$hookName]))
		{
			// we are lazy lets copy the hooklist to a shorter var
			$list = $this->hookList[$hookName];

			// sort according to priority
			ksort($list);

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
					$list[$key]['obj']->initialize($this->DatabaseHandler,
					                               $this->ConfigHandler,
					                               $this,
					                               $this->LogHandler,
					                               $this->TemplateHandler);
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
					$error = sprintf(self::ERROR_METHOD_MISSING, $method);
					throw new Syscp_Handler_Hook_Exception($error);
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
	public function schedule($file, $class, $method, $params)
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
		$this->DatabaseHandler->query($query);
	}
	public function addHook($hook, $priority, $file, $class, $method)
	{
		// First we need to check if there is already another
		// hook with the same priority. If it's so, we do
		// first come first serve, and add the new hook at
		// the first empty position after the existing hook
		while( isset($this->hookList[$hook][$priority] ) )
		{
			$priority++;
		}

		// store the hook
		$this->hookList[$hook][$priority]['file']   = $file;
		$this->hookList[$hook][$priority]['class']  = $class;
		$this->hookList[$hook][$priority]['method'] = $method;
	}

}

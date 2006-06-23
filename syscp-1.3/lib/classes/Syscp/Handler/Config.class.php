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

Syscp::uses('Syscp.Handler.Database.Interface');

/**
 * @todo Extended documentation here!
 *
 * @author     Martin Burchert <eremit@syscp.org>
 * @copyright  (c) 2006, The Authors
 * @package    Syscp.Framework
 * @subpackage Syscp.Handler
 */
class Syscp_Handler_Config implements Syscp_Handler_Config_Interface
{
	/**
	 * Array to store the config informations
	 *
	 * @var    array
	 * @access private
	 */
	private $_settings = array();

	public function initialize($params = array())
	{
		if(isset($params['files']))
		{
			foreach($params['files'] as $file)
			{
				$result = Syscp::parseConfig($file);
				if(isset($result['conf']['mysql']['database']))
				{
					$this->set('sql.db', $result['conf']['mysql']['database']);
				}
				if(isset($result['conf']['mysql']['hostname']))
				{
					$this->set('sql.host', $result['conf']['mysql']['hostname']);
				}
				if(isset($result['conf']['mysql']['username']))
				{
					$this->set('sql.user', $result['conf']['mysql']['username']);
				}
				if(isset($result['conf']['mysql']['password']))
				{
					$this->set('sql.password', $result['conf']['mysql']['password']);
				}
				if(isset($result['conf']['mysql']['root']['username']))
				{
					$this->set('sql.root.user', $result['conf']['mysql']['root']['username']);
				}
				if(isset($result['conf']['mysql']['root']['password']))
				{
					$this->set('sql.root.password', $result['conf']['mysql']['root']['password']);
				}
				if(isset($result['installed']['version']))
				{
					$this->set('env.version', $result['installed']['version']);
				}
				if(isset($result['conf']['theme']['default']))
				{
					if( !defined('SYSCP_DEFAULT_THEME') )
					{
						define('SYSCP_DEFAULT_THEME', $result['conf']['theme']['default']);
					}
					$this->set('env.default_theme', $result['conf']['theme']['default']);
				}
				if(isset($result['devel']['cache']))
				{
					$caching = $result['devel']['cache'];
					if($caching == 'off')
					{
						$caching = false;
					}
					else
					{
						$caching = true;
					}
					if( !defined('SYSCP_CLEAR_CACHE') )
					{
						define('SYSCP_CLEAR_CACHE', !$caching);
					}
					$this->set('env.caching', $caching);
				}
			}
		}
		if( isset( $_REQUEST['action'] ) )
		{
			$this->set('env.action', $_REQUEST['action']);
		}
		// set the current session
		if( isset( $_REQUEST['s'] ) )
		{
			$this->set('env.s', $_REQUEST['s']);
		}
		// set the current page
		if( isset( $_REQUEST['page'] ) )
		{
			$this->set( 'env.page', $_REQUEST['page'] );
		}
		else
		{
			$this->set( 'env.page', 'overview' );
		}
		//set the current id
		if ( isset( $_REQUEST['id'] ) )
		{
			$this->set('env.id', $_REQUEST['id'] );
		}

		// set information about the calling user
		if (isset($_SERVER['REMOTE_ADDR']))
		{
			$this->set('env.remote_addr', htmlspecialchars($_SERVER['REMOTE_ADDR']));
		}
		if (isset($_SERVER['HTTP_USER_AGENT']))
		{
			$this->set('env.http_user_agent', htmlspecialchars($_SERVER['HTTP_USER_AGENT']) );
		}
	}
	/**
	 * Returns a value of the given path, or the defValue if the path is not found.
	 *
	 * @author  Martin Burchert <eremit@syscp.org>
	 *
	 * @since   1.0
	 * @access  public
	 *
	 * @param   string  $path      The path to be returned.
	 * @param   mixed   $defValue  The default value to which is returned if the
	 *                             path do not exists.
	 *
	 * @return  mixed   The requested value or the defValue
	 */
	public function get($path, $defValue = false)
	{
		if(isset($this->_settings[$path]))
		{
			return $this->_settings[$path];
		}
		else
		{
			return $defValue;
		}
	}

	/**
	 * Changes the value of a given path, if the path do not exists, it will
	 * be created.
	 *
	 * @author  Martin Burchert <eremit@syscp.org>
	 *
	 * @since   1.0
	 * @access  public
	 *
	 * @param   string $path   The path at which the value should be stored
	 * @param   mixed  $value  The value which should be stored
	 *
	 * @return  void
	 */
	public function set($path, $value)
	{
		$this->_settings[$path] = $value;
	}

	/**
	 * Returns if the storage has the given path.
	 *
	 * @author  Martin Burchert <eremit@syscp.org>
	 *
	 * @since   1.0
	 * @access  public
	 *
	 * @param   string   $path  The path to be checked
	 *
	 * @return  boolean  If the path exists or not.
	 */
	public function has($path)
	{
		if ( isset( $this->_settings[$path] ) )
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	/**
	 * Loads the settings from the database and fills the local storage.
	 *
	 * @author  Martin Burchert <eremit@syscp.org>
	 *
	 * @since   1.0
	 * @access  public
	 *
	 * @param   Syscp_DB_Mysql  $db  A database connection object
	 *
	 * @return  void
	 */
	public function loadFromDB(Syscp_Handler_Database_Interface $db)
	{
		$query = 'SELECT * FROM `%s`';
		$query = sprintf( $query, TABLE_PANEL_SETTINGS );
		$result = $db->query( $query );
		while(false !== ($row = $db->fetch_array($result)) )
		{
			$path = sprintf( '%s.%s', $row['settinggroup'], $row['varname'] );
			$this->set( $path, $row['value'] );
		}

	}

	/**
	 * Loads the basic configuration from userdata.
	 *
	 * @author  Martin Burchert <eremit@syscp.org>
	 *
	 * @since   1.0
	 * @access  public
	 *
	 * @return  boolean  If this succeeded or not.
	 */
	function loadBaseConfig()
	{


	}

}
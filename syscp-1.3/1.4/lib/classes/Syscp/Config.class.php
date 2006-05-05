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
 * @copyright  (c) the authors
 * @package    Org.Syscp.Core
 * @subpackage Config
 * @license    GPLv2 http://files.syscp.org/misc/COPYING.txt
 * @version    $Id: class_mysqldb.php 340 2006-02-21 06:47:03Z martin $
 */

/**
 * Class to manage the configuration of SysCP
 *  
 * @author     Martin Burchert <eremit@syscp.org>
 * @copyright  (c) the authors
 * @package    Org.Syscp.Core
 * @subpackage Config
 */
class Syscp_Config
{
	/**
	 * Array to store the config informations
	 *
	 * @var    array
	 * @access private
	 */
	var $_settings;
	
	/**
	 * Class Constructor
	 *
	 * @author  Martin Burchert <eremit@syscp.org>
	 * 
	 * @since   1.0
	 * @access  public
	 * 
	 * @param   Syscp_DB_Mysql  $db  A database connection object
	 * 
	 * @return  Org_Syscp_Core_Config
	 */
	function __construct( $db=null )
	{
		$this->_settings = array();
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
	function get( $path, $defValue = false )
	{
		if ( isset( $this->_settings[$path] ) )
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
	function set( $path, $value )
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
	function has( $path )
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
	function loadSettings( $db = null )
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
		if ( file_exists( SYSCP_PATH_BASE.'etc/version.conf' ) )
		{
			$version = file( SYSCP_PATH_BASE.'etc/version.conf' );
			foreach( $version as $line )
			{
				$line = trim($line);
				if( $line != '' && substr($line, 0, 1) != '#' )
				{
					list($var,$value) = split('=', $line);
					$var   = trim($var);
					$value = trim($value);
					switch( $var )
					{
						case 'installed.version':
							$this->set('env.version', $value);
							break;
					}
				}
			}
		}
		else
		{
			return false;
		}
		if ( file_exists( SYSCP_PATH_BASE.'etc/syscp.conf' ) )
		{
			$config = file( SYSCP_PATH_BASE.'etc/syscp.conf' );
			
			foreach( $config as $line )
			{
				$line = trim($line);
				if( $line != '' && substr($line, 0, 1) != '#' )
				{
					list($var,$value) = split('=', $line);
					$var   = trim($var);
					$value = trim($value);
					switch ($var)
					{
						case 'conf.mysql.database':
							$this->set('sql.db', $value);
							break;
						case 'conf.mysql.hostname':
							$this->set('sql.host', $value);
							break;
						case 'conf.mysql.username':
							$this->set('sql.user', $value);
							break;
						case 'conf.mysql.password':
							$this->set('sql.password', $value);
							break;
						case 'conf.mysql.root.username':
							$this->set('sql.root.user', $value);
							break;
						case 'conf.mysql.root.password':
							$this->set('sql.root.password', $value);
							break;
					}
				}
			}
//			require SYSCP_PATH_BASE.'etc/userdata.inc.php';
//			$this->set( 'sql.db', $sql['db'] );
//			$this->set( 'sql.host', $sql['host'] );
//			$this->set( 'sql.user', $sql['user'] );
//			$this->set( 'sql.password', $sql['password'] );
//			$this->set( 'sql.root.user', $sql['root_user'] );
//			$this->set( 'sql.root.password', $sql['root_password'] );
			return true;
		}
		else 
		{
			return false;
		}
		
	}
	
}
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
 * @author     Florian Lippert <flo@syscp.org>
 * @copyright  (c) 2003-2006 the authors
 * @package    SysCP
 * @subpackage Core.DB
 * @license    GPLv2 http://files.syscp.org/misc/COPYING.txt
 * @version    $Id: customer_domains.php 464 2006-04-23 15:54:24 +0000 (So, 23 Apr 2006) martin $
 */

/**
 * This class implements the session handler for mysql session storage. 
 *
 * @package    SysCP
 * @subpackage DB
 *
 * @author     Martin Burchert <martin.burchert@syscp.org>
 * @author     Florian Lippert <florian.lippert@syscp.org>
 * @version    1.0
 */
class Syscp_DB_MysqlSession
{
	/**
	 * Stores an instanciated database connection object
	 *
	 * @var Syscp_DB_Mysql
	 */
	private $_db = null;
	/**
	 * Stores an instanciated config object
	 *
	 * @var Syscp_Config
	 */
	private $_config = null;
	
	/**
	 * Constructor
	 *
	 * @author  Martin Burchert <martin.burchert@syscp.org>
	 * @author  Florian Lippert <florian.lippert@syscp.org>
	 * 
	 * @since   1.0
	 * 
	 * @param   Syscp_DB_Mysql  $dbLink
	 * @param   Syscp_Config    $config
	 * 
	 * @return  Syscp_DB_MysqlSession
	 */
	public function __construct( $dbLink = null, $config = null )
	{
		$this->_db = $dbLink;
		$this->_config = $config;
		
		session_set_save_handler ( array ( $this, 'openSession' ),
                                   array ( $this, 'closeSession' ),
                                   array ( $this, 'readSession' ),
                                   array ( $this, 'writeSession' ),
                                   array ( $this, 'destroySession' ),
                                   array ( $this, 'gcSession' ) );
	}
	
	/**
	 * Creates a new session with the given sessionId
	 * 
	 * @author  Martin Burchert <martin.burchert@syscp.org>
	 * @author  Florian Lippert <florian.lippert@syscp.org>
	 * 
	 * @since   1.0
	 * 
	 * @param   mixed  $sessionId
	 * 
	 * @return  void
	 */
	public function createSession( $sessionId )
	{
		$query = 'INSERT INTO `%s` ' .
			     'SET `hash`         = \'%s\', ' .
			     '    `data`         = \'\', ' .
			     '    `lastactivity` = \'%s\', ' .
			     '    `ipaddress`    = \'%s\', ' .
			     '    `useragent`    = \'%s\' ';
		$query = sprintf( $query, TABLE_PANEL_SESSIONS,
		                          $sessionId,
		                          time(),
		                          $this->_config->get('env.remote_addr'),
		                          $this->_config->get('env.http_user_agent') );
		$this->_db->query( $query );
	}
		
	/**
	 * returnes whether a session exists or not
	 *   true : session exists
	 *   false: session does not exist
	 *
	 * @author  Martin Burchert <martin.burchert@syscp.org>
	 * @author  Florian Lippert <florian.lippert@syscp.org>
	 * 
	 * @since   1.0
	 * 
	 * @param   string   $sessionId
	 * 
	 * @return  boolean 
	 */
	public function isSession( $sessionId )
	{
		$query = 'SELECT `hash` ' .
			     'FROM   `%s` ' .
			     'WHERE  `hash`         = \'%s\' ' .
			     '  AND  `ipaddress`    = \'%s\' ' .
			     '  AND  `useragent`    = \'%s\' ';
			     '  AND `lastactivity` >= \'%s\' ';
		$query = sprintf( $query, TABLE_PANEL_SESSIONS,
		                          $sessionId,
		                          $this->_config->get('env.remote_addr'),
		                          $this->_config->get('env.http_user_agent'),
		                          time() - ini_get( 'session.gc_maxlifetime' ) );
		$result = $this->_db->query( $query );
		$row    = $this->_db->fetch_array( $result );
		if ( $row )
		{
			$return = true;
		}
		else
		{
			$return = false;
		}
		return $return;
	}
	
	/**
	 * Dummy handler for session_open for phpsession 
	 * This handler is not needed here, will always return true
	 *
	 * @author  Martin Burchert <martin.burchert@syscp.org>
	 * @author  Florian Lippert <florian.lippert@syscp.org>
	 * 
	 * @since   1.0
	 * 
	 * @return  boolean
	 */
	public function openSession( $savePath, $sessionName )
	{
		return true;
	}
	
	/**
	 * Dummy handler for session_close for phpsession 
	 * This handler is not needed here, will always return true
	 *
	 * @author  Martin Burchert <martin.burchert@syscp.org>
	 * @author  Florian Lippert <florian.lippert@syscp.org>
	 * 
	 * @since   1.0
	 * 
	 * @return  boolean
	 */
	public function closeSession()
	{
		return true;
	}
	
	/**
	 * Returns data for a session
	 *
	 * Can be invoked as:
	 * <code>
	 *     $yourvar = $_SESSION['varname'];
	 * </code>
	 *
	 * @author  Martin Burchert <martin.burchert@syscp.org>
	 * @author  Florian Lippert <florian.lippert@syscp.org>
	 * 
	 * @since   1.0
	 * 
	 * @param   string  $sessionId
	 * @return  mixed
	 */
	public function readSession( $sessionId )
	{
		if ( !$this->isSession( $sessionId ) )
		{
			$this->createSession( $sessionId );
		}
		$query  = 'SELECT * ' .
			      'FROM   `%s` ' .
			      'WHERE  `hash`         = \'%s\' ' .
			      '  AND `ipaddress`     = \'%s\' ' .
			      '  AND `useragent`     = \'%s\' ' .
			      '  AND `lastactivity` >= \'%s\' ';
		$query = sprintf( $query, TABLE_PANEL_SESSIONS,
		                          $sessionId,
		                          $this->_config->get('env.remote_addr'),
		                          $this->_config->get('env.http_user_agent'),
		                          time() - ini_get( 'session.gc_maxlifetime' ) );
		$data = $this->_db->query_first( $query );
		return $data['data'];
	}
	
	/**
	 * Writes session data to database
	 *
	 * Can be invoked as:
	 * <code>
	 *     $_SESSION['varname'] = 'newval';
	 * </code>
	 *
	 * @author  Martin Burchert <martin.burchert@syscp.org>
	 * @author  Florian Lippert <florian.lippert@syscp.org>
	 * 
	 * @since   1.0
	 * 
	 * @param   string  $sessionId
	 * @return  boolean
	 */
	public function writeSession( $sessionId, $data )
	{
		$query = 'UPDATE `%s` ' .
 		         'SET    `data`          = \'%s\', ' . 
		         '       `lastactivity`  = \'%s\' ' .
		         'WHERE  `hash`          = \'%s\' ' .
		         '  AND  `ipaddress`     = \'%s\' ' .
		         '  AND  `useragent`     = \'%s\' ' .
		         '  AND  `lastactivity` >= \'%s\' ';
		$query = sprintf( $query, TABLE_PANEL_SESSIONS,
		                          $data,
		                          time(),
		                          $sessionId,
		                          $this->_config->get('env.remote_addr'),
		                          $this->_config->get('env.http_user_agent'),
		                          time() - ini_get( 'session.gc_maxlifetime' ) );
		$this->_db->query( $query );
		return true;
	}
	
	/**
	 * Destroys a session and deletes it from database
	 *
	 * @author  Martin Burchert <martin.burchert@syscp.org>
	 * @author  Florian Lippert <florian.lippert@syscp.org>
	 * 
	 * @since   1.0
	 * 
	 * @param   string  $sessionId
	 * @return  boolean
	 */
	public function destroySession( $sessionId )
	{
		$query = 'DELETE FROM `%s` ' .
		         'WHERE `hash`      = \'%s\' ' .
		         '  AND `ipaddress` = \'%s\' ' .
		         '  AND `useragent` = \'%s\' ';
		$query = sprintf( $query, TABLE_PANEL_SESSIONS,
		                          $sessionId,
		                          $this->_config->get('env.remote_addr'),
		                          $this->_config->get('env.http_user_agent') );
		$this->_db->query( $query );
		return true;
	}
	
	/**
	 * Deletes all expired sessions from database
	 *
	 * @author  Martin Burchert <martin.burchert@syscp.org>
	 * @author  Florian Lippert <florian.lippert@syscp.org>
	 * 
	 * @since   1.0
	 * 
	 * @param   integer  $maxlifetime
	 * @return  boolean
	 */
	public function gcSession( $maxlifetime )
	{
		$query = 'DELETE FROM `%s` ' .
		         'WHERE `lastactivity` < \'%s\'';
		$query = sprintf( $query, TABLE_PANEL_SESSIONS,
		                          (time()-$maxlifetime) );
		$this->_db->query( $query );                          
		return true;
	}
	
}
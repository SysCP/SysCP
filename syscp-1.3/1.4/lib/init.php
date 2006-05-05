<?php
/**
 * This file is part of the SysCP project. 
 * Copyright (c) 2003-2006 the SysCP Project. 
 * 
 * For the full copyright and license information, please view the COPYING 
 * file that was distributed with this source code. You can also view the
 * COPYING file online at http://files.syscp.org/misc/COPYING.txt
 * 
 * @author     Florian Lippert <flo@redenswert.de>
 * @copyright  (c) the authors
 * @package    Org.Syscp.Core
 * @subpackage Panel
 * @license    GPLv2 http://files.syscp.org/misc/COPYING.txt
 * @version    $Id:init.php 460 2006-04-23 15:07:49 +0200 (So, 23 Apr 2006) martin $
 * 
 * @todo We need some code cleanup here:<br/>
 *       * requires, defines everything is scattered accross the code and don't have the
 *         same syntax<br/>
 *       * the file needs to be structured logically, defining, requirement loading, instanciation etc<br/>
 *       * a documentation regarding what is set and defined, what purpose does it have etc is missing<br/>
 */

// +----------------------------------------------------------------
// | GENERAL PHP PART 
// +----------------------------------------------------------------
	error_reporting( E_ALL );
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
	
// +----------------------------------------------------------------
// | SYSCP CORE SETUP  
// +----------------------------------------------------------------

	/**@#+
	 * Define SysCP Pathes
	 * This works only for calls made directly to the main directory of SysCP 
	 */
	define('SYSCP_PATH_BASE',  dirname(dirname($_SERVER['SCRIPT_FILENAME'])).'/'   );
	define('SYSCP_PATH_CLASS', sprintf('%slib/classes/', SYSCP_PATH_BASE) );
	define('SYSCP_PATH_PEAR',  sprintf('%slib/classes/PEAR', SYSCP_PATH_BASE) );
	
	define('SYSCP_PATH_LIB',   sprintf('%slib/', SYSCP_PATH_BASE ) );
	/**@#-*/
	
	/**@#+
	 * Change the INCLUDE_PATH this is needed to have PEAR work properly. 
	 * Additionally we setup an PEAR overlay here, basically it works the following
	 * way: 
	 *   1. we add the basic pear installation to the INCLUDE_PATH 
	 *   2. we decide which php version we have installed and
	 *   3. add the PHP version specific part of PEAR to the INCLUDE_PATH
	 */
	ini_set('include_path', SYSCP_PATH_PEAR . PATH_SEPARATOR . ini_get('include_path'));
	/**@#-*/
	
	/**@#+
	 * Require SysCP Dependency Classes
	 */
	require_once SYSCP_PATH_BASE.'etc/tables.inc.php';
	require_once SYSCP_PATH_BASE.'lib/functions.php';
	
	require_once SYSCP_PATH_CLASS.'Syscp/Config.class.php';
	require_once SYSCP_PATH_CLASS.'Syscp/IDNA.class.php';
	require_once SYSCP_PATH_CLASS.'Syscp/Language.class.php';
	require_once SYSCP_PATH_CLASS.'Syscp/BaseHook.class.php';
	require_once SYSCP_PATH_CLASS.'Syscp/HookManager.class.php';
	require_once SYSCP_PATH_CLASS.'Syscp/DB/Mysql.class.php';
	require_once SYSCP_PATH_CLASS.'Syscp/DB/MysqlSession.class.php';

	require_once 'PEAR.php';
	require_once 'Log.php';
	/**@#-*/

	/**
	 * The Logmanager, used for logfile creation
	 * We create several log instances here, 
	 * $log      : Will be the user specific log later on
	 * $debugLog : Will be used as the debug specific log 
	 * $authLog  : Will be used as authentication log
	 * $sysLog   : Will be used as system log
	 */
	$log   = null;
	$debugLog = &Log::factory('file', SYSCP_PATH_BASE.'logs/debug.log',  'Debug',  array() );
	$authLog  = &Log::factory('file', SYSCP_PATH_BASE.'logs/auth.log',   'Auth',   array() );
	$sysLog   = &Log::factory('file', SYSCP_PATH_BASE.'logs/system.log', 'System', array() );

	/**
	 * Load and Init some the Config of SysCP
	 */
	$config = new Syscp_Config();
	// Check if we can load the base config, including the userdata
	if ( !$config->loadBaseConfig() )
	{
		die('You have to configure and install SysCP first! Please go to the bash and type \'/path/to/syscp/sbin/syscp install\'');
	}

	/**
	 * Instanciate the Database Abstraction Layer
	 */
	$db = new Syscp_DB_Mysql( $config->get('sql.host'),     $config->get('sql.user'), 
	                                   $config->get('sql.password'), $config->get('sql.db') );

	/**
	 * Load the rest of the SysCP config from the database and set some envoiremental things
	 */
	$config->loadSettings( $db );
	// set the current filename
	$config->set( 'env.filename', basename($_SERVER['PHP_SELF']));
	// set the current action
	if( isset( $_REQUEST['action'] ) )
	{
		$config->set( 'env.action', $_REQUEST['action'] );
	}
	// set the current session
	if( isset( $_REQUEST['s'] ) )
	{
		$config->set('env.s', $_REQUEST['s']);
	}
	// set the current page
	if( isset( $_REQUEST['page'] ) )
	{
		$config->set( 'env.page', $_REQUEST['page'] );
	}
	else 
	{
		$config->set( 'env.page', 'overview' );
	}
	//set the current id
	if ( isset( $_REQUEST['id'] ) )
	{
		$config->set('env.id', $_REQUEST['id'] );
	}
	
	// set information about the calling user
	$config->set('env.remote_addr', htmlspecialchars($_SERVER['REMOTE_ADDR']));
	$config->set('env.http_user_agent', htmlspecialchars($_SERVER['HTTP_USER_AGENT']) );
//	unset( $version );
	// set the current version
//	$config->set( 'env.version', $version );
	/**
	 * Check if we have to call the updater
	 */
	if( $config->get('panel.version') != $config->get('env.version') )
	{
		die( 'You\'ll have to upgrade your SysCP installation. <br/>Please call "syscp upgrade" in your bash!<br/>Installed Version: '.$config->get('env.version').'<br/>Database Version: '.$config->get('panel.version').'<br/>');
	}

	/**
	 * SESSION MANAGEMENT
	 */
	// create a new session object
	$session  = new Syscp_DB_MysqlSession( $db, $config );
	$userinfo = array();
	
	ini_set( 'session.gc_maxlifetime', $config->get( 'session.sessiontimeout' ) );
	ini_set( 'session.gc_probability', 100 );
	ini_set( 'session.use_cookies', false );
	session_name('s');
	
	// if there has been given a session name in $_REQUEST
	if( isset( $_REQUEST[session_name()] ) && $_REQUEST[session_name()] != '' )
	{
		// We start a session
        session_start();
        
        // We now need to check if there is a userid given in the session and the 
        // admin non admin flag 
        if ( isset($_SESSION['userid']) && isset($_SESSION['adminsession']) )
        {
        	// we need to load the user from the database
        	$query = 'SELECT * FROM `%s` WHERE `%s`=\'%s\'';
        	if ( AREA == 'admin' && (int)$_SESSION['adminsession'] == 1 )
        	{
        		$query = sprintf( $query, TABLE_PANEL_ADMINS, 
        		                          'adminid', 
        		                          (int) $_SESSION['userid'] );
        	}
        	else
        	{
        		$query = sprintf( $query, TABLE_PANEL_CUSTOMERS, 
        		                          'customerid', 
        		                          (int) $_SESSION['userid'] );	
        	}
        	$userinfo = $db->query_first( $query );
        }

		if(
		   (
		    ( $_SESSION['adminsession'] == '1' && AREA == 'admin' && isset($userinfo['adminid']) ) ||
		    ( $_SESSION['adminsession'] == '0' && ( AREA == 'customer' || AREA == 'login' ) && isset($userinfo['customerid']) )
		   ) &&
		   (!isset($userinfo['deactivated']) || $userinfo['deactivated'] != '1')
		  )
		{
			// Log user recognized successfull 
			$logMsg = sprintf( 'Recognized user %s from %s with session id of %s',
			                   $userinfo['loginname'],
			                   $config->get('env.remote_addr'),
			                   $config->get('env.s') );
			$authLog->info( $logMsg );
			// and create the new logfile instance for this user
			$log = &Log::factory( 'file', SYSCP_PATH_BASE.'logs/user-'.$userinfo['loginname'].'.log', $userinfo['loginname'] );
			// and log the file the user called
			$log->info( sprintf( 'User called file %s', $_SERVER['PHP_SELF'] ) );
			
		}
		else
		{
			$config->set('env.s', false );
		}
	}

//	 Redirects to index.php (login page) if no session exists
	if( !$config->get('env.s') && AREA != 'login' )
	{
		unset($userinfo);
		redirectTo ( 'index.php' ) ;
		exit;
	}
	// unset not needed vars
	unset( $adminsession );
	unset( $query );
	unset( $timediff );
	
	/**
	 * Create some helper classes
	 */
	// the idna converter
	$idna = new Syscp_IDNA();
	// the hook manager
	$hooks = new Syscp_HookManager( $db, $config );	

	/**
	 * Language Managament (rewritten martin@2006-03-11)
	 */
	$language = new Syscp_Language( $db, 'English' );
	
	// decide which language to use as user selected language	
	if( !isset( $userinfo['language'] ) || !$language->hasLanguage( $userinfo['language']) )
	{
		if( isset( $_GET['language'] ) && $language->hasLanguage( $_GET['language'] ) )
		{
			$language->setLanguage( $_GET['language'] );
		}
		else
		{
			$language->setLanguage( $config->get('panel.standardlanguage') );
		}
	}
	else
	{
		$language->setLanguage( $userinfo['language'] );
	}
	$language->load( $db );
	
	/**
	 * Unset some no longer needed Log instances
	 */
	unset( $sysLog );
//	unset( $authLog );
	unset( $debugLog ); 
	
	/**
	 * COMPATIBILITY STUFF HERE
	 * 
	 * This part covers the upcoming changes in the API, by reflecting
	 * the new parts to the old API. 
	 */
	// the oldstyle language arrays instead of the new language object
	$lng = $language->toArray();
	
	// the navigation and template stuff
	$navigation = getNavigation($config->get('env.s'), $userinfo);
	eval("\$header = \"".getTemplate('header', '1')."\";");
	eval("\$footer = \"".getTemplate('footer', '1')."\";");		
	
	
	/**
	 * AT THIS POINT THE FOLLOWING THINGS OF SYSCP ARE SET
	 * 
	 * Variables: 
	 *   $config   : An object of Org_Syscp_Core_Config containing the envoirement and
	 *               settings of SysCP
	 *   $db       : An object of Syscp_DB_Mysql, the database abstraction layer
	 *   $idna     : An object of Org_Syscp_Core_IDNA, helper class to convert punnycode domains
	 *   $language : An object of Org_Syscp_Core_Language, providing the i18l functionality
	 *   $template : An object of PHPTAL, providing the template engine
	 *   $userinfo : An array holding the current userdata
	 *   $hooks    : An object of Org_Syscp_Core_Hooks_Manager, providing the hook api 
	 *   $log      : An object of Log, providing a logfile generator
	 *   $authLog  : An object of Log, providing authentication logs 
	 */
?>

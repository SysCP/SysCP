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
 * @copyright  (c) 2003-2006 Florian Lippert
 * @package    Org.Syscp.Core
 * @subpackage Cronscript
 * @license    GPLv2 http://files.syscp.org/misc/COPYING.txt
 * @version    $Id:cronscript.php 460 2006-04-23 15:07:49 +0200 (So, 23 Apr 2006) martin $
 * 
 * @todo Any kind of logfile is missing at the moment, consider using
 *       PEAR_Log here!
 */

// +----------------------------------------------------------------
// | FOR DEVELOPER SPECIFIC NOTES REGARDING THE DEVELOPMENT OF 
// | CRONSCRIPTS SEE THE END OF THIS FILE!
// +----------------------------------------------------------------
	error_reporting( E_ALL ); // REMOVE IN RELEASE VERSION!

// +----------------------------------------------------------------
// | GENERAL PHP PART 
// +----------------------------------------------------------------
	// Check our envoirement, if we are really in the CLI
	if(@php_sapi_name() != 'cli' && @php_sapi_name() != 'cgi' && @php_sapi_name() != 'cgi-fcgi')
	{
		die('This script will only work in the shell.');
	}

// +----------------------------------------------------------------
// | SYSCP CORE SETUP  
// +----------------------------------------------------------------
	define( 'SYSCP_LOCK_NAME', '/var/run/syscp_cron.lock' );
	define( 'SYSCP_LOCK_FILE', sprintf( '%s-%s', SYSCP_LOCK_NAME, time() ) );
	
	// guess the syscp installation path
	// normally you should not need to modify this script anymore, if your
	// syscp installation isn't in /var/www/syscp 
	$pathtophpfiles = '';
	if( substr($_SERVER['PHP_SELF'], 0, 1) != '/' )
	{ 
		$pathtophpfiles = $_SERVER['PWD'];
	}
	$pathtophpfiles .= '/'.$_SERVER['PHP_SELF'];
	$pathtophpfiles = str_replace('/./', '/', $pathtophpfiles );
	$pathtophpfiles = str_replace('//','/', $pathtophpfiles );
	$pathtophpfiles = dirname(dirname( $pathtophpfiles ));
	
	// should the syscp installation guessing not work correctly,
	// uncomment the following line, and put your path in there!
	//$pathtophpfiles = '/var/www/syscp';	

	define( 'SYSCP_PATH_BASE',  $pathtophpfiles.'/' );
	define( 'SYSCP_PATH_CLASS', sprintf('%slib/classes/', SYSCP_PATH_BASE) );
	define( 'SYSCP_PATH_PEAR',  sprintf('%slib/classes/PEAR/', SYSCP_PATH_BASE)    );
	unset( $pathtophpfiles );
		
	/**
	 * Lockfile Checking 
	 */
	// Create a new lockfile
	$debugHandler   = fopen( SYSCP_LOCK_FILE, 'w' ); 
	// Open the lockfile directory
	$lockDirHandle  = opendir( dirname(SYSCP_LOCK_FILE) );
	// And search the lockfile directory
	while ($fName = readdir( $lockDirHandle ) )
	{
		if (    SYSCP_LOCK_NAME == substr( $fName, 0, strlen(SYSCP_LOCK_NAME) ) 
		     && SYSCP_LOCK_FILE!= $fName )
		{
			// we have found an older lockfile
			// close the current lockfile
			fclose( $debugHandler );
			// ... and delete it 
			unlink( SYSCP_LOCK_FILE );		
			die('There is already a lockfile. Exiting...' . "\n" . 
			    'Take a look into the contents of the lockfile at '.dirname(SYSCP_LOCKFILE).' for more information!'."\n" );
		}
	}
	// cleanup the vars
	closedir( $lockDirHandle );
	unset( $lockDirHandle );
	unset( $fName );
	
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
	require_once SYSCP_PATH_CLASS.'Syscp/DB/Mysql.class.php';
	require_once SYSCP_PATH_CLASS.'Syscp/IDNA.class.php';
	require_once SYSCP_PATH_CLASS.'Syscp/Language.class.php';
	require_once SYSCP_PATH_CLASS.'Syscp/BaseHook.class.php';
	require_once SYSCP_PATH_CLASS.'Syscp/HookManager.class.php';
	
	require_once 'Log.php';
	/**@#-*/
	
	/**
	 * The Logmanager, used for logfile creation
	 */
	$log   = &Log::singleton('file', SYSCP_PATH_BASE.'logs/cron.log', 'Cron', array() );
	$log->info( '---> MARK <---' );
	$log->info( 'Logging started...' );
	
	/**
	 * Load and Init some the Config of SysCP
	 */
	$config = new Syscp_Config();
	// Check if we can load the base config, including the userdata
	if ( !$config->loadBaseConfig() )
	{
		$log->err( 'SysCP is not configured yet! Please configure and install SysCP before running this script.' );
		$log->err( 'Exiting without lockfile removal...' );
		die('You have to configure SysCP first! Please configure and install SysCP before running this script.');
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
	$config->set( 'env.filename', 'cronscript.php' );
	// set the current version
//	$config->set( 'env.version', $version );
//	unset( $version );
	
	/**
	 * Check if we have to call the updater
	 */
	if( $config->get('panel.version') != $config->get('env.version') )
	{
		$log->err( 'SysCP needs to be upgraded, please open the webpanel at least once!' );
		$log->err( 'Exiting without lockfile removal...' );
		die( 'You have to upgrade SysCP, please open the webpanel at least once!' );
	}
	
	/**
	 * Create some helper classes
	 */
	// the idna converter
	$idna = new Syscp_IDNA();
	// the hook manager
	$hooks = new Syscp_HookManager( $db, $config );	

	/**
	 * CORE SETUP FINISHED
	 * 
	 * At this moment we have defined: 
	 *   $idna         : The idna converter object
	 *   $config       : The config object
	 *   $db           : A database object
	 *   $debugHandler : A filehandler to the lockfile
	 *   $hooks        : The hooksmanager object
	 */
// +----------------------------------------------------------------
// | ACTUAL CRONSCRIPT WORK
// +----------------------------------------------------------------
	// we init some vars we really need
	$idList  = array(); // list of processed task id's	
	$classes = array(); // list of instanciated classes


	// We start to fetch all the tasks which are sheduled
	$query  = 'SELECT * FROM `%s` ORDER BY `id` ASC';
	$query  = sprintf( $query, TABLE_PANEL_TASKS );
	$result = $db->query( $query );
	
	while( false !== ($row = $db->fetch_array( $result ) ) ) 
	{
		// we need to reparse the params first
		$row['params'] = unserialize( urldecode( $row['params'] ) );
		// and put the values into short vars, to make the following more readable
		$file   = $row['file'];
		$class  = $row['class'];
		$method = $row['method'];
		$params = $row['params'];
		// now we need to check if the requested class is already instanciated
		if (    !isset( $classes[$class] ) 
		     && file_exists( SYSCP_PATH_BASE.$file ) )
		{
			$log->info( sprintf( 'Instanciating class %s', $class ) );
			// it is not, lets load it and instanciate
			require_once SYSCP_PATH_BASE.$file;
			// instanciate
			$classes[$class] = new $class();
			// init the hook
			$classes[$class]->initialize( $db, $config, $hooks, $log );
		}
		
		// lets check if the class we need to have exists now
		if( isset( $classes[$class] ) )
		{
			// the class exists, lets call the requested method
			$log->info( sprintf('Executing %s::%s', $class, $method ) );
			$classes[$class]->$method( $params );
			
			// and remove the scheduled function call
			$log->info( sprintf( 'Removing %s::%s from schedule list', $class, $method ) );
			$query = 'DELETE FROM `%s` WHERE `id`=\'%s\'';
			$query = sprintf( $query, TABLE_PANEL_TASKS, $row['id'] );
			$db->query( $query );
		}
		else 
		{
			$error = 'The scheduled function %s::%s in file %s cannot be found!';
			$error = sprintf( $error, $class, $method, $file );
			$log->err( $error );
			$log->err( 'Exiting without lockfile removal...' );
			die($error);
		}
		// cleanup - to have a clean context for next run
		unset( $file );
		unset( $class );
		unset( $method );
		unset( $params );
	}
	
	// close the current lockfile
	fclose( $debugHandler );
	// ... and delete it 
	unlink( SYSCP_LOCK_FILE );
	$log->log( 'Removing lockfile and exiting...' );
	exit(0);
	
/**********************************************************************
 * DEVELOPER NOTES
 * ----------------
 * During the run of the cronscript you have the $hooks object, if you
 * need to have a script called each cron run, you should simply 
 * reschedule your script at the end of it's call. 
 *
 **********************************************************************/
?>
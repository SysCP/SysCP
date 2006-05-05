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
 * @subpackage Panel
 * @license    GPLv2 http://files.syscp.org/misc/COPYING.txt
 * @version    $Id:index.php 460 2006-04-23 15:07:49 +0200 (So, 23 Apr 2006) martin $
 * 
 * @todo this file should be refactored towards a frontcontroller solution, having release 1.4 in mind
 */

	/**
	 * defines the context within this scripts is running
	 */
	define('AREA', 'login');

	/**
	 * Include our init.php, which manages Sessions, Language etc.
	 */
	require_once '../lib/init.php';

	if( isset($_POST['send']) && $_POST['send']=='send' )
	{
		/**
		 * Init Vars
		 */
		$query     = '';       // string: for queries
		$row       = array();  // array: for results
		$userTable = '';       // string: the table where the userrecord can be found
		$uidField  = '';       // string: name of uid field in the table
		
		/**
		 * Fetch Form Data
		 */
		$loginname   = addslashes($_POST['loginname']);
		$password    = addslashes($_POST['password']);
		$curLanguage = addslashes( htmlentities( _html_entity_decode( $_POST['language'] ) ) ) ;
		/**
		 * Prepare Form Data
		 */
		$password = md5($password); // our passes are md5 encrypted, lets encrypt the form pass
		
		/**
		 * Process Form Data
		 */
		// try to find a customer
		$query = 'SELECT * FROM `%s` WHERE `loginname` = \'%s\' ';
		$query = sprintf( $query, TABLE_PANEL_CUSTOMERS, $loginname );
		$row   = $db->query_first( $query ); // returns false if no record is found!
		if( $row != false )
		{
			$userTable    = TABLE_PANEL_CUSTOMERS;
			$uidField     = 'customerid';
		}
		else
		{
			// no customer found, lets take a look for an admin
			$query = 'SELECT * FROM `%s` WHERE `loginname` = \'%s\' ';
			$query = sprintf( $query, TABLE_PANEL_ADMINS, $loginname );
			$row   = $db->query_first( $query );  // returns false if no record is found!
			if( $row != false )
			{
				$userTable    = TABLE_PANEL_ADMINS;
				$uidField     = 'adminid';
			}
			else
			{
				// we don't have an admin, so no user is found!
				$authLog->err( sprintf( 'Login failed: User %s unkown from %s',
				                        $loginname, $config->get('env.remote_addr') ) );
				standard_error('login');
				exit;
			}
		}

		// at this point we have row which contains the userrecord of the current user
		
		// we next check if the user is allowed to login, or if his account is blocked.
		// we are doing this check BEFORE we check if the supplied password was correct!
		if ( $row['loginfail_count'] >= $config->get('login.maxloginattempts') 
		     && $row['lastlogin_fail'] > (time()-$config->get('login.deactivatetime')) )		
		{
			// the user tried to login too often with a wrong password, and his last try 
			// was not before the timerange of login.deactivatetime, his account is blocked!
			$authLog->err( sprintf('User %s tried to login but is blocked.', $row['loginname']) );
			standard_error('login_blocked', $config->get('login.deactivatetime')); 
			exit;
		}
		
		// now we check if the password supplied in the form equals the password in the 
		// userrecord. 
		if ( $row['password'] == $password )
		{
			// the passwords match, lets reset the loginfail counter and set lastlogin_success
			$query = 
				'UPDATE `%s` ' .
				'SET `lastlogin_succ`  = \'%s\', '.
				'    `loginfail_count` = \'0\' '.
				'WHERE `%s` = \'%s\' ';
			$query = sprintf( $query, $userTable, time(), $uidField, $row[$uidField] );
			$db->query( $query );
			
			// put the actual userid into $row['userid']
			$row['userid'] = $row[$uidField];
			
			// put a flag into the userrecord if this is an adminsession
			if ( $userTable == TABLE_PANEL_ADMINS )
			{
				$row['adminsession'] = 1;
			}
			else 
			{
				$row['adminsession'] = 0;
			}
		}
		else 
		{
			// the passwords don't match, we update the `lastlogin_fail` and `loginfail_count`
			// fields in the database
			$query = 
				'UPDATE `%s` ' .
				'SET `lastlogin_fail`  = \'%s\', ' .
				'    `loginfail_count` = `loginfail_count` + 1 ' .
				'WHERE `%s` = \'%s\'';
			$query = sprintf( $query, $userTable, time(), $uidField, $row[$uidField] );
			$db->query( $query );
			$authLog->err( sprintf( 'User %s wrong password from %s', 
			                          $row['loginname'], 
			                          $config->get('env.remote_addr') ) );
		}

		// we continue with the session stuff, but only if the user if valid
		// @todo: Consider if this check is really necessary, or if every possible error has
		//        already been catched. 
		if ( isset($row['userid']) && $row['userid'] != '' )
		{
			// create session id
			$config->set('env.s', md5(uniqid(microtime(),1)));
			
			// decide which language to use in this session
			if( $curLanguage == 'profile' )
			{
				$curLanguage = $row['def_language'];
			}

			session_id( $config->get('env.s') );
			session_start();
			
			// register userinfo in session
			$_SESSION['userid']       = $row['userid'];
			$_SESSION['adminsession'] = $row['adminsession'];
			$_SESSION['language']     = $curLanguage;
			// delete all old session for this user
//			$query = 'DELETE FROM `%s` WHERE `userid` = \'%s\' AND `adminsession` = \'%s\' ';
//			$query = sprintf( $query, TABLE_PANEL_SESSIONS, $row['userid'], $row['adminsession'] );
//			$db->query( $query );
			
			// create the new session for this user
//			$query = 
//				'INSERT INTO `%s` ' .
//				'SET `hash`         = \'%s\', ' .
//				'    `userid`       = \'%s\', ' .
//				'    `ipaddress`    = \'%s\', ' .
//				'    `useragent`    = \'%s\', ' .
//				'    `lastactivity` = \'%s\', ' .
//				'    `language`     = \'%s\', ' .
//				'    `adminsession` = \'%s\' ';
//			$query = sprintf( $query, TABLE_PANEL_SESSIONS, $config->get('env.s'), 
//			                          $row['userid'], addslashes($config->get('env.remote_addr')),
//			                          addslashes($config->get('env.http_user_agent')), 
//			                          time(), $curLanguage, $row['adminsession'] );
//			$db->query( $query );
			$authLog->info( sprintf( 'User %s logged in successfully from %s', 
			                         $row['loginname'],
			                         $config->get('env.remote_addr') ) );
			// and create the new logfile instance for this user
			$log = &Log::factory( 'file', SYSCP_PATH_BASE.'logs/user-'.$row['loginname'].'.log', $row['loginname'] );
			$log->info( sprintf( 'User %s logged in successfully from %s', 
			                         $row['loginname'],
			                         $config->get('env.remote_addr') ) );
			
			// forward to the new action
			if( $row['adminsession'] == '1' )
			{
				redirectTo( 'admin_index.php', array('s' => $config->get('env.s')) );
			}
			else 
			{
				redirectTo( 'customer_index.php', array('s' => $config->get('env.s')) );
			}
		}
		else
		{
			standard_error('login');
			exit;
		}
	}
	else
	{
		$lng = $language->toArray();
		$language_options = '';
		$language_options .= makeoption($lng['login']['profile_lng'], 'profile', 'profile');
		$languages = $language->getList();
		while(list($language_file, $language_name) = each($languages))
		{
			$language_options .= makeoption($language_name, $language_file, 'profile');
		}
		eval("echo \"".getTemplate("login")."\";");
	}
	

?>

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
 * @package    Syscp.Modules
 * @subpackage Login
 * @license    GPLv2 http://files.syscp.org/misc/COPYING.txt
 * @version    $Id:index.php 460 2006-04-23 15:07:49 +0200 (So, 23 Apr 2006) martin $
 *
 * @todo this file should be refactored towards a frontcontroller solution, having release 1.4 in mind
 */

	// DON'T FORGET THIS SCRIPT RUNS IN THE CONTEXT OF THE FRONTCONTROLLER.
	// We can use all FrontController privates here. $this is the instance of the FrontController.

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
		$curTheme    = addslashes( htmlentities( _html_entity_decode( $_POST['theme'] ) ) );
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
		$row   = $this->DatabaseHandler->queryFirst($query); // returns false if no record is found!
		if($row != false)
		{
			$userTable    = TABLE_PANEL_CUSTOMERS;
			$uidField     = 'customerid';
		}
		else
		{
			// no customer found, lets take a look for an admin
			$query = 'SELECT * FROM `%s` WHERE `loginname` = \'%s\' ';
			$query = sprintf( $query, TABLE_PANEL_ADMINS, $loginname );
			$row   = $this->DatabaseHandler->queryFirst($query);  // returns false if no record is found!
			if( $row != false )
			{
				$userTable    = TABLE_PANEL_ADMINS;
				$uidField     = 'adminid';
			}
			else
			{
				// we don't have an admin, so no user is found!
				// log this to the auth log facility
				$this->LogHandler->warning( Syscp_Handler_Log_Interface::FACILITY_AUTH,
				                            sprintf('Login failed: User %s unkown from %s',
				                                    $loginname,
				                                    $this->ConfigHandler->get('env.remote_addr')));
				$this->TemplateHandler->showError('login');
				return false;
			}
		}

		// at this point we have row which contains the userrecord of the current user

		// we next check if the user is allowed to login, or if his account is blocked.
		// we are doing this check BEFORE we check if the supplied password was correct!
		if ($row['loginfail_count'] >= $this->ConfigHandler->get('login.maxloginattempts') &&
		    $row['lastlogin_fail'] > (time()-$this->ConfigHandler->get('login.deactivatetime')))
		{
			// the user tried to login too often with a wrong password, and his last try
			// was not before the timerange of login.deactivatetime, his account is blocked!
			$this->LogHandler->warning( Syscp_Handler_Log_Interface::FACILITY_AUTH,
			                            sprintf('User %s tried to login but is blocked.',
			                                    $row['loginname']) );
			$this->TemplateHandler->showError('login_blocked', $config->get('login.deactivatetime'));
			return false;
		}

		// now we check if the password supplied in the form equals the password in the
		// userrecord.
		if ($row['password'] == $password)
		{
			// the passwords match, lets reset the loginfail counter and set lastlogin_success
			$query =
				'UPDATE `%s` ' .
				'SET `lastlogin_succ`  = \'%s\', '.
				'    `loginfail_count` = \'0\' '.
				'WHERE `%s` = \'%s\' ';
			$query = sprintf( $query, $userTable, time(), $uidField, $row[$uidField] );
			$this->DatabaseHandler->query($query);

			// put the actual userid into $row['userid']
			$row['userid'] = $row[$uidField];

			// put a flag into the userrecord if this is an adminsession
			if ($userTable == TABLE_PANEL_ADMINS)
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
			$this->DatabaseHandler->query($query);
			$this->LogHandler->warning(Syscp_Handler_Log_Interface::FACILITY_AUTH,
			                           sprintf('User %s wrong password from %s',
			                                   $row['loginname'],
			                                   $this->ConfigHandler->get('env.remote_addr')));
		}

		// we continue with the session stuff, but only if the user if valid
		/**
		 *  @todo: Consider if this check is really necessary, or if every possible error has
		 *         already been catched.
		 */
		if (isset($row['userid']) && $row['userid'] != '')
		{
			// create session id
			/**
			 * @todo Remove all requests to env.s it's superseeded by session_id()
			 */
			$this->ConfigHandler->set('env.s', md5(uniqid(microtime(),1)));

			// decide which language to use in this session
			if($curLanguage == 'profile')
			{
				$curLanguage = $row['def_language'];
			}

			session_id($this->ConfigHandler->get('env.s'));
			session_start();

			// register userinfo in session
			$_SESSION['userid']       = $row['userid'];
			$_SESSION['adminsession'] = $row['adminsession'];
			$_SESSION['language']     = $curLanguage;
			$_SESSION['theme']        = $curTheme;

			$this->LogHandler->info(Syscp_Handler_Log_Interface::FACILITY_AUTH,
			                        sprintf('User %s logged in successfully from %s',
			                                $row['loginname'],
			                                $this->ConfigHandler->get('env.remote_addr') ) );
			// and create the new logfile instance for this user
			$this->LogHandler->setUsername($row['loginname']);
			$this->LogHandler->info(Syscp_Handler_Log_Interface::FACILITY_USER,
			                        sprintf('User %s logged in successfully from %s',
			                                $row['loginname'],
			                                $this->ConfigHandler->get('env.remote_addr') ) );

			// forward to the new action
			if( $row['adminsession'] == '1' )
			{
				$this->redirectTo(array('module'=>'index'));
			}
			else
			{
				$this->redirectTo(array('module'=>'index'));
			}
		}
		else
		{
			$this->TemplateHandler->showError('login');
			return false;
		}
	}
	else
	{
		// generate theme list
		$dh = opendir(SYSCP_PATH_LIB.'themes/');
		$themes = array();
		while(false !== ($dir = readdir($dh)))
		{
			if($dir != '.' && $dir != '..')
			{
				$themes[$dir] = $dir;
			}
		}
		closedir($dh);

		$languageList = $this->L10nHandler->getLanguageList();
		$languageList['profile'] = $this->L10nHandler->get('login.profile_lng');
		$this->TemplateHandler->set('lang_list', $languageList);
		$this->TemplateHandler->set('lang_sel', 'profile');
		$this->TemplateHandler->set('theme_list', $themes);
		$this->TemplateHandler->set('theme_sel', self::DEFAULT_THEME);
		$this->TemplateHandler->setTemplate('SysCP/login/login/login.tpl');
	}

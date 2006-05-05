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
 * @subpackage Panel.Customer
 * @license    GPLv2 http://files.syscp.org/misc/COPYING.txt
 * @version    $Id$
 * 
 * @todo code cleanup is needed, exspecially in queries, loops, conditions
 * @todo code restructuring is needed, we need to seperate view, controller and model logic,
 *       as a preparation towards MVC patterns
 */


	define('AREA', 'customer');

	/**
	 * Include our init.php, which manages Sessions, Language etc.
	 */
	require_once '../lib/init.php';


	if($config->get('env.page')=='overview')
	{
		eval("echo \"".getTemplate("mysql/mysql")."\";");
	}

	elseif($config->get('env.page')=='mysqls')
	{
		if($config->get('env.action')=='')
		{
			$result=$db->query( "SELECT `id`, `databasename`, `description` FROM `" . TABLE_PANEL_DATABASES . "` WHERE `customerid`='" . $userinfo['customerid'] . "' ORDER BY `databasename` ASC" );
			$mysqls='';
			while($row=$db->fetch_array($result))
			{
				eval("\$mysqls.=\"".getTemplate("mysql/mysqls_database")."\";");
			}
			$mysqls_count = $db->num_rows($result);

			eval("echo \"".getTemplate("mysql/mysqls")."\";");
		}

		elseif($config->get('env.action')=='delete' && $config->get('env.id')!=0)
		{
			$result=$db->query_first( 'SELECT `id`, `databasename` FROM `' . TABLE_PANEL_DATABASES . '` WHERE `customerid`="' . $userinfo['customerid'] . '" AND `id`="' . $config->get('env.id') . '"' );
			if(isset($result['databasename']) && $result['databasename'] != '')
			{
				if(isset($_POST['send']) && $_POST['send']=='send')
				{
					// Begin root-session
					$db_root = new Syscp_DB_Mysql( $config->get('sql.host'),
					                                        $config->get('sql.root.user'),
					                                        $config->get('sql.root.password'),
					                                        '' );
					unset($db_root->password);

					$db_root->query( 'REVOKE ALL PRIVILEGES ON * . * FROM `' . $result['databasename'] . '`@' . $config->get('system.mysql_access_host') . ';' );
					$db_root->query( 'REVOKE ALL PRIVILEGES ON `' . str_replace ( '_' , '\_' , $result['databasename'] ) . '` . * FROM `' . $result['databasename'] . '`@' . $config->get('system.mysql_access_host') . ';' );
					$db_root->query( 'DELETE FROM `mysql`.`user` WHERE `User` = "' . $result['databasename'] . '" AND `Host` = "' . $config->get('system.mysql_access_host') . '"' );
					$db_root->query( 'DROP DATABASE IF EXISTS `' . $result['databasename'] . '`' );
					$db_root->query( 'FLUSH PRIVILEGES' );

					$db_root->close();
					// End root-session
	
					$db->query( 'DELETE FROM `' . TABLE_PANEL_DATABASES . '` WHERE `customerid`="' . $userinfo['customerid'] . '" AND `id`="' . $config->get('env.id') . '"' );

					if($userinfo['mysqls_used']=='1')
					{
						$resetaccnumber=" , `mysql_lastaccountnumber`='0' ";
					}
					else
					{
						$resetaccnumber='';
					}

					$result=$db->query( 'UPDATE `' . TABLE_PANEL_CUSTOMERS . '` SET `mysqls_used`=`mysqls_used`-1 ' . $resetaccnumber . 'WHERE `customerid`="' . $userinfo['customerid'] .'"' );

    				redirectTo ( $config->get('env.filename') , Array ( 'page' => $config->get('env.page') , 's' => $config->get('env.s') ) ) ;
				}
				else 
				{
					ask_yesno('mysql_reallydelete', $config->get('env.filename'), "id=".$config->get('env.id').";page=".$config->get('env.page').";action=".$config->get('env.action'), $result['databasename']);
				}
			}
		}

		elseif($config->get('env.action')=='add')
		{
			if($userinfo['mysqls_used'] < $userinfo['mysqls'] || $userinfo['mysqls'] == '-1')
			{
				if(isset($_POST['send']) && $_POST['send']=='send')
				{
					$password=addslashes($_POST['password']);
					if($password=='')
					{
						standard_error(array('stringisempty','mypassword'));
					}
					else
					{
						$username=$userinfo['loginname'].$config->get('customer.mysqlprefix').(intval($userinfo['mysql_lastaccountnumber'])+1);

						// Begin root-session
						$db_root = new Syscp_DB_Mysql( $config->get('sql.host'),
						                                        $config->get('sql.root.user'),
						                                        $config->get('sql.root.password'),
						                                        '' );
						unset($db_root->password);

						$db_root->query( 'CREATE DATABASE `' . $username . '`' );
						$db_root->query( 'GRANT ALL PRIVILEGES ON `' . str_replace ( '_' , '\_' , $username ) . '`.* TO `' . $username . '`@' . $config->get('system.mysql_access_host') . ' IDENTIFIED BY \'password\'' );
						$db_root->query( 'SET PASSWORD FOR `' . $username .'`@' . $config->get('system.mysql_access_host') . ' = PASSWORD(\'' . $password . '\')' );
						$db_root->query( 'FLUSH PRIVILEGES' );

						$db_root->close();
						// End root-session

						// Statement modifyed for Database description -- PH 2004-11-29
						$databasedescription=addslashes($_POST['description']);
						$result=$db->query( 'INSERT INTO `' . TABLE_PANEL_DATABASES . '` (`customerid`, `databasename`, `description`) VALUES ("' . $userinfo['customerid'] .'", "' . $username .'", "' . $databasedescription .'")' );
						$result=$db->query( 'UPDATE `' . TABLE_PANEL_CUSTOMERS . '` SET `mysqls_used`=`mysqls_used`+1, `mysql_lastaccountnumber`=`mysql_lastaccountnumber`+1 WHERE `customerid`="' . $userinfo['customerid'] . '"' );

        				redirectTo ( $config->get('env.filename') , Array ( 'page' => $config->get('env.page') , 's' => $config->get('env.s') ) ) ;
					}
				}
				else 
				{
					eval("echo \"".getTemplate("mysql/mysqls_add")."\";");
				}
			}
		}

		elseif($config->get('env.action')=='edit' && $config->get('env.id')!=0)
		{
			$result=$db->query_first( 'SELECT `id`, `databasename`, `description` FROM `' . TABLE_PANEL_DATABASES . '` WHERE `customerid`="' . $userinfo['customerid'] . '" AND `id`="' . $config->get('env.id') . '"' );
			if(isset($result['databasename']) && $result['databasename'] != '')
			{
				if(isset($_POST['send']) && $_POST['send']=='send')
				{
					// Only change Password if it is set, do nothing if it is empty! -- PH 2004-11-29
					$password=addslashes($_POST['password']);
					if($password!='')
					{
						// Begin root-session
						$db_root = new Syscp_DB_Mysql( $config->get('sql.host'),
						                                        $config->get('sql.root.user'),
						                                        $config->get('sql.root.password'),
						                                        '' );
						unset($db_root->password);

						$db_root->query('SET PASSWORD FOR `'.$result['databasename'].'`@' . $config->get('system.mysql_access_host') . ' = PASSWORD(\'' . $password .'\')');
						$db_root->query('FLUSH PRIVILEGES');

						$db_root->close();
						// End root-session
					}

					// Update the Database description -- PH 2004-11-29
					$databasedescription=addslashes($_POST['description']);
					$result=$db->query( 'UPDATE `' . TABLE_PANEL_DATABASES . '` SET `description`="' . $databasedescription . '" WHERE `customerid`="' . $userinfo['customerid'] . '" AND `id`="' . $config->get('env.id') . '"');

        			redirectTo ( $config->get('env.filename') , Array ( 'page' => $config->get('env.page') , 's' => $config->get('env.s') ) ) ;
				}
				else 
				{
					eval("echo \"".getTemplate("mysql/mysqls_edit")."\";");
				}
			}
		}
	}

?>
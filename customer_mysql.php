<?php
/**
 * filename: $Source$
 * begin: Friday, Aug 06, 2004
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version. This program is distributed in the
 * hope that it will be useful, but WITHOUT ANY WARRANTY; without even the
 * implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU General Public License for more details.
 *
 * @author Florian Lippert <flo@redenswert.de>
 * @copyright (C) 2003-2004 Florian Lippert
 * @package Panel
 * @version $Id$
 */

	define('AREA', 'customer');

	/**
	 * Include our init.php, which manages Sessions, Language etc.
	 */
	require("./lib/init.php");

	if(isset($_POST['id']))
	{
		$id=intval($_POST['id']);
	}
	elseif(isset($_GET['id']))
	{
		$id=intval($_GET['id']);
	}

	if($page=='overview')
	{
		eval("echo \"".getTemplate("mysql/mysql")."\";");
	}

	elseif($page=='mysqls')
	{
		if($action=='')
		{
			$result=$db->query( "SELECT `id`, `databasename`, `description` FROM `" . TABLE_PANEL_DATABASES . "` WHERE `customerid`='" . $userinfo['customerid'] . "' ORDER BY `databasename` ASC" );
			$mysqls='';
			while($row=$db->fetch_array($result))
			{
				$row = htmlentities_array( $row );
				eval("\$mysqls.=\"".getTemplate("mysql/mysqls_database")."\";");
			}
			$mysqls_count = $db->num_rows($result);

			eval("echo \"".getTemplate("mysql/mysqls")."\";");
		}

		elseif($action=='delete' && $id!=0)
		{
			$result=$db->query_first( 'SELECT `id`, `databasename` FROM `' . TABLE_PANEL_DATABASES . '` WHERE `customerid`="' . $userinfo['customerid'] . '" AND `id`="' . $id . '"' );
			if(isset($result['databasename']) && $result['databasename'] != '')
			{
				if(isset($_POST['send']) && $_POST['send']=='send')
				{
					// Begin root-session
					$db_root=new db($sql['host'],$sql['root_user'],$sql['root_password'],'');
					unset($db_root->password);

					$db_root->query( 'REVOKE ALL PRIVILEGES ON * . * FROM `' . $result['databasename'] . '`@' . $settings['system']['mysql_access_host'] . ';' );
					$db_root->query( 'REVOKE ALL PRIVILEGES ON `' . str_replace ( '_' , '\_' , $result['databasename'] ) . '` . * FROM `' . $result['databasename'] . '`@' . $settings['system']['mysql_access_host'] . ';' );
					$db_root->query( 'DELETE FROM `mysql`.`user` WHERE `User` = "' . $result['databasename'] . '" AND `Host` = "' . $settings['system']['mysql_access_host'] . '"' );
					$db_root->query( 'DROP DATABASE IF EXISTS `' . $result['databasename'] . '`' );
					$db_root->query( 'FLUSH PRIVILEGES' );

					$db_root->close();
					// End root-session
	
					$db->query( 'DELETE FROM `' . TABLE_PANEL_DATABASES . '` WHERE `customerid`="' . $userinfo['customerid'] . '" AND `id`="' . $id . '"' );

					if($userinfo['mysqls_used']=='1')
					{
						$resetaccnumber=" , `mysql_lastaccountnumber`='0' ";
					}
					else
					{
						$resetaccnumber='';
					}

					$result=$db->query( 'UPDATE `' . TABLE_PANEL_CUSTOMERS . '` SET `mysqls_used`=`mysqls_used`-1 ' . $resetaccnumber . 'WHERE `customerid`="' . $userinfo['customerid'] .'"' );

    				redirectTo ( $filename , Array ( 'page' => $page , 's' => $s ) ) ;
				}
				else 
				{
					ask_yesno('mysql_reallydelete', $filename, array( 'id' => $id, 'page' => $page, 'action' => $action ), $result['databasename']);
				}
			}
		}

		elseif($action=='add')
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
						$username=$userinfo['loginname'].$settings['customer']['mysqlprefix'].(intval($userinfo['mysql_lastaccountnumber'])+1);

						// Begin root-session
						$db_root=new db($sql['host'],$sql['root_user'],$sql['root_password'],'');
						unset($db_root->password);

						$db_root->query( 'CREATE DATABASE `' . $username . '`' );
						$db_root->query( 'GRANT ALL PRIVILEGES ON `' . str_replace ( '_' , '\_' , $username ) . '`.* TO `' . $username . '`@' . $settings['system']['mysql_access_host'] . ' IDENTIFIED BY \'password\'' );
						$db_root->query( 'SET PASSWORD FOR `' . $username .'`@' . $settings['system']['mysql_access_host'] . ' = PASSWORD(\'' . $password . '\')' );
						$db_root->query( 'FLUSH PRIVILEGES' );

						$db_root->close();
						// End root-session

						// Statement modifyed for Database description -- PH 2004-11-29
						$databasedescription=addslashes($_POST['description']);
						$result=$db->query( 'INSERT INTO `' . TABLE_PANEL_DATABASES . '` (`customerid`, `databasename`, `description`) VALUES ("' . $userinfo['customerid'] .'", "' . $username .'", "' . $databasedescription .'")' );
						$result=$db->query( 'UPDATE `' . TABLE_PANEL_CUSTOMERS . '` SET `mysqls_used`=`mysqls_used`+1, `mysql_lastaccountnumber`=`mysql_lastaccountnumber`+1 WHERE `customerid`="' . $userinfo['customerid'] . '"' );

        				redirectTo ( $filename , Array ( 'page' => $page , 's' => $s ) ) ;
					}
				}
				else 
				{
					eval("echo \"".getTemplate("mysql/mysqls_add")."\";");
				}
			}
		}

		elseif($action=='edit' && $id!=0)
		{
			$result=$db->query_first( 'SELECT `id`, `databasename`, `description` FROM `' . TABLE_PANEL_DATABASES . '` WHERE `customerid`="' . $userinfo['customerid'] . '" AND `id`="' . $id . '"' );
			if(isset($result['databasename']) && $result['databasename'] != '')
			{
				if(isset($_POST['send']) && $_POST['send']=='send')
				{
					// Only change Password if it is set, do nothing if it is empty! -- PH 2004-11-29
					$password=addslashes($_POST['password']);
					if($password!='')
					{
						// Begin root-session
						$db_root=new db($sql['host'],$sql['root_user'],$sql['root_password'],'');
						unset($db_root->password);

						$db_root->query('SET PASSWORD FOR `'.$result['databasename'].'`@' . $settings['system']['mysql_access_host'] . ' = PASSWORD(\'' . $password .'\')');
						$db_root->query('FLUSH PRIVILEGES');

						$db_root->close();
						// End root-session
					}

					// Update the Database description -- PH 2004-11-29
					$databasedescription=addslashes($_POST['description']);
					$result=$db->query( 'UPDATE `' . TABLE_PANEL_DATABASES . '` SET `description`="' . $databasedescription . '" WHERE `customerid`="' . $userinfo['customerid'] . '" AND `id`="' . $id . '"');

        			redirectTo ( $filename , Array ( 'page' => $page , 's' => $s ) ) ;
				}
				else 
				{
					eval("echo \"".getTemplate("mysql/mysqls_edit")."\";");
				}
			}
		}
	}

?>
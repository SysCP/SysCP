<?php
/**
 * This file is part of the SysCP project.
 * Copyright (c) 2003-2006 the SysCP Team (see authors).
 *
 * For the full copyright and license information, please view the COPYING
 * file that was distributed with this source code. You can also view the
 * COPYING file online at http://files.syscp.org/misc/COPYING.txt
 *
 * @copyright  (c) the authors
 * @author     Florian Lippert <flo@syscp.org>
 * @license    GPLv2 http://files.syscp.org/misc/COPYING.txt
 * @package    Panel
 * @version    $Id$
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
		$lng['mysql']['description'] = str_replace( '<SQL_HOST>', $sql['host'], $lng['mysql']['description'] );
		eval("echo \"".getTemplate("mysql/mysql")."\";");
	}

	elseif($page=='mysqls')
	{
		if($action=='')
		{
			$fields = array(
								'databasename' => $lng['mysql']['databasename'],
								'description' => $lng['mysql']['databasedescription']
							);
			$paging = new paging( $userinfo, $db, TABLE_PANEL_DATABASES, $fields, $settings['panel']['paging'] );

			$result=$db->query(
				"SELECT `id`, `databasename`, `description` FROM `" . TABLE_PANEL_DATABASES . "` WHERE `customerid`='" . (int)$userinfo['customerid'] . "' " . 
				$paging->getSqlWhere( true )." ".$paging->getSqlOrderBy()." ".$paging->getSqlLimit()
			);
			$paging->setEntries( $db->num_rows($result) );

			$sortcode = $paging->getHtmlSortCode( $lng );
			$arrowcode = $paging->getHtmlArrowCode( $filename . '?page=' . $page . '&s=' . $s );
			$searchcode = $paging->getHtmlSearchCode( $lng );
			$pagingcode = $paging->getHtmlPagingCode( $filename . '?page=' . $page . '&s=' . $s );

			$i = 0;
			$count = 0;
			$mysqls='';
			while($row=$db->fetch_array($result))
			{
				if( $paging->checkDisplay( $i ) )
				{
					$row = htmlentities_array( $row );
					eval("\$mysqls.=\"".getTemplate("mysql/mysqls_database")."\";");
					$count++;
				}
				$i++;
			}
			$mysqls_count = $db->num_rows($result);

			eval("echo \"".getTemplate("mysql/mysqls")."\";");
		}

		elseif($action=='delete' && $id!=0)
		{
			$result=$db->query_first( 'SELECT `id`, `databasename` FROM `' . TABLE_PANEL_DATABASES . '` WHERE `customerid`="' . (int)$userinfo['customerid'] . '" AND `id`="' . (int)$id . '"' );
			if(isset($result['databasename']) && $result['databasename'] != '')
			{
				if(isset($_POST['send']) && $_POST['send']=='send')
				{
					// Begin root-session
					$db_root=new db($sql['host'],$sql['root_user'],$sql['root_password'],'');
					unset($db_root->password);

					$db_root->query( 'REVOKE ALL PRIVILEGES ON * . * FROM `' . $db_root->escape($result['databasename']) . '`@' . $db_root->escape($settings['system']['mysql_access_host']));
					$db_root->query( 'REVOKE ALL PRIVILEGES ON `' . str_replace ( '_' , '\_' , $db_root->escape($result['databasename']) ) . '` . * FROM `' . $db_root->escape($result['databasename']) . '`@' . $db_root->escape($settings['system']['mysql_access_host']));
					$db_root->query( 'DELETE FROM `mysql`.`user` WHERE `User` = "' . $db_root->escape($result['databasename']) . '" AND `Host` = "' . $db_root->escape($settings['system']['mysql_access_host']) . '"' );
					$db_root->query( 'DROP DATABASE IF EXISTS `' . $db_root->escape($result['databasename']) . '`' );
					$db_root->query( 'FLUSH PRIVILEGES' );

					$db_root->close();
					// End root-session
	
					$db->query( 'DELETE FROM `' . TABLE_PANEL_DATABASES . '` WHERE `customerid`="' . (int)$userinfo['customerid'] . '" AND `id`="' . (int)$id . '"' );

					if($userinfo['mysqls_used']=='1')
					{
						$resetaccnumber=" , `mysql_lastaccountnumber`='0' ";
					}
					else
					{
						$resetaccnumber='';
					}

					$result=$db->query( 'UPDATE `' . TABLE_PANEL_CUSTOMERS . '` SET `mysqls_used`=`mysqls_used`-1 ' . $resetaccnumber . 'WHERE `customerid`="' . (int)$userinfo['customerid'] .'"' );

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
					$password=validate($_POST['password'], 'password');
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

						$db_root->query( 'CREATE DATABASE `' . $db_root->escape($username) . '`' );
						$db_root->query( 'GRANT ALL PRIVILEGES ON `' . str_replace ( '_' , '\_' , $db_root->escape($username) ) . '`.* TO `' . $db_root->escape($username) . '`@' . $db_root->escape($settings['system']['mysql_access_host']) . ' IDENTIFIED BY \'password\'' );
						$db_root->query( 'SET PASSWORD FOR `' . $db_root->escape($username) .'`@' . $db_root->escape($settings['system']['mysql_access_host']) . ' = PASSWORD(\'' . $db_root->escape($password) . '\')' );
						$db_root->query( 'FLUSH PRIVILEGES' );

						$db_root->close();
						// End root-session

						// Statement modifyed for Database description -- PH 2004-11-29
						$databasedescription=validate($_POST['description'], 'description');
						$result=$db->query( 'INSERT INTO `' . TABLE_PANEL_DATABASES . '` (`customerid`, `databasename`, `description`) VALUES ("' . (int)$userinfo['customerid'] .'", "' . $db->escape($username) .'", "' . $db->escape($databasedescription) .'")' );
						$result=$db->query( 'UPDATE `' . TABLE_PANEL_CUSTOMERS . '` SET `mysqls_used`=`mysqls_used`+1, `mysql_lastaccountnumber`=`mysql_lastaccountnumber`+1 WHERE `customerid`="' . (int)$userinfo['customerid'] . '"' );

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
					$password=validate($_POST['password'], 'password');
					if($password!='')
					{
						// Begin root-session
						$db_root=new db($sql['host'],$sql['root_user'],$sql['root_password'],'');
						unset($db_root->password);

						$db_root->query('SET PASSWORD FOR `'.$db_root->escape($result['databasename']).'`@' . $db_root->escape($settings['system']['mysql_access_host']) . ' = PASSWORD(\'' . $db_root->escape($password) .'\')');
						$db_root->query('FLUSH PRIVILEGES');

						$db_root->close();
						// End root-session
					}

					// Update the Database description -- PH 2004-11-29
					$databasedescription=validate($_POST['description'], 'description');
					$result=$db->query( 'UPDATE `' . TABLE_PANEL_DATABASES . '` SET `description`="' . $db->escape($databasedescription) . '" WHERE `customerid`="' . (int)$userinfo['customerid'] . '" AND `id`="' . (int)$id . '"');

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
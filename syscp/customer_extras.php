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
		eval("echo \"".getTemplate("extras/extras")."\";");
	}
	elseif($page=='htpasswds')
	{
		if($action=='')
		{
			$fields = array(
								'username' => $lng['login']['username'],
								'path' => $lng['panel']['path']
							);
			$paging = new paging( $userinfo, $db, TABLE_PANEL_HTPASSWDS, $fields, $settings['panel']['paging'] );

			$result=$db->query(
				"SELECT `id`, `username`, `path` FROM `".TABLE_PANEL_HTPASSWDS."` WHERE `customerid`='".(int)$userinfo['customerid']."' " . 
				$paging->getSqlWhere( true )." ".$paging->getSqlOrderBy()." ".$paging->getSqlLimit()
			);
			$paging->setEntries( $db->num_rows($result) );

			$sortcode = $paging->getHtmlSortCode( $lng );
			$arrowcode = $paging->getHtmlArrowCode( $filename . '?page=' . $page . '&s=' . $s );
			$searchcode = $paging->getHtmlSearchCode( $lng );
			$pagingcode = $paging->getHtmlPagingCode( $filename . '?page=' . $page . '&s=' . $s );

			$i = 0;
			$count = 0;
			$htpasswds='';
			while($row=$db->fetch_array($result))
			{
				if( $paging->checkDisplay( $i ) )
				{
					$row['path']=str_replace($userinfo['documentroot'],'',$row['path']);
					$row = htmlentities_array( $row );
					eval("\$htpasswds.=\"".getTemplate("extras/htpasswds_htpasswd")."\";");
					$count++;
				}
				$i++;
			}
			eval("echo \"".getTemplate("extras/htpasswds")."\";");
		}

		elseif($action=='delete' && $id!=0)
		{
			$result=$db->query_first("SELECT `id`, `customerid`, `username`, `path` FROM `".TABLE_PANEL_HTPASSWDS."` WHERE `customerid`='".(int)$userinfo['customerid']."' AND `id`='".(int)$id."'");
			if(isset($result['username']) && $result['username']!='')
			{
				if(isset($_POST['send']) && $_POST['send']=='send')
				{
					$db->query("DELETE FROM `".TABLE_PANEL_HTPASSWDS."` WHERE `customerid`='".(int)$userinfo['customerid']."' AND `id`='$id'");
					inserttask('3',$result['path']);
					redirectTo ( $filename , Array ( 'page' => $page , 's' => $s ) ) ;
				}
				else
				{
					ask_yesno('extras_reallydelete', $filename, array( 'id' => $id, 'page' => $page, 'action' => $action ), $result['username'] . ' (' . str_replace($userinfo['documentroot'],'',$result['path']) . ')' );
				}
			}
		}

		elseif($action=='add')
		{
			if(isset($_POST['send']) && $_POST['send']=='send')
			{
				$path=makeCorrectDir(validate($_POST['path'], 'path'));
				$userpath=$path;
				$path=$userinfo['documentroot'].$path;
				$username=validate($_POST['username'], 'username');
				validate($_POST['password'], 'password');
				$username_path_check=$db->query_first("SELECT `id`, `username`, `path` FROM `".TABLE_PANEL_HTPASSWDS."` WHERE `username`='".$db->escape($username)."' AND `path`='".$db->escape($path)."' AND `customerid`='".(int)$userinfo['customerid']."'");
				if ( CRYPT_STD_DES == 1 )
				{
					$saltfordescrypt = substr(md5(uniqid(microtime(),1)),4,2);
					$password = crypt($_POST['password'], $saltfordescrypt);
				}
				else
				{
					$password = crypt($_POST['password']);
				}

				if(!$_POST['path'])
				{
					standard_error('invalidpath');
				}
				if(!is_dir($path))
				{
					standard_error('directorymustexist',$userpath);
				}
				elseif($username=='')
				{
					standard_error(array('stringisempty','myloginname'));
				}
				elseif($username_path_check['username'] == $username && $username_path_check['path'] == $path)
				{
					standard_error('userpathcombinationdupe');
				}
				elseif($_POST['password'] == '')
				{
					standard_error(array('stringisempty','mypassword'));
				}
				elseif($path=='')
				{
					standard_error('patherror');
				}
				else
				{
					$db->query("INSERT INTO `".TABLE_PANEL_HTPASSWDS."` (`customerid`, `username`, `password`, `path`) VALUES ('".(int)$userinfo['customerid']."', '".$db->escape($username)."', '".$db->escape($password)."', '".$db->escape($path)."')");
					inserttask('3',$path);
					redirectTo ( $filename , Array ( 'page' => $page , 's' => $s ) ) ;
				}
			}
			else 
			{
				$pathSelect = makePathfield( $userinfo['documentroot'], $userinfo['guid'], 
				                             $userinfo['guid'], $settings['panel']['pathedit'] );				
				eval("echo \"".getTemplate("extras/htpasswds_add")."\";");
			}
		}

		elseif($action=='edit' && $id!=0)
		{
			$result=$db->query_first("SELECT `id`, `username`, `path` FROM `".TABLE_PANEL_HTPASSWDS."` WHERE `customerid`='".(int)$userinfo['customerid']."' AND `id`='".(int)$id."'");
			if(isset($result['username']) && $result['username']!='')
			{
				if(isset($_POST['send']) && $_POST['send']=='send')
				{
					validate($_POST['password'], 'password');
					if ( CRYPT_STD_DES == 1 )
					{
						$saltfordescrypt = substr(md5(uniqid(microtime(),1)),4,2);
						$password = crypt($_POST['password'], $saltfordescrypt);
					}
					else
					{
						$password = crypt($_POST['password']);
					}
					if ($_POST['password'] == '')
					{
						standard_error(array('stringisempty','mypassword'));
					}
					else
					{
						$db->query("UPDATE `".TABLE_PANEL_HTPASSWDS."` SET `password`='".$db->escape($password)."' WHERE `customerid`='".(int)$userinfo['customerid']."' AND `id`='".(int)$id."'");
						inserttask('3',$result['path']);
						redirectTo ( $filename , Array ( 'page' => $page , 's' => $s ) ) ;
					}
				}
				else
				{
					if (strpos($result['path'], $userinfo['documentroot']) === 0)
					{
						$result['path']=substr($result['path'], strlen($userinfo['documentroot']));
					}

					$result = htmlentities_array( $result );
					eval("echo \"".getTemplate("extras/htpasswds_edit")."\";");
				}
			}
		}
	}

	elseif($page=='htaccess')
	{
		if($action=='')
		{
			$fields = array(
								'path' => $lng['panel']['path'],
								'options_indexes' => $lng['extras']['view_directory'],
								'error404path' => $lng['extras']['error404path'],
								'error403path' => $lng['extras']['error403path'],
								'error500path' => $lng['extras']['error500path']
							);
			$paging = new paging( $userinfo, $db, TABLE_PANEL_HTACCESS, $fields, $settings['panel']['paging'] );

			$result=$db->query(
				"SELECT `id`, `path`, `options_indexes`, `error404path`, `error403path`, `error500path` FROM `".TABLE_PANEL_HTACCESS."` WHERE `customerid`='".(int)$userinfo['customerid']."' ". 
				$paging->getSqlWhere( true )." ".$paging->getSqlOrderBy()." ".$paging->getSqlLimit()
			);
			$paging->setEntries( $db->num_rows($result) );

			$sortcode = $paging->getHtmlSortCode( $lng );
			$arrowcode = $paging->getHtmlArrowCode( $filename . '?page=' . $page . '&s=' . $s );
			$searchcode = $paging->getHtmlSearchCode( $lng );
			$pagingcode = $paging->getHtmlPagingCode( $filename . '?page=' . $page . '&s=' . $s );

			$i = 0;
			$count = 0;
			$htaccess = '';
			while($row=$db->fetch_array($result))
			{
				if( $paging->checkDisplay( $i ) )
				{
					if (strpos($row['path'], $userinfo['documentroot']) === 0)
					{
						$row['path']=substr($row['path'], strlen($userinfo['documentroot']));
					}
					$row['options_indexes'] = str_replace('1', $lng['panel']['yes'], $row['options_indexes']);
					$row['options_indexes'] = str_replace('0', $lng['panel']['no'], $row['options_indexes']);

					$row = htmlentities_array( $row );
					eval("\$htaccess.=\"".getTemplate("extras/htaccess_htaccess")."\";");
					$count++;
				}
				$i++;
			}
			eval("echo \"".getTemplate("extras/htaccess")."\";");
		}
		elseif($action=='delete' && $id!=0)
		{
			$result = $db->query_first("SELECT * FROM `".TABLE_PANEL_HTACCESS."` WHERE `customerid`='".(int)$userinfo['customerid']."' AND `id`='".(int)$id."'");
			if(isset($result['customerid']) && $result['customerid']!='' && $result['customerid'] == $userinfo['customerid'])
			{
				if (isset($_POST['send']) && $_POST['send']=='send')
				{
					$db->query("DELETE FROM `".TABLE_PANEL_HTACCESS."` WHERE `customerid`='".(int)$userinfo['customerid']."' AND `id`='".(int)$id."'");
					inserttask('3', $result['path']);
					redirectTo ( $filename , Array ( 'page' => $page , 's' => $s ) ) ;
				}
				else
				{
					ask_yesno('extras_reallydelete_pathoptions', $filename, array( 'id' => $id, 'page' => $page, 'action' => $action ), str_replace($userinfo['documentroot'],'',$result['path']) );
				}
			}
		}
		elseif($action=='add')
		{
			if( (isset($_POST['send'])) && ($_POST['send']=='send') )
			{
				$path            = makeCorrectDir(validate($_POST['path'], 'path'));
				$userpath        = $path;
				$path            = $userinfo['documentroot'].$path;
				$path_dupe_check = $db->query_first("SELECT `id`, `path` FROM `".TABLE_PANEL_HTACCESS."` WHERE `path`='".$db->escape($path)."' AND `customerid`='".(int)$userinfo['customerid']."'");

				if(!$_POST['path'])
				{
					standard_error('invalidpath');
				}
				if (    ($_POST['error404path'] === '')
				     || (verify_url($_POST['error404path']) )
				   )
				{
					$error404path = $_POST['error404path'];
				}
				else
				{
					standard_error('mustbeurl');
				}
				if (    ($_POST['error403path'] === '')
				     || (verify_url($_POST['error403path']) )
				   )
				{
					$error403path = $_POST['error403path'];
				}
				else
				{
					standard_error('mustbeurl');
				}

				if (    ($_POST['error500path'] === '')
				     || (verify_url($_POST['error500path']) )
				   )
				{
					$error500path = $_POST['error500path'];
				}
				else
				{
					standard_error('mustbeurl');
				}

/*
				if (    ($_POST['error401path'] === '')
				     || (verify_url($_POST['error401path']) )
				   )
				{
					$error401path = $_POST['error401path'];
				}
				else
				{
					standard_error('mustbeurl');
				}
*/

				if (!is_dir($path))
				{
					standard_error('directorymustexist',$userpath);
				}
				elseif ($path_dupe_check['path'] == $path)
				{
					standard_error('errordocpathdupe',$userpath);
				}
				elseif ($path == '')
				{
					standard_error('patherror');
				}

				else
				{
					$db->query(
						'INSERT INTO `'.TABLE_PANEL_HTACCESS.'` ' .
						'       (`customerid`, ' .
						'        `path`, ' .
						'        `options_indexes`, ' .
						'        `error404path`, ' .
						'        `error403path`, ' .
//						'        `error401path`, ' .
						'        `error500path` ' .
						'       ) ' .
						'VALUES ("'.(int)$userinfo['customerid'].'", ' .
						'        "'.$db->escape($path).'", ' .
						'        "'.$db->escape($_POST['options_indexes'] == '1' ? '1' : '0').'", ' .
						'        "'.$db->escape($error404path).'", ' .
						'        "'.$db->escape($error403path).'", ' .
//						'        "'.$db->escape($error401path).'", ' .
						'        "'.$db->escape($error500path).'" ' .
						'       )'
					);
					inserttask('3',$path);
					redirectTo ( $filename , Array ( 'page' => $page , 's' => $s ) ) ;
				}
			}
			else
			{
				$pathSelect = makePathfield( $userinfo['documentroot'], $userinfo['guid'], 
				                             $userinfo['guid'], $settings['panel']['pathedit'] );
				$options_indexes = makeyesno('options_indexes','1','0','1');
				eval("echo \"".getTemplate("extras/htaccess_add")."\";");
			}
		}

		elseif ( ($action=='edit') && ($id != 0) )
		{
			$result = $db->query_first(
				'SELECT * ' .
				'FROM `'.TABLE_PANEL_HTACCESS.'` ' .
				'WHERE `customerid` = "'.(int)$userinfo['customerid'].'" ' .
				'  AND `id`         = "'.(int)$id.'"'
			);

			if (    (isset($result['customerid']))
			     && ($result['customerid'] != '')
			     && ($result['customerid'] == $userinfo['customerid'])
			   )
			{
				if (isset($_POST['send']) && $_POST['send']=='send')
				{
					$option_indexes = intval($_POST['options_indexes']);
					if ($option_indexes != '1')
					{
						$option_indexes = '0';
					}
					if (    ($_POST['error404path'] === '')
					     || (verify_url($_POST['error404path']) )
					   )
					{
						$error404path = $_POST['error404path'];
					}
					else
					{
						standard_error('mustbeurl');
					}
					if (    ($_POST['error403path'] === '')
					     || (verify_url($_POST['error403path']) )
					   )
					{
						$error403path = $_POST['error403path'];
					}
					else
					{
						standard_error('mustbeurl');
					}
					if (    ($_POST['error500path'] === '')
					     || (verify_url($_POST['error500path']) )
					   )
					{
						$error500path = $_POST['error500path'];
					}
					else
					{
						standard_error('mustbeurl');
					}

/*
					if (    ($_POST['error401path'] === '')
					     || (verify_url($_POST['error401path']) )
					   )
					{
						$error401path = $_POST['error401path'];
					}
					else
					{
						standard_error('mustbeurl');
					}
*/

					if (    ($option_indexes != $result['options_indexes'])
					     || ($error404path   != $result['error404path'])
					     || ($error403path   != $result['error403path'])
//					     || ($error401path   != $result['error401path'])
					     || ($error500path   != $result['error500path'])
					   )
					{
						inserttask('3', $result['path']);
						$db->query(
							'UPDATE `'.TABLE_PANEL_HTACCESS.'` ' .
							'SET `options_indexes` = "'.$db->escape($option_indexes).'",' .
							'    `error404path`    = "'.$db->escape($error404path).'", ' .
							'    `error403path`    = "'.$db->escape($error403path).'", ' .
//							'    `error401path`    = "'.$db->escape($error401path).'", ' .
							'    `error500path`    = "'.$db->escape($error500path).'" ' .
							'WHERE `customerid` = "'.(int)$userinfo['customerid'].'" ' .
							'  AND `id` = "'.(int)$id.'"'
						);
					}
					redirectTo ( $filename , Array ( 'page' => $page , 's' => $s ) ) ;
				}
				else
				{
					if (strpos($result['path'], $userinfo['documentroot']) === 0)
					{
						$result['path'] = substr($result['path'], strlen($userinfo['documentroot']));
					}
					$result['error404path'] = $result['error404path'];
					$result['error403path'] = $result['error403path'];
//					$result['error401path'] = $result['error401path'];
					$result['error500path'] = $result['error500path'];
					$options_indexes = makeyesno('options_indexes', '1', '0', $result['options_indexes']);

					$result = htmlentities_array( $result );
					eval("echo \"".getTemplate("extras/htaccess_edit")."\";");
				}
			}
		}
	}
?>
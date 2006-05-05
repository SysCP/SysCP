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
 * @version    $Id:customer_extras.php 460 2006-04-23 15:07:49 +0200 (So, 23 Apr 2006) martin $
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

//	if(isset($_POST['id']))
//	{
//		$id=intval($_POST['id']);
//	}
//	elseif(isset($_GET['id']))
//	{
//		$id=intval($_GET['id']);
//	}

	if($config->get('env.page')=='overview')
	{
		eval("echo \"".getTemplate("extras/extras")."\";");
	}
	elseif($config->get('env.page')=='htpasswds')
	{
		if($config->get('env.action')=='')
		{
			$result=$db->query("SELECT `id`, `username`, `path` FROM `".TABLE_PANEL_HTPASSWDS."` WHERE `customerid`='".$userinfo['customerid']."' ORDER BY `username` ASC");
			$htpasswds='';
			while($row=$db->fetch_array($result))
			{
				$row['path']=str_replace($userinfo['documentroot'],'',$row['path']);
				eval("\$htpasswds.=\"".getTemplate("extras/htpasswds_htpasswd")."\";");
			}
			eval("echo \"".getTemplate("extras/htpasswds")."\";");
		}

		elseif($config->get('env.action')=='delete' && $config->get('env.id')!=0)
		{
			$result=$db->query_first("SELECT `id`, `customerid`, `username`, `path` FROM `".TABLE_PANEL_HTPASSWDS."` WHERE `customerid`='".$userinfo['customerid']."' AND `id`='".$config->get('env.id')."'");
			if(isset($result['username']) && $result['username']!='')
			{
				if(isset($_POST['send']) && $_POST['send']=='send')
				{
					$db->query("DELETE FROM `".TABLE_PANEL_HTPASSWDS."` WHERE `customerid`='".$userinfo['customerid']."' AND `id`='".$config->get('env.id')."'");
					$hooks->call( 'core.deleteHTPasswd', 
					              array( 'id'   => $config->get('env.id'),
					                     'path' => $result['path'] ) );
           			redirectTo ( $config->get('env.filename') , array( 'page' => $config->get('env.page') , 's' => $config->get('env.s') ) ) ;
				}
				else
				{
					ask_yesno('extras_reallydelete', $config->get('env.filename'), "id=".$config->get('env.id').";page=".$config->get('env.page').";action=".$config->get('env.action'), $result['username'] . ' (' . str_replace($userinfo['documentroot'],'',$result['path']) . ')' );
				}
			}
		}

		elseif($config->get('env.action')=='add')
		{
			if(isset($_POST['send']) && $_POST['send']=='send')
			{
				$path=makeCorrectDir(addslashes($_POST['path']));
				$userpath=$path;
				$path=$userinfo['documentroot'].$path;
				$username=addslashes($_POST['username']);
				$username_path_check=$db->query_first("SELECT `id`, `username`, `path` FROM `".TABLE_PANEL_HTPASSWDS."` WHERE `username`='$username' AND `path`='$path' AND `customerid`='".$userinfo['customerid']."'");
				if ( CRYPT_STD_DES == 1 )
				{
					$saltfordescrypt = substr(md5(uniqid(microtime(),1)),4,2);
					$password = addslashes(crypt($_POST['password'], $saltfordescrypt));
				}
				else
				{
					$password = addslashes(crypt($_POST['password']));
				}
                $passwordtest=$_POST['password'];

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
				elseif($passwordtest=='')
				{
					standard_error(array('stringisempty','mypassword'));
				}
				elseif($path=='')
				{
					standard_error('patherror');
				}
				else
				{
					$db->query("INSERT INTO `".TABLE_PANEL_HTPASSWDS."` (`customerid`, `username`, `password`, `path`) VALUES ('".$userinfo['customerid']."', '$username', '$password', '$path')");
					$htpasswdID = $db->insert_id();
					$hooks->call( 'core.createHTPasswd', 
					              array( 'id'   => $htpasswdID,
					                     'path' => $path ) );
           			redirectTo ( $config->get('env.filename') , Array ( 'page' => $config->get('env.page') , 's' => $config->get('env.s') ) ) ;
				}
			}
			else 
			{
				$pathSelect = makePathfield( $userinfo['documentroot'], $userinfo['guid'], 
				                             $userinfo['guid'], $config->get('panel.pathedit') );				
				eval("echo \"".getTemplate("extras/htpasswds_add")."\";");
			}
		}

		elseif($config->get('env.action')=='edit' && $config->get('env.id')!=0)
		{
			$result=$db->query_first("SELECT `id`, `username`, `path` FROM `".TABLE_PANEL_HTPASSWDS."` WHERE `customerid`='".$userinfo['customerid']."' AND `id`='".$config->get('env.id')."'");
			if(isset($result['username']) && $result['username']!='')
			{
				if(isset($_POST['send']) && $_POST['send']=='send')
				{
					if ( CRYPT_STD_DES == 1 )
					{
						$saltfordescrypt = substr(md5(uniqid(microtime(),1)),4,2);
						$password = addslashes(crypt($_POST['password'], $saltfordescrypt));
					}
					else
					{
						$password = addslashes(crypt($_POST['password']));
					}
					$passwordtest=$_POST['password'];
					if ($passwordtest=='')
					{
						standard_error(array('stringisempty','mypassword'));
					}
					else
					{
						$db->query("UPDATE `".TABLE_PANEL_HTPASSWDS."` SET `password`='$password' WHERE `customerid`='".$userinfo['customerid']."' AND `id`='".$config->get('env.id')."'");
						
						$hooks->call( 'core.updateHTPasswd',
						              array( 'id'   => $config->get('env.id'),
						                     'path' => $result['path'] ) );
						                     
            			redirectTo ( $config->get('env.filename') , Array ( 'page' => $config->get('env.page') , 's' => $config->get('env.s') ) ) ;
					}
				}
				else
				{
					$result['path']=str_replace($userinfo['documentroot'],'',$result['path']);
					eval("echo \"".getTemplate("extras/htpasswds_edit")."\";");
				}
			}
		}
	}

	elseif($config->get('env.page')=='htaccess')
	{
		if($config->get('env.action')=='')
		{
			$result=$db->query("SELECT * FROM `".TABLE_PANEL_HTACCESS."` WHERE `customerid`='".$userinfo['customerid']."'");
			$htaccess = '';
			while($row=$db->fetch_array($result))
			{
				$row['path']=str_replace($userinfo['documentroot'],'',$row['path']);
				$row['options_indexes'] = str_replace('1', $lng['panel']['yes'], $row['options_indexes']);
				$row['options_indexes'] = str_replace('0', $lng['panel']['no'], $row['options_indexes']);
				eval("\$htaccess.=\"".getTemplate("extras/htaccess_htaccess")."\";");
			}
			eval("echo \"".getTemplate("extras/htaccess")."\";");
		}
		elseif($config->get('env.action')=='delete' && $config->get('env.id')!=0)
		{
			$result = $db->query_first("SELECT * FROM `".TABLE_PANEL_HTACCESS."` WHERE `customerid`='".$userinfo['customerid']."' AND `id`='".$config->get('env.id')."'");
			if(isset($result['customerid']) && $result['customerid']!='' && $result['customerid'] == $userinfo['customerid'])
			{
				if (isset($_POST['send']) && $_POST['send']=='send')
				{
					$db->query("DELETE FROM `".TABLE_PANEL_HTACCESS."` WHERE `customerid`='".$userinfo['customerid']."' AND `id`='".$config->get('env.id')."'");
					
					$hooks->call( 'core.deleteHTAccess', 
					              array( 'id'    => $config->get('env.id'),
					                     'path' => $result['path'] ) );
					                     
           			redirectTo ( $config->get('env.filename') , Array ( 'page' => $config->get('env.page') , 's' => $config->get('env.s') ) ) ;
				}
				else
				{
					ask_yesno('extras_reallydelete_pathoptions', $config->get('env.filename'), "id=".$config->get('env.id').";page=".$config->get('env.page').";action=".$config->get('env.action'), str_replace($userinfo['documentroot'],'',$result['path']) );
				}
			}
		}
		elseif($config->get('env.action')=='add')
		{
			if( (isset($_POST['send'])) && ($_POST['send']=='send') )
			{
				$path            = makeCorrectDir(addslashes($_POST['path']));
                $userpath        = $path;
				$path            = $userinfo['documentroot'].$path;
				$path_dupe_check = $db->query_first("SELECT `id`, `path` FROM `".TABLE_PANEL_HTACCESS."` WHERE `path`='$path' AND `customerid`='".$userinfo['customerid']."'");
					if(!$_POST['path'])
					{
						standard_error('invalidpath');
					}
					if (    ($_POST['error404path'] == '')
					     || (preg_match('/^https?\:\/\//', $_POST['error404path']) )
					   )
					{
						$error404path = $_POST['error404path'];
					}
					else
					{
						standard_error('mustbeurl');
					}
					if (    ($_POST['error403path'] == '')
					     || (preg_match('/^https?\:\/\//', $_POST['error403path']) )
					   )
					{
						$error403path = $_POST['error403path'];
					}
					else
					{
						standard_error('mustbeurl');
					}
					if (    ($_POST['error500path'] == '')
					     || (preg_match('/^https?\:\/\//', $_POST['error500path']) )
					   )
					{
						$error500path = $_POST['error500path'];
					}
					else
					{
						standard_error('mustbeurl');
					}
//					if (    ($_POST['error401path'] == '')
//					     || (preg_match('/^https?\:\/\//', $_POST['error401path']) )
//					   )
//					{
//						$error401path = $_POST['error401path'];
//					}
//					else
//					{
//						standard_error('mustbeurl');
//					}

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
						'VALUES ("'.$userinfo['customerid'].'", ' .
						'        "'.$path.'", ' .
						'        "'.$_POST['options_indexes'].'", ' .
						'        "'.$error404path.'", ' .
						'        "'.$error403path.'", ' .
//						'        "'.$error401path.'", ' .
						'        "'.$error500path.'" ' .
						'       )'
					);
					$htaccessID = $db->insert_id();
					$hooks->call( 'core.createHTAccess', 
					              array( 'id'   => $htaccessID,
					                     'path' => $path ) );
           			redirectTo ( $config->get('env.filename') , Array ( 'page' => $config->get('env.page') , 's' => $config->get('env.s') ) ) ;
				}
			}
			else
			{
				$pathSelect = makePathfield( $userinfo['documentroot'], $userinfo['guid'], 
				                             $userinfo['guid'], $config->get('panel.pathedit') );				
				$options_indexes = makeyesno('options_indexes','1','0','1');
				eval("echo \"".getTemplate("extras/htaccess_add")."\";");
			}
		}
		elseif ( ($config->get('env.action')=='edit') && ($config->get('env.id') != 0) )
		{
			$result = $db->query_first(
				'SELECT * ' .
				'FROM `'.TABLE_PANEL_HTACCESS.'` ' .
				'WHERE `customerid` = "'.$userinfo['customerid'].'" ' .
				'  AND `id`         = "'.$config->get('env.id').'"'
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
					if (    ($_POST['error404path'] == '')
					     || (preg_match('/^https?\:\/\//', $_POST['error404path']) )
					   )
					{
						$error404path = $_POST['error404path'];
					}
					else
					{
						standard_error('mustbeurl');
					}
					if (    ($_POST['error403path'] == '')
					     || (preg_match('/^https?\:\/\//', $_POST['error403path']) )
					   )
					{
						$error403path = $_POST['error403path'];
					}
					else
					{
						standard_error('mustbeurl');
					}
					if (    ($_POST['error500path'] == '')
					     || (preg_match('/^https?\:\/\//', $_POST['error500path']) )
					   )
					{
						$error500path = $_POST['error500path'];
					}
					else
					{
						standard_error('mustbeurl');
					}
//					if (    ($_POST['error401path'] == '')
//					     || (preg_match('/^https?\:\/\//', $_POST['error401path']) )
//					   )
//					{
//						$error401path = $_POST['error401path'];
//					}
//					else
//					{
//						standard_error('mustbeurl');
//					}

					if (    ($option_indexes != $result['options_indexes'])
					     || ($error404path   != $result['error404path'])
					     || ($error403path   != $result['error403path'])
//					     || ($error401path   != $result['error401path'])
					     || ($error500path   != $result['error500path'])
					   )
					{
						$db->query(
							'UPDATE `'.TABLE_PANEL_HTACCESS.'` ' .
							'SET `options_indexes` = "'.$option_indexes.'",' .
							'    `error404path`    = "'.$error404path.'", ' .
							'    `error403path`    = "'.$error403path.'", ' .
//							'    `error401path`    = "'.$error401path.'", ' .
							'    `error500path`    = "'.$error500path.'" ' .
							'WHERE `customerid` = "'.$userinfo['customerid'].'" ' .
							'  AND `id` = "'.$config->get('env.id').'"'
						);
						
						$hooks->call( 'core.updateHTAccess', 
						              array( 'id'   => $config->get('env.id'),
						                     'path' => $result['path'] ) );
					}
           			redirectTo ( $config->get('env.filename') , Array ( 'page' => $config->get('env.page') , 's' => $config->get('env.s') ) ) ;
				}
				else
				{
					$result['path']         = str_replace($userinfo['documentroot'], '', $result['path']);
					$result['error404path'] = $result['error404path'];
					$result['error403path'] = $result['error403path'];
//					$result['error401path'] = $result['error401path'];
					$result['error500path'] = $result['error500path'];
					$options_indexes = makeyesno('options_indexes', '1', '0', $result['options_indexes']);
					eval("echo \"".getTemplate("extras/htaccess_edit")."\";");
				}
			}
		}
	}
?>
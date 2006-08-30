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
		eval("echo \"".getTemplate("ftp/ftp")."\";");
	}
	elseif($page=='accounts')
	{
		if($action=='')
		{
			$fields = array(
								'username' => $lng['login']['username'],
								'homedir' => $lng['panel']['path']
							);
			$paging = new paging( $userinfo, $db, TABLE_FTP_USERS, $fields, $settings['panel']['paging'] );

			$result=$db->query(
				"SELECT `id`, `username`, `homedir` FROM `".TABLE_FTP_USERS."` WHERE `customerid`='".$userinfo['customerid']."' " .
				$paging->getSqlWhere( true )." ".$paging->getSqlOrderBy()." ".$paging->getSqlLimit()
			);
			$paging->setEntries( $db->num_rows($result) );

			$sortcode = $paging->getHtmlSortCode( $lng );
			$arrowcode = $paging->getHtmlArrowCode( $filename . '?page=' . $page . '&s=' . $s );
			$searchcode = $paging->getHtmlSearchCode( $lng );
			$pagingcode = $paging->getHtmlPagingCode( $filename . '?page=' . $page . '&s=' . $s );

			$i = 0;
			$count = 0;
			$accounts='';
			while($row=$db->fetch_array($result))
			{
				if( $paging->checkDisplay( $i ) )
				{
					if (strpos($row['homedir'], $userinfo['documentroot']) === 0)
					{
						$row['documentroot'] = substr($row['homedir'], strlen($userinfo['documentroot']));
					}
					else
					{
						$row['documentroot'] = $row['homedir'];
					}
					$row = htmlentities_array( $row );
					eval("\$accounts.=\"".getTemplate("ftp/accounts_account")."\";");
					$count++;
				}
				$i++;
			}
			$ftps_count = $db->num_rows($result);

			eval("echo \"".getTemplate("ftp/accounts")."\";");
		}

		elseif($action=='delete' && $id!=0)
		{
			$result=$db->query_first("SELECT `id`, `username`, `homedir`, `up_count`, `up_bytes`, `down_count`, `down_bytes` FROM `".TABLE_FTP_USERS."` WHERE `customerid`='".(int)$userinfo['customerid']."' AND `id`='".(int)$id."'");
			if(isset($result['username']) && $result['username']!=$userinfo['loginname'])
			{
				if(isset($_POST['send']) && $_POST['send']=='send')
				{
					$db->query("UPDATE `".TABLE_FTP_USERS."` SET `up_count`=`up_count`+'".(int)$result['up_count']."', `up_bytes`=`up_bytes`+'".(int)$result['up_bytes']."', `down_count`=`down_count`+'".(int)$result['down_count']."', `down_bytes`=`down_bytes`+'".(int)$result['down_bytes']."' WHERE `username`='".$db->escape($userinfo['loginname'])."'");
					$db->query("DELETE FROM `".TABLE_FTP_USERS."` WHERE `customerid`='".(int)$userinfo['customerid']."' AND `id`='".(int)$id."'");
					$db->query("UPDATE `".TABLE_FTP_GROUPS."` SET `members`=REPLACE(`members`,',".$db->escape($result['username'])."','') WHERE `customerid`='".(int)$userinfo['customerid']."'");
//					$db->query("DELETE FROM `".TABLE_FTP_GROUPS."` WHERE `customerid`='".$userinfo['customerid']."' AND `id`='$id'");
					if($userinfo['ftps_used']=='1')
					{
						$resetaccnumber=" , `ftp_lastaccountnumber`='0'";
					}
					else
					{
						$resetaccnumber='';
					}
					$result=$db->query("UPDATE `".TABLE_PANEL_CUSTOMERS."` SET `ftps_used`=`ftps_used`-1 $resetaccnumber WHERE `customerid`='".(int)$userinfo['customerid']."'");
					redirectTo ( $filename , Array ( 'page' => $page , 's' => $s ) ) ;
				}
				else
				{
					ask_yesno('ftp_reallydelete', $filename, array( 'id' => $id, 'page' => $page, 'action' => $action ), $result['username']);
				}
			}
			else
			{
				standard_error('ftp_cantdeletemainaccount');
			}
		}

		elseif($action=='add')
		{
			if($userinfo['ftps_used'] < $userinfo['ftps'] || $userinfo['ftps'] == '-1')
			{
				if(isset($_POST['send']) && $_POST['send']=='send')
				{
					$path=makeCorrectDir(validate($_POST['path'], 'path'));
					$userpath=$path;
					$path=$userinfo['documentroot'].$path;
					$path_check=$db->query_first("SELECT `id`, `username`, `homedir` FROM `".TABLE_FTP_USERS."` WHERE `homedir`='".$db->escape($path)."' AND `customerid`='".(int)$userinfo['customerid']."'");
					$password=validate($_POST['password'], 'password');

					if(!$_POST['path'])
					{
						standard_error('invalidpath');
					}
					if(!is_dir($path))
					{
						standard_error('directorymustexist',$userpath);
					}
					elseif($password=='')
					{
						standard_error(array('stringisempty','mypassword'));
					}
					elseif($path=='')
					{
						standard_error('patherror');
					}

					else
					{
						$username=$userinfo['loginname'].$settings['customer']['ftpprefix'].(intval($userinfo['ftp_lastaccountnumber'])+1);
						$db->query("INSERT INTO `".TABLE_FTP_USERS."` (`customerid`, `username`, `password`, `homedir`, `login_enabled`, `uid`, `gid`) VALUES ('".(int)$userinfo['customerid']."', '".$db->escape($username)."', ENCRYPT('".$db->escape($password)."'), '".$db->escape($path)."', 'y', '".(int)$userinfo['guid']."', '".(int)$userinfo['guid']."')");
						$db->query("UPDATE `".TABLE_FTP_GROUPS."` SET `members`=CONCAT_WS(',',`members`,'".$db->escape($username)."') WHERE `customerid`='".$userinfo['customerid']."' AND `gid`='".(int)$userinfo['guid']."'");
//						$db->query("INSERT INTO `".TABLE_FTP_GROUPS."` (`customerid`, `groupname`, `gid`, `members`) VALUES ('".$userinfo['customerid']."', '$username', '$uid', '$username')");
						$db->query("UPDATE `".TABLE_PANEL_CUSTOMERS."` SET `ftps_used`=`ftps_used`+1, `ftp_lastaccountnumber`=`ftp_lastaccountnumber`+1 WHERE `customerid`='".(int)$userinfo['customerid']."'");
//						$db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='$uid' WHERE settinggroup='ftp' AND varname='lastguid'");
						redirectTo ( $filename , Array ( 'page' => $page , 's' => $s ) ) ;
					}
				}
				else
				{
					$pathSelect = makePathfield( $userinfo['documentroot'], $userinfo['guid'], 
					                             $userinfo['guid'], $settings['panel']['pathedit'] );
					eval("echo \"".getTemplate("ftp/accounts_add")."\";");
				}
			}
		}

		elseif($action=='edit' && $id!=0)
		{
			$result=$db->query_first("SELECT `id`, `username`, `homedir` FROM `".TABLE_FTP_USERS."` WHERE `customerid`='".(int)$userinfo['customerid']."' AND `id`='".(int)$id."'");
			if(isset($result['username']) && $result['username']!='')
			{
				if(isset($_POST['send']) && $_POST['send']=='send')
				{
					$password=validate($_POST['password'], 'password');
					if($password=='')
					{
						standard_error(array('stringisempty','mypassword'));
						exit;
					}
					else
					{
						$db->query("UPDATE `".TABLE_FTP_USERS."` SET `password`=ENCRYPT('".$db->escape($password)."') WHERE `customerid`='".(int)$userinfo['customerid']."' AND `id`='".(int)$id."'");
						redirectTo ( $filename , Array ( 'page' => $page , 's' => $s ) ) ;
					}
				}
				else
				{
					eval("echo \"".getTemplate("ftp/accounts_edit")."\";");
				}
			}
		}
	}

?>
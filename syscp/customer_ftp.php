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
			$result=$db->query("SELECT `id`, `username`, `homedir` FROM `".TABLE_FTP_USERS."` WHERE `customerid`='".$userinfo['customerid']."' ORDER BY `username` ASC");
			$accounts='';
			while($row=$db->fetch_array($result))
			{
				$row['documentroot']=str_replace($userinfo['documentroot'],'',$row['homedir']);
				eval("\$accounts.=\"".getTemplate("ftp/accounts_account")."\";");
			}
			if($userinfo['ftps_used'] < $userinfo['ftps'] || $userinfo['ftps'] == '-1')
			{
				if($db->num_rows($result) > 15)
				{
					eval("\$accounts=\"".getTemplate("ftp/accounts_addaccount")."\".\$accounts;");
				}
				eval("\$accounts.=\"".getTemplate("ftp/accounts_addaccount")."\";");
			}
			eval("echo \"".getTemplate("ftp/accounts")."\";");
		}

		elseif($action=='delete' && $id!=0)
		{
			$result=$db->query_first("SELECT `id`, `username`, `homedir`, `up_count`, `up_bytes`, `down_count`, `down_bytes` FROM `".TABLE_FTP_USERS."` WHERE `customerid`='".$userinfo['customerid']."' AND `id`='$id'");
			if(isset($result['username']) && $result['username']!=$userinfo['loginname'])
			{
				if(isset($_POST['send']) && $_POST['send']=='send')
				{
					$db->query("UPDATE `".TABLE_FTP_USERS."` SET `up_count`=`up_count`+'".$result['up_count']."', `up_bytes`=`up_bytes`+'".$result['up_bytes']."', `down_count`=`down_count`+'".$result['down_count']."', `down_bytes`=`down_bytes`+'".$result['down_bytes']."' WHERE `username`='".$userinfo['loginname']."' ");
					$db->query("DELETE FROM `".TABLE_FTP_USERS."` WHERE `customerid`='".$userinfo['customerid']."' AND `id`='$id'");
//					$db->query("DELETE FROM `".TABLE_FTP_GROUPS."` WHERE `customerid`='".$userinfo['customerid']."' AND `id`='$id'");
					if($userinfo['ftps_used']=='1')
					{
						$resetaccnumber=" , `ftp_lastaccountnumber`='0'";
					}
					else
					{
						$resetaccnumber='';
					}
					$result=$db->query("UPDATE `".TABLE_PANEL_CUSTOMERS."` SET `ftps_used`=`ftps_used`-1 $resetaccnumber WHERE `customerid`='".$userinfo['customerid']."'");
					header("Location: $filename?page=$page&s=$s");
				}
				else {
					ask_yesno('ftp_reallydelete', $filename, "id=$id;page=$page;action=$action");
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
					$path=makeCorrectDir(addslashes($_POST['path']));
					$path=$userinfo['documentroot'].$path;
					$path_check=$db->query_first("SELECT `id`, `username`, `homedir` FROM `".TABLE_FTP_USERS."` WHERE `homedir`='$path' AND `customerid`='".$userinfo['customerid']."'");
					$password=addslashes($_POST['password']);
					if($path=='' || $password=='' || /*$path_check['homedir']==$path ||*/ !is_dir($path))
					{
						if(!is_dir($path))
						{
							standard_error('directorymustexist');
						}
						else
						{
							standard_error('notallreqfieldsorerrors');
						}
						exit;
					}
					else
					{
						$username=$userinfo['loginname'].$settings['customer']['ftpprefix'].(intval($userinfo['ftp_lastaccountnumber'])+1);
						$db->query("INSERT INTO `".TABLE_FTP_USERS."` (`customerid`, `username`, `password`, `homedir`, `login_enabled`, `uid`, `gid`) VALUES ('".$userinfo['customerid']."', '$username', '$password', '$path', 'y', '".$userinfo['guid']."', '".$userinfo['guid']."')");
//						$db->query("INSERT INTO `".TABLE_FTP_GROUPS."` (`customerid`, `groupname`, `gid`, `members`) VALUES ('".$userinfo['customerid']."', '$username', '$uid', '$username')");
						$db->query("UPDATE `".TABLE_PANEL_CUSTOMERS."` SET `ftps_used`=`ftps_used`+1, `ftp_lastaccountnumber`=`ftp_lastaccountnumber`+1 WHERE `customerid`='".$userinfo['customerid']."'");
//						$db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='$uid' WHERE settinggroup='ftp' AND varname='lastguid'");
						header("Location: $filename?page=$page&s=$s");
					}
				}
				else {
					eval("echo \"".getTemplate("ftp/accounts_add")."\";");
				}
			}
		}

		elseif($action=='edit' && $id!=0)
		{
			$result=$db->query_first("SELECT `id`, `username`, `homedir` FROM `".TABLE_FTP_USERS."` WHERE `customerid`='".$userinfo['customerid']."' AND `id`='$id'");
			if(isset($result['username']) && $result['username']!='')
			{
				if(isset($_POST['send']) && $_POST['send']=='send')
				{
					$password=addslashes($_POST['password']);
					if($password=='')
					{
						standard_error('notallreqfieldsorerrors');
						exit;
					}
					else
					{
						$db->query("UPDATE `".TABLE_FTP_USERS."` SET `password`='$password' WHERE `customerid`='".$userinfo['customerid']."' AND `id`='$id'");
						header("Location: $filename?page=$page&s=$s");
					}
				}
				else {
					eval("echo \"".getTemplate("ftp/accounts_edit")."\";");
				}
			}
		}
	}

?>

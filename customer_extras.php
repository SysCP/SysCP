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
			$result=$db->query("SELECT `id`, `username`, `path` FROM `".TABLE_PANEL_HTPASSWDS."` WHERE `customerid`='".$userinfo['customerid']."' ORDER BY `username` ASC");
			$htpasswds='';
			while($row=$db->fetch_array($result))
			{
				$row['path']=str_replace($userinfo['documentroot'],'',$row['path']);
				eval("\$htpasswds.=\"".getTemplate("extras/htpasswds_htpasswd")."\";");
			}
			eval("\$htpasswds.=\"".getTemplate("extras/htpasswds_addhtpasswd")."\";");
			eval("echo \"".getTemplate("extras/htpasswds")."\";");
		}

		elseif($action=='delete' && $id!=0)
		{
			$result=$db->query_first("SELECT `id`, `customerid`, `username`, `path` FROM `".TABLE_PANEL_HTPASSWDS."` WHERE `customerid`='".$userinfo['customerid']."' AND `id`='$id'");
				if(isset($_POST['send']) && $_POST['send']=='send')
			{
				$db->query("DELETE FROM `".TABLE_PANEL_HTPASSWDS."` WHERE `customerid`='".$userinfo['customerid']."' AND `id`='$id'");
				inserttask('3',$result['path']);
				header("Location: $filename?page=$page&s=$s");
			}
			else {
				ask_yesno('extras_reallydelete', $filename, "id=$id;page=$page;action=$action");
			}
		}

		elseif($action=='add')
		{
			if(isset($_POST['send']) && $_POST['send']=='send')
			{
				$path=addslashes($_POST['path']);
				$path=str_replace('..','',$path);
				if(substr($path, -1, 1) != '/')
				{
					$path.='/';
				}
				if(substr($path, 0, 1) != '/')
				{
					$path='/'.$path;
				}
				$path=$userinfo['documentroot'].$path;
				$username=addslashes($_POST['username']);
				$username_path_check=$db->query_first("SELECT `id`, `username`, `path` FROM `".TABLE_PANEL_HTPASSWDS."` WHERE `username`='$username' AND `path`='$path' AND `customerid`='".$userinfo['customerid']."'");
				$password=crypt(addslashes($_POST['password']));
				if($path=='' || $username == '' || ($username_path_check['username'] == $username && $username_path_check['path'] == $path) || $password=='' || !is_dir($path))
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
					$db->query("INSERT INTO `".TABLE_PANEL_HTPASSWDS."` (`customerid`, `username`, `password`, `path`) VALUES ('".$userinfo['customerid']."', '$username', '$password', '$path')");
					inserttask('3',$path);
					header("Location: $filename?page=$page&s=$s");
				}
			}
			else {
				eval("echo \"".getTemplate("extras/htpasswds_add")."\";");
			}
		}

		elseif($action=='edit' && $id!=0)
		{
			$result=$db->query_first("SELECT `id`, `username`, `path` FROM `".TABLE_PANEL_HTPASSWDS."` WHERE `customerid`='".$userinfo['customerid']."' AND `id`='$id'");
			if(isset($result['username']) && $result['username']!='')
			{
				if(isset($_POST['send']) && $_POST['send']=='send')
				{
					$password=crypt(addslashes($_POST['password']));
					if($password=='')
					{
						standard_error('notallreqfieldsorerrors');
						exit;
					}
					else
					{
						$db->query("UPDATE `".TABLE_PANEL_HTPASSWDS."` SET `password`='$password' WHERE `customerid`='".$userinfo['customerid']."' AND `id`='$id'");
						inserttask('3',$result['path']);
						header("Location: $filename?page=$page&s=$s");
					}
				}
				else {
					$result['path']=str_replace($userinfo['documentroot'],'',$result['path']);
					eval("echo \"".getTemplate("extras/htpasswds_edit")."\";");
				}
			}
		}
	}

?>

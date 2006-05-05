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
		eval("echo \"".getTemplate("ftp/ftp")."\";");
	}
	elseif($config->get('env.page')=='accounts')
	{
		if($config->get('env.action')=='')
		{
			$result=$db->query("SELECT `id`, `username`, `homedir` FROM `".TABLE_FTP_USERS."` WHERE `customerid`='".$userinfo['customerid']."' ORDER BY `username` ASC");
			$accounts='';
			$rows = $db->num_rows($result);
			if ($config->get('panel.paging') > 0)
			{
				$pages = intval($rows / $config->get('panel.paging'));
			}
			else
			{
				$pages = 0;
			}
			if ($pages != 0)
			{
				if(isset($_GET['no']))
				{
					$pageno = intval($_GET['no']);
				}
				else
				{
					$pageno = 1;
				}
				if ($pageno > $pages)
				{
					$pageno = $pages + 1;
				}
				elseif ($pageno < 1)
				{
					$pageno = 1;
				}
				$pagestart = ($pageno - 1) * $config->get('panel.paging');
				$result=$db->query(
					"SELECT `id`, `username`, `homedir` FROM `".TABLE_FTP_USERS."` WHERE `customerid`='".$userinfo['customerid']."' ORDER BY `username` ASC " .
					" LIMIT $pagestart , ".$config->get('panel.paging').";"
				);
				$paging = '';
				for ($count = 1; $count <= $pages+1; $count++)
				{
					if ($count == $pageno)
					{
						$paging .= "<a href=\"".$config->get('env.filename')."?s=".$config->get('env.s')."&page=".$config->get('env.page')."&no=$count\"><b>$count</b></a>&nbsp;";
					}
					else
					{
						$paging .= "<a href=\"".$config->get('env.filename')."?s=".$config->get('env.s')."&page=".$config->get('env.page')."&no=$count\">$count</a>&nbsp;";
					}
				}
			}
			else
			{
				$paging = "";
			}
			while($row=$db->fetch_array($result))
			{
				$row['documentroot']=str_replace($userinfo['documentroot'],'',$row['homedir']);
				eval("\$accounts.=\"".getTemplate("ftp/accounts_account")."\";");
			}
			$ftps_count = $db->num_rows($result);

			eval("echo \"".getTemplate("ftp/accounts")."\";");
		}

		elseif($config->get('env.action')=='delete' && $config->get('env.id')!=0)
		{
			$result=$db->query_first("SELECT `id`, `username`, `homedir`, `up_count`, `up_bytes`, `down_count`, `down_bytes` FROM `".TABLE_FTP_USERS."` WHERE `customerid`='".$userinfo['customerid']."' AND `id`='".$config->get('env.id')."'");
			if(isset($result['username']) && $result['username']!=$userinfo['loginname'])
			{
				if(isset($_POST['send']) && $_POST['send']=='send')
				{
					$db->query("UPDATE `".TABLE_FTP_USERS."` SET `up_count`=`up_count`+'".$result['up_count']."', `up_bytes`=`up_bytes`+'".$result['up_bytes']."', `down_count`=`down_count`+'".$result['down_count']."', `down_bytes`=`down_bytes`+'".$result['down_bytes']."' WHERE `username`='".$userinfo['loginname']."' ");
					$db->query("DELETE FROM `".TABLE_FTP_USERS."` WHERE `customerid`='".$userinfo['customerid']."' AND `id`='".$config->get('env.id')."'");
					$db->query("UPDATE `".TABLE_FTP_GROUPS."` SET `members`=REPLACE(`members`,',".$result['username']."','') WHERE `customerid`='".$userinfo['customerid']."'");
//					$db->query("DELETE FROM `".TABLE_FTP_GROUPS."` WHERE `customerid`='".$userinfo['customerid']."' AND `id`='".$config->get('env.id')."'");
					if($userinfo['ftps_used']=='1')
					{
						$resetaccnumber=" , `ftp_lastaccountnumber`='0'";
					}
					else
					{
						$resetaccnumber='';
					}
					$result=$db->query("UPDATE `".TABLE_PANEL_CUSTOMERS."` SET `ftps_used`=`ftps_used`-1 $resetaccnumber WHERE `customerid`='".$userinfo['customerid']."'");
            			redirectTo ( $config->get('env.filename') , Array ( 'page' => $config->get('env.page') , 's' => $config->get('env.s') ) ) ;
				}
				else
				{
					ask_yesno('ftp_reallydelete', $config->get('env.filename'), "id=".$config->get('env.id').";page=".$config->get('env.page').";action=".$config->get('env.action'), $result['username']);
				}
			}
			else
			{
				standard_error('ftp_cantdeletemainaccount');
			}
		}

		elseif($config->get('env.action')=='add')
		{
			if($userinfo['ftps_used'] < $userinfo['ftps'] || $userinfo['ftps'] == '-1')
			{
				if(isset($_POST['send']) && $_POST['send']=='send')
				{
					$path=makeCorrectDir(addslashes($_POST['path']));
					$userpath=$path;
					$path=$userinfo['documentroot'].$path;
					$path_check=$db->query_first("SELECT `id`, `username`, `homedir` FROM `".TABLE_FTP_USERS."` WHERE `homedir`='$path' AND `customerid`='".$userinfo['customerid']."'");
					$password=addslashes($_POST['password']);

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
						$username=$userinfo['loginname'].$config->get('customer.ftpprefix').(intval($userinfo['ftp_lastaccountnumber'])+1);
						$db->query("INSERT INTO `".TABLE_FTP_USERS."` (`customerid`, `username`, `password`, `homedir`, `login_enabled`, `uid`, `gid`) VALUES ('".$userinfo['customerid']."', '$username', ENCRYPT('$password'), '$path', 'y', '".$userinfo['guid']."', '".$userinfo['guid']."')");
						$db->query("UPDATE `".TABLE_FTP_GROUPS."` SET `members`=CONCAT_WS(',',`members`,'".$username."') WHERE `customerid`='".$userinfo['customerid']."' AND `gid`='".$userinfo['guid']."'");
//						$db->query("INSERT INTO `".TABLE_FTP_GROUPS."` (`customerid`, `groupname`, `gid`, `members`) VALUES ('".$userinfo['customerid']."', '$username', '$uid', '$username')");
						$db->query("UPDATE `".TABLE_PANEL_CUSTOMERS."` SET `ftps_used`=`ftps_used`+1, `ftp_lastaccountnumber`=`ftp_lastaccountnumber`+1 WHERE `customerid`='".$userinfo['customerid']."'");
//						$db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='$uid' WHERE settinggroup='ftp' AND varname='lastguid'");
            			redirectTo ( $config->get('env.filename') , Array ( 'page' => $config->get('env.page') , 's' => $config->get('env.s') ) ) ;
					}
				}
				else
				{
					$pathSelect = makePathfield( $userinfo['documentroot'], $userinfo['guid'], 
					                             $userinfo['guid'], $config->get('panel.pathedit') );				
					eval("echo \"".getTemplate("ftp/accounts_add")."\";");
				}
			}
		}

		elseif($config->get('env.action')=='edit' && $config->get('env.id')!=0)
		{
			$result=$db->query_first("SELECT `id`, `username`, `homedir` FROM `".TABLE_FTP_USERS."` WHERE `customerid`='".$userinfo['customerid']."' AND `id`='".$config->get('env.id')."'");
			if(isset($result['username']) && $result['username']!='')
			{
				if(isset($_POST['send']) && $_POST['send']=='send')
				{
					$password=addslashes($_POST['password']);
					if($password=='')
					{
						standard_error(array('stringisempty','mypassword'));
						exit;
					}
					else
					{
						$db->query("UPDATE `".TABLE_FTP_USERS."` SET `password`=ENCRYPT('$password') WHERE `customerid`='".$userinfo['customerid']."' AND `id`='".$config->get('env.id')."'");
            			redirectTo ( $config->get('env.filename') , Array ( 'page' => $config->get('env.page') , 's' => $config->get('env.s') ) ) ;
					}
				}
				else {
					eval("echo \"".getTemplate("ftp/accounts_edit")."\";");
				}
			}
		}
	}

?>
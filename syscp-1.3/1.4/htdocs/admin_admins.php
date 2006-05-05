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
 * @subpackage Panel.Admin
 * @license    GPLv2 http://files.syscp.org/misc/COPYING.txt
 * @version    $Id:admin_admins.php 460 2006-04-23 15:07:49 +0200 (So, 23 Apr 2006) martin $
 * 
 * @todo code cleanup is needed, exspecially in queries, loops, conditions
 * @todo code restructuring is needed, we need to seperate view, controller and model logic,
 *       as a preparation towards MVC patterns
 */
	define('AREA', 'admin');

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

	if($config->get('env.page')=='admins' && $userinfo['change_serversettings'] == '1' )
	{
		if($config->get('env.action')=='')
		{
			if(isset($_GET['sortby']))
			{
				$sortby=addslashes($_GET['sortby']);
			}
			else
			{
				$sortby='loginname';
			}
			if(isset($_GET['sortorder']) && strtolower($_GET['sortorder'])=='desc')
			{
				$sortorder='DESC';
			}
			else
			{
				$sortorder='ASC';
			}

			$admins='';
			$result=$db->query("SELECT * FROM `".TABLE_PANEL_ADMINS."` ORDER BY `$sortby` $sortorder");
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
					"SELECT * FROM `".TABLE_PANEL_ADMINS."` ORDER BY `$sortby` $sortorder " .
					"LIMIT $pagestart , ".$config->get('panel.paging').";"
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
				$row['traffic_used']=round($row['traffic_used']/(1024*1024),4);
				$row['traffic']=round($row['traffic']/(1024*1024),4);
				$row['diskspace_used']=round($row['diskspace_used']/1024,2);
				$row['diskspace']=round($row['diskspace']/1024,2);
				$row['deactivated'] = str_replace('0', $lng['panel']['yes'], $row['deactivated']);
				$row['deactivated'] = str_replace('1', $lng['panel']['no'], $row['deactivated']);

				$row = str_replace_array('-1', 'UL', $row, 'customers domains diskspace traffic mysqls emails email_accounts email_forwarders ftps subdomains');

				eval("\$admins.=\"".getTemplate("admins/admins_admin")."\";");
			}
			eval("echo \"".getTemplate("admins/admins")."\";");
		}

		elseif($config->get('env.action')=='delete' && $config->get('env.id')!=0)
		{
			if($config->get('env.id') == '1')
			{
				standard_error('youcantdeletechangemainadmin');
				exit;
			}
			$result=$db->query_first("SELECT * FROM `".TABLE_PANEL_ADMINS."` WHERE `adminid`='".$config->get('env.id')."'");
			if($result['loginname']!='')
			{
				if(isset($_POST['send']) && $_POST['send']=='send')
				{
					$db->query("DELETE FROM `".TABLE_PANEL_ADMINS."` WHERE `adminid`='".$config->get('env.id')."'");
					$db->query("DELETE FROM `".TABLE_PANEL_TRAFFIC_ADMINS."` WHERE `adminid`='".$config->get('env.id')."'");
					$db->query("UPDATE `".TABLE_PANEL_CUSTOMERS."` SET `adminid` = '1' WHERE `adminid` = '".$config->get('env.id')."'");
					$db->query("UPDATE `".TABLE_PANEL_DOMAINS."` SET `adminid` = '1' WHERE `adminid` = '".$config->get('env.id')."'");
					updateCounters () ;

					redirectTo ( $config->get('env.filename') , 
					             array( 'page' => $config->get('env.page') , 
					                    's'    => $config->get('env.s') ) ) ;
				}
				else {
					ask_yesno('admin_admin_reallydelete', $config->get('env.filename'), "id=".$config->get('env.id').";page=".$config->get('env.page').";action=".$config->get('env.action'), $result['loginname']);
				}
			}
		}

		elseif($config->get('env.action')=='add')
		{
			if(isset($_POST['send']) && $_POST['send']=='send')
			{
				$name = addslashes ( $_POST['name'] ) ;
				$loginname = addslashes ( $_POST['loginname'] ) ;
				$loginname_check = $db->query_first("SELECT `loginname` FROM `".TABLE_PANEL_ADMINS."` WHERE `loginname`='".$loginname."'");
				$password = addslashes ( $_POST['password'] ) ;
				$email = $idna->encode ( addslashes ( $_POST['email'] ) ) ;
				$def_language = addslashes($_POST['def_language']);
				$customers = intval_ressource ( $_POST['customers'] ) ;
				$domains = intval_ressource ( $_POST['domains'] ) ;
				$subdomains = intval_ressource ( $_POST['subdomains'] ) ;
				$emails = intval_ressource ( $_POST['emails'] ) ;
				$email_accounts = intval_ressource ( $_POST['email_accounts'] ) ;
				$email_forwarders = intval_ressource ( $_POST['email_forwarders'] ) ;
				$ftps = intval_ressource ( $_POST['ftps'] ) ;
				$mysqls = intval_ressource ( $_POST['mysqls'] ) ;
				$customers_see_all = intval ( $_POST['customers_see_all'] ) ;
				$domains_see_all = intval ( $_POST['domains_see_all'] ) ;
				$change_serversettings = intval ( $_POST['change_serversettings'] ) ;

				$diskspace = intval_ressource ( $_POST['diskspace'] ) ;
				$traffic = doubleval_ressource ( $_POST['traffic'] ) ;
				$diskspace = $diskspace * 1024 ;
				$traffic = $traffic * 1024 * 1024 ;

				if($loginname == '')
				{
					standard_error(array('stringisempty','myloginname'));
				}
				elseif($loginname_check['loginname'] == $loginname)
				{
					standard_error('loginnameexists',$loginname);
				}
				elseif(!check_username($loginname))
				{
					standard_error('loginnameiswrong',$loginname);
				}
				elseif($name == '')
				{
					standard_error(array('stringisempty','myname'));
				}
				elseif($email == '')
				{
					standard_error(array('stringisempty','emailadd'));
				}
				elseif($password == '')
				{
					standard_error(array('stringisempty','mypassword'));
				}
				elseif(!verify_email($email))
				{
					standard_error('emailiswrong',$email);
				}

				else
				{
					if($customers_see_all != '1')
					{
						$customers_see_all = '0';
					}
					if($domains_see_all != '1')
					{
						$domains_see_all = '0';
					}
					if($change_serversettings != '1')
					{
						$change_serversettings = '0';
					}

					$result=$db->query("INSERT INTO `".TABLE_PANEL_ADMINS."` (`loginname`, `password`, `name`, `email`, `def_language`, `change_serversettings`, `customers`, `customers_see_all`, `domains`, `domains_see_all`, `diskspace`, `traffic`, `subdomains`, `emails`, `email_accounts`, `email_forwarders`, `ftps`, `mysqls`)
					                   VALUES ('$loginname', '".md5($password)."', '$name', '$email','$def_language', '$change_serversettings', '$customers', '$customers_see_all', '$domains', '$domains_see_all', '$diskspace', '$traffic', '$subdomains', '$emails', '$email_accounts', '$email_forwarders', '$ftps', '$mysqls')");
					$adminid=$db->insert_id();
					redirectTo ( $config->get('env.filename'), 
					             array( 'page' => $config->get('env.page') , 
					                    's'    => $config->get('env.s' ) ) );
				}
			}
			else
			{
				$language_options = '';
				$languages = $language->getList();
				while(list($language_file, $language_name) = each($languages))
				{
					$language_options .= makeoption($language_name, $language_file, $userinfo['language']);
				}
				$change_serversettings=makeyesno('change_serversettings', '1', '0', '0');
				$customers_see_all=makeyesno('customers_see_all', '1', '0', '0');
				$domains_see_all=makeyesno('domains_see_all', '1', '0', '0');
				eval("echo \"".getTemplate("admins/admins_add")."\";");
			}
		}

		elseif($config->get('env.action')=='edit' && $config->get('env.id')!=0)
		{
			if($config->get('env.id') == '1')
			{
				standard_error('youcantdeletechangemainadmin');
				exit;
			}
			$result=$db->query_first("SELECT * FROM `".TABLE_PANEL_ADMINS."` WHERE `adminid`='".$config->get('env.id')."'");
			if($result['loginname']!='')
			{
				if(isset($_POST['send']) && $_POST['send']=='send')
				{
					$name = addslashes ( $_POST['name'] ) ;
					$newpassword = addslashes ( $_POST['newpassword'] ) ;
					$email = $idna->encode ( addslashes ( $_POST['email'] ) ) ;
					$def_language = addslashes($_POST['def_language']);
					$deactivated = intval ( $_POST['deactivated'] ) ;
					$customers = intval_ressource ( $_POST['customers'] ) ;
					$domains = intval_ressource ( $_POST['domains'] ) ;
					$subdomains = intval_ressource ( $_POST['subdomains'] ) ;
					$emails = intval_ressource ( $_POST['emails'] ) ;
					$email_accounts = intval_ressource ( $_POST['email_accounts'] ) ;
					$email_forwarders = intval_ressource ( $_POST['email_forwarders'] ) ;
					$ftps = intval_ressource ( $_POST['ftps'] ) ;
					$mysqls = intval_ressource ( $_POST['mysqls'] ) ;
					$customers_see_all = intval ( $_POST['customers_see_all'] ) ;
					$domains_see_all = intval ( $_POST['domains_see_all'] ) ;
					$change_serversettings = intval ( $_POST['change_serversettings'] ) ;

					$diskspace = intval ( $_POST['diskspace'] ) ;
					$traffic = doubleval_ressource ( $_POST['traffic'] ) ;
					$diskspace = $diskspace * 1024 ;
					$traffic = $traffic * 1024 * 1024 ;

					if($name == '')
					{
						standard_error(array('stringisempty','myname'));
					}
					elseif($email == '')
					{
						standard_error(array('stringisempty','emailadd'));
					}
					elseif(!verify_email($email))
					{
						standard_error('emailiswrong',$email);
					}
					else
					{
						$updatepassword='';
						if($newpassword!='')
						{
							$updatepassword="`password`='".md5($newpassword)."', ";
						}

						if($deactivated != '1')
						{
							$deactivated = '0';
						}
						
						if($customers_see_all != '1')
						{
							$customers_see_all = '0';
						}
						if($domains_see_all != '1')
						{
							$domains_see_all = '0';
						}
						if($change_serversettings != '1')
						{
							$change_serversettings = '0';
						}

						$db->query("UPDATE `".TABLE_PANEL_ADMINS."` SET `name`='$name', `email`='$email', `def_language`='$def_language', `change_serversettings` = '$change_serversettings', `customers` = '$customers', `customers_see_all` = '$customers_see_all', `domains` = '$domains', `domains_see_all` = '$domains_see_all', $updatepassword `diskspace`='$diskspace', `traffic`='$traffic', `subdomains`='$subdomains', `emails`='$emails', `email_accounts` = '$email_accounts', `email_forwarders`='$email_forwarders', `ftps`='$ftps', `mysqls`='$mysqls', `deactivated`='$deactivated' WHERE `adminid`='".$config->get('env.id')."'");

    					redirectTo ( $config->get('env.filename') , 
    					             array( 'page' => $config->get('env.page') , 
    					                    's'    => $config->get('env.s') ) ) ;
					}
				}
				else
				{
					$result['traffic']=$result['traffic']/(1024*1024);
					$result['diskspace']=$result['diskspace']/1024;
					$result['email'] = $idna->decode($result['email']);
					$language_options = '';
					$languages = $language->getList();
					while(list($language_file, $language_name) = each($languages))
					{
						$language_options .= makeoption($language_name, $language_file, $result['def_language']);
					}
					$change_serversettings=makeyesno('change_serversettings', '1', '0', $result['change_serversettings']);
					$customers_see_all=makeyesno('customers_see_all', '1', '0', $result['customers_see_all']);
					$domains_see_all=makeyesno('domains_see_all', '1', '0', $result['domains_see_all']);
					$deactivated=makeyesno('deactivated', '1', '0', $result['deactivated']);
					eval("echo \"".getTemplate("admins/admins_edit")."\";");
				}
			}
		}
	}

?>
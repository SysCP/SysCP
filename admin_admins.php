<?php
/**
 * filename: $Source$
 * begin: Wednesday, Aug 11, 2004
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

	define('AREA', 'admin');

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

	if($page=='admins' && $userinfo['change_serversettings'] == '1' )
	{
		if($action=='')
		{
			$fields = array(
								'loginname' => $lng['login']['username'],
								'name' => $lng['customer']['name'],
								'diskspace' => $lng['customer']['diskspace'],
								'diskspace_used' => $lng['customer']['diskspace'] . ' (' . $lng['panel']['used'] . ')',
								'traffic' => $lng['customer']['traffic'],
								'traffic_used' => $lng['customer']['traffic'] . ' (' . $lng['panel']['used'] . ')',
								'mysqls' => $lng['customer']['mysqls'],
								'mysqls_used' => $lng['customer']['mysqls'] . ' (' . $lng['panel']['used'] . ')',
								'ftps' => $lng['customer']['ftps'],
								'ftps_used' => $lng['customer']['ftps'] . ' (' . $lng['panel']['used'] . ')',
								'subdomains' => $lng['customer']['subdomains'],
								'subdomains_used' => $lng['customer']['subdomains'] . ' (' . $lng['panel']['used'] . ')',
								'emails' => $lng['customer']['emails'],
								'emails_used' => $lng['customer']['emails'] . ' (' . $lng['panel']['used'] . ')',
								'email_accounts' => $lng['customer']['accounts'],
								'email_accounts_used' => $lng['customer']['accounts'] . ' (' . $lng['panel']['used'] . ')',
								'email_forwarders' => $lng['customer']['forwarders'],
								'email_forwarders_used' => $lng['customer']['forwarders'] . ' (' . $lng['panel']['used'] . ')',
								'deactivated' => $lng['admin']['deactivated']
							);
			$paging = new paging( $userinfo, $db, TABLE_PANEL_ADMINS, $fields, $settings['panel']['paging'] );

			$admins='';
			$result=$db->query("SELECT * FROM `".TABLE_PANEL_ADMINS."` " .
				$paging->getSqlWhere( false )." ".$paging->getSqlOrderBy()." ".$paging->getSqlLimit()
			);
			$paging->setEntries( $db->num_rows($result) );

			$sortcode = $paging->getHtmlSortCode( $lng, true );
			$arrowcode = $paging->getHtmlArrowCode( $filename . '?page=' . $page . '&amp;s=' . $s );
			$searchcode = $paging->getHtmlSearchCode( $lng );
			$pagingcode = $paging->getHtmlPagingCode( $filename . '?page=' . $page . '&amp;s=' . $s );

			$i = 0;
			$count = 0;
			while($row=$db->fetch_array($result))
			{
				if( $paging->checkDisplay( $i ) )
				{
					$row['traffic_used']=round($row['traffic_used']/(1024*1024),4);
					$row['traffic']=round($row['traffic']/(1024*1024),4);
					$row['diskspace_used']=round($row['diskspace_used']/1024,2);
					$row['diskspace']=round($row['diskspace']/1024,2);

					$row = str_replace_array('-1', 'UL', $row, 'customers domains diskspace traffic mysqls emails email_accounts email_forwarders ftps subdomains');

					$row = htmlentities_array( $row );
					eval("\$admins.=\"".getTemplate("admins/admins_admin")."\";");

					$count++;
				}
				$i++;
			}
			eval("echo \"".getTemplate("admins/admins")."\";");
		}
		elseif($action=='su' && $id != 1 && $userinfo['userid'] == '1')
		{
			$result=$db->query_first("SELECT * FROM `".TABLE_PANEL_ADMINS."` WHERE `adminid` = '$id'; ");
			if($result['loginname'] != '')
			{
				$result=$db->query_first("SELECT * FROM `".TABLE_PANEL_SESSIONS."` WHERE `userid`={$userinfo['userid']}");
				$s = md5(uniqid(microtime(),1));
				$db->query("INSERT INTO `".TABLE_PANEL_SESSIONS."` (`hash`, `userid`, `ipaddress`, `useragent`, `lastactivity`, `language`, `adminsession`) VALUES ('$s', '$id', '{$result['ipaddress']}', '{$result['useragent']}', '" . time() . "', '{$result['language']}', '1')");
				redirectTo ( 'admin_index.php' , Array ( 's' => $s ) ) ;
			}
			else
			{
				redirectTo ( 'index.php' , Array ( 'action' => 'login' ) ) ;
			}
		}
		elseif($action=='delete' && $id!=0)
		{
			if($id == '1')
			{
				standard_error('youcantdeletechangemainadmin');
				exit;
			}
			$result=$db->query_first("SELECT * FROM `".TABLE_PANEL_ADMINS."` WHERE `adminid`='$id'");
			if($result['loginname']!='')
			{
				if(isset($_POST['send']) && $_POST['send']=='send')
				{
					$db->query("DELETE FROM `".TABLE_PANEL_ADMINS."` WHERE `adminid`='$id'");
					$db->query("DELETE FROM `".TABLE_PANEL_TRAFFIC_ADMINS."` WHERE `adminid`='$id'");
					$db->query("UPDATE `".TABLE_PANEL_CUSTOMERS."` SET `adminid` = '1' WHERE `adminid` = '$id'");
					$db->query("UPDATE `".TABLE_PANEL_DOMAINS."` SET `adminid` = '1' WHERE `adminid` = '$id'");
					updateCounters () ;

					redirectTo ( $filename , Array ( 'page' => $page , 's' => $s ) ) ;
				}
				else
				{
					ask_yesno('admin_admin_reallydelete', $filename, array( 'id' => $id, 'page' => $page, 'action' => $action ), $result['loginname']);
				}
			}
		}

		elseif($action=='add')
		{
			if(isset($_POST['send']) && $_POST['send']=='send')
			{
				$name = addslashes ( $_POST['name'] ) ;
				$loginname = addslashes ( $_POST['loginname'] ) ;
				$loginname_check = $db->query_first("SELECT `loginname` FROM `".TABLE_PANEL_ADMINS."` WHERE `loginname`='".$loginname."'");
				$password = addslashes ( $_POST['password'] ) ;
				$email = $idna_convert->encode ( addslashes ( $_POST['email'] ) ) ;
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
					redirectTo ( $filename , Array ( 'page' => $page , 's' => $s ) ) ;
				}
			}
			else
			{
				$language_options = '';
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

		elseif($action=='edit' && $id!=0)
		{
			if($id == '1')
			{
				standard_error('youcantdeletechangemainadmin');
				exit;
			}
			$result=$db->query_first("SELECT * FROM `".TABLE_PANEL_ADMINS."` WHERE `adminid`='$id'");
			if($result['loginname']!='')
			{
				if(isset($_POST['send']) && $_POST['send']=='send')
				{
					$name = addslashes ( $_POST['name'] ) ;
					$newpassword = addslashes ( $_POST['newpassword'] ) ;
					$email = $idna_convert->encode ( addslashes ( $_POST['email'] ) ) ;
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

						$db->query("UPDATE `".TABLE_PANEL_ADMINS."` SET `name`='$name', `email`='$email', `def_language`='$def_language', `change_serversettings` = '$change_serversettings', `customers` = '$customers', `customers_see_all` = '$customers_see_all', `domains` = '$domains', `domains_see_all` = '$domains_see_all', $updatepassword `diskspace`='$diskspace', `traffic`='$traffic', `subdomains`='$subdomains', `emails`='$emails', `email_accounts` = '$email_accounts', `email_forwarders`='$email_forwarders', `ftps`='$ftps', `mysqls`='$mysqls', `deactivated`='$deactivated' WHERE `adminid`='$id'");

					redirectTo ( $filename , Array ( 'page' => $page , 's' => $s ) ) ;
					}
				}
				else
				{
					$result['traffic']=$result['traffic']/(1024*1024);
					$result['diskspace']=$result['diskspace']/1024;
					$result['email'] = $idna_convert->decode($result['email']);
					$language_options = '';
					while(list($language_file, $language_name) = each($languages))
					{
						$language_options .= makeoption($language_name, $language_file, $result['def_language']);
					}
					$change_serversettings=makeyesno('change_serversettings', '1', '0', $result['change_serversettings']);
					$customers_see_all=makeyesno('customers_see_all', '1', '0', $result['customers_see_all']);
					$domains_see_all=makeyesno('domains_see_all', '1', '0', $result['domains_see_all']);
					$deactivated=makeyesno('deactivated', '1', '0', $result['deactivated']);

					$result = htmlentities_array( $result );
					eval("echo \"".getTemplate("admins/admins_edit")."\";");
				}
			}
		}
	}

?>
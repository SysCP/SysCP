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

	if($page=='customers' && $userinfo['customers'] != '0' )
	{
		if($action=='')
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

			$customers='';
			$result=$db->query("SELECT * FROM `".TABLE_PANEL_CUSTOMERS."` ".( $userinfo['customers_see_all'] ? '' : " WHERE `adminid` = '{$userinfo['adminid']}' " )."ORDER BY `$sortby` $sortorder");
			while($row=$db->fetch_array($result))
			{
				$row['traffic_used']=round($row['traffic_used']/(1024*1024),4);
				$row['traffic']=round($row['traffic']/(1024*1024),4);
				$row['diskspace_used']=round($row['diskspace_used']/1024,2);
				$row['diskspace']=round($row['diskspace']/1024,2);
				$row['deactivated'] = str_replace('0', $lng['panel']['yes'], $row['deactivated']);
				$row['deactivated'] = str_replace('1', $lng['panel']['no'], $row['deactivated']);

				$row = str_replace_array('-1', 'UL', $row, 'diskspace traffic mysqls emails email_forwarders ftps subdomains');

				eval("\$customers.=\"".getTemplate("customers/customers_customer")."\";");
			}
			eval("echo \"".getTemplate("customers/customers")."\";");
		}

		elseif($action=='delete' && $id!=0)
		{
			$result=$db->query_first("SELECT * FROM `".TABLE_PANEL_CUSTOMERS."` WHERE `customerid`='$id' ".( $userinfo['customers_see_all'] ? '' : " AND `adminid` = '{$userinfo['adminid']}' "));
			if($result['loginname']!='')
			{
				if(isset($_POST['send']) && $_POST['send']=='send')
				{
					$databases=$db->query("SELECT * FROM ".TABLE_PANEL_DATABASES." WHERE customerid='$id'");
					$db_root=new db($sql['host'],$sql['root_user'],$sql['root_password'],'');
					unset($db_root->password);
					while($row_database=$db->fetch_array($databases))
					{
						$db_root->query('REVOKE ALL PRIVILEGES ON * . * FROM '.$row_database['databasename'].'@localhost;');
						$db_root->query('REVOKE ALL PRIVILEGES ON `'.$row_database['databasename'].'` . * FROM '.$row_database['databasename'].'@localhost;');
						$db_root->query('DELETE FROM `mysql`.`user` WHERE `User` = "'.$row_database['databasename'].'" AND `Host` = "localhost";');
						$db_root->query('DROP DATABASE IF EXISTS `'.$row_database['databasename'].'` ;');
					}
					$db_root->query('FLUSH PRIVILEGES;');
					$db_root->close();

					$db->query("DELETE FROM `".TABLE_PANEL_CUSTOMERS."` WHERE `customerid`='$id'");
					$db->query("DELETE FROM `".TABLE_PANEL_DATABASES."` WHERE `customerid`='$id'");
					$db->query("DELETE FROM `".TABLE_PANEL_DOMAINS."` WHERE `customerid`='$id'");
					$domains_deleted = $db->affected_rows();
					$db->query("DELETE FROM `".TABLE_PANEL_HTPASSWDS."` WHERE `customerid`='$id'");
					$db->query("DELETE FROM `".TABLE_PANEL_SESSIONS."` WHERE `userid`='$id' AND `adminsession` = '0'");
					$db->query("DELETE FROM `".TABLE_PANEL_TRAFFIC."` WHERE `customerid`='$id'");

					$db->query("DELETE FROM `".TABLE_POSTFIX_TRANSPORT."` WHERE `customerid`='$id'");
					$db->query("DELETE FROM `".TABLE_POSTFIX_USERS."` WHERE `customerid`='$id'");
					$db->query("DELETE FROM `".TABLE_POSTFIX_VIRTUAL."` WHERE `customerid`='$id'");

					$db->query("DELETE FROM `".TABLE_PROFTPD_GROUPS."` WHERE `customerid`='$id'");
					$db->query("DELETE FROM `".TABLE_PROFTPD_USERS."` WHERE `customerid`='$id'");

					$db->query("UPDATE `".TABLE_PANEL_ADMINS."` SET `customers_used` = `customers_used` - 1,".
						" `domains_used` = `domains_used` - 0".($domains_deleted - $result['subdomains_used']).",".
						" `mysqls_used` = `mysqls_used` - 0".$result['mysqls'].",".
						" `emails_used` = `emails_used` - 0".$result['emails'].",".
						" `email_forwarders_used` = `email_forwarders_used` - 0".$result['email_forwarders'].",".
						" `subdomains_used` = `subdomains_used` - 0".$result['subdomains'].",".
						" `ftps_used` = `ftps_used` - 0".$result['ftps'].",".
						" `traffic_used` = `traffic_used` - 0".$result['traffic'].",".
						" `diskspace_used` = `diskspace_used` - 0".$result['diskspace'].
						" WHERE `adminid` = '{$userinfo['adminid']}'");

					inserttask('1');
					inserttask('4');

					header("Location: $filename?page=$page&s=$s");
				}
				else {
					ask_yesno('admin_customer_reallydelete', $filename, "id=$id;page=$page;action=$action");
				}
			}
		}

		elseif($action=='add')
		{
			if($userinfo['customers_used'] < $userinfo['customers'] || $userinfo['customers'] == '-1')
			{
				if(isset($_POST['send']) && $_POST['send']=='send')
				{
					$name=addslashes($_POST['name']);
					$surname=addslashes($_POST['surname']);
					$company=addslashes($_POST['company']);
					$street=addslashes($_POST['street']);
					$zipcode=addslashes($_POST['zipcode']);
					$city=addslashes($_POST['city']);
					$phone=addslashes($_POST['phone']);
					$fax=addslashes($_POST['fax']);
					$email=addslashes($_POST['email']);
					$customernumber=addslashes($_POST['customernumber']);
					$diskspace=intval($_POST['diskspace']);
					$traffic=doubleval($_POST['traffic']);
					$subdomains=intval($_POST['subdomains']);
					$emails=intval($_POST['emails']);
					$email_forwarders=intval($_POST['email_forwarders']);
					$ftps=intval($_POST['ftps']);
					$mysqls=intval($_POST['mysqls']);
					$createstdsubdomain=intval($_POST['createstdsubdomain']);
					$password=addslashes($_POST['password']);
					$sendpassword=intval($_POST['sendpassword']);
				
					$diskspace=$diskspace*1024;
					$traffic=$traffic*1024*1024;

					if( ( ( ($userinfo['diskspace_used'] + $diskspace) > $userinfo['diskspace']) && ($userinfo['diskspace']/1024) != '-1') || 
					    ( ( ($userinfo['mysqls_used'] + $mysqls) > $userinfo['mysqls']) && $userinfo['mysqls'] != '-1') || 
					    ( ( ($userinfo['emails_used'] + $emails) > $userinfo['emails']) && $userinfo['emails'] != '-1') || 
					    ( ( ($userinfo['email_forwarders_used'] + $email_forwarders) > $userinfo['email_forwarders']) && $userinfo['email_forwarders'] != '-1') || 
					    ( ( ($userinfo['ftps_used'] + $ftps) > $userinfo['ftps']) && $userinfo['ftps'] != '-1') || 
					    ( ( ($userinfo['subdomains_used'] + $subdomains) > $userinfo['subdomains']) && $userinfo['subdomains'] != '-1') || 
					    ( ( ($userinfo['traffic_used'] + $traffic) > $userinfo['traffic']) && ($userinfo['traffic']/(1024*1024)) != '-1')
					  )
					{
						standard_error('youcantallocatemorethanyouhave');
						exit;
					}

					if($name=='' || $surname=='' || /*$company=='' || $street=='' || $zipcode=='' || $city=='' || $phone=='' || $fax=='' || $customernumber=='' ||*/ $email=='' || !verify_email($email) || ($settings['customer']['loginnamestyle'] == 'dynamic' && $_POST['loginname'] == '') )
					{
						standard_error('notallreqfieldsorerrors');
						exit;
					}
					else
					{
						if(isset($_POST['loginname']) && $_POST['loginname'] != '')
						{
							$loginname = addslashes($_POST['loginname']);
							$loginname_check = $db->query_first("SELECT `loginname` FROM `".TABLE_PANEL_CUSTOMERS."` WHERE `loginname`='".$loginname."'");
							if($loginname_check['loginname'] == $loginname)
							{
								standard_error('notallreqfieldsorerrors');
							}
							$accountnumber=intval($settings['system']['lastaccountnumber']);
						}
						else
						{
							$accountnumber=intval($settings['system']['lastaccountnumber'])+1;
							$loginname = $settings['customer']['accountprefix'].$accountnumber;
						}

						$guid=intval($settings['system']['lastguid'])+1;
						$documentroot = $settings['system']['documentroot_prefix'].$loginname;

						if($createstdsubdomain != '1')
						{
							$createstdsubdomain = '0';
						}

						if($password == '')
						{
							$password=substr(md5(uniqid(microtime(),1)),12,6);
						}

						$result=$db->query("INSERT INTO ".TABLE_PANEL_CUSTOMERS."(`adminid`, `loginname`, `password`, `name`, `surname`, `company`, `street`, `zipcode`, `city`, `phone`, `fax`, `email`, `customernumber`, `documentroot`, `guid`, `diskspace`, `traffic`, `subdomains`, `emails`, `email_forwarders`, `ftps`, `mysqls`, `createstdsubdomain`) VALUES('{$userinfo['adminid']}', '$loginname', '".md5($password)."', '$name', '$surname', '$company', '$street', '$zipcode', '$city', '$phone', '$fax', '$email', '$customernumber', '$documentroot', '$guid', '$diskspace', '$traffic', '$subdomains', '$emails', '$email_forwarders', '$ftps', '$mysqls', '$createstdsubdomain')");
						$customerid=$db->insert_id();

						$db->query("UPDATE `".TABLE_PANEL_ADMINS."` SET `customers_used` = `customers_used` + 1,".
							" `mysqls_used` = `mysqls_used` + 0".$mysqls.",".
							" `emails_used` = `emails_used` + 0".$emails.",".
							" `email_forwarders_used` = `email_forwarders_used` + 0".$email_forwarders.",".
							" `subdomains_used` = `subdomains_used` + 0".$subdomains.",".
							" `ftps_used` = `ftps_used` + 0".$ftps.",".
							" `traffic_used` = `traffic_used` + 0".$traffic.",".
							" `diskspace_used` = `diskspace_used` + 0".$diskspace.
							" WHERE `adminid` = '{$userinfo['adminid']}'");

						$db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='$guid' WHERE `settinggroup`='system' AND `varname`='lastguid'");
						$db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='$accountnumber' WHERE `settinggroup`='system' AND `varname`='lastaccountnumber'");

						inserttask('2',$loginname,$guid,$guid);

						$result=$db->query("INSERT INTO `".TABLE_PROFTPD_USERS."` (`customerid`, `username`, `password`, `homedir`, `login_enabled`, `uid`, `gid`) VALUES ('$customerid', '$loginname', '$password', '$documentroot/', 'y', '$guid', '$guid')");
						$result=$db->query("INSERT INTO `".TABLE_PROFTPD_GROUPS."` (`customerid`, `groupname`, `gid`, `members`) VALUES ('$customerid', '$loginname', '$guid', '$loginname')");

						if($sendpassword == '1')
						{
							eval("\$mail_subject=\"".$lng['mails']['createcustomer']['subject']."\";");
							eval("\$mail_body=\"".$lng['mails']['createcustomer']['mailbody']."\";");
							mail("$surname $name <$email>",$mail_subject,$mail_body,"From: {$settings['panel']['adminmail']} <{$settings['panel']['adminmail']}>\r\n");
						}

						header("Location: $filename?page=$page&s=$s");
					}
				}
				else
				{
					$createstdsubdomain=makeyesno('createstdsubdomain', '1', '0', '1');
					$sendpassword=makeyesno('sendpassword', '1', '0', '1');
					eval("echo \"".getTemplate("customers/customers_add")."\";");
				}
			}
		}

		elseif($action=='edit' && $id!=0)
		{
			$result=$db->query_first("SELECT * FROM `".TABLE_PANEL_CUSTOMERS."` WHERE `customerid`='$id' ".( $userinfo['customers_see_all'] ? '' : " AND `adminid` = '{$userinfo['adminid']}' ") );
			if($result['loginname']!='')
			{
				if(isset($_POST['send']) && $_POST['send']=='send')
				{
					$name=addslashes($_POST['name']);
					$surname=addslashes($_POST['surname']);
					$company=addslashes($_POST['company']);
					$street=addslashes($_POST['street']);
					$zipcode=addslashes($_POST['zipcode']);
					$city=addslashes($_POST['city']);
					$phone=addslashes($_POST['phone']);
					$fax=addslashes($_POST['fax']);
					$email=addslashes($_POST['email']);
					$customernumber=addslashes($_POST['customernumber']);
					$newpassword=$_POST['newpassword'];
					$diskspace=intval($_POST['diskspace']);
					$traffic=doubleval($_POST['traffic']);
					$subdomains=intval($_POST['subdomains']);
					$emails=intval($_POST['emails']);
					$email_forwarders=intval($_POST['email_forwarders']);
					$ftps=intval($_POST['ftps']);
					$mysqls=intval($_POST['mysqls']);
					$createstdsubdomain=intval($_POST['createstdsubdomain']);
					$deactivated=intval($_POST['deactivated']);

					$diskspace=$diskspace*1024;
					$traffic=$traffic*1024*1024;

					if( ( ( ($userinfo['diskspace_used']        + $diskspace        - $result['diskspace'])        > $userinfo['diskspace'])        && ($userinfo['diskspace']/1024) != '-1') || 
					    ( ( ($userinfo['mysqls_used']           + $mysqls           - $result['mysqls'])           > $userinfo['mysqls'])           && $userinfo['mysqls'] != '-1') || 
					    ( ( ($userinfo['emails_used']           + $emails           - $result['emails'])           > $userinfo['emails'])           && $userinfo['emails'] != '-1') || 
					    ( ( ($userinfo['email_forwarders_used'] + $email_forwarders - $result['email_forwarders']) > $userinfo['email_forwarders']) && $userinfo['email_forwarders'] != '-1') || 
					    ( ( ($userinfo['ftps_used']             + $ftps             - $result['ftps'])             > $userinfo['ftps'])             && $userinfo['ftps'] != '-1') || 
					    ( ( ($userinfo['subdomains_used']       + $subdomains       - $result['subdomains'])       > $userinfo['subdomains'])       && $userinfo['subdomains'] != '-1') || 
					    ( ( ($userinfo['traffic_used']          + $traffic          - $result['traffic'])          > $userinfo['traffic'])          && ($userinfo['traffic']/(1024*1024)) != '-1')
					  )
					{
						standard_error('youcantallocatemorethanyouhave');
						exit;
					}

					if($name=='' || $surname=='' || /*$company=='' || $street=='' || $zipcode=='' || $city=='' || $phone=='' || $fax=='' || $customernumber=='' ||*/ $email=='' || !verify_email($email) )
					{
						standard_error('notallreqfieldsorerrors');
						exit;
					}
					else
					{
						$updatepassword='';
						if($newpassword!='')
						{
							$updatepassword="`password`='".md5($newpassword)."', ";
						}

						if($createstdsubdomain != '1')
						{
							$createstdsubdomain = '0';
						}
						if($createstdsubdomain != $result['createstdsubdomain'])
						{
							inserttask('1');
						}

						if($deactivated != '1')
						{
							$deactivated = '0';
						}
						if($deactivated != $result['deactivated'])
						{
							$db->query("UPDATE `".TABLE_POSTFIX_USERS."` SET `postfix`='".( ($deactivated) ? 'N' : 'Y' )."' WHERE `customerid`='$id'");
							$db->query("UPDATE `".TABLE_PROFTPD_USERS."` SET `login_enabled`='".( ($deactivated) ? 'N' : 'Y' )."' WHERE `customerid`='$id'");
							$db->query("UPDATE `".TABLE_PANEL_DOMAINS."` SET `deactivated`='$deactivated' WHERE `customerid`='$id'");
							inserttask('1');
						}

						$db->query("UPDATE `".TABLE_PANEL_CUSTOMERS."` SET `name`='$name', `surname`='$surname', `company`='$company', `street`='$street', `zipcode`='$zipcode', `city`='$city', `phone`='$phone', `fax`='$fax', `email`='$email', `customernumber`='$customernumber', $updatepassword `diskspace`='$diskspace', `traffic`='$traffic', `subdomains`='$subdomains', `emails`='$emails', `email_forwarders`='$email_forwarders', `ftps`='$ftps', `mysqls`='$mysqls', `createstdsubdomain`='$createstdsubdomain', `deactivated`='$deactivated' WHERE `customerid`='$id'");

						$db->query("UPDATE `".TABLE_PANEL_ADMINS."` SET ".
							" `mysqls_used` = `mysqls_used` + 0".($mysqls)." - 0".($result['mysqls']).",".
							" `emails_used` = `emails_used` + 0".($emails)." - 0".($result['emails']).",".
							" `email_forwarders_used` = `email_forwarders_used` + 0".($email_forwarders)." - 0".($result['email_forwarders']).",".
							" `subdomains_used` = `subdomains_used` + 0".($subdomains)." - 0".($result['subdomains']).",".
							" `ftps_used` = `ftps_used` + 0".($ftps)." - 0".($result['ftps']).",".
							" `traffic_used` = `traffic_used` + 0".($traffic)." - 0".($result['traffic']).",".
							" `diskspace_used` = `diskspace_used` + 0".($diskspace)." - 0".($result['diskspace']).
							" WHERE `adminid` = '{$userinfo['adminid']}'");

						header("Location: $filename?page=$page&s=$s");
					}
				}
				else
				{
					$result['traffic']=$result['traffic']/(1024*1024);
					$result['diskspace']=$result['diskspace']/1024;
					$createstdsubdomain=makeyesno('createstdsubdomain', '1', '0', $result['createstdsubdomain']);
					$deactivated=makeyesno('deactivated', '1', '0', $result['deactivated']);
					eval("echo \"".getTemplate("customers/customers_edit")."\";");
				}
			}
		}
	}

?>

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

	if($page=='customers')
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
			$result=$db->query("SELECT * FROM `".TABLE_PANEL_CUSTOMERS."` ORDER BY `$sortby` $sortorder");
			while($row=$db->fetch_array($result))
			{
				$row['traffic_used']=round($row['traffic_used']/(1024*1024),4);
				$row['traffic']=round($row['traffic']/(1024*1024),4);
				$row['diskspace_used']=round($row['diskspace_used']/1024,2);
				$row['diskspace']=round($row['diskspace']/1024,2);
				$row['deactivated'] = str_replace('0', $lng['panel']['yes'], $row['deactivated']);
				$row['deactivated'] = str_replace('1', $lng['panel']['no'], $row['deactivated']);

				if($row['traffic_used'] > $row['traffic'] && $row['traffic'] != '-1')
				{
					$row['traffic_color']='red';
				}
				else
				{
					$row['traffic_color']='';
				}

				if($row['diskspace_used'] > $row['diskspace'] && $row['diskspace'] != '-1')
				{
					$row['diskspace_color']='red';
				}
				else
				{
					$row['diskspace_color']='';
				}

				$row = str_replace_array('-1', 'UL', $row, 'diskspace traffic mysqls emails email_forwarders ftps subdomains');

				eval("\$customers.=\"".getTemplate("customers/customers_customer")."\";");
			}
			eval("echo \"".getTemplate("customers/customers")."\";");
		}

		elseif($action=='delete' && $id!=0)
		{
			$result=$db->query_first("SELECT * FROM `".TABLE_PANEL_CUSTOMERS."` WHERE `customerid`='$id'");
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
					$db->query("DELETE FROM `".TABLE_PANEL_SESSIONS."` WHERE `customerid`='$id'");
					$db->query("DELETE FROM `".TABLE_PANEL_TRAFFIC."` WHERE `customerid`='$id'");

					$db->query("DELETE FROM `".TABLE_POSTFIX_TRANSPORT."` WHERE `customerid`='$id'");
					$db->query("DELETE FROM `".TABLE_POSTFIX_USERS."` WHERE `customerid`='$id'");
					$db->query("DELETE FROM `".TABLE_POSTFIX_VIRTUAL."` WHERE `customerid`='$id'");

					$db->query("DELETE FROM `".TABLE_PROFTPD_GROUPS."` WHERE `customerid`='$id'");
					$db->query("DELETE FROM `".TABLE_PROFTPD_USERS."` WHERE `customerid`='$id'");

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

				if($name=='' || $surname=='' || /*$company=='' || $street=='' || $zipcode=='' || $city=='' || $phone=='' || $fax=='' || $customernumber=='' ||*/ $email=='' || !verify_email($email) )
				{
					standard_error('notallreqfieldsorerrors');
					exit;
				}
				else
				{
					$diskspace=$diskspace*1024;
					$traffic=$traffic*1024*1024;

					$accountnumber=intval($settings['system']['lastaccountnumber'])+1;
					$guid=intval($settings['system']['lastguid'])+1;
					$documentroot=$settings['system']['documentroot_prefix'].$settings['customer']['accountprefix'].$accountnumber;
					$loginname=$settings['customer']['accountprefix'].$accountnumber;

					if($createstdsubdomain != '1')
					{
						$createstdsubdomain = '0';
					}

					if($password == '')
					{
						$password=substr(md5(uniqid(microtime(),1)),12,6);
					}

					$result=$db->query("INSERT INTO ".TABLE_PANEL_CUSTOMERS."(`loginname`, `password`, `name`, `surname`, `company`, `street`, `zipcode`, `city`, `phone`, `fax`, `email`, `customernumber`, `documentroot`, `guid`, `diskspace`, `traffic`, `subdomains`, `emails`, `email_forwarders`, `ftps`, `mysqls`, `createstdsubdomain`) VALUES('$loginname', '".md5($password)."', '$name', '$surname', '$company', '$street', '$zipcode', '$city', '$phone', '$fax', '$email', '$customernumber', '$documentroot', '$guid', '$diskspace', '$traffic', '$subdomains', '$emails', '$email_forwarders', '$ftps', '$mysqls', '$createstdsubdomain')");
					$customerid=$db->insert_id();

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

		elseif($action=='edit' && $id!=0)
		{
			$result=$db->query_first("SELECT * FROM `".TABLE_PANEL_CUSTOMERS."` WHERE `customerid`='$id'");
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

						$diskspace=$diskspace*1024;
						$traffic=$traffic*1024*1024;
						$result=$db->query("UPDATE `".TABLE_PANEL_CUSTOMERS."` SET `name`='$name', `surname`='$surname', `company`='$company', `street`='$street', `zipcode`='$zipcode', `city`='$city', `phone`='$phone', `fax`='$fax', `email`='$email', `customernumber`='$customernumber', $updatepassword `diskspace`='$diskspace', `traffic`='$traffic', `subdomains`='$subdomains', `emails`='$emails', `email_forwarders`='$email_forwarders', `ftps`='$ftps', `mysqls`='$mysqls', `createstdsubdomain`='$createstdsubdomain', `deactivated`='$deactivated' WHERE `customerid`='$id'");
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

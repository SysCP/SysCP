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
		eval("echo \"".getTemplate("email/email")."\";");
	}

	elseif($page=='pop')
	{
		if($action=='')
		{
			$result=$db->query("SELECT `id`, `email`, `customerid` FROM `".TABLE_MAIL_USERS."` WHERE `customerid`='".$userinfo['customerid']."' ORDER BY `email` ASC");
			$accounts='';
			$emails_count=0;
			while($row=$db->fetch_array($result))
			{
				eval("\$accounts.=\"".getTemplate("email/pop_account")."\";");
				$emails_count++;
			}
			$emaildomains_count=$db->query_first("SELECT COUNT(`id`) AS `count` FROM `".TABLE_PANEL_DOMAINS."` WHERE `customerid`='".$userinfo['customerid']."' AND `isemaildomain`='1' ORDER BY `domain` ASC");
			$emaildomains_count=$emaildomains_count['count'];

			eval("echo \"".getTemplate("email/pop")."\";");
		}

		elseif($action=='delete' && $id!=0)
		{
			$result=$db->query_first("SELECT `id`, `email`, `customerid` FROM `".TABLE_MAIL_USERS."` WHERE `customerid`='".$userinfo['customerid']."' AND `id`='$id'");
			if(isset($result['email']) && $result['email']!='')
			{
				if(isset($_POST['send']) && $_POST['send']=='send')
				{
					$db->query("DELETE FROM `".TABLE_MAIL_USERS."` WHERE `customerid`='".$userinfo['customerid']."' AND `id`='$id'");
					$db->query("DELETE FROM `".TABLE_MAIL_VIRTUAL."` WHERE `customerid`='".$userinfo['customerid']."' AND `popaccountid`='".$result['id']."'");
					$db->query("UPDATE `".TABLE_PANEL_CUSTOMERS."` SET `emails_used`=`emails_used`-1 WHERE `customerid`='".$userinfo['customerid']."'");
					header("Location: $filename?page=$page&s=$s");
				}
				else {
					ask_yesno('email_reallydelete_pop', $filename, "id=$id;page=$page;action=$action", $result['email']);
				}
			}
		}

		elseif($action=='add')
		{
			if($userinfo['emails_used'] < $userinfo['emails'] || $userinfo['emails'] == '-1')
			{
				if(isset($_POST['send']) && $_POST['send']=='send')
				{
					$email_part=addslashes($_POST['email_part']);
					$domain=addslashes($_POST['domain']);
					$domain_check=$db->query_first("SELECT `id`, `domain`, `customerid` FROM `".TABLE_PANEL_DOMAINS."` WHERE `domain`='$domain' AND `customerid`='".$userinfo['customerid']."'");
					$email = $email_part.'@'.$domain;
					$destination = $email;
					if(substr($email, 0, strlen($settings['email']['catchallkeyword']) + 1) == $settings['email']['catchallkeyword'].'@')
					{
						$email = str_replace($settings['email']['catchallkeyword'], '', $email);
					}
					$email_check=$db->query_first("SELECT `id`, `email`, `destination`, `customerid` FROM `".TABLE_MAIL_VIRTUAL."` WHERE `email`='$email' AND `customerid`='".$userinfo['customerid']."'");
					$password=addslashes($_POST['password']);
					if($email=='' || $email_part=='' || $domain== '' || $password=='' || $domain_check['domain']!=$domain || $email_check['email']==$email)
					{
						standard_error('notallreqfieldsorerrors');
						exit;
					}
					else
					{
						$db->query("INSERT INTO `".TABLE_MAIL_USERS."` (`customerid`, `email`, `password`, `password_enc`, `homedir`, `maildir`, `uid`, `gid`, `domainid`, `postfix`) VALUES ('".$userinfo['customerid']."', '$destination', '$password', ENCRYPT('$password'), '".$settings['system']['vmail_homedir']."', '".$userinfo['loginname']."/$destination/', '".$settings['system']['vmail_uid']."', '".$settings['system']['vmail_gid']."', '".$domain_check['id']."', 'y')");
						$popaccountid = $db->insert_id();
						$db->query("INSERT INTO `".TABLE_MAIL_VIRTUAL."` (`customerid`, `email`, `destination`, `domainid`, `popaccountid`) VALUES ('".$userinfo['customerid']."', '$email', '$destination', '".$domain_check['id']."', '$popaccountid')");
						$db->query("UPDATE `".TABLE_PANEL_CUSTOMERS."` SET `emails_used`=`emails_used`+1 WHERE `customerid`='".$userinfo['customerid']."'");
	
						eval("\$mail_subject=\"".$lng['mails']['pop_success']['subject']."\";");
						eval("\$mail_body=\"".$lng['mails']['pop_success']['mailbody']."\";");
						mail("$destination <$destination>",$mail_subject,$mail_body,"From: {$settings['panel']['adminmail']} <{$settings['panel']['adminmail']}>\r\n");
	
						header("Location: $filename?page=$page&s=$s");
					}
				}
				else {
					$result=$db->query("SELECT `id`, `domain`, `customerid` FROM `".TABLE_PANEL_DOMAINS."` WHERE `customerid`='".$userinfo['customerid']."' AND `isemaildomain`='1' ORDER BY `domain` ASC");
					$domains='';
					while($row=$db->fetch_array($result))
					{
						$domains.=makeoption($row['domain'],$row['domain']);
					}
					eval("echo \"".getTemplate("email/pop_add")."\";");
				}
			}
		}

		elseif($action=='edit' && $id!=0)
		{
			$result=$db->query_first("SELECT `id`, `email`, `customerid` FROM `".TABLE_MAIL_USERS."` WHERE `customerid`='".$userinfo['customerid']."' AND `id`='$id'");
			if(isset($result['email']) && $result['email']!='')
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
						$result=$db->query("UPDATE `".TABLE_MAIL_USERS."` SET `password` = '$password', `password_enc`=ENCRYPT('$password') WHERE `customerid`='".$userinfo['customerid']."' AND `id`='$id'");
						header("Location: $filename?page=$page&s=$s");
					}
				}
				else {
					eval("echo \"".getTemplate("email/pop_edit")."\";");
				}
			}
		}
	}

	elseif($page=='forwarders')
	{
		if($action=='')
		{
			$result=$db->query("SELECT `id`, `email`, `destination`, `popaccountid` FROM `".TABLE_MAIL_VIRTUAL."` WHERE `customerid`='".$userinfo['customerid']."' AND `popaccountid` = '0' ORDER BY `email` ASC");
			$accounts='';
			$forwarders_count=0;
			while($row=$db->fetch_array($result))
			{
				if($row['email']{0} == '@')
				{
					$row['email'] = $settings['email']['catchallkeyword'].$row['email'];
				}
				$forwarders_count++;
				eval("\$accounts.=\"".getTemplate("email/forwarders_forwarder")."\";");
			}
			$emaildomains_count=$db->query_first("SELECT COUNT(`id`) AS `count` FROM `".TABLE_PANEL_DOMAINS."` WHERE `customerid`='".$userinfo['customerid']."' AND `isemaildomain`='1' ORDER BY `domain` ASC");
			$emaildomains_count=$emaildomains_count['count'];

			eval("echo \"".getTemplate("email/forwarders")."\";");
		}

		elseif($action=='delete' && $id!=0)
		{
			$result=$db->query_first("SELECT `id`, `email`, `destination`, `customerid` FROM `".TABLE_MAIL_VIRTUAL."` WHERE `customerid`='".$userinfo['customerid']."' AND `id`='$id'");
			if(isset($result['email']) && $result['email']!='')
			{
				if(isset($_POST['send']) && $_POST['send']=='send')
				{
					$db->query("DELETE FROM `".TABLE_MAIL_VIRTUAL."` WHERE `customerid`='".$userinfo['customerid']."' AND `id`='$id'");
					$db->query("UPDATE `".TABLE_PANEL_CUSTOMERS."` SET `email_forwarders_used`=`email_forwarders_used`-1 WHERE `customerid`='".$userinfo['customerid']."'");
					header("Location: $filename?page=$page&s=$s");
				}
				else {
					ask_yesno('email_reallydelete_forwarders', $filename, "id=$id;page=$page;action=$action", $result['email'] . ' -> ' . $result['destination']);
				}
			}
		}

		elseif($action=='add')
		{
			if($userinfo['email_forwarders_used'] < $userinfo['email_forwarders'] || $userinfo['email_forwarders'] == '-1')
			{
				if(isset($_POST['send']) && $_POST['send']=='send')
				{
					$email_part=addslashes($_POST['email_part']);
					$domain=addslashes($_POST['domain']);
					$destination=addslashes($_POST['destination']);
					$domain_check=$db->query_first("SELECT `id`, `domain`, `customerid` FROM `".TABLE_PANEL_DOMAINS."` WHERE `domain`='$domain' AND `customerid`='".$userinfo['customerid']."'");
					$email=$email_part.'@'.$domain;
					if(substr($email, 0, strlen($settings['email']['catchallkeyword']) + 1) == $settings['email']['catchallkeyword'].'@')
					{
						$email = str_replace($settings['email']['catchallkeyword'], '', $email);
					}
					$email_check=$db->query_first("SELECT `id`, `email`, `destination`, `customerid` FROM `".TABLE_MAIL_VIRTUAL."` WHERE `email`='$email' AND `customerid`='".$userinfo['customerid']."'");
					if($email=='' || $email_part=='' || $domain=='' || $destination=='' || !verify_email($destination) || $domain_check['domain']!=$domain || $email_check['email'] == $email)
					{
						standard_error('notallreqfieldsorerrors');
						exit;
					}
					else
					{
						$db->query("INSERT INTO `".TABLE_MAIL_VIRTUAL."` (`customerid`, `email`, `destination`, `domainid`) VALUES ('".$userinfo['customerid']."', '$email', '$destination', '".$domain_check['id']."')");
						$db->query("UPDATE ".TABLE_PANEL_CUSTOMERS." SET `email_forwarders_used`=`email_forwarders_used`+1 WHERE `customerid`='".$userinfo['customerid']."'");
						header("Location: $filename?page=$page&s=$s");
					}
				}
				else {
					$result=$db->query("SELECT `id`, `domain`, `customerid` FROM `".TABLE_PANEL_DOMAINS."` WHERE `customerid`='".$userinfo['customerid']."' AND `isemaildomain`='1' ORDER BY `domain` ASC");
					$domains='';
					while($row=$db->fetch_array($result))
					{
						$domains.=makeoption($row['domain'],$row['domain']);
					}
					eval("echo \"".getTemplate("email/forwarders_add")."\";");
				}
			}
		}

	}

?>

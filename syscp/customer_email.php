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

	elseif($page=='emails')
	{
		if($action=='')
		{
			$result=$db->query("SELECT `id`, `email`, `destination`, `popaccountid` FROM `".TABLE_MAIL_VIRTUAL."` WHERE `customerid`='".$userinfo['customerid']."' ORDER BY `email` ASC");
			$accounts='';
			$emails_count=0;
			while($row=$db->fetch_array($result))
			{
				if($row['email']{0} == '@')
				{
					$row['email'] = $settings['email']['catchallkeyword'].$row['email'];
				}
				$emails_count++;
				$row['email'] = $idna_convert->decode($row['email']);
				$row['destination'] = explode ( ' ', $row['destination'] ) ;
				while ( list ( $dest_id , $destination ) = each ( $row['destination'] ) )
				{
					$row['destination'][$dest_id] = $idna_convert->decode($row['destination'][$dest_id]);
					if ( $row['destination'][$dest_id] == $row['email'] )
					{
						unset ( $row['destination'][$dest_id] ) ;
					}
				}
				$destinations_count = count ($row['destination']);
				$row['destination'] = implode ( ', ', $row['destination'] ) ;
				if ( strlen ($row['destination']) > 35 )
				{
					$row['destination'] = substr ( $row['destination'] , 0, 32 ) . '... (' . $destinations_count . ')' ;
				}
				eval("\$accounts.=\"".getTemplate("email/emails_email")."\";");
			}
			$emaildomains_count=$db->query_first("SELECT COUNT(`id`) AS `count` FROM `".TABLE_PANEL_DOMAINS."` WHERE `customerid`='".$userinfo['customerid']."' AND `isemaildomain`='1' ORDER BY `domain` ASC");
			$emaildomains_count=$emaildomains_count['count'];

			eval("echo \"".getTemplate("email/emails")."\";");
		}

		elseif($action=='delete' && $id!=0)
		{
			$result=$db->query_first("SELECT `id`, `email`, `destination`, `customerid`, `popaccountid` FROM `".TABLE_MAIL_VIRTUAL."` WHERE `customerid`='".$userinfo['customerid']."' AND `id`='$id'");
			if(isset($result['email']) && $result['email']!='')
			{
				if($result['email']{0} == '@')
				{
					$result['email'] = $settings['email']['catchallkeyword'].$result['email'];
				}

				if(isset($_POST['send']) && $_POST['send']=='send')
				{
					$update_users_query_addon = '';
					if ( $result['destination'] != '' )
					{
						$result['destination'] = explode ( ' ', $result['destination'] ) ;
						$number_forwarders = count ($result['destination']);
						if ( $result['popaccountid'] != 0 )
						{
							$db->query("DELETE FROM `".TABLE_MAIL_USERS."` WHERE `customerid`='".$userinfo['customerid']."' AND `id`='".$result['popaccountid']."'");
							$update_users_query_addon = " , `email_accounts_used` = `email_accounts_used` - 1 ";
							$number_forwarders -= 1;
						}
					}
					else
					{
						$number_forwarders = 0;
					}
					$db->query("DELETE FROM `".TABLE_MAIL_VIRTUAL."` WHERE `customerid`='".$userinfo['customerid']."' AND `id`='$id'");
					$db->query("UPDATE `".TABLE_PANEL_CUSTOMERS."` SET `emails_used`=`emails_used` - 1 , `email_forwarders_used` = `email_forwarders_used` - $number_forwarders $update_users_query_addon WHERE `customerid`='".$userinfo['customerid']."'");
					header("Location: $filename?page=$page&s=$s");
				}
				else
				{
					ask_yesno('email_reallydelete', $filename, "id=$id;page=$page;action=$action", $idna_convert->decode($result['email']));
				}
			}
		}

		elseif($action=='add')
		{
			if($userinfo['emails_used'] < $userinfo['emails'] || $userinfo['emails'] == '-1')
			{
				if(isset($_POST['send']) && $_POST['send']=='send')
				{
					$email_part = addslashes($_POST['email_part']);
					$domain = $idna_convert->encode(addslashes($_POST['domain']));
					$domain_check = $db->query_first("SELECT `id`, `domain`, `customerid` FROM `".TABLE_PANEL_DOMAINS."` WHERE `domain`='$domain' AND `customerid`='".$userinfo['customerid']."'");
					if($email_part == $settings['email']['catchallkeyword'])
					{
						$email = '@' . $domain ;
					}
					else
					{
						$email = $email_part . '@' . $domain ;
					}
					$email_check=$db->query_first("SELECT `id`, `email`, `destination`, `customerid` FROM `".TABLE_MAIL_VIRTUAL."` WHERE `email`='$email' AND `customerid`='".$userinfo['customerid']."'");
					if($email=='' || $email_part=='' || $domain=='' || $domain_check['domain']!=$domain || $email_check['email'] == $email || !verify_email($email_part . '@' . $domain))
					{
						standard_error('notallreqfieldsorerrors');
						exit;
					}
					else
					{
						$db->query("INSERT INTO `".TABLE_MAIL_VIRTUAL."` (`customerid`, `email`, `domainid`) VALUES ('".$userinfo['customerid']."', '$email', '".$domain_check['id']."')");
						$db->query("UPDATE ".TABLE_PANEL_CUSTOMERS." SET `emails_used` = `emails_used` + 1 WHERE `customerid`='".$userinfo['customerid']."'");
						header("Location: $filename?page=$page&s=$s");
					}
				}
				else
				{
					$result=$db->query("SELECT `id`, `domain`, `customerid` FROM `".TABLE_PANEL_DOMAINS."` WHERE `customerid`='".$userinfo['customerid']."' AND `isemaildomain`='1' ORDER BY `domain` ASC");
					$domains='';
					while($row=$db->fetch_array($result))
					{
						$domains.=makeoption($idna_convert->decode($row['domain']),$row['domain']);
					}
					eval("echo \"".getTemplate("email/emails_add")."\";");
				}
			}
			else
			{
				standard_error('allresourcesused');
			}
		}

		elseif($action=='edit' && $id!=0)
		{
			$result=$db->query_first("SELECT `id`, `email`, `destination`, `customerid`, `popaccountid` FROM `".TABLE_MAIL_VIRTUAL."` WHERE `customerid`='".$userinfo['customerid']."' AND `id`='$id'");
			if(isset($result['email']) && $result['email']!='')
			{
				if($result['email']{0} == '@')
				{
					$result['email'] = $settings['email']['catchallkeyword'].$result['email'];
				}

				$result['email'] = $idna_convert->decode($result['email']);
				$result['destination'] = explode ( ' ', $result['destination'] ) ;
				$forwarders = '';
				$forwarderid = 0;
				$forwarders_count = 0;
				while ( list ( $dest_id , $destination ) = each ( $result['destination'] ) )
				{
					$forwarderid++;
					$destination = $idna_convert->decode($destination);
					if ( $destination != $result['email'] && $destination != '')
					{
						eval("\$forwarders.=\"".getTemplate("email/emails_edit_forwarder")."\";");
						$forwarders_count++;
					}
					$result['destination'][$dest_id] = $destination;
				}
				$destinations_count = count ($result['destination']);
				eval("echo \"".getTemplate("email/emails_edit")."\";");
			}
		}
	}

	elseif($page=='accounts')
	{
		if($action=='add' && $id!=0)
		{
			if($userinfo['email_accounts_used'] < $userinfo['email_accounts'] || $userinfo['email_accounts'] == '-1')
			{
				$result=$db->query_first("SELECT `id`, `email`, `destination`, `customerid`, `popaccountid`, `domainid` FROM `".TABLE_MAIL_VIRTUAL."` WHERE `customerid`='".$userinfo['customerid']."' AND `id`='$id'");
				if(isset($result['email']) && $result['email']!='' && $result['popaccountid'] == '0')
				{
					if($result['email']{0} == '@')
					{
						$result['email'] = $settings['email']['catchallkeyword'].$result['email'];
					}

					if(isset($_POST['send']) && $_POST['send']=='send')
					{
						$email = $result['email'];
						$username = $idna_convert->decode($email);
						$destination = $email;
						if(substr($email, 0, strlen($settings['email']['catchallkeyword']) + 1) == $settings['email']['catchallkeyword'].'@')
						{
							$email = str_replace($settings['email']['catchallkeyword'], '', $email);
						}
						$password=addslashes($_POST['password']);
						if($email=='' || $password=='')
						{
							standard_error('notallreqfieldsorerrors');
							exit;
						}
						else
						{
							$db->query("INSERT INTO `".TABLE_MAIL_USERS."` (`customerid`, `email`, `username`, `password`, `password_enc`, `homedir`, `maildir`, `uid`, `gid`, `domainid`, `postfix`) VALUES ('".$userinfo['customerid']."', '$destination', '$username', '$password', ENCRYPT('$password'), '".$settings['system']['vmail_homedir']."', '".$userinfo['loginname']."/$username/', '".$settings['system']['vmail_uid']."', '".$settings['system']['vmail_gid']."', '".$result['domainid']."', 'y')");
							$popaccountid = $db->insert_id();
							$result['destination'] .= ' ' . $destination;
							$db->query("UPDATE `".TABLE_MAIL_VIRTUAL."` SET `destination` = '".makeCorrectDestination($result['destination'])."', `popaccountid` = '$popaccountid' WHERE `customerid`='".$userinfo['customerid']."' AND `id`='$id'");
							$db->query("UPDATE `".TABLE_PANEL_CUSTOMERS."` SET `email_accounts_used`=`email_accounts_used`+1 WHERE `customerid`='".$userinfo['customerid']."'");
	
							eval("\$mail_subject=\"".$lng['mails']['pop_success']['subject']."\";");
							eval("\$mail_body=\"".$lng['mails']['pop_success']['mailbody']."\";");
							mail("$destination <$destination>",$mail_subject,$mail_body,"From: {$settings['panel']['adminmail']} <{$settings['panel']['adminmail']}>\r\n");
	
							header("Location: $filename?page=emails&action=edit&id=$id&s=$s");
						}
					}
					else
					{
						$result['email'] = $idna_convert->decode($result['email']);
						eval("echo \"".getTemplate("email/account_add")."\";");
					}
				}
			}
			else
			{
				standard_error('allresourcesused');
			}
		}

		elseif($action=='changepw' && $id!=0)
		{
			$result=$db->query_first("SELECT `id`, `email`, `destination`, `customerid`, `popaccountid` FROM `".TABLE_MAIL_VIRTUAL."` WHERE `customerid`='".$userinfo['customerid']."' AND `id`='$id'");
			if(isset($result['popaccountid']) && $result['popaccountid']!='')
			{
				if($result['email']{0} == '@')
				{
					$result['email'] = $settings['email']['catchallkeyword'].$result['email'];
				}

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
						$result=$db->query("UPDATE `".TABLE_MAIL_USERS."` SET `password` = '$password', `password_enc`=ENCRYPT('$password') WHERE `customerid`='".$userinfo['customerid']."' AND `id`='".$result['popaccountid']."'");
						header("Location: $filename?page=emails&action=edit&id=$id&s=$s");
					}
				}
				else
				{
					$result['email'] = $idna_convert->decode($result['email']);
					eval("echo \"".getTemplate("email/account_changepw")."\";");
				}
			}
		}

		elseif($action=='delete' && $id!=0)
		{
			$result=$db->query_first("SELECT `id`, `email`, `destination`, `customerid`, `popaccountid` FROM `".TABLE_MAIL_VIRTUAL."` WHERE `customerid`='".$userinfo['customerid']."' AND `id`='$id'");
			if(isset($result['popaccountid']) && $result['popaccountid']!='')
			{
				if($result['email']{0} == '@')
				{
					$result['email'] = $settings['email']['catchallkeyword'].$result['email'];
				}

				if(isset($_POST['send']) && $_POST['send']=='send')
				{
					$db->query("DELETE FROM `".TABLE_MAIL_USERS."` WHERE `customerid`='".$userinfo['customerid']."' AND `id`='".$result['popaccountid']."'");
					$result['destination'] = str_replace ( $result['email'] , '' , $result['destination'] ) ;
					$db->query("UPDATE `".TABLE_MAIL_VIRTUAL."` SET `destination` = '".makeCorrectDestination($result['destination'])."', `popaccountid` = '0' WHERE `customerid`='".$userinfo['customerid']."' AND `id`='$id'");
					$db->query("UPDATE `".TABLE_PANEL_CUSTOMERS."` SET `email_accounts_used` = `email_accounts_used` - 1 WHERE `customerid`='".$userinfo['customerid']."'");
					header("Location: $filename?page=emails&action=edit&id=$id&s=$s");
				}
				else
				{
					ask_yesno('email_reallydelete_account', $filename, "id=$id;page=$page;action=$action", $idna_convert->decode($result['email']));
				}
			}
		}
	}

	elseif($page=='forwarders')
	{
		if($action=='add' && $id!=0)
		{
			if($userinfo['email_forwarders_used'] < $userinfo['email_forwarders'] || $userinfo['email_forwarders'] == '-1')
			{
				$result=$db->query_first("SELECT `id`, `email`, `destination`, `customerid`, `popaccountid`, `domainid` FROM `".TABLE_MAIL_VIRTUAL."` WHERE `customerid`='".$userinfo['customerid']."' AND `id`='$id'");
				if(isset($result['email']) && $result['email']!='')
				{
					if($result['email']{0} == '@')
					{
						$result['email'] = $settings['email']['catchallkeyword'].$result['email'];
					}

					if(isset($_POST['send']) && $_POST['send']=='send')
					{
						$destination = $idna_convert->encode(addslashes($_POST['destination']));
						$result['destination_array'] = explode ( ' ', $result['destination'] ) ;
						if($destination == '' || !verify_email($destination) || in_array ( $destination , $result['destination_array'] ) )
						{
							standard_error('notallreqfieldsorerrors');
							exit;
						}
						else
						{
							$result['destination'] .= ' ' . $destination;
							$db->query("UPDATE `".TABLE_MAIL_VIRTUAL."` SET `destination` = '".makeCorrectDestination($result['destination'])."' WHERE `customerid`='".$userinfo['customerid']."' AND `id`='$id'");
							$db->query("UPDATE `".TABLE_PANEL_CUSTOMERS."` SET `email_forwarders_used` = `email_forwarders_used` + 1 WHERE `customerid`='".$userinfo['customerid']."'");

							header("Location: $filename?page=emails&action=edit&id=$id&s=$s");
						}
					}
					else
					{
						$result['email'] = $idna_convert->decode($result['email']);
						eval("echo \"".getTemplate("email/forwarder_add")."\";");
					}
				}
			}
			else
			{
				standard_error('allresourcesused');
			}
		}

		elseif($action=='delete' && $id!=0)
		{
			$result=$db->query_first("SELECT `id`, `email`, `destination`, `customerid`, `popaccountid` FROM `".TABLE_MAIL_VIRTUAL."` WHERE `customerid`='".$userinfo['customerid']."' AND `id`='$id'");
			if(isset($result['destination']) && $result['destination']!='')
			{
				if($result['email']{0} == '@')
				{
					$result['email'] = $settings['email']['catchallkeyword'].$result['email'];
				}

				if(isset($_POST['forwarderid']))
				{
					$forwarderid = intval($_POST['forwarderid']) ;
				}
				elseif(isset($_GET['forwarderid']))
				{
					$forwarderid = intval($_GET['forwarderid']) ;
				}
				else
				{
					$forwarderid = 0 ;
				}

				$result['destination_array'] = explode ( ' ', $result['destination'] ) ;
				if ( isset ( $result['destination_array'][$forwarderid-1] ) )
				{
					$forwarder = $result['destination_array'][$forwarderid-1] ;
					if ( $forwarder == $result['email'] )
					{
						$forwarder = $result['destination_array'][$forwarderid] ;
					}

					if(isset($_POST['send']) && $_POST['send']=='send')
					{
						$result['destination'] = str_replace ( $forwarder , '' , $result['destination'] ) ;
						$db->query("UPDATE `".TABLE_MAIL_VIRTUAL."` SET `destination` = '".makeCorrectDestination($result['destination'])."' WHERE `customerid`='".$userinfo['customerid']."' AND `id`='$id'");
						$db->query("UPDATE `".TABLE_PANEL_CUSTOMERS."` SET `email_forwarders_used` = `email_forwarders_used` - 1 WHERE `customerid`='".$userinfo['customerid']."'");
						header("Location: $filename?page=emails&action=edit&id=$id&s=$s");
					}
					else
					{
						ask_yesno('email_reallydelete_forwarder', $filename, "id=$id;forwarderid=$forwarderid;page=$page;action=$action", $idna_convert->decode($result['email']) . ' -> ' . $idna_convert->decode($forwarder));
					}
				}
			}
		}
	}

?>
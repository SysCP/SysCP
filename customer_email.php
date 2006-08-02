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
			$result = $db->query(
				'SELECT `'.TABLE_MAIL_VIRTUAL.'`.`id`, ' .
				'       `'.TABLE_MAIL_VIRTUAL.'`.`domainid`, ' .
				'       `'.TABLE_MAIL_VIRTUAL.'`.`email`, ' .
				'       `'.TABLE_MAIL_VIRTUAL.'`.`email_full`, ' .
				'       `'.TABLE_MAIL_VIRTUAL.'`.`iscatchall`, ' .
				'       `'.TABLE_MAIL_VIRTUAL.'`.`destination`, ' .
				'       `'.TABLE_MAIL_VIRTUAL.'`.`popaccountid`, ' .
				'       `'.TABLE_PANEL_DOMAINS.'`.`domain` ' .
				'FROM `'.TABLE_MAIL_VIRTUAL.'` ' .
				'LEFT JOIN `'.TABLE_PANEL_DOMAINS.'` ' .
				'  ON (`'.TABLE_MAIL_VIRTUAL.'`.`domainid` = `'.TABLE_PANEL_DOMAINS.'`.`id`)' .
				'WHERE `'.TABLE_MAIL_VIRTUAL.'`.`customerid`="'.$userinfo['customerid'].'" ' .
				'ORDER BY `domainid`, `email` ASC'
			);
 			$accounts='';
			$emails_count=0;
			$domainname = '';
 			while($row = $db->fetch_array($result))
 			{
				if ($domainname != $idna_convert->decode($row['domain']))
				{
					$domainname = $idna_convert->decode($row['domain']);
					eval("\$accounts.=\"".getTemplate("email/emails_domain")."\";");
				}
				$emails_count++;
				$row['email'] = $idna_convert->decode($row['email']);
				$row['email_full'] = $idna_convert->decode($row['email_full']);
				$row['destination'] = explode ( ' ', $row['destination'] ) ;
				while ( list ( $dest_id , $destination ) = each ( $row['destination'] ) )
				{
					$row['destination'][$dest_id] = $idna_convert->decode($row['destination'][$dest_id]);
					if ( $row['destination'][$dest_id] == $row['email_full'] )
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

				$row = htmlentities_array( $row );
				eval("\$accounts.=\"".getTemplate("email/emails_email")."\";");
			}
			$emaildomains_count=$db->query_first("SELECT COUNT(`id`) AS `count` FROM `".TABLE_PANEL_DOMAINS."` WHERE `customerid`='".$userinfo['customerid']."' AND `isemaildomain`='1' ORDER BY `domain` ASC");
			$emaildomains_count=$emaildomains_count['count'];

			eval("echo \"".getTemplate("email/emails")."\";");
		}

		elseif($action=='delete' && $id!=0)
		{
			$result=$db->query_first("SELECT `id`, `email`, `email_full`, `iscatchall`, `destination`, `customerid`, `popaccountid` FROM `".TABLE_MAIL_VIRTUAL."` WHERE `customerid`='".$userinfo['customerid']."' AND `id`='$id'");
			if(isset($result['email']) && $result['email']!='')
			{
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
					redirectTo ( $filename , Array ( 'page' => $page , 's' => $s ) ) ;
				}
				else
				{
					ask_yesno('email_reallydelete', $filename, array( 'id' => $id, 'page' => $page, 'action' => $action ), $idna_convert->decode($result['email_full']));
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
					$domain_check = $db->query_first("SELECT `id`, `domain`, `customerid` FROM `".TABLE_PANEL_DOMAINS."` WHERE `domain`='$domain' AND `customerid`='".$userinfo['customerid']."' AND `isemaildomain`='1' ");
					if ( isset ( $_POST['iscatchall'] ) && $_POST['iscatchall'] == '1' )
					{
						$iscatchall = '1' ;
						$email = '@' . $domain ;
					}
					else
					{
						$iscatchall = '0' ;
						$email = $email_part . '@' . $domain ;
					}
					$email_full = $email_part . '@' . $domain ;

					$email_check=$db->query_first("SELECT `id`, `email`, `email_full`, `iscatchall`, `destination`, `customerid` FROM `".TABLE_MAIL_VIRTUAL."` WHERE ( `email`='$email' OR `email_full` = '$email_full' ) AND `customerid`='".$userinfo['customerid']."'");

					if($email=='' || $email_full == '' || $email_part=='')
					{
						standard_error(array('stringisempty','emailadd'));
					}
					elseif($domain=='')
					{
						standard_error('domaincantbeempty');
					}
					elseif($domain_check['domain']!=$domain)
					{
						standard_error('maindomainnonexist',$domain);
					}
					elseif($email_check['email_full'] == $email_full)
					{
						standard_error('emailexistalready',$email_full);
					}
					elseif(!verify_email($email_part . '@' . $domain))
					{
						standard_error('emailiswrong',$email_full);
					}


					elseif ( $email_check['email'] == $email )
					{
						standard_error('youhavealreadyacatchallforthisdomain');
						exit;
					}
					else
					{
						$db->query("INSERT INTO `".TABLE_MAIL_VIRTUAL."` (`customerid`, `email`, `email_full`, `iscatchall`, `domainid`) VALUES ('".$userinfo['customerid']."', '$email', '$email_full', '$iscatchall', '".$domain_check['id']."')");
						$address_id = $db->insert_id();
						$db->query("UPDATE ".TABLE_PANEL_CUSTOMERS." SET `emails_used` = `emails_used` + 1 WHERE `customerid`='".$userinfo['customerid']."'");
    					redirectTo ( $filename , Array ( 'page' => $page , 'action' => 'edit' , 'id' => $address_id , 's' => $s ) ) ;
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
					$iscatchall = makeyesno ( 'iscatchall' , '1' , '0' , '0');
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
			$result=$db->query_first("SELECT `id`, `email`, `email_full`, `iscatchall`, `destination`, `customerid`, `popaccountid` FROM `".TABLE_MAIL_VIRTUAL."` WHERE `customerid`='".$userinfo['customerid']."' AND `id`='$id'");
			if(isset($result['email']) && $result['email']!='')
			{
				$result['email'] = $idna_convert->decode($result['email']);
				$result['email_full'] = $idna_convert->decode($result['email_full']);
				$result['destination'] = explode ( ' ', $result['destination'] ) ;
				$forwarders = '';
				$forwarderid = 0;
				$forwarders_count = 0;
				while ( list ( $dest_id , $destination ) = each ( $result['destination'] ) )
				{
					$forwarderid++;
					$destination = $idna_convert->decode($destination);
					if ( $destination != $result['email_full'] && $destination != '')
					{
						eval("\$forwarders.=\"".getTemplate("email/emails_edit_forwarder")."\";");
						$forwarders_count++;
					}
					$result['destination'][$dest_id] = $destination;
				}
				$destinations_count = count ($result['destination']);

				$result = htmlentities_array( $result );
				eval("echo \"".getTemplate("email/emails_edit")."\";");
			}
		}

		elseif($action=='togglecatchall' && $id!=0)
		{
			$result=$db->query_first("SELECT `id`, `email`, `email_full`, `iscatchall`, `destination`, `customerid`, `popaccountid` FROM `".TABLE_MAIL_VIRTUAL."` WHERE `customerid`='".$userinfo['customerid']."' AND `id`='$id'");
			if(isset($result['email']) && $result['email']!='')
			{
				if ( $result['iscatchall'] == '1' )
				{
					$db->query ( "UPDATE `".TABLE_MAIL_VIRTUAL."` SET `email` = '" . $result['email_full'] . "', `iscatchall` = '0' WHERE `customerid`='".$userinfo['customerid']."' AND `id`='".$result['id']."'" );
				}
				else
				{
					$email_parts = explode ( '@' , $result['email_full'] ) ;
					$email = '@' . $email_parts[1] ;
					$email_check=$db->query_first("SELECT `id`, `email`, `email_full`, `iscatchall`, `destination`, `customerid` FROM `".TABLE_MAIL_VIRTUAL."` WHERE `email`='$email' AND `customerid`='".$userinfo['customerid']."'");
					if ( $email_check['email'] == $email )
					{
						standard_error('youhavealreadyacatchallforthisdomain');
						exit;
					}
					else
					{
						$db->query ( "UPDATE `".TABLE_MAIL_VIRTUAL."` SET `email` = '$email' , `iscatchall` = '1' WHERE `customerid`='".$userinfo['customerid']."' AND `id`='".$result['id']."'" );
					}
				}
				redirectTo ( $filename , Array ( 'page' => $page , 'action' => 'edit' , 'id' => $id , 's' => $s ) ) ;
			}
		}
	}

	elseif($page=='accounts')
	{
		if($action=='add' && $id!=0)
		{
			if($userinfo['email_accounts_used'] < $userinfo['email_accounts'] || $userinfo['email_accounts'] == '-1')
			{
				$result=$db->query_first("SELECT `id`, `email`, `email_full`, `iscatchall`, `destination`, `customerid`, `popaccountid`, `domainid` FROM `".TABLE_MAIL_VIRTUAL."` WHERE `customerid`='".$userinfo['customerid']."' AND `id`='$id'");
				if(isset($result['email']) && $result['email']!='' && $result['popaccountid'] == '0')
				{
					if(isset($_POST['send']) && $_POST['send']=='send')
					{
						$email_full = $result['email_full'];
						$username = $idna_convert->decode($email_full);
						$password=addslashes($_POST['password']);

						if($email_full == '')
						{
							standard_error(array('stringisempty','emailadd'));
						}
						elseif($password == '')
						{
							standard_error(array('stringisempty','mypassword'));
						}

						else
						{
							$db->query("INSERT INTO `".TABLE_MAIL_USERS."` (`customerid`, `email`, `username`, `password`, `password_enc`, `homedir`, `maildir`, `uid`, `gid`, `domainid`, `postfix`) VALUES ('".$userinfo['customerid']."', '$email_full', '$username', '$password', ENCRYPT('$password'), '".$settings['system']['vmail_homedir']."', '".$userinfo['loginname']."/$email_full/', '".$settings['system']['vmail_uid']."', '".$settings['system']['vmail_gid']."', '".$result['domainid']."', 'y')");
							$popaccountid = $db->insert_id();
							$result['destination'] .= ' ' . $email_full;
							$db->query("UPDATE `".TABLE_MAIL_VIRTUAL."` SET `destination` = '".makeCorrectDestination($result['destination'])."', `popaccountid` = '$popaccountid' WHERE `customerid`='".$userinfo['customerid']."' AND `id`='$id'");
							$db->query("UPDATE `".TABLE_PANEL_CUSTOMERS."` SET `email_accounts_used`=`email_accounts_used`+1 WHERE `customerid`='".$userinfo['customerid']."'");
							
							$replace_arr=array(
								'EMAIL' => $email_full,
								'USERNAME' => $username
							);
							$admin = $db->query_first('SELECT `name`, `email` FROM `' . TABLE_PANEL_ADMINS . '` WHERE `adminid`=\'' . $userinfo['adminid'] . '\'');
							$result=$db->query_first('SELECT `value` FROM `'.TABLE_PANEL_TEMPLATES.'` WHERE `adminid`=\''.$userinfo['adminid'].'\' AND `language`=\''.$userinfo['def_language'].'\' AND `templategroup`=\'mails\' AND `varname`=\'pop_success_subject\'');
							$mail_subject=_html_entity_decode(replace_variables((($result['value']!='') ? $result['value'] : $lng['mails']['pop_success']['subject']),$replace_arr));
							$result=$db->query_first('SELECT `value` FROM `'.TABLE_PANEL_TEMPLATES.'` WHERE `adminid`=\''.$userinfo['adminid'].'\' AND `language`=\''.$userinfo['def_language'].'\' AND `templategroup`=\'mails\' AND `varname`=\'pop_success_mailbody\'');
							$mail_body=_html_entity_decode(replace_variables((($result['value']!='') ? $result['value'] : $lng['mails']['pop_success']['mailbody']),$replace_arr));
							mail("$email_full",$mail_subject,$mail_body,"From: {$admin['name']} <{$admin['email']}>\r\n");

							redirectTo ( $filename , Array ( 'page' => 'emails' , 'action' => 'edit' , 'id' => $id , 's' => $s ) ) ;
						}
					}
					else
					{
						$result['email_full'] = $idna_convert->decode($result['email_full']);
						$result = htmlentities_array( $result );
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
			$result=$db->query_first("SELECT `id`, `email`, `email_full`, `iscatchall`, `destination`, `customerid`, `popaccountid` FROM `".TABLE_MAIL_VIRTUAL."` WHERE `customerid`='".$userinfo['customerid']."' AND `id`='$id'");
			if(isset($result['popaccountid']) && $result['popaccountid']!='')
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
						$result=$db->query("UPDATE `".TABLE_MAIL_USERS."` SET `password` = '$password', `password_enc`=ENCRYPT('$password') WHERE `customerid`='".$userinfo['customerid']."' AND `id`='".$result['popaccountid']."'");
						redirectTo ( $filename , Array ( 'page' => 'emails' , 'action' => 'edit' , 'id' => $id , 's' => $s ) ) ;
					}
				}
				else
				{
					$result['email_full'] = $idna_convert->decode($result['email_full']);
					$result = htmlentities_array( $result );
					eval("echo \"".getTemplate("email/account_changepw")."\";");
				}
			}
		}

		elseif($action=='delete' && $id!=0)
		{
			$result=$db->query_first("SELECT `id`, `email`, `email_full`, `iscatchall`, `destination`, `customerid`, `popaccountid` FROM `".TABLE_MAIL_VIRTUAL."` WHERE `customerid`='".$userinfo['customerid']."' AND `id`='$id'");
			if(isset($result['popaccountid']) && $result['popaccountid']!='')
			{
				if(isset($_POST['send']) && $_POST['send']=='send')
				{
					$db->query("DELETE FROM `".TABLE_MAIL_USERS."` WHERE `customerid`='".$userinfo['customerid']."' AND `id`='".$result['popaccountid']."'");
					$result['destination'] = str_replace ( $result['email_full'] , '' , $result['destination'] ) ;
					$db->query("UPDATE `".TABLE_MAIL_VIRTUAL."` SET `destination` = '".makeCorrectDestination($result['destination'])."', `popaccountid` = '0' WHERE `customerid`='".$userinfo['customerid']."' AND `id`='$id'");
					$db->query("UPDATE `".TABLE_PANEL_CUSTOMERS."` SET `email_accounts_used` = `email_accounts_used` - 1 WHERE `customerid`='".$userinfo['customerid']."'");
   					redirectTo ( $filename , Array ( 'page' => 'emails' , 'action' => 'edit' , 'id' => $id , 's' => $s ) ) ;
				}
				else
				{
					ask_yesno('email_reallydelete_account', $filename, array( 'id' => $id, 'page' => $page, 'action' => $action ), $idna_convert->decode($result['email_full']));
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
				$result=$db->query_first("SELECT `id`, `email`, `email_full`, `iscatchall`, `destination`, `customerid`, `popaccountid`, `domainid` FROM `".TABLE_MAIL_VIRTUAL."` WHERE `customerid`='".$userinfo['customerid']."' AND `id`='$id'");
				if(isset($result['email']) && $result['email']!='')
				{
					if(isset($_POST['send']) && $_POST['send']=='send')
					{
						$destination = $idna_convert->encode(addslashes($_POST['destination']));
						$result['destination_array'] = explode ( ' ', $result['destination'] ) ;

						if($destination == '')
						{
							standard_error('destinationnonexist');
						}
						elseif(!verify_email($destination))
						{
							standard_error('destinationiswrong',$destination);
						}
						elseif($destination == $result['email'])
						{
							standard_error('destinationalreadyexistasmail',$destination);
						}
						elseif(in_array ( $destination , $result['destination_array'] ))
						{
							standard_error('destinationalreadyexist',$destination);
						}

						else
						{
							$result['destination'] .= ' ' . $destination;
							$db->query("UPDATE `".TABLE_MAIL_VIRTUAL."` SET `destination` = '".makeCorrectDestination($result['destination'])."' WHERE `customerid`='".$userinfo['customerid']."' AND `id`='$id'");
							$db->query("UPDATE `".TABLE_PANEL_CUSTOMERS."` SET `email_forwarders_used` = `email_forwarders_used` + 1 WHERE `customerid`='".$userinfo['customerid']."'");

							redirectTo ( $filename , Array ( 'page' => 'emails' , 'action' => 'edit' , 'id' => $id , 's' => $s ) ) ;
						}
					}
					else
					{
						$result['email_full'] = $idna_convert->decode($result['email_full']);
						$result = htmlentities_array( $result );
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
			$result=$db->query_first("SELECT `id`, `email`, `email_full`, `iscatchall`, `destination`, `customerid`, `popaccountid` FROM `".TABLE_MAIL_VIRTUAL."` WHERE `customerid`='".$userinfo['customerid']."' AND `id`='$id'");
			if(isset($result['destination']) && $result['destination']!='')
			{
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
						redirectTo ( $filename , Array ( 'page' => 'emails' , 'action' => 'edit' , 'id' => $id , 's' => $s ) ) ;
					}
					else
					{
						ask_yesno('email_reallydelete_forwarder', $filename, array( 'id' => $id, 'forwarderid' => $forwarderid, 'page' => $page, 'action' => $action ), $idna_convert->decode($result['email_full']) . ' -> ' . $idna_convert->decode($forwarder));
					}
				}
			}
		}
	}

?>

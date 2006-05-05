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
 * @version    $Id:admin_customers.php 460 2006-04-23 15:07:49 +0200 (So, 23 Apr 2006) martin $
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

	if($config->get('env.page')=='customers' && $userinfo['customers'] != '0' )
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

			$customers='';
			$result=$db->query(
				"SELECT `c`.`customerid`, `c`.`loginname`, `c`.`name`, `c`.`firstname`, `c`.`diskspace`, `c`.`diskspace_used`, `c`.`traffic`, `c`.`traffic_used`, `c`.`mysqls`, `c`.`mysqls_used`, `c`.`emails`, `c`.`emails_used`, `c`.`email_accounts`, `c`.`email_accounts_used`, `c`.`deactivated`, `c`.`ftps`, `c`.`ftps_used`, `c`.`subdomains`, `c`.`subdomains_used`, `c`.`email_forwarders`, `c`.`email_forwarders_used`, `c`.`standardsubdomain`, `a`.`loginname` AS `adminname` " .
				"FROM `".TABLE_PANEL_CUSTOMERS."` `c`, `".TABLE_PANEL_ADMINS."` `a` " .
				"WHERE ".( $userinfo['customers_see_all'] ? '' : " `c`.`adminid` = '{$userinfo['adminid']}' AND " )."`c`.`adminid`=`a`.`adminid` " .
				"ORDER BY `c`.`$sortby` $sortorder");
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
					"SELECT `c`.`customerid`, `c`.`loginname`, `c`.`name`, `c`.`firstname`, `c`.`diskspace`, `c`.`diskspace_used`, `c`.`traffic`, `c`.`traffic_used`, `c`.`mysqls`, `c`.`mysqls_used`, `c`.`emails`, `c`.`emails_used`, `c`.`email_accounts`, `c`.`email_accounts_used`, `c`.`deactivated`, `c`.`ftps`, `c`.`ftps_used`, `c`.`subdomains`, `c`.`subdomains_used`, `c`.`email_forwarders`, `c`.`email_forwarders_used`, `c`.`standardsubdomain`, `a`.`loginname` AS `adminname` " .
					"FROM `".TABLE_PANEL_CUSTOMERS."` `c`, `".TABLE_PANEL_ADMINS."` `a` " .
					"WHERE ".( $userinfo['customers_see_all'] ? '' : " `c`.`adminid` = '{$userinfo['adminid']}' AND " )."`c`.`adminid`=`a`.`adminid` " .
					"ORDER BY `c`.`$sortby` $sortorder " .
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
				$domains=$db->query_first(
					"SELECT COUNT(`id`) AS `domains` " .
					"FROM `".TABLE_PANEL_DOMAINS."` " .
					"WHERE `customerid`='".$row['customerid']."' AND `parentdomainid`='0' AND `id` <> '" . $row['standardsubdomain'] . "' "
				);
				$row['domains']=$domains['domains'];
				$row['traffic_used']=round($row['traffic_used']/(1024*1024),4);
				$row['traffic']=round($row['traffic']/(1024*1024),4);
				$row['diskspace_used']=round($row['diskspace_used']/1024,2);
				$row['diskspace']=round($row['diskspace']/1024,2);
				$row['deactivated'] = str_replace('0', $lng['panel']['yes'], $row['deactivated']);
				$row['deactivated'] = str_replace('1', $lng['panel']['no'], $row['deactivated']);

				$row = str_replace_array('-1', 'UL', $row, 'diskspace traffic mysqls emails email_accounts email_forwarders ftps subdomains');

				eval("\$customers.=\"".getTemplate("customers/customers_customer")."\";");
			}
			eval("echo \"".getTemplate("customers/customers")."\";");
		}

		elseif($config->get('env.action')=='su' && $config->get('env.id')!=0)
		{
			$result=$db->query_first("SELECT * FROM `".TABLE_PANEL_CUSTOMERS."` WHERE `customerid`='".$config->get('env.id')."' ".( $userinfo['customers_see_all'] ? '' : " AND `adminid` = '{$userinfo['adminid']}' "));
			if($result['loginname']!='')
			{
				$result=$db->query_first("SELECT * FROM `".TABLE_PANEL_SESSIONS."` WHERE `userid`={$userinfo['userid']}");
				$s = md5(uniqid(microtime(),1));
				$db->query("INSERT INTO `".TABLE_PANEL_SESSIONS."` (`hash`, `userid`, `ipaddress`, `useragent`, `lastactivity`, `language`, `adminsession`) VALUES ('$s', '".$config->get('env.id')."', '{$result['ipaddress']}', '{$result['useragent']}', '" . time() . "', '{$result['language']}', '0')");
				redirectTo ( 'customer_index.php' , Array ( 's' => $s ) ) ;
			}
			else
			{
				redirectTo ( 'index.php' , Array ( 'action' => 'login' ) ) ;
			}
		}

		elseif($config->get('env.action')=='delete' && $config->get('env.id')!=0)
		{
			$result=$db->query_first("SELECT * FROM `".TABLE_PANEL_CUSTOMERS."` WHERE `customerid`='".$config->get('env.id')."' ".( $userinfo['customers_see_all'] ? '' : " AND `adminid` = '{$userinfo['adminid']}' "));
			if($result['loginname']!='')
			{
				if(isset($_POST['send']) && $_POST['send']=='send')
				{
					$databases=$db->query("SELECT * FROM ".TABLE_PANEL_DATABASES." WHERE customerid='".$config->get('env.id')."'");
					$db_root=new Syscp_DB_Mysql( $config->get('sql.host'),
					                                      $config->get('sql.root.user'),
					                                      $config->get('sql.root.password') );
					unset($db_root->password);
					while($row_database=$db->fetch_array($databases))
					{
						$db_root->query( 'REVOKE ALL PRIVILEGES ON * . * FROM `' . $row_database['databasename'] . '`@' . $config->get('system.mysql_access_host') . ';' );
						$db_root->query( 'REVOKE ALL PRIVILEGES ON `' . str_replace ( '_' , '\_' , $row_database['databasename'] ) . '` . * FROM `' . $row_database['databasename'] . '`@' . $config->get('system.mysql_access_host') . ';' );
						$db_root->query( 'DELETE FROM `mysql`.`user` WHERE `User` = "' . $row_database['databasename'] . '" AND `Host` = "' . $config->get('system.mysql_access_host') . '"' );
						$db_root->query( 'DROP DATABASE IF EXISTS `' . $row_database['databasename'] . '`' );
					}
					$db_root->query('FLUSH PRIVILEGES;');
					$db_root->close();

					$db->query("DELETE FROM `".TABLE_PANEL_CUSTOMERS."` WHERE `customerid`='".$config->get('env.id')."'");
					$db->query("DELETE FROM `".TABLE_PANEL_DATABASES."` WHERE `customerid`='".$config->get('env.id')."'");
					$db->query("DELETE FROM `".TABLE_PANEL_DOMAINS."` WHERE `customerid`='".$config->get('env.id')."'");
					$domains_deleted = $db->affected_rows();
					$db->query("DELETE FROM `".TABLE_PANEL_HTPASSWDS."` WHERE `customerid`='".$config->get('env.id')."'");
					$db->query("DELETE FROM `".TABLE_PANEL_SESSIONS."` WHERE `userid`='".$config->get('env.id')."' AND `adminsession` = '0'");
					$db->query("DELETE FROM `".TABLE_PANEL_TRAFFIC."` WHERE `customerid`='".$config->get('env.id')."'");

					$db->query("DELETE FROM `".TABLE_MAIL_USERS."` WHERE `customerid`='".$config->get('env.id')."'");
					$db->query("DELETE FROM `".TABLE_MAIL_VIRTUAL."` WHERE `customerid`='".$config->get('env.id')."'");

					$db->query("DELETE FROM `".TABLE_FTP_GROUPS."` WHERE `customerid`='".$config->get('env.id')."'");
					$db->query("DELETE FROM `".TABLE_FTP_USERS."` WHERE `customerid`='".$config->get('env.id')."'");

					$admin_update_query = "UPDATE `".TABLE_PANEL_ADMINS."` SET `customers_used` = `customers_used` - 1 ";
					$admin_update_query .= ", `domains_used` = `domains_used` - 0".($domains_deleted - $result['subdomains_used']) ;
					if ( $result['mysqls'] != '-1' )
					{
						$admin_update_query .= ", `mysqls_used` = `mysqls_used` - 0".$result['mysqls'];
					}
					if ( $result['emails'] != '-1' )
					{
						$admin_update_query .= ", `emails_used` = `emails_used` - 0".$result['emails'];
					}
					if ( $result['email_accounts'] != '-1' )
					{
						$admin_update_query .= ", `email_accounts_used` = `email_accounts_used` - 0".$result['email_accounts'];
					}
					if ( $result['email_forwarders'] != '-1' )
					{
						$admin_update_query .= ", `email_forwarders_used` = `email_forwarders_used` - 0".$result['email_forwarders'];
					}
					if ( $result['subdomains'] != '-1' )
					{
						$admin_update_query .= ", `subdomains_used` = `subdomains_used` - 0".$result['subdomains'];
					}
					if ( $result['ftps'] != '-1' )
					{
						$admin_update_query .= ", `ftps_used` = `ftps_used` - 0".$result['ftps'];
					}
					if ( ($result['diskspace']/1024) != '-1' )
					{
						$admin_update_query .= ", `diskspace_used` = `diskspace_used` - 0".$result['diskspace'];
					}
					$admin_update_query .= " WHERE `adminid` = '{$result['adminid']}'";
					$db->query( $admin_update_query );

					$hooks->call( 'core.deleteCustomer', 
					              array( 'id' => $config->get('env.id') ) );

					redirectTo ( $config->get('env.filename') , Array ( 'page' => $config->get('env.page') , 's' => $config->get('env.s') ) ) ;
				}
				else
				{
					ask_yesno('admin_customer_reallydelete', $config->get('env.filename'), "id=".$config->get('env.id').";page=".$config->get('env.page').";action=".$config->get('env.action'), $result['loginname']);
				}
			}
		}

		elseif($config->get('env.action')=='add')
		{
			if($userinfo['customers_used'] < $userinfo['customers'] || $userinfo['customers'] == '-1')
			{
				if(isset($_POST['send']) && $_POST['send']=='send')
				{
					$name = addslashes ( $_POST['name'] ) ;
					$firstname = addslashes ( $_POST['firstname'] ) ;
					$company = addslashes ( $_POST['company'] ) ;
					$street = addslashes ( $_POST['street'] ) ;
					$zipcode = addslashes($_POST['zipcode'] ) ;
					$city = addslashes ( $_POST['city'] ) ;
					$phone = addslashes ( $_POST['phone'] ) ;
					$fax = addslashes ( $_POST['fax'] ) ;
					$email = $idna->encode ( addslashes ( $_POST['email'] ) ) ;
					$customernumber = addslashes ( $_POST['customernumber'] ) ;
					$def_language = addslashes ( htmlentities ( _html_entity_decode ( $_POST['def_language'] ) ) ) ;
					$diskspace = intval_ressource ( $_POST['diskspace'] ) ;
					$traffic = doubleval_ressource ( $_POST['traffic'] ) ;
					$subdomains = intval_ressource ( $_POST['subdomains'] ) ;
					$emails = intval_ressource ( $_POST['emails'] ) ;
					$email_accounts = intval_ressource ( $_POST['email_accounts'] ) ;
					$email_forwarders = intval_ressource ( $_POST['email_forwarders'] ) ;
					$ftps = intval_ressource ( $_POST['ftps'] ) ;
					$mysqls = intval_ressource ( $_POST['mysqls'] ) ;
					$createstdsubdomain = intval ( $_POST['createstdsubdomain'] ) ;
					$password = addslashes ( $_POST['password'] ) ;
					$sendpassword = intval ( $_POST['sendpassword'] ) ;
				
					$diskspace=$diskspace*1024;
					$traffic=$traffic*1024*1024;

					if( ( ( ($userinfo['diskspace_used']        + $diskspace)        > $userinfo['diskspace'])        && ($userinfo['diskspace']/1024) != '-1') || 
					    ( ( ($userinfo['mysqls_used']           + $mysqls)           > $userinfo['mysqls'])           && $userinfo['mysqls'] != '-1') || 
					    ( ( ($userinfo['emails_used']           + $emails)           > $userinfo['emails'])           && $userinfo['emails'] != '-1') || 
					    ( ( ($userinfo['email_accounts_used']   + $email_accounts)   > $userinfo['email_accounts'])   && $userinfo['email_accounts'] != '-1') || 
					    ( ( ($userinfo['email_forwarders_used'] + $email_forwarders) > $userinfo['email_forwarders']) && $userinfo['email_forwarders'] != '-1') || 
					    ( ( ($userinfo['ftps_used']             + $ftps)             > $userinfo['ftps'])             && $userinfo['ftps'] != '-1') || 
					    ( ( ($userinfo['subdomains_used']       + $subdomains)       > $userinfo['subdomains'])       && $userinfo['subdomains'] != '-1') ||
					    ( ($diskspace/1024) == '-1' && ($userinfo['diskspace']/1024) != '-1' ) ||
					    ( $mysqls == '-1'           && $userinfo['mysqls'] != '-1' ) ||
					    ( $emails == '-1'           && $userinfo['emails'] != '-1' ) ||
					    ( $email_accounts == '-1'   && $userinfo['email_accounts'] != '-1' ) ||
					    ( $email_forwarders == '-1' && $userinfo['email_forwarders'] != '-1' ) ||
					    ( $ftps == '-1'             && $userinfo['ftps'] != '-1' ) ||
					    ( $subdomains == '-1'       && $userinfo['subdomains'] != '-1' )
					  )
					{
						standard_error('youcantallocatemorethanyouhave');
						exit;
					}

					if($name == '')
					{
					       	standard_error(array('stringisempty','myname'));
					}
					elseif($firstname=='')
					{
						standard_error(array('stringisempty','myfirstname'));
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
						if(isset($_POST['loginname']) && $_POST['loginname'] != '')
						{
							$loginname = addslashes($_POST['loginname']);
							$loginname_check = $db->query_first("SELECT `loginname` FROM `".TABLE_PANEL_CUSTOMERS."` WHERE `loginname`='".$loginname."'");
							$loginname_check_admin = $db->query_first("SELECT `loginname` FROM `".TABLE_PANEL_ADMINS."` WHERE `loginname`='".$loginname."'");

						if($loginname_check['loginname'] == $loginname || $loginname_check_admin['loginname'] == $loginname)
						{
							standard_error('loginnameexists',$loginname);
						}
						elseif(!check_username($loginname))
						{
							standard_error('loginnameiswrong',$loginname);
						}

							$accountnumber=intval($config->get('system.lastaccountnumber'));
						}
						else
						{
							$accountnumber=intval($config->get('system.lastaccountnumber'))+1;
							$loginname = $config->get('customer.accountprefix').$accountnumber;
						}

						$guid=intval($config->get('system.lastguid'))+1;
						$documentroot = $config->get('system.documentroot_prefix').$loginname;

						if($createstdsubdomain != '1')
						{
							$createstdsubdomain = '0';
						}

						if($password == '')
						{
							$password=substr(md5(uniqid(microtime(),1)),12,6);
						}
						
						$result=$db->query(
							"INSERT INTO `".TABLE_PANEL_CUSTOMERS."` ".
							"(`adminid`, `loginname`, `password`, `name`, `firstname`, `company`, `street`, `zipcode`, `city`, `phone`, `fax`, `email`, `customernumber`, `def_language`, `documentroot`, `guid`, `diskspace`, `traffic`, `subdomains`, `emails`, `email_accounts`, `email_forwarders`, `ftps`, `mysqls`, `standardsubdomain`) ".
							" VALUES ('{$userinfo['adminid']}', '$loginname', '".md5($password)."', '$name', '$firstname', '$company', '$street', '$zipcode', '$city', '$phone', '$fax', '$email', '$customernumber','$def_language', '$documentroot', '$guid', '$diskspace', '$traffic', '$subdomains', '$emails', '$email_accounts', '$email_forwarders', '$ftps', '$mysqls', '0')"
							);
						$customerid=$db->insert_id();

						$admin_update_query = "UPDATE `".TABLE_PANEL_ADMINS."` SET `customers_used` = `customers_used` + 1";
						if ( $mysqls != '-1' )
						{
							$admin_update_query .= ", `mysqls_used` = `mysqls_used` + 0".$mysqls;
						}
						if ( $emails != '-1' )
						{
							$admin_update_query .= ", `emails_used` = `emails_used` + 0".$emails;
						}
						if ( $email_accounts != '-1' )
						{
							$admin_update_query .= ", `email_accounts_used` = `email_accounts_used` + 0".$email_accounts;
						}
						if ( $email_forwarders != '-1' )
						{
							$admin_update_query .= ", `email_forwarders_used` = `email_forwarders_used` + 0".$email_forwarders;
						}
						if ( $subdomains != '-1' )
						{
							$admin_update_query .= ", `subdomains_used` = `subdomains_used` + 0".$subdomains;
						}
						if ( $ftps != '-1' )
						{
							$admin_update_query .= ", `ftps_used` = `ftps_used` + 0".$ftps;
						}
						if ( ($diskspace/1024) != '-1' )
						{
							$admin_update_query .= ", `diskspace_used` = `diskspace_used` + 0".$diskspace;
						}
						$admin_update_query .= " WHERE `adminid` = '{$userinfo['adminid']}'";
						$db->query( $admin_update_query );

						$db->query(
							"UPDATE `".TABLE_PANEL_SETTINGS."` " .
							"SET `value`='$guid' " .
							"WHERE `settinggroup`='system' AND `varname`='lastguid'"
						);
						$db->query(
							"UPDATE `".TABLE_PANEL_SETTINGS."` " .
							"SET `value`='$accountnumber' " .
							"WHERE `settinggroup`='system' AND `varname`='lastaccountnumber'"
						);

						$hooks->call( 'core.createCustomer', 
						              array( 'id'        => $customerid, 
						                     'loginname' => $loginname,
						                     'uid'       => $guid,
						                     'gid'       => $guid ) );
						//inserttask('2',$loginname,$guid,$guid);

						// Add htpasswd for the webalizer stats
						$path = $documentroot . '/webalizer/' ;
						if ( CRYPT_STD_DES == 1 )
						{
							$saltfordescrypt = substr(md5(uniqid(microtime(),1)),4,2);
							$htpasswdPassword = crypt($password, $saltfordescrypt);
						}
						else
						{
							$htpasswdPassword = crypt($password);
						}
						$db->query(
							"INSERT INTO `".TABLE_PANEL_HTPASSWDS."` " .
							"(`customerid`, `username`, `password`, `path`) " .
							"VALUES ('$customerid', '$loginname', '$htpasswdPassword', '$path')"
						);
						
						$htpasswdID = $db->insert_id();
						$hooks->call( 'core.createHTPasswd', 
						              array( 'id'   => $htpasswdID,
						                     'path' => $path ) );

						$result=$db->query(
							"INSERT INTO `".TABLE_FTP_USERS."` " .
							"(`customerid`, `username`, `password`, `homedir`, `login_enabled`, `uid`, `gid`) " .
							"VALUES ('$customerid', '$loginname', ENCRYPT('$password'), '$documentroot/', 'y', '$guid', '$guid')"
						);
						$result=$db->query(
							"INSERT INTO `".TABLE_FTP_GROUPS."` " .
							"(`customerid`, `groupname`, `gid`, `members`) " .
							"VALUES ('$customerid', '$loginname', '$guid', '$loginname')"
						);
						
						if($createstdsubdomain == '1') {
							
							$db->query(
								"INSERT INTO `".TABLE_PANEL_DOMAINS."` " .
								"(`domain`, `customerid`, `adminid`, `parentdomainid`, `documentroot`, `ipandport`, `zonefile`, `isemaildomain`, `caneditdomain`, `openbasedir`, `safemode`, `speciallogfile`, `specialsettings`) " . 
 								"VALUES ('$loginname.{$settings['system']['hostname']}', '$customerid', '{$userinfo['adminid']}', '-1', '$documentroot','.$ipandport.', '', '0', '0', '1', '1', '0', '')" 
							);
							$domainid=$db->insert_id();
							$db->query(
								'UPDATE `'.TABLE_PANEL_CUSTOMERS.'` ' .
								'SET `standardsubdomain`=\''.$domainid.'\' ' .
								'WHERE `customerid`=\''.$customerid.'\''
							);
							$hooks->call( 'core.createDomain', 
							              array( 'id' => $domainid ) );
						}

						if($sendpassword == '1')
						{
							$replace_arr = array(
								'FIRSTNAME' => $firstname,
								'NAME' => $name,
								'USERNAME' => $loginname,
								'PASSWORD' => $password
							);
							// Get mail templates from database; the ones from 'admin' are fetched for fallback
							$result=$db->query_first('SELECT `value` FROM `'.TABLE_PANEL_TEMPLATES.'` WHERE `adminid`=\''.$userinfo['adminid'].'\' AND `language`=\''.$def_language.'\' AND `templategroup`=\'mails\' AND `varname`=\'createcustomer_subject\'');
							$mail_subject=_html_entity_decode(replace_variables((($result['value']!='') ? $result['value'] : $lng['mails']['createcustomer']['subject']),$replace_arr));
							$result=$db->query_first('SELECT `value` FROM `'.TABLE_PANEL_TEMPLATES.'` WHERE `adminid`=\''.$userinfo['adminid'].'\' AND `language`=\''.$def_language.'\' AND `templategroup`=\'mails\' AND `varname`=\'createcustomer_mailbody\'');
							$mail_body=_html_entity_decode(replace_variables((($result['value']!='') ? $result['value'] : $lng['mails']['createcustomer']['mailbody']),$replace_arr));
							mail("$firstname $name <$email>",$mail_subject,$mail_body,"From: {$userinfo['name']} <{$userinfo['email']}>\r\n");
						}

    					redirectTo ( $config->get('env.filename') , Array ( 'page' => $config->get('env.page') , 's' => $config->get('env.s') ) ) ;
					}
				}
				else
				{
					$language_options = '';
					$languages = $language->getList();
					while(list($language_file, $language_name) = each($languages))
					{
						$language_options .= makeoption($language_name, $language_file, $userinfo['def_language']);
					}
					$createstdsubdomain=makeyesno('createstdsubdomain', '1', '0', '1');
					$sendpassword=makeyesno('sendpassword', '1', '0', '1');
					eval("echo \"".getTemplate("customers/customers_add")."\";");
				}
			}
		}

		elseif($config->get('env.action')=='edit' && $config->get('env.id')!=0)
		{
			$result=$db->query_first("SELECT * FROM `".TABLE_PANEL_CUSTOMERS."` WHERE `customerid`='".$config->get('env.id')."' ".( $userinfo['customers_see_all'] ? '' : " AND `adminid` = '{$userinfo['adminid']}' ") );
			if($result['loginname']!='')
			{
				if(isset($_POST['send']) && $_POST['send']=='send')
				{
					$name = addslashes ( $_POST['name'] ) ;
					$firstname = addslashes ( $_POST['firstname'] ) ;
					$company = addslashes ( $_POST['company'] ) ;
					$street = addslashes ( $_POST['street'] ) ;
					$zipcode = addslashes ( $_POST['zipcode'] ) ;
					$city = addslashes ( $_POST['city'] ) ;
					$phone = addslashes ( $_POST['phone'] ) ;
					$fax = addslashes ( $_POST['fax'] ) ;
					$email = $idna->encode ( addslashes ( $_POST['email'] ) ) ;
					$customernumber = addslashes ( $_POST['customernumber'] ) ;
					$def_language = addslashes ( htmlentities ( _html_entity_decode ( $_POST['def_language'] ) ) ) ;
					$newpassword = $_POST['newpassword'];
					$diskspace = intval_ressource ( $_POST['diskspace'] ) ;
					$traffic = doubleval_ressource ( $_POST['traffic'] ) ;
					$subdomains = intval_ressource ( $_POST['subdomains'] ) ;
					$emails = intval_ressource ( $_POST['emails'] ) ;
					$email_accounts = intval_ressource ( $_POST['email_accounts'] ) ;
					$email_forwarders = intval_ressource ( $_POST['email_forwarders'] ) ;
					$ftps = intval_ressource ( $_POST['ftps'] ) ;
					$mysqls = intval_ressource ( $_POST['mysqls'] ) ;
					$createstdsubdomain = intval ( $_POST['createstdsubdomain'] ) ;
					$deactivated = intval ( $_POST['deactivated'] ) ;

					$diskspace=$diskspace*1024;
					$traffic=$traffic*1024*1024;

					if( ( ( ($userinfo['diskspace_used']        + $diskspace        - $result['diskspace'])        > $userinfo['diskspace'])        && ($userinfo['diskspace']/1024) != '-1') || 
					    ( ( ($userinfo['mysqls_used']           + $mysqls           - $result['mysqls'])           > $userinfo['mysqls'])           && $userinfo['mysqls'] != '-1') || 
					    ( ( ($userinfo['emails_used']           + $emails           - $result['emails'])           > $userinfo['emails'])           && $userinfo['emails'] != '-1') || 
					    ( ( ($userinfo['email_accounts_used']   + $email_accounts   - $result['email_accounts'])   > $userinfo['email_accounts'])   && $userinfo['email_accounts'] != '-1') || 
					    ( ( ($userinfo['email_forwarders_used'] + $email_forwarders - $result['email_forwarders']) > $userinfo['email_forwarders']) && $userinfo['email_forwarders'] != '-1') || 
					    ( ( ($userinfo['ftps_used']             + $ftps             - $result['ftps'])             > $userinfo['ftps'])             && $userinfo['ftps'] != '-1') || 
					    ( ( ($userinfo['subdomains_used']       + $subdomains       - $result['subdomains'])       > $userinfo['subdomains'])       && $userinfo['subdomains'] != '-1') ||
					    ( ($diskspace/1024) == '-1' && ($userinfo['diskspace']/1024) != '-1' ) ||
					    ( $mysqls == '-1'           && $userinfo['mysqls'] != '-1' ) ||
					    ( $emails == '-1'           && $userinfo['emails'] != '-1' ) ||
					    ( $email_accounts == '-1'   && $userinfo['email_accounts'] != '-1' ) ||
					    ( $email_forwarders == '-1' && $userinfo['email_forwarders'] != '-1' ) ||
					    ( $ftps == '-1'             && $userinfo['ftps'] != '-1' ) ||
					    ( $subdomains == '-1'       && $userinfo['subdomains'] != '-1' )
					  )
					{
						standard_error('youcantallocatemorethanyouhave');
						exit;
					}

					if($name == '')
					{
						standard_error(array('stringisempty','myname'));
					}
					elseif($firstname=='')
					{
						standard_error(array('stringisempty','myfirstname'));
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

						if($createstdsubdomain != '1')
						{
							$createstdsubdomain = '0';
						}
						if($createstdsubdomain == '1' && $result['standardsubdomain'] == '0')
						{
							$db->query(
								"INSERT INTO `".TABLE_PANEL_DOMAINS."` " .
								"(`domain`, `customerid`, `adminid`, `documentroot`, `zonefile`, `isemaildomain`, `caneditdomain`, `openbasedir`, `safemode`, `speciallogfile`, `specialsettings`) " .
								"VALUES ('{$result['loginname']}.{$config->get('system.hostname')}', '{$result['customerid']}', '{$userinfo['adminid']}', '{$result['documentroot']}', '', '0', '0', '1', '1', '0', '')"
							);
							$domainid=$db->insert_id();
							$db->query(
								'UPDATE `'.TABLE_PANEL_CUSTOMERS.'` ' .
								'SET `standardsubdomain`=\''.$domainid.'\' ' .
								'WHERE `customerid`=\''.$result['customerid'].'\''
							);
							$hooks->call( 'core.createDomain', 
							              array( 'id' => $domainid ) );
						}
						if($createstdsubdomain == '0' && $result['standardsubdomain'] != '0')
						{
							$db->query(
								'DELETE FROM `'.TABLE_PANEL_DOMAINS.'` ' .
								'WHERE `id`=\''.$result['standardsubdomain'].'\''
							);
							$db->query(
								'UPDATE `'.TABLE_PANEL_CUSTOMERS.'` ' .
								'SET `standardsubdomain`=\'0\' ' .
								'WHERE `customerid`=\''.$result['customerid'].'\''
							);
							$hooks->call( 'core.deleteDomain', 
							              array( 'id' => $result['standardsubdomain'] ) );
						}

						if($deactivated != '1')
						{
							$deactivated = '0';
						}
						if($deactivated != $result['deactivated'])
						{
							$db->query("UPDATE `".TABLE_MAIL_USERS."` SET `postfix`='".( ($deactivated) ? 'N' : 'Y' )."' WHERE `customerid`='".$config->get('env.id')."'");
							$db->query("UPDATE `".TABLE_FTP_USERS."` SET `login_enabled`='".( ($deactivated) ? 'N' : 'Y' )."' WHERE `customerid`='".$config->get('env.id')."'");
							$db->query("UPDATE `".TABLE_PANEL_DOMAINS."` SET `deactivated`='$deactivated' WHERE `customerid`='".$config->get('env.id')."'");
							// @TODO: Check this code carefully! It does both enable/disable
							$hooks->call( 'core.deactivateCustomer', 
							              array( 'id' => $config->get('env.id') ) );
						}

						$db->query("UPDATE `".TABLE_PANEL_CUSTOMERS."` SET `name`='$name', `firstname`='$firstname', `company`='$company', `street`='$street', `zipcode`='$zipcode', `city`='$city', `phone`='$phone', `fax`='$fax', `email`='$email', `customernumber`='$customernumber', `def_language`='$def_language', $updatepassword `diskspace`='$diskspace', `traffic`='$traffic', `subdomains`='$subdomains', `emails`='$emails', `email_accounts` = '$email_accounts', `email_forwarders`='$email_forwarders', `ftps`='$ftps', `mysqls`='$mysqls', `deactivated`='$deactivated' WHERE `customerid`='".$config->get('env.id')."'");

						$admin_update_query = "UPDATE `".TABLE_PANEL_ADMINS."` SET `customers_used` = `customers_used` ";
						if ( $mysqls != '-1' || $result['mysqls'] != '-1' )
						{
							$admin_update_query .= ", `mysqls_used` = `mysqls_used` ";
							if ( $mysqls != '-1' )
							{
								$admin_update_query .= " + 0".($mysqls)." ";
							}
							if ( $result['mysqls'] != '-1' )
							{
								$admin_update_query .= " - 0".($result['mysqls'])." ";
							}
						}
						if ( $emails != '-1' || $result['emails'] != '-1' )
						{
							$admin_update_query .= ", `emails_used` = `emails_used` ";
							if ( $emails != '-1' )
							{
								$admin_update_query .= " + 0".($emails)." ";
							}
							if ( $result['emails'] != '-1' )
							{
								$admin_update_query .= " - 0".($result['emails'])." ";
							}
						}
						if ( $email_accounts != '-1' || $result['email_accounts'] != '-1' )
						{
							$admin_update_query .= ", `email_accounts_used` = `email_accounts_used` ";
							if ( $email_accounts != '-1' )
							{
								$admin_update_query .= " + 0".($email_accounts)." ";
							}
							if ( $result['email_accounts'] != '-1' )
							{
								$admin_update_query .= " - 0".($result['email_accounts'])." ";
							}
						}
						if ( $email_forwarders != '-1' || $result['email_forwarders'] != '-1' )
						{
							$admin_update_query .= ", `email_forwarders_used` = `email_forwarders_used` ";
							if ( $email_forwarders != '-1' )
							{
								$admin_update_query .= " + 0".($email_forwarders)." ";
							}
							if ( $result['email_forwarders'] != '-1' )
							{
								$admin_update_query .= " - 0".($result['email_forwarders'])." ";
							}
						}
						if ( $subdomains != '-1' || $result['subdomains'] != '-1' )
						{
							$admin_update_query .= ", `subdomains_used` = `subdomains_used` ";
							if ( $subdomains != '-1' )
							{
								$admin_update_query .= " + 0".($subdomains)." ";
							}
							if ( $result['subdomains'] != '-1' )
							{
								$admin_update_query .= " - 0".($result['subdomains'])." ";
							}
						}
						if ( $ftps != '-1' || $result['ftps'] != '-1' )
						{
							$admin_update_query .= ", `ftps_used` = `ftps_used` ";
							if ( $ftps != '-1' )
							{
								$admin_update_query .= " + 0".($ftps)." ";
							}
							if ( $result['ftps'] != '-1' )
							{
								$admin_update_query .= " - 0".($result['ftps'])." ";
							}
						}
						if ( ($diskspace/1024) != '-1' || ($result['diskspace']/1024) != '-1' )
						{
							$admin_update_query .= ", `diskspace_used` = `diskspace_used` ";
							if ( ($diskspace/1024) != '-1' )
							{
								$admin_update_query .= " + 0".($diskspace)." ";
							}
							if ( ($result['diskspace']/1024) != '-1' )
							{
								$admin_update_query .= " - 0".($result['diskspace'])." ";
							}
						}
						$admin_update_query .= " WHERE `adminid` = '{$result['adminid']}'";
						$db->query( $admin_update_query );

    					redirectTo ( $config->get('env.filename') , Array ( 'page' => $config->get('env.page') , 's' => $config->get('env.s') ) ) ;
					}
				}
				else
				{
					$language_options = '';
					$languages = $language->getList();
					while(list($language_file, $language_name) = each($languages))
					{
						$language_options .= makeoption($language_name, $language_file, $result['def_language']);
					}
					$result['traffic']=$result['traffic']/(1024*1024);
					$result['diskspace']=$result['diskspace']/1024;
					$result['email'] = $idna->decode($result['email']);
					$createstdsubdomain=makeyesno('createstdsubdomain', '1', '0', (($result['standardsubdomain'] != '0') ? '1' : '0'));
					$deactivated=makeyesno('deactivated', '1', '0', $result['deactivated']);
					eval("echo \"".getTemplate("customers/customers_edit")."\";");
				}
			}
		}
	}

?>

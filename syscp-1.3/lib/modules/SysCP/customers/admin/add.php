<?php
	if ($this->User['customers'] != '0')
    {
			if($this->User['customers_used'] < $this->User['customers'] || $this->User['customers'] == '-1')
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
					$email = $this->IdnaHandler->encode ( addslashes ( $_POST['email'] ) ) ;
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

					if( ( ( ($this->User['diskspace_used']        + $diskspace)        > $this->User['diskspace'])        && ($this->User['diskspace']/1024) != '-1') ||
					    ( ( ($this->User['mysqls_used']           + $mysqls)           > $this->User['mysqls'])           && $this->User['mysqls'] != '-1') ||
					    ( ( ($this->User['emails_used']           + $emails)           > $this->User['emails'])           && $this->User['emails'] != '-1') ||
					    ( ( ($this->User['email_accounts_used']   + $email_accounts)   > $this->User['email_accounts'])   && $this->User['email_accounts'] != '-1') ||
					    ( ( ($this->User['email_forwarders_used'] + $email_forwarders) > $this->User['email_forwarders']) && $this->User['email_forwarders'] != '-1') ||
					    ( ( ($this->User['ftps_used']             + $ftps)             > $this->User['ftps'])             && $this->User['ftps'] != '-1') ||
					    ( ( ($this->User['subdomains_used']       + $subdomains)       > $this->User['subdomains'])       && $this->User['subdomains'] != '-1') ||
					    ( ($diskspace/1024) == '-1' && ($this->User['diskspace']/1024) != '-1' ) ||
					    ( $mysqls == '-1'           && $this->User['mysqls'] != '-1' ) ||
					    ( $emails == '-1'           && $this->User['emails'] != '-1' ) ||
					    ( $email_accounts == '-1'   && $this->User['email_accounts'] != '-1' ) ||
					    ( $email_forwarders == '-1' && $this->User['email_forwarders'] != '-1' ) ||
					    ( $ftps == '-1'             && $this->User['ftps'] != '-1' ) ||
					    ( $subdomains == '-1'       && $this->User['subdomains'] != '-1' )
					  )
					{
						$this->TemplateHandler->showError('youcantallocatemorethanyouhave');
						return false;
					}

					if($name == '')
					{
				       	$this->TemplateHandler->showError(array('stringisempty','myname'));
						return false;
					}
					elseif($firstname=='')
					{
						$this->TemplateHandler->showError(array('stringisempty','myfirstname'));
						return false;
					}
					elseif($email == '')
					{
						$this->TemplateHandler->showError(array('stringisempty','emailadd'));
						return false;
					}
					elseif(!verify_email($email))
					{
						$this->TemplateHandler->showError('emailiswrong',$email);
						return false;
					}
					else
					{
						if(isset($_POST['loginname']) && $_POST['loginname'] != '')
						{
							$loginname = addslashes($_POST['loginname']);
							$loginname_check = $this->DatabaseHandler->queryFirst("SELECT `loginname` FROM `".TABLE_PANEL_CUSTOMERS."` WHERE `loginname`='".$loginname."'");
							$loginname_check_admin = $this->DatabaseHandler->queryFirst("SELECT `loginname` FROM `".TABLE_PANEL_ADMINS."` WHERE `loginname`='".$loginname."'");

						if($loginname_check['loginname'] == $loginname || $loginname_check_admin['loginname'] == $loginname)
						{
							$this->TemplateHandler->showError('loginnameexists',$loginname);
							return false;
						}
						elseif(!check_username($loginname))
						{
							$this->TemplateHandler->showError('loginnameiswrong',$loginname);
							return false;
						}

							$accountnumber=intval($this->ConfigHandler->get('system.lastaccountnumber'));
						}
						else
						{
							$accountnumber=intval($this->ConfigHandler->get('system.lastaccountnumber'))+1;
							$loginname = $this->ConfigHandler->get('customer.accountprefix').$accountnumber;
						}

						$guid=intval($this->ConfigHandler->get('system.lastguid'))+1;

						$documentroot = $this->ConfigHandler->get('system.user_homedir');
						$documentroot = str_replace('{LOGIN}', $loginname, $documentroot);

						if($createstdsubdomain != '1')
						{
							$createstdsubdomain = '0';
						}

						if($password == '')
						{
							$password=substr(md5(uniqid(microtime(),1)),12,6);
						}

						$result=$this->DatabaseHandler->query(
							"INSERT INTO `".TABLE_PANEL_CUSTOMERS."` ".
							"(`adminid`, `loginname`, `password`, `name`, `firstname`, `company`, `street`, `zipcode`, `city`, `phone`, `fax`, `email`, `customernumber`, `def_language`, `homedir`, `guid`, `diskspace`, `traffic`, `subdomains`, `emails`, `email_accounts`, `email_forwarders`, `ftps`, `mysqls`, `standardsubdomain`) ".
							" VALUES ('{$this->User['adminid']}', '$loginname', '".md5($password)."', '$name', '$firstname', '$company', '$street', '$zipcode', '$city', '$phone', '$fax', '$email', '$customernumber','$def_language', '$documentroot', '$guid', '$diskspace', '$traffic', '$subdomains', '$emails', '$email_accounts', '$email_forwarders', '$ftps', '$mysqls', '0')"
							);
						$customerid=$this->DatabaseHandler->insert_id();

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
						$admin_update_query .= " WHERE `adminid` = '{$this->User['adminid']}'";
						$this->DatabaseHandler->query( $admin_update_query );

						$this->DatabaseHandler->query(
							"UPDATE `".TABLE_PANEL_SETTINGS."` " .
							"SET `value`='$guid' " .
							"WHERE `settinggroup`='system' AND `varname`='lastguid'"
						);
						$this->DatabaseHandler->query(
							"UPDATE `".TABLE_PANEL_SETTINGS."` " .
							"SET `value`='$accountnumber' " .
							"WHERE `settinggroup`='system' AND `varname`='lastaccountnumber'"
						);

						$this->HookHandler->call( 'OnCreateCustomer',
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
						$this->DatabaseHandler->query(
							"INSERT INTO `".TABLE_PANEL_HTPASSWDS."` " .
							"(`customerid`, `username`, `password`, `path`) " .
							"VALUES ('$customerid', '$loginname', '$htpasswdPassword', '$path')"
						);

						$htpasswdID = $this->DatabaseHandler->insert_id();
						$this->HookHandler->call( 'OnCreateHTPasswd',
						              array( 'id'   => $htpasswdID,
						                     'path' => $path ) );

						$result=$this->DatabaseHandler->query(
							"INSERT INTO `".TABLE_FTP_USERS."` " .
							"(`customerid`, `username`, `password`, `homedir`, `login_enabled`, `uid`, `gid`) " .
							"VALUES ('$customerid', '$loginname', ENCRYPT('$password'), '$documentroot/', 'y', '$guid', '$guid')"
						);
						$result=$this->DatabaseHandler->query(
							"INSERT INTO `".TABLE_FTP_GROUPS."` " .
							"(`customerid`, `groupname`, `gid`, `members`) " .
							"VALUES ('$customerid', '$loginname', '$guid', '$loginname')"
						);

						// If the users want a standardsubdomain, we need to create it here.
						// We will use the documentroot_prefix as new subdomain
						if($createstdsubdomain == '1') {

							$stdSubdomainName = $loginname.'.'.$this->ConfigHandler->get('system.hostname');

							$stdSubdomainDocRoot = $this->ConfigHandler->get('system.documentroot_prefix');
							$stdSubdomainDocRoot = str_replace('{LOGIN}', $loginname, $stdSubdomainDocRoot);
							$stdSubdomainDocRoot = str_replace('{USERHOME}', $documentroot, $stdSubdomainDocRoot);
							$stdSubdomainDocRoot = str_replace('{DOMAIN}', $stdSubdomainName, $stdSubdomainDocRoot);

							// set the access logfile
							$accessLog = $this->ConfigHandler->get('system.apache_access_log');
							$accessLog = str_replace('{LOGIN}', $loginname, $accessLog);
							$accessLog = str_replace('{USERHOME}', $documentroot, $accessLog);
							$accessLog = str_replace('{DOMAIN}', $stdSubdomainName, $accessLog);

							// set the error logfile
							$errorLog = $this->ConfigHandler->get('system.apache_error_log');
							$errorLog = str_replace('{LOGIN}', $loginname, $errorLog);
							$errorLog = str_replace('{USERHOME}', $documentroot, $errorLog);
							$errorLog = str_replace('{DOMAIN}', $stdSubdomainName, $errorLog);

							$ipandport = $this->DatabaseHandler->queryFirst('SELECT `id` FROM `'.TABLE_PANEL_IPSANDPORTS.'` WHERE `default`=\'1\'');
							$ipandport = intval($ipandport['id']);
							$query = 'INSERT INTO `%s` '
							       . 'SET `domain`          = \'%s\', '
							       . '    `customerid`      = \'%s\', '
							       . '    `adminid`         = \'%s\', '
							       . '    `parentdomainid`  = \'-1\', '
							       . '    `documentroot`    = \'%s\', '
							       . '    `ipandport`       = \'%s\', '
							       . '    `zonefile`        = \'\', '
							       . '    `isemaildomain`   = \'0\', '
							       . '    `caneditdomain`   = \'0\', '
							       . '    `openbasedir`     = \'1\', '
							       . '    `safemode`        = \'1\', '
							       . '    `access_logfile`  = \''.$accessLog.'\', '
							       . '    `error_logfile`   = \''.$errorLog.'\', '
//							       . '    `speciallogfile`  = \'0\', '
							       . '    `specialsettings` = \'\'';
							$query = sprintf($query, TABLE_PANEL_DOMAINS,
							                         $stdSubdomainName,
							                         $customerid,
							                         $this->User['adminid'],
							                         $stdSubdomainDocRoot,
							                         $ipandport);
							$this->DatabaseHandler->query($query);
							$domainid=$this->DatabaseHandler->insert_id();
							$this->DatabaseHandler->query(
								'UPDATE `'.TABLE_PANEL_CUSTOMERS.'` ' .
								'SET `standardsubdomain`=\''.$domainid.'\' ' .
								'WHERE `customerid`=\''.$customerid.'\''
							);
							$this->HookHandler->call( 'OnCreateDomain',
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
							$result=$this->DatabaseHandler->queryFirst('SELECT `value` FROM `'.TABLE_PANEL_TEMPLATES.'` WHERE `adminid`=\''.$this->User['adminid'].'\' AND `language`=\''.$def_language.'\' AND `templategroup`=\'mails\' AND `varname`=\'createcustomer_subject\'');
							$mail_subject=_html_entity_decode(replace_variables((($result['value']!='') ? $result['value'] : $this->L10nHandler->get('mails.createcustomer.subject')),$replace_arr));
							$result=$this->DatabaseHandler->queryFirst('SELECT `value` FROM `'.TABLE_PANEL_TEMPLATES.'` WHERE `adminid`=\''.$this->User['adminid'].'\' AND `language`=\''.$def_language.'\' AND `templategroup`=\'mails\' AND `varname`=\'createcustomer_mailbody\'');
							$mail_body=_html_entity_decode(replace_variables((($result['value']!='') ? $result['value'] : $this->L10nHandler->get('mails.createcustomer.mailbody')),$replace_arr));
							mail("$firstname $name <$email>",$mail_subject,$mail_body,"From: {$this->User['name']} <{$this->User['email']}>\r\n");
						}

    					$this->redirectTo(array('module' => 'customers',
    					                        'action' => 'list'));
					}
				}
				else
				{
					$language_options = '';
					$languages = $this->L10nHandler->getList();

					$createstdsubdomain = array(1 => $this->L10nHandler->get('panel.yes'),
					                            0 => $this->L10nHandler->get('panel.no') );
					$createstdsubdomain_sel = 1;
					$sendpassword = array( 1 => $this->L10nHandler->get('panel.yes'),
					                       0 => $this->L10nHandler->get('panel.no') );
					$sendpassword_sel = 1;

					$this->TemplateHandler->set('createstdsubdomain', $createstdsubdomain);
					$this->TemplateHandler->set('createstdsubdomain_sel', $createstdsubdomain_sel);
					$this->TemplateHandler->set('languages', $languages );
					$this->TemplateHandler->set('sendpassword', $sendpassword);
					$this->TemplateHandler->set('sendpassword_sel', $sendpassword_sel);
					$this->TemplateHandler->setTemplate('SysCP/customers/admin/add.tpl');
				}
			}
    }

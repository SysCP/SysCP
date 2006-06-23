<?php
	if ($this->User['customers'] != '0')
    {
		if($this->ConfigHandler->get('env.id')!=0)
		{
			$result=$this->DatabaseHandler->queryFirst("SELECT * FROM `".TABLE_PANEL_CUSTOMERS."` WHERE `customerid`='".$this->ConfigHandler->get('env.id')."' ".( $this->User['customers_see_all'] ? '' : " AND `adminid` = '{$this->User['adminid']}' ") );
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
					$email = $this->IdnaHandler->encode ( addslashes ( $_POST['email'] ) ) ;
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

					if( ( ( ($this->User['diskspace_used']        + $diskspace        - $result['diskspace'])        > $this->User['diskspace'])        && ($this->User['diskspace']/1024) != '-1') ||
					    ( ( ($this->User['mysqls_used']           + $mysqls           - $result['mysqls'])           > $this->User['mysqls'])           && $this->User['mysqls'] != '-1') ||
					    ( ( ($this->User['emails_used']           + $emails           - $result['emails'])           > $this->User['emails'])           && $this->User['emails'] != '-1') ||
					    ( ( ($this->User['email_accounts_used']   + $email_accounts   - $result['email_accounts'])   > $this->User['email_accounts'])   && $this->User['email_accounts'] != '-1') ||
					    ( ( ($this->User['email_forwarders_used'] + $email_forwarders - $result['email_forwarders']) > $this->User['email_forwarders']) && $this->User['email_forwarders'] != '-1') ||
					    ( ( ($this->User['ftps_used']             + $ftps             - $result['ftps'])             > $this->User['ftps'])             && $this->User['ftps'] != '-1') ||
					    ( ( ($this->User['subdomains_used']       + $subdomains       - $result['subdomains'])       > $this->User['subdomains'])       && $this->User['subdomains'] != '-1') ||
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
						$updatepassword='';
						if($newpassword!='')
						{
							$updatepassword="`password`='".md5($newpassword)."', ";
						}

						if($createstdsubdomain != '1')
						{
							$createstdsubdomain = '0';
						}
						// Check if we need to create a new standardsudomain for this user
						if($createstdsubdomain == '1' && $result['standardsubdomain'] == '0')
						{
							$stdSubdomainName = $result['loginname'].'.'.$this->ConfigHandler->get('system.hostname');
							$stdSubdomainDocRoot = $this->ConfigHandler->get('system.documentroot_prefix');
							$stdSubdomainDocRoot = str_replace('{LOGIN}', $result['loginname'], $stdSubdomainDocRoot);
							$stdSubdomainDocRoot = str_replace('{USERHOME}', $result['homedir'], $stdSubdomainDocRoot);
							$stdSubdomainDocRoot = str_replace('{DOMAIN}', $stdSubdomainName, $stdSubdomainDocRoot);
							$stdSubdomainDocRoot = makeCorrectDir($stdSubdomainDocRoot);
							// set the access logfile
							$accessLog = $this->ConfigHandler->get('system.apache_access_log');
							$accessLog = str_replace('{LOGIN}', $result['loginname'], $accessLog);
							$accessLog = str_replace('{USERHOME}', $result['homedir'], $accessLog);
							$accessLog = str_replace('{DOMAIN}', $stdSubdomainName, $accessLog);

							// set the error logfile
							$errorLog = $this->ConfigHandler->get('system.apache_error_log');
							$errorLog = str_replace('{LOGIN}', $result['loginname'], $errorLog);
							$errorLog = str_replace('{USERHOME}', $result['homedir'], $errorLog);
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
							                         $result['customerid'],
							                         $this->User['adminid'],
							                         $stdSubdomainDocRoot,
							                         $ipandport);
							$this->DatabaseHandler->query($query);
							$domainid=$this->DatabaseHandler->insert_id();
							$this->DatabaseHandler->query(
								'UPDATE `'.TABLE_PANEL_CUSTOMERS.'` ' .
								'SET `standardsubdomain`=\''.$domainid.'\' ' .
								'WHERE `customerid`=\''.$result['customerid'].'\''
							);
							$this->HookHandler->call( 'OnCreateDomain',
							              array( 'id' => $domainid ) );
						}
						if($createstdsubdomain == '0' && $result['standardsubdomain'] != '0')
						{
							$this->DatabaseHandler->query(
								'DELETE FROM `'.TABLE_PANEL_DOMAINS.'` ' .
								'WHERE `id`=\''.$result['standardsubdomain'].'\''
							);
							$this->DatabaseHandler->query(
								'UPDATE `'.TABLE_PANEL_CUSTOMERS.'` ' .
								'SET `standardsubdomain`=\'0\' ' .
								'WHERE `customerid`=\''.$result['customerid'].'\''
							);
							$this->HookHandler->call( 'OnDeleteDomain',
							              array( 'id' => $result['standardsubdomain'] ) );
						}

						if($deactivated != '1')
						{
							$deactivated = '0';
						}
						if($deactivated != $result['deactivated'])
						{
							$this->DatabaseHandler->query("UPDATE `".TABLE_MAIL_USERS."` SET `postfix`='".( ($deactivated) ? 'N' : 'Y' )."' WHERE `customerid`='".$this->ConfigHandler->get('env.id')."'");
							$this->DatabaseHandler->query("UPDATE `".TABLE_FTP_USERS."` SET `login_enabled`='".( ($deactivated) ? 'N' : 'Y' )."' WHERE `customerid`='".$this->ConfigHandler->get('env.id')."'");
							$this->DatabaseHandler->query("UPDATE `".TABLE_PANEL_DOMAINS."` SET `deactivated`='$deactivated' WHERE `customerid`='".$this->ConfigHandler->get('env.id')."'");
							// @TODO: Check this code carefully! It does both enable/disable
							$this->HookHandler->call( 'OnDeactivateCustomer',
							              array( 'id' => $this->ConfigHandler->get('env.id') ) );
						}

						$this->DatabaseHandler->query("UPDATE `".TABLE_PANEL_CUSTOMERS."` SET `name`='$name', `firstname`='$firstname', `company`='$company', `street`='$street', `zipcode`='$zipcode', `city`='$city', `phone`='$phone', `fax`='$fax', `email`='$email', `customernumber`='$customernumber', `def_language`='$def_language', $updatepassword `diskspace`='$diskspace', `traffic`='$traffic', `subdomains`='$subdomains', `emails`='$emails', `email_accounts` = '$email_accounts', `email_forwarders`='$email_forwarders', `ftps`='$ftps', `mysqls`='$mysqls', `deactivated`='$deactivated' WHERE `customerid`='".$this->ConfigHandler->get('env.id')."'");

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
						$this->DatabaseHandler->query( $admin_update_query );

    					$this->redirectTo( array('module' => 'customers',
    					                         'action' => 'list'));
					}
				}
				else
				{
					// EDIT VIEW
					$result['traffic']   = $result['traffic']/(1024*1024);
					$result['diskspace'] = $result['diskspace']/1024;
					$result['email']     = $this->IdnaHandler->decode($result['email']);
					$lang_list = $this->L10nHandler->getList();
					$deactivated = array( 1 => $this->L10nHandler->get('panel.yes'),
					                      0 => $this->L10nHandler->get('panel.no') );
					$createstdsubdomain = array(1 => $this->L10nHandler->get('panel.yes'),
					                            0 => $this->L10nHandler->get('panel.no') );
					if($result['standardsubdomain'] != '0')
					{
						$createstdsubdomain_sel = 1;
					}
					else
					{
						$createstdsubdomain_sel = 0;
					}

					$this->TemplateHandler->set('result', $result);
					$this->TemplateHandler->set('lang_list', $lang_list);
					$this->TemplateHandler->set('createstdsubdomain', $createstdsubdomain);
					$this->TemplateHandler->set('createstdsubdomain_sel', $createstdsubdomain_sel);
					$this->TemplateHandler->set('deactivated', $deactivated);
					$this->TemplateHandler->setTemplate('SysCP/customers/admin/edit.tpl');
				}
			}
		}
    }

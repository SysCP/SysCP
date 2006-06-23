<?php
if ($this->User['domains_used'] < $this->User['domains'] || $this->User['domains'] == '-1')
{
	if(isset($_POST['send']) && $_POST['send']=='send')
	{
		// load some core values from the submitted form
		$domain = $this->IdnaHandler->encode(preg_replace(array('/\:(\d)+$/','/^https?\:\/\//'),
		                                                  '',
		                                                  addslashes($_POST['domain'])));
		$customerid        = (int)$_POST['customerid'];
		$subcanemaildomain = (int)$_POST['subcanemaildomain'];
		$isemaildomain     = (int)$_POST['isemaildomain'];
		$aliasdomain       = (int)$_POST['alias'];

		// load the user owning the domain
		$query    = 'SELECT `loginname`, `homedir` FROM `%s` WHERE `customerid`=\'%s\'';
		$query    = sprintf($query, TABLE_PANEL_CUSTOMERS, $customerid);
		$customer = $this->DatabaseHandler->queryFirst($query);

		// set the documentroot
		$documentroot = $this->ConfigHandler->get('system.documentroot_prefix');
		$documentroot = str_replace('{LOGIN}', $customer['loginname'], $documentroot);
		$documentroot = str_replace('{USERHOME}', $customer['homedir'], $documentroot);
		$documentroot = str_replace('{DOMAIN}', $domain, $documentroot);

		// set the access logfile
		$accessLog = (string)$_POST['access_log'];
		if($accessLog == '')
		{
			$accessLog = $this->ConfigHandler->get('system.apache_access_log');
		}
		$accessLog = str_replace('{LOGIN}', $customer['loginname'], $accessLog);
		$accessLog = str_replace('{USERHOME}', $customer['homedir'], $accessLog);
		$accessLog = str_replace('{DOMAIN}', $domain, $accessLog);

		// set the error logfile
		$errorLog = (string)$_POST['error_log'];
		if($errorLog == '')
		{
			$errorLog = $this->ConfigHandler->get('system.apache_error_log');
		}
		$errorLog = str_replace('{LOGIN}', $customer['loginname'], $errorLog);
		$errorLog = str_replace('{USERHOME}', $customer['homedir'], $errorLog);
		$errorLog = str_replace('{DOMAIN}', $domain, $errorLog);

		// now lets continue to load data from the form, if the user has the needed rights
		if($this->User['change_serversettings'] == '1')
		{
			$isbinddomain    = $_POST['isbinddomain']; // (int) ??
			$caneditdomain   = (int)$_POST['caneditdomain'];
			$zonefile        = addslashes($_POST['zonefile']);
			$openbasedir     = (int)$_POST['openbasedir'];
			$safemode        = (int)$_POST['safemode'];
//			$speciallogfile  = (int)$_POST['speciallogfile'];
			$specialsettings = str_replace("\r\n", "\n", $_POST['specialsettings']);
			$ipandport       = (int)$_POST['ipandport'];

			// and now lets check if the user has changed the documentroot
			if(isset($_POST['documentroot']) && $_POST['documentroot'] != '')
			{
				if (substr($_POST['documentroot'],0,1) != '/')
				{
					// WE DON'T HAVE ANY REPLACERS HERE!
					/**
					 * @todo Check what's exactly happening here with the new templated pathes
					 */
					$documentroot .= '/'.addslashes($_POST['documentroot']);
				}
				else
				{
					$documentroot = addslashes($_POST['documentroot']);
				}
			}
		}
		else
		{
			// The user is not allowed to change serversettings, we set some manual ones
			/**
			 * @todo Consider loading the default values from the database
			 */
			$isbinddomain    = 1;
			$caneditdomain   = 1;
			$zonefile        = '';
			$openbasedir     = 1;
			$safemode        = 1;
//			$speciallogfile  = 1;
			$specialsettings = '';
			// query the default ip:port
			$query = 'SELECT `id` FROM `'.TABLE_PANEL_IPSANDPORTS.'` WHERE `default`=\'1\'';
			$ipandport = $this->DatabaseHandler->queryFirst($query);
			$ipandport = (int)$ipandport['id'];
		}

		// make a correct dir if the documentroot is not a redirect
		if(!preg_match('/^https?\:\/\//', $documentroot))
		{
			$documentroot = makeCorrectDir($documentroot);
		}

		$domain_check = $this->DatabaseHandler->queryFirst("SELECT `id`, `domain` FROM `".TABLE_PANEL_DOMAINS."` WHERE `domain` = '$domain'");
		$aliasdomain_check=array('id' => 0);
		if($aliasdomain!=0)
		{
			$aliasdomain_check = $this->DatabaseHandler->queryFirst('SELECT `id` FROM `'.TABLE_PANEL_DOMAINS.'` `d`,`'.TABLE_PANEL_CUSTOMERS.'` `c` WHERE `d`.`customerid`=\''.$customerid.'\' AND `d`.`aliasdomain` IS NULL AND `d`.`id`<>`c`.`standardsubdomain` AND `c`.`customerid`=\''.$customerid.'\' AND `d`.`id`=\''.$aliasdomain.'\'');
		}

		// Sanitize some values
		/**
		 * @todo consider this to be written as if ($value != 1) $value = 0; to reduce lines and enhance
		 *       code cleaness
		 */
		if($openbasedir != 1)
		{
			$openbasedir = 0;
		}
		if($safemode != 1)
		{
			$safemode = 0;
		}
//		if($speciallogfile != 1)
//		{
//			$speciallogfile = 0;
//		}
		if($isbinddomain != 1)
		{
			$isbinddomain = 0;
		}
		if($isemaildomain != 1)
		{
			$isemaildomain = 0;
		}
		if($subcanemaildomain != 1)
		{
			$subcanemaildomain = 0;
		}
		if($caneditdomain != 1)
		{
			$caneditdomain = 0;
		}

		// Check for errors in the values we got
		if ($domain == '')
		{
			$this->TemplateHandler->showError(array('stringisempty','mydomain'));
			return false;
		}
		elseif (!check_domain($domain))
		{
			$this->TemplateHandler->showError(array('stringiswrong','mydomain'));
			return false;
		}
		elseif ($documentroot == '')
		{
			$this->TemplateHandler->showError(array('stringisempty','mydocumentroot'));
			return false;
		}
		elseif ($customerid == 0)
		{
			$this->TemplateHandler->showError('adduserfirst');
			return false;
		}
		elseif ($domain_check['domain'] == $domain)
		{
			$this->TemplateHandler->showError('domainalreadyexists',$domain);
			return false;
		}
		elseif ($aliasdomain_check['id'] != $aliasdomain)
		{
			$this->TemplateHandler->showError('domainisaliasorothercustomer');
			return false;
		}
		else
		{
			// we had no errors yet, we now need to ensure some values, because they are critical
			// we ask if they are really correct
			if (($openbasedir == '0' || $safemode == '0') &&
				(!isset($_POST['reallydoit']) || $_POST['reallydoit'] != 'reallydoit'))
			{
				$this->TemplateHandler->showQuestion(
				     'admin_domain_reallydisablesecuritysetting',
				     array('module' => 'domains',
				           'action' => $this->ConfigHandler->get('env.action'),
				           'domain' => $domain,
				           'documentroot' => $documentroot,
				           'customerid' => $customerid,
				           'alias' => $aliasdomain,
				           'isbinddomain' => $isbinddomain,
				           'isemaildomain' => $isemaildomain,
				           'subcanemaildomain' => $subcanemaildomain,
				           'caneditdomain' => $caneditdomain,
				           'zonefile' => $zonefile,
				           'speciallogfile' => $speciallogfile,
				           'openbasedir' => $openbasedir,
				           'ipandport' => $ipandport,
				           'safemode' => $safemode,
				           'access_log' => $accessLog,
				           'error_log' => $errorLog,
				           'specialsettings' => urlencode($specialsettings),
				           'reallydoit' => 'reallydoit'));
				// we stop the execution here and return with a positive result to the
				// controller
				return true;
			}
			// lets do a check if the documentroot is not within the users
			// homedirectory, which could be happen by accident or intentionally,
			// we simply ensure it was the intention of the admin
			$pattern = sprintf('#^%s#', $customer['homedir']);
			if (!preg_match($pattern, $documentroot) &&
			   (!isset($_POST['reallydocroot']) || $_POST['reallydocroot'] != 'reallydocroot'))
			{
				$params = array('module'            => 'domains',
				                'action'            => $this->ConfigHandler->get('env.action'),
				                'domain'            => $domain,
				                'documentroot'      => $documentroot,
				                'customerid'        => $customerid,
				                'alias'             => $aliasdomain,
				                'isbinddomain'      => $isbinddomain,
				                'isemaildomain'     => $isemaildomain,
				                'subcanemaildomain' => $subcanemaildomain,
				                'caneditdomain'     => $caneditdomain,
				                'zonefile'          => $zonefile,
				                'speciallogfile'    => $speciallogfile,
				                'openbasedir'       => $openbasedir,
				                'ipandport'         => $ipandport,
				                'safemode'          => $safemode,
					            'access_log' => $accessLog,
					            'error_log' => $errorLog,
				                'specialsettings'   => urlencode($specialsettings),
				                'reallydocroot'     => 'reallydocroot');
				if ( isset($_POST['reallydoit']) )
				{
					$params['reallydoit']      = 'reallydoit';
					$params['specialsettings'] = $specialsettings;
				}
				$this->TemplateHandler->showQuestion('admin_domain_reallydocrootoutofcustomerroot',
				                                     $params);
				// we stop the execution here and return with a positive result to the
				// controller
				return true;
			}
			// if we had a question printed to the screen we need to decode the specialsettings
			// because they were send urlencoded through the question.
			if ((isset($_POST['reallydoit'])    && $_POST['reallydoit']    == 'reallydoit') ||
			    (isset($_POST['reallydocroot']) && $_POST['reallydocroot'] == 'reallydocroot'))
			{
				$specialsettings = urldecode($specialsettings);
			}
			$specialsettings = addslashes($specialsettings);

			if ($aliasdomain == 0)
			{
				$aliasdomain = 'NULL';
			}
			$query = 'INSERT INTO `'.TABLE_PANEL_DOMAINS.'` '
			       . 'SET `domain`           = \''.$domain.'\', '
			       . '    `customerid`       = \''.$customerid.'\', '
			       . '    `adminid`          = \''.$this->User['adminid'].'\', '
			       . '    `documentroot`     = \''.$documentroot.'\', '
			       . '    `ipandport`        = \''.$ipandport.'\', '
			       . '    `aliasdomain`      = '.$aliasdomain.', '
			       . '    `zonefile`         = \''.$zonefile.'\', '
			       . '    `isbinddomain`     = \''.$isbinddomain.'\', '
			       . '    `isemaildomain`    = \''.$isemaildomain.'\', '
			       . '    `subcanemaildomain`= \''.$subcanemaildomain.'\', '
			       . '    `caneditdomain`    = \''.$caneditdomain.'\', '
			       . '    `openbasedir`      = \''.$openbasedir.'\', '
			       . '    `safemode`         = \''.$safemode.'\', '
			       . '    `access_logfile`   = \''.$accessLog.'\', '
			       . '    `error_logfile`    = \''.$errorLog.'\', '
//			       . '    `speciallogfile`   = \''.$speciallogfile.'\', '
			       . '    `specialsettings`  = \''.$specialsettings.'\' ';
			$this->DatabaseHandler->query($query);
			$domainid = $this->DatabaseHandler->insert_id();
			// update resources of the admin
			/**
			 * @todo We should implement a reaccount resources function!
			 */
			$query = 'UPDATE `'.TABLE_PANEL_ADMINS.'` '
			       . 'SET `domains_used` = `domains_used` + 1 '
			       . 'WHERE `adminid` = \''.$this->User['adminid'].'\'';
			$this->DatabaseHandler->query($query);
			// call the hook to notice everybody a domain has been created
			$this->HookHandler->call('OnCreateDomain',
			                         array( 'id' => $domainid));
			// redirect back to the list
			$this->redirectTo(array('module' => 'domains',
    		                        'action' => 'list'));
		}
	}
	else
	{
		// Generate the View
		$customers = array();
		$query     = 'SELECT `customerid`, `loginname`, `name`, `firstname` '
		           . 'FROM `'.TABLE_PANEL_CUSTOMERS.'` ';
		if($this->User['customers_see_all'] != 1)
		{
			$query .= 'WHERE `adminid` = \''.$this->User['adminid'].'\' ';
		}
		$query    .= 'ORDER BY `name` ASC';
		$result_customers = $this->DatabaseHandler->query($query);

		while($row_customer = $this->DatabaseHandler->fetchArray($result_customers))
		{
			$customers[$row_customer['customerid']] = $row_customer['name'] . ' '
			                                        . $row_customer['firstname']
			                                        . ' ('.$row_customer['loginname'].')';
		}
		$this->TemplateHandler->set('customers', $customers);

		$ipsandports = array();
		$query = 'SELECT `id`, `ip`, `port` '
		       . 'FROM `'.TABLE_PANEL_IPSANDPORTS.'` '
		       . 'ORDER BY `ip` ASC';
		$result_ipsandports = $this->DatabaseHandler->query($query);
		$query = 'SELECT `id` FROM `'.TABLE_PANEL_IPSANDPORTS.'` WHERE `default` = 1';
		$result_ipsandports_default=$this->DatabaseHandler->queryFirst($query);
		while($row_ipandport = $this->DatabaseHandler->fetchArray($result_ipsandports))
		{
			$ipsandports[$row_ipandport['id']] = $row_ipandport['ip'].':'.$row_ipandport['port'];
		}
		$this->TemplateHandler->set('ipsandports_sel', $result_ipsandports_default['id']);
		$this->TemplateHandler->set('ipsandports', $ipsandports);

		$standardsubdomains=array();
		$query = 'SELECT `id` '
		       . 'FROM `'.TABLE_PANEL_DOMAINS.'` `d`, `'.TABLE_PANEL_CUSTOMERS.'` `c` '
		       . 'WHERE `d`.`id` = `c`.`standardsubdomain`';
		$result_standardsubdomains=$this->DatabaseHandler->query($query);
		while($row_standardsubdomain=$this->DatabaseHandler->fetch_array($result_standardsubdomains))
		{
			$standardsubdomains[]=$row_standardsubdomain['id'];
		}
		if(count($standardsubdomains)>0)
		{
			$standardsubdomains='AND `d`.`id` NOT IN ('.join(',',$standardsubdomains).') ';
		}
		else
		{
			$standardsubdomains='';
		}
		$domains = array();
		$domains[0] = $this->L10nHandler->get('domains.noaliasdomain');
		$result_domains=$this->DatabaseHandler->query("SELECT `d`.`id`, `d`.`domain`, `c`.`loginname` FROM `".TABLE_PANEL_DOMAINS."` `d`, `".TABLE_PANEL_CUSTOMERS."` `c` WHERE `d`.`aliasdomain` IS NULL AND `d`.`parentdomainid`=0 ".$standardsubdomains.( $this->User['customers_see_all'] ? '' : "AND `d`.`adminid` = '{$this->User['adminid']}' ")."AND `d`.`customerid`=`c`.`customerid` ORDER BY `loginname`, `domain` ASC");
		while($row_domain=$this->DatabaseHandler->fetch_array($result_domains))
		{
			$domains[$row_domain['id']] = $this->IdnaHandler->decode($row_domain['domain']).' ('.$row_domain['loginname'].')';
		}
		$this->TemplateHandler->set('domains', $domains );

		$isbinddomain      = array( 1 => $this->L10nHandler->get('panel.yes'),
		                            0 => $this->L10nHandler->get('panel.no'));
		$isemaildomain     = array( 1 => $this->L10nHandler->get('panel.yes'),
		                            0 => $this->L10nHandler->get('panel.no'));
		$subcanemaildomain = array( 1 => $this->L10nHandler->get('panel.yes'),
		                            0 => $this->L10nHandler->get('panel.no'));
		$caneditdomain     = array( 1 => $this->L10nHandler->get('panel.yes'),
		                            0 => $this->L10nHandler->get('panel.no'));
		$openbasedir       = array( 1 => $this->L10nHandler->get('panel.yes'),
		                            0 => $this->L10nHandler->get('panel.no'));
		$safemode          = array( 1 => $this->L10nHandler->get('panel.yes'),
		                            0 => $this->L10nHandler->get('panel.no'));
		$speciallogfile    = array( 1 => $this->L10nHandler->get('panel.yes'),
		                            0 => $this->L10nHandler->get('panel.no'));
		$this->TemplateHandler->set('isbinddomain', $isbinddomain);
		$this->TemplateHandler->set('isemaildomain', $isemaildomain);
		$this->TemplateHandler->set('subcanemaildomain', $subcanemaildomain);
		$this->TemplateHandler->set('caneditdomain', $caneditdomain);
		$this->TemplateHandler->set('openbasedir', $openbasedir);
		$this->TemplateHandler->set('safemode', $safemode);
		$this->TemplateHandler->set('speciallogfile', $speciallogfile);

		$this->TemplateHandler->set('isbinddomain_sel', 1);
		$this->TemplateHandler->set('isemaildomain_sel', 1);
		$this->TemplateHandler->set('subcanemaildomain_sel', 0);
		$this->TemplateHandler->set('caneditdomain_sel', 1);
		$this->TemplateHandler->set('openbasedir_sel', 1);
		$this->TemplateHandler->set('safemode_sel', 1);
		$this->TemplateHandler->set('speciallogfile_sel', 0);

		$this->TemplateHandler->setTemplate('SysCP/domains/admin/add.tpl');
	}
}
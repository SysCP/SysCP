<?php
if($this->ConfigHandler->get('env.id')!=0)
{
	// Check if the requested domain does exists and load it.
	$result=$this->DatabaseHandler->queryFirst(
		"SELECT `d`.`id`, `d`.`domain`, `d`.`customerid`, `d`.`documentroot`, `d`.`ipandport`, `d`.`aliasdomain`, `d`.`isbinddomain`, `d`.`isemaildomain`, `d`.`subcanemaildomain`, `d`.`caneditdomain`, `d`.`zonefile`, `d`.`openbasedir`, `d`.`safemode`, `d`.`speciallogfile`, `d`.`specialsettings`, `d`.`access_logfile`, `d`.`error_logfile`, `c`.`loginname`, `c`.`name`, `c`.`firstname` " .
		"FROM `".TABLE_PANEL_DOMAINS."` `d` " .
		"LEFT JOIN `".TABLE_PANEL_CUSTOMERS."` `c` USING(`customerid`) " .
		"WHERE `d`.`parentdomainid`='0' AND `d`.`id`='".$this->ConfigHandler->get('env.id')."'".( $this->User['customers_see_all'] ? '' : " AND `d`.`adminid` = '{$this->User['adminid']}' ")
	);
	$alias_check=$this->DatabaseHandler->queryFirst('SELECT COUNT(`id`) AS count FROM `'.TABLE_PANEL_DOMAINS.'` WHERE `aliasdomain`=\''.$result['id'].'\'');
	$alias_check=$alias_check['count'];
	if($result['domain']!='')
	{
		if(isset($_POST['send']) && $_POST['send']=='send')
		{
			$customer=$this->DatabaseHandler->queryFirst("SELECT `homedir` FROM ".TABLE_PANEL_CUSTOMERS." WHERE `customerid`='".$result['customerid']."'");

			$aliasdomain = intval($_POST['alias']);
			$isemaildomain = intval($_POST['isemaildomain']);
			$subcanemaildomain = intval($_POST['subcanemaildomain']);
			$caneditdomain = intval($_POST['caneditdomain']);
			if($this->User['change_serversettings'] == '1')
			{
				$isbinddomain = $_POST['isbinddomain'];
				$zonefile = addslashes($_POST['zonefile']);
				$openbasedir = intval($_POST['openbasedir']);
				$safemode = intval($_POST['safemode']);
				$specialsettings = str_replace("\r\n", "\n", $_POST['specialsettings']);
				$ipandport = intval($_POST['ipandport']);
				$documentroot = addslashes($_POST['documentroot']);
				if($documentroot=='')
				{
					$documentroot = $customer['homedir'];
				}
			}
			else
			{
				$isbinddomain = $result['isbinddomain'];
				$zonefile = $result['zonefile'];
				$openbasedir = $result['openbasedir'];
				$safemode = $result['safemode'];
				$specialsettings = $result['specialsettings'];
				$ipandport = $result['ipandport'];
				$documentroot = $result['documentroot'];
			}
					if(!preg_match('/^https?\:\/\//', $documentroot))
					{
						$documentroot = makeCorrectDir($documentroot);
					}

					if($openbasedir != '1')
					{
						$openbasedir = '0';
					}
					if($safemode != '1')
					{
						$safemode = '0';
					}
					if($isbinddomain != '1')
					{
						$isbinddomain = '0';
					}
					if($isemaildomain != '1')
					{
						$isemaildomain = '0';
					}
					if($subcanemaildomain != '1')
					{
						$subcanemaildomain = '0';
					}
					if($caneditdomain != '1')
					{
						$caneditdomain = '0';
					}

					$aliasdomain_check=array('id' => 0);
					if($aliasdomain!=0)
					{
						$aliasdomain_check = $this->DatabaseHandler->queryFirst('SELECT `id` FROM `'.TABLE_PANEL_DOMAINS.'` `d`,`'.TABLE_PANEL_CUSTOMERS.'` `c` WHERE `d`.`customerid`=\''.$result['customerid'].'\' AND `d`.`aliasdomain` IS NULL AND `d`.`id`<>`c`.`standardsubdomain` AND `c`.`customerid`=\''.$result['customerid'].'\' AND `d`.`id`=\''.$aliasdomain.'\'');
					}
					if($aliasdomain_check['id']!=$aliasdomain)
					{
						$this->throwError('domainisaliasorothercustomer');
					}

					if(($openbasedir == '0' || $safemode == '0') && (!isset($_POST['reallydoit']) || $_POST['reallydoit'] != 'reallydoit') && $this->User['change_serversettings'] == '1')
					{
						$this->TemplateHandler->showQuestion('admin_domain_reallydisablesecuritysetting',
						                    array('module' => 'domains',
						                          'id' => $this->ConfigHandler->get('env.id'),
						                          'action' => $this->ConfigHandler->get('env.action'),
						                          'documentroot' => $documentroot,
						                          'alias' => $aliasdomain,
						                          'isbinddomain' => $isbinddomain,
						                          'isemaildomain' => $isemaildomain,
						                          'subcanemaildomain' => $subcanemaildomain,
						                          'caneditdomain' => $caneditdomain,
						                          'zonefile' => $zonefile,
						                          'openbasedir' => $openbasedir,
						                          'ipandport' => $ipandport,
						                          'safemode' => $safemode,
						                          'specialsettings' => urlencode($specialsettings),
						                          'reallydoit' => 'reallydoit'));
						return true;
					}
					$pattern = sprintf('^%s', $customer['homedir']);
					if( !ereg($pattern,$documentroot)
					    && ( !isset($_POST['reallydocroot'] )
					       || $_POST['reallydocroot'] != 'reallydocroot') )
					{
						$params = array('module' => 'domains',
						                'id' => $this->ConfigHandler->get('env.id'),
						                'action' => $this->ConfigHandler->get('env.action'),
						                'documentroot' => $documentroot,
						                'alias' => $aliasdomain,
						                'isbinddomain' => $isbinddomain,
						                'isemaildomain' => $isemaildomain,
						                'subcanemaildomain' => $subcanemaildomain,
						                'caneditdomain' => $caneditdomain,
						                'zonefile' => $zonefile,
						                'openbasedir' => $openbasedir,
						                'ipandport' => $ipandport,
						                'safemode' => $safemode,
						                'specialsettings' => urlencode($specialsettings),
						                'reallydocroot' => 'reallydocroot');
						if ( isset($_POST['reallydoit']) )
						{
							$params['reallydoit'] = 'reallydoit';
							$params['specialsettings'] = $specialsettings;
						}
						$this->TemplateHandler->showQuestion('admin_domain_reallydocrootoutofcustomerroot',
						                    $params);
						return true;

					}

					if( (isset($_POST['reallydoit']) && $_POST['reallydoit'] == 'reallydoit')
					   || (isset( $_POST['reallydocroot']) && $_POST['reallydocroot'] == 'reallydocroot' ) )
					{
						$specialsettings = urldecode($specialsettings);
					}

					if(    $documentroot != $result['documentroot']
					    || $ipandport != $result['ipandport']
					    || $openbasedir != $result['openbasedir']
					    || $safemode != $result['safemode']
					    || $specialsettings != $result['specialsettings'])
					{
						$this->HookHandler->call( 'OnUpdateDomain',
						              array( 'id' => $this->ConfigHandler->get('env.id') ) );
					}
					if($isbinddomain != $result['isbinddomain'] || $zonefile != $result['zonefile'] || $ipandport != $result['ipandport'])
					{
						$this->HookHandler->call( 'OnUpdateDomain',
						              array( 'id' => $this->ConfigHandler->get('env.id') ) );
					}
					if($isemaildomain == '0' && $result['isemaildomain'] == '1')
					{
						$this->DatabaseHandler->query("DELETE FROM `".TABLE_MAIL_USERS."` WHERE `domainid`='".$this->ConfigHandler->get('env.id')."' ");
						$this->DatabaseHandler->query("DELETE FROM `".TABLE_MAIL_VIRTUAL."` WHERE `domainid`='".$this->ConfigHandler->get('env.id')."' ");
					}

					$specialsettings = addslashes($specialsettings);
					$result=$this->DatabaseHandler->query("UPDATE `".TABLE_PANEL_DOMAINS."` SET `documentroot`='$documentroot', `ipandport`='$ipandport', `aliasdomain`=".(($aliasdomain!=0 && $alias_check==0) ? '\''.$aliasdomain.'\'' : 'NULL').", `isbinddomain`='$isbinddomain', `isemaildomain`='$isemaildomain', `subcanemaildomain`='$subcanemaildomain', `caneditdomain`='$caneditdomain', `zonefile`='$zonefile', `openbasedir`='$openbasedir', `safemode`='$safemode', `specialsettings`='$specialsettings' WHERE `id`='".$this->ConfigHandler->get('env.id')."'");
					$result=$this->DatabaseHandler->query("UPDATE `".TABLE_PANEL_DOMAINS."` SET `ipandport`='$ipandport', `openbasedir`='$openbasedir', `safemode`='$safemode', `specialsettings`='$specialsettings'  WHERE `parentdomainid`='".$this->ConfigHandler->get('env.id')."'");

					$this->HookHandler->call('OnUpdateDomain', array('id' => $this->ConfigHandler->get('env.id')));

					$this->redirectTo(array('module' => 'domains',
					                        'action' => 'list'));
				}
				else
				{
					$result['domain']          = $this->IdnaHandler->decode($result['domain']);
					$result['specialsettings'] = stripslashes($result['specialsettings']);
					$result['speciallogfile']  = ($result['speciallogfile'] == 1 ? $this->L10nHandler->get('panel.yes') : $this->L10nHandler->get('panel.no'));

					$domains = array();
					$domains[0] = $this->L10nHandler->get('domains.noaliasdomain');
					$result_domains=$this->DatabaseHandler->query("SELECT `d`.`id`, `d`.`domain` FROM `".TABLE_PANEL_DOMAINS."` `d`, `".TABLE_PANEL_CUSTOMERS."` `c` WHERE `d`.`aliasdomain` IS NULL AND `d`.`parentdomainid`=0 AND `d`.`id`<>'".$result['id']."' AND `c`.`standardsubdomain`<>`d`.`id` AND `d`.`customerid`='".$result['customerid']."' AND `c`.`customerid`=`d`.`customerid` ORDER BY `d`.`domain` ASC");
					while($row_domain=$this->DatabaseHandler->fetch_array($result_domains))
					{
						$domains[$row_domain['id']] = $this->IdnaHandler->decode($row_domain['domain']);
					}
					$this->TemplateHandler->set('domains', $domains);
					$this->TemplateHandler->set('alias_check', $alias_check);

					$ipsandports = array();
					$result_ipsandports=$this->DatabaseHandler->query("SELECT `id`, `ip`, `port` FROM `".TABLE_PANEL_IPSANDPORTS."` ORDER BY `ip` ASC");
					while($row_ipandport=$this->DatabaseHandler->fetch_array($result_ipsandports))
					{
						$ipsandports[$row_ipandport['id']] = $row_ipandport['ip'].':'.$row_ipandport['port'];
					}
					$this->TemplateHandler->set('ipsandports', $ipsandports);

					// eval("echo \"".getTemplate("domains/domains_edit")."\";");
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
					$this->TemplateHandler->set('isbinddomain', $isbinddomain);
					$this->TemplateHandler->set('isemaildomain', $isemaildomain);
					$this->TemplateHandler->set('subcanemaildomain', $subcanemaildomain);
					$this->TemplateHandler->set('caneditdomain', $caneditdomain);
					$this->TemplateHandler->set('openbasedir', $openbasedir);
					$this->TemplateHandler->set('safemode', $safemode);

					$this->TemplateHandler->set('result', $result);
					$this->TemplateHandler->setTemplate('SysCP/domains/admin/edit.tpl');
				}
			}
		}
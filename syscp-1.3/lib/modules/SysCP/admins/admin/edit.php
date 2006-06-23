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
 * @package    Syscp.Modules
 * @subpackage Index
 * @license    GPLv2 http://files.syscp.org/misc/COPYING.txt
 * @version    $Id:admin_index.php 460 2006-04-23 15:07:49 +0200 (So, 23 Apr 2006) martin $
 */
	if($this->User['change_serversettings'] == '1' )
	{
			if($this->ConfigHandler->get('env.id') == '1')
			{
				$this->TemplateHandler->showError('youcantdeletechangemainadmin');
				return false;
			}
			$result=$this->DatabaseHandler->queryFirst("SELECT * FROM `".TABLE_PANEL_ADMINS."` WHERE `adminid`='".$this->ConfigHandler->get('env.id')."'");
			if($result['loginname']!='')
			{
				if(isset($_POST['send']) && $_POST['send']=='send')
				{
					$name = addslashes ( $_POST['name'] ) ;
					$newpassword = addslashes ( $_POST['newpassword'] ) ;
					$email = $this->IdnaHandler->encode ( addslashes ( $_POST['email'] ) ) ;
					$def_language = addslashes($_POST['def_language']);
					$deactivated = intval ( $_POST['deactivated'] ) ;
					$customers = intval_ressource ( $_POST['customers'] ) ;
					$domains = intval_ressource ( $_POST['domains'] ) ;
					$subdomains = intval_ressource ( $_POST['subdomains'] ) ;
					$emails = intval_ressource ( $_POST['emails'] ) ;
					$email_accounts = intval_ressource ( $_POST['email_accounts'] ) ;
					$email_forwarders = intval_ressource ( $_POST['email_forwarders'] ) ;
					$ftps = intval_ressource ( $_POST['ftps'] ) ;
					$mysqls = intval_ressource ( $_POST['mysqls'] ) ;
					$customers_see_all = intval ( $_POST['customers_see_all'] ) ;
					$domains_see_all = intval ( $_POST['domains_see_all'] ) ;
					$change_serversettings = intval ( $_POST['change_serversettings'] ) ;

					$diskspace = intval ( $_POST['diskspace'] ) ;
					$traffic = doubleval_ressource ( $_POST['traffic'] ) ;
					$diskspace = $diskspace * 1024 ;
					$traffic = $traffic * 1024 * 1024 ;

					if($name == '')
					{
						$this->TemplateHandler->showError(array('stringisempty','myname'));
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

						if($deactivated != '1')
						{
							$deactivated = '0';
						}

						if($customers_see_all != '1')
						{
							$customers_see_all = '0';
						}
						if($domains_see_all != '1')
						{
							$domains_see_all = '0';
						}
						if($change_serversettings != '1')
						{
							$change_serversettings = '0';
						}

						$this->DatabaseHandler->query("UPDATE `".TABLE_PANEL_ADMINS."` SET `name`='$name', `email`='$email', `def_language`='$def_language', `change_serversettings` = '$change_serversettings', `customers` = '$customers', `customers_see_all` = '$customers_see_all', `domains` = '$domains', `domains_see_all` = '$domains_see_all', $updatepassword `diskspace`='$diskspace', `traffic`='$traffic', `subdomains`='$subdomains', `emails`='$emails', `email_accounts` = '$email_accounts', `email_forwarders`='$email_forwarders', `ftps`='$ftps', `mysqls`='$mysqls', `deactivated`='$deactivated' WHERE `adminid`='".$this->ConfigHandler->get('env.id')."'");

    					$this->redirectTo(array('module' => 'admins',
    					                        'action' => 'list'));
					}
				}
				else
				{
					$result['traffic']   = $result['traffic']/(1024*1024);
					$result['diskspace'] = $result['diskspace']/1024;
					$result['email']     = $this->IdnaHandler->decode($result['email']);

					$change_serversettings = array( 1 => $this->L10nHandler->get('panel.yes'),
					                                0 => $this->L10nHandler->get('panel.no'));
					$customers_see_all = array( 1 => $this->L10nHandler->get('panel.yes'),
					                                0 => $this->L10nHandler->get('panel.no'));
					$domains_see_all = array( 1 => $this->L10nHandler->get('panel.yes'),
					                                0 => $this->L10nHandler->get('panel.no'));
					$deactivated = array( 1 => $this->L10nHandler->get('panel.yes'),
					                                0 => $this->L10nHandler->get('panel.no'));

					$this->TemplateHandler->set('lang_list', $this->L10nHandler->getList() );
					$this->TemplateHandler->set('change_serversettings', $change_serversettings);
					$this->TemplateHandler->set('domains_see_all', $domains_see_all);
					$this->TemplateHandler->set('customers_see_all', $customers_see_all);
					$this->TemplateHandler->set('deactivated', $deactivated);

					$this->TemplateHandler->set('result', $result );

					$this->TemplateHandler->setTemplate('SysCP/admins/admin/edit.tpl');
				}
			}
	}
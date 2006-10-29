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

if($this->User['change_serversettings'] == '1')
{
    if($this->ConfigHandler->get('env.id') == '1')
    {
        $this->TemplateHandler->showError('SysCP.admins.error.youcantdeletechangemainadmin');
        return false;
    }

    $result = $this->DatabaseHandler->queryFirst("SELECT * FROM `".TABLE_PANEL_ADMINS."` WHERE `adminid`='".(int)$this->ConfigHandler->get('env.id')."'");

    if($result['loginname'] != '')
    {
        if(isset($_POST['send'])
           && $_POST['send'] == 'send')
        {
            // check name

            $name = $_POST['name'];

            if($name == '')
            {
                $this->TemplateHandler->showError('SysCP.globallang.error.stringisempty', $this->L10nHandler->get('SysCP.globallang.name'));
                return false;
            }
            elseif(!$this->ValidationHandler->getValidator('basic')->validate($name))
            {
                $this->TemplateHandler->showError('SysCP.globallang.error.stringiswrong', $this->L10nHandler->get('SysCP.globallang.name'));
                return false;
            }

            // check password

            $newpassword = $_POST['newpassword'];
            $updatepassword = '';

            if($newpassword != '')
            {
                $updatepassword = "`password`='".md5($newpassword)."', ";
            }

            // check email

            $email = $this->IdnaHandler->encode($_POST['email']);

            if($email == '')
            {
                $this->TemplateHandler->showError('SysCP.globallang.error.stringisempty', $this->L10nHandler->get('SysCP.admins.email'));
                return false;
            }
            elseif(!$this->ValidationHandler->getValidator('email')->validate($email))
            {
                $this->TemplateHandler->showError('SysCP.globallang.error.stringiswrong', $this->L10nHandler->get('SysCP.admins.email'));
                return false;
            }

            // check default language

            $def_language = $_POST['def_language'];

            if(!$this->ValidationHandler->getValidator('basic')->validate($def_language))
            {
                $this->TemplateHandler->showError('Syscp.globallang.error.stringiswrong', $this->L10nHandler->get('SysCP.admins.language'));
                return false;
            }

            // transform input to proper types

            $deactivated = intval($_POST['deactivated']);
            $customers = intval_ressource($_POST['customers']);
            $domains = intval_ressource($_POST['domains']);
            $subdomains = intval_ressource($_POST['subdomains']);
            $emails = intval_ressource($_POST['emails']);
            $email_accounts = intval_ressource($_POST['email_accounts']);
            $email_forwarders = intval_ressource($_POST['email_forwarders']);
            $ftps = intval_ressource($_POST['ftps']);
            $mysqls = intval_ressource($_POST['mysqls']);
            $customers_see_all = intval($_POST['customers_see_all']);
            $domains_see_all = intval($_POST['domains_see_all']);
            $change_serversettings = intval($_POST['change_serversettings']);
            $diskspace = intval($_POST['diskspace']);
            $traffic = doubleval_ressource($_POST['traffic']);
            $diskspace = $diskspace*1024;
            $traffic = $traffic*1024*1024;

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

            $this->DatabaseHandler->query("UPDATE `".TABLE_PANEL_ADMINS."` SET
                    `name`='".$this->DatabaseHandler->escape($name)."',
                    `email`='".$this->DatabaseHandler->escape($email)."',
                    `def_language`='".$this->DatabaseHandler->escape($def_language)."',
                    `change_serversettings` = '".$this->DatabaseHandler->escape($change_serversettings)."',
                    `customers` = '".$this->DatabaseHandler->escape($customers)."',
                    `customers_see_all` = '".$this->DatabaseHandler->escape($customers_see_all)."',
                    `domains` = '".$this->DatabaseHandler->escape($domains)."',
                    `domains_see_all` = '".$this->DatabaseHandler->escape($domains_see_all)."',
                    $updatepassword
                    `diskspace`='".$this->DatabaseHandler->escape($diskspace)."',
                    `traffic`='".$this->DatabaseHandler->escape($traffic)."',
                    `subdomains`='".$this->DatabaseHandler->escape($subdomains)."',
                    `emails`='".$this->DatabaseHandler->escape($emails)."',
                    `email_accounts` = '".$this->DatabaseHandler->escape($email_accounts)."',
                    `email_forwarders`='".$this->DatabaseHandler->escape($email_forwarders)."',
                    `ftps`='".$this->DatabaseHandler->escape($ftps)."',
                    `mysqls`='".$this->DatabaseHandler->escape($mysqls)."',
                    `deactivated`='".$this->DatabaseHandler->escape($deactivated)."'
                WHERE `adminid`='".(int)$this->ConfigHandler->get('env.id')."'");
            $this->redirectTo(array(
                'module' => 'admins',
                'action' => 'list'
            ));
        }
        else
        {
            $result['traffic'] = $result['traffic']/(1024*1024);
            $result['diskspace'] = $result['diskspace']/1024;
            $result['email'] = $this->IdnaHandler->decode($result['email']);
            $change_serversettings = array(
                1 => $this->L10nHandler->get('SysCP.globallang.yes'),
                0 => $this->L10nHandler->get('SysCP.globallang.no')
            );
            $customers_see_all = array(
                1 => $this->L10nHandler->get('SysCP.globallang.yes'),
                0 => $this->L10nHandler->get('SysCP.globallang.no')
            );
            $domains_see_all = array(
                1 => $this->L10nHandler->get('SysCP.globallang.yes'),
                0 => $this->L10nHandler->get('SysCP.globallang.no')
            );
            $deactivated = array(
                1 => $this->L10nHandler->get('SysCP.globallang.yes'),
                0 => $this->L10nHandler->get('SysCP.globallang.no')
            );
            $this->TemplateHandler->set('lang_list', $this->L10nHandler->getList());
            $this->TemplateHandler->set('change_serversettings', $change_serversettings);
            $this->TemplateHandler->set('domains_see_all', $domains_see_all);
            $this->TemplateHandler->set('customers_see_all', $customers_see_all);
            $this->TemplateHandler->set('deactivated', $deactivated);
            $this->TemplateHandler->set('result', $result);
            $this->TemplateHandler->setTemplate('SysCP/admins/admin/edit.tpl');
        }
    }
}


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

        // check loginname

        $loginname = $_POST['loginname'];

        if($loginname == '')
        {
            $this->TemplateHandler->showError('SysCP.globallang.error.stringisempty', $this->L10nHandler->get('SysCP.admins.username'));
            return false;
        }
        elseif(!$this->ValidationHandler->getValidator('username')->validate($loginname))
        {
            $this->TemplateHandler->showError('SysCP.globallang.error.stringiswrong', $this->L10nHandler->get('SysCP.admins.loginname'));
            return false;
        }

        $loginname_check = $this->DatabaseHandler->queryFirst("SELECT `loginname` FROM `".TABLE_PANEL_ADMINS."` WHERE `loginname`='".$this->DatabaseHandler->escape($loginname)."'");

        if($loginname_check['loginname'] == $loginname)
        {
            $this->TemplateHandler->showError('SysCP.globallang.error.loginnameexists', $loginname);
            return false;
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

        // check password

        $password = $_POST['password'];

        if($password == '')
        {
            $this->TemplateHandler->showError('SysCP.globallang.error.stringisempty', $this->L10nHandler->get('SysCP.admins.password'));
            return false;
        }
        elseif(!$this->ValidationHandler->getValidator('basic')->validate($password))
        {
            $this->TemplateHandler->showError('SysCP.globallang.error.stringiswrong', $this->L10nHandler->get('SysCP.admins.password'));
            return false;
        }

        // check default language

        $def_language = $_POST['def_language'];

        if(!$this->ValidationHandler->getValidator('basic')->validate($def_language))
        {
            $this->TemplateHandler->showError('SysCP.globallang.error.stringiswrong', $this->L10nHandler->get('SysCP.admins.language'));
            return false;
        }

        // transform input to proper types

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
        $diskspace = intval_ressource($_POST['diskspace']);
        $traffic = doubleval_ressource($_POST['traffic']);
        $diskspace = $diskspace*1024;
        $traffic = $traffic*1024*1024;

        // check for "right" values

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

        $result = $this->DatabaseHandler->query("INSERT INTO `".TABLE_PANEL_ADMINS."` (`loginname`, `password`, `name`, `email`, `def_language`, `change_serversettings`, `customers`, `customers_see_all`, `domains`, `domains_see_all`, `diskspace`, `traffic`, `subdomains`, `emails`, `email_accounts`, `email_forwarders`, `ftps`, `mysqls`)
        VALUES ('".$this->DatabaseHandler->escape($loginname)."',
            '".md5($password)."',
            '".$this->DatabaseHandler->escape($name)."',
            '".$this->DatabaseHandler->escape($email)."',
            '".$this->DatabaseHandler->escape($def_language)."',
            '".$this->DatabaseHandler->escape($change_serversettings)."',
            '".$this->DatabaseHandler->escape($customers)."',
            '".$this->DatabaseHandler->escape($customers_see_all)."',
            '".$this->DatabaseHandler->escape($domains)."',
            '".$this->DatabaseHandler->escape($domains_see_all)."',
            '".$this->DatabaseHandler->escape($diskspace)."',
            '".$this->DatabaseHandler->escape($traffic)."',
            '".$this->DatabaseHandler->escape($subdomains)."',
            '".$this->DatabaseHandler->escape($emails)."',
            '".$this->DatabaseHandler->escape($email_accounts)."',
            '".$this->DatabaseHandler->escape($email_forwarders)."',
            '".$this->DatabaseHandler->escape($ftps)."',
            '".$this->DatabaseHandler->escape($mysqls)."')");
        $adminid = $this->DatabaseHandler->insert_id();
        $this->redirectTo(array(
            'module' => 'admins',
            'action' => 'list'
        ));
    }
    else
    {
        // ADD VIEW

        $this->TemplateHandler->set('lang_list', $this->L10nHandler->getList());
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
        $this->TemplateHandler->set('change_serversettings', $change_serversettings);
        $this->TemplateHandler->set('customers_see_all', $customers_see_all);
        $this->TemplateHandler->set('domains_see_all', $domains_see_all);
        $this->TemplateHandler->set('change_serversettings_sel', 0);
        $this->TemplateHandler->set('customers_see_all_sel', 0);
        $this->TemplateHandler->set('domains_see_all_sel', 0);
        $this->TemplateHandler->setTemplate('SysCP/admins/admin/add.tpl');
    }
}


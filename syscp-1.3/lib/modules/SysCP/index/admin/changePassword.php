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

if(isset($_POST['send'])
   && $_POST['send'] == 'send')
{
    $old_password = addslashes($_POST['old_password']);

    if(md5($old_password) != $this->User['password'])
    {
        $this->TemplateHandler->showError('SysCP.index.error.oldpw_incorrect');
        return false;
    }

    $new_password = addslashes($_POST['new_password']);
    $new_password_confirm = addslashes($_POST['new_password_confirm']);

    if($old_password == '')
    {
        $this->TemplateHandler->showError(
            'SysCP.globallang.error.stringisempty',
            'oldpassword'
        );
        return false;
    }
    elseif($new_password == '')
    {
        $this->TemplateHandler->showError(
            'SysCP.globallang.error.stringisempty',
            'newpassword'
        );
        return false;
    }
    elseif($new_password_confirm == '')
    {
        $this->TemplateHandler->showError(
            'SysCP.globallang.error.stringisempty',
            'newpasswordconfirm'
        );
        return false;
    }
    elseif($new_password != $new_password_confirm)
    {
        $this->TemplateHandler->showError('SysCP.index.error.newpw_confirmation');
        return false;
    }
    else
    {
        $this->DatabaseHandler->query("UPDATE `".TABLE_PANEL_ADMINS."` SET `password`='".md5($new_password)."' WHERE `adminid`='".$this->User['adminid']."' AND `password`='".md5($old_password)."'");
        $this->redirectTo(array(
            'module' => 'index',
            'action' => 'index'
        ));
    }
}
else
{
    $this->TemplateHandler->setTemplate('SysCP/index/admin/change_password.tpl');
}
<?php

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
        $this->DatabaseHandler->query("UPDATE `".TABLE_PANEL_CUSTOMERS."` SET `password`='".md5($new_password)."' WHERE `customerid`='".$this->User['customerid']."' AND `password`='".md5($old_password)."'");

        if(isset($_POST['change_main_ftp'])
           && $_POST['change_main_ftp'] == 'true')
        {
            $this->DatabaseHandler->query("UPDATE `".TABLE_FTP_USERS."` SET `password`=ENCRYPT('$new_password') WHERE `customerid`='".$this->User['customerid']."' AND `username`='".$this->User['loginname']."'");
        }

        $this->redirectTo(array(
            'module' => 'index',
            'action' => 'index'
        ));
    }
}
else
{
    $this->TemplateHandler->setTemplate('SysCP/index/customer/change_password.tpl');
}
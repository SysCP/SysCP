<?php

if($this->ConfigHandler->get('env.id') != 0)
{
    $result = $this->DatabaseHandler->query_first("SELECT `id`, `username`, `homedir` FROM `".TABLE_FTP_USERS."` WHERE `customerid`='".$this->User['customerid']."' AND `id`='".$this->ConfigHandler->get('env.id')."'");

    if(isset($result['username'])
       && $result['username'] != '')
    {
        if(isset($_POST['send'])
           && $_POST['send'] == 'send')
        {
            $password = addslashes($_POST['password']);

            if($password == '')
            {
                $this->TemplateHandler->showError(
                    'SysCP.globallang.error.stringisempty',
                    'mypassword'
                );
                return false;
            }
            else
            {
                $this->DatabaseHandler->query("UPDATE `".TABLE_FTP_USERS."` SET `password`=ENCRYPT('$password') WHERE `customerid`='".$this->User['customerid']."' AND `id`='".$this->ConfigHandler->get('env.id')."'");
                $this->redirectTo(array(
                    'module' => 'ftp',
                    'action' => 'list'
                ));
            }
        }
        else
        {
            $this->TemplateHandler->set('result', $result);
            $this->TemplateHandler->setTemplate('SysCP/ftp/customer/edit.tpl');
        }
    }
}


<?php

if(isset($_POST['send'])
   && $_POST['send'] == 'send')
{
    $path = makeCorrectDir(addslashes($_POST['path']));
    $userpath = $path;
    $path = $this->User['homedir'].$path;
    $username = addslashes($_POST['username']);
    $username_path_check = $this->DatabaseHandler->query_first("SELECT `id`, `username`, `path` FROM `".TABLE_PANEL_HTPASSWDS."` WHERE `username`='$username' AND `path`='$path' AND `customerid`='".$this->User['customerid']."'");

    if(CRYPT_STD_DES == 1)
    {
        $saltfordescrypt = substr(md5(uniqid(microtime(), 1)), 4, 2);
        $password = addslashes(crypt($_POST['password'], $saltfordescrypt));
    }
    else
    {
        $password = addslashes(crypt($_POST['password']));
    }

    $passwordtest = $_POST['password'];

    if(!$_POST['path'])
    {
        $this->TemplateHandler->showError('SysCP.extras.error.invalidpath');
        return false;
    }

    if(!is_dir($path))
    {
        $this->TemplateHandler->showError('SysCP.extras.error.directorymustexist', $userpath);
        return false;
    }
    elseif($username == '')
    {
        $this->TemplateHandler->showError(
            'SysCP.globallang.error.stringisempty',
            'myloginname'
        );
        return false;
    }
    elseif($username_path_check['username'] == $username
           && $username_path_check['path'] == $path)
    {
        $this->TemplateHandler->showError('SysCP.extras.error.userpathcombinationdupe');
        return false;
    }
    elseif($passwordtest == '')
    {
        $this->TemplateHandler->showError(
            'SysCP.globallang.error.stringisempty',
            'mypassword'
        );
        return false;
    }
    elseif($path == '')
    {
        $this->TemplateHandler->showError('SysCP.globallang.error.patherror');
        return false;
    }
    else
    {
        $this->DatabaseHandler->query("INSERT INTO `".TABLE_PANEL_HTPASSWDS."` (`customerid`, `username`, `password`, `path`) VALUES ('".$this->User['customerid']."', '$username', '$password', '$path')");
        $htpasswdID = $this->DatabaseHandler->insert_id();
        $this->HookHandler->call('OnCreateHTPasswd', array(
            'id' => $htpasswdID,
            'path' => $path
        ));
        $this->redirectTo(array(
            'module' => 'extras',
            'action' => 'listHtpasswds'
        ));
    }
}
else
{
    $pathSelect = makePathfield($this->User['homedir'], $this->User['guid'], $this->User['guid'], $this->ConfigHandler->get('panel.pathedit'));
    $this->TemplateHandler->set('pathSelect', $pathSelect);
    $this->TemplateHandler->setTemplate('SysCP/extras/customer/htpasswd_add.tpl');
}


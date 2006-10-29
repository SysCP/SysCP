<?php

if($this->User['ftps_used'] < $this->User['ftps']
   || $this->User['ftps'] == '-1')
{
    if(isset($_POST['send'])
       && $_POST['send'] == 'send')
    {
        $path = makeCorrectDir(addslashes($_POST['path']));
        $userpath = $path;
        $path = $this->User['homedir'].$path;
        $path_check = $this->DatabaseHandler->query_first("SELECT `id`, `username`, `homedir` FROM `".TABLE_FTP_USERS."` WHERE `homedir`='$path' AND `customerid`='".$this->User['customerid']."'");
        $password = addslashes($_POST['password']);

        if(!$_POST['path'])
        {
            $this->TemplateHandler->showError('SysCP.ftp.error.invalidpath');
            return false;
        }

        if(!is_dir($path))
        {
            $this->TemplateHandler->showError('SysCP.ftp.error.directorymustexist', $userpath);
            return false;
        }
        elseif($password == '')
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
            $username = $this->User['loginname'].$this->ConfigHandler->get('customer.ftpprefix').(intval($this->User['ftp_lastaccountnumber'])+1);
            $this->DatabaseHandler->query("INSERT INTO `".TABLE_FTP_USERS."` (`customerid`, `username`, `password`, `homedir`, `login_enabled`, `uid`, `gid`) VALUES ('".$this->User['customerid']."', '$username', ENCRYPT('$password'), '$path', 'y', '".$this->User['guid']."', '".$this->User['guid']."')");
            $this->DatabaseHandler->query("UPDATE `".TABLE_FTP_GROUPS."` SET `members`=CONCAT_WS(',',`members`,'".$username."') WHERE `customerid`='".$this->User['customerid']."' AND `gid`='".$this->User['guid']."'");

            //						$this->DatabaseHandler->query("INSERT INTO `".TABLE_FTP_GROUPS."` (`customerid`, `groupname`, `gid`, `members`) VALUES ('".$this->User['customerid']."', '$username', '$uid', '$username')");

            $this->DatabaseHandler->query("UPDATE `".TABLE_PANEL_CUSTOMERS."` SET `ftps_used`=`ftps_used`+1, `ftp_lastaccountnumber`=`ftp_lastaccountnumber`+1 WHERE `customerid`='".$this->User['customerid']."'");

            //						$this->DatabaseHandler->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value`='$uid' WHERE settinggroup='ftp' AND varname='lastguid'");

            $this->redirectTo(array(
                'module' => 'ftp',
                'action' => 'list'
            ));
        }
    }
    else
    {
        $pathSelect = makePathfield($this->User['homedir'], $this->User['guid'], $this->User['guid'], $this->ConfigHandler->get('panel.pathedit'));
        $this->TemplateHandler->set('pathSelect', $pathSelect);
        $this->TemplateHandler->setTemplate('SysCP/ftp/customer/add.tpl');
    }
}


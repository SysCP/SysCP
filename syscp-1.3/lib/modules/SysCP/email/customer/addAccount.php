<?php

if($this->ConfigHandler->get('env.id') != 0)
{
    if($this->User['email_accounts_used'] < $this->User['email_accounts']
       || $this->User['email_accounts'] == '-1')
    {
        $result = $this->DatabaseHandler->query_first("SELECT `id`, `email`, `email_full`, `iscatchall`, `destination`, `customerid`, `popaccountid`, `domainid` FROM `".TABLE_MAIL_VIRTUAL."` WHERE `customerid`='".$this->User['customerid']."' AND `id`='".$this->ConfigHandler->get('env.id')."'");

        if(isset($result['email'])
           && $result['email'] != ''
           && $result['popaccountid'] == '0')
        {
            if(isset($_POST['send'])
               && $_POST['send'] == 'send')
            {
                $email_full = $result['email_full'];
                $username = $this->IdnaHandler->decode($email_full);
                $password = addslashes($_POST['password']);

                if($email_full == '')
                {
                    $this->TemplateHandler->showError(
                        'SysCP.globallang.error.stringisempty',
                        'emailadd'
                    );
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
                else
                {
                    $homedir = $this->ConfigHandler->get('system.vmail_homedir');
                    $homedir = str_replace('{LOGIN}', $this->User['loginname'], $homedir);
                    $homedir = str_replace('{USERHOME}', $this->User['homedir'], $homedir);
                    $homedir = makeCorrectDir($homedir);
                    $query = 'INSERT INTO `'.TABLE_MAIL_USERS.'` '.'SET `customerid`   =\''.$this->User['customerid'].'\', '.'    `email`        =\''.$email_full.'\', '.'    `username`     =\''.$username.'\', '.'    `password`     =\''.$password.'\', '.'    `password_enc` =ENCRYPT(\''.$password.'\'), '.'    `homedir`      =\''.$homedir.'\', '.'    `maildir`      =\'/'.$email_full.'/\', '.'    `uid`          =\''.$this->ConfigHandler->get('system.vmail_uid').'\', '.'    `gid`          =\''.$this->ConfigHandler->get('system.vmail_gid').'\', '.'    `domainid`     =\''.$result['domainid'].'\', '.'    `postfix`      =\'y\' ';
                    $this->DatabaseHandler->query($query);
                    $popaccountid = $this->DatabaseHandler->insert_id();
                    $result['destination'].= ' '.$email_full;
                    $this->DatabaseHandler->query("UPDATE `".TABLE_MAIL_VIRTUAL."` SET `destination` = '".makeCorrectDestination($result['destination'])."', `popaccountid` = '$popaccountid' WHERE `customerid`='".$this->User['customerid']."' AND `id`='".$this->ConfigHandler->get('env.id')."'");
                    $this->DatabaseHandler->query("UPDATE `".TABLE_PANEL_CUSTOMERS."` SET `email_accounts_used`=`email_accounts_used`+1 WHERE `customerid`='".$this->User['customerid']."'");
                    $replace_arr = array(
                        'EMAIL' => $email_full,
                        'USERNAME' => $username
                    );
                    $admin = $this->DatabaseHandler->query_first('SELECT `name`, `email` FROM `'.TABLE_PANEL_ADMINS.'` WHERE `adminid`=\''.$this->User['adminid'].'\'');
                    $result = $this->DatabaseHandler->query_first('SELECT `value` FROM `'.TABLE_PANEL_TEMPLATES.'` WHERE `adminid`=\''.$this->User['adminid'].'\' AND `language`=\''.$this->User['def_language'].'\' AND `templategroup`=\'mails\' AND `varname`=\'pop_success_subject\'');
                    $mail_subject = html_entity_decode(replace_variables((($result['value'] != '') ? $result['value'] : $this->L10nHandler->get('mails.pop_success.subject')), $replace_arr));
                    $result = $this->DatabaseHandler->query_first('SELECT `value` FROM `'.TABLE_PANEL_TEMPLATES.'` WHERE `adminid`=\''.$this->User['adminid'].'\' AND `language`=\''.$this->User['def_language'].'\' AND `templategroup`=\'mails\' AND `varname`=\'pop_success_mailbody\'');
                    $mail_body = html_entity_decode(replace_variables((($result['value'] != '') ? $result['value'] : $this->L10nHandler->get('mails.pop_success')), $replace_arr));
                    mail("$email_full", $mail_subject, $mail_body, "From: {$admin['name']} <{$admin['email']}>\r\n");
                    $this->redirectTo(array(
                        'module' => 'email',
                        'action' => 'edit',
                        'id' => $this->ConfigHandler->get('env.id')
                    ));
                }
            }
            else
            {
                $result['email_full'] = $this->IdnaHandler->decode($result['email_full']);
                $this->TemplateHandler->set('result', $result);
                $this->TemplateHandler->setTemplate('SysCP/email/customer/addAccount.tpl');
            }
        }
    }
    else
    {
        $this->TemplateHandler->showError('SysCP.globallang.error.allresourcesused');
        return false;
    }
}


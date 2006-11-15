<?php

if($this->User['emails_used'] < $this->User['emails']
   || $this->User['emails'] == '-1')
{
    if(isset($_POST['send'])
       && $_POST['send'] == 'send')
    {
        $email_part = addslashes($_POST['email_part']);
        $domain = $this->IdnaHandler->encode(addslashes($_POST['domain']));
        $domain_check = $this->DatabaseHandler->query_first("SELECT `id`, `domain`, `customerid` FROM `".TABLE_PANEL_DOMAINS."` WHERE `domain`='$domain' AND `customerid`='".$this->User['customerid']."' AND `isemaildomain`='1' AND aliasdomain IS NULL");

        if(isset($_POST['iscatchall'])
           && $_POST['iscatchall'] == '1')
        {
            $iscatchall = '1';
            $email = '@'.$domain;
        }
        else
        {
            $iscatchall = '0';
            $email = $email_part.'@'.$domain;
        }

        $email_full = $email_part.'@'.$domain;
        $email_check = $this->DatabaseHandler->query_first("SELECT `id`, `email`, `email_full`, `iscatchall`, `destination`, `customerid` FROM `".TABLE_MAIL_VIRTUAL."` WHERE ( `email`='$email' OR `email_full` = '$email_full' ) AND `customerid`='".$this->User['customerid']."'");

        if($email == ''
           || $email_full == ''
           || $email_part == '')
        {
            $this->TemplateHandler->showError(
                'SysCP.globallang.error.stringisempty',
                'emailadd'
            );
            return false;
        }
        elseif($domain == '')
        {
            $this->TemplateHandler->showError('SysCP.email.error.domaincantbeempty');
            return false;
        }
        elseif($domain_check['domain'] != $domain)
        {
            $this->TemplateHandler->showError('SysCP.email.error.maindomainnonexist', $domain);
            return false;
        }
        elseif($email_check['email_full'] == $email_full)
        {
            $this->TemplateHandler->showError('SysCP.email.error.emailexistalready', $email_full);
            return false;
        }
        elseif(!$this->ValidationHandler->getValidator('email')->validate($email_part.'@'.$domain))
        {
            $this->TemplateHandler->showError('SysCP.email.error.emailiswrong', $email_full);
            return false;
        }
        elseif($email_check['email'] == $email)
        {
            $this->TemplateHandler->showError('SysCP.email.error.youhavealreadyacatchallforthisdomain');
            return false;
        }
        else
        {
            $this->DatabaseHandler->query("INSERT INTO `".TABLE_MAIL_VIRTUAL."` (`customerid`, `email`, `email_full`, `iscatchall`, `domainid`) VALUES ('".$this->User['customerid']."', '$email', '$email_full', '$iscatchall', '".$domain_check['id']."')");
            $address_id = $this->DatabaseHandler->insert_id();
            $this->DatabaseHandler->query("UPDATE ".TABLE_PANEL_CUSTOMERS." SET `emails_used` = `emails_used` + 1 WHERE `customerid`='".$this->User['customerid']."'");
            $this->redirectTo(array(
                'module' => 'email',
                'action' => 'edit',
                'id' => $address_id
            ));
        }
    }
    else
    {
        $result = $this->DatabaseHandler->query("SELECT `id`, `domain`, `customerid` FROM `".TABLE_PANEL_DOMAINS."` WHERE `customerid`='".$this->User['customerid']."' AND `isemaildomain`='1' AND aliasdomain IS NULL ORDER BY `domain` ASC");
        $domains = array();

        while($row = $this->DatabaseHandler->fetch_array($result))
        {
            $domains[$row['domain']] = $this->IdnaHandler->decode($row['domain']);
        }

        //$iscatchall = makeyesno ( 'iscatchall' , '1' , '0' , '0');

        $isCatchall = array(
            1 => $this->L10nHandler->get('SysCP.globallang.yes'),
            0 => $this->L10nHandler->get('SysCP.globallang.no')
        );
        $isCatchallSel = 0;
        $this->TemplateHandler->set('domainList', $domains);
        $this->TemplateHandler->set('isCatchall', $isCatchall);
        $this->TemplateHandler->set('isCatchallSel', $isCatchallSel);
        $this->TemplateHandler->setTemplate('SysCP/email/customer/add.tpl');

        //eval("echo \"".getTemplate("email/emails_add")."\";");
    }
}
else
{
    $this->TemplateHandler->showError('SysCP.globallang.error.allresourcesused');
    return false;
}


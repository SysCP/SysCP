<?php

if($this->ConfigHandler->get('env.id') != 0)
{
    if($this->User['email_forwarders_used'] < $this->User['email_forwarders']
       || $this->User['email_forwarders'] == '-1')
    {
        $result = $this->DatabaseHandler->query_first("SELECT `id`, `email`, `email_full`, `iscatchall`, `destination`, `customerid`, `popaccountid`, `domainid` FROM `".TABLE_MAIL_VIRTUAL."` WHERE `customerid`='".$this->User['customerid']."' AND `id`='".$this->ConfigHandler->get('env.id')."'");

        if(isset($result['email'])
           && $result['email'] != '')
        {
            if(isset($_POST['send'])
               && $_POST['send'] == 'send')
            {
                $destination = $this->IdnaHandler->encode(addslashes($_POST['destination']));
                $result['destination_array'] = explode(' ', $result['destination']);

                if($destination == '')
                {
                    $this->TemplateHandler->showError('SysCP.email.error.destinationnonexist');
                    return false;
                }
                elseif(!$this->ValidationHandler->getValidator('email')->validate($destination))
                {
                    $this->TemplateHandler->showError('SysCP.email.error.destinationiswrong', $destination);
                    return false;
                }
                elseif($destination == $result['email'])
                {
                    $this->TemplateHandler->showError('SysCP.email.error.destinationalreadyexistasmail', $destination);
                    return false;
                }
                elseif(in_array($destination, $result['destination_array']))
                {
                    $this->TemplateHandler->showError('SysCP.email.error.destinationalreadyexist', $destination);
                    return false;
                }
                else
                {
                    $result['destination'].= ' '.$destination;
                    $this->DatabaseHandler->query("UPDATE `".TABLE_MAIL_VIRTUAL."` SET `destination` = '".makeCorrectDestination($result['destination'])."' WHERE `customerid`='".$this->User['customerid']."' AND `id`='".$this->ConfigHandler->get('env.id')."'");
                    $this->DatabaseHandler->query("UPDATE `".TABLE_PANEL_CUSTOMERS."` SET `email_forwarders_used` = `email_forwarders_used` + 1 WHERE `customerid`='".$this->User['customerid']."'");
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
                $this->TemplateHandler->setTemplate('SysCP/email/customer/addForwarder.tpl');
            }
        }
    }
    else
    {
        standard_error('SysCP.globallang.error.allresourcesused');
    }
}


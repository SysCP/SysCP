<?php

if($this->ConfigHandler->get('env.id') != 0)
{
    $result = $this->DatabaseHandler->query_first("SELECT `id`, `email`, `email_full`, `iscatchall`, `destination`, `customerid`, `popaccountid` FROM `".TABLE_MAIL_VIRTUAL."` WHERE `customerid`='".$this->User['customerid']."' AND `id`='".$this->ConfigHandler->get('env.id')."'");

    if(isset($result['destination'])
       && $result['destination'] != '')
    {
        if(isset($_POST['forwarderid']))
        {
            $forwarderid = intval($_POST['forwarderid']);
        }
        elseif(isset($_GET['forwarderid']))
        {
            $forwarderid = intval($_GET['forwarderid']);
        }
        else
        {
            $forwarderid = 0;
        }

        $result['destination_array'] = explode(' ', $result['destination']);

        if(isset($result['destination_array'][$forwarderid]))
        {
            $forwarder = $result['destination_array'][$forwarderid];

            if($forwarder == $result['email'])
            {
                $forwarder = $result['destination_array'][$forwarderid];
            }

            if(isset($_POST['send'])
               && $_POST['send'] == 'send')
            {
                $result['destination'] = str_replace($forwarder, '', $result['destination']);
                $this->DatabaseHandler->query("UPDATE `".TABLE_MAIL_VIRTUAL."` SET `destination` = '".makeCorrectDestination($result['destination'])."' WHERE `customerid`='".$this->User['customerid']."' AND `id`='".$this->ConfigHandler->get('env.id')."'");
                $this->DatabaseHandler->query("UPDATE `".TABLE_PANEL_CUSTOMERS."` SET `email_forwarders_used` = `email_forwarders_used` - 1 WHERE `customerid`='".$this->User['customerid']."'");
                $this->redirectTo(array(
                    'module' => 'email',
                    'action' => 'edit',
                    'id' => $this->ConfigHandler->get('env.id')
                ));
            }
            else
            {
                $this->TemplateHandler->showQuestion('SysCP.email.question.reallydelete_forwarder', array(
                    'module' => 'email',
                    'id' => $this->ConfigHandler->get('env.id'),
                    'forwarderid' => $forwarderid,
                    'action' => $this->ConfigHandler->get('env.action')
                ), $this->IdnaHandler->decode($result['email_full']).' -> '.$this->IdnaHandler->decode($forwarder));
            }
        }
    }
}


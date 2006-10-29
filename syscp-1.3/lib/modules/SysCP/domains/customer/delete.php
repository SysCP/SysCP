<?php
$result = $this->DatabaseHandler->query_first("SELECT `id`, `customerid`, `domain`, `documentroot`, `isemaildomain`, `parentdomainid` FROM `".TABLE_PANEL_DOMAINS."` WHERE `customerid`='".$this->User['customerid']."' AND `id`='".$this->ConfigHandler->get('env.id')."'");
$alias_check = $this->DatabaseHandler->query_first('SELECT COUNT(`id`) AS `count` FROM `'.TABLE_PANEL_DOMAINS.'` WHERE `aliasdomain`=\''.$this->ConfigHandler->get('env.id').'\'');

if(isset($result['parentdomainid'])
   && $result['parentdomainid'] != '0'
   && $alias_check['count'] == 0)
{
    if(isset($_POST['send'])
       && $_POST['send'] == 'send')
    {
        if($result['isemaildomain'] == '1')
        {
            $emails = $this->DatabaseHandler->query_first('SELECT COUNT(`id`) AS `count` FROM `'.TABLE_MAIL_VIRTUAL.'` WHERE `customerid`=\''.$this->User['customerid'].'\' AND `domainid`=\''.$this->ConfigHandler->get('env.id').'\'');

            if($emails['count'] != '0')
            {
                $this->TemplateHandler->showError('SysCP.domains.error.cantdeletedomainwithemail');
                return false;
            }
        }

        $result = $this->DatabaseHandler->query("DELETE FROM `".TABLE_PANEL_DOMAINS."` WHERE `customerid`='".$this->User['customerid']."' AND `id`='".$this->ConfigHandler->get('env.id')."'");
        $result = $this->DatabaseHandler->query("UPDATE `".TABLE_PANEL_CUSTOMERS."` SET `subdomains_used`=`subdomains_used`-1 WHERE `customerid`='".$this->User['customerid']."'");
        $this->HookHandler->call('OnDeleteDomain', array(
            'id' => $this->ConfigHandler->get('env.id')
        ));
        $this->redirectTo(array(
            'module' => 'domains',
            'action' => 'list'
        ));
    }
    else
    {
        $this->TemplateHandler->showQuestion('SysCP.domains.question.reallydelete', array(
            'module' => 'domains',
            'id' => $this->ConfigHandler->get('env.id'),
            'action' => $this->ConfigHandler->get('env.action')
        ), $this->IdnaHandler->decode($result['domain']));
        return true;
    }
}
else
{
    $this->TemplateHandler->showError('SysCP.domains.error.cantdeletemaindomain');
    return false;
}


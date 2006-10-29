<?php

if($this->ConfigHandler->get('env.id') != 0)
{
    $result = $this->DatabaseHandler->queryFirst("SELECT `id`, `ip`, `port` FROM `".TABLE_PANEL_IPSANDPORTS."` WHERE `id`='".$this->ConfigHandler->get('env.id')."'");

    if($result['ip'] != '')
    {
        if(isset($_POST['send'])
           && $_POST['send'] == 'send')
        {
            $ip = addslashes($_POST['ip']);
            $port = intval($_POST['port']);
            $result2 = $this->DatabaseHandler->queryFirst("SELECT `id` FROM `".TABLE_PANEL_IPSANDPORTS."` WHERE `ip`='$ip' AND `port`='$port'");
            $result3 = $this->DatabaseHandler->queryFirst("SELECT `id` FROM `".TABLE_PANEL_IPSANDPORTS."` WHERE `ip`='".$result['ip']."' AND `id`!='".$this->ConfigHandler->get('env.id')."'");

            if($ip == '')
            {
                $this->TemplateHandler->showError(
                    'SysCP.globallang.error.stringisempty',
                    'myipaddress'
                );
                return false;
            }
            elseif($port == '')
            {
                $this->TemplateHandler->showError(
                    'SysCP.globallang.error.stringisempty',
                    'myport'
                );
                return false;
            }
            elseif($result['ip'] != $ip
                   && $result['ip'] == $this->ConfigHandler->get('system.ipaddress')
                   && $result3['id'] == '')
            {
                $this->TemplateHandler->showError('SysCP.ipsandports.error.change_systemip');
                return false;
            }
            elseif($result2['id'] != ''
                   && $result2['id'] != $this->ConfigHandler->get('env.id'))
            {
                $this->TemplateHandler->showError('SysCP.ipsandports.error.ip_duplicate');
                return false;
            }
            else
            {
                $this->DatabaseHandler->query("UPDATE `".TABLE_PANEL_IPSANDPORTS."` SET `ip`='$ip', `port`='$port' WHERE `id`='".$this->ConfigHandler->get('env.id')."'");
                $this->HookHandler->call('OnUpdateIPPort', array(
                    'id' => $this->ConfigHandler->get('env.id')
                ));
                $this->redirectTo(array(
                    'module' => 'ipsandports',
                    'action' => 'list'
                ));
            }
        }
        else
        {
            $this->TemplateHandler->set('result', $result);
            $this->TemplateHandler->setTemplate('SysCP/ipsandports/admin/edit.tpl');
        }
    }
}
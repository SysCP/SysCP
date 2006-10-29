<?php

if($this->ConfigHandler->get('env.id') != 0)
{
    $result = $this->DatabaseHandler->queryFirst("SELECT `id` FROM `".TABLE_PANEL_DOMAINS."` WHERE `ipandport`='".$this->ConfigHandler->get('env.id')."'");

    if($result['id'] == '')
    {
        $result = $this->DatabaseHandler->queryFirst("SELECT `default` FROM `".TABLE_PANEL_IPSANDPORTS."` WHERE `id`='".$this->ConfigHandler->get('env.id')."'");

        if($result['default'] == '0')
        {
            $result = $this->DatabaseHandler->queryFirst("SELECT `ip` FROM `".TABLE_PANEL_IPSANDPORTS."` WHERE `id`='".$this->ConfigHandler->get('env.id')."'");
            $result2 = $this->DatabaseHandler->queryFirst("SELECT `id` FROM `".TABLE_PANEL_IPSANDPORTS."` WHERE `ip`='".$result['ip']."' AND `id`!='".$this->ConfigHandler->get('env.id')."'");

            if(($result['ip'] != $this->ConfigHandler->get('system.ipaddress'))
               || ($result['ip'] == $this->ConfigHandler->get('system.ipaddress') && $result2['id'] != ''))
            {
                $result = $this->DatabaseHandler->queryFirst("SELECT `ip`, `port` FROM `".TABLE_PANEL_IPSANDPORTS."` WHERE `id`='".$this->ConfigHandler->get('env.id')."'");

                if($result['ip'] != '')
                {
                    if(isset($_POST['send'])
                       && $_POST['send'] == 'send')
                    {
                        $this->DatabaseHandler->query("DELETE FROM `".TABLE_PANEL_IPSANDPORTS."` WHERE `id`='".$this->ConfigHandler->get('env.id')."'");
                        $this->HookHandler->call('OnDeleteIPPort', array(
                            'id' => $this->ConfigHandler->get('env.id')
                        ));
                        $this->redirectTo(array(
                            'module' => 'ipsandports',
                            'action' => 'list'
                        ));
                    }
                    else
                    {
                        $this->TemplateHandler->showQuestion('SysCP.ipsandports.question.delete', array(
                            'module' => 'ipsandports',
                            'id' => $this->ConfigHandler->get('env.id'),
                            'action' => 'delete'
                        ), $result['ip'].':'.$result['port']);
                    }
                }
            }
            else
            {
                $this->TemplateHandler->showError('SysCP.ipsandports.error.delete_systemip');
                return false;
            }
        }
        else
        {
            $this->TemplateHandler->showError('SysCP.ipsandports.error.delete_defaultip');
            return false;;
        }
    }
    else
    {
        $this->TemplateHandler->showError('SysCP.ipsandports.error.ip_has_domains');
        return false;
    }
}
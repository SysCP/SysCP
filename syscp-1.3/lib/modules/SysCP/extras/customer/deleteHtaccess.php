<?php

if($this->ConfigHandler->get('env.id') != 0)
{
    $result = $this->DatabaseHandler->query_first("SELECT * FROM `".TABLE_PANEL_HTACCESS."` WHERE `customerid`='".$this->User['customerid']."' AND `id`='".$this->ConfigHandler->get('env.id')."'");

    if(isset($result['customerid'])
       && $result['customerid'] != ''
       && $result['customerid'] == $this->User['customerid'])
    {
        if(isset($_POST['send'])
           && $_POST['send'] == 'send')
        {
            $this->DatabaseHandler->query("DELETE FROM `".TABLE_PANEL_HTACCESS."` WHERE `customerid`='".$this->User['customerid']."' AND `id`='".$this->ConfigHandler->get('env.id')."'");
            $this->HookHandler->call('OnDeleteHTAccess', array(
                'id' => $this->ConfigHandler->get('env.id'),
                'path' => $result['path']
            ));
            $this->redirectTo(array(
                'module' => 'extras',
                'action' => 'listHtaccess'
            ));
        }
        else
        {
            $this->TemplateHandler->showQuestion('SysCP.extras.question.reallydelete_pathoptions', array(
                'module' => 'extras',
                'id' => $this->ConfigHandler->get('env.id'),
                'action' => $this->ConfigHandler->get('env.action')
            ), str_replace($this->User['homedir'], '', $result['path']));
        }
    }
}


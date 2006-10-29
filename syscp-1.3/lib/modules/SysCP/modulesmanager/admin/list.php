<?php

if($this->User['change_serversettings'] == '1')
{
    $modules = $this->moduleConfig;
    ksort($modules);
    foreach($modules as $key => $value)
    {
        ksort($modules[$key]);
    }

    $this->TemplateHandler->set('modules', $modules);
    $this->TemplateHandler->setTemplate('SysCP/modulesmanager/admin/list.tpl');
}

?>

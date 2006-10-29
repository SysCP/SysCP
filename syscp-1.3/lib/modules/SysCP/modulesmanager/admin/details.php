<?php

if($this->User['change_serversettings'] == '1')
{
    if(isset($_GET['modulename'])
       && $_GET['modulename'] != ''
       && isset($_GET['vendorname'])
       && $_GET['vendorname'] != '')
    {
        $vendor = $_GET['vendorname'];
        $module = $_GET['modulename'];
        $this->TemplateHandler->set('module', $this->moduleConfig[$vendor][$module]);
        $this->TemplateHandler->setTemplate('SysCP/modulesmanager/admin/details.tpl');
    }
}

?>

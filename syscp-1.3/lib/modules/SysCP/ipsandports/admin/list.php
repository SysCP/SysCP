<?php

if(isset($_POST['send'])
   && $_POST['send'] == 'send')
{
    $defaultid = intval($_POST['defaultipandport']);

    if($defaultid == '')
    {
        $this->TemplateHandler->showError('SysCP.ipsandports.error.select_defaultip');
        return false;
    }
    else
    {
        $this->DatabaseHandler->query("UPDATE `".TABLE_PANEL_IPSANDPORTS."` SET `default` = '0' WHERE `default` = '1'");
        $this->DatabaseHandler->query("UPDATE `".TABLE_PANEL_IPSANDPORTS."` SET `default` = '1' WHERE `id`='$defaultid'");
        $this->redirectTo(array(
            'module' => 'ipsandports',
            'action' => 'list'
        ));
    }
}
else
{
    $ipsandports = array();
    $ipsandports_default = '';
    $ipsandports_default_id = '';
    $result = $this->DatabaseHandler->query("SELECT `id`, `ip`, `port`, `default` FROM `".TABLE_PANEL_IPSANDPORTS."` ORDER BY `ip` ASC");

    while($row = $this->DatabaseHandler->fetch_array($result))
    {
        if($row['default'] == '1')
        {
            $ipsandports_default_id = $row['id'];
        }

        $ipsandports[] = $row;
        $ipsandports_default[$row['id']] = $row['ip'].':'.$row['port'];
    }

    $this->TemplateHandler->set('ipsandports', $ipsandports);
    $this->TemplateHandler->set('ipsandports_default', $ipsandports_default);
    $this->TemplateHandler->set('ipsandports_default_id', $ipsandports_default_id);
    $this->TemplateHandler->setTemplate('SysCP/ipsandports/admin/list.tpl');
}
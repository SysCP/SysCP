<?php
$result = $this->DatabaseHandler->query("SELECT `id`, `databasename`, `description` FROM `".TABLE_PANEL_DATABASES."` WHERE `customerid`='".$this->User['customerid']."' ORDER BY `databasename` ASC");
$mysqls = array();

while($row = $this->DatabaseHandler->fetch_array($result))
{
    $mysqls[] = $row;
}

$mysqls_count = $this->DatabaseHandler->num_rows($result);
$this->TemplateHandler->set('mysqlList', $mysqls);
$this->TemplateHandler->setTemplate('SysCP/mysql/customer/list.tpl');

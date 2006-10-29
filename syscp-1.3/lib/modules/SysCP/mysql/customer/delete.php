<?php

if($this->ConfigHandler->get('env.id') != 0)
{
    $result = $this->DatabaseHandler->query_first('SELECT `id`, `databasename` FROM `'.TABLE_PANEL_DATABASES.'` WHERE `customerid`="'.$this->User['customerid'].'" AND `id`="'.$this->ConfigHandler->get('env.id').'"');

    if(isset($result['databasename'])
       && $result['databasename'] != '')
    {
        if(isset($_POST['send'])
           && $_POST['send'] == 'send')
        {
            // Begin root-session

            $dsn = sprintf('mysql://%s:%s@%s', $this->ConfigHandler->get('sql.root.user'), $this->ConfigHandler->get('sql.root.password'), $this->ConfigHandler->get('sql.host'));
            $db_root = new Syscp_Handler_Database();
            $db_root->initialize(array(
                'dsn' => $dsn
            ));
            $db_root->query('REVOKE ALL PRIVILEGES ON * . * FROM `'.$result['databasename'].'`@'.$this->ConfigHandler->get('system.mysql_access_host').';');
            $db_root->query('REVOKE ALL PRIVILEGES ON `'.str_replace('_', '\_', $result['databasename']).'` . * FROM `'.$result['databasename'].'`@'.$this->ConfigHandler->get('system.mysql_access_host').';');
            $db_root->query('DELETE FROM `mysql`.`user` WHERE `User` = "'.$result['databasename'].'" AND `Host` = "'.$this->ConfigHandler->get('system.mysql_access_host').'"');
            $db_root->query('DROP DATABASE IF EXISTS `'.$result['databasename'].'`');
            $db_root->query('FLUSH PRIVILEGES');
            $db_root->close();

            // End root-session

            $this->DatabaseHandler->query('DELETE FROM `'.TABLE_PANEL_DATABASES.'` WHERE `customerid`="'.$this->User['customerid'].'" AND `id`="'.$this->ConfigHandler->get('env.id').'"');

            if($this->User['mysqls_used'] == '1')
            {
                $resetaccnumber = " , `mysql_lastaccountnumber`='0' ";
            }
            else
            {
                $resetaccnumber = '';
            }

            $result = $this->DatabaseHandler->query('UPDATE `'.TABLE_PANEL_CUSTOMERS.'` SET `mysqls_used`=`mysqls_used`-1 '.$resetaccnumber.'WHERE `customerid`="'.$this->User['customerid'].'"');
            $this->redirectTo(array(
                'module' => 'mysql',
                'action' => 'list'
            ));
        }
        else
        {
            $this->TemplateHandler->showQuestion('SysCP.mysql.question.delete_db', array(
                'module' => 'mysql',
                'id' => $this->ConfigHandler->get('env.id'),
                'action' => $this->ConfigHandler->get('env.action')
            ), $result['databasename']);
        }
    }
}
<?php

if($this->ConfigHandler->get('env.id') != 0)
{
    $result = $this->DatabaseHandler->queryFirst('SELECT `id`, `databasename`, `description` FROM `'.TABLE_PANEL_DATABASES.'` WHERE `customerid`="'.$this->User['customerid'].'" AND `id`="'.$this->ConfigHandler->get('env.id').'"');

    if(isset($result['databasename'])
       && $result['databasename'] != '')
    {
        if(isset($_POST['send'])
           && $_POST['send'] == 'send')
        {
            // Only change Password if it is set, do nothing if it is empty! -- PH 2004-11-29

            $password = addslashes($_POST['password']);

            if($password != '')
            {
                // Begin root-session

                $dsn = sprintf('mysql://%s:%s@%s', $this->ConfigHandler->get('sql.root.user'), $this->ConfigHandler->get('sql.root.password'), $this->ConfigHandler->get('sql.host'));
                $db_root = new Syscp_Handler_Database();
                $db_root->initialize(array(
                    'dsn' => $dsn
                ));
                $db_root->query('SET PASSWORD FOR `'.$result['databasename'].'`@'.$this->ConfigHandler->get('system.mysql_access_host').' = PASSWORD(\''.$password.'\')');
                $db_root->query('FLUSH PRIVILEGES');
                $db_root->close();

                // End root-session
            }

            // Update the Database description -- PH 2004-11-29

            $databasedescription = addslashes($_POST['description']);
            $result = $this->DatabaseHandler->query('UPDATE `'.TABLE_PANEL_DATABASES.'` SET `description`="'.$databasedescription.'" WHERE `customerid`="'.$this->User['customerid'].'" AND `id`="'.$this->ConfigHandler->get('env.id').'"');
            $this->redirectTo(array(
                'module' => 'mysql',
                'action' => 'list'
            ));
        }
        else
        {
            $this->TemplateHandler->set('result', $result);
            $this->TemplateHandler->setTemplate('SysCP/mysql/customer/edit.tpl');
        }
    }
}
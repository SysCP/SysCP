<?php

if($this->User['mysqls_used'] < $this->User['mysqls']
   || $this->User['mysqls'] == '-1')
{
    if(isset($_POST['send'])
       && $_POST['send'] == 'send')
    {
        $password = addslashes($_POST['password']);

        if($password == '')
        {
            $this->TemplateHandler->showError(
                'SysCP.globallang.error.stringisempty',
                'mypassword'
            );
            return false;
        }
        else
        {
            $username = $this->User['loginname'].$this->ConfigHandler->get('customer.mysqlprefix').(intval($this->User['mysql_lastaccountnumber'])+1);

            // Begin root-session

            $dsn = sprintf('mysql://%s:%s@%s', $this->ConfigHandler->get('sql.root.user'), $this->ConfigHandler->get('sql.root.password'), $this->ConfigHandler->get('sql.host'));
            $db_root = new Syscp_Handler_Database();
            $db_root->initialize(array(
                'dsn' => $dsn
            ));
            $db_root->query('CREATE DATABASE `'.$username.'`');
            $db_root->query('GRANT ALL PRIVILEGES ON `'.str_replace('_', '\_', $username).'`.* TO `'.$username.'`@'.$this->ConfigHandler->get('system.mysql_access_host').' IDENTIFIED BY \'password\'');
            $db_root->query('SET PASSWORD FOR `'.$username.'`@'.$this->ConfigHandler->get('system.mysql_access_host').' = PASSWORD(\''.$password.'\')');
            $db_root->query('FLUSH PRIVILEGES');
            $db_root->close();

            // End root-session
            // Statement modifyed for Database description -- PH 2004-11-29

            $databasedescription = addslashes($_POST['description']);
            $result = $this->DatabaseHandler->query('INSERT INTO `'.TABLE_PANEL_DATABASES.'` (`customerid`, `databasename`, `description`) VALUES ("'.$this->User['customerid'].'", "'.$username.'", "'.$databasedescription.'")');
            $result = $this->DatabaseHandler->query('UPDATE `'.TABLE_PANEL_CUSTOMERS.'` SET `mysqls_used`=`mysqls_used`+1, `mysql_lastaccountnumber`=`mysql_lastaccountnumber`+1 WHERE `customerid`="'.$this->User['customerid'].'"');
            $this->redirectTo(array(
                'module' => 'mysql',
                'action' => 'list'
            ));
        }
    }
    else
    {
        $this->TemplateHandler->setTemplate('SysCP/mysql/customer/add.tpl');
    }
}
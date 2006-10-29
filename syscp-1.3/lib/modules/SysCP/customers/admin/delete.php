<?php

if($this->User['customers'] != '0')
{
    if($this->ConfigHandler->get('env.id') != 0)
    {
        $result = $this->DatabaseHandler->queryFirst("SELECT * FROM `".TABLE_PANEL_CUSTOMERS."` WHERE `customerid`='".$this->ConfigHandler->get('env.id')."' ".($this->User['customers_see_all'] ? '' : " AND `adminid` = '{$this->User['adminid']}' "));

        if($result['loginname'] != '')
        {
            if(isset($_POST['send'])
               && $_POST['send'] == 'send')
            {
                $databases = $this->DatabaseHandler->query("SELECT * FROM ".TABLE_PANEL_DATABASES." WHERE customerid='".$this->ConfigHandler->get('env.id')."'");
                $dsn = sprintf('mysql://%s:%s@%s', $this->ConfigHandler->get('sql.root.user'), $this->ConfigHandler->get('sql.root.password'), $this->ConfigHandler->get('sql.host'));
                $db_root = new Syscp_Handler_Database();
                $db_root->initialize(array(
                    'dsn' => $dsn
                ));

                //unset($db_root->password);

                while($row_database = $this->DatabaseHandler->fetch_array($databases))
                {
                    $db_root->query('REVOKE ALL PRIVILEGES ON * . * FROM `'.$row_database['databasename'].'`@'.$this->ConfigHandler->get('system.mysql_access_host').';');
                    $db_root->query('REVOKE ALL PRIVILEGES ON `'.str_replace('_', '\_', $row_database['databasename']).'` . * FROM `'.$row_database['databasename'].'`@'.$this->ConfigHandler->get('system.mysql_access_host').';');
                    $db_root->query('DELETE FROM `mysql`.`user` WHERE `User` = "'.$row_database['databasename'].'" AND `Host` = "'.$this->ConfigHandler->get('system.mysql_access_host').'"');
                    $db_root->query('DROP DATABASE IF EXISTS `'.$row_database['databasename'].'`');
                }

                $db_root->query('FLUSH PRIVILEGES;');
                $db_root->close();
                $this->DatabaseHandler->query("DELETE FROM `".TABLE_PANEL_CUSTOMERS."` WHERE `customerid`='".$this->ConfigHandler->get('env.id')."'");
                $this->DatabaseHandler->query("DELETE FROM `".TABLE_PANEL_DATABASES."` WHERE `customerid`='".$this->ConfigHandler->get('env.id')."'");
                $this->DatabaseHandler->query("DELETE FROM `".TABLE_PANEL_DOMAINS."` WHERE `customerid`='".$this->ConfigHandler->get('env.id')."'");
                $domains_deleted = $this->DatabaseHandler->affected_rows();
                $this->DatabaseHandler->query("DELETE FROM `".TABLE_PANEL_HTPASSWDS."` WHERE `customerid`='".$this->ConfigHandler->get('env.id')."'");

                //$this->DatabaseHandler->query("DELETE FROM `".TABLE_PANEL_SESSIONS."` WHERE `userid`='".$this->ConfigHandler->get('env.id')."' AND `adminsession` = '0'");

                $this->DatabaseHandler->query("DELETE FROM `".TABLE_PANEL_TRAFFIC."` WHERE `customerid`='".$this->ConfigHandler->get('env.id')."'");
                $this->DatabaseHandler->query("DELETE FROM `".TABLE_MAIL_USERS."` WHERE `customerid`='".$this->ConfigHandler->get('env.id')."'");
                $this->DatabaseHandler->query("DELETE FROM `".TABLE_MAIL_VIRTUAL."` WHERE `customerid`='".$this->ConfigHandler->get('env.id')."'");
                $this->DatabaseHandler->query("DELETE FROM `".TABLE_FTP_GROUPS."` WHERE `customerid`='".$this->ConfigHandler->get('env.id')."'");
                $this->DatabaseHandler->query("DELETE FROM `".TABLE_FTP_USERS."` WHERE `customerid`='".$this->ConfigHandler->get('env.id')."'");
                $admin_update_query = "UPDATE `".TABLE_PANEL_ADMINS."` SET `customers_used` = `customers_used` - 1 ";
                $admin_update_query.= ", `domains_used` = `domains_used` - 0".($domains_deleted-$result['subdomains_used']);

                if($result['mysqls'] != '-1')
                {
                    $admin_update_query.= ", `mysqls_used` = `mysqls_used` - 0".$result['mysqls'];
                }

                if($result['emails'] != '-1')
                {
                    $admin_update_query.= ", `emails_used` = `emails_used` - 0".$result['emails'];
                }

                if($result['email_accounts'] != '-1')
                {
                    $admin_update_query.= ", `email_accounts_used` = `email_accounts_used` - 0".$result['email_accounts'];
                }

                if($result['email_forwarders'] != '-1')
                {
                    $admin_update_query.= ", `email_forwarders_used` = `email_forwarders_used` - 0".$result['email_forwarders'];
                }

                if($result['subdomains'] != '-1')
                {
                    $admin_update_query.= ", `subdomains_used` = `subdomains_used` - 0".$result['subdomains'];
                }

                if($result['ftps'] != '-1')
                {
                    $admin_update_query.= ", `ftps_used` = `ftps_used` - 0".$result['ftps'];
                }

                if(($result['diskspace']/1024) != '-1')
                {
                    $admin_update_query.= ", `diskspace_used` = `diskspace_used` - 0".$result['diskspace'];
                }

                $admin_update_query.= " WHERE `adminid` = '{$result['adminid']}'";
                $this->DatabaseHandler->query($admin_update_query);
                $this->HookHandler->call('OnDeleteCustomer', array(
                    'id' => $this->ConfigHandler->get('env.id')
                ));
                $this->redirectTo(array(
                    'module' => 'customers',
                    'action' => 'list'
                ));
            }
            else
            {
                $this->TemplateHandler->showQuestion('SysCP.customers.question.reallydelete', array(
                    'module' => 'customers',
                    'id' => $this->ConfigHandler->get('env.id'),
                    'action' => $this->ConfigHandler->get('env.action')
                ), $result['loginname']);
            }
        }
    }
}


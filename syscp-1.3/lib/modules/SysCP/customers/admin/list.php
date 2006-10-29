<?php

if($this->User['customers'] != '0')
{
    if(isset($_GET['sortby']))
    {
        $sortby = addslashes($_GET['sortby']);
    }
    else
    {
        $sortby = 'loginname';
    }

    if(isset($_GET['sortorder'])
       && strtolower($_GET['sortorder']) == 'desc')
    {
        $sortorder = 'DESC';
    }
    else
    {
        $sortorder = 'ASC';
    }

    $customers = '';
    $result = $this->DatabaseHandler->query("SELECT `c`.`customerid`, `c`.`loginname`, `c`.`name`, `c`.`firstname`, `c`.`diskspace`, `c`.`diskspace_used`, `c`.`traffic`, `c`.`traffic_used`, `c`.`mysqls`, `c`.`mysqls_used`, `c`.`emails`, `c`.`emails_used`, `c`.`email_accounts`, `c`.`email_accounts_used`, `c`.`deactivated`, `c`.`ftps`, `c`.`ftps_used`, `c`.`subdomains`, `c`.`subdomains_used`, `c`.`email_forwarders`, `c`.`email_forwarders_used`, `c`.`standardsubdomain`, `a`.`loginname` AS `adminname` "."FROM `".TABLE_PANEL_CUSTOMERS."` `c`, `".TABLE_PANEL_ADMINS."` `a` "."WHERE ".($this->User['customers_see_all'] ? '' : " `c`.`adminid` = '{$this->User['adminid']}' AND ")."`c`.`adminid`=`a`.`adminid` "."ORDER BY `c`.`$sortby` $sortorder");
    $rows = $this->DatabaseHandler->num_rows($result);

    if($this->ConfigHandler->get('panel.paging') > 0)
    {
        $pages = $rows/$this->ConfigHandler->get('panel.paging');
        $pages = (int)$pages;
    }
    else
    {
        $pages = 0;
    }

    if($pages != 0)
    {
        if(isset($_GET['no']))
        {
            $pageno = (int)$_GET['no'];
        }
        else
        {
            $pageno = 1;
        }

        if($pageno > $pages)
        {
            $pageno = $pages+1;
        }
        elseif($pageno < 1)
        {
            $pageno = 1;
        }

        $pagestart = ($pageno-1)*$this->ConfigHandler->get('panel.paging');
        $result = $this->DatabaseHandler->query("SELECT `c`.`customerid`, `c`.`loginname`, `c`.`name`, `c`.`firstname`, `c`.`diskspace`, `c`.`diskspace_used`, `c`.`traffic`, `c`.`traffic_used`, `c`.`mysqls`, `c`.`mysqls_used`, `c`.`emails`, `c`.`emails_used`, `c`.`email_accounts`, `c`.`email_accounts_used`, `c`.`deactivated`, `c`.`ftps`, `c`.`ftps_used`, `c`.`subdomains`, `c`.`subdomains_used`, `c`.`email_forwarders`, `c`.`email_forwarders_used`, `c`.`standardsubdomain`, `a`.`loginname` AS `adminname` "."FROM `".TABLE_PANEL_CUSTOMERS."` `c`, `".TABLE_PANEL_ADMINS."` `a` "."WHERE ".($this->User['customers_see_all'] ? '' : " `c`.`adminid` = '{$this->User['adminid']}' AND ")."`c`.`adminid`=`a`.`adminid` "."ORDER BY `c`.`$sortby` $sortorder "."LIMIT $pagestart , ".$this->ConfigHandler->get('panel.paging').";");
        $paging = '';
        for ($count = 1;$count <= $pages+1;$count++)
        {
            if($count == $pageno)
            {
                $paging.= '<a href="'.$this->createLink(array(
                    'page' => $this->ConfigHandler->get('env.page'),
                    'no' => $count
                )).'"><b>'.$count.'</b></a>&nbsp;';
            }
            else
            {
                $paging.= '<a href="'.$this->createLink(array(
                    'page' => $this->ConfigHandler->get('env.page'),
                    'no' => $count
                )).'">'.$count.'</a>&nbsp;';
            }
        }
    }
    else
    {
        $paging = "";
    }

    $customerList = array();

    while($row = $this->DatabaseHandler->fetchArray($result))
    {
        $domains = $this->DatabaseHandler->queryFirst("SELECT COUNT(`id`) AS `domains` "."FROM `".TABLE_PANEL_DOMAINS."` "."WHERE `customerid`='".$row['customerid']."' AND `parentdomainid`='0' AND `id` <> '".$row['standardsubdomain']."' ");
        $row['domains'] = $domains['domains'];
        $row['traffic_used'] = round($row['traffic_used']/(1024*1024*1024), 4);
        $row['traffic'] = round($row['traffic']/(1024*1024), 4);
        $row['diskspace_used'] = round($row['diskspace_used']/(1024*1024), 2);
        $row['diskspace'] = round($row['diskspace']/1024, 2);
        $row['deactivated'] = str_replace('0', $this->L10nHandler->get('SysCP.globallang.yes'), $row['deactivated']);
        $row['deactivated'] = str_replace('1', $this->L10nHandler->get('SysCP.globallang.no'), $row['deactivated']);
        $row = str_replace_array('-1', 'UL', $row, 'diskspace traffic mysqls emails email_accounts email_forwarders ftps subdomains');
        $customerList[] = $row;
    }

    $this->TemplateHandler->set('customer_list', $customerList);
    $this->TemplateHandler->set('pages', $pages);
    $this->TemplateHandler->set('paging', $paging);
    $this->TemplateHandler->setTemplate('SysCP/customers/admin/list.tpl');
}


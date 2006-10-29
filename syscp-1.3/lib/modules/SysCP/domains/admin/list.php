<?php

if(isset($_GET['sortby']))
{
    $sortby = addslashes($_GET['sortby']);
}
else
{
    $sortby = 'domain';
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

$domains = '';
$result = $this->DatabaseHandler->query("SELECT `d`.`id`, `d`.`domain`, `d`.`customerid`, `d`.`documentroot`, CONCAT(`ip`.`ip`,':',`ip`.`port`) AS `ipandport`, `d`.`zonefile`, `d`.`openbasedir`, `d`.`safemode`, `d`.`isemaildomain`, `d`.`parentdomainid`, `c`.`loginname`, `c`.`name`, `c`.`firstname`, `ad`.`domain` AS `alias` "."FROM `".TABLE_PANEL_DOMAINS."` `d` "."LEFT JOIN `".TABLE_PANEL_CUSTOMERS."` `c` USING(`customerid`) "."LEFT JOIN `".TABLE_PANEL_DOMAINS."` `ad` ON `d`.`aliasdomain`=`ad`.`id` "."LEFT JOIN `".TABLE_PANEL_IPSANDPORTS."` `ip` ON (`d`.`ipandport` = `ip`.`id`) "."WHERE `d`.`parentdomainid`='0' ".($this->User['customers_see_all'] ? '' : " AND `d`.`adminid` = '{$this->User['adminid']}' ").""."ORDER BY `$sortby` $sortorder");
$rows = $this->DatabaseHandler->num_rows($result);

if($this->ConfigHandler->get('panel.paging') > 0)
{
    $pages = intval($rows/$this->ConfigHandler->get('panel.paging'));
}
else
{
    $pages = 0;
}

if($pages != 0)
{
    if(isset($_GET['no']))
    {
        $pageno = intval($_GET['no']);
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
    $result = $this->DatabaseHandler->query("SELECT `d`.`id`, `d`.`domain`, `d`.`customerid`, `d`.`documentroot`, CONCAT(`ip`.`ip`,':',`ip`.`port`) AS `ipandport`, `d`.`zonefile`, `d`.`openbasedir`, `d`.`safemode`, `d`.`isemaildomain`, `d`.`parentdomainid`, `c`.`loginname`, `c`.`name`, `c`.`firstname`, `ad`.`domain` AS `alias` "."FROM `".TABLE_PANEL_DOMAINS."` `d` "."LEFT JOIN `".TABLE_PANEL_CUSTOMERS."` `c` USING(`customerid`) "."LEFT JOIN `".TABLE_PANEL_DOMAINS."` `ad` ON `d`.`aliasdomain`=`ad`.`id` "."LEFT JOIN `".TABLE_PANEL_IPSANDPORTS."` `ip` ON (`d`.`ipandport` = `ip`.`id`) "."WHERE `d`.`parentdomainid`='0' ".($this->User['customers_see_all'] ? '' : " AND `d`.`adminid` = '{$this->User['adminid']}' ").""."ORDER BY `$sortby` $sortorder "."LIMIT $pagestart , ".$this->ConfigHandler->get('panel.paging').";");
    $paging = '';
    for ($count = 1;$count <= $pages+1;$count++)
    {
        if($count == $pageno)
        {
            $paging.= "<a href=\"".$this->ConfigHandler->get('env.filename')."?s=".$this->ConfigHandler->get('env.s')."&page=".$this->ConfigHandler->get('env.page')."&no=$count\"><b>$count</b></a>&nbsp;";
        }
        else
        {
            $paging.= "<a href=\"".$this->ConfigHandler->get('env.filename')."?s=".$this->ConfigHandler->get('env.s')."&page=".$this->ConfigHandler->get('env.page')."&no=$count\">$count</a>&nbsp;";
        }
    }
}
else
{
    $paging = "";
}

$domain_array = array();

while($row = $this->DatabaseHandler->fetch_array($result))
{
    $row['domain'] = $this->IdnaHandler->decode($row['domain']);
    $domain_array[$row['domain']] = $row;
}

//			ksort($domain_array);

$domainList = array();
foreach($domain_array as $row)
{
    $row['standardsubdomain'] = false;

    // --- martin @ 03.08.2005 ---------------------------------------------------------
    // changed query variable not to collide with an elemental config variable

    $query = 'SELECT `customerid` '.'FROM   `'.TABLE_PANEL_CUSTOMERS.'` '.'WHERE  `standardsubdomain` = \''.$row['id'].'\'';
    $result = $this->DatabaseHandler->queryFirst($query);

    // ---------------------------------------------------------------------------------

    if(isset($result['customerid']))
    {
        $row['standardsubdomain'] = true;
    }

    $row['aliasdomain'] = false;
    $result = $this->DatabaseHandler->queryFirst('SELECT COUNT(`id`) AS `count` FROM `'.TABLE_PANEL_DOMAINS.'` WHERE `aliasdomain`=\''.$row['id'].'\'');

    if($result['count'] > 0)
    {
        $row['aliasdomain'] = true;
    }

    $domainList[] = $row;

    //eval("\$domains.=\"".getTemplate("domains/domains_domain")."\";");
}

//eval("echo \"".getTemplate("domains/domains")."\";");

$this->TemplateHandler->set('domain_list', $domainList);
$this->TemplateHandler->setTemplate('SysCP/domains/admin/list.tpl');

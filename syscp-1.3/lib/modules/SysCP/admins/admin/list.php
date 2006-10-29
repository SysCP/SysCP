<?php

/**
 * This file is part of the SysCP project.
 * Copyright (c) 2003-2006 the SysCP Project.
 *
 * For the full copyright and license information, please view the COPYING
 * file that was distributed with this source code. You can also view the
 * COPYING file online at http://files.syscp.org/misc/COPYING.txt
 *
 * @author     Florian Lippert <flo@redenswert.de>
 * @copyright  (c) 2003-2006 Florian Lippert
 * @package    Syscp.Modules
 * @subpackage Index
 * @license    GPLv2 http://files.syscp.org/misc/COPYING.txt
 * @version    $Id:admin_index.php 460 2006-04-23 15:07:49 +0200 (So, 23 Apr 2006) martin $
 */

if($this->User['change_serversettings'] == '1')
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

    $admins = '';
    $result = $this->DatabaseHandler->query("SELECT * FROM `".TABLE_PANEL_ADMINS."` ORDER BY `$sortby` $sortorder");
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
        $result = $this->DatabaseHandler->query("SELECT * FROM `".TABLE_PANEL_ADMINS."` ORDER BY `$sortby` $sortorder "."LIMIT $pagestart , ".$this->ConfigHandler->get('panel.paging').";");
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

    $adminList = array();

    while($row = $this->DatabaseHandler->fetch_array($result))
    {
        $row['traffic_used'] = round($row['traffic_used']/(1024*1024), 4);
        $row['traffic'] = round($row['traffic']/(1024*1024), 4);
        $row['diskspace_used'] = round($row['diskspace_used']/1024, 2);
        $row['diskspace'] = round($row['diskspace']/1024, 2);
        $row['deactivated'] = str_replace('0', $this->L10nHandler->get('SysCP.globallang.yes'), $row['deactivated']);
        $row['deactivated'] = str_replace('1', $this->L10nHandler->get('SysCP.globallang.no'), $row['deactivated']);
        $row = str_replace_array('-1', $this->L10nHandler->get('SysCP.globallang.unlimited'), $row, 'customers domains diskspace traffic mysqls emails email_accounts email_forwarders ftps subdomains');
        $adminList[] = $row;

        //				eval("\$admins.=\"".getTemplate("admins/admins_admin")."\";");
    }

    //eval("echo \"".getTemplate("admins/admins")."\";");

    $this->TemplateHandler->set('admin_list', $adminList);
    $this->TemplateHandler->setTemplate('SysCP/admins/admin/list.tpl');
}


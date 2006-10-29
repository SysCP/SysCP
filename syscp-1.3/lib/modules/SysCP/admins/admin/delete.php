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
    if($this->ConfigHandler->get('env.id') == '1')
    {
        $this->TemplateHandler->showError('SysCP.admins.error.youcantdeletechangemainadmin');
        return false;
    }

    $result = $this->DatabaseHandler->queryFirst("SELECT * FROM `".TABLE_PANEL_ADMINS."` WHERE `adminid`='".(int)$this->ConfigHandler->get('env.id')."'");

    if($result['loginname'] != '')
    {
        if(isset($_POST['send'])
           && $_POST['send'] == 'send')
        {
            $this->DatabaseHandler->query("DELETE FROM `".TABLE_PANEL_ADMINS."` WHERE `adminid`='".(int)$this->ConfigHandler->get('env.id')."'");
            $this->DatabaseHandler->query("DELETE FROM `".TABLE_PANEL_TRAFFIC_ADMINS."` WHERE `adminid`='".(int)$this->ConfigHandler->get('env.id')."'");
            $this->DatabaseHandler->query("UPDATE `".TABLE_PANEL_CUSTOMERS."` SET `adminid` = '1' WHERE `adminid` = '".(int)$this->ConfigHandler->get('env.id')."'");
            $this->DatabaseHandler->query("UPDATE `".TABLE_PANEL_DOMAINS."` SET `adminid` = '1' WHERE `adminid` = '".(int)$this->ConfigHandler->get('env.id')."'");

            /**
             * @todo Find a way to reimplement this
             */

            // updateCounters () ;

            $this->redirectTo(array(
                'module' => 'admins',
                'action' => 'list'
            ));
        }
        else
        {
            $this->TemplateHandler->showQuestion('SysCP.admins.question.reallydelete', array(
                'module' => 'admins',
                'id' => $this->ConfigHandler->get('env.id'),
                'action' => $this->ConfigHandler->get('env.action')
            ), $result['loginname']);
        }
    }
}


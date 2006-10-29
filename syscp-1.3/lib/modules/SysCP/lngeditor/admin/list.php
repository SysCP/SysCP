<?php

/**
 * This file is part of the SysCP project.
 * Copyright (c) 2003-2006 the SysCP Project.
 *
 * For the full copyright and license information, please view the COPYING
 * file that was distributed with this source code. You can also view the
 * COPYING file online at http://files.syscp.org/misc/COPYING.txt
 *
 * @author     Florian Aders <eleras@syscp.org>
 * @copyright  (c) 2003-2006 Florian Lippert
 * @package    Syscp.Modules
 * @subpackage Panel.Admin
 * @license    GPLv2 http://files.syscp.org/misc/COPYING.txt
 * @version    $Id$
 *
 */

if($this->User['change_serversettings'] == '1')
{
    $modules = $this->moduleConfig;
    ksort($modules);
    foreach($modules as $key => $value)
    {
        ksort($modules[$key]);
    }

    $this->TemplateHandler->set('lang_num', count($this->L10nHandler->getLanguageList())+1);
    $this->TemplateHandler->set('languages', $this->L10nHandler->getLanguageList());
    $this->TemplateHandler->set('modules', $modules);
    $this->TemplateHandler->setTemplate('SysCP/lngeditor/admin/list.tpl');
}


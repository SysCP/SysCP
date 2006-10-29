<?php

/**
 * This file is part of the SysCP project.
 * Copyright (c) 2003-2006 the SysCP Project.
 *
 * For the full copyright and license information, please view the COPYING
 * file that was distributed with this source code. You can also view the
 * COPYING file online at http://files.syscp.org/misc/COPYING.txt
 *
 * @author     Luca Longinotti <chtekk@syscp.org>
 * @copyright  (c) 2006, the authors
 * @package    Syscp.Framework.API
 * @subpackage Syscp.Handler.Plugins
 * @license    GPLv2 http://files.syscp.org/misc/COPYING.txt
 * @version
 */

interface Syscp_Handler_Plugins_Interface extends Syscp_Handler_Interface
{
    public function getPluginsLogic($vendor = '', $module = '', $area = '', $action = '');

    public function getPluginsDisplay($vendor = '', $module = '', $area = '', $action = '');
}


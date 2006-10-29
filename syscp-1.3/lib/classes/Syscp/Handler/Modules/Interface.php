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
 * @subpackage Syscp.Handler.Modules
 * @license    GPLv2 http://files.syscp.org/misc/COPYING.txt
 * @version
 */

interface Syscp_Handler_Modules_Interface extends Syscp_Handler_Interface
{
    public function checkDeps($vendor = '', $module = '');

    public function checkRevDeps($vendor = '', $module = '');

    public function checkAllDeps($exception = 1);

    public function getFailedModEnabledChecks();

    public function getFailedModVersionChecks();

    public function clearFailedModEnabledChecks();

    public function clearFailedModVersionChecks();
}


<?php

/**
 * This file is part of the SysCP project.
 * Copyright (c) 2003-2006 the SysCP Project.
 *
 * For the full copyright and license information, please view the COPYING
 * file that was distributed with this source code. You can also view the
 * COPYING file online at http://files.syscp.org/misc/COPYING.txt
 *
 * @author     Martin Burchert <eremit@syscp.org>
 * @copyright  (c) 2006, the authors
 * @package    Syscp.Framework.API
 * @subpackage Syscp.FrontController
 * @license    GPLv2 http://files.syscp.org/misc/COPYING.txt
 * @version    $Id:admin_admins.php 460 2006-04-23 15:07:49 +0200 (So, 23 Apr 2006) martin $
 */

/**
 * Interface which implements the API requirements of Syscp_FrontController
 *
 * @todo Extended documentation here!
 *
 * @author     Martin Burchert <eremit@syscp.org>
 * @copyright  (c) 2006, The Authors
 * @package    Syscp.Framework.API
 * @subpackage Syscp.FrontController
 */

interface Syscp_FrontController_Interface
{
    /**
     * Initialisation Method
     *
     * This method will always be called to initialize a frontcontroller instance, take
     * a look at the direct implementation of this method for an overview regarding the
     * parameters the implementation needs.
     *
     * @param  array  $params  List of initialisation parameters, see the direct
     *                         implementation for more information.
     * @return void
     */

    public function initialize($params = array());

    /**
     * Dispatch Method
     *
     * Dispatches the FrontController implementation and let him
     * do his work. This method is called by another script to indicate the FrontController
     * implementation can start its work.
     *
     * @return void
     */

    public function dispatch();

    /**
     * @todo Document me!
     * @todo Compile Error: Syscp/FrontController/Interface.php line 56
     *       - Access type for interface method Syscp_FrontController_Interface::initModules()
     *         must be omitted
     *       Whatever, looks like only public methods are allowed in Interfaces. It's PHP :/
     */

    public function initModules();
}


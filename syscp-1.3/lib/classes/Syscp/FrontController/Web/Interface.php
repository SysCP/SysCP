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
 * @subpackage Syscp.FrontController.Web
 * @license    GPLv2 http://files.syscp.org/misc/COPYING.txt
 * @version    $Id:admin_admins.php 460 2006-04-23 15:07:49 +0200 (So, 23 Apr 2006) martin $
 */

/**
 * Interface which implements the API requirements of Syscp_FrontController_Web
 *
 * @todo Extended documentation here!
 *
 * @author     Martin Burchert <eremit@syscp.org>
 * @copyright  (c) 2006, The Authors
 * @package    Syscp.Framework.API
 * @subpackage Syscp.FrontController.Web
 * @see        Syscp_FrontController_Web
 */

interface Syscp_FrontController_Web_Interface extends Syscp_FrontController_Interface
{
    /**
     * Method to generate a link this FrontController_Web can handle and recognize.
     *
     * @param  array   $params  An associative array of _GET => value pairs.
     * @param  string  $host    The target host of the link, if no host is given, this must
     *                          resolve to the local host.
     * @return string  The generated url.
     */

    public function createLink($params, $host = null);

    /**
     * Method to send a header redirect call to the client.
     *
     * @param  array   $params  An associative array of _GET => value pairs.
     * @param  string  $host    The target host of the link, if no host is given, this must
     *                          resolve to the local host.
     * @return void
     */

    public function redirectTo($params, $host = null);
}


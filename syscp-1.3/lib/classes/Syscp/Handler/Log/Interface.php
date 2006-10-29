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
 * @subpackage Syscp.Handler.Log
 * @license    GPLv2 http://files.syscp.org/misc/COPYING.txt
 * @version    $Id:admin_admins.php 460 2006-04-23 15:07:49 +0200 (So, 23 Apr 2006) martin $
 */

/**
 * @todo Extended documentation here!
 *
 * @author     Martin Burchert <eremit@syscp.org>
 * @copyright  (c) 2006, The Authors
 * @package    Syscp.Framework.API
 * @subpackage Syscp.Handler.Log
 * @see        Syscp_Handler_Log
 */

interface Syscp_Handler_Log_Interface
{
    const FACILITY_AUTH = '1';
    const FACILITY_USER = '2';
    const FACILITY_SYSTEM = '4';
    const FACILITY_DEBUG = '8';

    /**
     * This function should log a critical level given message
     * to the facility given.
     *
     * @param integer  $facility  One of the facility constants defined.
     * @param string   $message   The message which should be logged.
     */

    public function critical($facility, $message);

    /**
     * This function should log a warning level given message
     * to the facility given.
     *
     * @param integer  $facility  One of the facility constants defined.
     * @param string   $message   The message which should be logged.
     */

    public function warning($facility, $message);

    /**
     * This function should log a notice level given message
     * to the facility given.
     *
     * @param integer  $facility  One of the facility constants defined.
     * @param string   $message   The message which should be logged.
     */

    public function notice($facility, $message);

    /**
     * This function should log a info level given message
     * to the facility given.
     *
     * @param integer  $facility  One of the facility constants defined.
     * @param string   $message   The message which should be logged.
     */

    public function info($facility, $message);

    /**
     * This function should log a debug level given message
     * to the facility given.
     *
     * @param integer  $facility  One of the facility constants defined.
     * @param string   $message   The message which should be logged.
     */

    public function debug($facility, $message);

    /**
     * Sets the current logged in username
     *
     * @param  string  $username  the name of the current logged in user
     */

    public function setUsername($username);
}


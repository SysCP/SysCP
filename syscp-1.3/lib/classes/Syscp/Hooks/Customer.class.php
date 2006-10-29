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
 * @author     Michael Duergner <michael@duergner.com>
 * @copyright  (c) the authors
 * @package    Syscp.Modules
 * @subpackage Customer
 * @license    GPLv2 http://files.syscp.org/misc/COPYING.txt
 * @version    $Id: class_idna_convert_wrapper.php 425 2006-03-18 09:48:20Z martin $
 */

/**
 * The customer hook implementation.
 *
 * This hook implements the customer specific functions regarding the
 * creation of customer directories.
 *
 * @package    Syscp.Modules
 * @subpackage Customer
 *
 * @author     Martin Burchert <martin.burchert@syscp.org>
 * @version    1.0
 */

class Syscp_Hooks_Customer extends Syscp_BaseHook
{
    /**
     * Filename of the file this hook is implemented in.
     * Consider this variable to be class specific constant.
     *
     * @var    string
     * @access private
     */

    var $FILE;

    // CONST later

    /**
     * Classname of this class
     * Consider this variable to be class specific constant.
     *
     * @var    string
     * @access private
     */

    var $CLASS;

    // CONST later

    /**
     * Class Constructor
     *
     * @author  Martin Burchert <eremit@syscp.org>
     *
     * @since   1.0
     * @access  public
     *
     * @return  Org_Syscp_Core_Hooks_Apache
     */

    function __construct()
    {
        $this->FILE = 'lib/classes/Syscp/Hooks/Customer.class.php';
        $this->CLASS = __CLASS__;
    }

    /**
     * core.createCustomer Hook
     *
     * This hook basically only schedules the cronCreateCustomer() function
     * call for the backend.
     *
     * @author  Martin Burchert <eremit@syscp.org>
     *
     * @since   1.0
     * @access  public
     *
     * @param   array  $params  Parameters to be used in this hook call
     *
     * @return  void
     */

    function createCustomer($params = array())
    {
        $this->_hooks->schedule($this->FILE, $this->CLASS, 'cronCreateCustomer', $params);
    }

    /**
     * This method should _ONLY_ be called from the backend cronscript.
     *
     * This method creates the directories for a new customer.
     *
     * @author  Martin Burchert <eremit@syscp.org>
     *
     * @since   1.0
     * @access  public
     *
     * @param   array  $params  Parameters to be used in this hook call
     *
     * @return  void
     *
     * @todo Reimplement this function to use templates later on.
     */

    function cronCreateCustomer($params = array())
    {
        // load the config and db vars from our attributes

        $config = $this->_config;
        $db = $this->_db;
        $log = $this->_log;

        // switching log facility of user to customerhook

        $log->setUsername('Hook_Customer');

        if(isset($params['loginname'])
           && isset($params['uid'])
           && isset($params['gid']))
        {
            $log->info(Syscp_Handler_Log_Interface::FACILITY_USER, sprintf('-- cronCreateCustomer: creating customer directories for user %s', $params['loginname']));

            // lets query the userrecord

            $loginname = (string)$params['loginname'];
            $query = 'SELECT * FROM `%s` WHERE `loginname` = \'%s\'';
            $query = sprintf($query, TABLE_PANEL_CUSTOMERS, $loginname);
            $userRow = $db->queryFirst($query);

            if(is_array($userRow))
            {
                $userHome = $userRow['homedir'];
                $mailHome = $config->get('system.vmail_homedir');
                $mailHome = str_replace('{LOGIN}', $userRow['loginname'], $mailHome);
                $mailHome = str_replace('{USERHOME}', $userRow['homedir'], $mailHome);
                $mailHome = makeCorrectDir($mailHome);
                $cmd = 'mkdir -p %s';
                $cmd = sprintf($cmd, $mailHome);
                Syscp::exec($cmd);
                Syscp::exec('cp -a '.SYSCP_PATH_BASE.'etc/user-skel/* "'.$userHome.'/"');
                Syscp::exec('chown -R '.$params['uid'].':'.$params['gid'].' "'.$userHome.'"');
                Syscp::exec('chown -R '.$config->get('system.vmail_uid').':'.$config->get('system.vmail_gid').' "'.$mailHome.'"');
            }
        }
    }
}

?>
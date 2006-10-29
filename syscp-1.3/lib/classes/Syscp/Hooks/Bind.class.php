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
 * @author     Michael Dürgner <michael@duergner.com>
 * @copyright  (c) the authors
 * @package    Syscp.Modules
 * @subpackage Bind
 * @license    GPLv2 http://files.syscp.org/misc/COPYING.txt
 * @version    $Id: class_idna_convert_wrapper.php 425 2006-03-18 09:48:20Z martin $
 */

/**
 * The bind hook implementation.
 *
 * This hook implements the bind specific functions regarding the
 * rewriting of the syscp_bind.conf file.
 *
 * @package    Syscp.Modules
 * @subpackage Bind
 *
 * @author     Martin Burchert <martin.burchert@syscp.org>
 * @version    1.0
 */

class Syscp_Hooks_Bind extends Syscp_BaseHook
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
        $this->FILE = 'lib/classes/Syscp/Hooks/Bind.class.php';
        $this->CLASS = __CLASS__;
    }

    /**
     * core.deleteCustomer Hook
     *
     * This hook basically only schedules the cronRebuildVhosts() function
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

    function deleteCustomer($params = array())
    {
        $this->_hooks->schedule($this->FILE, $this->CLASS, 'cronRebuildBindConfig', $params);
    }

    /**
     * core.createDomain Hook
     *
     * This hook basically only schedules the cronRebuildVhosts() function
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

    function createDomain($params = array())
    {
        $this->_hooks->schedule($this->FILE, $this->CLASS, 'cronRebuildBindConfig', $params);
    }

    /**
     * core.deleteDomain Hook
     *
     * This hook basically only schedules the cronRebuildVhosts() function
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

    function deleteDomain($params = array())
    {
        $this->_hooks->schedule($this->FILE, $this->CLASS, 'cronRebuildBindConfig', $params);
    }

    /**
     * core.updateDomain Hook
     *
     * This hook basically only schedules the cronRebuildVhosts() function
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

    function updateDomain($params = array())
    {
        $this->_hooks->schedule($this->FILE, $this->CLASS, 'cronRebuildBindConfig', $params);
    }

    /**
     * core.createIPPort Hook
     *
     * This hook basically only schedules the cronRebuildVhosts() function
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

    function createIPPort($params = array())
    {
        $this->_hooks->schedule($this->FILE, $this->CLASS, 'cronRebuildBindConfig', $params);
    }

    /**
     * core.updateIPPort Hook
     *
     * This hook basically only schedules the cronRebuildVhosts() function
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

    function updateIPPort($params = array())
    {
        $this->_hooks->schedule($this->FILE, $this->CLASS, 'cronRebuildBindConfig', $params);
    }

    /**
     * core.deleteIPPort Hook
     *
     * This hook basically only schedules the cronRebuildVhosts() function
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

    function deleteIPPort($params = array())
    {
        $this->_hooks->schedule($this->FILE, $this->CLASS, 'cronRebuildBindConfig', $params);
    }

    /**
     * This method should _ONLY_ be called from the backend cronscript.
     *
     * This method creates a new vhosts.conf file and stores it at the
     * places configured in $config.
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

    function cronRebuildBindConfig($params = array())
    {
        $config = $this->_config;
        $db = $this->_db;
        $log = $this->_log;
        $log->setUsername('Hook_Bind');
        $log->info(Syscp_Handler_Log_Interface::FACILITY_USER, '-- cronRebuildBindConfig: Recreating bind config file');
        $bindconf_file = '# '.$config->get('system.bindconf_directory').'syscp_bind.conf'."\n".'# Created '.date('d.m.Y H:i')."\n".'# Do NOT manually edit this file, all changes will be deleted after the next domain change at the panel.'."\n"."\n";
        $result_domains = $db->query("SELECT `d`.`id`, `d`.`domain`, `d`.`customerid`, `d`.`zonefile`, `c`.`loginname`, `c`.`guid` FROM `".TABLE_PANEL_DOMAINS."` `d` LEFT JOIN `".TABLE_PANEL_CUSTOMERS."` `c` USING(`customerid`) WHERE `d`.`isbinddomain` = '1' ORDER BY `d`.`domain` ASC");

        while($domain = $db->fetch_array($result_domains))
        {
            if($domain['zonefile'] == '')
            {
                $domain['zonefile'] = $config->get('system.binddefaultzone');
            }

            $bindconf_file.= '# Domain ID: '.$domain['id'].' - CustomerID: '.$domain['customerid'].' - CustomerLogin: '.$domain['loginname']."\n";
            $bindconf_file.= 'zone "'.$domain['domain'].'" in {'."\n";
            $bindconf_file.= '	type master;'."\n";
            $bindconf_file.= '	file "'.$config->get('system.bindconf_directory').$domain['zonefile'].'";'."\n";
            $bindconf_file.= '	allow-query { any; };'."\n";
            $bindconf_file.= '};'."\n";
            $bindconf_file.= "\n";
        }

        $bindconf_file_handler = fopen($config->get('system.bindconf_directory').'syscp_bind.conf', 'w');
        fwrite($bindconf_file_handler, $bindconf_file);
        fclose($bindconf_file_handler);
        $log->info(Syscp_Handler_Log_Interface::FACILITY_USER, '-- cronRebuildBindConfig: restarting bind...');
        Syscp::exec($config->get('system.bindreload_command'));
    }
}

?>
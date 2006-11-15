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
 * @subpackage Apache
 * @license    GPLv2 http://files.syscp.org/misc/COPYING.txt
 * @version    $Id: class_idna_convert_wrapper.php 425 2006-03-18 09:48:20Z martin $
 */

/**
 * The apache hook implementation.
 *
 * This hook implements the apache specific functions regarding the
 * rewriting of the vhosts.conf file.
 *
 * @package    Syscp.Modules
 * @subpackage Apache
 *
 * @author     Martin Burchert <martin.burchert@syscp.org>
 * @version    1.0
 */

class Syscp_Hooks_Apache extends Syscp_BaseHook
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
        $this->FILE = 'lib/classes/Syscp/Hooks/Apache.class.php';
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
        $this->_hooks->schedule($this->FILE, $this->CLASS, 'cronRebuildVhosts', $params);
    }

    /**
     * core.deactivateCustomer Hook
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

    function deactivateCustomer($params = array())
    {
        $this->_hooks->schedule($this->FILE, $this->CLASS, 'cronRebuildVhosts', $params);
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
        $this->_hooks->schedule($this->FILE, $this->CLASS, 'cronRebuildVhosts', $params);
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
        $this->_hooks->schedule($this->FILE, $this->CLASS, 'cronRebuildVhosts', $params);
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
     *
     * @todo Implement this function
     */

    function updateDomain($params = array())
    {
        // check if we need to update the vhosts
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
        $this->_hooks->schedule($this->FILE, $this->CLASS, 'cronRebuildVhosts', $params);
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
        $this->_hooks->schedule($this->FILE, $this->CLASS, 'cronRebuildVhosts', $params);
    }

    /**
     * core.createHTPasswd Hook
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

    function createHTPasswd($params = array())
    {
        $this->_hooks->schedule($this->FILE, $this->CLASS, 'cronRebuildDiroptions', $params);
    }

    /**
     * core.updateHTPasswd Hook
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

    function updateHTPasswd($params = array())
    {
        $this->_hooks->schedule($this->FILE, $this->CLASS, 'cronRebuildDiroptions', $params);
    }

    /**
     * core.deleteHTPasswd Hook
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

    function deleteHTPasswd($params = array())
    {
        $this->_hooks->schedule($this->FILE, $this->CLASS, 'cronRebuildDiroptions', $params);
    }

    /**
     * core.createHTAccess Hook
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

    function createHTAccess($params = array())
    {
        $this->_hooks->schedule($this->FILE, $this->CLASS, 'cronRebuildDiroptions', $params);
    }

    /**
     * core.updateHTAccess Hook
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

    function updateHTAccess($params = array())
    {
        $this->_hooks->schedule($this->FILE, $this->CLASS, 'cronRebuildDiroptions', $params);
    }

    /**
     * core.deleteHTAccess Hook
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

    function deleteHTAccess($params = array())
    {
        $this->_hooks->schedule($this->FILE, $this->CLASS, 'cronRebuildDiroptions', $params);
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
        $this->_hooks->schedule($this->FILE, $this->CLASS, 'cronRebuildVhosts', $params);
    }

    /**
     * This method should _ONLY_ be called from the backend cronscript.
     *
     * This method creates a new vhosts.conf file and stores it at the
     * places configured in $config.
     *
     * This method has been reimplemented 2006/06/15 (martin)
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

    public function cronRebuildVhosts($params = array())
    {
        // load the config and db vars from our attributes

        $config = $this->_config;
        $db = $this->_db;
        $log = $this->_log;

        // switching user log facility to apachehook

        $log->setUsername('Hook_Apache');
        $log->info(Syscp_Handler_Log_Interface::FACILITY_USER, '-- cronRebuildVhosts: Creating new vhosts.conf');

        // lets build a list of all ip's used

        $query = 'SELECT `%s`.`id`, CONCAT(`%s`.`ip`,\':\',`%s`.`port`) AS `ipandport` '.'FROM `%s` '.'LEFT JOIN `%s` ON (`%s`.`ipandport` = `%s`.`id`) '.'ORDER BY `%s`.`ip` ASC';
        $query = sprintf($query, TABLE_PANEL_IPSANDPORTS, TABLE_PANEL_IPSANDPORTS, TABLE_PANEL_IPSANDPORTS, TABLE_PANEL_DOMAINS, TABLE_PANEL_IPSANDPORTS, TABLE_PANEL_DOMAINS, TABLE_PANEL_IPSANDPORTS, TABLE_PANEL_IPSANDPORTS);
        $result = $db->query($query);
        $ipList = array();

        while(false !== ($row = $db->fetchArray($result)))
        {
            $ipList[$row['id']] = $row['ipandport'];
        }

        // check for diroptions

        $hasDiroptions = false;

        if(Syscp::isReadableFile($config->get('system.apacheconf_directory').'diroptions.conf'))
        {
            $hasDiroptions = true;
        }

        // load all non deactivated non alias domains

        $result = $db->query('SELECT * '.'FROM `'.TABLE_PANEL_DOMAINS.'` '.'WHERE `deactivated` = \'1\' '.'AND `aliasdomain` IS NULL');

        while(false !== ($row = $db->fetchArray($result)))
        {
            // we need some additonal information regarding this domain, query
            // them from the database. This could be done using one big query
            // but it's more easy to maintain if we split the information
            // gathering into several smaller queries
            // we check if the customer data of this domain has already been
            // cached by a previous subQuery

            if(!isset($cache['customer'][$row['customerid']]))
            {
                // if not, fetch from the database

                $subQuery = 'SELECT * FROM `%s` WHERE `customerid` = \'%s\'';
                $subQuery = sprintf($subQuery, TABLE_PANEL_CUSTOMERS, $row['customerid']);
                $customer = $db->queryFirst($subQuery);

                // additionally store in cache

                $cache['customer'][$row['customerid']] = $customer;
            }
            else
            {
                // fetch from cache

                $customer = $cache['customer'][$row['customerid']];
            }

            // put customer data into the customer subarray

            $row['customer'] = $customer;

            // check if the documentroot is a redirect

            $row['redirectTo'] = false;

            if(preg_match('/^https?\:\/\//', $row['documentroot']))
            {
                $row['redirectTo'] = $row['documentroot'];
            }

            // resolve ip and port

            $row['ipandport'] = $ipList[$row['ipandport']];

            // load all aliasdomains this time
            //   all aliases will be put in $aliases and
            //   later directly given to the domain row, should
            //   the template care about the rest.

            $aliases = array();

            // query database

            $subQuery = 'SELECT `domain`, `iswildcarddomain` '.'FROM `%s` '.'WHERE `aliasdomain`=\'%s\'';
            $subQuery = sprintf($subQuery, TABLE_PANEL_DOMAINS, $row['id']);
            $subResult = $db->query($subQuery);

            // iterate result

            while(false !== ($subRow = $db->fetchArray($subResult)))
            {
                // put resulting domain directly to aliases

                $aliases[] = $subRow['domain'];

                // check if resulting domain is a wildcarddomain

                if($subRow['iswildcarddomain'] == 1)
                {
                    // it is, additionally put a wildcard entry to aliases

                    $aliases[] = '*.'.$subRow['domain'];
                }
                else
                {
                    // it's not, only put the default www entry to aliases

                    $aliases[] = 'www.'.$subRow['domain'];
                }
            }

            // now lets check the domain itself

            if($row['iswildcarddomain'] == 1)
            {
                $aliases[] = '*.'.$row['domain'];
            }
            else
            {
                $aliases[] = 'www.'.$row['domain'];
            }

            $queryAliases = 'SELECT `domain`, `iswildcarddomain` FROM %s WHERE parentdomainid=0 AND aliasdomain=%s';
            $queryAliases = sprintf($queryAliases, TABLE_PANEL_DOMAINS, $row['parentdomainid']);

            $resultAliases = $db->query($queryAliases);
            while(false !== ($rowAliases = $db->fetchArray($resultAliases)))
            {
                if ($row['parentdomainid'] == 0)
                {
                    $subDomainName = '';
                }
                else
                {
                    $subDomainName = substr($row['domain'],0,strpos($row['domain'], '.'));;
                }

                if ($rowAliases['iswildcarddomain'] == 1)
                {
                    $aliases[] .= '*.'.$subDomainName.'.'.$rowAliases['domain'];
                }
                else
                {
                    $aliases[] .= $subDomainName.'.'.$rowAliases['domain'];
                    $aliases[] .= 'www.'.$subDomainName.'.'.$rowAliases['domain'];
                }
            }

            // store the aliases

            $row['aliases'] = $aliases;

            // the specialsettings

            $row['specialsettings'] = stripslashes($row['specialsettings']);

            // we need to enfore the existance of the domains documentroot

            if(!is_dir($row['documentroot']))
            {
                Syscp::exec('mkdir -p "'.$row['documentroot'].'"');
                Syscp::exec('chown -R '.$row['customer']['guid'].':'.$row['customer']['guid'].' "'.$row['documentroot'].'"');
            }

            // we need to enfore the existance of the access logfile directory
            if(!is_dir(dirname($row['access_logfile'])))
            {
                Syscp::exec('mkdir -p "'.dirname($row['access_logfile']).'"');
                Syscp::exec('chown -R '.$row['customer']['guid'].':'.$row['customer']['guid'].
                            ' "'.dirname($row['access_logfile']).'"');
            }

            // we need to enfore the existance of the error logfile directory
            if(!is_dir(dirname($row['error_logfile'])))
            {
                Syscp::exec('mkdir -p "'.dirname($row['error_logfile']).'"');
                Syscp::exec('chown -R '.$row['customer']['guid'].':'.$row['customer']['guid'].
                            ' "'.dirname($row['error_logfile']).'"');
            }

            // we need to enfore the existance of the access logfile directory
            if(!is_dir(dirname($row['access_logfile'])))
            {
                    Syscp::exec('mkdir -p "'.dirname($row['access_logfile']).'"');
                    Syscp::exec('chown -R '.$row['customer']['guid'].':'.$row['customer']['guid'].
                                ' "'.dirname($row['access_logfile']).'"');
            }

            // we need to enfore the existance of the error logfile directory
            if(!is_dir(dirname($row['error_logfile'])))
            {
                    Syscp::exec('mkdir -p "'.dirname($row['error_logfile']).'"');
                    Syscp::exec('chown -R '.$row['customer']['guid'].':'.$row['customer']['guid'].
                                ' "'.dirname($row['error_logfile']).'"');
            }

            // put the location of the traffic log

            $row['trafficLog'] = SYSCP_PATH_BASE.'logs/apache2-traffic/'.$row['customer']['loginname'].'.log';
            $domains[$row['id']] = $row;
        }

        // we have loaded all domains, we need to iterate the domains
        // and make them resolve parentdomainid to parentdomain, either
        // with the domain name of the parentdomain or with the name
        // of the domain itself.

        foreach($domains as $id => $row)
        {
            if($row['parentdomainid'] != 0)
            {
                $row['parentdomain'] = $domains[$row['parentdomainid']]['domain'];
            }
            else
            {
                $row['parentdomain'] = $row['domain'];
            }
        }

        if(!file_exists(SYSCP_PATH_BASE.'logs/apache2-traffic/'))
        {
            Syscp::exec('mkdir -p '.SYSCP_PATH_BASE.'logs/apache2-traffic/');
        }

        $this->TemplateHandler->set('domains', $domains);
        $this->TemplateHandler->set('ipList', $ipList);
        $this->TemplateHandler->set('hasDiroptions', $hasDiroptions);
        $this->TemplateHandler->set('now', date('d.m.Y H:i'));
        $this->TemplateHandler->setTemplate(SYSCP_PATH_BASE.'etc/apache-vhosts.tpl');
        $vhosts_file = $this->TemplateHandler->fetch();
        file_put_contents($config->get('system.apacheconf_directory').$config->get('system.apacheconf_filename'), $vhosts_file);
        $log->info(Syscp_Handler_Log_Interface::FACILITY_USER, '-- cronRebuildVhosts: Calling OnNewVhostsFile Hook...');
        $this->_hooks->call('OnNewVhostsFile', array());
        $log->info(Syscp_Handler_Log_Interface::FACILITY_USER, '-- cronRebuildVhosts: Restarting Apache...');
        Syscp::exec($config->get('system.apachereload_command'));
    }

    /**
     * This method should _ONLY_ be called from the backend cronscript.
     *
     * This method creates a new diroptions.conf file and stores it at the
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

    function cronRebuildDiroptions($params = array())
    {
        $config = $this->_config;
        $db = $this->_db;
        $log = $this->_log;
        $log->setUsername('Hook_Apache');

        if(isset($params['path']))
        {
            $path = $params['path'];
            $log->info(Syscp_Handler_Log_Interface::FACILITY_USER, sprintf('-- cronRebuildDiroptions: Creating diroption for %s', $path));

            if(!is_dir($path))
            {
                $db->query('DELETE FROM `'.TABLE_PANEL_HTACCESS.'` '.'WHERE `path` = "'.$path.'"');
                $db->query('DELETE FROM `'.TABLE_PANEL_HTPASSWDS.'` '.'WHERE `path` = "'.$path.'"');
            }

            $diroptions_file = '';
            $diroptions_file = '# '.$config->get('system.apacheconf_directory').'diroptions.conf'."\n".'# Created '.date('d.m.Y H:i')."\n".'# Do NOT manually edit this file, all changes will be deleted after the next dir options change at the panel.'."\n"."\n";
            $result = $db->query('SELECT * '.'FROM `'.TABLE_PANEL_HTACCESS.'` '.'ORDER BY `path`');
            $diroptions = array();

            while($row_diroptions = $db->fetch_array($result))
            {
                $diroptions[$row_diroptions['path']] = $row_diroptions;
                $diroptions[$row_diroptions['path']]['htpasswds'] = array();
            }

            $result = $db->query('SELECT * '.'FROM `'.TABLE_PANEL_HTPASSWDS.'` '.'ORDER BY `path`, `username`');

            while($row_htpasswds = $db->fetch_array($result))
            {
                $diroptions[$row_htpasswds['path']]['path'] = $row_htpasswds['path'];
                $diroptions[$row_htpasswds['path']]['customerid'] = $row_htpasswds['customerid'];
                $diroptions[$row_htpasswds['path']]['htpasswds'][] = $row_htpasswds;
            }

            $htpasswd_files = array();
            foreach($diroptions as $row_diroptions)
            {
                $diroptions_file.= '<Directory "'.$row_diroptions['path'].'">'."\n";

                if(isset($row_diroptions['options_indexes'])
                   && $row_diroptions['options_indexes'] == '1')
                {
                    $diroptions_file.= '  Options +Indexes'."\n";
                }

                if(isset($row_diroptions['options_indexes'])
                   && $row_diroptions['options_indexes'] == '0')
                {
                    $diroptions_file.= '  Options -Indexes'."\n";
                }

                if(isset($row_diroptions['error404path'])
                   && $row_diroptions['error404path'] != '')
                {
                    $diroptions_file.= '  ErrorDocument 404 "'.$row_diroptions['error404path']."\"\n";
                }

                if(isset($row_diroptions['error403path'])
                   && $row_diroptions['error403path'] != '')
                {
                    $diroptions_file.= '  ErrorDocument 403 "'.$row_diroptions['error403path']."\"\n";
                }

                if(isset($row_diroptions['error500path'])
                   && $row_diroptions['error500path'] != '')
                {
                    $diroptions_file.= '  ErrorDocument 500 "'.$row_diroptions['error500path']."\"\n";
                }

                if(count($row_diroptions['htpasswds']) > 0)
                {
                    $htpasswd_file = '';
                    $htpasswd_filename = '';
                    foreach($row_diroptions['htpasswds'] as $row_htpasswd)
                    {
                        if($htpasswd_filename == '')
                        {
                            $htpasswd_filename = $config->get('system.apacheconf_directory').'htpasswd/'.$row_diroptions['customerid'].'-'.$row_htpasswd['id'].'-'.md5($row_diroptions['path']).'.htpasswd';
                            $htpasswd_files[] = basename($htpasswd_filename);
                        }

                        $htpasswd_file.= $row_htpasswd['username'].':'.$row_htpasswd['password']."\n";
                    }

                    $diroptions_file.= '  AuthType Basic'."\n";
                    $diroptions_file.= '  AuthName "Restricted Area"'."\n";
                    $diroptions_file.= '  AuthUserFile '.$htpasswd_filename."\n";
                    $diroptions_file.= '  require valid-user'."\n";

                    if(!file_exists($config->get('system.apacheconf_directory').'htpasswd/'))
                    {
                        $umask = umask();
                        umask(0000);
                        mkdir($config->get('system.apacheconf_directory').'htpasswd/', 0751);
                        umask($umask);
                    }
                    elseif(!is_dir($config->get('system.apacheconf_directory').'htpasswd/'))
                    {
                        $log->warning(Syscp_Handler_Log_Interface::FACILITY_USER, sprintf('%shtpasswd/ is not a directory, directory protection disabled!', $config->get('system.apacheconf_directory')));
                        echo 'WARNING!!! '.$config->get('system.apacheconf_directory').'htpasswd/ is not a directory. htpasswd directory protection is disabled!!!';
                    }

                    if(file_exists($config->get('system.apacheconf_directory').'htpasswd/')
                       && is_dir($config->get('system.apacheconf_directory').'htpasswd/'))
                    {
                        $htpasswd_file_handler = fopen($htpasswd_filename, 'w');
                        fwrite($htpasswd_file_handler, $htpasswd_file);
                        fclose($htpasswd_file_handler);
                    }
                }

                $diroptions_file.= '</Directory>'."\n\n";
            }

            $diroptions_file_handler = fopen($config->get('system.apacheconf_directory').'diroptions.conf', 'w');
            fwrite($diroptions_file_handler, $diroptions_file);
            fclose($diroptions_file_handler);
            $log->info(Syscp_Handler_Log_Interface::FACILITY_USER, '-- cronRebuildDiroptions: restarting apache...');
            Syscp::exec($config->get('system.apachereload_command'));

            if(file_exists($config->get('system.apacheconf_directory').'htpasswd/')
               && is_dir($config->get('system.apacheconf_directory').'htpasswd/'))
            {
                $htpasswd_file_dirhandle = opendir($config->get('system.apacheconf_directory').'htpasswd/');

                while(false !== ($htpasswd_filename = readdir($htpasswd_file_dirhandle)))
                {
                    if($htpasswd_filename != '.'
                       && $htpasswd_filename != '..'
                       && !in_array($htpasswd_filename, $htpasswd_files)
                       && file_exists($config->get('system.apacheconf_directory').'htpasswd/'.$htpasswd_filename))
                    {
                        unlink($config->get('system.apacheconf_directory').'htpasswd/'.$htpasswd_filename);
                    }
                }
            }
        }
    }
}

?>

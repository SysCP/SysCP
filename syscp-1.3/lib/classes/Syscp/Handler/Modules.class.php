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
 * @package    Syscp.Framework
 * @subpackage Syscp.Handler.Modules
 * @license    GPLv2 http://files.syscp.org/misc/COPYING.txt
 * @version
 */

class Syscp_Handler_Modules implements Syscp_Handler_Modules_Interface
{
    private $modcachedata = array();
    private $failedmodenabledchecks = array();
    private $failedmodversionchecks = array();
    const ERROR_MISSING_PARAM = 'You need to specify the %s parameter when calling Handler_Modules functions.';
    const ERROR_MISSING_MODULE_CONF = 'Your module configuration is broken. The module.conf for the %s module is missing the %s entry.';
    const ERROR_MISSING_MODULE_CONF_VALUE = 'Your module configuration is broken. The module.conf for the %s module is missing a value for the %s entry.';
    const ERROR_BROKEN_MODULE_DEPS = 'Your module dependencies are broken. Dependency resolution failed at %s. This could be because one of the following modules isn\'t enabled: %s or because one of the following modules isn\'t installed with the expected version: %s.';

    public function initialize($params = array())
    {
        if(isset($params))
        {
            $this->modcachedata = $params;
        }
        else
        {
            $error = sprintf(self::ERROR_MISSING_PARAM, 'params');
            throw new Syscp_Handler_Modules_Exception($error);
        }
    }

    private function checkModuleData($moddata = '')
    {
        $modstring = trim($moddata, '<=>-.0..9');
        $modstring = explode('/', $modstring);

        if(@$this->modcachedata[$modstring[0]][$modstring[1]]['enabled'] == '')
        {
            if(Syscp::isReadableFile(SYSCP_PATH_LIB.'modules/'.$modstring[0].'/'.$modstring[1].'/module.conf'))
            {
                $modconf = Syscp::parseConfig(SYSCP_PATH_LIB.'modules/'.$modstring[0].'/'.$modstring[1].'/module.conf');
                $modstatus = isset($modconf['Module']['enabled']) ? $modconf['Module']['enabled'] : null;
                unset($modconf);

                if($modstatus === null)
                {
                    $error = sprintf(self::ERROR_MISSING_MODULE_CONF, $modstring[0].'/'.$modstring[1], 'Module.enabled');
                    throw new Syscp_Handler_Modules_Exception($error);
                }
            }
            else
            {
                $modstatus = 'notpresent';
            }
        }
        else
        {
            $modstatus = $this->modcachedata[$modstring[0]][$modstring[1]]['enabled'];
        }

        if($modstatus == '')
        {
            $error = sprintf(self::ERROR_MISSING_MODULE_CONF_VALUE, $modstring[0].'/'.$modstring[1], 'Module.enabled');
            throw new Syscp_Handler_Modules_Exception($error);
        }

        if($modstatus == 'true'
           || $modstatus == 'core')
        {
            $enabledcheck = 1;
        }
        elseif($modstatus == 'notpresent')
        {
            array_push($this->failedmodenabledchecks, $modstring[0].'/'.$modstring[1].' (not present)');
            $enabledcheck = 0;
        }
        else
        {
            array_push($this->failedmodenabledchecks, $modstring[0].'/'.$modstring[1]);
            $enabledcheck = 0;
        }

        if($modstatus != 'notpresent')
        {
            $versionoperator = trim($moddata, '-.0..9a..zA..Z/');
            $expectedversion = trim($moddata, '<=>-a..zA..Z/');

            if($versionoperator != ''
               || $expectedversion != '')
            {
                if(@$this->modcachedata[$modstring[0]][$modstring[1]]['version'] == '')
                {
                    if(Syscp::isReadableFile(SYSCP_PATH_LIB.'modules/'.$modstring[0].'/'.$modstring[1].'/module.conf'))
                    {
                        $modconf = Syscp::parseConfig(SYSCP_PATH_LIB.'modules/'.$modstring[0].'/'.$modstring[1].'/module.conf');
                        $installedversion = isset($modconf['Module']['version']) ? $modconf['Module']['version'] : null;
                        unset($modconf);

                        if($installedversion === null)
                        {
                            $error = sprintf(self::ERROR_MISSING_MODULE_CONF, $modstring[0].'/'.$modstring[1], 'Module.version');
                            throw new Syscp_Handler_Modules_Exception($error);
                        }
                    }
                }
                else
                {
                    $installedversion = $this->modcachedata[$modstring[0]][$modstring[1]]['version'];
                }

                if($installedversion == '')
                {
                    $error = sprintf(self::ERROR_MISSING_MODULE_CONF_VALUE, $modstring[0].'/'.$modstring[1], 'Module.version');
                    throw new Syscp_Handler_Modules_Exception($error);
                }

                if(version_compare($installedversion, $expectedversion, $versionoperator))
                {
                    $versioncheck = 1;
                }
                else
                {
                    $failedversioncheck = array(
                        $modstring[0].'/'.$modstring[1],
                        $installedversion,
                        $versionoperator,
                        $expectedversion
                    );
                    array_push($this->failedmodversionchecks, $failedversioncheck);
                    $versioncheck = 0;
                }
            }
            else
            {
                $versioncheck = 1;
            }
        }
        else
        {
            $versioncheck = 0;
        }

        if($enabledcheck == 1
           && $versioncheck == 1)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public function checkDeps($vendor = '', $module = '')
    {
        if($vendor == '')
        {
            $error = sprintf(self::ERROR_MISSING_PARAM, 'vendor');
            throw new Syscp_Handler_Modules_Exception($error);
        }

        if($module == '')
        {
            $error = sprintf(self::ERROR_MISSING_PARAM, 'module');
            throw new Syscp_Handler_Modules_Exception($error);
        }

        if(@$this->modcachedata[$vendor][$module]['deps'] == '')
        {
            if(Syscp::isReadableFile(SYSCP_PATH_LIB.'modules/'.$vendor.'/'.$module.'/module.conf'))
            {
                $modconf = Syscp::parseConfig(SYSCP_PATH_LIB.'modules/'.$vendor.'/'.$module.'/module.conf');
                $depstring = isset($modconf['Module']['deps']) ? $modconf['Module']['deps'] : null;
                unset($modconf);

                if($depstring === null)
                {
                    $error = sprintf(self::ERROR_MISSING_MODULE_CONF, $vendor.'/'.$module, 'Module.deps');
                    throw new Syscp_Handler_Modules_Exception($error);
                }
            }
        }
        else
        {
            $depstring = $this->modcachedata[$vendor][$module]['deps'];
        }

        if($depstring == '')
        {
            $depstring = 1;
        }
        else
        {
            $pattern = '#([<=>]{1,2})?[a-z]+/[a-z_]+(-\d{1,3}\.\d{1,3}(\.\d{1,3})?)?#i';
            $replace = '$this->checkModuleData(\'$0\')';
            $depstring = preg_replace($pattern, $replace, $depstring);
        }

        eval('if( '.$depstring.' ) { $depsok = 1; } else { $depsok = 0; }');

        if($depsok == 1)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public function checkRevDeps($vendor = '', $module = '')
    {
        if($vendor == '')
        {
            $error = sprintf(self::ERROR_MISSING_PARAM, 'vendor');
            throw new Syscp_Handler_Modules_Exception($error);
        }

        if($module == '')
        {
            $error = sprintf(self::ERROR_MISSING_PARAM, 'module');
            throw new Syscp_Handler_Modules_Exception($error);
        }

        // check the deps for all modules, so we're sure to start from a consistent system

        if(!$this->checkAllDeps(0))
        {
            // we're not on a consistent system, tell that to the caller

            return 'notconsistent';
        }
        else
        {
            // we're on a consistent system, so let's do the actual revdep test

            $this->clearFailedModEnabledChecks();
            $this->clearFailedModVersionChecks();
            $this->modcachedata[$vendor][$module]['enabled'] = 'false';

            if($this->checkAllDeps(0))
            {
                $this->modcachedata[$vendor][$module]['enabled'] = 'true';
                return true;
            }
            else
            {
                $this->modcachedata[$vendor][$module]['enabled'] = 'true';
                return false;
            }
        }
    }

    public function checkAllDeps($exception = 1)
    {
        $modulesarray = array();
        $brokendeps = 0;
        foreach($this->modcachedata as $vendor => $value)
        {
            foreach($this->modcachedata[$vendor] as $module => $value)
            {
                if($this->modcachedata[$vendor][$module]['enabled'] != 'false')
                {
                    if(!$this->checkDeps($vendor, $module))
                    {
                        if($exception == 1)
                        {
                            array_push($modulesarray, $vendor.'/'.$module);
                        }

                        $brokendeps = 1;
                    }
                }
            }
        }

        if($exception == 1)
        {
            if($brokendeps == 1)
            {
                $modulesarray = array_unique($modulesarray);
                $moduleslist = '';
                foreach($modulesarray as $modulearr)
                {
                    $moduleslist.= $modulearr.' ';
                }

                $enablederrors = $this->getFailedModEnabledChecks();
                $enablederrorslist = '';
                foreach($enablederrors as $error)
                {
                    $enablederrorslist.= $error.' ';
                }

                if($enablederrorslist == '')
                {
                    $enablederrorslist = '- ';
                }

                $versionerrors = $this->getFailedModVersionChecks();
                $versionerrorslist = '';
                foreach($versionerrors as $errorarr)
                {
                    $versionerrorslist.= 'expected: '.$errorarr[2].$errorarr[0].$errorarr[3].' (installed: '.$errorarr[1].' ) ';
                }

                if($versionerrorslist == '')
                {
                    $versionerrorslist = '- ';
                }

                $error = sprintf(self::ERROR_BROKEN_MODULE_DEPS, $moduleslist, $enablederrorslist, $versionerrorslist);
                throw new Syscp_Handler_Modules_Exception($error);
            }
        }
        else
        {
            if($brokendeps == 1)
            {
                return false;
            }
            else
            {
                return true;
            }
        }
    }

    public function getFailedModEnabledChecks()
    {
        return array_unique($this->failedmodenabledchecks);
    }

    public function getFailedModVersionChecks()
    {
        return $this->failedmodversionchecks;
    }

    public function clearFailedModEnabledChecks()
    {
        $this->failedmodenabledchecks = array();
    }

    public function clearFailedModVersionChecks()
    {
        $this->failedmodversionchecks = array();
    }
}


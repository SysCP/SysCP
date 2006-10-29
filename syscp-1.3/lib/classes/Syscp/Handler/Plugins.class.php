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
 * @subpackage Syscp.Handler.Plugins
 * @license    GPLv2 http://files.syscp.org/misc/COPYING.txt
 * @version
 */

class Syscp_Handler_Plugins implements Syscp_Handler_Plugins_Interface
{
    private $moduleConfig = array();
    private $theme = '';
    private $language = '';
    private $ConfigHandler = null;
    private $DatabaseHandler = null;
    private $HookHandler = null;
    private $IdnaHandler = null;
    private $L10nHandler = null;
    private $LogHandler = null;
    private $SessionHandler = null;
    private $TemplateHandler = null;
    private $ValidationHandler = null;
    const ERROR_MISSING_MAININIT = 'You need to specify the following parameters correctly when calling Handler_Plugins initialize: %s.';
    const ERROR_MISSING_FUNCINIT = 'You need to specify the following parameters correctly when calling one of Handler_Plugins functions: %s.';

    public function initialize($params = array())
    {
        if(isset($params['moduleconfig'])
           && $params['moduleconfig'] != ''
           && isset($params['theme'])
           && $params['theme'] != ''
           && isset($params['language'])
           && $params['language'] != ''
           && isset($params['confighandler'])
           && $params['confighandler'] != null
           && isset($params['databasehandler'])
           && $params['databasehandler'] != null
           && isset($params['hookhandler'])
           && $params['hookhandler'] != null
           && isset($params['idnahandler'])
           && $params['idnahandler'] != null
           && isset($params['l10nhandler'])
           && $params['l10nhandler'] != null
           && isset($params['loghandler'])
           && $params['loghandler'] != null
           && isset($params['sessionhandler'])
           && $params['sessionhandler'] != null
           && isset($params['templatehandler'])
           && $params['templatehandler'] != null
           && isset($params['validationhandler'])
           && $params['validationhandler'] != null)
        {
            $this->moduleConfig = $params['moduleconfig'];
            $this->theme = $params['theme'];
            $this->language = $params['language'];
            $this->ConfigHandler = $params['confighandler'];
            $this->DatabaseHandler = $params['databasehandler'];
            $this->HookHandler = $params['hookhandler'];
            $this->IdnaHandler = $params['idnahandler'];
            $this->L10nHandler = $params['l10nhandler'];
            $this->LogHandler = $params['loghandler'];
            $this->SessionHandler = $params['sessionhandler'];
            $this->TemplateHandler = $params['templatehandler'];
            $this->ValidationHandler = $params['validationhandler'];
        }
        else
        {
            $error = sprintf(self::ERROR_MISSING_MAININIT, 'moduleconfig, theme, language, confighandler, databasehandler, hookhandler, idnahandler, l10nhandler, loghandler, sessionhandler, templatehandler, validationhandler');
            throw new Syscp_Handler_Plugins_Exception($error);
        }
    }

    public function getPluginsLogic($vendor = '', $module = '', $area = '', $action = '')
    {
        if($vendor == ''
           || $module == ''
           || $area == ''
           || $action == '')
        {
            $error = sprintf(self::ERROR_MISSING_FUNCINIT, 'vendor, module, area, action');
            throw new Syscp_Handler_Plugins_Exception($error);
        }

        // Init vars

        $pluginsarray = array();

        // Build the list of modules that define a plugin for the caller module

        foreach($this->moduleConfig as $vendorall => $value)
        {
            foreach($this->moduleConfig[$vendorall] as $moduleall => $value)
            {
                if(($this->moduleConfig[$vendorall][$moduleall]['enabled'] == 'true' || $this->moduleConfig[$vendorall][$moduleall]['enabled'] == 'core')
                   && isset($this->moduleConfig[$vendorall][$moduleall]['plugins'])
                   && $this->moduleConfig[$vendorall][$moduleall]['plugins'] != ''
                   && strpos($this->moduleConfig[$vendorall][$moduleall]['plugins'], $vendor.'/'.$module) !== false)
                {
                    array_push($pluginsarray, $vendorall.'/'.$moduleall);
                }
            }
        }

        // Let's sort the array

        sort($pluginsarray);

        // And now let's require the plugin files

        foreach($pluginsarray as $venmodplugin)
        {
            // Plugin file

            if(Syscp::isReadableFile(SYSCP_PATH_LIB.'modules/'.$venmodplugin.'/plugins/logic/'.$vendor.'-'.$module.'-'.$area.'-'.$action.'.php'))
            {
                require_once SYSCP_PATH_LIB.'modules/'.$venmodplugin.'/plugins/logic/'.$vendor.'-'.$module.'-'.$area.'-'.$action.'.php';
            }
        }
    }

    public function getPluginsDisplay($vendor = '', $module = '', $area = '', $action = '')
    {
        if($vendor == ''
           || $module == ''
           || $area == ''
           || $action == '')
        {
            $error = sprintf(self::ERROR_MISSING_FUNCINIT, 'vendor, module, area, action');
            throw new Syscp_Handler_Plugins_Exception($error);
        }

        // Init vars

        $syscp_plugins = '';
        $pluginsarray = array();

        // Build the list of modules that define a plugin for the caller module

        foreach($this->moduleConfig as $vendorall => $value)
        {
            foreach($this->moduleConfig[$vendorall] as $moduleall => $value)
            {
                if(($this->moduleConfig[$vendorall][$moduleall]['enabled'] == 'true' || $this->moduleConfig[$vendorall][$moduleall]['enabled'] == 'core')
                   && isset($this->moduleConfig[$vendorall][$moduleall]['plugins'])
                   && $this->moduleConfig[$vendorall][$moduleall]['plugins'] != ''
                   && strpos($this->moduleConfig[$vendorall][$moduleall]['plugins'], $vendor.'/'.$module) !== false)
                {
                    array_push($pluginsarray, $vendorall.'/'.$moduleall);
                }
            }
        }

        // Let's sort the array

        sort($pluginsarray);

        // And now let's require the plugin files and build up the HTML template

        foreach($pluginsarray as $venmodplugin)
        {
            // Plugin file

            if(Syscp::isReadableFile(SYSCP_PATH_LIB.'modules/'.$venmodplugin.'/plugins/display/'.$vendor.'-'.$module.'-'.$area.'-'.$action.'.php'))
            {
                require_once SYSCP_PATH_LIB.'modules/'.$venmodplugin.'/plugins/display/'.$vendor.'-'.$module.'-'.$area.'-'.$action.'.php';
            }

            // HTML template

            if(Syscp::isReadableFile(SYSCP_PATH_LIB.'themes/'.$this->theme.'/'.$venmodplugin.'/plugins/'.$vendor.'-'.$module.'-'.$area.'-'.$action.'.tpl'))
            {
                $syscp_plugins.= file_get_contents(SYSCP_PATH_LIB.'themes/'.$this->theme.'/'.$venmodplugin.'/plugins/'.$vendor.'-'.$module.'-'.$area.'-'.$action.'.tpl');
            }
        }

        // Assign the HTML template to the TemplateHandler for inclusion and evaluation

        $this->TemplateHandler->set('syscp_plugins', $syscp_plugins);
    }
}


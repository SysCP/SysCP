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
 * @package    Syscp.Framework
 * @subpackage Syscp.Handler
 * @license    GPLv2 http://files.syscp.org/misc/COPYING.txt
 * @version    $Id:admin_admins.php 460 2006-04-23 15:07:49 +0200 (So, 23 Apr 2006) martin $
 */

Syscp::uses('PEAR.Log');

/**
 * @todo Extended documentation here!
 *
 * @author     Martin Burchert <eremit@syscp.org>
 * @copyright  (c) 2006, The Authors
 * @package    Syscp.Framework
 * @subpackage Syscp.Handler
 */

class Syscp_Handler_Log implements Syscp_Handler_Log_Interface
{
    private $_instances = array();
    private $_username = null;
    private $_logdir = null;
    const ERROR_MISSING_PARAM = 'You need to specify the %s parameter.';
    const ERROR_LOGDIR_MISSING = 'Can not find the log directory at %s.';
    const ERROR_LOGDIR_READONLY = 'The log directory at %s is readonly by the webserver.';
    const ERROR_FACILITY_INVALID = 'The facility (%s) you requested does not exist or is not instanciated.';

    /**
     * Initializes the log handler.
     *
     * Valid parameters:
     *   'logdir': The base path, where all logfiles should be stored. (required)
     *
     * @param unknown_type $params
     * @throws Syscp_Handler_Log_Exception
     */

    public function initialize($params = array())
    {
        // Lets do some param checks first
        // the 'logdir'

        if(isset($params['logdir'])
           && trim((string)$params['logdir']) != '')
        {
            $logdir = $params['logdir'];

            // we need to check first if the given directory exists and is writeable

            if(is_dir($logdir)
               && is_writeable($logdir))
            {
                // now we need to ensure a slash is at the end of the logdir
                // we do it the hacky way

                $logdir.= '/';
                $logdir = str_replace('//', '/', $logdir);
                $this->_logdir = $logdir;
            }
            else
            {
                if(!is_dir($logdir))
                {
                    $error = sprintf(self::ERROR_LOGDIR_MISSING, $logdir);
                    throw new Syscp_Handler_Log_Exception($error);
                }

                if(!is_writeable($logdir))
                {
                    $error = sprintf(self::ERROR_LOGDIR_READONLY, $logdir);
                    throw new Syscp_Handler_Log_Exception($error);
                }
            }
        }
        else
        {
            $error = sprintf(self::ERROR_MISSING_PARAM, 'logdir');
            throw new Syscp_Handler_Log_Exception($error);
        }

        // initialises the facility instances of PEAR_Log

        $this->_instances[self::FACILITY_AUTH] = &Log::factory('file', $this->_logdir.'auth.log', 'Auth', array());
        $this->_instances[self::FACILITY_USER] = null;
        $this->_instances[self::FACILITY_SYSTEM] = &Log::factory('file', $this->_logdir.'system.log', 'System', array());
        $this->_instances[self::FACILITY_DEBUG] = &Log::factory('file', $this->_logdir.'debug.log', 'Debug', array());
    }

    public function critical($facility, $message)
    {
        if(isset($this->_instances[$facility])
           && !is_null($this->_instances[$facility]))
        {
            $this->_instances[$facility]->crit($message);
        }
        else
        {
            $error = sprintf(self::ERROR_FACILITY_INVALID, $facility);
            throw new Syscp_Handler_Log_Exception($error);
        }
    }

    public function warning($facility, $message)
    {
        if(isset($this->_instances[$facility])
           && !is_null($this->_instances[$facility]))
        {
            $this->_instances[$facility]->crit($message);
        }
        else
        {
            $error = sprintf(self::ERROR_FACILITY_INVALID, $facility);
            throw new Syscp_Handler_Log_Exception($error);
        }
    }

    public function notice($facility, $message)
    {
        if(isset($this->_instances[$facility])
           && !is_null($this->_instances[$facility]))
        {
            $this->_instances[$facility]->notice($message);
        }
        else
        {
            $error = sprintf(self::ERROR_FACILITY_INVALID, $facility);
            throw new Syscp_Handler_Log_Exception($error);
        }
    }

    public function info($facility, $message)
    {
        if(isset($this->_instances[$facility])
           && !is_null($this->_instances[$facility]))
        {
            $this->_instances[$facility]->info($message);
        }
        else
        {
            $error = sprintf(self::ERROR_FACILITY_INVALID, $facility);
            throw new Syscp_Handler_Log_Exception($error);
        }
    }

    public function debug($facility, $message)
    {
        if(isset($this->_instances[$facility])
           && !is_null($this->_instances[$facility]))
        {
            $this->_instances[$facility]->debug($message);
        }
        else
        {
            $error = sprintf(self::ERROR_FACILITY_INVALID, $facility);
            throw new Syscp_Handler_Log_Exception($error);
        }
    }

    public function setUsername($username)
    {
        // check if the username was set previously

        if($this->_username !== null)
        {
            // it was set, we need to close the current instance
            // before creating a new log instance.

            $this->_instances[self::FACILITY_USER]->flush();
            $this->_instances[self::FACILITY_USER]->close();
        }

        // set the username

        $this->_username = $username;

        // and create the needed facility

        $this->_instances[self::FACILITY_USER] = &Log::factory('file', $this->_logdir.'user-'.$username.'.log', $username);
    }
}


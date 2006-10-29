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

/**
 * @todo Extended documentation here!
 *
 * @author     Martin Burchert <eremit@syscp.org>
 * @copyright  (c) 2006, The Authors
 * @package    Syscp.Framework
 * @subpackage Syscp.Handler
 */

class Syscp_Handler_Session implements Syscp_Handler_Session_Interface
{
    private $DatabaseHandler = null;
    private $remoteAddr = null;
    private $httpUserAgent = null;
    private $sessionTimeout = null;
    const ERROR_MISSING_PARAM = 'You need to specify the %s parameter.';
    const ERROR_PARAM_TYPE = 'The param %s needs to be an instance of %s.';

    public function initialize($params = array())
    {
        // we don't want to repeat ourselves

        $required = array(
            'DatabaseHandler' => 'Syscp_Handler_Database_Interface',
            'remoteAddr' => '',
            'httpUserAgent' => '',
            'sessionTimeout' => ''
        );
        foreach($required as $index => $instanceOf)
        {
            if(isset($params[$index]))
            {
                if($instanceOf == ''
                   || $params[$index] instanceof $instanceOf)
                {
                    $this->$index = $params[$index];
                }
                else
                {
                    $error = sprintf(self::ERROR_PARAM_TYPE, $index, $instanceOf);
                    throw new Syscp_Handler_Session_Exception($error);
                }
            }
            else
            {
                $error = sprintf(self::ERROR_MISSING_PARAM, $index);
                throw new Syscp_Handler_Session_Exception($error);
            }
        }

        ini_set('session.gc_maxlifetime', $this->sessionTimeout);
        ini_set('session.gc_probability', 100);
        ini_set('session.use_cookies', false);
        session_name('s');
        session_set_save_handler(array(
            $this,
            'openSession'
        ), array(
            $this,
            'closeSession'
        ), array(
            $this,
            'readSession'
        ), array(
            $this,
            'writeSession'
        ), array(
            $this,
            'destroySession'
        ), array(
            $this,
            'gcSession'
        ));
    }

    /**
     * returnes whether a session exists or not
     *   true : session exists
     *   false: session does not exist
     *
     * @author  Martin Burchert <martin.burchert@syscp.org>
     * @author  Florian Lippert <florian.lippert@syscp.org>
     *
     * @since   1.0
     *
     * @param   string   $sessionId
     *
     * @return  boolean
     */

    public function isValid($sessionId)
    {
        $query = 'SELECT `hash` '.'FROM   `%s` '.'WHERE  `hash`         = \'%s\' '.'  AND  `ipaddress`    = \'%s\' '.'  AND  `useragent`    = \'%s\' ';
        '  AND `lastactivity` >= \'%s\' ';
        $query = sprintf($query, TABLE_PANEL_SESSIONS, $sessionId, $this->remoteAddr, $this->httpUserAgent, time()-ini_get('session.gc_maxlifetime'));
        $result = $this->DatabaseHandler->query($query);
        $row = $this->DatabaseHandler->fetchArray($result);

        if($row
           && $sessionId != '')
        {
            $return = true;
        }
        else
        {
            $return = false;
        }

        return $return;
    }

    /**
     * Creates a new session with the given sessionId
     *
     * @author  Martin Burchert <martin.burchert@syscp.org>
     * @author  Florian Lippert <florian.lippert@syscp.org>
     *
     * @since   1.0
     *
     * @param   mixed  $sessionId
     *
     * @return  void
     */

    public function createSession($sessionId)
    {
        $query = 'INSERT INTO `%s` '.'SET `hash`         = \'%s\', '.'    `data`         = \'\', '.'    `lastactivity` = \'%s\', '.'    `ipaddress`    = \'%s\', '.'    `useragent`    = \'%s\' ';
        $query = sprintf($query, TABLE_PANEL_SESSIONS, $sessionId, time(), $this->remoteAddr, $this->httpUserAgent);
        $this->DatabaseHandler->query($query);
    }

    /**
     * Dummy handler for session_open for phpsession
     * This handler is not needed here, will always return true
     *
     * @author  Martin Burchert <martin.burchert@syscp.org>
     * @author  Florian Lippert <florian.lippert@syscp.org>
     *
     * @since   1.0
     *
     * @return  boolean
     */

    public function openSession($savePath, $sessionName)
    {
        return true;
    }

    /**
     * Dummy handler for session_close for phpsession
     * This handler is not needed here, will always return true
     *
     * @author  Martin Burchert <martin.burchert@syscp.org>
     * @author  Florian Lippert <florian.lippert@syscp.org>
     *
     * @since   1.0
     *
     * @return  boolean
     */

    public function closeSession()
    {
        return true;
    }

    /**
     * Returns data for a session
     *
     * Can be invoked as:
     * <code>
     *     $yourvar = $_SESSION['varname'];
     * </code>
     *
     * @author  Martin Burchert <martin.burchert@syscp.org>
     * @author  Florian Lippert <florian.lippert@syscp.org>
     *
     * @since   1.0
     *
     * @param   string  $sessionId
     * @return  mixed
     */

    public function readSession($sessionId)
    {
        if(!$this->isValid($sessionId))
        {
            $this->createSession($sessionId);
        }

        $query = 'SELECT * '.'FROM   `%s` '.'WHERE  `hash`         = \'%s\' '.'  AND `ipaddress`     = \'%s\' '.'  AND `useragent`     = \'%s\' '.'  AND `lastactivity` >= \'%s\' ';
        $query = sprintf($query, TABLE_PANEL_SESSIONS, $sessionId, $this->remoteAddr, $this->httpUserAgent, time()-ini_get('session.gc_maxlifetime'));
        $data = $this->DatabaseHandler->queryFirst($query);
        return $data['data'];
    }

    /**
     * Writes session data to database
     *
     * Can be invoked as:
     * <code>
     *     $_SESSION['varname'] = 'newval';
     * </code>
     *
     * @author  Martin Burchert <martin.burchert@syscp.org>
     * @author  Florian Lippert <florian.lippert@syscp.org>
     *
     * @since   1.0
     *
     * @param   string  $sessionId
     * @return  boolean
     */

    public function writeSession($sessionId, $data)
    {
        $query = 'UPDATE `%s` '.'SET    `data`          = \'%s\', '.'       `lastactivity`  = \'%s\' '.'WHERE  `hash`          = \'%s\' '.'  AND  `ipaddress`     = \'%s\' '.'  AND  `useragent`     = \'%s\' '.'  AND  `lastactivity` >= \'%s\' ';
        $query = sprintf($query, TABLE_PANEL_SESSIONS, $data, time(), $sessionId, $this->remoteAddr, $this->httpUserAgent, time()-ini_get('session.gc_maxlifetime'));
        $this->DatabaseHandler->query($query);
        return true;
    }

    /**
     * Destroys a session and deletes it from database
     *
     * @author  Martin Burchert <martin.burchert@syscp.org>
     * @author  Florian Lippert <florian.lippert@syscp.org>
     *
     * @since   1.0
     *
     * @param   string  $sessionId
     * @return  boolean
     */

    public function destroySession($sessionId)
    {
        $query = 'DELETE FROM `%s` '.'WHERE `hash`      = \'%s\' '.'  AND `ipaddress` = \'%s\' '.'  AND `useragent` = \'%s\' ';
        $query = sprintf($query, TABLE_PANEL_SESSIONS, $sessionId, $this->remoteAddr, $this->httpUserAgent);
        $this->DatabaseHandler->query($query);
        return true;
    }

    /**
     * Deletes all expired sessions from database
     *
     * @author  Martin Burchert <martin.burchert@syscp.org>
     * @author  Florian Lippert <florian.lippert@syscp.org>
     *
     * @since   1.0
     *
     * @param   integer  $maxlifetime
     * @return  boolean
     */

    public function gcSession($maxlifetime)
    {
        $query = 'DELETE FROM `%s` '.'WHERE `lastactivity` < \'%s\'';
        $query = sprintf($query, TABLE_PANEL_SESSIONS, (time()-$maxlifetime));
        $this->DatabaseHandler->query($query);
        return true;
    }
}


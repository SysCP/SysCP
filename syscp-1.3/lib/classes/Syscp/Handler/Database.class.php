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

class Syscp_Handler_Database implements Syscp_Handler_Database_Interface
{
    private $link = null;
    const ERROR_MISSING_PARAM = 'You need to specify the %s parameter.';
    const ERROR_MISSING_EXTENSION = 'You should install the PHP MySQL extension!';
    const ERROR_DBSELECT_FAILED = 'Trying to use database %s failed, exiting';
    const ERROR_INVALID_SQL = 'Invalid SQL: %s';

    public function initialize($params = array())
    {
        // we first need to check if the requested param 'dsn' is defined

        if(isset($params['dsn']))
        {
            // we first need to reparse the dsn
            // mysql://user:pass@host/database

            $dsn = $params['dsn'];
            list(, $dsn) = split('://', $dsn);
            list($user, $dsn) = split(':', $dsn);
            list($pass, $dsn) = split('@', $dsn);

            if(preg_match('/\//', $dsn))
            {
                list($host, $name) = split('/', $dsn);
            }
            else
            {
                $host = $dsn;
            }

            // we now have $user, $pass, $host, $name
        }
        else
        {
            $error = sprintf(self::ERROR_MISSING_PARAM, 'dsn');
            throw new Syscp_Handler_Database_Exception($error);
        }

        // check if the mysql extension has been loaded

        if(!extension_loaded('mysql'))
        {
            throw new Syscp_Handler_Database_Exception(self::ERROR_MISSING_EXTENSION);
        }

        $this->link = mysql_connect($host, $user, $pass);

        if(isset($name))
        {
            $result = mysql_select_db($name, $this->link);

            // now check if the database could be selected

            if($result === false)
            {
                $error = sprintf(self::ERROR_DBSELECT_FAILED, $name);
                throw new Syscp_Handler_Database_Exception($error);
            }
        }
    }

    public function close()
    {
        return mysql_close($this->link);
    }

    public function query($queryStr, $unbuffered = false)
    {
        if(!$unbuffered)
        {
            $result = mysql_query($queryStr, $this->link);
        }
        else
        {
            $result = mysql_unbuffered_query($queryStr, $this->link);
        }

        if(!$result)
        {
            $error = sprintf(self::ERROR_INVALID_SQL, $queryStr);
            throw new Syscp_Handler_Database_Exception($error);
        }

        return $result;
    }

    public function fetchArray($resultID, $datatype = MYSQL_ASSOC)
    {
        return mysql_fetch_array($resultID, $datatype);
    }

    public function queryFirst($queryStr, $datatype = MYSQL_ASSOC)
    {
        $result = $this->query($queryStr);
        return $this->fetchArray($result, $datatype);
    }

    public function numRows($resultID)
    {
        return mysql_num_rows($resultID);
    }

    public function insertID()
    {
        return mysql_insert_id($this->link);
    }

    public function affectedRows()
    {
        return mysql_affected_rows($this->link);
    }

    public function escape($input)
    {
        return mysql_real_escape_string($input, $this->link);
    }

    // -- old style convenience wrapper

    public function fetch_array($resultID, $datatype = MYSQL_ASSOC)
    {
        return $this->fetchArray($resultID, $datatype);
    }

    public function query_first($queryStr, $datatype = MYSQL_ASSOC)
    {
        return $this->queryFirst($queryStr, $datatype);
    }

    public function num_rows($resultID)
    {
        return $this->numRows($resultID);
    }

    public function insert_id()
    {
        return $this->insertID();
    }

    public function affected_rows()
    {
        return $this->affectedRows();
    }
}


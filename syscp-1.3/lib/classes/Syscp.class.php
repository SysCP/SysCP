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
 * @subpackage Syscp
 * @license    GPLv2 http://files.syscp.org/misc/COPYING.txt
 * @version    $Id:admin_admins.php 460 2006-04-23 15:07:49 +0200 (So, 23 Apr 2006) martin $
 */

/**
 * This final class contains some basic convenience methods. All methods in this class are
 * static.
 *
 * @author     Martin Burchert <eremit@syscp.org>
 * @copyright  (c) 2006, The Authors
 * @package    Syscp.Framework
 * @subpackage Syscp
 */

final class Syscp
{
    /**
     * Stores if this class has been initialized.
     * @var boolean
     */

    static private $initialized = false;

    /**
     * Internal configuration for method uses()
     * @var array
     */

    static private $USES_EXTENSIONS = array(
        '.php',
        '.class.php'
    );

    /**
     * Error Message if the initialization failed.
     */

    const ERROR_INIT_FAILED = 'Syscp::initialize() failed, cannot load all core dependencies.';

    /**
     * Error message if uses() cannot find the requested file.
     */

    const ERROR_USES_FAILED = 'Syscp::uses() failed, the requested file %s does not exist.';

    /**
     * Error message for generic file not found errors.
     */

    const ERROR_FILE_NOT_FOUND = 'The file %s cannot be found.';

    /**
     * Error message if the command given to exec() fails the security check.
     */

    const ERROR_EXEC_SECURITY = 'Syscp::exec() failed the SECURITY CHECK! There are not allowed chars in the command "%s"!';

    /**
     * Initialisation Method
     * This method initialisates this class, by loading some requirements, like
     * the needed exception.
     *
     * @author    Martin Burchert <eremit@syscp.org>
     *
     * @return void
     */

    static private function initialize()
    {
        $file = dirname(__FILE__);
        $file.= '/Syscp/Exception.class.php';

        if(file_exists($file))
        {
            require_once $file;
            self::$initialized = true;
        }
        else
        {
            // we need to throw a normal exception here, because we
            // maybe could not load the base exception

            throw new Exception(sprintf(self::ERROR_INIT_FAILED));
        }
    }

    /**
     * This method tries to load a class, the interface description and
     * the exception bundled to the given class by using the namespace
     * the class has. The namespace of the class is a dot delimited string.
     *
     * Basically this method only replaces the dots of the namespace with
     * slashes and takes a look relative to the directory of this file.
     *
     * @author Martin Burchert <eremit@adm1n.de>
     *
     * @param  string  $namespace The dot seperated namespace of the class to load
     * @param  boolean $required  Should this method fail if there is no file
     *                            within the namespace
     *
     * @return void
     *
     * @throws Syscp_Exception if required is true and the class cannot be found.
     */

    static public function uses($namespace, $required = true)
    {
        if(!self::$initialized)self::initialize();

        // get the basename of this file

        $basepath = dirname(__FILE__);

        // now lets generate the full path with changing the namespace to path

        $file = $basepath.'/'.str_replace('.', '/', $namespace);
        $isLoaded = false;
        foreach(self::$USES_EXTENSIONS as $ext)
        {
            if(self::isReadableFile($file.$ext)
               && !$isLoaded)
            {
                self::uses($namespace.'.Exception', false);
                self::uses($namespace.'.Interface', false);
                require_once $file.$ext;
                $isLoaded = true;
            }
        }

        if(!$isLoaded
           && $required)
        {
            throw new Syscp_Exception(sprintf(self::ERROR_USES_FAILED, $file));
        }
    }

    /**
     * This method loads the given config file, parses it and returns an array
     * representation of the config data stored within the file.
     *
     * @author Martin Burchert <eremit@syscp.org>
     *
     * @param  string $filename absolute path to the config file
     * @return array
     *
     * @throws Syscp_Exception if the config file cannot be found.
     */

    static public function parseConfig($filename)
    {
        if(!self::$initialized)self::initialize();
        $result = array();

        if(self::isReadableFile($filename))
        {
            $file = file($filename);

            // parse the file iterating over the lines

            foreach($file as $line)
            {
                $line = trim($line);

                if($line != ''
                   && substr($line, 0, 1) != '#')
                {
                    // we have found a config line

                    list($var, $value) = split('=', $line, 2);
                    $var = trim($var);
                    $value = trim($value);

                    // now we have the value and the var, we need to
                    // split the varname into it's parts

                    $var = split('\.', $var);

                    // and create a multidimensional array out of it

                    $tmp = array();

                    // we turn around the whole array

                    $var = array_reverse($var);
                    $tmp = $value;
                    $tmp2 = array();
                    foreach($var as $part)
                    {
                        $tmp2[$part] = $tmp;
                        $tmp = $tmp2;
                        $tmp2 = array();
                    }

                    $result = array_merge_recursive($result, $tmp);
                }
            }
        }
        else
        {
            throw new Syscp_Exception(sprintf(self::ERROR_FILE_NOT_FOUND, $filename));
        }

        return $result;
    }

    /**
     * Convenience method which does a file_exists and is_readable and is_file
     * check on the given filename.
     *
     * @author Martin Burchert <eremit@adm1n.de>
     *
     * @param  string  $filename absolute path of the file to be checked
     *
     * @return boolean Either the file exists and is readable or not.
     */

    static public function isReadableFile($filename)
    {
        if(!self::$initialized)self::initialize();

        if(file_exists($filename)
           && is_readable($filename)
           && is_file($filename))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    /**
     * Convenience method which does a file_exists and is_readable and is_dir
     * check on the given path.
     *
     * @author Martin Burchert <eremit@adm1n.de>
     *
     * @param  string  $path  absolute path of the directory to be checked
     *
     * @return boolean Either the directory exists and is readable or not.
     */

    static public function isReadableDir($path)
    {
        if(!self::$initialized)self::initialize();

        if(file_exists($path)
           && is_readable($path)
           && is_dir($path))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    /**
     * Convenience method which does a file_exists and is_writeable and is_file
     * check on the given filename.
     *
     * @author Martin Burchert <eremit@adm1n.de>
     *
     * @param  string  $filename absolute path of the file to be checked
     *
     * @return boolean Either the file exists and is writeable or not.
     */

    static public function isWriteableFile($filename)
    {
        if(!self::$initialized)self::initialize();

        if(file_exists($filename)
           && is_writeable($filename)
           && is_file($filename))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    /**
     * Convenience method which does a file_exists and is_writeable and is_dir
     * check on the given path.
     *
     * @author Martin Burchert <eremit@adm1n.de>
     *
     * @param  string  $path  absolute path of the directory to be checked
     *
     * @return boolean Either the directory exists and is writeable or not.
     */

    static public function isWriteableDir($path)
    {
        if(!self::$initialized)self::initialize();

        if(file_exists($path)
           && is_writeable($path)
           && is_dir($path))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    /**
     * Wrapper arround the php exec() command, with some additional security checks
     *
     * This method checks if the given command contains at least one of the chars
     * listed in $harmfull. If this is the case, an exception will be thrown.
     * Otherwise the given command will be executed and the result will be returned.
     *
     * @author Martin Burchert <eremit@adm1n.de>
     *
     * @param  string $command Shell command to be executed
     *
     * @return mixed  The result of the shell command, as returned from the exec()
     *                command.
     *
     * @throws Syscp_Exception if the command doesn't pass the security check.
     */

    static public function exec($command)
    {
        // init the result with null

        $result = null;

        // define the list of harmfull chars

        $harmfull = array(
            ';',
            '|',
            '&',
            '>',
            '<',
            '`',
            '$',
            '~',
            '?'
        );

        // check for possible harmfull chars in the command

        foreach($harmfull as $test)
        {
            if(stristr($command, $test))
            {
                $error = sprintf(self::ERROR_EXEC_SECURITY, $command);
                throw new Syscp_Exception($error);
            }
        }

        // if we get here, the command passed the security barrier, we can
        // continue with command execution

        exec($command, $result);
        return $result;
    }
}


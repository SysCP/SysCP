<?php

/**
 * This file is part of the SysCP project.
 * Copyright (c) 2003-2006 the SysCP Project.
 *
 * For the full copyright and license information, please view the COPYING
 * file that was distributed with this source code. You can also view the
 * COPYING file online at http://files.syscp.org/misc/COPYING.txt
 *
 * @author     Florian Aders <eleras@syscp.org>
 * @copyright  (c) 2006, the authors
 * @package    Syscp.Framework
 * @subpackage Syscp.Handler
 * @license    GPLv2 http://files.syscp.org/misc/COPYING.txt
 * @version    $Id$
 */

/**
 * @todo Extended documentation here!
 *
 * @author     Florian Aders <eleras@syscp.org>
 * @copyright  (c) 2006, The Authors
 * @package    Syscp.Framework
 * @subpackage Syscp.Handler
 */

class Syscp_Handler_Validation implements Syscp_Handler_Validation_Interface
{
    protected $validators = array();
    const ERROR_VALIDATOR_EXISTS = 'A validator named \'%s\' does already exists';
    const ERROR_VALIDATOR_DOES_NOT_EXISTS = 'A validator named \'%s\' does not exists';

    public function initialize($params = array())
    {
        $this->loadDefaultValidators();
    }

    public function addValidator($name, $validator)
    {
        if(isset($this->validators[$name]))
        {
            $error = sprintf(self::ERROR_VALIDATOR_EXISTS, $name);
            throw new Syscp_Handler_Validation_Exception($error);
        }
        else
        {
            $this->validators[$name] = $validator;
        }
    }

    public function getValidator($name)
    {
        if(!isset($this->validators[$name]))
        {
            $error = sprintf(self::ERROR_VALIDATOR_DOES_NOT_EXISTS, $name);
            throw new Syscp_Handler_Validation_Exception($error);
        }
        else
        {
            return $this->validators[$name];
        }
    }

    public function removeValidator($name)
    {
        if(isset($this->validators[$name]))
        {
            unset($this->validators[$name]);
        }
    }

    public function replaceValidator($name, $validator)
    {
        $this->removeValidator($name);
        $this->addValidator($name, $validator);
    }

    protected function loadDefaultValidators()
    {
        Syscp::uses('Syscp.Validators.Regex');

        // add some generic RegexValidators

        $this->addValidator('basic', new Syscp_Validator_Regex('/^[^\r\n\0]*$/'));
        $this->addValidator('url', new Syscp_Validator_Regex('!^https?://[a-z0-9öüäÖÜÄ\.\-/\?&=%\+#]+$!i'));
        $this->addValidator('username', new Syscp_Validator_Regex('/^[a-zA-Z0-9][a-zA-Z0-9\-_]+\$?$/'));
        $this->addValidator('email', new Syscp_Validator_Regex('/^([_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*(\.[a-zA-Z]{2,}))/si'));
    }
}


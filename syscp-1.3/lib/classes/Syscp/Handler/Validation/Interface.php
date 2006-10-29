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
 * @package    Syscp.Framework.API
 * @subpackage Syscp.Handler.Validation
 * @license    GPLv2 http://files.syscp.org/misc/COPYING.txt
 * @version    $Id$
 */

/**
 * @todo Extended documentation here!
 *
 * @author     Florian Aders <eleras@syscp.org>
 * @copyright  (c) 2006, The Authors
 * @package    Syscp.Framework.API
 * @subpackage Syscp.Handler.Validation
 * @see        Syscp_Handler_Validation
 */

interface Syscp_Handler_Validation_Interface extends Syscp_Handler_Interface
{
    public function addValidator($name, $validator);

    public function getValidator($name);

    public function replaceValidator($name, $validator);

    public function removeValidator($name);
}


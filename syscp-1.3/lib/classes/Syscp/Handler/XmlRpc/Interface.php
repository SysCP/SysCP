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
 * @package    Syscp.Framework.API
 * @subpackage Syscp.Handler.XmlRpc
 * @license    GPLv2 http://files.syscp.org/misc/COPYING.txt
 * @version
 */

interface Syscp_Handler_XmlRpc_Interface extends Syscp_Handler_Interface
{
    public function setEncoding($enc = 'iso-8859-1');

    public function getEncoding();

    public function setVerbosity($verb = 'pretty');

    public function getVerbosity();

    public function doRequest($path = '', $method = '', $methodparams = '');

    public function getRawData();

    public function getDecodedData();
}


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
 * @package    Syscp.Framework.API
 * @subpackage Syscp.Handler
 * @license    GPLv2 http://files.syscp.org/misc/COPYING.txt
 * @version    $Id:admin_admins.php 460 2006-04-23 15:07:49 +0200 (So, 23 Apr 2006) martin $
 */

/**
 * @todo Extended documentation here!
 *
 * @author     Martin Burchert <eremit@syscp.org>
 * @copyright  (c) 2006, The Authors
 * @package    Syscp.Framework.API
 * @subpackage Syscp.Handler
 */
interface Syscp_Handler_Interface
{
	/**
	 * Initialisation Method
	 *
	 * This method must implement the initialisation routines of the
	 * descendant handler. It will be called during the setup of the
	 * handlers.
	 *
	 * Params is an associative array containing all needed configuration
	 * parameters of the implementing class. Please take a look at the
	 * implementation regarding a list of mandatory and optional parameters.
	 *
	 * @param array  $params  Associative array holding all parameter
	 *                        to initialize the implementation of this
	 *                        interface
	 */
	public function initialize($params = array());
}
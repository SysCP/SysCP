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
 * @subpackage Syscp.Handler.Config
 * @license    GPLv2 http://files.syscp.org/misc/COPYING.txt
 * @version    $Id:admin_admins.php 460 2006-04-23 15:07:49 +0200 (So, 23 Apr 2006) martin $
 */

// Load the required Database Handler Interface description
Syscp::uses('Syscp.Handler.Database.Interface');

/**
 * @todo Extended documentation here!
 *
 * @author     Martin Burchert <eremit@syscp.org>
 * @copyright  (c) 2006, The Authors
 * @package    Syscp.Framework.API
 * @subpackage Syscp.Handler.Config
 * @see        Syscp_Handler_Config
 */
interface Syscp_Handler_Config_Interface extends Syscp_Handler_Interface
{
	/**
	 * Returns the value of the given path or defValue if the requested
	 * value is not found.
	 *
	 * @param  string  $path      The path the requested value is stored at.
	 * @param  mixed   $defValue  The default value if the requested value
	 *                            cannot be found.
	 * @return mixed   The requested value if found, or the given default
	 *                 value.
	 */
	public function get($path, $defValue = false);
	/**
	 * Sets the given path with the given value
	 *
	 * @param  string  $path   The path the given value should be stored at.
	 * @param  mixed   $value  The value to store at the given path.
	 * @return void
	 */
	public function set($path, $value);
	/**
	 * Returns if the given path has a value assigned.
	 *
	 * @param  string   $path  The path to check for existance.
	 * @return boolean  If the path exists or not.
	 */
	public function has($path);
	/**
	 * Loads a set of pathes and values from the database using the given
	 * Syscp_Handler_Database_Interface.
	 *
	 * @param  Syscp_Handler_Database_Interface  $db  The database handler to
	 *                                                use to load  the set of
	 *                                                path/value pairs.
	 */
	public function loadFromDB(Syscp_Handler_Database_Interface $db);
}
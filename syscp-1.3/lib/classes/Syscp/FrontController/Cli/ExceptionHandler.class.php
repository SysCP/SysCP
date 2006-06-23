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
 * @subpackage Syscp.FrontController.Cli
 * @license    GPLv2 http://files.syscp.org/misc/COPYING.txt
 * @version    $Id:admin_admins.php 460 2006-04-23 15:07:49 +0200 (So, 23 Apr 2006) martin $
 */

/**
 * @todo Extended documentation here!
 *
 * @author     Martin Burchert <eremit@syscp.org>
 * @copyright  (c) 2006, The Authors
 * @package    Syscp.Framework
 * @subpackage Syscp.FrontController.Cli
 */
final class Syscp_FrontController_Cli_ExceptionHandler
{
	public static function initialize()
	{
		set_exception_handler(array(__CLASS__, 'handleException'));
	}

	public static function handleException($exception)
	{
		// lets prepare the printing of the exception

		// determine the exceptions classname
		$name = get_class($exception);

		$message = $exception->getMessage();
		$line    = $exception->getLine();
		$file    = $exception->getFile();

		// and now prepare the trace
		$trace = $exception->getTrace();
		$stack = array();
		foreach($trace as $traceElem)
		{
			$tmp = array();
			$tmp['file'] = $traceElem['file'].':'.$traceElem['line'];
			$tmp['call'] = '';
			if(isset($traceElem['class']))
			{
				$tmp['call'] .= $traceElem['class'].$traceElem['type'];
			}
			$tmp['call'] .= $traceElem['function'].'()';
			$stack[] = $tmp;
		}
		print  "Caught Exception: ".$name."\n"
		     . "============================================ \n"
		     . "Message    : ".$message."\n"
		     . "File/Line  : ".$file.':'.$line."\n"
		     . "Stacktrace : \n";
		foreach($stack as $elem)
		{
			print "  + ".$elem['call']."\n";
			print "      (".$elem['file'].")\n";
		}
		print "\n";
	}
}
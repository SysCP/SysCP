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
 * @subpackage Syscp.FrontController.Web
 * @license    GPLv2 http://files.syscp.org/misc/COPYING.txt
 * @version    $Id:admin_admins.php 460 2006-04-23 15:07:49 +0200 (So, 23 Apr 2006) martin $
 */

/**
 * @todo Extended documentation here!
 *
 * @author     Martin Burchert <eremit@syscp.org>
 * @copyright  (c) 2006, The Authors
 * @package    Syscp.Framework
 * @subpackage Syscp.FrontController.Web
 */

final class Syscp_FrontController_Web_ExceptionHandler
{
    public static function initialize()
    {
        set_exception_handler(array(
            __CLASS__,
            'handleException'
        ));
    }

    public static function handleException($exception)
    {
        // lets prepare the printing of the exception
        // determine the exceptions classname

        $name = get_class($exception);
        $message = $exception->getMessage();
        $line = $exception->getLine();
        $file = $exception->getFile();

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
                $tmp['call'].= $traceElem['class'].$traceElem['type'];
            }

            $tmp['call'].= $traceElem['function'].'()';
            $stack[] = $tmp;
        }

?>
<html>
	<head>
		<title>SysCP Exception</title>
		<style type="text/css">
			body {
				margin: 0px;
				padding: 0px;
				width: 100%;
				font-family: Verdana, Arial, Helvetica, sans-serif;
			}
			h1 {
				margin: 5px 10px;
			}
			table.box {
				border-collapse: collapse;
				border: 1px solid black;
				width: 100%;
			}
			table.box td {
				border: 1px solid black;
				padding: 5px;
			}
			table.box td.label {
				font-weight: bold;
				background-color: #69C;
			}
			table.box td.data {
				background-color: #CCC;
			}
			table.trace {
				width: 100%;
				border-collapse: collapse;
				border: 1px solid black;
			}
			table.trace td.label {
				font-size: 80%;
				padding: 2px;
				background-color: #C96;
				text-align: center;
			}
			table.trace td.data {
				font-family: Courier, monospace;
				font-size: 80%;
				padding: 2px;
			}
		</style>
	</head>
	<body>
		<h1>SysCP - <?php echo $name; ?></h1>
		<table class="box">
			<tr>
				<td class="label">Message:</td>
				<td class="data" colspan="3"><?php echo $message; ?></td>
			</tr>
			<tr>
				<td class="label">File:</td>
				<td class="data"><?php echo $file; ?></td>
				<td class="label">Line:</td>
				<td class="data"><?php echo $line; ?></td>
			</tr>
			<tr>
				<td class="label" colspan="4">Stack Trace:</td>
			</tr>
			<tr>
				<td class="data" colspan="4">
				<table class="trace">
					<tr>
						<td class="label">Method/Function Call</td>
						<td class="label">File:Line</td>
					</tr>
			<?php foreach($stack as $elem): ?>
				<tr>
					<td class="data"><?php echo $elem['call']; ?></td>
					<td class="data"><?php echo $elem['file']; ?></td>
				</tr>
			<?php
        endforeach

?>
				</table>
				</td>
			</tr>
		</table>
	</body>
</html>
		<?php
    }
}


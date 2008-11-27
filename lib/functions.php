<?php

/**
 * This file is part of the SysCP project.
 * Copyright (c) 2003-2008 the SysCP Team (see authors).
 *
 * For the full copyright and license information, please view the COPYING
 * file that was distributed with this source code. You can also view the
 * COPYING file online at http://files.syscp.org/misc/COPYING.txt
 *
 * @copyright  (c) the authors
 * @author     Florian Lippert <flo@syscp.org>
 * @license    GPLv2 http://files.syscp.org/misc/COPYING.txt
 * @package    Functions
 * @version    $Id$
 */

/**
 * Get template from filesystem
 *
 * @param string Templatename
 * @param string noarea If area should be used to get template
 * @return string The Template
 * @author Florian Lippert <flo@syscp.org>
 */

function getProcessId($value = user)
{
	if($value == 'user')
	{
		$processUser = posix_getpwuid(posix_geteuid());

		if(empty($process))
		{
			$process = array();
			$sys = array();
			@exec("whoami", $sys);

			if(isset($sys[0]))
			{
				$process['name'] = $sys[0];
			}
		}
	}
	elseif($value == 'group')
	{
		$process = posix_getpwuid(posix_geteuid());

		if(empty($process))
		{
			$process = array();
			$sys = array();
			@exec("whoami", $sys);

			if(isset($sys[0]))
			{
				$process['name'] = $sys[0];
			}
		}
	}

	return $process[name];
}

function getTemplate($template, $noarea = 0)
{
	global $templatecache;

	if($noarea != 1)
	{
		$template = AREA . '/' . $template;
	}

	if(!isset($templatecache[$template]))
	{
		$filename = './templates/' . $template . '.tpl';

		if(file_exists($filename)
		   && is_readable($filename))
		{
			$templatefile = addcslashes(file_get_contents($filename), '"\\');

			// loop through template more than once in case we have an "if"-statement in another one

			while(preg_match('/<if[ \t]*(.*)>(.*)(<\/if>|<else>(.*)<\/if>)/Uis', $templatefile))
			{
				$templatefile = preg_replace('/<if[ \t]*(.*)>(.*)(<\/if>|<else>(.*)<\/if>)/Uis', '".( ($1) ? ("$2") : ("$4") )."', $templatefile);
			}
		}
		else
		{
			$templatefile = 'TEMPLATE NOT FOUND: ' . $filename;
		}

		$templatecache[$template] = $templatefile;
	}

	return $templatecache[$template];
}

/**
 * Prints one ore more errormessages on screen
 *
 * @param array Errormessages
 * @param string A %s in the errormessage will be replaced by this string.
 * @author Florian Lippert <flo@syscp.org>
 * @author Ron Brand <ron.brand@web.de>
 */

function standard_error($errors = '', $replacer = '')
{
	global $db, $tpl, $userinfo, $s, $header, $footer, $lng;
	$replacer = htmlentities($replacer);

	if(!is_array($errors))
	{
		$errors = array(
			$errors
		);
	}

	$error = '';
	foreach($errors as $single_error)
	{
		if(isset($lng['error'][$single_error]))
		{
			$single_error = $lng['error'][$single_error];
			$single_error = strtr($single_error, array(
				'%s' => $replacer
			));
		}
		else
		{
			$error = 'Unknown Error (' . $single_error . ')';
			break;
		}

		if(empty($error))
		{
			$error = $single_error;
		}
		else
		{
			$error.= ' ' . $single_error;
		}
	}

	eval("echo \"" . getTemplate('misc/error', '1') . "\";");
	exit;
}

/**
 * Returns HTML Code for two radio buttons with two choices: yes and no
 *
 * @param string Name of HTML-Variable
 * @param string Value which will be returned if user chooses yes
 * @param string Value which will be returned if user chooses no
 * @param string Value which is chosen by default
 * @return string HTML Code
 * @author Florian Lippert <flo@syscp.org>
 */

function makeyesno($name, $yesvalue, $novalue = '', $yesselected = '')
{
	global $lng;
	return '<select class="dropdown_noborder" name="' . $name . '"><option value="' . $yesvalue . '"' . ($yesselected ? ' selected="selected"' : '') . '>' . $lng['panel']['yes'] . '</option><option value="' . $novalue . '"' . ($yesselected ? '' : ' selected="selected"') . '>' . $lng['panel']['no'] . '</option></select>';
}

/**
 * Prints Question on screen
 *
 * @param string The question
 * @param string File which will be called with POST if user clicks yes
 * @param array Values which will be given to $yesfile. Format: array(variable1=>value1, variable2=>value2, variable3=>value3)
 * @param string Name of the target eg Domain or eMail address etc.
 * @author Florian Lippert <flo@syscp.org>
 */

function ask_yesno($text, $yesfile, $params = array(), $targetname = '')
{
	global $userinfo, $tpl, $db, $s, $header, $footer, $lng;

	/*
		// For compatibility reasons (if $params contains a string like "field1=value1;field2=value2") this will convert it into a usable array
		if(!is_array($params))
		{
			$params_tmp=explode(';',$params);
			unset($params);
			$params=array();
			while(list(,$param_tmp)=each($params_tmp))
			{
				$param_tmp=explode('=',$param_tmp);
				$params[$param_tmp[0]]=$param_tmp[1];
			}
		}
*/

	$hiddenparams = '';

	if(is_array($params))
	{
		foreach($params as $field => $value)
		{
			$hiddenparams.= '<input type="hidden" name="' . htmlspecialchars($field) . '" value="' . htmlspecialchars($value) . '" />' . "\n";
		}
	}

	if(isset($lng['question'][$text]))
	{
		$text = $lng['question'][$text];
	}

	$text = strtr($text, array(
		'%s' => $targetname
	));
	eval("echo \"" . getTemplate('misc/question_yesno', '1') . "\";");
}

/**
 * Return HTML Code for an option within a <select>
 *
 * @param string The caption
 * @param string The Value which will be returned
 * @param string Values which will be selected by default.
 * @param bool Whether the title may contain html or not
 * @param bool Whether the value may contain html or not
 * @return string HTML Code
 * @author Florian Lippert <flo@syscp.org>
 */

function makeoption($title, $value, $selvalue = NULL, $title_trusted = false, $value_trusted = false)
{
	if($selvalue !== NULL
	   && ((is_array($selvalue) && in_array($value, $selvalue)) || $value == $selvalue))
	{
		$selected = 'selected="selected"';
	}
	else
	{
		$selected = '';
	}

	if(!$title_trusted)
	{
		$title = htmlspecialchars($title);
	}

	if(!$value_trusted)
	{
		$value = htmlspecialchars($value);
	}

	$option = '<option value="' . $value . '" ' . $selected . ' >' . $title . '</option>';
	return $option;
}

/**
 * Return HTML Code for a checkbox
 *
 * @param string The fieldname
 * @param string The captions
 * @param string The Value which will be returned
 * @param bool Add a <br /> at the end of the checkbox
 * @param string Values which will be selected by default
 * @param bool Whether the title may contain html or not
 * @param bool Whether the value may contain html or not
 * @return string HTML Code
 * @author Michael Kaufmann <mkaufmann@nutime.de>
 */

function makecheckbox($name, $title, $value, $break = false, $selvalue = NULL, $title_trusted = false, $value_trusted = false)
{
	if($selvalue !== NULL
	   && $value == $selvalue)
	{
		$checked = 'checked="checked"';
	}
	else
	{
		$checked = '';
	}

	if(!$title_trusted)
	{
		$title = htmlspecialchars($title);
	}

	if(!$value_trusted)
	{
		$value = htmlspecialchars($value);
	}

	$checkbox = '<input type="checkbox" name="' . $name . '" value="' . $value . '" ' . $checked . ' />&nbsp;' . $title;

	if($break)
	{
		$checkbox.= '<br />';
	}

	return $checkbox;
}

/**
 * Sends an header ( 'Location ...' ) to the browser.
 *
 * @param   string   Destination
 * @param   array    Get-Variables
 * @param   boolean  if the target we are creating for a redirect
 *                   should be a relative or an absolute url
 *
 * @return  boolean  false if params is not an array
 *
 * @author  Florian Lippert <flo@syscp.org>
 * @author  Martin Burchert <eremit@syscp.org>
 *
 * @changes martin@2005-01-29
 *          - added isRelative parameter
 *          - speed up the url generation
 *          - fixed bug #91
 */

function redirectTo($destination, $get_variables = array(), $isRelative = false)
{
	$params = array();

	if(is_array($get_variables))
	{
		foreach($get_variables as $key => $value)
		{
			$params[] = urlencode($key) . '=' . urlencode($value);
		}

		$params = '?' . implode($params, '&');

		if($isRelative)
		{
			$protocol = '';
			$host = '';
			$path = './';
		}
		else
		{
			if(isset($_SERVER['HTTPS'])
			   && strtolower($_SERVER['HTTPS']) == 'on')
			{
				$protocol = 'https://';
			}
			else
			{
				$protocol = 'http://';
			}

			$host = $_SERVER['HTTP_HOST'];

			if(dirname($_SERVER['PHP_SELF']) == '/')
			{
				$path = '/';
			}
			else
			{
				$path = dirname($_SERVER['PHP_SELF']) . '/';
			}
		}

		header('Location: ' . $protocol . $host . $path . $destination . $params);
		exit;
	}
	elseif($get_variables == null)
	{
		header('Location: ' . $destination);
		exit;
	}

	return false;
}

/**
 * Returns Array, whose elements have been checked whether thay are empty or not
 *
 * @param array The array to trim
 * @return array The trim'med array
 * @author Florian Lippert <flo@syscp.org>
 */

function array_trim($source)
{
	$returnval = array();

	if(is_array($source))
	{
		while(list($var, $val) = each($source))
		{
			if($val != ' '
			   && $val != '')$returnval[$var] = $val;
		}
	}
	else
	{
		$returnval = $source;
	}

	return $returnval;
}

/**
 * Replaces Strings in an array, with the advantage that you
 * can select which fields should be str_replace'd
 *
 * @param mixed String or array of strings to search for
 * @param mixed String or array to replace with
 * @param array The subject array
 * @param string The fields which should be checked for, separated by spaces
 * @return array The str_replace'd array
 * @author Florian Lippert <flo@syscp.org>
 */

function str_replace_array($search, $replace, $subject, $fields = '')
{
	if(is_array($subject))
	{
		$fields = array_trim(explode(' ', $fields));
		foreach($subject as $field => $value)
		{
			if((!is_array($fields) || empty($fields))
			   || (is_array($fields) && !empty($fields) && in_array($field, $fields)))
			{
				$subject[$field] = str_replace($search, $replace, $subject[$field]);
			}
		}
	}
	else
	{
		$subject = str_replace($search, $replace, $subject);
	}

	return $subject;
}

/**
 * Wrapper around htmlentities to handle arrays, with the advantage that you
 * can select which fields should be handled by htmlentities
 *
 * @param array The subject array
 * @param string The fields which should be checked for, separated by spaces
 * @param int See php documentation about this
 * @param string See php documentation about this
 * @return array The array with htmlentitie'd strings
 * @author Florian Lippert <flo@syscp.org>
 */

function htmlentities_array($subject, $fields = '', $quote_style = ENT_COMPAT, $charset = 'ISO-8859-1')
{
	if(is_array($subject))
	{
		if(!is_array($fields))
		{
			$fields = array_trim(explode(' ', $fields));
		}

		foreach($subject as $field => $value)
		{
			if((!is_array($fields) || empty($fields))
			   || (is_array($fields) && !empty($fields) && in_array($field, $fields)))
			{
				/**
				 * Just call ourselve to manage multi-dimensional arrays
				 */

				$subject[$field] = htmlentities_array($subject[$field], $fields, $quote_style, $charset);
			}
		}
	}
	else
	{
		$subject = htmlentities($subject, $quote_style, $charset);
	}

	return $subject;
}

/**
 * Wrapper around html_entity_decode to handle arrays, with the advantage that you
 * can select which fields should be handled by htmlentities and with advantage,
 * that you can eliminate all html entities by setting complete=true
 *
 * @param array The subject array
 * @param string The fields which should be checked for, separated by spaces
 * @param bool Select true to use html_entity_decode_complete instead of html_entity_decode
 * @param int See php documentation about this
 * @param string See php documentation about this
 * @return array The array with html_entity_decode'd strings
 * @author Florian Lippert <flo@syscp.org>
 */

function html_entity_decode_array($subject, $fields = '', $complete = false, $quote_style = ENT_COMPAT, $charset = 'ISO-8859-1')
{
	if(is_array($subject))
	{
		if(!is_array($fields))
		{
			$fields = array_trim(explode(' ', $fields));
		}

		foreach($subject as $field => $value)
		{
			if((!is_array($fields) || empty($fields))
			   || (is_array($fields) && !empty($fields) && in_array($field, $fields)))
			{
				/**
				 * Just call ourselve to manage multi-dimensional arrays
				 */

				$subject[$field] = html_entity_decode_array($subject[$field], $fields, $complete, $quote_style, $charset);
			}
		}
	}
	else
	{
		if($complete == true)
		{
			$subject = html_entity_decode_complete($subject, $quote_style, $charset);
		}
		else
		{
			$subject = html_entity_decode($subject, $quote_style, $charset);
		}
	}

	return $subject;
}

/**
 * Wrapper around stripslashes to handle arrays, with the advantage that you
 * can select which fields should be handled by htmlentities and with advantage,
 * that you can eliminate all slashes by setting complete=true
 *
 * @param array The subject array
 * @param int See php documentation about this
 * @param string See php documentation about this
 * @param string The fields which should be checked for, separated by spaces
 * @param bool Select true to use stripslashes_complete instead of stripslashes
 * @return array The array with stripslashe'd strings
 * @author Florian Lippert <flo@syscp.org>
 */

function stripslashes_array($subject, $fields = '', $complete = false)
{
	if(is_array($subject))
	{
		if(!is_array($fields))
		{
			$fields = array_trim(explode(' ', $fields));
		}

		foreach($subject as $field => $value)
		{
			if((!is_array($fields) || empty($fields))
			   || (is_array($fields) && !empty($fields) && in_array($field, $fields)))
			{
				/**
				 * Just call ourselve to manage multi-dimensional arrays
				 */

				$subject[$field] = stripslashes_array($subject[$field], $fields, $complete);
			}
		}
	}
	else
	{
		if($complete == true)
		{
			$subject = stripslashes_complete($subject);
		}
		else
		{
			$subject = stripslashes($subject);
		}
	}

	return $subject;
}

/**
 * Returns if an username is in correct format or not.
 *
 * @param string The username to check
 * @return bool Correct or not
 * @author Michael Duergner <michael@duergner.com>
 *
 * @changes Backported regex from SysCP 1.3 (lib/classes/Syscp/Handler/Validation.class.php)
 */

function validateUsername($username, $unix_names = 1, $mysql_max = '')
{
	if($unix_names == 0)
	{
		if(strpos($username, '--') === false)
		{
			return preg_match('/^[a-z][a-z0-9\-_]{1,' . (int)($mysql_max - 1) . '}[a-z0-9]{1}$/Di', $username);
		}
		else
		{
			return false;
		}
	}
	else
	{
		return preg_match('/^[a-z][a-z0-9]{1,' . $mysql_max . '}$/Di', $username);
	}
}

/**
 * Returns if an emailaddress is in correct format or not
 *
 * @param string The email address to check
 * @return bool Correct or not
 * @author Florian Lippert <flo@syscp.org>
 *
 * @changes Backported regex from SysCP 1.3 (lib/classes/Syscp/Handler/Validation.class.php)
 */

function validateEmail($email)
{
	$email = strtolower($email);
	return filter_var($email, FILTER_VALIDATE_EMAIL);
}

/**
 * Check if the submitted string is a valid domainname, i.e.
 * it consists only of the following characters ([a-z0-9][a-z0-9\-]+\.)+[a-z]{2,4}
 *
 * @param string The domainname which should be checked.
 * @return boolean True if the domain is valid, false otherwise
 * @author Michael Duergner
 *
 */

function validateDomain($domainname)
{
	// we add http:// because this makes a domain valid for the filter;
	// but if a user gives "http://" it's not a valid domain
	// (because for syscp, a domain mustn't have "http://" in it

	$domainname_tmp = 'http://' . $domainname;

	if(filter_var($domainname_tmp, FILTER_VALIDATE_URL) !== false)
	{
		return $domainname;
	}
	else
	{
		return false;
	}
}

/**
 * Returns whether a URL is in a correct format or not
 *
 * @param string URL to be tested
 * @return bool
 * @author Christian Hoffmann
 *
 */

function validateUrl($url)
{
	if(strtolower(substr($url, 0, 7)) != "http://"
	   && strtolower(substr($url, 0, 8)) != "https://")
	{
		$url = 'http://' . $url;
	}

	if(filter_var($url, FILTER_VALIDATE_URL, FILTER_FLAG_SCHEME_REQUIRED) !== false)
	{
		return true;
	}
	else
	{
		if(strtolower(substr($url, 0, 7)) == "http://"
		   || strtolower(substr($url, 0, 8)) == "https://")
		{
			if(strtolower(substr($url, 0, 7)) == "http://")
			{
				$ip = strtolower(substr($url, 7));
			}

			if(strtolower(substr($url, 0, 8)) == "https://")
			{
				$ip = strtolower(substr($url, 8));
			}

			$ip = substr($ip, 0, strpos($ip, '/'));

			if(validate_ip($ip, true) !== false)
			{
				return true;
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}
	}
}

/**
 * Validates the given string by matching against the pattern, prints an error on failure and exits
 *
 * @param string $str the string to be tested (user input)
 * @param string the $fieldname to be used in error messages
 * @param string $pattern the regular expression to be used for testing
 * @param string language id for the error
 * @return string the clean string
 *
 * If the default pattern is used and the string does not match, we try to replace the
 * 'bad' values and log the action.
 *
 */

function validate($str, $fieldname, $pattern = '', $lng = '', $emptydefault = array())
{
	global $log;

	if(!is_array($emptydefault))
	{
		$emptydefault_array = array($emptydefault);
		unset($emptydefault);
		$emptydefault = $emptydefault_array;
		unset($emptydefault_array);
	}

	// Check if the $str is one of the values which represent the default for an 'empty' value

	if(is_array($emptydefault) && !empty($emptydefault) && in_array($str, $emptydefault) && isset($emptydefault[0]))
	{
		return $emptydefault[0];
	}

	if($pattern == '')
	{
		$pattern = '/^[^\r\n\t\f\0]*$/D';

		if(!preg_match($pattern, $str))
		{
			// Allows letters a-z, digits, space (\\040), hyphen (\\-), underscore (\\_) and backslash (\\\\),
			// everything else is removed from the string.

			$allowed = "/[^a-z0-9\\040\\.\\-\\_\\\\]/i";
			preg_replace($allowed, "", $str);
			$log->logAction(null, LOG_WARNING, "cleaned bad formatted string (" . $str . ")");
		}
	}

	if(preg_match($pattern, $str))
	{
		return $str;
	}

	if($lng == '')
	{
		$lng = 'stringformaterror';
	}

	standard_error($lng, $fieldname);
	exit;
}

/**
 * Inserts a task into the PANEL_TASKS-Table
 *
 * @param int Type of task
 * @param string Parameter 1
 * @param string Parameter 2
 * @param string Parameter 3
 * @author Florian Lippert <flo@syscp.org>
 */

function inserttask($type, $param1 = '', $param2 = '', $param3 = '')
{
	global $db, $settings;

	if($type == '1'
	   || $type == '3'
	   || $type == '4'
	   || $type == '5')
	{
		$db->query('DELETE FROM `' . TABLE_PANEL_TASKS . '` WHERE `type`="' . $type . '"');
		$db->query('INSERT INTO `' . TABLE_PANEL_TASKS . '` (`type`) VALUES ("' . $type . '")');
		$doupdate = true;
	}
	elseif($type == '2'
	       && $param1 != ''
	       && $param2 != ''
	       && $param3 != '')
	{
		$data = Array();
		$data['loginname'] = $param1;
		$data['uid'] = $param2;
		$data['gid'] = $param3;
		$data = serialize($data);
		$db->query('INSERT INTO `' . TABLE_PANEL_TASKS . '` (`type`, `data`) VALUES ("2", "' . $db->escape($data) . '")');
		$doupdate = true;
	}

	// Taken from https://wiki.syscp.org/contrib/realtime

	if($doupdate === true && (int)$settings['system']['realtime_port'] !== 0)
	{
		$timeout = 15;
		$socket = @socket_create (AF_INET, SOCK_STREAM, SOL_UDP);
		if($socket !== false) {
			$time = time();
			while (!@socket_connect($socket, '127.0.0.1', (int)$settings['system']['realtime_port']))
			{
				$err = socket_last_error($socket);
				if ($err == 115 || $err == 114)
				{
					if ((time() - $time) >= $timeout)
					{
						break;
					}
					sleep(1);
					continue;
				}
			}
			@socket_close ($socket);
		}
	}
}

/**
 * Function which returns a secure path, means to remove all multiple dots and slashes
 *
 * @param string The path
 * @return string The corrected path
 * @author Florian Lippert <flo@syscp.org>
 */

function makeSecurePath($path)
{
	$search = Array(
		'#/+#',
		'#\.+#',
		'#\0+#'
	);
	$replace = Array(
		'/',
		'.',
		''
	);
	$path = preg_replace($search, $replace, $path);
	return $path;
}

/**
 * Function which returns a correct dirname, means to add slashes at the beginning and at the end if there weren't some
 *
 * @param string The dirname
 * @return string The corrected dirname
 * @author Florian Lippert <flo@syscp.org>
 */

function makeCorrectDir($dir)
{
	if(substr($dir, -1, 1) != '/')
	{
		$dir.= '/';
	}

	if(substr($dir, 0, 1) != '/')
	{
		$dir = '/' . $dir;
	}

	$dir = makeSecurePath($dir);
	return $dir;
}

/**
 * Function which returns a correct filename, means to add a slash at the beginning if there wasn't one
 *
 * @param string filename the filename
 * @return string the corrected filename
 * @author Florian Lippert <flo@syscp.org>
 * @author Michael Russ <mr@edvruss.com>
 * @author Martin Burchert <eremit@adm1n.de>
 */

function makeCorrectFile($filename)
{
	if(substr($filename, 0, 1) != '/')
	{
		$filename = '/' . $filename;
	}

	$filename = makeSecurePath($filename);
	return $filename;
}

/**
 * Function which returns a correct destination for Postfix Virtual Table
 *
 * @param string The destinations
 * @return string the corrected destinations
 * @author Florian Lippert <flo@syscp.org>
 */

function makeCorrectDestination($destination)
{
	$search = '/ +/';
	$replace = ' ';
	$destination = preg_replace($search, $replace, $destination);

	if(substr($destination, 0, 1) == ' ')
	{
		$destination = substr($destination, 1);
	}

	if(substr($destination, -1, 1) == ' ')
	{
		$destination = substr($destination, 0, strlen($destination) - 1);
	}

	return $destination;
}

/**
 * Function which updates all counters of used ressources in panel_admins and panel_customers
 * @param bool Set to true to get an array with debug information
 * @return array Contains debug information if parameter 'returndebuginfo' is set to true
 *
 * @author Florian Lippert <flo@syscp.org>
 */

function updateCounters($returndebuginfo = false)
{
	global $db;
	$returnval = array();

	if($returndebuginfo === true)
	{
		$returnval = array(
			'admins' => array(),
			'customers' => array()
		);
	}

	$admin_resources = array();

	// Customers

	$customers = $db->query('SELECT * FROM `' . TABLE_PANEL_CUSTOMERS . '` ORDER BY `customerid`');

	while($customer = $db->fetch_array($customers))
	{
		if(!isset($admin_resources[$customer['adminid']]))
		{
			$admin_resources[$customer['adminid']] = Array();
		}

		if(!isset($admin_resources[$customer['adminid']]['diskspace_used']))
		{
			$admin_resources[$customer['adminid']]['diskspace_used'] = 0;
		}

		if(($customer['diskspace'] / 1024) != '-1')
		{
			$admin_resources[$customer['adminid']]['diskspace_used']+= intval_ressource($customer['diskspace']);
		}

		if(!isset($admin_resources[$customer['adminid']]['traffic_used']))
		{
			$admin_resources[$customer['adminid']]['traffic_used'] = 0;
		}

		$admin_resources[$customer['adminid']]['traffic_used']+= $customer['traffic_used'];

		if(!isset($admin_resources[$customer['adminid']]['mysqls_used']))
		{
			$admin_resources[$customer['adminid']]['mysqls_used'] = 0;
		}

		if($customer['mysqls'] != '-1')
		{
			$admin_resources[$customer['adminid']]['mysqls_used']+= intval_ressource($customer['mysqls']);
		}

		if(!isset($admin_resources[$customer['adminid']]['ftps_used']))
		{
			$admin_resources[$customer['adminid']]['ftps_used'] = 0;
		}

		if($customer['ftps'] != '-1')
		{
			$admin_resources[$customer['adminid']]['ftps_used']+= intval_ressource($customer['ftps']);
		}

		if(!isset($admin_resources[$customer['adminid']]['tickets_used']))
		{
			$admin_resources[$customer['adminid']]['tickets_used'] = 0;
		}

		if($customer['tickets'] != '-1')
		{
			$admin_resources[$customer['adminid']]['tickets_used']+= intval_ressource($customer['tickets']);
		}

		if(!isset($admin_resources[$customer['adminid']]['emails_used']))
		{
			$admin_resources[$customer['adminid']]['emails_used'] = 0;
		}

		if($customer['emails'] != '-1')
		{
			$admin_resources[$customer['adminid']]['emails_used']+= intval_ressource($customer['emails']);
		}

		if(!isset($admin_resources[$customer['adminid']]['email_accounts_used']))
		{
			$admin_resources[$customer['adminid']]['email_accounts_used'] = 0;
		}

		if($customer['email_accounts'] != '-1')
		{
			$admin_resources[$customer['adminid']]['email_accounts_used']+= intval_ressource($customer['email_accounts']);
		}

		if(!isset($admin_resources[$customer['adminid']]['email_forwarders_used']))
		{
			$admin_resources[$customer['adminid']]['email_forwarders_used'] = 0;
		}

		if($customer['email_forwarders'] != '-1')
		{
			$admin_resources[$customer['adminid']]['email_forwarders_used']+= intval_ressource($customer['email_forwarders']);
		}

		if(!isset($admin_resources[$customer['adminid']]['subdomains_used']))
		{
			$admin_resources[$customer['adminid']]['subdomains_used'] = 0;
		}

		if($customer['subdomains'] != '-1')
		{
			$admin_resources[$customer['adminid']]['subdomains_used']+= intval_ressource($customer['subdomains']);
		}

		$customer_mysqls = $db->query_first('SELECT COUNT(*) AS `number_mysqls` FROM `' . TABLE_PANEL_DATABASES . '` WHERE `customerid` = "' . (int)$customer['customerid'] . '"');
		$customer['mysqls_used_new'] = $customer_mysqls['number_mysqls'];
		$customer_emails = $db->query_first('SELECT COUNT(*) AS `number_emails` FROM `' . TABLE_MAIL_VIRTUAL . '` WHERE `customerid` = "' . (int)$customer['customerid'] . '"');
		$customer['emails_used_new'] = $customer_emails['number_emails'];
		$customer_emails_result = $db->query('SELECT `email`, `email_full`, `destination`, `popaccountid` AS `number_email_forwarders` FROM `' . TABLE_MAIL_VIRTUAL . '` WHERE `customerid` = "' . (int)$customer['customerid'] . '" ');
		$customer_email_forwarders = 0;
		$customer_email_accounts = 0;

		while($customer_emails_row = $db->fetch_array($customer_emails_result))
		{
			if($customer_emails_row['destination'] != '')
			{
				$customer_emails_row['destination'] = explode(' ', makeCorrectDestination($customer_emails_row['destination']));
				$customer_email_forwarders+= count($customer_emails_row['destination']);

				if(in_array($customer_emails_row['email_full'], $customer_emails_row['destination']))
				{
					$customer_email_forwarders-= 1;
					$customer_email_accounts++;
				}
			}
		}

		$customer['email_accounts_used_new'] = $customer_email_accounts;
		$customer['email_forwarders_used_new'] = $customer_email_forwarders;
		$customer_ftps = $db->query_first('SELECT COUNT(*) AS `number_ftps` FROM `' . TABLE_FTP_USERS . '` WHERE `customerid` = "' . (int)$customer['customerid'] . '"');
		$customer['ftps_used_new'] = ($customer_ftps['number_ftps'] - 1);
		$customer_tickets = $db->query_first('SELECT COUNT(*) AS `number_tickets` FROM `' . TABLE_PANEL_TICKETS . '` WHERE `answerto` = "0" AND `customerid` = "' . (int)$customer['customerid'] . '"');
		$customer['tickets_used_new'] = $customer_tickets['number_tickets'];
		$customer_subdomains = $db->query_first('SELECT COUNT(*) AS `number_subdomains` FROM `' . TABLE_PANEL_DOMAINS . '` WHERE `customerid` = "' . (int)$customer['customerid'] . '" AND `parentdomainid` <> "0"');
		$customer['subdomains_used_new'] = $customer_subdomains['number_subdomains'];
		$db->query('UPDATE `' . TABLE_PANEL_CUSTOMERS . '` SET `mysqls_used` = "' . (int)$customer['mysqls_used_new'] . '",  `emails_used` = "' . (int)$customer['emails_used_new'] . '",  `email_accounts_used` = "' . (int)$customer['email_accounts_used_new'] . '",  `email_forwarders_used` = "' . (int)$customer['email_forwarders_used_new'] . '",  `ftps_used` = "' . (int)$customer['ftps_used_new'] . '",   `tickets_used` = "' . (int)$customer['tickets_used_new'] . '",  `subdomains_used` = "' . (int)$customer['subdomains_used_new'] . '" WHERE `customerid` = "' . (int)$customer['customerid'] . '"');

		if($returndebuginfo === true)
		{
			$returnval['customers'][$customer['customerid']] = $customer;
		}
	}

	// Admins

	$admins = $db->query('SELECT * FROM `' . TABLE_PANEL_ADMINS . '` ORDER BY `adminid`');

	while($admin = $db->fetch_array($admins))
	{
		$admin_customers = $db->query_first('SELECT COUNT(*) AS `number_customers` FROM `' . TABLE_PANEL_CUSTOMERS . '` WHERE `adminid` = "' . (int)$admin['adminid'] . '"');
		$admin['customers_used_new'] = $admin_customers['number_customers'];
		$admin_domains = $db->query_first('SELECT COUNT(*) AS `number_domains` FROM `' . TABLE_PANEL_DOMAINS . '` WHERE `adminid` = "' . (int)$admin['adminid'] . '" AND `isemaildomain` = "1"');
		$admin['domains_used_new'] = $admin_domains['number_domains'];

		if(!isset($admin_resources[$admin['adminid']]))
		{
			$admin_resources[$admin['adminid']] = Array();
		}

		if(!isset($admin_resources[$admin['adminid']]['diskspace_used']))
		{
			$admin_resources[$admin['adminid']]['diskspace_used'] = 0;
		}

		$admin['diskspace_used_new'] = $admin_resources[$admin['adminid']]['diskspace_used'];

		if(!isset($admin_resources[$admin['adminid']]['traffic_used']))
		{
			$admin_resources[$admin['adminid']]['traffic_used'] = 0;
		}

		$admin['traffic_used_new'] = $admin_resources[$admin['adminid']]['traffic_used'];

		if(!isset($admin_resources[$admin['adminid']]['mysqls_used']))
		{
			$admin_resources[$admin['adminid']]['mysqls_used'] = 0;
		}

		$admin['mysqls_used_new'] = $admin_resources[$admin['adminid']]['mysqls_used'];

		if(!isset($admin_resources[$admin['adminid']]['ftps_used']))
		{
			$admin_resources[$admin['adminid']]['ftps_used'] = 0;
		}

		$admin['ftps_used_new'] = $admin_resources[$admin['adminid']]['ftps_used'];

		if(!isset($admin_resources[$admin['adminid']]['emails_used']))
		{
			$admin_resources[$admin['adminid']]['emails_used'] = 0;
		}

		if(!isset($admin_resources[$admin['adminid']]['tickets_used']))
		{
			$admin_resources[$admin['adminid']]['tickets_used'] = 0;
		}

		$admin['tickets_used_new'] = $admin_resources[$admin['adminid']]['tickets_used'];
		$admin['emails_used_new'] = $admin_resources[$admin['adminid']]['emails_used'];

		if(!isset($admin_resources[$admin['adminid']]['email_accounts_used']))
		{
			$admin_resources[$admin['adminid']]['email_accounts_used'] = 0;
		}

		$admin['email_accounts_used_new'] = $admin_resources[$admin['adminid']]['email_accounts_used'];

		if(!isset($admin_resources[$admin['adminid']]['email_forwarders_used']))
		{
			$admin_resources[$admin['adminid']]['email_forwarders_used'] = 0;
		}

		$admin['email_forwarders_used_new'] = $admin_resources[$admin['adminid']]['email_forwarders_used'];

		if(!isset($admin_resources[$admin['adminid']]['subdomains_used']))
		{
			$admin_resources[$admin['adminid']]['subdomains_used'] = 0;
		}

		$admin['subdomains_used_new'] = $admin_resources[$admin['adminid']]['subdomains_used'];
		$db->query('UPDATE `' . TABLE_PANEL_ADMINS . '` SET `customers_used` = "' . (int)$admin['customers_used_new'] . '",  `domains_used` = "' . (int)$admin['domains_used_new'] . '",  `diskspace_used` = "' . (int)$admin['diskspace_used_new'] . '",  `mysqls_used` = "' . (int)$admin['mysqls_used_new'] . '",  `emails_used` = "' . (int)$admin['emails_used_new'] . '",  `email_accounts_used` = "' . (int)$admin['email_accounts_used_new'] . '",  `email_forwarders_used` = "' . (int)$admin['email_forwarders_used_new'] . '",  `ftps_used` = "' . (int)$admin['ftps_used_new'] . '",  `tickets_used` = "' . (int)$admin['tickets_used_new'] . '",  `subdomains_used` = "' . (int)$admin['subdomains_used_new'] . '",  `traffic_used` = "' . (int)$admin['traffic_used_new'] . '" WHERE `adminid` = "' . (int)$admin['adminid'] . '"');

		if($returndebuginfo === true)
		{
			$returnval['admins'][$admin['adminid']] = $admin;
		}
	}

	return $returnval;
}

/**
 * Wrapper around the exec command.
 *
 * @author Martin Burchert <eremit@adm1n.de>
 * @version 1.2
 * @param string exec_string String to be executed
 * @return string The result of the exec()
 *
 * History:
 * 1.0 : Initial Version
 * 1.1 : Added |,&,>,<,`,*,$,~,? as security breaks.
 * 1.2 : Removed * as security break
 */

function safe_exec($exec_string, &$return_value = false)
{
	global $settings;

	//
	// define allowed system commands
	//

	$allowed_commands = array(
		'touch',
		'chown',
		'mkdir',
		'webalizer',
		'cp',
		'du',
		'chmod',
		'chattr',
		$settings['system']['apachereload_command'],
		$settings['system']['bindreload_command'],
		$settings['dkim']['dkimrestart_command'],
		$settings['system']['awstats_updateall_command'],
		'openssl',
		'unzip',
		'php'
	);

	//
	// check for ; in execute command
	//

	if((stristr($exec_string, ';'))
	   or (stristr($exec_string, '|'))
	   or (stristr($exec_string, '&'))
	   or (stristr($exec_string, '>'))
	   or (stristr($exec_string, '<'))
	   or (stristr($exec_string, '`'))
	   or (stristr($exec_string, '$'))
	   or (stristr($exec_string, '~'))
	   or (stristr($exec_string, '?')))
	{
		die('SECURITY CHECK FAILED!' . "\n" . 'The execute string "' . htmlspecialchars($exec_string) . '" is a possible security risk!' . "\n" . 'Please check your whole server for security problems by hand!' . "\n");
	}

	//
	// check if command is allowed here
	//

	$ok = false;
	foreach($allowed_commands as $allowed_command)
	{
		if(strpos($exec_string, $allowed_command) == 0
		   && (strlen($exec_string) === ($allowed_command_pos = strlen($allowed_command)) || substr($exec_string, $allowed_command_pos, 1) === ' '))
		{
			$ok = true;
		}
	}

	if(!$ok)
	{
		die('SECURITY CHECK FAILED!' . "\n" . 'Your command "' . htmlspecialchars($exec_string) . '" is not allowed!' . "\n" . 'Please check your whole server for security problems by hand!' . "\n");
	}

	//
	// execute the command and return output
	//
	// --- martin @ 08.08.2005 -------------------------------------------------------
	// fixing usage of uninitialised variable

	$return = '';

	// -------------------------------------------------------------------------------

	if($return_value == false)
	{
		exec($exec_string, $return);
	}
	else
	{
		exec($exec_string, $return, $return_value);
	}

	return $return;
}

/**
 * Navigation generator
 *
 * @author Martin Burchert <eremit@adm1n.de>
 * @version 1.0
 * @param string s The session-id of the user
 * @param array userinfo the userinfo of the user
 * @return string the content of the navigation bar
 *
 * History:
 * 1.0 : Initial Version
 * 1.1 : Added new_window and required_resources (flo)
 */

function getNavigation($s, $userinfo)
{
	global $db, $lng, $settings;
	$return = '';

	//
	// query database
	//

	$query = 'SELECT * FROM `' . TABLE_PANEL_NAVIGATION . '` WHERE `area`=\'' . $db->escape(AREA) . '\' AND (`parent_url`=\'\' OR `parent_url`=\' \') ORDER BY `order`, `id` ASC';
	$result = $db->query($query);

	//
	// presort in multidimensional array
	//

	while($row = $db->fetch_array($result))
	{
		if($row['required_resources'] != ''
		   && strpos($row['required_resources'], '.') !== false) 
		{
			$_tmp = explode('.', $row['required_resources']);
			$_required_res = isset($settings[$_tmp[0]][$_tmp[1]]) ? (int)$settings[$_tmp[0]][$_tmp[1]] : 0;
		}

		if($_required_res == 1
		   || ($row['required_resources'] == ''
		       || (isset($userinfo[$row['required_resources']]) 
                           && ((int)$userinfo[$row['required_resources']] > 0 
				|| $userinfo[$row['required_resources']] == '-1')
			  )
                      )
		  )
		{	
			$row['parent_url'] = $row['url'];
			$row['isparent'] = 1;
			$nav[$row['parent_url']][] = _createNavigationEntry($s, $row);
			$subQuery = 'SELECT * FROM `' . TABLE_PANEL_NAVIGATION . '` WHERE `area`=\'' . $db->escape(AREA) . '\' AND `parent_url`=\'' . $db->escape($row['url']) . '\' ORDER BY `order`, `id` ASC';
			$subResult = $db->query($subQuery);

			while($subRow = $db->fetch_array($subResult))
			{
				if($subRow['required_resources'] != ''
				&& strpos($subRow['required_resources'], '.') !== false) 
				{
					$_tmp = explode('.', $subRow['required_resources']);
					$_required_res = isset($settings[$_tmp[0]][$_tmp[1]]) ? (int)$settings[$_tmp[0]][$_tmp[1]] : 0;
				}
		
				if($_required_res == 1
				   || ($subRow['required_resources'] == ''
					|| (isset($userinfo[$subRow['required_resources']]) 
					   && ((int)$userinfo[$subRow['required_resources']] > 0 
						|| $userinfo[$subRow['required_resources']] == '-1')
					   )
				      )
				  )
				{
					// respect three special cases: phpmyadmin_uri, webmail_uri and webftp_uri
					if($subRow['url'] != '')
					{
						$subRow['isparent'] = 0;
						$nav[$row['parent_url']][] = _createNavigationEntry($s, $subRow);
					}
				}
			}
		}
	}

	//
	// generate output
	//

	if((isset($nav))
	   && (sizeof($nav) > 0))
	{
		foreach($nav as $parent_url => $row)
		{
			$navigation_links = '';
			foreach($row as $id => $navElem)
			{
				if($navElem['isparent'] == 1)
				{
					$completeLink_ElementTitle = $navElem['completeLink'];
				}
				else
				{
					// assign url

					$completeLink = $navElem['completeLink'];

					// read template

					eval("\$navigation_links .= \"" . getTemplate("navigation_link", 1) . "\";");
				}
			}

			if($navigation_links != '')
			{
				eval("\$return .= \"" . getTemplate("navigation_element", 1) . "\";");
			}
		}
	}

	return $return;
}

/**
 * Processes a navigation entry in the database. It generates the correct
 * link and language.
 *
 * @param string The sessionid.
 * @param array The data recieved during the mysql query.
 * @return array The processed data.
 */

function _createNavigationEntry($s, $data)
{
	global $db, $lng;

	// get corect lang string

	$lngArr = explode(';', $data['lang']);
	$data['text'] = $lng;
	foreach($lngArr as $lKey => $lValue)
	{
		$data['text'] = $data['text'][$lValue];
	}

	if(str_replace(' ', '', $data['url']) != ''
	   && !stristr($data['url'], 'nourl'))
	{
		// append sid only to local

		if(!preg_match('/^https?\:\/\//', $data['url'])
		   && (isset($s) && $s != ''))
		{
			// generate sid with ? oder &

			if(strpos($data['url'], '?') !== false)
			{
				$data['url'].= '&s=' . $s;
			}
			else
			{
				$data['url'].= '?s=' . $s;
			}
		}

		$target = '';

		if($data['new_window'] == '1')
		{
			$target = ' target="_blank"';
		}

		$data['completeLink'] = '<a href="' . htmlspecialchars($data['url']) . '"' . $target . ' class="menu">' . $data['text'] . '</a>';
	}
	else
	{
		$data['completeLink'] = $data['text'];
	}

	return $data;
}

/**
 * Returns an integer of the given value which isn't negative.
 * Returns -1 if the given value was -1.
 *
 * @param any The value
 * @return int The positive value
 * @author Florian Lippert <flo@syscp.org>
 */

function intval_ressource($the_value)
{
	$the_value = intval($the_value);

	if($the_value < 0
	   && $the_value != '-1')
	{
		$the_value*= - 1;
	}

	return $the_value;
}

/**
 * Returns a double of the given value which isn't negative.
 * Returns -1 if the given value was -1.
 *
 * @param any The value
 * @return double The positive value
 * @author Florian Lippert <flo@syscp.org>
 */

function doubleval_ressource($the_value)
{
	$the_value = doubleval($the_value);

	if($the_value < 0
	   && $the_value != '-1')
	{
		$the_value*= - 1;
	}

	return $the_value;
}

/**
 * Replaces all occurences of variables defined in the second argument
 * in the first argument with their values.
 *
 * @param string The string that should be searched for variables
 * @param array The array containing the variables with their values
 * @return string The submitted string with the variables replaced.
 * @author Michael Duergner
 */

function replace_variables($text, $vars)
{
	$pattern = "/\{([a-zA-Z0-9\-_]+)\}/";

	// --- martin @ 08.08.2005 -------------------------------------------------------
	// fixing usage of uninitialised variable

	$matches = array();

	// -------------------------------------------------------------------------------

	if(count($vars) > 0
	   && preg_match_all($pattern, $text, $matches))
	{
		for ($i = 0;$i < count($matches[1]);$i++)
		{
			$current = $matches[1][$i];

			if(isset($vars[$current]))
			{
				$var = $vars[$current];
				$text = str_replace("{" . $current . "}", $var, $text);
			}
		}
	}

	$text = str_replace('\n', "\n", $text);
	return $text;
}

/**
 * Calls html_entity_decode in a loop until the result doesn't differ from original anymore
 *
 * @param string The string in which the html entities should be eliminated.
 * @return string The cleaned string
 * @author Florian Lippert <flo@syscp.org>
 */

function html_entity_decode_complete($string)
{
	while($string != html_entity_decode($string))
	{
		$string = html_entity_decode($string);
	}

	return $string;
}

/**
 * Calls stripslashes in a loop until the result doesn't differ from original anymore
 *
 * @param string The string in which the slashes should be eliminated.
 * @return string The cleaned string
 * @author Florian Lippert <flo@syscp.org>
 */

function stripslashes_complete($string)
{
	while($string != stripslashes($string))
	{
		$string = stripslashes($string);
	}

	return $string;
}

/**
 * Returns an array of found directories
 *
 * This function checks every found directory if they match either $uid or $gid, if they do
 * the found directory is valid. It uses recursive function calls to find subdirectories. Due
 * to the recursive behauviour this function may consume much memory.
 *
 * @param  string   path       The path to start searching in
 * @param  integer  uid        The uid which must match the found directories
 * @param  integer  gid        The gid which must match the found direcotries
 * @param  array    _fileList  recursive transport array !for internal use only!
 * @return array    Array of found valid pathes
 *
 * @author Martin Burchert  <martin.burchert@syscp.de>
 * @author Manuel Bernhardt <manuel.bernhardt@syscp.de>
 */

function findDirs($path, $uid, $gid)
{
	$list = array(
		$path
	);
	$_fileList = array();

	while(sizeof($list) > 0)
	{
		$path = array_pop($list);
		$path = makeCorrectDir($path);
		$dh = opendir($path);

		if($dh === false)
		{
			standard_error('cannotreaddir', $path);
			return null;
		}
		else
		{
			while(false !== ($file = @readdir($dh)))
			{
				if($file == '.'
				   && (fileowner($path . '/' . $file) == $uid || filegroup($path . '/' . $file) == $gid))
				{
					$_fileList[] = makeCorrectDir($path);
				}

				if(is_dir($path . '/' . $file)
				   && $file != '..'
				   && $file != '.')
				{
					array_push($list, $path . '/' . $file);
				}
			}

			@closedir($dh);
		}
	}

	return $_fileList;
}

/**
 * Returns a valid html tag for the choosen $fieldType for pathes
 *
 * @param  string   path       The path to start searching in
 * @param  integer  uid        The uid which must match the found directories
 * @param  integer  gid        The gid which must match the found direcotries
 * @param  string   fieldType  Either "Manual" or "Dropdown"
 * @return string   The html tag for the choosen $fieldType
 *
 * @author Martin Burchert  <martin.burchert@syscp.de>
 * @author Manuel Bernhardt <manuel.bernhardt@syscp.de>
 */

function makePathfield($path, $uid, $gid, $fieldType, $value = '')
{
	global $lng;
	$value = str_replace($path, '', $value);
	$field = '';

	if($fieldType == 'Manual')
	{
		$field = '<input type="text" name="path" value="' . htmlspecialchars($value) . '" size="30" />';
	}
	elseif($fieldType == 'Dropdown')
	{
		$dirList = findDirs($path, $uid, $gid);

		if(sizeof($dirList) > 0)
		{
			$field = '<select name="path">';
			foreach($dirList as $key => $dir)
			{
				if(strpos($dir, $path) === 0)
				{
					$dir = makeCorrectDir(substr($dir, strlen($path)));
				}

				$field.= makeoption($dir, $dir, $value);
			}

			$field.= '</select>';
		}
		else
		{
			$field = $lng['panel']['dirsmissing'];
			$field.= '<input type="hidden" name="path" value="/" />';
		}
	}

	return $field;
}

/**
 * Returns an array with all tables with keys which are in the currently selected database
 *
 * @param  db    A valid DB-object
 * @return array Array with tables and keys
 *
 * @author Florian Lippert <flo@syscp.org>
 */

function getTables(&$db)
{
	// This variable is our return-value

	$tables = array();

	// The fieldname in the associative array which we get by fetch_array()

	$tablefieldname = 'Tables_in_' . $db->database;

	// Query for a list of tables in the currently selected database

	$tables_result = $db->query('SHOW TABLES');

	while($tables_row = $db->fetch_array($tables_result))
	{
		// Extract tablename

		$tablename = $tables_row[$tablefieldname];

		// Create sub-array with key tablename

		$tables[$tablename] = array();

		// Query for a list of indexes of the currently selected table

		$keys_result = $db->query('SHOW INDEX FROM ' . $tablename);

		while($keys_row = $db->fetch_array($keys_result))
		{
			// Extract keyname

			$keyname = $keys_row['Key_name'];

			// If there is aleady a key in our tablename-sub-array with has the same name as our key
			// OR if the sequence is not one
			// then we have more then index-columns for our keyname

			if((isset($tables[$tablename][$keyname]) && $tables[$tablename][$keyname] != '')
			   || $keys_row['Seq_in_index'] != '1')
			{
				// If there is no keyname in the tablename-sub-array set ...

				if(!isset($tables[$tablename][$keyname]))
				{
					// ... then create one

					$tables[$tablename][$keyname] = array();
				}

				// If the keyname-sub-array isn't an array ...

				elseif (!is_array($tables[$tablename][$keyname]))
				{
					// temporary move columname

					$tmpkeyvalue = $tables[$tablename][$keyname];

					// unset keyname-key

					unset($tables[$tablename][$keyname]);

					// create new array for keyname-key

					$tables[$tablename][$keyname] = array();

					// keyindex will be 1 by default, if seq is also 1 we'd better use 0 (this case shouldn't ever occur)

					$keyindex = ($keys_row['Seq_in_index'] == '1') ? '0' : '1';

					// then move back our tmp columname from above

					$tables[$tablename][$keyname][$keyindex] = $tmpkeyvalue;

					// end unset the variable afterwards

					unset($tmpkeyvalue);
				}

				// set columname

				$tables[$tablename][$keyname][$keys_row['Seq_in_index']] = $keys_row['Column_name'];
			}
			else
			{
				// set columname

				$tables[$tablename][$keyname] = $keys_row['Column_name'];
			}
		}
	}

	return $tables;
}

/**
 * Creates a directory below a users homedir and sets all directories,
 * which had to be created below with correct Owner/Group
 * (Copied from cron_tasks.php:rev1189 as we'll need this more often in future)
 *
 * @param  string The homedir of the user
 * @param  string The dir which should be created
 * @param  int    The uid of the user
 * @param  int    The gid of the user
 * @return bool   true if everything went okay, false if something went wrong
 *
 * @author Florian Lippert <flo@syscp.org>
 * @author Martin Burchert <martin.burchert@syscp.org>
 */

function mkDirWithCorrectOwnership($homeDir, $dirToCreate, $uid, $gid)
{
	$returncode = true;

	if($homeDir != ''
	   && $dirToCreate != '')
	{
		$homeDir = makeCorrectDir($homeDir);
		$dirToCreate = makeCorrectDir($dirToCreate);

		if(substr($dirToCreate, 0, strlen($homeDir)) == $homeDir)
		{
			$subdir = substr($dirToCreate, strlen($homeDir));
		}
		else
		{
			$subdir = $dirToCreate;
		}

		$subdir = makeCorrectDir($subdir);
		$subdirlen = strlen($subdir);
		$subdirs = array();
		array_push($subdirs, $dirToCreate);
		$offset = 0;

		while($offset < $subdirlen)
		{
			$offset = strpos($subdir, '/', $offset);
			$subdirelem = substr($subdir, 0, $offset);
			$offset++;
			array_push($subdirs, makeCorrectDir($homeDir . $subdirelem));
		}

		$subdirs = array_unique($subdirs);
		sort($subdirs);
		foreach($subdirs as $sdir)
		{
			if(!is_dir($sdir))
			{
				$sdir = makeCorrectDir($sdir);
				safe_exec('mkdir -p ' . escapeshellarg($sdir));
				safe_exec('chown -R ' . (int)$uid . ':' . (int)$gid . ' ' . escapeshellarg($sdir));
			}
		}
	}
	else
	{
		$returncode = false;
	}

	return $returncode;
}

/**
 * Create a valid from/to - mailheader (remove carriage-returns)
 *
 * @param string The name of the recipient
 * @param string The mailaddress
 * @return string A valid header-entry
 * @author Florian Aders <eleras@syscp.org>
 */

function buildValidMailFrom($name, $mailaddress)
{
	$mailfrom = str_replace(array(
		"\r",
		"\n"
	), '', $name) . ' <' . str_replace(array(
		"\r",
		"\n"
	), '', $mailaddress) . '>';
	return $mailfrom;
}

/**
 * Checks if a given directory is valid for multiple configurations
 * or should rather be used as a single file
 *
 * @param  string The dir
 * @return bool   true if usable as dir, false otherwise
 *
 * @author Florian Lippert <flo@syscp.org>
 */

function isConfigDir($dir)
{
	if(file_exists($dir))
	{
		if(is_dir($dir))
		{
			$returnval = true;
		}
		else
		{
			$returnval = false;
		}
	}
	else
	{
		if(substr($dir, -1) == '/')
		{
			$returnval = true;
		}
		else
		{
			$returnval = false;
		}
	}

	return $returnval;
}

function correctMysqlUsers(&$db, &$db_root, $mysql_access_host_array)
{
	global $settings, $sql;
	$users = array();
	$users_result = $db_root->query('SELECT * FROM `mysql`.`user`');

	while($users_row = $db_root->fetch_array($users_result))
	{
		if(!isset($users[$users_row['User']])
		   || !is_array($users[$users_row['User']]))
		{
			$users[$users_row['User']] = array(
				'password' => $users_row['Password'],
				'hosts' => array()
			);
		}

		$users[$users_row['User']]['hosts'][] = $users_row['Host'];
	}

	$databases = array(
		$sql['db']
	);
	$databases_result = $db->query('SELECT * FROM `' . TABLE_PANEL_DATABASES . '`');

	while($databases_row = $db->fetch_array($databases_result))
	{
		$databases[] = $databases_row['databasename'];
	}

	foreach($databases as $username)
	{
		if(isset($users[$username])
		   && is_array($users[$username])
		   && isset($users[$username]['hosts'])
		   && is_array($users[$username]['hosts']))
		{
			$password = $users[$username]['password'];
			foreach($mysql_access_host_array as $mysql_access_host)
			{
				$mysql_access_host = trim($mysql_access_host);

				if(!in_array($mysql_access_host, $users[$username]['hosts']))
				{
					$db_root->query('GRANT ALL PRIVILEGES ON `' . str_replace('_', '\_', $db_root->escape($username)) . '`.* TO `' . $db_root->escape($username) . '`@`' . $db_root->escape($mysql_access_host) . '` IDENTIFIED BY \'password\'');
					$db_root->query('SET PASSWORD FOR `' . $db_root->escape($username) . '`@`' . $db_root->escape($mysql_access_host) . '` = \'' . $db_root->escape($password) . '\'');
				}
			}

			foreach($users[$username]['hosts'] as $mysql_access_host)
			{
				if(!in_array($mysql_access_host, $mysql_access_host_array))
				{
					$db_root->query('REVOKE ALL PRIVILEGES ON * . * FROM `' . $db_root->escape($username) . '`@`' . $db_root->escape($mysql_access_host) . '`');
					$db_root->query('REVOKE ALL PRIVILEGES ON `' . str_replace('_', '\_', $db_root->escape($username)) . '` . * FROM `' . $db_root->escape($username) . '`@`' . $db_root->escape($mysql_access_host) . '`');
					$db_root->query('DELETE FROM `mysql`.`user` WHERE `User` = "' . $db_root->escape($username) . '" AND `Host` = "' . $db_root->escape($mysql_access_host) . '"');
				}
			}
		}
	}

	$db_root->query('FLUSH PRIVILEGES');
}

/**
 * Checks whether it is a valid ip
 *
 * @return mixed 	ip address on success, standard_error on failure
 */

function validate_ip($ip, $return_bool = false, $lng = 'invalidip')
{
	if(filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) === FALSE
	   && filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) === FALSE
	   && filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_RES_RANGE) === FALSE)
	{
		if($return_bool)
		{
			return false;
		}
		else
		{
			standard_error($lng, $ip);
			exit;
		}
	}
	else
	{
		return $ip;
	}
}

/**
 * Create or modify the AWStats configuration file for the given domain.
 * Modified by Berend Dekens to allow custom configurations.
 *
 * @param logFile
 * @param siteDomain
 * @param hostAliases
 * @return
 * @author Michael Duergner
 * @author Berend Dekens
 */

function createAWStatsConf($logFile, $siteDomain, $hostAliases)
{
	global $pathtophpfiles;

	// Generation header

	$header = "## GENERATED BY SYSCP\n";
	$header2 = "## Do not remove the line above! This tells SysCP to update this configuration\n## If you wish to manually change this configuration file, remove the first line to make sure SysCP won't rebuild this file\n## Generated for domain {SITE_DOMAIN} on " . date('l dS \of F Y h:i:s A') . "\n";

	// These are the variables we will replace

	$regex = array(
		'/\{LOG_FILE\}/',
		'/\{SITE_DOMAIN\}/',
		'/\{HOST_ALIASES\}/'
	);
	$replace = array(
		$logFile,
		$siteDomain,
		$hostAliases
	);

	// File names

	$domain_file = '/etc/awstats/awstats.' . $siteDomain . '.conf';
	$model_file = '/etc/awstats/awstats.model.conf.syscp';

	// Test if the file exists

	if(file_exists($domain_file))
	{
		// Check for the generated header - if this is a manual modification we won't update

		$awstats_domain_conf = fopen($domain_file, 'r');

		if(fgets($awstats_domain_conf, strlen($header)) != $header)
		{
			fclose($awstats_domain_conf);
			return;
		}

		// Close the file

		fclose($awstats_domain_conf);
	}

	$awstats_domain_conf = fopen($domain_file, 'w');
	$awstats_model_conf = fopen($model_file, 'r');

	// Write the header

	fwrite($awstats_domain_conf, $header);
	fwrite($awstats_domain_conf, preg_replace($regex, $replace, $header2));

	// Write the configuration file

	while(($line = fgets($awstats_model_conf, 4096)) !== false)
	{
		if(!preg_match('/^#/', $line)
		   && trim($line) != '')
		{
			fwrite($awstats_domain_conf, preg_replace($regex, $replace, $line));
		}
	}

	fclose($awstats_domain_conf);
	fclose($awstats_model_conf);
}

/**
 * This function generates the VHost configuration for AWStats
 * This will enable the /stats or /awstats url and enable security on these folders
 * @param siteDomain Name of the domain we want stats for
 * @return String with configuration for use in vhost file
 * @author Berend Dekens
 */

function createAWStatsVhost($siteDomain, $settings = null)
{
	if($settings['system']['mod_fcgid'] != '1')
	{
		$vhosts_file = '  # AWStats statistics' . "\n";
		$vhosts_file.= '  RewriteEngine On' . "\n";
		$vhosts_file.= '  RewriteRule /stats(/.*)? /awstats/awstats.pl?config=' . $siteDomain . ' [L,PT]' . "\n";
		$vhosts_file.= '  RewriteRule /awstats.pl(.*)* /awstats/awstats.pl$1 [QSA,L,PT]' . "\n";
	}
	else
	{
		$vhosts_file = '  <IfModule mod_proxy.c>' . "\n";
		$vhosts_file.= '    RewriteEngine On' . "\n";
		$vhosts_file.= '    RewriteRule awstats.pl(.*)$	http://' . $settings['system']['hostname'] . '/cgi-bin/awstats.pl$1 [R,P]' . "\n";
		$vhosts_file.= '    RewriteRule awstats$	http://' . $settings['system']['hostname'] . '/cgi-bin/awstats.pl?config=' . $siteDomain . ' [R,P]' . "\n";
		$vhosts_file.= '  </IfModule>' . "\n";
	}
	return $vhosts_file;
}

/**
 * Get all date interval types as an array or option code
 *
 * @param  string Either array or option, affects the value returned by function
 * @param  string Only relevant when first argument is option, this one will be the selected one
 * @return mixed  Depends on first option, array of intervaltypes or optioncode of intervaltypes ready to be inserted in a select statement
 *
 * @author Former03 GmbH :: Florian Lippert <flo@syscp.org>
 */

function getIntervalTypes($what = 'array', $selected = '')
{
	global $lng;
	$intervalTypes = array(
		'y',
		'm',
		'd'
	);

	if(!in_array($selected, $intervalTypes))
	{
		$selected = '';
	}

	switch($what)
	{
	case 'option':
		$returnval = '';
		foreach($intervalTypes as $intervalFeeType)
		{
			$returnval.= makeoption($lng['panel']['intervalfee_type'][$intervalFeeType], $intervalFeeType, $selected);
		}

		break;
	case 'array':
	default:
		$returnval = $intervalTypes;
		break;
	}

	return $returnval;
}

/**
 * Get full month name for interval short
 *
 * @param  string one digit short of month (y,m,d,h,i,s)
 * @param  bool   should we add a plural s?
 * @return mixed  the full month name
 *
 * @author Former03 GmbH :: Florian Lippert <flo@syscp.org>
 */

function getFullIntervalName($intervalType, $pluralS = false)
{
	switch(strtolower($intervalType))
	{
	case 'y':
		$payment_every_type_fullname = 'year';
		break;
	case 'm':
		$payment_every_type_fullname = 'month';
		break;
	case 'd':
		$payment_every_type_fullname = 'day';
		break;
	default:
		$payment_every_type_fullname = false;
	}

	if($pluralS === true
	   && $payment_every_type_fullname !== false)
	{
		$payment_every_type_fullname.= 's';
	}

	return $payment_every_type_fullname;
}

/**
 * Determines the number of days at a specified month/year.
 *
 * @param  int The month
 * @param  int The year
 * @return int Number of days
 *
 * @author Former03 GmbH :: Florian Lippert <flo@syscp.org>
 */

function getDaysForMonth($month, $year)
{
	if((int)$month > 12)
	{
		$year+= (int)($month / 12);
		$month = $month % 12;
	}

	if((int)($month) == 0)
	{
		$month = 12;
	}

	$months = array(
		1 => 31,
		2 => 28,
		3 => 31,
		4 => 30,
		5 => 31,
		6 => 30,
		7 => 31,
		8 => 31,
		9 => 30,
		10 => 31,
		11 => 30,
		12 => 31
	);

	if(getDaysForYear($month, $year) == 366)
	{
		$months[2] = '29';
	}

	return $months[intval($month)];
}

/**
 * Determines the number of days at a specified year.
 *
 * @param  int The year
 * @return int Number of days
 *
 * @author Former03 GmbH :: Florian Lippert <flo@syscp.org>
 */

function getDaysForYear($month, $year)
{
	if($month >= 3)$year++;
	return ((($year % 4) == 0 && ($year % 100) != 0) ? 366 : 365);
}

/**
 * Calculates the number of days between first and second parameter
 *
 * @param  int Date 1
 * @param  int Date 2
 * @return int Number of days
 *
 * @author Former03 GmbH :: Florian Lippert <flo@syscp.org>
 */

function calculateDayDifference($begin, $end)
{
	$daycount = 0;
	$begin = transferDateToArray($begin);
	$end = transferDateToArray($end);
	$direction = 1;

	if(strtotime($end['y'] . '-' . $end['m'] . '-' . $end['d']) < strtotime($begin['y'] . '-' . $begin['m'] . '-' . $begin['d']))
	{
		$tmp = $end;
		$end = $begin;
		$begin = $tmp;
		unset($tmp);
		$direction = (-1);
	}

	// Sanity check, if our given array is in the right format

	if(checkDateArray($begin) === true
	   && checkDateArray($end) === true)
	{
		$yeardiff = (int)$end['y'] - (int)$begin['y'];
		$monthdiff = ((int)$end['m'] + 12 * $yeardiff) - (int)$begin['m'];
		for ($i = 0;$i < abs($monthdiff);$i++)
		{
			$daycount+= getDaysForMonth($begin['m'] + $i, $begin['y']);
		}

		$daycount+= $end['d'] - $begin['d'];
		$daycount*= $direction;
	}

	return $daycount;
}

/**
 * Makes nice array out of a date.
 *
 * @param  mixed The date: either string (2008-02-14), unix timestamp, or array.
 * @return array The array( 'y' => 2008, 'm' => 2, 'd' => 14 );
 *
 * @author Former03 GmbH :: Florian Lippert <flo@syscp.org>
 */

function transferDateToArray($date)
{
	if(!is_array($date))
	{
		if(is_numeric($date))
		{
			$date = array(
				'y' => (int)date('Y', $date),
				'm' => (int)date('m', $date),
				'd' => (int)date('d', $date)
			);
		}
		else
		{
			$date = explode('-', $date);
			$date = array(
				'y' => (int)$date[0],
				'm' => (int)$date[1],
				'd' => (int)$date[2]
			);
		}
	}
	else
	{
		$date['y'] = (int)$date['y'];
		$date['m'] = (int)$date['m'];
		$date['d'] = (int)$date['d'];
	}

	return $date;
}

/**
 * Checks if a date array is valid.
 *
 * @param  array The date array
 * @return bool  True if valid, false otherwise.
 *
 * @author Former03 GmbH :: Florian Lippert <flo@syscp.org>
 */

function checkDateArray($date)
{
	return (is_array($date) && isset($date['y']) && isset($date['m']) && isset($date['d']) && (int)$date['m'] >= 1 && (int)$date['m'] <= 12 && (int)$date['d'] >= 1 && (int)$date['d'] <= getDaysForMonth($date['m'], $date['y']));
}

/**
 * Manipulates a date, like adding a month or so and correcting it afterwards
 * (2008-01-33 -> 2008-02-02)
 *
 * @param  array  The date array
 * @param  string The operation, may be '+', 'add', 'sum' or '-', 'subtract', 'subduct'
 * @param  int    Number if days/month/years
 * @param  string Either 'y', 'm', 'd', depending on what part to change.
 * @param  array  A valid date array with original date, mandatory for more than one manipulation on same date.
 * @return date   The manipulated date array
 *
 * @author Former03 GmbH :: Florian Lippert <flo@syscp.org>
 */

function manipulateDate($date, $operation, $count, $type, $original_date = null)
{
	$newdate = $date;
	$date = transferDateToArray($date);

	if(checkDateArray($date) === true
	   && isset($date[$type]))
	{
		switch($operation)
		{
		case '+':
		case 'add':
		case 'sum':
			$date[$type]+= (int)$count;
			break;
		case '-':
		case 'subtract':
		case 'subduct':
			$date[$type]-= (int)$count;
			break;
		}

		if($original_date !== null
		   && ($original_date = transferDateToArray($original_date)) !== false
		   && $type == 'm')
		{
			if($original_date['d'] > getDaysForMonth($date['m'], $date['y']))
			{
				$date['d'] = getDaysForMonth($date['m'], $date['y']) - (getDaysForMonth($original_date['m'], $original_date['y']) - $original_date['d']);
			}
			else
			{
				$date['d'] = $original_date['d'];
			}
		}

		while(checkDateArray($date) === false)
		{
			if($date['d'] > getDaysForMonth($date['m'], $date['y']))
			{
				$date['d']-= getDaysForMonth($date['m'], $date['y']);
				$date['m']++;
			}

			if($date['d'] < 1)
			{
				$date['m']--;
				$date['d']+= getDaysForMonth($date['m'], $date['y']);

				// Adding here, because date[d] is negative
			}

			if($date['m'] > 12)
			{
				$date['m']-= 12;
				$date['y']++;
			}

			if($date['m'] < 1)
			{
				$date['y']--;
				$date['m']+= 12;
			}
		}

		$newdate = $date['y'] . '-' . $date['m'] . '-' . $date['d'];
	}

	return $newdate;
}

/**
 * Simple reformater for a date strtotime understands
 *
 * @param  string A date strtotime understands
 * @param  string Time format, may contain anything date() can handle.
 * @return string New nicely formatted date
 *
 * @author Former03 GmbH :: Florian Lippert <flo@syscp.org>
 */

function makeNicePresentableDate($date, $format = 'Y-m-d')
{
	return date($format, strtotime($date));
}

/**
 * Wrapper for in_array, which can also handle an array as needle.
 *
 * @param  mixed Either array or string, if string it behaves like in_array.
 * @param  array A haystack to search in.
 * @param  bool  See in_array documentation for details, will passed directly.
 * @return bool  True if one needle is in the haystack.
 *
 * @author Former03 GmbH :: Florian Lippert <flo@syscp.org>
 */

function one_in_array($needles, $haystack, $strict = false)
{
	$returnval = false;

	if(!is_array($needles))
	{
		$needle = $needles;
		unset($needles);
		$needles = array(
			$needle
		);
		unset($needle);
	}

	foreach($needles as $needle)
	{
		if(in_array($needle, $haystack, $strict))
		{
			$returnval = true;
		}
	}

	return $returnval;
}

/**
 * Returns appropriate table names and -keys depending on mode (admin or customer).
 *
 * @param  int   The mode
 * @param  string Subject, eg tablename.
 * @param  string Key, eg 'table' or 'key'
 * @return string Table or Key
 *
 * @author Former03 GmbH :: Florian Lippert <flo@syscp.org>
 */

function getModeDetails($mode, $subject, $key)
{
	$modes = array(
		0 => array(
			'TABLE_PANEL_USERS' => array(
				'table' => TABLE_PANEL_CUSTOMERS,
				'key' => 'customerid'
			),
			'TABLE_PANEL_TRAFFIC' => array(
				'table' => TABLE_PANEL_TRAFFIC,
				'key' => 'customerid'
			),
			'TABLE_PANEL_DISKSPACE' => array(
				'table' => TABLE_PANEL_DISKSPACE,
				'key' => 'customerid'
			),
			'TABLE_BILLING_INVOICES' => array(
				'table' => TABLE_BILLING_INVOICES,
				'key' => 'customerid'
			),
			'TABLE_BILLING_INVOICE_CHANGES' => array(
				'table' => TABLE_BILLING_INVOICE_CHANGES,
				'key' => 'customerid'
			),
			'TABLE_BILLING_SERVICE_CATEGORIES' => array(
				'table' => TABLE_BILLING_SERVICE_CATEGORIES,
				'key' => 'customerid'
			),
		),
		1 => array(
			'TABLE_PANEL_USERS' => array(
				'table' => TABLE_PANEL_ADMINS,
				'key' => 'adminid'
			),
			'TABLE_PANEL_TRAFFIC' => array(
				'table' => TABLE_PANEL_TRAFFIC_ADMINS,
				'key' => 'adminid'
			),
			'TABLE_PANEL_DISKSPACE' => array(
				'table' => TABLE_PANEL_DISKSPACE_ADMINS,
				'key' => 'adminid'
			),
			'TABLE_BILLING_INVOICES' => array(
				'table' => TABLE_BILLING_INVOICES_ADMINS,
				'key' => 'adminid'
			),
			'TABLE_BILLING_INVOICE_CHANGES' => array(
				'table' => TABLE_BILLING_INVOICE_CHANGES_ADMINS,
				'key' => 'adminid'
			),
			'TABLE_BILLING_SERVICE_CATEGORIES' => array(
				'table' => TABLE_BILLING_SERVICE_CATEGORIES_ADMINS,
				'key' => 'adminid'
			),
		)
	);

	if(isset($modes[$mode])
	   && isset($modes[$mode][$subject])
	   && isset($modes[$mode][$subject][$key]))
	{
		return $modes[$mode][$subject][$key];
	}
	else
	{
		return false;
	}
}

/**
 * Calculates invoice fees and stores it in panel_users,
 * according to details in billing_service_categories.
 *
 * @param  int   The mode
 * @param  int   Userid to begin with Subject, eg tablename.
 * @param  int   Number of Users we should handle in this run
 * @param  int   Single userid we should focus on.
 * @return array Results like current invoice fees etc.
 *
 * @author Former03 GmbH :: Florian Lippert <flo@syscp.org>
 */

function cacheInvoiceFees($mode = 0, $begin = null, $count = null, $userid = null)
{
	global $db;
	$returnval = array();
	$service_categories_result = $db->query('SELECT * FROM `' . getModeDetails($mode, 'TABLE_BILLING_SERVICE_CATEGORIES', 'table') . '` ORDER BY `id` ASC');

	while($service_categories_row = $db->fetch_array($service_categories_result))
	{
		$service_categories[$service_categories_row['category_name']] = $service_categories_row;

		if($service_categories_row['category_cachefield'] != '')
		{
			$zeroUpdates[$service_categories_row['category_cachefield']] = 0;
		}
	}

	if($userid !== null
	   && intval($userid) !== 0)
	{
		$userSelection = " WHERE `" . getModeDetails($mode, 'TABLE_PANEL_USERS', 'key') . "` = '" . $userid . "' ";
	}
	else
	{
		$userSelection = '';
	}

	if($begin !== null
	   && intval($count) !== 0)
	{
		$limit = ' LIMIT ' . intval($begin) . ', ' . intval($count);
	}
	else
	{
		$limit = '';
	}

	$users = $db->query("SELECT * FROM `" . getModeDetails($mode, 'TABLE_PANEL_USERS', 'table') . "` " . $userSelection . ' ' . $limit);

	while($user = $db->fetch_array($users))
	{
		if(!isset($user['customer_categories_once']))
		{
			$user['customer_categories_once'] = '';
		}

		if(!isset($user['customer_categories_period']))
		{
			$user['customer_categories_period'] = '';
		}

		$myInvoice = new invoice($db, $mode, explode('-', $user['customer_categories_once']), explode('-', $user['customer_categories_period']));

		if($myInvoice->collect($user[getModeDetails($mode, 'TABLE_PANEL_USERS', 'key')]) === true)
		{
			$total_fee_taxed = 0;
			$myUpdates = $zeroUpdates;
			$total_fees_array = $myInvoice->getTotalFee($lng);
			foreach($total_fees_array as $service_type => $total_fee_array)
			{
				if(isset($service_categories[$service_type])
				   && isset($service_categories[$service_type]['category_cachefield'])
				   && $service_categories[$service_type]['category_cachefield'] != '')
				{
					$myUpdates[$service_categories[$service_type]['category_cachefield']] = $total_fee_array['total_fee_taxed'];
					$total_fee_taxed+= $total_fee_array['total_fee_taxed'];
				}
			}

			$updates = '';
			foreach($myUpdates as $myField => $myValue)
			{
				$updates.= ', `' . $myField . '` = \'' . $myValue . '\' ';
			}

			$db->query('UPDATE `' . getModeDetails($mode, 'TABLE_PANEL_USERS', 'table') . '` SET `invoice_fee` = \'' . $total_fee_taxed . '\' ' . $updates . ' WHERE `' . getModeDetails($mode, 'TABLE_PANEL_USERS', 'key') . '` = \'' . $user[getModeDetails($mode, 'TABLE_PANEL_USERS', 'key')] . '\' ');
			$returnval[$user[getModeDetails($mode, 'TABLE_PANEL_USERS', 'key')]] = $myUpdates;
			$returnval[$user[getModeDetails($mode, 'TABLE_PANEL_USERS', 'key')]]['total'] = $total_fee_taxed;
			$returnval[$user[getModeDetails($mode, 'TABLE_PANEL_USERS', 'key')]]['loginname'] = $user['loginname'];
		}
	}

	return $returnval;
}

/**
 * function will return the temporary directory where we can write data
 * function exists as a fallback for php versions lower than 5.2.1
 * source copied from php.net
 *
 * @author	Sven Skrabal <info@nexpa.de>
 */

if(!function_exists('sys_get_temp_dir') )
{
	function sys_get_temp_dir()
	{
		// Try to get from environment variable
		if ( !empty($_ENV['TMP']) )
		{
			return realpath( $_ENV['TMP'] );
		}
		elseif ( !empty($_ENV['TMPDIR']) )
		{
			return realpath( $_ENV['TMPDIR'] );
		}
		elseif ( !empty($_ENV['TEMP']) )
		{
			return realpath( $_ENV['TEMP'] );
		}
		else
		{
			// Detect by creating a temporary file
			// Try to use system's temporary directory
			// as random name shouldn't exist
			$temp_file = tempnam( md5(uniqid(rand(), true)), '' );
			if ( $temp_file )
			{
				$temp_dir = realpath( dirname($temp_file) );
				unlink( $temp_file );
				return $temp_dir;
			}
			else
			{
				return false;
			}
		}
	}
}

?>
